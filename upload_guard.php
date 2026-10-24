<?php
/**
 * Strict server-side validation for every file upload in the application.
 *
 * The finding: three handlers (eSignature, discharge attachment, billing
 * discharge attachment) moved the uploaded file into the web-accessible
 * uploads/ directory keeping the USER-SUPPLIED filename and extension:
 *
 *     $file = rand(1000,100000)."-".$_FILES["signature"]["name"];
 *     move_uploaded_file($_FILES['signature']['tmp_name'], "../uploads/".$file);
 *
 * so shell.php arrived as uploads/12345-shell.php and executed. The accept=
 * attribute on the form is a client-side hint and stops nothing.
 *
 * What this enforces, in order:
 *   1. The PHP upload succeeded and the file really came from an upload.
 *   2. Size is within the per-kind limit.
 *   3. The extension is on the whitelist for that kind.
 *   4. The MIME type reported by finfo (content, not the browser) agrees.
 *   5. The magic bytes agree.
 *   6. Images must actually parse as images (getimagesize).
 *   7. The bytes contain no script signature — blocks polyglots such as a GIF
 *      with PHP appended.
 *   8. The stored name is random and the extension is derived from the DETECTED
 *      type, never from the user's filename. The original name is discarded.
 *
 * Files are written 0644 (no execute) and each upload directory carries an
 * .htaccess that disables handlers — see uploadGuardProtectDir().
 */

if (!function_exists('uploadGuardStore')) {

/** kind => [extensions, mimes, max bytes] */
function uploadGuardKinds()
{
    return [
        // Signature images shown with <img>.
        'image' => [
            'ext'  => ['jpg', 'jpeg', 'png'],
            'mime' => ['image/jpeg', 'image/png'],
            'max'  => 4 * 1024 * 1024,
        ],
        // Patient documents / discharge attachments: PDF or an image.
        'document' => [
            'ext'  => ['pdf', 'jpg', 'jpeg', 'png'],
            'mime' => ['application/pdf', 'image/jpeg', 'image/png'],
            'max'  => 10 * 1024 * 1024,
        ],
    ];
}

/** Detected MIME => the extension we will store it under. */
function uploadGuardCanonicalExt($mime)
{
    $map = [
        'image/jpeg'      => 'jpg',
        'image/png'       => 'png',
        'application/pdf' => 'pdf',
    ];
    return isset($map[$mime]) ? $map[$mime] : '';
}

/** Content sniffing that does not trust the browser's Content-Type. */
function uploadGuardDetectMime($path)
{
    if (function_exists('finfo_open')) {
        $fi = finfo_open(FILEINFO_MIME_TYPE);
        if ($fi) {
            $m = finfo_file($fi, $path);
            finfo_close($fi);
            if ($m) {
                return strtolower(trim(explode(';', $m)[0]));
            }
        }
    }
    // finfo is compiled in by default; if it is not, fall back to the image
    // parser rather than trusting anything the client said.
    $info = @getimagesize($path);
    if ($info && !empty($info['mime'])) {
        return strtolower($info['mime']);
    }
    return '';
}

/** Does the file start with the signature its MIME claims? */
function uploadGuardMagicOk($path, $mime)
{
    $fh = @fopen($path, 'rb');
    if (!$fh) {
        return false;
    }
    $head = fread($fh, 8);
    fclose($fh);
    if ($head === false || $head === '') {
        return false;
    }
    switch ($mime) {
        case 'image/png':
            return strncmp($head, "\x89PNG\r\n\x1a\n", 8) === 0;
        case 'image/jpeg':
            return strncmp($head, "\xFF\xD8\xFF", 3) === 0;
        case 'application/pdf':
            return strncmp($head, '%PDF-', 5) === 0;
    }
    return false;
}

/**
 * Look for embedded scripts. A GIF/JPEG with "<?php" appended is still a valid
 * image to a parser but a web shell to an interpreter, so the bytes are checked
 * regardless of how well-formed the file is.
 */
function uploadGuardHasScriptSignature($path)
{
    $fh = @fopen($path, 'rb');
    if (!$fh) {
        return true;   // unreadable — refuse rather than guess
    }
    $needles = ['<?php', '<?=', '<script', '<%', '#!/'];
    $carry   = '';
    while (!feof($fh)) {
        $chunk = fread($fh, 65536);
        if ($chunk === false) {
            break;
        }
        $hay = strtolower($carry . $chunk);
        foreach ($needles as $n) {
            if (strpos($hay, $n) !== false) {
                fclose($fh);
                return true;
            }
        }
        // Keep a short tail so a needle split across chunks is still found.
        $carry = substr($chunk, -8);
    }
    fclose($fh);
    return false;
}

/**
 * Reject a PDF that carries ACTIVE CONTENT.
 *
 * A PDF is not just paper — it can auto-run JavaScript on open, launch an
 * external program, or carry an embedded payload file. None of that shows up as
 * "<?php" or "<script", so uploadGuardHasScriptSignature() misses it entirely:
 * a PDF whose catalog holds /OpenAction<</S/JavaScript/JS(...)>> passed every
 * earlier check. A scan or a printed report never needs any of these.
 *
 * Markers are matched with a trailing PDF delimiter so /JS does not fire on a
 * name like /JSName. /URI is deliberately NOT flagged — plain hyperlinks are
 * everywhere and are inert.
 *
 * Object streams are Flate-compressed, so the deflated blocks are inflated and
 * scanned too; otherwise the markers could simply be hidden inside one.
 */
function uploadGuardPdfHasActiveContent($path)
{
    $data = @file_get_contents($path);
    if ($data === false) {
        return true;   // unreadable — refuse rather than guess
    }

    $markers = [
        '#/JavaScript[\s/<\[(]#i',
        '#/JS[\s/<\[(]#i',
        '#/Launch[\s/<\[(]#i',
        '#/EmbeddedFile[s]?[\s/<\[(]#i',
        '#/RichMedia[\s/<\[(]#i',
        '#/XFA[\s/<\[(]#i',
        '#/AA[\s/<\[(]#i',          // additional-actions dictionary
        '#/SubmitForm[\s/<\[(]#i',
        '#/ImportData[\s/<\[(]#i',
    ];

    $scan = function ($buf) use ($markers) {
        foreach ($markers as $re) {
            if (preg_match($re, $buf)) {
                return true;
            }
        }
        return false;
    };

    if ($scan($data)) {
        return true;
    }

    // Inflate compressed streams and scan those as well. Bounded so a
    // compression bomb cannot exhaust memory.
    $budget = 48 * 1024 * 1024;
    $offset = 0;
    while ($budget > 0 && ($s = strpos($data, 'stream', $offset)) !== false) {
        $start = $s + 6;
        if (isset($data[$start]) && $data[$start] === "\r") { $start++; }
        if (isset($data[$start]) && $data[$start] === "\n") { $start++; }
        $end = strpos($data, 'endstream', $start);
        if ($end === false) {
            break;
        }
        $offset = $end + 9;
        $len = $end - $start;
        if ($len <= 0 || $len > $budget) {
            continue;
        }
        $blob = substr($data, $start, $len);
        $inflated = @gzuncompress($blob);
        if ($inflated === false) {
            $inflated = @gzinflate($blob);
        }
        if ($inflated === false || $inflated === '') {
            continue;
        }
        $budget -= strlen($inflated);
        if ($scan($inflated)) {
            return true;
        }
    }

    return false;
}

/**
 * Re-encode an image from its decoded pixels, discarding everything else.
 *
 * The byte-level checks above catch known signatures, but the only way to be
 * certain an image carries no payload is to throw the original container away:
 * decode to a pixel buffer and write a brand-new file. EXIF/XMP comments, data
 * appended after the image trailer, and anything hidden in an ancillary chunk
 * all disappear, because none of it survives the round trip.
 *
 * Dimensions are capped first — decoding allocates roughly w*h*4 bytes, so an
 * otherwise-valid "decompression bomb" would exhaust memory before any of this
 * ran.
 *
 * @return bool false if the image could not be safely rebuilt (caller rejects).
 */
function uploadGuardRebuildImage($path, $mime)
{
    if (!extension_loaded('gd')) {
        // No GD: the signature/magic/decode checks still apply, so fall through
        // rather than refusing every upload on a server without the extension.
        return true;
    }

    $info = @getimagesize($path);
    if (!$info || empty($info[0]) || empty($info[1])) {
        return false;
    }
    $w = (int) $info[0];
    $h = (int) $info[1];
    if ($w > 12000 || $h > 12000 || ($w * $h) > 50000000) {
        return false;
    }

    $img = false;
    if ($mime === 'image/jpeg' && function_exists('imagecreatefromjpeg')) {
        $img = @imagecreatefromjpeg($path);
    } elseif ($mime === 'image/png' && function_exists('imagecreatefrompng')) {
        $img = @imagecreatefrompng($path);
    }
    if (!$img) {
        return false;
    }

    $tmp = $path . '.rebuild';
    $ok  = false;
    if ($mime === 'image/jpeg') {
        $ok = @imagejpeg($img, $tmp, 90);
    } else {
        // Keep transparency — signatures are routinely PNGs on a clear ground.
        @imagealphablending($img, false);
        @imagesavealpha($img, true);
        $ok = @imagepng($img, $tmp, 6);
    }
    @imagedestroy($img);

    if (!$ok || !is_file($tmp) || filesize($tmp) <= 0) {
        @unlink($tmp);
        return false;
    }
    if (!@rename($tmp, $path)) {
        @unlink($tmp);
        return false;
    }
    return true;
}

/**
 * Drop an .htaccess into an upload directory that stops the web server
 * executing anything in it. Defence in depth: even if a bad file somehow lands
 * here, Apache will not hand it to an interpreter.
 */
function uploadGuardProtectDir($dir)
{
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $ht = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . '.htaccess';
    if (is_file($ht)) {
        return;
    }
    $rules = "# Uploaded files are DATA. Never let the server execute them.\n"
        . "php_flag engine off\n"
        . "AddType text/plain .php .php3 .php4 .php5 .php7 .phtml .phps .pht .inc\n"
        . "RemoveHandler .php .php3 .php4 .php5 .php7 .phtml .phps .pht .inc\n"
        . "RemoveType .php .php3 .php4 .php5 .php7 .phtml .phps .pht .inc\n"
        . "<IfModule mod_cgi.c>\n  Options -ExecCGI\n</IfModule>\n"
        . "Options -Indexes -ExecCGI\n";
    @file_put_contents($ht, $rules);
}

/**
 * Validate and store an upload.
 *
 * @param array       $file    one entry from $_FILES
 * @param string      $kind    'image' | 'document'
 * @param string      $destDir absolute directory to write into
 * @param string|null $error   set to a user-safe reason on failure
 * @return string|null the stored FILENAME (not path), or null on rejection
 */
function uploadGuardStore(array $file, $kind, $destDir, &$error = null)
{
    $error = null;
    $kinds = uploadGuardKinds();
    if (!isset($kinds[$kind])) {
        $error = 'Internal error: unknown upload type.';
        return null;
    }
    $rule = $kinds[$kind];

    // 1. The upload itself.
    if (!isset($file['tmp_name'], $file['error'])) {
        $error = 'No file was received.';
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $map = [
            UPLOAD_ERR_INI_SIZE   => 'The file is larger than the server allows.',
            UPLOAD_ERR_FORM_SIZE  => 'The file is larger than the form allows.',
            UPLOAD_ERR_PARTIAL    => 'The file was only partially uploaded — please retry.',
            UPLOAD_ERR_NO_FILE    => 'Please choose a file to upload.',
            UPLOAD_ERR_NO_TMP_DIR => 'Server is missing a temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Server failed to write the file to disk.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension blocked the upload.',
        ];
        $error = isset($map[$file['error']]) ? $map[$file['error']] : 'File upload failed.';
        return null;
    }
    // Refuses a path that was not actually uploaded in this request — the guard
    // against a caller being tricked into moving an arbitrary local file.
    if (!is_uploaded_file($file['tmp_name'])) {
        $error = 'The file did not arrive as a valid upload.';
        return null;
    }

    // 2. Size.
    $size = @filesize($file['tmp_name']);
    if ($size === false || $size <= 0) {
        $error = 'The file is empty.';
        return null;
    }
    if ($size > $rule['max']) {
        $error = 'File is too large — the limit is ' . (int) ($rule['max'] / 1048576) . ' MB.';
        return null;
    }

    // 3. Extension claimed by the filename (checked, then discarded).
    $claimed = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));
    if (!in_array($claimed, $rule['ext'], true)) {
        $error = 'File type not allowed. Permitted: ' . implode(', ', $rule['ext']) . '.';
        return null;
    }

    // 4. MIME by content.
    $mime = uploadGuardDetectMime($file['tmp_name']);
    if (!in_array($mime, $rule['mime'], true)) {
        $error = 'The file contents are not a permitted type (detected: ' .
            ($mime !== '' ? $mime : 'unknown') . ').';
        return null;
    }

    // 5. Magic bytes must match the detected type.
    if (!uploadGuardMagicOk($file['tmp_name'], $mime)) {
        $error = 'The file signature does not match its type — it may be corrupt or disguised.';
        return null;
    }

    // 6. Images must genuinely decode.
    if (strpos($mime, 'image/') === 0) {
        $info = @getimagesize($file['tmp_name']);
        if (!$info || empty($info[0]) || empty($info[1])) {
            $error = 'The image could not be read.';
            return null;
        }
    }

    // 7. No embedded script (polyglot files).
    if (uploadGuardHasScriptSignature($file['tmp_name'])) {
        $error = 'The file contains embedded script content and was rejected.';
        return null;
    }

    // 7b. A PDF must be paper, not a program — no auto-run JavaScript, no
    // /Launch, no embedded payload file.
    if ($mime === 'application/pdf' && uploadGuardPdfHasActiveContent($file['tmp_name'])) {
        $error = 'The PDF contains active content (JavaScript, an auto-run action '
               . 'or an embedded file) and was rejected. Please upload a plain PDF.';
        return null;
    }

    // 8. Store under a random name with the extension of the DETECTED type.
    $ext = uploadGuardCanonicalExt($mime);
    if ($ext === '') {
        $error = 'Unsupported file type.';
        return null;
    }
    uploadGuardProtectDir($destDir);
    $name   = bin2hex(random_bytes(16)) . '.' . $ext;
    $target = rtrim($destDir, '/\\') . DIRECTORY_SEPARATOR . $name;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        $error = 'Could not save the uploaded file.';
        return null;
    }
    @chmod($target, 0644);   // readable, never executable

    // 9. Rebuild images from their pixels, so what is stored cannot contain
    // anything but image data. Done after the move so the file that ships is
    // the rebuilt one; a failure deletes it rather than keeping the original.
    if (strpos($mime, 'image/') === 0 && !uploadGuardRebuildImage($target, $mime)) {
        @unlink($target);
        $error = 'The image could not be processed safely and was rejected.';
        return null;
    }

    return $name;
}

} // function_exists guard

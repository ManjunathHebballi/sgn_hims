<?php
/**
 * ABDM care-context `display` normalisation.
 *
 * ABDM rejects a link request with `ABDM-9999: Invalid display` when any display
 * string in the payload is empty or carries content it will not accept. Every
 * label we send is assembled from clinical free text — diagnosis, complaints,
 * medicine names, consultancy — typed into textareas, so newlines, stray symbols
 * and very long strings reach the payload verbatim.
 *
 * Normalising is safe: `display` is a human-readable label only. Nothing keys off
 * it — a care context is addressed by its `referenceNumber`.
 */

// ABDM does not publish the limit. 100 matches the HIECM samples, and our own
// case-sheet labels already run past it on clean demo data. One place to raise
// or lower it if the sandbox proves to accept something different.
if (!defined('ABDM_DISPLAY_MAX')) {
    define('ABDM_DISPLAY_MAX', 100);
}

/**
 * Reduce one display string to something ABDM will accept.
 * Never returns an empty string — an empty display is itself rejected.
 */
function abdmSafeDisplay($text, $fallback = 'Health record')
{
    $text = (string) $text;

    // Non-breaking / zero-width spaces and control characters (a diagnosis typed
    // into a textarea carries CR/LF) become ordinary spaces.
    $text = str_replace(["\xC2\xA0", "\xE2\x80\x8B"], ' ', $text);
    $text = preg_replace('/[\x00-\x1F\x7F]+/', ' ', $text);

    // Conservative character set. Keeps dates (03.07.2026), vitals (BP- 120 / 80),
    // amounts (280.00), bill numbers (PH/2026-27/1878), dosing (1-0-1) and
    // bracketed abbreviations (URTI); drops quotes, angle brackets, braces,
    // backslashes and anything non-ASCII — the symbols a validator is liable to
    // reject. Byte-wise on purpose: after this the string is pure ASCII, so the
    // truncation below cannot split a multibyte character.
    $text = preg_replace('/[^A-Za-z0-9 .,:;\/()\[\]_+%&-]+/', ' ', $text);

    // Collapse the gaps the substitutions leave behind.
    $text = trim(preg_replace('/\s+/', ' ', $text));
    $text = trim($text, " ,;:-");

    if ($text === '') {
        return $fallback;
    }

    if (strlen($text) > ABDM_DISPLAY_MAX) {
        $cut = substr($text, 0, ABDM_DISPLAY_MAX);
        $sp  = strrpos($cut, ' ');
        // Break on a word boundary only when it still leaves a readable label.
        $text = rtrim($sp !== false && $sp >= 40 ? substr($cut, 0, $sp) : $cut, " ,;:-/");
    }

    return $text;
}

/**
 * Normalise every display in a `patient[]` linking payload — the group label and
 * each care context under it. Shape is left untouched otherwise.
 */
function abdmSanitizeCareContextEntries($entries)
{
    if (!is_array($entries)) {
        return $entries;
    }

    foreach ($entries as $i => $entry) {
        if (!is_array($entry)) {
            continue;
        }

        $hiType = isset($entry['hiType']) && $entry['hiType'] !== ''
            ? (string) $entry['hiType']
            : 'Health record';

        $entries[$i]['display'] = abdmSafeDisplay(
            isset($entry['display']) ? $entry['display'] : '',
            $hiType
        );

        if (!isset($entry['careContexts']) || !is_array($entry['careContexts'])) {
            continue;
        }

        foreach ($entry['careContexts'] as $j => $cc) {
            if (!is_array($cc)) {
                continue;
            }
            // Fall back to the reference number rather than the hiType: it keeps
            // the label distinct per care context when the clinical text is blank.
            $ref = isset($cc['referenceNumber']) ? (string) $cc['referenceNumber'] : '';
            $entries[$i]['careContexts'][$j]['display'] = abdmSafeDisplay(
                isset($cc['display']) ? $cc['display'] : '',
                abdmSafeDisplay($ref, $hiType)
            );
        }
    }

    return $entries;
}

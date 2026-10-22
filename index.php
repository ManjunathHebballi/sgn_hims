<?php    // Load environment configuration, then force session path before starting session
require_once(__DIR__ . '/model/env.php');
require_once(__DIR__ . '/model/session_config.php');      if(!isset($_SESSION))
    {
        session_start();
    }
    date_default_timezone_set(env('TZ', 'Asia/Calcutta'));
    $_SESSION['college_society']=env('HOSPITAL_SOCIETY', '');
    $_SESSION['college_name']=env('HOSPITAL_NAME', 'SGN HOSPITAL');
    $_SESSION['college_location']=env('HOSPITAL_LOCATION', 'Jalna');
    $_SESSION['college_mail']=env('HOSPITAL_MAIL', '');
    $_SESSION['college_phone']=env('HOSPITAL_PHONE', '');
    $_SESSION['pharma_name']=env('PHARMA_NAME', 'Pharmacy');
    $_SESSION['pharma_license_no']=env('PHARMA_LICENSE_NO', '');

    // ABDM / ABHA. The constants come from .env via config/abdm_config.php;
    // abha_integration.php reads them off the session on every request.
    require_once(__DIR__ . '/config/abdm_config.php');
    $_SESSION['clientid']     = ABDM_CLIENT_ID;
    $_SESSION['clientSecret'] = ABDM_CLIENT_SECRET;
    $_SESSION['base_url']     = ABDM_BASE_URL;
    $_SESSION['hipId']        = ABDM_HIP_ID;
    $_SESSION['hiuId']        = ABDM_HIU_ID;
    $_SESSION['x_cm_id']      = ABDM_CM_ID;
    $_SESSION['encKey']       = env('APP_ENC_KEY', '');

$date=date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sign in &middot; <?= htmlspecialchars($_SESSION['college_name']) ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

    <link rel="preload" as="image" href="images/1.jpg">
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link href="https://fonts.bunny.net/css?family=poppins:500,600,700|plus-jakarta-sans:400,500,600,700|ibm-plex-mono:400,500&display=swap" rel="stylesheet">

    <script>
        // Carried over from the application so a night-shift sign-in does not
        // flash white before the rest of the shift stays dark.
        (function () {
            try {
                if (localStorage.getItem('hms-theme') === 'night') {
                    document.documentElement.setAttribute('data-theme', 'night');
                }
            } catch (e) { /* storage blocked: day stays the default */ }
        })();
    </script>

    <style>
    /* =======================================================================
       Sign in
       -----------------------------------------------------------------------
       Deliberately standalone: no style.css, no Bootstrap. The admin theme
       hides #main-wrapper at opacity 0 until its JavaScript runs and forces
       every .form-control to #f9f9f9 !important, neither of which this page
       needs to inherit for two fields and a button.

       The tokens are the same ones hms-theme.css defines, so this page and the
       application read as one system.

       The page is the top of a ward register: a letterhead, a double rule, and
       the entry below it. There is no clinical state on a sign-in screen, so by
       the rule the rest of the system follows there is no colour here either --
       apart from the one thing you press.
       ======================================================================= */

    /* The same values hms-theme.css carries, taken from the Nexenstial HIMS
       demo so the sign-in screen and the application read as one product. */
    :root {
        color-scheme: light;
        --paper:      #E7ECF4;
        --surface:    #FFFFFF;
        --surface-2:  #EBF3FB;
        --rule:       #D5DCE6;
        --rule-2:     #E4E9F1;
        --ink:        #1A2431;
        --ink-2:      #49576B;
        --ink-3:      #5C6A7D;
        --ink-4:      #7B8798;
        --ink-head:   #1B3E66;   /* herb-800 */
        --action:     #245A9A;   /* herb-600 */
        --action-2:   #1E4A7E;   /* herb-700 */
        --action-ring: rgba(36, 90, 154, .18);
        --critical:      #A8412A;   /* clay-500 */
        --critical-bg:   #F1DED7;
        --critical-rule: #DBB5A8;
        --radius: 10px;
        --radius-pill: 9999px;

        --font-ui:      "Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        --font-display: "Poppins", "Plus Jakarta Sans", -apple-system, "Segoe UI", sans-serif;
        --font-id:      "IBM Plex Mono", ui-monospace, Consolas, monospace;
    }

    html[data-theme="night"] {
        color-scheme: dark;
        --paper:      #0E1D30;
        --surface:    #13263E;
        --surface-2:  #182F4C;
        --rule:       #26405E;
        --rule-2:     #1C3350;
        --ink:        #E7ECF4;
        --ink-2:      #B7C3D4;
        --ink-3:      #93A2B6;
        --ink-4:      #7B8798;
        --ink-head:   #A9CCEC;
        --action:     #76ABDD;   /* herb-300 */
        --action-2:   #A9CCEC;
        --action-ring: rgba(118, 171, 221, .25);
        --critical:      #E09A80;
        --critical-bg:   #3A1F17;
        --critical-rule: #6B3A28;
    }

    *, *::before, *::after { box-sizing: border-box; }

    body {
        margin: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 18px;
        padding: 32px 20px;
        /* The hospital's own front elevation under a light wash. The photo is a
           centred, symmetrical composition and the sheet sits directly over its
           signboard, so the building is treated as place rather than as content:
           washed back far enough that the white sheet still reads against it and
           the two dark lines below the sheet stay legible over both the bright
           sky and the shaded forecourt.

           The colour is declared separately from the image so that a browser
           without AVIF support (the file is AVIF despite its .jpg name) simply
           falls back to the flat paper ground. */
        background-color: var(--paper);
        background-image:
            linear-gradient(rgba(241, 244, 249, .80), rgba(241, 244, 249, .89)),
            url("images/1.jpg");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: var(--ink);
        font-family: var(--font-ui);
        font-size: 14px;
        line-height: 1.5;
        -webkit-font-smoothing: antialiased;
    }
    /* At night the building recedes almost entirely -- a lit photograph is the
       last thing a dim nurses' station needs. */
    html[data-theme="night"] body {
        background-image:
            linear-gradient(rgba(6, 8, 11, .93), rgba(6, 8, 11, .89)),
            url("images/1.jpg");
    }


    /* ------------------------------------------------------------- the sheet */
    .sheet {
        width: 100%;
        max-width: 400px;
        background: var(--surface);
        border: 1px solid var(--rule);
        border-radius: 28px;
        box-shadow: 0 20px 44px -18px rgba(23, 33, 51, .28), 0 2px 6px rgba(23, 33, 51, .06);
        padding: 30px 32px 32px;
    }

    /* ------------------------------------------------------------ letterhead */
    .letterhead { text-align: center; }

    .letterhead__mark {
        height: 46px;
        width: auto;
        margin: 0 auto 14px;
        display: block;
    }

    .letterhead__name {
        font-family: var(--font-display);
        font-size: 17px;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--ink-head);
        margin: 0;
        line-height: 1.25;
    }

    .letterhead__meta {
        font-size: 12px;
        color: var(--ink-3);
        margin: 5px 0 0;
    }

    /* The double rule a printed letterhead uses to close its heading: a heavy
       line, a gap, a hairline. It is the one piece of ornament on the page,
       and it is borrowed rather than invented. */
    .letterhead__rule {
        border: 0;
        height: 3px;
        margin: 18px 0 22px;
        border-top: 2px solid var(--ink-head);
        border-bottom: 1px solid var(--rule);
        background: transparent;
    }

    /* ----------------------------------------------------------------- form */
    .field { margin-bottom: 16px; }

    .field label {
        display: block;
        font-family: var(--font-display);
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--ink-3);
        margin-bottom: 5px;
    }

    .field input {
        width: 100%;
        font-family: var(--font-ui);
        font-size: 14px;
        color: var(--ink);
        background: var(--surface);
        border: 1px solid var(--rule);
        border-radius: var(--radius);
        padding: 9px 11px;
        transition: border-color .12s ease, box-shadow .12s ease;
    }

    .field input:focus {
        outline: none;
        border-color: var(--action);
        box-shadow: 0 0 0 3px var(--action-ring);
    }

    .field--password { position: relative; }
    .field--password input { padding-right: 42px; }

    .reveal {
        position: absolute;
        right: 4px;
        bottom: 4px;
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 0;
        background: transparent;
        color: var(--ink-3);
        cursor: pointer;
        border-radius: var(--radius);
    }
    .reveal:hover { color: var(--ink); background: var(--surface-2); }
    .reveal:focus-visible { outline: 2px solid var(--action); outline-offset: 1px; }
    .reveal svg { width: 18px; height: 18px; display: block; }

    .submit {
        width: 100%;
        font-family: var(--font-ui);
        font-size: 14px;
        font-weight: 500;
        color: #fff;
        background: var(--action);
        border: 1px solid var(--action);
        border-radius: var(--radius-pill);
        padding: 10px 16px;
        margin-top: 4px;
        cursor: pointer;
        transition: background-color .12s ease;
    }
    .submit:hover { background: var(--action-2); border-color: var(--action-2); }
    .submit:focus-visible { outline: 2px solid var(--ink); outline-offset: 2px; }

    /* Chrome paints its own pale blue over an autofilled field, which is
       unreadable once the sheet goes dark. Repainting it needs the inset
       shadow trick -- background-color alone is ignored. */
    .field input:-webkit-autofill,
    .field input:-webkit-autofill:hover {
        -webkit-text-fill-color: var(--ink);
        box-shadow: 0 0 0 1000px var(--surface) inset;
        caret-color: var(--ink);
    }
    .field input:-webkit-autofill:focus {
        -webkit-text-fill-color: var(--ink);
        box-shadow: 0 0 0 1000px var(--surface) inset, 0 0 0 3px var(--action-ring);
    }

    /* An error says what happened and what to do about it. It does not
       apologise and it does not name which of the two fields was wrong. */
    .notice {
        display: flex;
        gap: 9px;
        font-size: 13px;
        line-height: 1.45;
        color: var(--critical);
        background: var(--critical-bg);
        border: 1px solid var(--critical-rule);
        border-left: 3px solid var(--critical);
        border-radius: var(--radius);
        padding: 10px 12px;
        margin: 0 0 18px;
    }
    .notice svg { width: 16px; height: 16px; flex: 0 0 16px; margin-top: 1px; }

    /* ------------------------------------------------------------- the date
       A register page carries its date at the head. Staff check it before
       writing, and this screen is where a shift starts. */
    .datefoot {
        font-family: var(--font-id);
        font-size: 11.5px;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--ink-2);
        margin: 0;
        text-align: center;
    }

    .credit {
        font-size: 11.5px;
        color: var(--ink-3);
        margin: 0;
        text-align: center;
    }
    .credit a { color: var(--action); text-decoration: none; }
    .credit a:hover { color: var(--action-2); text-decoration: underline; }

    .sr-only {
        position: absolute; width: 1px; height: 1px;
        padding: 0; margin: -1px; overflow: hidden;
        clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;
    }


    /* The night teal is light enough that white text on it falls to about 3:1.
       Near-black on the same fill reaches 5.8:1. */
    html[data-theme="night"] .submit { color: #0B0E12; font-weight: 600; }

    /* The mark is a JPEG on a white ground, so on the dark sheet it is given a
       deliberate chip rather than left as a stray white rectangle. */
    html[data-theme="night"] .letterhead__mark {
        background: #fff;
        padding: 5px 9px;
        border-radius: 3px;
    }

    @media (max-width: 420px) {
        .sheet { padding: 24px 20px 26px; }
        .letterhead__name { font-size: 15px; }
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .001ms !important; }
    }
    </style>
</head>

<body>

    <main class="sheet">
        <div class="letterhead">
            <img class="letterhead__mark" src="images/Nexenstial TM.jpg"
                 alt="<?= htmlspecialchars($_SESSION['college_name']) ?>">
            <h1 class="letterhead__name"><?= htmlspecialchars($_SESSION['college_name']) ?></h1>
            <p class="letterhead__meta">
                <?= htmlspecialchars($_SESSION['college_location']) ?><?php
                if (!empty($_SESSION['college_phone'])) {
                    echo ' &middot; ' . htmlspecialchars($_SESSION['college_phone']);
                } ?>
            </p>
        </div>

        <hr class="letterhead__rule">

        <?php if (isset($_GET['e'])) { ?>
        <p class="notice" role="alert">
            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 4a1 1 0 011 1v4a1 1 0 01-2 0V7a1 1 0 011-1zm0 8.5a1.1 1.1 0 110-2.2 1.1 1.1 0 010 2.2z"/>
            </svg>
            <span>That username and password don&rsquo;t match. Check both and try again &mdash;
                  if it still fails, the IT desk can reset your password.</span>
        </p>
        <?php } ?>

        <form action="controller/processLogin.php" method="POST">
            <div class="field">
                <label for="username">User name</label>
                <input type="text" name="username" id="username"
                       autocomplete="username" autocapitalize="none"
                       spellcheck="false" autofocus required>
            </div>

            <div class="field field--password">
                <label for="password">Password</label>
                <input type="password" name="password" id="password"
                       autocomplete="current-password" minlength="5" required>
                <button type="button" class="reveal" id="togglePassword"
                        aria-controls="password" aria-pressed="false">
                    <span class="sr-only">Show password</span>
                    <svg id="eyeShow" viewBox="0 0 20 20" fill="none" stroke="currentColor"
                         stroke-width="1.6" aria-hidden="true">
                        <path d="M1.5 10S4.6 4.5 10 4.5 18.5 10 18.5 10 15.4 15.5 10 15.5 1.5 10 1.5 10z"/>
                        <circle cx="10" cy="10" r="2.6"/>
                    </svg>
                    <svg id="eyeHide" viewBox="0 0 20 20" fill="none" stroke="currentColor"
                         stroke-width="1.6" aria-hidden="true" style="display:none">
                        <path d="M1.5 10S4.6 4.5 10 4.5c1.4 0 2.6.4 3.7.9M18.5 10s-3.1 5.5-8.5 5.5c-1.4 0-2.6-.4-3.7-.9"/>
                        <path d="M3 3l14 14"/>
                    </svg>
                </button>
            </div>

            <button type="submit" class="submit">Sign in</button>
        </form>
    </main>

    <p class="datefoot"><?= date('l j F Y') ?></p>

    <p class="credit">
        Built by <a href="https://www.nexenstial.com/" target="_blank" rel="noopener">Nexenstial</a>
        &middot; <?= date('Y') ?>
    </p>

    <script>
        (function () {
            var input  = document.getElementById('password');
            var toggle = document.getElementById('togglePassword');
            if (!input || !toggle) { return; }

            var show = document.getElementById('eyeShow');
            var hide = document.getElementById('eyeHide');
            var srLabel = toggle.querySelector('.sr-only');

            toggle.addEventListener('click', function () {
                var hidden = input.getAttribute('type') === 'password';
                input.setAttribute('type', hidden ? 'text' : 'password');
                toggle.setAttribute('aria-pressed', hidden ? 'true' : 'false');
                show.style.display = hidden ? 'none' : 'block';
                hide.style.display = hidden ? 'block' : 'none';
                srLabel.textContent = hidden ? 'Hide password' : 'Show password';
                input.focus();
            });
        })();
    </script>

</body>

</html>

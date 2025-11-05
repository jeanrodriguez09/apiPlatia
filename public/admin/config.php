<?php

    // --- Facebook / Meta WhatsApp config (agregar en config.php) ---
    define('FB_APP_ID', '1653771918598099');               // App ID de Facebook Developers
    define('FB_APP_SECRET', '531647c6d9160cc8207f34d92c68da65');       // App Secret (no lo publiques)
    define('FB_REDIRECT_URI', 'https://platia.plat.com.py/admin/subprocesos/callbackFacebook.php'); // EXACTO como lo pongas en FB Dev
    // Genera una llave segura de 32 bytes y pégala como hex (ej: bin2hex(random_bytes(32)))
    // En consola: php -r "echo bin2hex(random_bytes(32));"
    define('FB_TOKEN_ENCRYPTION_KEY', '2eb948b6669999491d2598dc02d638b5aff3126ea79790bd77bf00b5a7dc8b3a'); // 64 hex chars = 32 bytes

    $link = mysqli_connect("api_platia_db", "root", "root", "api_platia_db");

?>
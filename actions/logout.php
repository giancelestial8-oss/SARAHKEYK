<?php
// 1. Buksan ang kasalukuyang session para ma-access ito
session_start();

// 2. Burahin lahat ng session variables (gaya ng user_id at email)
$_SESSION = array();

// 3. Sirain mismo ang session cookie sa browser kung meron man
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Tuluyang sirain ang session sa server
session_destroy();

// 5. Ibalik ang user sa login page (umakyat ng isang folder gamit ang ../)
header("Location: ../index.php");
exit;
<?php
require_once __DIR__ . '/includes/auth.php';
header('Location: ' . app_url(is_admin() ? 'admin/dashboard.php' : 'login.php'));
exit;

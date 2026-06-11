<?php
require_once __DIR__ . '/../api/config/database.php';
session_start();
$_SESSION = [];
session_destroy();
header('Location: ' . SITE_ROOT . '/admin/login.php');
exit;
?>
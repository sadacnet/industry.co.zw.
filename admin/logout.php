<?php
session_start();
$_SESSION = [];
session_destroy();
header('Location: /industry.co.zw/admin/login.php');
exit;
?>
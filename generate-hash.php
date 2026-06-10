<?php
// Generate a new password hash
$password = 'Admin@2026!';
$hash = password_hash($password, PASSWORD_BCRYPT);
echo "Password: $password<br>";
echo "Hash: $hash<br>";
?>
<?php
require '../php/db.php';
$email = 'admin@swapnest.com';
$hash = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE email = ?");
$stmt->execute([$hash, $email]);
echo "Done!";

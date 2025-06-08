<?php
require '../php/db.php';
header('Content-Type: application/json');
$stmt = $pdo->query("SELECT id, name, email, created_at FROM users");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

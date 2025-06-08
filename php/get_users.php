<?php
require 'db.php';
header('Content-Type: application/json');
$stmt = $pdo->query("SELECT id, name, email FROM users ORDER BY id");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

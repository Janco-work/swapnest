<?php
require '../php/db.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['name'], $data['email'], $data['password'])) {
    echo json_encode(['success'=>false, 'message'=>'Missing fields']); exit;
}
$hashed = password_hash($data['password'], PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
if ($stmt->execute([$data['name'], $data['email'], $hashed])) {
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false, 'message'=>'Insert failed']);
}

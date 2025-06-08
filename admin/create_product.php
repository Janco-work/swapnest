<?php
require '../php/db.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['name'], $data['description'], $data['price'], $data['email'])) {
    echo json_encode(['success'=>false, 'message'=>'Missing fields']); exit;
}
$stmt = $pdo->prepare("INSERT INTO products (name, description, price, email, image, created_at, sold) VALUES (?, ?, ?, ?, '', NOW(), 0)");
if ($stmt->execute([$data['name'], $data['description'], $data['price'], $data['email']])) {
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false, 'message'=>'Insert failed']);
}

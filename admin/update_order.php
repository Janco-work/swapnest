<?php
require '../php/db.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
if(!$data || !isset($data['id'])) {
    echo json_encode(['success'=>false, 'message'=>'Missing ID']); exit;
}
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$data['id']]);
echo json_encode(['success'=>true]);

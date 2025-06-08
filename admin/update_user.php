<?php
require '../php/db.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['id'], $data['name'], $data['email'])) {
    echo json_encode(['success'=>false, 'message'=>'Missing fields']); exit;
}
if (!empty($data['password'])) {
    $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, password=? WHERE id=?");
    $ok = $stmt->execute([$data['name'], $data['email'], $hashed, $data['id']]);
} else {
    $stmt = $pdo->prepare("UPDATE users SET name=?, email=? WHERE id=?");
    $ok = $stmt->execute([$data['name'], $data['email'], $data['id']]);
}
echo json_encode(['success'=>$ok]);

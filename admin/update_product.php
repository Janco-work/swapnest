<?php
require '../php/db.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
    echo json_encode(['success'=>false, 'message'=>'Missing ID']);
    exit;
}

if (isset($data['sold']) && count($data) === 2) {
    // Updating sold status
    $stmt = $pdo->prepare("UPDATE products SET sold=? WHERE id=?");
    $success = $stmt->execute([$data['sold'], $data['id']]);
    echo json_encode(['success' => $success]);
    exit;
}

// Full product update
if (!isset($data['name'], $data['description'], $data['price'], $data['email'], $data['sold'])) {
    echo json_encode(['success'=>false, 'message'=>'Missing fields']);
    exit;
}

$stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, email=?, sold=? WHERE id=?");
$success = $stmt->execute([
    $data['name'],
    $data['description'],
    $data['price'],
    $data['email'],
    $data['sold'],
    $data['id']
]);
echo json_encode(['success' => $success]);

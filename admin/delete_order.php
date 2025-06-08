<?php
require '../php/db.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['order_id'])) {
    echo json_encode(['success' => false, 'message' => 'No order ID provided']);
    exit;
}
$order_id = intval($data['order_id']);

$stmt = $pdo->prepare("DELETE FROM orders WHERE order_id = ?");
if ($stmt->execute([$order_id])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Delete failed.']);
}

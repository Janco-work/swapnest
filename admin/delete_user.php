<?php
require '../php/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'No user ID provided']);
    exit;
}
$id = intval($data['id']);

if ($id === 1) { 
    echo json_encode(['success' => false, 'message' => 'Cannot delete admin user.']);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
if ($stmt->execute([$id])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Delete failed.']);
}

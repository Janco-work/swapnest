<?php
header('Content-Type: application/json');
session_start();
require 'db.php';

// Read POST data
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email'], $data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Missing fields']);
    exit;
}

$email = trim($data['email']);
$password = $data['password'];

// Check if user exists
$stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    echo json_encode(['success' => true, 'name' => $user['name']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
}
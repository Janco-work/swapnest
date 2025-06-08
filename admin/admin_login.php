<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../php/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
$stmt->execute([$email]);
$admin = $stmt->fetch();

if (!$admin) {
    echo json_encode(['success' => false, 'message' => 'Email not found']);
    exit;
}
if (!password_verify($password, $admin['password'])) {
    echo json_encode(['success' => false, 'message' => 'Password incorrect']);
    exit;
}

$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_email'] = $admin['email'];
echo json_encode(['success' => true]);

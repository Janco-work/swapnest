<?php
require 'db.php';

header('Content-Type: application/json');

// POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success' => false, 'message' => 'Invalid request method']);
  exit;
}

// Check all required fields
if (
  !isset($_POST['name']) ||
  !isset($_POST['description']) ||
  !isset($_POST['price']) ||
  !isset($_POST['user_email']) ||
  !isset($_FILES['image'])
) {
  echo json_encode(['success' => false, 'message' => 'Missing fields']);
  exit;
}

$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$user_email = $_POST['user_email'];

// Handle image upload
$image = $_FILES['image'];
$uploadDir = '../uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
$imageName = uniqid('prod_', true) . '_' . basename($image['name']);
$imagePath = $uploadDir . $imageName;
if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
  echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
  exit;
}

try {
  $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image, email) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$name, $description, $price, $imageName, $user_email]);
  echo json_encode(['success' => true]);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

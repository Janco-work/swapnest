<?php
require 'db.php';
header('Content-Type: application/json');

$email = $_GET['email'] ?? '';
if (!$email) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE seller_email = ?");
$stmt->execute([$email]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>

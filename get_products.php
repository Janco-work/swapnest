<?php
require '../php/db.php';
header('Content-Type: application/json');
// Only select products that are NOT sold
$stmt = $pdo->query("SELECT id, name, description, price, image, email, created_at FROM products WHERE sold = 0");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>

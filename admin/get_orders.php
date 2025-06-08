<?php
require '../php/db.php';
header('Content-Type: application/json');
$stmt = $pdo->query("SELECT * FROM orders");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>

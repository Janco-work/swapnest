<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['buyer_email']) || !isset($data['items'])) {
    echo json_encode(['success' => false, 'message' => 'Missing order data']);
    exit;
}


foreach ($data['items'] as $item) {

    // Insert each product as a separate order row
    $stmt = $pdo->prepare("INSERT INTO orders (buyer_email, email, product_id, order_status, payment_status, order_date)
                           VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
        $data['buyer_email'],
        $item['email'] ?? '',
        $item['id'] ?? 0, 
        'pending',
        'paid'
    ]);

    // *** Mark product as sold ***
    if (isset($item['id'])) {
        $pid = intval($item['id']);
        $pdo->prepare("UPDATE products SET sold=1 WHERE id=?")->execute([$pid]);
    }
}

echo json_encode(['success' => true]);
?>

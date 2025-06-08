<?php
$host = 'maglev.proxy.rlwy.net';
$db   = 'railway';
$user = 'root';
$pass = 'vtROrSCjaysMdWojGkUFnSTYNnlYBdkp';
$port = 57175;

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}
?>

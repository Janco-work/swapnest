<?php
$host = 'mysql.railway.internal';
$db   = 'railway';
$user = 'root';
$pass = 'VRSNRkQtHuDqNCCLrssqxTzgzbOsqQLy';

$port = 3306; 
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
}   catch (PDOException $e) {
    die("DB error: " . $e->getMessage());
}

?>
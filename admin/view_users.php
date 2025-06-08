<?php
require '../php/db.php';
session_start();

if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

require '../php/db.php';
$users = $pdo->query("SELECT id, name, email FROM users")->fetchAll();

ob_start();
include 'view_users.html';
$html = ob_get_clean();

$rows = '';
foreach ($users as $user) {
    $rows .= "<tr>
        <td>{$user['id']}</td>
        <td>" . htmlspecialchars($user['name']) . "</td>
        <td>" . htmlspecialchars($user['email']) . "</td>
    </tr>";
}

echo str_replace('{{USER_ROWS}}', $rows, $html);

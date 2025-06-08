<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
    <h1>Admin Login</h1>
    <?php if (!empty($_SESSION['login_error'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['login_error']); ?></p>
        <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>

    <form method="POST" action="admin_login.php">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>

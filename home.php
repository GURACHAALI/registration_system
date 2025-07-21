<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; }
        .container { padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        .logout-btn { background: #f44336; color: white; padding: 10px 20px; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .logout-btn:hover { background: #d32f2f; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>You are logged in.</p>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>
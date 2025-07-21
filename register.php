<?php

require_once 'db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($phone) || empty($password)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Email is already registered.";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $username, $email, $phone, $hashed_password);
        if ($stmt->execute()) {
            $success = "Registration successful! <a href='login.php'>Login</a>.";
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; }
        .container { padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        input[type="text"], input[type="email"], input[type="password"], input[type="tel"] {
            width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px;
        }
        input[type="submit"] { background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        input[type="submit"]:hover { background: #45a049; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username ?? ''); ?>">
            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
            <input type="tel" name="phone" placeholder="Phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>">
            <input type="password" name="password" placeholder="Password">
            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>

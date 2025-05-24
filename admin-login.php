<?php
require_once('includes/init.php');

// Start the session
session_start();

// Hardcoded credentials (In production, use hashed passwords + database)
$admin_username = "admin";
$admin_password_plain = "admin123"; // plaintext for testing only

$error = '';

// Check for POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_user = trim($_POST['username'] ?? '');
    $input_pass = trim($_POST['password'] ?? '');

    if ($input_user === $admin_username && $input_pass === $admin_password_plain) {
        // Store user info in session
        $_SESSION['user'] = [
            'username' => $admin_username,
            'role' => 'admin',
            'is_admin' => true
        ];

        // Redirect to dashboard
        header("Location: admin/dashboard.php");
        exit();
    } else {
        $error = "Invalid admin credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Montserrat:wght@500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6a0dad;
            --secondary-color: #f3e9ff;
            --text-color: #333;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom right, #f6f0fc, #fff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-form {
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: var(--primary-color);
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 1.5rem;
        }

        form div {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
        }

        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #5800a3;
        }

        .error {
            color: red;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Admin Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div>
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

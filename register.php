<?php
require_once('includes/init.php');

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $username = trim($_POST['username'] ?? '');
        $display_name = trim($_POST['display_name'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate inputs
        if (empty($username) || empty($display_name) || empty($password)) {
            throw new Exception("All fields are required.");
        }

        if (strlen($username) < 4) {
            throw new Exception("Username must be at least 4 characters long.");
        }

        if (strlen($password) < 8) {
            throw new Exception("Password must be at least 8 characters long.");
        }

        $db = new Database();

        $existing = $db->query("SELECT id FROM users WHERE username = ?", [$username]);
        if ($existing->num_rows > 0) {
            throw new Exception("Username already exists. Please choose another.");
        }

        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $db->query(
            "INSERT INTO users (username, display_name, password) VALUES (?, ?, ?)",
            [$username, $display_name, $hashed]
        );

        header("Location: login.php?registered=1");
        exit();

    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .auth-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 25px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 15px;
        }
        .alert.error {
            background: #f8d7da;
            color: #721c24;
        }
        .password-strength {
            font-size: 14px;
            margin-top: 5px;
        }
        button {
            width: 100%;
            background: #007bff;
            color: #fff;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .footer-links {
            margin-top: 20px;
            text-align: center;
        }
        .footer-links a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthText = document.getElementById('password-strength');
            
            if (password.length === 0) {
                strengthText.textContent = '';
                return;
            }

            if (password.length < 8) {
                strengthText.textContent = 'Weak (minimum 8 characters)';
                strengthText.style.color = '#dc3545';
            } else if (password.length < 12) {
                strengthText.textContent = 'Medium';
                strengthText.style.color = '#fd7e14';
            } else {
                strengthText.textContent = 'Strong';
                strengthText.style.color = '#28a745';
            }
        }
    </script>
</head>
<body>
    <div class="auth-container">
        <h1>Create Your Account</h1>

        <?php if ($message): ?>
            <div class="alert <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text"
                       id="username"
                       name="username"
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                       required minlength="4"
                       placeholder="Enter username">
            </div>

            <div class="form-group">
                <label for="display_name">Display Name</label>
                <input type="text"
                       id="display_name"
                       name="display_name"
                       value="<?php echo htmlspecialchars($_POST['display_name'] ?? ''); ?>"
                       required
                       placeholder="Enter display name">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password"
                       id="password"
                       name="password"
                       required
                       minlength="8"
                       placeholder="Enter a secure password"
                       oninput="checkPasswordStrength()">
                <div id="password-strength" class="password-strength"></div>
            </div>

            <button type="submit">Register</button>
        </form>

        <div class="footer-links">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>

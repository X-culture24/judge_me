<?php
require_once __DIR__ . '/includes/init.php';

$db = new Database();
$auth = new Auth();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $is_judge = isset($_POST['is_judge']);

    try {
        if (empty($username) || empty($password)) {
            throw new Exception("Both fields are required.");
        }

        $stmt = $db->getConnection()->prepare("SELECT * FROM users WHERE username = ? AND is_judge = ?");
        // Fix: assign to variable before binding to pass by reference
        $is_judge_flag = $is_judge ? 1 : 0;
        $stmt->bind_param("si", $username, $is_judge_flag);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Set session details
            $_SESSION['user'] = $user;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_judge'] = $user['is_judge'];
            $_SESSION['display_name'] = $user['display_name'] ?? $user['username'];

            // Redirect judges to judge/scoring.php, others to public/scoreboard.php
            if ($user['is_judge']) {
                header("Location: judge/scoring.php");
            } else {
                header("Location: public/scoreboard.php");
            }
            exit();
        } else {
            throw new Exception("Invalid credentials.");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Judging System</title>
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
            color: var(--text-color);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            font-family: 'Montserrat', sans-serif;
        }

        form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
        }

        input[type="checkbox"] {
            margin-right: 8px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        button {
            background-color: var(--primary-color);
            color: #fff;
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

    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label>
                Username:
                <input type="text" name="username" required>
            </label>
            <label>
                Password:
                <input type="password" name="password" required>
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="is_judge"> I am a judge
            </label>
            <button type="submit">Login</button>
        </form>
    </div>

</body>
</html>

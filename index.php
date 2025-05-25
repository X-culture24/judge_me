<?php
require_once('includes/init.php');

// Redirect if already logged in
if ($auth->isLoggedIn()) {
    header("Location: " . ($auth->isAdmin() ? "admin/judges.php" : "judge/users.php"));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judging System</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6a0dad;
            --secondary-color: #dcd6f7;
            --text-color: #fff;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, #dcd6f7, #fff);
            color: #333;
            margin: 0;
            padding: 0;
        }

        .hero {
            background-color: var(--primary-color);
            color: var(--text-color);
            padding: 80px 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 1.2rem;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .auth-options {
            margin-top: 30px;
        }

        .btn {
            background-color: #fff;
            color: var(--primary-color);
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn:hover {
            background-color: var(--secondary-color);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
        }

        .features {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            flex: 1 1 30%;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 30px 20px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .card i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .card h3 {
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 10px;
            font-size: 1.3rem;
        }

        footer {
            background-color: var(--primary-color);
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        .footer-links {
            margin-bottom: 10px;
        }

        .footer-links a {
            margin: 0 15px;
            color: #fff;
            text-decoration: none;
            font-weight: 500;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>

    <section class="hero">
        <div class="container">
            <h1>Welcome to the Judging System</h1>
            <p>A comprehensive platform for managing competitions, scoring participants, and displaying real-time results.</p>
            <div class="auth-options">
                <a href="login.php" class="btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="register.php?public=1" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="features">
            <div class="card" onclick="location.href='admin-login.php'">
                <i class="fas fa-user-shield"></i>
                <h3>Admin Panel</h3>
                <p>Manage users, judges, and system settings.</p>
            </div>

            <div class="card" onclick="location.href='login.php'">
                <i class="fas fa-user-tie"></i>
                <h3>Judge Portal</h3>
                <p>Submit and manage scores for participants.</p>
            </div>

            <div class="card" onclick="location.href='public/scoreboard.php'">
                <i class="fas fa-trophy"></i>
                <h3>Live Scoreboard</h3>
                <p>View real-time competition results.</p>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-links">
            <a href="#"><i class="fab fa-github"></i> GitHub</a>
            <a href="#"><i class="fas fa-question-circle"></i> Help</a>
            <a href="#"><i class="fas fa-envelope"></i> Contact</a>
            <a href="#"><i class="fas fa-shield-alt"></i> Privacy</a>
        </div>
        <p>&copy; <?php echo date('Y'); ?> Judging System. All rights reserved.</p>
    </footer>

</body>
</html>

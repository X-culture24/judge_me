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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        .hero {
            background: linear-gradient(135deg, #6e48aa 0%, #9d50bb 100%);
            color: white;
            padding: 5rem 2rem;
            text-align: center;
            margin-bottom: 3rem;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto 2rem;
        }

        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #fff;
            color: #6e48aa;
        }

        .btn-secondary {
            background-color: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
        }

        .feature-card i {
            font-size: 2.5rem;
            color: #6e48aa;
            margin-bottom: 1rem;
        }

        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }

        .footer-links a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <section class="hero">
        <div class="container">
            <h1>Welcome to the Judging System</h1>
            <p>A comprehensive platform for managing competitions, scoring participants, and displaying real-time results.</p>
            
            <div class="cta-buttons">
                <a href="login.php" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="register.php" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="features">
            <div class="feature-card">
                <i class="fas fa-trophy"></i>
                <h3>Live Scoreboard</h3>
                <p>View real-time competition results with our auto-updating scoreboard.</p>
                <a href="public/scoreboard.php" class="btn btn-secondary" style="margin-top: 1rem;">
                    View Scoreboard
                </a>
            </div>

            <div class="feature-card">
                <i class="fas fa-user-tie"></i>
                <h3>Judge Portal</h3>
                <p>Submit and manage scores for participants through our intuitive interface.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-cog"></i>
                <h3>Admin Tools</h3>
                <p>Manage judges, participants, and system settings with powerful admin tools.</p>
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

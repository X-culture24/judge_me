<?php
require_once('includes/init.php');
$auth->requireAdmin();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $displayName = $_POST['display_name'] ?? '';
    $password = $_POST['password'] ?? '';
    $isAdmin = isset($_POST['is_admin']) ? true : false;
    
    $judge = new Judge();
    
    if (!$judge->judgeExists($username)) {
        if ($auth->register($username, $displayName, $password, $isAdmin)) {
            $message = "User registered successfully!";
        } else {
            $message = "Error registering user.";
        }
    } else {
        $message = "Username already exists.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register New User</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h1>Register New User</h1>
        
        <?php if ($message): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Display Name:</label>
                <input type="text" name="display_name" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_admin"> Is Admin
                </label>
            </div>
            <button type="submit">Register</button>
        </form>
        
        <p><a href="admin/judges.php">Back to Admin Panel</a></p>
    </div>
</body>
</html>

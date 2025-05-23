<?php
require_once('../includes/Judge.php');

$judge = new Judge();
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $displayName = $_POST['display_name'] ?? '';
    
    if (!empty($username) && !empty($displayName)) {
        if (!$judge->judgeExists($username)) {
            if ($judge->addJudge($username, $displayName)) {
                $message = "Judge added successfully!";
            } else {
                $message = "Error adding judge.";
            }
        } else {
            $message = "Judge with this username already exists.";
        }
    } else {
        $message = "Please fill all fields.";
    }
}

$judges = $judge->getAllJudges();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Manage Judges</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Manage Judges</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <h2>Add New Judge</h2>
        <form method="POST">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Display Name:</label>
                <input type="text" name="display_name" required>
            </div>
            <button type="submit">Add Judge</button>
        </form>
        
        <h2>Existing Judges</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Display Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($judges as $judge): ?>
                <tr>
                    <td><?php echo $judge['id']; ?></td>
                    <td><?php echo htmlspecialchars($judge['username']); ?></td>
                    <td><?php echo htmlspecialchars($judge['display_name']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

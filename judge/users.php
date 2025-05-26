<?php
require_once('../includes/init.php');
$auth->requireAuth(); // Any logged in judge can access

$user = new User();
$score = new Score();
$judgeId = $_SESSION['user_id'];

// Handle score submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $userId = $_POST['user_id'] ?? 0;
    $scoreValue = $_POST['score'] ?? 0;
    
    if ($userId && $scoreValue >= 1 && $scoreValue <= 100) {
        if ($score->addScore($judgeId, $userId, $scoreValue)) {
            $message = "Score submitted successfully!";
        } else {
            $message = "Error submitting score.";
        }
    } else {
        $message = "Invalid score value.";
    }
}

$users = $user->getAllUsers();
$judgeScores = $score->getScoresByJudge($judgeId);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Judge Panel - Score Participants</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['display_name']); ?></h1>
            <a href="../logout.php" class="logout">Logout</a>
        </header>
        
        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <h2>Score Participants</h2>
        <table>
            <thead>
                <tr>
                    <th>Participant</th>
                    <th>Current Score</th>
                    <th>Your Score</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $participant): ?>
                <tr>
                    <td><?php echo htmlspecialchars($participant['display_name']); ?></td>
                    <td><?php echo $participant['total_score']; ?></td>
                    <td>
                        <?php echo $judgeScores[$participant['id']] ?? 'Not scored yet'; ?>
                    </td>
                    <td>
                        <form method="POST" class="score-form">
                            <input type="hidden" name="user_id" value="<?php echo $participant['id']; ?>">
                            <input type="number" name="score" min="1" max="100" required>
                            <button type="submit">Submit</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

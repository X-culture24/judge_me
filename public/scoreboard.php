<?php
require_once('../includes/init.php');

$user = new User();
$users = $user->getAllUsers();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Public Scoreboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Auto-refresh every 10 seconds
        setInterval(function() {
            $.ajax({
                url: 'scoreboard_data.php',
                success: function(data) {
                    $('#scoreboard-body').html(data);
                }
            });
        }, 10000);
    });
    </script>
</head>
<body>
    <div class="container">
        <h1>Competition Scoreboard</h1>
        
        <table id="scoreboard">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Participant</th>
                    <th>Total Score</th>
                </tr>
            </thead>
            <tbody id="scoreboard-body">
                <?php 
                $rank = 1;
                foreach ($users as $participant): 
                    $highlight = $rank <= 3 ? "highlight-$rank" : '';
                ?>
                <tr class="<?php echo $highlight; ?>">
                    <td><?php echo $rank++; ?></td>
                    <td><?php echo htmlspecialchars($participant['name']); ?></td>
                    <td><?php echo $participant['total_score']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <p class="refresh-notice">Scores auto-refresh every 10 seconds</p>
    </div>
</body>
</html>

<?php
require_once(__DIR__ . '/../includes/init.php');

// Ensure only judges can access
$auth = new Auth();
$auth->requireJudge();

$db = new Database();
$scoreSystem = new Score();
$judgeId = $_SESSION['user']['id'] ?? null;

if (!$judgeId) {
    die("Unauthorized access. Judge ID missing.");
}

// Handle score submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'], $_POST['user_id'])) {
    $userId = (int)$_POST['user_id'];
    $scoreValue = (int)$_POST['score'];

    try {
        if ($scoreValue < 0 || $scoreValue > 100) {
            throw new Exception("Score must be between 1 and 100.");
        }

        if ($scoreValue === 0) {
            $scoreSystem->clearScore($judgeId, $userId);
            $message = "Score cleared successfully!";
        } else {
            $scoreSystem->addScore($judgeId, $userId, $scoreValue);
            $message = "Score submitted successfully!";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get participants and their scores
try {
    $participants = $db->query(
        "SELECT u.id, u.display_name, s.score 
         FROM users u
         LEFT JOIN scores s ON u.id = s.user_id AND s.judge_id = ?
         WHERE u.is_admin = FALSE AND u.is_judge = FALSE
         ORDER BY u.display_name",
        [$judgeId]
    )->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    die("Error loading participants: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Judge Scoring Panel</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Poppins:wght@300;400&display=swap" rel="stylesheet">

    <style>
        :root {
            --purple-dark: #5a189a;
            --purple-medium: #7b2ff7;
            --purple-light: #ede7f6;
            --text-dark: #2c2c54;
            --btn-purple: #7b2ff7;
            --btn-purple-hover: #5a189a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--purple-light);
            margin: 0;
            padding: 30px 20px;
            color: var(--text-dark);
            display: flex;
            justify-content: center;
        }

        .container {
            background: white;
            border-radius: 12px;
            max-width: 900px;
            width: 100%;
            box-shadow: 0 6px 15px rgba(123, 47, 247, 0.3);
            padding: 25px 30px;
            display: flex;
            flex-direction: column;
            height: 80vh;
        }

        h1 {
            font-family: 'Montserrat', sans-serif;
            color: var(--purple-dark);
            margin: 0 0 20px 0;
            text-align: center;
            font-weight: 600;
            font-size: 2.2rem;
        }

        p.welcome {
            font-weight: 500;
            text-align: center;
            margin-bottom: 20px;
            color: var(--purple-medium);
        }

        .message {
            padding: 12px 18px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-weight: 500;
            font-size: 1rem;
            text-align: center;
        }

        .success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }

        .error {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            box-shadow: 0 0 15px rgba(123, 47, 247, 0.1);
        }

        thead {
            background: var(--purple-medium);
            color: white;
            flex-shrink: 0;
        }

        thead tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        tbody {
            display: block;
            overflow-y: auto;
            height: 55vh;
            background: white;
        }

        tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
            border-bottom: 1px solid #eee;
        }

        tbody tr:hover {
            background: var(--purple-light);
        }

        th, td {
            padding: 14px 15px;
            text-align: left;
            font-size: 1rem;
        }

        input[type="number"] {
            width: 80px;
            padding: 6px 8px;
            font-size: 1rem;
            border: 1.5px solid var(--purple-medium);
            border-radius: 6px;
        }

        input[type="number"]:focus {
            outline: none;
            border-color: var(--purple-dark);
            box-shadow: 0 0 6px var(--purple-medium);
        }

        button {
            background-color: var(--btn-purple);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
        }

        button:hover {
            background-color: var(--btn-purple-hover);
        }

        form {
            margin: 0;
        }

        a.logout-link {
            margin-top: 20px;
            display: inline-block;
            text-align: center;
            font-weight: 600;
            color: var(--purple-medium);
            text-decoration: none;
        }

        a.logout-link:hover {
            text-decoration: underline;
            color: var(--purple-dark);
        }

        @media (max-width: 600px) {
            body {
                padding: 15px 10px;
            }

            .container {
                padding: 20px 15px;
                height: 70vh;
            }

            input[type="number"] {
                width: 60px;
            }

            th, td {
                padding: 10px 8px;
                font-size: 0.9rem;
            }

            button {
                padding: 6px 12px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Judge Scoring Panel</h1>
    <p class="welcome">Welcome, <?= htmlspecialchars($_SESSION['user']['display_name']) ?></p>

    <?php if (!empty($message)): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Participant</th>
                <th>Current Score</th>
                <th>New Score</th>
                <th>Clear</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($participants as $participant): ?>
            <tr>
                <td><?= htmlspecialchars($participant['display_name']) ?></td>
                <td><?= isset($participant['score']) ? htmlspecialchars($participant['score']) : 'Not scored' ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="user_id" value="<?= $participant['id'] ?>">
                        <input type="number" name="score" min="1" max="100" required />
                        <button type="submit">Submit</button>
                    </form>
                </td>
                <td>
                    <?php if (isset($participant['score'])): ?>
                        <form method="POST">
                            <input type="hidden" name="user_id" value="<?= $participant['id'] ?>">
                            <input type="hidden" name="score" value="0" />
                            <button type="submit">Clear</button>
                        </form>
                    <?php else: ?>
                        &mdash;
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a href="../logout.php" class="logout-link">Logout</a>
</div>
</body>
</html>

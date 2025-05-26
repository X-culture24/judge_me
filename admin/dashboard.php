<?php
require_once __DIR__ . '/../includes/init.php';

// Fix: only start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header("Location: ../admin-login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = new Database();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['promote'])) {
            $db->query("UPDATE users SET is_judge=1 WHERE id=?", [$_POST['user_id']]);
        }
        if (isset($_POST['delete'])) {
            $userId = $_POST['user_id'];
            $db->query("UPDATE scores SET judge_id = NULL WHERE judge_id = ?", [$userId]);
            $db->query("DELETE FROM users WHERE id=? AND username != 'admin'", [$userId]);
        }
    }

    $users = $db->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fc;
            margin: 0;
            padding: 2rem;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #6e48aa;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #6e48aa;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        form {
            display: inline-block;
        }
        button {
            padding: 8px 14px;
            margin: 0 4px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button[name="promote"] {
            background-color: #28a745;
            color: white;
        }
        button[name="promote"]:hover {
            background-color: #218838;
        }
        button[name="delete"] {
            background-color: #dc3545;
            color: white;
        }
        button[name="delete"]:hover {
            background-color: #c82333;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            tr {
                margin-bottom: 1rem;
                border: 1px solid #ddd;
                border-radius: 8px;
                background: white;
            }
            th {
                display: none;
            }
            td {
                padding: 10px;
                text-align: right;
                position: relative;
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                top: 10px;
                font-weight: bold;
                color: #666;
                text-align: left;
            }
        }
    </style>
</head>
<body>

<h1>Admin Dashboard – User Management</h1>

<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Display Name</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td data-label="Username"><?= htmlspecialchars($user['username']) ?></td>
                <td data-label="Display Name"><?= htmlspecialchars($user['display_name']) ?></td>
                <td data-label="Type"><?= $user['is_judge'] ? 'Judge' : 'User' ?></td>
                <td data-label="Actions">
                    <?php if ($user['username'] !== 'admin'): ?>
                        <?php if (!$user['is_judge']): ?>
                            <form method="POST">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" name="promote">Make Judge</button>
                            </form>
                        <?php endif; ?>
                        <form method="POST">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>

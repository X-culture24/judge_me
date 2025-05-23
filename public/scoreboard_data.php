<?php
require_once('../includes/init.php');

$user = new User();
$users = $user->getAllUsers();

$rank = 1;
foreach ($users as $participant) {
    $highlight = $rank <= 3 ? "highlight-$rank" : '';
    echo '<tr class="' . $highlight . '">';
    echo '<td>' . $rank++ . '</td>';
    echo '<td>' . htmlspecialchars($participant['name']) . '</td>';
    echo '<td>' . $participant['total_score'] . '</td>';
    echo '</tr>';
}
?>

<?php
if (!defined('INIT_LOADED')) {
    die('Direct access not permitted');
}

// Use Docker service name for DB host
if (!defined('DB_HOST')) define('DB_HOST', 'db'); // Not localhost
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', 'Cristiano7!');
if (!defined('DB_NAME')) define('DB_NAME', 'judging_system');
?>

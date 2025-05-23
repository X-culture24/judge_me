<?php
define('INIT_LOADED', true);
session_start();
session_start();
require_once('config.php');
require_once('Database.php');
require_once('Auth.php');
require_once('Judge.php');
require_once('User.php');
require_once('Score.php');

$auth = new Auth();
?>

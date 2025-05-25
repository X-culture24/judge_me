<?php
require_once('Database.php');

class Auth {
    private $db;
    private $basePath;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->db = new Database();

        // Automatically detect base path (adjusts if inside /judging-system or root)
        $this->basePath = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    }

    public function register($username, $displayName, $password, $isJudge = true) {
        $username = $this->db->escapeString($username);
        $displayName = $this->db->escapeString($displayName);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $isJudge = $isJudge ? 1 : 0;

        $sql = "INSERT INTO users (username, display_name, password, is_judge)
                VALUES ('$username', '$displayName', '$hashedPassword', $isJudge)";
        return $this->db->query($sql);
    }

    public function login($username, $password) {
        $username = $this->db->escapeString($username);
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $this->db->query($sql);

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_judge'] = $user['is_judge'];
                $_SESSION['display_name'] = $user['display_name'];
                $_SESSION['user'] = $user;
                return true;
            }
        }
        return false;
    }

    public function logout() {
        if (session_status() !== PHP_SESSION_NONE) {
            session_unset();
            session_destroy();
        }
        $this->redirect('login.php');
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin() {
        return $this->isLoggedIn() && $_SESSION['username'] === 'admin';
    }

    public function isJudge() {
        return $this->isLoggedIn() && !empty($_SESSION['is_judge']);
    }

    private function redirect($path) {
        header("Location: {$this->basePath}/$path");
        exit();
    }

    public function requireUser() {
        if (!$this->isLoggedIn()) {
            $this->redirect('login.php');
        }
    }

    public function requireJudge() {
        if (!$this->isLoggedIn() || !$this->isJudge()) {
            $this->redirect('login.php');
        }
    }

    public function requireAdmin() {
        if (!$this->isLoggedIn() || !$this->isAdmin()) {
            $this->redirect('admin-login.php');
        }
    }

    public function requireScoreboardAccess() {
        if (!$this->isLoggedIn() || (!$this->isJudge() && !$this->isAdmin())) {
            $this->redirect('login.php');
        }
    }

    public function requireAuth() {
        $this->requireUser();
    }

    public function changePassword($userId, $newPassword) {
        if (!$this->isAdmin()) {
            return false;
        }
        $userId = intval($userId);
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password = '$hashedPassword' WHERE id = $userId";
        return $this->db->query($sql);
    }
}
?>

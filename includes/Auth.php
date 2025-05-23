<?php
require_once('Database.php');

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function register($username, $displayName, $password, $isAdmin = false) {
        $username = $this->db->escapeString($username);
        $displayName = $this->db->escapeString($displayName);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO judges (username, display_name, password, is_admin) 
                VALUES ('$username', '$displayName', '$hashedPassword', " . ($isAdmin ? 'TRUE' : 'FALSE') . ")";
        
        return $this->db->query($sql);
    }
    
    public function login($username, $password) {
        $username = $this->db->escapeString($username);
        $sql = "SELECT * FROM judges WHERE username = '$username'";
        $result = $this->db->query($sql);
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'];
                $_SESSION['display_name'] = $user['display_name'];
                return true;
            }
        }
        return false;
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function isAdmin() {
        return $this->isLoggedIn() && $_SESSION['is_admin'];
    }
    
    public function logout() {
        session_unset();
        session_destroy();
    }
    
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            header("Location: ../login.php");
            exit();
        }
    }
    
    public function requireAdmin() {
        $this->requireAuth();
        if (!$this->isAdmin()) {
            header("Location: ../unauthorized.php");
            exit();
        }
    }
}
?>

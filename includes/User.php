<?php
require_once('Database.php');

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllUsers() {
        $sql = "SELECT users.*, COALESCE(SUM(scores.score), 0) as total_score 
                FROM users 
                LEFT JOIN scores ON users.id = scores.user_id 
                GROUP BY users.id 
                ORDER BY total_score DESC";
        
        $result = $this->db->query($sql);
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        return $users;
    }

    public function getUserById($id) {
        $id = $this->db->escapeString($id);
        $sql = "SELECT * FROM users WHERE id = $id";
        $result = $this->db->query($sql);
        
        return $result->fetch_assoc();
    }
}
?>

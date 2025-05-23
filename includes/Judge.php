<?php
require_once('Database.php');

class Judge {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllJudges() {
        $sql = "SELECT * FROM judges ORDER BY display_name ASC";
        $result = $this->db->query($sql);
        
        $judges = [];
        while ($row = $result->fetch_assoc()) {
            $judges[] = $row;
        }
        
        return $judges;
    }

    public function addJudge($username, $displayName) {
        $username = $this->db->escapeString($username);
        $displayName = $this->db->escapeString($displayName);
        
        $sql = "INSERT INTO judges (username, display_name) VALUES ('$username', '$displayName')";
        
        return $this->db->query($sql);
    }

    public function judgeExists($username) {
        $username = $this->db->escapeString($username);
        $sql = "SELECT id FROM judges WHERE username = '$username'";
        $result = $this->db->query($sql);
        
        return $result->num_rows > 0;
    }
}
?>

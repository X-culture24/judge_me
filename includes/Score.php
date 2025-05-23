<?php
require_once('Database.php');

class Score {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addScore($judgeId, $userId, $score) {
        $judgeId = $this->db->escapeString($judgeId);
        $userId = $this->db->escapeString($userId);
        $score = $this->db->escapeString($score);
        
        // Check if score exists first
        $checkSql = "SELECT id FROM scores WHERE judge_id = $judgeId AND user_id = $userId";
        $checkResult = $this->db->query($checkSql);
        
        if ($checkResult->num_rows > 0) {
            // Update existing score
            $sql = "UPDATE scores SET score = $score WHERE judge_id = $judgeId AND user_id = $userId";
        } else {
            // Insert new score
            $sql = "INSERT INTO scores (judge_id, user_id, score) VALUES ($judgeId, $userId, $score)";
        }
        
        return $this->db->query($sql);
    }

    public function getScoresByJudge($judgeId) {
        $judgeId = $this->db->escapeString($judgeId);
        $sql = "SELECT user_id, score FROM scores WHERE judge_id = $judgeId";
        $result = $this->db->query($sql);
        
        $scores = [];
        while ($row = $result->fetch_assoc()) {
            $scores[$row['user_id']] = $row['score'];
        }
        
        return $scores;
    }
}
?>

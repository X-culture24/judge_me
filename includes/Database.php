<?php
class Database {
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $connection;

    public function __construct() {
        require_once('config.php');
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
        $this->dbname = DB_NAME;

        $this->connectWithRetry();
    }

    private function connectWithRetry() {
        $maxRetries = 10;
        $retryDelay = 3;
        $attempt = 0;

        while ($attempt < $maxRetries) {
            $this->connection = @new mysqli($this->host, $this->user, $this->pass, $this->dbname);

            if (!$this->connection->connect_error) {
                return;
            }

            $attempt++;
            error_log("Attempt $attempt: Database connection failed – retrying in $retryDelay seconds...");
            sleep($retryDelay);
        }

        // Final failure
        die("Connection failed after $maxRetries attempts: " . $this->connection->connect_error);
    }

    public function getConnection() {
        return $this->connection;
    }

    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->connection->error);
        }

        if (!empty($params)) {
            $types = '';
            foreach ($params as $param) {
                $types .= is_int($param) ? 'i' : 's'; // You can expand this logic for floats ('d') or blobs ('b') as needed
            }
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    public function escapeString($string) {
        return $this->connection->real_escape_string($string);
    }

    public function getLastInsertId() {
        return $this->connection->insert_id;
    }
}
?>

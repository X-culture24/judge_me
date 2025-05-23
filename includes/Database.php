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
        
        $this->connect();
    }

    private function connect() {
        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function query($sql) {
        return $this->connection->query($sql);
    }

    public function escapeString($string) {
        return $this->connection->real_escape_string($string);
    }

    public function getLastInsertId() {
        return $this->connection->insert_id;
    }
}
?>

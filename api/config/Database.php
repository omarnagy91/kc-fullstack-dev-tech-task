<?php
class Database
{
    // Use 'db' as the hostname which is the service name in docker-compose.yml
    private $host = 'db';
    private $db_name = 'course_catalog';
    private $username = 'test_user';
    private $password = 'test_password';
    private $conn;

    public function connect()
    {
        $this->conn = null;
        try {
            // Use the internal Docker network hostname (service name) and port
            $this->conn = new PDO('mysql:host=' . $this->host . ';port=3306;dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
        return $this->conn;
    }
}
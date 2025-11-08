<?php

namespace FitSphere\Database;

use PDO;
use PDOException;

class Database
{
    private $host = "localhost";
    private $db_name = "test_rentfit";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("DB Connection Error: " . $e->getMessage());
        }
        return $this->conn;
    }
}

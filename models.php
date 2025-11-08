<?php
require_once __DIR__ . '/includes/db.php';


use FitSphere\Database\Database;   
use FitSphere\Core\Session; 

class User {
    private $conn;
    private $table = "users";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function findByEmail($email) {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function register($email, $password, $role = 'guest') {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (email, password, role) VALUES (:email, :password, :role)");
        $stmt->execute([':email' => $email, ':password' => $hash, ':role' => $role]);
    }
}

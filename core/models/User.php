<?php
require_once 'bd.php';

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect(); 
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }

    public function createUser($nombre, $email, $password, $rol) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO usuarios (nombre, email, password, rol_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nombre, $email, $passwordHash, $rol);

        $result = $stmt->execute();
        $insertId = $stmt->insert_id;
        $stmt->close();

        return $result ? $insertId : false;
    }

    
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT id, nombre, password, rol_id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_id, $nombre, $hashed_password, $rol_id);
        $stmt->fetch();
        $stmt->close();

        if ($user_id && password_verify($password, $hashed_password)) {
            return ['id' => $user_id, 'nombre' => $nombre, 'rol_id' => $rol_id];
        }

        return false;
    }

    public function closeConnection() {
        $this->db->close();
    }
}

<?php
// assets/models/User.php
require_once 'bd.php';

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect(); // Iniciar la conexión
    }

    // Verificar si el email ya está registrado
    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }

    // Crear nuevo usuario
    public function createUser($nombre, $email, $password, $rol) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO usuarios (nombre, email, password, rol_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nombre, $email, $passwordHash, $rol);

        $result = $stmt->execute();
        $insertId = $stmt->insert_id;
        $stmt->close();

        return $result ? $insertId : false;
    }

    // Cerrar la conexión de la base de datos
    public function closeConnection() {
        $this->db->close();
    }
}

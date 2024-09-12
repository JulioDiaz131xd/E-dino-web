<?php
require_once 'bd.php';

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    // Obtener nombre del usuario por ID
    public function getUserNameById($user_id) {
        $stmt = $this->conn->prepare("SELECT nombre FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($nombre);
        $stmt->fetch();
        $stmt->close();

        return $nombre;
    }

    // Obtener clases del usuario
    public function getUserClasses($user_id) {
        $stmt = $this->conn->prepare("SELECT c.id, c.nombre, c.descripcion, c.codigo
                                      FROM clases c
                                      JOIN clases_usuarios cu ON c.id = cu.clase_id
                                      WHERE cu.usuario_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $clases = [];
        while ($row = $result->fetch_assoc()) {
            $clases[] = $row;
        }
        $stmt->close();

        return $clases;
    }

    // Crear nueva clase
    public function createClass($user_id, $nombre, $descripcion) {
        $codigo = substr(md5(uniqid(mt_rand(), true)), 0, 8); // Generar código único

        $stmt = $this->conn->prepare("INSERT INTO clases (nombre, descripcion, codigo, creador_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nombre, $descripcion, $codigo, $user_id);
        
        if ($stmt->execute()) {
            $clase_id = $stmt->insert_id;
            $stmt->close();

            // Unir al creador a la clase
            $stmt = $this->conn->prepare("INSERT INTO clases_usuarios (usuario_id, clase_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $clase_id);
            $stmt->execute();
            $stmt->close();

            return $codigo;
        } else {
            return false;
        }
    }

    // Unirse a una clase por código
    public function joinClassByCode($user_id, $codigo) {
        // Buscar la clase por código
        $stmt = $this->conn->prepare("SELECT id FROM clases WHERE codigo = ?");
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($clase_id);
            $stmt->fetch();
            $stmt->close();

            // Verificar si el usuario ya está inscrito en la clase
            $stmt = $this->conn->prepare("SELECT id FROM clases_usuarios WHERE usuario_id = ? AND clase_id = ?");
            $stmt->bind_param("ii", $user_id, $clase_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 0) {
                // Unir al usuario a la clase
                $stmt = $this->conn->prepare("INSERT INTO clases_usuarios (usuario_id, clase_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $user_id, $clase_id);
                $stmt->execute();
                $stmt->close();

                return 'success';
            } else {
                return 'Ya estás inscrito en esta clase.';
            }
        } else {
            return 'Código de clase no encontrado.';
        }
    }

    // Obtener progreso de las clases del usuario
    public function getUserClassProgress($user_id) {
        $stmt = $this->conn->prepare("SELECT c.nombre, pc.progreso
                                      FROM progreso_clases pc
                                      JOIN clases c ON pc.clase_id = c.id
                                      WHERE pc.usuario_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $progreso = [];
        while ($row = $result->fetch_assoc()) {
            $progreso[] = $row;
        }
        $stmt->close();

        return $progreso;
    }

    // Cerrar la conexión a la base de datos
    public function closeConnection() {
        $this->conn->close();
    }
}

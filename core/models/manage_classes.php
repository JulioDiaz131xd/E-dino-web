<?php
require_once 'bd.php';

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    // Obtener detalles de la clase
    public function getClassDetails($clase_id) {
        $stmt = $this->conn->prepare("SELECT nombre, descripcion FROM clases WHERE id = ?");
        $stmt->bind_param("i", $clase_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $clase = $result->fetch_assoc();
        $stmt->close();
        return $clase;
    }

    // Verificar si el usuario está inscrito en la clase
    public function isUserInClass($user_id, $clase_id) {
        $stmt = $this->conn->prepare("SELECT id FROM clases_usuarios WHERE usuario_id = ? AND clase_id = ?");
        $stmt->bind_param("ii", $user_id, $clase_id);
        $stmt->execute();
        $stmt->store_result();
        $is_in_class = $stmt->num_rows > 0;
        $stmt->close();
        return $is_in_class;
    }

    // Crear nuevo examen
    public function createExam($exam_name, $exam_description, $clase_id) {
        $stmt = $this->conn->prepare("INSERT INTO examenes (nombre, descripcion, clase_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $exam_name, $exam_description, $clase_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Crear nuevo material de clase
    public function createClassMaterial($material_title, $material_description, $clase_id) {
        $stmt = $this->conn->prepare("INSERT INTO materiales_clase (titulo, descripcion, clase_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $material_title, $material_description, $clase_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Obtener miembros de la clase
    public function getClassMembers($clase_id) {
        $stmt = $this->conn->prepare("
            SELECT u.id, u.nombre, u.email 
            FROM usuarios u
            INNER JOIN clases_usuarios cu ON u.id = cu.usuario_id
            WHERE cu.clase_id = ?
        ");
        $stmt->bind_param("i", $clase_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $miembros = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $miembros;
    }

    // Función para que el usuario salga de la clase
    public function leaveClass($user_id, $clase_id) {
        $stmt = $this->conn->prepare("DELETE FROM clases_usuarios WHERE usuario_id = ? AND clase_id = ?");
        $stmt->bind_param("ii", $user_id, $clase_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Cerrar la conexión a la base de datos
    public function closeConnection() {
        $this->conn->close();
    }
}

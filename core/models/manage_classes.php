<?php
require_once 'bd.php';

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    public function getClassDetails($clase_id) {
        $stmt = $this->conn->prepare("SELECT nombre, descripcion FROM clases WHERE id = ?");
        $stmt->bind_param("i", $clase_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $clase = $result->fetch_assoc();
        $stmt->close();
        return $clase;
    }

    public function isUserInClass($user_id, $clase_id) {
        $stmt = $this->conn->prepare("SELECT id FROM clases_usuarios WHERE usuario_id = ? AND clase_id = ?");
        $stmt->bind_param("ii", $user_id, $clase_id);
        $stmt->execute();
        $stmt->store_result();
        $is_in_class = $stmt->num_rows > 0;
        $stmt->close();
        return $is_in_class;
    }

    public function createExam($exam_name, $exam_description, $clase_id) {
        $stmt = $this->conn->prepare("INSERT INTO examenes (nombre, descripcion, clase_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $exam_name, $exam_description, $clase_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function createClassMaterial($material_title, $material_description, $clase_id, $material_value, $due_date) {
        $stmt = $this->conn->prepare("INSERT INTO materiales_clase (titulo, descripcion, clase_id, valor, fecha_limite) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiis", $material_title, $material_description, $clase_id, $material_value, $due_date);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    

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

    public function leaveClass($user_id, $clase_id) {
        $stmt = $this->conn->prepare("DELETE FROM clases_usuarios WHERE usuario_id = ? AND clase_id = ?");
        $stmt->bind_param("ii", $user_id, $clase_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function closeConnection() {
        $this->conn->close();
    }
    public function getClassMaterials($clase_id) {
        $stmt = $this->conn->prepare("SELECT id, titulo FROM materiales_clase WHERE clase_id = ?");
        $stmt->bind_param("i", $clase_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $materiales = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $materiales;
    }
    
    public function getMaterialDetails($material_id) {
        $stmt = $this->conn->prepare("SELECT titulo, descripcion, fecha_creacion FROM materiales_clase WHERE id = ?");
        $stmt->bind_param("i", $material_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $material = $result->fetch_assoc();
        $stmt->close();
        return $material;
    }
    
    public function deleteClassMaterial($material_id) {
        $stmt = $this->conn->prepare("DELETE FROM materiales_clase WHERE id = ?");
        $stmt->bind_param("i", $material_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
}

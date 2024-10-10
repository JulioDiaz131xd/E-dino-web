<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../core/models/manage_classes.php';

$material_id = isset($_GET['material_id']) ? intval($_GET['material_id']) : 0;

$user = new User();

// Intenta eliminar el material
if ($user->deleteClassMaterial($material_id)) {
    // Redirige a la página de gestión de clase con un mensaje de éxito
    header("Location: gestionar_clase.php?mensaje=Material eliminado con éxito");
} else {
    // Redirige a la página de gestión de clase con un mensaje de error
    header("Location: gestionar_clase.php?error=Error al eliminar el material");
}

$user->closeConnection();
?>

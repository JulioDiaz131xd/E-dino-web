<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../core/models/manage_classes.php';

$usuario_id = $_SESSION['user_id'];
$material_title = $_POST['material-title'];
$material_description = $_POST['material-description'];
$material_value = $_POST['material-value'];
$due_date = $_POST['due-date'];
$clase_id = isset($_POST['clase_id']) ? intval($_POST['clase_id']) : 0; // Asegúrate de que esto esté definido.

$user = new User();

// Guarda el material de clase en la base de datos
$result = $user->createClassMaterial($material_title, $material_description, $clase_id, $material_value, $due_date); // Actualiza esta línea según el método en tu modelo.

if ($result) {
    header("Location: gestionar_clase.php?clase_id=" . $clase_id);
} else {
    echo "Error al crear el material.";
}

$user->closeConnection();
?>
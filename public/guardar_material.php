<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../core/models/manage_classes.php';

$usuario_id = $_SESSION['user_id'];
$clase_id = isset($_POST['clase_id']) ? intval($_POST['clase_id']) : 0;

$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $material_title = $_POST['material-title'];
    $material_description = $_POST['material-description'];

    $result = $user->createClassMaterial($material_title, $material_description, $clase_id);
    if ($result) {
        header("Location: gestionar_clase.php?clase_id=$clase_id");
        exit();
    } else {
        echo "Error al guardar el material.";
    }
}

$user->closeConnection();
?>

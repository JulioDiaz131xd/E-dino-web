<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../core/models/manage_classes.php';

$usuario_id = $_SESSION['user_id'];
$material_id = isset($_GET['material_id']) ? intval($_GET['material_id']) : 0;
$clase_id = isset($_GET['clase_id']) ? intval($_GET['clase_id']) : 0;

$user = new User();

if ($material_id > 0) {
    $user->deleteClassMaterial($material_id);
}

header("Location: gestionar_clase.php?clase_id=" . $clase_id);
exit();

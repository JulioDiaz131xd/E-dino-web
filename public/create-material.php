<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$clase_id = isset($_GET['clase_id']) ? intval($_GET['clase_id']) : 0;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Material de Clase - E-Dino</title>
    <link rel="stylesheet" href="../assets/css/create_material.css">
</head>
<body>
    <header>
        <h1>Crear Material de Clase</h1>
    </header>
    <main>
        <form id="create-material-form" action="guardar_material.php" method="POST">
            <div class="form-group">
                <label for="material-title">Título del Material</label>
                <input type="text" id="material-title" name="material-title" required>
            </div>
            <div class="form-group">
                <label for="material-description">Descripción</label>
                <textarea id="material-description" name="material-description" required></textarea>
            </div>
            <div class="form-group">
                <label for="material-value">Valor del Material</label>
                <input type="number" id="material-value" name="material-value" required>
            </div>
            <div class="form-group">
                <label for="due-date">Fecha Límite de Entrega</label>
                <input type="date" id="due-date" name="due-date" required>
            </div>
            <button type="submit" class="submit-btn">Crear Material</button>
        </form>
    </main>
</body>
</html>
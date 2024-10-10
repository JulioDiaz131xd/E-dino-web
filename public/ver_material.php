<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../core/models/manage_classes.php';

$material_id = isset($_GET['material_id']) ? intval($_GET['material_id']) : 0;

$user = new User();

// Obtener los detalles del material
$material_detalles = $user->getMaterialDetails($material_id);
if (!$material_detalles) {
    header("Location: gestionar_clase.php");
    exit();
}

$titulo_material = $material_detalles['titulo'];
$descripcion_material = $material_detalles['descripcion'];
$fecha_creacion = $material_detalles['fecha_creacion'];

$user->closeConnection();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo_material); ?> - Detalles del Material</title>
    <link rel="stylesheet" href="../assets/css/material_details.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="../assets/images/logo.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
</head>

<body>
    <header class="header">
        <h1><?php echo htmlspecialchars($titulo_material); ?></h1>
        <nav>
            <ul>
                <li><a href="gestionar_clase.php?clase_id=<?php echo $_GET['clase_id']; ?>">Volver a la Clase</a></li>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        <section class="material-details">
            <h2>Descripción del Material</h2>
            <p><?php echo htmlspecialchars($descripcion_material); ?></p>
            <p><strong>Fecha de creación:</strong> <?php echo htmlspecialchars($fecha_creacion); ?></p>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> E-Dino. Todos los derechos reservados.</p>
    </footer>

</body>

</html>

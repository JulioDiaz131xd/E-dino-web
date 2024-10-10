<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../core/models/manage_classes.php';

$usuario_id = $_SESSION['user_id'];
$rol_id = $_SESSION['rol_id'];  
$clase_id = isset($_GET['clase_id']) ? intval($_GET['clase_id']) : 0;

$user = new User();

$clase_detalles = $user->getClassDetails($clase_id);
if (!$clase_detalles) {
    header("Location: dashboard.php");
    exit();
}

$nombre_clase = $clase_detalles['nombre'];
$descripcion_clase = $clase_detalles['descripcion'];

if (!$user->isUserInClass($usuario_id, $clase_id)) {
    header("Location: dashboard.php");
    exit();
}

// Obtener miembros de clase
$miembros_clase = $user->getClassMembers($clase_id);

// Obtener materiales de clase
$materiales_clase = $user->getClassMaterials($clase_id);

$user->closeConnection();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Clase - E-Dino</title>
    <link rel="stylesheet" href="../assets/css/manage_classes.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="../assets/images/logo.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
</head>
<body>
    <header class="header">
        <h1><?php echo htmlspecialchars($nombre_clase); ?></h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Volver al Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        <section class="class-info">
            <h2>Descripción</h2>
            <p><?php echo htmlspecialchars($descripcion_clase); ?></p>
        </section>

        <section class="class-materials">
            <h2>Materiales de Clase</h2>
            <?php if (count($materiales_clase) > 0): ?>
                <ul>
                    <?php foreach ($materiales_clase as $material): ?>
                        <li>
                            <button class="material-btn" onclick="window.location.href='ver_material.php?material_id=<?php echo $material['id']; ?>&clase_id=<?php echo $clase_id; ?>'">
                                <?php echo htmlspecialchars($material['titulo']); ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No hay materiales disponibles.</p>
            <?php endif; ?>
        </section>

        <section class="class-members">
            <h2>Miembros de la Clase</h2>
            <?php if (count($miembros_clase) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($miembros_clase as $miembro): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($miembro['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($miembro['email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay miembros en esta clase.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> E-Dino. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

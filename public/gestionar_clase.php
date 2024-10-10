<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../core/models/manage_classes.php';

$usuario_id = $_SESSION['user_id'];
$rol_id = $_SESSION['rol_id'];  // Añadimos la verificación de rol
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

$miembros_clase = $user->getClassMembers($clase_id);

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

        <section class="class-actions">
            <?php if ($rol_id == 1): // Solo mostrar las acciones de creación si es maestro ?>
                <button id="create-exam-btn" class="action-btn" 
                    onclick="window.location.href='customize_exam.php?clase_id=<?php echo $clase_id; ?>'">Crear Examen</button>
                <button id="create-class-material-btn" class="action-btn">Crear Material de Clase</button>
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

        <!-- Modales -->
        <div class="modal" id="create-exam-modal">
            <div class="modal-content">
                <span class="close-btn" id="close-create-exam-modal">&times;</span>
                <h2>Crear Nuevo Examen</h2>
                <form id="create-exam-form">
                    <div class="form-group">
                        <label for="exam-name">Nombre del Examen</label>
                        <input type="text" id="exam-name" name="exam-name" required>
                    </div>
                    <div class="form-group">
                        <label for="exam-description">Descripción</label>
                        <textarea id="exam-description" name="exam-description" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Crear Examen</button>
                </form>
            </div>
        </div>

        <div class="modal" id="create-class-material-modal">
            <div class="modal-content">
                <span class="close-btn" id="close-create-class-material-modal">&times;</span>
                <h2>Crear Nuevo Material de Clase</h2>
                <form id="create-class-material-form">
                    <div class="form-group">
                        <label for="material-title">Título del Material</label>
                        <input type="text" id="material-title" name="material-title" required>
                    </div>
                    <div class="form-group">
                        <label for="material-description">Descripción</label>
                        <textarea id="material-description" name="material-description" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Crear Material</button>
                </form>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> E-Dino. Todos los derechos reservados.</p>
    </footer>

    <script src="../assets/js/gestionar_clase.js"></script>
</body>

</html>

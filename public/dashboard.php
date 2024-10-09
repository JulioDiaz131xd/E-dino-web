<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../core/models/class.php';

$usuario_id = $_SESSION['user_id'];

$user = new User();

$nombre_usuario = $user->getUserNameById($usuario_id);

$clases = $user->getUserClasses($usuario_id);

$progreso = $user->getUserClassProgress($usuario_id);

$clases_nombres_json = json_encode(array_column($progreso, 'nombre'));
$progreso_valores_json = json_encode(array_column($progreso, 'progreso'));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'crear_clase') {
    $nombre = $_POST['class-name'];
    $descripcion = $_POST['class-description'];

    $codigo = $user->createClass($usuario_id, $nombre, $descripcion);

    if ($codigo) {
        echo json_encode(['status' => 'success', 'message' => 'Clase creada exitosamente.', 'codigo' => $codigo]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al crear la clase.']);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'unirse_clase') {
    $codigo = $_POST['class-code'];
    $result = $user->joinClassByCode($usuario_id, $codigo);

    if ($result === 'success') {
        echo json_encode(['status' => 'success', 'message' => 'Te has unido a la clase exitosamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $result]);
    }
    exit();
}

$user->closeConnection();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - E-Dino</title>
    <link rel="stylesheet" href="../assets/css/dashboard-user.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <header class="dashboard-header">
        <div class="header-container">
            <h1 class="logo">
                <a href="index.php">E-Dino</a> 
            </h1>
            <nav class="nav-menu">
                <ul>
                    <li><a href="dashboard.php"><?php echo htmlspecialchars($nombre_usuario); ?></a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>


    <main class="dashboard-main">
        <section class="welcome-section">
            <h2>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?> 👋</h2>
            <p>¡Explora tus clases y sigue tu progreso!</p>
        </section>

        <section class="actions-section">
            <button class="action-btn" id="create-class-btn">Crear Clase</button>
            <button class="action-btn" id="join-class-btn">Unirse a una Clase</button>
            <button class="action-btn" id="view-classes-btn">Ver Mis Clases</button>
        </section>

        <section class="classes-section">
            <h3>Tus Clases</h3>
            <div class="classes-container">
                <?php if (!empty($clases)): ?>
                    <?php foreach ($clases as $clase): ?>
                        <div class="class-card">
                            <h4><?php echo htmlspecialchars($clase['nombre']); ?></h4>
                            <p><?php echo htmlspecialchars($clase['descripcion']); ?></p>
                            <p><strong>Código de Clase:</strong> <?php echo htmlspecialchars($clase['codigo']); ?></p>
                            <a href="gestionar_clase.php?clase_id=<?php echo $clase['id']; ?>" class="class-action-btn">Entrar</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No estás inscrito en ninguna clase aún.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="progress-section">
            <h3>Tu Progreso</h3>
            <canvas id="progressChart"></canvas>
        </section>
    </main>

    <footer class="dashboard-footer">
        <div class="footer-container">
            <p>&copy; <?php echo date("Y"); ?> E-Dino. Todos los derechos reservados.</p>
            <p>Desarrollado con ❤️ por el equipo de E-Dino.</p>
        </div>
    </footer>

    <div class="modal" id="create-class-modal">
        <div class="modal-content">
            <span class="close-btn" id="close-create-class-modal">&times;</span>
            <h2>Crear Nueva Clase</h2>
            <form id="create-class-form">
                <div class="form-group">
                    <label for="class-name">Nombre de la Clase</label>
                    <input type="text" id="class-name" name="class-name" required>
                </div>
                <div class="form-group">
                    <label for="class-description">Descripción</label>
                    <textarea id="class-description" name="class-description" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Crear Clase</button>
            </form>
        </div>
    </div>

    <!-- Modal: Unirse a Clase -->
    <div class="modal" id="join-class-modal">
        <div class="modal-content">
            <span class="close-btn" id="close-join-class-modal">&times;</span>
            <h2>Unirse a una Clase</h2>
            <form id="join-class-form">
                <div class="form-group">
                    <label for="class-code">Código de la Clase</label>
                    <input type="text" id="class-code" name="class-code" required>
                </div>
                <button type="submit" class="submit-btn">Unirse a Clase</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/dashboard.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('progressChart').getContext('2d');
            const clasesNombres = <?php echo $clases_nombres_json; ?>;
            const progresoValores = <?php echo $progreso_valores_json; ?>;
            const colores = ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#4caf50', '#ffc107', '#e91e63', '#9c27b0'];

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: clasesNombres,
                    datasets: [{
                        label: 'Progreso (%)',
                        data: progresoValores,
                        backgroundColor: colores.slice(0, progresoValores.length),
                        borderColor: colores.map(color => darkenColor(color)),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                }
            });

            function darkenColor(color) {
                let colorCopy = color.substring(1);
                let rgb = parseInt(colorCopy, 16);
                let r = (rgb >> 16) - 20;
                let g = ((rgb >> 8) & 0x00FF) - 20;
                let b = (rgb & 0x0000FF) - 20;
                return `#${(0x1000000 + (r < 255 ? r : 255) * 0x10000 + (g < 255 ? g : 255) * 0x100 + (b < 255 ? b : 255)).toString(16).slice(1)}`;
            }
        });
    </script>
</body>

</html>
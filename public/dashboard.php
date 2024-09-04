<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['user_id'];

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e_dino";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener nombre del usuario
$stmt = $conn->prepare("SELECT nombre FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nombre_usuario);
$stmt->fetch();
$stmt->close();

// Obtener clases del usuario
$stmt = $conn->prepare("SELECT c.id, c.nombre, c.descripcion
                        FROM clases c
                        JOIN clases_usuarios cu ON c.id = cu.clase_id
                        WHERE cu.usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result_clases = $stmt->get_result();
$clases = [];
while ($row = $result_clases->fetch_assoc()) {
    $clases[] = $row;
}
$stmt->close();


// Crear una clase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'crear_clase') {
    $nombre = $_POST['class-name'];
    $descripcion = $_POST['class-description'];
    $codigo = substr(md5(uniqid(mt_rand(), true)), 0, 8); // Genera un código único
    $creador_id = $usuario_id; // Usar el ID del usuario que está en la sesión

    $stmt = $conn->prepare("INSERT INTO clases (nombre, descripcion, codigo, creador_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nombre, $descripcion, $codigo, $creador_id);

    if ($stmt->execute()) {
        $clase_id = $stmt->insert_id;
        // Unir al creador a la clase
        $stmt = $conn->prepare("INSERT INTO clases_usuarios (usuario_id, clase_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $usuario_id, $clase_id);
        $stmt->execute();
        
        echo json_encode(['status' => 'success', 'message' => 'Clase creada exitosamente.', 'codigo' => $codigo]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al crear la clase.']);
    }

    exit();
}

// Unirse a una clase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'unirse_clase') {
    $codigo = $_POST['class-code'];

    // Buscar la clase por código
    $stmt = $conn->prepare("SELECT id FROM clases WHERE codigo = ?");
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($clase_id);
        $stmt->fetch();

        // Verificar si el usuario ya está inscrito en la clase
        $stmt = $conn->prepare("SELECT id FROM clases_usuarios WHERE usuario_id = ? AND clase_id = ?");
        $stmt->bind_param("ii", $usuario_id, $clase_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            // Unir al usuario a la clase
            $stmt = $conn->prepare("INSERT INTO clases_usuarios (usuario_id, clase_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $usuario_id, $clase_id);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Te has unido a la clase exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al unirse a la clase.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ya estás inscrito en esta clase.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Código de clase no encontrado.']);
    }

    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - E-Dino</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Header -->
    <header class="dashboard-header">
        <div class="header-container">
            <h1 class="logo">E-Dino</h1>
            <nav class="nav-menu">
                <ul>
                    <li><a href="#"><?php echo htmlspecialchars($nombre_usuario); ?></a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
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

                            <button class="class-action-btn" onclick="enterClass(<?php echo $clase['id']; ?>)">Entrar</button>
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

    <!-- Footer -->
    <footer class="dashboard-footer">
        <div class="footer-container">
            <p>&copy; <?php echo date("Y"); ?> E-Dino. Todos los derechos reservados.</p>
            <p>Desarrollado con ❤️ por el equipo de E-Dino.</p>
        </div>
    </footer>

    <!-- Modales -->
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

    <!-- Scripts -->
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>

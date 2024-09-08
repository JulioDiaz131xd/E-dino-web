<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['user_id'];
$clase_id = isset($_GET['clase_id']) ? intval($_GET['clase_id']) : 0;

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e_dino";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener detalles de la clase
$stmt = $conn->prepare("SELECT nombre, descripcion FROM clases WHERE id = ?");
$stmt->bind_param("i", $clase_id);
$stmt->execute();
$stmt->bind_result($nombre_clase, $descripcion_clase);
$stmt->fetch();
$stmt->close();

// Verificar si el usuario está inscrito en la clase
$stmt = $conn->prepare("SELECT id FROM clases_usuarios WHERE usuario_id = ? AND clase_id = ?");
$stmt->bind_param("ii", $usuario_id, $clase_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    header("Location: dashboard.php");
    exit();
}
$stmt->close();

// Manejo de la creación de exámenes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'create_exam') {
        $exam_name = $_POST['exam-name'];
        $exam_description = $_POST['exam-description'];

        $stmt = $conn->prepare("INSERT INTO examenes (nombre, descripcion, clase_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $exam_name, $exam_description, $clase_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
    }

    if ($_POST['action'] === 'create_material') {
        $material_title = $_POST['material-title'];
        $material_description = $_POST['material-description'];

        $stmt = $conn->prepare("INSERT INTO materiales_clase (titulo, descripcion, clase_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $material_title, $material_description, $clase_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Clase - E-Dino</title>
    <link rel="stylesheet" href="../assets/css/gestionar_clase.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
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

        <!-- Parte del HTML en gestionar_clase.php -->
        <section class="class-actions">
            <button id="create-exam-btn" class="action-btn"
                onclick="window.location.href='customize_exam.php?clase_id=<?php echo $clase_id; ?>'">Crear
                Examen</button>
            <button id="create-class-material-btn" class="action-btn">Crear Material de Clase</button>
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
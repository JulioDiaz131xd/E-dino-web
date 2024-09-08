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

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personalizar Examen - E-Dino</title>
    <link rel="stylesheet" href="../assets/css/gestionar_clase.css">
    <link rel="stylesheet" href="../assets/css/customize_exam.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
</head>
<body>
    <header class="header">
        <h1><?php echo htmlspecialchars($nombre_clase); ?></h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Volver al Dashboard</a></li>
                <li><a href="gestionar_clase.php?clase_id=<?php echo $clase_id; ?>">Volver a Gestionar Clase</a></li>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        <section class="class-info">
            <h2>Personalizar Examen</h2>
            <p>En esta página podrás personalizar los exámenes para la clase.</p>
            <form id="examForm" method="POST" action="">
                <input type="hidden" name="classId" value="<?php echo htmlspecialchars($clase_id); ?>">
                <div id="questionsContainer" style="display: none;">
                    <!-- Aquí se agregarán las preguntas -->
                </div>
                <button type="button" id="createExam" class="button">Crear Examen</button>
                <button type="button" id="createExamAI" class="button">Crear Examen por IA</button>
                <button type="button" id="addMoreQuestions" class="button" style="display: none;">Agregar Más Preguntas</button>
            </form>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> E-Dino. Todos los derechos reservados.</p>
    </footer>

    <script src="../assets/js/customize_exam.js"></script>
</body>
</html>
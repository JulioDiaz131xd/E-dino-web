<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../core/models/manage_classes.php';

$usuario_id = $_SESSION['user_id'];
$clase_id = isset($_GET['clase_id']) ? intval($_GET['clase_id']) : 0;

$user = new User();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $material_title = $_POST['material-title'];
    $material_description = $_POST['material-description'];

    $result = $user->createClassMaterial($material_title, $material_description, $clase_id);
    if ($result) {
        header("Location: gestionar_clase.php?clase_id=$clase_id");
        exit();
    } else {
        $error = "Error al crear el material.";
    }
}

$user->closeConnection();
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
        <form action="" method="POST">
            <div>
                <label for="material-title">Título del Material:</label>
                <input type="text" id="material-title" name="material-title" required>
            </div>
            <div>
                <label for="material-description">Descripción:</label>
                <textarea id="material-description" name="material-description" required></textarea>
            </div>
            <button type="submit">Crear Material</button>
        </form>
        <?php if (isset($error)): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> E-Dino. Todos los derechos reservados.</p>
    </footer>
</body>

</html>

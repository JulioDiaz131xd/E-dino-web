<?php
session_start();

// Conexión a la base de datos (asegúrate de cambiar los parámetros por los tuyos)
$servername = "localhost";
$username = "tu_usuario";
$password = "tu_contraseña";
$dbname = "tu_base_de_datos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Validar entrada
    if (!empty($nombre) && !empty($email) && !empty($password)) {
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nombre, $email, $password);

        if ($stmt->execute()) {
            // Obtener el ID del usuario recién registrado
            $user_id = $stmt->insert_id;

            // Iniciar sesión automáticamente
            $_SESSION['user_id'] = $user_id;
            $_SESSION['nombre'] = $nombre;

            // Redirigir a index.php
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, completa todos los campos.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - E-Dino</title>
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="register-main">
        <div class="register-container">
            <h2>Regístrate</h2>
            <form class="register-form" method="POST" action="">
                <div class="input-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="input-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="register-btn">Registrarse</button>
            </form>
            <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

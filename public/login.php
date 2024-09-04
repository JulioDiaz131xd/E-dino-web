<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "e_dino";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener y sanitizar los datos del formulario
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = trim($_POST['password']);

    // Verificar si el correo existe en la base de datos
    $stmt = $conn->prepare("SELECT id, nombre, password, rol_id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $nombre, $hashed_password, $rol_id);
    $stmt->fetch();
    $stmt->close();

    if ($user_id && password_verify($password, $hashed_password)) {
        // Iniciar sesión
        $_SESSION['user_id'] = $user_id;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['rol_id'] = $rol_id;

        // Redirigir al usuario a la página de inicio
        header("Location: index.php");
        exit();
    } else {
        $error = "Correo electrónico o contraseña incorrectos.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - E-Dino</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <main class="login-main">
        <div class="login-container">
            <h2>Iniciar sesión</h2>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form class="login-form" method="POST" action="">
                <div class="input-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="login-btn">Iniciar sesión</button>
            </form>
            <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>.</p>
        </div>
    </main>
    <script src="../assets/js/validation.js"></script>
</body>
</html>

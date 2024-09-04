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
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $rol = (int)$_POST['rol'];

    // Verificar si el correo ya está registrado
    $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $error = "El correo electrónico ya está registrado.";
    } else {
        // Insertar el nuevo usuario en la base de datos
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nombre, $email, $password, $rol);

        if ($stmt->execute()) {
            // Iniciar sesión automáticamente
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['rol_id'] = $rol;

            // Redirigir al usuario a la página de inicio
            header("Location: index.php");
            exit();
        } else {
            $error = "Hubo un problema al registrarte. Por favor, inténtalo de nuevo.";
        }

        $stmt->close();
    }

    $conn->close();
}
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
    <main class="register-main">
        <div class="register-container">
            <h2>Regístrate</h2>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form class="register-form" id="register-form" method="POST" action="">
                <div class="input-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required>
                    <div class="error-message" id="name-error"></div>
                </div>
                <div class="input-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" required>
                    <div class="error-message" id="email-error"></div>
                </div>
                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                    <div class="error-message" id="password-error"></div>
                </div>
                <div class="role-options">
                    <input type="radio" id="role-alumno" name="rol" value="2" class="role-input" required>
                    <label for="role-alumno" class="role-card">
                        <div class="role-icon">🎓</div>
                        <div class="role-name">Alumno</div>
                    </label>

                    <input type="radio" id="role-maestro" name="rol" value="1" class="role-input" required>
                    <label for="role-maestro" class="role-card">
                        <div class="role-icon">📘</div>
                        <div class="role-name">Maestro</div>
                    </label>
                </div>
                <button type="submit" class="register-btn">Registrarse</button>
                <div class="error-message" id="form-error"></div>
            </form>
            <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
        </div>
    </main>
    <script src="../assets/js/validation.js"></script>
</body>
</html>

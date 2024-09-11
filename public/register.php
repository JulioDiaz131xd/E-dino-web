<?php
session_start();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crear instancia de la clase User
    $user = new User();

    // Obtener y sanitizar los datos del formulario
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $rol = (int)$_POST['rol'];

    // Verificar si el correo ya está registrado
    if ($user->emailExists($email)) {
        $error = "El correo electrónico ya está registrado.";
    } else {
        // Crear nuevo usuario
        $userId = $user->createUser($nombre, $email, $password, $rol);

        if ($userId) {
            // Iniciar sesión automáticamente
            $_SESSION['user_id'] = $userId;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['rol_id'] = $rol;

            // Redirigir al usuario a la página de inicio
            header("Location: index.php");
            exit();
        } else {
            $error = "Hubo un problema al registrarte. Por favor, inténtalo de nuevo.";
        }
    }

    // Cerrar la conexión a la base de datos
    $user->closeConnection();
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

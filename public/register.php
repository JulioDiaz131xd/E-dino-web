<?php
session_start();
require_once __DIR__ . '/../core/models/User.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = new User();

        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $rol_id = (int)$_POST['rol'];

        // Validaciones
        if (empty($nombre) || empty($email) || empty($password) || !in_array($rol_id, [1, 2])) {
            $error = "Todos los campos son obligatorios.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Formato de correo electrónico no válido.";
        } elseif (strlen($password) < 6) {
            $error = "La contraseña debe tener al menos 6 caracteres.";
        } elseif ($user->emailExists($email)) {
            $error = "El correo electrónico ya está registrado.";
        } else {
            $userId = $user->createUser($nombre, $email, $password, $rol_id);

            if ($userId) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['rol_id'] = $rol_id;

                header("Location: /index.php");
                exit();
            } else {
                $error = "Hubo un problema al registrarte. Por favor, inténtalo de nuevo.";
            }
        }

        $user->closeConnection();
    }
} catch (Exception $e) {
    $error = "Error: " . $e->getMessage(); // Capturar errores en caso de excepción
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - E-Dino</title>
    <link rel="stylesheet" href="../assets/css/register.css">
    <link rel="icon" href="../assets/images/logo.ico">
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <main class="register-main">
        <div class="register-container">
            <h2>Regístrate</h2>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
<form class="register-form" id="register-form" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

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

    <!-- Botones para seleccionar rol -->
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

    <?php include '../includes/footer.php'; ?>
</body>
</html>

<?php
session_start();

require_once __DIR__ . '/../core/models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $loggedInUser = $user->login($email, $password);

    if ($loggedInUser) {
        $_SESSION['user_id'] = $loggedInUser['id'];
        $_SESSION['nombre'] = $loggedInUser['nombre'];
        $_SESSION['rol_id'] = $loggedInUser['rol_id'];

        header("Location: index.php");
        exit();
    } else {
        $error = "Correo electrónico o contraseña incorrectos.";
    }

    $user->closeConnection();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Dino</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <main class="login-main">
        <div class="login-container">
            <h2>Iniciar Sesión</h2>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="input-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="login-btn">Iniciar Sesión</button>
            </form>
            <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>.</p>
        </div>
    </main>
</body>
</html>

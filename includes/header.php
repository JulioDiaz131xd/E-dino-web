<!-- includes/header.php -->
<header>
    <div class="header-container">
        <div class="logo">E-Dino</div>
        <div class="header-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="logout-info">
                    <form action="logout.php" method="post">
                        <button type="submit" id="logout-btn">Cerrar sesión</button>
                    </form>
                    <div class="user-info">
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                        <span class="user-role">(<?php echo htmlspecialchars($_SESSION['tipo_cuenta']); ?>)</span>
                    </div>
                </div>
            <?php else: ?>
                <button id="register-btn" onclick="window.location.href='register.php'">Registrarse</button>
                <button id="login-btn" onclick="window.location.href='login.php'">Iniciar sesión</button>
            <?php endif; ?>
        </div>
    </div>
</header>

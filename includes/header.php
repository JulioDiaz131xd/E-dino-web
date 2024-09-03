<header class="header">
    <div class="header-container">
        <a href="index.php" class="logo">E-Dino</a>
        <div class="header-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="logout.php" method="post" class="logout-form">
                    <button type="submit" class="header-btn">Cerrar sesión</button>
                </form>
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                </div>
            <?php else: ?>
                <button onclick="window.location.href='login.php'" class="header-btn">Iniciar sesión</button>
                <button onclick="window.location.href='register.php'" class="header-btn">Registrarse</button>
            <?php endif; ?>
        </div>
    </div>
</header>

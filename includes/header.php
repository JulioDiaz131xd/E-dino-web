<header class="header">
    <div class="header-container">
        <a href="/../index.php" class="logo">E-Dino</a>
        <div class="header-buttons">
            <?php
            $currentPage = basename($_SERVER['PHP_SELF']);
            if (!in_array($currentPage, ['login.php', 'register.php'])):
                if (isset($_SESSION['user_id'])): ?>
                    <form action="logout.php" method="post" class="logout-form">
                        <button type="submit" class="header-btn">Cerrar sesion</button>
                    </form>
                    <div class="user-info"
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                    </div>
                <?php else: ?>
                    <button onclick="window.location.href='/../public/login.php'" class="header-btn">Iniciar sesion</button>
                    <button onclick="window.location.href='/../public/register.php'" class="header-btn">Registrarse</button>
                <?php endif; 
            endif; ?>
        </div>
    </div>
</header>

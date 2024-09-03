?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="E-Dino es una plataforma de aprendizaje personalizada. Aprende a tu ritmo con nuestros planes de estudio flexibles.">
    <title>E-Dino</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="icon" href="../assets/images/logo.ico">
    <script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.21/build/spline-viewer.js"></script>
</head>
<body>
    <?php
    include '../includes/header.php';
    include '../includes/navbar.php';
    ?>
    <main>
        <section class="hero fade-in">
            <h1>E-Dino</h1>
            <p>Aprende a tu modo... A tu ritmo.</p>
            <button id="hero-register-btn" onclick="window.location.href='register.php'">Regístrate</button>
        </section>
        <section class="hero fade-int">
            <script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.21/build/spline-viewer.js"></script>
            <spline-viewer url="https://prod.spline.design/iqY31g5XVr8nmtia/scene.splinecode"></spline-viewer>
        </section>
        <section class="hero fullscreen">
            <img src="../assets/images/img2.svg" alt="Descripción de la imagen">
            <h1>Sumate a Nosotros</h1>
            <p>y se parte del cambio mas significativo de la educacion.</p>
        </section>
        <section class="hero fade-ints">
        <script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.21/build/spline-viewer.js"></script>
        <spline-viewer url="https://prod.spline.design/AhMmmMlzKudqGwu0/scene.splinecode"></spline-viewer>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/scripts.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.fade-in').forEach(section => {
                observer.observe(section);
            });
        });
    </script>
</body>
</html>
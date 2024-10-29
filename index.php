<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-Dino es una plataforma de aprendizaje personalizada. Aprende a tu ritmo con nuestros planes de estudio flexibles.">
    <title>E-Dino</title>
    <link rel="stylesheet" href="./assets/css/style-index.css">
    <link rel="icon" href="./assets/images/logo.ico">
    <script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.21/build/spline-viewer.js"></script>
</head>
<body>
    <?php
    include './includes/header.php';
    include './includes/navbar.php';
    ?>

    <main>
        <!-- Sección Hero -->
        <section class="hero fade-in-scroll">
            <h1>E-Dino</h1>
            <p>Aprende a tu modo... A tu ritmo.</p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <button id="hero-classes-btn" onclick="window.location.href='./public/dashboard.php'">Ir a mis clases</button>
            <?php else: ?>
                <button id="hero-register-btn" onclick="window.location.href='./public/register.php'">Regístrate</button>
            <?php endif; ?>
        </section>

        <!-- Sección Beneficios -->
        <section class="benefits slide-left-scroll">
            <h2>Beneficios de E-Dino</h2>
            <div class="benefit-item">
                <img src="./assets/images/benefit1.svg" alt="Beneficio 1">
                <h3>Estudio Flexible</h3>
                <p>Accede a los cursos y programas en cualquier momento, desde cualquier lugar.</p>
            </div>
            <div class="benefit-item">
                <img src="./assets/images/benefit2.svg" alt="Beneficio 2">
                <h3>Soporte 24/7</h3>
                <p>Siempre tendrás asistencia, ya sea técnica o académica, en cualquier momento.</p>
            </div>
        </section>

        <!-- Sección Testimonios -->
        <section class="testimonials zoom-in-scroll">
            <h2>Lo que dicen nuestros usuarios</h2>
            <div class="testimonial-item">
                <img src="./assets/images/user1.jpg" alt="Usuario 1">
                <blockquote>“E-Dino cambió mi forma de aprender. Puedo ajustar mi horario de estudio y avanzar a mi propio ritmo.”</blockquote>
                <h4>Juan Pérez</h4>
                <span>Estudiante</span>
            </div>
            <div class="testimonial-item">
                <img src="./assets/images/user2.jpg" alt="Usuario 2">
                <blockquote>“La plataforma es intuitiva y fácil de usar. Estoy muy satisfecho con el contenido y la forma en que se presenta.”</blockquote>
                <h4>María López</h4>
                <span>Docente</span>
            </div>
        </section>

        <!-- Sección Call to Action -->
        <section class="cta slide-right-scroll">
            <h2>Únete hoy</h2>
            <p>No pierdas la oportunidad de ser parte de la revolución educativa. ¡Inscríbete ahora!</p>
            <button onclick="window.location.href='./public/register.php'">Empezar</button>
        </section>
    </main>

    <?php include './includes/footer.php'; ?>

    <!-- Animaciones de Scroll y JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observerOptions = {
                threshold: 0.2,
            };

            const scrollObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    } else {
                        entry.target.classList.remove('visible');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.fade-in-scroll, .slide-left-scroll, .slide-right-scroll, .zoom-in-scroll').forEach(section => {
                scrollObserver.observe(section);
            });

            const header = document.querySelector('header');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 100) {
                    header.classList.add('sticky');
                } else {
                    header.classList.remove('sticky');
                }
            });

            // Smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
</body>
</html>

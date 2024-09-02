document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 }); // El 10% del elemento debe estar visible para activar la animación

    document.querySelectorAll('.fade-in').forEach(section => {
        observer.observe(section);
    });
});

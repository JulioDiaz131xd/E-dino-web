document.addEventListener("DOMContentLoaded", function() {
    const inputs = document.querySelectorAll(".input-group input");

    inputs.forEach(input => {
        input.addEventListener("focus", () => {
            input.parentElement.querySelector("label").style.opacity = "1";
        });

        input.addEventListener("blur", () => {
            if (input.value === "") {
                input.parentElement.querySelector("label").style.opacity = "0.8";
            }
        });
    });

    window.addEventListener("scroll", function() {
        const registerContainer = document.querySelector(".register-container");
        registerContainer.style.transform = `translateY(${window.scrollY * 0.1}px)`;
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('register-form');
    const emailInput = document.getElementById('email');
    const nameInput = document.getElementById('nombre');
    const passwordInput = document.getElementById('password');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        clearErrors();

        let hasErrors = false;

        if (nameInput.value.trim() === '') {
            showError('name-error', 'El nombre es obligatorio.');
            hasErrors = true;
        }

        if (!validateEmail(emailInput.value)) {
            showError('email-error', 'El correo electrónico no es válido.');
            hasErrors = true;
        } else {
            const emailExists = await checkEmailExists(emailInput.value);
            if (emailExists) {
                showError('email-error', 'El correo electrónico ya está registrado.');
                hasErrors = true;
            }
        }

        if (passwordInput.value.length < 6) {
            showError('password-error', 'La contraseña debe tener al menos 6 caracteres.');
            hasErrors = true;
        }

        if (hasErrors) {
            return;
        }

        form.submit();
    });

    function showError(elementId, message) {
        document.getElementById(elementId).textContent = message;
    }

    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(elem => elem.textContent = '');
    }

    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    async function checkEmailExists(email) {
        const response = await fetch('../assets/php/check_email.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email }),
        });

        const result = await response.json();
        return result.exists;
    }
});

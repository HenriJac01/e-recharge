document.addEventListener('DOMContentLoaded', function () {
    // Password Visibility Toggle
    const togglePasswordVisibility = (e) => {
        const input = e.target.previousElementSibling;
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        e.target.classList.toggle('fa-eye');
        e.target.classList.toggle('fa-eye-slash');
    };

    // Email Validation
    const validateEmail = (e) => {
        const email = e.target;
        const emailWrapper = email.closest('.input-icon-wrapper');
        const isValid = email.validity.valid;

        emailWrapper.classList.toggle('valid', isValid);
        emailWrapper.classList.toggle('invalid', !isValid && email.value.length > 0);
    };

    // Password Strength Checker
    const checkPasswordStrength = (password) => {
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password),
            special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        };

        const strength = Object.values(requirements).filter(Boolean).length;
        const passwordStrengthBar = document.querySelector('.strength-progress');
        const strengthLabel = document.querySelector('.strength-label');

        // Update visual requirements
        Object.entries(requirements).forEach(([key, isValid]) => {
            document.getElementById(key).classList.toggle('valid', isValid);
        });

        // Update strength bar
        const width = (strength / 5) * 100;
        passwordStrengthBar.style.width = `${width}%`;

        // Update strength label and color
        if (strength === 5) {
            passwordStrengthBar.style.backgroundColor = 'green';
            strengthLabel.textContent = 'Mot de passe très fort';
        } else if (strength >= 3) {
            passwordStrengthBar.style.backgroundColor = 'yellow';
            strengthLabel.textContent = 'Mot de passe moyen';
        } else {
            passwordStrengthBar.style.backgroundColor = 'red';
            strengthLabel.textContent = 'Mot de passe faible';
        }

        return strength === 5;
    };

    // Phone Number Validation
    const validatePhoneNumber = (phoneInput) => {
        const phonePattern = /^0\d{2} \d{2} \d{3} \d{2}$/;
        return phonePattern.test(phoneInput.value);
    };

    // Password Confirmation Validation
    const validateConfirmPassword = (e) => {
        const confirmPassword = e.target;
        const password = document.getElementById('password').value;
        const confirmPasswordWrapper = confirmPassword.closest('.input-icon-wrapper');
        const isValid = confirmPassword.value === password;

        confirmPasswordWrapper.classList.toggle('valid', isValid);
        confirmPasswordWrapper.classList.toggle('invalid', !isValid && confirmPassword.value.length > 0);
    };

    // Form Validation Handler
    const handleFormValidation = (e) => {
        const form = e.target;
        const phoneInput = document.getElementById('phone_number');
        const termsCheckbox = document.getElementById('terms');

        // Validate phone number
        if (!validatePhoneNumber(phoneInput)) {
            e.preventDefault();
            alert('Veuillez entrer un numéro de téléphone valide (0XX XX XXX XX)');
            return;
        }

        // Check terms checkbox
        if (!termsCheckbox.checked) {
            e.preventDefault();
            alert('Veuillez accepter les conditions d\'utilisation');
            return;
        }
    };

    // Attach Event Listeners
    const attachEvents = () => {
        // Password visibility toggle
        document.body.addEventListener('click', (e) => {
            if (e.target && e.target.classList.contains('toggle-password')) {
                togglePasswordVisibility(e);
            }
        });

        // Email validation
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('input', validateEmail);
        }

        // Password strength checking
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', (e) => {
                checkPasswordStrength(e.target.value);
            });
        }

        // Phone number formatting
        const phoneInput = document.getElementById('phone_number');
        if (phoneInput) {
            phoneInput.addEventListener('input', () => {
                phoneInput.value = phoneInput.value.replace(/[^0-9 ]/g, '');
            });
        }

        // Password confirmation validation
        const confirmPasswordInput = document.getElementById('password_confirmation');
        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', validateConfirmPassword);
        }

        // Terms checkbox
        const termsCheckbox = document.getElementById('terms');
        const registerButton = document.getElementById('registerButton');
        if (termsCheckbox && registerButton) {
            termsCheckbox.addEventListener('change', () => {
                registerButton.disabled = !termsCheckbox.checked;
            });
        }

        // Form submission validation
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', handleFormValidation);
        }
    };

    // Initialize events
    attachEvents();
});

const loginForm = document.getElementById('loginForm');

loginForm.addEventListener('submit', function (event) {
    event.preventDefault(); // Empêche la soumission par défaut

    // Récupération des données du formulaire
    const formData = new FormData(loginForm);

    // Envoi des données au backend via Fetch API
    fetch(loginForm.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-Token': document.querySelector('input[name="_token"]').value,
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Connexion réussie',
                    text: data.message || 'Vous êtes maintenant connecté!',
                    timer: 5000,
                    timerProgressBar: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    toast: true
                }).then(() => {
                    window.location.href = data.redirect; // Redirection selon la réponse backend
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: data.message || 'Identifiants incorrects.',
                    timer: 5000,
                    timerProgressBar: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    toast: true
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Erreur réseau',
                text: 'Une erreur est survenue. Veuillez réessayer.',
                timer: 5000,
                timerProgressBar: true,
                position: 'top-end',
                showConfirmButton: false,
                toast: true
            });
        });

});




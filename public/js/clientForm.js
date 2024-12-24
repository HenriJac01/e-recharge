document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('customerForm');
    const inputs = form.querySelectorAll('input');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const nextBtn = document.querySelector('.btn-next');
    const prevBtn = document.querySelector('.btn-prev');
    const steps = document.querySelectorAll('.step');

    function formatPhoneNumber(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 10) {
            value = value.slice(0, 10);
        }
        input.value = value;
    }

    function validateStep1() {
        const step1Inputs = step1.querySelectorAll('input[required]');
        let isValid = true;

        step1Inputs.forEach(input => {
            if (!input.value || (input.pattern && !input.value.match(new RegExp(input.pattern)))) {
                isValid = false;
                input.classList.add('error');

                let errorDiv = input.nextElementSibling;
                if (!errorDiv || !errorDiv.classList.contains('error-message')) {
                    errorDiv = document.createElement('div');
                    errorDiv.classList.add('error-message');
                    input.parentNode.insertBefore(errorDiv, input.nextSibling);
                }
                errorDiv.textContent = input.dataset.error || 'Ce champ est requis';
            }
        });

        return isValid;
    }

    nextBtn.addEventListener('click', function () {
        if (validateStep1()) {
            step1.style.display = 'none';
            step2.style.display = 'block';
        }
    });

    prevBtn.addEventListener('click', function () {
        step2.style.display = 'none';
        step1.style.display = 'block';
    });

    inputs.forEach(input => {
        input.addEventListener('input', function () {
            if (input.id === 'phone_number') {
                formatPhoneNumber(input);
            }

            if (input.pattern && !input.value.match(new RegExp(input.pattern))) {
                input.classList.add('error');

                let errorDiv = input.nextElementSibling;
                if (!errorDiv || !errorDiv.classList.contains('error-message')) {
                    errorDiv = document.createElement('div');
                    errorDiv.classList.add('error-message');
                    input.parentNode.insertBefore(errorDiv, input.nextSibling);
                }
                errorDiv.textContent = input.dataset.error || 'Format invalide';
            } else {
                input.classList.remove('error');
                const errorDiv = input.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('error-message')) {
                    errorDiv.remove();
                }
            }
        });
    });

    // Configuration de base des notifications
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        timer: 2500,
        timerProgressBar: true,
        showConfirmButton: false,
        customClass: {
            popup: 'swal-popup-small'
        }
    });

    // Fonction pour afficher les notifications
    const showNotification = (type, message) => {
        const config = {
            success: {
                icon: 'success',
                iconColor: '#4BB543',
                background: '#f1f9f5',
            },
            error: {
                icon: 'error',
                iconColor: '#FF4136',
                background: '#f8d7da',
            }
        };

        return Toast.fire({
            title: message,
            ...config[type]
        });
    };

    // Gestion du formulaire
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Validation du formulaire
        let isValid = true;
        inputs.forEach(input => {
            if (input.required && !input.value ||
                (input.pattern && !input.value.match(new RegExp(input.pattern)))) {
                isValid = false;
                input.classList.add('error');
            }
        });

        if (!isValid) {
            showNotification('error', 'Veuillez remplir tous les champs requis correctement.');
            return;
        }

        const submitBtn = form.querySelector('.btn-submit');
        submitBtn.disabled = true;

        // Envoi des données
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', data.message).then(() => {
                        window.location.href = '/yas/tables/client';
                    });
                } else {
                    throw new Error(data.message || 'Erreur lors de l\'enregistrement');
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                showNotification('error', error.message);
            });
    });


});

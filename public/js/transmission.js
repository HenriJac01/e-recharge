document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('customerForm');
    const inputs = form.querySelectorAll('input');

    // Fonction pour formater le nif
    function formatNifNumber(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 10) {
            value = value.slice(0, 10);
        }
        input.value = value;
    }

    // Validation des champs
    inputs.forEach(input => {
        const errorMessage = input.getAttribute('data-error');

        input.addEventListener('input', function () {
            // Formatage spécial pour le téléphone
            if (input.id === 'nif') {
                formatNifNumber(input);
            }

            // Validation du pattern
            if (input.pattern && !input.value.match(new RegExp(input.pattern))) {
                input.classList.add('error');

                // Afficher le message d'erreur
                let errorDiv = input.nextElementSibling;
                if (!errorDiv || !errorDiv.classList.contains('error-message')) {
                    errorDiv = document.createElement('div');
                    errorDiv.classList.add('error-message');
                    input.parentNode.insertBefore(errorDiv, input.nextSibling);
                }
                errorDiv.textContent = errorMessage || 'Format invalide';
            } else {
                input.classList.remove('error');
                const errorDiv = input.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('error-message')) {
                    errorDiv.remove();
                }
            }
        });
    });

});

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
                    window.location.href = '/yas/tables/stock';
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


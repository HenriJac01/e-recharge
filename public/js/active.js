document.querySelectorAll('.side_menu li.has-dropdown > a').forEach(item => {
    item.addEventListener('click', function () {
        const parentLi = item.closest('li');

        // Toggle active state for the clicked item
        parentLi.classList.toggle('active');

        // Close other dropdowns when one is clicked
        document.querySelectorAll('.side_menu li.has-dropdown').forEach(otherItem => {
            if (otherItem !== parentLi) {
                otherItem.classList.remove('active');
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Sélectionner tous les liens de menu avec sous-menu
    const menuItems = document.querySelectorAll('.menu-toggle');

    menuItems.forEach(item => {
        item.addEventListener('click', function (event) {
            const parentLi = this.closest('li');  // L'élément <li> contenant le menu
            const subMenu = parentLi.querySelector('.side_dropdown'); // Le sous-menu correspondant

            // Si le sous-menu est déjà affiché, on le cache
            if (subMenu.style.display === 'block') {
                subMenu.style.display = 'none';
                parentLi.classList.remove('active'); // Retirer la classe active de <li>
            } else {
                // Sinon, on affiche le sous-menu et on ajoute la classe active
                subMenu.style.display = 'block';
                parentLi.classList.add('active');
            }
        });
    });

    // Si vous souhaitez ajouter une classe active à l'élément de menu lorsque vous cliquez
    const links = document.querySelectorAll('.side_menu li a');

    links.forEach(link => {
        link.addEventListener('click', function () {
            // Supprimer 'active' de tous les autres liens
            links.forEach(link => link.classList.remove('active'));

            // Ajouter la classe 'active' au lien sélectionné
            this.classList.add('active');
        });
    });
});



//sidebar collapse
var allDropdown = document.querySelectorAll('.dropdown');
const toggleSidebar = document.querySelector('nav .toggle_sidebar ');
const allSideDivider = document.querySelectorAll('#sidebar .divider');

if (sidebar.classList.contains('hide')) {
    allSideDivider.forEach(item => {
        item.textContent = '-'
    })
    allDropdown.forEach(item => {
        const a = item.parentElement.querySelector('a:first-child');
        a.classList.remove('active');
        item.classList.remove('show');
    });
}
else {
    allSideDivider.forEach(item => {
        item.textContent = item.dataset.text;
    })
}
toggleSidebar.addEventListener('click', function () {
    sidebar.classList.toggle('hide');
    if (sidebar.classList.contains('hide')) {
        allSideDivider.forEach(item => {
            item.textContent = '-'
        })
        allDropdown.forEach(item => {
            const a = item.parentElement.querySelector('a:first-child');
            a.classList.remove('active');
            item.classList.remove('show');
        });
    }
    else {
        allSideDivider.forEach(item => {
            item.textContent = item.dataset.text;
        })
    }
});

sidebar.addEventListener('mouseleave', function () {
    if (this.classList.contains('hide')) {

    }
    allDropdown.forEach(item => {
        const a = item.parentElement.querySelector('a:first-child');
        a.classList.remove('active');
        item.classList.remove('show');
    });
    allSideDivider.forEach(item => {
        item.textContent = '-'
    })

});

sidebar.addEventListener('mouseenter', function () {
    if (this.classList.contains('hide')) {

    }
    allDropdown.forEach(item => {
        const a = item.parentElement.querySelector('a:first-child');
        a.classList.remove('active');
        item.classList.remove('show');
    });
    allSideDivider.forEach(item => {
        item.textContent = item.dataset.text;
    })


});

//active pour le profile dropdown
const profile = document.querySelector('nav .profile');
const imgProfile = profile.querySelector('img');
const dropProfile = profile.querySelector('.profile_link');

imgProfile.addEventListener('click', function () {
    dropProfile.classList.toggle('show');
});

//click quelque part
window.addEventListener('click', function (e) {
    if (e.target !== imgProfile) {
        if (e.target !== dropProfile) {
            if (dropProfile.classList.contains('show')) {
                dropProfile.classList.remove('show');
            }
        }
    }
});

//bar processing 
const prossing = document.querySelectorAll('main .card .process')

prossing.forEach(item => {
    item.style.setProperty('--value', item.dataset.value)
});




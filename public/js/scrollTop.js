document.addEventListener('DOMContentLoaded', function() {
    const scrollTopBtn = document.getElementById('scrollTopBtn');
    const progressCircle = document.querySelector('.progress-ring-circle');
    let circumference;

    function updateCircumference() {
        // Ajuster le rayon en fonction de la taille de l'écran
        let radius = 20; // rayon par défaut
        if (window.innerWidth <= 480) {
            radius = 16;
        } else if (window.innerWidth <= 768) {
            radius = 18;
        }
        
        circumference = 2 * Math.PI * radius;
        progressCircle.style.strokeDasharray = `${circumference} ${circumference}`;
        progressCircle.style.strokeDashoffset = circumference;
    }

    function setProgress(percent) {
        const offset = circumference - (percent / 100 * circumference);
        progressCircle.style.strokeDashoffset = offset;
    }

    function handleScroll() {
        // Calculer la progression
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight - windowHeight;
        const scrolled = (window.pageYOffset / documentHeight) * 100;
        
        // Mettre à jour le cercle de progression
        setProgress(Math.min(Math.max(scrolled, 0), 100));

        // Afficher/masquer le bouton
        if (window.pageYOffset > 300) {
            scrollTopBtn.classList.add('visible');
        } else {
            scrollTopBtn.classList.remove('visible');
        }
    }

    // Gérer le défilement
    window.addEventListener('scroll', handleScroll, { passive: true });

    // Animation de défilement fluide
    scrollTopBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

        // Animation du bouton
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = '';
        }, 200);
    });

    // Initialisation
    updateCircumference();
    handleScroll();

    // Gérer le redimensionnement
    window.addEventListener('resize', function() {
        updateCircumference();
        handleScroll();
    }, { passive: true });
});

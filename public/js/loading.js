// Afficher l'animation de loading
function showLoading() {
    document.getElementById('loadingOverlay').classList.add('show');
}
// Déclencher l'animation de loading avant que la soumission du formulaire commence
document.getElementById('loginForm').addEventListener('submit', function () {
    showLoading();
});
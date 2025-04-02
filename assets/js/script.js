document.addEventListener("DOMContentLoaded", () => {
    function visitNow() {
        window.location.href = "visit/visit.html";
    }

    function hideLoadingScreen() {
        document.getElementById("full-loading-screen").style.display = "none";
    }

    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('show');
        });
    }

    window.visitNow = visitNow;
    window.hideLoadingScreen = hideLoadingScreen;
});

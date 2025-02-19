function visitNow() {
    window.location.href = "signin.html";
}

// Hide loading screen when everything is loaded
function hideLoadingScreen() {
    document.getElementById("full-loading-screen").style.display = "none";
}

// Mobile Menu Toggle
document.querySelector('.menu-toggle').addEventListener('click', function () {
    document.querySelector('.nav-links').classList.toggle('show');
});

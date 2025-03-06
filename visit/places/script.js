function scrollToSection(id) {
    const section = document.getElementById(id);
    if (section) {
        window.scrollTo({
            top: section.offsetTop - 80, // Adjusts for navbar height
            behavior: 'smooth'
        });
    }
}

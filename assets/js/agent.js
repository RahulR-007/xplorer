document.addEventListener("DOMContentLoaded", () => {
  const toggleMenu = document.querySelector(".menu-toggle");
  const navLinks = document.querySelector(".nav-links");

  if (toggleMenu) {
    toggleMenu.addEventListener("click", () => {
      navLinks.classList.toggle("show");
    });
  }

  const successMsg = document.getElementById("successMessage");
  if (successMsg) {
    setTimeout(() => {
      successMsg.style.opacity = "0";
      setTimeout(() => successMsg.remove(), 600);
    }, 4000);
  }

  const confirmDelete = document.querySelectorAll(".delete-btn");
  confirmDelete.forEach(btn => {
    btn.addEventListener("click", (e) => {
      if (!confirm("Are you sure you want to delete this place?")) {
        e.preventDefault();
      }
    });
  });
});

// Adds a wave animation when clicking inside inputs
document.querySelectorAll(".input-group input").forEach(input => {
    input.addEventListener("focus", () => {
        input.style.transform = "scale(1.01)";
        setTimeout(() => input.style.transform = "scale(1)", 150);
    });
});

// Small hover animation for inputs
document.querySelectorAll(".form-control").forEach(input => {
    input.addEventListener("focus", () => {
        input.style.transform = "scale(1.01)";
    });
    input.addEventListener("blur", () => {
        input.style.transform = "scale(1)";
    });
});

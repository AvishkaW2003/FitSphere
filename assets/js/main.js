const navbar = document.querySelector(".navbar");

window.addEventListener("scroll", () => {
  if (window.scrollY > 50) {
    navbar.classList.add("scrolled");
  } else {
    navbar.classList.remove("scrolled");
  }
});

// Function to check if an element is visible in viewport
function isInViewport(element) {
  const rect = element.getBoundingClientRect();
  return rect.top < window.innerHeight - 100 && rect.bottom > 0;
}

// Select all .wch elements
const boxes = document.querySelectorAll('.wch');

// Scroll event listener
window.addEventListener('scroll', () => {
  boxes.forEach(box => {
    if (isInViewport(box)) {
      box.classList.add('visible');
    }
  });
});

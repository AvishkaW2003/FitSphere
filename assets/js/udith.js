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

// Reviews of about
const slider = document.querySelector('.reviews-slider');
const prev = document.querySelector('.prev-btn');
const next = document.querySelector('.next-btn');

let currentIndex = 0;
const totalCards = document.querySelectorAll('.review-card').length;
const visibleCards = 3;

function updateSlider() {
  const cardWidth = slider.querySelector('.review-card').offsetWidth + 20; // include margin
  slider.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
}

next.addEventListener('click', () => {
  if (currentIndex < totalCards - visibleCards) {
    currentIndex++;
    updateSlider();
  }
});

prev.addEventListener('click', () => {
  if (currentIndex > 0) {
    currentIndex--;
    updateSlider();
  }
});

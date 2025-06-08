// Validasi
function validateRegister() {
  const password = document.querySelector('input[name="password"]').value;
  if (password.length < 6) {
    alert("Password minimal 6 karakter");
    return false;
  }
  return true;
}

// Sidebar functionality
document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("sidebar-mobile");
  const backdrop = document.getElementById("sidebar-mobile-backdrop");
  const toggleButtons = document.querySelectorAll(
    '[data-drawer-toggle="sidebar-mobile"]'
  );
  const hideButtons = document.querySelectorAll(
    '[data-drawer-hide="sidebar-mobile"]'
  );
  const navLinks = document.querySelectorAll('a[href^="/"]');

  // Initialize timeline filter
  const firstStatus = document.querySelector(
    'input[name="status-filter"]:checked'
  )?.dataset.status;
  if (firstStatus) {
    filterTimeline(firstStatus);
  }

  // Add page transition effect
  function addPageTransition() {
    document.body.style.opacity = "0";
    setTimeout(() => {
      document.body.style.opacity = "1";
    }, 100);
  }

  // Add transition to all navigation links
  navLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      if (!this.getAttribute("href").startsWith("#")) {
        e.preventDefault();
        document.body.style.opacity = "0";
        setTimeout(() => {
          window.location.href = this.getAttribute("href");
        }, 200);
      }
    });
  });

  // Toggle sidebar
  function toggleSidebar() {
    if (!sidebar || !backdrop) return;
    sidebar.classList.toggle("-translate-x-full");
    backdrop.classList.toggle("hidden");
    document.body.classList.toggle("overflow-hidden");
  }

  // Hide sidebar
  function hideSidebar() {
    if (!sidebar || !backdrop) return;
    if (!sidebar.classList.contains("-translate-x-full")) {
      sidebar.classList.add("-translate-x-full");
      backdrop.classList.add("hidden");
      document.body.classList.remove("overflow-hidden");
    }
  }

  // Add click event listeners
  toggleButtons.forEach((button) =>
    button.addEventListener("click", toggleSidebar)
  );
  hideButtons.forEach((button) =>
    button.addEventListener("click", hideSidebar)
  );

  // Handle window resize
  window.addEventListener("resize", function () {
    if (!sidebar || !backdrop) return;
    if (window.innerWidth >= 640) {
      sidebar.classList.remove("-translate-x-full");
      backdrop.classList.add("hidden");
      document.body.classList.remove("overflow-hidden");
    } else {
      sidebar.classList.add("-translate-x-full");
      backdrop.classList.add("hidden");
      document.body.classList.remove("overflow-hidden");
    }
  });

  // Initialize page transition
  addPageTransition();
});

// Testimonial Slider functionality
document.addEventListener("DOMContentLoaded", function () {
  const track = document.querySelector(".testimonial-track");
  const slides = document.querySelectorAll(".testimonial-slide");
  const sliderContainer = document.querySelector(".testimonial-slider");

  if (!track || !slides.length || !sliderContainer) return;

  let currentIndex = 0;
  let isAnimating = false;
  let autoplayInterval;
  let isDragging = false;
  let startPos = 0;
  let currentTranslate = 0;
  let prevTranslate = 0;

  // Helper to add/remove transition
  function setTransition(active) {
    if (active) {
      track.classList.add(
        "transition-transform",
        "duration-500",
        "ease-in-out"
      );
    } else {
      track.classList.remove(
        "transition-transform",
        "duration-500",
        "ease-in-out"
      );
    }
  }

  // Initialize slider position
  setTransition(true);
  track.style.transform = "translateX(0)";

  // Function to update slider position
  function updateSlider() {
    if (isAnimating) return;
    isAnimating = true;
    setTransition(true);
    track.style.transform = `translateX(-${currentIndex * 100}%)`;
    // Update slides
    slides.forEach((slide, index) => {
      if (index === currentIndex) {
        slide.classList.add("active");
      } else {
        slide.classList.remove("active");
      }
    });
    setTimeout(() => {
      isAnimating = false;
    }, 500);
  }

  // Initialize active states
  function initializeActiveStates() {
    slides[0].classList.add("active");
    track.style.transform = "translateX(0)";
  }

  function nextSlide() {
    if (isAnimating) return;
    currentIndex = (currentIndex + 1) % slides.length;
    updateSlider();
  }

  function prevSlide() {
    if (isAnimating) return;
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    updateSlider();
  }

  function startAutoplay() {
    autoplayInterval = setInterval(nextSlide, 5000);
  }

  function resetAutoplay() {
    clearInterval(autoplayInterval);
    startAutoplay();
  }

  initializeActiveStates();
  startAutoplay();

  // Pause autoplay on hover/touch
  sliderContainer.addEventListener("mouseenter", () => {
    clearInterval(autoplayInterval);
  });
  sliderContainer.addEventListener("mouseleave", () => {
    startAutoplay();
  });

  // Touch and mouse events
  function touchStart(event) {
    isDragging = true;
    startPos = getPositionX(event);
    sliderContainer.style.cursor = "grabbing";
    clearInterval(autoplayInterval);
    setTransition(false);
  }

  function touchMove(event) {
    if (!isDragging) return;
    const currentPosition = getPositionX(event);
    let diff = currentPosition - startPos;

    // Add resistance at the edges
    if (
      (currentIndex === 0 && diff > 0) ||
      (currentIndex === slides.length - 1 && diff < 0)
    ) {
      diff *= 0.3;
    }

    track.style.transform = `translateX(calc(-${
      currentIndex * 100
    }% + ${diff}px))`;
  }

  function touchEnd(event) {
    if (!isDragging) return;
    isDragging = false;
    sliderContainer.style.cursor = "grab";
    setTransition(true);

    const endPos = getPositionX(event);
    const diff = endPos - startPos;

    // Determine if swipe was significant enough to change slide
    const threshold = window.innerWidth * 0.2; // 20% of screen width

    if (Math.abs(diff) > threshold) {
      if (diff > 0) {
        prevSlide();
      } else {
        nextSlide();
      }
    } else {
      // Return to current slide if swipe wasn't significant
      track.style.transform = `translateX(-${currentIndex * 100}%)`;
    }

    startAutoplay();
  }

  function getPositionX(event) {
    if (event.type.includes("mouse")) {
      return event.pageX;
    } else if (event.touches && event.touches[0]) {
      return event.touches[0].clientX;
    } else if (event.changedTouches && event.changedTouches[0]) {
      return event.changedTouches[0].clientX;
    }
    return 0;
  }

  // Add event listeners for both touch and mouse events
  sliderContainer.addEventListener("mousedown", touchStart);
  sliderContainer.addEventListener("mousemove", touchMove);
  sliderContainer.addEventListener("mouseup", touchEnd);
  sliderContainer.addEventListener("mouseleave", touchEnd);

  // Touch events
  sliderContainer.addEventListener("touchstart", touchStart, {
    passive: true,
  });
  sliderContainer.addEventListener("touchmove", touchMove, {
    passive: true,
  });
  sliderContainer.addEventListener("touchend", touchEnd);

  // Add keyboard navigation
  document.addEventListener("keydown", (e) => {
    if (e.key === "ArrowLeft") {
      prevSlide();
    } else if (e.key === "ArrowRight") {
      nextSlide();
    }
  });
});

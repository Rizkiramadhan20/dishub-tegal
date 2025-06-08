// Format video duration
function formatDuration(seconds) {
  const minutes = Math.floor(seconds / 60);
  const remainingSeconds = Math.floor(seconds % 60);
  return `${minutes}:${remainingSeconds.toString().padStart(2, "0")}`;
}

// Validasi
function validateRegister() {
  const password = document.querySelector('input[name="password"]').value;
  if (password.length < 6) {
    alert("Password minimal 6 karakter");
    return false;
  }
  return true;
}

// Toast Notification
function showToast(message, type = "success") {
  const toast = document.getElementById("toast");
  const toastMessage = document.getElementById("toast-message");
  const toastIcon = document.getElementById("toast-icon");

  if (!toast || !toastMessage || !toastIcon) return;

  // Set message
  toastMessage.textContent = message;

  // Set icon and color based on type
  if (type === "success") {
    toastIcon.className =
      "inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-600 bg-green-50 rounded-lg";
    toastIcon.innerHTML = '<i class="bx bx-check text-xl"></i>';
  } else if (type === "error") {
    toastIcon.className =
      "inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-600 bg-red-50 rounded-lg";
    toastIcon.innerHTML = '<i class="bx bx-x text-xl"></i>';
  }

  // Show toast
  toast.classList.remove("hidden");

  // Hide after 3 seconds
  setTimeout(() => {
    hideToast();
  }, 3000);
}

function hideToast() {
  const toast = document.getElementById("toast");
  if (toast) {
    toast.classList.add("hidden");
  }
}

// Video Modal Functionality
function initializeVideoModal() {
  const modalElement = document.getElementById("videoModal");
  if (!modalElement) return;

  const modal = new Modal(modalElement, {
    placement: "center",
    backdrop: "dynamic",
    backdropClasses: "bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40",
    closable: true,
    onHide: () => {
      const modalVideo = document.getElementById("modalVideo");
      if (modalVideo) {
        modalVideo.pause();
      }
    },
  });

  // Get all watch video buttons
  const watchButtons = document.querySelectorAll(".watch-video-btn");
  const modalVideo = document.getElementById("modalVideo");
  const modalTitle = document.getElementById("modalTitle");
  const modalDescription = document.getElementById("modalDescription");
  const closeButton = modalElement.querySelector(
    '[data-modal-hide="videoModal"]'
  );

  // Add click event to each button
  watchButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const videoSrc = this.getAttribute("data-video");
      const title = this.getAttribute("data-title");
      const description = this.getAttribute("data-description");

      // Set modal content
      modalVideo.querySelector("source").src = videoSrc;
      modalVideo.load();
      modalTitle.textContent = title;
      modalDescription.textContent = description;

      // Show modal
      modal.show();
    });
  });

  // Handle close button click
  closeButton.addEventListener("click", function () {
    modal.hide();
  });

  // Handle click outside
  modalElement.addEventListener("click", function (e) {
    if (e.target === modalElement) {
      modal.hide();
    }
  });

  // Handle escape key
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !modalElement.classList.contains("hidden")) {
      modal.hide();
    }
  });
}

// Image Modal Functionality
function initializeImageModal() {
  const modalElement = document.getElementById("imageModal");
  if (!modalElement) return;

  const modal = new Modal(modalElement, {
    placement: "center",
    backdrop: "dynamic",
    backdropClasses: "bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40",
    closable: true,
  });

  // Get all view image buttons
  const viewButtons = document.querySelectorAll(".view-image-btn");
  const modalImage = document.getElementById("modalImage");
  const modalTitle = document.getElementById("modalImageTitle");
  const closeButton = modalElement.querySelector(
    '[data-modal-hide="imageModal"]'
  );

  // Add click event to each button
  viewButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const imageSrc = this.getAttribute("data-image");
      const title = this.getAttribute("data-title");

      // Set modal content
      modalImage.src = imageSrc;
      modalImage.alt = title;
      modalTitle.textContent = title;

      // Show modal
      modal.show();
    });
  });

  // Handle close button click
  closeButton.addEventListener("click", function () {
    modal.hide();
  });

  // Handle click outside
  modalElement.addEventListener("click", function (e) {
    if (e.target === modalElement) {
      modal.hide();
    }
  });

  // Handle escape key
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !modalElement.classList.contains("hidden")) {
      modal.hide();
    }
  });
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

  // Initialize video modal
  initializeVideoModal();

  // Initialize image modal
  initializeImageModal();

  // Function to handle active navigation links
  function handleActiveLinks() {
    const navLinks = document.querySelectorAll('nav a[href^="#"]');
    const scrollPosition = window.scrollY + 100;

    // Find the current section
    let currentSection = null;

    // Check all sections with IDs
    document.querySelectorAll("section[id]").forEach((section) => {
      const sectionTop = section.offsetTop;
      const sectionHeight = section.offsetHeight;

      if (
        scrollPosition >= sectionTop &&
        scrollPosition < sectionTop + sectionHeight
      ) {
        currentSection = section.id;
      }
    });

    // Update active state for all links
    navLinks.forEach((link) => {
      const href = link.getAttribute("href");
      const linkSection =
        href === "/" || href === "#home" ? "home" : href.substring(1);

      // Remove active classes
      link.classList.remove("text-blue-600", "after:w-full", "bg-blue-50");

      // Add active classes if it's the current section
      if (linkSection === currentSection) {
        link.classList.add("text-blue-600", "after:w-full");
        if (link.closest(".mobile-menu")) {
          link.classList.add("bg-blue-50");
        }
      }
    });
  }

  // Add scroll event listener
  window.addEventListener("scroll", handleActiveLinks);

  // Initial check for active links
  handleActiveLinks();

  // Handle smooth scrolling for navigation links
  document.querySelectorAll('nav a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("href");

      // Special handling for home link
      if (targetId === "/" || targetId === "#home") {
        const homeSection = document.getElementById("home");
        if (homeSection) {
          homeSection.scrollIntoView({
            behavior: "smooth",
          });
        }
        return;
      }

      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        targetElement.scrollIntoView({
          behavior: "smooth",
        });
      }
    });
  });
});

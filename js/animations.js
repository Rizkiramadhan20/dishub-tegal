// Register ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger);

// Initial animations when page loads
function initAnimations() {
  // Hero section animations
  const heroTimeline = gsap.timeline();

  // Title animation
  heroTimeline
    .from(".home-title", {
      y: 100,
      opacity: 0,
      duration: 1.2,
      ease: "power4.out",
    })
    // Description animation
    .from(
      ".home-description",
      {
        y: 50,
        opacity: 0,
        duration: 1,
        ease: "power4.out",
      },
      "-=0.8"
    )
    // Image animation
    .from(
      "#home .aspect-\\[16\\/9\\]",
      {
        scale: 0.8,
        opacity: 0,
        duration: 1.2,
        ease: "power3.out",
      },
      "-=0.6"
    );

  // About section animations
  const aboutTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: "#about",
      start: "top center",
      end: "bottom center",
      toggleActions: "play none none reverse",
    },
  });

  // Set initial state for about button
  gsap.set("#about a", {
    opacity: 1,
  });

  aboutTimeline
    .from("#about .rounded-2xl", {
      x: -100,
      opacity: 0,
      duration: 1,
      ease: "power4.out",
    })
    .from(
      "#about .text-blue-600",
      {
        y: 20,
        opacity: 0,
        duration: 0.8,
        ease: "power4.out",
      },
      "-=0.6"
    )
    .from(
      "#about h3",
      {
        y: 30,
        opacity: 0,
        duration: 0.8,
        ease: "power4.out",
      },
      "-=0.6"
    )
    .from(
      "#about p",
      {
        y: 20,
        opacity: 0,
        duration: 0.8,
        stagger: 0.2,
        ease: "power4.out",
      },
      "-=0.6"
    )
    .from(
      "#about a",
      {
        y: 20,
        opacity: 0,
        duration: 0.8,
        ease: "back.out(1.7)",
      },
      "-=0.6"
    );

  // Add hover animation for about section button
  const aboutButton = document.querySelector("#about a");
  if (aboutButton) {
    aboutButton.addEventListener("mouseenter", () => {
      gsap.to(aboutButton, {
        scale: 1.05,
        duration: 0.3,
        ease: "power2.out",
      });
    });
    aboutButton.addEventListener("mouseleave", () => {
      gsap.to(aboutButton, {
        scale: 1,
        duration: 0.3,
        ease: "power2.out",
      });
    });
  }

  // Education section animations
  const educationTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: "#education",
      start: "top center",
      end: "bottom center",
      toggleActions: "play none none reverse",
    },
  });

  // Set initial state for education cards
  gsap.set("#education .group", {
    opacity: 1,
  });

  educationTimeline
    .from("#education .text-blue-600", {
      y: 20,
      opacity: 0,
      duration: 0.8,
      ease: "power4.out",
    })
    .from(
      "#education h2",
      {
        y: 30,
        opacity: 0,
        duration: 0.8,
        ease: "power4.out",
      },
      "-=0.6"
    )
    .from(
      "#education p",
      {
        y: 20,
        opacity: 0,
        duration: 0.8,
        ease: "power4.out",
      },
      "-=0.6"
    )
    .from(
      "#education .group",
      {
        y: 50,
        opacity: 0,
        duration: 0.8,
        stagger: 0.2,
        ease: "power4.out",
      },
      "-=0.6"
    );

  // Gallery section animations
  const galleryTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: "#gallery",
      start: "top center",
      end: "bottom center",
      toggleActions: "play none none reverse",
    },
  });

  // Set initial state for desktop gallery cards
  gsap.set(".inspiration-card", {
    opacity: 1,
    scale: 0.95,
  });

  galleryTimeline
    .from(".gallery-subtitle", {
      y: 20,
      opacity: 0,
      duration: 0.8,
      ease: "power4.out",
    })
    .from(
      ".gallery-title",
      {
        y: 30,
        opacity: 0,
        duration: 0.8,
        ease: "power4.out",
      },
      "-=0.6"
    )
    .from(
      ".gallery-description",
      {
        y: 20,
        opacity: 0,
        duration: 0.8,
        ease: "power4.out",
      },
      "-=0.6"
    )
    .from(
      ".inspiration-card",
      {
        scale: 0.95,
        opacity: 0,
        duration: 0.8,
        stagger: {
          amount: 0.8,
          from: "random",
        },
        ease: "power3.out",
      },
      "-=0.6"
    );

  // Add hover animation for gallery cards
  document.querySelectorAll(".inspiration-card").forEach((card) => {
    card.addEventListener("mouseenter", () => {
      gsap.to(card, {
        scale: 1.02,
        duration: 0.3,
        ease: "power2.out",
      });
    });
    card.addEventListener("mouseleave", () => {
      gsap.to(card, {
        scale: 1,
        duration: 0.3,
        ease: "power2.out",
      });
    });
  });

  // News section animations
  const newsTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: "#news",
      start: "top center",
      end: "bottom center",
      toggleActions: "play none none reverse",
    },
  });

  // Set initial state for news cards
  gsap.set("#news .group", {
    opacity: 1,
    scale: 0.95,
  });

  newsTimeline
    .from("#news .text-blue-600", {
      y: 20,
      opacity: 0,
      duration: 0.8,
      ease: "power4.out",
    })
    .from(
      "#news h2",
      {
        y: 30,
        opacity: 0,
        duration: 0.8,
        ease: "power4.out",
      },
      "-=0.6"
    )
    .from(
      "#news p",
      {
        y: 20,
        opacity: 0,
        duration: 0.8,
        ease: "power4.out",
      },
      "-=0.6"
    )
    .from(
      "#news .group",
      {
        scale: 0.95,
        opacity: 0,
        duration: 0.8,
        stagger: {
          amount: 0.8,
          from: "random",
        },
        ease: "power3.out",
      },
      "-=0.6"
    );

  // Add hover animation for news cards
  document.querySelectorAll("#news .group").forEach((card) => {
    card.addEventListener("mouseenter", () => {
      gsap.to(card, {
        scale: 1.02,
        duration: 0.3,
        ease: "power2.out",
      });
    });
    card.addEventListener("mouseleave", () => {
      gsap.to(card, {
        scale: 1,
        duration: 0.3,
        ease: "power2.out",
      });
    });
  });

  // Contact section animations
  const contactTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: "#contact",
      start: "top 80%",
      once: true,
    },
  });

  contactTimeline
    .from("#contact .bg-white", {
      y: 50,
      opacity: 0,
      duration: 0.8,
      stagger: 0.2,
      ease: "power4.out",
    })
    .from(
      "#contact form",
      {
        y: 30,
        opacity: 0,
        duration: 0.8,
        ease: "power4.out",
      },
      "-=0.4"
    );
}

// Initialize animations when DOM is loaded
document.addEventListener("DOMContentLoaded", initAnimations);

// Reinitialize animations when content changes (for dynamic content)
function reinitializeAnimations() {
  // Kill all existing ScrollTriggers
  ScrollTrigger.getAll().forEach((trigger) => trigger.kill());

  // Reinitialize animations
  initAnimations();
}

// Export the reinitialize function for use in other scripts
window.reinitializeAnimations = reinitializeAnimations;

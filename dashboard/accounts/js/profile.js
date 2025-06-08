// Show toast notification
function showToast(message, type = "success") {
  const toast = document.getElementById("toast");
  const toastIcon = document.getElementById("toast-icon");
  const toastMessage = document.getElementById("toast-message");

  // Set icon based on type
  if (type === "success") {
    toastIcon.innerHTML = '<i class="bx bx-check text-xl"></i>';
    toastIcon.className =
      "inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg";
  } else {
    toastIcon.innerHTML = '<i class="bx bx-x text-xl"></i>';
    toastIcon.className =
      "inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg";
  }

  toastMessage.textContent = message;
  toast.classList.remove("hidden");
  setTimeout(() => {
    toast.classList.add("hidden");
  }, 3000);
}

// Hide toast notification
function hideToast() {
  const toast = document.getElementById("toast");
  toast.classList.add("hidden");
}

// Handle edit profile form submission
document
  .getElementById("editProfileForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append("action", "edit_profile");

    try {
      const response = await fetch("../../process.php", {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.success) {
        showToast(data.message);
        // Close modal
        const modal = document.getElementById("editProfileModal");
        const modalInstance = new Modal(modal);
        modalInstance.hide();
        // Reload page after 1 second to show updated data
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      } else {
        showToast(data.message, "error");
      }
    } catch (error) {
      showToast("An error occurred. Please try again.", "error");
    }
  });

// Handle change password form submission
document
  .getElementById("changePasswordForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append("action", "change_password");

    // Validate passwords match
    const newPassword = formData.get("new_password");
    const confirmPassword = formData.get("confirm_password");

    if (newPassword !== confirmPassword) {
      showToast("New passwords do not match", "error");
      return;
    }

    try {
      const response = await fetch("../../process.php", {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.success) {
        showToast(data.message);
        // Close modal
        const modal = document.getElementById("changePasswordModal");
        const modalInstance = new Modal(modal);
        modalInstance.hide();
        // Clear form
        this.reset();

        // Redirect to login page after 2 seconds
        setTimeout(() => {
          window.location.href = "../../../index.php";
        }, 2000);
      } else {
        showToast(data.message, "error");
      }
    } catch (error) {
      showToast("An error occurred. Please try again.", "error");
    }
  });

// Initialize modals
document.addEventListener("DOMContentLoaded", function () {
  // Edit Profile button click handler
  document
    .getElementById("editProfileBtn")
    .addEventListener("click", function () {
      const modal = document.getElementById("editProfileModal");
      const modalInstance = new Modal(modal);
      modalInstance.show();
    });

  // Change Password button click handler
  document
    .getElementById("changePasswordBtn")
    .addEventListener("click", function () {
      const modal = document.getElementById("changePasswordModal");
      const modalInstance = new Modal(modal);
      modalInstance.show();
    });

  // Close buttons for Edit Profile Modal
  const editProfileModal = document.getElementById("editProfileModal");
  const editProfileCloseBtn = editProfileModal.querySelector(
    "[data-modal-hide='editProfileModal']"
  );
  const editProfileCancelBtn = editProfileModal.querySelector(
    "button[type='button'][data-modal-hide='editProfileModal']"
  );

  if (editProfileCloseBtn) {
    editProfileCloseBtn.addEventListener("click", function () {
      const modalInstance = new Modal(editProfileModal);
      modalInstance.hide();
    });
  }

  if (editProfileCancelBtn) {
    editProfileCancelBtn.addEventListener("click", function () {
      const modalInstance = new Modal(editProfileModal);
      modalInstance.hide();
    });
  }

  // Close buttons for Change Password Modal
  const changePasswordModal = document.getElementById("changePasswordModal");
  const changePasswordCloseBtn = changePasswordModal.querySelector(
    "[data-modal-hide='changePasswordModal']"
  );
  const changePasswordCancelBtn = changePasswordModal.querySelector(
    "button[type='button'][data-modal-hide='changePasswordModal']"
  );

  if (changePasswordCloseBtn) {
    changePasswordCloseBtn.addEventListener("click", function () {
      const modalInstance = new Modal(changePasswordModal);
      modalInstance.hide();
    });
  }

  if (changePasswordCancelBtn) {
    changePasswordCancelBtn.addEventListener("click", function () {
      const modalInstance = new Modal(changePasswordModal);
      modalInstance.hide();
    });
  }

  // Add click event for all cancel buttons
  document.querySelectorAll("button[data-modal-hide]").forEach((button) => {
    button.addEventListener("click", function () {
      const modalId = this.getAttribute("data-modal-hide");
      const modal = document.getElementById(modalId);
      if (modal) {
        const modalInstance = new Modal(modal);
        modalInstance.hide();
      }
    });
  });
});

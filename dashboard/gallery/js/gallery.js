// Toast function
function showToast(message, type = "success") {
  const toastContainer = document.getElementById("toast-container");
  const toastId = "toast-" + Date.now();

  const toast = document.createElement("div");
  toast.id = toastId;
  toast.className = `flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800 ${
    type === "success"
      ? "border-l-4 border-green-500"
      : "border-l-4 border-red-500"
  }`;
  toast.role = "alert";

  const icon =
    type === "success"
      ? '<i class="bx bx-check-circle text-green-500 text-xl"></i>'
      : '<i class="bx bx-x-circle text-red-500 text-xl"></i>';

  toast.innerHTML = `
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8">
      ${icon}
    </div>
    <div class="ml-3 text-sm font-normal">${message}</div>
    <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#${toastId}" aria-label="Close">
      <i class="bx bx-x text-xl"></i>
    </button>
  `;

  toastContainer.appendChild(toast);

  // Auto remove after 3 seconds
  setTimeout(() => {
    toast.remove();
  }, 3000);
}

// Create form submission
document.addEventListener("DOMContentLoaded", function () {
  const createForm = document.getElementById("createContentForm");
  if (createForm) {
    createForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const submitButton = this.querySelector('button[type="submit"]');
      const originalButtonText = submitButton.innerHTML;

      // Show loading state
      submitButton.disabled = true;
      submitButton.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      `;

      try {
        const response = await fetch("utils/create_content.php", {
          method: "POST",
          body: formData,
        });

        const result = await response.json();
        if (result.success) {
          showToast("Content created successfully");
          setTimeout(() => {
            window.location.reload();
          }, 1000);
        } else {
          showToast(result.message || "Failed to create content", "error");
          // Reset button state on error
          submitButton.disabled = false;
          submitButton.innerHTML = originalButtonText;
        }
      } catch (error) {
        console.error("Error:", error);
        showToast("An error occurred while creating content", "error");
        // Reset button state on error
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
      }
    });
  }

  // Edit functionality
  const editButtons = document.querySelectorAll(".edit-content");
  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.getAttribute("data-id");
      const title = this.getAttribute("data-title");
      const imageSrc = this.getAttribute("data-image");

      console.log("Edit clicked:", { id, title, imageSrc }); // Debug log

      // Set form values
      document.getElementById("edit_id").value = id;
      document.getElementById("edit_title").value = title;

      // Update image preview
      const currentImagePreview = document.getElementById(
        "current_image_preview"
      );
      currentImagePreview.src = imageSrc;
      currentImagePreview.onload = function () {
        // Show current image, hide upload container
        document.getElementById("current_image").classList.remove("hidden");
        document
          .getElementById("image_input_container")
          .classList.add("hidden");
      };

      // Reset file input
      document.getElementById("edit_image").value = "";
    });
  });

  // Handle image change button
  document
    .getElementById("change_image_btn")
    .addEventListener("click", function () {
      document.getElementById("current_image").classList.add("hidden");
      document
        .getElementById("image_input_container")
        .classList.remove("hidden");
      // Reset file input
      document.getElementById("edit_image").value = "";
    });

  // Handle image preview
  document
    .getElementById("edit_image")
    .addEventListener("change", function (e) {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          const preview = document.getElementById("current_image_preview");
          preview.src = e.target.result;
          document.getElementById("current_image").classList.remove("hidden");
          document
            .getElementById("image_input_container")
            .classList.add("hidden");
        };
        reader.readAsDataURL(this.files[0]);
      }
    });

  // Edit form submission
  const editForm = document.getElementById("editContentForm");
  if (editForm) {
    editForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const formData = new FormData(this);
      const submitButton = this.querySelector('button[type="submit"]');
      const originalButtonText = submitButton.innerHTML;

      // Disable submit button and show loading state
      submitButton.disabled = true;
      submitButton.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Updating...
      `;

      try {
        const response = await fetch("utils/edit_content.php", {
          method: "POST",
          body: formData,
        });

        const result = await response.json();
        if (result.success) {
          showToast("Content updated successfully");
          setTimeout(() => {
            window.location.reload();
          }, 1500);
        } else {
          showToast(result.message || "Failed to update content", "error");
        }
      } catch (error) {
        console.error("Error:", error);
        showToast("An error occurred while updating content", "error");
      } finally {
        // Reset button state
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
      }
    });
  }

  // Delete functionality
  const deleteButtons = document.querySelectorAll(".delete-content");
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.getAttribute("data-id");
      const confirmDeleteBtn = document.getElementById("confirmDelete");
      const deleteText = confirmDeleteBtn.querySelector(".delete-text");
      const deleteLoading = confirmDeleteBtn.querySelector(".delete-loading");

      confirmDeleteBtn.onclick = async function () {
        deleteText.classList.add("hidden");
        deleteLoading.classList.remove("hidden");
        confirmDeleteBtn.disabled = true;

        try {
          const response = await fetch("utils/delete_content.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({ id: id }),
          });

          const result = await response.json();
          if (result.success) {
            showToast("Content deleted successfully");
            setTimeout(() => {
              window.location.reload();
            }, 1000);
          } else {
            showToast(result.message || "Failed to delete content", "error");
            deleteText.classList.remove("hidden");
            deleteLoading.classList.add("hidden");
            confirmDeleteBtn.disabled = false;
          }
        } catch (error) {
          console.error("Error:", error);
          showToast("An error occurred while deleting content", "error");
          deleteText.classList.remove("hidden");
          deleteLoading.classList.add("hidden");
          confirmDeleteBtn.disabled = false;
        }
      };
    });
  });

  // Create form image preview
  const createImageInput = document.getElementById("image");
  const createImagePreview = document.getElementById("image-preview");
  const createPreviewImg = createImagePreview.querySelector("img");
  const createPreviewRemoveBtn = createImagePreview.querySelector("button");
  const createImageInputContainer = createImageInput.closest(
    ".flex.items-center.justify-center.w-full"
  );

  if (createImageInput) {
    createImageInput.addEventListener("change", function () {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          createPreviewImg.src = e.target.result;
          createImagePreview.classList.remove("hidden");
          createImageInputContainer.classList.add("hidden");
        };
        reader.readAsDataURL(this.files[0]);
      }
    });

    createPreviewRemoveBtn.addEventListener("click", function () {
      createImageInput.value = "";
      createPreviewImg.src = "";
      createImagePreview.classList.add("hidden");
      createImageInputContainer.classList.remove("hidden");
    });
  }
});

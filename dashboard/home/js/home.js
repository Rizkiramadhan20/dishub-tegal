// Toast function
function showToast(message, type = "success") {
  const toastContainer = document.getElementById("toast-container");
  const toastId = "toast-" + Date.now();

  const toast = document.createElement("div");
  toast.id = toastId;
  toast.className = `flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800`;
  toast.role = "alert";

  const icon =
    type === "success"
      ? "bx-check-circle text-green-500"
      : "bx-error-circle text-red-500";

  toast.innerHTML = `
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8">
      <i class='bx ${icon} text-xl'></i>
    </div>
    <div class="ml-3 text-sm font-normal">${message}</div>
    <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#${toastId}" aria-label="Close">
      <i class='bx bx-x text-xl'></i>
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
        Creating...
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
      const card = this.closest(".bg-white");
      const title = card.querySelector("h5").textContent.trim();
      const description = card.querySelector("p").textContent.trim();
      const imageSrc = card.querySelector("img").src;

      // Populate form fields
      document.getElementById("edit_id").value = id;
      document.getElementById("edit_title").value = title;
      document.getElementById("edit_description").value = description;

      // Handle image display
      document.getElementById("current_image_preview").src = imageSrc;
      document.getElementById("current_image").style.display = "block";
      document.getElementById("image_input_container").style.display = "none";
    });
  });

  // Edit form submission
  const editForm = document.getElementById("editContentForm");
  if (editForm) {
    editForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const submitButton = this.querySelector('button[type="submit"]');
      const originalButtonText = submitButton.innerHTML;

      // Show loading state
      submitButton.disabled = true;
      submitButton.innerHTML = `
        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
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
          }, 1000);
        } else {
          showToast(result.message || "Failed to update content", "error");
          // Reset button state on error
          submitButton.disabled = false;
          submitButton.innerHTML = originalButtonText;
        }
      } catch (error) {
        console.error("Error:", error);
        showToast("An error occurred while updating content", "error");
        // Reset button state on error
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

  if (createImageInput) {
    createImageInput.addEventListener("change", function () {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          createPreviewImg.src = e.target.result;
          createImagePreview.classList.remove("hidden");
        };
        reader.readAsDataURL(this.files[0]);
      }
    });

    createPreviewRemoveBtn.addEventListener("click", function () {
      createImageInput.value = "";
      createPreviewImg.src = "";
      createImagePreview.classList.add("hidden");
    });
  }

  // Edit form image handling
  const editImageInput = document.getElementById("edit_image");
  const imageInputContainer = document.getElementById("image_input_container");
  const currentImage = document.getElementById("current_image");
  const changeImageBtn = document.getElementById("change_image_btn");
  const editImagePreview = document.getElementById("current_image_preview");

  if (editImageInput && changeImageBtn) {
    changeImageBtn.addEventListener("click", function () {
      imageInputContainer.style.display = "block";
      currentImage.style.display = "none";
      editImageInput.value = "";
    });

    editImageInput.addEventListener("change", function () {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          editImagePreview.src = e.target.result;
          currentImage.style.display = "block";
          imageInputContainer.style.display = "none";
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  }
});

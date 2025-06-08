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
      const file = formData.get("video");

      if (!file) {
        showToast("Please select a file to upload", "error");
        return;
      }

      // Create progress bar
      const progressContainer = document.createElement("div");
      progressContainer.className =
        "w-full bg-gray-200 rounded-full h-2.5 mt-4";
      const progressBar = document.createElement("div");
      progressBar.className =
        "bg-blue-600 h-2.5 rounded-full transition-all duration-300";
      progressBar.style.width = "0%";
      progressContainer.appendChild(progressBar);
      submitButton.parentElement.insertBefore(progressContainer, submitButton);

      // Show loading state
      submitButton.disabled = true;
      submitButton.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Uploading...
      `;

      try {
        const CHUNK_SIZE = 1024 * 1024; // 1MB chunks
        const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
        let uploadedChunks = 0;

        // Create a unique upload ID
        const uploadId = Date.now().toString();

        for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
          const start = chunkIndex * CHUNK_SIZE;
          const end = Math.min(start + CHUNK_SIZE, file.size);
          const chunk = file.slice(start, end);

          const chunkFormData = new FormData();
          chunkFormData.append("chunk", chunk);
          chunkFormData.append("chunkIndex", chunkIndex);
          chunkFormData.append("totalChunks", totalChunks);
          chunkFormData.append("uploadId", uploadId);
          chunkFormData.append("fileName", file.name);
          chunkFormData.append("title", formData.get("title"));
          chunkFormData.append("description", formData.get("description"));

          const xhr = new XMLHttpRequest();

          await new Promise((resolve, reject) => {
            xhr.upload.addEventListener("progress", (event) => {
              if (event.lengthComputable) {
                const chunkProgress =
                  (event.loaded / event.total) * (100 / totalChunks);
                const totalProgress =
                  uploadedChunks * (100 / totalChunks) + chunkProgress;
                progressBar.style.width = totalProgress + "%";
              }
            });

            xhr.onload = function () {
              if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                  uploadedChunks++;
                  resolve();
                } else {
                  reject(new Error(response.message || "Upload failed"));
                }
              } else {
                reject(new Error("Upload failed"));
              }
            };

            xhr.onerror = () => reject(new Error("Network error"));

            xhr.open("POST", "utils/create_content.php", true);
            xhr.send(chunkFormData);
          });
        }

        // All chunks uploaded successfully
        progressBar.className =
          "bg-green-500 h-2.5 rounded-full transition-all duration-300";
        submitButton.innerHTML = `
          <i class='bx bx-check text-xl mr-2'></i>
          Success!
        `;
        showToast("Content created successfully");
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      } catch (error) {
        console.error("Error:", error);
        progressBar.className =
          "bg-red-500 h-2.5 rounded-full transition-all duration-300";
        showToast(
          error.message || "An error occurred while uploading",
          "error"
        );
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
        progressContainer.remove();
      }
    });
  }

  // Edit functionality
  const editButtons = document.querySelectorAll(".edit-content");
  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.getAttribute("data-id");
      const row = this.closest("tr");
      const title = row.querySelector("td:nth-child(2)").textContent.trim();
      const description = row
        .querySelector("td:nth-child(3)")
        .textContent.trim();
      const videoSrc = row.querySelector("video source").src;

      // Populate form fields
      document.getElementById("edit_id").value = id;
      document.getElementById("edit_title").value = title;
      document.getElementById("edit_description").value = description;

      // Handle video display
      document
        .getElementById("current_video_preview")
        .querySelector("source").src = videoSrc;
      document.getElementById("current_video_preview").load();
      document.getElementById("current_video").style.display = "block";
      document.getElementById("video_input_container").style.display = "none";
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

  // Create form video preview
  const createVideoInput = document.getElementById("video");
  const createVideoPreview = document.getElementById("video-preview");
  const createPreviewVideo = createVideoPreview.querySelector("video");
  const createPreviewRemoveBtn = createVideoPreview.querySelector("button");

  if (createVideoInput) {
    createVideoInput.addEventListener("change", function () {
      if (this.files && this.files[0]) {
        const file = this.files[0];

        // Hide input container and show preview
        this.parentElement.parentElement.style.display = "none";
        createVideoPreview.classList.remove("hidden");

        // Show loading state
        createPreviewVideo.innerHTML = `
          <div class="flex items-center justify-center h-full">
            <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>
        `;

        // Create video preview
        const videoURL = URL.createObjectURL(file);
        createPreviewVideo.innerHTML = `
          <source src="${videoURL}" type="${file.type}">
          Your browser does not support the video tag.
        `;
        createPreviewVideo.load();

        // Add event listener for video metadata loaded
        createPreviewVideo.addEventListener("loadedmetadata", function () {
          // Set video duration
          const duration = Math.round(createPreviewVideo.duration);
          const minutes = Math.floor(duration / 60);
          const seconds = duration % 60;
          const durationText = `${minutes}:${seconds
            .toString()
            .padStart(2, "0")}`;

          // Add duration overlay
          const durationOverlay = document.createElement("div");
          durationOverlay.className =
            "absolute bottom-2 right-2 bg-black bg-opacity-75 text-white px-2 py-1 rounded text-sm";
          durationOverlay.textContent = durationText;
          createPreviewVideo.parentElement.appendChild(durationOverlay);
        });
      }
    });

    createPreviewRemoveBtn.addEventListener("click", function () {
      createVideoInput.value = "";
      createPreviewVideo.innerHTML = "";
      createVideoPreview.classList.add("hidden");
      // Show input container again
      createVideoInput.parentElement.parentElement.style.display = "block";
      // Remove duration overlay if exists
      const durationOverlay =
        createPreviewVideo.parentElement.querySelector(".absolute");
      if (durationOverlay) {
        durationOverlay.remove();
      }
    });
  }

  // Edit form video handling
  const editVideoInput = document.getElementById("edit_video");
  const videoInputContainer = document.getElementById("video_input_container");
  const currentVideo = document.getElementById("current_video");
  const changeVideoBtn = document.getElementById("change_video_btn");
  const editVideoPreview = document.getElementById("current_video_preview");

  if (editVideoInput && changeVideoBtn) {
    changeVideoBtn.addEventListener("click", function () {
      videoInputContainer.style.display = "block";
      currentVideo.style.display = "none";
      editVideoInput.value = "";
      // Remove duration overlay if exists
      const durationOverlay =
        editVideoPreview.parentElement.querySelector(".absolute");
      if (durationOverlay) {
        durationOverlay.remove();
      }
    });

    editVideoInput.addEventListener("change", function () {
      if (this.files && this.files[0]) {
        const file = this.files[0];

        // Show loading state
        currentVideo.style.display = "block";
        videoInputContainer.style.display = "none";
        editVideoPreview.innerHTML = `
          <div class="flex items-center justify-center h-full">
            <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>
        `;

        // Create video preview
        const videoURL = URL.createObjectURL(file);
        editVideoPreview.innerHTML = `
          <source src="${videoURL}" type="${file.type}">
          Your browser does not support the video tag.
        `;
        editVideoPreview.load();

        // Add event listener for video metadata loaded
        editVideoPreview.addEventListener("loadedmetadata", function () {
          // Set video duration
          const duration = Math.round(editVideoPreview.duration);
          const minutes = Math.floor(duration / 60);
          const seconds = duration % 60;
          const durationText = `${minutes}:${seconds
            .toString()
            .padStart(2, "0")}`;

          // Add duration overlay
          const durationOverlay = document.createElement("div");
          durationOverlay.className =
            "absolute bottom-2 right-2 bg-black bg-opacity-75 text-white px-2 py-1 rounded text-sm";
          durationOverlay.textContent = durationText;
          editVideoPreview.parentElement.appendChild(durationOverlay);
        });
      }
    });
  }

  // Add video preview for existing videos in the table
  document.querySelectorAll("video").forEach((video) => {
    video.addEventListener("loadedmetadata", function () {
      const duration = Math.round(this.duration);
      const minutes = Math.floor(duration / 60);
      const seconds = duration % 60;
      const durationText = `${minutes}:${seconds.toString().padStart(2, "0")}`;

      // Add duration overlay
      const durationOverlay = document.createElement("div");
      durationOverlay.className =
        "absolute bottom-2 right-2 bg-black bg-opacity-75 text-white px-2 py-1 rounded text-sm";
      durationOverlay.textContent = durationText;
      this.parentElement.appendChild(durationOverlay);
    });
  });
});

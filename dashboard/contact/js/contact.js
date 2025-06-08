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

let deleteId = null;
let deleteRow = null;

function showDeleteModal(id, row) {
  deleteId = id;
  deleteRow = row;
  document.getElementById("deleteModal").classList.remove("hidden");
}

function hideDeleteModal() {
  document.getElementById("deleteModal").classList.add("hidden");
  deleteId = null;
  deleteRow = null;
}

document.addEventListener("DOMContentLoaded", function () {
  // Handle view message button clicks
  const viewButtons = document.querySelectorAll(".view-message-btn");
  viewButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const message = this.getAttribute("data-message");
      const name = this.getAttribute("data-name");
      const email = this.getAttribute("data-email");
      const date = this.getAttribute("data-date");
      const id = this.getAttribute("data-id");
      const row = this.closest("tr");

      // Update modal content
      document.getElementById("modalName").textContent = name;
      document.getElementById("modalEmail").textContent = email;
      document.getElementById("modalDate").textContent = date;
      document.getElementById("modalMessage").textContent = message;

      // Update status to read
      fetch("update_status.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + encodeURIComponent(id),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            // Update status badge in the table
            const statusCell = row.querySelector("td:nth-last-child(2)");
            if (statusCell) {
              statusCell.innerHTML =
                '<span class="inline-block px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Sudah Dibaca</span>';
            }
          }
        })
        .catch((error) => console.error("Error updating status:", error));
    });
  });

  // Handle delete message button clicks
  const deleteButtons = document.querySelectorAll(".delete-message-btn");
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.getAttribute("data-id");
      const row = this.closest("tr");

      // Show delete confirmation modal
      const deleteModal = document.getElementById("deleteModal");
      deleteModal.classList.remove("hidden");

      // Handle delete confirmation
      const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
      const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
      const closeDeleteModal = document.getElementById("closeDeleteModal");
      const deleteText = confirmDeleteBtn.querySelector(".delete-text");
      const deleteLoading = confirmDeleteBtn.querySelector(".delete-loading");

      const closeModal = () => {
        deleteModal.classList.add("hidden");
        deleteText.classList.remove("hidden");
        deleteLoading.classList.add("hidden");
        confirmDeleteBtn.disabled = false;
      };

      confirmDeleteBtn.onclick = async function () {
        deleteText.classList.add("hidden");
        deleteLoading.classList.remove("hidden");
        confirmDeleteBtn.disabled = true;

        try {
          const response = await fetch("delete_contact.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id=" + encodeURIComponent(id),
          });

          const result = await response.json();
          if (result.success) {
            // Remove the row from the table
            row.remove();
            closeModal();
            showToast("Pesan berhasil dihapus");
          } else {
            showToast(result.message || "Gagal menghapus pesan", "error");
            closeModal();
          }
        } catch (error) {
          console.error("Terjadi kesalahan saat menghapus pesan:", error);
          showToast("Terjadi kesalahan saat menghapus pesan", "error");
          closeModal();
        }
      };

      cancelDeleteBtn.onclick = closeModal;
      closeDeleteModal.onclick = closeModal;
    });
  });
});

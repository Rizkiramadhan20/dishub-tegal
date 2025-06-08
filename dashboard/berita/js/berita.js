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

// Formatting functions
function wrapSelection(editor, before, after) {
  const selection = editor.getSelection();
  const cursor = editor.getCursor();

  if (selection) {
    editor.replaceSelection(before + selection + after);
  } else {
    // If no selection, insert at cursor position
    editor.replaceRange(before + after, cursor);
    // Move cursor between the tags
    editor.setCursor({ line: cursor.line, ch: cursor.ch + before.length });
  }
  editor.focus();
}

function insertAtCursor(editor, text) {
  const cursor = editor.getCursor();
  editor.replaceRange(text, cursor);
  editor.focus();
}

function handleFormatting(editor, format) {
  switch (format) {
    case "h1":
      wrapSelection(editor, '<h1 class="text-4xl font-bold mb-4">', "</h1>");
      break;
    case "h2":
      wrapSelection(editor, '<h2 class="text-3xl font-bold mb-3">', "</h2>");
      break;
    case "h3":
      wrapSelection(editor, '<h3 class="text-2xl font-bold mb-2">', "</h3>");
      break;
    case "p":
      wrapSelection(editor, '<p class="mb-4">', "</p>");
      break;
    case "bold":
      wrapSelection(editor, '<strong class="font-bold">', "</strong>");
      break;
    case "italic":
      wrapSelection(editor, '<em class="italic">', "</em>");
      break;
    case "ul":
      wrapSelection(
        editor,
        '<ul class="list-disc list-inside mb-4">\n  <li>',
        "</li>\n</ul>"
      );
      break;
    case "ol":
      wrapSelection(
        editor,
        '<ol class="list-decimal list-inside mb-4">\n  <li>',
        "</li>\n</ol>"
      );
      break;
    case "link":
      const url = prompt("Masukkan URL:", "https://");
      if (url) {
        wrapSelection(
          editor,
          `<a href="${url}" class="text-blue-600 hover:text-blue-800 hover:underline">`,
          "</a>"
        );
      }
      break;
    case "image":
      const imageUrl = prompt("Masukkan URL gambar:", "https://");
      if (imageUrl) {
        const alt = prompt("Masukkan teks alternatif:", "");
        insertAtCursor(
          editor,
          `<img src="${imageUrl}" alt="${alt}" class="max-w-full h-auto rounded-lg shadow-md my-4" />`
        );
      }
      break;
  }
}

// Initialize CodeMirror instances
let createEditor, editEditor;

document.addEventListener("DOMContentLoaded", function () {
  // Initialize CodeMirror for create form
  const createContentTextarea = document.getElementById("content");
  const createEditorContainer = document.getElementById("content-editor");

  if (createContentTextarea && createEditorContainer) {
    createEditor = CodeMirror(createEditorContainer, {
      value: createContentTextarea.value || "",
      mode: "htmlmixed",
      theme: "monokai",
      lineNumbers: false,
      autoCloseTags: true,
      matchBrackets: true,
      matchTags: true,
      foldGutter: true,
      lineWrapping: true,
      indentUnit: 2,
      tabSize: 2,
      indentWithTabs: false,
      extraKeys: {
        "Ctrl-Space": "autocomplete",
        "Ctrl-/": "toggleComment",
        Tab: "indentMore",
        "Shift-Tab": "indentLess",
      },
      hintOptions: {
        completeSingle: false,
      },
      gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
      autoCloseBrackets: true,
      matchBrackets: true,
      styleActiveLine: true,
      lineWiseCopyCut: true,
      pasteLinesPerSelection: true,
      smartIndent: true,
      electricChars: true,
      workTime: 200,
      workDelay: 300,
      undoDepth: 200,
      historyEventDelay: 1250,
      viewportMargin: 10,
      maxHighlightLength: 10000,
      moveInputWithCursor: true,
      dragDrop: true,
      autofocus: true,
    });

    // Sync CodeMirror content with textarea
    createEditor.on("change", function () {
      createContentTextarea.value = createEditor.getValue();
    });

    // Add formatting button handlers for create form
    const createFormatButtons = document.querySelectorAll(
      "#createContentForm .format-btn"
    );
    createFormatButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const format = this.getAttribute("data-format");
        handleFormatting(createEditor, format);
      });
    });
  }

  // Initialize CodeMirror for edit form
  const editContentTextarea = document.getElementById("edit_content");
  const editEditorContainer = document.getElementById("edit-content-editor");

  if (editContentTextarea && editEditorContainer) {
    editEditor = CodeMirror(editEditorContainer, {
      value: editContentTextarea.value || "",
      mode: "htmlmixed",
      theme: "monokai",
      lineNumbers: false,
      autoCloseTags: true,
      matchBrackets: true,
      matchTags: true,
      foldGutter: true,
      lineWrapping: true,
      indentUnit: 2,
      tabSize: 2,
      indentWithTabs: false,
      extraKeys: {
        "Ctrl-Space": "autocomplete",
        "Ctrl-/": "toggleComment",
        Tab: "indentMore",
        "Shift-Tab": "indentLess",
      },
      hintOptions: {
        completeSingle: false,
      },
      gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
      autoCloseBrackets: true,
      matchBrackets: true,
      styleActiveLine: true,
      lineWiseCopyCut: true,
      pasteLinesPerSelection: true,
      smartIndent: true,
      electricChars: true,
      workTime: 200,
      workDelay: 300,
      undoDepth: 200,
      historyEventDelay: 1250,
      viewportMargin: 10,
      maxHighlightLength: 10000,
      moveInputWithCursor: true,
      dragDrop: true,
      autofocus: true,
    });

    // Sync CodeMirror content with textarea
    editEditor.on("change", function () {
      editContentTextarea.value = editEditor.getValue();
    });

    // Add formatting button handlers for edit form
    const editFormatButtons = document.querySelectorAll(
      "#editContentForm .format-btn"
    );
    editFormatButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const format = this.getAttribute("data-format");
        handleFormatting(editEditor, format);
      });
    });
  }

  // Get current page from URL
  function getCurrentPage() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get("page") || 1;
  }

  // Update URL with new page
  function updatePageUrl(page) {
    const url = new URL(window.location.href);
    url.searchParams.set("page", page);
    window.history.pushState({}, "", url);
  }

  // Create form submission
  const createForm = document.getElementById("createContentForm");
  if (createForm) {
    createForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);

      // Get content from CodeMirror
      formData.set("content", createEditor.getValue());

      const submitButton = this.querySelector('button[type="submit"]');
      const originalButtonText = submitButton.innerHTML;

      try {
        // Show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = `
          <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>Menyimpan...</span>
        `;

        const response = await fetch("utils/create_content.php", {
          method: "POST",
          body: formData,
        });

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        if (result.success) {
          showToast("Berita berhasil dibuat");
          // Redirect to first page after creating new content
          setTimeout(() => {
            window.location.href = "?page=1";
          }, 1000);
        } else {
          throw new Error(result.message || "Gagal membuat berita");
        }
      } catch (error) {
        console.error("Error:", error);
        showToast(
          error.message || "Terjadi kesalahan saat membuat berita",
          "error"
        );
        // Reset button state on error
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
      }
    });
  }

  // Edit functionality
  const editButtons = document.querySelectorAll(".edit-content");
  editButtons.forEach((button) => {
    button.addEventListener("click", async function () {
      const id = this.getAttribute("data-id");

      try {
        // Fetch content data
        const response = await fetch(`utils/get_content.php?id=${id}`);
        if (!response.ok) {
          throw new Error("Failed to fetch content");
        }
        const data = await response.json();

        if (!data.success) {
          throw new Error(data.message || "Failed to fetch content");
        }

        const content = data.data;
        console.log("Content data:", content); // Debug log

        // Populate form fields
        document.getElementById("edit_id").value = content.id;
        document.getElementById("edit_title").value = content.title;
        document.getElementById("edit_slug").value = content.slug;
        document.getElementById("edit_description").value =
          content.description || "";

        // Set content in CodeMirror
        if (editEditor) {
          editEditor.setValue(content.content || "");
        }

        // Handle image display
        const currentImage = document.getElementById("current_image");
        const imageInputContainer = document.getElementById(
          "image_input_container"
        );
        const currentImagePreview = document.getElementById(
          "current_image_preview"
        );

        if (content.image) {
          currentImagePreview.src = `/dashboard/uploads/berita/${content.image}`;
          currentImage.classList.remove("hidden");
          imageInputContainer.classList.add("hidden");
        } else {
          currentImage.classList.add("hidden");
          imageInputContainer.classList.remove("hidden");
        }
      } catch (error) {
        console.error("Error:", error);
        showToast(error.message || "Failed to load content", "error");
      }
    });
  });

  // Edit form submission
  const editForm = document.getElementById("editContentForm");
  if (editForm) {
    editForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const currentPage = getCurrentPage();

      // Get content from CodeMirror
      formData.set("content", editEditor.getValue());

      // Ensure description is set
      const description = document.getElementById("edit_description").value;
      console.log("Description value:", description); // Debug log
      formData.set("description", description);

      const submitButton = this.querySelector('button[type="submit"]');
      const originalButtonText = submitButton.innerHTML;

      // Show loading state
      submitButton.disabled = true;
      submitButton.innerHTML = `
        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Memperbarui...
      `;

      try {
        const response = await fetch("utils/edit_content.php", {
          method: "POST",
          body: formData,
        });

        const result = await response.json();
        if (result.success) {
          showToast("Berita berhasil diperbarui");
          // Maintain current page after update
          setTimeout(() => {
            window.location.href = `?page=${currentPage}`;
          }, 1000);
        } else {
          showToast(result.message || "Gagal memperbarui berita", "error");
          // Reset button state on error
          submitButton.disabled = false;
          submitButton.innerHTML = originalButtonText;
        }
      } catch (error) {
        console.error("Error:", error);
        showToast("Terjadi kesalahan saat memperbarui berita", "error");
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
      const currentPage = getCurrentPage();
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
            showToast("Berita berhasil dihapus");
            // Maintain current page after deletion
            setTimeout(() => {
              window.location.href = `?page=${currentPage}`;
            }, 1000);
          } else {
            showToast(result.message || "Gagal menghapus berita", "error");
            deleteText.classList.remove("hidden");
            deleteLoading.classList.add("hidden");
            confirmDeleteBtn.disabled = false;
          }
        } catch (error) {
          console.error("Error:", error);
          showToast("Terjadi kesalahan saat menghapus berita", "error");
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

  // Edit form image handling
  const editImageInput = document.getElementById("edit_image");
  const imageInputContainer = document.getElementById("image_input_container");
  const currentImage = document.getElementById("current_image");
  const changeImageBtn = document.getElementById("change_image_btn");
  const editImagePreview = document.getElementById("current_image_preview");

  if (editImageInput && changeImageBtn) {
    changeImageBtn.addEventListener("click", function () {
      imageInputContainer.classList.remove("hidden");
      currentImage.classList.add("hidden");
      editImageInput.value = "";
    });

    editImageInput.addEventListener("change", function () {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          editImagePreview.src = e.target.result;
          currentImage.classList.remove("hidden");
          imageInputContainer.classList.add("hidden");
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  }

  // Auto-generate slug from title
  const titleInput = document.getElementById("title");
  const slugInput = document.getElementById("slug");
  if (titleInput && slugInput) {
    // Make slug input readonly
    slugInput.setAttribute("readonly", true);

    titleInput.addEventListener("input", function () {
      const slug = this.value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, "-")
        .replace(/(^-|-$)/g, "");
      slugInput.value = slug;
    });
  }

  // Auto-generate slug from title in edit form
  const editTitleInput = document.getElementById("edit_title");
  const editSlugInput = document.getElementById("edit_slug");
  if (editTitleInput && editSlugInput) {
    // Make edit slug input readonly
    editSlugInput.setAttribute("readonly", true);

    editTitleInput.addEventListener("input", function () {
      const slug = this.value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, "-")
        .replace(/(^-|-$)/g, "");
      editSlugInput.value = slug;
    });
  }

  // View Content
  document.querySelectorAll(".view-content").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.getAttribute("data-id");

      // Fetch content details
      fetch(`/dashboard/berita/get_content.php?id=${id}`)
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            const content = data.content;

            // Update modal content
            document.getElementById(
              "view_image"
            ).src = `/dashboard/uploads/berita/${content.image}`;
            document.getElementById("view_title").textContent = content.title;
            document.getElementById("view_slug").textContent = content.slug;

            // Render the HTML content in the prose container
            const viewContentDiv = document
              .getElementById("view_content")
              .querySelector(".prose");
            if (viewContentDiv) {
              viewContentDiv.innerHTML = content.content;

              // Add custom styling to blockquotes
              const blockquotes =
                viewContentDiv.getElementsByTagName("blockquote");
              Array.from(blockquotes).forEach((blockquote) => {
                blockquote.style.borderLeft = "4px solid #3b82f6";
                blockquote.style.paddingLeft = "1rem";
                blockquote.style.paddingTop = "0.5rem";
                blockquote.style.paddingBottom = "0.5rem";
                blockquote.style.margin = "1rem 0";
                blockquote.style.fontStyle = "italic";
                blockquote.style.color = "#374151";
                blockquote.style.backgroundColor = "#f9fafb";
                blockquote.style.borderRadius = "0 0.5rem 0.5rem 0";
              });
            }
          } else {
            showToast("error", "Gagal mengambil data berita");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          showToast("error", "Terjadi kesalahan saat mengambil data");
        });
    });
  });

  // Clean up CodeMirror instance when modal is closed
  document
    .getElementById("viewContentModal")
    .addEventListener("hidden.bs.modal", function () {
      if (window.viewEditor) {
        window.viewEditor.toTextArea();
        window.viewEditor = null;
      }
    });
});

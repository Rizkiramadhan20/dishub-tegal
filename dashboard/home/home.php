<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../../../index.php");
    exit;
}
$user = $_SESSION['user'];

// Include database configuration
require_once '../../config/db.php';

// Fetch all content
$result = $db->query("SELECT * FROM home ORDER BY created_at DESC");
$contents = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $contents[] = $row;
    }
}

// Include sidebar dan header
include '../sidebar.php';
include '../header.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Home - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <!-- Preload critical resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" as="style">
    <link rel="preload" href="https://cdn.boxicons.com/fonts/basic/boxicons.min.css" as="style">

    <!-- Load styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>

    <!-- Load Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js" defer></script>
</head>

<body class="bg-gray-50">
    <section class="ml-0 sm:ml-64 pt-16 sm:pt-20 px-4 sm:px-8 min-h-screen transition-all duration-200">
        <div class="p-6 rounded-xl bg-white shadow-lg transition-shadow duration-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="space-y-2">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Home Content</h2>
                    <p class="text-gray-600 text-sm sm:text-base">Manage and organize your home page content efficiently
                    </p>
                </div>

                <?php if (empty($contents)): ?>
                <button data-modal-target="createContentModal" data-modal-toggle="createContentModal"
                    class="inline-flex items-center gap-2 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none transition-colors duration-200">
                    <i class='bx bx-plus text-xl'></i>
                    Create Content
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Content List -->
        <div class="mt-6">
            <?php if (empty($contents)): ?>
            <div class="flex flex-col items-center justify-center py-12 px-4">
                <svg class="w-32 h-32 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z">
                    </path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Content Found</h3>
                <p class="text-gray-500 text-center mb-4">Start by creating your first content to display here.</p>
                <button data-modal-target="createContentModal" data-modal-toggle="createContentModal"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none transition-colors duration-200">
                    Create Your First Content
                </button>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 gap-6">
                <?php foreach ($contents as $content): ?>
                <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class="aspect-[3/1] w-full">
                        <img class="rounded-t-lg w-full h-full object-cover"
                            src="/dashboard/uploads/home/<?php echo htmlspecialchars($content['image']); ?>"
                            alt="<?php echo htmlspecialchars($content['title']); ?>" />
                    </div>
                    <div class="p-6">
                        <h5 class="mb-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            <?php echo htmlspecialchars($content['title']); ?>
                        </h5>
                        <p class="mb-4 text-gray-700 dark:text-gray-400">
                            <?php echo htmlspecialchars($content['description']); ?>
                        </p>
                        <div class="flex justify-end gap-3">
                            <button type="button"
                                class="edit-content inline-flex items-center px-4 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                data-id="<?php echo $content['id']; ?>" data-modal-target="editContentModal"
                                data-modal-toggle="editContentModal">
                                <i class='bx bx-edit text-xl mr-2'></i>
                                Edit
                            </button>
                            <button type="button"
                                class="delete-content inline-flex items-center px-4 py-2.5 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                                data-id="<?php echo $content['id']; ?>" data-modal-target="deleteContentModal"
                                data-modal-toggle="deleteContentModal">
                                <i class='bx bx-trash text-xl mr-2'></i>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Create Modal -->
        <div id="createContentModal" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-4xl max-h-full overflow-x-hidden overflow-y-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <div class="flex items-center gap-3">
                            <i class='bx bx-plus-circle text-2xl text-blue-600'></i>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Create New Content
                            </h3>
                        </div>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="createContentModal">
                            <i class='bx bx-x text-xl'></i>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form id="createContentForm" class="p-6 space-y-6" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Enter content title" required>
                            </div>

                            <div>
                                <label for="description"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Description <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" name="description" rows="3"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Enter content description" required></textarea>
                            </div>

                            <div>
                                <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Image <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="image"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i
                                                class='bx bx-cloud-upload text-3xl text-gray-500 dark:text-gray-400 mb-2'></i>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                                    class="font-semibold">Click to upload</span> or drag and drop</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG or JPEG (MAX.
                                                2MB)</p>
                                        </div>
                                        <input type="file" id="image" name="image" accept="image/*" class="hidden"
                                            required>
                                    </label>
                                </div>
                                <div id="image-preview" class="mt-4 hidden">
                                    <div class="relative inline-block">
                                        <img src="" alt="Preview" class="max-w-full h-auto rounded-lg shadow-md">
                                        <button type="button"
                                            class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition-colors duration-200">
                                            <i class='bx bx-x text-xl'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button type="button" data-modal-hide="createContentModal"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                Cancel
                            </button>
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Create Content
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="editContentModal" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-4xl max-h-full overflow-x-hidden overflow-y-auto">
                <div class="relative bg-white rounded-lg shadow-xl max-h-[90vh] flex flex-col">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Edit Content
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center transition-colors duration-200"
                            data-modal-hide="editContentModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <form id="editContentForm" class="p-6 space-y-6 overflow-y-auto" enctype="multipart/form-data">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="edit_title"
                                    class="block mb-2 text-sm font-medium text-gray-900">Title</label>
                                <input type="text" id="edit_title" name="title"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    required>
                            </div>

                            <div>
                                <label for="edit_description"
                                    class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                                <textarea id="edit_description" name="description" rows="3"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    required></textarea>
                            </div>

                            <div>
                                <label for="edit_image"
                                    class="block mb-2 text-sm font-medium text-gray-900">Image</label>
                                <div id="image_input_container" class="mb-4">
                                    <div class="flex items-center justify-center w-full">
                                        <label for="edit_image"
                                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i
                                                    class='bx bx-cloud-upload text-3xl text-gray-500 dark:text-gray-400 mb-2'></i>
                                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                                        class="font-semibold">Click to upload</span> or drag and drop
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG or JPEG
                                                    (MAX. 2MB)</p>
                                            </div>
                                            <input type="file" id="edit_image" name="image" accept="image/*"
                                                class="hidden">
                                        </label>
                                    </div>
                                </div>
                                <div id="current_image" class="mt-4">
                                    <div class="relative inline-block">
                                        <img id="current_image_preview" class="max-w-full h-auto rounded-lg shadow-md"
                                            src="" alt="Current image">
                                        <button type="button" id="change_image_btn"
                                            class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition-colors duration-200">
                                            <i class='bx bx-x text-xl'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <button type="button" data-modal-hide="editContentModal"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 transition-colors duration-200">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 transition-colors duration-200">
                                Update Content
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div id="deleteContentModal" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md mx-auto my-4 sm:my-8">
                <div class="relative bg-white rounded-lg shadow">
                    <div class="flex items-start justify-between p-4 border-b rounded-t border-gray-200">
                        <div class="flex items-center gap-3">
                            <i class='bx bx-trash text-2xl text-red-600'></i>
                            <h3 class="text-xl font-semibold text-gray-900">
                                Delete Content
                            </h3>
                        </div>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                            data-modal-hide="deleteContentModal">
                            <i class='bx bx-x text-xl'></i>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">Are you sure you want to delete this content? This action cannot
                            be undone.</p>
                        <div class="flex items-center justify-end space-x-2">
                            <button type="button" data-modal-hide="deleteContentModal"
                                class="text-gray-700 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 text-center">Cancel</button>
                            <button type="button" id="confirmDelete"
                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 text-center">
                                <span class="delete-text">Delete</span>
                                <span class="delete-loading hidden">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="js/home.js"></script>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50"></div>
</body>

</html>
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../../../index.php");
    exit;
}
$user = $_SESSION['user'];

// Include database configuration
require_once '../../config/db.php';

// Pagination settings
$items_per_page = 8; // Number of items per page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Get total number of items
$total_items_query = $db->query("SELECT COUNT(*) as count FROM berita");
$total_items = $total_items_query->fetch_assoc()['count'];
$total_pages = ceil($total_items / $items_per_page);

// Fetch paginated content
$result = $db->query("SELECT * FROM berita ORDER BY created_at DESC LIMIT $offset, $items_per_page");
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
    <title>Berita - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/assets/logo.png">

    <!-- Preload fonts -->
    <link rel="preload" href="https://fonts.gstatic.com/s/materialicons/v143/flUhRq6tzZclQEJ-Vdg-IuiaDsNc.woff2"
        as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://cdn.boxicons.com/fonts/basic/boxicons.min.css" as="style">

    <!-- Preload critical resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" as="style">

    <!-- Load styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- CodeMirror CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css" rel="stylesheet">

    <!-- Load Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js" defer></script>

    <!-- CodeMirror JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closetag.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/matchbrackets.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/matchtags.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/xml-fold.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/show-hint.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/xml-hint.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/html-hint.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/javascript-hint.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/css-hint.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/show-hint.min.css" rel="stylesheet">

    <!-- Initialize Flowbite -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all modals
        const modals = [
            'createContentModal',
            'editContentModal',
            'deleteContentModal'
        ];

        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal) {
                const modalInstance = new Modal(modal, {
                    placement: 'center',
                    backdrop: 'dynamic',
                    backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
                    closable: true,
                    onHide: () => {
                        console.log('modal is hidden');
                    },
                    onShow: () => {
                        console.log('modal is shown');
                    },
                    onToggle: () => {
                        console.log('modal has been toggled');
                    }
                });
            }
        });
    });
    </script>
</head>

<body class="bg-gray-50">
    <section class="ml-0 sm:ml-64 pt-16 sm:pt-20 py-4 px-4 min-h-screen transition-all duration-200">
        <div class="p-6 rounded-xl bg-white shadow-lg transition-shadow duration-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="space-y-2">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Berita</h2>
                    <p class="text-gray-600 text-sm sm:text-base">Kelola berita dan artikel untuk website Dishub Tegal
                    </p>
                </div>

                <button data-modal-target="createContentModal" data-modal-toggle="createContentModal"
                    class="inline-flex items-center gap-2 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none transition-colors duration-200">
                    <i class='bx bx-plus text-xl'></i>
                    Tambah Berita
                </button>
            </div>
        </div>

        <!-- Content List -->
        <div class="mt-6">
            <?php if (empty($contents)): ?>
            <div class="flex flex-col items-center justify-center py-12 px-4">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-xl font-semibold text-gray-900">Belum Ada Berita</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan berita pertama Anda.</p>
                    <div class="mt-6">
                        <button data-modal-target="createContentModal" data-modal-toggle="createContentModal"
                            type="button"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class='bx bx-plus mr-2 text-xl'></i>
                            Tambah Berita Pertama
                        </button>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($contents as $content): ?>
                <div
                    class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="relative group">
                        <img src="/dashboard/uploads/berita/<?php echo htmlspecialchars($content['image']); ?>"
                            alt="<?php echo htmlspecialchars($content['title']); ?>"
                            class="w-full h-48 object-cover rounded-t-lg">
                        <div
                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-t-lg">
                            <div
                                class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button type="button"
                                    class="view-content p-2 text-white bg-gray-800/80 hover:bg-gray-900/80 rounded-full transition-colors duration-200"
                                    data-id="<?php echo $content['id']; ?>" data-modal-target="viewContentModal"
                                    data-modal-toggle="viewContentModal">
                                    <i class='bx bx-show-alt text-xl'></i>
                                </button>
                                <button type="button"
                                    class="edit-content p-2 text-white bg-gray-800/80 hover:bg-gray-900/80 rounded-full transition-colors duration-200"
                                    data-id="<?php echo $content['id']; ?>" data-modal-target="editContentModal"
                                    data-modal-toggle="editContentModal">
                                    <i class='bx bx-edit text-xl'></i>
                                </button>
                                <button type="button"
                                    class="delete-content p-2 text-white bg-gray-800/80 hover:bg-gray-900/80 rounded-full transition-colors duration-200"
                                    data-id="<?php echo $content['id']; ?>" data-modal-target="deleteContentModal"
                                    data-modal-toggle="deleteContentModal">
                                    <i class='bx bx-trash text-xl'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3
                            class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 hover:text-blue-600 transition-colors duration-200">
                            <?php echo htmlspecialchars($content['title']); ?>
                        </h3>
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?php echo htmlspecialchars($content['slug']); ?>
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                            <?php echo htmlspecialchars($content['description']); ?>
                        </p>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class='bx bx-time-five mr-1'></i>
                            <span><?php echo date('d M Y', strtotime($content['created_at'])); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="mt-8 flex justify-center">
                <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <?php if ($current_page > 1): ?>
                    <a href="?page=<?php echo $current_page - 1; ?>"
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <i class='bx bx-chevron-left text-xl'></i>
                    </a>
                    <?php endif; ?>

                    <?php
                    $start_page = max(1, $current_page - 2);
                    $end_page = min($total_pages, $current_page + 2);

                    if ($start_page > 1) {
                        echo '<a href="?page=1" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</a>';
                        if ($start_page > 2) {
                            echo '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>';
                        }
                    }

                    for ($i = $start_page; $i <= $end_page; $i++) {
                        $active_class = $i === $current_page ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50';
                        echo "<a href=\"?page={$i}\" class=\"relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium {$active_class}\">{$i}</a>";
                    }

                    if ($end_page < $total_pages) {
                        if ($end_page < $total_pages - 1) {
                            echo '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>';
                        }
                        echo "<a href=\"?page={$total_pages}\" class=\"relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50\">{$total_pages}</a>";
                    }
                    ?>

                    <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo $current_page + 1; ?>"
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <i class='bx bx-chevron-right text-xl'></i>
                    </a>
                    <?php endif; ?>
                </nav>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- View Modal -->
        <div id="viewContentModal" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-4xl max-h-full overflow-x-hidden overflow-y-auto">
                <div class="relative bg-white rounded-lg shadow-xl max-h-[90vh] flex flex-col">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Detail Berita
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center transition-colors duration-200"
                            data-modal-hide="viewContentModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-6 overflow-y-auto">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Gambar</h4>
                                <img id="view_image" src="" alt="News Image"
                                    class="max-w-full h-auto rounded-lg shadow-md">
                            </div>

                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Judul</h4>
                                <p id="view_title" class="text-gray-700"></p>
                            </div>

                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Slug</h4>
                                <p id="view_slug" class="text-gray-700"></p>
                            </div>

                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Konten</h4>
                                <div id="view_content" class="border border-gray-300 rounded-lg p-4 bg-white">
                                    <div
                                        class="prose prose-lg max-w-none [&>blockquote]:border-l-4 [&>blockquote]:border-blue-500 [&>blockquote]:pl-4 [&>blockquote]:py-2 [&>blockquote]:my-4 [&>blockquote]:italic [&>blockquote]:text-gray-700 [&>blockquote]:bg-gray-50 [&>blockquote]:rounded-r-lg">
                                        <!-- Content will be rendered here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end gap-3 p-4 border-t border-gray-200">
                        <button type="button" data-modal-hide="viewContentModal"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 transition-colors duration-200">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
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
                                Tambah Berita Baru
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
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="title"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Judul <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="title" name="title"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                        placeholder="Masukkan judul berita" required>
                                </div>

                                <div>
                                    <label for="slug"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Slug <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="slug" name="slug" readonly
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                        placeholder="Slug akan dibuat otomatis dari judul">
                                </div>
                            </div>

                            <div>
                                <label for="description"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Deskripsi <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" name="description" rows="3"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Masukkan deskripsi singkat berita" required></textarea>
                            </div>

                            <div>
                                <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Gambar <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="image"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i
                                                class='bx bx-cloud-upload text-3xl text-gray-500 dark:text-gray-400 mb-2'></i>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                                    class="font-semibold">Klik untuk upload</span> atau drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG atau JPEG (MAX.
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

                            <div>
                                <label for="content"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Konten <span class="text-red-500">*</span>
                                </label>
                                <div class="mb-2 p-2 bg-white border border-gray-200 rounded-lg shadow-sm">
                                    <div class="flex flex-wrap gap-1">
                                        <div class="flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-md">
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="h1" title="Heading 1">
                                                <i class='bx bx-heading text-gray-600 text-lg'></i>
                                            </button>
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="h2" title="Heading 2">
                                                <i class='bx bx-heading text-gray-600 text-base'></i>
                                            </button>
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="h3" title="Heading 3">
                                                <i class='bx bx-heading text-gray-600 text-sm'></i>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-md">
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="bold" title="Bold">
                                                <i class='bx bx-bold text-gray-600'></i>
                                            </button>
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="italic" title="Italic">
                                                <i class='bx bx-italic text-gray-600'></i>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-md">
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="ul" title="Unordered List">
                                                <i class='bx bx-list-ul text-gray-600'></i>
                                            </button>
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="ol" title="Ordered List">
                                                <i class='bx bx-list-ol text-gray-600'></i>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-md">
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="link" title="Insert Link">
                                                <i class='bx bx-link text-gray-600'></i>
                                            </button>
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="image" title="Insert Image">
                                                <i class='bx bx-image text-gray-600'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="content-editor" class="border border-gray-300 rounded-lg"></div>
                                <textarea id="content" name="content" class="hidden" required></textarea>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button type="button" data-modal-hide="createContentModal"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                Batal
                            </button>
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Simpan Berita
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
                            Edit Berita
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
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="edit_title"
                                        class="block mb-2 text-sm font-medium text-gray-900">Judul</label>
                                    <input type="text" id="edit_title" name="title"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        required>
                                </div>

                                <div>
                                    <label for="edit_slug"
                                        class="block mb-2 text-sm font-medium text-gray-900">Slug</label>
                                    <input type="text" id="edit_slug" name="slug" readonly
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        required>
                                </div>
                            </div>

                            <div>
                                <label for="edit_description"
                                    class="block mb-2 text-sm font-medium text-gray-900">Deskripsi</label>
                                <textarea id="edit_description" name="description" rows="3"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    required></textarea>
                            </div>

                            <div>
                                <label for="edit_content"
                                    class="block mb-2 text-sm font-medium text-gray-900">Konten</label>
                                <div class="mb-2 p-2 bg-white border border-gray-200 rounded-lg shadow-sm">
                                    <div class="flex flex-wrap gap-1">
                                        <div class="flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-md">
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="h1" title="Heading 1">
                                                <i class='bx bx-heading text-gray-600 text-lg'></i>
                                            </button>
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="h2" title="Heading 2">
                                                <i class='bx bx-heading text-gray-600 text-base'></i>
                                            </button>
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="h3" title="Heading 3">
                                                <i class='bx bx-heading text-gray-600 text-sm'></i>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-md">
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="bold" title="Bold">
                                                <i class='bx bx-bold text-gray-600'></i>
                                            </button>
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="italic" title="Italic">
                                                <i class='bx bx-italic text-gray-600'></i>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-md">
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="ul" title="Unordered List">
                                                <i class='bx bx-list-ul text-gray-600'></i>
                                            </button>
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="ol" title="Ordered List">
                                                <i class='bx bx-list-ol text-gray-600'></i>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-md">
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="link" title="Insert Link">
                                                <i class='bx bx-link text-gray-600'></i>
                                            </button>
                                            <button type="button" class="format-btn hover:bg-gray-100 p-1 rounded"
                                                data-format="image" title="Insert Image">
                                                <i class='bx bx-image text-gray-600'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="edit-content-editor" class="border border-gray-300 rounded-lg"></div>
                                <textarea id="edit_content" name="content" class="hidden" required></textarea>
                            </div>

                            <div>
                                <label for="edit_image"
                                    class="block mb-2 text-sm font-medium text-gray-900">Gambar</label>
                                <div id="image_input_container" class="mb-4">
                                    <div class="flex items-center justify-center w-full">
                                        <label for="edit_image"
                                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i
                                                    class='bx bx-cloud-upload text-3xl text-gray-500 dark:text-gray-400 mb-2'></i>
                                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                                        class="font-semibold">Klik untuk upload</span> atau drag and
                                                    drop
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG atau JPEG
                                                    (MAX. 2MB)</p>
                                            </div>
                                            <input type="file" id="edit_image" name="image" accept="image/*"
                                                class="hidden">
                                        </label>
                                    </div>
                                </div>
                                <div id="current_image" class="mt-4 hidden">
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
                                Batal
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 transition-colors duration-200">
                                Update Berita
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
                                Hapus Berita
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
                        <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus berita ini? Tindakan ini tidak
                            dapat dibatalkan.</p>
                        <div class="flex items-center justify-end space-x-2">
                            <button type="button" data-modal-hide="deleteContentModal"
                                class="text-gray-700 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 text-center">Batal</button>
                            <button type="button" id="confirmDelete"
                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 text-center">
                                <span class="delete-text">Hapus</span>
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

    <script src="js/berita.js"></script>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50"></div>
</body>

</html>
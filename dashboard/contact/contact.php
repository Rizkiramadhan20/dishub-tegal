<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Dishub Tegal</title>
    <!-- Core Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Flowbite -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</head>

<body class="bg-gray-50">
    <?php include '../sidebar.php'; ?>
    <?php include '../header.php'; ?>
    <?php
    require_once '../../config/db.php';
    
    // Fetch all contact messages
    $query = "SELECT * FROM contacts ORDER BY created_at DESC";
    $result = mysqli_query($db, $query);
    ?>

    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 rounded-lg mt-14">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-900">Contact Messages</h1>
            </div>

            <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Message</th>
                            <th scope="col" class="px-6 py-3">Date</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($row['email']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs truncate">
                                    <?php echo htmlspecialchars($row['message']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo date('d M Y H:i', strtotime($row['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($row['status'] === 'unread'): ?>
                                <span
                                    class="inline-block px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Belum
                                    Dibaca</span>
                                <?php else: ?>
                                <span
                                    class="inline-block px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Sudah
                                    Dibaca</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <button type="button" class="view-message-btn text-blue-600 hover:text-blue-900"
                                    data-modal-target="messageModal" data-modal-toggle="messageModal"
                                    data-message="<?php echo htmlspecialchars($row['message']); ?>"
                                    data-name="<?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>"
                                    data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                    data-date="<?php echo date('d M Y H:i', strtotime($row['created_at'])); ?>"
                                    data-id="<?php echo $row['id']; ?>">
                                    <i class='bx bx-show text-xl'></i>
                                </button>
                                <button type="button" class="delete-message-btn text-red-600 hover:text-red-900 ml-2"
                                    data-id="<?php echo $row['id']; ?>">
                                    <i class='bx bx-trash text-xl'></i>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-6">
                    <i class='bx bx-message-square-detail text-4xl text-blue-600'></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Messages Yet</h3>
                <p class="text-gray-600">There are no contact messages in the database.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full mx-auto">
            <div class="relative bg-white rounded-lg shadow">
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900" id="modalName"></h3>
                        <p class="text-sm text-gray-600" id="modalEmail"></p>
                        <p class="text-xs text-gray-500" id="modalDate"></p>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                        data-modal-hide="messageModal">
                        <i class='bx bx-x text-xl'></i>
                    </button>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 whitespace-pre-wrap" id="modalMessage"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50"></div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/30 flex items-center justify-center">
        <div class="relative w-full max-w-md max-h-full mx-auto">
            <div class="relative bg-white rounded-lg shadow">
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                    <button type="button" id="closeDeleteModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                        <i class='bx bx-x text-xl'></i>
                    </button>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-6">Apakah Anda yakin ingin menghapus pesan ini?</p>
                    <div class="flex justify-end gap-2">
                        <button type="button" id="cancelDeleteBtn"
                            class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Batal</button>
                        <button type="button" id="confirmDeleteBtn"
                            class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 flex items-center gap-2">
                            <span class="delete-text">Ya, Hapus</span>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle view message button clicks
        const viewButtons = document.querySelectorAll('.view-message-btn');
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const message = this.getAttribute('data-message');
                const name = this.getAttribute('data-name');
                const email = this.getAttribute('data-email');
                const date = this.getAttribute('data-date');

                document.getElementById('modalName').textContent = name;
                document.getElementById('modalEmail').textContent = email;
                document.getElementById('modalDate').textContent = date;
                document.getElementById('modalMessage').textContent = message;
            });
        });
    });
    </script>
    <script src="js/contact.js"></script>
</body>

</html>
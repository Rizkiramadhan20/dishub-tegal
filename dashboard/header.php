<?php
// Header Dashboard
if (!isset($user)) {
    session_start();
    $user = $_SESSION['user'] ?? null;
}
// Notif Contact
$unread_contacts = 0;
$unread_contacts_list = [];
try {
    require_once __DIR__ . '/../config/db.php';
    $unread_result = $db->query("SELECT COUNT(*) as unread FROM contacts WHERE is_read = 0");
    if ($unread_result) {
        $unread_contacts = (int)$unread_result->fetch_assoc()['unread'];
    }
    // Ambil semua pesan contact yang belum dibaca, urut terbaru
    $unread_list_result = $db->query("SELECT name, email, created_at FROM contacts WHERE is_read = 0 ORDER BY created_at DESC");
    if ($unread_list_result) {
        while ($row = $unread_list_result->fetch_assoc()) {
            $unread_contacts_list[] = $row;
        }
    }
} catch (Exception $e) {}
?>
<header class="fixed top-0 right-0 left-0 sm:left-64 z-30">
    <nav class="bg-white border-b border-gray-200 px-4 py-2.5">
        <div class="flex flex-wrap justify-between items-center">
            <div class="flex justify-start items-center">
                <button data-drawer-target="sidebar-mobile" data-drawer-toggle="sidebar-mobile"
                    aria-controls="sidebar-mobile" type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-600 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="material-icons">menu</span>
                </button>
                <span class="self-center text-xl font-semibold whitespace-nowrap text-gray-900 ml-4">Dashboard</span>
            </div>
            <div class="flex items-center lg:order-2 gap-2">
                <!-- Notifications -->
                <button type="button" data-dropdown-toggle="notification-dropdown"
                    class="p-2 text-gray-600 rounded-lg hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 relative">
                    <span class="material-icons">notifications</span>
                    <?php if ($unread_contacts > 0): ?>
                    <span
                        class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full"><?php echo $unread_contacts; ?></span>
                    <?php endif; ?>
                </button>
                <!-- Dropdown menu -->
                <div class="hidden overflow-hidden z-50 my-4 max-w-sm text-base list-none bg-white rounded-lg divide-y divide-gray-200 shadow-lg"
                    id="notification-dropdown">
                    <div class="block py-2 px-4 text-base font-medium text-center text-gray-600 bg-gray-50">
                        Notifications
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <?php if (count($unread_contacts_list) > 0): ?>
                        <?php foreach ($unread_contacts_list as $contact): ?>
                        <div
                            class="flex py-3 px-4 border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex-shrink-0">
                                <span class="material-icons text-blue-600">mail</span>
                            </div>
                            <div class="pl-3 w-full">
                                <div class="text-gray-900 text-sm mb-1.5 font-semibold">
                                    <?php echo htmlspecialchars($contact['name']); ?></div>
                                <div class="text-xs text-gray-600 mb-1">
                                    <?php echo htmlspecialchars($contact['email']); ?></div>
                                <div class="text-xs text-gray-500">
                                    <?php echo date('d M Y H:i', strtotime($contact['created_at'])); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="py-4 px-4 text-center text-gray-600">No new contact messages.</div>
                        <?php endif; ?>
                    </div>
                    <a href="/dashboard/contact/contact.php"
                        class="block py-2 text-sm font-medium text-center text-gray-600 hover:bg-gray-50 transition-colors duration-200">
                        <div class="inline-flex items-center">
                            View all
                        </div>
                    </a>
                </div>

                <!-- Profile -->
                <button type="button"
                    class="flex text-sm bg-gray-50 rounded-lg md:mr-0 focus:ring-4 focus:ring-gray-200 hover:bg-gray-100 transition-colors duration-200"
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                    data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <div class="flex items-center gap-2 px-4 py-2">
                        <span class="material-icons text-gray-600">account_circle</span>
                        <span
                            class="text-gray-900 font-medium hidden sm:inline"><?= htmlspecialchars($user['fullname'] ?? 'Admin') ?></span>
                    </div>
                </button>
                <!-- Dropdown menu -->
                <div class="hidden z-50 my-4 text-base list-none bg-white rounded-lg divide-y divide-gray-200 shadow-lg"
                    id="user-dropdown">
                    <div class="py-3 px-4">
                        <span
                            class="block text-sm text-gray-900"><?= htmlspecialchars($user['fullname'] ?? 'Admin') ?></span>
                        <span
                            class="block text-sm text-gray-600 truncate"><?= htmlspecialchars($user['email'] ?? 'admin@example.com') ?></span>
                    </div>
                    <ul class="py-1" aria-labelledby="user-menu-button">
                        <li>
                            <a href="/logout.php"
                                class="flex items-center py-2 px-4 text-sm text-gray-600 hover:bg-gray-50 transition-colors duration-200">
                                <span class="material-icons text-sm mr-2">logout</span>
                                Sign out
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="../js/main.js"></script>
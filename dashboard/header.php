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
    $unread_result = $db->query("SELECT COUNT(*) as unread FROM contacts WHERE status = 'unread'");
    if ($unread_result) {
        $unread_contacts = (int)$unread_result->fetch_assoc()['unread'];
    }
    // Ambil semua pesan contact yang belum dibaca, urut terbaru
    $unread_messages = $db->query("SELECT * FROM contacts WHERE status = 'unread' ORDER BY created_at DESC LIMIT 5");
    $unread_count = $db->query("SELECT COUNT(*) as count FROM contacts WHERE status = 'unread'")->fetch_assoc()['count'];
} catch (Exception $e) {
    $unread_messages = [];
    $unread_count = 0;
}
?>
<header class="fixed top-0 right-0 left-0 sm:left-64 z-30 bg-white border-b border-gray-200">
    <div class="px-4 sm:px-8 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button type="button" class="sm:hidden text-gray-500 hover:text-gray-600" id="sidebarToggle">
                    <i class='bx bx-menu text-2xl'></i>
                </button>
                <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
            </div>
            <div class="flex items-center gap-4">
                <!-- Notifications -->
                <div class="relative">
                    <button type="button" class="relative text-gray-500 hover:text-gray-600" id="notificationButton">
                        <i class='bx bx-bell text-2xl'></i>
                        <?php if ($unread_count > 0): ?>
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            <?php echo $unread_count; ?>
                        </span>
                        <?php endif; ?>
                    </button>
                    <!-- Notification Dropdown -->
                    <div id="notificationDropdown"
                        class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <?php if ($unread_count > 0 && $unread_messages): ?>
                            <?php while ($message = $unread_messages->fetch_assoc()): ?>
                            <div class="p-4 border-b border-gray-200 hover:bg-gray-50">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class='bx bx-user text-blue-600 text-xl'></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($message['first_name'] . ' ' . $message['last_name']); ?>
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">
                                            <?php echo htmlspecialchars($message['message']); ?>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            <?php echo date('d M Y H:i', strtotime($message['created_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <div class="p-4 text-center text-gray-500">
                                No new notifications
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($unread_count > 0): ?>
                        <div class="p-4 border-t border-gray-200">
                            <a href="contact/contact.php" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                View all messages
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- User Menu -->
                <div class="relative">
                    <button type="button" class="flex items-center gap-2 text-gray-500 hover:text-gray-600"
                        id="userMenuButton">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-user text-blue-600'></i>
                        </div>
                        <span
                            class="hidden sm:block text-sm font-medium"><?php echo htmlspecialchars($user['fullname'] ?? 'Admin'); ?></span>
                    </button>
                    <!-- User Dropdown -->
                    <div id="userDropdown"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200">
                        <div class="p-2">
                            <a href="profile.php"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                <i class='bx bx-user'></i>
                                <span>Profile</span>
                            </a>
                            <a href="settings.php"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                <i class='bx bx-cog'></i>
                                <span>Settings</span>
                            </a>
                            <hr class="my-2 border-gray-200">
                            <a href="../../logout.php"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                                <i class='bx bx-log-out'></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Notification dropdown toggle
    const notificationButton = document.getElementById('notificationButton');
    const notificationDropdown = document.getElementById('notificationDropdown');

    notificationButton.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationDropdown.classList.toggle('hidden');
    });

    // User dropdown toggle
    const userMenuButton = document.getElementById('userMenuButton');
    const userDropdown = document.getElementById('userDropdown');

    userMenuButton.addEventListener('click', function(e) {
        e.stopPropagation();
        userDropdown.classList.toggle('hidden');
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationButton.contains(e.target)) {
            notificationDropdown.classList.add('hidden');
        }
        if (!userMenuButton.contains(e.target)) {
            userDropdown.classList.add('hidden');
        }
    });
});
</script>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="../js/main.js"></script>
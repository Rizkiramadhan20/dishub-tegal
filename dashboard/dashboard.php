<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
$user = $_SESSION['user'];

// Include database configuration
require_once '../config/db.php';

$recent_activities = [];
if ($activities_result) {
    while ($row = $activities_result->fetch_assoc()) {
        $recent_activities[] = $row;
    }
}

// Include sidebar dan header
include 'sidebar.php';
include 'header.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
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
    <!-- Loading indicator -->
    <div id="loading" class="loading">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <script>
    // Hide loading indicator when page is fully loaded
    window.addEventListener('load', function() {
        document.getElementById('loading').classList.add('hidden');
    });
    </script>

    <main class="ml-0 sm:ml-64 pt-16 sm:pt-20 px-4 sm:px-8 min-h-screen transition-all duration-200">
        <div class="mb-6 sm:mb-8">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Selamat datang Admin,
                <?= htmlspecialchars($user['fullname']) ?>!</h1>
            <p class="text-sm sm:text-base text-gray-600">Ini adalah halaman khusus untuk admin.</p>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 sm:mb-8">
            <div class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div
                        class="inline-flex flex-shrink-0 items-center justify-center h-8 w-8 rounded-lg bg-blue-600 text-white">
                        <i class='bx bx-user text-xl'></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600">Total Admin</h2>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div
                        class="inline-flex flex-shrink-0 items-center justify-center h-8 w-8 rounded-lg bg-green-600 text-white">
                        <i class='bx bx-briefcase text-xl'></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600">Total Projects</h2>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900"><?php echo $total_projects; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div
                        class="inline-flex flex-shrink-0 items-center justify-center h-8 w-8 rounded-lg bg-yellow-600 text-white">
                        <i class='bx bx-code-alt text-xl'></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600">Total Works</h2>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900"><?php echo $total_works; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div
                        class="inline-flex flex-shrink-0 items-center justify-center h-8 w-8 rounded-lg bg-purple-600 text-white">
                        <i class='bx bx-message-square-dots text-xl'></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600">Total Testimonials</h2>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900"><?php echo $total_testimonials; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow p-4 sm:p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900">Recent Activity</h2>
            </div>
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-600 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 sm:px-6 py-3">Type</th>
                            <th scope="col" class="px-4 sm:px-6 py-3">Title</th>
                            <th scope="col" class="px-4 sm:px-6 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_activities as $activity): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-4 sm:px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    <?php 
                                    echo match($activity['type']) {
                                        'project' => 'bg-blue-100 text-blue-800',
                                        'work' => 'bg-yellow-100 text-yellow-800',
                                        'testimonial' => 'bg-purple-100 text-purple-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                    ?>">
                                    <?php echo ucfirst($activity['type']); ?>
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4"><?php echo htmlspecialchars($activity['title']); ?></td>
                            <td class="px-4 sm:px-6 py-4">
                                <?php echo date('d M Y H:i', strtotime($activity['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>

</html>
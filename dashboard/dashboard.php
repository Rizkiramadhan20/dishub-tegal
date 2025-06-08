<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
$user = $_SESSION['user'];

// Include database configuration
require_once '../config/db.php';

// Get recent activities
$activities_query = "SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5";
$activities_result = mysqli_query($db, $activities_query);

$recent_activities = [];
if ($activities_result) {
    while ($row = $activities_result->fetch_assoc()) {
        $recent_activities[] = $row;
    }
}

// Get unread messages count
$unread_query = "SELECT COUNT(*) as count FROM contacts WHERE status = 'unread'";
$unread_result = mysqli_query($db, $unread_query);
$unread_count = mysqli_fetch_assoc($unread_result)['count'];

// Get latest unread messages
$messages_query = "SELECT * FROM contacts WHERE status = 'unread' ORDER BY created_at DESC LIMIT 5";
$messages_result = mysqli_query($db, $messages_query);

// Include sidebar dan header
include 'sidebar.php';
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dishub Tegal</title>
    <!-- Core Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Flowbite -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 rounded-lg mt-20">
            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Selamat Datang,
                    <?php echo htmlspecialchars($_SESSION['user']['fullname'] ?? 'Admin'); ?>!</h1>
                <p class="text-gray-600 mt-2">Berikut adalah ringkasan aktivitas terbaru di dashboard Anda.</p>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
                <!-- Messages Chart -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Pesan</h3>
                    <canvas id="messagesChart"></canvas>
                </div>

                <!-- Content Distribution Chart -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Konten</h3>
                    <canvas id="contentChart"></canvas>
                </div>
            </div>

            <!-- Recent Messages Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Pesan Terbaru</h2>
                    <p class="text-sm text-gray-600 mt-1">Pesan yang belum dibaca</p>
                </div>
                <div class="divide-y divide-gray-200">
                    <?php if (mysqli_num_rows($messages_result) > 0): ?>
                    <?php while ($message = mysqli_fetch_assoc($messages_result)): ?>
                    <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class='bx bx-user text-blue-600 text-xl'></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($message['first_name'] . ' ' . $message['last_name']); ?>
                                    </p>
                                    <span class="text-xs text-gray-500">
                                        <?php echo date('d M Y H:i', strtotime($message['created_at'])); ?>
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                                    <?php echo htmlspecialchars($message['message']); ?>
                                </p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">
                                        <i class='bx bx-envelope mr-1'></i>
                                        <?php echo htmlspecialchars($message['email']); ?>
                                    </span>
                                    <a href="contact/contact.php"
                                        class="inline-flex items-center text-xs text-blue-600 hover:text-blue-700">
                                        Lihat detail
                                        <i class='bx bx-right-arrow-alt ml-1'></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <div class="p-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <i class='bx bx-message-square-detail text-3xl text-gray-500'></i>
                        </div>
                        <p class="text-gray-500">Tidak ada pesan baru</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
    <script>
    // Get data from PHP variables
    const totalUsers = <?php echo $total_users ?? 0; ?>;
    const totalProjects = <?php echo $total_projects ?? 0; ?>;
    const totalWorks = <?php echo $total_works ?? 0; ?>;
    const unreadCount = <?php echo $unread_count ?? 0; ?>;

    // Messages Chart
    const messagesCtx = document.getElementById('messagesChart').getContext('2d');
    new Chart(messagesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Pesan Masuk',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: 'rgb(59, 130, 246)',
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Content Distribution Chart
    const contentCtx = document.getElementById('contentChart').getContext('2d');
    new Chart(contentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Berita', 'Galeri', 'Edukasi', 'Pesan'],
            datasets: [{
                data: [5, 17, 6, unreadCount],
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    </script>
</body>

</html>
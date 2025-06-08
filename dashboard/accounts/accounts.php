<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../../../index.php");
    exit;
}
$user = $_SESSION['user'];

// Include database configuration
require_once '../../config/db.php';

// Get user profile data
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

// Include sidebar dan header
include '../sidebar.php';
include '../header.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>My Profile - Dashboard</title>
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
        <!-- Profile Header -->
        <div class="relative">
            <div class="h-32 bg-gradient-to-r from-blue-500 to-purple-600 rounded-t-xl"></div>
            <div class="absolute -bottom-16 left-8">
                <div
                    class="w-32 h-32 rounded-full border-4 border-white bg-white shadow-lg flex items-center justify-center">
                    <div class="text-4xl text-gray-600">
                        <?php echo strtoupper(substr($profile['fullname'], 0, 1)); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="mt-20 px-4 sm:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Profile Info -->
                <div class="p-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">
                                <?php echo htmlspecialchars($profile['fullname']); ?></h2>
                            <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($profile['email']); ?></p>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" id="editProfileBtn"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors duration-200">
                                <i class='bx bx-edit text-xl'></i>
                                Edit Profile
                            </button>
                            <button type="button" id="changePasswordBtn"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-300 transition-colors duration-200">
                                <i class='bx bx-lock-alt text-xl'></i>
                                Change Password
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Personal Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Personal Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Role</label>
                                    <p class="mt-1">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $profile['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'; ?>">
                                            <?php echo ucfirst(htmlspecialchars($profile['role'])); ?>
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Created At</label>
                                    <p class="mt-1 text-gray-900">
                                        <?php echo date('d M Y H:i', strtotime($profile['created_at'])); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Last Updated</label>
                                    <p class="mt-1 text-gray-900">
                                        <?php echo date('d M Y H:i', strtotime($profile['updated_at'])); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Account Security -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Account Security</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Password</h4>
                                        <p class="text-sm text-gray-600">Last changed:
                                            <?php echo date('d M Y', strtotime($profile['updated_at'])); ?></p>
                                    </div>
                                    <button type="button" id="changePasswordBtn2"
                                        class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                        Change
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div id="editProfileModal" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow">
                    <div class="flex items-start justify-between p-4 border-b rounded-t border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Edit Profile
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                            data-modal-hide="editProfileModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                    <form id="editProfileForm" class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label for="fullname" class="block text-sm font-medium text-gray-600">Full Name</label>
                                <input type="text" id="fullname" name="fullname"
                                    value="<?php echo htmlspecialchars($profile['fullname']); ?>"
                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                                <input type="email" id="email" name="email"
                                    value="<?php echo htmlspecialchars($profile['email']); ?>"
                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <div class="flex items-center justify-end space-x-2 mt-6">
                            <button type="button" data-modal-hide="editProfileModal"
                                class="text-gray-600 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cancel</button>
                            <button type="submit"
                                class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Password Modal -->
        <div id="changePasswordModal" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow">
                    <div class="flex items-start justify-between p-4 border-b rounded-t border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Change Password
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                            data-modal-hide="changePasswordModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                    <form id="changePasswordForm" class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-600">Current
                                    Password</label>
                                <input type="password" id="current_password" name="current_password"
                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-600">New
                                    Password</label>
                                <input type="password" id="new_password" name="new_password"
                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-gray-600">Confirm
                                    New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password"
                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <div class="flex items-center justify-end space-x-2 mt-6">
                            <button type="button" data-modal-hide="changePasswordModal"
                                class="text-gray-600 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cancel</button>
                            <button type="submit"
                                class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-4 right-4 z-50 hidden">
        <div class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow" role="alert">
            <div id="toast-icon"
                class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
                <i class='bx bx-check text-xl'></i>
            </div>
            <div class="ml-3 text-sm font-normal" id="toast-message"></div>
            <button type="button"
                class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8"
                onclick="hideToast()">
                <i class='bx bx-x text-xl'></i>
            </button>
        </div>
    </div>

    <script src="js/profile.js"></script>
</body>

</html>
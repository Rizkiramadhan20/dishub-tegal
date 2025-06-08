<?php
session_start();
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header("Location: dashboard/dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="assets/logo.png">
    <script src="https://cdn.tailwindcss.com" async></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 min-h-screen">
    <section class="flex flex-col lg:flex-row min-h-screen">
        <!-- Mobile Image Header -->
        <div class="lg:hidden relative h-80 sm:h-96 md:h-[500px]">
            <img src="https://images.unsplash.com/photo-1579546929518-9e396f3cc809?q=80&w=2070&auto=format&fit=crop"
                alt="Art" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="text-center text-white p-4">
                    <img src="assets/logo.png" alt="Logo"
                        class="w-28 h-28 sm:w-36 sm:h-36 mx-auto mb-4 sm:mb-5 object-contain" />
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-3 sm:mb-4">Welcome to our community</h2>
                    <p class="text-lg sm:text-xl">Sign in to access your account</p>
                </div>
            </div>
        </div>

        <!-- Left: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-8">
            <div class="w-full max-w-md space-y-6 sm:space-y-8">
                <div class="text-center">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Welcome Back</h2>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">Sign in to continue to your account</p>
                </div>

                <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-3 sm:px-4 py-2 sm:py-3 rounded relative text-sm sm:text-base"
                    role="alert">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
                <?php endif; ?>

                <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-3 sm:px-4 py-2 sm:py-3 rounded relative text-sm sm:text-base"
                    role="alert">
                    <?php echo htmlspecialchars($_GET['success']); ?>
                </div>
                <?php endif; ?>

                <form action="process.php" method="POST" class="mt-6 sm:mt-8 space-y-4 sm:space-y-6">
                    <div class="space-y-3 sm:space-y-4">
                        <input type="email" name="email" placeholder="Email" required
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        <input type="password" name="password" placeholder="Password" required
                            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>
                    <button type="submit" name="login"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm sm:text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Sign In
                    </button>
                </form>
            </div>
        </div>

        <!-- Desktop Image Section -->
        <div class="hidden lg:block lg:w-1/2 relative">
            <img src="assets/login.jpg" alt="Art" class="absolute inset-0 w-full h-full object-cover" />
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="text-center text-white p-6 sm:p-8">
                    <img src="assets/logo.png" alt="Logo"
                        class="w-32 sm:w-48 h-32 sm:h-48 mx-auto mb-4 sm:mb-6 object-contain" />
                    <h2 class="text-2xl sm:text-3xl font-bold mb-3 sm:mb-4">Welcome to our community</h2>
                    <p class="text-base sm:text-lg">Sign in to access your account and start sharing your photos</p>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js" async></script>
</body>

</html>
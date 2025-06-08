<?php
session_start();
require_once 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Dishub Tegal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link rel="stylesheet" href="style/style.css" />
    <!-- Core Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script>
    tailwind.config = {
        theme: {
            extend: {}
        }
    }
    </script>

    <!-- Custom Scripts -->
    <script src="js/main.js" defer></script>
    <script src="js/toast.js" defer></script>
    <!-- <script src="js/animations.js" defer></script> -->
    <?php if (isset($_SESSION['toast'])): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        showToast("<?php echo $_SESSION['toast']['message']; ?>", "<?php echo $_SESSION['toast']['type']; ?>");
    });
    </script>
    <?php 
    unset($_SESSION['toast']);
    endif; 
    ?>
</head>

<body>
    <?php include 'components/header.php'; ?>

    <!-- Main Content -->
    <main className="overflow-hidden">

    </main>

    <?php include 'components/footer.php'; ?>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-4 right-4 z-50 hidden">
        <div class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-600 bg-white rounded-lg shadow-lg border border-gray-100"
            role="alert">
            <div id="toast-icon"
                class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-600 bg-green-50 rounded-lg">
                <i class='bx bx-check text-xl'></i>
            </div>
            <div class="ml-3 text-sm font-normal" id="toast-message"></div>
            <button type="button"
                class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-50 inline-flex items-center justify-center h-8 w-8"
                onclick="hideToast()">
                <i class='bx bx-x text-xl'></i>
            </button>
        </div>
    </div>

    <?php if (isset($_SESSION['toast'])): ?>
    <script>
    showToast("<?php echo $_SESSION['toast']['message']; ?>", "<?php echo $_SESSION['toast']['type']; ?>");
    </script>
    <?php 
    unset($_SESSION['toast']);
    endif; 
    ?>
</body>

</html>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get current page and hash
$current_page = basename($_SERVER['PHP_SELF']);
$current_hash = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_FRAGMENT) : '';

// Function to check if link is active
function isActive($page, $hash = '') {
    global $current_page, $current_hash;
    
    if ($page === 'index.php' && empty($hash)) {
        return $current_page === 'index.php' && empty($current_hash);
    }
    
    return $current_hash === $hash;
}
?>
<!-- Fixed Header -->
<header class="fixed-header py-4 w-full bg-white shadow-sm transition-all duration-300" x-data="{ 
        mobileMenuOpen: false, 
        userDropdownOpen: false,
        isScrolled: false
    }" x-init="window.addEventListener('scroll', () => {
        isScrolled = window.scrollY > 20;
    })" :class="{
        'py-2': isScrolled,
        'py-4': !isScrolled,
        'shadow-md': isScrolled,
        'shadow-sm': !isScrolled
    }" style="position: fixed; top: 0; left: 0; right: 0; z-index: 40;">
    <nav class="container mx-auto px-4 flex justify-between items-center">
        <a href="/index.php" class="flex items-center">
            <img src="/assets/logo.png" alt="Kreasi Digital Logo" class="h-10">
        </a>

        <!-- Desktop Navigation -->
        <ul class="hidden md:flex items-center gap-6">
            <li>
                <a href="#home"
                    class="px-3 py-2 <?php echo isActive('index.php', 'home') ? 'text-blue-600 after:w-full' : 'text-gray-600'; ?> hover:text-blue-600 font-medium transition-colors relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-blue-600 hover:after:w-full after:transition-all">Home</a>
            </li>
            <li>
                <a href="#about"
                    class="px-3 py-2 <?php echo isActive('index.php', 'about') ? 'text-blue-600 after:w-full' : 'text-gray-600'; ?> hover:text-blue-600 font-medium transition-colors relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-blue-600 hover:after:w-full after:transition-all">About</a>
            </li>
            <li>
                <a href="#education"
                    class="px-3 py-2 <?php echo isActive('index.php', 'education') ? 'text-blue-600 after:w-full' : 'text-gray-600'; ?> hover:text-blue-600 font-medium transition-colors relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-blue-600 hover:after:w-full after:transition-all">Education</a>
            </li>
            <li>
                <a href="#gallery"
                    class="px-3 py-2 <?php echo isActive('index.php', 'gallery') ? 'text-blue-600 after:w-full' : 'text-gray-600'; ?> hover:text-blue-600 font-medium transition-colors relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-blue-600 hover:after:w-full after:transition-all">Gallery</a>
            </li>
            <li>
                <a href="#news"
                    class="px-3 py-2 <?php echo isActive('index.php', 'news') ? 'text-blue-600 after:w-full' : 'text-gray-600'; ?> hover:text-blue-600 font-medium transition-colors relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-blue-600 hover:after:w-full after:transition-all">News</a>
            </li>
            <li>
                <a href="#contact"
                    class="px-3 py-2 <?php echo isActive('index.php', 'contact') ? 'text-blue-600 after:w-full' : 'text-gray-600'; ?> hover:text-blue-600 font-medium transition-colors relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-blue-600 hover:after:w-full after:transition-all">Contact</a>
            </li>
        </ul>

        <!-- Mobile Navigation -->
        <div class="md:hidden">
            <!-- Mobile Menu Overlay -->
            <div x-show="mobileMenuOpen" class="fixed inset-0 bg-black bg-opacity-50 z-40"
                @click="mobileMenuOpen = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
            </div>

            <!-- Mobile Menu Panel -->
            <div x-show="mobileMenuOpen" class="fixed inset-0 bg-white z-50 transform transition-transform duration-300"
                :class="mobileMenuOpen ? 'translate-x-0' : 'translate-x-full'" @click.away="mobileMenuOpen = false"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                <!-- Menu Header -->
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-gray-800">Menu</h3>
                        <button @click="mobileMenuOpen = false" class="text-gray-500 hover:text-gray-700">
                            <span class="material-icons text-3xl">close</span>
                        </button>
                    </div>
                </div>

                <!-- Menu Items -->
                <div class="p-6">
                    <ul class="space-y-4">
                        <li>
                            <a href="#home"
                                class="flex items-center gap-4 px-4 py-4 <?php echo isActive('index.php', 'home') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'; ?> rounded-xl transition-colors">
                                <span
                                    class="material-icons text-2xl <?php echo isActive('index.php', 'home') ? 'text-blue-600' : 'text-gray-500'; ?>">home</span>
                                <span class="text-lg">Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="#about"
                                class="flex items-center gap-4 px-4 py-4 <?php echo isActive('index.php', 'about') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'; ?> rounded-xl transition-colors">
                                <span
                                    class="material-icons text-2xl <?php echo isActive('index.php', 'about') ? 'text-blue-600' : 'text-gray-500'; ?>">info</span>
                                <span class="text-lg">About</span>
                            </a>
                        </li>
                        <li>
                            <a href="#education"
                                class="flex items-center gap-4 px-4 py-4 <?php echo isActive('index.php', 'education') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'; ?> rounded-xl transition-colors">
                                <span
                                    class="material-icons text-2xl <?php echo isActive('index.php', 'education') ? 'text-blue-600' : 'text-gray-500'; ?>">school</span>
                                <span class="text-lg">Education</span>
                            </a>
                        </li>
                        <li>
                            <a href="#gallery"
                                class="flex items-center gap-4 px-4 py-4 <?php echo isActive('index.php', 'gallery') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'; ?> rounded-xl transition-colors">
                                <span
                                    class="material-icons text-2xl <?php echo isActive('index.php', 'gallery') ? 'text-blue-600' : 'text-gray-500'; ?>">photo_library</span>
                                <span class="text-lg">Gallery</span>
                            </a>
                        </li>
                        <li>
                            <a href="#news"
                                class="flex items-center gap-4 px-4 py-4 <?php echo isActive('index.php', 'news') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'; ?> rounded-xl transition-colors">
                                <span
                                    class="material-icons text-2xl <?php echo isActive('index.php', 'news') ? 'text-blue-600' : 'text-gray-500'; ?>">newspaper</span>
                                <span class="text-lg">News</span>
                            </a>
                        </li>
                        <li>
                            <a href="#contact"
                                class="flex items-center gap-4 px-4 py-4 <?php echo isActive('index.php', 'contact') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'; ?> rounded-xl transition-colors">
                                <span
                                    class="material-icons text-2xl <?php echo isActive('index.php', 'contact') ? 'text-blue-600' : 'text-gray-500'; ?>">contact_mail</span>
                                <span class="text-lg">Contact</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Menu Footer -->
                <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-gray-100 bg-white">
                    <?php if (isset($_SESSION['user'])): ?>
                    <a href="/dashboard/dashboard.php"
                        class="flex items-center justify-center gap-2 w-full bg-blue-500 text-white px-4 py-4 rounded-xl hover:bg-blue-600 transition-colors">
                        <span class="material-icons">dashboard</span>
                        <span class="text-lg">Dashboard</span>
                    </a>
                    <a href="/logout.php"
                        class="flex items-center justify-center gap-2 w-full mt-2 text-red-600 px-4 py-4 rounded-xl hover:bg-gray-50 transition-colors">
                        <span class="material-icons">logout</span>
                        <span class="text-lg">Logout</span>
                    </a>
                    <?php else: ?>
                    <a href="/login.php"
                        class="flex items-center justify-center gap-2 w-full bg-blue-500 text-white px-4 py-4 rounded-xl hover:bg-blue-600 transition-colors">
                        <span class="material-icons">login</span>
                        <span class="text-lg">Login</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Desktop Button -->
        <div class="hidden md:block">
            <?php if (isset($_SESSION['user'])): ?>
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open"
                    class="flex items-center gap-3 hover:bg-gray-50 px-3 py-2 rounded-lg transition-colors">
                    <div
                        class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
                        <?php echo strtoupper(substr($_SESSION['user']['fullname'], 0, 1)); ?>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-700">
                            <?php echo htmlspecialchars($_SESSION['user']['fullname']); ?></p>
                        <p class="text-xs text-gray-500"><?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
                    </div>
                    <span class="material-icons text-gray-400">arrow_drop_down</span>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-64 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
                    <div class="p-2">
                        <a href="/dashboard/dashboard.php"
                            class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                            <span class="material-icons text-sm">dashboard</span>
                            Dashboard
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <a href="/logout.php"
                            class="flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-gray-50 rounded-lg transition-colors">
                            <span class="material-icons text-sm">logout</span>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <a href="/login.php"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">Login</a>
            <?php endif; ?>
        </div>

        <!-- Mobile Menu Button -->
        <div class="flex items-center gap-2 md:hidden">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-gray-700">
                <span class="material-icons">menu</span>
            </button>
        </div>
    </nav>
</header>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="/js/header.js"></script>
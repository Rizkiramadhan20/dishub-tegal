<?php
session_start();
require_once 'config/db.php';
require_once 'fetch_data.php';
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
    <!-- Flowbite -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

    <script>
    tailwind.config = {
        theme: {
            extend: {}
        }
    }
    </script>

    <!-- Custom Scripts -->
    <script src="js/main.js" defer></script>
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="js/animations.js" defer></script>
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
    <main class="overflow-hidden">
        <!-- Home -->
        <section class="py-20 bg-gray-50 flex items-center justify-center mt-0 sm:mt-10 overflow-hidden" id="home">
            <div class="container px-4 text-center flex flex-col gap-2">

                <?php if (!empty($contents)):
                // Get the first item from the fetched data to use for the hero section
                $hero_content = $contents[0];
                ?>

                <h1
                    class="mx-auto text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl max-w-4xl font-bold text-gray-900 leading-tight mb-4 px-4 home-title">
                    <?php echo htmlspecialchars($hero_content['title']); ?>
                </h1>

                <p
                    class="text-sm sm:text-base lg:text-lg text-gray-700 mb-6 sm:mb-8 max-w-2xl mx-auto px-4 home-description">
                    <?php echo htmlspecialchars($hero_content['description']); ?>
                </p>

                <!-- Call to Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8 md:mb-16 home-cta">
                    <a href="#about"
                        class="inline-block px-5 sm:px-6 md:px-8 py-2.5 sm:py-3 bg-blue-600 text-white font-semibold text-sm sm:text-base md:text-lg rounded-full shadow-lg hover:bg-blue-700 transition duration-300 transform scale-95 hover:scale-105">
                        <span>Tentang kami</span>
                    </a>

                    <a href="#education"
                        class="inline-block px-5 sm:px-6 md:px-8 py-2.5 sm:py-3 bg-white text-gray-800 font-semibold text-sm sm:text-base md:text-lg rounded-full shadow-lg hover:bg-gray-100 border border-gray-300 transition duration-300  transform scale-95 hover:scale-105">
                        <span>Belajar Sekarang</span>
                    </a>
                </div>

                <div class="w-full h-full relative">
                    <div
                        class="relative rounded-xl sm:rounded-2xl overflow-hidden aspect-[16/9] bg-gray-50 flex items-center justify-center">
                        <img src="dashboard/uploads/home/<?php echo htmlspecialchars($hero_content['image']); ?>"
                            alt="About Us Image" class="w-full h-full object-cover">
                    </div>
                </div>

                <?php endif; ?>
            </div>
        </section>

        <!-- About -->
        <section class="py-16 md:py-24 bg-white" id="about">
            <div class="container px-4 mx-auto">
                <?php if ($about_content): ?>
                <div class="flex flex-col lg:flex-row gap-12 lg:gap-20 items-center">
                    <!-- Left Side - Image -->
                    <div class="w-full lg:w-1/2">
                        <div class="relative group">
                            <div
                                class="absolute -inset-4 bg-gradient-to-r from-blue-100 to-blue-50 rounded-2xl blur-xl opacity-50 group-hover:opacity-75 transition duration-500">
                            </div>
                            <div class="relative rounded-2xl overflow-hidden aspect-[4/3] bg-gray-50">
                                <img src="dashboard/uploads/about/<?php echo htmlspecialchars($about_content['image']); ?>"
                                    alt="About Us Image"
                                    class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Content -->
                    <div class="w-full lg:w-1/2 flex flex-col gap-8">
                        <div class="flex flex-col gap-6">
                            <div
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-600 text-sm font-medium w-fit">
                                <i class='bx bx-info-circle'></i>
                                About Us
                            </div>

                            <h3 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight">
                                <?php echo htmlspecialchars($about_content['title']); ?>
                            </h3>

                            <div class="space-y-4">
                                <p class="text-gray-600 text-base lg:text-lg leading-relaxed">
                                    <?php echo htmlspecialchars($about_content['text']); ?>
                                </p>

                                <p class="text-gray-600 text-base lg:text-lg leading-relaxed">
                                    <?php echo htmlspecialchars($about_content['description']); ?>
                                </p>
                            </div>
                        </div>

                        <a href="#contact"
                            class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-blue-600 text-white font-medium rounded-full shadow-lg hover:bg-blue-700 transition duration-300 text-base w-fit group">
                            <span>Let's work together</span>
                            <i class='bx bx-right-arrow-alt text-xl group-hover:translate-x-1 transition-transform'></i>
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <div class="text-center py-16">
                    <h3 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-4">About Content Coming Soon</h3>
                    <p class="text-gray-500 text-base">We're working on something amazing. Stay tuned!</p>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Education -->
        <section class="py-16 md:py-24 bg-gray-50" id="education">
            <div class="container px-4 mx-auto">
                <div class="text-center mb-16">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-600 text-sm font-medium w-fit mx-auto mb-4">
                        <i class='bx bx-book-open'></i>
                        Education
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Educational Resources</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Access our comprehensive collection of educational videos
                        and resources to enhance your knowledge.</p>
                </div>

                <?php if (!empty($education_contents)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($education_contents as $content): ?>
                    <div
                        class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <!-- Video Thumbnail Container -->
                        <div class="relative aspect-video overflow-hidden">
                            <video
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                                preload="metadata"
                                onloadedmetadata="this.parentElement.parentElement.querySelector('.duration').textContent = formatDuration(this.duration)">
                                <source
                                    src="dashboard/uploads/education/<?php echo htmlspecialchars($content['video']); ?>"
                                    type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <!-- Overlay with Play Button -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <button type="button"
                                    class="watch-video-btn transform scale-90 group-hover:scale-100 transition-transform duration-300 inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-colors duration-300"
                                    data-modal-target="videoModal" data-modal-toggle="videoModal"
                                    data-video="dashboard/uploads/education/<?php echo htmlspecialchars($content['video']); ?>"
                                    data-title="<?php echo htmlspecialchars($content['title']); ?>"
                                    data-description="<?php echo htmlspecialchars($content['description']); ?>">
                                    <i class='bx bx-play-circle text-2xl'></i>
                                    <span class="font-medium">Watch Video</span>
                                </button>
                            </div>
                        </div>
                        <!-- Content Container -->
                        <div class="p-6">
                            <h3
                                class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">
                                <?php echo htmlspecialchars($content['title']); ?>
                            </h3>
                            <p class="text-gray-600 line-clamp-2">
                                <?php echo htmlspecialchars($content['description']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-16 bg-white rounded-2xl shadow-sm">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-6">
                        <i class='bx bx-book-open text-4xl text-blue-600'></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">No Educational Content Available</h3>
                    <p class="text-gray-600 max-w-md mx-auto">We're currently preparing educational resources. Please
                        check back soon!</p>
                </div>
                <?php endif; ?>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="flex justify-center mt-12 md:mt-20">
                    <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <!-- Previous button -->
                        <a href="?page=<?php echo max(1, $page - 1); ?>#education"
                            class="inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo $page <= 1 ? 'opacity-50 cursor-not-allowed' : ''; ?>">
                            <i class='bx bx-chevron-left'></i>
                            <span class="sr-only">Previous</span>
                        </a>

                        <!-- Page numbers -->
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>#education"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo $i === $page ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50'; ?>">
                            <?php echo $i; ?>
                        </a>
                        <?php endfor; ?>

                        <!-- Next button -->
                        <a href="?page=<?php echo min($total_pages, $page + 1); ?>#education"
                            class="inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo $page >= $total_pages ? 'opacity-50 cursor-not-allowed' : ''; ?>">
                            <i class='bx bx-chevron-right'></i>
                            <span class="sr-only">Next</span>
                        </a>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Video Modal -->
        <div id="videoModal" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-4xl max-h-full mx-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <div class="flex flex-col gap-1">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="modalTitle"></h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400" id="modalDescription"></p>
                        </div>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="videoModal" onclick="closeModal()">
                            <i class='bx bx-x text-xl'></i>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4">
                        <div class="relative aspect-video">
                            <video id="modalVideo" class="w-full h-full" controls>
                                <source src="" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery -->
        <section class="py-12 bg-white relative" id="gallery">
            <div class="container px-4">
                <div class="mb-10">
                    <span class="text-sm text-gray-400 gallery-subtitle">Gallery</span>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-2 gallery-title">Our Photo
                        Gallery</h1>
                    <p class="text-gray-400 text-base sm:text-lg max-w-2xl gallery-description">
                        Explore our collection of photos showcasing our work and achievements.
                    </p>
                </div>

                <?php if (!empty($gallery_contents)): ?>
                <!-- Mobile Scroll Container -->
                <div class="md:hidden overflow-x-auto pb-4 -mx-4 px-4">
                    <div class="flex gap-4" style="min-width: max-content;">
                        <?php foreach ($gallery_contents as $gallery): ?>
                        <div
                            class="relative group overflow-hidden rounded-xl shadow-lg w-[240px] aspect-[4/3] flex-shrink-0 gallery-card">
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <img src="dashboard/uploads/gallery/<?php echo htmlspecialchars($gallery['image']); ?>"
                                    alt="<?php echo htmlspecialchars($gallery['title']); ?>"
                                    class="w-full h-full object-cover transition-all duration-500 group-hover:scale-110" />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-black/0 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                    <div
                                        class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                        <h3 class="text-white text-lg font-semibold mb-2">
                                            <?php echo htmlspecialchars($gallery['title']); ?>
                                        </h3>
                                        <div class="flex items-center gap-3">
                                            <button
                                                class="flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-full transition-colors duration-300 view-image-btn"
                                                data-modal-target="imageModal" data-modal-toggle="imageModal"
                                                data-image="dashboard/uploads/gallery/<?php echo htmlspecialchars($gallery['image']); ?>"
                                                data-title="<?php echo htmlspecialchars($gallery['title']); ?>">
                                                <i class='bx bx-show text-white text-xl'></i>
                                                <span class="text-white text-sm font-medium">View image</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Desktop Grid Layout -->
                <div class="hidden md:block">
                    <div class="grid grid-cols-4 gap-4 md:gap-6">
                        <?php foreach ($gallery_contents as $gallery): ?>
                        <div class="relative group overflow-hidden rounded-xl shadow-lg inspiration-card">
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <img src="dashboard/uploads/gallery/<?php echo htmlspecialchars($gallery['image']); ?>"
                                    alt="<?php echo htmlspecialchars($gallery['title']); ?>"
                                    class="w-full h-full object-cover transition-all duration-500 group-hover:scale-110" />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-black/0 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                    <div
                                        class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                        <h3 class="text-white text-lg font-semibold mb-2">
                                            <?php echo htmlspecialchars($gallery['title']); ?></h3>
                                        <div class="flex items-center gap-3">
                                            <button
                                                class="flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-full transition-colors duration-300 view-image-btn"
                                                data-modal-target="imageModal" data-modal-toggle="imageModal"
                                                data-image="dashboard/uploads/gallery/<?php echo htmlspecialchars($gallery['image']); ?>"
                                                data-title="<?php echo htmlspecialchars($gallery['title']); ?>">
                                                <i class='bx bx-show text-white text-xl'></i>
                                                <span class="text-white text-sm font-medium">View image</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Pagination for Gallery -->
                <?php if ($total_gallery_pages > 1): ?>
                <div class="flex justify-center mt-4 md:mt-12">
                    <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <!-- Previous button -->
                        <a href="?gallery_page=<?php echo max(1, $gallery_page - 1); ?>#gallery"
                            class="inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo $gallery_page <= 1 ? 'opacity-50 cursor-not-allowed' : ''; ?>">
                            <i class='bx bx-chevron-left'></i>
                            <span class="sr-only">Previous</span>
                        </a>

                        <!-- Page numbers -->
                        <?php for ($i = 1; $i <= $total_gallery_pages; $i++): ?>
                        <a href="?gallery_page=<?php echo $i; ?>#gallery"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo $i === $gallery_page ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50'; ?>">
                            <?php echo $i; ?>
                        </a>
                        <?php endfor; ?>

                        <!-- Next button -->
                        <a href="?gallery_page=<?php echo min($total_gallery_pages, $gallery_page + 1); ?>#gallery"
                            class="inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo $gallery_page >= $total_gallery_pages ? 'opacity-50 cursor-not-allowed' : ''; ?>">
                            <i class='bx bx-chevron-right'></i>
                            <span class="sr-only">Next</span>
                        </a>
                    </nav>
                </div>
                <?php endif; ?>

                <!-- Image Modal -->
                <div id="imageModal" tabindex="-1" aria-hidden="true"
                    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative w-full max-w-4xl max-h-full mx-auto">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Modal header -->
                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="modalImageTitle">
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="imageModal">
                                    <i class='bx bx-x text-xl'></i>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-4">
                                <div class="relative aspect-[4/3]">
                                    <img id="modalImage" class="w-full h-full object-contain" src="" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <h3 class="text-xl font-semibold text-gray-600 mb-4">No Gallery Content Available</h3>
                    <p class="text-gray-500">Check back later for new gallery photos.</p>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- News Section -->
        <section class="py-12 bg-white" id="news">
            <div class="container px-4 mx-auto">
                <div class="text-center mb-16">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-600 text-sm font-medium w-fit mx-auto mb-4">
                        <i class='bx bx-news'></i>
                        Berita Terkini
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Berita & Informasi</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Dapatkan informasi terbaru seputar Dishub Tegal dan
                        perkembangan terkini.</p>
                </div>

                <?php if (!empty($all_news_contents)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($all_news_contents as $news): ?>
                    <div
                        class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
                        <div class="relative aspect-[16/9] overflow-hidden">
                            <img src="dashboard/uploads/berita/<?php echo htmlspecialchars($news['image']); ?>"
                                alt="<?php echo htmlspecialchars($news['title']); ?>"
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-black/0 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                <div
                                    class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                    <a href="berita.php?slug=<?php echo htmlspecialchars($news['slug']); ?>"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-full transition-colors duration-300">
                                        <i class='bx bx-right-arrow-alt text-white text-xl'></i>
                                        <span class="text-white text-sm font-medium">Baca selengkapnya</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3
                                class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">
                                <?php echo htmlspecialchars($news['title']); ?>
                            </h3>
                            <p class="text-gray-600 line-clamp-2 mb-4">
                                <?php echo htmlspecialchars($news['description']); ?>
                            </p>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class='bx bx-time-five mr-1'></i>
                                <span><?php echo date('d M Y', strtotime($news['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php else: ?>
                <div class="text-center py-12">
                    <h3 class="text-xl font-semibold text-gray-600 mb-4">Belum Ada Berita</h3>
                    <p class="text-gray-500">Berita akan segera hadir.</p>
                </div>
                <?php endif; ?>

                <!-- Pagination for News -->
                <?php if ($total_news_pages > 1): ?>
                <div class="flex justify-center mt-4 md:mt-12">
                    <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <!-- Previous button -->
                        <a href="?news_page=<?php echo max(1, $news_page - 1); ?>#news"
                            class="inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo $news_page <= 1 ? 'opacity-50 cursor-not-allowed' : ''; ?>">
                            <i class='bx bx-chevron-left'></i>
                            <span class="sr-only">Previous</span>
                        </a>

                        <!-- Page numbers -->
                        <?php for ($i = 1; $i <= $total_news_pages; $i++): ?>
                        <a href="?news_page=<?php echo $i; ?>#news"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo $i === $news_page ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50'; ?>">
                            <?php echo $i; ?>
                        </a>
                        <?php endfor; ?>

                        <!-- Next button -->
                        <a href="?news_page=<?php echo min($total_news_pages, $news_page + 1); ?>#news"
                            class="inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo $news_page >= $total_news_pages ? 'opacity-50 cursor-not-allowed' : ''; ?>">
                            <i class='bx bx-chevron-right'></i>
                            <span class="sr-only">Next</span>
                        </a>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="py-12 bg-gray-50" id="contact">
            <div class="container px-4">
                <div class="text-center mb-16">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-600 text-sm font-medium w-fit mx-auto mb-4">
                        <i class='bx bx-envelope'></i>
                        Contact Us
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Get in Touch</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Have questions or need assistance? We're here to help.
                        Send us a message and we'll respond as soon as possible.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Contact Information Cards -->
                    <div class="lg:col-span-4 space-y-6">
                        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex-shrink-0 w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                    <i class='bx bx-map text-2xl text-blue-600'></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Our Location</h4>
                                    <p class="text-gray-600">Jl. Proklamasi No.1, Tegal, Jawa Tengah</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex-shrink-0 w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                    <i class='bx bx-phone text-2xl text-blue-600'></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Phone Number</h4>
                                    <p class="text-gray-600">+62 283 123456</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex-shrink-0 w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                    <i class='bx bx-envelope text-2xl text-blue-600'></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Email Address</h4>
                                    <p class="text-gray-600">info@dishubtegal.go.id</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex-shrink-0 w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                    <i class='bx bx-time text-2xl text-blue-600'></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Working Hours</h4>
                                    <p class="text-gray-600">Monday - Friday: 8:00 AM - 4:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form and Map -->
                    <div class="lg:col-span-8 space-y-6">
                        <!-- Map -->
                        <div class="bg-white rounded-2xl p-4 shadow-sm">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.0!2d109.0!3d-6.0!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMDAnMDAuMCJTIDEwOcKwMDAnMDAuMCJF!5e0!3m2!1sen!2sid!4v1234567890!5m2!1sen!2sid"
                                class="w-full h-[200px] rounded-xl" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>

                        <!-- Contact Form -->
                        <div class="bg-white rounded-2xl p-8 shadow-sm w-full">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h3>

                            <form id="contactForm" action="process_contact.php" method="POST"
                                class="space-y-6 max-w-7xl">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="w-full">
                                        <label for="first_name"
                                            class="block mb-2 text-sm font-medium text-gray-900">First name</label>
                                        <input type="text" id="first_name" name="first_name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-3 px-4"
                                            required>
                                    </div>
                                    <div class="w-full">
                                        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900">Last
                                            name</label>
                                        <input type="text" id="last_name" name="last_name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-3 px-4"
                                            required>
                                    </div>
                                </div>

                                <div class="w-full">
                                    <label for="email"
                                        class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                                    <input type="email" id="email" name="email"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-3 px-4"
                                        required>
                                </div>

                                <div class="w-full">
                                    <label for="message"
                                        class="block mb-2 text-sm font-medium text-gray-900">Message</label>
                                    <textarea id="message" name="message" rows="4"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-3 px-4"
                                        required></textarea>
                                </div>

                                <button type="submit" id="submitButton"
                                    class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition duration-300 flex items-center justify-center gap-2">
                                    <span id="buttonText">Send Message</span>
                                    <div id="loadingSpinner" class="hidden">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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

    <script>
    window.appData = <?php echo json_encode([
        'contents' => $contents,
        'about' => $about_content,
        'education_contents' => $education_contents,
        'gallery_contents' => $gallery_contents,
        'news_contents' => $news_contents
    ]); ?>;
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contactForm');
        const submitButton = document.getElementById('submitButton');
        const buttonText = document.getElementById('buttonText');
        const loadingSpinner = document.getElementById('loadingSpinner');

        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Disable the submit button and show loading state
            submitButton.disabled = true;
            buttonText.textContent = 'Sending...';
            loadingSpinner.classList.remove('hidden');

            // Submit the form
            fetch('process_contact.php', {
                    method: 'POST',
                    body: new FormData(contactForm)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(() => {
                    // Show success message
                    showToast('Message sent successfully!', 'success');

                    // Reset form
                    contactForm.reset();
                })
                .catch(error => {
                    // Show error message
                    showToast('Failed to send message. Please try again.', 'error');
                    console.error('Error:', error);
                })
                .finally(() => {
                    // Re-enable the submit button and hide loading state
                    submitButton.disabled = false;
                    buttonText.textContent = 'Send Message';
                    loadingSpinner.classList.add('hidden');
                });
        });
    });
    </script>
</body>

</html>
<?php
require_once 'config/db.php';

// Get the slug from URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if (empty($slug)) {
    header("Location: index.php");
    exit;
}

// Fetch the news article
$stmt = $db->prepare("SELECT * FROM berita WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$news = $result->fetch_assoc();

if (!$news) {
    header("Location: index.php");
    exit;
}

// Fetch other news for sidebar
$other_news_stmt = $db->prepare("SELECT id, title, slug, image, created_at FROM berita WHERE id != ? ORDER BY created_at DESC LIMIT 3");
$other_news_stmt->bind_param("i", $news['id']);
$other_news_stmt->execute();
$other_news_result = $other_news_stmt->get_result();
$other_news = [];
while ($row = $other_news_result->fetch_assoc()) {
    $other_news[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title><?php echo htmlspecialchars($news['title']); ?> - Dishub Tegal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link rel="stylesheet" href="style/style.css" />
    <!-- Core Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Flowbite -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <!-- CodeMirror -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css" rel="stylesheet">

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

    <style>
    /* Content Styling */
    .prose h1 {
        font-size: 2.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #111827;
    }

    .prose h2 {
        font-size: 1.875rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: #111827;
    }

    .prose h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #111827;
    }

    .prose p {
        margin-bottom: 1rem;
        line-height: 1.75;
        color: #374151;
    }

    .prose strong {
        font-weight: 700;
    }

    .prose em {
        font-style: italic;
    }

    .prose ul {
        list-style-type: disc;
        list-style-position: inside;
        margin-bottom: 1rem;
    }

    .prose ol {
        list-style-type: decimal;
        list-style-position: inside;
        margin-bottom: 1rem;
    }

    .prose a {
        color: #2563eb;
        text-decoration: none;
    }

    .prose a:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    .prose img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        margin: 1rem 0;
    }

    .prose blockquote {
        border-left: 4px solid #3b82f6;
        padding: 0.5rem 1rem;
        margin: 1rem 0;
        font-style: italic;
        color: #374151;
        background-color: #f9fafb;
        border-radius: 0 0.5rem 0.5rem 0;
    }
    </style>
</head>

<body>
    <?php include 'components/header.php'; ?>

    <!-- Main Content -->
    <main class="py-28">
        <div class="container px-4">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="index.php" class="text-gray-700 hover:text-blue-600">
                            <i class='bx bx-home-alt mr-2'></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class='bx bx-chevron-right text-gray-400'></i>
                            <a href="index.php#news" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2">Berita</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class='bx bx-chevron-right text-gray-400'></i>
                            <span
                                class="ml-1 text-gray-500 md:ml-2"><?php echo htmlspecialchars($news['title']); ?></span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <article class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="aspect-[16/9] overflow-hidden">
                            <img src="dashboard/uploads/berita/<?php echo htmlspecialchars($news['image']); ?>"
                                alt="<?php echo htmlspecialchars($news['title']); ?>"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="p-6 md:p-8">
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                                <?php echo htmlspecialchars($news['title']); ?>
                            </h1>
                            <div class="flex items-center text-sm text-gray-500 mb-6">
                                <i class='bx bx-time-five mr-1'></i>
                                <span><?php echo date('d M Y', strtotime($news['created_at'])); ?></span>
                            </div>
                            <div class="prose max-w-none">
                                <?php echo $news['content']; ?>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Berita Lainnya</h3>
                        <?php if (!empty($other_news)): ?>
                        <div class="space-y-6">
                            <?php foreach ($other_news as $item): ?>
                            <a href="berita.php?slug=<?php echo htmlspecialchars($item['slug']); ?>"
                                class="block group">
                                <div class="flex gap-4">
                                    <div class="w-24 h-24 flex-shrink-0 rounded-lg overflow-hidden">
                                        <img src="dashboard/uploads/berita/<?php echo htmlspecialchars($item['image']); ?>"
                                            alt="<?php echo htmlspecialchars($item['title']); ?>"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <h4
                                            class="text-sm font-medium text-gray-900 group-hover:text-blue-600 line-clamp-2 mb-1">
                                            <?php echo htmlspecialchars($item['title']); ?>
                                        </h4>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class='bx bx-time-five mr-1'></i>
                                            <span><?php echo date('d M Y', strtotime($item['created_at'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p class="text-gray-500 text-sm">Tidak ada berita lainnya.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>
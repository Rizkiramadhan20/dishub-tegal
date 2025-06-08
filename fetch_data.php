<?php
require_once 'config/db.php';

// Fetch home content
$result = $db->query("SELECT * FROM home ORDER BY created_at DESC");
$contents = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $contents[] = $row;
    }
}

$result = $db->query("SELECT * FROM about ORDER BY created_at DESC");
$about_content = null;
if ($result) {
    $about_content = $result->fetch_assoc();
}

// Fetch education content with pagination
$items_per_page = 6; // Number of items to show per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Get total number of education items
$total_result = $db->query("SELECT COUNT(*) as count FROM education");
$total_items = $total_result->fetch_assoc()['count'];
$total_pages = ceil($total_items / $items_per_page);

// Fetch paginated education content
$result = $db->query("SELECT * FROM education ORDER BY created_at DESC LIMIT $offset, $items_per_page");
$education_contents = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $education_contents[] = $row;
    }
}

// Fetch gallery content with pagination
$gallery_items_per_page = 8; // Number of items to show per page
$gallery_page = isset($_GET['gallery_page']) ? (int)$_GET['gallery_page'] : 1;
$gallery_offset = ($gallery_page - 1) * $gallery_items_per_page;

// Get total number of gallery items
$total_gallery_result = $db->query("SELECT COUNT(*) as count FROM gallery");
$total_gallery_items = $total_gallery_result->fetch_assoc()['count'];
$total_gallery_pages = ceil($total_gallery_items / $gallery_items_per_page);

// Fetch paginated gallery content
$result = $db->query("SELECT * FROM gallery ORDER BY created_at DESC LIMIT $gallery_offset, $gallery_items_per_page");
$gallery_contents = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $gallery_contents[] = $row;
    }
}

// Fetch latest news for homepage
$news_result = $db->query("SELECT * FROM berita ORDER BY created_at DESC LIMIT 3");
$news_contents = [];
if ($news_result) {
    while ($row = $news_result->fetch_assoc()) {
        $news_contents[] = $row;
    }
}

// Fetch all news with pagination
$news_items_per_page = 3; // Number of items to show per page
$news_page = isset($_GET['news_page']) ? (int)$_GET['news_page'] : 1;
$news_offset = ($news_page - 1) * $news_items_per_page;

// Get total number of news items
$total_news_result = $db->query("SELECT COUNT(*) as count FROM berita");
$total_news_items = $total_news_result->fetch_assoc()['count'];
$total_news_pages = ceil($total_news_items / $news_items_per_page);

// Fetch paginated news content
$result = $db->query("SELECT * FROM berita ORDER BY created_at DESC LIMIT $news_offset, $news_items_per_page");
$all_news_contents = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $all_news_contents[] = $row;
    }
}
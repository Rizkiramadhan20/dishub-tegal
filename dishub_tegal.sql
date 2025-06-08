-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 08, 2025 at 04:39 AM
-- Server version: 8.4.3
-- PHP Version: 8.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dishub_tegal`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `fullname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(2, 'admin', 'admin@gmail.com', '$2y$10$hJcggLXtm0NUefmfcoLIre0iqg4ZeZBygA8iVftBrHMHMcQrLKbta', 'admin', '2025-06-07 21:50:21', '2025-06-07 21:50:33');

-- --------------------------------------------------------

--
-- Table structure for table `home`
--

CREATE TABLE `home` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home`
--

INSERT INTO `home` (`id`, `title`, `description`, `image`, `created_at`, `updated_at`) VALUES
(3, 'Selamat Datang di Dishub Tegal', 'Informasi dan Edukasi Transportasi untuk Masyarakat Kabupaten Tegal', '6844b5f4c8d03_image-removebg-preview 1.png', '2025-06-07 21:58:12', '2025-06-07 21:59:12');

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `text` text COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `title`, `text`, `description`, `image`, `created_at`, `updated_at`) VALUES
(3, 'Tentang kami', 'merupakan salah satu instansi pemerintahan yang memiliki peran vital dalam pembangunan dan pengelolaan sistem transportasi nasional maupun daerah. Instansi ini berada di bawah koordinasi Kementerian Perhubungan Republik Indonesia di tingkat pusat, dan di tingkat daerah berada di bawah struktur pemerintahan provinsi, kabupaten, atau kota.', 'Dishub bertugas menyelenggarakan urusan pemerintahan di bidang perhubungan, meliputi transportasi darat, laut, udara, dan perkeretaapian, tergantung pada cakupan wilayah kewenangannya. Fokus utamanya adalah memastikan bahwa segala bentuk mobilitas masyarakat dan distribusi barang dapat berlangsung dengan aman, lancar, teratur, terkoordinasi, dan efisien, sekaligus mendukung pertumbuhan ekonomi serta pembangunan yang berkelanjutan.', '6844b8c87c1b4.jpg', '2025-06-07 22:10:16', '2025-06-07 22:10:16');

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `video` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`id`, `title`, `description`, `video`, `created_at`, `updated_at`) VALUES
(4, 'Transfortasi Publik', 'Informasi layanan transportasi publik', '30.tambahan video lalu lintas.mp4', '2025-06-07 22:45:54', '2025-06-08 02:54:57'),
(5, 'Tata Cara Uji KIR', 'Prosedur pengujian kendaraan bermotor', 'Dinas Perhubungan Kabupaten Tegal.mp4', '2025-06-07 22:46:11', '2025-06-08 02:54:22'),
(6, 'Keselamatan Berkendara', 'Panduan keselamatan berkendara di jalan raya', 'Macbook-Air-tegal-info-hub-digital.lovable.app-3m9_ujto7mqzog.webm', '2025-06-07 22:46:26', '2025-06-08 02:53:36');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `title`, `image`, `created_at`, `updated_at`) VALUES
(5, 'Mollitia mollitia co', '6844d6555e218_dishub.jpg', '2025-06-08 00:16:21', '2025-06-08 00:16:21'),
(6, 'Velit voluptatem do ', '6844d76b95948_pexels-margerretta-548089.jpg', '2025-06-08 00:20:59', '2025-06-08 00:20:59'),
(7, 'Ex cupiditate offici', '6844d774c8ca5_pexels-carolyn-1539564-3345082.jpg', '2025-06-08 00:21:08', '2025-06-08 00:21:08'),
(8, 'Recusandae Molestia', '6844d785cef92_pexels-nikki-shrestha-415993470-32385971.jpg', '2025-06-08 00:21:25', '2025-06-08 00:21:25'),
(9, 'In voluptatibus nobi', '6844d7b64fc9b_pexels-margerretta-548077.jpg', '2025-06-08 00:22:14', '2025-06-08 00:22:14'),
(10, 'Neque error unde ess', '6844d7bf5f38b_pexels-evans-joel-930567223-32422844.jpg', '2025-06-08 00:22:23', '2025-06-08 00:22:23'),
(11, 'Nam itaque duis exer', '6844d7c923dfb_pexels-athena-3009989.jpg', '2025-06-08 00:22:33', '2025-06-08 00:22:33'),
(12, 'Ipsa voluptatem Do', '6844d7dd96550_pexels-antonio-filigno-159809-584799.jpg', '2025-06-08 00:22:53', '2025-06-08 00:22:53'),
(13, 'Quibusdam maxime min', '6844d7e93dfa8_pexels-kelly-1179532-2833683.jpg', '2025-06-08 00:23:05', '2025-06-08 00:23:05'),
(14, 'Est quia exercitati', '6844d7f2ef3f4_pexels-alscre-1631778.jpg', '2025-06-08 00:23:15', '2025-06-08 00:23:15'),
(15, 'Est eu commodi et of', '6844d7fe83cf8_pexels-hanne-sema-1273780199-30675194.jpg', '2025-06-08 00:23:26', '2025-06-08 00:23:26'),
(16, 'Ut est in possimus ', '6844ef74d836c_pexels-hanne-sema-1273780199-30675194.jpg', '2025-06-08 02:03:32', '2025-06-08 02:03:32'),
(17, 'Voluptatibus ut repu', '6844ef88c64d6_pexels-alscre-1631778.jpg', '2025-06-08 02:03:52', '2025-06-08 02:03:52');

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `title`, `slug`, `description`, `content`, `image`, `created_at`, `updated_at`) VALUES
(2, 'Pentingnya Desain Responsif dalam Website Modern', 'pentingnya-desain-responsif-dalam-website-modern', 'Di era digital saat ini, mayoritas pengguna internet mengakses situs web melalui perangkat mobile. Oleh karena itu, desain responsif bukan lagi pilihan, melainkan keharusan.', '<img src=\"https://images.pexels.com/photos/31173368/pexels-photo-31173368/free-photo-of-fasad-berwarna-warni-di-sepanjang-kanal-amsterdam.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1\" alt=\"https://images.pexels.com/photos/31173368/pexels-photo-31173368/free-photo-of-fasad-berwarna-warni-di-sepanjang-kanal-amsterdam.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1\" class=\"max-w-full h-auto rounded-lg shadow-md my-4\" />\r\n<h1 class=\"text-4xl font-bold mb-4\">Mengapa Desain Responsif Menjadi Kebutuhan Utama di Era Digital</h1>\r\n    <p>Desain responsif memungkinkan tampilan website menyesuaikan diri dengan berbagai ukuran layar, baik itu smartphone, tablet, maupun desktop. Ini memberikan pengalaman pengguna (user experience) yang optimal dan meningkatkan keterlibatan pengunjung.</p>\r\n    <p>Selain itu, Google juga memberi peringkat lebih baik bagi situs web yang responsif, karena dianggap lebih ramah pengguna.</p>\r\n    <p>Maka dari itu, pastikan situs Anda menggunakan teknik responsif seperti media queries, layout fleksibel, serta gambar yang menyesuaikan ukuran layar.</p>\r\n    <p>Dengan menerapkan desain responsif, Anda tidak hanya mempermudah pengguna, tetapi juga meningkatkan performa bisnis secara keseluruhan.</p>\r\n', '6844e6c93962f_pexels-margerretta-548089.jpg', '2025-06-08 01:26:33', '2025-06-08 01:32:01'),
(3, 'Manfaat Website Cepat untuk Bisnis Online', 'manfaat-website-cepat-untuk-bisnis-online', 'Mengapa Kecepatan Website Menjadi Kunci Sukses Bisnis Digital', '<img src=\"https://images.pexels.com/photos/22020981/pexels-photo-22020981/free-photo-of-matahari-terbenam-bangunan-gedung-rumah.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1\" alt=\"https://images.pexels.com/photos/22020981/pexels-photo-22020981/free-photo-of-matahari-terbenam-bangunan-gedung-rumah.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1\" class=\"max-w-full h-auto rounded-lg shadow-md my-4\" />\r\n<h1 class=\"text-4xl font-bold mb-4\">Mengapa Kecepatan Website Menjadi Kunci Sukses Bisnis Digital\r\n</h1>\r\n<p>\r\nPengunjung cenderung meninggalkan situs yang membutuhkan waktu lebih dari 3 detik untuk dimuat.\r\nWebsite yang cepat tidak hanya meningkatkan pengalaman pengguna, tapi juga membantu menaikkan peringkat SEO.\r\n</p>\r\n<p>\r\nDengan mempercepat website melalui optimasi gambar, caching, dan minimisasi file, Anda meningkatkan konversi serta kepercayaan pelanggan terhadap brand Anda.  \r\n</p>\r\n', '6844e9ebed50e_pexels-carolyn-1539564-3345082.jpg', '2025-06-08 01:39:55', '2025-06-08 01:39:55'),
(4, 'Perbedaan UI dan UX dalam Desain Web', 'perbedaan-ui-dan-ux-dalam-desain-web', 'Perbedaan UI dan UX dalam Desain Web\r\n', '<img src=\"https://images.pexels.com/photos/20894271/pexels-photo-20894271/free-photo-of-pasar-kerajinan-tembikar-kerajinan-tanah-liat.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1\" alt=\"https://images.pexels.com/photos/20894271/pexels-photo-20894271/free-photo-of-pasar-kerajinan-tembikar-kerajinan-tanah-liat.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1\" class=\"max-w-full h-auto rounded-lg shadow-md my-4\" />\r\nUI (User Interface) fokus pada tampilan antarmuka seperti warna, tombol, dan tipografi. Sementara UX (User Experience) lebih kepada alur dan kenyamanan pengguna saat berinteraksi.\r\n\r\nKeduanya saling melengkapi. Desain yang indah tapi membingungkan bisa merusak UX. Sebaliknya, UX yang solid dengan UI buruk juga dapat menurunkan kredibilitas situs.\r\n\r\nPastikan Anda menyeimbangkan kedua aspek tersebut untuk menciptakan pengalaman pengguna yang menyenangkan dan efisien.', '6844ea67345ff_pexels-nikki-shrestha-415993470-32385971.jpg', '2025-06-08 01:41:59', '2025-06-08 01:41:59'),
(5, 'Pentingnya SEO dalam Pengembangan Website', 'pentingnya-seo-dalam-pengembangan-website', 'Pentingnya SEO dalam Pengembangan Website', '<img src=\"https://images.pexels.com/photos/32010399/pexels-photo-32010399/free-photo-of-ruang-lukisan-dinding-kuno-di-pompeii.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1\" alt=\"\" class=\"max-w-full h-auto rounded-lg shadow-md my-4\" />\r\nSEO (Search Engine Optimization) memungkinkan website Anda muncul di halaman pertama mesin pencari.\r\n\r\nTeknik dasar SEO meliputi penggunaan kata kunci yang tepat, struktur heading yang rapi, URL yang bersih, dan kecepatan website.\r\n\r\nDengan SEO yang baik, Anda tidak hanya meningkatkan traffic organik, tapi juga memperluas jangkauan pasar Anda tanpa biaya iklan yang besar.', '6844eac4908de_pexels-evans-joel-930567223-32422844.jpg', '2025-06-08 01:43:32', '2025-06-08 01:43:32');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('unread','read') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `first_name`, `last_name`, `email`, `message`, `status`, `created_at`) VALUES
(1, 'Zane', 'Nichols', 'qumav@mailinator.com', 'Lorem nisi ut aperia', 'unread', '2025-06-08 04:23:39'),
(2, 'Karina', 'Conley', 'nypat@mailinator.com', 'Aut esse itaque comm', 'unread', '2025-06-08 04:24:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home`
--
ALTER TABLE `home`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `home`
--
ALTER TABLE `home`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2026 at 02:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `moodspace_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `konten_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `konten_id`, `created_at`) VALUES
(13, 7, 18, '2026-06-03 14:21:32'),
(16, 5, 18, '2026-06-07 14:00:34'),
(25, 5, 26, '2026-06-07 16:08:14'),
(33, 5, 25, '2026-06-07 17:50:21');

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `following_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`id`, `follower_id`, `following_id`, `created_at`) VALUES
(1, 7, 6, '2026-06-03 14:16:02'),
(3, 8, 3, '2026-06-05 05:55:29'),
(4, 8, 5, '2026-06-05 06:01:52'),
(12, 3, 8, '2026-06-07 14:47:34'),
(32, 5, 3, '2026-06-07 16:39:11'),
(33, 5, 4, '2026-06-07 17:42:40'),
(34, 5, 2, '2026-06-07 17:42:45'),
(35, 5, 6, '2026-06-07 17:42:55'),
(39, 3, 2, '2026-06-08 03:50:45'),
(46, 3, 5, '2026-06-08 03:56:47'),
(47, 3, 6, '2026-06-08 04:06:56'),
(48, 3, 4, '2026-06-08 04:07:03'),
(49, 2, 4, '2026-06-08 12:30:46'),
(50, 2, 6, '2026-06-08 12:30:53'),
(51, 4, 3, '2026-06-08 12:31:21'),
(52, 4, 2, '2026-06-08 12:31:30'),
(53, 4, 6, '2026-06-08 12:31:36'),
(54, 4, 5, '2026-06-08 12:31:43'),
(55, 6, 3, '2026-06-08 12:32:11'),
(56, 6, 5, '2026-06-08 12:32:19'),
(57, 6, 2, '2026-06-08 12:32:25'),
(58, 6, 4, '2026-06-08 12:32:32'),
(59, 2, 3, '2026-06-08 12:33:06'),
(60, 2, 5, '2026-06-08 12:33:14');

-- --------------------------------------------------------

--
-- Table structure for table `komentar`
--

CREATE TABLE `komentar` (
  `id` int(11) NOT NULL,
  `konten_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `teks` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `komentar`
--

INSERT INTO `komentar` (`id`, `konten_id`, `user_id`, `teks`, `created_at`) VALUES
(2, 26, 5, 'sedih bgt cik', '2026-06-07 15:41:12'),
(3, 16, 5, 'sedih bgt cik', '2026-06-07 16:29:20'),
(4, 18, 5, 'kasian takaki kun', '2026-06-07 16:37:00'),
(5, 19, 3, 'gakuat lagi ye', '2026-06-08 04:00:52');

-- --------------------------------------------------------

--
-- Table structure for table `konten_mood`
--

CREATE TABLE `konten_mood` (
  `id` int(11) NOT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `mood` varchar(50) NOT NULL,
  `tipe` varchar(50) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `sumber` varchar(255) NOT NULL,
  `media_id` varchar(255) NOT NULL,
  `file_url` varchar(255) DEFAULT NULL,
  `cover_url` varchar(500) DEFAULT NULL,
  `public_id` varchar(255) DEFAULT NULL,
  `durasi` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konten_mood`
--

INSERT INTO `konten_mood` (`id`, `uploaded_by`, `mood`, `tipe`, `judul`, `sumber`, `media_id`, `file_url`, `cover_url`, `public_id`, `durasi`, `created_at`) VALUES
(11, 3, 'joy', 'quote', 'Senang bisa mengenalmu', 'Gabuzy', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780473626/moodspace_konten/irzreqloavanh5fpekjm.jpg', NULL, 'moodspace_konten/irzreqloavanh5fpekjm', NULL, '2026-06-03 08:00:25'),
(16, 3, 'sadness', 'music', 'Multo', 'Cup of Joe', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780484570/moodspace_konten/fuwczeystoi9mrmirub8.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780484575/moodspace/covers/rrf3ohtvi2prbyus0s8g.png', 'moodspace_konten/fuwczeystoi9mrmirub8', '3', '2026-06-03 11:02:56'),
(17, 3, 'joy', 'quote', 'Happiness', 'Gabuzy', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780487799/moodspace_konten/srvwbcam522vei9hieff.jpg', NULL, 'moodspace_konten/srvwbcam522vei9hieff', NULL, '2026-06-03 11:56:39'),
(18, 3, 'sadness', 'video', 'Lihat Bunga Sakura Lagi', 'Gabuzy', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780487903/moodspace_konten/bty8cmww4twztwtqvij5.mp4', NULL, 'moodspace_konten/bty8cmww4twztwtqvij5', '0', '2026-06-03 11:58:24'),
(19, 3, 'sadness', 'video', 'Tukeran Hidup', 'Gabuzy', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780488749/moodspace_konten/pnwu20v7misqyrd2fui4.mp4', NULL, 'moodspace_konten/pnwu20v7misqyrd2fui4', '0', '2026-06-03 12:12:30'),
(20, 3, 'sadness', 'quote', 'Kerja', 'Gabuzy', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780490492/moodspace_konten/skrs17s7vsy8t0xvzu28.jpg', NULL, 'moodspace_konten/skrs17s7vsy8t0xvzu28', NULL, '2026-06-03 12:41:33'),
(21, 3, 'sadness', 'quote', 'Tidak bisa apa apa', 'Gabuzy', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780490542/moodspace_konten/ngjbfhlqkd8uoxfdh03k.jpg', NULL, 'moodspace_konten/ngjbfhlqkd8uoxfdh03k', NULL, '2026-06-03 12:42:24'),
(22, 3, 'sadness', 'quote', 'Gagal dalam Hidup', 'Gabuzy', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780490628/moodspace_konten/rft3x1awavxx5mzeyxye.jpg', NULL, 'moodspace_konten/rft3x1awavxx5mzeyxye', NULL, '2026-06-03 12:43:48'),
(23, 3, 'sadness', 'quote', 'Memaksakan Diri', 'Gabuzy', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780492963/moodspace_konten/sonqdehdu30shhwjfads.jpg', NULL, 'moodspace_konten/sonqdehdu30shhwjfads', NULL, '2026-06-03 13:22:43'),
(24, 3, 'sadness', 'quote', 'Gagal Masuk Perguruan Tinggi', 'Gabuzy', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780493008/moodspace_konten/ytkigk2qyseybyqzwojc.jpg', NULL, 'moodspace_konten/ytkigk2qyseybyqzwojc', NULL, '2026-06-03 13:23:28'),
(25, 3, 'sadness', 'quote', 'Tidak amat sengsara', 'Gabuzy', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780493194/moodspace_konten/gipvku30ldodresr3xwp.jpg', NULL, 'moodspace_konten/gipvku30ldodresr3xwp', NULL, '2026-06-03 13:26:36'),
(26, 3, 'sadness', 'music', 'Serana', 'For Revenge', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780501706/moodspace_konten/zqutxx5n8oyb1sajxdyy.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780501709/moodspace/covers/jvfn3zm0sptix8cyftic.png', 'moodspace_konten/zqutxx5n8oyb1sajxdyy', '4', '2026-06-03 15:48:29'),
(28, 3, 'sadness', 'music', 'Iris', 'Goo Goo Dolls', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780502037/moodspace_konten/w0hhcwfewsehy1zte2oe.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780502040/moodspace/covers/lsqpiuxsixq7ko7cgxcl.png', 'moodspace_konten/w0hhcwfewsehy1zte2oe', '4', '2026-06-03 15:54:00'),
(29, 3, 'sadness', 'video', 'Menolong Diri Sendiri', 'Gabuzy', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780502967/moodspace_konten/gibtlpsxbwe3ia0ufebv.mp4', NULL, 'moodspace_konten/gibtlpsxbwe3ia0ufebv', '0', '2026-06-03 16:09:27'),
(30, 3, 'sadness', 'video', 'Ngelindungin Monster', 'Gabuzy', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780504123/moodspace_konten/j2glohqnch6zcltgeita.mp4', NULL, 'moodspace_konten/j2glohqnch6zcltgeita', '0:35', '2026-06-03 16:28:43'),
(31, 3, 'anger', 'video', 'Dragon Warrior', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780543059/moodspace_konten/wugs4hjrrl0ywcronnfv.mp4', NULL, 'moodspace_konten/wugs4hjrrl0ywcronnfv', '0:59', '2026-06-04 03:17:40'),
(32, 3, 'anxiety', 'video', 'Meragukan Diri Sendiri', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780543154/moodspace_konten/or7lsiqpzehijhvkdslg.mp4', NULL, 'moodspace_konten/or7lsiqpzehijhvkdslg', '0:33', '2026-06-04 03:19:14'),
(33, 3, 'joy', 'video', 'Beautiful Girl Made in Korea', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780543248/moodspace_konten/j8zomarhe2qz20cnwgxk.mp4', NULL, 'moodspace_konten/j8zomarhe2qz20cnwgxk', '0:24', '2026-06-04 03:20:49'),
(34, 3, 'sadness', 'video', 'Pilih Satu', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780543397/moodspace_konten/ce8lqcvam1vzt2ns2wrz.mp4', NULL, 'moodspace_konten/ce8lqcvam1vzt2ns2wrz', '0:34', '2026-06-04 03:23:17'),
(35, 3, 'fear', 'video', 'Tidak Sanggup Lagi', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780543466/moodspace_konten/upamba0ahsezorrlkt2u.mp4', NULL, 'moodspace_konten/upamba0ahsezorrlkt2u', '0:44', '2026-06-04 03:24:27'),
(36, 3, 'embarrassment', 'video', 'Menatap Semua Orang', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780543688/moodspace_konten/sts3hxsvdlga5aq8tnix.mp4', NULL, 'moodspace_konten/sts3hxsvdlga5aq8tnix', '0:52', '2026-06-04 03:28:08'),
(37, 3, 'sadness', 'video', 'Semua Kecewa Padaku', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780543838/moodspace_konten/lutpxqzoxb5siyoebpaj.mp4', NULL, 'moodspace_konten/lutpxqzoxb5siyoebpaj', '0:39', '2026-06-04 03:30:38'),
(38, 3, 'sadness', 'video', 'In Another Life', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780544072/moodspace_konten/qalxbmz1sh1offmflqhj.mp4', NULL, 'moodspace_konten/qalxbmz1sh1offmflqhj', '0:33', '2026-06-04 03:34:32'),
(39, 3, 'joy', 'video', 'Little Cute Thing', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780544407/moodspace_konten/srbm9xe6esrmwhaogztz.mp4', NULL, 'moodspace_konten/srbm9xe6esrmwhaogztz', '0:19', '2026-06-04 03:40:07'),
(40, 3, 'sadness', 'music', 'That Should Be Me', 'Justin Bieber', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780544501/moodspace_konten/frk6jgp3bm7adpjwgjkw.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780544504/moodspace/covers/erejzjlknjxgtbjb5npp.png', 'moodspace_konten/frk6jgp3bm7adpjwgjkw', '3:53', '2026-06-04 03:41:44'),
(41, 3, 'sadness', 'music', 'Duvet', 'boa', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780544986/moodspace_konten/zhaiojcylkat4uogtgak.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780544990/moodspace/covers/dwu6sqrom0btkjxft5nq.png', 'moodspace_konten/zhaiojcylkat4uogtgak', '3:24', '2026-06-04 03:49:50'),
(42, 3, 'sadness', 'music', 'Satu Bulan', 'Bernadya', '3:21', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780545252/moodspace_konten/qvicunqulprogen3f26r.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780545274/moodspace/covers/usq8zrtsrpxe575jy9vu.png', 'moodspace_konten/qvicunqulprogen3f26r', '4:23', '2026-06-04 03:54:34'),
(43, 3, 'sadness', 'music', 'Backburner', 'NIKI', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780545333/moodspace_konten/eze6qgbwpi2ppy5v2yhn.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780545337/moodspace/covers/rpoyrcw2a05qh3rn6xkn.png', 'moodspace_konten/eze6qgbwpi2ppy5v2yhn', '3:57', '2026-06-04 03:55:37'),
(44, 3, 'sadness', 'music', 'Ayah', 'Seventeen', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780545410/moodspace_konten/tilexnpsq8eeyhec57et.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780545414/moodspace/covers/ywnvm9bn4skyplo0xv0g.png', 'moodspace_konten/tilexnpsq8eeyhec57et', '4:02', '2026-06-04 03:56:54'),
(45, 3, 'sadness', 'music', 'December', 'Neck Deep', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780545525/moodspace_konten/s9xosrtt4wco0lznynxv.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780545529/moodspace/covers/inonfw8dw5wdtddzlwp9.png', 'moodspace_konten/s9xosrtt4wco0lznynxv', '3:39', '2026-06-04 03:58:49'),
(46, 3, 'sadness', 'music', 'Antara Ada dan Tiada', 'Utopia', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780545687/moodspace_konten/wapb7jwd5rf7flrj6nry.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780545690/moodspace/covers/vbnqwsdybljzwbya0iig.png', 'moodspace_konten/wapb7jwd5rf7flrj6nry', '4:03', '2026-06-04 04:01:30'),
(47, 3, 'sadness', 'video', 'Ayahku Tidak Pernah Ada', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780545835/moodspace_konten/spycoa7qomkvxd37ikei.mp4', NULL, 'moodspace_konten/spycoa7qomkvxd37ikei', '0:49', '2026-06-04 04:03:55'),
(48, 3, 'sadness', 'video', 'Selalu Kecewa', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780546311/moodspace_konten/fwrfgwvrsivtgfyau9lf.mp4', NULL, 'moodspace_konten/fwrfgwvrsivtgfyau9lf', '0:42', '2026-06-04 04:11:51'),
(49, 3, 'sadness', 'video', 'Fly Away Bongseok', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780546388/moodspace_konten/xbz75wxkofqdx5q3jnx4.mp4', NULL, 'moodspace_konten/xbz75wxkofqdx5q3jnx4', '0:34', '2026-06-04 04:13:08'),
(50, 3, 'sadness', 'video', 'Bagaimana Bisa Aku Melepaskanmu', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780546540/moodspace_konten/uwwnjilpzuhr2sc1cfys.mp4', NULL, 'moodspace_konten/uwwnjilpzuhr2sc1cfys', '0:38', '2026-06-04 04:15:41'),
(51, 3, 'sadness', 'video', '0 UCL', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780546693/moodspace_konten/h1l9ao1w20ngmhfsvrco.mp4', NULL, 'moodspace_konten/h1l9ao1w20ngmhfsvrco', '0:34', '2026-06-04 04:18:14'),
(52, 5, 'anger', 'video', 'Escape From Chorh-Gom Prison', 'adeptri', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780852042/moodspace_konten/y4xgakcbsijrfe6adrkn.mp4', NULL, 'moodspace_konten/y4xgakcbsijrfe6adrkn', '1:01', '2026-06-07 17:07:23'),
(53, 2, 'joy', 'music', 'You\'re on Your Own, Kid', 'Taylor Swift', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780922586/moodspace_konten/owdykvtskjry7zecvdzj.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780922588/moodspace/covers/hemul4hyun0wd1tokuel.png', 'moodspace_konten/owdykvtskjry7zecvdzj', '3:14', '2026-06-08 12:43:09');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `konten_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `konten_id`, `created_at`) VALUES
(12, 7, 18, '2026-06-03 14:20:29'),
(15, 7, 20, '2026-06-03 14:21:49'),
(18, 3, 22, '2026-06-03 17:38:42'),
(24, 8, 18, '2026-06-05 06:01:21'),
(27, 5, 18, '2026-06-07 14:00:44'),
(28, 5, 31, '2026-06-07 14:26:58'),
(30, 5, 26, '2026-06-07 15:29:52'),
(36, 5, 22, '2026-06-07 17:32:43'),
(39, 5, 20, '2026-06-07 17:50:15'),
(40, 5, 23, '2026-06-07 17:50:18'),
(46, 3, 52, '2026-06-08 11:59:06');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `teks` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `teks`, `is_read`, `created_at`) VALUES
(1, 3, 2, 'woi', 1, '2026-06-08 03:21:54'),
(2, 2, 3, 'woi juga', 1, '2026-06-08 03:23:08'),
(3, 3, 2, 'woi woi woi', 0, '2026-06-08 03:30:42'),
(4, 3, 8, 'woi', 0, '2026-06-08 03:32:23'),
(5, 3, 5, 'woi', 0, '2026-06-08 03:48:16'),
(6, 3, 6, 'woi', 0, '2026-06-08 03:54:46'),
(7, 3, 4, 'woi', 0, '2026-06-08 12:05:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('creator','user') DEFAULT 'user',
  `username` varchar(50) DEFAULT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `following_count` int(11) DEFAULT 0,
  `followers_count` int(11) DEFAULT 0,
  `likes_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `username`, `display_name`, `bio`, `profile_picture`, `following_count`, `followers_count`, `likes_count`) VALUES
(2, 'rahma@gmail.com', '$2y$10$R.qzmkBtpEAxFtCJ82XikunJiZmTjuYc.t30tlAYOzhkXhyWmS0Nm', 'creator', 'rahma', 'rahma', '', 'assets/uploads/avatar_2_1780487114.png', 4, 4, 0),
(3, 'gabuzy@gmail.com', '$2y$10$Jtp6olwz4pnKHWqh3dC0hueTr4DjttrSIFASv/4mbhs2yvc4FTYsm', 'creator', 'gabuzy', 'GABUZY', 'jika aku mati maka manusia sudah punah', 'assets/uploads/avatar_3_1780466281.webp', 5, 5, 0),
(4, 'kezia@gmail.com', '$2y$10$PJn7y8.UxGgNgySa7eZr0.y0CFQ28w0wTJZJhXtnZhRy82/MAMkI.', 'creator', 'kezia', 'kezia', '', 'assets/uploads/avatar_4_1780487158.png', 4, 4, 0),
(5, 'adeptri@gmail.com', '$2y$10$DTHXc2lnHDEQ.X9x40Aq4Obxbm1hLmkNLMOTXx8GeBBVqKEgj1TUO', 'creator', 'adeptri', 'adeptri', '', 'assets/uploads/avatar_5_1780487208.jpeg', 4, 5, 0),
(6, 'angel@gmail.com', '$2y$10$f9t790HWkTbnZoDuFZbKmOIOU5vX6wdVLdhQfG/mp6zYBAbkjamRm', 'creator', 'angel', 'angel', '', 'assets/uploads/avatar_6_1780487262.jpeg', 4, 5, 0),
(7, 'adeptri@yahoo.com', '$2y$10$5ZbtMi.fo/LCmOqoiwwkK.p24NoTbqxxILBkOSHDMLy6cD.U0RjyK', 'user', 'adeptri51', 'adeptri51', NULL, NULL, 1, 0, 0),
(8, 'peter@gmail.com', '$2y$10$k8GnjOxx2nmCWhg2Z3UIkepKoA4s4jNJd7IPi9YwkWW.NJtCagYOS', 'user', 'peter', 'peter', '', 'assets/uploads/avatar_8_1780638914.jpg', 2, 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_fav` (`user_id`,`konten_id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_follow` (`follower_id`,`following_id`),
  ADD KEY `idx_follower` (`follower_id`),
  ADD KEY `idx_following` (`following_id`);

--
-- Indexes for table `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_konten_id` (`konten_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `konten_mood`
--
ALTER TABLE `konten_mood`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`user_id`,`konten_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_conversation` (`sender_id`,`receiver_id`),
  ADD KEY `idx_receiver_unread` (`receiver_id`,`is_read`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `konten_mood`
--
ALTER TABLE `konten_mood`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `komentar`
--
ALTER TABLE `komentar`
  ADD CONSTRAINT `fk_komentar_konten` FOREIGN KEY (`konten_id`) REFERENCES `konten_mood` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_komentar_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_msg_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_msg_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

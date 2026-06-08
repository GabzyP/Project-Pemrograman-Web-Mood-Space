-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2026 at 01:09 AM
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
(33, 5, 25, '2026-06-07 17:50:21'),
(38, 5, 20, '2026-06-08 17:47:07'),
(39, 5, 26, '2026-06-08 17:47:23'),
(40, 5, 18, '2026-06-08 17:47:36');

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
(56, 6, 5, '2026-06-08 12:32:19'),
(57, 6, 2, '2026-06-08 12:32:25'),
(58, 6, 4, '2026-06-08 12:32:32'),
(59, 2, 3, '2026-06-08 12:33:06'),
(60, 2, 5, '2026-06-08 12:33:14'),
(72, 5, 3, '2026-06-08 17:59:47'),
(73, 5, 4, '2026-06-08 18:01:12'),
(74, 6, 3, '2026-06-08 18:57:33');

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
(5, 19, 3, 'gakuat lagi ye', '2026-06-08 04:00:52'),
(6, 18, 5, 'kasian', '2026-06-08 17:52:21'),
(7, 20, 5, 'karena pilihanmu sendiri lah', '2026-06-08 17:54:44'),
(8, 81, 5, 'lagu anak anak ye', '2026-06-08 17:55:49'),
(9, 34, 5, 'jadi suami yang baiklah kocak', '2026-06-08 17:56:20'),
(10, 20, 5, 'kocak lu', '2026-06-08 17:59:41'),
(11, 70, 5, 'tah lagu apa', '2026-06-08 18:01:08'),
(12, 17, 5, 'kek kau baik aja', '2026-06-08 18:02:02');

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
(40, 3, 'envy', 'music', 'That Should Be Me', 'Justin Bieber', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780544501/moodspace_konten/frk6jgp3bm7adpjwgjkw.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780544504/moodspace/covers/erejzjlknjxgtbjb5npp.png', 'moodspace_konten/frk6jgp3bm7adpjwgjkw', '3:53', '2026-06-04 03:41:44'),
(41, 3, 'sadness', 'music', 'Duvet', 'boa', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780544986/moodspace_konten/zhaiojcylkat4uogtgak.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780544990/moodspace/covers/dwu6sqrom0btkjxft5nq.png', 'moodspace_konten/zhaiojcylkat4uogtgak', '3:24', '2026-06-04 03:49:50'),
(42, 3, 'sadness', 'music', 'Satu Bulan', 'Bernadya', '3:21', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780545252/moodspace_konten/qvicunqulprogen3f26r.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780545274/moodspace/covers/usq8zrtsrpxe575jy9vu.png', 'moodspace_konten/qvicunqulprogen3f26r', '4:23', '2026-06-04 03:54:34'),
(43, 3, 'sadness', 'music', 'Backburner', 'NIKI', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780545333/moodspace_konten/eze6qgbwpi2ppy5v2yhn.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780545337/moodspace/covers/rpoyrcw2a05qh3rn6xkn.png', 'moodspace_konten/eze6qgbwpi2ppy5v2yhn', '3:57', '2026-06-04 03:55:37'),
(44, 3, 'sadness', 'music', 'Ayah', 'Seventeen', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780545410/moodspace_konten/tilexnpsq8eeyhec57et.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780545414/moodspace/covers/ywnvm9bn4skyplo0xv0g.png', 'moodspace_konten/tilexnpsq8eeyhec57et', '4:02', '2026-06-04 03:56:54'),
(45, 3, 'sadness', 'music', 'December', 'Neck Deep', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780545525/moodspace_konten/s9xosrtt4wco0lznynxv.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780545529/moodspace/covers/inonfw8dw5wdtddzlwp9.png', 'moodspace_konten/s9xosrtt4wco0lznynxv', '3:39', '2026-06-04 03:58:49'),
(46, 3, 'sadness', 'music', 'Antara Ada dan Tiada', 'Utopia', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780545687/moodspace_konten/wapb7jwd5rf7flrj6nry.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780545690/moodspace/covers/vbnqwsdybljzwbya0iig.png', 'moodspace_konten/wapb7jwd5rf7flrj6nry', '4:03', '2026-06-04 04:01:30'),
(47, 3, 'sadness', 'video', 'Ayahku Tidak Pernah Ada', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780545835/moodspace_konten/spycoa7qomkvxd37ikei.mp4', NULL, 'moodspace_konten/spycoa7qomkvxd37ikei', '0:49', '2026-06-04 04:03:55'),
(48, 3, 'sadness', 'video', 'Selalu Kecewa', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780546311/moodspace_konten/fwrfgwvrsivtgfyau9lf.mp4', NULL, 'moodspace_konten/fwrfgwvrsivtgfyau9lf', '0:42', '2026-06-04 04:11:51'),
(49, 3, 'embarrassment', 'video', 'Fly Away Bongseok', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780546388/moodspace_konten/xbz75wxkofqdx5q3jnx4.mp4', NULL, 'moodspace_konten/xbz75wxkofqdx5q3jnx4', '0:34', '2026-06-04 04:13:08'),
(50, 3, 'sadness', 'video', 'Bagaimana Bisa Aku Melepaskanmu', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780546540/moodspace_konten/uwwnjilpzuhr2sc1cfys.mp4', NULL, 'moodspace_konten/uwwnjilpzuhr2sc1cfys', '0:38', '2026-06-04 04:15:41'),
(51, 3, 'sadness', 'video', '0 UCL', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780546693/moodspace_konten/h1l9ao1w20ngmhfsvrco.mp4', NULL, 'moodspace_konten/h1l9ao1w20ngmhfsvrco', '0:34', '2026-06-04 04:18:14'),
(52, 5, 'anger', 'video', 'Escape From Chorh-Gom Prison', 'adeptri', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780852042/moodspace_konten/y4xgakcbsijrfe6adrkn.mp4', NULL, 'moodspace_konten/y4xgakcbsijrfe6adrkn', '1:01', '2026-06-07 17:07:23'),
(53, 2, 'joy', 'music', 'You\'re on Your Own, Kid', 'Taylor Swift', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780922586/moodspace_konten/owdykvtskjry7zecvdzj.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780922588/moodspace/covers/hemul4hyun0wd1tokuel.png', 'moodspace_konten/owdykvtskjry7zecvdzj', '3:14', '2026-06-08 12:43:09'),
(54, 2, 'joy', 'music', 'Lover', 'Taylor Swift', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780931228/moodspace_konten/hfsrfea3pwtftxfmzqfa.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780931231/moodspace/covers/jbrjip8f5zhvflvijiol.png', 'moodspace_konten/hfsrfea3pwtftxfmzqfa', '3:42', '2026-06-08 15:07:12'),
(55, 2, 'joy', 'music', 'That\'s So True', 'Gracie Abrams', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780931419/moodspace_konten/y9mgqihrbekrdvsuij3q.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780931423/moodspace/covers/g5t3hmvxkvmbgysumrkr.png', 'moodspace_konten/y9mgqihrbekrdvsuij3q', '2:47', '2026-06-08 15:10:24'),
(56, 2, 'joy', 'music', 'Cupid (Twin Ver)', 'FIFTY FIFTY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780931590/moodspace_konten/llloyhlazmcffc5yhjsb.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780931593/moodspace/covers/cfdrn4jkiuubhikbidow.png', 'moodspace_konten/llloyhlazmcffc5yhjsb', '2:55', '2026-06-08 15:13:14'),
(57, 2, 'joy', 'music', 'Remaja', 'Hivi!', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780931780/moodspace_konten/xpvzrqwjqylphayp9epk.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780931783/moodspace/covers/puyc2k0cwzvjbzfcbxsv.png', 'moodspace_konten/xpvzrqwjqylphayp9epk', '3:36', '2026-06-08 15:16:24'),
(58, 2, 'joy', 'music', 'Panah Asmara', 'Afgan', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780931923/moodspace_konten/o8cmynkbqm5cdhicphyy.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780931926/moodspace/covers/f8qfuvtron18wqy3ntzw.png', 'moodspace_konten/o8cmynkbqm5cdhicphyy', '4:35', '2026-06-08 15:18:46'),
(59, 2, 'joy', 'music', 'Cantik', 'Kahitna', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780932067/moodspace_konten/j03ijwqexcasex2yhaoc.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780932070/moodspace/covers/y0ngrht9oj8p1mdtdwpw.png', 'moodspace_konten/j03ijwqexcasex2yhaoc', '4:01', '2026-06-08 15:21:11'),
(60, 2, 'joy', 'music', 'Saat Bahagia', 'Ungu', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780932365/moodspace_konten/m063y8nkgmt5lyfqjqjx.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780932368/moodspace/covers/rlbujvb0i1bsj9smikhy.png', 'moodspace_konten/m063y8nkgmt5lyfqjqjx', '4:10', '2026-06-08 15:26:09'),
(61, 2, 'anger', 'music', 'Don\'t You Remember', 'Adele', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780932561/moodspace_konten/tpd9kcrgsqve13dfs7pr.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780932564/moodspace/covers/qcouvemf1aewyw1qyj7g.png', 'moodspace_konten/tpd9kcrgsqve13dfs7pr', '4:18', '2026-06-08 15:29:25'),
(62, 2, 'anger', 'music', 'brutal', 'Olivia Rodrigo', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780932726/moodspace_konten/nvd72haazgzr7oodw4gh.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780932729/moodspace/covers/o1yuwkuudxwe3pwzgp4q.png', 'moodspace_konten/nvd72haazgzr7oodw4gh', '2:24', '2026-06-08 15:32:11'),
(63, 2, 'anger', 'music', 'Kill Bill', 'SZA', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780932871/moodspace_konten/dbtz4q4d3lpify9doj1e.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780932875/moodspace/covers/kne0xx7srrq2sedverji.png', 'moodspace_konten/dbtz4q4d3lpify9doj1e', '4:36', '2026-06-08 15:34:35'),
(64, 2, 'anger', 'music', 'abcdefu', 'GAYLE', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780933053/moodspace_konten/sz1gde0bstrx8ftrt5yk.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780933056/moodspace/covers/krr0qfyxnjaqed0zx0tb.png', 'moodspace_konten/sz1gde0bstrx8ftrt5yk', '2:58', '2026-06-08 15:37:37'),
(65, 4, 'sadness', 'music', 'Duka', 'Last Child', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780933268/moodspace_konten/sjawxfpgonqklgipabdn.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780933271/moodspace/covers/hiralkhdtibh3dtf0zgm.png', 'moodspace_konten/sjawxfpgonqklgipabdn', '5:26', '2026-06-08 15:41:12'),
(66, 4, 'sadness', 'music', 'Bawa Dia Kembali', 'Mahalini', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780933408/moodspace_konten/zizzql8grrjs16ptxumx.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780933411/moodspace/covers/wkakjyli8qncsqhwjdro.png', 'moodspace_konten/zizzql8grrjs16ptxumx', '3:47', '2026-06-08 15:43:32'),
(67, 4, 'sadness', 'music', 'Biar Aku Yang Pergi', 'Aldi Maldini', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780933568/moodspace_konten/uag2xi61ubuvsszytkfu.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780933572/moodspace/covers/awege83x6klzhw3jbgso.png', 'moodspace_konten/uag2xi61ubuvsszytkfu', '4:08', '2026-06-08 15:46:13'),
(68, 4, 'sadness', 'music', 'Merindumu Lagi', 'Khifnu', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780933838/moodspace_konten/sugsdnt5tulvbpfqtev9.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780933841/moodspace/covers/ceshwxmjt7qyi369c3i3.png', 'moodspace_konten/sugsdnt5tulvbpfqtev9', '3:39', '2026-06-08 15:50:41'),
(69, 4, 'sadness', 'music', 'Somebody Pleasure', 'Aziz Hedra', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780933977/moodspace_konten/z6rf9bwab9shqmy8zqd4.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780933980/moodspace/covers/pnyyrsnarnhfavobyayt.png', 'moodspace_konten/z6rf9bwab9shqmy8zqd4', '3:58', '2026-06-08 15:53:01'),
(70, 4, 'anger', 'music', 'LOS VOLTAJE', 'Sayfalse', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780934216/moodspace_konten/jdmina6q2dvdli8sykcx.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780934218/moodspace/covers/ho0sfnogkmuoetvlywc0.png', 'moodspace_konten/jdmina6q2dvdli8sykcx', '1:46', '2026-06-08 15:56:59'),
(71, 4, 'anger', 'music', 'ACELARADA', 'MXZI', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780934354/moodspace_konten/axvwn0kxcc0bk3mvebot.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780934357/moodspace/covers/m0p8w6xoz94q2sropltu.png', 'moodspace_konten/axvwn0kxcc0bk3mvebot', '1:02', '2026-06-08 15:59:18'),
(72, 4, 'anger', 'music', 'MATADORA', 'DJ Asul', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780934467/moodspace_konten/tn0efpwoj1kqv4sg0fbf.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780934470/moodspace/covers/aohnfb8afbdx9o2xnwil.png', 'moodspace_konten/tn0efpwoj1kqv4sg0fbf', '1:29', '2026-06-08 16:01:11'),
(73, 4, 'anger', 'music', 'LUZ ROJA', 'bxkq', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780934596/moodspace_konten/x5ezy2yptvytsdpyjjxv.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780934599/moodspace/covers/rty3kdgzd1ur8bdk3xvo.png', 'moodspace_konten/x5ezy2yptvytsdpyjjxv', '1:50', '2026-06-08 16:03:19'),
(74, 4, 'anger', 'music', 'Amor Na Praia', 'Flame Runner', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780934783/moodspace_konten/cj5jztw2h1pffo8wgs7v.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780934786/moodspace/covers/pmk6i0gwddl5mq27vnsj.png', 'moodspace_konten/cj5jztw2h1pffo8wgs7v', '1:29', '2026-06-08 16:06:27'),
(75, 4, 'anger', 'music', 'Montagem Rebola', 'ATLXS', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780934956/moodspace_konten/armzybtbjbbttk411sho.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780934959/moodspace/covers/evsgkbtlwj8cb637immk.png', 'moodspace_konten/armzybtbjbbttk411sho', '1:42', '2026-06-08 16:09:20'),
(76, 4, 'anger', 'music', 'Montagem Alquimia', 'h6itam', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780935098/moodspace_konten/yjabjxgypvnzb6earhev.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780935100/moodspace/covers/ds28yqn8ilvywf4kixzr.png', 'moodspace_konten/yjabjxgypvnzb6earhev', '1:37', '2026-06-08 16:11:41'),
(77, 4, 'anxiety', 'music', 'happy', 'Gustixa', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780935500/moodspace_konten/npmlnmo7bqchkvv9dnx6.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780935503/moodspace/covers/aywhet4jpibrowmhrvl7.png', 'moodspace_konten/npmlnmo7bqchkvv9dnx6', '2:01', '2026-06-08 16:18:24'),
(78, 4, 'joy', 'music', 'Lemon Tree', 'Gustixa', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780935608/moodspace_konten/bi2cydnsmfz7ccax5zyr.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780935610/moodspace/covers/t2b90ifn6h3cydod3jkl.png', 'moodspace_konten/bi2cydnsmfz7ccax5zyr', '2:39', '2026-06-08 16:20:11'),
(79, 4, 'joy', 'music', 'Happy Ajalah', 'DJ Qhelfin', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780935765/moodspace_konten/fswo2uk14jltwmyp3whn.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780935769/moodspace/covers/wvzjx5qw8bt9stemsroz.png', 'moodspace_konten/fswo2uk14jltwmyp3whn', '4:36', '2026-06-08 16:22:49'),
(80, 4, 'embarrassment', 'music', 'MALU MALU', 'dia & Indahkus', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780939808/moodspace_konten/ms1apbsqsw8smktagf2a.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780939812/moodspace/covers/j919uhcnzudku7kbiy0s.png', 'moodspace_konten/ms1apbsqsw8smktagf2a', '3:26', '2026-06-08 17:30:13'),
(81, 4, 'embarrassment', 'music', 'Malu Sama Kucing', 'Romaria', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780939969/moodspace_konten/bhxugvu1niumzievbscz.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780939973/moodspace/covers/xbw1ux64vxptp64rdked.png', 'moodspace_konten/bhxugvu1niumzievbscz', '3:12', '2026-06-08 17:32:53'),
(82, 4, 'embarrassment', 'music', 'Super Shy', 'New Jeans', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780940165/moodspace_konten/lugsjhnyaqiaam0wadun.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780940168/moodspace/covers/qm48w2bigh7g5pc4duy5.png', 'moodspace_konten/lugsjhnyaqiaam0wadun', '3:21', '2026-06-08 17:36:08'),
(83, 5, 'embarrassment', 'music', 'Curi Curi Pandang', 'Maman Fvndy', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780940338/moodspace_konten/gmul1a04idpwmzslaoe6.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780940341/moodspace/covers/sdzyzsfokaix24rnympk.png', 'moodspace_konten/gmul1a04idpwmzslaoe6', '3:18', '2026-06-08 17:39:01'),
(84, 5, 'embarrassment', 'music', 'Malu Malu Dong', 'T2', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780940456/moodspace_konten/pt3xu3usjj5porkp6u9u.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780940459/moodspace/covers/hddixnktfdzsaq1iyzh0.png', 'moodspace_konten/pt3xu3usjj5porkp6u9u', '3:00', '2026-06-08 17:40:59'),
(85, 5, 'embarrassment', 'music', 'Pulang Malu Tak Pulang Rindu', 'Armada', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780940568/moodspace_konten/zbwbljhttvmgu1krqvki.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780940570/moodspace/covers/f6yahb2a8nftdfgl8foi.png', 'moodspace_konten/zbwbljhttvmgu1krqvki', '4:12', '2026-06-08 17:42:51'),
(86, 5, 'embarrassment', 'music', 'Not Shy', 'ITZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780940748/moodspace_konten/bnbdmupsxkj3hmn6phes.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780940751/moodspace/covers/kzlxabu1kjlpcpsolvmr.png', 'moodspace_konten/bnbdmupsxkj3hmn6phes', '4:03', '2026-06-08 17:45:51'),
(87, 5, 'embarrassment', 'music', 'Kisah Kasih di Sekolah', 'Obbie Messakh', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780941877/moodspace_konten/bwibthjs6u0al4itlmeo.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780941881/moodspace/covers/y8cpijpfk5hvq0ek1he9.png', 'moodspace_konten/bwibthjs6u0al4itlmeo', '4:06', '2026-06-08 18:04:42'),
(88, 5, 'embarrassment', 'music', 'Ku Berlari Malu Malu Kucing', 'JERUD', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780942214/moodspace_konten/rpzjgqjwsljadpfi5i5c.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780942218/moodspace/covers/osnwwvv5nffdopt80euq.png', 'moodspace_konten/rpzjgqjwsljadpfi5i5c', '3:15', '2026-06-08 18:10:18'),
(89, 5, 'embarrassment', 'music', 'Calon Mantu Idaman', 'Rombongan Bodonk Koplo', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780942331/moodspace_konten/yl0udtsmuokjjbhefnpt.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780942336/moodspace/covers/suykczolyppnxrkiqqdi.png', 'moodspace_konten/yl0udtsmuokjjbhefnpt', '3:10', '2026-06-08 18:12:17'),
(90, 5, 'embarrassment', 'music', 'Dag Dig Dug', 'BLINK', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780942528/moodspace_konten/klwuuvjzibjfoccwtpwm.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780942531/moodspace/covers/yj4xifdqqxovjdwygweg.png', 'moodspace_konten/klwuuvjzibjfoccwtpwm', '3:54', '2026-06-08 18:15:32'),
(91, 5, 'joy', 'music', 'MBG (Mas Bahlil Ganteng)', 'DJ Topeng', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780942780/moodspace_konten/mxtyatwe9tppoceuxm95.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780942784/moodspace/covers/iguoy02b6ssr5ooocr6a.png', 'moodspace_konten/mxtyatwe9tppoceuxm95', '2:55', '2026-06-08 18:19:45'),
(92, 5, 'disgust', 'music', 'Playboy', '7 Icons', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780942938/moodspace_konten/miwjhyzg34c2n7qexvrc.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780942944/moodspace/covers/ymn0dfot1yqunaqxcsw2.png', 'moodspace_konten/miwjhyzg34c2n7qexvrc', '3:27', '2026-06-08 18:22:24'),
(93, 5, 'disgust', 'music', 'Kamseupay', 'Shelly Puspita', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780943060/moodspace_konten/yenahuse2lht8yanjjnl.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780943064/moodspace/covers/j4rf0txt6afzgqwh9epb.png', 'moodspace_konten/yenahuse2lht8yanjjnl', '3:17', '2026-06-08 18:24:24'),
(94, 5, 'disgust', 'music', 'Aku Jijik', 'Sandrina Azzahra', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780943175/moodspace_konten/ttwuqqhjhymgzc3905lb.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780943178/moodspace/covers/kr8ztitli840cflvsmll.png', 'moodspace_konten/ttwuqqhjhymgzc3905lb', '2:56', '2026-06-08 18:26:18'),
(95, 5, 'disgust', 'music', 'Kamu Hoaxxx', 'Boiyen Pesek', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780943309/moodspace_konten/ftmcckvb8idu9wmfkei4.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780943313/moodspace/covers/mujv8p8xrni683xxn8cp.png', 'moodspace_konten/ftmcckvb8idu9wmfkei4', '3:40', '2026-06-08 18:28:34'),
(96, 5, 'disgust', 'music', 'thank u, next', 'Ariana Grande', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780943412/moodspace_konten/kzkkyhwodyssyo205zph.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780943415/moodspace/covers/oyn4sgt4la6diykp768p.png', 'moodspace_konten/kzkkyhwodyssyo205zph', '3:28', '2026-06-08 18:30:16'),
(97, 5, 'disgust', 'music', 'Alay', 'Lolita', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780943510/moodspace_konten/twu7poquji6r4qxrxdyv.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780943515/moodspace/covers/aynjapsfxxtopsbznyax.png', 'moodspace_konten/twu7poquji6r4qxrxdyv', '3:38', '2026-06-08 18:31:56'),
(98, 5, 'disgust', 'music', 'Cabe Cabean', 'iMeyMey', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780943632/moodspace_konten/bu95nzpiurkl6pru37ps.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780943636/moodspace/covers/knyzb6pgr2jsmnve0vft.png', 'moodspace_konten/bu95nzpiurkl6pru37ps', '3:01', '2026-06-08 18:33:56'),
(99, 5, 'disgust', 'music', 'Emang Gua Pikirin', 'Lolita', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780943830/moodspace_konten/kjbgpjzs9a5yxazl9aw8.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780943835/moodspace/covers/ldeuliboe13qwaqkrvux.png', 'moodspace_konten/kjbgpjzs9a5yxazl9aw8', '3:39', '2026-06-08 18:37:16'),
(100, 5, 'envy', 'music', 'Ngga Dulu', 'Akbar Chalay', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780944096/moodspace_konten/ukstoeht1bnf0sp0xh6o.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780944101/moodspace/covers/lh7swhjwwla9vzm5emtt.png', 'moodspace_konten/ukstoeht1bnf0sp0xh6o', '3:10', '2026-06-08 18:41:42'),
(101, 6, 'envy', 'music', 'Lantas', 'Juicy Luicy', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780944287/moodspace_konten/va7ihkkbkv1wa5wvkrhz.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780944290/moodspace/covers/c6ml9gjkxv6tdhxsate7.png', 'moodspace_konten/va7ihkkbkv1wa5wvkrhz', '3:55', '2026-06-08 18:44:50'),
(102, 6, 'envy', 'music', 'everything i wanted', 'Billie Eilish', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780944507/moodspace_konten/huegwktp88uq5dvnflkc.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780944510/moodspace/covers/t0sldnmprmd62hg1l7bw.png', 'moodspace_konten/huegwktp88uq5dvnflkc', '4:48', '2026-06-08 18:48:30'),
(103, 6, 'envy', 'music', 'jealousy, jealousy', 'Olivia Rodrigo', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780944637/moodspace_konten/wxntxnvw8ahicf9ejoz7.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780944640/moodspace/covers/azv9xpyblj5efigq4vzd.png', 'moodspace_konten/wxntxnvw8ahicf9ejoz7', '2:54', '2026-06-08 18:50:40'),
(104, 6, 'envy', 'music', 'Jealousy', 'Pamungkas', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780944818/moodspace_konten/vw8xazdsgkm35bmoz0s1.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780944822/moodspace/covers/fc7iq5pullmjm4fskzyi.png', 'moodspace_konten/vw8xazdsgkm35bmoz0s1', '4:38', '2026-06-08 18:53:42'),
(105, 6, 'envy', 'music', 'jellyous', 'ILLIT', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780944944/moodspace_konten/bb8whuntaqebb4wm984n.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780944947/moodspace/covers/jegtabmrfih78b5rbg2l.png', 'moodspace_konten/bb8whuntaqebb4wm984n', '2:44', '2026-06-08 18:55:47'),
(106, 6, 'envy', 'music', 'Treat You Better', 'Shawn Mendes', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780945242/moodspace_konten/pej1qmurhjupecdooe2p.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780945246/moodspace/covers/f0bmuiadnmn8avtoju8d.png', 'moodspace_konten/pej1qmurhjupecdooe2p', '4:17', '2026-06-08 19:00:46'),
(107, 3, 'fear', 'music', 'Takut', 'Idgitaf', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780945660/moodspace_konten/acmtihbtcqghyeozl3u4.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780945664/moodspace/covers/ssmzgxdk2gvfboqjaqcb.png', 'moodspace_konten/acmtihbtcqghyeozl3u4', '5:20', '2026-06-08 19:07:44'),
(108, 3, 'fear', 'music', 'Somebody\'s Watching Me', 'Rockwell', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780947959/moodspace_konten/ukqxmjcshqqttgiscznx.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780947962/moodspace/covers/qarzxdnrfqyb9qbkou5q.png', 'moodspace_konten/ukqxmjcshqqttgiscznx', '4:59', '2026-06-08 19:46:03'),
(109, 3, 'fear', 'music', 'Disturbia', 'Rihanna', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780948087/moodspace_konten/okny4vdjgtucedw19hks.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780948090/moodspace/covers/xoby8f4tgkree0amuvmz.png', 'moodspace_konten/okny4vdjgtucedw19hks', '3:59', '2026-06-08 19:48:11'),
(110, 3, 'fear', 'music', 'bury a friend', 'Billie Eilish', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780948191/moodspace_konten/aymtxnzel9onwsqpdjty.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780948194/moodspace/covers/s8ixzq0sn7jxcwqqdzml.png', 'moodspace_konten/aymtxnzel9onwsqpdjty', '3:33', '2026-06-08 19:49:54'),
(111, 3, 'fear', 'music', 'Afraid', 'The Neighbourhood', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780948348/moodspace_konten/fdir077lj9lfgatbiit2.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780948350/moodspace/covers/u8pavh6j6d3b2z29ybwu.png', 'moodspace_konten/fdir077lj9lfgatbiit2', '4:12', '2026-06-08 19:52:31'),
(112, 3, 'fear', 'music', 'Paranoid', 'Black Sabbath', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780948465/moodspace_konten/yc6x8irlobj55ucuhvng.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780948468/moodspace/covers/p83fn1wstrkmybxxxbqt.png', 'moodspace_konten/yc6x8irlobj55ucuhvng', '2:49', '2026-06-08 19:54:29'),
(113, 3, 'fear', 'music', 'Haunted', 'Taylor Swift', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780948671/moodspace_konten/qnxwqrtcpudcwbhcyww1.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780948676/moodspace/covers/zwvzpoodxfz8dtcaekb6.png', 'moodspace_konten/qnxwqrtcpudcwbhcyww1', '4:03', '2026-06-08 19:57:57'),
(114, 3, 'fear', 'music', 'Chandelier', 'Sia', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780948966/moodspace_konten/mzt2kooszpiunwzqhe5o.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780948969/moodspace/covers/njrr8vouvjypgmrmzta4.png', 'moodspace_konten/mzt2kooszpiunwzqhe5o', '3:37', '2026-06-08 20:02:50'),
(115, 3, 'anxiety', 'music', 'Anxiety', 'Julia Michaels', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780949165/moodspace_konten/iicdidm2mrexlb49ysam.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780949167/moodspace/covers/ksxjdi0diqfadqzazav1.png', 'moodspace_konten/iicdidm2mrexlb49ysam', '3:31', '2026-06-08 20:06:08'),
(116, 3, 'anxiety', 'music', 'Overwhelmed', 'Royal & The Serpent', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780949380/moodspace_konten/d8zuhwb1nqlkfg0mjcvf.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780949385/moodspace/covers/vtskcjgdqklng9fxdqj3.png', 'moodspace_konten/d8zuhwb1nqlkfg0mjcvf', '2:40', '2026-06-08 20:09:45'),
(117, 3, 'anxiety', 'music', 'Unwell', 'Matchbox Twenty', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780949501/moodspace_konten/pyisvj8mkpkwaw3axolt.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780949504/moodspace/covers/hu5rvzcwruzqqwgdjhyl.png', 'moodspace_konten/pyisvj8mkpkwaw3axolt', '3:49', '2026-06-08 20:11:45'),
(118, 3, 'anxiety', 'music', 'breathin', 'Ariana Grande', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780949615/moodspace_konten/s9fdsik8bammz7go9q3m.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780949618/moodspace/covers/cdvt6q7uhfg8m4vj00cz.png', 'moodspace_konten/s9fdsik8bammz7go9q3m', '3:19', '2026-06-08 20:13:39'),
(119, 3, 'anxiety', 'music', 'Under Pressure', 'Queen', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780949722/moodspace_konten/wb17uqrqnqh0gxni8gu4.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780949725/moodspace/covers/elza4qxcgfxmpjjijnrw.png', 'moodspace_konten/wb17uqrqnqh0gxni8gu4', '4:09', '2026-06-08 20:15:26'),
(120, 3, 'anxiety', 'music', 'Stressed Out', 'Twenty One Pilot', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780949848/moodspace_konten/fkstmx2ugtxigeg7ss30.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780949850/moodspace/covers/vmewtpaeoq8nn6de6xah.png', 'moodspace_konten/fkstmx2ugtxigeg7ss30', '3:46', '2026-06-08 20:17:31'),
(121, 3, 'ennui', 'music', 'Bored', 'Billie Eilish', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780949999/moodspace_konten/gqjnps3jghtt2ahhe4sh.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780950003/moodspace/covers/pnfarnddh80alf6nkank.png', 'moodspace_konten/gqjnps3jghtt2ahhe4sh', '3:01', '2026-06-08 20:20:04'),
(122, 3, 'ennui', 'music', 'Lost In The World', 'Kanye West', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780950168/moodspace_konten/lkbhnkpg1neca22vsdso.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780950172/moodspace/covers/tc68uqxbsvch4qvny6oq.png', 'moodspace_konten/lkbhnkpg1neca22vsdso', '4:17', '2026-06-08 20:22:52'),
(123, 3, 'ennui', 'music', 'Here', 'Alessia Cara', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780950280/moodspace_konten/bgaxtqfrlfe9mnsbyinp.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780950286/moodspace/covers/nwewlxjttg0o14qf1qli.png', 'moodspace_konten/bgaxtqfrlfe9mnsbyinp', '3:20', '2026-06-08 20:24:47'),
(124, 3, 'ennui', 'music', 'The Lazy Song', 'Bruno Mars', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780950399/moodspace_konten/oodzvlu8gccezzg7bhof.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780950402/moodspace/covers/p3j8q8fbxhbxn9bq1ozq.png', 'moodspace_konten/oodzvlu8gccezzg7bhof', '3:20', '2026-06-08 20:26:42'),
(125, 3, 'ennui', 'music', 'TV', 'Billie Eilish', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780950574/moodspace_konten/winvqabdaesn9uqtpf9r.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780950577/moodspace/covers/xf9jrqwhketh8rdseia0.png', 'moodspace_konten/winvqabdaesn9uqtpf9r', '4:42', '2026-06-08 20:29:37'),
(126, 3, 'ennui', 'music', 'Space Song', 'Beach House', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780950733/moodspace_konten/e46ekobyybgvvoazjcn8.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780950737/moodspace/covers/xmrqimsrv8hrq2jm8cqt.png', 'moodspace_konten/e46ekobyybgvvoazjcn8', '5:21', '2026-06-08 20:32:19'),
(127, 3, 'ennui', 'music', 'Smells Like Teen Spirit', 'Nirvana', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780950860/moodspace_konten/pqh4carjtyucttp2gyfw.mp4', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780950864/moodspace/covers/rkqsjiogn7faril9nfu3.png', 'moodspace_konten/pqh4carjtyucttp2gyfw', '5:02', '2026-06-08 20:34:24'),
(128, 3, 'anxiety', 'video', 'Mintalah Pertolongan', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780951121/moodspace_konten/im9pkyiwjsd1kwrobfak.mp4', NULL, 'moodspace_konten/im9pkyiwjsd1kwrobfak', '1:03', '2026-06-08 20:38:42'),
(129, 3, 'anxiety', 'video', 'Berhenti Lakukan yang Kau Benci', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780951218/moodspace_konten/ozyqjlk8f0yhychbwnql.mp4', NULL, 'moodspace_konten/ozyqjlk8f0yhychbwnql', '0:47', '2026-06-08 20:40:19'),
(130, 3, 'sadness', 'video', 'Bertamu ke Rumah Barumu', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780951328/moodspace_konten/uytw8dng15ykj5jeta8r.mp4', NULL, 'moodspace_konten/uytw8dng15ykj5jeta8r', '0:29', '2026-06-08 20:42:09'),
(131, 3, 'embarrassment', 'video', 'Germany 2014', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780951426/moodspace_konten/tcmeeujkmse5zz1p2abe.mp4', NULL, 'moodspace_konten/tcmeeujkmse5zz1p2abe', '0:16', '2026-06-08 20:43:47'),
(133, 3, 'sadness', 'video', 'Bayern 2026 Ending', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780951589/moodspace_konten/o342wvtaurj1ylirw3tq.mp4', NULL, 'moodspace_konten/o342wvtaurj1ylirw3tq', '0:27', '2026-06-08 20:46:30'),
(134, 3, 'joy', 'video', 'Moving Couple', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780951914/moodspace_konten/prrm12ovmm7gisoqg7f3.mp4', NULL, 'moodspace_konten/prrm12ovmm7gisoqg7f3', '0:33', '2026-06-08 20:51:55'),
(135, 3, 'joy', 'video', 'Healing', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780951985/moodspace_konten/xzlkfqv9bw2lijvlwskf.mp4', NULL, 'moodspace_konten/xzlkfqv9bw2lijvlwskf', '0:21', '2026-06-08 20:53:06'),
(136, 3, 'fear', 'video', 'Duo Sikopat', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780952348/moodspace_konten/fuw1olulhcrexlkuwo5r.mp4', NULL, 'moodspace_konten/fuw1olulhcrexlkuwo5r', '0:16', '2026-06-08 20:59:09'),
(137, 3, 'anger', 'video', 'Kungfu Panda Villain', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780952498/moodspace_konten/mu2qspoy2n4jfdntbfgv.mp4', NULL, 'moodspace_konten/mu2qspoy2n4jfdntbfgv', '1:08', '2026-06-08 21:01:39'),
(138, 3, 'sadness', 'video', 'How You Find Peace', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780952619/moodspace_konten/vlaumxailpmeh9iirrcu.mp4', NULL, 'moodspace_konten/vlaumxailpmeh9iirrcu', '0:44', '2026-06-08 21:03:39'),
(139, 3, 'sadness', 'video', 'Aku Tak Tahu yang Kusuka', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780952692/moodspace_konten/of16tdvbf5kmxj6akbn4.mp4', NULL, 'moodspace_konten/of16tdvbf5kmxj6akbn4', '0:51', '2026-06-08 21:04:52'),
(140, 3, 'anxiety', 'video', 'Satu Menit Saat Ini', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780952749/moodspace_konten/zfdo0fiuur4jtuzcyqzr.mp4', NULL, 'moodspace_konten/zfdo0fiuur4jtuzcyqzr', '0:50', '2026-06-08 21:05:49'),
(141, 3, 'ennui', 'video', 'Angin Kencang', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780952838/moodspace_konten/wairm4afah7pcc9d8qkr.mp4', NULL, 'moodspace_konten/wairm4afah7pcc9d8qkr', '0:07', '2026-06-08 21:07:19'),
(142, 3, 'ennui', 'video', 'Kota Tua', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780952887/moodspace_konten/wguqgijlbftt3isd12hp.mp4', NULL, 'moodspace_konten/wguqgijlbftt3isd12hp', '0:42', '2026-06-08 21:08:08'),
(144, 3, 'ennui', 'video', 'Sunset', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780953102/moodspace_konten/yixu8ai23ruwgifkrcy5.mp4', NULL, 'moodspace_konten/yixu8ai23ruwgifkrcy5', '0:17', '2026-06-08 21:11:42'),
(145, 3, 'joy', 'video', '2026 World Cup Is Coming', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780953158/moodspace_konten/rgeit83v8bcig7gcycni.mp4', NULL, 'moodspace_konten/rgeit83v8bcig7gcycni', '0:24', '2026-06-08 21:12:38'),
(146, 3, 'sadness', 'video', 'Menangis Saat Bersamamu', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780953332/moodspace_konten/zqsm6rthx7fglo9uqdzx.mp4', NULL, 'moodspace_konten/zqsm6rthx7fglo9uqdzx', '1:01', '2026-06-08 21:15:33'),
(147, 3, 'joy', 'video', 'Alay', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780953406/moodspace_konten/fg0hr5f6s9mip2htgmoi.mp4', NULL, 'moodspace_konten/fg0hr5f6s9mip2htgmoi', '0:41', '2026-06-08 21:16:47'),
(148, 3, 'disgust', 'video', 'Bedanya Gua ga Seberisik Elu', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780953518/moodspace_konten/zkk0k2ti1hnvbo1s9uxd.mp4', NULL, 'moodspace_konten/zkk0k2ti1hnvbo1s9uxd', '0:28', '2026-06-08 21:18:39'),
(149, 3, 'sadness', 'video', 'Aku Tak Baik Baik Saja', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780953583/moodspace_konten/nd8nhxpntxtpmy0b8jbb.mp4', NULL, 'moodspace_konten/nd8nhxpntxtpmy0b8jbb', '0:37', '2026-06-08 21:19:44'),
(150, 3, 'ennui', 'video', 'Nongkrong', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780953662/moodspace_konten/cut17osb4ii7mx3lquga.mp4', NULL, 'moodspace_konten/cut17osb4ii7mx3lquga', '0:07', '2026-06-08 21:21:02'),
(151, 3, 'ennui', 'video', 'Terowongan', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780953734/moodspace_konten/d81a0ns8k90oapjbj93e.mp4', NULL, 'moodspace_konten/d81a0ns8k90oapjbj93e', '0:15', '2026-06-08 21:22:14'),
(152, 3, 'ennui', 'video', 'a Day in Life', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780953803/moodspace_konten/zizlhc2wxsedb6jnkmcu.mp4', NULL, 'moodspace_konten/zizlhc2wxsedb6jnkmcu', '0:26', '2026-06-08 21:23:24'),
(153, 3, 'ennui', 'video', 'Golden Hour over The Fields', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780953881/moodspace_konten/dv5ttasp9jabz6wplod8.mp4', NULL, 'moodspace_konten/dv5ttasp9jabz6wplod8', '0:12', '2026-06-08 21:24:41'),
(154, 3, 'ennui', 'video', 'Lofi', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780953959/moodspace_konten/zyur3qk5fjqh3aepixmk.mp4', NULL, 'moodspace_konten/zyur3qk5fjqh3aepixmk', '0:28', '2026-06-08 21:26:00'),
(155, 3, 'ennui', 'video', 'Beach on Sunset', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780954044/moodspace_konten/iajncmyiitpnxtagk6nr.mp4', NULL, 'moodspace_konten/iajncmyiitpnxtagk6nr', '0:11', '2026-06-08 21:27:24'),
(156, 3, 'ennui', 'video', 'Karet Tengsin', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780954108/moodspace_konten/jtic04lxku46cfcekrzj.mp4', NULL, 'moodspace_konten/jtic04lxku46cfcekrzj', '0:44', '2026-06-08 21:28:29'),
(157, 3, 'ennui', 'video', 'Labuan Bajo', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780954202/moodspace_konten/xo0i1snjmzdelfys6hjd.mp4', NULL, 'moodspace_konten/xo0i1snjmzdelfys6hjd', '0:21', '2026-06-08 21:30:03'),
(158, 3, 'ennui', 'video', 'Lombok', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780954327/moodspace_konten/klwrypt7yhwetltgibwv.mp4', NULL, 'moodspace_konten/klwrypt7yhwetltgibwv', '0:26', '2026-06-08 21:32:08'),
(159, 3, 'anger', 'video', 'Tanjiro Kamado', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780954585/moodspace_konten/ko7rdbtmukmupmndk4lr.mp4', NULL, 'moodspace_konten/ko7rdbtmukmupmndk4lr', '0:39', '2026-06-08 21:36:25'),
(160, 3, 'anger', 'video', 'Tengen Uzui vs Gyutaro', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780955306/moodspace_konten/jye0ty7np1a9jdbqy1s1.mp4', NULL, 'moodspace_konten/jye0ty7np1a9jdbqy1s1', '1:22', '2026-06-08 21:48:29'),
(161, 3, 'anger', 'video', 'Saber vs Rider', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780955369/moodspace_konten/kqlwhpllhiimp60dam65.mp4', NULL, 'moodspace_konten/kqlwhpllhiimp60dam65', '1:56', '2026-06-08 21:49:31'),
(162, 3, 'anger', 'video', 'Higuruma vs Itadori', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780955414/moodspace_konten/z7mgqwvrwcj6gy2lo5db.mp4', NULL, 'moodspace_konten/z7mgqwvrwcj6gy2lo5db', '1:01', '2026-06-08 21:50:15'),
(163, 3, 'anger', 'video', 'Mbah Frieren', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780955490/moodspace_konten/xrax2tumc81hbkk38z5s.mp4', NULL, 'moodspace_konten/xrax2tumc81hbkk38z5s', '0:15', '2026-06-08 21:51:30'),
(164, 3, 'joy', 'video', 'Love Should Be Fun', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780955811/moodspace_konten/unhzl2sqpsqipi8n0fub.mp4', NULL, 'moodspace_konten/unhzl2sqpsqipi8n0fub', '0:50', '2026-06-08 21:56:53'),
(165, 3, 'joy', 'quote', 'Kamu Sudah Berusaha', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780956017/moodspace_konten/u4aw4wing41qcjvjvwzq.jpg', NULL, 'moodspace_konten/u4aw4wing41qcjvjvwzq', NULL, '2026-06-08 22:00:18'),
(166, 3, 'sadness', 'quote', 'Tidak Ingin Memikirkan apapun lagi', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780956079/moodspace_konten/zisx7fnffhttqcfstqa8.jpg', NULL, 'moodspace_konten/zisx7fnffhttqcfstqa8', NULL, '2026-06-08 22:01:20'),
(167, 3, 'joy', 'quote', 'Menua Bersamamu', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780956142/moodspace_konten/uyjsbc9euwrbsk7d0d6s.jpg', NULL, 'moodspace_konten/uyjsbc9euwrbsk7d0d6s', NULL, '2026-06-08 22:02:22'),
(168, 3, 'joy', 'quote', 'Selalu Orang Favoritku', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780956200/moodspace_konten/cyf5ilqtnedyjcknopgp.jpg', NULL, 'moodspace_konten/cyf5ilqtnedyjcknopgp', NULL, '2026-06-08 22:03:21'),
(169, 3, 'sadness', 'quote', 'GYJ', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780956250/moodspace_konten/b0y1cwwvixsplrpgzebk.jpg', NULL, 'moodspace_konten/b0y1cwwvixsplrpgzebk', NULL, '2026-06-08 22:04:10'),
(170, 3, 'anger', 'quote', 'Bajingan Munafik', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780956288/moodspace_konten/z92bsnqh6sgcrug5loyw.jpg', NULL, 'moodspace_konten/z92bsnqh6sgcrug5loyw', NULL, '2026-06-08 22:04:49'),
(171, 3, 'sadness', 'quote', 'Maaf Tros', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780956377/moodspace_konten/iynlxrg8kyo7nhab7lvo.jpg', NULL, 'moodspace_konten/iynlxrg8kyo7nhab7lvo', NULL, '2026-06-08 22:06:18'),
(172, 3, 'joy', 'quote', 'Sukses lah by', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780956421/moodspace_konten/weihoxxl5p7ouwajewgf.jpg', NULL, 'moodspace_konten/weihoxxl5p7ouwajewgf', NULL, '2026-06-08 22:07:02'),
(173, 3, 'joy', 'quote', 'Nikah', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780956506/moodspace_konten/svldz6ctqxfollw6zxhj.jpg', NULL, 'moodspace_konten/svldz6ctqxfollw6zxhj', NULL, '2026-06-08 22:08:26'),
(174, 3, 'sadness', 'quote', 'Hidup Baru', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780956553/moodspace_konten/se1vpdozvxmzlgvdebzu.jpg', NULL, 'moodspace_konten/se1vpdozvxmzlgvdebzu', NULL, '2026-06-08 22:09:13'),
(175, 3, 'fear', 'quote', 'Mak', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780956669/moodspace_konten/l0kp0ntsnrour8psumxh.jpg', NULL, 'moodspace_konten/l0kp0ntsnrour8psumxh', NULL, '2026-06-08 22:11:09'),
(176, 3, 'disgust', 'quote', 'Gembel', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780956777/moodspace_konten/dnvnxp35nprbb7yspex0.jpg', NULL, 'moodspace_konten/dnvnxp35nprbb7yspex0', NULL, '2026-06-08 22:12:57'),
(177, 3, 'envy', 'quote', 'Makan Apa', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780956842/moodspace_konten/kkz37bdh4xrl9aqifj03.jpg', NULL, 'moodspace_konten/kkz37bdh4xrl9aqifj03', NULL, '2026-06-08 22:14:02'),
(178, 3, 'envy', 'quote', 'Gamau', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780956901/moodspace_konten/dytrbdbhecamz9i4eiz1.jpg', NULL, 'moodspace_konten/dytrbdbhecamz9i4eiz1', NULL, '2026-06-08 22:15:02'),
(179, 3, 'sadness', 'quote', 'Selamat atas Pernikahanmu', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957020/moodspace_konten/zezwh98b4qtolkvzravy.jpg', NULL, 'moodspace_konten/zezwh98b4qtolkvzravy', NULL, '2026-06-08 22:17:00'),
(180, 3, 'anger', 'quote', 'Ga ilang', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957126/moodspace_konten/ua92dpomps29ie8nypio.jpg', NULL, 'moodspace_konten/ua92dpomps29ie8nypio', NULL, '2026-06-08 22:18:47'),
(181, 3, 'disgust', 'quote', 'Maaf', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957162/moodspace_konten/q1rjyyywnunmzrtvgvlr.jpg', NULL, 'moodspace_konten/q1rjyyywnunmzrtvgvlr', NULL, '2026-06-08 22:19:22'),
(182, 3, 'anxiety', 'quote', 'Selesai in', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957239/moodspace_konten/akdxiy0refqzgwx63zyw.jpg', NULL, 'moodspace_konten/akdxiy0refqzgwx63zyw', NULL, '2026-06-08 22:20:39'),
(183, 3, 'envy', 'quote', 'pgn balik sekolah', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957272/moodspace_konten/uihdbtyzrrouskxgcgeb.jpg', NULL, 'moodspace_konten/uihdbtyzrrouskxgcgeb', NULL, '2026-06-08 22:21:12'),
(184, 3, 'embarrassment', 'quote', 'fungsi kerja', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957312/moodspace_konten/ttcwshwsfp9d3yuwswqk.jpg', NULL, 'moodspace_konten/ttcwshwsfp9d3yuwswqk', NULL, '2026-06-08 22:21:53'),
(185, 3, 'envy', 'quote', 'kecuali aku', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957344/moodspace_konten/vnpkkalqtsprqxlimy9h.jpg', NULL, 'moodspace_konten/vnpkkalqtsprqxlimy9h', NULL, '2026-06-08 22:22:25');
INSERT INTO `konten_mood` (`id`, `uploaded_by`, `mood`, `tipe`, `judul`, `sumber`, `media_id`, `file_url`, `cover_url`, `public_id`, `durasi`, `created_at`) VALUES
(186, 3, 'anger', 'quote', 'sialan', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957408/moodspace_konten/mqhuv8gfgvmgdwcugnsn.jpg', NULL, 'moodspace_konten/mqhuv8gfgvmgdwcugnsn', NULL, '2026-06-08 22:23:28'),
(187, 3, 'anxiety', 'quote', 'bisa apa', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957492/moodspace_konten/rbbtjgjfpr8r9vcuqais.jpg', NULL, 'moodspace_konten/rbbtjgjfpr8r9vcuqais', NULL, '2026-06-08 22:24:52'),
(188, 3, 'envy', 'quote', 'mereka masih bisa pulang', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957555/moodspace_konten/ijsk2ysoo5tlq7jankpt.jpg', NULL, 'moodspace_konten/ijsk2ysoo5tlq7jankpt', NULL, '2026-06-08 22:25:55'),
(189, 3, 'fear', 'quote', 'wkwk', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957595/moodspace_konten/d89kjwzijs3fotbbejj8.jpg', NULL, 'moodspace_konten/d89kjwzijs3fotbbejj8', NULL, '2026-06-08 22:26:35'),
(190, 3, 'envy', 'quote', 'aku ga punya siapa siapa', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957701/moodspace_konten/blofhlpgsiqzkunfczjp.jpg', NULL, 'moodspace_konten/blofhlpgsiqzkunfczjp', NULL, '2026-06-08 22:28:21'),
(192, 3, 'fear', 'quote', 'besok senin', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957797/moodspace_konten/a3n2z7yyistuxihssvjo.jpg', NULL, 'moodspace_konten/a3n2z7yyistuxihssvjo', NULL, '2026-06-08 22:29:57'),
(193, 3, 'anxiety', 'quote', 'tetep gabisa', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957860/moodspace_konten/scwj4bgqxehvqthk5zcl.jpg', NULL, 'moodspace_konten/scwj4bgqxehvqthk5zcl', NULL, '2026-06-08 22:31:00'),
(194, 3, 'embarrassment', 'quote', 'musuh terbesarku', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780957910/moodspace_konten/kqjhdtote0ueirwrw1hq.jpg', NULL, 'moodspace_konten/kqjhdtote0ueirwrw1hq', NULL, '2026-06-08 22:31:51'),
(195, 3, 'anxiety', 'quote', 'gapunya kelebihan', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958013/moodspace_konten/otehfeuitffykzl7shbh.jpg', NULL, 'moodspace_konten/otehfeuitffykzl7shbh', NULL, '2026-06-08 22:33:33'),
(196, 3, 'disgust', 'quote', 'wujud asli keluar', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958090/moodspace_konten/bodociynsrndik1aimcm.jpg', NULL, 'moodspace_konten/bodociynsrndik1aimcm', NULL, '2026-06-08 22:34:50'),
(197, 3, 'anger', 'quote', 'lalap aku', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958124/moodspace_konten/jientgrn1ctzgiyuzlx6.jpg', NULL, 'moodspace_konten/jientgrn1ctzgiyuzlx6', NULL, '2026-06-08 22:35:24'),
(198, 3, 'anger', 'quote', 'mulutmu', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958159/moodspace_konten/vs4k2esfetp3gp8fcpqc.jpg', NULL, 'moodspace_konten/vs4k2esfetp3gp8fcpqc', NULL, '2026-06-08 22:35:59'),
(199, 3, 'disgust', 'quote', 'manusia manusia', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958191/moodspace_konten/vdipsbzmnth5webaqoij.jpg', NULL, 'moodspace_konten/vdipsbzmnth5webaqoij', NULL, '2026-06-08 22:36:32'),
(200, 3, 'anxiety', 'quote', 'hemat brok', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958236/moodspace_konten/d423nhti0xly82wpvpoh.jpg', NULL, 'moodspace_konten/d423nhti0xly82wpvpoh', NULL, '2026-06-08 22:37:16'),
(201, 3, 'disgust', 'quote', 'salah orang lain', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958288/moodspace_konten/genpxjxjqfiyufmlbjpu.jpg', NULL, 'moodspace_konten/genpxjxjqfiyufmlbjpu', NULL, '2026-06-08 22:38:08'),
(202, 3, 'disgust', 'quote', 'lebay', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958386/moodspace_konten/rt9arpcs6tiool5v73yl.jpg', NULL, 'moodspace_konten/rt9arpcs6tiool5v73yl', NULL, '2026-06-08 22:39:46'),
(203, 3, 'disgust', 'quote', 'malas amat', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958538/moodspace_konten/kjgb30ppelckqxo6347p.jpg', NULL, 'moodspace_konten/kjgb30ppelckqxo6347p', NULL, '2026-06-08 22:42:18'),
(204, 3, 'disgust', 'quote', 'nangis aja mending', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958601/moodspace_konten/txs1rlwattsbzrbobapv.jpg', NULL, 'moodspace_konten/txs1rlwattsbzrbobapv', NULL, '2026-06-08 22:43:21'),
(205, 3, 'disgust', 'quote', 'sampe muak', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958673/moodspace_konten/twagfpacxp92dfazaaf8.jpg', NULL, 'moodspace_konten/twagfpacxp92dfazaaf8', NULL, '2026-06-08 22:44:33'),
(206, 3, 'joy', 'quote', 'siapa coba', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958766/moodspace_konten/l7fsktj1avvmgluma3zf.jpg', NULL, 'moodspace_konten/l7fsktj1avvmgluma3zf', NULL, '2026-06-08 22:46:06'),
(207, 3, 'embarrassment', 'quote', 'makan lek', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958799/moodspace_konten/xp9yxxpysotvubo2pvqk.jpg', NULL, 'moodspace_konten/xp9yxxpysotvubo2pvqk', NULL, '2026-06-08 22:46:40'),
(208, 3, 'ennui', 'quote', 'turu', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958887/moodspace_konten/h2wsna44wgycevn1uppn.jpg', NULL, 'moodspace_konten/h2wsna44wgycevn1uppn', NULL, '2026-06-08 22:48:08'),
(209, 3, 'anger', 'quote', 'pergilah ke neraka', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780958941/moodspace_konten/oqev25qlaqdpcdqmt0s9.jpg', NULL, 'moodspace_konten/oqev25qlaqdpcdqmt0s9', NULL, '2026-06-08 22:49:02'),
(210, 3, 'anxiety', 'quote', 'haruskah', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780959023/moodspace_konten/hm2kzfxhlx5l8tnq7tn7.jpg', NULL, 'moodspace_konten/hm2kzfxhlx5l8tnq7tn7', NULL, '2026-06-08 22:50:23'),
(211, 3, 'anxiety', 'quote', 'ah elah', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780959160/moodspace_konten/swagayvnidmcbg4vfuy2.jpg', NULL, 'moodspace_konten/swagayvnidmcbg4vfuy2', NULL, '2026-06-08 22:52:41'),
(212, 3, 'anger', 'quote', 'entah napa', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780959266/moodspace_konten/rd1lqa9igsor8sfqolsf.jpg', NULL, 'moodspace_konten/rd1lqa9igsor8sfqolsf', NULL, '2026-06-08 22:54:26'),
(213, 3, 'ennui', 'quote', 'tetep di rumah aja', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780959521/moodspace_konten/ax7u7innsb3ku3vcuybd.jpg', NULL, 'moodspace_konten/ax7u7innsb3ku3vcuybd', NULL, '2026-06-08 22:58:41'),
(214, 3, 'ennui', 'quote', 'omong kosong', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780959758/moodspace_konten/p13nz61wiwwnhhmo92lz.jpg', NULL, 'moodspace_konten/p13nz61wiwwnhhmo92lz', NULL, '2026-06-08 23:02:39'),
(215, 3, 'fear', 'quote', 'takut patah hati lagi', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780959826/moodspace_konten/puqg0mexglwgg6ex5nvc.jpg', NULL, 'moodspace_konten/puqg0mexglwgg6ex5nvc', NULL, '2026-06-08 23:03:47'),
(216, 3, 'fear', 'quote', 'gk jadi apa apa', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780959851/moodspace_konten/ql2e87mott6s9cpacunt.jpg', NULL, 'moodspace_konten/ql2e87mott6s9cpacunt', NULL, '2026-06-08 23:04:12'),
(217, 3, 'ennui', 'quote', 'ngantuk', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780959988/moodspace_konten/yjeh1o5pdcvrgatunxfr.jpg', NULL, 'moodspace_konten/yjeh1o5pdcvrgatunxfr', NULL, '2026-06-08 23:06:28'),
(218, 3, 'ennui', 'quote', 'rencana besok', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780960112/moodspace_konten/ee5jnrfwd791uhs1hp7x.jpg', NULL, 'moodspace_konten/ee5jnrfwd791uhs1hp7x', NULL, '2026-06-08 23:08:34');

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
(28, 5, 31, '2026-06-07 14:26:58'),
(36, 5, 22, '2026-06-07 17:32:43'),
(40, 5, 23, '2026-06-07 17:50:18'),
(46, 3, 52, '2026-06-08 11:59:06'),
(52, 5, 26, '2026-06-08 17:47:25'),
(56, 5, 20, '2026-06-08 17:55:05'),
(58, 5, 48, '2026-06-08 17:58:33'),
(59, 5, 49, '2026-06-08 17:58:34'),
(60, 5, 50, '2026-06-08 17:58:36'),
(61, 5, 51, '2026-06-08 17:58:37'),
(62, 5, 18, '2026-06-08 17:58:40'),
(63, 5, 19, '2026-06-08 17:58:41'),
(64, 5, 29, '2026-06-08 17:58:42'),
(65, 5, 30, '2026-06-08 17:58:43'),
(66, 5, 34, '2026-06-08 17:58:44'),
(67, 5, 37, '2026-06-08 17:58:45'),
(68, 5, 38, '2026-06-08 17:58:46'),
(69, 5, 47, '2026-06-08 17:58:48'),
(70, 5, 21, '2026-06-08 18:00:07'),
(71, 5, 24, '2026-06-08 18:00:16'),
(72, 5, 25, '2026-06-08 18:00:19'),
(73, 5, 70, '2026-06-08 18:01:14'),
(74, 5, 52, '2026-06-08 18:01:35'),
(75, 5, 11, '2026-06-08 18:01:50'),
(76, 5, 17, '2026-06-08 18:01:52'),
(77, 5, 33, '2026-06-08 18:02:16'),
(78, 5, 39, '2026-06-08 18:02:17');

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
(5, 3, 5, 'woi', 1, '2026-06-08 03:48:16'),
(6, 3, 6, 'woi', 0, '2026-06-08 03:54:46'),
(7, 3, 4, 'woi', 0, '2026-06-08 12:05:26'),
(8, 5, 3, 'woi juga woi', 0, '2026-06-08 18:02:31');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `konten_mood`
--
ALTER TABLE `konten_mood`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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

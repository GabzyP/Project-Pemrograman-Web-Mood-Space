-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2026 at 08:07 PM
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
(13, 7, 18, '2026-06-03 14:21:32');

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
(2, 3, 2, '2026-06-03 17:32:23');

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
(15, 3, 'joy', 'music', 'Happy Ajalah', 'GABUZY', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780482121/moodspace_konten/nnxmsxhkuh2gtzgdoiuf.mp3', 'https://res.cloudinary.com/dcb7iqteo/image/upload/v1780482124/moodspace/covers/mrgqciwgf3jzmkhdnnwd.png', 'moodspace_konten/nnxmsxhkuh2gtzgdoiuf', '4', '2026-06-03 10:22:05'),
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
(30, 3, 'sadness', 'video', 'Ngelindungin Monster', 'Gabuzy', '', 'https://res.cloudinary.com/dcb7iqteo/video/upload/v1780504123/moodspace_konten/j2glohqnch6zcltgeita.mp4', NULL, 'moodspace_konten/j2glohqnch6zcltgeita', '0:35', '2026-06-03 16:28:43');

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
(18, 3, 22, '2026-06-03 17:38:42');

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
(2, 'rahma@gmail.com', '$2y$10$R.qzmkBtpEAxFtCJ82XikunJiZmTjuYc.t30tlAYOzhkXhyWmS0Nm', 'creator', 'rahma', 'rahma', '', 'assets/uploads/avatar_2_1780487114.png', 0, 1, 0),
(3, 'gabuzy@gmail.com', '$2y$10$Jtp6olwz4pnKHWqh3dC0hueTr4DjttrSIFASv/4mbhs2yvc4FTYsm', 'creator', 'gabuzy', 'GABUZY', 'koncet', 'assets/uploads/avatar_3_1780466281.webp', 1, 0, 0),
(4, 'kezia@gmail.com', '$2y$10$PJn7y8.UxGgNgySa7eZr0.y0CFQ28w0wTJZJhXtnZhRy82/MAMkI.', 'creator', 'kezia', 'kezia', '', 'assets/uploads/avatar_4_1780487158.png', 0, 0, 0),
(5, 'adeptri@gmail.com', '$2y$10$DTHXc2lnHDEQ.X9x40Aq4Obxbm1hLmkNLMOTXx8GeBBVqKEgj1TUO', 'creator', 'adeptri', 'adeptri', '', 'assets/uploads/avatar_5_1780487208.jpeg', 0, 0, 0),
(6, 'angel@gmail.com', '$2y$10$f9t790HWkTbnZoDuFZbKmOIOU5vX6wdVLdhQfG/mp6zYBAbkjamRm', 'creator', 'angel', 'angel', '', 'assets/uploads/avatar_6_1780487262.jpeg', 0, 1, 0),
(7, 'adeptri@yahoo.com', '$2y$10$5ZbtMi.fo/LCmOqoiwwkK.p24NoTbqxxILBkOSHDMLy6cD.U0RjyK', 'user', 'adeptri51', 'adeptri51', NULL, NULL, 1, 0, 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `konten_mood`
--
ALTER TABLE `konten_mood`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

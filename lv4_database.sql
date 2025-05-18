-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2025 at 06:19 PM
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
-- Database: `lv4_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `liked_movies`
--

CREATE TABLE `liked_movies` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `movie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `liked_movies`
--

INSERT INTO `liked_movies` (`id`, `username`, `movie_id`) VALUES
(10, 'student', 1),
(11, 'student', 10);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `genres` varchar(255) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `directors` varchar(255) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `total_votes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `year`, `genres`, `duration`, `rating`, `directors`, `country`, `total_votes`) VALUES
(1, 'Inception', 2010, 'Action;Sci-Fi', 148, 8.8, 'Christopher Nolan', 'United States', 200),
(2, 'The Matrix', 1999, 'Action;Sci-Fi', 136, 8.7, 'Lana Wachowski;Lilly Wachowski', 'United States', 300),
(3, 'The Godfather', 1972, 'Crime;Drama', 175, 9.2, 'Francis Ford Coppola', 'United States', 400),
(4, 'Interstellar', 2014, 'Sci-Fi;Drama', 169, 8.6, 'Christopher Nolan', 'United States', 500),
(5, 'The Dark Knight', 2008, 'Action;Crime;Drama', 152, 9.0, 'Christopher Nolan', 'United States', 600),
(6, 'test', 2000, 'Action', 140, 9.0, NULL, 'Croatia', NULL),
(7, 'test', 2000, 'Action', 140, 9.0, NULL, 'Croatia', NULL),
(8, 'test', 2000, 'Crime', 100, 4.0, NULL, 'Croatia', NULL),
(9, 'test2', 2000, 'Action', 133, 3.0, '', 'Croatia', 0),
(10, 'lalal', 2000, 'Action', 111, 4.0, '', 'Croatia', 0),
(11, 'NOVI FILM', 2000, 'Action', 150, 3.0, '', 'Croatia', 0),
(12, 'lalalalal test', 2010, 'Action', 120, 8.0, '', 'Croatia', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ocjene_slika`
--

CREATE TABLE `ocjene_slika` (
  `id` int(11) NOT NULL,
  `id_korisnik` varchar(100) NOT NULL,
  `id_slika` varchar(100) NOT NULL,
  `ocjena` int(11) NOT NULL,
  `vrijeme_ocjene` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ocjene_slika`
--

INSERT INTO `ocjene_slika` (`id`, `id_korisnik`, `id_slika`, `ocjena`, `vrijeme_ocjene`) VALUES
(2, 'student', 'sample-1.svg', 3, '2025-05-12 15:33:41'),
(3, 'student2', 'sample-1.svg', 5, '2025-05-12 15:28:36'),
(4, 'student2', 'sample-4.svg', 2, '2025-05-12 15:28:41'),
(5, 'student2', 'sample-2.svg', 4, '2025-05-12 15:28:47'),
(6, 'student', 'sample-2.svg', 2, '2025-05-12 15:34:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'student', '$2y$10$3W/iahmFKJD4.kg2A7scGuaivF8qg8ibGVwwY0O5f1DIU5a5NNacm'),
(2, 'student2', '$2y$10$JS3pe5Dq.y5RzE.Z456Sieo68BSQuEZ8LQIbqAPbaUsBi0Ge1nZdu'),
(3, 'student3', '$2y$10$2HwNTsetEw0O1z9HgATw8eUXYdh3hhwgBrYY90DJjyKIQUCV.1aOS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `liked_movies`
--
ALTER TABLE `liked_movies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_movie` (`movie_id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocjene_slika`
--
ALTER TABLE `ocjene_slika`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_korisnik` (`id_korisnik`,`id_slika`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `liked_movies`
--
ALTER TABLE `liked_movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ocjene_slika`
--
ALTER TABLE `ocjene_slika`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `liked_movies`
--
ALTER TABLE `liked_movies`
  ADD CONSTRAINT `fk_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2019 at 03:32 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `karlad_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books_1`
--

CREATE TABLE `books_1` (
  `id` int(6) NOT NULL,
  `object_designs_id` int(6) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `author` varchar(250) DEFAULT NULL,
  `genre` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `books_1`
--

INSERT INTO `books_1` (`id`, `object_designs_id`, `title`, `author`, `genre`) VALUES
(1, 36, 'A Game of Thrones', 'George RR Martin', 'Fiction'),
(2, 36, 'A Clash of Kings', 'George RR Martin', 'Fiction');

-- --------------------------------------------------------

--
-- Table structure for table `object_designs`
--

CREATE TABLE `object_designs` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `privacy` text,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `object_designs`
--

INSERT INTO `object_designs` (`id`, `name`, `privacy`, `user_id`) VALUES
(36, 'books', 'public', 1),
(38, 'pokemon', 'public', 16),
(39, 'to_do_list', 'private', 1),
(40, 'people', 'public', 16);

-- --------------------------------------------------------

--
-- Table structure for table `people_16`
--

CREATE TABLE `people_16` (
  `id` int(6) NOT NULL,
  `object_designs_id` int(6) DEFAULT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `occupation` varchar(250) NOT NULL,
  `relationship` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `people_16`
--

INSERT INTO `people_16` (`id`, `object_designs_id`, `first_name`, `last_name`, `occupation`, `relationship`) VALUES
(1, 40, 'Kirsten', 'Dampilag', 'Student', 'Sister'),
(2, 40, 'Kent', 'Dampilag', 'Student', 'Brother'),
(3, 40, 'Sansa', 'Stark', 'Lady of Winterfell', 'None'),
(4, 40, 'Jon', 'Snow', 'King of the North', 'None');

-- --------------------------------------------------------

--
-- Table structure for table `pokemon_16`
--

CREATE TABLE `pokemon_16` (
  `id` int(6) NOT NULL,
  `object_designs_id` int(6) DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `type` varchar(250) NOT NULL,
  `hp` varchar(250) NOT NULL,
  `attack` varchar(250) NOT NULL,
  `defense` varchar(250) NOT NULL,
  `sp_attack` varchar(250) NOT NULL,
  `sp_defense` varchar(250) NOT NULL,
  `speed` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pokemon_16`
--

INSERT INTO `pokemon_16` (`id`, `object_designs_id`, `name`, `type`, `hp`, `attack`, `defense`, `sp_attack`, `sp_defense`, `speed`) VALUES
(1, 38, 'Bulbasaur', 'Grass, Poison', '45', '49', '49', '65', '65', '45'),
(2, 38, 'Charmander', 'Fire', '39', '52', '43', '60', '50', '65');

-- --------------------------------------------------------

--
-- Table structure for table `to_do_list_1`
--

CREATE TABLE `to_do_list_1` (
  `id` int(6) NOT NULL,
  `object_designs_id` int(6) DEFAULT NULL,
  `task` varchar(250) NOT NULL,
  `description` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `to_do_list_1`
--

INSERT INTO `to_do_list_1` (`id`, `object_designs_id`, `task`, `description`) VALUES
(1, 39, 'Push new project code to github', 'Make a proper readme.md');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(10) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'karladam', '$2y$10$fJLLirnV4gBA4CvvXB.WPO/D4cRqWKY35npR7/I2MuacWP30mP7z2', '2019-02-27 01:31:46'),
(16, 'spongebob', '$2y$10$Y.IUHvKazPN0.gGoQR6ioeUmA/SDckIrjt8E5zDXhAOryUCaN.BuS', '2019-03-09 02:05:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books_1`
--
ALTER TABLE `books_1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `object_designs`
--
ALTER TABLE `object_designs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `people_16`
--
ALTER TABLE `people_16`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pokemon_16`
--
ALTER TABLE `pokemon_16`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `to_do_list_1`
--
ALTER TABLE `to_do_list_1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books_1`
--
ALTER TABLE `books_1`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `object_designs`
--
ALTER TABLE `object_designs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `people_16`
--
ALTER TABLE `people_16`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `pokemon_16`
--
ALTER TABLE `pokemon_16`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `to_do_list_1`
--
ALTER TABLE `to_do_list_1`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2024 at 06:13 PM
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
-- Database: `webdev_lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `interactive_video_answer`
--

CREATE TABLE `interactive_video_answer` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_score` int(11) NOT NULL DEFAULT 0,
  `user_answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interactive_video_assessment`
--

CREATE TABLE `interactive_video_assessment` (
  `id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `_timestamp` time NOT NULL,
  `submitted` tinyint(4) NOT NULL DEFAULT 0,
  `score` int(11) NOT NULL DEFAULT 0,
  `grade` decimal(10,0) NOT NULL DEFAULT 0,
  `response_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interactive_video_choice`
--

CREATE TABLE `interactive_video_choice` (
  `id` int(11) NOT NULL,
  `choice_text` varchar(255) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interactive_video_question`
--

CREATE TABLE `interactive_video_question` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `question_title` varchar(255) NOT NULL,
  `question_type` tinyint(4) NOT NULL DEFAULT 1,
  `question_score` int(11) NOT NULL,
  `correct_answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interactive_video_video`
--

CREATE TABLE `interactive_video_video` (
  `id` int(11) NOT NULL,
  `video_title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `interactive_video_answer`
--
ALTER TABLE `interactive_video_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `interactive_video_assessment`
--
ALTER TABLE `interactive_video_assessment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indexes for table `interactive_video_choice`
--
ALTER TABLE `interactive_video_choice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `interactive_video_question`
--
ALTER TABLE `interactive_video_question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assessment_id` (`assessment_id`);

--
-- Indexes for table `interactive_video_video`
--
ALTER TABLE `interactive_video_video`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `interactive_video_answer`
--
ALTER TABLE `interactive_video_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `interactive_video_assessment`
--
ALTER TABLE `interactive_video_assessment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `interactive_video_choice`
--
ALTER TABLE `interactive_video_choice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `interactive_video_question`
--
ALTER TABLE `interactive_video_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `interactive_video_video`
--
ALTER TABLE `interactive_video_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `interactive_video_answer`
--
ALTER TABLE `interactive_video_answer`
  ADD CONSTRAINT `interactive_video_answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `interactive_video_question` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `interactive_video_assessment`
--
ALTER TABLE `interactive_video_assessment`
  ADD CONSTRAINT `interactive_video_assessment_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `interactive_video_video` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `interactive_video_choice`
--
ALTER TABLE `interactive_video_choice`
  ADD CONSTRAINT `interactive_video_choice_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `interactive_video_question` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `interactive_video_question`
--
ALTER TABLE `interactive_video_question`
  ADD CONSTRAINT `interactive_video_question_ibfk_1` FOREIGN KEY (`assessment_id`) REFERENCES `interactive_video_assessment` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

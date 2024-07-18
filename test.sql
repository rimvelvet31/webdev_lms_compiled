-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2024 at 11:55 AM
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
-- Database: `web_lms`
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

--
-- Dumping data for table `interactive_video_answer`
--

INSERT INTO `interactive_video_answer` (`id`, `question_id`, `user_score`, `user_answer`) VALUES
(2, 3, 0, '');

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

--
-- Dumping data for table `interactive_video_assessment`
--

INSERT INTO `interactive_video_assessment` (`id`, `video_id`, `_timestamp`, `submitted`, `score`, `grade`, `response_date`) VALUES
(1, 1, '00:30:00', 0, 0, 0, '2024-07-15 17:16:04'),
(2, 1, '00:35:00', 0, 0, 0, '2024-07-15 17:16:04'),
(3, 2, '00:00:05', 0, 0, 0, '2024-07-15 18:57:24'),
(4, 2, '00:00:10', 0, 0, 0, '2024-07-15 18:57:24');

-- --------------------------------------------------------

--
-- Table structure for table `interactive_video_choice`
--

CREATE TABLE `interactive_video_choice` (
  `id` int(11) NOT NULL,
  `choice_text` varchar(255) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interactive_video_choice`
--

INSERT INTO `interactive_video_choice` (`id`, `choice_text`, `question_id`) VALUES
(1, 'a mammal', 1),
(2, 'a fish', 1),
(3, 'animal with fur', 2),
(4, 'animal with scales', 2),
(7, '1', 6),
(8, '2', 6),
(9, '3', 6),
(10, '4', 6),
(11, 'Earth', 8),
(12, 'Venus', 8),
(13, 'Mercury', 8),
(41, 'awdawdawd', 28),
(42, '5', 29),
(43, '3', 29),
(44, 'd', 29),
(45, 'a', 30),
(46, 'b', 30),
(47, 'c', 30);

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

--
-- Dumping data for table `interactive_video_question`
--

INSERT INTO `interactive_video_question` (`id`, `assessment_id`, `question_title`, `question_type`, `question_score`, `correct_answer`) VALUES
(1, 1, 'What is a dog?', 1, 5, 'a mammal'),
(2, 1, 'What is a cat?', 1, 5, 'animal with fur'),
(3, 1, 'What is the largest planet?', 2, 10, 'Jupiter'),
(6, 3, 'What is 1 + 1?', 1, 5, '2'),
(7, 3, 'What is 5 * 2?', 2, 10, '10'),
(8, 4, 'What is the first planet in the solar system?', 1, 10, 'Mercury'),
(9, 4, 'What planet is the most distant to the sun?', 2, 10, 'Neptune'),
(28, 2, 'How many legs does a dog have?', 1, 10, 'awdawdawd'),
(29, 2, 'How many sides does a triangle have?', 1, 5, 'd'),
(30, 1, 'awdadw', 1, 12, 'b'),
(31, 1, 'awdawd', 2, 12, 'adawda'),
(32, 1, 'wdawdadw', 2, 1231, 'adawda');

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
-- Dumping data for table `interactive_video_video`
--

INSERT INTO `interactive_video_video` (`id`, `video_title`, `description`, `video_path`, `user_id`, `date_added`) VALUES
(1, 'Lecture 1: Test', 'edited', 'videos/1570752538video-presentation.mp4', 12345, '2024-07-17 11:46:41'),
(2, 'Video 2', NULL, NULL, NULL, NULL),
(3, 'testing', 'describing', 'videos/1570752538video-presentation.mp4', 12345, '2024-07-17 16:29:42'),
(4, 'test 3', 'tasdf fasdfadf sda dafd', 'videos/2909914-uhd_3840_2024_24fps.mp4', 12345, '2024-07-18 09:12:47'),
(6, 'we', 'fa dfd sfa', 'videos/1570752538video-presentation.mp4', 12345, '2024-07-18 17:13:53');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `interactive_video_assessment`
--
ALTER TABLE `interactive_video_assessment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `interactive_video_choice`
--
ALTER TABLE `interactive_video_choice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `interactive_video_question`
--
ALTER TABLE `interactive_video_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `interactive_video_video`
--
ALTER TABLE `interactive_video_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
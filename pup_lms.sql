-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2024 at 07:25 PM
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
-- Database: `pup_lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessment`
--

CREATE TABLE `assessment` (
  `assessment_ID` varchar(10) NOT NULL,
  `date_Created` datetime DEFAULT NULL,
  `creator_ID` varchar(12) DEFAULT NULL,
  `assessment_Name` varchar(255) DEFAULT NULL,
  `subject_Code` varchar(10) DEFAULT NULL,
  `assessment_Type` char(1) DEFAULT NULL,
  `time_Limit` varchar(5) DEFAULT NULL,
  `no_Of_Items` varchar(3) DEFAULT NULL,
  `closing_Date` datetime DEFAULT NULL,
  `allowed_Attempts` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cohort`
--

CREATE TABLE `cohort` (
  `cohort_ID` varchar(5) NOT NULL,
  `creator_ID` varchar(12) DEFAULT NULL,
  `cohort_Name` varchar(50) DEFAULT NULL,
  `cohort_Size` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cohort`
--

INSERT INTO `cohort` (`cohort_ID`, `creator_ID`, `cohort_Name`, `cohort_Size`) VALUES
('PUPMN', 'FA0123561212', 'PUP Sta. Mesa', '9999'),
('PUPSJ', 'FA0123561212', 'PUP San Juan', '9999');

-- --------------------------------------------------------

--
-- Table structure for table `cohort_archive`
--

CREATE TABLE `cohort_archive` (
  `cohort_ID` varchar(5) NOT NULL,
  `creator_ID` varchar(12) DEFAULT NULL,
  `cohort_Name` varchar(50) DEFAULT NULL,
  `cohort_Size` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `college`
--

CREATE TABLE `college` (
  `college_ID` varchar(10) NOT NULL,
  `college_Name` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `college`
--

INSERT INTO `college` (`college_ID`, `college_Name`, `description`) VALUES
('CCIS', 'College of Computer Science', 'College of Computer Science');

-- --------------------------------------------------------

--
-- Table structure for table `college_archive`
--

CREATE TABLE `college_archive` (
  `college_ID` varchar(10) NOT NULL,
  `college_Name` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_ID` varchar(15) NOT NULL,
  `course_Name` varchar(50) NOT NULL,
  `user_ID` varchar(12) DEFAULT NULL,
  `cohort_ID` varchar(5) DEFAULT NULL,
  `course_Description` varchar(50) DEFAULT NULL,
  `college_ID` varchar(10) DEFAULT NULL,
  `no_Of_Years` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_ID`, `course_Name`, `user_ID`, `cohort_ID`, `course_Description`, `college_ID`, `no_Of_Years`) VALUES
('BSCS', '', 'FA0123561212', 'PUPSJ', 'The program is BS Computer Science', 'CCIS', '4');

-- --------------------------------------------------------

--
-- Table structure for table `course_archive`
--

CREATE TABLE `course_archive` (
  `course_ID` varchar(15) NOT NULL,
  `creator_ID` varchar(12) DEFAULT NULL,
  `cohort_ID` varchar(5) DEFAULT NULL,
  `course_Description` varchar(50) DEFAULT NULL,
  `college_ID` varchar(10) DEFAULT NULL,
  `no_Of_Years` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_enrolled`
--

CREATE TABLE `course_enrolled` (
  `user_ID` varchar(12) NOT NULL,
  `cohort_ID` varchar(5) NOT NULL,
  `course_ID` varchar(15) NOT NULL,
  `ay` varchar(4) DEFAULT NULL,
  `semester` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_enrolled`
--

INSERT INTO `course_enrolled` (`user_ID`, `cohort_ID`, `course_ID`, `ay`, `semester`) VALUES
('202100828mn0', 'PUPMN', 'BSCS', '2324', '1'),
('202110273mn0', 'PUPMN', 'BSCS', '2324', '1'),
('202110755mn0', 'PUPMN', 'BSCS', '2324', '1'),
('202110792mn0', 'PUPMN', 'BSCS', '2324', '1');

-- --------------------------------------------------------

--
-- Table structure for table `course_enrolled_archive`
--

CREATE TABLE `course_enrolled_archive` (
  `user_ID` varchar(12) NOT NULL,
  `cohort_ID` varchar(5) NOT NULL,
  `course_ID` varchar(15) NOT NULL,
  `ay` varchar(4) NOT NULL,
  `semester` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_ID` varchar(10) NOT NULL,
  `department_Name` varchar(50) DEFAULT NULL,
  `department_Description` varchar(100) DEFAULT NULL,
  `college_ID` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `examination_bank`
--

CREATE TABLE `examination_bank` (
  `assessment_ID` varchar(10) NOT NULL,
  `question_ID` int(11) NOT NULL,
  `question_No` int(11) DEFAULT NULL,
  `question` varchar(200) DEFAULT NULL,
  `points` float DEFAULT NULL,
  `question_Type` char(1) DEFAULT NULL,
  `choice1` varchar(200) DEFAULT NULL,
  `choice2` varchar(200) DEFAULT NULL,
  `choice3` varchar(200) DEFAULT NULL,
  `choice4` varchar(200) DEFAULT NULL,
  `boolean` char(1) DEFAULT NULL,
  `fill_Blank` varchar(50) DEFAULT NULL,
  `match1` varchar(50) DEFAULT NULL,
  `match2` varchar(50) DEFAULT NULL,
  `match3` varchar(50) DEFAULT NULL,
  `match4` varchar(50) DEFAULT NULL,
  `match5` varchar(50) DEFAULT NULL,
  `match6` varchar(50) DEFAULT NULL,
  `match7` varchar(50) DEFAULT NULL,
  `match8` varchar(50) DEFAULT NULL,
  `match9` varchar(50) DEFAULT NULL,
  `match10` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_answer`
--

CREATE TABLE `exam_answer` (
  `assessment_ID` varchar(10) NOT NULL,
  `question_ID` int(11) NOT NULL,
  `answer` varchar(200) DEFAULT NULL,
  `m_Ans1` char(2) DEFAULT NULL,
  `m_Ans2` char(2) DEFAULT NULL,
  `m_Ans3` char(2) DEFAULT NULL,
  `m_Ans4` char(2) DEFAULT NULL,
  `m_Ans5` char(2) DEFAULT NULL,
  `m_Ans6` char(2) DEFAULT NULL,
  `m_Ans7` char(2) DEFAULT NULL,
  `m_Ans8` char(2) DEFAULT NULL,
  `m_Ans9` char(2) DEFAULT NULL,
  `m_Ans10` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interactive_video`
--

CREATE TABLE `interactive_video` (
  `video_ID` varchar(6) NOT NULL,
  `user_ID` varchar(12) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `filename` varchar(50) DEFAULT NULL,
  `path` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(15, 60, 0, 'a'),
(16, 71, 5, 'a'),
(17, 72, 5, 'b');

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
(16, 14, '00:00:01', 1, 0, 0, '2024-07-21 01:01:51'),
(19, 14, '00:00:05', 1, 10, 100, '2024-07-21 01:03:03');

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
(97, 'a', 60),
(98, 'b', 60),
(99, 'c', 60),
(110, 'a', 71),
(111, 'b', 71),
(112, 'a', 72),
(113, 'b', 72);

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
(60, 16, 'sample question', 1, 10, 'b'),
(71, 19, 'sample question 2', 1, 5, 'a'),
(72, 19, 'sample question 3', 1, 5, 'b');

-- --------------------------------------------------------

--
-- Table structure for table `interactive_video_video`
--

CREATE TABLE `interactive_video_video` (
  `id` int(11) NOT NULL,
  `user_id` varchar(12) NOT NULL,
  `course_id` varchar(15) NOT NULL,
  `video_title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interactive_video_video`
--

INSERT INTO `interactive_video_video` (`id`, `user_id`, `course_id`, `video_title`, `description`, `video_path`, `date_added`) VALUES
(14, '202010839mn0', 'BSCS', 'Sample Video', 'sample video description (edited)', 'videos/sample_vid.mp4', '2024-07-21 00:53:51');

-- --------------------------------------------------------

--
-- Table structure for table `password_maintenance`
--

CREATE TABLE `password_maintenance` (
  `user_ID` varchar(12) NOT NULL,
  `lockout_Time` timestamp NULL DEFAULT NULL,
  `current_Password` varchar(50) DEFAULT NULL,
  `previous_Password` varchar(50) DEFAULT NULL,
  `date_Created` date DEFAULT NULL,
  `expiry_Days` smallint(6) DEFAULT NULL,
  `login_Attempt` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_ID` varchar(255) NOT NULL,
  `reset_Token` varchar(255) NOT NULL,
  `reset_Expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_submission`
--

CREATE TABLE `student_submission` (
  `submission_ID` varchar(18) NOT NULL,
  `user_ID` varchar(12) DEFAULT NULL,
  `subject_ID` varchar(10) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `remarks` varchar(10) DEFAULT NULL,
  `note` varchar(100) DEFAULT NULL,
  `file_path` varchar(200) DEFAULT NULL,
  `requirement_Code` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_ID` varchar(10) NOT NULL,
  `user_ID` varchar(12) DEFAULT NULL,
  `subject_Name` varchar(50) DEFAULT NULL,
  `subject_Description` varchar(100) DEFAULT NULL,
  `cohort_ID` varchar(5) NOT NULL,
  `course_ID` varchar(15) DEFAULT NULL,
  `ay` varchar(4) DEFAULT NULL,
  `semester` char(1) DEFAULT NULL,
  `year` char(1) DEFAULT NULL,
  `section` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_ID`, `user_ID`, `subject_Name`, `subject_Description`, `cohort_ID`, `course_ID`, `ay`, `semester`, `year`, `section`) VALUES
('COMP10173', 'FA0123561212', 'Web Development', 'Web Development', 'PUPSJ', 'BSCS', '2324', '2', '3', '5'),
('COSC 40013', 'FA0123561212', 'Thesis Writing 1', 'Prerequisite to Thesis Writing 2', 'PUPSJ', 'BSCS', '2425', '2', '3', '4'),
('COSC12300', 'FA0123561212', 'Software Engineering', '', 'PUPQC', 'BSCS', '2425', '1', '3', '4');

-- --------------------------------------------------------

--
-- Table structure for table `subject_enrolled`
--

CREATE TABLE `subject_enrolled` (
  `user_ID` varchar(12) NOT NULL,
  `subject_ID` varchar(10) NOT NULL,
  `course_ID` varchar(15) NOT NULL,
  `cohort_ID` varchar(5) NOT NULL,
  `ay` varchar(4) DEFAULT NULL,
  `semester` char(1) DEFAULT NULL,
  `year` varchar(1) DEFAULT NULL,
  `section` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_enrolled`
--

INSERT INTO `subject_enrolled` (`user_ID`, `subject_ID`, `course_ID`, `cohort_ID`, `ay`, `semester`, `year`, `section`) VALUES
('202100828mn0', 'COMP10173', 'BSCS', 'PUPSJ', '2324', '2', '3', '5');

-- --------------------------------------------------------

--
-- Table structure for table `subject_enrolled_archive`
--

CREATE TABLE `subject_enrolled_archive` (
  `user_ID` varchar(12) NOT NULL,
  `subject_ID` varchar(10) NOT NULL,
  `course_ID` varchar(15) NOT NULL,
  `cohort_ID` varchar(5) NOT NULL,
  `ay` varchar(4) NOT NULL,
  `semester` char(1) NOT NULL,
  `year` char(1) NOT NULL,
  `section` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submission_requirement`
--

CREATE TABLE `submission_requirement` (
  `requirement_Code` varchar(6) NOT NULL,
  `subject_ID` varchar(10) DEFAULT NULL,
  `date_Start` date DEFAULT NULL,
  `time_Start` time DEFAULT NULL,
  `date_End` date DEFAULT NULL,
  `time_End` time DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `upload_lecture`
--

CREATE TABLE `upload_lecture` (
  `lecture_ID` varchar(10) NOT NULL,
  `subject_ID` varchar(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `file_Path` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_access`
--

CREATE TABLE `user_access` (
  `user_ID` varchar(12) NOT NULL,
  `user_Password` varchar(60) DEFAULT NULL,
  `last_Access` date DEFAULT NULL,
  `time_Access` time DEFAULT NULL,
  `first_Access` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_access`
--

INSERT INTO `user_access` (`user_ID`, `user_Password`, `last_Access`, `time_Access`, `first_Access`) VALUES
('202010839mn0', '$2y$10$FjUYSRh8G423/AAIQ.hstu5XkWr8oyTZ9tiHymTGxRJYfS1ZeK5nq', '2024-07-17', '10:42:27', '2024-07-17'),
('202110261mn0', '$2y$10$.Jr0VGFy1x6yAHza5kQVAelr//ApzSmADFVozCaM8nhKvu6ZIuQA.', '2024-07-17', '09:08:06', '2024-07-17'),
('202110839mn0', '$2y$10$KTdM64xSRc5tV.1NjRj8duYLBeE1SiMv7T6oD586Ep03m8JXiBgEu', '2024-07-17', '10:43:20', '2024-07-17'),
('202310839mn0', '$2y$10$IdKM0RhpcelvC/HrO6xCAubYRPXw9eS74M/gGmeN5ZBgYEwGC.e4G', '2024-07-17', '11:06:02', '2024-07-17');

-- --------------------------------------------------------

--
-- Table structure for table `user_examination`
--

CREATE TABLE `user_examination` (
  `user_ID` varchar(12) NOT NULL,
  `assessment_ID` varchar(10) NOT NULL,
  `date_Start` date DEFAULT NULL,
  `time_Start` time DEFAULT NULL,
  `date_End` date DEFAULT NULL,
  `time_End` time DEFAULT NULL,
  `score` char(3) DEFAULT NULL,
  `grade` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_exam_report`
--

CREATE TABLE `user_exam_report` (
  `user_ID` varchar(12) NOT NULL,
  `assessment_ID` varchar(10) NOT NULL,
  `attempt_Number` int(11) DEFAULT NULL,
  `score` char(3) DEFAULT NULL,
  `grade` float DEFAULT NULL,
  `subject_Code` varchar(10) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_information`
--

CREATE TABLE `user_information` (
  `user_ID` varchar(12) NOT NULL,
  `last_Name` varchar(50) DEFAULT NULL,
  `first_Name` varchar(75) DEFAULT NULL,
  `middle_Name` varchar(50) DEFAULT NULL,
  `date_Of_Birth` date DEFAULT NULL,
  `email_Address` varchar(75) DEFAULT NULL,
  `mobile_Number` varchar(13) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `region` varchar(20) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `zip_Code` varchar(5) DEFAULT NULL,
  `date_Created` date DEFAULT NULL,
  `account_Status` char(1) DEFAULT NULL,
  `time_Created` time DEFAULT NULL,
  `id_Number` char(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_information`
--

INSERT INTO `user_information` (`user_ID`, `last_Name`, `first_Name`, `middle_Name`, `date_Of_Birth`, `email_Address`, `mobile_Number`, `country`, `city`, `region`, `province`, `zip_Code`, `date_Created`, `account_Status`, `time_Created`, `id_Number`) VALUES
('202010839mn0', 'Reyes', 'Jose', 'Martinez', '2024-07-18', 'bojenriquez@gmail.com', '09728194222', 'Philippines', 'IMUS CITY', 'REGION IV-A', 'CAVITE', '4103', '2024-07-17', '1', '04:37:49', '2020108'),
('202100828mn0', 'Nunez', 'Nigel', 'Corpus', '2001-01-01', 'nigel@gmail.com', '09234765876', 'Philippines', 'Manila', 'NCR', 'Manila', '4572', '2024-06-28', '1', '23:12:06', '2021008'),
('202110239mn0', 'Almerol', 'Alvin', 'Lamit', '2001-01-01', 'almerolalvin@gmail.com', '09267361961', 'Philippines', 'Quezon City', 'NCR', 'Manila', '1030', '2024-06-28', '1', '22:49:48', '2021102'),
('202110261mn0', 'de la Cruz', 'Juan', 'Santos', '2024-07-10', 'juandelacruz@gmail.com', '09728194207', 'Philippines', 'BUENAVISTA', 'REGION VI', 'GUIMARAS', '1234', '2024-07-16', '1', '20:34:57', '2021102'),
('202110266mn0', 'Melad', 'Nicole', 'Catabay', '2001-01-01', 'meladnicole@gmail.com', '09234765878', 'Philippines', 'Manila', 'NCR', 'Manila', '9072', '2024-06-28', '1', '23:11:02', '2021102'),
('202110273mn0', 'Pedralba', 'Rochelle', 'Bionat', '2001-01-01', 'pedralabarochelle@gmail.com', '09231765876', 'Philippines', 'Manila', 'NCR', 'Manila', '4570', '2024-06-28', '1', '23:13:03', '2021102'),
('202110413mn0', 'Jimenez', 'Jewelle', 'Rivera', '2001-01-01', 'jewelle@gmail.com', '09123987656', 'Philippines', 'Manila', 'NCR', 'Manila', '1204', '2024-06-28', '1', '23:04:07', '2021104'),
('202110424mn0', 'Macapanas', 'Anthonette', 'Villafuerte', '2001-01-01', 'anthonette@gmail.com', '09234765871', 'Philippines', 'Manila', 'NCR', 'Manila', '9072', '2024-06-28', '1', '23:09:37', '2021104'),
('202110462mn0', 'Pena', 'Charissa', 'Bartolome', '2001-01-01', 'charissa@gmail.com', '09231765376', 'Philippines', 'Manila', 'NCR', 'Manila', '4570', '2024-06-28', '1', '23:14:03', '2021104'),
('202110478mn0', 'Soriano', 'Jericho', 'Tagamolila', '2001-01-01', 'jerichosoriano@gmail.com', '09231765378', 'Philippines', 'Manila', 'NCR', 'Manila', '4570', '2024-06-28', '1', '23:16:18', '2021104'),
('202110483mn0', 'Taripe', 'Leejani', 'Rellora', '2001-01-01', 'leejani@gmail.com', '09231765372', 'Philippines', 'Manila', 'NCR', 'Manila', '4571', '2024-06-28', '1', '23:17:50', '2021104'),
('202110505mn0', 'Turado', 'Jasper', 'Relacion', '2001-01-01', 'jturado@gmail.com', '09201765370', 'Philippines', 'Manila', 'NCR', 'Manila', '4071', '2024-06-28', '1', '23:19:36', '2021105'),
('202110541mn0', 'Alba', 'Khent', 'Alonzo', '2001-01-01', 'khentalba1@gmail.com', '09267361967', 'Philippines', 'City', 'Region 4A', 'Cavite', '1000', '2024-06-28', '1', '22:47:23', '2021105'),
('202110752mn0', 'Aquino', 'Mark Joseph', 'Jimenez', '2001-01-01', 'aquinomark@gmail.com', '09267361962', 'Philippines', 'Quezon City', 'NCR', 'Manila', '1031', '2024-06-28', '1', '22:50:52', '2021107'),
('202110755mn0', 'Bautista', 'Pauline', 'Panganiban', '2001-01-01', 'paulinebautista@gmail.com', '09123859987', 'Philippines', 'Manila', 'NCR', 'Manila', '1234', '2024-06-28', '1', '22:52:53', '2021107'),
('202110763mn0', 'Dela Cruz', 'Alloysius', 'Gajol', '2001-01-01', 'alloynike@gmail.com', '09987654321', 'Philippines', 'Manila', 'NCR', 'Manila', '1234', '2024-06-28', '1', '22:58:48', '2021107'),
('202110792mn0', 'Tolentino', 'Daniela', 'Ignalan', '2001-01-01', 'daniela@gmail.com', '09201765372', 'Philippines', 'Manila', 'NCR', 'Manila', '4071', '2024-06-28', '1', '23:19:02', '2021107'),
('202110815mn0', 'Bolo', 'Wenzel', 'Tolarba', '2001-01-01', 'bolowenzel@gmail.com', '09123859980', 'Philippines', 'Manila', 'NCR', 'Manila', '1234', '2024-06-28', '1', '22:53:50', '2021108'),
('202110824mn0', 'Calapati', 'Christine', 'Galvez', '2001-01-01', 'cjcalapati@gmail.com', '09123859982', 'Philippines', 'Manila', 'NCR', 'Manila', '1234', '2024-06-28', '1', '22:54:44', '2021108'),
('202110839mn0', 'Enriquez', 'Isaiah Job', 'Cuenca', '2003-06-30', 'jobiskits@gmail.com', '09728194201', 'Philippines', 'IMUS CITY', 'REGION IV-A', 'CAVITE', '4103', '2024-07-16', '1', '20:29:22', '2021108'),
('202110870mn0', 'Maminta', 'Carlos', 'Gatlabayan', '2001-01-01', 'carlosmaminta@gmail.com', '09234765879', 'Philippines', 'Manila', 'NCR', 'Manila', '9072', '2024-06-28', '1', '23:10:35', '2021108'),
('202110907mn0', 'Virgines', 'Xein', 'Manalo', '2001-01-01', 'virginesxein@gmail.com', '09200765070', 'Philippines', 'Manila', 'NCR', 'Manila', '3500', '2024-06-28', '1', '23:20:59', '2021109'),
('202110929mn0', 'Importante', 'Red', 'Meigen', '2001-01-01', 'redmeigen@gmail.com', '09123987658', 'Philippines', 'Manila', 'NCR', 'Manila', '1234', '2024-06-28', '1', '23:02:27', '2021109'),
('202110930mn0', 'Labinay', 'Stefen', 'Villanueva', '2001-01-01', 'stefen@gmail.com', '09876459123', 'Philippines', 'Manila', 'NCR', 'Manila', '9872', '2024-06-28', '1', '23:07:05', '2021109'),
('202110941mn0', 'Concepcion', 'Hazel', 'Gajol', '2001-01-01', 'hazelconcepcion@gmail.com', '09123456789', 'Philippines', 'Manila', 'NCR', 'Manila', '1234', '2024-06-28', '1', '22:57:12', '2021109'),
('202110946mn0', 'Reyes', 'Mark Angelo', 'Sunga', '2001-01-01', 'reyesmark@gmail.com', '09231765371', 'Philippines', 'Manila', 'NCR', 'Manila', '4570', '2024-06-28', '1', '23:14:44', '2021109'),
('202110965mn0', 'River', 'Tricia', 'Alpas', '2001-01-01', 'riveratricia@gmail.com', '09231765374', 'Philippines', 'Manila', 'NCR', 'Manila', '4570', '2024-06-28', '1', '23:15:34', '2021109'),
('202110976mn0', 'Jugueta', 'Ashley', 'Nipales', '2001-01-01', 'ashley@gmail.com', '09876456123', 'Philippines', 'Manila', 'NCR', 'Manila', '1204', '2024-06-28', '1', '23:06:18', '2021109'),
('2021117657mn', 'Lizardo', 'Aldrin', 'Arriola', '2001-01-01', 'aldrin@gmail.com', '09896459123', 'Philippines', 'Manila', 'NCR', 'Manila', '9872', '2024-06-28', '1', '23:08:22', '2021117'),
('202113186mn0', 'Uy', 'Lord', 'Bassig', '2001-01-01', 'lordallain@gmail.com', '09201765070', 'Philippines', 'Manila', 'NCR', 'Manila', '3571', '2024-06-28', '1', '23:20:20', '2021131'),
('202113187mn0', 'Geronimo', 'Jameel', 'Briones', '2001-01-01', 'geronimojam@gmail.com', '09123987652', 'Philippines', 'Manila', 'NCR', 'Manila', '1234', '2024-06-28', '1', '23:01:16', '2021131'),
('202310839mn0', 'de la Cruz', 'Pedro', 'Sanchez', '2024-07-15', 'pdelacruz@gmail.com', '09728194209', 'Philippines', 'BURGOS', 'REGION I', 'ILOCOS SUR', '4412', '2024-07-17', '1', '03:18:17', '2023108'),
('FA0123561212', 'Canlas', 'Arlene', 'NA', '2024-07-17', 'arlene@gmail.com', '09462217190', 'Philippines', 'San Juan', 'NCR', 'Manila', '4011', '2024-07-09', '1', '17:11:51', '1235612');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `user_ID` varchar(12) NOT NULL,
  `user_Role` char(1) NOT NULL,
  `date_Assigned` date DEFAULT NULL,
  `previous_Role` char(1) DEFAULT NULL,
  `date_Change` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`user_ID`, `user_Role`, `date_Assigned`, `previous_Role`, `date_Change`) VALUES
('202010839mn0', '2', '2024-07-17', '4', '2024-07-17'),
('202110261mn0', '2', '2024-07-16', '4', '2024-07-16'),
('202110839mn0', '1', '2024-07-16', '0', '2024-07-16'),
('202310839mn0', '2', '2024-07-17', '4', '2024-07-17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessment`
--
ALTER TABLE `assessment`
  ADD PRIMARY KEY (`assessment_ID`),
  ADD KEY `creator_ID` (`creator_ID`);

--
-- Indexes for table `cohort`
--
ALTER TABLE `cohort`
  ADD PRIMARY KEY (`cohort_ID`),
  ADD KEY `creator_ID` (`creator_ID`);

--
-- Indexes for table `cohort_archive`
--
ALTER TABLE `cohort_archive`
  ADD PRIMARY KEY (`cohort_ID`),
  ADD KEY `creator_ID` (`creator_ID`);

--
-- Indexes for table `college`
--
ALTER TABLE `college`
  ADD PRIMARY KEY (`college_ID`);

--
-- Indexes for table `college_archive`
--
ALTER TABLE `college_archive`
  ADD PRIMARY KEY (`college_ID`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_ID`),
  ADD KEY `college_ID` (`college_ID`),
  ADD KEY `cohort_ID` (`cohort_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `course_archive`
--
ALTER TABLE `course_archive`
  ADD PRIMARY KEY (`course_ID`),
  ADD KEY `college_ID` (`college_ID`),
  ADD KEY `creator_ID` (`creator_ID`),
  ADD KEY `cohort_ID` (`cohort_ID`);

--
-- Indexes for table `course_enrolled`
--
ALTER TABLE `course_enrolled`
  ADD PRIMARY KEY (`user_ID`,`course_ID`),
  ADD KEY `course_ID` (`course_ID`),
  ADD KEY `cohort_ID` (`cohort_ID`);

--
-- Indexes for table `course_enrolled_archive`
--
ALTER TABLE `course_enrolled_archive`
  ADD KEY `user_ID` (`user_ID`,`cohort_ID`,`course_ID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_ID`),
  ADD KEY `college_ID` (`college_ID`);

--
-- Indexes for table `examination_bank`
--
ALTER TABLE `examination_bank`
  ADD PRIMARY KEY (`assessment_ID`,`question_ID`);

--
-- Indexes for table `exam_answer`
--
ALTER TABLE `exam_answer`
  ADD PRIMARY KEY (`assessment_ID`,`question_ID`);

--
-- Indexes for table `interactive_video`
--
ALTER TABLE `interactive_video`
  ADD PRIMARY KEY (`video_ID`),
  ADD KEY `user_ID` (`user_ID`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `password_maintenance`
--
ALTER TABLE `password_maintenance`
  ADD PRIMARY KEY (`user_ID`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `student_submission`
--
ALTER TABLE `student_submission`
  ADD PRIMARY KEY (`submission_ID`),
  ADD KEY `requirement_Code` (`requirement_Code`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `subject_ID` (`subject_ID`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_ID`),
  ADD KEY `course_ID` (`course_ID`),
  ADD KEY `cohort_ID` (`cohort_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `subject_enrolled`
--
ALTER TABLE `subject_enrolled`
  ADD PRIMARY KEY (`user_ID`,`subject_ID`),
  ADD KEY `subject_ID` (`subject_ID`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `course_ID` (`course_ID`,`cohort_ID`);

--
-- Indexes for table `subject_enrolled_archive`
--
ALTER TABLE `subject_enrolled_archive`
  ADD KEY `user_ID` (`user_ID`,`subject_ID`,`course_ID`,`cohort_ID`);

--
-- Indexes for table `submission_requirement`
--
ALTER TABLE `submission_requirement`
  ADD PRIMARY KEY (`requirement_Code`),
  ADD KEY `subject_ID` (`subject_ID`);

--
-- Indexes for table `upload_lecture`
--
ALTER TABLE `upload_lecture`
  ADD PRIMARY KEY (`lecture_ID`),
  ADD KEY `subject_ID` (`subject_ID`);

--
-- Indexes for table `user_access`
--
ALTER TABLE `user_access`
  ADD PRIMARY KEY (`user_ID`);

--
-- Indexes for table `user_examination`
--
ALTER TABLE `user_examination`
  ADD PRIMARY KEY (`user_ID`,`assessment_ID`),
  ADD KEY `assessment_ID` (`assessment_ID`);

--
-- Indexes for table `user_exam_report`
--
ALTER TABLE `user_exam_report`
  ADD PRIMARY KEY (`user_ID`,`assessment_ID`),
  ADD KEY `assessment_ID` (`assessment_ID`);

--
-- Indexes for table `user_information`
--
ALTER TABLE `user_information`
  ADD PRIMARY KEY (`user_ID`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`user_ID`,`user_Role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `interactive_video_answer`
--
ALTER TABLE `interactive_video_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `interactive_video_assessment`
--
ALTER TABLE `interactive_video_assessment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `interactive_video_choice`
--
ALTER TABLE `interactive_video_choice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `interactive_video_question`
--
ALTER TABLE `interactive_video_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `interactive_video_video`
--
ALTER TABLE `interactive_video_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessment`
--
ALTER TABLE `assessment`
  ADD CONSTRAINT `assessment_ibfk_1` FOREIGN KEY (`creator_ID`) REFERENCES `user_information` (`user_ID`);

--
-- Constraints for table `cohort`
--
ALTER TABLE `cohort`
  ADD CONSTRAINT `cohort_ibfk_1` FOREIGN KEY (`creator_ID`) REFERENCES `user_information` (`user_ID`);

--
-- Constraints for table `cohort_archive`
--
ALTER TABLE `cohort_archive`
  ADD CONSTRAINT `cohort_archive_ibfk_1` FOREIGN KEY (`creator_ID`) REFERENCES `user_information` (`user_ID`);

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`college_ID`) REFERENCES `college` (`college_ID`),
  ADD CONSTRAINT `course_ibfk_2` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`),
  ADD CONSTRAINT `course_ibfk_3` FOREIGN KEY (`cohort_ID`) REFERENCES `cohort` (`cohort_ID`);

--
-- Constraints for table `course_archive`
--
ALTER TABLE `course_archive`
  ADD CONSTRAINT `course_archive_ibfk_1` FOREIGN KEY (`college_ID`) REFERENCES `college` (`college_ID`),
  ADD CONSTRAINT `course_archive_ibfk_2` FOREIGN KEY (`creator_ID`) REFERENCES `user_information` (`user_ID`),
  ADD CONSTRAINT `course_archive_ibfk_3` FOREIGN KEY (`cohort_ID`) REFERENCES `cohort` (`cohort_ID`);

--
-- Constraints for table `course_enrolled`
--
ALTER TABLE `course_enrolled`
  ADD CONSTRAINT `course_enrolled_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`),
  ADD CONSTRAINT `course_enrolled_ibfk_2` FOREIGN KEY (`course_ID`) REFERENCES `course` (`course_ID`);

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`college_ID`) REFERENCES `college` (`college_ID`);

--
-- Constraints for table `examination_bank`
--
ALTER TABLE `examination_bank`
  ADD CONSTRAINT `examination_bank_ibfk_1` FOREIGN KEY (`assessment_ID`) REFERENCES `assessment` (`assessment_ID`);

--
-- Constraints for table `exam_answer`
--
ALTER TABLE `exam_answer`
  ADD CONSTRAINT `exam_answer_ibfk_1` FOREIGN KEY (`assessment_ID`,`question_ID`) REFERENCES `examination_bank` (`assessment_ID`, `question_ID`);

--
-- Constraints for table `interactive_video`
--
ALTER TABLE `interactive_video`
  ADD CONSTRAINT `interactive_video_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`);

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

--
-- Constraints for table `interactive_video_video`
--
ALTER TABLE `interactive_video_video`
  ADD CONSTRAINT `interactive_video_video_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_information` (`user_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `interactive_video_video_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_ID`) ON DELETE CASCADE;

--
-- Constraints for table `password_maintenance`
--
ALTER TABLE `password_maintenance`
  ADD CONSTRAINT `password_maintenance_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`);

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_access` (`user_ID`);

--
-- Constraints for table `student_submission`
--
ALTER TABLE `student_submission`
  ADD CONSTRAINT `student_submission_ibfk_1` FOREIGN KEY (`requirement_Code`) REFERENCES `submission_requirement` (`requirement_Code`),
  ADD CONSTRAINT `student_submission_ibfk_2` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`),
  ADD CONSTRAINT `student_submission_ibfk_3` FOREIGN KEY (`subject_ID`) REFERENCES `subject` (`subject_ID`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`),
  ADD CONSTRAINT `subject_ibfk_2` FOREIGN KEY (`course_ID`) REFERENCES `course` (`course_ID`);

--
-- Constraints for table `subject_enrolled`
--
ALTER TABLE `subject_enrolled`
  ADD CONSTRAINT `subject_enrolled_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`),
  ADD CONSTRAINT `subject_enrolled_ibfk_2` FOREIGN KEY (`subject_ID`) REFERENCES `subject` (`subject_ID`);

--
-- Constraints for table `submission_requirement`
--
ALTER TABLE `submission_requirement`
  ADD CONSTRAINT `submission_requirement_ibfk_1` FOREIGN KEY (`subject_ID`) REFERENCES `subject` (`subject_ID`);

--
-- Constraints for table `upload_lecture`
--
ALTER TABLE `upload_lecture`
  ADD CONSTRAINT `upload_lecture_ibfk_1` FOREIGN KEY (`subject_ID`) REFERENCES `subject` (`subject_ID`);

--
-- Constraints for table `user_access`
--
ALTER TABLE `user_access`
  ADD CONSTRAINT `user_access_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`);

--
-- Constraints for table `user_examination`
--
ALTER TABLE `user_examination`
  ADD CONSTRAINT `user_examination_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`),
  ADD CONSTRAINT `user_examination_ibfk_2` FOREIGN KEY (`assessment_ID`) REFERENCES `assessment` (`assessment_ID`);

--
-- Constraints for table `user_exam_report`
--
ALTER TABLE `user_exam_report`
  ADD CONSTRAINT `user_exam_report_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`),
  ADD CONSTRAINT `user_exam_report_ibfk_2` FOREIGN KEY (`assessment_ID`) REFERENCES `assessment` (`assessment_ID`);

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

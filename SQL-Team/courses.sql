-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025 at 10:36 PM
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
-- Database: `ta_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `course_year` year(4) NOT NULL,
  `course_type` enum('Lecture','Lab') NOT NULL,
  `terms_offered` set('Fall','Spring') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_code`, `course_name`, `course_year`, `course_type`, `terms_offered`) VALUES
('EE 210', 'Digital Logic Design', '2002', 'Lecture', 'Spring'),
('EE 210 L', 'Digital Logic Design Lab', '2002', 'Lab', 'Spring'),
('EE 305', 'Computer Networks', '2003', 'Lecture', 'Fall'),
('EE 305 L', 'Computer Networks Lab', '2003', 'Lab', 'Fall'),
('SE 100', 'Programming for Engineers', '2001', 'Lecture', 'Fall'),
('SE 100 L', 'Programming for Engineers Lab', '2001', 'Lab', 'Fall'),
('SE 117', 'Software Practice and Society', '2001', 'Lecture', 'Fall'),
('SE 120', 'Object-Oriented Programming I', '2001', 'Lecture', 'Spring'),
('SE 120 L', 'Object-Oriented Programming I Lab', '2001', 'Lab', 'Spring'),
('SE 201', 'Introduction to Software Engineering', '2002', 'Lecture', 'Fall'),
('SE 212', 'Discrete Structure for Software Engineers', '2002', 'Lecture', 'Spring'),
('SE 214', 'Algorithms and Data Structures', '2002', 'Lecture', 'Fall'),
('SE 214 L', 'Algorithms and Data Structures Lab', '2002', 'Lab', 'Fall'),
('SE 220', 'Object-Oriented Programming II', '2002', 'Lecture', 'Fall'),
('SE 220 L', 'Object-Oriented Programming II Lab', '2002', 'Lab', 'Fall'),
('SE 225', 'Software Requirements', '2002', 'Lecture', 'Spring'),
('SE 225 L', 'Software Requirements Lab', '2002', 'Lab', 'Spring'),
('SE 310', 'Software Design and Architecture', '2003', 'Lecture', 'Fall'),
('SE 312', 'Database Management Systems', '2003', 'Lecture', 'Fall'),
('SE 312 L', 'Database Management Systems Lab', '2003', 'Lab', 'Fall'),
('SE 314', 'Operating Systems', '2003', 'Lecture', 'Fall'),
('SE 314 L', 'Operating Systems Lab', '2003', 'Lab', 'Fall'),
('SE 322', 'Internet of Things Application Development', '2003', 'Lecture', 'Spring'),
('SE 324', 'Web Application Development', '2003', 'Lecture', 'Spring'),
('SE 324 L', 'Web Application Development Lab', '2003', 'Lab', 'Spring'),
('SE 328', 'Mobile Application Development', '2003', 'Lecture', 'Spring'),
('SE 328 L', 'Mobile Application Development Lab', '2003', 'Lab', 'Spring'),
('SE 330', 'Introduction to Cybersecurity', '2003', 'Lecture', 'Spring'),
('SE 412', 'Software Testing & Quality Assurance', '2004', 'Lecture', 'Fall'),
('SE 414', 'Software Project Management', '2004', 'Lecture', 'Fall'),
('SE 423', 'Software Construction and Processes', '2004', 'Lecture', 'Spring'),
('SE 495', 'Capstone Project I', '2004', 'Lecture', 'Fall'),
('SE 496', 'Capstone Project II', '2004', 'Lecture', 'Spring'),
('SE 4_', 'Technical Elective', '2004', 'Lecture', 'Fall'),
('SE 4__', 'Technical Elective', '2004', 'Lecture', 'Fall'),
('SE 4___', 'Technical Elective', '2004', 'Lecture', 'Spring'),
('SE 4____', 'Technical Elective', '2004', 'Lecture', 'Spring'),

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

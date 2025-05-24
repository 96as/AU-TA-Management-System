-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 11, 2025 at 11:28 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `activecourses`
--

CREATE TABLE `activecourses` (
  `course_code` varchar(20) NOT NULL,
  `instructor` int(11) NOT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `num_students` int(11) DEFAULT NULL,
  `num_sections` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activecourses`
--

INSERT INTO `activecourses` (`course_code`, `instructor`, `semester`, `num_students`, `num_sections`, `is_active`) VALUES
('SE 100', 8951, 'Spring 2025', NULL, 1, 1),
('SE 100 L', 8951, 'Spring 2025', 60, 3, 1);

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
('SE 4____', 'Technical Elective', '2004', 'Lecture', 'Spring');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `instructor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`instructor_id`, `name`, `email`, `pass_hash`) VALUES
(2433, 'Taghreed Arafat Altamimi', 'taaltamimi@alfaisal.edu', '$2y$10$4nfOZEGJNtC5ezJtObTiT.GZ9S4Q9H3fuu8uzX7IWEB9DWt/igXGm'),
(7607, 'Randa Abdulrahman Almomen', 'ralmomen@alfaisal.edu', '$2y$10$n/w25aD7Oc17C9pKDxncoOtWTtCD9V3uYnd8DgXx4CcNP2sB6XEj.'),
(7621, 'Muhammed Suleiman Herwis', 'muherwis@alfaisal.edu', '$2y$10$/YUYCIk.TFJQYKgU9mfdEu/3YDH5E9UU31MVtrSevzyY04e3ZZFxe'),
(7633, 'Safia Yasmeen', 'syasmeen@alfaisal.edu', '$2y$10$9c94iK5gY2rLd2QdtCs0EOwkVrXziA0XDasQo1h67/4fVJ81E3OcC'),
(7750, 'Nidal Nasser', 'nnasser@alfaisal.edu', '$2y$10$c7ETjcvnGZAHw0Agf591veKAZJdlk84wsAKG4kO06BNZdBoU8J/7i'),
(7765, 'Ahmad Hani Sawalmeh', 'asawalmeh@alfaisal.edu', '$2y$10$VNAt6knzIvPaz6FiGZXFoeapwaOkzGZ3HWlxZM9xlp3eW2IduRv4O'),
(7957, 'Safia Mohammad Dawood', 'sdawood@alfaisal.edu', '$2y$10$Speyxib6xJnOTwVJb0ktAOFjn9Kwdt/.J0nGGXPFJWjneliqRflze'),
(7959, 'Waleed Mohammed Alsabhan', 'walsabhan@alfaisal.edu', '$2y$10$bLx9Xr8YBB.d5uZcyRFSZOnHgL/ZU5TXhD/Z47wMPbi84Y5L6Hlb6'),
(7998, 'Sarra Mohammed Drine', 'sdrine@alfaisal.edu', '$2y$10$nDVyf/Jc798wE7g0UNcUKuDqcLUvqAdT4FsABFdXqHWuAcN1ou4Fy'),
(8813, 'Sara Ghaleb Alhamdani', 'salhamdani@alfaisal.edu', '$2y$10$FfUqG9I75UiktrXF6p6yu.KDxzTSRy6BvRElD8zxU5yLdx0YIIcyu'),
(8823, 'Aljawharah Abdullatif Almuaythir', 'aalmuaythir@alfaisal.edu', '$2y$10$WON1NadhovQQWAsH3Ou1a.cbsG9QrWFR1yFBiolCC5YNa5seOeMbi'),
(8827, 'Muhammad Umair Khan', 'mumkhan@alfaisal.edu', '$2y$10$oKFX7vxkhque54XWAhVrm.iFGsFN41Pzl2ylO9Mw.tKqdvjuS9XtK'),
(8878, 'Areej Al-Wabil', 'awabil@alfaisal.edu', '$2y$10$XwKU8bRllSxKw4DqVr/oAumE11BQhH5sB3Ptj0U031tGRbMtI4dYa'),
(8951, 'Jomalyn Ariola Pancho', 'jpancho@alfaisal.edu', '$2y$10$OLgznhfOvDhtww/EaeiIieWydueFBnI2K9lJCP9Qa9uSJ4KhzUt4S'),
(121252, 'Hoda Ahmed Galal ElSayed', 'helsayed@alfaisal.edu', '$2y$10$UNYoqowXGaGfDqOoxBrwrejXwfr/1Nft.vGfyiJ70LBIJG9lsSZ2e');

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`manager_id`, `name`, `email`, `pass_hash`) VALUES
(1, 'System Manager', 'manager@alfaisal.edu', '$2y$10$IBmkAOJYqpODdl7lDdux7eIS1jx5NDoiJML1YI.172WX79d61eVvq');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `section_id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `section_number` varchar(10) DEFAULT NULL,
  `instructor_id` int(11) NOT NULL,
  `days` enum('Sunday','Monday') DEFAULT NULL,
  `time_slot` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tas`
--

CREATE TABLE `tas` (
  `ta_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `year` enum('Freshman','Sophomore','Junior','Senior') NOT NULL,
  `pass_hash` varchar(255) NOT NULL,
  `max_hours` int(11) DEFAULT 15
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tas`
--

INSERT INTO `tas` (`ta_id`, `name`, `email`, `year`, `pass_hash`, `max_hours`) VALUES
(202004, 'Layla Zaid', 'lzaid@alfaisal.edu', 'Senior', '$2y$10$aHoQVdnHRs/LtroElDC0fuc8secbg0egNzU6ZnTawgw0o/VvZT3uG', 15),
(202300, 'Amal Saud', 'asaud@alfaisal.edu', 'Freshman', '$2y$10$MHDaahjlHzm.fg2BPK2cJeTYf6v8VD3FKp68TDKKeuUhe5PLztAHe', 15),
(202302, 'Mona Khalid', 'mkhalid@alfaisal.edu', 'Sophomore', '$2y$10$PrkLvZ8git8VeGgktWVwp./ajxRCgK1cR8seKo0IFdmMikdBtQKvK', 15),
(203005, 'Salem Nasser', 'snasser@alfaisal.edu', 'Junior', '$2y$10$RhFhXn9kWLxQyr8NWcnIjeeY76CJfjQ8qsdgjgm3TDBFlTva2UxTu', 15),
(223006, 'Nour Jamal', 'njamal@alfaisal.edu', 'Freshman', '$2y$10$SO3riZMEvBvt3hpZKUvblOBbAiMTYr.KY5DQaN/t7V4xgWhQzyS7q', 15);

-- --------------------------------------------------------

--
-- Table structure for table `ta_course`
--

CREATE TABLE `ta_course` (
  `ta_name` varchar(100) NOT NULL,
  `total_assigned_hours` int(11) DEFAULT 0,
  `proctor_hours` int(11) DEFAULT 0,
  `correcting_hours` int(11) DEFAULT 0,
  `lab_hours` int(11) DEFAULT 0,
  `course_code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ta_course`
--

INSERT INTO `ta_course` (`ta_name`, `total_assigned_hours`, `proctor_hours`, `correcting_hours`, `lab_hours`, `course_code`) VALUES
('Amal Saud', 13, 10, 1, 1, 'SE 100'),
('Amal Saud', 2, 1, 1, 0, 'SE 100 L');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activecourses`
--
ALTER TABLE `activecourses`
  ADD KEY `course_code` (`course_code`),
  ADD KEY `instructor` (`instructor`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_code`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`instructor_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`manager_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`section_id`),
  ADD KEY `course_code` (`course_code`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `tas`
--
ALTER TABLE `tas`
  ADD PRIMARY KEY (`ta_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `ta_course`
--
ALTER TABLE `ta_course`
  ADD KEY `course_code` (`course_code`),
  ADD KEY `ta_name` (`ta_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activecourses`
--
ALTER TABLE `activecourses`
  ADD CONSTRAINT `activecourses_ibfk_1` FOREIGN KEY (`course_code`) REFERENCES `courses` (`course_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `activecourses_ibfk_2` FOREIGN KEY (`instructor`) REFERENCES `instructors` (`instructor_id`);

--
-- Constraints for table `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `section_ibfk_1` FOREIGN KEY (`course_code`) REFERENCES `courses` (`course_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `section_ibfk_2` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE;

--
-- Constraints for table `ta_course`
--
ALTER TABLE `ta_course`
  ADD CONSTRAINT `ta_course_ibfk_2` FOREIGN KEY (`course_code`) REFERENCES `activecourses` (`course_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `ta_course_ibfk_3` FOREIGN KEY (`ta_name`) REFERENCES `tas` (`name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

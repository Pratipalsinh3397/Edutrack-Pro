-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 09:30 AM
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
-- Database: `studentmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_tbl`
--

CREATE TABLE `attendance_tbl` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_date` date NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1 = Present, 2 = Late, 3 = Absent, 4 = Holiday',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_tbl`
--

INSERT INTO `attendance_tbl` (`id`, `student_id`, `class_date`, `status`, `created_at`, `updated_at`) VALUES
(25, 13, '2025-05-02', 1, '2025-05-02 21:44:09', NULL),
(26, 16, '2025-05-02', 1, '2025-05-02 21:44:09', NULL),
(27, 20, '2025-05-02', 3, '2025-05-02 21:44:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feespayment`
--

CREATE TABLE `feespayment` (
  `id` int(11) NOT NULL,
  `stuID` int(200) DEFAULT NULL,
  `paymentID` varchar(200) DEFAULT NULL,
  `totalfees` varchar(200) DEFAULT NULL,
  `paidfees` varchar(200) DEFAULT NULL,
  `remainingfees` varchar(200) DEFAULT NULL,
  `remark` varchar(500) DEFAULT NULL,
  `paymentDate` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feespayment`
--

INSERT INTO `feespayment` (`id`, `stuID`, `paymentID`, `totalfees`, `paidfees`, `remainingfees`, `remark`, `paymentDate`) VALUES
(43, 101, 'pay_QQ8R8w85DIbSPs', '10000', '2000', '8000', '1st term', '2025-05-02 22:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `feespaymenthistory`
--

CREATE TABLE `feespaymenthistory` (
  `id` int(11) NOT NULL,
  `stuID` int(200) DEFAULT NULL,
  `paymentID` varchar(200) DEFAULT NULL,
  `totalfees` varchar(200) DEFAULT NULL,
  `paidfees` varchar(200) DEFAULT NULL,
  `remainingfees` varchar(200) DEFAULT NULL,
  `remark` varchar(500) DEFAULT NULL,
  `paymentDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feespaymenthistory`
--

INSERT INTO `feespaymenthistory` (`id`, `stuID`, `paymentID`, `totalfees`, `paidfees`, `remainingfees`, `remark`, `paymentDate`) VALUES
(22, 101, 'pay_QQ8R8w85DIbSPs', '10000', '2000', '8000', '1st term', '2025-05-02 17:00:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(10) NOT NULL,
  `AdminName` varchar(120) DEFAULT NULL,
  `UserName` varchar(120) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `UserName`, `MobileNumber`, `Email`, `Password`, `AdminRegdate`) VALUES
(1, 'Admin', 'admin', 8979555558, 'hirenvaghela30@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2025-01-01 04:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `tblclass`
--

CREATE TABLE `tblclass` (
  `ID` int(200) NOT NULL,
  `ClassName` varchar(50) DEFAULT NULL,
  `Section` varchar(20) DEFAULT NULL,
  `fees` varchar(200) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclass`
--

INSERT INTO `tblclass` (`ID`, `ClassName`, `Section`, `fees`, `CreationDate`) VALUES
(19, 'Standard - 1', 'A', '10000', '2025-05-02 15:36:44'),
(20, 'Standard - 2', 'A', '10000', '2025-05-02 15:36:55'),
(21, 'Standard - 3', 'A', '10000', '2025-05-02 15:37:11'),
(22, 'Standard - 4', 'A', '14000', '2025-05-02 15:37:33'),
(23, 'Standard - 5', 'A', '14000', '2025-05-02 15:37:48'),
(24, 'Standard - 6', 'A', '14000', '2025-05-02 15:37:59'),
(25, 'Standard - 7', 'A', '15000', '2025-05-02 15:38:25'),
(26, 'Standard - 8', 'A', '17000', '2025-05-02 15:38:46'),
(27, 'Standard - 9', 'A', '20000', '2025-05-02 15:39:10'),
(28, 'Standard - 10', 'A', '25000', '2025-05-02 15:39:24'),
(29, 'Standard - 11', 'A', '28000', '2025-05-02 15:39:53'),
(30, 'Standard - 12', 'A', '30000', '2025-05-02 15:40:09');

-- --------------------------------------------------------

--
-- Table structure for table `tblcontactus`
--

CREATE TABLE `tblcontactus` (
  `id` int(11) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `phoneno` bigint(10) NOT NULL,
  `subject` varchar(80) NOT NULL,
  `message` varchar(500) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcontactus`
--

INSERT INTO `tblcontactus` (`id`, `fullname`, `phoneno`, `subject`, `message`, `email`) VALUES
(2, 'RANA', 9924140288, 'ADMISSION', 'HELLo', 'admin@example.com'),
(3, 'JAY', 7777960864, 'ADMISSION', 'cxc', 'codelabs@gmail.com'),
(4, 'Hiren', 9924140288, 'ETC', 'ETC', 'codelabs@gmail.com'),
(6, 'Dhruv', 7885465454, 'ADDMISSION', 'For the Admission', 'admin@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `tblenroll`
--

CREATE TABLE `tblenroll` (
  `ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Standard` varchar(50) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Message` text DEFAULT NULL,
  `IsResponded` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblenroll`
--

INSERT INTO `tblenroll` (`ID`, `Name`, `Standard`, `Phone`, `Email`, `Message`, `IsResponded`) VALUES
(7, 'hello', '12', '4563285555', 'asd@abc.com', 'hello', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblfeedback`
--

CREATE TABLE `tblfeedback` (
  `id` int(11) NOT NULL,
  `studentid` varchar(100) DEFAULT NULL,
  `teacherid` int(11) DEFAULT NULL,
  `feedbacktext` text DEFAULT NULL,
  `postingDate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblfeedback`
--

INSERT INTO `tblfeedback` (`id`, `studentid`, `teacherid`, `feedbacktext`, `postingDate`) VALUES
(8, '101', 8, 'Very Helpful', '2025-05-02 22:31:49'),
(9, '101', 9, 'Very Helpful', '2025-05-02 22:31:56');

-- --------------------------------------------------------

--
-- Table structure for table `tblhomework`
--

CREATE TABLE `tblhomework` (
  `id` int(11) NOT NULL,
  `homeworkTitle` mediumtext DEFAULT NULL,
  `classId` int(11) DEFAULT NULL,
  `teacherId` int(11) DEFAULT NULL,
  `homeworkDescription` longtext DEFAULT NULL,
  `homeworkFile` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT current_timestamp(),
  `lastDateofSubmission` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblhomework`
--

INSERT INTO `tblhomework` (`id`, `homeworkTitle`, `classId`, `teacherId`, `homeworkDescription`, `homeworkFile`, `postingDate`, `lastDateofSubmission`) VALUES
(19, 'Maths', 19, 7, 'Today\'s Homework', '62007a2cd9602d4cdb6327939d5e3bba.pdf', '2025-05-02 16:22:09', '2025-05-07'),
(20, 'DBMS', 20, 7, 'Today\'s Homework', 'a61181c6cf4502ad820b99c2c65aa091.pdf', '2025-05-02 16:23:22', '2025-05-10');

-- --------------------------------------------------------

--
-- Table structure for table `tblmaterial`
--

CREATE TABLE `tblmaterial` (
  `id` int(11) NOT NULL,
  `materialTitle` mediumtext DEFAULT NULL,
  `classId` int(11) DEFAULT NULL,
  `teacherId` int(11) DEFAULT NULL,
  `materialDescription` longtext DEFAULT NULL,
  `materialFile` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblmaterial`
--

INSERT INTO `tblmaterial` (`id`, `materialTitle`, `classId`, `teacherId`, `materialDescription`, `materialFile`, `postingDate`) VALUES
(10, 'SS', 19, 7, 'Ch-1', 'fa00f732f7a558f3e125b054301ef72f.pdf', '2025-05-02 16:24:14'),
(11, 'DCN', 20, 7, 'Ch-3 (Network Layer)', 'fa00f732f7a558f3e125b054301ef72f.pdf', '2025-05-02 16:24:54');

-- --------------------------------------------------------

--
-- Table structure for table `tblnotice`
--

CREATE TABLE `tblnotice` (
  `ID` int(5) NOT NULL,
  `NoticeTitle` mediumtext DEFAULT NULL,
  `ClassId` int(10) DEFAULT NULL,
  `NoticeMsg` mediumtext DEFAULT NULL,
  `noticeFile` varchar(255) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblnotice`
--

INSERT INTO `tblnotice` (`ID`, `NoticeTitle`, `ClassId`, `NoticeMsg`, `noticeFile`, `CreationDate`) VALUES
(22, 'Fees For 2025', 19, '1st Term of Fees', 'fa00f732f7a558f3e125b054301ef72f.pdf', '2025-05-02 16:15:26');

-- --------------------------------------------------------

--
-- Table structure for table `tblpublicnotice`
--

CREATE TABLE `tblpublicnotice` (
  `ID` int(5) NOT NULL,
  `NoticeTitle` varchar(200) DEFAULT NULL,
  `NoticeMessage` mediumtext DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblpublicnotice`
--

INSERT INTO `tblpublicnotice` (`ID`, `NoticeTitle`, `NoticeMessage`, `CreationDate`) VALUES
(3, 'Winter vaction', 'Vacation til 15 Jan', '2025-01-04 04:14:32'),
(4, 'HOLI HOLIDAY', '15 - 03 - 2025 Holiday Due to HOLI FESTIVAL\r\n', '2025-03-11 05:02:46'),
(20, 'Summer Vacation', 'Summer Vacation Starts from 5th May', '2025-05-02 16:16:56');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent`
--

CREATE TABLE `tblstudent` (
  `ID` int(10) NOT NULL,
  `StudentName` varchar(200) DEFAULT NULL,
  `StudentEmail` varchar(200) DEFAULT NULL,
  `StudentClass` int(200) DEFAULT NULL,
  `Gender` varchar(50) DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `StuID` varchar(200) DEFAULT NULL,
  `FatherName` mediumtext DEFAULT NULL,
  `MotherName` mediumtext DEFAULT NULL,
  `ContactNumber` bigint(10) DEFAULT NULL,
  `AltenateNumber` bigint(10) DEFAULT NULL,
  `Address` mediumtext DEFAULT NULL,
  `UserName` varchar(200) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `Image` varchar(200) DEFAULT NULL,
  `DateofAdmission` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudent`
--

INSERT INTO `tblstudent` (`ID`, `StudentName`, `StudentEmail`, `StudentClass`, `Gender`, `DOB`, `StuID`, `FatherName`, `MotherName`, `ContactNumber`, `AltenateNumber`, `Address`, `UserName`, `Password`, `Image`, `DateofAdmission`) VALUES
(13, 'Dhruv', 'dhruvrapariya77@gmail.com', 19, 'Male', '2005-03-05', '101', 'Dineshbhai', 'shitalben', 7954566465, 8765448845, 'Chandlodia , Ahmedabad', 'Dhruv1', '53dfada5edb3f89c688a7ecf61000c44', '6d011fed9c9b657d82774049e7cb987b1746200586.png', '2025-05-02 15:43:06'),
(14, 'Dhruv', 'dhruvrapariya77@gmail.com', 20, 'Male', '2005-03-05', '201', 'Dineshbhai', 'shitalben', 7954566465, 987654321, 'Chandlodia', 'Dhruv2', '855aca97f8eb346f5348ffaa0fbd266f', '6d011fed9c9b657d82774049e7cb987b1746200746.png', '2025-05-02 15:45:46'),
(15, 'Dhruv', 'dhruvrapariya77@gmail.com', 21, 'Male', '2005-03-05', '301', 'Dineshbhai', 'shitalben', 7954566465, 8765448845, 'Chandlodia, Ahmedabad', 'Dhruv3', '0c77cb86ac6d8448f143084e2bff4d8e', '6d011fed9c9b657d82774049e7cb987b1746200918.png', '2025-05-02 15:48:38'),
(16, 'Hiren', 'vaghelahiren30@gmail.com', 19, 'Male', '2004-12-14', '102', 'Dineshbhai', 'Jyotiben', 7954566465, 8765448845, 'Nava naroda, Nikol, Ahmedabad', 'Hiren1', '38465800d62c5fbcee051f607e8f0e92', '99f3349bafe522e8b2d291060640726d1746201161.jpg', '2025-05-02 15:52:41'),
(17, 'Hiren', 'vaghelahiren30@gmail.com', 20, 'Male', '2005-12-14', '202', 'Dineshbhai', 'Jyotiben', 7954566465, 8765448845, 'Nava Naroda, Ahmedabad', 'Hiren2', 'e40c3dd7796f9d5a759d31fa08ecd7a8', '99f3349bafe522e8b2d291060640726d1746201249.jpg', '2025-05-02 15:54:09'),
(18, 'Hiren', 'vaghelahiren30@gmail.com', 21, 'Male', '2004-12-14', '302', 'Dineshbhai', 'Jyotiben', 7954566465, 987654321, 'Nava Naroda , Ahmedabad', 'Hiren3', '5315c5de8eaffa6c944fe5995731cd3b', '99f3349bafe522e8b2d291060640726d1746201327.jpg', '2025-05-02 15:55:27'),
(19, 'Dhruv', 'dhruvrapariya77@gmail.com', 22, 'Male', '2005-03-05', '401', 'Dineshbhai', 'shitalben', 7954566465, 987654321, 'Chandlodia, Ahmedabad', 'Dhruv4', '5c1f4514fbcece70122c856c4932c5c9', '6d011fed9c9b657d82774049e7cb987b1746201498.png', '2025-05-02 15:58:18'),
(20, 'Pratipal', 'ranapratipalsinh25@gmail.com', 19, 'Male', '2005-02-28', '103', 'Hardevsinh', 'Jyotiben', 1234567890, 987654321, 'Gatrad Gam, Ahmedabad', 'Pratipal1', '3cebb5a4636f40395310703b9ab43824', 'db409c2d8f1539eea60e544c8f3dd26a1746201821.jpg', '2025-05-02 16:03:41'),
(21, 'Pratipal', 'ranapratipalsinh25@gmail.com', 20, 'Male', '2004-02-28', '203', 'Hardevsinh', 'Varshaba', 7954566465, 987654321, 'Gatrad Gam, Ahmedabad', 'Pratipal2', '34b085315c77d271afc61750d0403388', 'db409c2d8f1539eea60e544c8f3dd26a1746201914.jpg', '2025-05-02 16:05:14'),
(22, 'Pratipal', 'ranapratipalsinh25@gmail.com', 21, 'Male', '2004-02-28', '303', 'Hardevsinh', 'Varshaba', 7954566465, 987654321, 'Gatrad Gam , Ahmedabad', 'Pratipal3', '1c5238c31c150c97a41da5d6e6b5dfa5', 'db409c2d8f1539eea60e544c8f3dd26a1746202039.jpg', '2025-05-02 16:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `tblteacher`
--

CREATE TABLE `tblteacher` (
  `ID` int(10) NOT NULL,
  `TeacherName` varchar(120) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `UserName` varchar(120) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `TeacherClass` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Gender` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Image` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `TeacherID` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Password` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `TeacherRegdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblteacher`
--

INSERT INTO `tblteacher` (`ID`, `TeacherName`, `UserName`, `MobileNumber`, `Email`, `TeacherClass`, `Gender`, `Image`, `TeacherID`, `Password`, `TeacherRegdate`) VALUES
(7, 'Karan Bharwad', 'Karan1', 7954566465, 'vaghelahiren30@gmail.com', '19', 'Male', '46752bcad05e0dadc7a933db0129afed1746202177.jpg', '785', '759f27a9ed420c2e5e646b8457c8afa4', '2025-05-02 16:09:37'),
(8, 'Jay Patel', 'Jay2', 7954566465, 'vaghelahiren30@gmail.com', '20', 'Male', '99f3349bafe522e8b2d291060640726d1746202237.jpg', '786', '38309f62d8505b63fa84f7c404031447', '2025-05-02 16:10:37'),
(9, 'Uday Bholane', 'Uday3', 7954566465, 'dhruvrapariya77@gmail.com', '21', 'Male', '6d011fed9c9b657d82774049e7cb987b1746202353.png', '787', 'ed41df426e89f64276f6b27a0249a1b4', '2025-05-02 16:12:33');

-- --------------------------------------------------------

--
-- Table structure for table `tbluploadedhomeworks`
--

CREATE TABLE `tbluploadedhomeworks` (
  `id` int(11) NOT NULL,
  `homeworkId` int(11) DEFAULT NULL,
  `studentId` int(11) DEFAULT NULL,
  `homeworkDescription` longtext DEFAULT NULL,
  `homeworkFile` varchar(255) DEFAULT NULL,
  `postinDate` timestamp NULL DEFAULT current_timestamp(),
  `teacherRemark` mediumtext DEFAULT NULL,
  `teacherRemarkDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblvideo`
--

CREATE TABLE `tblvideo` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `teacherId` int(11) DEFAULT NULL,
  `videotitle` varchar(200) DEFAULT NULL,
  `video_name` varchar(255) NOT NULL,
  `video_path` varchar(255) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `postingDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_tbl`
--
ALTER TABLE `attendance_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Test` (`student_id`);

--
-- Indexes for table `feespayment`
--
ALTER TABLE `feespayment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feespaymenthistory`
--
ALTER TABLE `feespaymenthistory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblclass`
--
ALTER TABLE `tblclass`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblcontactus`
--
ALTER TABLE `tblcontactus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblenroll`
--
ALTER TABLE `tblenroll`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblfeedback`
--
ALTER TABLE `tblfeedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblhomework`
--
ALTER TABLE `tblhomework`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_homework_class` (`classId`),
  ADD KEY `fk_homework_teacher` (`teacherId`);

--
-- Indexes for table `tblmaterial`
--
ALTER TABLE `tblmaterial`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_material_class` (`classId`),
  ADD KEY `fk_material_teacher` (`teacherId`);

--
-- Indexes for table `tblnotice`
--
ALTER TABLE `tblnotice`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblpublicnotice`
--
ALTER TABLE `tblpublicnotice`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_tblstudent_class` (`StudentClass`);

--
-- Indexes for table `tblteacher`
--
ALTER TABLE `tblteacher`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbluploadedhomeworks`
--
ALTER TABLE `tbluploadedhomeworks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblvideo`
--
ALTER TABLE `tblvideo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `fk_video_teacher` (`teacherId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance_tbl`
--
ALTER TABLE `attendance_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `feespayment`
--
ALTER TABLE `feespayment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `feespaymenthistory`
--
ALTER TABLE `feespaymenthistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblclass`
--
ALTER TABLE `tblclass`
  MODIFY `ID` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tblcontactus`
--
ALTER TABLE `tblcontactus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblenroll`
--
ALTER TABLE `tblenroll`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblfeedback`
--
ALTER TABLE `tblfeedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblhomework`
--
ALTER TABLE `tblhomework`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tblmaterial`
--
ALTER TABLE `tblmaterial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblnotice`
--
ALTER TABLE `tblnotice`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tblpublicnotice`
--
ALTER TABLE `tblpublicnotice`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tblteacher`
--
ALTER TABLE `tblteacher`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbluploadedhomeworks`
--
ALTER TABLE `tbluploadedhomeworks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblvideo`
--
ALTER TABLE `tblvideo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance_tbl`
--
ALTER TABLE `attendance_tbl`
  ADD CONSTRAINT `Test` FOREIGN KEY (`student_id`) REFERENCES `tblstudent` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblhomework`
--
ALTER TABLE `tblhomework`
  ADD CONSTRAINT `fk_homework_class` FOREIGN KEY (`classId`) REFERENCES `tblclass` (`ID`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_homework_teacher` FOREIGN KEY (`teacherId`) REFERENCES `tblteacher` (`ID`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `tblmaterial`
--
ALTER TABLE `tblmaterial`
  ADD CONSTRAINT `fk_material_class` FOREIGN KEY (`classId`) REFERENCES `tblclass` (`ID`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_material_teacher` FOREIGN KEY (`teacherId`) REFERENCES `tblteacher` (`ID`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD CONSTRAINT `fk_tblstudent_class` FOREIGN KEY (`StudentClass`) REFERENCES `tblclass` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tblvideo`
--
ALTER TABLE `tblvideo`
  ADD CONSTRAINT `fk_video_teacher` FOREIGN KEY (`teacherId`) REFERENCES `tblteacher` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblvideo_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `tblclass` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

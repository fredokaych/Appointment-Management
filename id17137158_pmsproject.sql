-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2022 at 02:31 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id17137158_pmsproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminmessages`
--

CREATE TABLE `adminmessages` (
  `id` int(11) NOT NULL,
  `docID` int(11) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `message` varchar(260) NOT NULL,
  `patientID` int(11) NOT NULL DEFAULT 0,
  `adminID` int(11) NOT NULL,
  `IsRead` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `adminmessages`
--

INSERT INTO `adminmessages` (`id`, `docID`, `date`, `message`, `patientID`, `adminID`, `IsRead`) VALUES
(1, 0, '2021-08-03 04:16:10', 'Message from joan@gmail.com: Test The dot: This is a testing msg', 11, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contactno` int(11) DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `updationDate` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `contactno`, `password`, `updationDate`) VALUES
(2, 'Fred Okinyi', 'fred@gmail.com', 789898990, '$2y$10$M9/c2dXMoruUtlpuixnY6.A/KbtlRQkvZezK5eMFwPVd1oliYnYuO', ''),
(3, '', 'test', NULL, '$2y$10$XkGf.9lxPLS3aYgyqo6AmOFuWnOpo7zugeae81.ujZN7pl.XVKx7C', ''),
(4, 'admin', 'admin@admin.com', NULL, '$2y$10$Emx5yvHyf0mwKTf60CRbh.y43pnFcrkjgekSfODnKouYFaafJW6eq', '');

-- --------------------------------------------------------

--
-- Table structure for table `adminslog`
--

CREATE TABLE `adminslog` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userip` binary(16) DEFAULT NULL,
  `loginTime` timestamp NULL DEFAULT current_timestamp(),
  `logout` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `doctorSpecialization` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctorId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `consultancyFees` int(11) DEFAULT NULL,
  `appointmentDate` date DEFAULT NULL,
  `appointmentTime` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT current_timestamp(),
  `userStatus` int(11) NOT NULL DEFAULT 1,
  `doctorStatus` int(11) NOT NULL DEFAULT 1,
  `payStatus` int(11) NOT NULL DEFAULT 0,
  `approvStatus` int(11) NOT NULL DEFAULT 0,
  `completed` int(11) NOT NULL DEFAULT 0,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctormessages`
--

CREATE TABLE `doctormessages` (
  `id` int(11) NOT NULL,
  `docID` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `message` varchar(260) NOT NULL,
  `patientID` int(11) NOT NULL DEFAULT 0,
  `adminID` int(11) NOT NULL DEFAULT 0,
  `IsRead` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `specilization` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `docFees` double DEFAULT NULL,
  `contactno` bigint(11) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `specilization`, `name`, `address`, `docFees`, `contactno`, `email`, `password`, `creationDate`, `updationDate`) VALUES
(5, 'General Physician', 'Naomi Obuya', '44 Juja Street', 30, 717909089, 'naomi@gmail.com', '$2y$10$SBGlNZUN5wwxYUjDzTfy3uCj98HUdBqK2WC6kQmWpvDk7oBveDM0m', '2021-06-26 19:07:05', '2021-08-22 18:33:55'),
(7, 'Dermatologist', 'Fred Okech', 'Juja Kenya\r\nKalimoni', 20, 717905039, 'fred@gmail.com', '$2y$10$wBlrvD5CAmkxFrIubkub8eyErOika9Tcmhbetg1j.s8VppwxbznZK', '2021-06-25 18:33:23', '2021-08-22 18:50:52'),
(9, 'Gynecologist/Obstetrician', 'Regina Kwamboka', 'Kenyatta Road', 400, 789232345, 'regina@gmail.com', '$2y$10$0WLik2bllJATWJ01FTYlt.JWbInsFwTA.kyt3B9L7f36MONIEZsJm', '2021-06-26 19:10:20', '2021-08-22 19:09:36'),
(16, 'Dermatologist', 'Alfred Kibe', '67 Mangu', 30, 722222222, 'kibe@gmail.com', '$2y$10$PSsxk/2o1nJL4RzNf.VwB.8bRq7ImW4NMZ9lfxuc32rY2UOdwbg36', '2021-06-27 00:39:29', '2021-08-25 19:17:17');

-- --------------------------------------------------------

--
-- Table structure for table `doctorslog`
--

CREATE TABLE `doctorslog` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userip` binary(16) DEFAULT NULL,
  `loginTime` timestamp NULL DEFAULT current_timestamp(),
  `logout` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctorspecilization`
--

CREATE TABLE `doctorspecilization` (
  `id` int(11) NOT NULL,
  `specilization` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctorspecilization`
--

INSERT INTO `doctorspecilization` (`id`, `specilization`, `creationDate`, `updationDate`) VALUES
(1, 'Gynecologist/Obstetrician', '2021-08-10 06:37:25', '2021-08-13 23:11:19'),
(2, 'General Physician', '2021-08-10 06:37:30', '2021-08-20 11:36:58'),
(3, 'Dermatologist', '2021-08-10 06:37:35', '2021-08-13 23:11:40'),
(4, 'Homeopath', '2021-08-10 06:37:40', '2021-08-13 23:11:57'),
(5, 'Ayurveda', '2021-08-10 06:37:45', '2021-08-13 23:12:09'),
(7, 'Ear-Nose-Throat (Ent) Specialist', '2021-08-10 06:37:50', '2021-08-18 16:32:07');

-- --------------------------------------------------------

--
-- Table structure for table `medhist`
--

CREATE TABLE `medhist` (
  `ID` int(10) NOT NULL,
  `PatientID` int(10) DEFAULT NULL,
  `BloodPressure` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `BloodSugar` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Weight` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Temperature` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `MedicalPres` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `medhist`
--

INSERT INTO `medhist` (`ID`, `PatientID`, `BloodPressure`, `BloodSugar`, `Weight`, `Temperature`, `MedicalPres`, `remarks`, `CreationDate`) VALUES
(2, 11, '120/185', '80/120', '85 Kg', '101 degree', '#Fever, #BP high\r\n1.Paracetamol\r\n2.jocib tab\r\n', NULL, '2021-07-22 08:19:35'),
(3, 11, '90/120', '92/190', '86 kg', '99 deg', '#Sugar High\r\n1.Petz 30', NULL, '2021-07-22 08:19:30'),
(4, 1, '125/200', '86/120', '56 kg', '98 deg', '# blood pressure is high\r\n1.koil cipla', NULL, '2019-11-06 04:52:42'),
(5, 4, '96/120', '98/120', '57 kg', '102 deg', '#Viral\r\n1.gjgjh-1Ml\r\n2.kjhuiy-2M', NULL, '2021-08-13 21:44:16'),
(6, 4, '90/120', '120', '56', '98 F', '#blood sugar high\r\n#Asthma problem', NULL, '2019-11-06 14:38:33'),
(7, 11, '80/120', '120', '85', '98.6', 'Rx\r\n\r\nAbc tab\r\nxyz Syrup', NULL, '2021-07-22 08:19:22');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contactno` bigint(12) DEFAULT NULL,
  `message` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `AdminRemark` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `LastupdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `IsRead` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `fullname`, `email`, `contactno`, `message`, `PostingDate`, `AdminRemark`, `LastupdationDate`, `IsRead`) VALUES
(5, 'Steve Comp', 'stevo@gmail.com', 718804523, 'I am  new patient, kindly approve my appointment, ill pay later', '2021-07-22 08:06:38', 'We cant approve anything without payment.', '2021-07-22 08:16:10', 1),
(6, 'Steve Comp', 'stevo@gmail.com', 718804523, 'Another folowup message since ive received no reply', '2021-07-22 08:09:24', 'updated', '2021-08-20 14:19:23', 1),
(7, 'Fred Otieno Okech', 'stevo@gmail.com', 718804523, 'test from abour', '2021-08-16 15:06:59', 'hdfhdsdhgf', '2021-08-20 14:18:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `patientmessages`
--

CREATE TABLE `patientmessages` (
  `id` int(11) NOT NULL,
  `docID` int(11) NOT NULL DEFAULT 0,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `message` varchar(260) NOT NULL,
  `patientID` int(11) NOT NULL,
  `adminID` int(11) NOT NULL DEFAULT 0,
  `IsRead` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(10) NOT NULL,
  `Docid` int(10) DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `PatientContno` bigint(10) DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `PatientGender` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `PatientAdd` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `PatientAge` int(10) DEFAULT NULL,
  `PatientMedhis` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `hacc` int(20) DEFAULT 0,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `Docid`, `name`, `PatientContno`, `email`, `PatientGender`, `PatientAdd`, `PatientAge`, `PatientMedhis`, `hacc`, `CreationDate`, `UpdationDate`, `password`) VALUES
(2, NULL, 'Suleiman Abdillahi', 717905039, 'suleiman@gmail.com', 'Male', 'Kihunguro', 23, 'Allergic to peanuts', NULL, '2021-06-27 01:08:39', '2021-07-22 07:45:51', '$2y$10$91RE7iW25Dnv3603bppiQuBo8gJmcDPmm4cnah3BXS.oTZaFKLib2'),
(9, NULL, 'Agnetta Okwemba', 789897667, 'agnetta@gmail.com', 'Female', '6767 Kihunguro', 56, '', NULL, '2021-06-27 01:04:05', '2021-08-22 19:22:36', '$2y$10$soQZFji16PERGF1kLH8qCeWFsbf3r0zTY1eZ9RvyoY3BLOOWYJJCu'),
(11, NULL, 'Joan Nanjala', 717905039, 'joan@gmail.com', 'Female', 'Mathare Mental H', 34, 'No Records to show', 14, '2021-06-27 01:27:36', '2021-08-25 18:26:37', '$2y$10$k0di4LgpxvfWCGQbmo9znOb4NRhcKU3sJZYpkhWfpG3nzyKSIBsd2'),
(13, NULL, 'Babra Kimani', 789876545, 'babra@gmail.com', 'Female', 'Kagongo Road', 35, 'I have been allergic to peanuts', NULL, '2021-08-16 16:15:24', '2021-08-16 16:16:34', '$2y$10$nEelc15Wbb173suFTP.zeu8q7xVctAsXd4h1UjobLaAYFRSUbEshu'),
(14, NULL, 'Edward Kariukki', 789897656, 'edward@gmail.com', 'Male', '234 Mathare North', 23, 'I have been allergic to peanuts', NULL, '2021-08-23 00:02:42', '2021-08-23 00:05:20', '$2y$10$lSDUuG6tOmHq9z1fKHu9UurPaoOanh8g/xKDQ1/wnBMOqfn8owFiO');

-- --------------------------------------------------------

--
-- Table structure for table `patientslog`
--

CREATE TABLE `patientslog` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userip` binary(16) DEFAULT NULL,
  `loginTime` timestamp NULL DEFAULT current_timestamp(),
  `logout` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `patientslog`
--

INSERT INTO `patientslog` (`id`, `uid`, `username`, `userip`, `loginTime`, `logout`, `status`) VALUES
(115, 11, 'joan@gmail.com', 0x3a3a3100000000000000000000000000, '2022-07-16 18:58:44', '16-07-2022 11:56:19 PM', 1),
(116, 11, 'joan@gmail.com', 0x3a3a3100000000000000000000000000, '2022-07-16 21:17:22', '17-07-2022 12:56:20 AM', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payhist`
--

CREATE TABLE `payhist` (
  `id` int(11) NOT NULL,
  `amount` int(11) DEFAULT NULL,
  `userNo` varchar(30) DEFAULT NULL,
  `payNo` varchar(30) DEFAULT NULL,
  `refNo` varchar(30) DEFAULT NULL,
  `paydate` datetime DEFAULT current_timestamp(),
  `transactionNo` varchar(30) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `docId` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `MerchantRequestID` varchar(100) DEFAULT NULL,
  `CheckoutRequestID` varchar(100) DEFAULT NULL,
  `resultCode` int(11) DEFAULT NULL,
  `ResultDesc` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `docID` int(11) NOT NULL,
  `date` date NOT NULL,
  `mo1` int(11) NOT NULL DEFAULT 0,
  `mo2` int(11) NOT NULL DEFAULT 0,
  `mo3` int(11) NOT NULL DEFAULT 0,
  `mo4` int(11) NOT NULL DEFAULT 0,
  `mo5` int(11) NOT NULL DEFAULT 0,
  `mo6` int(11) NOT NULL DEFAULT 0,
  `mo7` int(11) NOT NULL DEFAULT 0,
  `mo8` int(11) NOT NULL DEFAULT 0,
  `mo9` int(11) NOT NULL DEFAULT 0,
  `mo10` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `docID`, `date`, `mo1`, `mo2`, `mo3`, `mo4`, `mo5`, `mo6`, `mo7`, `mo8`, `mo9`, `mo10`) VALUES
(34, 9, '2021-08-26', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(35, 5, '2021-08-26', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(36, 9, '2022-07-17', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminmessages`
--
ALTER TABLE `adminmessages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `adminslog`
--
ALTER TABLE `adminslog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctormessages`
--
ALTER TABLE `doctormessages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specilization` (`specilization`);

--
-- Indexes for table `doctorslog`
--
ALTER TABLE `doctorslog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctorspecilization`
--
ALTER TABLE `doctorspecilization`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specilization` (`specilization`);

--
-- Indexes for table `medhist`
--
ALTER TABLE `medhist`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patientmessages`
--
ALTER TABLE `patientmessages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patientslog`
--
ALTER TABLE `patientslog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payhist`
--
ALTER TABLE `payhist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminmessages`
--
ALTER TABLE `adminmessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `adminslog`
--
ALTER TABLE `adminslog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `doctormessages`
--
ALTER TABLE `doctormessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `doctorslog`
--
ALTER TABLE `doctorslog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `doctorspecilization`
--
ALTER TABLE `doctorspecilization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `medhist`
--
ALTER TABLE `medhist`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `patientmessages`
--
ALTER TABLE `patientmessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `patientslog`
--
ALTER TABLE `patientslog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `payhist`
--
ALTER TABLE `payhist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`specilization`) REFERENCES `doctorspecilization` (`specilization`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

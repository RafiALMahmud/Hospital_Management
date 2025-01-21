-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2025 at 05:51 PM
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
-- Database: `login_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`, `password`) VALUES
(1, 'RAFI', 'cc1af790b8e0c7460a7d41f6b57105ac04b4cac6a1edf9653bf85b006b77ee80');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `serial_number` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `counselor_name` varchar(100) DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `message` text DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`serial_number`, `patient_id`, `patient_name`, `counselor_name`, `appointment_date`, `message`, `appointment_time`, `end_time`, `doctor_id`, `phone_number`) VALUES
(30, 17, 'Suki', 'Dr. Dummy Doctor', '2025-01-15', '', '21:46:00', '22:16:00', 1, '0121546541'),
(31, 17, 'Suki', 'Dr. Dummy Doctor', '2025-01-15', '', '21:46:00', '22:16:00', 1, '0121546541'),
(32, 17, 'Suki', 'Megh', '2025-01-06', 'dd', '14:55:00', '15:25:00', 2, '0121546541'),
(33, 17, 'Suki', 'Dr. Dummy Doctor', '2025-01-15', '', '21:52:00', '22:22:00', 1, '0121546541'),
(34, 17, 'Suki', 'Dr. Dummy Doctor', '2025-01-15', '', '22:12:00', '22:42:00', 1, '0121546541');

--
-- Triggers `appointments`
--
DELIMITER $$
CREATE TRIGGER `update_end_time` BEFORE INSERT ON `appointments` FOR EACH ROW BEGIN
  SET NEW.end_time = ADDTIME(NEW.appointment_time, '00:30:00');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `bill_id` int(11) NOT NULL,
  `room_no` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `bill_amount` decimal(10,2) NOT NULL DEFAULT 1000.00,
  `bill_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`bill_id`, `room_no`, `patient_id`, `bill_amount`, `bill_date`) VALUES
(1, NULL, 14, 1000.00, '2025-01-20 17:17:41'),
(2, NULL, 14, 10000.00, '2025-01-20 17:17:58'),
(6, NULL, 14, 10000.00, '2025-01-20 17:36:19'),
(7, NULL, 14, 10000.00, '2025-01-20 17:37:57'),
(8, NULL, 14, 10000.00, '2025-01-20 17:43:41'),
(9, NULL, 14, 10000.00, '2025-01-20 17:43:43'),
(10, NULL, 14, 10000.00, '2025-01-20 17:43:52'),
(11, NULL, 14, 10000.00, '2025-01-20 17:54:34'),
(12, NULL, 14, 10000.00, '2025-01-20 18:02:53'),
(17, 123, 14, 10000.00, '2025-01-20 18:04:51'),
(18, 151, 14, 7000.00, '2025-01-20 18:06:12');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `speciality` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `user_id`, `name`, `address`, `email`, `phone_number`, `speciality`, `created_at`) VALUES
(1, 1, 'Dr. Dummy Doctor', '123 Fake Street, Dummyville', 'dummy.doctor@example.com', '9998887777', 'General Practice', '2025-01-19 14:35:56'),
(2, 25, 'Megh', 'ass', 'supermanrahman123112323@gmail.com', '018164756', 'Sex', '2025-01-20 19:09:47'),
(3, 26, 'Headsuoup', 'raier bazar', 'rafi.al.mahmud@g.bracu.ac.bd', '0181616402', 'Neuro', '2025-01-20 19:12:37');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `phone`, `address`) VALUES
(1, 'Rafi', '+8801974624144', '123 Main St'),
(2, 'Farisa', '555-0123', '123 Oak Street, Springfield'),
(3, 'Richi', '555-0456', '456 Maple Avenue, Springfield'),
(4, 'Farin', '555-0789', '789 Elm Road, Springfield'),
(5, 'Rubab', '555-1011', '321 Pine Lane, Springfield'),
(6, 'Dr. Shamim Hasan', '1234567890', '123 Main St, City'),
(7, 'Dr. Imran Azad', '1234567891', '456 Side St, City'),
(8, 'Dr. Emily Rahman', '1234567892', '789 Elm St, City'),
(9, 'Dr. Mark Johnson', '1234567893', '321 Oak St, City');

-- --------------------------------------------------------

--
-- Table structure for table `nurses`
--

CREATE TABLE `nurses` (
  `nurse_id` int(11) NOT NULL,
  `nurse_name` varchar(100) NOT NULL,
  `e_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nurses`
--

INSERT INTO `nurses` (`nurse_id`, `nurse_name`, `e_id`) VALUES
(1, 'Farisa', 2),
(2, 'Richi', 3),
(3, 'Farin', 4),
(4, 'Rubab', 5);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `name`, `age`, `user_ID`) VALUES
(1, 'alif', 70, 1),
(2, 'jj', 20, 3),
(3, 'messi', 40, 4),
(4, 'Bbg', 20, 5),
(5, 'shayonton', 20, 6),
(12, 'meraj', 26, 16),
(13, 'k', 80, 17),
(14, 'z', 26, 22),
(15, 'ka', 12, 23),
(16, 'R', 20, 24),
(17, 'Suki', 20, 29);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `name_of_the_counselor` varchar(100) DEFAULT NULL,
  `review` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `name_of_the_counselor`, `review`) VALUES
(1, 'Dr. Shamim Hasan - Gastroliver', 'he is the best');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_no` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `admitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `discharge_time` datetime DEFAULT NULL,
  `nurse_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_no`, `type`, `patient_id`, `admitted_at`, `discharge_time`, `nurse_id`) VALUES
(106, 'AC', 3, '2024-09-25 13:14:43', '2025-01-20 11:02:21', 2),
(107, 'AC', 4, '2024-09-25 13:16:19', NULL, 3),
(120, 'AC', 16, '2025-01-20 16:57:52', '2025-01-21 17:48:23', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('Admin','Patient','Doctor') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `user_name`, `password`, `user_type`) VALUES
(1, 'alif', '12', 'Patient'),
(3, 'jj', '50', 'Patient'),
(4, 'messi', '10', 'Patient'),
(5, 'Bbg', '10', 'Patient'),
(6, 'shayonton', '1234', 'Patient'),
(7, 'Name', '$2y$10$4LHeM9jVV7gKczRt9c0wv.2llv80DIix4lAnGh8r0KxchGz24mMFK', 'Patient'),
(8, 'Bbg', '$2y$10$UaXJaaO8qv8/Dh7GA1QD/O9MVGxgfDP/xs7XNEZ.AyJix/Zj9sfAG', 'Patient'),
(16, 'meraj', '$2y$10$s5gtP7cCnFBe0Uxw8Odm4e34XHHd8N./kaaF/B4VgQ.4zWEINelMq', 'Patient'),
(17, 'k', '$2y$10$NHjgAGTfoyUM1Il6JAEllOYAwp1SMWnEOyQ5bLqfG3YpPocOZIgyG', 'Patient'),
(21, 'RAFI', '$2y$10$JQiYH1drmKDxBZrKc7199./VqQMOgOsB1xd7p1qzzDMMOEzz9Hgj2', 'Admin'),
(22, 'z', '$2y$10$HRJWnffs.SETVjGjb2Ha4.35EdnricIS5HGVjvY8Z3n0Z3jBd2mum', 'Patient'),
(23, 'ka', '$2y$10$TxXxpJOoFlsHufG63AqQSuuPWM9JmF0ysHHkW4dGcaww7hmpwN/u.', 'Patient'),
(24, 'R', '$2y$10$xsSoD7u4yoHRwE28aPdxgOK4/Xy6Ox5L1fTuIjkdCb44PTyzx/NjW', 'Patient'),
(25, 'Megh', '$2y$10$UMdyPwl6LVQU9tf6n84w/OQaJEE5RBuXZczg2389xExQZgNStQfy6', 'Doctor'),
(26, 'Headsuoup', '$2y$10$6McsVkQTfA.SKqk.oASC1u4ovv1ZZtnv6WfsBWkk9k75UflsrUBte', 'Doctor'),
(27, 'Headsuoup', '$2y$10$W.jj4Fy8aa5vyZ18ZY/cpeFlNmy0mxNjwyEu3EFKpUq0Qo7r7BYvK', 'Doctor'),
(28, 'Headsuoup', '$2y$10$x2vLUAwqTliByW.rRTUMXeao809IV2lNn91m7x2P4jQS9hNTbzUnS', 'Doctor'),
(29, 'Suki', '$2y$10$Fg24E1cAmWqGkWd.PJE1H.BkD.357cwLPZJJ5du4tSUqI/rKgRoW6', 'Patient');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`serial_number`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `fk_doctor_id` (`doctor_id`);

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `room_no` (`room_no`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nurses`
--
ALTER TABLE `nurses`
  ADD PRIMARY KEY (`nurse_id`),
  ADD KEY `e_id` (`e_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_no`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `nurse_id` (`nurse_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `serial_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `nurses`
--
ALTER TABLE `nurses`
  MODIFY `nurse_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`),
  ADD CONSTRAINT `fk_doctor_id` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE SET NULL;

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`room_no`) REFERENCES `rooms` (`room_no`),
  ADD CONSTRAINT `bill_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `nurses`
--
ALTER TABLE `nurses`
  ADD CONSTRAINT `nurses_ibfk_1` FOREIGN KEY (`e_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `users` (`ID`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`),
  ADD CONSTRAINT `rooms_ibfk_2` FOREIGN KEY (`nurse_id`) REFERENCES `nurses` (`nurse_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

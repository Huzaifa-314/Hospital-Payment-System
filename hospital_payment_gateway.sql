-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2024 at 12:18 AM
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
-- Database: `hospital_payment_gateway`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `BillID` int(11) NOT NULL,
  `PatientID` int(11) DEFAULT NULL,
  `BillAmount` decimal(10,2) NOT NULL,
  `BillDate` date NOT NULL,
  `BillStatus` enum('Unpaid','Paid') NOT NULL DEFAULT 'Unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`BillID`, `PatientID`, `BillAmount`, `BillDate`, `BillStatus`) VALUES
(1, 1, 1000.00, '2024-05-01', 'Unpaid'),
(2, 2, 150.00, '2024-04-15', 'Unpaid'),
(3, 1, 100.00, '2024-05-01', 'Unpaid'),
(4, 2, 150.00, '2024-04-15', 'Unpaid'),
(9, 6, 1000.00, '2024-05-10', 'Unpaid'),
(10, 5, 4523.00, '2024-05-10', 'Paid'),
(11, 5, 100.00, '2024-05-10', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `PatientID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `ContactNumber` varchar(20) DEFAULT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `BloodGroup` varchar(5) DEFAULT NULL,
  `MedicalHistory` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`PatientID`, `UserID`, `Name`, `Address`, `ContactNumber`, `Gender`, `DateOfBirth`, `BloodGroup`, `MedicalHistory`) VALUES
(1, 1, 'John Doe', '123 Main St, Anytown, USA', '+1234567890', 'Male', '1980-01-15', 'O+', 'None'),
(2, 2, 'Jane Smith', '456 Elm St, Othertown, USA', '+0987654321', 'Female', '1975-06-20', 'A-', 'Asthma, Hypertension'),
(5, 4, 'Sohaib Islam', 'Bogra', '01774667690', 'Male', '2000-06-06', 'O-', 'Barite'),
(6, 5, 'Shamim', 'Uttara', '01968017308', 'Male', '2000-06-21', 'AB-', 'khali ghumaaaay');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `PaymentID` int(11) NOT NULL,
  `BillID` int(11) DEFAULT NULL,
  `PaymentAmount` varchar(20) NOT NULL,
  `PaymentDate` varchar(20) NOT NULL,
  `PaymentMethod` varchar(50) NOT NULL,
  `PaymentStatus` enum('Pending','Completed') NOT NULL DEFAULT 'Pending',
  `TransactionDate` varchar(20) DEFAULT NULL,
  `TransactionID` varchar(50) DEFAULT NULL,
  `StoreAmount` varchar(20) DEFAULT NULL,
  `BankTransactionID` varchar(50) DEFAULT NULL,
  `CardType` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`PaymentID`, `BillID`, `PaymentAmount`, `PaymentDate`, `PaymentMethod`, `PaymentStatus`, `TransactionDate`, `TransactionID`, `StoreAmount`, `BankTransactionID`, `CardType`) VALUES
(1, 1, '100.00', '2024-05-02', 'Credit Card', 'Completed', NULL, NULL, NULL, NULL, NULL),
(2, 2, '150.00', '2024-04-20', 'Debit Card', 'Completed', NULL, NULL, NULL, NULL, NULL),
(36, 10, '4523.00', '2024-05-10 04:10:41', 'Credit Card', 'Completed', '2024-05-10 04:10:36', 'SSLCZ_TEST_663d49dcc415f', '4409.93', '24051041039UeyxRWXw8sn7tQC', 'BKASH-BKash'),
(37, 11, '100.00', '2024-05-10 04:15:12', 'Credit Card', 'Completed', '2024-05-10 04:15:08', 'SSLCZ_TEST_663d4aecc7c0d', '97.50', '24051041510Qq8w8pNFmOr7aEg', 'BKASH-BKash');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `PasswordHash` varchar(64) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Role` enum('Patient','Admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `PasswordHash`, `Email`, `Role`) VALUES
(1, 'john_doe', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'john.doe@example.com', 'Patient'),
(2, 'jane_smith', 'fbb4a8a163ffa958b4f02bf9cabb30cfefb40de803f2c4c346a9d39b3be1b544', 'jane.smith@example.com', 'Patient'),
(3, 'Admin', '$2y$10$FE7ZBGPAPSIFQs415WDtP.lS7JgNLn11gf9EEa4DkTUwDVwGrpKqu', 'admin@gmail.com', 'Admin'),
(4, 'Huzaifa', '$2y$10$h0lzYxtV9FDvdYpYc2Fnxuaq1vzxLodGdlcn6zPo0BJ5PgyMBUiry', 'huzaifa@gmail.com', 'Patient'),
(5, 'Tanvir', '$2y$10$IshwYaDkxc32frFA5dlykuN5yqqBNeEX6C1IbLt2glPLesHTAIpp2', 'tanvir@gmail.com', 'Patient');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`BillID`),
  ADD KEY `PatientID` (`PatientID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`PatientID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `BillID` (`BillID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `BillID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `PatientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`);

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`BillID`) REFERENCES `bill` (`BillID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

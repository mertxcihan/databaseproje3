CREATE TABLE `allergies` (
  `allId` int(11) NOT NULL,
  `allergyName` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `allergies`
--

INSERT INTO `allergies` (`allId`, `allergyName`) VALUES
(1, 'Peanuts'),
(2, 'Penicillin'),
(3, 'Dust');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `departmentId` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `specialty` varchar(100) NOT NULL,
  `medManagerId` int(11) NOT NULL,
  `adManagerId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`departmentId`, `name`, `specialty`, `medManagerId`, `adManagerId`) VALUES
(1, 'Dermatology Dept', 'Dermatology', 1, 1),
(2, 'Neurology Dept', 'Neurology', 7, 1),
(3, 'Oncology Dept', 'Oncology', 15, 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `specialty` varchar(45) NOT NULL,
  `userId` int(11) NOT NULL,
  `workingIn` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`specialty`, `userId`, `workingIn`) VALUES
('Dermatology', 1, 1),
('Neurology', 6, 2),
('Neurology', 8, 2),
('Oncology', 9, 3),
('Oncology', 10, 3),
('Neurology', 11, 2),
('Neurology', 12, 2),
('Oncology', 13, 3),
('Dermatology', 14, 1),
('Oncology', 15, 3),
('Dermatology', 16, 1),
('Dermatology', 17, 1);

-- --------------------------------------------------------

--
-- Table structure for table `laboratory`
--

CREATE TABLE `laboratory` (
  `LabId` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `connectedDept` int(11) NOT NULL,
  `respDoctorID` int(11) NOT NULL,
  `respSID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `laboratory`
--

INSERT INTO `laboratory` (`LabId`, `name`, `connectedDept`, `respDoctorID`, `respSID`) VALUES
(1, 'Lab 1', 3, 9, 121),
(2, 'Lab 2', 3, 9, 121),
(3, 'Lab 3', 3, 10, 122),
(4, 'Lab 4', 2, 8, 7),
(5, 'Lab 5', 1, 1, 121),
(101, 'Main Lab', 1, 17, 7),
(102, 'Neuro Lab', 2, 6, 7);

-- --------------------------------------------------------

--
-- Table structure for table `medications`
--

CREATE TABLE `medications` (
  `medicationId` int(11) NOT NULL,
  `medicationName` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `medications`
--

INSERT INTO `medications` (`medicationId`, `medicationName`) VALUES
(301, 'Atenolol'),
(303, 'Ibuprofen'),
(302, 'Lisinopril'),
(1, 'Medication 1'),
(2, 'Medication 2'),
(3, 'Medication 3');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `birthDate` date NOT NULL,
  `gender` enum('M','F','N') DEFAULT NULL,
  `insurencePolId` varchar(10) DEFAULT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`birthDate`, `gender`, `insurencePolId`, `userId`) VALUES
('1990-05-10', 'F', 'POL1234567', 3),
('1985-07-22', 'M', 'POL7654321', 4),
('1992-08-15', 'F', 'POL9988776', 5),
('1990-01-02', 'M', 'POL0001', 101),
('1990-01-03', 'M', 'POL0002', 102),
('1990-01-04', 'M', 'POL0003', 103),
('1990-01-05', 'M', 'POL0004', 104),
('1990-01-06', 'M', 'POL0005', 105),
('1990-01-07', 'M', 'POL0006', 106),
('1990-01-08', 'M', 'POL0007', 107),
('1990-01-09', 'M', 'POL0008', 108),
('1990-01-10', 'M', 'POL0009', 109),
('1990-01-11', 'M', 'POL0010', 110),
('1990-01-12', 'M', 'POL0011', 111),
('1990-01-13', 'M', 'POL0012', 112),
('1990-01-14', 'M', 'POL0013', 113),
('1990-01-15', 'M', 'POL0014', 114),
('1990-01-16', 'M', 'POL0015', 115),
('1990-01-17', 'M', 'POL0016', 116),
('1990-01-18', 'M', 'POL0017', 117),
('1990-01-19', 'M', 'POL0018', 118),
('1990-01-20', 'M', 'POL0019', 119),
('1990-01-21', 'M', 'POL0020', 120);

-- --------------------------------------------------------

--
-- Table structure for table `patient_has_allergies`
--

CREATE TABLE `patient_has_allergies` (
  `userId` int(11) NOT NULL,
  `allId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `patient_has_allergies`
--

INSERT INTO `patient_has_allergies` (`userId`, `allId`) VALUES
(3, 1),
(4, 2),
(5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `Test_name` int(11) NOT NULL,
  `Test_doctorId` int(11) NOT NULL,
  `Test_patientId` int(11) NOT NULL,
  `Test_date` date NOT NULL,
  `Test_labId` int(11) NOT NULL,
  `medicationName` varchar(45) NOT NULL,
  `dosageInstructions` varchar(45) DEFAULT NULL,
  `prescriptionID` int(5) NOT NULL,
  `includedInReport` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `reportId` int(11) NOT NULL,
  `diagnosis` varchar(45) DEFAULT NULL,
  `billing` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`reportId`, `diagnosis`, `billing`) VALUES
(1, 'Diagnosis 1', 219),
(2, 'Diagnosis 2', 180),
(3, 'Diagnosis 3', 110),
(4, 'Diagnosis 4', 394),
(5, 'Diagnosis 5', 403),
(6, 'Diagnosis 6', 436),
(7, 'Diagnosis 7', 409),
(8, 'Diagnosis 8', 345),
(9, 'Diagnosis 9', 394),
(10, 'Diagnosis 10', 474),
(11, 'Diagnosis 11', 170),
(12, 'Diagnosis 12', 444),
(13, 'Diagnosis 13', 396),
(14, 'Diagnosis 14', 325),
(15, 'Diagnosis 15', 430),
(16, 'Diagnosis 16', 472),
(17, 'Diagnosis 17', 491),
(18, 'Diagnosis 18', 378),
(19, 'Diagnosis 19', 339),
(20, 'Diagnosis 20', 141),
(1001, 'Hypertension', 500),
(1002, 'Normal', 300),
(1003, 'Migraine', 450);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `role` varchar(45) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`role`, `userId`) VALUES
('Admin Manager', 2),
('Lab Assistant', 7),
('Lab Assistant', 121),
('Lab Assistant', 122);

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `name` int(11) NOT NULL,
  `result` varchar(45) DEFAULT NULL,
  `doctorId` int(11) NOT NULL,
  `patientId` int(11) NOT NULL,
  `date` date NOT NULL,
  `labId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) DEFAULT NULL,
  `fullName` varchar(45) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `isPatient` tinyint(4) DEFAULT NULL,
  `isDoctor` tinyint(4) DEFAULT NULL,
  `isStaff` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `email`, `password`, `fullName`, `phone`, `address`, `isPatient`, `isDoctor`, `isStaff`) VALUES
(1, 'dralice@example.com', 'pass123', 'Dr.Alice Smith', '1234567890', '123 Main St', 0, 1, 0),
(2, 'bob@example.com', 'pass456', 'Bob Johnson', '2345678901', '456 Elm St', 0, 0, 1),
(3, 'carol@example.com', 'pass789', 'Carol Lee', '3456789012', '789 Oak St', 1, 0, 0),
(4, 'david@example.com', 'pass321', 'David Kim', '4567890123', '101 Pine St', 1, 0, 0),
(5, 'emma@example.com', 'pass654', 'Emma White', '5678901234', '222 Maple St', 1, 0, 0),
(6, 'drfrank@example.com', 'pass987', 'Dr.Frank Black', '6789012345', '333 Cedar St', 0, 1, 0),
(7, 'grace@example.com', 'passabc', 'Grace Hall', '7890123456', '444 Birch St', 0, 0, 1),
(8, 'drgreg@example.com', 'pass123', 'Dr. Greg House', '555-1003', 'Street 3, City', 0, 1, 0),
(9, 'drwilson@example.com', 'pass123', 'Dr. James Wilson', '555-1004', 'Street 4, City', 0, 1, 0),
(10, 'drcam@example.com', 'pass123', 'Dr. Cameron', '555-1005', 'Street 5, City', 0, 1, 0),
(11, 'drforeman@example.com', 'pass123', 'Dr. Foreman', '555-1006', 'Street 6, City', 0, 1, 0),
(12, 'drkutner@example.com', 'pass123', 'Dr. Kutner', '555-1007', 'Street 7, City', 0, 1, 0),
(13, 'dr13@example.com', 'pass123', 'Dr. Remy Hadley', '555-1008', 'Street 8, City', 0, 1, 0),
(14, 'drtaub@example.com', 'pass123', 'Dr. Taub', '555-1009', 'Street 9, City', 0, 1, 0),
(15, 'dradams@example.com', 'pass123', 'Dr. Adams', '555-1010', 'Street 10, City', 0, 1, 0),
(16, 'drsmith@example.com', 'pass123', 'Dr. John Smith', '555-1001', 'Street 1, City', 0, 1, 0),
(17, 'drjane@example.com', 'pass123', 'Dr. Jane Doe', '555-1002', 'Street 2, City', 0, 1, 0),
(101, 'patient1@example.com', 'pass123', 'Patient 1', '555-2001', 'Street 1, City', 1, 0, 0),
(102, 'patient2@example.com', 'pass123', 'Patient 2', '555-2002', 'Street 2, City', 1, 0, 0),
(103, 'patient3@example.com', 'pass123', 'Patient 3', '555-2003', 'Street 3, City', 1, 0, 0),
(104, 'patient4@example.com', 'pass123', 'Patient 4', '555-2004', 'Street 4, City', 1, 0, 0),
(105, 'patient5@example.com', 'pass123', 'Patient 5', '555-2005', 'Street 5, City', 1, 0, 0),
(106, 'patient6@example.com', 'pass123', 'Patient 6', '555-2006', 'Street 6, City', 1, 0, 0),
(107, 'patient7@example.com', 'pass123', 'Patient 7', '555-2007', 'Street 7, City', 1, 0, 0),
(108, 'patient8@example.com', 'pass123', 'Patient 8', '555-2008', 'Street 8, City', 1, 0, 0),
(109, 'patient9@example.com', 'pass123', 'Patient 9', '555-2009', 'Street 9, City', 1, 0, 0),
(110, 'patient10@example.com', 'pass123', 'Patient 10', '555-2010', 'Street 10, City', 1, 0, 0),
(111, 'patient11@example.com', 'pass123', 'Patient 11', '555-2011', 'Street 11, City', 1, 0, 0),
(112, 'patient12@example.com', 'pass123', 'Patient 12', '555-2012', 'Street 12, City', 1, 0, 0),
(113, 'patient13@example.com', 'pass123', 'Patient 13', '555-2013', 'Street 13, City', 1, 0, 0),
(114, 'patient14@example.com', 'pass123', 'Patient 14', '555-2014', 'Street 14, City', 1, 0, 0),
(115, 'patient15@example.com', 'pass123', 'Patient 15', '555-2015', 'Street 15, City', 1, 0, 0),
(116, 'patient16@example.com', 'pass123', 'Patient 16', '555-2016', 'Street 16, City', 1, 0, 0),
(117, 'patient17@example.com', 'pass123', 'Patient 17', '555-2017', 'Street 17, City', 1, 0, 0),
(118, 'patient18@example.com', 'pass123', 'Patient 18', '555-2018', 'Street 18, City', 1, 0, 0),
(119, 'patient19@example.com', 'pass123', 'Patient 19', '555-2019', 'Street 19, City', 1, 0, 0),
(120, 'patient20@example.com', 'pass123', 'Patient 20', '555-2020', 'Street 20, City', 1, 0, 0),
(121, 'staff3@example.com', 'passabc', 'Grace Hall', '7890123453', 'Sile', 0, 0, 1),
(122, 'staff4@example.com', 'passabc', 'Grace Hall', '7890123454', 'Sile4', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `visit`
--

CREATE TABLE `visit` (
  `doctorId` int(11) NOT NULL,
  `patientId` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(45) DEFAULT NULL,
  `reportId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `visit`
--

INSERT INTO `visit` (`doctorId`, `patientId`, `date`, `status`, `reportId`) VALUES
(1, 3, '2024-06-01', 'Completed', 1001),
(1, 4, '2024-06-02', 'Completed', 1002),
(6, 5, '2024-06-03', 'Ongoing', NULL),
(6, 101, '2025-05-19', 'Completed', 1003),
(6, 104, '2025-05-16', 'Completed', 4),
(6, 107, '2025-05-13', 'Completed', 7),
(6, 109, '2025-05-11', 'Completed', 9),
(6, 114, '2025-05-06', 'Completed', 14),
(6, 116, '2025-05-04', 'Completed', 16),
(9, 120, '0000-00-00', 'Completed', 20),
(9, 120, '2025-03-30', 'cancelled', NULL),
(10, 110, '2025-05-10', 'Completed', 10),
(10, 117, '2025-04-03', 'Completed', 17),
(10, 117, '2025-05-03', 'Completed', 17),
(10, 119, '2025-05-01', 'Completed', 19),
(11, 108, '2025-05-12', 'Completed', 8),
(11, 113, '2025-05-07', 'Completed', 13),
(11, 120, '2025-04-30', 'Completed', 20),
(12, 118, '2025-05-02', 'Completed', 18),
(13, 112, '2025-05-08', 'Completed', 12),
(14, 103, '2025-05-17', 'Completed', 3),
(15, 102, '2025-05-18', 'Completed', 2),
(15, 111, '2025-05-09', 'Completed', 11),
(17, 105, '2025-05-15', 'Completed', 5),
(17, 106, '2025-05-14', 'Completed', 6),
(17, 115, '2025-05-05', 'Completed', 15);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allergies`
--
ALTER TABLE `allergies`
  ADD PRIMARY KEY (`allId`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`departmentId`),
  ADD KEY `medManagerId` (`medManagerId`),
  ADD KEY `adManagerId` (`adManagerId`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`userId`),
  ADD KEY `workingIn` (`workingIn`);

--
-- Indexes for table `laboratory`
--
ALTER TABLE `laboratory`
  ADD PRIMARY KEY (`LabId`),
  ADD KEY `respDoctorID` (`respDoctorID`),
  ADD KEY `connectedDept` (`connectedDept`),
  ADD KEY `respSID` (`respSID`);

--
-- Indexes for table `medications`
--
ALTER TABLE `medications`
  ADD PRIMARY KEY (`medicationName`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `patient_has_allergies`
--
ALTER TABLE `patient_has_allergies`
  ADD PRIMARY KEY (`userId`,`allId`),
  ADD KEY `allId` (`allId`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`prescriptionID`),
  ADD KEY `Test_name` (`Test_name`,`Test_doctorId`,`Test_patientId`,`Test_date`,`Test_labId`),
  ADD KEY `medicationName` (`medicationName`),
  ADD KEY `includedInReport` (`includedInReport`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`reportId`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`name`,`doctorId`,`patientId`,`date`,`labId`),
  ADD KEY `doctorId` (`doctorId`,`patientId`,`date`),
  ADD KEY `labId` (`labId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`doctorId`,`patientId`,`date`),
  ADD KEY `patientId` (`patientId`),
  ADD KEY `reportId` (`reportId`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`medManagerId`) REFERENCES `doctor` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `department_ibfk_2` FOREIGN KEY (`adManagerId`) REFERENCES `staff` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `doctor_ibfk_2` FOREIGN KEY (`workingIn`) REFERENCES `department` (`departmentId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `laboratory`
--
ALTER TABLE `laboratory`
  ADD CONSTRAINT `laboratory_ibfk_1` FOREIGN KEY (`respDoctorID`) REFERENCES `doctor` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `laboratory_ibfk_2` FOREIGN KEY (`connectedDept`) REFERENCES `department` (`departmentId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `laboratory_ibfk_3` FOREIGN KEY (`respSID`) REFERENCES `staff` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `patient_has_allergies`
--
ALTER TABLE `patient_has_allergies`
  ADD CONSTRAINT `patient_has_allergies_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `patient` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `patient_has_allergies_ibfk_2` FOREIGN KEY (`allId`) REFERENCES `allergies` (`allId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`Test_name`,`Test_doctorId`,`Test_patientId`,`Test_date`,`Test_labId`) REFERENCES `test` (`name`, `doctorId`, `patientId`, `date`, `labId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `prescription_ibfk_2` FOREIGN KEY (`medicationName`) REFERENCES `medications` (`medicationName`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `prescription_ibfk_3` FOREIGN KEY (`includedInReport`) REFERENCES `report` (`reportId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `test`
--
ALTER TABLE `test`
  ADD CONSTRAINT `test_ibfk_1` FOREIGN KEY (`doctorId`,`patientId`,`date`) REFERENCES `visit` (`doctorId`, `patientId`, `date`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `test_ibfk_2` FOREIGN KEY (`labId`) REFERENCES `laboratory` (`LabId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `visit`
--
ALTER TABLE `visit`
  ADD CONSTRAINT `visit_ibfk_1` FOREIGN KEY (`doctorId`) REFERENCES `doctor` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `visit_ibfk_2` FOREIGN KEY (`patientId`) REFERENCES `patient` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `visit_ibfk_3` FOREIGN KEY (`reportId`) REFERENCES `report` (`reportId`) ON DELETE NO ACTION ON UPDATE NO ACTION;
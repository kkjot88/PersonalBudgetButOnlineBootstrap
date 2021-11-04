-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2021 at 03:00 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `personal_budget`
--
CREATE DATABASE IF NOT EXISTS `personal_budget` DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci;
USE `personal_budget`;

-- --------------------------------------------------------

--
-- Table structure for table `expensecategories`
--

CREATE TABLE `expensecategories` (
  `categoryid` int(11) NOT NULL,
  `category` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `expensecategories`
--

INSERT INTO `expensecategories` (`categoryid`, `category`) VALUES
(1, 'Jedzenie'),
(2, 'Mieszkanie'),
(3, 'Transport'),
(4, 'Telekomunikacja'),
(5, 'Ubranie'),
(6, 'Dzieci'),
(7, 'Rozrywka'),
(8, 'Wycieczki'),
(9, 'Szkolenia'),
(10, 'Książki'),
(11, 'Oszczędności'),
(12, 'Emerytura'),
(13, 'Spłata długów'),
(14, 'Darowizna'),
(15, 'Inne wydatki');

-- --------------------------------------------------------

--
-- Table structure for table `expensecategories_default`
--

CREATE TABLE `expensecategories_default` (
  `categoryid` int(11) NOT NULL,
  `category` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `incomeid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `amount` decimal(65,2) NOT NULL,
  `date` date NOT NULL,
  `methodid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `comment` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`incomeid`, `userid`, `amount`, `date`, `methodid`, `categoryid`, `comment`) VALUES
(25, 1, '300.00', '2021-09-14', 3, 3, 'to tez'),
(26, 1, '555.55', '2021-11-26', 1, 1, ''),
(29, 1, '5.00', '2021-11-26', 1, 1, ''),
(30, 1, '2.00', '2021-11-26', 1, 2, ''),
(31, 1, '3.00', '2021-11-26', 1, 6, ''),
(32, 1, '1.00', '2021-11-26', 1, 1, ''),
(33, 1, '3.00', '2021-11-26', 1, 1, ''),
(34, 1, '5.00', '2021-11-26', 1, 13, '5');

-- --------------------------------------------------------

--
-- Table structure for table `incomecategories`
--

CREATE TABLE `incomecategories` (
  `categoryid` int(11) NOT NULL,
  `category` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `incomecategories`
--

INSERT INTO `incomecategories` (`categoryid`, `category`) VALUES
(1, 'Wynagrodzenie'),
(2, 'Odsetki bankowe'),
(3, 'Sprzedaż na allegro'),
(4, 'Inne');

-- --------------------------------------------------------

--
-- Table structure for table `incomecategories_default`
--

CREATE TABLE `incomecategories_default` (
  `categoryid` int(11) NOT NULL,
  `category` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `incomecategories_default`
--

INSERT INTO `incomecategories_default` (`categoryid`, `category`) VALUES
(1, 'Wynagrodzenie'),
(2, 'Odsetki bankowe'),
(3, 'Sprzedaż na allegro'),
(4, 'Inne');

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `incomeid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `amount` decimal(65,2) NOT NULL,
  `date` date NOT NULL,
  `categoryid` int(11) NOT NULL,
  `comment` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `incomes`
--

INSERT INTO `incomes` (`incomeid`, `userid`, `amount`, `date`, `categoryid`, `comment`) VALUES
(26, 1, '500.00', '2021-11-25', 1, ''),
(35, 1, '500.00', '2021-11-25', 2, ''),
(39, 1, '500.00', '2021-11-15', 3, 'najs'),
(42, 1, '600.00', '2021-12-16', 4, 'working'),
(44, 1, '5.55', '2021-11-26', 1, ''),
(45, 1, '5.00', '2021-11-26', 1, ''),
(46, 1, '3.00', '2021-11-26', 3, '3');

-- --------------------------------------------------------

--
-- Table structure for table `paymentmethods`
--

CREATE TABLE `paymentmethods` (
  `methodid` int(11) NOT NULL,
  `method` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `paymentmethods`
--

INSERT INTO `paymentmethods` (`methodid`, `method`) VALUES
(1, 'Karta kredytowa'),
(2, 'Gotówka'),
(3, 'Karta debetowa'),
(4, 'Inne'),
(5, 'TESTMETHOD');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `name` text COLLATE utf8_polish_ci NOT NULL,
  `password` text COLLATE utf8_polish_ci NOT NULL,
  `email` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `name`, `password`, `email`) VALUES
(1, 'kjot', '$2y$10$i.ILpfRcWugqR5hbEOtUUOa.hCTghRqUNEyvW49p0DqSJiQu8eIQm', 'k@j.ot'),
(2, 'ka', 'rol', 'ka@rol'),
(4, 'ka', 'rol', 'ka@rol'),
(5, 'ka', 'rol', 'ka@rol'),
(6, 'ka', 'rol', 'ka@rol'),
(9, 'ka', 'rol', 'ka@rol'),
(15, 'ka', 'rol', 'ka@rol'),
(25, 'ka', 'rol', 'ka@rol'),
(29, 'ka', 'rol', 'ka@rol'),
(30, 'ka', 'rol', 'ka@rol');

-- --------------------------------------------------------

--
-- Table structure for table `users_expensecategories`
--

CREATE TABLE `users_expensecategories` (
  `userid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `users_expensecategories`
--

INSERT INTO `users_expensecategories` (`userid`, `categoryid`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15);

-- --------------------------------------------------------

--
-- Table structure for table `users_incomecategories`
--

CREATE TABLE `users_incomecategories` (
  `userid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `users_incomecategories`
--

INSERT INTO `users_incomecategories` (`userid`, `categoryid`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users_paymentmethods`
--

CREATE TABLE `users_paymentmethods` (
  `userid` int(11) NOT NULL,
  `methodid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `users_paymentmethods`
--

INSERT INTO `users_paymentmethods` (`userid`, `methodid`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expensecategories`
--
ALTER TABLE `expensecategories`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indexes for table `expensecategories_default`
--
ALTER TABLE `expensecategories_default`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`incomeid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `categoryid` (`categoryid`),
  ADD KEY `methodid` (`methodid`);

--
-- Indexes for table `incomecategories`
--
ALTER TABLE `incomecategories`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indexes for table `incomecategories_default`
--
ALTER TABLE `incomecategories_default`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`incomeid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `categoryid` (`categoryid`);

--
-- Indexes for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  ADD PRIMARY KEY (`methodid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `users_expensecategories`
--
ALTER TABLE `users_expensecategories`
  ADD UNIQUE KEY `userid_2` (`userid`,`categoryid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `categoryid` (`categoryid`);

--
-- Indexes for table `users_incomecategories`
--
ALTER TABLE `users_incomecategories`
  ADD UNIQUE KEY `userid_2` (`userid`,`categoryid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `categoryid` (`categoryid`);

--
-- Indexes for table `users_paymentmethods`
--
ALTER TABLE `users_paymentmethods`
  ADD UNIQUE KEY `userid_2` (`userid`,`methodid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `methodid` (`methodid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expensecategories`
--
ALTER TABLE `expensecategories`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `expensecategories_default`
--
ALTER TABLE `expensecategories_default`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `incomeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `incomecategories`
--
ALTER TABLE `incomecategories`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `incomecategories_default`
--
ALTER TABLE `incomecategories_default`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `incomeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  MODIFY `methodid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `expensecategories` (`categoryid`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_ibfk_3` FOREIGN KEY (`methodid`) REFERENCES `paymentmethods` (`methodid`) ON DELETE CASCADE;

--
-- Constraints for table `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `incomes_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `incomes_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `incomecategories` (`categoryid`) ON DELETE CASCADE;

--
-- Constraints for table `users_expensecategories`
--
ALTER TABLE `users_expensecategories`
  ADD CONSTRAINT `users_expensecategories_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_expensecategories_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `expensecategories` (`categoryid`) ON DELETE CASCADE;

--
-- Constraints for table `users_incomecategories`
--
ALTER TABLE `users_incomecategories`
  ADD CONSTRAINT `users_incomecategories_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_incomecategories_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `incomecategories` (`categoryid`) ON DELETE CASCADE;

--
-- Constraints for table `users_paymentmethods`
--
ALTER TABLE `users_paymentmethods`
  ADD CONSTRAINT `users_paymentmethods_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_paymentmethods_ibfk_2` FOREIGN KEY (`methodid`) REFERENCES `paymentmethods` (`methodid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

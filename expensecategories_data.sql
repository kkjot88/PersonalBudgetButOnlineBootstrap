-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 29 Lis 2021, 23:11
-- Wersja serwera: 10.4.21-MariaDB
-- Wersja PHP: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `personal_budget`
--

--
-- Zrzut danych tabeli `expensecategories`
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
(69, 'Inne wydatki');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

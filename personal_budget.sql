-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 02 Gru 2021, 15:08
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
CREATE DATABASE IF NOT EXISTS `personal_budget` DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci;
USE `personal_budget`;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expensecategories`
--

CREATE TABLE `expensecategories` (
  `categoryid` int(11) NOT NULL,
  `category` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expensecategories`
--

INSERT INTO `expensecategories` (`categoryid`, `category`) VALUES
(70, 'Jedzenie'),
(71, 'Mieszkanie'),
(72, 'Inne wydatki');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expensecategories_default`
--

CREATE TABLE `expensecategories_default` (
  `categoryid` int(11) NOT NULL,
  `category` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expensecategories_default`
--

INSERT INTO `expensecategories_default` (`categoryid`, `category`) VALUES
(1, 'Jedzenie'),
(2, 'Mieszkanie'),
(3, 'Inne wydatki');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expenses`
--

CREATE TABLE `expenses` (
  `expenseid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `amount` decimal(65,2) NOT NULL,
  `date` date NOT NULL,
  `methodid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `comment` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expenses`
--

INSERT INTO `expenses` (`expenseid`, `userid`, `amount`, `date`, `methodid`, `categoryid`, `comment`) VALUES
(40, 239, '1.00', '2021-12-02', 16, 70, '1'),
(41, 239, '1.00', '2021-12-02', 16, 71, '1'),
(42, 239, '1.00', '2021-12-02', 16, 72, '1'),
(43, 239, '1.00', '2021-12-02', 17, 70, '1'),
(44, 239, '3.00', '2021-12-02', 19, 72, '3');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomecategories`
--

CREATE TABLE `incomecategories` (
  `categoryid` int(11) NOT NULL,
  `category` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `incomecategories`
--

INSERT INTO `incomecategories` (`categoryid`, `category`) VALUES
(56, 'Odsetki bankowe'),
(57, 'Sprzedaż na allegro'),
(58, 'Inne'),
(59, 'Wynagrodzenie');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomecategories_default`
--

CREATE TABLE `incomecategories_default` (
  `categoryid` int(11) NOT NULL,
  `category` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `incomecategories_default`
--

INSERT INTO `incomecategories_default` (`categoryid`, `category`) VALUES
(1, 'Wynagrodzenie'),
(2, 'Odsetki bankowe'),
(3, 'Sprzedaż na allegro'),
(4, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomes`
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
-- Zrzut danych tabeli `incomes`
--

INSERT INTO `incomes` (`incomeid`, `userid`, `amount`, `date`, `categoryid`, `comment`) VALUES
(51, 240, '535.00', '2021-11-29', 56, '55'),
(52, 239, '5.00', '1970-01-01', 56, '135'),
(53, 239, '531.00', '1970-01-01', 56, '13515'),
(54, 239, '111.40', '1970-01-01', 56, '34'),
(55, 239, '5.00', '2021-12-01', 56, '5'),
(56, 239, '3.00', '2021-12-01', 56, '55'),
(57, 239, '414.00', '2021-12-01', 58, ''),
(58, 239, '53.00', '2021-12-02', 56, '355'),
(59, 239, '3.00', '2021-12-02', 57, '434'),
(60, 239, '0.00', '2021-12-02', 56, ''),
(61, 239, '0.00', '2021-12-02', 56, ''),
(62, 239, '0.00', '2021-12-02', 56, ''),
(63, 239, '0.00', '2021-12-02', 56, '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `paymentmethods`
--

CREATE TABLE `paymentmethods` (
  `methodid` int(11) NOT NULL,
  `method` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `paymentmethods`
--

INSERT INTO `paymentmethods` (`methodid`, `method`) VALUES
(16, 'Karta kredytowa'),
(17, 'Gotówka'),
(18, 'Karta debetowa'),
(19, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `paymentmethods_default`
--

CREATE TABLE `paymentmethods_default` (
  `methodid` int(11) NOT NULL,
  `method` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `paymentmethods_default`
--

INSERT INTO `paymentmethods_default` (`methodid`, `method`) VALUES
(1, 'Karta kredytowa'),
(2, 'Gotówka'),
(3, 'Karta debetowa'),
(4, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `name` text COLLATE utf8_polish_ci NOT NULL,
  `password` text COLLATE utf8_polish_ci NOT NULL,
  `email` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`userid`, `name`, `password`, `email`) VALUES
(237, 'karol', '$2y$10$OfGYG/nxEbKblrvJlH5Gq.LTHc2YxEEQzLPR2IBV/f6Fveg6n/M2m', 'karol@jan.oszek'),
(238, 'kolejny', '$2y$10$UlSvam2IE8U6BnYSmkTXsu1AiFACOh4Pku8S.VsrIPekwYfGPJXyS', 'test@udany.copium'),
(239, 'kjot', '$2y$10$mCBElExNLlGaZ84BWJCVcO2436w3U7IRZ7HticeT2vUyoRaj95aJK', 'k@jot.ja'),
(240, 'teraz', '$2y$10$fCkr57Z3kjixvRnWbTytA.59B.MT3YXIL3AXuzmlujsAJAdtRsyDi', 'bd@wieksz.chyba'),
(241, 'kajot', '$2y$10$BUblNv3a5thUqIvM8Y1v3.iQBiDYMwPLFHIT7zeKFd78yD2ZYYcpS', 'jot@jot.jot');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_expensecategories`
--

CREATE TABLE `users_expensecategories` (
  `userid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users_expensecategories`
--

INSERT INTO `users_expensecategories` (`userid`, `categoryid`) VALUES
(237, 70),
(237, 71),
(237, 72),
(238, 70),
(238, 71),
(238, 72),
(239, 70),
(239, 71),
(239, 72),
(240, 70),
(240, 71),
(240, 72),
(241, 70),
(241, 71),
(241, 72);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_incomecategories`
--

CREATE TABLE `users_incomecategories` (
  `userid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users_incomecategories`
--

INSERT INTO `users_incomecategories` (`userid`, `categoryid`) VALUES
(237, 56),
(237, 57),
(237, 58),
(238, 56),
(238, 57),
(238, 58),
(239, 56),
(239, 57),
(239, 58),
(240, 56),
(240, 57),
(240, 58),
(240, 59),
(241, 56),
(241, 57),
(241, 58),
(241, 59);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_paymentmethods`
--

CREATE TABLE `users_paymentmethods` (
  `userid` int(11) NOT NULL,
  `methodid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users_paymentmethods`
--

INSERT INTO `users_paymentmethods` (`userid`, `methodid`) VALUES
(237, 16),
(237, 17),
(237, 18),
(237, 19),
(238, 16),
(238, 17),
(238, 18),
(238, 19),
(239, 16),
(239, 17),
(239, 18),
(239, 19),
(240, 16),
(240, 17),
(240, 18),
(240, 19),
(241, 16),
(241, 17),
(241, 18),
(241, 19);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `expensecategories`
--
ALTER TABLE `expensecategories`
  ADD PRIMARY KEY (`categoryid`),
  ADD UNIQUE KEY `category` (`category`) USING HASH;

--
-- Indeksy dla tabeli `expensecategories_default`
--
ALTER TABLE `expensecategories_default`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indeksy dla tabeli `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expenseid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `categoryid` (`categoryid`),
  ADD KEY `methodid` (`methodid`);

--
-- Indeksy dla tabeli `incomecategories`
--
ALTER TABLE `incomecategories`
  ADD PRIMARY KEY (`categoryid`),
  ADD UNIQUE KEY `category` (`category`) USING HASH;

--
-- Indeksy dla tabeli `incomecategories_default`
--
ALTER TABLE `incomecategories_default`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indeksy dla tabeli `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`incomeid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `categoryid` (`categoryid`);

--
-- Indeksy dla tabeli `paymentmethods`
--
ALTER TABLE `paymentmethods`
  ADD PRIMARY KEY (`methodid`),
  ADD UNIQUE KEY `method` (`method`) USING HASH;

--
-- Indeksy dla tabeli `paymentmethods_default`
--
ALTER TABLE `paymentmethods_default`
  ADD PRIMARY KEY (`methodid`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- Indeksy dla tabeli `users_expensecategories`
--
ALTER TABLE `users_expensecategories`
  ADD UNIQUE KEY `userid_2` (`userid`,`categoryid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `categoryid` (`categoryid`);

--
-- Indeksy dla tabeli `users_incomecategories`
--
ALTER TABLE `users_incomecategories`
  ADD UNIQUE KEY `userid_2` (`userid`,`categoryid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `categoryid` (`categoryid`);

--
-- Indeksy dla tabeli `users_paymentmethods`
--
ALTER TABLE `users_paymentmethods`
  ADD UNIQUE KEY `userid_2` (`userid`,`methodid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `methodid` (`methodid`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `expensecategories`
--
ALTER TABLE `expensecategories`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT dla tabeli `expensecategories_default`
--
ALTER TABLE `expensecategories_default`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expenseid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT dla tabeli `incomecategories`
--
ALTER TABLE `incomecategories`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT dla tabeli `incomecategories_default`
--
ALTER TABLE `incomecategories_default`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `incomes`
--
ALTER TABLE `incomes`
  MODIFY `incomeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT dla tabeli `paymentmethods`
--
ALTER TABLE `paymentmethods`
  MODIFY `methodid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT dla tabeli `paymentmethods_default`
--
ALTER TABLE `paymentmethods_default`
  MODIFY `methodid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `expensecategories` (`categoryid`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_ibfk_3` FOREIGN KEY (`methodid`) REFERENCES `paymentmethods` (`methodid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `incomes_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `incomes_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `incomecategories` (`categoryid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `users_expensecategories`
--
ALTER TABLE `users_expensecategories`
  ADD CONSTRAINT `users_expensecategories_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_expensecategories_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `expensecategories` (`categoryid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `users_incomecategories`
--
ALTER TABLE `users_incomecategories`
  ADD CONSTRAINT `users_incomecategories_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_incomecategories_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `incomecategories` (`categoryid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `users_paymentmethods`
--
ALTER TABLE `users_paymentmethods`
  ADD CONSTRAINT `users_paymentmethods_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_paymentmethods_ibfk_2` FOREIGN KEY (`methodid`) REFERENCES `paymentmethods` (`methodid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

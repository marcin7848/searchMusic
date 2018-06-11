-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 13 Lut 2018, 09:48
-- Wersja serwera: 10.1.30-MariaDB
-- Wersja PHP: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `searchMusic`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `login` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `accounts`
--

INSERT INTO `accounts` (`id`, `login`, `email`, `password`) VALUES
(1, 'test', 'test@test.pl', '098f6bcd4621d373cade4e832627b4f6'),
(5, 'test2', 'test2@test2.pl', 'ad0234829205b9033196ba818f7a872b');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `listTracks`
--

CREATE TABLE `listTracks` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `href` varchar(2000) COLLATE utf8_polish_ci NOT NULL,
  `name` varchar(1000) COLLATE utf8_polish_ci NOT NULL,
  `artist` varchar(1000) COLLATE utf8_polish_ci NOT NULL,
  `album` varchar(1000) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `listTracks`
--

INSERT INTO `listTracks` (`id`, `account_id`, `href`, `name`, `artist`, `album`) VALUES
(6, 5, '/music/Muse/_/Plug+In+Baby', 'Plug In Baby [3:40]', 'Muse', 'Origin of Symmetry'),
(7, 5, '/music/Muse/_/Feeling+Good', 'Feeling Good [3:19]', 'Muse', 'Origin of Symmetry'),
(12, 1, '/music/Muse/_/Plug+In+Baby', 'Plug In Baby [3:40]', 'Muse', 'Origin of Symmetry'),
(13, 1, '/music/Muse/_/Hyper+Music', 'Hyper Music [3:20]', 'Muse', 'Origin of Symmetry'),
(14, 1, '/music/Britney+Spears/_/...Baby+One+More+Time', '...Baby One More Time [3:30]', 'Britney Spears', 'Greatest Hits: My Prerogative');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `listTracks`
--
ALTER TABLE `listTracks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `listTracks`
--
ALTER TABLE `listTracks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

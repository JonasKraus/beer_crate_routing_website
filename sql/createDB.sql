/*
CREATE TABLE `beercrate_routing`.`progress` (
  `pseudonym`   VARCHAR(6)
                CHARACTER SET utf8mb4
                COLLATE utf8mb4_bin                   NOT NULL,
  `progress`    INT                                   NOT NULL,
  `dateCreated` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
  PRIMARY KEY (`pseudonym`)
)
  ENGINE = InnoDB;
*/

CREATE DATABASE `beercrate_routing`;

-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 28. Okt 2016 um 10:56
-- Server-Version: 10.1.13-MariaDB
-- PHP-Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `beercrate_routing`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `progress`
--

CREATE TABLE `progress` (
  `pseudonym` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `progress` int(11) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `subject`
--

CREATE TABLE `subject` (
  `pseudonym` varchar(6) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `progress` int(11) NOT NULL DEFAULT '0',
  `version` tinyint(1) NOT NULL,
  `code` varchar(60) DEFAULT NULL,
  `exam` float DEFAULT NULL,
  `exercise` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `progress`
--
ALTER TABLE `progress`
  ADD PRIMARY KEY (`pseudonym`);

--
-- Indizes für die Tabelle `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`pseudonym`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
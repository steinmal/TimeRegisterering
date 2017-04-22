-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 22, 2017 at 09:24 AM
-- Server version: 10.1.20-MariaDB
-- PHP Version: 7.0.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stud_v17_stople`
--

-- --------------------------------------------------------

--
-- Table structure for table `bruker`
--

CREATE TABLE `bruker` (
  `bruker_id` int(11) NOT NULL,
  `brukertype_id` int(11) NOT NULL,
  `bruker_navn` varchar(30) DEFAULT NULL,
  `bruker_epost` varchar(50) DEFAULT NULL,
  `bruker_telefon` int(11) DEFAULT NULL,
  `bruker_passord` varchar(255) DEFAULT NULL,
  `bruker_aktivert` tinyint(1) NOT NULL,
  `bruker_registreringsdato` date DEFAULT NULL,
  `bruker_aktiveringskode` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `bruker`
--

INSERT INTO `bruker` (`bruker_id`, `brukertype_id`, `bruker_navn`, `bruker_epost`, `bruker_telefon`, `bruker_passord`, `bruker_aktivert`, `bruker_registreringsdato`, `bruker_aktiveringskode`) VALUES
(1, 1, 'Admin', 'admin', 123, '$2y$10$Vf1tWKFQ.ITaqmmJdhYsJe43NRW1SdPW0Ya1yXefVTwFJass5izxi', 1, '2017-02-17', ''),
(2, 4, 'Ansatt', 'ansatt', 123, '$2y$10$yjufepgvl0T4LwM5lCyY7.WaqAatweV.yMfP0paf4n90nOlavgnM2', 1, '2017-02-21', '');

-- --------------------------------------------------------

--
-- Table structure for table `brukertype`
--

CREATE TABLE `brukertype` (
  `brukertype_id` int(11) NOT NULL,
  `brukertype_navn` varchar(30) DEFAULT NULL,
  `brukertype_teamleder` tinyint(1) NOT NULL,
  `brukertype_prosjektadmin` tinyint(1) NOT NULL,
  `brukertype_brukeradmin` tinyint(1) NOT NULL,
  `brukertype_systemadmin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `brukertype`
--

INSERT INTO `brukertype` (`brukertype_id`, `brukertype_navn`, `brukertype_teamleder`, `brukertype_prosjektadmin`, `brukertype_brukeradmin`, `brukertype_systemadmin`) VALUES
(1, 'Systemadministrator', 1, 1, 1, 1),
(2, 'Prosjektadministrator', 1, 1, 1, 0),
(3, 'Teamleder', 1, 0, 0, 0),
(4, 'Ansatt', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `fase`
--

CREATE TABLE `fase` (
  `fase_id` int(11) NOT NULL,
  `prosjekt_id` int(11) NOT NULL,
  `fase_navn` varchar(30) DEFAULT NULL,
  `fase_tilstand` varchar(20) NOT NULL DEFAULT 'Aktiv',
  `fase_startdato` date DEFAULT NULL,
  `fase_sluttdato` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `fase`
--

INSERT INTO `fase` (`fase_id`, `prosjekt_id`, `fase_navn`, `fase_tilstand`, `fase_startdato`, `fase_sluttdato`) VALUES
(1, 2, 'Demo-fase', 'Aktiv', '2017-02-21', '2017-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `forslag_tidsestimat`
--

CREATE TABLE `forslag_tidsestimat` (
  `estimat_id` int(11) NOT NULL,
  `oppgave_id` int(11) NOT NULL,
  `bruker_id` int(11) NOT NULL,
  `estimat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oppgave`
--

CREATE TABLE `oppgave` (
  `oppgave_id` int(11) NOT NULL,
  `foreldre_oppgave_id` int(11) DEFAULT NULL,
  `oppgavetype_id` int(11) NOT NULL DEFAULT '1',
  `fase_id` int(11) DEFAULT '4',
  `oppgave_navn` varchar(30) DEFAULT NULL,
  `oppgave_tidsestimat` decimal(6,1) DEFAULT NULL,
  `oppgave_periode` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `oppgave`
--

INSERT INTO `oppgave` (`oppgave_id`, `foreldre_oppgave_id`, `oppgavetype_id`, `fase_id`, `oppgave_navn`, `oppgave_tidsestimat`, `oppgave_periode`) VALUES
(1, NULL, 1, 1, 'Demo-oppgave', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `oppgavetype`
--

CREATE TABLE `oppgavetype` (
  `oppgavetype_id` int(11) NOT NULL,
  `oppgavetype_navn` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `oppgavetype`
--

INSERT INTO `oppgavetype` (`oppgavetype_id`, `oppgavetype_navn`) VALUES
(1, 'Produktiv'),
(2, 'Administrativ'),
(3, 'Research'),
(4, 'Opplæring'),
(5, 'Kaffekoking');

-- --------------------------------------------------------

--
-- Table structure for table `prosjekt`
--

CREATE TABLE `prosjekt` (
  `prosjekt_id` int(11) NOT NULL,
  `foreldre_prosjekt_id` int(11) DEFAULT '1',
  `prosjekt_navn` varchar(30) DEFAULT NULL,
  `prosjekt_leder` int(11) DEFAULT NULL,
  `prosjekt_product_owner` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `prosjekt_beskrivelse` varchar(60) DEFAULT NULL,
  `prosjekt_registreringsdato` date DEFAULT NULL,
  `prosjekt_startdato` date DEFAULT NULL,
  `prosjekt_sluttdato` date DEFAULT NULL,
  `prosjekt_arkivert` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prosjekt`
--

INSERT INTO `prosjekt` (`prosjekt_id`, `foreldre_prosjekt_id`, `prosjekt_navn`, `prosjekt_leder`, `prosjekt_product_owner`, `team_id`, `prosjekt_beskrivelse`, `prosjekt_registreringsdato`, `prosjekt_startdato`, `prosjekt_sluttdato`, `prosjekt_arkivert`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(2, 1, 'Demo-prosjekt', 1, 1, 1, 'Demo-prosjekt', '2017-02-21', '2017-02-23', '2017-02-25', 0);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `team_id` int(11) NOT NULL,
  `team_leder` int(11) NOT NULL,
  `team_navn` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`team_id`, `team_leder`, `team_navn`) VALUES
(1, 1, 'Demo-team');

-- --------------------------------------------------------

--
-- Table structure for table `teammedlemskap`
--

CREATE TABLE `teammedlemskap` (
  `bruker_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teammedlemskap`
--

INSERT INTO `teammedlemskap` (`bruker_id`, `team_id`) VALUES
(1, 1),
(2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `timeregistrering`
--

CREATE TABLE `timeregistrering` (
  `timereg_id` int(11) NOT NULL,
  `bruker_id` int(11) NOT NULL,
  `oppgave_id` int(11) NOT NULL,
  `timereg_dato` date DEFAULT NULL,
  `timereg_start` time DEFAULT NULL,
  `timereg_stopp` time DEFAULT NULL,
  `timereg_pause` int(3) NOT NULL DEFAULT '0',
  `timereg_redigeringsdato` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timereg_status` int(1) DEFAULT '3',
  `timereg_tilstand` int(1) NOT NULL,
  `timereg_aktiv` tinyint(1) NOT NULL DEFAULT '1',
  `timereg_automatisk` tinyint(1) NOT NULL,
  `timereg_godkjent` tinyint(1) NOT NULL,
  `timereg_kommentar` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bruker`
--
ALTER TABLE `bruker`
  ADD PRIMARY KEY (`bruker_id`),
  ADD KEY `fk_bruker_brukertype_idx` (`brukertype_id`);

--
-- Indexes for table `brukertype`
--
ALTER TABLE `brukertype`
  ADD PRIMARY KEY (`brukertype_id`);

--
-- Indexes for table `fase`
--
ALTER TABLE `fase`
  ADD PRIMARY KEY (`fase_id`),
  ADD KEY `fk_fase_prosjekt1_idx` (`prosjekt_id`);

--
-- Indexes for table `forslag_tidsestimat`
--
ALTER TABLE `forslag_tidsestimat`
  ADD PRIMARY KEY (`estimat_id`),
  ADD KEY `oppgave_id` (`oppgave_id`),
  ADD KEY `bruker_id` (`bruker_id`);

--
-- Indexes for table `oppgave`
--
ALTER TABLE `oppgave`
  ADD PRIMARY KEY (`oppgave_id`),
  ADD KEY `fk_oppgave_fase1_idx` (`fase_id`),
  ADD KEY `fk_oppgave_oppgavetype1_idx` (`oppgavetype_id`),
  ADD KEY `fk_oppgave_foreldreoppgave1_idx` (`foreldre_oppgave_id`);

--
-- Indexes for table `oppgavetype`
--
ALTER TABLE `oppgavetype`
  ADD PRIMARY KEY (`oppgavetype_id`);

--
-- Indexes for table `prosjekt`
--
ALTER TABLE `prosjekt`
  ADD PRIMARY KEY (`prosjekt_id`),
  ADD KEY `fk_prosjekt_bruker1_idx` (`prosjekt_leder`),
  ADD KEY `fk_prosjekt_team1_idx` (`team_id`),
  ADD KEY `fk_prosjekt_foreldreprosjekt1_idx` (`foreldre_prosjekt_id`),
  ADD KEY `fk_prosjekt_bruker2_idx` (`prosjekt_product_owner`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`team_id`),
  ADD KEY `fk_team_bruker1_idx` (`team_leder`);

--
-- Indexes for table `teammedlemskap`
--
ALTER TABLE `teammedlemskap`
  ADD PRIMARY KEY (`team_id`,`bruker_id`),
  ADD KEY `fk_teammedlemskap_team1_idx` (`team_id`),
  ADD KEY `fk_teammedlemskap_bruker1_idx` (`bruker_id`);

--
-- Indexes for table `timeregistrering`
--
ALTER TABLE `timeregistrering`
  ADD PRIMARY KEY (`timereg_id`),
  ADD KEY `fk_timeregistrering_oppgave1_idx` (`oppgave_id`),
  ADD KEY `fk_timeregistrering_bruker1_idx` (`bruker_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bruker`
--
ALTER TABLE `bruker`
  MODIFY `bruker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `brukertype`
--
ALTER TABLE `brukertype`
  MODIFY `brukertype_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `fase`
--
ALTER TABLE `fase`
  MODIFY `fase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `forslag_tidsestimat`
--
ALTER TABLE `forslag_tidsestimat`
  MODIFY `estimat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `oppgave`
--
ALTER TABLE `oppgave`
  MODIFY `oppgave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `oppgavetype`
--
ALTER TABLE `oppgavetype`
  MODIFY `oppgavetype_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `prosjekt`
--
ALTER TABLE `prosjekt`
  MODIFY `prosjekt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `timeregistrering`
--
ALTER TABLE `timeregistrering`
  MODIFY `timereg_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `bruker`
--
ALTER TABLE `bruker`
  ADD CONSTRAINT `fk_bruker_brukertype` FOREIGN KEY (`brukertype_id`) REFERENCES `brukertype` (`brukertype_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `fase`
--
ALTER TABLE `fase`
  ADD CONSTRAINT `fk_fase_prosjekt1` FOREIGN KEY (`prosjekt_id`) REFERENCES `prosjekt` (`prosjekt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `forslag_tidsestimat`
--
ALTER TABLE `forslag_tidsestimat`
  ADD CONSTRAINT `forslag_tidsestimat_ibfk_1` FOREIGN KEY (`oppgave_id`) REFERENCES `oppgave` (`oppgave_id`),
  ADD CONSTRAINT `forslag_tidsestimat_ibfk_2` FOREIGN KEY (`bruker_id`) REFERENCES `bruker` (`bruker_id`);

--
-- Constraints for table `oppgave`
--
ALTER TABLE `oppgave`
  ADD CONSTRAINT `fk_oppgave_fase` FOREIGN KEY (`fase_id`) REFERENCES `fase` (`fase_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_oppgave_foreldreoppgave` FOREIGN KEY (`foreldre_oppgave_id`) REFERENCES `oppgave` (`oppgave_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_oppgave_oppgavetype` FOREIGN KEY (`oppgavetype_id`) REFERENCES `oppgavetype` (`oppgavetype_id`);

--
-- Constraints for table `prosjekt`
--
ALTER TABLE `prosjekt`
  ADD CONSTRAINT `fk_prosjekt_bruker1` FOREIGN KEY (`prosjekt_leder`) REFERENCES `bruker` (`bruker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prosjekt_bruker2` FOREIGN KEY (`prosjekt_product_owner`) REFERENCES `bruker` (`bruker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prosjekt_foreldreprosjekt1` FOREIGN KEY (`foreldre_prosjekt_id`) REFERENCES `prosjekt` (`prosjekt_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_prosjekt_team1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `fk_team_teamleder` FOREIGN KEY (`team_leder`) REFERENCES `bruker` (`bruker_id`);

--
-- Constraints for table `teammedlemskap`
--
ALTER TABLE `teammedlemskap`
  ADD CONSTRAINT `fk_teammedlemskap_bruker1` FOREIGN KEY (`bruker_id`) REFERENCES `bruker` (`bruker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_teammedlemskap_team1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `timeregistrering`
--
ALTER TABLE `timeregistrering`
  ADD CONSTRAINT `fk_timeregistrering_bruker1` FOREIGN KEY (`bruker_id`) REFERENCES `bruker` (`bruker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_timeregistrering_oppgave1` FOREIGN KEY (`oppgave_id`) REFERENCES `oppgave` (`oppgave_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

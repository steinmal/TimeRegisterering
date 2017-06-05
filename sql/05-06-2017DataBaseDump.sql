
-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: kark.hin.no	Database: stud_v17_gruppe2
-- ------------------------------------------------------
-- Server version 	5.5.5-10.1.20-MariaDB
-- Date: Mon, 05 Jun 2017 22:39:25 +0200

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bruker`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bruker` (
  `bruker_id` int(11) NOT NULL AUTO_INCREMENT,
  `brukertype_id` int(11) NOT NULL,
  `bruker_navn` varchar(30) DEFAULT NULL,
  `bruker_epost` varchar(50) DEFAULT NULL,
  `bruker_telefon` int(11) DEFAULT NULL,
  `bruker_passord` varchar(255) DEFAULT NULL,
  `bruker_aktivert` tinyint(1) NOT NULL,
  `bruker_registreringsdato` date DEFAULT NULL,
  `bruker_aktiveringskode` varchar(40) NOT NULL,
  PRIMARY KEY (`bruker_id`),
  KEY `fk_bruker_brukertype_idx` (`brukertype_id`),
  CONSTRAINT `fk_bruker_brukertype` FOREIGN KEY (`brukertype_id`) REFERENCES `brukertype` (`brukertype_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bruker`
--

LOCK TABLES `bruker` WRITE;
/*!40000 ALTER TABLE `bruker` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `bruker` VALUES (1,1,'Admin','admin@morild.no',81549300,'$2y$10$Vf1tWKFQ.ITaqmmJdhYsJe43NRW1SdPW0Ya1yXefVTwFJass5izxi',1,'2017-02-17',''),(2,3,'Kjell Hansen','khansen@morild.no',99887766,'$2y$10$pr1YHcwVynAaK3MzdKkmruSOrKnSmq8JqBNnNqFxnwv1lJCy71zTC',1,'2017-02-21',''),(3,2,'Kari Nordmann','knordmann@morild.no',12345678,'$2y$10$86MzeVtt6krZY6WhppA.AeuU5RIL/Eq2O7JZULUN2ImlxrJj/smFW',1,'2017-02-21',''),(4,2,'Prosjektadmin2','prosjektadmin2',0,'$2y$10$86MzeVtt6krZY6WhppA.AeuU5RIL/Eq2O7JZULUN2ImlxrJj/smFW',1,'2017-02-21',''),(5,3,'Teamleder1','teamleder1',NULL,'$2y$10$86MzeVtt6krZY6WhppA.AeuU5RIL/Eq2O7JZULUN2ImlxrJj/smFW',1,'2017-02-21',''),(6,3,'Teamleder2','teamleder2',12345678,'$2y$10$86MzeVtt6krZY6WhppA.AeuU5RIL/Eq2O7JZULUN2ImlxrJj/smFW',1,'2017-02-21',''),(7,4,'Ansatt1','ansatt1',123,'$2y$10$2UNSckt9kTk8UTX6BI6LCug5wOF/Od2p78Dsvg0jfo3D69Ci7YY3i',1,'2017-02-21',''),(8,4,'Ansatt2','ansatt2',NULL,'$2y$10$86MzeVtt6krZY6WhppA.AeuU5RIL/Eq2O7JZULUN2ImlxrJj/smFW',1,'2017-02-21',''),(9,3,'Ansatt3','ansatt3',0,'$2y$10$86MzeVtt6krZY6WhppA.AeuU5RIL/Eq2O7JZULUN2ImlxrJj/smFW',1,'2017-02-21',''),(11,2,'Kenneth Johan Kristensen','chexxor@outlook.com',99644566,'$2y$10$Vf1tWKFQ.ITaqmmJdhYsJe43NRW1SdPW0Ya1yXefVTwFJass5izxi',1,'2017-03-06',''),(12,4,'test','test@test',32424,'$2y$10$Cf4Sduk7o7lsAFUDlC2qu.linGoyXtzWFSKAmlSieQiP/QW4/.jbq',1,'2017-03-06',''),(13,4,'lkj','test@test',1234,'$2y$10$/pTV5Z4nIlc5erga20Nv7unxV9baztjwxwcPHmoezbhbgXyC2YkIy',1,'2017-03-07',''),(14,4,'a','a@b.c',0,'$2y$10$vjgSAwgjRm7kMr8UXvnyqOoSCVMHIasMf0Ayt9.XucCrPmSmrJGSu',1,'2017-03-07',''),(15,2,'eirik-test1','eiriktest1@a.com',12345678,'$2y$10$u6lLG/pY0ZrAR.eiFY79YOKyLtCmz46mLivKm9Afkd1jz4QTPv8F6',1,'2017-03-07',''),(16,4,'Knut Jørgensen','kjørgensen@gmail.com',98765432,'$2y$10$tB3Wc7eIkbJ36lFO4.v1ZOyg0sHHODpoj4JAXQpkjYZwRXLBxLZki',0,'2017-03-14',''),(17,3,'Ine','i@i.i',1111,'$2y$10$GLSgvo46TQWwXTAanRwNZufEO54So2RlOeacyc8WEByRe1rPkcOGW',1,'2017-03-17',''),(18,4,'Ola Nordmann','Ola.Nordmann@Morild.no',81549300,'$2y$10$Db3dv4Ml/BoWW6YEiCSAieLl2nNe2p47ZqKOqit3q3HhWnUj5B5bm',1,'2017-03-17',''),(19,4,'Benedicte','benedicte@epost',12345,'$2y$10$K9xosPFKAywYZ4sl21rvo.6rTDAf/zki55buAq2EujM7v7x7JgFWG',1,'2017-03-19',''),(20,4,'benedicte2','benedicte@epost',1234,'$2y$10$q744Cq1VkiRItb1IoC92IuJuQGQDiH4xPnRwRSgNsBDK.YrgRUhjm',1,'2017-03-19',''),(21,4,'Steinar','s@s.com',12312345,'$2y$10$Tr.bi6GBI1bFCl4UelTRCe4M6In7Q/lRzACQJ.2nGXXBy/Y9/CRTa',1,'2017-03-19',''),(22,1,'testbruker','a@b.c',123456789,'$2y$10$xl5MQC3y35lD1T0bLQuuReENx9X4JrJeYyHRmCMsv1EouZw5BtaFm',1,'2017-03-21',''),(25,4,'aaa','steinar.malin@gmail.',99499914,'$2y$10$eqvuovNXib4peOqF6pCTr.4pSU7NI2PLZ1oFUVtows2Jz03hEC8.C',1,'2017-03-24',''),(28,3,'testbruker2000','',112,'$2y$10$3DgxGjhuy6O1cYhZVXwrH.V/H8XHlNFpKQE4uGoDjQVACmf0G34eG',1,'2017-03-27',''),(29,4,'Petter Dillesen','petter76@hotmail.no',0,'$2y$10$imwcLXk0KFB3GymKMZg5Eev/A2/XU9vBNMSDqcDRdwbY/6ZUg9/w6',0,'2017-03-29',''),(33,1,'Eirik Stople','eirik@pcfood.net',12345678,'$2y$10$TCECLCR7uuvLpWgGDTWfseLyAB8FSFlNxBhBR3VHWTI24ODeddOli',1,'2017-04-20',''),(34,4,'heihei','hei@hei.hei',123123,'$2y$10$D9LNFeoE1f0UC.fh1.zw0eN/gUa0XbRm2jBZxDkXMAFKicUwcUhdi',1,'2017-04-26',''),(35,4,'12','12@2',0,'$2y$10$ZXWcg5qioJ7.64BFXz/L8OmXpLsfhhNUx05HzriQRp4dMKylwfT/m',1,'2017-04-26',''),(36,4,'123123','12312@n',0,'$2y$10$ROsJ8Uf31xf1a3ENvuXoOubqE2AWlEGP0UpAY3jltyAWpZGkJ754i',1,'2017-04-26',''),(38,4,'Admin1112','steinar.malin@gmail.com',99499914,'$2y$10$.IAWb4jgrEPxUX3CRaIXR.7UFTsAOrAN0aS.1WEChIp32msHN87QS',1,'2017-05-04',''),(39,1,'Benedicte Karlsen','benedicte.karlsen@gmail.com',48218480,'$2y$10$/htP33/x.HVQmdMewFzelOyw8arm9K/AnrIlFuqQKX7Bt0h27Kvnq',1,'2017-05-10',''),(40,5,'Hans Richard Movik','hmovik@morild.no',77556688,'$2y$10$EdcIgB0Off4EqL/SYRRHBetIG3u8yPKLfsJCdWnX0sw22izGP7IA.',1,'2017-05-18','');
/*!40000 ALTER TABLE `bruker` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `brukertype`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brukertype` (
  `brukertype_id` int(11) NOT NULL AUTO_INCREMENT,
  `brukertype_navn` varchar(30) DEFAULT NULL,
  `brukertype_teamleder` tinyint(1) NOT NULL,
  `brukertype_prosjektadmin` tinyint(1) NOT NULL,
  `brukertype_brukeradmin` tinyint(1) NOT NULL,
  `brukertype_systemadmin` tinyint(1) NOT NULL,
  `brukertype_product_owner` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`brukertype_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brukertype`
--

LOCK TABLES `brukertype` WRITE;
/*!40000 ALTER TABLE `brukertype` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `brukertype` VALUES (1,'Systemadministrator',1,1,1,1,1),(2,'Prosjektadministrator',1,1,1,0,1),(3,'Teamleder',1,0,0,0,0),(4,'Ansatt',0,0,0,0,0),(5,'ProductOwner',0,0,0,0,1);
/*!40000 ALTER TABLE `brukertype` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `fase`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fase` (
  `fase_id` int(11) NOT NULL AUTO_INCREMENT,
  `prosjekt_id` int(11) NOT NULL,
  `fase_navn` varchar(30) DEFAULT NULL,
  `fase_tilstand` varchar(20) NOT NULL DEFAULT 'Aktiv',
  `fase_startdato` date DEFAULT NULL,
  `fase_sluttdato` date DEFAULT NULL,
  PRIMARY KEY (`fase_id`),
  KEY `fk_fase_prosjekt1_idx` (`prosjekt_id`),
  CONSTRAINT `fk_fase_prosjekt1` FOREIGN KEY (`prosjekt_id`) REFERENCES `prosjekt` (`prosjekt_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fase`
--

LOCK TABLES `fase` WRITE;
/*!40000 ALTER TABLE `fase` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `fase` VALUES (1,2,'Fase 1','Aktiv','2017-02-21','2017-02-22'),(2,3,'Retrospekt22','Aktiv','2017-03-02','2017-03-09'),(3,2,'Fase 2','Ikke påbegynt','2017-03-13','2017-04-08'),(4,3,'Sprint 2','Aktiv','2017-03-06','2017-03-26'),(10,2,'Fase 3','Aktiv','2017-03-13','2017-03-14'),(11,2,'FaseTest','Aktiv','0000-00-00','0000-00-00'),(12,2,'FasteTest2','Forsinket','0000-00-00','0000-00-00'),(15,3,'Fase sffsdfs','Aktiv','2017-12-17','2017-02-17'),(21,3,'Fastest','Ikke påbegynt','0000-00-00','0000-00-00'),(25,2,'T2','Ikke påbegynt','0000-00-00','0000-00-00'),(26,28,'Backlog','Aktiv','0000-00-00','0000-00-00'),(27,28,'Fase1','Ikke påbegynt','0000-00-00','0000-00-00'),(29,2,'test','Ikke påbegynt','0000-00-00','0000-00-00'),(33,3,'sass','Ikke påbegynt','0000-00-00','0000-00-00'),(49,42,'Backlog','Aktiv','2017-04-26','2017-04-28'),(51,14,'Backlog','Aktiv','2017-01-18','2017-04-28'),(64,14,'Sprint 5','Aktiv','2017-05-01','2017-05-14'),(66,14,'Sprint 4','Ferdig','2017-04-03','2017-04-30'),(67,54,'Backlog','Aktiv','2017-01-18','2017-06-07'),(68,55,'Backlog','Aktiv','2017-05-20','2017-05-22'),(69,56,'Backlog','Aktiv','2017-05-20','2017-05-23'),(70,57,'Backlog','Aktiv','2017-03-27','2017-07-02'),(71,58,'Backlog','Aktiv','2017-03-28','2017-04-16'),(73,60,'Backlog','Aktiv','2017-06-26','2017-07-01'),(74,59,'Sprint 1','Ferdig','2017-04-10','2017-04-23'),(75,59,'Sprint 2','Ferdig','2017-04-24','2017-05-07'),(76,59,'Sprint 3','Forsinket','2017-05-08','2017-05-21'),(77,59,'Sprint 4','Aktiv','2017-05-29','2017-06-11'),(78,59,'Sprint 5','Ikke påbegynt','2017-06-12','2017-06-25');
/*!40000 ALTER TABLE `fase` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `forslag_tidsestimat`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forslag_tidsestimat` (
  `estimat_id` int(11) NOT NULL AUTO_INCREMENT,
  `oppgave_id` int(11) NOT NULL,
  `bruker_id` int(11) NOT NULL,
  `estimat` int(11) NOT NULL,
  PRIMARY KEY (`estimat_id`),
  KEY `oppgave_id` (`oppgave_id`),
  KEY `bruker_id` (`bruker_id`),
  CONSTRAINT `forslag_tidsestimat_ibfk_1` FOREIGN KEY (`oppgave_id`) REFERENCES `oppgave` (`oppgave_id`) ON DELETE CASCADE,
  CONSTRAINT `forslag_tidsestimat_ibfk_2` FOREIGN KEY (`bruker_id`) REFERENCES `bruker` (`bruker_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forslag_tidsestimat`
--

LOCK TABLES `forslag_tidsestimat` WRITE;
/*!40000 ALTER TABLE `forslag_tidsestimat` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `forslag_tidsestimat` VALUES (6,12,1,4),(7,12,1,4),(29,19,17,11),(34,31,5,3);
/*!40000 ALTER TABLE `forslag_tidsestimat` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `oppgave`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oppgave` (
  `oppgave_id` int(11) NOT NULL AUTO_INCREMENT,
  `foreldre_oppgave_id` int(11) DEFAULT NULL,
  `oppgavetype_id` int(11) NOT NULL DEFAULT '1',
  `fase_id` int(11) DEFAULT '4',
  `oppgave_navn` varchar(30) DEFAULT NULL,
  `oppgave_tidsestimat` decimal(6,1) DEFAULT NULL,
  `oppgave_periode` int(11) DEFAULT NULL,
  `oppgave_tilstand` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`oppgave_id`),
  KEY `fk_oppgave_fase1_idx` (`fase_id`),
  KEY `fk_oppgave_oppgavetype1_idx` (`oppgavetype_id`),
  KEY `fk_oppgave_foreldreoppgave1_idx` (`foreldre_oppgave_id`),
  CONSTRAINT `fk_oppgave_fase` FOREIGN KEY (`fase_id`) REFERENCES `fase` (`fase_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_oppgave_foreldreoppgave` FOREIGN KEY (`foreldre_oppgave_id`) REFERENCES `oppgave` (`oppgave_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_oppgave_oppgavetype` FOREIGN KEY (`oppgavetype_id`) REFERENCES `oppgavetype` (`oppgavetype_id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oppgave`
--

LOCK TABLES `oppgave` WRITE;
/*!40000 ALTER TABLE `oppgave` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `oppgave` VALUES (1,NULL,2,4,'Filosofere over livet',3.0,0,'Påbegynt'),(2,NULL,1,4,'Telle sandkorn i sandkassen',99.9,1000,NULL),(4,NULL,1,4,'Test',10.0,10,NULL),(5,NULL,1,1,'TestTest',100.0,8,NULL),(7,NULL,1,4,'Lever resultater',1.0,0,NULL),(8,NULL,1,4,'',0.0,0,NULL),(9,NULL,1,4,'',0.0,0,NULL),(10,NULL,1,4,'asdsa',0.0,0,NULL),(11,NULL,1,4,'tette',0.0,0,NULL),(12,NULL,1,10,'Faseregistrering',1.0,24,NULL),(16,NULL,1,2,'sasassa',12.0,12354,NULL),(19,NULL,1,2,'<b>Hei</b>',11.0,11,NULL),(24,NULL,2,51,'Opprette sprint-backlogger',10.0,240,NULL),(27,NULL,1,64,'Rekursiv arkivering av underpr',1.0,0,NULL),(28,NULL,1,64,'Oppdeling av timereg v/midnatt',1.0,0,'Ferdig'),(29,NULL,1,64,'42 Fikse restriksjoner på rela',1.0,0,NULL),(30,NULL,1,64,'45 Code Refactor',10.0,0,NULL),(31,NULL,1,64,'24.2 TeamRapport Ansatt - Sum ',1.0,0,NULL),(32,NULL,1,51,'ÅÆØ',0.0,0,NULL),(33,NULL,1,64,'24.1 TeamRapport Ansatt - Det ',0.0,0,NULL),(34,NULL,1,51,'29.1	Videre begrensning av ret',3.0,0,NULL),(35,NULL,1,51,'30 Validering av input',16.0,0,NULL),(36,NULL,1,51,'42 Fikse restriksjoner på rela',1.0,0,NULL),(37,NULL,1,51,'45	Code Refactor',10.0,0,NULL),(38,NULL,1,64,'11.6 Timeregistrering - Muligh',1.7,0,NULL),(39,NULL,1,64,'11.7 Mulighet for manuell time',1.0,0,NULL),(40,NULL,1,64,'11.8 Timeregistreringssiden: s',2.0,0,NULL),(41,NULL,1,64,'11.9 Validering: Det skal kun ',1.0,0,NULL),(42,NULL,1,64,'11.10 Timeregistrering - Ta bo',0.5,0,NULL),(43,NULL,1,64,'12.5 Fase - Dato: Når du skal ',1.0,0,NULL),(44,NULL,1,64,'19.1.1 Begrense tidsperioden d',1.0,0,NULL),(45,NULL,1,51,'21.10 Timegodkjenning - Vis \"#',0.5,0,NULL),(46,NULL,1,51,'21.11 Timegodkjenning - Muligh',1.0,0,NULL),(47,NULL,1,64,'21.2.1 Teamleder - akseptere/f',0.5,0,NULL),(48,NULL,1,64,'21.6 Timegodkjenning - Utvidel',0.5,0,NULL),(49,NULL,1,64,'24.2 TeamRapport Ansatt - Sum ',1.0,0,NULL),(50,NULL,1,64,'24.3 TeamRapport Ansatt - Fra ',1.0,0,NULL),(51,NULL,1,64,'32.2	Gjenoppretting',1.0,0,NULL),(52,NULL,1,64,'51.1 Tilordne Product Owner ti',0.5,0,NULL),(53,NULL,1,64,'51.2 Som Prosjektleder ønsker ',2.0,0,NULL),(54,NULL,1,64,'51.3 Fikse tilgang til sidene ',1.0,0,NULL),(55,NULL,1,64,'52.1	Systemadministrering - Sy',1.0,0,NULL),(56,NULL,1,51,'53 Dele timeregistrering i to ',1.0,0,NULL),(57,NULL,1,64,'55 Underprosjekt på overstyres',1.0,0,NULL),(58,NULL,1,51,'56 Fargekoding av inputfelt me',0.5,0,NULL),(59,NULL,1,64,'57.1 En korrigert timeregistre',1.0,0,NULL),(60,NULL,1,64,'57.2 Sammenligning av korriger',1.0,0,NULL),(61,NULL,1,64,'57.3 Hvis en korrigering ikke ',1.0,0,NULL),(62,NULL,1,64,'58 Spørringer burde ta hensyn ',2.0,0,NULL),(63,NULL,1,64,'59.0 Index har timeregistrerin',3.0,0,NULL),(64,NULL,1,64,'59.1 Dashbord - Bruker',2.0,0,NULL),(65,NULL,1,64,'59.2 Dashbord - Teamleder',2.0,0,NULL),(66,NULL,1,64,'59.3 Dashbord - Prosjektleder',2.0,0,NULL),(67,NULL,1,64,'59.4 Dahbord - Product Owner',2.0,0,NULL),(68,NULL,1,64,'60.1 Forbedring - Bedre sted t',1.0,0,NULL),(69,NULL,1,64,'60.2 Forbedring - Bedre beskri',0.5,0,NULL),(70,NULL,1,64,'63 Ved endring av TeamLeder fo',1.0,0,NULL),(71,NULL,1,64,'64.1	Burnup',3.0,0,NULL),(72,NULL,1,64,'64.2	Burndown',3.0,0,NULL),(73,NULL,1,64,'65 Oppgavedetaljside ',1.0,0,NULL),(74,NULL,1,64,'66 Mulighet for å sette tilsta',1.0,0,NULL),(75,NULL,1,64,'67 Fremdriftsrapport - Status ',0.5,0,NULL),(76,NULL,1,64,'68 Når du legger til en TeamLe',0.5,0,NULL),(77,NULL,1,64,'69 Ved aktivering/deaktivering',2.0,0,NULL),(78,NULL,1,64,'71.1 Mulighet for å slette opp',1.0,0,NULL),(79,NULL,1,64,'71.3 Mulighet for å slette fas',0.5,0,NULL),(80,NULL,1,64,'71.4 Mulighet for å slette pro',0.5,0,NULL),(81,NULL,1,64,'51.4	Lettere tilgang til knapp',0.0,0,NULL),(82,NULL,1,64,'72 Slette database, og legge i',0.0,0,NULL),(83,NULL,1,64,'31 Testing og bugfiks',15.0,0,NULL),(84,NULL,2,64,'Internt møte (daily scrum etc.',0.0,0,NULL),(85,NULL,2,64,'Møte med kunde',0.0,0,NULL),(86,NULL,2,64,'Føring av timer/timeliste etc.',0.0,0,NULL),(87,NULL,2,64,'Skrive/lese referat',0.0,0,NULL),(90,NULL,1,68,'Framdrift-oppgave1',10.0,0,'Ikke-påbegynt'),(91,NULL,2,71,'Forprosjekt',195.0,0,'Ferdig'),(92,NULL,4,73,'Opplæring av kunde',15.0,0,'Ikke-påbegynt'),(93,NULL,6,73,'Installasjon av systemet',6.0,0,'Ikke-påbegynt'),(94,NULL,1,74,'Databasestruktur og data',15.0,0,'Ferdig'),(95,NULL,1,74,'Innlogging og hovedside',20.0,0,'Ferdig'),(96,NULL,1,74,'Prosjektadministrering',25.0,0,'Ferdig'),(97,NULL,1,75,'Timeregistrering',17.0,0,'Ferdig'),(98,NULL,1,75,'Teamadministrering',19.0,0,'Ferdig'),(99,NULL,1,75,'Brukerrettigheter',24.0,0,'Ferdig'),(100,NULL,1,76,'Systemadministrering',12.0,0,'Ferdig'),(101,NULL,1,76,'Rapporter/Oversikter',29.0,0,'Påbegynt'),(102,NULL,1,76,'Timeoversikt og godkjenning',19.0,0,'Ferdig');
/*!40000 ALTER TABLE `oppgave` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `oppgavetype`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oppgavetype` (
  `oppgavetype_id` int(11) NOT NULL AUTO_INCREMENT,
  `oppgavetype_navn` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`oppgavetype_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oppgavetype`
--

LOCK TABLES `oppgavetype` WRITE;
/*!40000 ALTER TABLE `oppgavetype` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `oppgavetype` VALUES (1,'Produktiv'),(2,'Administrativ'),(3,'Research'),(4,'Opplæring'),(6,'Annet');
/*!40000 ALTER TABLE `oppgavetype` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `prosjekt`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prosjekt` (
  `prosjekt_id` int(11) NOT NULL AUTO_INCREMENT,
  `foreldre_prosjekt_id` int(11) DEFAULT '1',
  `prosjekt_navn` varchar(30) DEFAULT NULL,
  `prosjekt_leder` int(11) DEFAULT NULL,
  `prosjekt_product_owner` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `prosjekt_beskrivelse` varchar(60) DEFAULT NULL,
  `prosjekt_registreringsdato` date DEFAULT NULL,
  `prosjekt_startdato` date DEFAULT NULL,
  `prosjekt_sluttdato` date DEFAULT NULL,
  `prosjekt_arkivert` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`prosjekt_id`),
  KEY `fk_prosjekt_bruker1_idx` (`prosjekt_leder`),
  KEY `fk_prosjekt_team1_idx` (`team_id`),
  KEY `fk_prosjekt_foreldreprosjekt1_idx` (`foreldre_prosjekt_id`),
  KEY `fk_prosjekt_bruker2_idx` (`prosjekt_product_owner`),
  CONSTRAINT `fk_prosjekt_bruker1` FOREIGN KEY (`prosjekt_leder`) REFERENCES `bruker` (`bruker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_prosjekt_bruker2` FOREIGN KEY (`prosjekt_product_owner`) REFERENCES `bruker` (`bruker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_prosjekt_foreldreprosjekt1` FOREIGN KEY (`foreldre_prosjekt_id`) REFERENCES `prosjekt` (`prosjekt_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_prosjekt_team1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prosjekt`
--

LOCK TABLES `prosjekt` WRITE;
/*!40000 ALTER TABLE `prosjekt` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `prosjekt` VALUES (1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(2,1,'Prosjekt2',6,2,2,'Eit nytt prosjekt, redigert','2017-02-21','2017-02-23','2017-02-25',1),(3,1,'Delprosjekt 1.1',5,1,2,'testasdf2222','2017-02-21','2017-02-23','2017-04-30',0),(14,54,'Scrum',39,40,20,'Utvikling vha. Scrum-metoden','2017-03-03','2017-02-20','2017-05-14',2),(28,1,'Steinars Prosjekt',1,NULL,NULL,'test','2017-03-27','0000-00-00','0000-00-00',1),(42,1,'Brøyte frem plen',3,NULL,2,'Rydd bort sne','2017-04-26','2017-04-26','2017-04-28',0),(54,1,'Morild data timeregistrering',11,40,20,'Prosjekt for utviklingen av system for timeregistrering i re','2017-05-18','2017-01-18','2017-07-15',1),(55,1,'test-framdriftsrapport',5,1,2,'test1','2017-05-20','2017-05-20','2017-05-22',0),(56,1,'test123',1,1,1,'','2017-05-20','2017-05-20','2017-05-23',0),(57,1,'Morild data AS - Timeregistrer',11,40,20,'Timeregistrering og prosjektledelse','2017-06-05','2017-03-27','2017-07-02',0),(58,57,'Forprosjekt',11,40,20,'Forprosjekt for timeregistreringen','2017-06-05','2017-03-28','2017-04-16',0),(59,57,'Utvikling',11,40,20,'Utvikling av product vha. Scrum','2017-06-05','2017-04-10','2017-06-25',0),(60,57,'Installasjon',11,40,20,'Installasjon av produkt og opplæring av brukere.','2017-06-05','2017-06-26','2017-07-01',0);
/*!40000 ALTER TABLE `prosjekt` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `system`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system` (
  `tidsparameter` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system`
--

LOCK TABLES `system` WRITE;
/*!40000 ALTER TABLE `system` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `system` VALUES (30);
/*!40000 ALTER TABLE `system` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `team`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team` (
  `team_id` int(11) NOT NULL AUTO_INCREMENT,
  `team_leder` int(11) NOT NULL,
  `team_navn` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`team_id`),
  KEY `fk_team_bruker1_idx` (`team_leder`),
  CONSTRAINT `fk_team_teamleder` FOREIGN KEY (`team_leder`) REFERENCES `bruker` (`bruker_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team`
--

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `team` VALUES (1,5,'Team 1'),(2,6,'Team 2'),(3,11,'Gruppe 11'),(4,2,'Eiriks Ensemblee'),(5,21,'Steinars Skvadron'),(6,5,'Benedictes Tropp'),(7,17,'Ines Band'),(8,11,'Kenneths Legion'),(9,1,'Admin1Team'),(20,17,'SysUt17 GrN2');
/*!40000 ALTER TABLE `team` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `teammedlemskap`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teammedlemskap` (
  `bruker_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  PRIMARY KEY (`team_id`,`bruker_id`),
  KEY `fk_teammedlemskap_team1_idx` (`team_id`),
  KEY `fk_teammedlemskap_bruker1_idx` (`bruker_id`),
  CONSTRAINT `fk_teammedlemskap_bruker1` FOREIGN KEY (`bruker_id`) REFERENCES `bruker` (`bruker_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_teammedlemskap_team1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teammedlemskap`
--

LOCK TABLES `teammedlemskap` WRITE;
/*!40000 ALTER TABLE `teammedlemskap` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `teammedlemskap` VALUES (21,1),(39,1),(6,2),(7,2),(11,3),(21,5),(19,6),(20,6),(8,7),(17,7),(34,7),(11,8),(39,8),(33,9),(11,20),(17,20),(21,20),(39,20);
/*!40000 ALTER TABLE `teammedlemskap` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

--
-- Table structure for table `timeregistrering`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timeregistrering` (
  `timereg_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `timereg_kommentar` varchar(200) DEFAULT NULL,
  `timereg_ordinaer` int(11) DEFAULT NULL,
  PRIMARY KEY (`timereg_id`),
  KEY `fk_timeregistrering_oppgave1_idx` (`oppgave_id`),
  KEY `fk_timeregistrering_bruker1_idx` (`bruker_id`),
  KEY `fk_timeregistrering_ordinær_idx` (`timereg_ordinaer`) USING BTREE,
  CONSTRAINT `fk_timeregistrering_bruker1` FOREIGN KEY (`bruker_id`) REFERENCES `bruker` (`bruker_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_timeregistrering_gammel` FOREIGN KEY (`timereg_ordinaer`) REFERENCES `timeregistrering` (`timereg_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `fk_timeregistrering_oppgave1` FOREIGN KEY (`oppgave_id`) REFERENCES `oppgave` (`oppgave_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=472 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timeregistrering`
--

LOCK TABLES `timeregistrering` WRITE;
/*!40000 ALTER TABLE `timeregistrering` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `timeregistrering` VALUES (3,2,2,'2017-02-22','05:00:00','06:00:00',0,'2017-02-21 22:00:00',3,0,1,0,0,NULL,NULL),(18,1,2,'2011-01-13','00:00:00','02:00:00',0,'2017-02-23 13:31:30',3,0,0,0,0,NULL,NULL),(20,1,1,'2011-01-13','00:00:00','01:00:00',0,'2017-02-23 13:33:36',3,0,0,0,0,NULL,NULL),(21,1,2,'2011-01-13','14:36:00','14:42:00',0,'2017-02-23 13:35:17',3,0,0,0,0,NULL,NULL),(22,1,2,'2011-01-13','17:24:00','17:25:00',0,'2017-02-27 16:24:53',3,0,0,1,0,NULL,NULL),(23,1,2,'2011-01-13','00:00:00','01:00:00',0,'2017-02-27 16:25:02',3,0,0,0,0,NULL,NULL),(24,1,2,'2017-02-27','18:42:00','18:44:00',0,'2017-02-27 16:42:48',3,0,0,0,0,NULL,NULL),(25,1,2,'2017-02-27','15:00:00','18:42:00',0,'2017-02-27 16:42:58',3,0,0,0,0,NULL,NULL),(26,1,2,'2017-02-28','09:04:00','09:08:00',0,'2017-02-28 07:09:14',3,0,0,0,0,NULL,NULL),(27,1,2,'2017-02-28','09:13:00','09:16:00',0,'2017-02-28 07:17:08',3,0,0,1,1,NULL,NULL),(34,1,2,'2017-02-28','12:17:00','12:19:00',0,'2017-02-28 10:17:55',3,0,1,1,0,NULL,NULL),(35,1,1,'2017-02-28','12:17:00','13:17:00',0,'2017-02-28 10:17:58',3,0,1,0,0,NULL,NULL),(36,1,2,'2017-02-28','12:18:00','12:33:00',0,'2017-02-28 10:18:39',3,0,1,0,0,NULL,NULL),(37,1,1,'2017-02-28','00:42:00','23:42:00',0,'2017-02-28 10:43:03',3,0,1,0,0,NULL,NULL),(38,1,1,'2017-02-27','01:43:00','23:43:00',0,'2017-02-28 10:43:48',3,0,1,1,0,NULL,NULL),(39,1,2,'2017-03-01','13:00:00','13:02:00',0,'2017-03-01 11:03:23',3,0,0,1,0,NULL,NULL),(40,1,5,'2017-03-01','19:35:00','21:35:00',0,'2017-03-31 10:15:32',3,0,0,0,0,NULL,NULL),(41,1,4,'2017-03-14','17:18:00','17:18:00',0,'2017-03-03 15:18:18',3,0,0,0,0,NULL,NULL),(42,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 10:17:47',3,0,0,0,0,NULL,NULL),(43,1,4,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 09:54:53',3,0,0,0,0,NULL,NULL),(44,1,2,'2017-03-03','17:25:00','17:25:00',0,'2017-03-31 10:11:23',3,0,0,0,0,NULL,NULL),(45,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 09:56:55',3,0,0,0,0,NULL,NULL),(46,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:21:29',3,0,0,0,0,NULL,NULL),(47,1,4,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:32:55',3,0,0,0,0,NULL,NULL),(48,1,5,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:08:21',3,0,0,0,0,NULL,NULL),(49,1,4,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:25:08',3,0,0,0,0,NULL,NULL),(50,1,5,'2017-03-03','17:28:00','17:28:00',0,'2017-03-03 15:28:38',3,0,1,0,0,NULL,NULL),(51,1,5,'2017-03-03','17:28:00','17:28:00',0,'2017-03-31 10:07:30',3,0,0,0,0,NULL,NULL),(52,1,4,'2017-03-03','17:29:00','17:29:00',0,'2017-03-03 15:29:20',3,0,0,0,0,NULL,NULL),(53,2,7,'2017-03-03','16:00:00','19:30:00',0,'2017-03-03 15:40:10',3,0,1,1,0,NULL,NULL),(54,2,7,'2017-03-03','17:40:00','17:40:00',0,'2017-03-03 15:44:08',3,0,1,1,1,NULL,NULL),(55,2,7,'2017-03-03','16:00:00','19:30:00',0,'2017-03-03 15:45:01',3,0,1,1,1,NULL,NULL),(56,15,2,'2017-03-07','17:47:00','17:49:00',0,'2017-03-07 15:49:42',3,0,1,1,1,NULL,NULL),(57,18,10,'2017-03-17','14:03:00','15:00:00',0,'2017-03-17 12:48:01',3,0,1,1,1,NULL,NULL),(58,1,5,'2017-03-16','14:50:00','17:55:00',0,'2017-03-17 12:50:59',3,0,1,1,1,NULL,NULL),(59,5,1,'2017-03-18','01:12:00','01:12:00',0,'2017-04-02 22:29:45',3,0,0,1,1,NULL,NULL),(60,5,5,'2017-03-18','02:04:00','06:04:00',0,'2017-03-18 00:04:43',3,0,1,0,0,NULL,NULL),(61,5,5,'2017-03-18','02:04:00','12:04:00',0,'2017-03-18 00:04:49',3,0,1,1,1,NULL,NULL),(62,7,1,'2017-03-18','10:39:00','13:39:00',0,'2017-03-18 08:39:12',3,0,1,1,1,NULL,NULL),(63,7,1,'2017-03-18','10:39:00','23:39:00',0,'2017-03-18 08:39:20',3,0,1,0,1,NULL,NULL),(64,7,5,'2017-03-19','02:16:00','06:16:00',0,'2017-03-19 00:16:41',3,0,1,0,1,NULL,NULL),(65,7,5,'2017-03-19','02:16:00','04:16:00',0,'2017-03-19 00:16:46',3,0,1,1,1,NULL,NULL),(66,8,5,'2017-03-19','02:17:00','06:17:00',0,'2017-03-19 00:17:21',3,0,0,0,1,NULL,NULL),(67,8,5,'2017-03-19','02:17:00','07:17:00',0,'2017-03-19 00:17:27',3,0,0,1,1,NULL,NULL),(68,1,1,'2017-03-19','22:44:00','22:44:00',0,'2017-03-31 09:54:20',3,0,0,1,1,NULL,NULL),(71,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 09:46:31',3,0,0,0,0,NULL,NULL),(72,17,5,'2017-03-20','08:46:00','09:46:00',0,'2017-03-20 09:46:41',3,0,0,0,0,NULL,NULL),(73,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 10:05:20',3,0,0,0,0,NULL,NULL),(74,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 10:06:29',3,0,0,0,0,NULL,NULL),(75,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 10:08:31',3,0,0,0,0,NULL,NULL),(76,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 12:23:59',3,4,0,0,0,NULL,NULL),(77,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 12:24:53',3,0,0,0,0,NULL,NULL),(78,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 12:25:14',3,0,0,0,0,NULL,NULL),(79,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 12:26:01',3,0,0,0,0,NULL,NULL),(80,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 12:26:26',3,0,0,0,0,NULL,NULL),(81,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 12:27:11',3,0,0,0,0,NULL,NULL),(82,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 12:28:07',3,0,0,0,0,NULL,NULL),(83,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 12:47:12',3,0,0,0,0,NULL,NULL),(84,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-04-05 09:29:56',3,0,0,0,0,NULL,NULL),(85,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 13:37:42',3,0,0,0,0,NULL,NULL),(86,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-04-05 09:30:29',3,0,0,0,0,NULL,NULL),(87,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 13:47:19',3,0,0,0,0,NULL,NULL),(88,17,5,'2017-03-18','11:46:00','12:46:00',0,'2017-03-20 13:48:34',3,0,1,0,0,'feil dag',NULL),(89,17,5,'2017-03-20','15:46:00','17:46:00',0,'2017-03-20 13:49:02',3,0,0,0,0,'feil tid',NULL),(90,8,5,'2017-03-19','03:17:00','06:17:00',0,'2017-03-21 11:33:34',3,0,0,0,0,'ny tid',NULL),(91,7,5,'2017-03-19','02:16:00','06:16:00',0,'2017-03-21 11:40:45',3,0,1,0,0,NULL,NULL),(92,7,5,'2017-03-19','06:16:00','12:16:00',0,'2017-03-21 11:42:08',3,0,1,0,0,'ny tid',NULL),(93,1,4,'2017-03-21','14:13:00','14:20:00',0,'2017-03-21 12:21:05',3,0,0,1,1,NULL,NULL),(94,1,1,'2018-03-19','22:44:00','22:46:00',0,'2017-03-21 12:26:54',3,0,0,1,0,'Test',NULL),(95,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-21 13:24:17',3,0,0,1,1,NULL,NULL),(96,11,1,'2017-03-22','19:47:21','20:53:54',0,'2017-03-22 18:53:54',3,0,0,0,1,NULL,NULL),(97,11,7,'2017-03-22','21:04:54','21:09:40',2,'2017-03-22 19:09:40',3,0,0,1,1,NULL,NULL),(98,11,7,'2017-03-22','21:04:54','21:39:40',0,'2017-03-22 20:42:23',3,0,0,1,1,'Kom borti knappen for tidlig',NULL),(101,8,5,'2017-03-19','10:17:00','20:17:00',0,'2017-03-23 12:14:50',3,0,1,0,0,'korrigert',NULL),(102,1,5,'2017-03-23','22:57:49','22:58:09',0,'2017-03-23 20:58:09',3,0,1,1,1,NULL,NULL),(103,1,12,'2017-03-23','22:58:50','22:59:18',0,'2017-03-23 20:59:18',3,0,1,1,1,NULL,NULL),(104,1,5,'2017-03-23','23:00:37','23:01:09',0,'2017-03-23 21:01:09',3,0,0,1,1,NULL,NULL),(105,1,5,'2017-03-23','23:01:41','23:02:17',0,'2017-03-31 11:10:06',3,0,0,1,1,NULL,NULL),(106,1,5,'2017-03-23','23:04:16','23:04:59',0,'2017-03-23 21:04:59',3,0,0,1,1,NULL,NULL),(107,8,5,'2017-03-19','02:17:00','06:17:00',0,'2017-03-24 13:10:20',3,0,0,0,0,'Endret',NULL),(108,8,5,'2017-03-19','04:17:00','11:17:00',0,'2017-03-24 13:11:11',3,0,0,0,0,'Test',NULL),(109,8,5,'2017-03-19','04:17:00','11:17:00',0,'2017-03-24 13:12:08',3,0,0,0,0,'siste test',NULL),(110,8,5,'2017-03-19','07:17:00','16:17:00',0,'2017-03-24 13:13:03',3,0,1,0,0,'test-test',NULL),(111,17,5,'2017-03-26','15:19:53','15:20:13',0,'2017-03-26 11:20:13',3,0,0,1,1,NULL,NULL),(112,11,7,'2017-03-22','21:04:54','21:09:40',0,'2017-03-27 04:27:38',3,0,0,1,1,NULL,NULL),(113,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-27 05:02:48',3,0,0,1,0,'Korrigert',NULL),(114,11,7,'2017-03-22','21:04:54','21:39:40',0,'2017-03-31 10:15:38',3,0,0,1,0,'',NULL),(115,11,11,'2017-03-21','15:23:00','20:52:16',37,'2017-03-27 05:10:32',3,0,0,1,0,'',NULL),(116,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-27 06:47:03',3,0,0,0,0,NULL,NULL),(117,17,1,'2017-03-27','10:47:40','10:47:48',0,'2017-03-27 06:47:48',3,0,1,1,1,NULL,NULL),(118,17,1,'2017-03-27','10:47:40','10:47:48',0,'2017-03-27 06:48:08',3,0,0,1,1,NULL,NULL),(119,17,5,'2017-03-26','15:19:53','15:20:13',0,'2017-03-27 07:25:44',3,0,0,1,1,NULL,NULL),(120,17,5,'2017-03-26','15:19:53','15:20:13',0,'2017-03-27 08:01:10',3,0,1,1,0,'test',NULL),(121,1,5,'2017-03-27','14:56:48','14:57:20',0,'2017-03-27 10:57:20',3,0,0,1,1,NULL,NULL),(122,11,11,'2017-03-21','15:23:00','20:52:16',37,'2017-03-28 06:53:15',3,0,0,1,0,'',NULL),(123,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-29 08:08:20',3,0,0,1,1,NULL,NULL),(124,17,2,'2017-03-29','13:18:22','15:32:53',52,'2017-04-05 06:56:05',3,0,0,1,1,NULL,NULL),(125,17,5,'2017-03-29','15:42:40','15:45:43',0,'2017-03-29 11:45:43',3,0,1,1,1,NULL,NULL),(126,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-31 09:14:27',3,0,0,1,0,NULL,NULL),(127,1,5,'2017-03-23','23:00:37','23:01:09',0,'2017-03-31 09:14:37',3,0,1,1,1,NULL,NULL),(128,1,4,'2017-03-14','17:18:00','17:18:00',0,'2017-03-31 10:05:31',3,0,0,0,0,NULL,NULL),(129,1,4,'2017-03-14','17:18:00','17:18:00',0,'2017-03-31 09:15:39',3,0,0,0,0,NULL,NULL),(130,1,5,'2017-03-27','14:56:48','14:57:20',0,'2017-03-31 09:15:55',3,0,0,1,1,NULL,NULL),(131,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-31 09:16:37',3,0,0,0,0,NULL,NULL),(132,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 09:16:49',3,0,0,1,1,NULL,NULL),(133,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 09:17:36',3,0,0,1,0,'',NULL),(134,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-31 09:17:39',3,0,0,1,0,'',NULL),(135,1,5,'2017-03-27','14:56:48','14:57:20',0,'2017-03-31 09:18:08',3,0,0,1,1,NULL,NULL),(136,1,5,'2017-03-27','14:56:48','14:57:20',0,'2017-03-31 09:18:29',3,0,0,1,1,NULL,NULL),(137,11,7,'2017-03-22','21:04:54','21:09:40',0,'2017-03-31 09:39:07',3,0,0,1,1,NULL,NULL),(138,1,2,'2017-03-01','13:00:00','13:02:00',0,'2017-03-31 09:55:58',3,0,0,1,0,NULL,NULL),(139,1,2,'2017-03-01','13:00:00','13:02:00',0,'2017-03-31 09:19:16',3,0,0,1,0,NULL,NULL),(140,17,5,'2017-03-20','15:46:00','17:46:00',0,'2017-03-31 09:19:20',3,0,1,0,0,NULL,NULL),(141,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 09:49:18',3,0,0,1,0,NULL,NULL),(142,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-31 09:21:08',3,0,0,1,0,NULL,NULL),(143,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-04-05 09:28:41',3,0,0,0,0,'',NULL),(144,1,5,'2017-03-27','14:56:48','15:57:20',0,'2017-03-31 09:56:31',3,0,0,1,0,'',NULL),(145,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-31 10:15:11',3,0,0,1,0,'',NULL),(146,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-31 09:23:37',3,0,0,1,0,NULL,NULL),(147,11,7,'2017-03-22','21:04:54','21:09:40',0,'2017-04-02 16:05:34',3,0,0,1,0,NULL,NULL),(148,11,7,'2017-03-22','21:04:54','21:09:40',0,'2017-03-31 09:39:07',3,0,0,1,0,'',NULL),(149,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 09:59:40',3,0,0,1,0,NULL,NULL),(152,1,2,'2017-03-01','13:00:00','13:02:00',0,'2017-03-31 10:06:54',3,0,0,1,0,NULL,NULL),(153,1,5,'2017-03-27','14:56:48','15:57:20',0,'2017-03-31 09:56:31',3,0,1,1,0,NULL,NULL),(154,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:50:32',3,0,0,0,0,NULL,NULL),(155,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 10:18:45',3,0,0,1,0,NULL,NULL),(156,1,4,'2017-03-14','17:18:00','17:18:00',0,'2017-03-31 10:05:09',3,0,1,0,0,NULL,NULL),(157,1,4,'2017-03-14','17:18:00','17:18:00',0,'2017-03-31 10:05:31',3,0,0,0,0,NULL,NULL),(158,1,2,'2017-03-01','13:00:00','13:02:00',0,'2017-03-31 10:06:54',3,0,1,1,0,NULL,NULL),(160,1,5,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:08:21',3,0,1,0,0,NULL,NULL),(161,1,2,'2017-03-03','17:25:00','17:25:00',0,'2017-03-31 11:10:49',3,0,0,0,0,NULL,NULL),(163,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-31 10:37:08',3,0,0,1,0,'',NULL),(165,11,7,'2017-03-22','21:04:54','21:39:40',0,'2017-04-02 16:11:25',3,0,0,1,0,'',NULL),(166,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 10:38:39',3,0,0,0,0,'',NULL),(167,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 10:20:09',3,0,0,1,0,NULL,NULL),(168,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 10:35:30',3,0,0,1,0,NULL,NULL),(169,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:32:08',3,0,0,0,0,NULL,NULL),(170,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:37:06',3,0,0,0,0,NULL,NULL),(171,1,4,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:25:08',3,0,1,0,0,'',NULL),(172,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:32:08',3,0,0,0,0,NULL,NULL),(173,1,4,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:32:54',3,0,1,0,0,NULL,NULL),(174,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 10:35:30',3,0,1,1,0,NULL,NULL),(175,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:36:11',3,0,1,0,0,NULL,NULL),(176,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:37:06',3,0,0,0,0,NULL,NULL),(177,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-04-02 16:09:30',3,0,0,1,0,'',NULL),(178,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 10:42:02',3,0,0,0,0,NULL,NULL),(179,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 10:38:39',3,0,0,0,0,NULL,NULL),(180,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 10:52:19',3,0,0,0,0,NULL,NULL),(181,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:51:20',3,0,0,0,0,NULL,NULL),(182,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 10:51:20',3,0,1,0,0,NULL,NULL),(183,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 10:56:28',3,0,0,0,0,NULL,NULL),(186,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 11:01:16',3,0,0,0,0,NULL,NULL),(191,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 11:02:01',3,0,0,0,0,NULL,NULL),(192,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 11:07:05',3,0,0,0,0,'',NULL),(193,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 11:07:36',3,0,0,0,0,NULL,NULL),(194,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 11:07:05',3,0,0,0,0,NULL,NULL),(195,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 11:08:30',3,0,0,0,0,NULL,NULL),(196,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 11:09:15',3,0,0,0,0,NULL,NULL),(197,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 11:15:45',3,0,0,0,0,NULL,NULL),(198,1,5,'2017-03-23','12:01:41','23:02:17',0,'2017-03-31 11:10:06',3,0,1,1,0,'',NULL),(199,1,2,'2017-03-03','17:25:00','17:25:00',0,'2017-03-31 11:10:49',3,0,1,0,0,NULL,NULL),(200,1,1,'2017-03-03','12:18:00','17:18:00',0,'2017-03-31 11:12:54',3,0,1,0,0,'',NULL),(201,1,1,'2017-03-03','12:18:00','17:18:00',0,'2017-03-31 11:14:04',3,0,0,0,0,'',NULL),(202,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 11:15:29',3,0,0,0,0,NULL,NULL),(203,1,1,'2017-03-03','12:18:00','17:18:00',0,'2017-03-31 11:15:45',3,0,0,0,0,'',NULL),(215,8,1,'2017-04-02','19:52:55','19:53:05',0,'2017-04-02 16:03:30',3,0,0,1,1,NULL,NULL),(217,8,1,'2017-04-02','20:28:59','20:29:03',0,'2017-04-02 16:29:03',3,0,1,1,1,NULL,NULL),(219,5,1,'2017-03-18','01:12:00','01:12:00',0,'2017-04-02 22:29:45',3,0,1,1,0,'test',NULL),(221,17,2,'2017-03-29','13:18:22','15:32:53',0,'2017-04-05 06:56:05',3,0,1,1,1,NULL,NULL),(222,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-04-05 09:28:41',3,0,1,0,0,NULL,NULL),(223,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-04-05 11:30:33',3,0,0,0,0,NULL,NULL),(224,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-04-05 09:30:29',3,0,1,0,0,NULL,NULL),(228,17,5,'2017-03-20','11:46:00','12:46:00',2,'2017-04-05 11:30:33',3,0,1,0,0,'123',NULL),(258,11,24,'2017-04-28','10:02:40','10:03:07',0,'2017-04-28 06:20:34',3,3,1,1,1,NULL,NULL),(259,11,24,'2017-04-28','10:02:40','10:57:07',45,'2017-04-28 06:20:34',3,0,1,1,0,'Kom borti knappen for tidlig',NULL),(262,11,24,'2017-04-30','09:33:06','21:50:58',0,'2017-04-30 17:50:58',3,0,1,1,1,NULL,NULL),(263,11,24,'2017-04-30','09:33:06','21:51:01',0,'2017-04-30 17:51:01',3,0,1,1,1,NULL,NULL),(286,11,24,'2017-05-04','08:12:08','08:15:12',0,'2017-05-04 04:15:12',3,0,1,1,1,NULL,NULL),(287,11,24,'2017-05-04','08:12:08','08:15:18',0,'2017-05-04 04:15:18',3,0,1,1,1,NULL,NULL),(292,11,24,'2017-05-04','13:34:15','13:48:58',0,'2017-05-04 09:48:58',3,0,1,1,1,NULL,NULL),(293,11,24,'2017-05-04','13:34:15','13:49:04',0,'2017-05-04 09:49:04',3,0,1,1,1,NULL,NULL),(306,11,24,'2017-05-04','13:58:12','13:58:55',0,'2017-05-04 09:58:55',3,0,1,1,1,NULL,NULL),(307,11,24,'2017-05-04','13:58:12','17:14:47',0,'2017-05-10 13:14:47',3,0,1,1,1,NULL,NULL),(328,11,27,'2017-05-10','12:15:18','15:44:01',1,'2017-05-11 11:44:01',3,0,1,1,1,NULL,NULL),(330,39,24,'2017-05-10','19:49:25','19:58:58',0,'2017-05-10 15:58:58',3,0,1,1,1,NULL,NULL),(331,39,24,'2017-05-10','19:49:25','19:59:04',0,'2017-05-21 18:07:28',3,3,1,1,1,NULL,NULL),(332,5,1,'2017-05-10','20:08:18','20:09:08',0,'2017-05-13 15:22:13',3,3,1,1,1,NULL,NULL),(333,5,1,'2017-05-10','20:08:18','20:09:26',0,'2017-05-10 16:09:26',3,0,1,1,1,NULL,NULL),(334,39,27,'2017-05-10','21:25:46','21:25:54',0,'2017-05-10 17:25:54',3,0,1,1,1,NULL,NULL),(336,5,7,'2017-05-10','22:37:23','22:37:27',0,'2017-05-13 15:22:19',3,3,1,1,1,NULL,NULL),(337,5,7,'2017-05-10','22:37:23','22:37:30',0,'2017-05-13 15:22:22',3,3,1,1,1,NULL,NULL),(338,5,1,'2017-05-10','22:38:18','22:38:23',0,'2017-05-13 15:22:26',3,3,1,1,1,NULL,NULL),(339,5,1,'2017-05-10','22:38:18','22:38:25',0,'2017-05-13 15:22:47',3,3,1,1,1,NULL,NULL),(340,5,7,'2017-05-10','22:44:04','22:44:15',0,'2017-05-13 15:22:44',3,3,1,1,1,NULL,NULL),(341,5,7,'2017-05-10','22:44:04','22:44:29',0,'2017-05-13 15:22:42',3,3,1,1,1,NULL,NULL),(342,5,5,'2017-05-11','04:10:08','04:10:31',0,'2017-05-13 15:22:39',3,3,1,1,1,NULL,NULL),(343,5,5,'2017-05-11','04:10:08','04:10:35',0,'2017-05-13 15:22:36',3,3,1,1,1,NULL,NULL),(376,11,27,'2017-05-11','16:11:44','16:11:51',0,'2017-05-11 12:11:51',3,0,1,1,1,NULL,NULL),(377,11,27,'2017-05-11','16:11:44','16:11:56',0,'2017-05-11 12:11:56',3,0,1,1,1,NULL,NULL),(378,1,1,'2017-05-12','08:44:52','08:45:01',0,'2017-05-12 04:45:01',3,0,1,1,1,NULL,NULL),(379,1,1,'2017-05-12','08:44:52','08:45:08',0,'2017-05-12 04:45:08',3,0,1,1,1,NULL,NULL),(380,1,1,'2017-05-12','08:49:37','08:50:37',0,'2017-05-12 04:50:37',3,0,1,1,1,NULL,NULL),(381,1,1,'2017-05-12','08:49:37','08:52:56',0,'2017-05-12 04:52:56',3,0,1,1,1,NULL,NULL),(384,1,1,'2017-05-12','09:03:46','09:37:13',0,'2017-05-12 05:37:13',3,0,1,1,1,NULL,NULL),(385,1,1,'2017-05-12','09:03:46','09:37:22',0,'2017-05-12 05:37:22',3,0,1,1,1,NULL,NULL),(386,1,1,'2017-05-12','09:42:01','09:45:55',0,'2017-05-12 05:45:55',3,0,1,1,1,NULL,NULL),(387,1,1,'2017-05-12','09:42:01','09:46:02',0,'2017-05-12 05:46:02',3,0,1,1,1,NULL,NULL),(388,1,1,'2017-05-12','09:47:22','09:47:34',0,'2017-05-12 05:47:34',3,0,1,1,1,NULL,NULL),(389,1,1,'2017-05-12','09:47:22','09:47:37',0,'2017-05-12 05:47:37',3,0,1,1,1,NULL,NULL),(394,1,1,'2017-05-12','10:20:06','10:20:58',0,'2017-05-12 06:20:58',3,0,1,1,1,NULL,NULL),(395,1,1,'2017-05-12','10:20:06','10:21:05',0,'2017-05-12 06:21:05',3,0,1,1,1,NULL,NULL),(396,1,1,'2017-05-12','10:22:24','10:22:28',0,'2017-05-12 06:22:28',3,0,1,1,1,NULL,NULL),(397,1,1,'2017-05-12','10:28:08','10:33:49',0,'2017-05-12 06:33:49',3,0,1,1,1,NULL,NULL),(398,1,1,'2017-05-12','10:36:12','10:39:43',0,'2017-05-12 06:39:43',3,0,1,1,1,NULL,NULL),(399,1,1,'2017-05-12','10:56:25','11:13:43',0,'2017-05-12 07:13:43',3,0,1,1,1,NULL,NULL),(407,17,2,'2017-05-12','14:08:56','14:08:58',0,'2017-05-12 10:08:58',3,0,1,1,1,NULL,NULL),(408,17,2,'2017-05-12','14:10:57','14:11:02',0,'2017-05-12 10:11:02',3,0,1,1,1,NULL,NULL),(411,11,27,'2017-05-13','00:56:35','02:08:17',0,'2017-05-12 22:08:17',3,0,1,1,1,NULL,NULL),(412,11,28,'2017-05-12','02:13:57','04:51:51',0,'2017-05-13 00:51:51',3,0,1,1,1,NULL,NULL),(413,39,27,'2017-05-13','05:25:54','05:42:54',0,'2017-05-13 01:26:16',3,1,1,0,0,'Kommentar',NULL),(414,39,30,'2017-05-13','05:26:54','05:38:32',0,'2017-05-13 01:38:32',3,0,1,1,1,NULL,NULL),(415,39,30,'2017-05-12','05:39:09','05:39:18',0,'2017-05-13 01:39:18',3,0,1,1,1,NULL,NULL),(416,11,28,'2017-05-12','06:50:02','23:59:59',0,'2017-05-13 03:24:27',3,0,1,1,0,NULL,NULL),(417,11,28,'2017-05-10','00:00:00','23:59:59',2,'2017-05-13 03:27:43',3,0,1,1,0,NULL,NULL),(418,11,28,'2017-05-11','00:00:00','23:59:59',0,'2017-05-13 03:27:43',3,0,1,1,1,NULL,NULL),(419,11,28,'2017-05-12','00:00:00','23:59:59',0,'2017-05-13 03:27:43',3,0,1,1,1,NULL,NULL),(420,11,28,'2017-05-13','00:00:00','07:27:43',0,'2017-05-13 03:27:43',3,0,1,1,1,NULL,NULL),(423,1,1,'2017-05-13','14:19:29','14:19:30',0,'2017-05-13 10:19:30',3,0,1,1,1,NULL,NULL),(424,1,1,'2017-05-13','15:37:40','15:37:43',0,'2017-05-13 11:37:43',3,0,1,1,1,NULL,NULL),(425,1,1,'2017-05-13','15:54:51','15:54:53',0,'2017-05-13 11:54:53',3,0,1,1,1,NULL,NULL),(426,17,1,'2017-05-13','16:35:04','16:35:18',0,'2017-05-13 12:35:18',3,0,1,1,1,NULL,NULL),(428,17,1,'2017-05-13','16:56:11','16:56:15',0,'2017-05-13 12:56:15',3,0,1,1,1,NULL,NULL),(430,17,1,'2017-05-13','17:04:03','17:13:44',0,'2017-05-13 13:13:44',3,0,1,1,1,NULL,NULL),(432,17,1,'2017-05-13','17:18:12','17:18:17',0,'2017-05-13 13:18:17',3,0,1,1,1,NULL,NULL),(433,5,31,'2017-05-13','19:11:13','19:21:51',0,'2017-05-13 15:21:51',3,0,1,1,1,NULL,NULL),(434,33,81,'2017-05-04','10:35:30','12:00:30',0,'2017-05-14 08:46:04',3,0,1,0,0,'Fikse gui-knapper på prosjektadmin/detaljer 51.4',NULL),(435,33,81,'2017-05-14','12:46:25','12:46:28',0,'2017-05-14 08:56:49',3,3,1,1,1,NULL,NULL),(436,33,81,'2017-05-04','12:00:27','12:25:27',0,'2017-05-14 08:49:20',3,0,1,0,0,'Fikse gui-knapper på teamredigering 51.4',NULL),(437,33,83,'2017-05-04','12:25:52','12:35:52',0,'2017-05-14 08:50:22',3,0,1,0,0,'Bugfiks, teamleder skal bruke brukertype-tabell',NULL),(438,33,81,'2017-05-04','12:35:39','12:40:39',0,'2017-05-14 08:51:04',3,0,1,0,0,'Fjerna parent på prosjektadministrering 51.4',NULL),(439,33,84,'2017-05-04','13:00:40','13:25:40',0,'2017-05-14 09:02:30',3,0,1,0,0,'Daily scrum + sprint retrospect',NULL),(440,33,83,'2017-05-04','13:25:06','14:00:06',0,'2017-05-14 09:03:45',3,0,1,0,0,'Testing, bugfix: stopp-knapp fungerer ikkje lenger',NULL),(441,33,85,'2017-05-04','14:00:53','15:05:53',0,'2017-05-14 13:41:20',3,0,1,0,0,'Møte med kunde',NULL),(442,33,46,'2017-05-04','16:00:32','17:20:32',0,'2017-05-14 13:42:31',3,0,1,0,0,'Checkbox for å godkjenne mange',NULL),(443,33,46,'2017-05-06','10:45:50','11:10:50',0,'2017-05-14 13:43:19',3,0,1,0,0,'Checkbox for å godkjenne mange 21.11',NULL),(444,33,51,'2017-05-06','11:10:59','12:30:59',0,'2017-05-14 13:44:21',3,0,1,0,0,'DB-gjenoppretting + port til php7 32.2',NULL),(445,33,50,'2017-05-06','12:30:29','12:35:29',0,'2017-05-14 13:46:12',3,0,1,0,0,'Datofilter på teamrapport 24.3',NULL),(446,33,50,'2017-05-06','13:55:22','14:20:22',0,'2017-05-14 13:46:50',3,0,1,0,0,'Datofilter på teamrapport',NULL),(447,33,71,'2017-05-06','14:25:10','19:10:10',0,'2017-05-14 13:47:35',3,0,1,0,0,'Legge inn burnup-diagram 64.1',NULL),(448,33,81,'2017-05-07','22:50:08','23:35:08',0,'2017-05-14 13:48:32',3,0,1,0,0,'Prøve å unngå linjeskift på fleire knapper 51.4',NULL),(449,33,45,'2017-05-11','12:35:55','13:30:55',0,'2017-05-14 13:49:17',3,0,1,0,0,'Timegodkjenning - \"# venter på godkj.\" 21.10',NULL),(450,33,83,'2017-05-11','13:30:28','14:40:28',0,'2017-05-14 13:49:50',3,0,1,0,0,'Bugfiks, forbedre timeregistrering',NULL),(451,33,84,'2017-05-11','17:00:00','17:40:00',0,'2017-05-14 13:50:21',3,0,1,0,0,'Daily scrum',NULL),(452,33,84,'2017-05-13','14:00:36','16:00:36',0,'2017-05-14 13:50:55',3,0,1,0,0,'Team - arbeidsmøte',NULL),(453,33,87,'2017-05-13','16:00:35','16:30:35',0,'2017-05-14 13:56:20',3,0,1,0,0,'Skrive referat',NULL),(454,33,87,'2017-05-13','18:50:31','19:15:31',0,'2017-05-14 13:57:04',3,0,1,0,0,'Skrive referat, lese referat for godkjenning',NULL),(455,33,83,'2017-05-13','19:35:16','19:45:16',0,'2017-05-14 13:57:36',3,0,1,0,0,'Bugfiks, fikse tabell i prosjektdetaljer',NULL),(456,33,86,'2017-05-14','12:05:52','13:05:52',0,'2017-05-14 13:58:11',3,0,1,0,0,'Registrere prosjektet vårt, føre mine timar',NULL),(457,33,84,'2017-05-14','14:00:23','15:10:23',0,'2017-05-14 13:58:39',3,0,1,0,0,'Sprint retrospect meeting',NULL),(458,33,49,'2017-05-14','15:10:03','15:35:03',0,'2017-05-14 13:59:22',3,0,1,0,0,'Legge inn form submit på sum av timer 24.2',NULL),(459,33,86,'2017-05-14','17:00:33','17:15:33',0,'2017-05-14 13:59:49',3,0,1,0,0,'Føre timer',NULL),(460,33,86,'2017-05-14','17:35:08','18:00:08',0,'2017-05-14 14:00:27',3,0,1,0,0,'Føre timer inn på nettsida',NULL),(468,1,1,'2017-06-02','13:34:27','13:34:38',0,'2017-06-02 11:34:38',3,0,1,1,1,NULL,NULL),(469,21,34,'2017-06-05','17:41:25','17:42:39',0,'2017-06-05 15:43:57',3,3,1,1,1,NULL,NULL),(470,21,34,'2017-06-05','17:41:25','17:42:39',0,'2017-06-05 15:43:57',3,1,1,1,0,'en redigering',469),(471,21,36,'2017-06-05','05:44:44','06:44:44',10,'2017-06-05 15:44:54',3,1,1,0,0,'manuell',NULL);
/*!40000 ALTER TABLE `timeregistrering` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on: Mon, 05 Jun 2017 22:39:29 +0200

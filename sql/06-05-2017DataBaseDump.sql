
-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: kark.hin.no	Database: stud_v17_gruppe2
-- ------------------------------------------------------
-- Server version 	5.5.5-10.1.20-MariaDB
-- Date: Sat, 06 May 2017 11:24:49 +0200

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
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bruker`
--

LOCK TABLES `bruker` WRITE;
/*!40000 ALTER TABLE `bruker` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `bruker` VALUES (1,1,'Admin1','admin1',123,'$2y$10$Vf1tWKFQ.ITaqmmJdhYsJe43NRW1SdPW0Ya1yXefVTwFJass5izxi',1,'2017-02-17',''),(2,3,'Admin3','admin3',123,'$2y$10$pr1YHcwVynAaK3MzdKkmruSOrKnSmq8JqBNnNqFxnwv1lJCy71zTC',1,'2017-02-21',''),(3,2,'Prosjektadmin1','prosjektadmin1',0,'$2y$10$86MzeVtt6krZY6WhppA.AeuU5RIL/Eq2O7JZULUN2ImlxrJj/smFW',1,'2017-02-21',''),(4,2,'Prosjektadmin2','prosjektadmin2',0,'$2y$10$86MzeVtt6krZY6WhppA.AeuU5RIL/Eq2O7JZULUN2ImlxrJj/smFW',1,'2017-02-21',''),(5,3,'Teamleder1','teamleder1',NULL,'$2y$10$86MzeVtt6krZY6WhppA.AeuU5RIL/Eq2O7JZULUN2ImlxrJj/smFW',1,'2017-02-21',''),(6,3,'Teamleder2','teamleder2',12345678,'$2y$10$86MzeVtt6krZY6WhppA.AeuU5RIL/Eq2O7JZULUN2ImlxrJj/smFW',1,'2017-02-21',''),(7,4,'Ansatt1','ansatt1',123,'$2y$10$2UNSckt9kTk8UTX6BI6LCug5wOF/Od2p78Dsvg0jfo3D69Ci7YY3i',1,'2017-02-21',''),(8,4,'Ansatt2','ansatt2',NULL,'$2y$10$86MzeVtt6krZY6WhppA.AeuU5RIL/Eq2O7JZULUN2ImlxrJj/smFW',1,'2017-02-21',''),(9,3,'Ansatt3','ansatt3',0,'$2y$10$86MzeVtt6krZY6WhppA.AeuU5RIL/Eq2O7JZULUN2ImlxrJj/smFW',1,'2017-02-21',''),(11,2,'Kenneth Johan Kristensen','chexxor@outlook.com',99644566,'$2y$10$2/BI.qIW/hbjOapJHl3JtuN4jknxCMz3TW4HPaih2yrj/KPpmt3Qi',1,'2017-03-06',''),(12,4,'test','test@test',32424,'$2y$10$Cf4Sduk7o7lsAFUDlC2qu.linGoyXtzWFSKAmlSieQiP/QW4/.jbq',1,'2017-03-06',''),(13,4,'lkj','test@test',1234,'$2y$10$/pTV5Z4nIlc5erga20Nv7unxV9baztjwxwcPHmoezbhbgXyC2YkIy',1,'2017-03-07',''),(14,4,'a','a@b.c',0,'$2y$10$vjgSAwgjRm7kMr8UXvnyqOoSCVMHIasMf0Ayt9.XucCrPmSmrJGSu',1,'2017-03-07',''),(15,2,'eirik-test1','eiriktest1@a.com',12345678,'$2y$10$u6lLG/pY0ZrAR.eiFY79YOKyLtCmz46mLivKm9Afkd1jz4QTPv8F6',1,'2017-03-07',''),(16,4,'IkkeGodkjent','IkkeGodkjent@a',123,'$2y$10$tB3Wc7eIkbJ36lFO4.v1ZOyg0sHHODpoj4JAXQpkjYZwRXLBxLZki',0,'2017-03-14',''),(17,1,'Ine','i@i.i',1111,'$2y$10$GLSgvo46TQWwXTAanRwNZufEO54So2RlOeacyc8WEByRe1rPkcOGW',1,'2017-03-17',''),(18,4,'Ola Nordmann','Ola.Nordmann@Morild.no',81549300,'$2y$10$Db3dv4Ml/BoWW6YEiCSAieLl2nNe2p47ZqKOqit3q3HhWnUj5B5bm',0,'2017-03-17',''),(19,4,'Benedicte','benedicte@epost',12345,'$2y$10$K9xosPFKAywYZ4sl21rvo.6rTDAf/zki55buAq2EujM7v7x7JgFWG',1,'2017-03-19',''),(20,4,'benedicte2','benedicte@epost',1234,'$2y$10$q744Cq1VkiRItb1IoC92IuJuQGQDiH4xPnRwRSgNsBDK.YrgRUhjm',1,'2017-03-19',''),(21,4,'Steinar','s@s.com',12312345,'$2y$10$kCpPIPtDxakAtGwOhhK5D.kB.1MxdMX6vspEMKoAVYmPFwl6l9/ei',0,'2017-03-19',''),(22,1,'testbruker','a@b.c',123456789,'$2y$10$xl5MQC3y35lD1T0bLQuuReENx9X4JrJeYyHRmCMsv1EouZw5BtaFm',1,'2017-03-21',''),(25,4,'aaa','steinar.malin@gmail.',99499914,'$2y$10$eqvuovNXib4peOqF6pCTr.4pSU7NI2PLZ1oFUVtows2Jz03hEC8.C',1,'2017-03-24',''),(28,3,'testbruker2000','',112,'$2y$10$3DgxGjhuy6O1cYhZVXwrH.V/H8XHlNFpKQE4uGoDjQVACmf0G34eG',1,'2017-03-27',''),(29,4,'nybruker','ny@bruker.test',0,'$2y$10$imwcLXk0KFB3GymKMZg5Eev/A2/XU9vBNMSDqcDRdwbY/6ZUg9/w6',0,'2017-03-29',''),(33,4,'Eirik4','eirik@pcfood.net',12345678,'$2y$10$TCECLCR7uuvLpWgGDTWfseLyAB8FSFlNxBhBR3VHWTI24ODeddOli',1,'2017-04-20',''),(34,4,'heihei','hei@hei.hei',123123,'$2y$10$D9LNFeoE1f0UC.fh1.zw0eN/gUa0XbRm2jBZxDkXMAFKicUwcUhdi',0,'2017-04-26',''),(35,4,'12','12@2',0,'$2y$10$ZXWcg5qioJ7.64BFXz/L8OmXpLsfhhNUx05HzriQRp4dMKylwfT/m',0,'2017-04-26','4762bb24465605afde489b98fdf42099fe8c181d'),(36,4,'123123','12312@n',0,'$2y$10$ROsJ8Uf31xf1a3ENvuXoOubqE2AWlEGP0UpAY3jltyAWpZGkJ754i',0,'2017-04-26','a939bde13e86d012c02a9e87c35a9f8961de9b84'),(38,4,'Admin1112','steinar.malin@gmail.com',99499914,'$2y$10$.IAWb4jgrEPxUX3CRaIXR.7UFTsAOrAN0aS.1WEChIp32msHN87QS',1,'2017-05-04','');
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
  PRIMARY KEY (`brukertype_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brukertype`
--

LOCK TABLES `brukertype` WRITE;
/*!40000 ALTER TABLE `brukertype` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `brukertype` VALUES (1,'Systemadministrator',1,1,1,1),(2,'Prosjektadministrator',1,1,1,0),(3,'Teamleder',1,0,0,0),(4,'Ansatt',0,0,0,0);
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
  CONSTRAINT `fk_fase_prosjekt1` FOREIGN KEY (`prosjekt_id`) REFERENCES `prosjekt` (`prosjekt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fase`
--

LOCK TABLES `fase` WRITE;
/*!40000 ALTER TABLE `fase` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `fase` VALUES (1,2,'Fase 1','Aktiv','2017-02-21','2017-02-22'),(2,3,'Retrospekt22','Aktiv','2017-03-02','2017-03-09'),(3,2,'Fase 2','Ikke påbegynt','2017-03-13','2017-04-08'),(4,3,'Sprint 2','Aktiv','2017-03-06','2017-03-26'),(5,5,'Sprint 1','Aktiv','0000-00-00','0000-00-00'),(6,6,'Sprint 1','Aktiv','2017-02-20','2017-03-05'),(7,15,'Uke 1','Aktiv','2017-02-20','2017-02-26'),(8,15,'Uke 2','Aktiv','2017-02-27','2017-03-05'),(9,4,'sasa','Aktiv','2017-03-08','2017-03-31'),(10,2,'Fase 3','Aktiv','2017-03-13','2017-03-14'),(11,2,'FaseTest','Aktiv','0000-00-00','0000-00-00'),(12,2,'FasteTest2','Forsinket','0000-00-00','0000-00-00'),(14,24,'Backlog','Aktiv','2017-03-15','2017-03-17'),(15,3,'Fase sffsdfs','Aktiv','2017-12-17','2017-02-17'),(16,25,'Backlog','Aktiv','2017-03-17','2018-03-17'),(17,26,'Backlog','Aktiv','2017-03-21','2018-03-21'),(19,16,'Fase 2','Ikke påbegynt','2017-04-01','2017-04-30'),(20,16,'Avsluttet fase','Ferdig','2017-02-01','2017-02-28'),(21,3,'Fastest','Ikke påbegynt','0000-00-00','0000-00-00'),(22,16,'','Ikke påbegynt','0000-00-00','0000-00-00'),(23,16,'','Ikke påbegynt','0000-00-00','2017-03-17'),(24,27,'Backlog','Aktiv','0000-00-00','0000-00-00'),(25,2,'T2','Ikke påbegynt','0000-00-00','0000-00-00'),(26,28,'Backlog','Aktiv','0000-00-00','0000-00-00'),(27,28,'Fase1','Ikke påbegynt','0000-00-00','0000-00-00'),(28,29,'Backlog','Aktiv','2017-03-30','2017-05-26'),(29,2,'test','Ikke påbegynt','0000-00-00','0000-00-00'),(31,20,'TestFase','Ferdig','0000-00-00','0000-00-00'),(32,30,'Backlog','Aktiv','0000-00-00','0000-00-00'),(33,3,'sass','Ikke påbegynt','0000-00-00','0000-00-00'),(34,31,'Backlog','Aktiv','0000-00-00','0000-00-00'),(35,32,'Backlog','Aktiv','0000-00-00','0000-00-00'),(36,33,'Backlog','Aktiv','0000-00-00','0000-00-00'),(37,34,'Backlog','Aktiv','0000-00-00','0000-00-00'),(38,35,'Backlog','Aktiv','2017-04-05','2017-04-08'),(39,36,'Backlog','Aktiv','2017-04-06','2017-04-14'),(40,37,'Backlog','Aktiv','0000-00-00','0000-00-00'),(41,38,'Backlog','Aktiv','2017-04-05','2017-04-12'),(42,39,'Backlog','Aktiv','2017-04-13','2017-04-23'),(43,40,'Backlog','Aktiv','2017-04-20','2018-04-20'),(44,41,'Backlog','Aktiv','2017-04-24','2017-05-22'),(45,41,'Fase 1','Aktiv','2017-04-25','2017-04-29'),(46,41,'Fase 2','Ikke påbegynt','2017-04-27','2017-04-30'),(47,41,'Fase 4','Ikke påbegynt','2017-04-29','2017-05-13'),(48,41,'Prosjektleder testfase rediger','Ikke påbegynt','2017-04-25','2017-04-29'),(49,42,'Backlog','Aktiv','2017-04-26','2017-04-28'),(50,43,'Backlog','Aktiv','2017-02-23','2017-04-30'),(51,14,'Backlog','Ikke påbegynt','2017-04-04','2017-04-28'),(52,44,'Backlog','Aktiv','2017-05-02','2017-05-27'),(53,45,'Backlog','Aktiv','2017-05-01','2017-05-14'),(54,46,'Backlog','Aktiv','2017-05-01','2017-05-14'),(55,47,'Backlog','Aktiv','2017-05-01','2017-06-14'),(56,8,'Fase fase','Aktiv','2017-05-02','2017-05-27'),(57,8,'Fase 2','Ikke påbegynt','2017-05-01','2017-05-24'),(58,8,'Fase 4','Ferdig','2017-04-01','2017-05-31'),(59,48,'Backlog','Aktiv','2017-03-18','2017-04-18'),(60,49,'Backlog','Aktiv','2017-01-01','2018-01-01'),(61,50,'Backlog','Aktiv','2017-05-03','2017-05-25'),(62,51,'Backlog','Aktiv','2017-05-03','2017-05-26');
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
  CONSTRAINT `forslag_tidsestimat_ibfk_1` FOREIGN KEY (`oppgave_id`) REFERENCES `oppgave` (`oppgave_id`),
  CONSTRAINT `forslag_tidsestimat_ibfk_2` FOREIGN KEY (`bruker_id`) REFERENCES `bruker` (`bruker_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forslag_tidsestimat`
--

LOCK TABLES `forslag_tidsestimat` WRITE;
/*!40000 ALTER TABLE `forslag_tidsestimat` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `forslag_tidsestimat` VALUES (6,12,1,4),(7,12,1,4),(14,6,7,100),(15,6,7,0),(17,6,7,8),(18,6,7,11111),(19,6,7,12),(20,6,7,12),(23,6,7,12),(24,6,7,5),(25,6,7,5),(29,19,17,11),(30,17,17,10),(31,18,1,2),(32,25,17,17),(33,25,17,10);
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
  PRIMARY KEY (`oppgave_id`),
  KEY `fk_oppgave_fase1_idx` (`fase_id`),
  KEY `fk_oppgave_oppgavetype1_idx` (`oppgavetype_id`),
  KEY `fk_oppgave_foreldreoppgave1_idx` (`foreldre_oppgave_id`),
  CONSTRAINT `fk_oppgave_fase` FOREIGN KEY (`fase_id`) REFERENCES `fase` (`fase_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_oppgave_foreldreoppgave` FOREIGN KEY (`foreldre_oppgave_id`) REFERENCES `oppgave` (`oppgave_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_oppgave_oppgavetype` FOREIGN KEY (`oppgavetype_id`) REFERENCES `oppgavetype` (`oppgavetype_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oppgave`
--

LOCK TABLES `oppgave` WRITE;
/*!40000 ALTER TABLE `oppgave` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `oppgave` VALUES (1,NULL,2,4,'Filosofere over livet',3.0,NULL),(2,NULL,1,4,'Telle sandkorn i sandkassen',99.9,1000),(4,NULL,1,4,'Test',10.0,10),(5,NULL,1,1,'TestTest',100.0,8),(6,NULL,1,6,'Innlevering',5.0,0),(7,NULL,1,4,'Lever resultater',1.0,0),(8,NULL,1,4,'',0.0,0),(9,NULL,1,4,'',0.0,0),(10,NULL,1,4,'asdsa',0.0,0),(11,NULL,1,4,'tette',0.0,0),(12,NULL,1,10,'Faseregistrering',1.0,24),(15,NULL,1,31,'TestOppgave',12.0,1),(16,NULL,1,2,'sasassa',12.0,0),(17,NULL,3,7,'Oppgave1',7.0,11),(18,NULL,1,43,'Hei verden-oppgave',1.0,1),(19,NULL,1,2,'<b>Hei</b>',11.0,11),(20,NULL,1,45,'Oppgave 1',12.0,123),(21,NULL,1,44,'Oppgave oppgave',1.0,12),(22,NULL,1,8,'Oppgave 2',12.0,123),(23,NULL,1,44,'Test',1.0,2),(24,NULL,2,51,'Opprette sprint-backlogger',10.0,240),(25,NULL,1,56,'Oppgave 1',12.0,10),(26,NULL,4,57,'Oppgave oppgave',4.0,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oppgavetype`
--

LOCK TABLES `oppgavetype` WRITE;
/*!40000 ALTER TABLE `oppgavetype` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `oppgavetype` VALUES (1,'Produktiv'),(2,'Administrativ'),(3,'Research'),(4,'Opplæring'),(5,'Kaffekoking');
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prosjekt`
--

LOCK TABLES `prosjekt` WRITE;
/*!40000 ALTER TABLE `prosjekt` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `prosjekt` VALUES (1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(2,1,'Prosjekt2',6,2,2,'Eit nytt prosjekt, redigert','2017-02-21','2017-02-23','2017-02-25',1),(3,1,'Delprosjekt 1.1',5,NULL,2,'testasdf2222','2017-02-21','2017-02-23','2017-04-30',0),(4,1,'Test oppdatert',2,NULL,3,'test er oppdatert!!!','2017-02-23','2217-01-11','2219-01-11',0),(5,4,'ny',8,NULL,6,'enda en test','2017-02-27','2217-01-11','2219-01-11',0),(6,2,'underprosjekt1',3,NULL,4,'nytt underprosjekt','2017-02-27','2027-02-17','2030-03-17',0),(7,1,'123123',1,NULL,7,'123123 - redigert etter arkivering?','2017-02-27','0000-00-00','0000-00-00',1),(8,1,'steinar123',1,NULL,7,'steinar123','2017-02-27','2017-02-28','2019-02-28',0),(9,1,'Grunnprosjekt1',8,NULL,6,'TestTestTest Redigert','2017-02-27','2027-02-17','2029-03-17',0),(10,1,'123123',1,NULL,5,'123123','2017-02-27','0000-00-00','0000-00-00',1),(13,1,'test med dato',1,NULL,4,'test med dato','2017-02-27','2017-02-02','2017-02-16',0),(14,1,'Systemutvikling',3,NULL,8,'','2017-03-03','2017-01-18','2017-05-15',0),(15,14,'Sprint 1',11,NULL,7,'Sprint 1','2017-03-03','2017-02-20','2017-03-05',0),(16,1,'tttt',1,NULL,7,'Redigert prosjekt','2017-03-11','2017-03-02','2017-03-29',1),(17,1,'tttt',1,NULL,6,'ttt','2017-03-11','0000-00-00','0000-00-00',0),(18,1,'aassasa',1,NULL,5,'assaas','2017-03-11','0000-00-00','0000-00-00',0),(19,1,'assad',1,NULL,4,'asdasd','2017-03-11','0000-00-00','0000-00-00',0),(20,1,'Test',1,NULL,3,'','2017-03-14','0000-00-00','0000-00-00',1),(21,1,'FaseProsjekt',11,NULL,8,'Et prosjekt som skal ha automatisk opprettet backlog-fase','2017-03-15','2017-03-15','2017-03-16',0),(22,21,'Enda ett',11,NULL,8,'Backlog?','2017-03-15','2017-03-15','2017-03-23',1),(23,21,'FaseProsjekt2',11,NULL,8,'Nå da?','2017-03-15','2017-03-15','2017-03-16',1),(24,21,'FaseProsjekt3',11,11,8,'Siste forsøk','2017-03-15','2017-03-15','2017-03-17',0),(25,3,'undertestt',1,NULL,2,'htsyhth','2017-03-17','2017-03-17','2017-04-20',1),(26,1,'Prosjekt, nytt',1,NULL,7,'test','2017-03-21','2017-03-21','2018-03-21',1),(27,NULL,'TestTest',3,NULL,NULL,'test','2017-03-26','2017-03-26','2017-04-02',1),(28,NULL,'Steinars Prosjekt',1,NULL,NULL,'test','2017-03-27','0000-00-00','0000-00-00',1),(29,NULL,'TestProsjekt1',17,NULL,NULL,'testtesttest','2017-03-30','2017-03-30','2017-05-26',1),(30,26,'nyttUnderProsjekt',1,NULL,1,'','2017-04-03','0000-00-00','0000-00-00',0),(31,1,'',1,NULL,1,'','2017-04-05','0000-00-00','0000-00-00',1),(32,1,'',1,NULL,1,'','2017-04-05','0000-00-00','0000-00-00',1),(33,1,'',1,NULL,1,'','2017-04-05','0000-00-00','0000-00-00',1),(34,1,'',1,NULL,1,'','2017-04-05','0000-00-00','0000-00-00',1),(35,8,'123123',1,NULL,1,'123','2017-04-05','2017-04-05','2017-04-08',1),(36,NULL,'123',1,NULL,1,'123123','2017-04-05','2017-04-06','2017-04-14',1),(37,1,'',1,NULL,1,'','2017-04-05','0000-00-00','0000-00-00',1),(38,6,'Flytting',11,NULL,8,'Flytting av Server-rekker','2017-04-05','2017-04-05','2017-04-12',0),(39,2,'1233',1,NULL,1,'123','2017-04-05','2017-04-13','2017-04-23',0),(40,NULL,'Admin1prosjekt',1,NULL,9,'Hei','2017-04-20','2017-04-20','2018-04-20',0),(41,NULL,'Prosjekt prosjekt',17,NULL,1,'testtesttest','2017-04-24','2017-04-24','2017-05-22',0),(42,NULL,'Brøyte frem plen',3,NULL,2,'Rydd bort sne','2017-04-26','2017-04-26','2017-04-28',0),(43,3,'DatoProsjekt 1',1,NULL,1,'Dette underprosjektet er en test mot dato i forhold til fore','2017-04-28','2017-02-24','2017-04-20',1),(44,NULL,'Aktivt prosjekt',2,NULL,7,'test','2017-05-02','2017-05-02','2017-05-27',0),(45,NULL,'Aktivt prosjekt',1,NULL,7,'123','2017-05-02','2017-05-01','2017-05-14',0),(46,NULL,'123123',1,NULL,1,'123','2017-05-02','2017-05-01','2017-05-14',0),(47,8,'Aktivt prosjekt',1,NULL,7,'123','2017-05-02','2017-05-01','2017-06-14',0),(48,25,'underunder',1,NULL,1,'sdfg','2017-05-04','2017-03-18','2017-04-18',0),(49,NULL,'testProductOwnerProsjekt',1,2,1,'test av product owner','2017-05-05','2017-01-01','2018-01-01',0),(50,1,'testProductOwnerProsjekt2',2,NULL,1,'tsst','2017-05-05','2017-05-03','2017-05-25',1),(51,1,'testProductOwnerProsjekt2',1,6,1,'test av product owner','2017-05-05','2017-05-03','2017-05-26',0);
/*!40000 ALTER TABLE `prosjekt` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team`
--

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `team` VALUES (1,5,'Team 1'),(2,6,'Team 2'),(3,11,'Gruppe 11'),(4,2,'Eiriks Ensemblee'),(5,21,'Steinars Skvadron'),(6,5,'Benedictes Tropp'),(7,17,'Ines Band'),(8,11,'Kenneths Legion'),(9,1,'Admin1Team'),(12,28,'test_endret'),(13,1,'Nytt_team_endret'),(14,5,'nytt-team'),(15,1,'NyttTeamtest'),(16,28,'testtest'),(17,28,'test5'),(18,1,'test63');
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
  CONSTRAINT `fk_teammedlemskap_bruker1` FOREIGN KEY (`bruker_id`) REFERENCES `bruker` (`bruker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_teammedlemskap_team1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teammedlemskap`
--

LOCK TABLES `teammedlemskap` WRITE;
/*!40000 ALTER TABLE `teammedlemskap` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `teammedlemskap` VALUES (21,1),(5,2),(11,3),(21,5),(19,6),(20,6),(8,7),(17,7),(34,7),(11,8),(33,9),(1,18),(2,18),(28,18);
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
  PRIMARY KEY (`timereg_id`),
  KEY `fk_timeregistrering_oppgave1_idx` (`oppgave_id`),
  KEY `fk_timeregistrering_bruker1_idx` (`bruker_id`),
  CONSTRAINT `fk_timeregistrering_bruker1` FOREIGN KEY (`bruker_id`) REFERENCES `bruker` (`bruker_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_timeregistrering_oppgave1` FOREIGN KEY (`oppgave_id`) REFERENCES `oppgave` (`oppgave_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=322 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timeregistrering`
--

LOCK TABLES `timeregistrering` WRITE;
/*!40000 ALTER TABLE `timeregistrering` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `timeregistrering` VALUES (3,2,2,'2017-02-22','05:00:00','06:00:00',0,'2017-02-21 23:00:00',3,0,1,0,0,NULL),(18,1,2,'2011-01-13','00:00:00','02:00:00',0,'2017-02-23 14:31:30',3,0,0,0,0,NULL),(20,1,1,'2011-01-13','00:00:00','01:00:00',0,'2017-02-23 14:33:36',3,0,0,0,0,NULL),(21,1,2,'2011-01-13','14:36:00','14:42:00',0,'2017-02-23 14:35:17',3,0,0,0,0,NULL),(22,1,2,'2011-01-13','17:24:00','17:25:00',0,'2017-02-27 17:24:53',3,0,0,1,0,NULL),(23,1,2,'2011-01-13','00:00:00','01:00:00',0,'2017-02-27 17:25:02',3,0,0,0,0,NULL),(24,1,2,'2017-02-27','18:42:00','18:44:00',0,'2017-02-27 17:42:48',3,0,0,0,0,NULL),(25,1,2,'2017-02-27','15:00:00','18:42:00',0,'2017-02-27 17:42:58',3,0,0,0,0,NULL),(26,1,2,'2017-02-28','09:04:00','09:08:00',0,'2017-02-28 08:09:14',3,0,0,0,0,NULL),(27,1,2,'2017-02-28','09:13:00','09:16:00',0,'2017-02-28 08:17:08',3,0,0,1,1,NULL),(34,1,2,'2017-02-28','12:17:00','12:19:00',0,'2017-02-28 11:17:55',3,0,1,1,0,NULL),(35,1,1,'2017-02-28','12:17:00','13:17:00',0,'2017-02-28 11:17:58',3,0,1,0,0,NULL),(36,1,2,'2017-02-28','12:18:00','12:33:00',0,'2017-02-28 11:18:39',3,0,1,0,0,NULL),(37,1,1,'2017-02-28','00:42:00','23:42:00',0,'2017-02-28 11:43:03',3,0,1,0,0,NULL),(38,1,1,'2017-02-27','01:43:00','23:43:00',0,'2017-02-28 11:43:48',3,0,1,1,0,NULL),(39,1,2,'2017-03-01','13:00:00','13:02:00',0,'2017-03-01 12:03:23',3,0,0,1,0,NULL),(40,1,5,'2017-03-01','19:35:00','21:35:00',0,'2017-03-31 12:15:32',3,0,0,0,0,NULL),(41,1,4,'2017-03-14','17:18:00','17:18:00',0,'2017-03-03 16:18:18',3,0,0,0,0,NULL),(42,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 12:17:47',3,0,0,0,0,NULL),(43,1,4,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 11:54:53',3,0,0,0,0,NULL),(44,1,2,'2017-03-03','17:25:00','17:25:00',0,'2017-03-31 12:11:23',3,0,0,0,0,NULL),(45,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 11:56:55',3,0,0,0,0,NULL),(46,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:21:29',3,0,0,0,0,NULL),(47,1,4,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:32:55',3,0,0,0,0,NULL),(48,1,5,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:08:21',3,0,0,0,0,NULL),(49,1,4,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:25:08',3,0,0,0,0,NULL),(50,1,5,'2017-03-03','17:28:00','17:28:00',0,'2017-03-03 16:28:38',3,0,1,0,0,NULL),(51,1,5,'2017-03-03','17:28:00','17:28:00',0,'2017-03-31 12:07:30',3,0,0,0,0,NULL),(52,1,4,'2017-03-03','17:29:00','17:29:00',0,'2017-03-03 16:29:20',3,0,0,0,0,NULL),(53,2,7,'2017-03-03','16:00:00','19:30:00',0,'2017-03-03 16:40:10',3,0,1,1,0,NULL),(54,2,7,'2017-03-03','17:40:00','17:40:00',0,'2017-03-03 16:44:08',3,0,1,1,1,NULL),(55,2,7,'2017-03-03','16:00:00','19:30:00',0,'2017-03-03 16:45:01',3,0,1,1,1,NULL),(56,15,2,'2017-03-07','17:47:00','17:49:00',0,'2017-03-07 16:49:42',3,0,1,1,1,NULL),(57,18,10,'2017-03-17','14:03:00','15:00:00',0,'2017-03-17 13:48:01',3,0,1,1,1,NULL),(58,1,5,'2017-03-16','14:50:00','17:55:00',0,'2017-03-17 13:50:59',3,0,1,1,1,NULL),(59,5,1,'2017-03-18','01:12:00','01:12:00',0,'2017-04-03 00:29:45',3,0,0,1,1,NULL),(60,5,5,'2017-03-18','02:04:00','06:04:00',0,'2017-03-18 01:04:43',3,0,1,0,0,NULL),(61,5,5,'2017-03-18','02:04:00','12:04:00',0,'2017-03-18 01:04:49',3,0,1,1,1,NULL),(62,7,1,'2017-03-18','10:39:00','13:39:00',0,'2017-03-18 09:39:12',3,0,1,1,1,NULL),(63,7,1,'2017-03-18','10:39:00','23:39:00',0,'2017-03-18 09:39:20',3,0,1,0,1,NULL),(64,7,5,'2017-03-19','02:16:00','06:16:00',0,'2017-03-19 01:16:41',3,0,1,0,1,NULL),(65,7,5,'2017-03-19','02:16:00','04:16:00',0,'2017-03-19 01:16:46',3,0,1,1,1,NULL),(66,8,5,'2017-03-19','02:17:00','06:17:00',0,'2017-03-19 01:17:21',3,0,0,0,1,NULL),(67,8,5,'2017-03-19','02:17:00','07:17:00',0,'2017-03-19 01:17:27',3,0,0,1,1,NULL),(68,1,1,'2017-03-19','22:44:00','22:44:00',0,'2017-03-31 11:54:20',3,0,0,1,1,NULL),(71,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 10:46:31',3,0,0,0,0,NULL),(72,17,5,'2017-03-20','08:46:00','09:46:00',0,'2017-03-20 10:46:41',3,0,0,0,0,NULL),(73,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 11:05:20',3,0,0,0,0,NULL),(74,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 11:06:29',3,0,0,0,0,NULL),(75,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 11:08:31',3,0,0,0,0,NULL),(76,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 13:23:59',3,4,0,0,0,NULL),(77,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 13:24:53',3,0,0,0,0,NULL),(78,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 13:25:14',3,0,0,0,0,NULL),(79,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 13:26:01',3,0,0,0,0,NULL),(80,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 13:26:26',3,0,0,0,0,NULL),(81,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 13:27:11',3,0,0,0,0,NULL),(82,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 13:28:07',3,0,0,0,0,NULL),(83,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 13:47:12',3,0,0,0,0,NULL),(84,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-04-05 11:29:56',3,0,0,0,0,NULL),(85,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 14:37:42',3,0,0,0,0,NULL),(86,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-04-05 11:30:29',3,0,0,0,0,NULL),(87,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-20 14:47:19',3,0,0,0,0,NULL),(88,17,5,'2017-03-18','11:46:00','12:46:00',0,'2017-03-20 14:48:34',3,0,1,0,0,'feil dag'),(89,17,5,'2017-03-20','15:46:00','17:46:00',0,'2017-03-20 14:49:02',3,0,0,0,0,'feil tid'),(90,8,5,'2017-03-19','03:17:00','06:17:00',0,'2017-03-21 12:33:34',3,0,0,0,0,'ny tid'),(91,7,5,'2017-03-19','02:16:00','06:16:00',0,'2017-03-21 12:40:45',3,0,1,0,0,NULL),(92,7,5,'2017-03-19','06:16:00','12:16:00',0,'2017-03-21 12:42:08',3,0,1,0,0,'ny tid'),(93,1,4,'2017-03-21','14:13:00','14:20:00',0,'2017-03-21 13:21:05',3,0,0,1,1,NULL),(94,1,1,'2018-03-19','22:44:00','22:46:00',0,'2017-03-21 13:26:54',3,0,0,1,0,'Test'),(95,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-21 14:24:17',3,0,0,1,1,NULL),(96,11,1,'2017-03-22','19:47:21','20:53:54',0,'2017-03-22 19:53:54',3,0,0,0,1,NULL),(97,11,7,'2017-03-22','21:04:54','21:09:40',2,'2017-03-22 20:09:40',3,0,0,1,1,NULL),(98,11,7,'2017-03-22','21:04:54','21:39:40',0,'2017-03-22 21:42:23',3,0,0,1,1,'Kom borti knappen for tidlig'),(101,8,5,'2017-03-19','10:17:00','20:17:00',0,'2017-03-23 13:14:50',3,0,1,0,0,'korrigert'),(102,1,5,'2017-03-23','22:57:49','22:58:09',0,'2017-03-23 21:58:09',3,0,1,1,1,NULL),(103,1,12,'2017-03-23','22:58:50','22:59:18',0,'2017-03-23 21:59:18',3,0,1,1,1,NULL),(104,1,5,'2017-03-23','23:00:37','23:01:09',0,'2017-03-23 22:01:09',3,0,0,1,1,NULL),(105,1,5,'2017-03-23','23:01:41','23:02:17',0,'2017-03-31 13:10:06',3,0,0,1,1,NULL),(106,1,5,'2017-03-23','23:04:16','23:04:59',0,'2017-03-23 22:04:59',3,0,0,1,1,NULL),(107,8,5,'2017-03-19','02:17:00','06:17:00',0,'2017-03-24 14:10:20',3,0,0,0,0,'Endret'),(108,8,5,'2017-03-19','04:17:00','11:17:00',0,'2017-03-24 14:11:11',3,0,0,0,0,'Test'),(109,8,5,'2017-03-19','04:17:00','11:17:00',0,'2017-03-24 14:12:08',3,0,0,0,0,'siste test'),(110,8,5,'2017-03-19','07:17:00','16:17:00',0,'2017-03-24 14:13:03',3,0,1,0,0,'test-test'),(111,17,5,'2017-03-26','15:19:53','15:20:13',0,'2017-03-26 13:20:13',3,0,0,1,1,NULL),(112,11,7,'2017-03-22','21:04:54','21:09:40',0,'2017-03-27 06:27:38',3,0,0,1,1,NULL),(113,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-27 07:02:48',3,0,0,1,0,'Korrigert'),(114,11,7,'2017-03-22','21:04:54','21:39:40',0,'2017-03-31 12:15:38',3,0,0,1,0,''),(115,11,11,'2017-03-21','15:23:00','20:52:16',37,'2017-03-27 07:10:32',3,0,0,1,0,''),(116,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-27 08:47:03',3,0,0,0,0,NULL),(117,17,1,'2017-03-27','10:47:40','10:47:48',0,'2017-03-27 08:47:48',3,0,1,1,1,NULL),(118,17,1,'2017-03-27','10:47:40','10:47:48',0,'2017-03-27 08:48:08',3,0,0,1,1,NULL),(119,17,5,'2017-03-26','15:19:53','15:20:13',0,'2017-03-27 09:25:44',3,0,0,1,1,NULL),(120,17,5,'2017-03-26','15:19:53','15:20:13',0,'2017-03-27 10:01:10',3,0,1,1,0,'test'),(121,1,5,'2017-03-27','14:56:48','14:57:20',0,'2017-03-27 12:57:20',3,0,0,1,1,NULL),(122,11,11,'2017-03-21','15:23:00','20:52:16',37,'2017-03-28 08:53:15',3,0,0,1,0,''),(123,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-29 10:08:20',3,0,0,1,1,NULL),(124,17,2,'2017-03-29','13:18:22','15:32:53',52,'2017-04-05 08:56:05',3,0,0,1,1,NULL),(125,17,5,'2017-03-29','15:42:40','15:45:43',0,'2017-03-29 13:45:43',3,0,1,1,1,NULL),(126,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-31 11:14:27',3,0,0,1,0,NULL),(127,1,5,'2017-03-23','23:00:37','23:01:09',0,'2017-03-31 11:14:37',3,0,1,1,1,NULL),(128,1,4,'2017-03-14','17:18:00','17:18:00',0,'2017-03-31 12:05:31',3,0,0,0,0,NULL),(129,1,4,'2017-03-14','17:18:00','17:18:00',0,'2017-03-31 11:15:39',3,0,0,0,0,NULL),(130,1,5,'2017-03-27','14:56:48','14:57:20',0,'2017-03-31 11:15:55',3,0,0,1,1,NULL),(131,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-03-31 11:16:37',3,0,0,0,0,NULL),(132,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 11:16:49',3,0,0,1,1,NULL),(133,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 11:17:36',3,0,0,1,0,''),(134,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-31 11:17:39',3,0,0,1,0,''),(135,1,5,'2017-03-27','14:56:48','14:57:20',0,'2017-03-31 11:18:08',3,0,0,1,1,NULL),(136,1,5,'2017-03-27','14:56:48','14:57:20',0,'2017-03-31 11:18:29',3,0,0,1,1,NULL),(137,11,7,'2017-03-22','21:04:54','21:09:40',0,'2017-03-31 11:39:07',3,0,0,1,1,NULL),(138,1,2,'2017-03-01','13:00:00','13:02:00',0,'2017-03-31 11:55:58',3,0,0,1,0,NULL),(139,1,2,'2017-03-01','13:00:00','13:02:00',0,'2017-03-31 11:19:16',3,0,0,1,0,NULL),(140,17,5,'2017-03-20','15:46:00','17:46:00',0,'2017-03-31 11:19:20',3,0,1,0,0,NULL),(141,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 11:49:18',3,0,0,1,0,NULL),(142,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-31 11:21:08',3,0,0,1,0,NULL),(143,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-04-05 11:28:41',3,0,0,0,0,''),(144,1,5,'2017-03-27','14:56:48','15:57:20',0,'2017-03-31 11:56:31',3,0,0,1,0,''),(145,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-31 12:15:11',3,0,0,1,0,''),(146,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-31 11:23:37',3,0,0,1,0,NULL),(147,11,7,'2017-03-22','21:04:54','21:09:40',0,'2017-04-02 18:05:34',3,0,0,1,0,NULL),(148,11,7,'2017-03-22','21:04:54','21:09:40',0,'2017-03-31 11:39:07',3,0,0,1,0,''),(149,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 11:59:40',3,0,0,1,0,NULL),(152,1,2,'2017-03-01','13:00:00','13:02:00',0,'2017-03-31 12:06:54',3,0,0,1,0,NULL),(153,1,5,'2017-03-27','14:56:48','15:57:20',0,'2017-03-31 11:56:31',3,0,1,1,0,NULL),(154,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:50:32',3,0,0,0,0,NULL),(155,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 12:18:45',3,0,0,1,0,NULL),(156,1,4,'2017-03-14','17:18:00','17:18:00',0,'2017-03-31 12:05:09',3,0,1,0,0,NULL),(157,1,4,'2017-03-14','17:18:00','17:18:00',0,'2017-03-31 12:05:31',3,0,0,0,0,NULL),(158,1,2,'2017-03-01','13:00:00','13:02:00',0,'2017-03-31 12:06:54',3,0,1,1,0,NULL),(160,1,5,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:08:21',3,0,1,0,0,NULL),(161,1,2,'2017-03-03','17:25:00','17:25:00',0,'2017-03-31 13:10:49',3,0,0,0,0,NULL),(163,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-03-31 12:37:08',3,0,0,1,0,''),(165,11,7,'2017-03-22','21:04:54','21:39:40',0,'2017-04-02 18:11:25',3,0,0,1,0,''),(166,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 12:38:39',3,0,0,0,0,''),(167,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 12:20:09',3,0,0,1,0,NULL),(168,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 12:35:30',3,0,0,1,0,NULL),(169,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:32:08',3,0,0,0,0,NULL),(170,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:37:06',3,0,0,0,0,NULL),(171,1,4,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:25:08',3,0,1,0,0,''),(172,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:32:08',3,0,0,0,0,NULL),(173,1,4,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:32:54',3,0,1,0,0,NULL),(174,1,12,'2017-03-29','12:08:15','12:08:20',0,'2017-03-31 12:35:30',3,0,1,1,0,NULL),(175,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:36:11',3,0,1,0,0,NULL),(176,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:37:06',3,0,0,0,0,NULL),(177,11,11,'2017-03-21','15:23:00','20:52:16',0,'2017-04-02 18:09:30',3,0,0,1,0,''),(178,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 12:42:02',3,0,0,0,0,NULL),(179,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 12:38:39',3,0,0,0,0,NULL),(180,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 12:52:19',3,0,0,0,0,NULL),(181,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:51:20',3,0,0,0,0,NULL),(182,1,2,'2017-03-03','17:27:00','17:27:00',0,'2017-03-31 12:51:20',3,0,1,0,0,NULL),(183,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 12:56:28',3,0,0,0,0,NULL),(186,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 13:01:16',3,0,0,0,0,NULL),(191,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 13:02:01',3,0,0,0,0,NULL),(192,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 13:07:05',3,0,0,0,0,''),(193,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 13:07:36',3,0,0,0,0,NULL),(194,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 13:07:05',3,0,0,0,0,NULL),(195,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 13:08:30',3,0,0,0,0,NULL),(196,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 13:09:15',3,0,0,0,0,NULL),(197,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 13:15:45',3,0,0,0,0,NULL),(198,1,5,'2017-03-23','12:01:41','23:02:17',0,'2017-03-31 13:10:06',3,0,1,1,0,''),(199,1,2,'2017-03-03','17:25:00','17:25:00',0,'2017-03-31 13:10:49',3,0,1,0,0,NULL),(200,1,1,'2017-03-03','12:18:00','17:18:00',0,'2017-03-31 13:12:54',3,0,1,0,0,''),(201,1,1,'2017-03-03','12:18:00','17:18:00',0,'2017-03-31 13:14:04',3,0,0,0,0,''),(202,1,1,'2017-03-03','17:18:00','17:18:00',0,'2017-03-31 13:15:29',3,0,0,0,0,NULL),(203,1,1,'2017-03-03','12:18:00','17:18:00',0,'2017-03-31 13:15:45',3,0,0,0,0,''),(208,7,6,'2017-04-02','04:42:38','04:59:21',0,'2017-04-02 17:02:02',3,0,0,1,1,NULL),(209,7,6,'2017-04-02','04:45:59','04:59:25',0,'2017-04-02 17:05:09',3,0,0,1,1,NULL),(212,7,6,'2017-04-02','05:06:20','05:06:26',0,'2017-04-02 18:11:33',3,0,0,1,1,NULL),(213,7,6,'2017-04-02','04:42:38','04:59:21',0,'2017-04-02 18:07:39',3,0,0,1,1,'test'),(214,7,6,'2017-04-02','04:45:59','04:59:25',0,'2017-04-02 17:09:43',3,0,0,1,0,'test'),(215,8,1,'2017-04-02','19:52:55','19:53:05',0,'2017-04-02 18:03:30',3,0,0,1,1,NULL),(216,7,6,'2017-04-02','04:42:38','04:59:21',0,'2017-04-02 18:12:22',3,0,0,1,0,'test2'),(217,8,1,'2017-04-02','20:28:59','20:29:03',0,'2017-04-02 18:29:03',3,0,1,1,1,NULL),(218,11,6,'2017-04-02','20:29:38','20:29:42',0,'2017-04-05 13:15:59',3,0,0,1,1,NULL),(219,5,1,'2017-03-18','01:12:00','01:12:00',0,'2017-04-03 00:29:45',3,0,1,1,0,'test'),(220,7,6,'2017-04-03','03:04:18','03:04:20',0,'2017-04-10 23:28:53',3,0,0,1,1,NULL),(221,17,2,'2017-03-29','13:18:22','15:32:53',0,'2017-04-05 08:56:05',3,0,1,1,1,NULL),(222,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-04-05 11:28:41',3,0,1,0,0,NULL),(223,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-04-05 13:30:33',3,0,0,0,0,NULL),(224,17,5,'2017-03-20','11:46:00','12:46:00',0,'2017-04-05 11:30:29',3,0,1,0,0,NULL),(225,11,6,'2017-04-02','20:29:38','21:29:42',0,'2017-04-05 13:17:35',3,0,0,1,0,'Testing'),(226,11,6,'2017-04-02','20:29:38','21:30:42',0,'2017-04-05 13:27:53',3,0,0,1,0,'asdwafaf'),(227,11,6,'2017-04-02','20:29:38','21:30:42',15,'2017-04-05 13:27:53',3,0,1,1,0,'pause'),(228,17,5,'2017-03-20','11:46:00','12:46:00',2,'2017-04-05 13:30:33',3,0,1,0,0,'123'),(229,7,6,'2017-04-11','01:13:34','01:17:37',0,'2017-04-10 23:17:37',3,0,1,1,1,NULL),(230,7,6,'2017-04-11','01:14:08','01:18:01',0,'2017-04-10 23:18:01',3,0,1,1,1,NULL),(231,7,6,'2017-04-11','01:16:40','01:18:08',0,'2017-04-10 23:18:08',3,0,1,1,1,NULL),(232,7,6,'2017-04-03','03:04:18','03:04:20',0,'2017-04-10 23:28:53',3,0,1,1,0,'test'),(233,17,17,'2017-04-17','18:25:39','18:27:26',0,'2017-04-18 12:11:13',3,0,0,1,1,NULL),(234,17,17,'2017-04-17','18:31:00','18:31:10',0,'2017-04-21 09:33:52',3,3,0,1,1,NULL),(235,17,17,'2017-04-18','13:51:23','13:55:07',2,'2017-04-18 11:55:07',3,0,1,1,1,NULL),(236,17,17,'2017-04-18','13:55:48','13:55:56',0,'2017-04-21 09:17:32',3,3,0,1,1,NULL),(237,17,17,'2017-04-18','13:56:35','14:00:40',3,'2017-04-18 13:13:45',3,0,0,1,1,NULL),(238,17,17,'2017-04-17','18:25:39','18:27:26',0,'2017-04-18 13:20:04',3,0,0,1,1,NULL),(239,17,17,'2017-04-17','18:25:39','18:27:26',0,'2017-04-18 13:13:00',3,3,1,1,1,NULL),(240,17,17,'2017-04-18','13:56:35','14:00:40',3,'2017-04-18 13:17:21',3,0,0,1,1,'test'),(241,17,17,'2017-04-18','13:56:35','14:00:40',3,'2017-04-18 13:23:05',3,0,0,1,1,NULL),(242,17,17,'2017-04-18','15:34:40','15:34:55',0,'2017-04-18 13:35:18',3,0,0,1,1,NULL),(243,17,17,'2017-04-18','15:34:40','15:34:55',0,'2017-04-21 09:48:31',3,3,0,1,0,'heihei'),(244,17,17,'2017-04-18','15:34:40','15:34:55',0,'2017-04-18 13:35:48',3,0,1,1,1,'heihei'),(245,17,17,'2017-04-19','14:14:20','14:14:26',0,'2017-04-21 09:17:20',3,0,0,1,0,NULL),(246,1,18,'2017-04-20','21:48:07','21:48:14',0,'2017-04-20 19:51:20',3,2,0,1,0,NULL),(247,1,18,'2017-04-20','21:48:39','21:48:43',0,'2017-04-20 19:48:43',3,2,1,1,1,NULL),(248,1,18,'2017-04-20','21:48:07','21:48:14',0,'2017-04-20 19:53:25',3,0,0,1,0,NULL),(249,17,17,'2017-04-18','13:55:48','13:55:56',0,'2017-04-21 09:48:22',3,3,0,1,0,'123123'),(250,17,17,'2017-04-17','18:25:39','18:27:26',0,'2017-04-21 09:17:49',3,4,1,1,0,NULL),(251,17,17,'2017-04-21','11:26:32','11:26:51',0,'2017-04-21 09:28:23',3,3,0,1,1,NULL),(252,17,17,'2017-04-21','11:26:32','11:26:51',0,'2017-04-21 09:48:37',3,3,0,1,0,'korrigering test'),(253,17,17,'2017-04-17','18:31:00','18:31:10',0,'2017-04-21 09:33:52',3,0,1,1,0,'enda en korrigering test'),(254,17,17,'2017-04-25','09:38:31','09:38:45',0,'2017-04-25 07:38:45',3,0,1,1,1,NULL),(255,17,17,'2017-04-25','20:40:38','20:40:41',0,'2017-04-25 18:40:41',3,2,1,1,1,NULL),(256,34,17,'2017-04-26','15:44:40','15:45:25',0,'2017-04-26 13:45:25',3,2,1,1,1,NULL),(257,17,17,'2017-04-19','14:14:20','14:14:26',0,'2017-04-27 08:24:43',3,0,1,1,0,NULL),(258,11,24,'2017-04-28','10:02:40','10:03:07',0,'2017-04-28 08:20:34',3,3,1,1,1,NULL),(259,11,24,'2017-04-28','10:02:40','10:57:07',45,'2017-04-28 08:20:34',3,0,1,1,0,'Kom borti knappen for tidlig'),(260,1,18,'2017-04-28','12:14:17','12:14:20',0,'2017-04-28 10:14:20',3,0,1,1,1,NULL),(261,21,21,'2017-04-30','02:16:01','02:16:06',0,'2017-04-30 00:18:32',3,3,1,1,1,NULL),(262,11,24,'2017-04-30','09:33:06','21:50:58',0,'2017-04-30 19:50:58',3,0,1,1,1,NULL),(263,11,24,'2017-04-30','09:33:06','21:51:01',0,'2017-04-30 19:51:01',3,0,1,1,1,NULL),(264,1,18,'2017-04-30','16:08:30','16:08:32',0,'2017-04-30 14:08:32',3,2,1,1,1,NULL),(265,1,18,'2017-04-30','16:08:30','16:08:39',0,'2017-05-04 13:46:55',3,4,1,1,1,NULL),(266,1,18,'2017-04-30','16:08:30','16:08:39',0,'2017-04-30 14:09:28',3,4,1,1,0,NULL),(267,1,18,'2017-04-30','16:08:30','16:08:39',0,'2017-04-30 17:50:37',3,4,1,1,0,NULL),(268,34,17,'2017-05-01','10:34:24','10:34:32',0,'2017-05-01 08:34:32',3,0,1,1,1,NULL),(269,34,17,'2017-05-01','10:34:24','10:34:40',0,'2017-05-01 08:34:40',3,0,1,1,1,NULL),(270,34,17,'2017-05-01','10:34:47','10:35:01',0,'2017-05-01 08:36:08',3,3,1,1,1,NULL),(271,34,17,'2017-05-01','10:34:47','10:35:09',0,'2017-05-01 08:35:45',3,3,1,1,1,NULL),(272,34,17,'2017-05-01','10:34:47','10:35:01',0,'2017-05-01 08:36:08',3,1,1,1,0,'korrigert'),(273,17,17,'2017-05-01','11:03:02','11:03:10',0,'2017-05-01 09:03:10',3,0,1,1,1,NULL),(274,17,17,'2017-05-01','11:03:02','11:03:14',0,'2017-05-01 09:03:14',3,0,1,1,1,NULL),(275,17,17,'2017-05-01','11:03:21','11:03:29',0,'2017-05-01 09:10:24',3,2,1,1,1,NULL),(276,17,17,'2017-05-01','11:03:21','11:03:31',0,'2017-05-01 09:28:02',3,4,1,1,1,NULL),(277,17,17,'2017-05-01','11:03:21','11:03:31',0,'2017-05-01 09:03:52',3,1,1,1,0,'Korrigert'),(278,17,17,'2017-05-01','11:03:21','11:03:29',0,'2017-05-01 09:04:08',3,5,1,1,0,NULL),(279,17,17,'2017-05-01','11:03:21','11:03:29',0,'2017-05-01 09:10:24',3,5,1,1,0,NULL),(280,17,17,'2017-05-01','11:03:21','11:03:31',0,'2017-05-01 09:30:13',3,3,1,1,0,NULL),(281,17,17,'2017-05-01','11:03:21','11:03:31',0,'2017-05-01 09:19:29',3,5,1,1,0,NULL),(282,17,17,'2017-05-01','11:03:21','11:03:31',0,'2017-05-01 09:20:34',3,5,1,1,0,NULL),(283,17,17,'2017-05-01','11:03:21','11:03:31',0,'2017-05-01 09:26:48',3,5,1,1,0,NULL),(284,17,17,'2017-05-01','11:03:21','11:03:31',0,'2017-05-01 09:29:55',3,3,1,1,0,NULL),(285,17,17,'2017-05-01','11:03:21','11:03:31',0,'2017-05-01 09:29:55',3,1,1,1,0,'Korrigert'),(286,11,24,'2017-05-04','08:12:08','08:15:12',0,'2017-05-04 06:15:12',3,0,1,1,1,NULL),(287,11,24,'2017-05-04','08:12:08','08:15:18',0,'2017-05-04 06:15:18',3,0,1,1,1,NULL),(288,1,18,'2017-05-04','13:28:12','13:43:47',0,'2017-05-04 12:15:49',3,4,1,1,1,NULL),(289,1,18,'2017-05-04','13:28:13','13:43:55',0,'2017-05-04 11:43:55',3,0,1,1,1,NULL),(290,17,22,'2017-05-04','13:29:19','13:40:09',0,'2017-05-04 11:40:09',3,0,1,1,1,NULL),(291,17,22,'2017-05-04','13:29:20','13:40:15',0,'2017-05-04 11:40:15',3,0,1,1,1,NULL),(292,11,24,'2017-05-04','13:34:15','13:48:58',0,'2017-05-04 11:48:58',3,0,1,1,1,NULL),(293,11,24,'2017-05-04','13:34:15','13:49:04',0,'2017-05-04 11:49:04',3,0,1,1,1,NULL),(294,8,17,'2017-05-04','13:35:48','13:40:48',0,'2017-05-04 11:40:48',3,0,1,1,1,NULL),(295,8,17,'2017-05-04','13:35:48','13:40:50',0,'2017-05-04 11:40:50',3,0,1,1,1,NULL),(296,8,17,'2017-05-04','13:43:03','13:43:06',0,'2017-05-04 11:43:06',3,0,1,1,1,NULL),(297,8,17,'2017-05-04','13:43:03','13:43:09',0,'2017-05-04 11:43:09',3,0,1,1,1,NULL),(298,8,17,'2017-05-04','13:44:07','13:44:10',0,'2017-05-04 11:44:10',3,0,1,1,1,NULL),(299,8,17,'2017-05-04','13:44:07','13:44:46',0,'2017-05-04 11:44:46',3,0,1,1,1,NULL),(300,1,18,'2017-05-04','13:44:26','13:45:31',0,'2017-05-04 11:45:31',3,2,1,1,1,NULL),(301,1,18,'2017-05-04','13:44:26','13:51:35',0,'2017-05-04 11:51:35',3,0,1,1,1,NULL),(302,1,18,'2017-05-04','13:52:04','13:52:08',0,'2017-05-04 11:52:08',3,2,1,1,1,NULL),(303,1,18,'2017-05-04','13:52:04','13:55:18',0,'2017-05-04 11:55:18',3,0,1,1,1,NULL),(304,17,22,'2017-05-04','13:56:32','14:02:22',0,'2017-05-04 12:02:22',3,0,1,1,1,NULL),(305,17,22,'2017-05-04','13:56:32','14:02:25',0,'2017-05-04 12:02:25',3,0,1,1,1,NULL),(306,11,24,'2017-05-04','13:58:12','13:58:55',0,'2017-05-04 11:58:55',3,0,1,1,1,NULL),(307,11,24,'2017-05-04','13:58:12','13:58:12',0,'2017-05-04 11:58:12',0,0,1,1,0,NULL),(308,1,18,'2017-05-04','13:58:23','13:58:25',0,'2017-05-04 11:58:25',3,0,1,1,1,NULL),(309,1,18,'2017-05-04','13:58:23','13:58:58',0,'2017-05-04 11:58:58',3,0,1,1,1,NULL),(310,1,18,'2017-05-04','13:59:12','13:59:13',0,'2017-05-04 11:59:13',3,0,1,1,1,NULL),(311,1,18,'2017-05-04','13:28:12','13:43:47',0,'2017-05-04 12:15:48',3,2,1,1,0,NULL),(312,1,18,'2017-05-04','15:43:06','15:43:07',0,'2017-05-04 13:43:07',3,0,1,1,1,NULL),(313,1,18,'2017-05-04','15:43:06','15:44:28',0,'2017-05-04 13:44:28',3,0,1,1,1,NULL),(314,1,18,'2017-05-04','15:44:57','15:45:00',0,'2017-05-04 13:45:00',3,0,1,1,1,NULL),(315,1,18,'2017-05-04','15:44:57','15:45:05',0,'2017-05-04 13:45:05',3,0,1,1,1,NULL),(316,1,18,'2017-04-30','16:08:30','16:08:39',0,'2017-05-04 13:46:55',3,0,1,1,0,NULL),(317,17,17,'2017-04-06','09:37:57','12:37:57',0,'2017-05-05 08:38:25',3,1,1,0,0,NULL),(318,17,17,'2017-04-20','09:00:15','11:00:15',10,'2017-05-05 09:06:49',3,1,1,0,0,'Manuell reg'),(319,17,22,'2017-05-05','11:08:29','11:08:43',0,'2017-05-05 09:08:43',3,0,1,1,1,NULL),(320,17,22,'2017-05-05','11:08:29','11:08:46',0,'2017-05-05 09:08:46',3,0,1,1,1,NULL),(321,17,22,'2017-05-05','11:14:58','11:14:58',0,'2017-05-05 09:15:01',3,1,1,0,0,'');
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

-- Dump completed on: Sat, 06 May 2017 11:24:53 +0200

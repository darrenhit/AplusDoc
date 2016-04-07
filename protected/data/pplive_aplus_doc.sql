-- MySQL dump 10.13  Distrib 5.5.15, for Linux (x86_64)
--
-- Host: localhost    Database: pplive_aplus_doc
-- ------------------------------------------------------
-- Server version	5.5.15-log

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
-- Table structure for table `doc_contents`
--

DROP TABLE IF EXISTS `doc_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_contents` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(64) NOT NULL,
  `pid` bigint(20) DEFAULT '0' COMMENT 'PARENT_ID',
  `explanation` text COMMENT 'EXPLANATION OF THE CONTENTS',
  `state` varchar(10) DEFAULT 'ONLINE' COMMENT 'STATE OF THE CONTENTS',
  `created_by` varchar(32) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_by` varchar(32) NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `PID` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `doc_documents`
--

DROP TABLE IF EXISTS `doc_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_documents` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(128) NOT NULL,
  `cid` bigint(20) NOT NULL COMMENT 'CONTENTS ID',
  `summary` varchar(1024) NOT NULL COMMENT 'SUMMARY OF THE DOC',
  `content` longtext COMMENT 'CONTENT OF THE DOC',
  `reference` text COMMENT 'REFERENCE OF THE DOC',
  `state` varchar(10) NOT NULL DEFAULT 'ONLINE' COMMENT 'STATE OF THE DOC',
  `created_by` varchar(32) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_by` varchar(32) NOT NULL,
  `modified_on` datetime NOT NULL,
  `guid` char(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `CID` (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `doc_reply`
--

DROP TABLE IF EXISTS `doc_reply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_reply` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(128) NOT NULL COMMENT 'TITLE OF THE REPLY',
  `description` text NOT NULL COMMENT 'CONTENT OF THE REPLY',
  `telephone` varchar(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `state` varchar(10) NOT NULL DEFAULT 'ONLINE' COMMENT 'STATE OF THE REPLY',
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `doc_user`
--

DROP TABLE IF EXISTS `doc_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(32) NOT NULL,
  `password` char(32) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'WHEATHER_IS_ADMIN',
  `realname` varchar(30) DEFAULT NULL,
  `department_name` varchar(100) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `extended` longtext,
  `state` varchar(10) NOT NULL DEFAULT 'ONLINE' COMMENT 'STATE OF THE USER',
  `modified_by` varchar(32) NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_user`
--

LOCK TABLES `doc_user` WRITE;
/*!40000 ALTER TABLE `doc_user` DISABLE KEYS */;
INSERT INTO `doc_user` VALUES (1,'admin','a236ff7114b95d0134dab4ef5712f8a4',1,'admin',NULL,NULL,NULL,'ONLINE','admin',now());
/*!40000 ALTER TABLE `doc_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-11-26 14:11:37

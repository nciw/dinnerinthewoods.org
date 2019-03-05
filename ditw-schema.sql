CREATE DATABASE  IF NOT EXISTS `ditw` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `ditw`;
-- MySQL dump 10.13  Distrib 5.7.25, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: ditw
-- ------------------------------------------------------
-- Server version	8.0.13

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
-- Table structure for table `guests`
--

DROP TABLE IF EXISTS `guests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `name` varchar(64) NULL,
  `email` varchar(64) NULL,
  `phone` varchar(64) NULL,
  `childcare` tinyint(4) NULL,
  `valet` tinyint(4) NULL,
  `restrictions` tinyint(4) NULL,
  `table` int(11) NULL,
  `paddle` int(11) NULL,
  `stripe_id` varchar(64) NULL,
  `uuid` varchar(64) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_UNIQUE` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guests`
--

LOCK TABLES `guests` WRITE;
/*!40000 ALTER TABLE `guests` DISABLE KEYS */;
/*!40000 ALTER TABLE `guests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_quantity` int(11) NOT NULL,
  `enhancer_quantity` int(11) NOT NULL DEFAULT '0',
  `ticket_cents` int(11) NOT NULL DEFAULT '0',
  `enhancer_cents` int(11) NOT NULL DEFAULT '0',
  `additional_cents` int(11) NOT NULL DEFAULT '0',
  `total_cents` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `email` varchar(64) NOT NULL,
  `address` varchar(45) NOT NULL,
  `city` varchar(45) NOT NULL,
  `state` varchar(45) NOT NULL,
  `zip` varchar(45) NOT NULL,
  `payment_type` int(11) NOT NULL DEFAULT '0',
  `stripe_token` varchar(64) NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uuid` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_UNIQUE` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `value` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'remainingTickets','400');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-03 20:25:43

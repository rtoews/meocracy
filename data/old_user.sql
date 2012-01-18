-- MySQL dump 10.11
--
-- Host: meocracy.db.3410806.hostedresource.com    Database: meocracy
-- ------------------------------------------------------
-- Server version	5.0.91-log

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
-- Table structure for table `old_user`
--

DROP TABLE IF EXISTS `old_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `old_user` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `subscribed` tinyint(1) NOT NULL,
  `entity_id` int(8) default NULL,
  `office_id` int(8) default NULL,
  `title` text,
  `salutation` text,
  `name_last` text,
  `name_first` text,
  `name_middle` text,
  `mobile_area` int(3) NOT NULL,
  `mobile_prefix` int(3) NOT NULL,
  `mobile_suffix` int(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `old_user`
--

LOCK TABLES `old_user` WRITE;
/*!40000 ALTER TABLE `old_user` DISABLE KEYS */;
INSERT INTO `old_user` VALUES (1,1,1,1,'Council Member','Council Member','phillips','william','bill',949,395,2309),(2,1,0,0,'','','Citizen','Jane','',949,395,2009),(3,1,0,0,'','','Dunning','Karine','',949,395,2009),(4,1,0,0,'','','Farnam','Courday','',949,702,7136);
/*!40000 ALTER TABLE `old_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-01-09  0:36:46

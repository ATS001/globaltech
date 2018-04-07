/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.6.17 : Database - globaltech
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`globaltech` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `globaltech`;

/*Table structure for table `action_ticket` */

DROP TABLE IF EXISTS `action_ticket`;

CREATE TABLE `action_ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `id_ticket` int(11) DEFAULT NULL COMMENT 'Ticket',
  `message` text COMMENT 'Description',
  `date_action` date DEFAULT NULL COMMENT 'Date',
  `photo` int(11) DEFAULT NULL COMMENT 'Photo',
  `pj` int(11) DEFAULT NULL COMMENT 'Pièce jointe',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `action_ticket` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

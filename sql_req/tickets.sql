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

/*Table structure for table `tickets` */

DROP TABLE IF EXISTS `tickets`;

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `id_client` int(11) DEFAULT NULL COMMENT 'Client',
  `projet` varchar(200) DEFAULT NULL COMMENT 'Projet',
  `message` text COMMENT 'Message',
  `date_previs` date DEFAULT NULL COMMENT 'Date prévisionnelle',
  `date_realis` date DEFAULT NULL COMMENT 'Date de réalisation',
  `type_produit` int(11) DEFAULT NULL COMMENT 'Type produit',
  `categorie_produit` int(11) DEFAULT NULL COMMENT 'Catégorie produit',
  `id_produit` int(11) DEFAULT NULL COMMENT 'Produit',
  `id_technicien` int(11) DEFAULT NULL COMMENT 'Technicien',
  `date_affectation` date DEFAULT NULL COMMENT 'Date affectation',
  `code_cloture` int(200) DEFAULT NULL COMMENT 'Code cloture',
  `observation` varchar(200) DEFAULT NULL COMMENT 'observation',
  `serial_number` varchar(11) DEFAULT NULL COMMENT 'Serial number',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_client_ticket` (`id_client`),
  KEY `fk_cproduit_ticket` (`categorie_produit`),
  KEY `fk_tproduit_ticket` (`type_produit`),
  KEY `fk_user_ticket` (`id_technicien`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

/*Data for the table `tickets` */

insert  into `tickets`(`id`,`id_client`,`projet`,`message`,`date_previs`,`date_realis`,`type_produit`,`categorie_produit`,`id_produit`,`id_technicien`,`date_affectation`,`code_cloture`,`observation`,`serial_number`,`etat`,`creusr`,`credat`,`updusr`,`upddat`) values (34,27,'Projet Farcha','<p>XXXX<br></p>','2018-04-26','2018-04-28',2,12,26,20,'2018-04-26',2,'OK',NULL,3,1,'2018-04-25 11:16:17',1,'2018-04-28 01:38:34'),(35,27,'FFF','TTT','2018-04-29','2018-04-28',2,12,26,19,'2018-04-28',2,'test ras',NULL,3,1,'2018-04-28 00:42:58',1,'2018-04-28 01:36:49'),(36,27,'PRRR','<p>XXXXXXXXXxx<br></p>','2018-04-29','2018-04-29',1,0,22,2,'2018-04-29',2,'<p>hhhh<br></p>',NULL,3,1,'2018-04-28 01:07:16',1,'2018-04-29 18:39:31'),(37,27,'Projet Farcha','<p>hhhh<br></p>','2018-04-29',NULL,3,11,25,1,NULL,NULL,NULL,NULL,1,1,'2018-04-28 01:38:04',NULL,NULL),(38,26,'cccckkk','<p>LLL<br></p>','2018-04-29','2018-04-28',2,0,32,19,'2018-04-28',2,'FRFR',NULL,3,1,'2018-04-28 01:39:18',1,'2018-04-28 21:53:59'),(39,27,'Optionnel ','<p>Le client PREPAS modem sn XXXX a signalé un problème de connexion depuis ce matin.&nbsp;</p><p><br></p>','2018-04-29','2018-04-28',3,11,0,1,'2018-04-28',2,'Tout est ok ',NULL,3,1,'2018-04-28 15:50:07',1,'2018-04-28 17:38:31'),(40,30,'Projet Farchahhh','<p>bbbbbbbbbbbbbbbbggg<br></p>','2018-05-04','2018-04-29',1,9,22,24,'2018-04-29',2,'<p>BBBB<br></p>',NULL,3,1,'2018-04-29 17:26:17',1,'2018-04-29 18:21:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

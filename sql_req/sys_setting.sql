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

/*Table structure for table `sys_setting` */

DROP TABLE IF EXISTS `sys_setting`;

CREATE TABLE `sys_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant de ligne',
  `key` varchar(30) DEFAULT '242' COMMENT 'clé paramètre',
  `value` varchar(200) CHARACTER SET latin1 NOT NULL COMMENT 'Valeur Paramètre',
  `comment` varchar(250) DEFAULT NULL COMMENT 'Description',
  `modul` int(11) NOT NULL COMMENT 'Module qui utilise paramètre',
  `etat` int(2) NOT NULL DEFAULT '1' COMMENT 'l''etat de la ligne',
  `creusr` varchar(60) NOT NULL COMMENT 'Crée par',
  `credat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updusr` varchar(60) DEFAULT NULL COMMENT 'Derniere modif par',
  `upddat` datetime DEFAULT NULL COMMENT 'Date derniere modif',
  PRIMARY KEY (`id`),
  KEY `fk_region_pays` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `sys_setting` */

insert  into `sys_setting`(`id`,`key`,`value`,`comment`,`modul`,`etat`,`creusr`,`credat`,`updusr`,`upddat`) values (2,'test','array(\'1\'=>\'val1\', \'2\'=>\'val2\')','description test',106,1,'1','2017-10-04 15:00:41','1','2017-10-04 15:10:40'),(3,'par2','{\"1\":\"val1\", \"2\":\"val2\"}','Test array',104,1,'1','2017-10-04 15:33:40','1','2017-10-04 15:51:42'),(4,'etat_valid_devis','2','L\'etat où le devis est validé pour exploitation dans le contrat',105,1,'1','2017-10-06 15:01:26',NULL,NULL),(5,'abr_ste','GT','Abréviation  du nom Ste pour les Références documents',5,1,'1','2017-10-09 15:22:50',NULL,NULL),(6,'send_mail_devis','false','Envoi devis par email ',115,1,'1','2017-10-10 11:33:06','1','2017-10-10 11:40:47'),(7,'etat_valid_devis','3','L\'etat où le devis est validé pour exploitation dans le contrat',115,1,'1','2017-10-10 11:44:31',NULL,NULL),(8,'etat_devis','{\"creat_devis\":\"0\", \"valid_devis\":\"1\",  \"send_devis\":\"2\", \"modif_client\": \"3\", \"valid_client\":\"4\", \"refus_client\":\"5\", \"devis_expir\":\"6\", \"devis_archive\":\"7\"}','Les différents etat de WF devis',115,1,'1','2017-10-12 12:33:14','1','2017-10-12 14:26:11'),(9,'etat_proforma','{\"creat_proforma\":\"0\", \"valid_proforma\":\"1\",  \"send_proforma\":\"2\", \"proforma_expir\":\"3\", \"proforma_archive\":\"4\"}','Les différents etat de WF Proforma',127,1,'1','2017-10-16 00:01:26',NULL,NULL),(10,'etat_devis','{\"0\":\"creat_devis\",\" 1\":\"valid_devis\", \"2\":\"send_devis\", \"3\":\"reponse_client\", \"4\":\"valid_client\", \"5\":\"refus_client\",\" 6\":\"devis_expir\", \"7\":\"devis_archive\"}','Les différents etat de WF devis',121,1,'1','2017-10-16 00:01:56',NULL,NULL),(11,'send_mail_devis','false','Envoi devis par email',121,1,'1','2017-10-16 00:02:40',NULL,NULL),(12,'tva','18','Valeur TVA',93,1,'1','2017-10-17 01:38:52',NULL,NULL),(13,'etat_commission','{\"0\":\"attente_payement\",\"1\":\"payer_part\",\"2\":\"payer_total\"}','Les différents etat du WF  de la commission',130,1,'1','2018-01-04 19:44:28','1','2018-01-04 19:51:00'),(14,'commission','{\"attente_validation\":\"0\",\"attente_payement\":\"1\",\"payer_part\":\"2\",\"payer_total\":\"3\"}','commission',1,1,'1','2018-01-09 01:08:10','1','2018-01-09 01:39:51'),(15,'etat_ticket','{\"attente_affectation\":\"0\",\"resolution_encours\":\"1\",\"resolution_termine\":\"2\",\"ticket_cloturer\":\"3\"}','tickets',133,1,'1','2018-04-24 02:22:48',NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

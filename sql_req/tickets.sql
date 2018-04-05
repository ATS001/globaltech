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
  `id_technicien` int(11) DEFAULT NULL COMMENT 'Technicien',
  `date_affectation` date DEFAULT NULL COMMENT 'Date affectation',
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tproduit_ticket` (`type_produit`),
  KEY `fk_cproduit_ticket` (`categorie_produit`),
  KEY `fk_user_ticket` (`id_technicien`),
  KEY `fk_client_ticket` (`id_client`),
  CONSTRAINT `fk_client_ticket` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cproduit_ticket` FOREIGN KEY (`categorie_produit`) REFERENCES `ref_categories_produits` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tproduit_ticket` FOREIGN KEY (`type_produit`) REFERENCES `ref_types_produits` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_ticket` FOREIGN KEY (`id_technicien`) REFERENCES `users_sys` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `tickets` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

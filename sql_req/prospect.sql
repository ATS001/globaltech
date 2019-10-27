/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.7.24 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table IF NOT EXISTS `prospects` (
	`id` int (11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
	`reference` varchar (60) COMMENT 'Réference',
	`id_commercial` int (11) COMMENT 'Commercial',
	`raison_sociale` varchar (3000) COMMENT 'Raison Sociale',
	`offre` int (11) COMMENT 'Offre',
	`ca_previsionnel` double COMMENT 'CA Previsionnel',
	`ponderation` double COMMENT 'Pondération',
	`ca_pondere` double COMMENT 'CA Pondéré',
	`date_entree` date COMMENT 'Date Entrée',
	`date_cible` date COMMENT 'Date Cible',
	`statut_deal` varchar (600)COMMENT 'Statut Deal',
	`commentaires` varchar (6000)COMMENT 'Commentaires',
	`etat` int(11) DEFAULT '0',
	`creusr` int(11) DEFAULT NULL,
	`credat` datetime DEFAULT CURRENT_TIMESTAMP,
	`updusr` int(11) DEFAULT NULL,
	`upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_offre` (`offre`)
  KEY `fk_commercial` (`id_commercial`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=165 ; 

ALTER TABLE `prospects`
  ADD CONSTRAINT `fk_offre` FOREIGN KEY (`offre`) REFERENCES `categorie_client` (`id`) ON UPDATE CASCADE;
ALTER TABLE `prospects`
  ADD CONSTRAINT `fk_commercial` FOREIGN KEY (`id_commercial`) REFERENCES `commerciaux` (`id`) ON UPDATE CASCADE;
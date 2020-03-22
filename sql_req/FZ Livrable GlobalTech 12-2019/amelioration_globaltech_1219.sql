--Global Tel et Tech:
--*********************************************************************
--Ajout de id_devise dans devis et facture --OK PROD
ALTER TABLE ste_info ADD COLUMN  ste_id_devise INT AFTER ste_website;

ALTER TABLE ste_info ADD CONSTRAINT fk_ste_ref_devise
FOREIGN KEY (ste_id_devise) REFERENCES ref_devise(id);
---------------------------------------------------------------------
--OK PROD
ALTER TABLE clients ADD COLUMN  id_banque INT AFTER email;

ALTER TABLE clients ADD CONSTRAINT fk_client_info_banque
FOREIGN KEY (id_banque) REFERENCES ste_info_banque(id);

UPDATE clients SET id_banque=1;
---------------------------------------------------------------------
--OK PROD
ALTER TABLE devis ADD COLUMN  id_banque INT AFTER id_client;

ALTER TABLE devis ADD CONSTRAINT fk_devis_client_info_banque
FOREIGN KEY (id_banque) REFERENCES ste_info_banque(id);

UPDATE devis SET id_banque=1;

ALTER TABLE devis ADD COLUMN  id_devise INT AFTER id_client;

ALTER TABLE devis ADD CONSTRAINT fk_devis_ref_devise
FOREIGN KEY (id_devise) REFERENCES ref_devise(id);

UPDATE devis SET id_devise=1;
---------------------------------------------------------------------
--OK PROD
ALTER TABLE d_devis ADD COLUMN taux_change DOUBLE(12,12) AFTER designation;

ALTER TABLE d_devis ADD COLUMN pu_devise_pays DOUBLE AFTER qte;
---------------------------------------------------------------------
--OK PROD
ALTER TABLE factures ADD COLUMN  id_banque INT AFTER CLIENT;

ALTER TABLE factures ADD CONSTRAINT fk_facture_client_info_banque
FOREIGN KEY (id_banque) REFERENCES ste_info_banque(id);

UPDATE factures SET id_banque=1;

ALTER TABLE factures ADD COLUMN  id_devise INT AFTER CLIENT;

ALTER TABLE factures ADD CONSTRAINT fk_facture_ref_devise
FOREIGN KEY (id_devise) REFERENCES ref_devise(id);

UPDATE factures SET id_devise=1;

---------------------------------------------------------------------
-- 
-- Structure de la table `ste_info_banque` --OK PROD
-- 

DROP TABLE IF EXISTS `ste_info_banque`;
CREATE TABLE IF NOT EXISTS `ste_info_banque` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ste` int(11) NOT NULL,
  `banque` varchar(50) NOT NULL,
  `rib` varchar(50) NOT NULL,
  `etat` int(11) DEFAULT NULL,
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_banque_ste_info` (`id_ste`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- 
-- Déchargement des données de la table `ste_info_banque`
-- 

INSERT INTO `ste_info_banque` (`id`, `id_ste`, `banque`, `rib`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 1, 'SGT', '04271284701-75', 1, 1, '2019-11-30 19:30:36', NULL, NULL),
(2, 1, 'ORABANK', '20403500201-37', 1, 1, '2019-11-30 19:46:10', NULL, NULL),
(3, 1, 'ECOBANK', '03214040801-62', 1, 1, '2019-12-04 19:21:58', NULL, NULL);

-- 
-- Contraintes pour la table `ste_info_banque`
-- 
ALTER TABLE `ste_info_banque`
  ADD CONSTRAINT `fk_banque_ste_info` FOREIGN KEY (`id_ste`) REFERENCES `ste_info` (`id`) ON UPDATE CASCADE; 
--------------------------------------------------------------------------
--
-- Structure de la table `sys_taux_change` --OK PROD
--

DROP TABLE IF EXISTS `sys_taux_change`;
CREATE TABLE IF NOT EXISTS `sys_taux_change` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `id_devise` int(11) NOT NULL COMMENT 'Devise',
  `conversion` decimal(12,12) DEFAULT NULL COMMENT 'Conversion',
  `etat` int(11) DEFAULT '0' COMMENT 'Statut',
  `creusr` int(11) NOT NULL COMMENT 'Créé Par',
  `credat` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Créé Le',
  `updusr` int(11) DEFAULT NULL COMMENT 'Modifié Par',
  `upddat` datetime DEFAULT NULL COMMENT 'Modifié Le',
  PRIMARY KEY (`id`),
  KEY `fk_taux_change_devise` (`id_devise`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `sys_taux_change`
--

INSERT INTO `sys_taux_change` (`id`, `id_devise`, `conversion`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 2, '61.4816', 0, 1, '2019-12-12 19:35:18', 1, '2019-12-12 20:35:23'),
(2, 3, '655.9570', 0, 1, '2019-12-12 20:40:59', NULL, NULL),
(3, 4, '590.4660', 0, 1, '2019-12-12 20:42:52', NULL, NULL);


ALTER TABLE  `sys_taux_change`  ADD CONSTRAINT fk_taux_ref_devise
FOREIGN KEY (id_devise) REFERENCES ref_devise(id);
-- --------------------------------------------------------
ALTER TABLE encaissements ADD montant_devise_ext INT NULL AFTER depositaire;--OK PROD

-- --------------------------------------------------------

------------Procédure -------------------------------------------

DROP PROCEDURE generate_fact --OK PROD
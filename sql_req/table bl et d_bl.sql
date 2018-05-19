- --------------------------------------------------------

--
-- Structure de la table `bl`
--

CREATE TABLE IF NOT EXISTS `bl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(20) DEFAULT NULL,
  `client` varchar(100) DEFAULT NULL,
  `projet` varchar(200) DEFAULT NULL COMMENT 'designation projet',
  `ref_bc` varchar(200) DEFAULT NULL COMMENT 'ref bon commande client',
  `iddevis` int(11) DEFAULT NULL COMMENT 'Devis',
  `date_bl` date DEFAULT NULL,
  `etat` int(11) DEFAULT '0',
  `creusr` int(11) DEFAULT NULL,
  `credat` datetime DEFAULT CURRENT_TIMESTAMP,
  `updusr` int(11) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_devis_bl` (`iddevis`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `bl`
--

INSERT INTO `bl` (`id`, `reference`, `client`, `projet`, `ref_bc`, `iddevis`, `date_bl`, `etat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 'GT-BL-0001/2018', 'DCT', 'PROJ', 'Ref', 54, '2018-05-02', 0, 1, '2018-05-02 10:39:19', NULL, NULL),
(2, 'GT-BL-0002/2018', 'Test2018', NULL, 'OKK', 61, '2018-05-02', 1, 1, '2018-05-02 10:40:13', 1, '2018-05-08 23:28:18'),
(3, 'GT-BL-0003/2018', 'DCT', 'Te', 'ok', 67, '2018-05-07', 2, 1, '2018-05-07 00:18:38', 1, '2018-05-07 01:23:54'),
(6, 'GT-BL-0004/2018', 'Test2018', NULL, 'OKK', 61, '2018-05-13', 0, 1, '2018-05-13 00:18:15', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `d_bl`
--

CREATE TABLE IF NOT EXISTS `d_bl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(5) DEFAULT NULL,
  `id_bl` int(11) DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `ref_produit` varchar(20) DEFAULT NULL,
  `designation` varchar(40) DEFAULT NULL,
  `qte_cmd` int(11) DEFAULT NULL COMMENT 'la quantité commandée sur le devis et la facture',
  `qte_livre` int(11) DEFAULT '0' COMMENT 'la quantité livrée',
  `reliquat` int(11) DEFAULT NULL COMMENT 'le reste à livrer',
  `creusr` varchar(50) DEFAULT NULL,
  `credat` datetime DEFAULT NULL,
  `updusr` varchar(50) DEFAULT NULL,
  `upddat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_produit` (`id_produit`),
  KEY `fk_factures` (`id_bl`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `d_bl`
--

INSERT INTO `d_bl` (`id`, `order`, `id_bl`, `id_produit`, `ref_produit`, `designation`, `qte_cmd`, `qte_livre`, `reliquat`, `creusr`, `credat`, `updusr`, `upddat`) VALUES
(1, 1, 2, 30, 'GT_PRD-0008', 'iDIRECT EVOLUTION X5', 5, 0, 5, '1', '2018-05-02 00:00:00', NULL, NULL),
(2, 2, 2, 22, 'GT-PRD-1/2017', 'LNB PLL bande C', 10, 0, 10, '1', '2018-05-02 00:00:00', NULL, NULL),
(3, 3, 2, 24, 'GT-PRD-3/2017', 'Antenne VSAT 1.2m bande Ku Skyware Globa', 5, 0, 5, '1', '2018-05-02 00:00:00', NULL, NULL),
(4, 1, 3, 1, 'GT_PRD-0008', 'iDIRECT EVOLUTION X6', 1, 0, 1, '1', '2018-05-07 00:00:00', NULL, NULL);

-- --------------------------------------------------------

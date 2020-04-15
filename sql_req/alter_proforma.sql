ALTER TABLE `globatxd_globaltel`.`proforma`   
  ADD CONSTRAINT `fk_id_banque_proforma` FOREIGN KEY (`id_banque`) REFERENCES `globatxd_globaltel`.`ste_info_banque`(`id`),
  ADD CONSTRAINT `fk_id_devise_proforma` FOREIGN KEY (`id_devise`) REFERENCES `globatxd_globaltel`.`ref_devise`(`id`),
  ADD CONSTRAINT `fk_id_client_proforma` FOREIGN KEY (`id_client`) REFERENCES `globatxd_globaltel`.`clients`(`id`) ON UPDATE CASCADE;
  
  
  
  ALTER TABLE `globatxd_globaltel`.`proforma`   
  ADD COLUMN `type_commission_ex` VARCHAR(1) NULL AFTER `total_commission`,
  ADD COLUMN `commission_ex` INT NULL AFTER `type_commission_ex`,
  ADD COLUMN `total_commission_ex` INT NULL AFTER `commission_ex`,
  ADD COLUMN `id_commercial_ex` INT NULL AFTER `total_commission_ex`,
  ADD COLUMN `total_remise` INT NULL AFTER `type_remise`,
  ADD COLUMN `projet` VARCHAR(200) NULL AFTER `reference`,  
  CHANGE `id_commercial` `id_commercial` VARCHAR(100) NULL   COMMENT 'commercial chargé du suivi',
  CHANGE `commission` `commission` INT(11) DEFAULT 0  NULL   COMMENT 'commission(%) du commercial';
	
	ALTER TABLE `globatxd_globaltel`.`d_proforma`   
    ADD COLUMN `taux_change` DOUBLE(12,12) NULL AFTER `designation`,
    ADD COLUMN `pu_devise_pays` DOUBLE NULL AFTER `qte`;
	
	UPDATE proforma SET id_commercial = CONCAT('["', id_commercial, '"]')
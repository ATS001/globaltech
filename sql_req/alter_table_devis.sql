ALTER TABLE `devis`   
  ADD COLUMN `date_valid_client` DATE NULL   COMMENT 'Date validation client' AFTER `date_devis`;
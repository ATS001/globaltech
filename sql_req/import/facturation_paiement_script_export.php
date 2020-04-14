<?php 
//Export Module 'facturation_paiement' Date: 12-04-2020
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('facturation_paiement', 'Gestion Facturation','facturation_paiement/main','factures,encaissement','facturation_paiement',NULL,'0', '0', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'facturation_paiement' </li>";}
  //Task 'facturation_paiement' 'Gestion FP'
  if(!$result_task_1225 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('facturation_paiement', $result_insert_modul, 'facturation_paiement','facturation_paiement/main', '1', 'Gestion FP', 'eye', '1', '0', '0','list', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion FP' </li>";}
      // Action Task 1225 - 'Gestion FP'
      if(!$result_action_2157 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1225, '8a8f7c0212016b4685e154af71237453', 'Gestion FP','facturation_paiement', NULL, '".NULL."', '1', '[-1-2-3-5-4-7-6-]', '0', '0', 'Attente Validation','warning','".'Attente Validation'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion FP' </li>";}

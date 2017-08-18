<?php 
//Export Module 'anonymes' Date: 13-06-2017z
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('anonymes', 'Anonymes','anonymes','prm_anonyme','anonymes',NULL,'0', '0', '[-1-2-17-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'anonymes' </li>";}
  //Task 'anonymes' 'Anonymes'
  if(!$result_task_102 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('anonymes', $result_insert_modul, 'anonymes','anonymes', '1', 'Anonymes', 'eye-slash', '1', '0', '0','list', '[-1-2-17-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Anonymes' </li>";}
      // Action Task 102 - 'Anonymes'
      if(!$result_action_135 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_102, 'd429aa0325fd3bb72ffcef76c0662878', 'Anonymes', '".NULL."', '1', '[-1-2-17-]', '1', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Anonymes' </li>";}
      // Action Task 102 - 'Valider Anonyme'
      if(!$result_action_136 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_102, 'a4e8c04c7d10e8d1633aaaa9d55fa619', 'Valider Anonyme', '".'<li><a href="#" class="this_exec" data="%id%" rel="validanonyme"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Anonyme</a></li>'."', '0', '[-1-]', '0', '1', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Anonyme' </li>";}
      // Action Task 102 - 'Modifier Anonyme'
      if(!$result_action_137 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_102, 'd575f186cd6c93b06ca7e6a0d9b1b569', 'Modifier Anonyme', '".'<li><a href="#" class="this_url" data="%id%" rel="editanonyme"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Modifier Anonyme</a></li>'."', '0', '[-1-]', '0', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier Anonyme' </li>";}
  //Task 'addanonyme' 'Ajouter un anonyme'
  if(!$result_task_103 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addanonyme', $result_insert_modul, 'addanonyme','anonymes', '1', 'Ajouter un anonyme', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter un anonyme' </li>";}
      // Action Task 103 - 'Ajouter un anonyme'
      if(!$result_action_139 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_103, '7ca8302af00b5acfda18320f9a5347fb', 'Ajouter un anonyme', '".NULL."', '1', '[-1-]', '1', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter un anonyme' </li>";}
  //Task 'editanonyme' 'Modifier Anonyme'
  if(!$result_task_104 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editanonyme', $result_insert_modul, 'editanonyme','anonymes', '1', 'Modifier Anonyme', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier Anonyme' </li>";}
      // Action Task 104 - 'Modifier Anonyme'
      if(!$result_action_140 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_104, 'f9709918cb20657564aab53988a56113', 'Modifier Anonyme', '".NULL."', '1', '[-1-]', '1', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier Anonyme' </li>";}
  //Task 'deleteanonyme' 'Supprimer Anonyme'
  if(!$result_task_105 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deleteanonyme', $result_insert_modul, 'deleteanonyme','anonymes', '1', 'Supprimer Anonyme', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer Anonyme' </li>";}
      // Action Task 105 - 'Supprimer Anonyme'
      if(!$result_action_141 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_105, '943e53ed64ca0abf7b915d99f45a12f1', 'Supprimer Anonyme', '".NULL."', '1', '[-1-]', '1', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer Anonyme' </li>";}
  //Task 'validanonyme' 'Valider Anonyme'
  if(!$result_task_106 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validanonyme', $result_insert_modul, 'validanonyme','anonymes', '1', 'Valider Anonyme', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider Anonyme' </li>";}
      // Action Task 106 - 'Valider Anonyme'
      if(!$result_action_142 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_106, 'ee0a32742ea57f8c15e7fd52cf3b5b6a', 'Valider Anonyme', '".NULL."', '1', '[-1-]', '1', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Anonyme' </li>";}

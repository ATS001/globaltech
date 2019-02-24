<?php 
//Export Module 'sites' Date: 25-02-2019
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('sites', 'Gestion des sites','sites/main','site_vsat,site_radio','sites',NULL,'0', '0', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'sites' </li>";}
  //Task 'sites' 'Gestion des sites'
  if(!$result_task_1061 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('sites', $result_insert_modul, 'sites','sites/main', '1', 'Gestion des sites', 'building ', '1', '0', '0','list', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion des sites' </li>";}
      // Action Task 1061 - 'Gestion des sites'
      if(!$result_action_1810 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1061, '52bd3a5983e220a0373c32d9b1bd7ec5', 'Gestion des sites','sites', NULL, '".NULL."', '1', '[-1-2-3-5-4-7-6-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion des sites' </li>";}
      // Action Task 1061 - 'Modifier Site'
      if(!$result_action_1813 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1061, 'c8b7752679d6afc428f150e3c6e6753b', 'Modifier Site','updatesite', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="updatesite"  ><i class="ace-icon fa fa-pencil-square-o bigger-100"></i> Modifier Site</a></li>'."', '0', '[-1-2-3-5-4-7-6-]', '0', '1', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier Site' </li>";}
      // Action Task 1061 - 'Valider site'
      if(!$result_action_1817 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1061, 'b748f9a7b4be866a0ee77560af50e614', 'Valider site','validsite', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validsite"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider site</a></li>'."', '0', '[-1-2-3-5-4-7-6-]', '0', '1', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider site' </li>";}
      // Action Task 1061 - 'Détails site'
      if(!$result_action_1819 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1061, '44c989cc59f441187b774c6517a81ea0', 'Détails site','detailssite', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailssite"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails site</a></li>'."', '0', '[-1-2-3-5-4-7-6-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails site' </li>";}
      // Action Task 1061 - 'Détails site '
      if(!$result_action_1820 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1061, 'd2bfd8ce7998c78de57a27b52d10584d', 'Détails site ','detailssite', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailssite"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails site </a></li>'."', '0', '[-1-2-3-5-4-7-6-]', '1', '0', 'Site Validé','success','".'<span class="label label-sm label-success">Site Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails site ' </li>";}
  //Task 'addsites' 'Ajouter site'
  if(!$result_task_1062 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addsites', $result_insert_modul, 'addsites','sites/main', '1', 'Ajouter site', 'cogs', '1', '0', '0','formadd', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter site' </li>";}
      // Action Task 1062 - 'Ajouter site'
      if(!$result_action_1811 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1062, 'c76ca9e55fa937be28451e7d9c4e8b1a', 'Ajouter site','addsites', NULL, '".NULL."', '1', '[-1-2-3-5-4-7-6-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter site' </li>";}
  //Task 'updatesite' 'Modifier Site'
  if(!$result_task_1063 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('updatesite', $result_insert_modul, 'updatesite','sites/main', '1', 'Modifier Site', 'building', '1', '0', '0','formedit', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier Site' </li>";}
      // Action Task 1063 - 'Modifier Site'
      if(!$result_action_1812 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1063, '87d9dec13563ffc9b8cd1b39d29823f4', 'Modifier Site','updatesite', NULL, '".NULL."', '1', '[-1-2-3-5-4-7-6-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier Site' </li>";}
  //Task 'deletesite' 'Supprimer site'
  if(!$result_task_1064 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletesite', $result_insert_modul, 'deletesite','sites/main', '1', 'Supprimer site', 'building', '1', '0', '0','exec', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer site' </li>";}
      // Action Task 1064 - 'Supprimer site'
      if(!$result_action_1814 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1064, '3dbeae19beb272c8f9306e7b7556654b', 'Supprimer site','deletesite', NULL, '".NULL."', '1', '[-1-2-3-5-4-7-6-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer site' </li>";}
  //Task 'validsite' 'Valider site'
  if(!$result_task_1065 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validsite', $result_insert_modul, 'validsite','sites/main', '1', 'Valider site', 'cogs', '1', '0', '0','exec', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider site' </li>";}
      // Action Task 1065 - 'Valider site'
      if(!$result_action_1816 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1065, '89e474d40a7947929be2ad09cf934e60', 'Valider site','validsite', NULL, '".NULL."', '1', '[-1-2-3-5-4-7-6-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider site' </li>";}
  //Task 'detailssite' 'Details site'
  if(!$result_task_1066 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detailssite', $result_insert_modul, 'detailssite','sites/main', '1', 'Details site', 'cogs', '1', '0', '0','profil', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Details site' </li>";}
      // Action Task 1066 - 'Details site'
      if(!$result_action_1818 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1066, 'c3c2c64bdad159a572f5e9e58f9d1a94', 'Details site','detailssite', NULL, '".NULL."', '1', '[-1-2-3-5-4-7-6-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Details site' </li>";}

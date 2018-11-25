<?php 
//Export Module 'objectif_service' Date: 25-11-2018
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('objectif_service', 'Objectifs Service','objectifs/submodul/objectif_service','objectif_service','objectif_service','objectifs','2', '0', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'objectif_service' </li>";}
  //Task 'objectif_service' 'Objectifs Service'
  if(!$result_task_1077 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('objectif_service', $result_insert_modul, 'objectif_service','objectifs/submodul/objectif_service', '1', 'Objectifs Service', 'bar-chart-o', '1', '0', '0','list', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Objectifs Service' </li>";}
      // Action Task 1077 - 'Objectifs Service'
      if(!$result_action_1840 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1077, 'ce7a65f39c6a9641b7f71853ce726624', 'Objectifs Service','objectif_service', NULL, '".NULL."', '1', '[-1-2-3-7-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Objectifs Service' </li>";}
      // Action Task 1077 - 'Valider Objectif'
      if(!$result_action_1844 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1077, 'cab28d894596927374eabcd445f80e3f', 'Valider Objectif','valid_objectif_service', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="valid_objectif_service"  ><i class="ace-icon fa fa-check bigger-100"></i> Valider Objectif</a></li>'."', '0', '[-1-2-3-7-]', '0', '1', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Objectif' </li>";}
      // Action Task 1077 - 'Modifier Objectif'
      if(!$result_action_1845 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1077, '45378fbcf10186649eeffb9482c9fdf0', 'Modifier Objectif','edit_objectif_service', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="edit_objectif_service"  ><i class="ace-icon fa fa-pencil-square-o blue bigger-100"></i> Modifier Objectif</a></li>'."', '0', '[-1-2-3-7-]', '0', '1', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier Objectif' </li>";}
      // Action Task 1077 - 'Détail Objectif'
      if(!$result_action_1847 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1077, 'af44f2001b5634f05151d6236763a242', 'Détail Objectif','detail_objectif_service', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detail_objectif_service"  ><i class="ace-icon fa fa-eye blue bigger-100"></i> Détail Objectif</a></li>'."', '0', '[-1-2-3-7-]', '0', '1', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail Objectif' </li>";}
      // Action Task 1077 - 'Détail Objectif'
      if(!$result_action_1848 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1077, 'ac270a34fa2aea5779b6458579b147f0', 'Détail Objectif','detail_objectif_service', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detail_objectif_service"  ><i class="ace-icon fa fa-eye blue bigger-100"></i> Détail Objectif</a></li>'."', '0', '[-1-2-3-7-]', '1', '0', 'Objectif en cours','success','".'<span class="label label-sm label-success">Objectif en cours</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail Objectif' </li>";}
  //Task 'add_objectif_service' 'Ajouter Objectif Service'
  if(!$result_task_1078 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('add_objectif_service', $result_insert_modul, 'add_objectif_service','objectifs/submodul/objectif_service', '1', 'Ajouter Objectif Service', 'plus', '1', '0', '0','formadd', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Objectif Service' </li>";}
      // Action Task 1078 - 'Ajouter Objectif Service'
      if(!$result_action_1841 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1078, '3d80d692290d386078773b5e70960b0d', 'Ajouter Objectif Service','add_objectif_service', NULL, '".NULL."', '1', '[-1-2-3-7-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter Objectif Service' </li>";}
  //Task 'edit_objectif_service' 'Modifier Objectif'
  if(!$result_task_1079 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('edit_objectif_service', $result_insert_modul, 'edit_objectif_service','objectifs/submodul/objectif_service', '1', 'Modifier Objectif', 'pen', '1', '0', '0','formedit', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier Objectif' </li>";}
      // Action Task 1079 - 'Modifier Objectif'
      if(!$result_action_1842 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1079, '851fb22fd86860448f57e91b28b0bbc1', 'Modifier Objectif','edit_objectif_service', NULL, '".NULL."', '1', '[-1-2-3-7-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier Objectif' </li>";}
  //Task 'valid_objectif_service' 'Valider Objectif'
  if(!$result_task_1080 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('valid_objectif_service', $result_insert_modul, 'valid_objectif_service','objectifs/submodul/objectif_service', '1', 'Valider Objectif', 'check', '1', '0', '0','exec', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider Objectif' </li>";}
      // Action Task 1080 - 'Valider Objectif'
      if(!$result_action_1843 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1080, 'e455035b810042644ec403be2e066137', 'Valider Objectif','valid_objectif_service', NULL, '".NULL."', '1', '[-1-3-7-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Objectif' </li>";}
  //Task 'detail_objectif_service' 'Détail Objectif'
  if(!$result_task_1081 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detail_objectif_service', $result_insert_modul, 'detail_objectif_service','objectifs/submodul/objectif_service', '1', 'Détail Objectif', 'eye green', '1', '0', '0','profil', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détail Objectif' </li>";}
      // Action Task 1081 - 'Détail Objectif'
      if(!$result_action_1846 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1081, 'e9b9502d5d92a34f82c8b7ca17812de7', 'Détail Objectif','detail_objectif_service', NULL, '".NULL."', '1', '[-1-2-3-7-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail Objectif' </li>";}

<?php 
//Export Module 'bl' Date: 03-05-2018
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('bl', 'Gestion BL','vente/submodul/bl','bl','bl','vente','2', '0', '[-1-2-3-5-4-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'bl' </li>";}
  //Task 'bl' 'Gestion BL'
  if(!$result_task_879 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('bl', $result_insert_modul, 'bl','vente/submodul/bl', '1', 'Gestion BL', 'bookmark-o', '1', '0', '0','list', '[-1-2-3-5-4-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion BL' </li>";}
      // Action Task 879 - 'Gestion BL'
      if(!$result_action_1442 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_879, '29d3ba7d2ceccd9ee362a8c546946cad', 'Gestion BL','bl', NULL, '".NULL."', '1', '[-1-2-3-5-4-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion BL' </li>";}
      // Action Task 879 - 'Valider BL'
      if(!$result_action_1444 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_879, 'aa39ac5e6ba2805784c71acf92030e0f', 'Valider BL','validbl', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validbl"  ><i class="ace-icon fa fa-check green bigger-100"></i> Valider BL</a></li>'."', '0', '[-1-2-3-5-]', '0', '1', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider BL' </li>";}
      // Action Task 879 - 'Détail BL'
      if(!$result_action_1446 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_879, '39691502722423e9ab234d24f409f90b', 'Détail BL','detailbl', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailbl"  ><i class="ace-icon fa fa-eye blue bigger-100"></i> Détail BL</a></li>'."', '0', '[-1-2-3-5-]', '0', '1', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail BL' </li>";}
  //Task 'validbl' 'Valider BL'
  if(!$result_task_880 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validbl', $result_insert_modul, 'validbl','vente/submodul/bl', '1', 'Valider BL', 'check green', '1', '0', '0','exec', '[-1-2-3-5-4-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider BL' </li>";}
      // Action Task 880 - 'Valider BL'
      if(!$result_action_1443 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_880, '0a8c8609ecb28b5ccbce6d7910d791fc', 'Valider BL','validbl', NULL, '".NULL."', '1', '[-1-2-3-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider BL' </li>";}
  //Task 'detailbl' 'Détail BL'
  if(!$result_task_881 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detailbl', $result_insert_modul, 'detailbl','vente/submodul/bl', '1', 'Détail BL', 'eye blue', '1', '0', '0','profil', '[-1-2-3-5-4-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détail BL' </li>";}
      // Action Task 881 - 'Détail BL'
      if(!$result_action_1445 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_881, 'cc8d69937778bdaa2240c40a229571ba', 'Détail BL','detailbl', NULL, '".NULL."', '1', '[-1-2-3-5-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail BL' </li>";}

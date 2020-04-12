<?php 
//Export Module 'fencaissements' Date: 12-04-2020
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('fencaissements', 'Gestion encaissements','facturation_paiement/submodul/fencaissements','encaissements','fencaissement','facturation_paiement','2', '0', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'fencaissements' </li>";}
  //Task 'fencaissement' 'Gestion encaissements'
  if(!$result_task_1226 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('fencaissement', $result_insert_modul, 'fencaissement','facturation_paiement/submodul/fencaissements', '1', 'Gestion encaissements', 'eye', '1', '0', '0','list', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion encaissements' </li>";}
      // Action Task 1226 - 'Gestion encaissements'
      if(!$result_action_2158 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1226, '6513b4518a296fadb8b7c8458a982199', 'Gestion encaissements','fencaissement', NULL, '".NULL."', '1', '[-1-2-3-5-4-7-6-]', '0', '0', 'Attente Validation','warning','".'Attente Validation'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion encaissements' </li>";}
      // Action Task 1226 - 'Editer Encaissement'
      if(!$result_action_2159 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1226, '05c8cb30426818f1509034db6b589cfc', 'Editer Encaissement','editfencaissement', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editfencaissement"  ><i class="ace-icon fa fa-pencil-square bigger-100"></i> Editer Encaissement</a></li>'."', '0', '[-1-2-3-5-4-7-6-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Encaissement' </li>";}
      // Action Task 1226 - 'Détails Encaissement'
      if(!$result_action_2160 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1226, '8393b8b342a82048cf303aeab38e6901', 'Détails Encaissement','detailsfencaissement', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsfencaissement"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Encaissement</a></li>'."', '0', '[-1-2-3-5-4-7-6-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Encaissement' </li>";}
      // Action Task 1226 - 'Détails Encaissement'
      if(!$result_action_2161 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1226, '1288810cef9cbaeeb01e9a519dd396f7', 'Détails Encaissement','detailsfencaissement', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsfencaissement"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Encaissement</a></li>'."', '0', '[-1-2-3-5-4-7-6-]', '1', '0', 'Encaissement Validé','success','".'<span class="label label-sm label-success">Encaissement Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Encaissement' </li>";}
      // Action Task 1226 - 'Valider Encaissement'
      if(!$result_action_2162 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1226, '984e3a5ba149be90738f7e4962ff8213', 'Valider Encaissement','validfencaissement', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validfencaissement"  ><i class="ace-icon fa fa-check bigger-100"></i> Valider Encaissement</a></li>'."', '0', '[-1-2-3-5-4-7-6-]', '0', '1', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Encaissement' </li>";}
  //Task 'editfencaissement' 'Editer Encaissement'
  if(!$result_task_1227 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editfencaissement', $result_insert_modul, 'editfencaissement','facturation_paiement/submodul/fencaissements', '1', 'Editer Encaissement', 'pencil', '1', '0', '0','formedit', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Editer Encaissement' </li>";}
  //Task 'validfencaissement' 'Valider Encaissement'
  if(!$result_task_1229 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validfencaissement', $result_insert_modul, 'validfencaissement','facturation_paiement/submodul/fencaissements', '1', 'Valider Encaissement', 'eye', '1', '0', '0','exec', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider Encaissement' </li>";}
  //Task 'detailsfencaissement' 'Détails Encaissement'
  if(!$result_task_1230 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detailsfencaissement', $result_insert_modul, 'detailsfencaissement','facturation_paiement/submodul/fencaissements', '1', 'Détails Encaissement', 'eye', '1', '0', '0','profil', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détails Encaissement' </li>";}
  //Task 'deletefencaissement' 'Supprimer Encaissement'
  if(!$result_task_1231 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletefencaissement', $result_insert_modul, 'deletefencaissement','facturation_paiement/submodul/fencaissements', '1', 'Supprimer Encaissement', 'wallet', '1', '0', '0','exec', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer Encaissement' </li>";}

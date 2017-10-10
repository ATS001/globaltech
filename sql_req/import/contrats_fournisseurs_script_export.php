<?php 
//Export Module 'contrats_fournisseurs' Date: 09-10-2017
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('contrats_fournisseurs', 'Contrats Fournisseur','contrats_fournisseurs/main','contrats_frn','contrats_fournisseurs',NULL,'0', '0', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'contrats_fournisseurs' </li>";}
  //Task 'contrats_fournisseurs' 'Gestion Contrats Fournisseurs'
  if(!$result_task_501 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('contrats_fournisseurs', $result_insert_modul, 'contrats_fournisseurs','contrats_fournisseurs/main', '1', 'Gestion Contrats Fournisseurs', 'book', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion Contrats Fournisseurs' </li>";}
      // Action Task 501 - 'Gestion Contrats Fournisseurs'
      if(!$result_action_724 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_501, 'ec45512f34613446e7a2e367d4b4cfbd', 'Gestion Contrats Fournisseurs','contrats_fournisseurs', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion Contrats Fournisseurs' </li>";}
      // Action Task 501 - 'Editer Contrat'
      if(!$result_action_744 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_501, 'e3c0d7e92dad7f8794b2415c334ec3ff', 'Editer Contrat','editcontrat_frn', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editcontrat_frn"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Contrat</a></li>'."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Contrat' </li>";}
      // Action Task 501 - 'Valider Contrat'
      if(!$result_action_745 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_501, '9dfff1c8dcb804837200f38e95381420', 'Valider Contrat','validcontrat_frn', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validcontrat_frn"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Contrat</a></li>'."', '0', '[-1-]', '0', '1', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Contrat' </li>";}
      // Action Task 501 - 'Désactiver Contrat'
      if(!$result_action_746 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_501, '9fe39b496077065105a57ccd9ed05863', 'Désactiver Contrat','validcontrat_frn', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validcontrat_frn"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Contrat</a></li>'."', '0', '[-1-]', '1', '0', 'Contrat Validé','success','".'<span class="label label-sm label-success">Contrat Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver Contrat' </li>";}
      // Action Task 501 - 'Détails  Contrat '
      if(!$result_action_779 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_501, 'faee342ff51dbe9f835529ae5b9b2a0b', 'Détails  Contrat ','detailscontrat_frn', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails  Contrat </a></li>'."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails  Contrat ' </li>";}
      // Action Task 501 - 'Détails   Contrat  '
      if(!$result_action_780 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_501, '83406b6b206ed08878f2b2e854932ae5', 'Détails   Contrat  ','detailscontrat_frn', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails   Contrat  </a></li>'."', '0', '[-1-]', '1', '0', 'Client Validé','success','".'<span class="label label-sm label-success">Client Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails   Contrat  ' </li>";}
      // Action Task 501 - 'Détails    Contrat '
      if(!$result_action_781 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_501, '8447888bef30fb983477cc1357ff7e6f', 'Détails    Contrat ','detailscontrat_frn', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails    Contrat </a></li>'."', '0', '[-1-]', '3', '0', 'Contrat Expiré','info','".'<span class="label label-sm label-info">Contrat Expiré</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails    Contrat ' </li>";}
      // Action Task 501 - '  Détails    Contrat'
      if(!$result_action_782 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_501, '4cc1845128f6a5ff3ed01100292d8ebb', '  Détails    Contrat','detailscontrat_frn', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i>   Détails    Contrat</a></li>'."', '0', '[-1-]', '2', '0', 'Attente Renouvelement','danger','".'<span class="label label-sm label-danger">Attente Renouvelement</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action '  Détails    Contrat' </li>";}
      // Action Task 501 - '  Renouveler   Contrat '
      if(!$result_action_787 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_501, 'cd82d84c5f70a633b10aae88c34e9159', '  Renouveler   Contrat ','renouveler_contrat', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="renouveler_contrat"  ><i class="ace-icon fa fa-exchange bigger-100"></i>   Renouveler   Contrat </a></li>'."', '0', '[-1-]', '2', '1', 'Attente Renouvelement','danger','".'<span class="label label-sm label-danger">Attente Renouvelement</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action '  Renouveler   Contrat ' </li>";}
      // Action Task 501 - ' Détails  Contrat '
      if(!$result_action_911 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_501, 'e9e994a0f8a204f1323fca7ce30931fe', ' Détails  Contrat ','detailscontrat_frn', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailscontrat_frn"  ><i class="ace-icon fa fa-eye bigger-100"></i>  Détails  Contrat </a></li>'."', '0', '[-1-]', '4', '0', 'Contrat Expiré','inverse','".'<span class="label label-sm label-inverse">Contrat Expiré</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action ' Détails  Contrat ' </li>";}
      // Action Task 501 - ' Renouveler  Contrat '
      if(!$result_action_912 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_501, 'b9e0a2a0236899590c72d31b878edfb2', ' Renouveler  Contrat ','renouveler_contrat', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="renouveler_contrat"  ><i class="ace-icon fa fa-exchange bigger-100"></i>  Renouveler  Contrat </a></li>'."', '0', '[-1-]', '3', '0', 'Contrat Expiré','info','".'<span class="label label-sm label-info">Contrat Expiré</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action ' Renouveler  Contrat ' </li>";}
  //Task 'addcontrat_frn' 'Ajouter Contrat'
  if(!$result_task_509 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addcontrat_frn', $result_insert_modul, 'addcontrat_frn','contrats_fournisseurs/main', '1', 'Ajouter Contrat', 'book', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Contrat' </li>";}
      // Action Task 509 - 'Ajouter Contrat'
      if(!$result_action_739 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_509, 'ded24eb817021c5a666a677b1565bc5e', 'Ajouter Contrat','addcontrat_frn', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter Contrat' </li>";}
  //Task 'editcontrat_frn' 'Editer Contrat'
  if(!$result_task_510 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editcontrat_frn', $result_insert_modul, 'editcontrat_frn','contrats_fournisseurs/main', '1', 'Editer Contrat', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Editer Contrat' </li>";}
      // Action Task 510 - 'Editer Contrat'
      if(!$result_action_740 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_510, 'ed6b8695494bf4ed86d5fb18690b3a59', 'Editer Contrat','editcontrat_frn', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Contrat' </li>";}
  //Task 'deletecontrat_frn' 'Supprimer Contrat'
  if(!$result_task_511 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletecontrat_frn', $result_insert_modul, 'deletecontrat_frn','contrats_fournisseurs/main', '1', 'Supprimer Contrat', 'trash', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer Contrat' </li>";}
      // Action Task 511 - 'Supprimer Contrat'
      if(!$result_action_741 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_511, 'b8a40913b5955209994aaa26d0e8c3d4', 'Supprimer Contrat','deletecontrat_frn', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer Contrat' </li>";}
  //Task 'validcontrat_frn' 'Valider Contrat'
  if(!$result_task_512 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validcontrat_frn', $result_insert_modul, 'validcontrat_frn','contrats_fournisseurs/main', '1', 'Valider Contrat', 'lock', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider Contrat' </li>";}
      // Action Task 512 - 'Valider Contrat'
      if(!$result_action_742 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_512, '5efb874e7d73ccd722df806e8275770f', 'Valider Contrat','validcontrat_frn', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Contrat' </li>";}
  //Task 'detailscontrat_frn' 'Détails Contrat'
  if(!$result_task_527 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detailscontrat_frn', $result_insert_modul, 'detailscontrat_frn','contrats_fournisseurs/main', '1', 'Détails Contrat', 'eye', '1', '0', '0','profil', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détails Contrat' </li>";}
      // Action Task 527 - 'Détails Contrat'
      if(!$result_action_778 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_527, '64a5f976687a8c5f7cd3d672cc5d9c8c', 'Détails Contrat','detailscontrat_frn', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Contrat' </li>";}
  //Task 'renouveler_contrat' 'Renouveler  Contrat'
  if(!$result_task_529 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('renouveler_contrat', $result_insert_modul, 'renouveler_contrat','contrats_fournisseurs/main', '1', 'Renouveler  Contrat', 'exchange', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Renouveler  Contrat' </li>";}
      // Action Task 529 - 'Renouveler  Contrat'
      if(!$result_action_786 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_529, '2cc55c65e79534161108288adb00472b', 'Renouveler  Contrat','renouveler_contrat', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Renouvelement','danger','".'<span class="label label-sm label-danger">Attente Renouvelement</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Renouveler  Contrat' </li>";}

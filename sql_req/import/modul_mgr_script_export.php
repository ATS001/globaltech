<?php 
//Export Module 'modul_mgr' Date: 13-10-2017
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('modul_mgr', 'Modules','modul_mgr','modul,task,task_action','modul',NULL,'0', '0', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'modul_mgr' </li>";}
  //Task 'modul' 'Modules'
  if(!$result_task_547 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('modul', $result_insert_modul, 'modul','modul_mgr', '1', 'Modules', 'cubes', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modules' </li>";}
      // Action Task 547 - 'Modules'
      if(!$result_action_816 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_547, 'b8e62907d367fb44d644a5189cd07f42', 'Modules','modul', NULL, '".NULL."', '1', 'null', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modules' </li>";}
      // Action Task 547 - 'Editer Module'
      if(!$result_action_817 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_547, '05ce9e55686161d99e0714bb86243e5b', 'Editer Module','modul', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editmodul" >
      <i class="ace-icon fa fa-pencil bigger-100"></i> Editer Module
    </a></li>'."', '0', '-1-2-', '0', '1', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Module' </li>";}
      // Action Task 547 - 'Exporter Module'
      if(!$result_action_818 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_547, '819cf9c18a44cb80771a066768d585f2', 'Exporter Module','modul', NULL, '".'<li><a href="#" class="export_mod" data="%id%&export=1&mod=%id%" rel="modul" item="%id%" >
      <i class="ace-icon fa fa-download bigger-100"></i> Exporter Module
    </a></li>'."', '0', '-1-2-', '0', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Exporter Module' </li>";}
      // Action Task 547 - 'Liste task modul'
      if(!$result_action_819 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_547, 'd2fc3ee15cee5208a8b9c70f1e53c196', 'Liste task modul','modul', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="task" >
     <i class="ace-icon fa fa-external-link bigger-100"></i>Application associes
    </a></li>'."', '0', '-1-2-', '0', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Liste task modul' </li>";}
      // Action Task 547 - 'Editer Module'
      if(!$result_action_820 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_547, 'ad75e6b877f20e3d6fc1789da4dcb3e6', 'Editer Module','modul', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editmodulsetting"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Module</a></li>'."', '0', '[-1-]', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Module' </li>";}
      // Action Task 547 - 'Liste task modul Setting'
      if(!$result_action_821 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_547, '064a9b0eff1006fd4f25cb4eaf894ca1', 'Liste task modul Setting','modul', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="task" >
     <i class="ace-icon fa fa-external-link bigger-100"></i>Application associes
    </a></li>'."', '0', '-1-2-', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Liste task modul Setting' </li>";}
      // Action Task 547 - 'MAJ Module'
      if(!$result_action_822 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_547, 'ac4eb0c94da00a48ad5d995f5e9e9366', 'MAJ Module','update_module', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="update_module"  ><i class="ace-icon fa fa-pencil-square-o bigger-100"></i> MAJ Module</a></li>'."', '0', '[-1-]', '0', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'MAJ Module' </li>";}
  //Task 'addmodul' 'Ajouter Module'
  if(!$result_task_548 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addmodul', $result_insert_modul, 'addmodul','modul_mgr', '1', 'Ajouter Module', NULL, '1', '1', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Module' </li>";}
      // Action Task 548 - 'Ajouter un Modul'
      if(!$result_action_823 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_548, '44bd5341b0ab41ced21db8b3e92cf5aa', 'Ajouter un Modul','addmodul', NULL, '".NULL."', '1', '-1-2-', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter un Modul' </li>";}
  //Task 'editmodul' 'Editer Module'
  if(!$result_task_549 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editmodul', $result_insert_modul, 'editmodul','modul_mgr', '1', 'Editer Module', NULL, '1', '1', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Editer Module' </li>";}
  //Task 'task' 'Liste Application Associes'
  if(!$result_task_550 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('task', $result_insert_modul, 'task','modul_mgr', '1', 'Liste Application Associes', NULL, '1', '1', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Liste Application Associes' </li>";}
      // Action Task 550 - 'Liste Action Task'
      if(!$result_action_824 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_550, '8653b156f1a4160a12e5a94b211e59a2', 'Liste Action Task','task', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="taskaction"  >
     <i class="ace-icon fa fa-external-link bigger-100"></i>Application associes
    </a></li>'."', '0', '-1-2-', '0', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Liste Action Task' </li>";}
      // Action Task 550 - 'Supprimer Application'
      if(!$result_action_825 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_550, '86aced763bc02e1957a5c740fb37b4f7', 'Supprimer Application','task', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="task"  ><i class="ace-icon fa fa-draft bigger-100"></i> Supprimer Application</a></li>'."', '0', '[-1-]', '0', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer Application' </li>";}
      // Action Task 550 - 'Ajout Affichage WF'
      if(!$result_action_826 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_550, 'f07352e32fe86da1483c6ab071b7e7a9', 'Ajout Affichage WF','task', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="addetatrule"  ><i class="ace-icon fa fa-eye bigger-100"></i> Ajout Affichage WF</a></li>'."', '0', '[-1-]', '0', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajout Affichage WF' </li>";}
  //Task 'addtask' 'Ajouter Module Task'
  if(!$result_task_551 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addtask', $result_insert_modul, 'addtask','modul_mgr', '1', 'Ajouter Module Task', NULL, '1', '1', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Module Task' </li>";}
      // Action Task 551 - 'Ajouter Task Modul'
      if(!$result_action_827 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_551, '1c452aff8f1551b3574e15b74147ea56', 'Ajouter Task Modul','addtask', NULL, '".NULL."', '1', '-1-2-', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter Task Modul' </li>";}
  //Task 'edittask' 'Editer Module Task'
  if(!$result_task_552 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('edittask', $result_insert_modul, 'edittask','modul_mgr', '1', 'Editer Module Task', NULL, '1', '1', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Editer Module Task' </li>";}
      // Action Task 552 - 'Editer Task Modul'
      if(!$result_action_828 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_552, 'f085fe4610576987db963501297e4d91', 'Editer Task Modul','edittask', NULL, '".NULL."', '1', '-1-2-', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Task Modul' </li>";}
      // Action Task 552 - 'Ajouter action modul'
      if(!$result_action_829 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_552, '38702c272a6f4d334c2f4c3684c8b163', 'Ajouter action modul','edittask', NULL, '".NULL."', '1', '-1-2-', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter action modul' </li>";}
  //Task 'taskaction' 'Liste Task Action'
  if(!$result_task_553 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('taskaction', $result_insert_modul, 'taskaction','modul_mgr', '1', 'Liste Task Action', '0', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Liste Task Action' </li>";}
      // Action Task 553 - 'Liste Action Task'
      if(!$result_action_830 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_553, 'cbae1ebe850f6dd8841426c6fedf1785', 'Liste Action Task','taskaction', NULL, '".NULL."', '1', '[-1-]', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Liste Action Task' </li>";}
      // Action Task 553 - 'Editer Task Action'
      if(!$result_action_831 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_553, 'e30471396f9b86ccdcc94943d80b679a', 'Editer Task Action','taskaction', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="edittaskaction"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Task Action</a></li>'."', '0', '[-1-]', '0', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Task Action' </li>";}
  //Task 'addtaskaction' 'Ajouter Action Task'
  if(!$result_task_554 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addtaskaction', $result_insert_modul, 'addtaskaction','modul_mgr', '1', 'Ajouter Action Task', '0', '1', '0', '0','form', '[-1-3-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Action Task' </li>";}
      // Action Task 554 - 'Ajouter Action Task'
      if(!$result_action_832 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_554, '502460cd9327b46ee7af0a258ebf8c80', 'Ajouter Action Task','addtaskaction', NULL, '".NULL."', '1', '[-1-3-]', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter Action Task' </li>";}
  //Task 'deletetask' 'Supprimer Application'
  if(!$result_task_555 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletetask', $result_insert_modul, 'deletetask','modul_mgr', '1', 'Supprimer Application', NULL, '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer Application' </li>";}
      // Action Task 555 - 'Supprimer Application'
      if(!$result_action_833 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_555, '13c107211904d4a2e65dd65c60ec7272', 'Supprimer Application','deletetask', NULL, '".NULL."', '1', 'null', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer Application' </li>";}
  //Task 'importmodul' 'Importer des modules'
  if(!$result_task_556 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('importmodul', $result_insert_modul, 'importmodul','modul_mgr', '1', 'Importer des modules', NULL, '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Importer des modules' </li>";}
      // Action Task 556 - 'Importer des modules'
      if(!$result_action_834 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_556, '8c8acf9cf3790b16b1fae26823f45eab', 'Importer des modules','importmodul', NULL, '".NULL."', '1', '[-1-]', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Importer des modules' </li>";}
  //Task 'addmodulsetting' 'Ajouter Module paramétrage'
  if(!$result_task_557 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addmodulsetting', $result_insert_modul, 'addmodulsetting','modul_mgr', '1', 'Ajouter Module paramétrage', NULL, '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Module paramétrage' </li>";}
      // Action Task 557 - 'Ajouter Module paramétrage'
      if(!$result_action_835 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_557, '2f4518dab90b706e2f4acd737a0425d8', 'Ajouter Module paramétrage','addmodulsetting', NULL, '".NULL."', '1', '[-1-]', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter Module paramétrage' </li>";}
  //Task 'editmodulsetting' 'Editer Module paramètrage'
  if(!$result_task_558 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editmodulsetting', $result_insert_modul, 'editmodulsetting','modul_mgr', '1', 'Editer Module paramètrage', 'na', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Editer Module paramètrage' </li>";}
      // Action Task 558 - 'Editer Module paramètrage'
      if(!$result_action_836 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_558, '8e0c0212d8337956ac2f4d6eb180d74b', 'Editer Module paramètrage','editmodulsetting', NULL, '".NULL."', '1', '[-1-]', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Module paramètrage' </li>";}
  //Task 'addetatrule' 'Ajouter Autorisation Etat'
  if(!$result_task_559 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addetatrule', $result_insert_modul, 'addetatrule','modul_mgr', '1', 'Ajouter Autorisation Etat', NULL, '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Autorisation Etat' </li>";}
      // Action Task 559 - 'Ajouter Autorisation Etat'
      if(!$result_action_837 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_559, 'fc54953b47b7fcb11cc14c0c2e2125f0', 'Ajouter Autorisation Etat','addetatrule', NULL, '".NULL."', '1', '[-1-]', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter Autorisation Etat' </li>";}
  //Task 'edittaskaction' 'Editer Task Action'
  if(!$result_task_560 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('edittaskaction', $result_insert_modul, 'edittaskaction','modul_mgr', '1', 'Editer Task Action', 'pen', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Editer Task Action' </li>";}
      // Action Task 560 - 'Editer Task Action'
      if(!$result_action_838 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_560, '966ec2dd83e6006c2d0ff1d1a5f12e33', 'Editer Task Action','edittaskaction', NULL, '".NULL."', '1', '[-1-]', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Task Action' </li>";}
  //Task 'update_module' 'MAJ Module'
  if(!$result_task_561 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('update_module', $result_insert_modul, 'update_module','modul_mgr', '1', 'MAJ Module', 'pencil-square-o', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'MAJ Module' </li>";}
      // Action Task 561 - 'MAJ Module'
      if(!$result_action_839 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_561, '3473119f6683893a3f1372dbf7d811e1', 'MAJ Module','update_module', NULL, '".NULL."', '1', '[-1-]', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'MAJ Module' </li>";}
  //Task 'dupliqtaskaction' 'Dupliquer Action Task'
  if(!$result_task_562 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('dupliqtaskaction', $result_insert_modul, 'dupliqtaskaction','modul_mgr', '1', 'Dupliquer Action Task', 'check', '1', '0', '0','formadd', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Dupliquer Action Task' </li>";}
      // Action Task 562 - 'Dupliquer Action Task'
      if(!$result_action_840 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_562, '2e2346bd422536c1d996ff25f9e71357', 'Dupliquer Action Task','dupliqtaskaction', NULL, '".NULL."', '0', '[-1-]', '1', '0', NULL,NULL,'".NULL."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Dupliquer Action Task' </li>";}
  //Task 'deletetaskaction' 'Supprimer Task action'
  if(!$result_task_637 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletetaskaction', $result_insert_modul, 'deletetaskaction','modul_mgr', '1', 'Supprimer Task action', 'trash', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer Task action' </li>";}
      // Action Task 637 - 'Supprimer Task action'
      if(!$result_action_979 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_637, '8a3634181ae5bc9223b689a310158962', 'Supprimer Task action','deletetaskaction', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Not Message','success','".'<span class="label label-sm label-success">Not Message</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer Task action' </li>";}
  //Task 'workflow' 'Affichage Work Flow'
  if(!$result_task_638 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('workflow', $result_insert_modul, 'workflow','modul_mgr', '1', 'Affichage Work Flow', 'exchange', '1', '0', '0','profil', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Affichage Work Flow' </li>";}
      // Action Task 638 - 'Affichage Work Flow'
      if(!$result_action_980 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_638, '8afb3c669307183cd3b7d189fbf204d7', 'Affichage Work Flow','workflow', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Work flow','success','".'<span class="label label-sm label-success">Work flow</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Affichage Work Flow' </li>";}

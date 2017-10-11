<?php
//array colomn
$array_column = array(
	array(
        'column' => 'session.id_sys',
        'type'   => '',
        'alias'  => 'id',
        'width'  => '5',
        'header' => 'ID',
        'align'  => 'C'
    ),
    array(
        'column' => 'IF (session.expir IS NULL,\'Session ouverte\',\'Session fermée\')',
        'type'   => '',
        'alias'  => 'operation',
        'width'  => '15',
        'header' => 'Statut',
        'align'  => 'L'
    ),
    array(
        'column' => 'CONCAT(users_sys.fnom,\' \',users_sys.lnom)',
        'type'   => '',
        'alias'  => 'nom',
        'width'  => '20',
        'header' => 'Utilisateur',
        'align'  => 'L'
    ),
    array(
        'column' => 'session.dat',
        'type'   => 'datetime',
        'alias'  => 'connexion',
        'width'  => '15',
        'header' => 'Heure Connexion',
        'align'  => 'C'
    ),
    array(
        'column' => 'session.expir',
        'type'   => 'datetime',
        'alias'  => 'deconnection',
        'width'  => '15',
        'header' => 'Heure Déconnexion',
        'align'  => 'C'
    ),
    array(
        'column' => 'CONCAT( TIMESTAMPDIFF(MINUTE, session.dat, (IF (session.expir IS NULL,NOW(),session.expir))), \' Minutes\')',
        'type'   => '',
        'alias'  => 'duree',
        'width'  => '15',
        'header' => 'Durée',
        'align'  => 'C'
    ),
    
 );
$user_id = Mreq::tp('id');

//Creat new instance
$list_data_table = new Mdatatable();
//Set tabels used in Query
$list_data_table->tables = array('users_sys', 'session');
//Jointure
$list_data_table->joint = "users_sys.id = session.userid AND users_sys.id = $user_id ";
//Call all columns
$list_data_table->columns = $array_column;
//Set main table of Query
$list_data_table->main_table = 'session';
//Set Task used for statut line
$list_data_table->task = 'history';
//Set File name for export
$list_data_table->file_name = 'liste_connexions';
//Set Title of report
$list_data_table->title_report = 'Liste Connexions';
//Need notif
$list_data_table->need_notif = false;
//Print JSON DATA
if(!$data = $list_data_table->Query_maker())
{
    exit("0#".$list_data_table->log);
}else{
    echo $data;
}



?>

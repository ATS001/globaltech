<?php
/**
 * Class Loggining systeme 1.0
 */
class Mlog
{

	public function __construct(){
	}

	static public function log_exec($table, $idm, $message, $type)
	{
		global $db;
		
		$values["message"]   = MySQL::SQLValue($message);
		$values["type_log"]  = MySQL::SQLValue($type);
		$values["table_use"] = MySQL::SQLValue($table);
		$values["idm"]       = MySQL::SQLValue($idm);
		$values["user_exec"] = MySQL::SQLValue(session::get('username'));
		//If no error on Insert commande
    		if (!$result = $db->InsertRow("sys_log", $values))
    		{
    			return false;  			 
    		}else{
                return true;
    		}
		
	}

}
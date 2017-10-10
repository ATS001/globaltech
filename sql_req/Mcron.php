<?php
session_start(); 
ob_start("ob_gzhandler");
//Run all file cron into cron_jobs folder v1.0
//requirement (definded, config, lib)
define('SLASH',          '/');
define('MPATH_LIBRARIES', '../libraries'. SLASH);
define('MPATH_MODULES',   '../modules'. SLASH);
define('MPATH_INCLUDES',      '.'. SLASH);
define('MPATH_CRON_JOB',      '../cron_jobs'. SLASH);
@include_once('auto_loader_cron.php');

$autoloadManager = new AutoloadManager();
$autoloadManager->setSaveFile('autoload_cron.php');
$autoloadManager->addFolder(MPATH_INCLUDES);
$autoloadManager->addFolder(MPATH_LIBRARIES);
$autoloadManager->addFolder(MPATH_MODULES);

$autoloadManager->register();
$db   =  new MySQL(); //Instance DB conx
$jobs_array = scandir(MPATH_CRON_JOB);
$terminison_file = '_job_cron.php';

//
/**
 * [log_cron description]Function LOG must call for each output cron job
 * @param  string $log         [Message be log]
 * @param  [string] $current_job [name of script file without _cron_job.php]
 * @return [file write]              [description]
 */
function log_cron($log='', $current_job)
{
	$file = MPATH_INCLUDES.SLASH.'log_cron.log';
	$line = '['.date('d-m-Y H:i:s').']: '.$log.' =>'.MPATH_CRON_JOB.$current_job.'_cron_job.php'.PHP_EOL;
	if(!file_put_contents($file, $line, FILE_APPEND | LOCK_EX))
	{
		exit();
	}
}


foreach ($jobs_array as $row){
	if(strstr($row,$terminison_file))
	{

		$file = MPATH_CRON_JOB.$row;
		if(file_exists($file))
		{

			include($file);

		}
	}
}

?>
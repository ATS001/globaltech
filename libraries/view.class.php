<?php
class view {

	public $view;
//recuperer repertoir et nom de fichier à charger 

	static public function load($view_rep,$view_file)
	{
		$view_file_include = MPATH_MODULES.$view_rep.SLASH.'view/'.$view_file.'_v.php';
		if(file_exists($view_file_include))
		{
			include_once($view_file_include);
		}else{
			/*exit ('<div class="space-16"></div><div class="space-16"></div><div class="alert alert-block alert-danger"><i class="ace-icon fa fa-exclamation-circle red fa-2x icon-animated-vertical"></i> <strong class="red"> STOP: </strong>Le fichier View n\'exist pas ! contacter l\'administrateur sysème<br><a href="./" class="btn btn-danger btn-sm"><i class="ace-icon fa fa-reply icon-only"> Accueil</i></a></div>');*/
			exit('3#Le fichier View n\'exist pas ! contacter l\'administrateur sysème');
		}
	}
	static public function load_view($view_file)
	{
		$view_file_include = APP_VIEW.$view_file.'_v.php';
		if(file_exists($view_file_include))
		{
			include_once($view_file_include);
		}else{
			exit('3#Le fichier View n\'exist pas ! contacter l\'administrateur sysème');
		}
	}


}

?>

<?php
//Get Modul liste from Template Classe
//Creat new objet template
$modules = new Template();
//Call modul list methode
$modules->left_menu_render();
//Fill Modul liste Array
$arr_modul = $modules->left_menu_arr;
//var_dump($arr_modul);

?>		

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
			try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar responsive sidebar-fixed">
				<script type="text/javascript">
				//try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<a class="btn btn-success" href="./">
							<i class="ace-icon fa fa-home home-icon"></i>
						</a>

						<button class="btn btn-info this_url" rel="compte" title="Mon Compte">
							<i class="ace-icon fa fa-pencil"></i>
						</button>

						<!-- #section:basics/sidebar.layout.shortcuts -->
						<button class="btn btn-warning this_url" rel="user" title="Administration">
							<i class="ace-icon fa fa-users"></i>
						</button>

						<button class="btn btn-danger this_url" rel="setting" title="Paramétrages">
							<i class="ace-icon fa fa-cogs"></i>
						</button>

						<!-- /section:basics/sidebar.layout.shortcuts -->
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
					<li class="">
							<a href="#" left_menu="1" class="tip-right this_url" rel="dbd" title="Tableau de bord">	
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Tableau de Bord </span>
						</a>

						<b class="arrow"></b>
					</li>
					

                     
                    	
<?php
//liste Modules

$render = '';
foreach ($arr_modul as $row)
{
		
	$check_sub_modul = $modules->get_sub_modul($row['modul'], $row['app'], $row['descrip'], $row['class']);
	//exit($check_sub_modul);
	if( $check_sub_modul == NULL){
        
		$render .='<li><a href="#" left_menu="1" class="tip-right this_url" rel="'.$row['app'].'" title="'.$row['descrip'].'"><i class="menu-icon fa fa-'.$row['class'].'"></i><span class="menu-text this_url" rel="'.$row['app'].'"> '.$row['descrip'].'</span></b></a></li>'; 
	}else{
		$render .='<li><a href="#" left_menu="1" class="dropdown-toggle" title="'.$row['descrip'].'"><i class="menu-icon fa fa-'.$row['class'].'"></i><span class="menu-text"> '.$row['descrip'].'</span><b class="arrow fa fa-angle-down"></b></a>';
		$render .= $check_sub_modul .'</li>';
	}
    
}
 $render .= '';

 echo $render;

?> 
                    </ul><!-- /.nav-list -->

                    <!-- #section:basics/sidebar.layout.minimize -->
                    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
                    	<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
                    </div>

                    <!-- /section:basics/sidebar.layout.minimize -->
                    <script type="text/javascript">
                    try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
                    </script>
                </div>

                <!-- /section:basics/sidebar -->








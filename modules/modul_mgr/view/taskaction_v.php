<?php

 
 $info_task = new Mmodul();
 $info_task->id_task = Mreq::tp('id');
 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_task->get_task())
 { 	
 	exit('3#'.$info_task->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }

 $id_modul = $info_task->task_info['modul'];




 ?>
<div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php 
		TableTools::btn_add('task', 'Liste Application Modul ('.$id_modul.') ', MInit::crypt_tp('id',$id_modul), $exec = NULL, 'reply'); 

		?>

					
	</div>
</div>  
<div class="page-header">
	<h1>
		Liste Action application
		<small>
			<i class="ace-icon fa fa-angle-double-left"></i>
		</small>
		 <?php echo $info_task->task_info['dscrip']. ' - '. $info_task->id_task. ' - ';?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			<div class="pull-right tableTools-container">
				<div class="btn-group btn-overlap">
					
					<?php TableTools::btn_add('addtaskaction','Ajouter Task Action',MInit::crypt_tp('id',$info_task->id_task)); ?>
					
				</div>
			</div>
		</div>

		<div class="table-header">
			<?php  echo ACTIV_APP  ?> " <?php echo $info_task->task_info['dscrip']; ?>"
		</div>
<div>
			<table id="task_action_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
				<thead>
					<tr>
						
						<th>
							ID
						</th>
						<th>
							Déscription
						</th>
						<th>
							Type
						</th>
						<th>
							Etat line
						</th>
						<th>
							Notif
						</th>
						<th>
							#
						</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">


$(document).ready(function(){
	
	var table = $('#task_action_grid').DataTable({
		bProcessing: true,
		serverSide: true,
		ajax_url:"taskaction",
		extra_data : "id=<?php echo Mreq::tp('id');?>",
		

                aoColumns: [
                   {"sClass": "center","sWidth":"5%"}, //
                   
                   {"sClass": "left","sWidth":"35%"},
                   {"sClass": "left","sWidth":"20%"},
                   {"sClass": "left","sWidth":"15%"},
                   {"sClass": "left","sWidth":"20%"},
                   {"sClass": "center","sWidth":"5%"},



                   ],

    });

	$('#task_action_grid').on('click', 'tr button', function() {
		var $row = $(this).closest('tr')
	    //alert(table.cell($row, 0).data());
	    append_drop_menu('taskaction', table.cell($row, 0).data(), '.btn_action')
    });

 
});
</script>















































<?php 
//SYS MRN ERP
// Modul: Modul MGR => View
 <div class="pull-right tableTools-container">
	<div class="btn-group btn-overlap">
					
		<?php TableTools::btn_add('produits','Liste des produits', Null, $exec = NULL, 'reply'); ?>
					
	</div>
</div>
<div class="page-header">
	<h1>
		Ajouter un produit
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div><!-- /.page-header -->
<!-- Bloc form Add Devis-->
<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			
		</div>
		<div class="table-header">
			Formulaire: "<?php echo ACTIV_APP; ?>"
		</div>
		<div class="widget-content">
			<div class="widget-box">
				
<?php
$form = new Mform('adddevis', 'adddevis', '', 'produits', '0', 'is_modal');
//Réference
$form->input('Client', 'client', 'text' ,6, '0', Null);
//Quantité


//Form render
$form->render();
?>
			</div>
		</div>
	</div>
</div>
<!-- End Add devis bloc -->

<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
		<?php echo '<a id="addRow" href="#" rel="adddetails" data="" data_titre="Détail Devis" class=" btn btn-white btn-info btn-bold  spaced"><span><i class="fa fa-add"></i> Ajouter une ligne Dima Wydade</span></a>'; ?>
		
		<table id="example" class="display table table-bordered table-condensed table-hover table-striped dataTable no-footer" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>item</th>
                <th>Réference</th>
                <th>Produit</th>
                <th>Qte</th>
                <th>Prix U</th>
                <th>Remise</th>
                <th>Total</th>
                <th>#</th>
            </tr>
        </thead>
        
    </table>
<script type="text/javascript">
$(document).ready(function() {
    var t = $('#example').DataTable({
    	bProcessing: true,
		//notifcol : 5,
		serverSide: true,
		bFilter: false,

		ajax_url:"testsou",
    	aoColumns: [
	        {"sClass": "center","sWidth":"5%"}, //
	        {"sClass": "center","sWidth":"10%"},
	        {"sClass": "left","sWidth":"25%"}, //
	        {"sClass": "left","sWidth":"10%"},
	        {"sClass": "center","sWidth":"5%"},
	        {"sClass": "center","sWidth":"5%"},
	        {"sClass": "center","sWidth":"10%"},
	        {"sClass": "center","sWidth":"5%"},
	        ],
	    });
    
    
    $('#addRow').on( 'click', function () {
        var $link  = $(this).attr('rel');
   		var $titre = $(this).attr('data_titre'); 
   		var $data  = $(this).attr('data'); 
        ajax_bbox_loader($link, $data, $titre, 'large')
    });

});
    </script>	
		</div>
	</div>
</div>
		
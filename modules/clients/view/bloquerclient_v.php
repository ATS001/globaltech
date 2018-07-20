<?php 
//SYS GLOBAL TECH
// Modul: clients => View
 defined('_MEXEC') or die; 
 ?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">
                    
        <?php 
              TableTools::btn_add('clients', 'Liste des Clients', Null, $exec = NULL, 'reply');      
         ?>

                    
    </div>
</div>
<div class="page-header">
    <h1>
        <?php echo ACTIV_APP; ?>
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small>
    </h1>
</div><!-- /.page-header -->
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
//
$form = new Mform('bloquerclient', 'bloquerclient','',  'clients', ' ');//Si on veut un wizzad on saisie 1, sinon null pour afficher un formulaire normal

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');


//Start Step 1
$form->step_start(1, 'Blocage Client');

//Catégorie client
$motif_array[]  = array('required', 'true', 'Sélectionnez la catégorie' );
$form->select_table('Motif de Blocage', 'id_motif_blocage', 8, 'motif_blocage_clients', 'id', 'motif' , 'motif', $indx = '------' ,
    $selected=NULL,$multi=NULL, $where='etat=1', $motif_array);

//Commentaire
$form->input_editor('Commentaire', 'commentaire', 6, $clauses=NULL , $js_array = null,  $input_height = 80);

//End Step 1
$form->step_end();

//Button submit 
$form->button('Bloquer Client');

//Form render
$form->render();
?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    });
    
   </script>  
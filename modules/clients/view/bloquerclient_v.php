<?php 
//SYS GLOBAL TECH
// Modul: clients => View
 defined('_MEXEC') or die; 
 //Get all compte info 
 $info_client = new Mclients();
//Set ID of Module with POST id
 $info_client->id_client = Mreq::tp('id');
 //var_dump(Mreq::tp('id'));

//Check if Post ID <==> Post idc or get_modul return false. 
 if(!MInit::crypt_tp('id', null, 'D')  or !$info_client->get_client())
 {  
    // returne message error red to client 
    exit('3#'.$info_client->log .'<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
 
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
        Bloquer Client 
        <small>
            <i class="ace-icon fa fa-aechongle-double-right"></i>
        </small>

        <?php echo ' ('.$info_client->Shw('denomination',1).' -'.$info_client->Shw('reference',1).'-)' ;
        
        ?>
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
$form = new Mform('bloquerclient', 'bloquerclient',$info_client->id,  'clients', ' ');//Si on veut un wizzad on saisie 1, sinon null pour afficher un formulaire normal

//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');

$form->input_hidden('id', $info_client->Shw('id',1));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));

//Start Step 1
$form->step_start(1, 'Blocage Client');

//Catégorie client
$motif_array[]  = array('required', 'true', 'Sélectionnez la catégorie' );
$form->select_table('Motif de Blocage', 'id_motif_blocage', 8, 'ref_motif_blocage', 'id', 'motif' , 'motif', $indx = '------' ,
    $selected=NULL,$multi=NULL, $where='type="C" and etat=1', $motif_array);

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
<?php 
//SYS GLOBAL TECH
// Modul: clients => View
 defined('_MEXEC') or die; 
 $id_prospect=Mreq::tp('id');
 ?>
<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">
                    
        <?php 
        if($id_prospect == null){
              TableTools::btn_add('clients', 'Liste des Clients', Null, $exec = NULL, 'reply');
        }
        else{
             TableTools::btn_add('prospects', 'Liste des Prospects', Null, $exec = NULL, 'reply');
        }  
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

if($id_prospect == null){
$form = new Mform('addclients', 'addclients','',  'clients', '1');
}
else{
$form = new Mform('addclients', 'addclients','',  'prospects', '1');   
}
//Step Wizard
$wizard_array[] = array(1,'Etape 1','active');
$wizard_array[] = array(2,'Etape 2');
$wizard_array[] = array(3,'Etape 3');
$form->wizard_steps = $wizard_array;


$form->input_hidden('id_prospect', $id_prospect);

//Start Step 1
$form->step_start(1, 'Renseignements client');
//Code
/*$code_array[]  = array('required', 'true', 'Insérer le code ' );
$code_array[]  = array('remote', 'code#clients#code', 'Ce Code Client existe déja' );
$form->input('Code Client', 'code', 'text' ,6 , null, $code_array);*/

//Denomination
$denomination_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$denomination_array[]  = array('required', 'true', 'Insérer La Dénomination' );
$denomination_array[]  = array('remote', 'denomination#clients#denomination', 'Ce client existe déja' );
$form->input('Dénomination', 'denomination', 'text' ,6 , null, $denomination_array);

//Catégorie client
if($id_prospect == null){
$cat_array[]  = array('required', 'true', 'Sélectionnez la catégorie' );
$form->select_table('Catégorie Client', 'id_categorie', 6, 'categorie_client', 'id', 'categorie_client' , 'categorie_client', $indx = '------' ,
    $selected=2,$multi=NULL, $where='etat=1', $cat_array);
}
else{
$prospects = new Mprospects();
$prospects->id_prospect = $id_prospect;
$prospects->get_prospect();
$cat_array[]  = array('required', 'true', 'Sélectionnez la catégorie' );
$form->select_table('Catégorie Client', 'id_categorie', 6, 'categorie_client', 'id', 'categorie_client' , 'categorie_client', $indx = '------' ,
    $selected=$prospects->g("offre"),$multi=NULL, $where='etat=1', $cat_array);
}

//Raison social
if($id_prospect == null){
//$rsocial_array[]  = array('required', 'true', 'Insérer Raison Social ' );
$rsocial_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$rsocial_array[]  = array('remote', 'r_social#clients#r_social', 'Cette société existe déja' );
$form->input('Raison Social', 'r_social', 'text' ,6 , null, $rsocial_array);
}
else{
$prospects = new Mprospects();
$prospects->id_prospect = $id_prospect;
$prospects->get_prospect();
//$rsocial_array[]  = array('required', 'true', 'Insérer Raison Social ' );
$rsocial_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$rsocial_array[]  = array('remote', 'r_social#clients#r_social', 'Cette société existe déja' );
$form->input('Raison Social', 'r_social', 'text' ,6 , $prospects->g("raison_sociale"), $rsocial_array);
}

//r_commerce
//$rc_array[]  = array('required', 'true', 'Insérer N° de registre de commerce' );
$rc_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$rc_array[]  = array('remote', 'r_commerce#clients#r_commerce', 'Ce N° de registre de commerce existe déja'); 
$form->input('N° de registre de commerce', 'r_commerce', 'text', 6, null, $rc_array);

//nif
//$nif_array[]  = array('required', 'true', 'Insérer N° Identification Fiscale' );
$nif_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$nif_array[]  = array('remote', 'nif#clients#nif', 'Ce N° Identification Fiscale existe déja'); 
$form->input('N° Identification Fiscale', 'nif', 'text', 6, null, $nif_array);

//End Step 1
$form->step_end();
//Start Step 2
$form->step_start(2, 'Informations du Représentant');

// nom personne à contacté 
$nom_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
//$nom_array[]  = array('required', 'true', 'Insérer Nom Représentant' );
$form->input('Nom', 'nom', 'text', 6, null, $nom_array);

// prenom personne à contacté 
$prenom_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
//$prenom_array[]  = array('required', 'true', 'Insérer Préom Représentant' );
$form->input('Prénom', 'prenom', 'text', 6, null, $prenom_array);

// civilite
$civilite_array[]  = array('Femme' , 'Femme' );
$civilite_array[]  = array('Homme' , 'Homme' );
$form->radio('Civilité', 'civilite', 'Femme', $civilite_array, '');

//Adresse
$adresse_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
//$adresse_array[]  = array('required', 'true', 'Insérer Adresse' );
$form->input('Adresse', 'adresse', 'text', 6, null, $adresse_array);

//Pays
//$pays_array[]  = array('required', 'true', 'Choisir le Pays' );
$form->select_table('Pays', 'id_pays', 6, 'ref_pays', 'id', 'pays' , 'pays', $indx = '------' ,242,$multi=NULL, $where='etat=1', null);

//ville
//$opt_ville = array('' => '------');

//$form->select('Ville', 'id_ville', 6, $opt_ville, $indx = NULL ,$selected = 43, $multi = NULL);

$form->select_table('Ville', 'id_ville', 6, 'ref_ville', 'id', 'ville' , 'ville', $indx = '------' ,$selected=43,$multi=NULL, $where='etat=1', null);

// Tél
$tel_array[]  = array('required', 'true', 'Insérer N° de téléphone' );
$tel_array[]  = array('minlength', '8', 'Le N° de téléphone doit contenir au moins 8 chiffres' );
//$tel_array[]  = array('remote', 'tel#clients#tel', 'Ce contact existe déja');
$tel_array[]  = array('number', 'true', 'Entrez un N° Téléphone Valid' );
$form->input('N° Téléphone', 'tel', 'text', 6, null, $tel_array);

// fax
$fax_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
//$fax_array[]  = array('required', 'true', 'Insérer N° de Fax' );
$fax_array[]  = array('minlength', '8', 'Le N° de Fax doit contenir au moins 8 chiffres' );
$fax_array[]  = array('number', 'true', 'Entrez un N° Fax Valid' );
$form->input('Fax', 'fax', 'text', 6, null, $fax_array);

// bp
$form->input('Boite Postale', 'bp', 'text', 6, null, '');

// email
$mail_array[]  = array('required', 'true', 'Insérer Email ' );
//$mail_array[]  = array('remote', 'email#clients#email', 'Ce contact existe déja');
$mail_array[]  = array('email', 'true', 'Adresse Email non valide' );
$form->input('Email ', 'email', 'text', 6, null, $mail_array);

//End Step 2
$form->step_end();
//Start Step 3

$form->step_start(3, 'Complément Informations');

//Banque
$form->select_table('Banque', 'id_banque', 6, 'ste_info_banque', 'id', 'banque' , 'banque', $indx = '------' ,$selected=1,$multi=NULL, $where='etat=1', null);

// devise
$form->select_table('Devise', 'id_devise', 6, 'ref_devise', 'id', 'devise' , 'devise', $indx = '------' ,
    $selected='1',$multi=NULL, $where='etat=1', NULL);

// taxe
$taxe_array[]  = array('Oui' , 'Oui' );
$taxe_array[]  = array('Non' , 'Non' );
$form->radio('TVA', 'tva', 'Oui', $taxe_array, '');


//pj_id
$form->input('Justifications du client', 'pj', 'file', 6, null, null);
$form->file_js('pj', 1000000, 'pdf');

//pj_id
$form->input('Photo du client', 'pj_photo', 'file', 6, null, null);
$form->file_js('pj_photo', 1000000, 'image');


$form->step_end();
//Button submit 
$form->button('Enregistrer le client');

//Form render
$form->render();
?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
//var $id_pays = $(this).val();
         $.ajax({


            cache: false,
            url  : '?_tsk=addclients&ajax=1',
            type : 'POST',
            data : '&act=1&id='+242+'&<?php echo MInit::crypt_tp('exec', 'load_select_ville') ?>',
            dataType:"JSON",
            success: function(data){
               
                if(data['error'] == false){
                    ajax_loadmessage(data['mess'] ,'nok',5000);
                    return false;
                }else{
                    $.each(data, function(key, value) {   
                     $('#id_ville')
                     .append($("<option></option>")
                         .attr("value",key)
                         .text(value)); 
                    });
                    $('#id_ville').trigger("chosen:updated");
                }
                
                
            }//end success
        });
    
    $('#id_pays').change(function(e) {
        var $id_pays = $(this).val();

        if($id_pays == null){
            return true;
        }
        $('#id_ville').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
        //$('#categ_produit').trigger('change');
        //$('#id_pays').find('option').remove().end().trigger("chosen:updated").append('<option>----</option>');
        $.ajax({

            cache: false,
            url  : '?_tsk=addclients&ajax=1',
            type : 'POST',
            data : '&act=1&id='+$id_pays+'&<?php echo MInit::crypt_tp('exec', 'load_select_ville') ?>',
            dataType:"JSON",
            success: function(data){
               
                if(data['error'] == false){
                    ajax_loadmessage(data['mess'] ,'nok',5000);
                    return false;
                }else{
                    $.each(data, function(key, value) {   
                     $('#id_ville')
                     .append($("<option></option>")
                         .attr("value",key)
                         .text(value)); 
                    });
                    $('#id_ville').trigger("chosen:updated");
                }
                
                
            }//end success
        });
    });
    

    });
    
   </script>  
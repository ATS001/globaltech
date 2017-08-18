<?php

/**
* Class Gestion Permissionnaire V1.0
*/


class Mprms {

    private $_data; //data receive from form

    var $table = 'permissionnaires'; //Main table of module
    var $last_id; //return last ID after insert command
    var $log = ''; //Log of all opération.
    var $error = true; //Error bol changed when an error is occured
    var $exige_logo; //set when logo is required must be defalut true.
    var $new_logo = ''; //new logo path
    var $exige_pj; // set when pk is required must be default true.
    var $new_pj= ''; //set new pj path
    var $id_prm; // User ID append when request
    var $token; //user for recovery function
    var $prm_info; //Array stock all prminfo 
    var $app_action; //Array action for each row
    public function __construct($properties = array()){
        $this->_data = $properties;
    }

    // magic methods!
    public function __set($property, $value){
        return $this->_data[$property] = $value;
    }

    public function __get($property){
        return array_key_exists($property, $this->_data)
        ? $this->_data[$property]
        : null
        ;
    }

    /**
     * Get information for one permissionaire
     * @return [Array] [fill $this->prm_info]
     */
    public function get_prm()
    {
        global $db;
        $table = $this->table;
        //Format Select commande
        $sql = "SELECT $table.*,r.categorie as cat, s.secteur as sect, IF($table.multi_national=1,'Oui','Non') as typ_group,p.pays as pays,v.ville as villes,nation.nation as nationalite FROM 
        $table,ref_categ_prm r,ref_secteur_activite s,ref_pays p,ref_pays nation,ref_ville v  WHERE $table.categorie=r.id and $table.pay_siege=p.id and $table.nation_p=nation.id and  $table.ville=v.id and $table.secteur_activ=s.id and $table.id = ".$this->id_prm;
        if(!$db->Query($sql))
        {
            $this->error = false;
            $this->log  .= $db->Error();
        }else{
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->prm_info = $db->RowArray();
                $this->error = true;
            }   
        }
        //return Array prm_info
        if($this->error == false)
        {
            return false;
        }else{
            return true;
        }
        
    }
        /**
     * Get id for one  permissionaire(r_social)
     * @return [Array] [fill $this->prm_info]
     */
    public function get_id_prm($r_social)
    {
        global $db;
        $table = $this->table;
        //Format Select commande
        $sql = "SELECT $table.* FROM 
        $table WHERE  $table.r_social = '".$r_social."'";
        if(!$db->Query($sql))
        {
            $this->error = false;
            $this->log  .= $db->Error();
        }else{
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->prm_info = $db->RowArray();
                $this->error = true;
            }   
        }
        //return Array prm_info
        if($this->error == false)
        {
            return false;
        }else{
            return true;
        }
        
    }


    /**
     * [Shw description]
     * @param [type] $key     [Key of info_array]
     * @param string $no_echo [Used for echo the value on View ]
     */
    public function Shw($key,$no_echo = null)
    {
        if($this->prm_info[$key] != null)
        {
            if($no_echo != null)
            {
                return $this->prm_info[$key];
            }

            echo $this->prm_info[$key];
        }else{
            echo "";
        }
        
    }

        /**
     * [s description]
     * @param [type] $key     [Key of info_array]
     * @param string $no_echo [Used for echo the value on View ]
     */
    public function s($key)
    {
      if($this->prm_info[$key] != null)
      {
        echo $this->prm_info[$key];
      }else{
        echo "";
      }
      
    }


    /**
     * [save_new_prm Save new Permissionaire after check values]
     * @return [bol] [Send Bol value to controller]
     */
    public function save_new_prm()
    {       
        //Befor execute do the multiple check
        // check raison social permissionnaire
        $this->Check_exist('r_social', $this->_data['r_social'], 'Permissionnaire', null);
                //Check Sigle permissionnaire
        $this->Check_exist('sigle', $this->_data['sigle'], 'N°', null);
        //Check RC
        $this->Check_exist('rc', $this->_data['rc'], 'N° de registre', null);           
        //Check NIF
        $this->Check_exist('nif', $this->_data['nif'], 'N° de NIF', null);
            //Check non exist secteur
        $this->check_non_exist('ref_secteur_activite', 'id', $this->_data['secteur_activ'], 'Secteur d\'activité');
        //Check non exist secteur
        $this->check_non_exist('ref_pays', 'id', $this->_data['pay_siege'], 'Pays de siège');
        //Check non exist catégorie
        $this->check_non_exist('ref_categ_prm', 'id', $this->_data['categorie'], 'Catégorie');
        //Check non exist Ville
        $this->check_non_exist('ref_ville', 'id', $this->_data['ville'], 'Ville');
        //Check non exist Nation
        $this->check_non_exist('ref_pays', 'id', $this->_data['nation_p'], 'Nationalité');
        //Check if PJ attached required
        if($this->exige_pj)
        {
            $this->check_file('pj', 'Le formulaire d\' Enregistrement.');
        }
        //Check $this->error (true / false)
        if($this->error == true){
            //Format values for Insert query 
            global $db;
            $values["r_social"]       = MySQL::SQLValue($this->_data['r_social']);
            $values["sigle"]          = MySQL::SQLValue($this->_data['sigle']);
            $values["categorie"]      = MySQL::SQLValue($this->_data['categorie']);
            $values["secteur_activ"]  = MySQL::SQLValue($this->_data['secteur_activ']);
            $values["pay_siege"]      = MySQL::SQLValue($this->_data['pay_siege']);
            $values["multi_national"] = MySQL::SQLValue($this->_data['multi_national']);
            $values["nif"]            = MySQL::SQLValue($this->_data['nif']);
            $values["rc"]             = MySQL::SQLValue($this->_data['rc']);
            $values["adresse"]        = MySQL::SQLValue($this->_data['adresse']);
            $values["bp"]             = MySQL::SQLValue($this->_data['bp']);
            $values["ville"]          = MySQL::SQLValue($this->_data['ville']);
            $values["email"]          = MySQL::SQLValue($this->_data['email']);
            $values["tel"]            = MySQL::SQLValue($this->_data['tel']);
            $values["fax"]            = MySQL::SQLValue($this->_data['fax']);
            $values["nom_p"]          = MySQL::SQLValue($this->_data['nom_p']);
            $values["qualite_p"]      = MySQL::SQLValue($this->_data['qualite_p']);
            $values["nation_p"]       = MySQL::SQLValue($this->_data['nation_p']);
            $values["adresse_p"]      = MySQL::SQLValue($this->_data['adresse_p']);
            $values["tel_p"]          = MySQL::SQLValue($this->_data['tel_p']);
            $values["email_p"]        = MySQL::SQLValue($this->_data['email_p']);
            $values["creusr"]         = MySQL::SQLValue(session::get('userid'));

            //Check if Insert Query been executed (False / True)
            if (!$result = $db->InsertRow($this->table, $values)){
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .='</br>Enregistrement BD non réussie'; 

            }else{

                $this->last_id = $result;
                //If Attached required Save file to Archive
                if($this->exige_pj)
                {
                    $this->save_file('pj', 'Formulaire d\'enregistrement Permissionnaire '.$this->_data['r_social'], 'Document');
                }               
                //Check $this->error = true return Green message and Bol true
                if($this->error == true)
                {
                    $this->log = '</br>Enregistrement réussie: <b>'.$this->_data['r_social'].' ID: '.$this->last_id;
                //Check $this->error = false return Red message and Bol false   
                }else{
                    $this->log .= '</br>Enregistrement réussie: <b>'.$this->_data['r_social'];
                    $this->log .= '</br>Un problème d\'Enregistrement ';
                }
            }
        //Else Error false  
        }else{
            $this->log .='</br>Enregistrement non réussie';
        }
        //check if last error is true then return true else rturn false.
        if($this->error == false){
            return false;
        }else{
            return true;
        }
    }
    /**
     * Edit existingpermissionnaire
     * @return [bol] [Send bol value to controller]
     */
    public function edit_prm()
    {   
        //Get existing data for prm
        $this->get_prm();
        
        $this->last_id = $this->id_prm; 
        //Befor execute do the multiple check
        // check raison social permissionnaire
        $this->Check_exist('r_social', $this->_data['r_social'], 'Permissionnaire', $this->id_prm);
        //Check Sigle permissionnaire
        $this->Check_exist('sigle', $this->_data['sigle'], 'Sigle', $this->id_prm);
        //Check RC
        $this->Check_exist('rc', $this->_data['rc'], 'N° de registre', $this->id_prm);          
        //Check NIF
        $this->Check_exist('nif', $this->_data['nif'], 'N° de NIF', $this->id_prm);
            //Check non exist secteur
        $this->check_non_exist('ref_secteur_activite', 'id', $this->_data['secteur_activ'], 'Secteur d\'activité');
        //Check non exist secteur
        $this->check_non_exist('ref_pays', 'id', $this->_data['pay_siege'], 'Pays de siège');
        //Check non exist catégorie
        $this->check_non_exist('ref_categ_prm', 'id', $this->_data['categorie'], 'Catégorie');
        //Check non exist Ville
        $this->check_non_exist('ref_ville', 'id', $this->_data['ville'], 'Ville');
        //Check non exist Nation
        $this->check_non_exist('ref_pays', 'id', $this->_data['nation_p'], 'Nationalité');
        //Check if PJ attached required
        if($this->exige_pj)
        {
            $this->check_file('pj', 'Le formulaire d\' Enregistrement.', $this->_data['pj_id']);
        }
        //Check $this->error (true / false)
        if($this->error == true){
            //Format values for Insert query 
            global $db;
            $values["r_social"]       = MySQL::SQLValue($this->_data['r_social']);
            $values["sigle"]          = MySQL::SQLValue($this->_data['sigle']);
            $values["categorie"]      = MySQL::SQLValue($this->_data['categorie']);
            $values["secteur_activ"]  = MySQL::SQLValue($this->_data['secteur_activ']);
            $values["pay_siege"]      = MySQL::SQLValue($this->_data['pay_siege']);
            $values["multi_national"] = MySQL::SQLValue($this->_data['multi_national']);
            $values["nif"]            = MySQL::SQLValue($this->_data['nif']);
            $values["rc"]             = MySQL::SQLValue($this->_data['rc']);
            $values["adresse"]        = MySQL::SQLValue($this->_data['adresse']);
            $values["bp"]             = MySQL::SQLValue($this->_data['bp']);
            $values["ville"]          = MySQL::SQLValue($this->_data['ville']);
            $values["email"]          = MySQL::SQLValue($this->_data['email']);
            $values["tel"]            = MySQL::SQLValue($this->_data['tel']);
            $values["fax"]            = MySQL::SQLValue($this->_data['fax']);
            $values["nom_p"]          = MySQL::SQLValue($this->_data['nom_p']);
            $values["qualite_p"]      = MySQL::SQLValue($this->_data['qualite_p']);
            $values["nation_p"]       = MySQL::SQLValue($this->_data['nation_p']);
            $values["adresse_p"]      = MySQL::SQLValue($this->_data['adresse_p']);
            $values["tel_p"]          = MySQL::SQLValue($this->_data['tel_p']);
            $values["email_p"]        = MySQL::SQLValue($this->_data['email_p']);
            $values["upddat"]            = ' CURRENT_TIMESTAMP ';
            $values["updusr"]         = MySQL::SQLValue(session::get('userid'));
            $wheres["id"]             = MySQL::SQLValue($this->id_prm);

            //Check if Update Query been executed (False / True)
            if (!$result = $db->UpdateRows($this->table, $values, $wheres)){
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .='</br>Modification BD non réussie'; 

            }else{

                
                //If Attached required Save file to Archive
                if($this->exige_pj)
                {
                    $this->save_file('pj', 'Formulaire d\'enregistrement Permissionnaire '.$this->_data['r_social'], 'Document');
                }
                //Esspionage
                if(!$db->After_update($this->table, $this->id_prm, $values, $this->prm_info)){
                    $this->log .= '</br>Problème Esspionage';
                    $this->error = false;   
                }               
                //Check $this->error = true return Green message and Bol true
                if($this->error == true)
                {
                    $this->log = '</br>Modification réussie: <b>'.$this->_data['r_social'].' ID: '.$this->last_id;
                //Check $this->error = false return Red message and Bol false   
                }else{
                    $this->log .= '</br>Modification réussie: <b>'.$this->_data['r_social'];
                    $this->log .= '</br>Un problème d\'Enregistrement ';
                }
            }
        //Else Error false  
        }else{
            $this->log .='</br>Modification non réussie';
        }
        //check if last error is true then return true else rturn false.
        if($this->error == false){
            return false;
        }else{
            return true;
        }
    }
    /**
     * [save_file For save anattached file for entrie ]
     * @param  [string] $item  [input_name of attached file we add _id]
     * @param  [string] $titre [Title stored for file on Archive DB]
     * @param  [string] $type  [Type of file (Document, PDF, Image)]
     * @return [Setting]       [Set $this->error and $this->log]
     */
    private function save_file($item, $titre, $type)
    {
        //Format all parameteres
        $temp_file     = $this->_data[$item.'_id'];
        $new_name_file = $item.'_'.$this->last_id;
        $folder        = MPATH_UPLOAD.'permissionnaires'.SLASH.$this->last_id;
        $id_line       = $this->last_id;
        $title         = $titre;
        $table         = $this->table;
        $column        = $item;
        $type          = $type;

        //Call save_file_upload from initial class
        if(!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'Permissionnaire', $table, $column, $type, $edit = null))
        {
            $this->error = false;
            $this->log .='</br>Enregistrement '.$item.' dans BD non réussie';
        }
    }
    /**
     * [check_file Check attached if required stop Insert this must be placed befor Insert commande]
     * @param  [string] $item [input_name of attached file we add _id]
     * @param  [string] $msg  [description]
     * @param  [int] $edit    [Used if is edit action must be the ID of row edited]
     * @return [Setting]      [Set $this->error and $this->log]
     */
    Private function check_file($item, $msg = null, $edit = null)
    {
        //Format temporary file
        $temp_file     = $this->_data[$item.'_id'];
        //Check if is edit action (is numeric when called from archive DB else is retrned target upload)
        if($edit != null && !is_numeric($temp_file))
        {
            if(!file_exists($temp_file))
            {
                $this->log .= '</br>Il faut choisir '.$msg.' pour la mise à jour '.$edit;
                $this->error = false;
            }
        //When is not edit do check for existing file
        }else{
            if($edit == null && $this->exige_.$item == true && ($this->_data[$item.'_id'] == null || !file_exists($this->_data[$item.'_id'])))
            {
                $this->log .= '</br>Il faut choisir '.$msg. '  '.$edit;
                $this->error = false; 
            }
        }

    }
    /**
     * [check_exist Check if one entrie already exist on table]
     * @param  [string] $column  [Column of field on main table]
     * @param  [string] $value   [the value to check]
     * @param  [string] $message [Returned message if exist]
     * @param  [int] $edit       [Used if is edit action must be the ID of row edited]
     * @return [Setting]         [Set $this->error and $this->log]
     */
    private function check_exist($column, $value, $message, $edit = null)
    {
        global $db;
        $table = $this->table;
        $sql_edit = $edit == null ? null: " AND id <> $edit";
        $result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
            WHERE $table.$column = ". MySQL::SQLValue($value) ." $sql_edit ");
        
        if ($result != "0") {
            $this->error = false;
            $this->log .='</br>'.$message.' exist déjà';
        }
    }
    /**
     * [check_non_exist Check if one entrie not exist on referential table]
     * @param  [string] $table   [referential table]
     * @param  [string] $column  [Column bechecked on referential table]
     * @param  [string] $value   [the value to check]
     * @param  [string] $message [Returned message if not  exist]
     * @return [Setting]         [Set $this->error and $this->log]
     */
    private function check_non_exist($table, $column, $value, $message)
    {
        global $db;
        $result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
            WHERE $table.$column = ". MySQL::SQLValue($value));
        if ($result == "0") {
            $this->error = false;
            $this->log .='</br>'.$message.' n\'exist pas';
            //exit('0#'.$this->log);
        }
    }

    /**
     * [valid_compte Validate compte permissionnaire]
     * @param  integer $etat [Etat de permissionnaire]
     * @return [bool]        [Retrn bol value read by controller]
     */
    public function valid_prm($etat = 0)
    {
        global $db;
        //Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
        $etat = $etat == 0 ? 1 : 0;
        //Format value for requet
        $value["etat"] = MySQL::SQLValue($etat);
        $where["id"]   = $this->id_prm;
        // Execute the update and show error case error
        if( !$result = $db->UpdateRows($this->table, $value , $where))
        {
            $this->log .= '</br>Impossible de changer le statut!';
            $this->log .= '</br>'.$db->Error();
            $this->error = false;
        }else{
            $this->log .= '</br>Statut changé! ';
            $this->error = true;

        } 
        if($this->error == false){
            return false;
        }else{
            return true;
        }
    }
     public function delete_prm()
    {
        global $db;
        //Format where clause
        $where['id'] = $this->id_prm;
        //check if id on where clause isset
        if($where['id'] == null)
        {
            $this->error = false;
            $this->log .='</br>L\' id est vide';
        }
        //execute Delete Query
        if(!$db->DeleteRows($this->table,$where))
        {
            $this->log .='</br>Suppression non réussie';
            $this->log .= '</br>'.$db->Error();
            $this->error = false;
            
        }else{
            
            $this->error = true;
            $this->log .='</br>Suppression réussie ';
        }
        //check if last error is true then return true else rturn false.
        if($this->error == false){
            return false;
        }else{
            return true;
        }
    }

}


?>
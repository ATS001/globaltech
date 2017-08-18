<?php

/**
 * Class Gestion BLR STATIONS V1.0
 */
class Muhf_vhf_stations {

    private $_data; //data receive from form
    var $table = 'uhf_vhf_stations'; //Main table of module
    var $last_id; //return last ID after insert command
    var $log = ''; //Log of all opération.
    var $error = true; //Error bol changed when an error is occured
    var $exige_pj; // set when pk is required must be default true.
    var $id_uhf_vhf_stations; // User ID append when request
    var $token; //user for recovery function
    var $uhf_vhf_stations_info; //Array stock all blrinfo 
    var $app_action; //Array action for each row

    public function __construct($properties = array()) {
        $this->_data = $properties;
    }

    // magic methods!
    public function __set($property, $value) {
        return $this->_data[$property] = $value;
    }

    public function __get($property) {
        return array_key_exists($property, $this->_data) ? $this->_data[$property] : null;
    }

    /**
     * Get information for one permissionaire
     * @return [Array] [fill $this->uhf_vhf_stations_info]
     */
    public function get_uhf_vhf_stations() {
        global $db;
        $table = $this->table;
        //Format Select commande
        $sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = " . $this->id_uhf_vhf_stations;
        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->uhf_vhf_stations_info = $db->RowArray();
                $this->error = true;
            }
        }
        //return Array prm_info
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * [Shw description]
     * @param [type] $key     [Key of info_array]
     * @param string $no_echo [Used for echo the value on View ]
     */
    public function Shw($key, $no_echo = null) {
        if ($this->uhf_vhf_stations_info[$key] != null) {
            if ($no_echo != null) {
                return $this->uhf_vhf_stations_info[$key];
            }

            echo $this->uhf_vhf_stations_info[$key];
        } else {
            echo "";
        }
    }

    /**
     * [save_new_prm Save new Permissionaire after check values]
     * @return [bol] [Send Bol value to controller]
     */
    public function save_new_uhf_vhf_stations() {
        if ($this->exige_pj) {
            $this->check_file('pj', 'Le formulaire d\' Enregistrement.');
        }

        if ($this->error == true) {
            global $db;
            $values["prm"]           = MySQL::SQLValue($this->_data['prm']);
            $values["site"]          = MySQL::SQLValue($this->_data['site']);
            $values["longi"]         = MySQL::SQLValue($this->_data['longi']);
            $values["latit"]         = MySQL::SQLValue($this->_data['latit']);
            $values["ville"]         = MySQL::SQLValue($this->_data['ville']);
            $values["modele"]        = MySQL::SQLValue($this->_data['modele']);
            $values["hauteur"]       = MySQL::SQLValue($this->_data['hauteur']);
            $values["puissance"]     = MySQL::SQLValue($this->_data['puissance']);
            $values["frequence"]     = MySQL::SQLValue($this->_data['frequence']);
            $values["modulation"]    = MySQL::SQLValue($this->_data['modulation']);
            $values["porte_antenne"] = MySQL::SQLValue($this->_data['porte_antenne']);
            $values["type_station"]  = MySQL::SQLValue($this->_data['type_station']);
            $values["date_visite"]   = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_visite'])));
            $values["creusr"]        = MySQL::SQLValue(session::get('userid'));

            //Check if Insert Query been executed (False / True)

            if (!$result = $db->InsertRow($this->table, $values)) {
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;

                if ($this->exige_pj) {
                    $this->save_file('pj', 'Formulaire d\'enregistrement Station BLR ' . $this->_data['site'], 'Document');
                }
                //Add Gallerie Images
                //Set Folder
                $folder = MPATH_UPLOAD . 'uhf_vhf_stations' . SLASH . $this->last_id;
                //Call save_multiple files
                //save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
                if (!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->last_id, $this->_data['photo_titl'], 'uhf_vhf_stations', $this->table, 'pj_images', $edit = null)) {
                    $this->error = false;
                    $this->log .= '</br>Problème archivage Images';
                }
                //Insert Marker MAP
                if(!$this->add_marker_vhf($this->last_id, $this->_data['ville'], $this->_data['longi'], $this->_data['latit'],$this->_data['porte_antenne'] , 'Station '.$this->_data['type_station'].' '.$this->_data['site'].' -'.$this->last_id.'-'))
                {
                    $this->error = false;
                    $this->log .='</br>Problème Ajout Marker';

                }

                $this->log .= '</br>Enregistrement  réussie ' . $this->_data['site'] . ' - ' . $this->last_id . ' -';
            }
        } else {

            $this->log .= '</br>Enregistrement non réussie';
        }

        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function edit_uhf_vhf_stations() {
        //Get existing data for prm
        $this->get_uhf_vhf_stations();

        $this->last_id = $this->id_uhf_vhf_stations;

        if ($this->exige_pj) {
            $this->check_file('pj', 'Le formulaire d\' Enregistrement.', $this->_data['pj_id']);
        }

        //Check $this->error (true / false)
        //Format values for Insert query
        if ($this->error == true) {
            global $db;
            $values["prm"]           = MySQL::SQLValue($this->_data['prm']);
            $values["site"]          = MySQL::SQLValue($this->_data['site']);
            $values["longi"]         = MySQL::SQLValue($this->_data['longi']);
            $values["latit"]         = MySQL::SQLValue($this->_data['latit']);
            $values["ville"]         = MySQL::SQLValue($this->_data['ville']);
            $values["modele"]        = MySQL::SQLValue($this->_data['modele']);
            $values["hauteur"]       = MySQL::SQLValue($this->_data['hauteur']);
            $values["puissance"]     = MySQL::SQLValue($this->_data['puissance']);
            $values["frequence"]     = MySQL::SQLValue($this->_data['frequence']);
            $values["modulation"]    = MySQL::SQLValue($this->_data['modulation']);
            $values["porte_antenne"] = MySQL::SQLValue($this->_data['porte_antenne']);
            $values["type_station"]  = MySQL::SQLValue($this->_data['type_station']);
            $values["date_visite"]   = MySQL::SQLValue(date('Y-m-d', strtotime($this->_data['date_visite'])));
            $wheres["id"]            = MySQL::SQLValue($this->id_uhf_vhf_stations);
            $values["updusr"]        = MySQL::SQLValue(session::get('userid'));
            $values["upddat"]        = MySQL::SQLValue(date("Y-m-d H:i:s"));


            //Check if Update Query been executed (False / True)

            if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Modification BD non réussie';
            } else {
                if ($this->exige_pj) {
                    $this->save_file('pj', 'Formulaire d\'enregistrement  ' . $this->_data['site'], 'Document');
                }
                //Esspionage
                if (!$db->After_update($this->table, $this->id_uhf_vhf_stations, $values, $this->uhf_vhf_stations_info)) {
                    $this->log .= '</br>Problème Esspionage';
                    $this->error = false;
                }
                //Add Gallerie Images
                //Set Folder
                $folder = MPATH_UPLOAD . 'uhf_vhf_stations' . SLASH . $this->id_uhf_vhf_stations;
                //Call save_multiple files
                //save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
                if (!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->id_uhf_vhf_stations, $this->_data['photo_titl'], 'uhf_vhf_stations', $this->table, 'pj_images', $this->uhf_vhf_stations_info['pj_images'])) {
                    $this->error = false;
                    $this->log .= '</br>Problème archivage Images';
                }
                //Insert Marker MAP
                if(!$this->add_marker_vhf($this->last_id, $this->_data['ville'], $this->_data['longi'], $this->_data['latit'],$this->_data['porte_antenne'] , 'Station '.$this->_data['type_station'].' '.$this->_data['site'].' -'.$this->last_id.'-'))
                {
                    $this->error = false;
                    $this->log .='</br>Problème Ajout Marker';

                }
                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Modification réussie: <b>' . $this->_data['site'] . ' ID: ' . $this->id_uhf_vhf_stations;
                    //Check $this->error = false return Red message and Bol false	
                } else {
                    $this->log .= '</br>Modification réussie: <b>' . $this->_data['site'];
                    $this->log .= '</br>Un problème d\'Enregistrement ';
                }

            }
            //Else Error false	
        } else {
            $this->log .= '</br>Modification non réussie';
        }

        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    public function delete_uhf_vhf_stations() {
        global $db;
        $id_uhf_vhf_stations = $this->id_uhf_vhf_stations;
        $where['id'] = MySQL::SQLValue($id_uhf_vhf_stations);
        if (!$db->DeleteRows('uhf_vhf_stations', $where)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= '</br>Suppression non réussie';
        } else {
            $this->error = true;
            $this->log .= '</br>Suppression réussie';
        }
        //check if last error is true then return true else rturn false.
        if ($this->error == false) {
            return false;
        } else {
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
    private function save_file($item, $titre, $type) {

        //Format all parameteres
        $temp_file = $this->_data[$item . '_id'];
        $new_name_file = $item . '_' . $this->last_id;
        $folder = MPATH_UPLOAD . 'uhf_vhf_stations' . SLASH . $this->last_id;
        $id_line = $this->last_id;
        $title = $titre;
        $table = $this->table;
        $column = $item;
        $type = $type;

        //Call save_file_upload from initial class
        if (!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'uhf_vhf_stations', $table, $column, $type, $edit = null)) {
            $this->error = false;
            $this->log .= '</br>Enregistrement ' . $item . ' dans BD non réussie';
        }
    }

    /**
     * [check_file Check attached if required stop Insert this must be placed befor Insert commande]
     * @param  [string] $item [input_name of attached file we add _id]
     * @param  [string] $msg  [description]
     * @param  [int] $edit    [Used if is edit action must be the ID of row edited]
     * @return [Setting]      [Set $this->error and $this->log]
     */
    Private function check_file($item, $msg = null, $edit = null) {
        //Format temporary file
        $temp_file = $this->_data[$item . '_id'];
        //Check if is edit action (is numeric when called from archive DB else is retrned target upload)
        if ($edit != null && !is_numeric($temp_file)) {
            if (!file_exists($temp_file)) {
                $this->log .= '</br>Il faut choisir ' . $msg . ' pour la mise à jour ' . $edit;
                $this->error = false;
            }
            //When is not edit do check for existing file
        } else {
            if ($edit == null && $this->exige_ . $item == true && ($this->_data[$item . '_id'] == null || !file_exists($this->_data[$item . '_id']))) {
                $this->log .= '</br>Il faut choisir ' . $msg . '  ' . $edit;
                $this->error = false;
            }
        }
    }

    public function valid_uhf_vhf_stations() {
        global $db;
        $values['etat'] = ' ETAT + 1 ';
        $wheres['id'] = MySQL::SQLValue($this->id_uhf_vhf_stations);

        if (!$result = $db->UpdateRows('uhf_vhf_stations', $values, $wheres)) {
            $this->log .= $db->Error();
            $this->error = false;
            $this->log .= 'Activation non réussie DB';
        } else {
            $this->log .= 'Activation réussie';
            $this->error = true;
        }
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }
    
      public function printattribute($attibute) {
        if ($this->uhf_vhf_stations_info[$attibute] != null) {
            echo $this->uhf_vhf_stations_info[$attibute];
        } else {
            echo "";
        }
    }
    
    public function get_uhf_vhf_stations_info() {
        global $db;
        $table = $this->table;
        //Format Select commande
        $sql = "SELECT $table.*,permissionnaires.r_social,ref_ville.ville
                FROM $table,permissionnaires, ref_ville"
                . " WHERE  $table.prm  = permissionnaires.id AND $table.ville = ref_ville.id AND $table.id = " . $this->id_uhf_vhf_stations;
        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->uhf_vhf_stations_info = $db->RowArray();
                $this->error = true;
            }
        }
        //return Array prm_info
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

     /**
     * [add_marker_vhf Add Marker to map]
     * @param [type] $id_vhf [description]
     * @param [type] $ville  [description]
     * @param [type] $log    [description]
     * @param [type] $lalt   [description]
     * @param [type] $text   [description]
     */
    public function add_marker_vhf($id_vhf, $ville, $log, $lalt, $radius, $text)
    {

        
        global $db;
        //Delete if an marker have same value
        if(!$db->Query("DELETE FROM map_markers WHERE marker_categorie = 'vhf' AND marker_line_id = $id_vhf"))
        {
            $this->error = false;
            $this->log .='</br>Unable to delete existing Marker'; 
        }

        $values["marker_categorie"] = MySQL::SQLValue('vhf');
        $values["marker_line_id"]   = MySQL::SQLValue($id_vhf);
        $values["marker_ville"]     = MySQL::SQLValue($ville);
        $values["marker_longitude"] = MySQL::SQLValue($log);
        $values["marker_latitude"]  = MySQL::SQLValue($lalt);
        $values["marker_text"]      = MySQL::SQLValue($text);
        $values["marker_radius"]      = MySQL::SQLValue($radius);
        $values["marker_actif"]     = MySQL::SQLValue(1);
        
        
        
        // If we have an error
        if($this->error == true){

            if (!$result = $db->InsertRow("map_markers", $values)) {
                //$db->Kill();
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .='</br>Enregistrement Marker BD non réussie'; 

            }else{

                if(!$db->UpdateSinglRows($this->table, 'marker_map', $result, $id_vhf))
                {
                    $this->error = false;
                    $this->log .='</br>Enregitrement Marker dans Table VHF non réussie'; 
                }else{
                    $this->error = true;
                    $this->log = '</br>Enregistrement Marker réussie: <b>';
                }

            }


        }else{

            $this->log .='</br>Enregistrement Marker non réussie';

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
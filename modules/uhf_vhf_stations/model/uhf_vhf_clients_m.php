<?php

/**
 * Class Gestion UHF VHF CLIENTS V1.0
 */
class Muhf_vhf_clients {

    private $_data; //data receive from form
    var $table = 'uhf_vhf_clients'; //Main table of module
    var $last_id; //return last ID after insert command
    var $log = ''; //Log of all opération.
    var $error = true; //Error bol changed when an error is occured
    var $id_uhf_vhf_clients; // User ID append when request
    var $token; //user for recovery function
    var $uhf_vhf_clients_info; //Array stock all uhf_vhfinfo 
    var $app_action; //Array action for each row

    public function __construct($properties = array()) {
        $this->_data = $properties;
    }

    // magic methods!
    public function __set($property, $value) {
        return $this->_data[$property] = $value;
    }

    public function __get($property) {
        return array_key_exists($property, $this->_data) ? $this->_data[$property] : null
        ;
    }

    /**
     * Get information for one client
     * @return [Array] [fill $this->uhf_vhf_stations_info]
     */
    public function get_uhf_vhf_clients() {
        global $db;
        $table = $this->table;
        //Format Select commande
        $sql = "SELECT $table.* FROM 
		$table WHERE  $table.id = " . $this->id_uhf_vhf_clients;
        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->uhf_vhf_clients_info = $db->RowArray();
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
        if ($this->uhf_vhf_clients_info[$key] != null) {
            if ($no_echo != null) {
                return $this->uhf_vhf_clients_info[$key];
            }

            echo $this->uhf_vhf_clients_info[$key];
        } else {
            echo "";
        }
    }

    public function save_new_uhf_vhf_clt_fixe() {


        global $db;
        $values["type_client"] = MySQL::SQLValue($this->_data['type_client']);
        $values["station_base"] = MySQL::SQLValue($this->_data['station_base']);
        $values["longi"] = MySQL::SQLValue($this->_data['longi']);
        $values["latit"] = MySQL::SQLValue($this->_data['latit']);
        $values["marque"] = MySQL::SQLValue($this->_data['marque']);
        $values["modele"] = MySQL::SQLValue($this->_data['modele']);
        $values["num_serie"] = MySQL::SQLValue($this->_data['num_serie']);
        $values["active"] = MySQL::SQLValue($this->_data['active']);
        $values["creusr"] = MySQL::SQLValue(session::get('userid'));

        if ($this->error == true) {
            if (!$result = $db->InsertRow("uhf_vhf_clients", $values)) {
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
                //Add Gallerie Images
                //Set Folder
                $folder = MPATH_UPLOAD . 'uhf_vhf_clients' . SLASH . $this->last_id;
                //Call save_multiple files
                //save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
                if (!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->last_id, $this->_data['photo_titl'], 'uhf_vhf_clients', $this->table, 'pj_images', $edit = null)) {
                    $this->error = false;
                    $this->log .= '</br>Problème archivage Images';
                }

                $this->log .= '</br>Enregistrement  réussie ' . $this->_data['station_base'] . ' - ' . $this->last_id . ' -';
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

    public function save_new_uhf_vhf_clt_mobile() {


        global $db;
        $values["type_client"] = MySQL::SQLValue($this->_data['type_client']);
        $values["station_base"] = MySQL::SQLValue($this->_data['station_base']);
        $values["marque"] = MySQL::SQLValue($this->_data['marque']);
        $values["modele"] = MySQL::SQLValue($this->_data['modele']);
        $values["num_serie"] = MySQL::SQLValue($this->_data['num_serie']);
        $values["matricule_vehicule"] = MySQL::SQLValue($this->_data['matricule_vehicule']);
        $values["active"] = MySQL::SQLValue($this->_data['active']);
        $values["creusr"] = MySQL::SQLValue(session::get('userid'));

        if ($this->error == true) {
            if (!$result = $db->InsertRow("uhf_vhf_clients", $values)) {
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
                //Add Gallerie Images
                //Set Folder
                $folder = MPATH_UPLOAD . 'uhf_vhf_clients' . SLASH . $this->last_id;
                //Call save_multiple files
                //save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
                if (!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->last_id, $this->_data['photo_titl'], 'uhf_vhf_clients', $this->table, 'pj_images', $edit = null)) {
                    $this->error = false;
                    $this->log .= '</br>Problème archivage Images';
                }

                $this->log .= '</br>Enregistrement  réussie ' . $this->_data['station_base'] . ' - ' . $this->last_id . ' -';
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

    public function save_new_uhf_vhf_clt_handset() {


        global $db;
        $values["type_client"]  = MySQL::SQLValue($this->_data['type_client']);
        $values["station_base"] = MySQL::SQLValue($this->_data['station_base']);
        $values["marque"]       = MySQL::SQLValue($this->_data['marque']);
        $values["modele"]       = MySQL::SQLValue($this->_data['modele']);
        $values["num_serie"]    = MySQL::SQLValue($this->_data['num_serie']);
        $values["active"]       = MySQL::SQLValue($this->_data['active']);
        $values["creusr"]       = MySQL::SQLValue(session::get('userid'));

        if ($this->error == true) {
            if (!$result = $db->InsertRow("uhf_vhf_clients", $values)) {
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Enregistrement BD non réussie';
            } else {

                $this->last_id = $result;
                //Add Gallerie Images
                //Set Folder
                $folder = MPATH_UPLOAD . 'uhf_vhf_clients' . SLASH . $this->last_id;
                //Call save_multiple files
                //save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
                if (!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->last_id, $this->_data['photo_titl'], 'uhf_vhf_clients', $this->table, 'pj_images', $edit = null)) {
                    $this->error = false;
                    $this->log .= '</br>Problème archivage Images';
                }

                //Insert Marker MAP case station fixe
                if($this->_data['type_client'] == 'Fixe')
                {
                    if(!$this->add_marker_vhf_clt($this->last_id, $this->_data['longi'], $this->_data['latit'], 'Station VHF CLient '.$this->_data['site'].' -'.$this->last_id.'-'))
                    {
                       $this->error = false;
                       $this->log .='</br>Problème Ajout Marker';
                    } 
                }
                

                $this->log .= '</br>Enregistrement  réussie ' . $this->_data['station_base'] . ' - ' . $this->last_id . ' -';
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
        $folder = MPATH_UPLOAD . 'uhf_vhf_clients' . SLASH . $this->last_id;
        $id_line = $this->last_id;
        $title = $titre;
        $table = $this->table;
        $column = $item;
        $type = $type;

        //Call save_file_upload from initial class
        if (!Minit::save_file_upload($temp_file, $new_name_file, $folder, $id_line, $title, 'uhf_vhf_clients', $table, $column, $type, $edit = null)) {
            $this->error = false;
            $this->log .= '</br>Enregistrement ' . $item . ' dans BD non réussie';
        }
    }

    public function edit_uhf_vhf_clt_fixe() {
        //Get data for selected station
        $this->get_uhf_vhf_clients();
        $this->last_id = $this->id_uhf_vhf_clients;

        global $db;
        $values["longi"]     = MySQL::SQLValue($this->_data['longi']);
        $values["latit"]     = MySQL::SQLValue($this->_data['latit']);
        $values["marque"]    = MySQL::SQLValue($this->_data['marque']);
        $values["modele"]    = MySQL::SQLValue($this->_data['modele']);
        $values["num_serie"] = MySQL::SQLValue($this->_data['num_serie']);
        $values["active"]    = MySQL::SQLValue($this->_data['active']);
        $values["updusr"]    = MySQL::SQLValue(session::get('userid'));
        $values["upddat"]    = MySQL::SQLValue(date("Y-m-d H:i:s"));
        $wheres["id"]        = MySQL::SQLValue($this->id_uhf_vhf_clients);
        //Check $this->error (true / false)
        if ($this->error == true) {
            //Check if Update Query been executed (False / True)
            if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Modification BD non réussie';
            } else {
                //Esspionage
                if (!$db->After_update($this->table, $this->id_uhf_vhf_clients, $values, $this->uhf_vhf_clients_info)) {
                    $this->log .= '</br>Problème Esspionage';
                    $this->error = false;
                }
                //Add Gallerie Images
                //Set Folder
                $folder = MPATH_UPLOAD . 'uhf_vhf_clients' . SLASH . $this->id_uhf_vhf_clients;
                //Call save_multiple files
                //save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
                if (!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->id_uhf_vhf_clients, $this->_data['photo_titl'], 'uhf_vhf_clients', $this->table, 'pj_images', $this->uhf_vhf_clients_info['pj_images'])) {
                    $this->error = false;
                    $this->log .= '</br>Problème archivage Images';
                }
                //Insert Marker MAP case station fixe
                if($this->_data['type_client'] == 'Fixe')
                {
                    if(!$this->add_marker_vhf_clt($this->last_id, $this->_data['longi'], $this->_data['latit'], 'Station VHF CLient '.$this->_data['site'].' -'.$this->last_id.'-'))
                    {
                       $this->error = false;
                       $this->log .='</br>Problème Ajout Marker';
                    } 
                }
                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Modification réussie: <b>' . $this->_data['station_base'] . ' ID: ' . $this->id_uhf_vhf_clients;
                    //Check $this->error = false return Red message and Bol false	
                } else {
                    $this->log .= '</br>Modification réussie: <b>' . $this->_data['station_base'];
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

    public function edit_uhf_vhf_clt_mobile() {
        //Get data for selected station
        $this->get_uhf_vhf_clients();
        $this->last_id = $this->id_uhf_vhf_clients;

        global $db;
        $values["marque"] = MySQL::SQLValue($this->_data['marque']);
        $values["modele"] = MySQL::SQLValue($this->_data['modele']);
        $values["num_serie"] = MySQL::SQLValue($this->_data['num_serie']);
        $values["matricule_vehicule"] = MySQL::SQLValue($this->_data['matricule_vehicule']);
        $values["active"] = MySQL::SQLValue($this->_data['active']);
        $values["updusr"] = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
        $wheres["id"] = MySQL::SQLValue($this->id_uhf_vhf_clients);
        //Check $this->error (true / false)
        if ($this->error == true) {
            //Check if Update Query been executed (False / True)
            if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Modification BD non réussie';
            } else {
                //Esspionage
                if (!$db->After_update($this->table, $this->id_uhf_vhf_clients, $values, $this->uhf_vhf_clients_info)) {
                    $this->log .= '</br>Problème Esspionage';
                    $this->error = false;
                }
                //Add Gallerie Images
                //Set Folder
                $folder = MPATH_UPLOAD . 'uhf_vhf_clients' . SLASH . $this->id_uhf_vhf_clients;
                //Call save_multiple files
                //save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
                if (!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->id_uhf_vhf_clients, $this->_data['photo_titl'], 'uhf_vhf_clients', $this->table, 'pj_images', $this->uhf_vhf_clients_info['pj_images'])) {
                    $this->error = false;
                    $this->log .= '</br>Problème archivage Images';
                }
                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Modification réussie: <b>' . $this->_data['station_base'] . ' ID: ' . $this->id_uhf_vhf_clients;
                    //Check $this->error = false return Red message and Bol false	
                } else {
                    $this->log .= '</br>Modification réussie: <b>' . $this->_data['station_base'];
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

    public function edit_uhf_vhf_clt_handset() {
        //Get data for selected station
        $this->get_uhf_vhf_clients();
        $this->last_id = $this->id_uhf_vhf_clients;

        global $db;
        $values["marque"] = MySQL::SQLValue($this->_data['marque']);
        $values["modele"] = MySQL::SQLValue($this->_data['modele']);
        $values["num_serie"] = MySQL::SQLValue($this->_data['num_serie']);
        $values["active"] = MySQL::SQLValue($this->_data['active']);
        $values["updusr"] = MySQL::SQLValue(session::get('userid'));
        $values["upddat"] = MySQL::SQLValue(date("Y-m-d H:i:s"));
        $wheres["id"] = MySQL::SQLValue($this->id_uhf_vhf_clients);
        //Check $this->error (true / false)
        if ($this->error == true) {
            //Check if Update Query been executed (False / True)
            if (!$result = $db->UpdateRows($this->table, $values, $wheres)) {
                //False => Set $this->log and $this->error = false
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .= '</br>Modification BD non réussie';
            } else {
                //Esspionage
                if (!$db->After_update($this->table, $this->id_uhf_vhf_clients, $values, $this->uhf_vhf_clients_info)) {
                    $this->log .= '</br>Problème Esspionage';
                    $this->error = false;
                }
                //Add Gallerie Images
                //Set Folder
                $folder = MPATH_UPLOAD . 'uhf_vhf_clients' . SLASH . $this->id_uhf_vhf_clients;
                //Call save_multiple files
                //save_multipl_file_upload($arr_link, $folder, $id_line, $arr_titl, $modul, $table, $column, $edit = null)
                if (!MInit::save_multipl_file_upload($this->_data['photo_id'], $folder, $this->id_uhf_vhf_clients, $this->_data['photo_titl'], 'uhf_vhf_clients', $this->table, 'pj_images', $this->uhf_vhf_clients_info['pj_images'])) {
                    $this->error = false;
                    $this->log .= '</br>Problème archivage Images';
                }
                //Insert Marker MAP
                if(!$this->add_marker_vhf_clt($this->last_id, $this->_data['longi'], $this->_data['latit'], 'Station VHF CLient '.$this->_data['site'].' -'.$this->last_id.'-'))
                {
                    $this->error = false;
                    $this->log .='</br>Problème Ajout Marker';

                }
                //Check $this->error = true return Green message and Bol true
                if ($this->error == true) {
                    $this->log = '</br>Modification réussie: <b>' . $this->_data['station_base'] . ' ID: ' . $this->id_uhf_vhf_clients;
                    //Check $this->error = false return Red message and Bol false	
                } else {
                    $this->log .= '</br>Modification réussie: <b>' . $this->_data['station_base'];
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

    public function delete_uhf_vhf_clients() {
        global $db;
        $id_uhf_vhf_clients = $this->id_uhf_vhf_clients;
        $where['id'] = MySQL::SQLValue($id_uhf_vhf_clients);
        if (!$db->DeleteRows('uhf_vhf_clients', $where)) {
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

    public function valid_uhf_vhf_clients() {
        global $db;
        $values['etat'] = ' ETAT + 1 ';
        $wheres['id'] = MySQL::SQLValue($this->id_uhf_vhf_clients);

        if (!$result = $db->UpdateRows('uhf_vhf_clients', $values, $wheres)) {
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
        if ($this->uhf_vhf_clients_info[$attibute] != null) {
            echo $this->uhf_vhf_clients_info[$attibute];
        } else {
            echo "";
        }
    }
    
         /**
     * [add_marker_gsm Add Marker to map]
     * @param [type] $id_vhf_clt [description]
     * @param [type] $ville  [description]
     * @param [type] $log    [description]
     * @param [type] $lalt   [description]
     * @param [type] $text   [description]
     */
    public function add_marker_vhf_clt($id_vhf_clt, $log, $lalt, $text)
    {

        if($log == null OR $lalt == null){
            return true;
        }
        global $db;
        $station_base = new Mvhf_stations();
        $station_base->id_vhf_stations = $this->_data['station_base'];
        $station_base->get_vhf_stations();

        $marker_line_lat  = $station_base->vhf_stations_info['latit'];
        $marker_line_long = $station_base->vhf_stations_info['longi'];
        $ville            = $station_base->vhf_stations_info['ville'];


        //Delete if an marker have same value
        if(!$db->Query("DELETE FROM map_markers WHERE marker_categorie = 'vhf_clt' AND marker_line_id = $id_vhf_clt"))
        {
            $this->error = false;
            $this->log .='</br>Unable to delete existing Marker'; 
        }

        $values["marker_categorie"] = MySQL::SQLValue('vhf_clt');
        $values["marker_line_id"]   = MySQL::SQLValue($id_vhf_clt);
        $values["marker_ville"]     = MySQL::SQLValue($ville);
        $values["marker_longitude"] = MySQL::SQLValue($log);
        $values["marker_latitude"]  = MySQL::SQLValue($lalt);
        $values["marker_text"]      = MySQL::SQLValue($text);
        $values["marker_network"]   = MySQL::SQLValue(1);
        $values["marker_line_lat"]  = MySQL::SQLValue($marker_line_lat);
        $values["marker_line_long"] = MySQL::SQLValue($marker_line_long);
        $values["marker_actif"]     = MySQL::SQLValue(1);
        
        
        
        // If we have an error
        if($this->error == true){

            if (!$result = $db->InsertRow("map_markers", $values)) {
                //$db->Kill();
                $this->log .= $db->Error();
                $this->error = false;
                $this->log .='</br>Enregistrement Marker BD non réussie'; 

            }else{

                if(!$db->UpdateSinglRows($this->table, 'marker_map', $result, $id_vhf_clt))
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
    public function get_uhf_vhf_client_info() {
        global $db;
        $table = $this->table;
        //Format Select commande
        $sql = "SELECT $table.*,uhf_vhf_stations.site,permissionnaires.r_social
                FROM $table,permissionnaires,uhf_vhf_stations"
                . " WHERE  uhf_vhf_stations.prm  = permissionnaires.id "
                . "AND uhf_vhf_stations.id  = uhf_vhf_clients.station_base "
                . "AND $table.id = " . $this->id_uhf_vhf_clients;
        
        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if ($db->RowCount() == 0) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->uhf_vhf_clients_info = $db->RowArray();
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

}

?>
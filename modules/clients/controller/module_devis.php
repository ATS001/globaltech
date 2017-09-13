<?php

//Delete a row in D_Devis depending on the line ID
    //Model
    public function delete_by_id($id)
    {
        global $db;
        
        //Format where clause
        $where['id'] = MySQL::SQLValue($id);
        //check if id on where clause isset
        if($where['id'] == null)
        {
            $this->error = false;
            $this->log .='</br>L\' id est vide';
        }
        //execute Delete Query
        if(!$db->DeleteRows('d_devis',$where))
        {

            $this->log .= $db->Error().'  '.$db->BuildSQLDelete('d_devis',$where);
            $this->error = false;
            $this->log .='</br>Suppression non réussie';

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

     //Controleur

    //Get devis info
    $devis = new Mdevis();

    if($devis->delete_by_id($id))
    {
        exit("1#".$devis->log);

    }else{
        exit("0#".$devis->log);
    }


//Il faut ajouter le champ taxe dans la table Client 
//--------------------------------------------------

//Get Customer informations depending on customer id


  public static function get_client_infos($id_client)
  {
    global $db;

    $sql = "SELECT code,denomination,Concat(nom,' ',prenom) as nom, r_social, r_commerce, nif, adresse, tel, fax, bp, email, taxe from clients where id="$id_client;
    if(!$db->Query($sql))
    {
      $this->error = false;
      $this->log  .= $db->Error();
    }else{
      if (!$db->RowCount()) {
        $this->error = false;
        $this->log .= 'Aucun enregistrement trouvé ';
      } else {
        $this->devis = $db->RecordsSimplArray();
                
        $this->error = true;
      }


    }
        //return Array client_infos
    if($this->error == false)
    {
      return false;
    }else{
      return true;
    }

  }


  /**
     * [s description]
     * @param [type] $key     [Key of info_array]
     * @param string $no_echo [Used for echo the value on View ]
     */
    public function s($key)
    {
      if($this->devis_info[$key] != null)
      {
        echo $this->devis_info[$key];
      }else{
        echo "";
      }
      
    }


?>


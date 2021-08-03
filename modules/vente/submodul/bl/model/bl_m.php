<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: bl
//Created : 02-05-2018
//Model
/**
* M%modul% 
* Version 1.0
* 
*/

class Mbl {
  
  private $_data;     //data receive from form
  var $table   = 'bl';//Main table of module
  var $table_details = 'd_bl';//Table détail 
  var $last_id = null;//return last ID after insert command
  var $log     = null;//Log of all opération.
  var $error   = true;//Error bol changed when an error is occured
  var $id_bl   = null;// bl ID append when request
  var $token   = null;//user for recovery function
  var $bl_info = array();     //Array stock all bl info
  var $d_bl_info = array();     //Array stock all bl details info
  

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
    //Get all info user fro database for edit form

  public function get_bl()
  {
    global $db;

    $table = $this->table;

    $sql = "SELECT $table.*, devis.reference as refdevis , DATE_FORMAT($table.date_bl,'%d-%m-%Y') as date_bl FROM 
    $table , devis WHERE  devis.id = $table.iddevis AND $table.id = ".$this->id_bl;

    if(!$db->Query($sql))
    {
      $this->error = false;
      $this->log  .= $db->Error();
    }else{
      if ($db->RowCount() == 0) {
        $this->error = false;
        $this->log .= 'Aucun enregistrement trouvé ';
      } else {
        $this->bl_info = $db->RowArray();
        $this->error = true;
      }
      
      
    }
    //return Array user_info
    if($this->error == false)
    {
      return false;
    }else{
      return true ;
    }
    
  }
    //Get détails BL
      public function get_details_bl() {
        global $db;


         $sql = "SELECT * FROM d_bl WHERE  id_bl = " . $this->id_bl;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if (!$db->RowCount()) {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->d_bl_info = $db->RecordsSimplArray();
                
                $this->error = true;
            }
        }
        //return Array user_activities
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    //Vérif Stock
    public function verif_qte_stock() {
        global $db;


         $sql = "SELECT * FROM qte_actuel q WHERE q.`type`=1  and q.`id_produit` IN 
                (SELECT id_produit FROM d_bl d WHERE d.`id_produit`=q.`id_produit` AND d.`qte` > q.`qte_act`AND d.`id_bl`= $this->id_bl ) ";

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= 'Check Qte Stock '.$db->Error();
        } else {
            if (!$db->RowCount()) {
                $this->error = true;
            } else {
                
                $this->error = false;
                $this->log = 'Quantité à livrer non disponible !!! Veuillez approvisionner le stock ou modifier le BL.';
            }
        }
        //return Array user_activities
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }
    public function verif_qte_stock_ligne($id_produit,$qte_liv) {
        global $db;


         $sql = "SELECT * FROM qte_actuel q WHERE q.`type`=1  and q.`id_produit`=$id_produit 
                and  q.`id_produit` IN 
                (SELECT id_produit FROM d_bl d WHERE d.`id_produit`=q.`id_produit` AND $qte_liv > q.`qte_act`AND d.`id_bl`= $this->id_bl ) ";

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= $db->Error();
        } else {
            if (!$db->RowCount()) {
                $this->error = true;
            } else {
                
                $this->error = false;
                $this->log = 'Quantité à livrer '. $qte_liv.' non disponible !!! Veuillez approvisionner le stock du produit '.$id_produit.' ou modifier le BL.';
            }
        }
        //return Array user_activities
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }
    

    
    //Mouvementer Stock
    public function mouvementer_stock() {
        global $db;


         $sql = "INSERT INTO stock (idproduit, qte, mouvement, id_bl, etat )
                SELECT id_produit, - qte, 'S', id_bl, 1 FROM d_bl WHERE id_bl=". $this->id_bl;

        if (!$db->Query($sql)) {
            $this->error = false;
            $this->log .= 'Mouvement Stock : '.$db->Error();
        } else {
            $this->error = true;
        }

        if($this->refresh_products())
        {
          $this->error = true;
        }else{
          $this->error = false;
          $this->log .= $db->Error();
        }

        //return Array user_activities
        if ($this->error == false) {
            return false;
        } else {
            return true;
        }
    }

    private function check_livraison()
    {
        global $db;
        
        $this->get_bl();
        $id_devis= $this->bl_info['iddevis'];

        //var_dump($this->bl_info['iddevis']);
        
        $req_check_livr = "SELECT (SELECT SUM(qte) FROM d_devis WHERE id_devis = $id_devis ) -
        (SELECT SUM(b.qte) FROM  d_bl b,bl WHERE  bl.iddevis = $id_devis  AND b.id_bl = bl.id  ) AS reste;";
        $result = $db->QuerySingleValue0($req_check_livr);
        
        if($result == 0){
            $etat_devis = 'valid_client';

        }else{
            $etat_devis = 'devis_livr';
        }
        $new_etat = Msetting::get_set('etat_devis', $etat_devis);
        $req_sql = " UPDATE devis SET etat = $new_etat  WHERE id = $id_devis ";
       
        if(!$db->Query($req_sql))
        {
            $this->log .= '</br>Erreur MAJ devis après BL'.$req_sql;
            $this->error = false;
            return false;

        }
        return true;
    } 

    private function refresh_products()
    {
        global $db;
        $sql_req = " CALL refresh_products()";

        if(!$db->Query($sql_req))
        {
            $this->log .= '</br>Erreur actualisation de produits'.$sql_req;
            $this->error = false;
        }else{
            $this->error = true;
        }

        if($this->error == false){
          return false;
        }else{
          return true;
        }

    }

    private function generate_facture($id_bl)
    {
        global $db;
        $sql_req = " CALL generate_bl_fact($id_bl)";

        if(!$db->Query($sql_req))
        {
            $this->log .= '</br>Erreur génération de facture'.$sql_req;
            $this->error = false;
        }else{
            $this->error = true;
        }

        if($this->error == false){
          return false;
        }else{
          return true;
        }

    }

  /**
   * Save new row to main table
   * @return [bol] [bol value send to controller]
   */
  public function save_new_bl(){
        //$this->check_exist($column, $value, $message, $edit = null);
        //$this->check_non_exist($table, $column, $value, $message)
    


        // If we have an error
    if($this->error == true){
      global $db;
        //Add all fields for the table
          $values["reference"]       = MySQL::SQLValue($this->_data["reference"]);
          $values["client"]       = MySQL::SQLValue($this->_data["client"]);
          $values["projet"]       = MySQL::SQLValue($this->_data["projet"]);
          $values["ref_bc"]       = MySQL::SQLValue($this->_data["ref_bc"]);
          $values["iddevis"]       = MySQL::SQLValue($this->_data["iddevis"]);
          $values["date_bl"]       = MySQL::SQLValue($this->_data["date_bl"]);


          $values["creusr"]       = MySQL::SQLValue(session::get('userid'));

          if (!$result = $db->InsertRow($this->table, $values)) {

            $this->log .= $db->Error();
            $this->error = false;
            $this->log .='</br>Enregistrement BD non réussie'; 

        }else{

            $this->last_id = $result;
            $this->log .='</br>Enregistrement  réussie '. $this->_data['bl'] .' - '.$this->last_id.' -';
            if(!Mlog::log_exec($this->table, $this->last_id, 'Création bl', 'Insert'))
                {
                  $this->log .= '</br>Un problème de log ';
              }
          }


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
   * Edit selected Row
   * @return Bol [send to controller]
   */
  public function edit_bl(){
    
    $this->get_bl();

     global $db;
        $data_d_bl   = $this->_data['line_d_d'];
        //var_dump('tableau');
        //var_dump($data_d_bl);
        $id_bl       = $this->id_bl;
        $updusr      = session::get('userid');
        $count_lines = count($data_d_bl);
        //var_dump('cpt');
        //var_dump($count_lines);
        
        for ($i = 0 , $c  = $count_lines  ; $i < $c ; $i++  ) 
        {
             
            $id_line = $data_d_bl[$i];
            //var_dump($id_line);
            $qte_liv = MReq::tp('qte_liv_'.$id_line);
            //var_dump($qte_liv);
            $id_produit = MReq::tp('id_produit_'.$id_line);
            $verif= $this-> verif_qte_stock_ligne($id_produit,$qte_liv);

          //Delete Null lignes
          if($qte_liv == 0){

            $sql_req_d_bl = "  delete from d_bl where id_bl = $id_bl and id = $id_line ";
           
             if(!$db->Query($sql_req_d_bl))
             {
                $this->log .= '</br>Erreur Suppression
                 ligne '.$id_line.' Produit:'.$id_produit. '  '.$sql_req_d_bl;
                $this->error = false;
             }else{
                
                $this->error = true;
             }
          }

            //var_dump($verif);
          if( $verif == true){

            $sql_req_d_bl = "  update d_bl set qte = $qte_liv , updusr = $updusr , upddat = CURRENT_TIMESTAMP  where id_bl = $id_bl and id = $id_line ";
           
             if(!$db->Query($sql_req_d_bl))
             {
                $this->log .= '</br>Erreur Mise à jour ligne '.$id_line.' Produit:'.$id_produit. '  '.$sql_req_d_bl;
                $this->error = false;
             }else{
                
                $this->error = true;
             }
          }else{
            //var_dump("errr");
                $this->log = 'Quantité à livrer '. $qte_liv.' non disponible !!! Veuillez approvisionner le stock du produit '.$id_produit.' ou modifier le BL.';
                $this->error = false; 
                return false;
                exit;            
          }

        }  

      if($this->check_livraison())
        {
          $this->error = true;
        }else{
          $this->error = false;
          $this->log .= $db->Error();
      }

      if($this->error == false){
      //var_dump('dkhel');
      //$this->log .='</br>KO ';
         return false;
         exit;
      }else{
         $this->log .='</br>Modification réussie ';
         return true;
      }
 }  

public function Gettable_d_bl()
    {
        global $db;
        $table    = $this->table_details;
        $input_qte_l = "CONCAT('<input type=\"hidden\" name=\"line_d_d[]\" value=\"',$table.id,'\"/><input type=\"hidden\" name=\"id_produit_',$table.id,'\" value=\"',$table.id_produit,'\"/><input id=\"type_',$table.id_produit,'\" type=\"hidden\" name=\"type_',$table.id,'\" value=\"',qte_actuel.type,'\"/><input id=\"qte_',$table.id_produit,'\" type=\"hidden\" name=\"qte_bl_',$table.id,'\" value=\"',$table.qte,'\"/><input id=\"qte_devis_',$table.id_produit,'\" type=\"hidden\" name=\"qte_devis_',$table.id,'\" value=\"',d.qte - 
(SELECT IFNULL(SUM(d_bl1.qte),0) FROM bl bl1, d_bl d_bl1 WHERE bl1.iddevis=d.id_devis AND bl1.id=d_bl1.`id_bl` AND d_bl1.id_produit=d.id_produit AND bl1.id <> bl.id ),'\"/><input id=\"liv_',$table.id_produit,'\" class=\"qte center  is-number\" name=\"qte_liv_',$table.id,'\" type=\"text\" value=\"',$table.qte,'\"/>') as qte_l";


        $etat_stock = "CASE WHEN $table.qte > qte_actuel.`qte_act` and qte_actuel.type =1 THEN 
  CONCAT('<span id=\"stok_',$table.id_produit,'\"class=\"badge badge-danger\">', qte_actuel.`qte_act`,'</span>')
  WHEN $table.qte < qte_actuel.`qte_act` and qte_actuel.type =1 THEN 
  CONCAT('<span id=\"stok_',$table.id_produit,'\"class=\"badge badge-success\">', qte_actuel.`qte_act`,'</span>')
   ELSE  CONCAT('<span id=\"stok_',$table.id_produit,'\" class=\"badge badge-warning\">', qte_actuel.`qte_act`,'</span>') END AS stock";
        $id_bl = $this->id_bl;
        
        $colms = null;
        $colms .= " $table.order item, ";
        $colms .= " $table.ref_produit, ";
        $colms .= " $table.designation, ";
        $colms .= " $etat_stock, ";
        $colms .= " $input_qte_l";
        
        
        $req_sql  = " SELECT $colms FROM $table, qte_actuel, d_devis d, bl WHERE $table.id_produit = qte_actuel.id_produit AND d.id_produit=$table.id_produit and bl.iddevis=d.id_devis and bl.id=$table.id_bl and id_bl = $id_bl ";
        
        if(!$db->Query($req_sql))
        {
            $this->error = false;
            $this->log  .= $db->Error().' '.$req_sql;
            exit($this->log);
        }
        
        
        $headers = array(
            'Item'                  => '5[#]center',
            'Référence'             => '10[#]center',
            'Déscription'           => '30[#]', 
            'Quantité Stock'        => '15[#]center', 
            'Quantité à livrer'     => '15[#]center', 
            
            
        );
                 
                
        $tableau = $db->GetMTable($headers);
        
        
        return $tableau; 
    }



    /**
     * Valide bl
     * @return bol send to controller
     */
    public function valid_bl($etat)
    {
      //Get existing data for row
      $this->get_bl();

      $this->last_id = $this->id_bl;
      global $db;

    //Format etat (if 0 ==> 1 activation else 1 ==> 0 Désactivation)
      $etat = $etat == 0 ? 1 : 0;

      $values["etat"]        = MySQL::SQLValue($etat);
      $values["updusr"]      = MySQL::SQLValue(session::get('userid'));
      $values["upddat"]      = MySQL::SQLValue(date("Y-m-d H:i:s"));

      $wheres['id']     = $this->id_bl;

     if($this->generate_facture($this->id_bl))
     {

    // Execute the update and show error case error
      if(!$result = $db->UpdateRows($this->table, $values, $wheres))
      {

          $this->log   .= '</br>Impossible de changer le statut!';
          $this->log   .= '</br>'.$db->Error();
          $this->error  = false;

      }else{

          $this->log   .= '</br>Statut changé! ';
          $this->error  = true;
          if(!Mlog::log_exec($this->table, $this->last_id, 'Changement ETAT  bl', 'Update'))
              {
                 $this->log .= '</br>Un problème de log ';
                 $this->error = false;

             }
               //Esspionage
             if(!$db->After_update($this->table, $this->id_bl, $values, $this->bl_info)){
                 $this->log .= '</br>Problème Espionnage';
                 $this->error = false;  

             }

      }

     }
     else{

          $this->log   .= '</br>Impossible de générer la facture';
          $this->log   .= '</br>'.$db->Error();
          $this->error  = false;
     }
      
      if($this->error == false){
          return false;
      }else{
          return true;
      }


  }

  /**
   *  [check_non_exist Check if one entrie not exist on referential table]
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
      $sql_edit = $edit == null ? null: " AND  <> $edit";
      $result = $db->QuerySingleValue0("SELECT $table.$column FROM $table 
        WHERE $table.$column = ". MySQL::SQLValue($value) ." $sql_edit ");

      if ($result != "0") {
        $this->error = false;
        $this->log .='</br>'.$message.' existe déjà';
      }
    }



    /**
     * Delet selectd Row
     * @return bol [Send to controller]
     */
    public function delete_bl()
    {
      global $db;
      $id_bl = $this->id_bl;
      $this->get_bl();
      //Format where clause
      $where['id'] = MySQL::SQLValue($id_bl);
      //check if id on where clause isset
      if($where['id'] == null)
      {
        $this->error = false;
        $this->log .='</br>L\' id est vide';
      }
      //execute Delete Query
      if(!$db->DeleteRows($this->table, $where))
      {
        
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

    /**
     * [s Print value of entry]
     * @param  [key array] $key [description]
     * @return [print string]      [description]
     */
    public function s($key)
    {
        if($this->bl_info[$key] != null)
        {
            echo $this->bl_info[$key];
        }else{
            echo "";
        }

    }
    /**
     * [g Get value of entry used into script]
     * @param  [key array] $key [description]
     * @return [string]      [description]
     */
    public function g($key)
    {
        if($this->bl_info[$key] != null)
        {
            return $this->bl_info[$key];
        }else{
            return null;
        }

    }
    public function Get_detail_bl_show()
    {
        global $db;
        $req_sql = "SELECT
        bl.*
        , DATE_FORMAT(bl.date_bl,'%d-%m-%Y') as date_bl
        , clients.reference as reference_client
        , clients.denomination
        , clients.adresse
        , CONCAT('BP', clients.bp) as bp
        , clients.tel
        , clients.nif
        , clients.email
        , ref_pays.pays
        , ref_ville.ville
        , ref_devise.abreviation as devise
        , services.service as comercial
        , CONCAT(commerciaux.nom,' ',commerciaux.prenom) as commercial
        , devis.id_banque as banque
        FROM
        bl
        LEFT JOIN devis 
        ON (bl.iddevis = devis.id)
        INNER JOIN clients 
        ON (devis.id_client = clients.id)
        LEFT JOIN ref_pays 
        ON (clients.id_pays = ref_pays.id)
        LEFT JOIN ref_ville
        ON (clients.id_ville = ref_ville.id)
        INNER JOIN ref_devise
        ON (clients.id_devise = ref_devise.id)
        INNER JOIN users_sys
        ON (bl.creusr = users_sys.id)
        INNER JOIN services
        ON (users_sys.service = services.id)
        INNER JOIN commerciaux
        ON (commerciaux.id IN (REPLACE((REPLACE(devis.id_commercial,'[\"','')),'\"]','')) )
        WHERE bl.id = ".$this->id_bl;
        if(!$db->Query($req_sql))
        {
            $this->error = false;
            $this->log  .= $db->Error();
        }else{
            if ($db->RowCount() == 0)
            {
                $this->error = false;
                $this->log .= 'Aucun enregistrement trouvé ';
            } else {
                $this->bl_info = $db->RowArray();
                $this->error = true;
            }


        }
        if($this->error == false)
        {
            return false;
        }else{
            return true ;
        }

    }
     
    /**
     * [Gettable_detail_devis description]
     * return HTML Table
     */
    public function Gettable_detail_bl()
    {
        global $db;
        $id_bl = $this->id_bl;
        $table = $this->table_details;
        $colms = null;
        $colms .= " $table.order item, ";
        $colms .= " $table.ref_produit, ";
        $colms .= " $table.designation, ";
        $colms .= " REPLACE(FORMAT($table.qte,0),',',' ')";

        
        
        $req_sql  = " SELECT $colms FROM $table WHERE id_bl = $id_bl order by item";
        if(!$db->Query($req_sql))
        {
            $this->error = false;
            $this->log  .= $db->Error().' '.$req_sql;
            exit($this->log);
        }
        
        
        $headers = array(
            'Item'          => '3[#]center',
            'Référence'     => '17[#]center',
            'Description'   => '32[#]', 
            'Quantité'  => '16[#]center',
            
        );
                 
                
        $tableau = $db->GetMTable($headers);
        
        
        return $tableau; 
    }
    /**
     * [Get_detail_devis_pdf Render query for export PDF]
     * @return bol send to controller
     */
    public function Get_detail_bl_pdf()
    {
        global $db;

        $id_bl = $this->id_bl;
        $table    = $this->table_details;
        $this->Get_detail_bl_show();
        $bl_info = $this->bl_info;
        $colms  = null;
        //$colms .= " $table.order item, ";
        //$colms .= " $table.ref_produit, ";
        $colms .= " $table.designation, ";
        $colms .= " REPLACE(FORMAT($table.qte,0),',',' ') ";
        
        
        $req_sql  = " SELECT $colms FROM $table WHERE id_bl = $id_bl order by $table.order";
        if(!$db->Query($req_sql))
        {
            $this->error = false;
            $this->log  .= $db->Error().' '.$req_sql;
            exit($this->log);
        }
        
        
        if($this->error == false)
        {
            return false;
        }else{
            return true ;
        }
        
        
    }

    //Archivage BL
    public function archivebl()
    {
        global $db;
        $id_bl = $this->id_bl;
        $table = $this->table;
        $this->get_bl();
        //Format where clause
        $id_bl = MySQL::SQLValue($id_bl);
        $sql_req = "UPDATE $table SET etat = 100 WHERE id = $id_bl";
       
        //check if id on where clause isset
        if($id_bl == null)
        {
            $this->error = false;
            $this->log .='</br>L\' id est vide';
            return false;
        }
        //execute Delete Query
        if(!$db->Query($sql_req))
        {

            $this->log .= $db->Error();
            $this->error = false;
            $this->log .='</br>Archivage non réussie';

        }else{

            $this->error = true;
            $this->log .='</br>Archivage réussie ';
            //log
            if(!Mlog::log_exec($table, $this->id_bl, 'Archivage abonnement '.$this->id_bl, 'Update'))
            {
                $this->log .= '</br>Un problème de log ';
                        
            }
        }
        //check if last error is true then return true else rturn false.
        if($this->error == false){
            return false;
        }else{
            return true;
        }
    }



}
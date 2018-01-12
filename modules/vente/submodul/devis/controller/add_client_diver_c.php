<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: devis
//Created : 10-01-2018
//Controller ADD Form
if(MInit::form_verif('add_client', false))
  {


          $posted_data = array(

               'denomination' => Mreq::tp('denomination') ,
               'adresse'      => Mreq::tp('adresse') ,
               'tel'          => Mreq::tp('tel') ,
               'email'        => Mreq::tp('email') ,
               
         );


        //Check if array have empty element return list
        //for acceptable empty field do not put here
          $checker = null;
          $empty_list = "Les champs suivants sont obligatoires:\n<ul>";

          if($posted_data["denomination"] == NULL)
          {
            $empty_list .= "<li>Insérer le nom de client</li>";
            $checker = 1;
          }
      


      $empty_list.= "</ul>";
      if($checker == 1)
      {
         exit("0#$empty_list");
      }



       //End check empty element
      $new_client_temp = new  Mdevis($posted_data);



        //execute Insert returne false if error
      if($new_client_temp->save_new_client_temp()){
         
            $result = json_encode($new_client_temp->info_temp_client);
            echo $result;
            exit();

      }else{

         echo json_encode(array('error' => false, 'mess' => 'Erreur Enregistrement Client Temporaire'.$new_client_temp->log ));
         exit();
      }


}

//No form posted show view
view::load_view('add_client_diver');







?>
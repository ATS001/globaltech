<div class="row">
  <div class="col-xs-12">        <!-- Small boxes (Stat box) -->
    <div class="row">
      <?php 
      
      if(session::get('service') == 3 OR session::get('service') == 1 OR session::get('service') == 7 OR session::get('service') == 2){
        Mobjectif_mensuel::indicator_objectif_mensuel();
        Mobjectif_service::indicator_objectif_service();
        Mdevis::indicator_nbr_devis(); 
        Mdevis::indicator_nbr_facture_non_paye();
      }
      ?> 
    </div>
  </div>
</div>
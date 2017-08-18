
<script type="text/javascript">
  
 
//Notification Aemploi	
<?php $sqlaemploinotif="Select count(userid) from permission_users where userid=$usrid  and (appid=44 or appid=61  or appid=73) ";
$aemploinotif = $db->QuerySingleValue0($sqlaemploinotif);
			
			if($aemploinotif>0){ ?>
$(document).ready(function() {	
	 var callnotification = function(){
	 var existvaluetsk = $('.notiftsk').text();
	 var existvaluetskt = $('.notiftskt').text();
	 var existvaluetskd = $('.notiftskd').text();
	 var existvaluetsko = $('.notiftsko').text();
	 
	 var existvaluemsg = $('.notifmsg').text();			
	 $.ajax({
                url: './?_tsk=notifier&ajax=1&tb=1&nae=1&tik=1',
                type: 'get',
                dataType:'json',
				
                success: function(data) {
                    if(data.n > existvaluetsk) {
						

                      //alert("Value for 'n': " + data.n + "\nValue for 'm': " + );
	                 $.sticky('<b>Vous avez '+data.n+'  '+data.m+'</b>');
	                 $('.notiftsk').text(data.n);
					
					 $('<audio id="sound"><source src="img/notify.mp3" type="audio/mpeg"><source src="img/notify.wav" type="audio/wav"></audio>').appendTo('body'); 
					 $('#sound')[0].play();
					}else{
					$('.notiftsk').text(data.n);
					
					}
					
					
					
					 if(data.nde > existvaluetskd) {
                      //alert("Value for 'n': " + data.n + "\nValue for 'm': " + );
	                 $.sticky('<b>Vous avez '+data.nde+'  '+data.mde+'</b>');
	                 $('.notiftskd').text(data.nde);
					
					 $('<audio id="sound"><source src="img/notify.mp3" type="audio/mpeg"><source src="img/notify.wav" type="audio/wav"></audio>').appendTo('body'); 
					 $('#sound')[0].play();
					}else{
					$('.notiftskd').text(data.nde);
					
					}
					
					
					
					 if(data.nof > existvaluetsko) {
	                 $.sticky('<b>Vous avez '+data.nof+'  '+data.mof+'</b>');
	                 $('.notiftsko').text(data.nof);
					
					 $('<audio id="sound"><source src="img/notify.mp3" type="audio/mpeg"><source src="img/notify.wav" type="audio/wav"></audio>').appendTo('body'); 
					 $('#sound')[0].play();
					}else{
					$('.notiftsko').text(data.nof);
					
					}
					$('.notiftskt').text(data.t);
                }
            });
	 }
	 setInterval(callnotification,2000);
 });	 
	  <?php } ?>
	                  
					  
	
 
</script>
<?php 





?>
<div class="navbar navbar-fixed-top ">

  <div class="navbar-inner top-nav">
    <div class="container-fluid">
      <div class="branding">
        <div class="logo"> <a href="./" title="Acceuil"><img src="img/logo.png"  alt="Logo"></a> </div>
      </div>
      
      <ul class="nav pull-right">
      

         
         </li>
      <?php 
	  
	// Nbr notification
global $db ;
 
$querynotif="SELECT count(users_sys.id) as nbrae ,users_sys.lnom as lnom ,users_sys.fnom as fnom,users_sys.id as iduser ,dat  from session , users_sys where  users_sys.nom = session.user  and expir is null order by session.id desc "; ?>
         <li class="dropdown"><a data-toggle="dropdown"  onclick="ajax_loader('./?_tsk=onligne');" class="dropdown-toggle" href="#"><i class="white-icons speech_bubble"></i>Messages<span class="alert-noty" ><?php echo $db->QuerySingleValue($querynotif)?></span><!-- <b class="caret"></b> --></a>
        </li>
  
<?php 
	// Nbr notification
if($aemploinotif>0){
$service = cryptage($_SESSION['service'],0);
$querynotif="SELECT count(aemploi.id) as nbrae FROM aemploi, rules WHERE aemploi.etat = rules.etat and rules.notif = 1 AND rules.service = $service and rules.app='aemploi' "; 
$nbrae =$db->QuerySingleValue($querynotif);

$querynotif="SELECT count(demploi.id) as nbrae FROM demploi, rules WHERE demploi.etat = rules.etat and rules.notif = 1 AND rules.service = $service and rules.app='demploi' "; 
$nbrde =$db->QuerySingleValue($querynotif);

$querynotif="SELECT count(offre.id) as nbrae FROM offre, rules WHERE offre.etat = rules.etat and rules.notif = 1 AND rules.service = $service and rules.app='offre' "; 
$nbro =$db->QuerySingleValue($querynotif);


}



?>



         <li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#">
         	<i class="white-icons speech_bubble"></i>Notifications<span class="alert-noty notiftskt">
         		<?php echo $nbrae+$nbrde+$nbro;?></span><b class="caret"></b></a>
          <ul class="dropdown-menu">
          	
          	
          	
          	
<?php if($nbrae>0){
	// Nbr notification

//$service = cryptage($_SESSION['service'],0);
//$querynotif="SELECT count(aemploi.id) as nbrae FROM aemploi, rules WHERE aemploi.etat = rules.etat and rules.notif = 1 AND rules.service = $service and rules.app='aemploi' "; ?>          
            <li><a href="#" onclick="ajax_loader('./?_tsk=aemploi');">Autorisation d'emploi<span class="alert-noty notiftsk"><?php echo $nbrae; ?></span></a></li>

<?php } ?>  
<?php if($nbrde>0){
	// Nbr notification

//$service = cryptage($_SESSION['service'],0);
//$querynotif="SELECT count(aemploi.id) as nbrae FROM aemploi, rules WHERE aemploi.etat = rules.etat and rules.notif = 1 AND rules.service = $service and rules.app='aemploi' "; ?>          
            <li><a href="#" onclick="ajax_loader('./?_tsk=demploi');">Demande  d'emploi<span class="alert-noty notiftskd"><?php echo $nbrde; ?></span></a></li>

<?php } ?>  
<?php if($nbro>0){
	// Nbr notification

//$service = cryptage($_SESSION['service'],0);
//$querynotif="SELECT count(aemploi.id) as nbrae FROM aemploi, rules WHERE aemploi.etat = rules.etat and rules.notif = 1 AND rules.service = $service and rules.app='aemploi' "; ?>          
            <li><a href="#" onclick="ajax_loader('./?_tsk=offre');">Offre d'emploi<span class="alert-noty notiftsko"><?php echo $nbro; ?></span></a></li>

<?php } ?>  


          </ul>
        </li>
        <li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo  $_SESSION['username']; ?><i class="white-icons admin_user"></i><b class="caret"></b></a>
          <ul class="dropdown-menu">
          
            <li><a href="#" onclick="ajax_loader('./?_tsk=editprofile');"><i class="icon-pencil"></i> Edit Profile</a></li>
           
            <li class="divider"></li>
            <li><a href="./?_tsk=logout"><i class="icon-off"></i><strong>Déconnexion</strong></a></li>
          </ul>
        </li>
        
      </ul>
      <div style="width:100%;" align="center"><div id="message" style="display:none;" ></div></div>
        
      </div>
    </div>
  </div>
</div>
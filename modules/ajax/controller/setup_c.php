<?php 
//Start Setup Service Immgration form
function setupIMM($imm){
	global $db;
	$error='';
	if($imm==1){$rul=98; $drul=177;}else{$rul=177; $drul=98; }
	if (!$db->Query("update rules set active=1 where id= $rul "))$error = $db->Kill($db->Error());
	if (!$db->Query("update rules set active=0 where id= $drul "))$error = $db->Kill($db->Error()); 
		 
		if($error=''){	   
        return "0".$error;  //good to register
		}else{
        return "1"; //already registered
		}
	
}

if(tg('imm')!=0){echo setupIMM(tg('imm'));}
//End Setup Service Immgration form


//Start Setup Url Serche Aemploi form
function geturlsrchaemploi(){
global $db;
$url = "./?_tsk=aemploi&srch=1&ajax=1";	
$aeid = tp('ref')!= NULL?"&aeid=".tp('ref'):NULL;

$rs = str_replace(' ', '', tp('ste'));
$getsteid = $db->QuerySingleValue0("select id from ste where REPLACE(rs, ' ','')  like '$rs'");
$steid= $getsteid!= "0" ?"&steid=".$getsteid :NULL;
if($rs != NULL && $getsteid == "0"){exit("1");}

$getnomid = $db->QuerySingleValue0("select DISTINCT id  from aemploi where CONCAT(aemploi.nom,' ',aemploi.prenom) LIKE '".tp('nom')."'");
$nomid= $getnomid!= "0"?"&nome=".$getnomid :NULL;
if(tp('nom') != NULL && $getnomid == "0"){exit("2");}

$ans = "&ans=".tp('ans');

$url.= $ans.$nomid.$aeid.$steid;
	
return $url;	
	
}

// Get url serche for OFFRE
function geturlsrchoffre(){
global $db;
$url = "./?_tsk=offre&srch=1&ajax=1";	

$ref = tp('ref')!= 0?"&ref=".tp('ref'):NULL;
$rs = str_replace(' ', '', tp('ste'));
$getsteid = $db->QuerySingleValue0("select id from ste where REPLACE(rs,' ','') like '$rs'");
$steid= $getsteid!= 0?"&steid=".$getsteid :NULL;
$rs = str_replace(' ', '', tp('metier'));
$getmetierid = $db->QuerySingleValue0("select id from metier where REPLACE(metier,' ','') like '".tp('metier')."'");
$metierid= $getmetierid!= 0?"&metierid=".$getmetierid :NULL;

$ans = "&ans=".tp('ans');

$url.= $ref.$ans.$metierid.$steid;
	
return $url;	
	
}

// Get url serche for CONTRAT
function geturlsrchcontrat(){
global $db;
$url = "./?_tsk=contrat&srch=1&ajax=1";	
$ref = tp('ref')!= 0?"&ref=".tp('ref'):NULL;
$rs = str_replace(' ', '', tp('ste'));
$getsteid = $db->QuerySingleValue0("select id from ste where REPLACE(rs,' ','') like '$rs'");
$steid= $getsteid!= 0?"&steid=".$getsteid :NULL;
$typ = tp('typ')!= "0"?"&typ=".tp('typ'):NULL;
$ans = "&ans=".tp('ans');

$url.= $ans.$typ.$steid.$ref;
	
return $url;	
	
}
// Get url serche for DAE
function geturlsrchdae(){
global $db;
$url = "./?_tsk=dae&srch=1&ajax=1";	
$ref = tp('ref')!= 0?"&ref=".tp('ref'):NULL;
$nom = str_replace(' ', '', tp('nom'));
$getnomid = $db->QuerySingleValue0("select id from demploi where REPLACE(CONCAT(demploi.nom,' ',demploi.prenom), ' ','')  like '$nom'");
$nomid= $getnomid!= 0?"&nome=".$getnomid :NULL;
$ans = "&ans=".tp('ans');

$url.= $ans.$nomid.$ref;
	
return $url;	
	
}

// END url serch DAE

function geturlsrchste(){
global $db;
$url = "./?_tsk=ste&srch=1&ajax=1";	
$ref = tp('ref')!= 0?"&ref=".tp('ref'):NULL;
$getsteid = $db->QuerySingleValue0("select id from ste where rs like '".tp('ste')."'");
$steid= $getsteid!= 0?"&steid=".$getsteid :NULL;
$typ = tp('typ')!= 0?"&typ=".tp('typ'):NULL;

$ans = "&ans=".tp('ans');

$url.= $ans.$typ.$steid.$ref;
	
return $url;	
	
}



//Start Setup Url Serche Demploi form
function geturlsrchdemploi(){
global $db;
$url = "./?_tsk=demploi&srch=1&ajax=1";	
$deid = tp('ref')!= 0?"&deid=".tp('ref'):NULL;

$nom = str_replace(' ', '', tp('nom'));
$getnomid = $db->QuerySingleValue0("select id from demploi where REPLACE(CONCAT(demploi.nom,' ',demploi.prenom), ' ','')  like '$nom'");
$nomid= $getnomid!= 0?"&nome=".$getnomid :NULL;

$netud = tp('netud')!= ""?"&netud=".tg('netud'):NULL;

$agence = tp('agence')!= ""?"&agence=".tp('agence'):NULL;


$ans = "&ans=".tp('ans');

$url.= $ans.$nomid.$netud.$deid.$agence;
	
return $url;	
	
}

//Start Setup Url Serche Note service form
function geturlsrchnote(){
global $db;
$url = "./?_tsk=note&srch=1&ajax=1";	
$id = tp('ref')!= 0?"&ref=".tp('ref'):NULL;

$objet = str_replace(' ', '', tp('objet'));
$getobjetid = $db->QuerySingleValue0("select id from noteservice where REPLACE(noteservice.objet, ' ','')  like '$objet'");
$objetid= $getobjetid!= 0?"&objet=".$getobjetid :NULL;
$ans = "&ans=".tp('ans');

$url.= $ans.$objetid.$id;
	
return $url;	
	
}
//Get Nom demandeur d'emploi
function getnomdemploi(){
global $db;	
$getnomdemploi = $db->QuerySingleValue("select DISTINCT(CONCAT(demploi.nom,' ',demploi.prenom,'#',demploi.adresse,'#',demploi.tel))as lenom from demploi where id =".tp('iddemploi'));
$nomdemploi = $getnomdemploi != ""?$getnomdemploi : "0";
return $nomdemploi;
}

//Get Projet DAE demandeur d'emploi
function getchekprojet(){
global $db;
$idd = 	tp('iddemploi');
$getchekprojet = $db->QuerySingleValue("select CONCAT('(',dae.titre,')') from dae where dae.etat < 10 AND dae.id in(select id_projet from dae_demander where carte = $idd)");
$chekprojet = $getchekprojet != ""?$getchekprojet : "0";
return $chekprojet;
}

// Usage
if(tg('srchaemploi')==1){echo geturlsrchaemploi();}
if(tg('srchste')==1){echo geturlsrchste();}
if(tg('srchoffre')==1){echo geturlsrchoffre();}
if(tg('srchcontrat')==1){echo geturlsrchcontrat();}
if(tg('srchdemploi')==1){echo geturlsrchdemploi();}
if(tg('srchdae')==1){echo geturlsrchdae();}
if(tg('srchnote')==1){echo geturlsrchnote();}
if(tg('ndemploi')==1){echo getnomdemploi();}
if(tg('chekprojet')==1){echo getchekprojet();}


?>

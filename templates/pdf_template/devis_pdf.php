<?php
//============================================================+
// File name   : devis_pdf.php
// Last Update : 08/10/2017
//
// Description : All info Devis
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+
//Get all info Devis from model
$devis = new Mdevis();
$devis->id_devis = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$devis->get_devis())
{
   // returne message error red to devis
   exit('0#<br>Les informations pour cette template sont erronées, contactez l\'administrateur');
}



//Execute Pdf render

if(!$devis->Get_detail_devis_pdf())
{
	exit("0#".$devis->log);

}
global $db;
if($devis->devis_info['type_devis']=='ABN'){

$headers = array(
            //'#'           => '5[#]C',
            //'Réf'         => '17[#]C',
            'DESCRIPTION DES SERVICES FACTURÉS' => '61[#]',
            'QTE'         						=> '7[#]C',
            'P. MENSUEL'  						=> '12[#]R',
            'P. TOTAL'    						=> '15[#]R',

        );
}
else{
$headers = array(
            //'#'           => '5[#]C',
            //'Réf'         => '17[#]C',
            'DESCRIPTION'						=> '61[#]', 
            'QTE'                               => '7[#]C', 
            'P. UNITAIRE'                        => '12[#]R',
            'P. TOTAL'                          => '15[#]R',

        );	
}
$devis_info   = $devis->devis_info;
$tableau_head = MySQL::make_table_head($headers);
$tableau_body = $db->GetMTable_pdf($headers);

//***************************************************
 $user = new Musers();
 $user->id_user = $devis_info['creusr'];
 $user->get_user();
 //$signature_foto = Minit::get_file_archive($user->user_info['signature']);
 $signature_foto ='';
 //**************************************************


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
     var $Table_head   = null;
     var $no_tabl_head = true;
     var $Table_body   = null;
     var $info_devis   = array();
     var $info_ste     = array();
     var $qr           = false;

	//Page header
	public function Header() {
		//writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) {

    if (date('Y-m-d', strtotime($this->info_devis['date_devis'])) < date('Y-m-d', strtotime('2020-04-16'))) {
      // Logo 1
      $image_file = MPATH_IMG.MCfg::get('logo');
      $this->writeHTMLCell(50, 25, '', '', '' , 0, 0, 0, true, 'C', true);
        $this->Image($image_file, 14, 10, 30, 23, 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);

  }else{
      // Logo 2
      $image_file = MPATH_IMG.MCfg::get('logo2');
      $this->writeHTMLCell(50, 25, '', '', '' , 0, 0, 0, true, 'C', true);
      $this->Image($image_file, 14, 11, 80, 20, 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);
  }

		//Get info ste from DB
		$ste_c = new MSte_info();

        if((date('Y-m-d', strtotime($this->info_devis['date_devis']))) < (date('Y-m-d', strtotime('16-04-2020')))){
		$ste = $ste_c->get_ste_info_report_head(1,$this->info_devis['date_devis'],'Devis');
	    }else{
		$ste = $ste_c->get_ste_info_report_head(2,$this->info_devis['date_devis'],'Devis');
	    }
		//$this->writeHTMLCell(0, 0, '', 30, $ste , '', 0, 0, true, 'L', true);
		$this->SetTextColor(0, 50, 127);
		// Set font
		$this->SetFont('gotham-book', 'B', 22);

		//Début FZ HANOUNOU le 01/08/2021 => Nouvelle template

		$ref_client = $this->info_devis['reference_client'] != null ? $this->info_devis['reference_client'] : null;
	    $tel = $this->info_devis['tel'] != null ? $this->info_devis['tel'] : null;
	    $email = $this->info_devis['email'] != null ? $this->info_devis['email'] : null;
	    $adresse = $this->info_devis['adresse'] != null ? $this->info_devis['adresse'] : null;
	    $bp = $this->info_devis['bp'] != null ? 'BP. '.$this->info_devis['bp'] : null;
	    $ville = $this->info_devis['ville'] != null ? $this->info_devis['ville'] : null;
	    $pays = $this->info_devis['pays'] != null ? $this->info_devis['pays'] : null;

		//Adresse Client
        $adresseClient = null;
        if($adresse.$bp.$ville.$pays != null){
			$adresseClient =   ' <tr style= "font-size:9; color:black;" >
		<td style="width: 30%; font-weight: bold;">Adresse</td>
		<td style="width: 5%; font-weight: bold;">:</td>
		<td style="width: 63%;">'.$adresse.' '.$bp.' '.$ville.' '.$pays.'</td>
		</tr>';

		}
                
        //Tél Client
        $telClient = null;
        if($tel != null){
			$telClient = '<tr style= "font-size:9; color:black;" >
		<td style="width: 30%; font-weight: bold;">Téléphone</td>
		<td style="width: 5%;  font-weight: bold;">:</td>
		<td style="width: 63%; ">'.$tel.'</td>
		</tr>
		';
		}
                
                
             //Mail Client
          $mailClient = null;
          if($email != null){
			$mailClient = '<tr style= "font-size:9; color:black;" >
		<td style="width: 30%;font-weight: bold;">Email</td>
		<td style="width: 5%; font-weight: bold;">:</td>
		<td style="width: 63%;">'.$email.'</td>
		</tr>
		';
		}
    //Tableau d'entête
	$entete_devis = '<br><br>
    <table style = "width:650px;">
    <tr>
	<td>
	<table cellpadding="2" border="0">
    <tr>
       <td style="width:90%; background-color:#173C5A; font-size:9; font-weight:bold; color:#fff;"><strong>DEVIS</strong></td>
	</tr>
	<tr>
        <td>'.$ste.'</td>
	</tr> 
	</table>
	</td>
	
	<td>
	
<table   cellpadding="2" border="0">
    <tr style="background-color:#00D7B9; font-size:9; font-weight:bold; color:#fff;">
       <td><strong>INFORMATIONS CLIENT</strong></td>
	 </tr>
	
		<tr style= "font-size:9; color:black;" >
		<td style="width: 30%; font-weight: bold;">Dénomination</td>
		<td style="width: 5%; font-weight: bold;">:</td>
		<td style="width: 63%;">'.$this->info_devis['denomination'].'</td>
		</tr>'
                
        . $adresseClient.''.$telClient.''.$mailClient.
                
	'</table>
	</td>
        </tr>
        
<tr>
<td>	
<table   cellpadding="2" border="0">
    <tr style=" font-size:9; font-weight:bold; color:black;">
       <td><strong>.....................................................................................................</strong></td>
	 </tr>
	
		<tr style= "font-size:9; color:black;" >
		<td style="width: 38%; font-weight: bold;color:#173C5A;">DEVIS N°</td>
		<td style="width: 5%; font-weight: bold;">:</td>
		<td style="width: 50%;color:#00D7B9;">'.$this->info_devis['reference'].'</td>
		</tr>
                
        <tr style= "font-size:9; color:black;" >
		<td style="width: 38%; font-weight: bold;color:#173C5A;">Date devis</td>
		<td style="width: 5%; font-weight: bold;">:</td>
		<td style="width: 50%;color:#00D7B9;">'.date('d/m/Y', strtotime($this->info_devis['date_devis'])).'</td>
		</tr>
                                
	</table>


	</td>
	
	<td>	
<table   cellpadding="2" border="0">
    <tr style="; font-size:9; font-weight:bold; color:#fff;">
       <td><strong>........................................................</strong></td>
	 </tr>
                
              <tr style= "font-size:9; color:black;" >
		<td style="width: 50%; font-weight: bold;">N° Compte client</td>
		<td style="width: 5%; font-weight: bold;">:</td>
		<td style="width: 55%;">'.$ref_client.'</td>
		</tr>
                                
	</table>


	</td>

</tr>
	
</table>
<br><br>';

    	$this->Ln();
    	$this->writeHTMLCell(0,0,10, 30, $entete_devis , '', 0, 0, true, 'L', true);
                
		
    	// ------------------------ Fin Tableau entête ----------------------

		//Fin FZ HANOUNOU le O1/08/2021

		//Ste

		// Title

		// $titre_doc = '
		// <h1 style="letter-spacing: 2px;color;#A1A0A0;font-size: 20pt;">D E V I S</h1>';
		// $this->writeHTMLCell(0, 0, 140, 10, $titre_doc , 'B', 0, 0, true, 'R', true, 2);
		// $this->SetTextColor(0, 0, 0);
		$this->SetFont('gotham-book', '', 9);
		// $detail_devis = '<table cellspacing="3" cellpadding="2" border="0">
		// <tr>
		// <td style="width:35%; color:#A1A0A0;"><strong>Réf Devis</strong></td>
		// <td style="width:5%;">:</td>
		// <td style="width:60%; background-color: #eeecec;">'.$this->info_devis['reference'].'</td>
		// </tr>
		// <tr>
		// <td style="width:35%; color:#A1A0A0;"><strong>Date</strong></td>
		// <td style="width:5%;">:</td>
		// <td style="width:60%; background-color: #eeecec; ">'.$this->info_devis['date_devis'].'</td>
		// </tr>
		// </table>';
		// $this->writeHTMLCell(0, 0, 140, 23, $detail_devis, '', 0, 0, true, 'L', true);
	 //    //Info Client
	 //    $nif = null;
	 //    if($this->info_devis['nif'] != null)
	 //    {
	 //    	$nif = '<tr>
		// <td align="right" style="width: 30%; color: #E99222;font-weight: bold;font-size: 9pt;">NIF</td>
		// <td style="width: 5%; color: #E99222;font-weight: bold;">:</td>
		// <td style="width: 65%; background-color: #eeecec;">'.$this->info_devis['nif'].'</td>
		// </tr>';
	 //    }
	 //    $ref_client = $this->info_devis['reference_client'] != null ? $this->info_devis['reference_client'] : null;
	 //    $tel = $this->info_devis['tel'] != null ? 'Tél.'.$this->info_devis['tel'] : null;
	 //    $email = $this->info_devis['email'] != null ? 'Email.'.$this->info_devis['email'] : null;
	 //    $adresse = $this->info_devis['adresse'] != null ? $this->info_devis['adresse'] : null;
	 //    $bp = $this->info_devis['bp'] != null ? 'BP. '.$this->info_devis['bp'] : null;
	 //    $ville = $this->info_devis['ville'] != null ? $this->info_devis['ville'] : null;
	 //    $pays = $this->info_devis['pays'] != null ? $this->info_devis['pays'] : null;
		// $detail_client = '<table cellspacing="3" cellpadding="2" border="0">
		// <tbody>
		// <tr style="background-color:#495375; font-size:11; font-weight:bold; color:#fff;">
		// <td colspan="3"><strong>Informations du client</strong></td>
		// </tr>
		// <tr>
		// <td align="right" style="width: 30%; color: #E99222;font-weight: bold;font-size: 9pt;">Réf Client</td>
		// <td style="width: 5%; color: #E99222;font-weight: bold;">:</td>
		// <td style="width: 65%; background-color: #eeecec;"><strong>'.$ref_client.'</strong></td>
		// </tr>
		// <tr>
		// <td align="right" style="width: 30%; color: #E99222;font-weight: bold;font-size: 9pt;">Dénomination</td>
		// <td style="width: 5%; color: #E99222;font-weight: bold;">:</td>
		// <td style="width: 65%; background-color: #eeecec;"><strong>'.$this->info_devis['denomination'].'</strong></td>
		// </tr>';

		// if($adresse.$bp.$ville.$pays != null){
		// 	$detail_client .= '<tr>
	 //    <td align="right" style="width: 30%;color: #E99222;font-weight: bold;font-size: 9pt;">Adresse</td>
		// <td style="width: 5%; color: #E99222;font-weight: bold;">:</td>
		// <td style="width: 65%; background-color: #eeecec;">'.$adresse.' '.$bp.' '.$ville.' '.$pays.'</td>
		// </tr>';

		// }



		// if($tel != null && $email != null){
		// 	$detail_client .= '<tr>
		// <td align="right" style="width: 30%; color: #E99222;font-weight: bold;font-size: 9pt;">Contact</td>
		// <td style="width: 5%; color: #E99222;font-weight: bold;">:</td>
		// <td style="width: 65%; background-color: #eeecec;">'.$tel.' '.$email.'</td>
		// </tr>
		// ';
		// }
		// $detail_client .= $nif.'
		// </tbody>
		// </table>';
		//$marge_after_detail_client =
		//$this->writeHTMLCell(100, 0, 99, 40, $detail_client, 0, 0, 0, true, 'L', true);
		// if($this->info_devis['projet'] != null){
		// 	$projet = '<span style="width: 65%;font-weight: bold;font-size: 10pt;"><strong>'.$this->info_devis['projet'].'</span>';
		//     $height = $this->getLastH();
		//     $this->SetTopMargin($height + $this->GetY() + 5);
		//     writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) {
		//     $this->setCellPadding(1);
		//     //$this->writeHTMLCell(183, '', 15.6, '', $projet, 1, 0, 0, true, 'L', true);
		// }
		// $this->Ln();
		$this->setCellPadding(0);
		$height = $this->getLastH() + $this->GetY();
		//$this->SetTopMargin(10 + $this->GetY());
		//Info général
		$tableau_head = $this->Table_head;
		if($this->no_tabl_head){
			$this->writeHTMLCell('', '', 15, $height, $tableau_head, 0, 0, 0, true, 'L', true);
		    $height = $this->getLastH();
            $this->SetTopMargin($height + $this->GetY());
		}

	}

	// Page footer
	public function Footer() {
		//if($this->qr == true){
// QRCODE,H : QR-CODE Best error correction
			$qr_content = $this->info_devis['reference']."\n".$this->info_devis['denomination']."\n".$this->info_devis['date_devis'];
			$style = array(
				'border' => 1,
				'vpadding' => 'auto',
				'hpadding' => 'auto',
				'fgcolor' => array(0,0,0),
	            'bgcolor' => false, //array(255,255,255)
	            'module_width' => 1, // width of a single module in points
	            'module_height' => 1 // height of a single module in points
           );
	//write2DBarcode($code, $type, $x='', $y='', $w='', $h='', $style='', $align='', $distort=false)
	        $this->SetY(-15);
			//$this->write2DBarcode($qr_content, 'QRCODE,H', 15, '', 25, 25, $style, 'N');
		//}
		$ste_c = new MSte_info();
        $this->SetY(-15);

        if((date('Y-m-d', strtotime($this->info_devis['date_devis']))) < (date('Y-m-d', strtotime('16-04-2020')))){
		$ste = $ste_c->get_ste_info_report_footer(1,$this->info_devis['id_banque'],$this->info_devis['date_devis'],'Devis');
	    }else{
		$ste = $ste_c->get_ste_info_report_footer(2,$this->info_devis['id_banque'],$this->info_devis['date_devis'],'Devis');
	    }

	    $this->SetFont('gotham-book', '', 9);
		$this->writeHTMLCell(0, 0, '', '', $ste , '', 0, 0, true, 'C', true);
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('gotham-book', 'I', 8);
		// Page number
		//$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
	public function writeHTMLTogether($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='') {
    $cp =  $this->getPage();
    $this->startTransaction();

    $this->writeHTML($html, $ln, $fill, $reseth, $cell, $align);

    if ($this->getPage() > $cp) {
         $this->rollbackTransaction(true);//true is very important
         $this->AddPage();
         $this->writeHTML($html, $ln, $fill, $reseth, $cell, $align);
    } else {
         $this->commitTransaction();
    }
    }


}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->Table_head = $tableau_head;
$pdf->info_devis = $devis->devis_info;
$pdf->qr = isset($qr_code) ? $qr_code : false;


// set document information
$pdf->SetCreator(MCfg::get('sys_titre'));
$pdf->SetAuthor(session::get('username'));
$pdf->SetTitle('devis');
$pdf->SetSubject(MCfg::get('client_titre'));
$pdf->SetKeywords(MCfg::get('client_titre'));


// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 90, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

$pdf->SetFooterMargin(30);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 30);


// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//$pdf->SetProtection(array('print', 'copy','modify'), "ourcodeworld", "ourcodeworld-master", 0, null);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// gotham-book or times to reduce file size.
// set font
$pdf->SetFont('gotham-book', '', 9);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();
//If is generated to stored the QR is need
// Print text using writeHTMLCell()
$pdf->Table_body = $tableau_body;
$html = $pdf->Table_body;
// ---------------------------------------------------------

//$pdf->writeHTMLCell('', '','' , '', $html , 0, 0, 0, true, 'L', true);



$obj = new nuts($pdf->info_devis['totalttc'], $pdf->info_devis['devise']);
$ttc_lettre = $obj->convert("fr-FR");
$total_no_remise = $pdf->info_devis['total_no_remise'];
$block_tt_no_remise = '<tr><td style="background-color: #fff;"></td>
					 	   <td style="background-color: #fff;"></td>
			     	   </tr>
				 <tr>
                    <td style="width:30%;color: #fff;background-color: #173C5A;font-weight: bold;font-size: 9pt;border-bottom:1px white;"><strong>Total</strong></td>
                    <td class="alignRight" style="width:45%; background-color: #fff;"><strong>'.$total_no_remise .'  '.$pdf->info_devis['devise'].'</strong></td>
                </tr>';

$block_remise = '
				 <tr>
                    <td style="width:30%;color: #fff;background-color: #173C5A;font-weight: bold;font-size: 9pt;border-bottom:1px white;"><strong>Remise '.$pdf->info_devis['valeur_remise'].' %</strong></td>
                    <td class="alignRight" style="width:45%; background-color: #fff;"><strong>'.$pdf->info_devis['total_remise'].'  '.$pdf->info_devis['devise'].'</strong></td>
                </tr>
               ';

$block_ttc = '<tr>
                    <td style="width:30%;color: #fff;background-color: #173C5A;font-weight: bold;font-size: 9pt;border-bottom:1px white;"><strong>TVA</strong></td>
                    <td class="alignRight" style="width:45%; background-color: #fff;"><strong>'.$pdf->info_devis['totaltva'].'  '.$pdf->info_devis['devise'].'</strong></td>
                </tr>               
                <tr>
                    <td style="width:30%;color: #fff;background-color: #173C5A;font-weight: bold;font-size: 9pt;border-bottom:1px white;"><strong>Total à payer</strong></td>
                    <td class="alignRight" style="width:45%;color: #00D7B9;background-color: #fff;"><strong>'.$pdf->info_devis['totalttc'].' '.$pdf->info_devis['devise'].'</strong></td>
                </tr>'; 

$block_remise = $pdf->info_devis['valeur_remise'] == 0 ? null : $block_remise;
$block_tt_no_remise = $pdf->info_devis['valeur_remise'] == 0 ? null : $block_tt_no_remise;
$block_ttc    = $pdf->info_devis['totaltva'] == 0 ? null : $block_ttc;
$titl_ht = $pdf->info_devis['totaltva'] == 0 ? 'Total à payer' : 'Total Net';
//$signature = $pdf->info_proforma['comercial'];

//$signature = 'La Direction';
$signature = 'Autorisé par : ';

$commercials_array = json_decode($pdf->info_devis['id_commercial'], true);

//var_dump($commercials_array);
//var_dump(count($commercials_array));
//var_dump(count($commercials_array) == 1);


if(count($commercials_array) == 1){
  $commercial = new Mcommerciale();
  $commerciauxIds = str_replace('[', '',   $pdf->info_devis['id_commercial']);
  $commerciauxIds = str_replace(']', '', $commerciauxIds);
  $commercial->id_commerciale = $commerciauxIds;
  $commercial->get_commerciale();
  $creusr = $commercial->commerciale_info['prenom']. " ". $commercial->commerciale_info['nom'];

}else{
$creusr = 'Le Service Commercial';
}

$block_sum = '<div></div>
<style>
p {
    line-height: 0.6;
    .row0
				{
					background-color: #eaebed;
					border:1pt solid black;
				}
				.row1{
					border:1px solid black;
				}
				.alignRight { text-align: right; }
				.center{ text-align: center; }
}

</style>
<table style="width: 685px;" cellpadding="2">
    <tr>
        <td width="60%" align="left">

        </td>
        <td width="40%" style="background-color: #fff;">
           <table class="table" cellspacing="0" cellpadding="2"  style="width: 300px;" >
           
            <tbody>
                '.$block_tt_no_remise.$block_remise.'
                <tr>
                    <td style="width:30%;color: #fff;background-color: #173C5A;font-weight: bold;font-size: 9pt;border-bottom:1px white;"><strong>'.$titl_ht.'</strong></td>
                    <td class="alignRight" style="width:45%; background-color: #fff;border-bottom:1px white;"><strong>'.$pdf->info_devis['totalht'].' '.$pdf->info_devis['devise'].'</strong></td>
                </tr>

                '.$block_ttc.'

            </tbody>
        </table>
    </td>
</tr>';
// <tr>
//     <td colspan="2" style="color: #E99222;font-weight: bold;">
//         Arrêté le présent Devis à la somme de :
//     </td>
// </tr>
// <tr>
//     <td colspan="2" style="color:#6B6868; width: 650px; border:1pt solid black; background-color: #eeecec; padding: 5px;">
//         <strong>'.$ttc_lettre.'</strong>
//     </td>
// </tr>
// <tr>
//     <td colspan="2" style="color: #E99222;font-weight: bold;">
//         <strong>Conditions générales:</strong>
//     </td>
// </tr>

// <tr>
//     <td colspan="2" style="color:#6B6868; width: 650px; border:1pt solid black; background-color: #eeecec; padding: 5px;">
//         '.$pdf->info_devis['claus_comercial'].'
//     </td>

// </tr>
// <tr><td><br><br><br></td></tr>
// <tr align="center">
// <td style="font: underline; width: 200px;" > </td>
// <td style="font: underline; width: 200px;" > </td>
//     <td style="font: underline;">
//                 <strong>'.$signature.'</strong>
//     </td>
// </tr>
// <tr align="center">
// <td style="font: underline; width: 200px;" > </td>
// <td style="font: underline; width: 200px;"> </td>
//     <td style="font: underline;">

//         <strong>'.$creusr.'</strong>
//     </td>
// </tr>
// </table>';
//$pdf->lastPage();
$block_sum .= '
 <tr><td><br><br><br></td></tr>
 <tr align="center">
 <td style="font: underline; width: 200px;" > </td>
 <td style="font: underline; width: 200px;" > </td>
     <td style="font: underline;">
                 <strong>'.$signature.'</strong>
     </td>
 </tr>
 <tr align="center">
 <td style="font: underline; width: 200px;" > </td>
 <td style="font: underline; width: 200px;"> </td>
     <td style="font: underline;">

         <strong>'.$creusr.'</strong>
     </td>
 </tr>
 </table>';

//Problème d'affichage devis sur 2 page 
//FZ le 22/10/2020
/*
$d = new Mdevis();
$d->id_devis = Mreq::tp('id');
$d->get_devis();

if($d->devis_info['etat'] == 0){
	//var_dump('ohhh 0');
$block_sum .= '</table>';

}else{
	//var_dump(' 0');
$block_sum .= '
<tr>
<td colspan="2" align="right" style="font: underline; width: 620px;  padding-right: 200px;">
        <br>
        <span class="profile-picture">
			<img width="170" height="170"
                        class="editable img-responsive"
                        alt="logo_global.png"
                        id="avatar2" src="'.$signature_foto.'"/>
		</span>

    </td>
</tr>
</table>';
}*/

//var_dump($block_sum2);

$pdf->writeHTML($html, true, false, true, false, '');
/*$y = $pdf->GetY();
//No space for Sum Blok then AddPage
if($y > 190)
{
	$pdf->no_tabl_head = false;
	$pdf->AddPage();
	$pdf->writeHTML($block_sum, true, false, true, false, '');

}else{
	$pdf->writeHTML($block_sum, true, false, true, false, '');
}*/
$pdf->writeHTMLTogether($block_sum, $ln=true, $fill=false, $reseth=false, $cell=false, $align='');
/*$block_sum1 = 'Y: '.$y;
$pdf->writeHTML($block_sum1, true, false, true, false, '');
$pdf->writeHTML($block_sum, true, false, true, false, '');
$y = $pdf->GetY();


$block_sum1 = 'Y: '.$y;*/

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($file_export,'F');


//============================================================+
// END OF FILE
//============================================================+

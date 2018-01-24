<?php
//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    var $Table_head  = null;
    var $Table_body  = null;
    var $info_contrat  = array();
    var $info_devis   = array();

    //Page header
    public function Header() {

        // Logo
        $image_file = MPATH_IMG.MCfg::get('logo');
        $this->writeHTMLCell(50, 25, '', '', '' , 0, 0, 0, true, 'C', true);
        $this->Image($image_file, 22, 6, 30, 23, 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);

        //Get info ste from DB
        $ste_c = new MSte_info();

        $ste = $ste_c->get_ste_info_report_head(1);
        $this->writeHTMLCell(0, 0, '', 30, $ste , '', 0, 0, true, 'L', true);
        $this->SetTextColor(0, 50, 127);
        // Set font
        $this->SetFont('helvetica', 'B', 22);

    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }


}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

//$pdf->Table_head = $tableau_head;
//$pdf->Table_body = $tableau_body;
//$pdf->info_contrat=$contrat_info;
//$pdf->info_devis=$devis_info;
//var_dump($contrat_info);
// set document information
$pdf->SetCreator(MCfg::get('sys_titre'));
$pdf->SetAuthor(session::get('username'));
$pdf->SetTitle('Contrat');
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
$pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

$pdf->SetFooterMargin(30);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 60);


// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
// set font
$pdf->SetFont('helvetica', '', 9);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// Print text using writeHTMLCell()
//$pdf->Table_body = $tableau_body;
//$html = $pdf->Table_body;
// ---------------------------------------------------------

//$pdf->writeHTMLCell('', '','' , '', $html , 0, 0, 0, true, 'L', true);
$html=NULL;
//$content = file_get_contents(MPATH_THEMES.'pdf_template/contrat_html.php');
$pdf->lastPage();
//$html = $content;
$html=' <!DOCTYPE html>
<html>
<head>
</head>
<body>
<table style="height: 122px;">
<tbody>
<tr><td style="text-align: center;"><b><h2>Décharge</h2></b></td></tr>
<tr>
<td>
<div style="font-family:Times New Roman;font-size: 15px">
Je soussigné  .................................................... reconnais avoir reçu de................................................<br>
la somme de ..........................................................................................................................................<br>

Ce remboursement de ...................................................... Franc CFA le décharge ainsi de
l\'obligation suivante à mon égard : ..........................................................................................................................<br>
Fait en deux exemplaires à ..................................................................<br>
</div>
<br>
<br>
</td>
</tr>
<tr>
<td style="text-align: right">
<b>Signature</b>
</td>
</tr>
<
</tbody>
</table>
<p>&nbsp;</p>
</body>
</html> ';


$pdf->writeHTMLCell('', '','' , '60', $html , 0, 0, 0, true, 'L', true);
//$pdf->writeHTML($html, true, false, true, false, '');
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($file_export,'F');

//============================================================+
// END OF FILE
//============================================================+


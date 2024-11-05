<?php
//============================================================+
// File name   : example_020.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 020 for TCPDF class
//               Two columns composed by MultiCell of different
//               heights
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
* Creates an example PDF TEST document using TCPDF
* @package com.tecnick.tcpdf
* @abstract TCPDF - Example: Two columns composed by MultiCell of different heights
* @author Nicola Asuni
* @since 2008-03-04
*/

// Include the main TCPDF library (search for installation path).
require_once('tcpdf.php');
require_once('tcpdf_barcodes_1d.php');

// extend TCPF with custom functions
class MYPDF extends TCPDF {

	public function MultiRow($left, $right) {
		// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)

		$page_start = $this->getPage();
		$y_start = $this->GetY();

		// write the left cell
		$this->MultiCell(40, 0, $left, 1, 'R', 1, 2, '', '', true, 0);

		$page_end_1 = $this->getPage();
		$y_end_1 = $this->GetY();

		$this->setPage($page_start);

		// write the right cell
		$this->MultiCell(0, 0, $right, 1, 'J', 0, 1, $this->GetX() ,$y_start, true, 0);

		$page_end_2 = $this->getPage();
		$y_end_2 = $this->GetY();

		// set the new row position by case
		if (max($page_end_1,$page_end_2) == $page_start) {
			$ynew = max($y_end_1, $y_end_2);
		} elseif ($page_end_1 == $page_end_2) {
			$ynew = max($y_end_1, $y_end_2);
		} elseif ($page_end_1 > $page_end_2) {
			$ynew = $y_end_1;
		} else {
			$ynew = $y_end_2;
		}

		$this->setPage(max($page_end_1,$page_end_2));
		$this->SetXY($this->GetX(),$ynew);
	}

}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
/*$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 020');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');*/

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 020', PDF_HEADER_STRING);

// set header and footer fonts
/*$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));*/

// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
/*$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
*/
// set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 20);
// add a page
$pdf->AddPage();

//$pdf->Write(0, 'Example of text layout using Multicell()', '', 0, 'L', true, 0, false, false, 0);

//$pdf->Ln(5);

$pdf->SetFont('times', '', 9);

//$pdf->SetCellPadding(0);
//$pdf->SetLineWidth(2);

// set color for background
$pdf->SetFillColor(255, 255, 200);
// set the barcode content and type
$barcodeobj = new TCPDFBarcode('http://www.tcpdf.org', 'C128');

// output the barcode as HTML object
$text1= $barcodeobj->getBarcodeHTML(2, 30, 'black');

$hospital_name = config_item('hospital_name');


$text = '<table width="100%" border="1" align="center">
  <tr align="center">
    <td width="40%" rowspan="3" valign="top"><p>&nbsp;</p>
      <p>&nbsp;
      <img  src="moz.png" width="55px"/><br>
      REP&Uacute;BLICA DE MO&Ccedil;AMBIQUE<br>
      SERV&Iacute;&Ccedil;O NACIONAL DE SA&Uacute;DE<br>
    <strong>' . $hospital_name . '</strong></p>

<p>&nbsp;</p>
		<p>Unidade Sanit&aacute;ria N.<sup>o</sup>: &nbsp;<b><font color="#ff0000">01040101</font></b> </p>

		</td>
    <td width="60%" bgcolor="#666666">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><b>BOLETIM DE SERVI&Ccedil;O DE URG&Ecirc;NCIA</b></td>
  </tr>
  <tr>
    <td rowspan="2">
		<table width="100%" border="1">
      <tr>
        <td width="30%" align="right">Nome:</td>
        <td  width="70%"  align="left">&nbsp; ofjjjefejkfjefkje fjfkjefkjekfjek fejfjekfjekfj</td>
      </tr>
      <tr>
        <td align="right">Data Emiss&atilde;o Processo/NID:</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">B.I. (N<sup>o</sup>., Arq. Data)</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Data Nasc./Estado/Sexo/Ra&ccedil;a:</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Pofiss&atilde;o:</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Local de Trabalho:</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Naturalidade:</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Resid&ecirc;ncia/Telefone:</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Filia&ccedil;&atilde;o:</td>
        <td align="left">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="bottom"><table width="90%" border="0">
      <tr>
        <td align="right"><h1>NID:</h1></td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  </table>
<table width="100%" border="1" align="center" cellpadding="10px">
  <tr>
    <td align="center" width="60%">PESSOA A CONTACTAR EM CASO DE NECESSIDADE</td>
    <td  width="40%"rowspan="2" align="left">
		<table width="100%" border="0">
      <tr>
        <td width="40%" align="right">Hora de Entrada:</td>
        <td> '.date("H:m:s").'</td>
      </tr>
      <tr>
        <td align="right">Hora de Saida: </td>
        <td> .............</td>
      </tr>
      <tr>
        <td align="right">Data:</td>
        <td> '.date("d/m/Y").'</td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="bottom">
        <td colspan="2" align="center">

<p>&nbsp;</p>
				<b>TOMA CONTA</b></td>
        </tr>
    </table></td>
  </tr>

  <tr>
    <td align="left"><table width="100%" border="0">
      <tr>
        <td width="30%" align="right">Nome:</td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td align="right">Grau de Parentesco:</td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td align="right">Morada/Telef.:</td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td align="right">Local de Trabalho/Telef.:</td>
        <td>&nbsp;</td>
        </tr>
    </table>

		</td>
  </tr>
	<tr>
		<td align="center" width="60%">REGISTO CLINICO</td>
		<td align="center" width="40%"> MOTIVO DE PROCURA DE C/MEDICO</td>
		</tr>
  <tr>
    <td align="left" valign="top"><br />Diagn&oacute;stico Provis&oacute;rio:
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td rowspan="3" align="left" valign="top"><p>1 - Doen&ccedil;a</p>
    <p>2 - Acidente de trabalho</p>
    <p>3 - Acidente de viacao</p>
    <p>4 - Agressao</p>
    <p>5 - Outros acidentes</p>
    <p>6 - Outros motivos</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p align="center">DESTINO</p>
    <p>1 - Alta</p>
    <p>2 - Consulta de .......................................</p>
    <p>3 - Internado no Sector de ......................... .................................................................</p>
    <p>4 - Falecido em ........./........./............</p> </td>
  </tr>
  <tr>
    <td align="left" valign="top"><p><br />
      Tratamento cl&iacute;nico efectuado:</p>
      <p>&nbsp;</p>

      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td align="left" valign="top"><p>Exames laboratoriais e radiogr&aacute;ficos feitos:</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td align="left" valign="top"><p>Diagnostico definitivo:</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td align="center">
      <p align="center">O M&Eacute;DICO</p>
    <p>&nbsp;</p>
    <p>.....................................................................................</p></td>
  </tr>

	<tr>
    <td align="left" valign="top">
Documento Processado por computador, em '.date("d-m-Y H:m:s").'
		</td>
    <td align="center">
      @admin</td>
  </tr>
</table>';




//require_once(dirname(__FILE__).'/tcpdf_barcodes_1d_include.php');

// set the barcode content and type


// print some rows just as example
//for ($i = 0; $i < 10; ++$i) {
//$pdf->MultiCell(0, 0, $text."\n", 1, 'J', 1, 1, '', '', true, 0, false, true, 0);
$pdf->writeHTMLCell(0, 0, '', '', $text, 0, 1, 0, true, '', true);
//}

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_020.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

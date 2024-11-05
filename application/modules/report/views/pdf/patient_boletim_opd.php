<?php
// Criado por Msc JORDAO COLOLO 829397690, 27.11.2018

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/tcpdf_barcodes_1d.php');

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

$pdf->SetFont('times', '', 9.5);

//$pdf->SetCellPadding(0);
//$pdf->SetLineWidth(2);

// set color for background
$pdf->SetFillColor(255, 255, 200);
// set the barcode content and type
$barcodeobj = new TCPDFBarcode('http://www.misau.gov.mz', 'C128');

// output the barcode as HTML object
$text1= $barcodeobj->getBarcodeHTML(2, 30, 'black');
//$pid=$_REQUEST['pid'];
$pac=(int)$pid;
$query="select * from patient where PID=$pac";
$result=$this->db->query($query);
$patient=$result->first_row();
$this->load->model('mpersistent');
$this->load->model('m_patient');


if ($result->num_rows()) {
    $pat_nam_d = $patient->Personal_Title.' '.$patient->Firstname. ' '. $patient->Name ; //returns the fullname
    $pat_hos_d = $hospital; //returns the default hospital
    $pat_sex_d = $patient->Gender;

    $pat_fathnam_d = $patient->FatherName;
    $pat_mothnam_d = $patient->MotherName;
    $pat_prof_d = $patient->Profession;
    $pat_workp_d = $patient->WorkingPlace;
    $pat_birthp_d = $patient->who_district_id;


    $query2="select * from who_districts where district_code=$pat_birthp_d";
    $result2=$this->db->query($query2);
    $patient2=$result2->first_row();
    $pat_birthp_d=$patient2->name;

$user_printer = $this->session->userdata('title') . ' ' . $this->session->userdata('name') . ' ' . $this->session->userdata('other_name');

    $pat_dob_d = $patient->DateOfBirth;
    $pat_dob_y =$patient->DateOfBirth;
		$age_str = get_age_str($patient->DateOfBirth,date("Y-m-d H:m:s"));
	//		$age_str = get_age($patient->DateOfBirth);


	/*	if (isset($patient->DateOfBirth)) {
				$dob = $this->get_age($patient->DateOfBirth);
				$age_str = $dob['years'];
				$pat_dob_m= $dob['months'];
				$pat_dob_d = $dob['days'];
		}*/

    $pat_civ_d = $patient->Personal_Civil_Status;
    $pat_nic_d = $patient->BI_ID;
    $pat_nuit = $patient->NUIT_ID;
    $pat_rem_d = utf8_decode($patient->Remarks);
    $barcode = $patient->PID;
    $pat_add_d = $patient->Address_Street;
    $pat_tel = $patient->Telephone;
    $pid = $patient->PID;
    $doc_num='SISH-<b>'.$hid.substr($patient->CreateDate,0,4).$pid.'</b>-'.date('YmdHms');

}

if(empty($pat_nam_d)) {
	echo "Paciente nao encontrado";
exit;
} else {
$text = '<table width="100%" border="1" align="center">
  <tr align="center">
    <td width="40%" rowspan="3" valign="top"><p>&nbsp;</p>
      <p>
      <img  src="images/moz.png" width="55px"  hight="55px"/>
      <br>
      REP&Uacute;BLICA DE MO&Ccedil;AMBIQUE<br>
      SERV&Iacute;&Ccedil;O NACIONAL DE SA&Uacute;DE<br>
    <strong>'.$hospital.'</strong>
    </p>


		<p>Unidade Sanit&aacute;ria N.<sup>o</sup>: &nbsp;<b><font>'.$hid.'</font></b> <br>
		NID:<font size="18px"  color="#ff0000">'.$pid.'</font></p>

		</td>
    <td width="60%" bgcolor="#666666">&nbsp;</td>
  </tr>

	<tr>
    <td align="center"><font size="14px">PROCESSO DE CONSULTA </font></td>
  </tr>
  <tr>
    <td rowspan="2">
		<table width="100%" border="1"  style="border: 1px dashed #cccccc;">
      <tr>
        <td width="40%" align="right">Nome&nbsp;</td>
        <td  width="60%"  align="left">&nbsp;<b> '.$pat_nam_d.'</b></td>
      </tr>
      <tr>
        <td align="right">Data Emiss&atilde;o Processo/NID&nbsp;</td>
        <td align="left">&nbsp;<b> '.date("d-m-Y").'/'.$pid.'</b></td>
      </tr>
      <tr>
        <td align="right">B.I. (N<sup>o</sup>., Arq. Data)&nbsp;</td>
        <td align="left">&nbsp; '.$pat_nic_d.'</td>
      </tr>
      <tr>
        <td align="right">Data Nasc./Estado/Sexo/Ra&ccedil;a&nbsp;</td>
        <td align="left">&nbsp;<b> '.$age_str.'/'.substr($pat_civ_d,0,1).'/'.$pat_sex_d.'</b></td>
      </tr>
      <tr>
        <td align="right">Pofiss&atilde;o&nbsp;</td>
        <td align="left">&nbsp;'.$pat_prof_d.'</td>
      </tr>
      <tr>
        <td align="right">Local de Trabalho&nbsp;</td>
        <td align="left">&nbsp;'.$pat_workp_d.'</td>
      </tr>
      <tr>
        <td align="right">Naturalidade&nbsp;</td>
        <td align="left">&nbsp;'.$pat_birthp_d.'</td>
      </tr>
      <tr>
        <td align="right">Resid&ecirc;ncia</td>
        <td align="left">&nbsp; '.$pat_add_d.'</td>
      </tr>

			<tr>
				<td align="right">Telefone&nbsp;</td>
				<td align="left">&nbsp; '.$pat_tel.'</td>
			</tr>

      <tr>
        <td align="right">Filia&ccedil;&atilde;o&nbsp;</td>
        <td align="left">&nbsp;<b>'.$pat_fathnam_d.' e de '.$pat_mothnam_d.'</b></td>
      </tr>
			<tr>
				<td align="left" colspan="2">&nbsp;</td>
			</tr>
    </table></td>
  </tr>

  </table>
	<table width="100%" border="1" align="center" cellpadding="10px">
	  <tr>
	    <td align="center" width="10%">Data</td>
	    <td  width="40%" align="left"> Diagn&oacute;sticos Provis&oacute;rios</td>
			<td align="center" width="10%">Data</td>
			<td align="center" width="40%">Diagn&oacute;sticos Definitivos</td>
		</tr>
		<tr>
		 <td align="center" width="10%">
		 <p>&nbsp; </p>
		 <p>&nbsp; </p>


		 </td>
		 <td  width="40%" align="left">&nbsp; </td>
		 <td align="center" width="10%">&nbsp;</td>
		 <td align="center" width="40%">&nbsp;</td>
	 </tr>
	</table>


	<table width="100%" border="1" align="center" cellpadding="10px">
	  <tr>
	    <td align="center" width="10%">Data</td>
			<td align="center" width="60%">Observacoes cl&iacute;nicas</td>
			<td align="center" width="30%">Prescricoes Terapeuticas</td>
		</tr>
		<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>

<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
	</table>

<br> Documento Processado por computador, em '.date("d-m-Y H:m:s").' Operador:  '.$user_printer.' '.$doc_num.'';

}




//require_once(dirname(__FILE__).'/tcpdf_barcodes_1d_include.php');

// set the barcode content and type


// print some rows just as example
//for ($i = 0; $i < 10; ++$i) {
//$pdf->MultiCell(0, 0, $text."\n", 1, 'J', 1, 1, '', '', true, 0, false, true, 0);
$pdf->writeHTMLCell(0, 0, '', '', $text, 0, 1, 0, true, '', true);
//}

// reset pointer to the last page
$pdf->lastPage();
// Clean any content of the output buffer
ob_end_clean();
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($doc_num.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

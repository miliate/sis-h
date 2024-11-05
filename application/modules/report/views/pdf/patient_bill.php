<?php


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
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);



$this->load->library(
    'class/MDSReporter',
    array('orientation' => 'P', 'unit' => 'mm', 'format' => 'receipt', 'footer' => false)
);
//$pdf = $this->mdsreporter;

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
//$pdf->AddPage();
$pdf->AddPage('P','A5');
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
$this->load->model('m_patient_active_list');


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


    $pat_civ_d = $patient->Personal_Civil_Status;
    $pat_nic_d = $patient->BI_ID;
    $pat_nuit = $patient->NUIT_ID;
    $pat_rem_d = utf8_decode($patient->Remarks);
    $barcode = $patient->PID;
    $pat_add_d = $patient->Address_Street;
    $pat_tel = $patient->Telephone;
    $pid = $patient->PID;
    $doc_num='SISH-<b>'.$hid.'.'.$pid.'</b>-'.date('YmdHms');

}

$_department='';
$this->_department = $this->session->userdata('department');



if ($this->_department == 'EMR') {

$titulo="TAXA DE CONSULTA DE URG&Ecirc;NCIA";
$valor='100,00';
} elseif ($this->_department == 'OPD') {
$titulo="TAXA DE CONSULTA EXTERNA";
$valor='5,00';

} else {
$titulo="TAXA DE CONSULTA";
$valor='100,00';
}

if(empty($pat_nam_d)) {
	echo "Paciente nao encontrado";
exit;
} else { 
$text = '<table width="100%" border="0" align="center">
  <tr align="center">
    <td width="40%" rowspan="3" valign="top">
		<p>&nbsp;</p>
      <p>&nbsp;
      <img  src="images/moz.png" width="55px"  hight="55px"/>
      <br><font size="7px">
      REP&Uacute;BLICA DE MO&Ccedil;AMBIQUE<br>----<br>
      GOVERNO DA PROVINCIA DE MAPUTO <br>
    <strong>'.$hospital.'</strong><br>
		Matola C, Qt 20
		<br>
		<b>NUIT:500069988</b></font>
  <br>RECIBO N.<sup>o</sup>: &nbsp;<b><font color="#ff0000">'.Date("ymdhm").substr($hid,2).'</font></b> </p>

<b>'.$titulo.'
	<h2>'.$valor.'</h2></b>
NID: '.$pid.'<br>
NOME: '.$pat_nam_d .'<br>
MP: Dinheiro<br>Data: '.date("Y/m/d H:m:s").'<br>

Processado por computador


		</td>
  </tr>

</table>';
} //end Else

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

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
$pdf->Output($doc_num.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

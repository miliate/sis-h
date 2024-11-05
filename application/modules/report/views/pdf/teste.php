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
    if(!empty($pat_fathnam_d)) { $pat_mothnam_d = 'e de '.$patient->MotherName;} else {$pat_mothnam_d = '';};
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
		$idade = get_age_str($patient->DateOfBirth,date("Y-m-d H:m:s"));
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

$hospital_name = config_item('hospital_name');

if(empty($pat_nam_d)) {
	echo "Paciente nao encontrado";
exit;
} else {
$text = '<table width="100%" border="1" align="center">
  <tr align="center">
    <td width="35%" rowspan="4" valign="top"><p>&nbsp;</p>
      <table width="100%" border="0">
        <tr>
          <td align="center"><p>&nbsp;
<img  src="images/moz.png" width="55px"  hight="55px"/>
            <br>
            REP&Uacute;BLICA DE MO&Ccedil;AMBIQUE<br>
            MINISTÉRIO DA SAÚDE<br>
            SERV&Iacute;&Ccedil;O NACIONAL DE SA&Uacute;DE</p>
          <p><strong>' . $hospital_name . '</strong></p></td>
        </tr>
        <tr>
          <td><p>&nbsp;</p>
            <table width="100%" border="0">
            <tr>
              <td height="30" align="left">Número de Registo:</td>
              <td>_____________</td>
            </tr>
            <tr>
              <td height="30" align="left">Unidade Sanit&aacute;ria N.<sup>o</sup>:</td>
              <td>&nbsp;<b><font color="#ff0000">01040101</font></b></td>
            </tr>
            <tr>
              <td height="30" align="left">NID/HDD:</td>
              <td>_____________</td>
            </tr>
          </table></td>
        </tr>
    </table></td>
    <td width="65%" bgcolor="#666666">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><b>BOLETIM  DA URG&Ecirc;NCIA DA PEDIATRIA</b></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" align="left">
      <tr>
        <td width="35%" align="right">Nome:</td>
        <td width="65%" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Apelido:</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Sexo/Data Nasc./Idade:</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">N<sup>o</sup> de B.I./ Outra Identificação:</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Localidade/Bairro:</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Avenida/Rua/Casa:</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Célula/Quarteirão:</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Posto Administrativo:</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Distrito:</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">Província:</td>
        <td align="left">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>
    <table width="100%" border="0">
      <tr>
        <td colspan="6"><strong>Pessoas de referência / Contacto</strong></td>
        </tr>
      <tr>
        <td width="15">Nome:</td>
        <td width="150">.....</td>
        <td align="left">Apelido:</td>
        <td width="150">.....</td>
        <td align="right">Tel:</td>
        <td width="150">....</td>
        </tr>
      <tr>
        <td>Morada:</td>
        <td>....</td>
        <td colspan="4" align="left">Local de Trabalho:...</td>
        </tr>
    </table>
    </td>
  </tr>
  </table>
<table width="100%" border="1" align="center">
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td width="50%" align="center">DATA:
          <?= date("d")."/".date("m")."/".date("Y"); ?></td>
        <td align="center">HORA DE CHEGADA:
          <?= date("H:i:s"); ?>
          </td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td width="60%">

	  <table width="100%" border="0" align="center" cellpadding="10px">
      <tr>
        <td align="left" valign="top">

				<p>QUEIXA PRINCIPAL:. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .  . . . . . . . . . . . . . . . . . </p>
          <p>HISTÓRIA DA DOENÇA ACTUAL<br>
            . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . </p>
          <p>ANTECEDENTES / ALERGIAS<br>
. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .  . . . . . . . . . . . . . . . . .   . . . . . . . . . . . . . . . . .   . . . . . . . . . . . . . . . . .   . . . . . . . . . . . . . . . . .   . . . . . . . . . . . . . . . . .   . . . . . . . . . . . .</p></td>
        <td align="left" valign="top">

				<p><strong>TIPO DE ADMISSÃO</strong></p>
          <p>1
            <input id="12" type="checkbox" value="no" name="12">
            Normal doutra Unidade Sanitária<br>
            2
  <input id="22" type="checkbox" value="no" name="22">
            Normal das consultas externas<br>
            3
  <input id="32" type="checkbox" value="no" name="32">
            Urgente doutra Unidade Sanitária<br>
            4
  <input id="42" type="checkbox" value="no" name="42">
  Urgente das consultas externas
  <br>
            5
  <input id="52" type="checkbox" value="no" name="52">
            Urgente dum serviço de urgência<br>
            6
  <input id="62" type="checkbox" value="no" name="62">
            Transferência doutro estabelecimento<br>
            7
            <input id="422" type="checkbox" value="no" name="422">
Nascimento nesta Unidade Sanitária<br>
8
<input id="522" type="checkbox" value="no" name="522">
Proveniente da Sala de operações
<br>
9
<input id="622" type="checkbox" value="no" name="622">
____________________________
<br>Marque se a admissão fôr compulsiva
  <input id="6222" type="checkbox" value="no" name="6222">
</p>
          <p><strong>ADMISSÃO</strong></p>
          <p>1 <input id="1" type="checkbox" value="no" name="1"> Doen&ccedil;a<br>
          2  <input id="2" type="checkbox" value="no" name="2">  Acidente de trabalho<br>
          3  <input id="3" type="checkbox" value="no" name="3">  Acidente de viacao<br>
          4  <input id="4" type="checkbox" value="no" name="4">  Agressao<br>
          5  <input id="5" type="checkbox" value="no" name="5">  Outros acidentes<br>
          6  <input id="6" type="checkbox" value="no" name="6">  Outros motivos</p>



					</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top"><p>TRATAMENTO EFECTUADO<br>
          . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . </p></td>
        </tr>
      <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="center"><p align="center">DIA......................... HORA...........</p>
          <p align="center">O Clínico</p>
          <p>_____________________</p></td>
      </tr>
    </table></td>
  </tr>
</table><br>'.$doc_num.'';

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

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($doc_num.'.pdf', 'I');

//============================================================+
// END OF FILE
//==================

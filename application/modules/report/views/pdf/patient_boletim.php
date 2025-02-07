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
		$pat_pid_d = Modules::run('patient/print_hin',$patient->HIN); // returns the ID
    $barcode = $patient->HIN;
    $doc_num='SISH-<b>'.$hid.'.'.$pid.'</b>-'.date('YmdHms');

}

if(empty($pat_nam_d)) {
	echo "Paciente nao encontrado";
exit;
} else {



$text = '<table width="100%" border="1" align="center">
  <tr align="center">
    <td width="40%" rowspan="3" valign="top"><p>&nbsp;</p>
      <p>&nbsp;
      <img  src="images/moz.png" width="55px"  hight="55px"/>
      <br>
      REP&Uacute;BLICA DE MO&Ccedil;AMBIQUE<br>
      SERV&Iacute;&Ccedil;O NACIONAL DE SA&Uacute;DE<br>
    <strong>'.$hospital.'</strong>
    </p>


		<p>Unidade Sanit&aacute;ria N.<sup>o</sup>: &nbsp;<b><font color="#ff0000">'.$hid.'</font></b> </p>

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
        <td width="40%" align="right">Nome:</td>
        <td  width="60%"  align="left">&nbsp;<b> '.$pat_nam_d.'</b></td>
      </tr>
      <tr>
        <td align="right">Data Emiss&atilde;o Processo/NID:</td>
        <td align="left">&nbsp;<b> '.date("d-m-Y").'/'.$pid.'</b></td>
      </tr>
      <tr>
        <td align="right">B.I. (N<sup>o</sup>., Arq. Data)</td>
        <td align="left">&nbsp; '.$pat_nic_d.'</td>
      </tr>
      <tr>
        <td align="right">Data Nasc./Estado/Sexo/Ra&ccedil;a:</td>
        <td align="left">&nbsp;<b> '.$pat_dob_y.' ('.$idade.')/'.substr($pat_civ_d,0,1).'/'.$pat_sex_d.'</b></td>
      </tr>
      <tr>
        <td align="right">Pofiss&atilde;o:</td>
        <td align="left">&nbsp;'.$pat_prof_d.'</td>
      </tr>
      <tr>
        <td align="right">Local de Trabalho:</td>
        <td align="left">&nbsp;'.$pat_workp_d.'</td>
      </tr>
      <tr>
        <td align="right">Naturalidade:</td>
        <td align="left">&nbsp;'.$pat_birthp_d.'</td>
      </tr>
      <tr>
        <td align="right">Resid&ecirc;ncia/Telefone:</td>
        <td align="left">&nbsp; '.$pat_add_d.'/'.$pat_tel.'</td>
      </tr>
      <tr>
        <td align="right">Filia&ccedil;&atilde;o:</td>
        <td align="left">&nbsp;<b>'.$pat_fathnam_d.' '.$pat_mothnam_d.'</b></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="bottom"><table width="75%" border="0">
      <tr>
        <td align="right"><font size="15px">NID:</font></td>
        <td align="left">&nbsp;<font size="15px">'.$pid.'</font></td>
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
        <td width="70%">&nbsp;................................................................................</td>
        </tr>
      <tr>
        <td align="right">Grau de Parentesco:</td>
        <td>&nbsp;................................................................................</td>
        </tr>
      <tr>
        <td align="right">Morada/Telef.:</td>
        <td>&nbsp;................................................................................</td>
        </tr>
      <tr>
        <td align="right">Local de Trabalho/Telef.:</td>
        <td valign="bottom">&nbsp;<br>&nbsp;................................................................................</td>
        </tr>
    </table>

		</td>
  </tr>
	<tr>
		<td align="center" width="60%">REGISTO CLINICO</td>
		<td align="center" width="40%"> MOTIVO DE PROCURA DE C/M&Eacute;DICO</td>
		</tr>
  <tr>
    <td align="left" valign="top"><br />Diagn&oacute;stico Provis&oacute;rio:
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td rowspan="3" align="left" valign="top"><p>1 - Doen&ccedil;a</p>
    <p>2 - Acidente de trabalho</p>
    <p>3 - Acidente de via&ccedil;&atilde;o</p>
    <p>4 - Agress&atilde;o</p>
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
    <td align="left" valign="top"><p>Diagn&oacute;stico definitivo:</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td align="center">
      <p align="center">O M&Eacute;DICO</p>
    <p>&nbsp;</p>
    <p>............................................................................</p></td>
  </tr>

	<tr>
    <td align="left" valign="top">
Documento Processado por computador, em '.date("d-m-Y H:m:s").'
		</td>
    <td align="center">
      Operador: '.$user_printer.'</td>
  </tr>
</table><br>'.$doc_num.'';

}



//require_once(dirname(__FILE__).'/tcpdf_barcodes_1d_include.php');

// set the barcode content and type
$barcodeX = $pdf->getPageWidth() - $pdf->GetStringWidth($barcode);
$barcodeX = $pdf->setBarcode($barcode, $barcodeX + 10, 4);

// print some rows just as example
//for ($i = 0; $i < 10; ++$i) {
//$pdf->MultiCell(0, 0, $text."\n", 1, 'J', 1, 1, '', '', true, 0, false, true, 0);
$pdf->writeHTMLCell(0, 0, '', '', $text, 0, 1, 0, true, '', true);
//}



// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------
// Clean any content of the output buffer
ob_end_clean();

//Close and output PDF document
$pdf->Output($doc_num.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

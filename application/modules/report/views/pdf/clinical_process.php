<?php
// Include the main TCPDF library (search for installation path).
ini_set('memory_limit', '256M');
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
$this->load->model('mpersistent');
$this->load->model('m_patient');
$user_printer = $this->session->userdata('title') . ' ' . $this->session->userdata('name') . ' ' . $this->session->userdata('other_name');
$doc_num='SISH-<b>'.$hid.'.'.$pid.'</b>-'.date('YmdHms');
if (!empty($result_patient_info) && isset($result_patient_info[0])) {
  // Extract entry time
  $entry_time = isset($result_patient_info[0]['entry_time']) ? date("H:i:s", strtotime($result_patient_info[0]['entry_time'])) : 'N/R';

  // Extract exit time
  $exit_time = isset($result_patient_info[0]['exit_time']) 
  ? ($result_patient_info[0]['exit_time'] 
      ? date("H:i:s", strtotime($result_patient_info[0]['exit_time'])) 
      : lang('still_in_hospital'))
  : lang('still_in_hospital');

} else {
  // Set default values if no data is available
  $entry_time = 'N/R';
  $exit_time = 'N/R';
}

if(empty($pid)) {
	echo lang('patient_not_found');
exit;
} else {


  $text .= '<table width="100%" border="1" align="center">
  <tr align="center">
    <td width="40%" rowspan="3" valign="top"><p>&nbsp;</p>
      <p>&nbsp;
      <img  src="images/moz.png" width="55px" height="55px"/>
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
      <table width="100%" border="1">';

  if (!empty($result_patient_info)) {
    $row = $result_patient_info[0];
    $text .= '<tr>
        <td width="40%" align="right">Nome:</td>
        <td width="60%" align="left">&nbsp;<b>' . htmlspecialchars($row['Personal_Title'].' '.$row['Firstname'].' '.$row['Name']). '</b></td>
      </tr>
      <tr>
        <td align="right">Data Emiss&atilde;o Processo/NID:</td>
        <td align="left">&nbsp;<b>' . date("d-m-Y") . '/' .$pid . '</b></td>
      </tr>
      <tr>
        <td align="right">B.I. (N<sup>o</sup>., Arq. Data)</td>
        <td align="left">&nbsp;' . htmlspecialchars($row['BI_ID']) . '</td>
      </tr>
      <tr>
        <td align="right">Data Nasc./Estado/Sexo/Ra&ccedil;a:</td>
        <td align="left">&nbsp;<b>' . htmlspecialchars($row['DateOfBirth']) . ' (' . htmlspecialchars($row['Personal_Civil_Status']) . ')/' . substr(htmlspecialchars($row['Gender']), 0, 1) . '/' . htmlspecialchars($row['Gender']) . '</b></td>
      </tr>
      <tr>
        <td align="right">Profiss&atilde;o:</td>
        <td align="left">&nbsp;' . htmlspecialchars($row['Profession']) . '</td>
      </tr>
      <tr>
        <td align="right">Local de Trabalho:</td>
        <td align="left">&nbsp;' . htmlspecialchars($row['WorkingPlace']) . '</td>
      </tr>
      <tr>
        <td align="right">Naturalidade:</td>
        <td align="left">&nbsp;' . htmlspecialchars($row['district_name']) . '</td>
      </tr>
      <tr>
        <td align="right">Resid&ecirc;ncia/Telefone:</td>
        <td align="left">&nbsp;' . htmlspecialchars($row['Address_Street']) . '/ ' . htmlspecialchars($row['Telephone']) . '</td>
      </tr>
      <tr>
        <td align="right">Filia&ccedil;&atilde;o:</td>
        <td align="left">&nbsp;<b>' . htmlspecialchars($row['FatherName']) . ' & ' . htmlspecialchars($row['MotherName']) . '</b></td>
      </tr>


</table></td>
  </tr>
  <tr>
    <td align="center" valign="bottom"><table width="75%" border="0">
      <tr>
        <td align="right"><font size="15px">NID:</font></td>
        <td align="left">&nbsp;<font size="15px">' . $pid . '</font></td>
      </tr>
    </table></td>
  </tr>
  </table>
<table width="100%" border="1" align="center" cellpadding="10px">
  <tr>
    <td align="center" width="60%">PESSOA A CONTACTAR EM CASO DE NECESSIDADE</td>
    <td width="40%" rowspan="2" align="left">
      <table width="100%" border="0">
     <tr>
          <td width="40%" align="right">Hora de Entrada:</td>
          <td>' . $entry_time . '</td>
      </tr>
      <tr>
          <td align="right">Hora de Saida:</td>
          <td>' . $exit_time . '</td>
      </tr>
      <tr>
        <td align="right">Data:</td>
        <td>' . date("d/m/Y") . '</td>
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
        <td width="70%">&nbsp;<b>'. htmlspecialchars($row['ContactPerson']) .'</b></td>
      </tr>
      <tr>
        <td align="right">Grau de Parentesco:</td>
        <td>&nbsp;<b>'. htmlspecialchars($row['ContactKinship']) .'</b></td>
      </tr>
      <tr>
        <td align="right">Morada/Telef.:</td>
        <td>&nbsp;<b>'. htmlspecialchars($row['ContactAddress']) .'/ '. htmlspecialchars($row['ContactTelephone']).'</b></td>
      </tr>
      <tr>
        <td align="right">Local de Trabalho/Telef.:</td>
        <td valign="bottom">&nbsp;<br>&nbsp;<b>'. htmlspecialchars($row['ContactWorkingPlace']) .'/ '. htmlspecialchars($row['ContactTelephone']).'</b></td>
      </tr>
    </table>
    </td>
  </tr>';
}
  $text .= '
	<tr>
		<td align="center" width="60%">REGISTO CLINICO</td>
		<td align="center" width="40%"> MOTIVO DE PROCURA DE C/M&Eacute;DICO</td>
		</tr>
  <tr>
    <td align="left" valign="top"><br />Prescrição Médica:';

      if (!empty($result_patient_info)) {
        // Initialize an array to store unique prescription names
        $prescriptions = array();
    
        foreach ($result_patient_info as $row) {
            // Check if drug_name exists, is not empty, and is not already in the array
            if (isset($row["drug_name"]) && !empty($row["drug_name"]) && !in_array($row["drug_name"], $prescriptions)) {
                $prescriptions[] = htmlspecialchars($row["drug_name"]);
            }
        }
    
        // Check if there are any prescriptions to display
        if (!empty($prescriptions)) {
            $text .= '<p>' . implode('; ', $prescriptions) . '</p>';
        } else {
            $text .= '<p style="color: red; text-align: center;">Nenhuma Prescrição Médica foi Encontrada!</p>';
        }
    } else {
        $text .= '<p style="color: red; text-align: center;">Nenhuma Prescrição Médica foi Encontrada!</p>';
    }
  
    $text .= '
    </td>
    <td rowspan="3" align="left" valign="top"><p>';
    foreach ($result_patient_info as $row) {
      // Extrair motivo de procura do médico (HospitalizationReason)
      $hospitalization_reason = $row['HospitalizationReason'];
      
      // Adicionar motivo de procura do médico ao HTML apenas uma vez
      $text .= '
          <tr>
              <td align="center"><b>1 - </b> ' . $hospitalization_reason . '</td>
          </tr>';
      
      // Quebrar o loop após a primeira iteração, já que queremos mostrar o motivo apenas uma vez
      break;
  }
  $text .= '
    </p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p align="center">DESTINO</p>
    <p>1 - Alta</p>
    <p>2 - Consulta de .......................................</p>
    <p>3 - Internado no Sector de ......................... .................................................................</p>
    <p>4 - Falecido em ........./........./............</p> </td>
  </tr>';
  
  $text .= '
  <tr>
      <td align="left" valign="top">
          <p><br />
          Tratamento clínico efectuado:</p>';
  
          if (!empty($result_patient_info)) {
            $has_treatments = false;
            $treatments = array(); // Array to keep track of added treatments
            $text .= '<p>&nbsp;';
            foreach ($result_patient_info as $row) {
                if (!empty($row["treatment_name"])) {
                    if (!in_array($row["treatment_name"], $treatments)) { // Check if treatment is already added
                        $text .= htmlspecialchars($row["treatment_name"]) . ';';
                        $treatments[] = $row["treatment_name"]; // Add treatment to the array
                        $has_treatments = true;
                    }
                }
            }
            $text .= '</p>';
        
            if (!$has_treatments) {
                $text .= '<p style="color: red; text-align: center;">Nenhum Tratamento foi Encontrado!</p>';
            }
        } else {
            $text .= '<p style="color: red; text-align: center;">Nenhum Tratamento foi Encontrado!</p>';
        }
        
  
  $text .= '
      </td>
  </tr>';


    

$text .= '
<tr>
    <td align="left" valign="top">
        <p>Exames laboratoriais e radiogr&aacute;ficos feitos:</p>';
         // Add spacing
         $text .= '<strong>Radiologia:</strong>';
         $radiology_details = [];
         $lab_details = [];
         
         // Collect radiology and treatment details
         if (!empty($result_patient_info)) {
             foreach ($result_patient_info as $row) {
                 if (!empty($row['radiology_name']) && !in_array($row['radiology_name'], $radiology_details)) {
                     $radiology_details[] = htmlspecialchars($row['radiology_name']);
                 }
                 if (!empty($row['lab_name']) && !in_array($row['lab_name'], $lab_details)) {
                     $lab_details[] = htmlspecialchars($row['lab_name']);
                 }
             }
         }
         
         // Display radiology details
         if (!empty($radiology_details)) {
             $text .= '<p>' . implode('; ', $radiology_details) . '</p>';
         } else {
             $text .= '<p style="color: red; text-align: center;">Nenhum Exame de Radiologia Encontrado!</p>';
         }
         
         // Display lab test details
         $text .= '<strong>Testes Laboratoriais:</strong>';
         if (!empty($lab_details)) {
             $text .= '<p>' . implode('; ', $lab_details) . '</p>';
         } else {
             $text .= '<p style="color: red; text-align: center;">Nenhum Teste Laboratorial Encontrado!</p>';
         }

    $text .= '
    </td>
</tr>';

$text .= '
<tr>
    <td align="left" valign="top">
        <p>Diagn&oacute;stico definitivo:</p>';

        if (!empty($result_patient_info)) {
          $diagnosis_list = [];
          foreach ($result_patient_info as $row) {
              if (!empty($row["diagnosis_name"]) && !in_array($row["diagnosis_name"], $diagnosis_list)) {
                  $diagnosis_list[] = htmlspecialchars($row["diagnosis_name"]);
              }
          }
          if (!empty($diagnosis_list)) {
              $text .= '<p>&nbsp;' . implode('; ', $diagnosis_list) . '</p>';
          } else {
              $text .= '<p style="color: red;">Nenhum Diagnóstico foi Encontrado!</p>';
          }
      } else {
          $text .= '<p style="color: red;">Nenhum Diagnóstico foi Encontrado!</p>';
      }
      

$text .= '
    </td>
    <td align="center">
        <p align="center">O M&Eacute;DICO</p>
        <p>&nbsp;</p>
        <p>............................................................................</p>
    </td>
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

//$pdf->MultiCell(0, 0, $text."\n", 1, 'J', 1, 1, '', '', true, 0, false, true, 0);
$pdf->writeHTMLCell(0, 0, '', '', $text, 0, 1, 0, true, '', true);

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

?>
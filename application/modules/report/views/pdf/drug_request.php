<?php
// Created by Chelton Mabulambe

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/tcpdf_barcodes_1d.php');

// extend TCPF with custom functions
class MYPDF extends TCPDF {

    public function MultiRow($left, $right) {
        $page_start = $this->getPage();
        $y_start = $this->GetY();

        $this->MultiCell(40, 0, $left, 1, 'R', 1, 2, '', '', true, 0);

        $page_end_1 = $this->getPage();
        $y_end_1 = $this->GetY();

        $this->setPage($page_start);

        $this->MultiCell(0, 0, $right, 1, 'J', 0, 1, $this->GetX() ,$y_start, true, 0);

        $page_end_2 = $this->getPage();
        $y_end_2 = $this->GetY();

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

// Fetch data from database based on request_id
$sql_request = 'SELECT request_code FROM request WHERE id = ?';
$query_request = $this->db->query($sql_request, array($request_id));
$row = $query_request->row();  // Use row() instead of row_array() to get a single row

if ($row) {
    $request_code = $row->request_code;
} else {
    // Handle case where no row is found
    $request_code = '';  // Set a default value or handle as necessary
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

// set font
$pdf->SetFont('helvetica', '', 22);

// add a page
$pdf->AddPage('L');

// set color for background
$pdf->SetFillColor(255, 255, 200);

// set the barcode content and type
$barcodeobj = new TCPDFBarcode('http://www.misau.gov.mz', 'C128');

// output the barcode as HTML object
$text1 = $barcodeobj->getBarcodeHTML(2, 30, 'black');

// Simulate the request_id (for demonstration purposes)

// Fetch data from database based on request_id
$sql = 'SELECT ri.requested_quantity, wd.count, wd.name, wd.dosage, wd.pharmaceutical_form, wd.fnm 
        FROM request_item ri 
        JOIN who_drug wd ON ri.who_drugs_id = wd.wd_id 
        WHERE ri.request_id = ?';

$query = $this->db->query($sql, array($request_id));

$text = '
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, Helvetica, sans-serif;font-size:12px;
  overflow:hidden;padding:10px 5px;word-break:normal;
  border-right: solid 1px black; 
  border-left: solid 1px black;}
.tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, Helvetica, sans-serif;font-size:12px;
  font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
.tg .tg-c3ow{border-color:black;text-align:center;vertical-align:top}
.tg .tg-73oq{border-color:black;text-align:left;vertical-align:top}
.tg .tg-dvpl{border-color:black;text-align:right;vertical-align:top}
.tg .tg-0pky{border-color:black;text-align:left;vertical-align:top}
</style>
<table class="tg" border="1">
    <tbody>
      <tr>
        <td class="tg-0pky" colspan="10">
            <table width="100%" border="0" style="padding-top: 10px;">
            <tr>
              <td align="center" width="75px"><img src="images/moz.png" width="75px" height="75px" />&nbsp;</td>
              <td align="left" colspan="6">REPÚBLICA DE MOÇAMBIQUE<br />
                MINISTÉRIO DA SAÚDE <br />
                CENTRAL DE MEDICAMENTOS E ARTIGOS MÉDICOS</td>
              <td align="center" colspan="4"><h1>REQUISIÇÃO INTERNA</h1><br/>
              <h3>Requisição Nº: '.$request_code.'</h3></td>
            </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="3">Unidade Sanitária:</td>
        <td class="tg-0pky" colspan="3">Distrito:</td>
        <td class="tg-0pky" colspan="3">Província:</td>
        <td class="tg-0pky"></td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="3">Enfermaria de:</td>
        <td class="tg-0pky" colspan="3">Farmácia do ambulatório:</td>
        <td class="tg-0pky" colspan="3">MES/ANO</td>
        <td class="tg-0pky"></td>
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="2">FNM</td>
        <td class="tg-c3ow" colspan="8">Designação</td>
      </tr>
      <tr>
        <td class="tg-c3ow" colspan="2">Código</td>
        <td class="tg-c3ow" colspan="2">Nome</td>
        <td class="tg-c3ow" colspan="1">Dosagem</td>
        <td class="tg-c3ow" colspan="2">Forma Farmacêutica</td>
        <td class="tg-c3ow" colspan="1">Quantidade</td>
        <td class="tg-c3ow" colspan="2">Estoque Existente</td>
      </tr>';

foreach ($query->result_array() as $row) {
    $text .= '
      <tr>
        <td class="tg-0pky" colspan="2">'. $row["fnm"] .'</td>
        
        <td class="tg-0pky" colspan="2">'. $row["name"] .'</td>
        <td class="tg-0pky">'. $row["dosage"] .'</td>
        <td class="tg-0pky" colspan="2">'. $row["pharmaceutical_form"] .'</td>
        <td class="tg-0pky">'. $row["requested_quantity"] .'</td>
        <td class="tg-0pky" colspan="2">'. $row["count"] .'</td>
      </tr>';
}

$text .= '
    </tbody>
</table>';

$hospital_name = config_item('hospital_name');

$pdf->Ln();
$pdf->SetAuthor('Chelton Mabulambe');
$pdf->SetTitle('Requisição Interna '.$request_id." - " .$hospital_name);
$pdf->SetFooterData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

$pdf->writeHTMLCell(0, 0, '', '', $text, 0, 1, 0, true, '', true);

// reset pointer to the last page
$pdf->lastPage();

// Close and output PDF document
ob_end_clean();
$pdf->Output('requisicao_interna.pdf', 'I');
?>

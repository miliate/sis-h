<?php
// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/tcpdf_barcodes_1d.php');

// extend TCPDF with custom functions
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
$pdf->SetFont('helvetica', '', 22);
// add a page
$pdf->AddPage('L');

$pdf->SetFont('courier', '', 9);

$pdf->SetFillColor(255, 255, 200);





// Set header of the table
$text = '
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, Helvetica, sans-serif;font-size:10px;
  overflow:hidden;padding:8px 5px;word-break:normal;
  border-right: solid 1px black; 
  border-left: solid 1px black;}
.tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, Helvetica, sans-serif;font-size:10px;
  font-weight:normal;overflow:hidden;padding:8px 5px;word-break:normal;}
.tg .tg-c3ow{border-color:black;text-align:center;vertical-align:top}
.tg .tg-73oq{border-color:black;text-align:left;vertical-align:top}
.tg .tg-dvpl{border-color:black;text-align:right;vertical-align:top}
.tg .tg-0pky{border-color:black;text-align:left;vertical-align:top}
</style>
<table class="tg" border="1">
    <tbody border="1">
      <tr>
        <td class="tg-0pky" colspan="16">
            <table width="100%" border="0" style="padding-top: 10px;">
            <tr>
              <td align="center" width="75px"><img src="images/moz.png" width="75px" height="75px" />&nbsp;</td>
              <td align="left">REPÚBLICA DE MOÇAMBIQUE<br />
                MINISTÉRIO DA SAÚDE <br />
                CENTRAL DE MEDICAMENTOS E ARTIGOS MÉDICOS</td>
              <td align="center"><h1>FICHA DE STOCK</h1></td>
            </tr>
        </table>
        </td>
      </tr>
    
      <tr>
        <td class="tg-dvpl" colspan="5">US:</td>
        <td class="tg-dvpl" colspan="5">Distrito:</td>
        <td class="tg-dvpl" colspan="5">Província:</td>
      </tr>
      <tr>
        <td class="tg-dvpl" colspan="7">Enfermaria de:</td>
        <td class="tg-dvpl" colspan="8">Farmária de:</td>
      </tr>
      <tr>
        <td class="tg-c3ow">FNM</td>
        <td class="tg-c3ow" colspan="5">Designação (Nome, dosagem <br>forma Farmacéutica)</td>
        <td class="tg-c3ow" colspan="2">CMM</td>
        <td class="tg-c3ow" colspan="7">Prasos de validade</td>
      </tr>
      <tr>
        <td class="tg-0pky"></td>
        <td class="tg-0pky" colspan="3"></td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-0pky" colspan="9"></td>
      </tr>
      <tr>
        <td class="tg-c3ow">Data do Movimento</td>
        <td class="tg-c3ow">Origem/Destino<br>do Movimento</td>
        <td class="tg-c3ow">Nº de Documento</td>
        <td class="tg-c3ow">Nome do Medicamento</td>
        <td class="tg-c3ow">Dosagem</td>
        <td class="tg-c3ow">Forma Farmacêutica</td>
        <td class="tg-c3ow">Entradas</td>
        <td class="tg-c3ow">Ajustes<br>Negativos</td>
        <td class="tg-c3ow">Ajustes<br>Positivos</td>
        <td class="tg-c3ow">Desperdícios</td>
        <td class="tg-c3ow">Consumo</td>
        <td class="tg-c3ow">Dispensas</td>
        <td class="tg-c3ow">Stock Existente</td>
        <td class="tg-c3ow">Pedidos</td>
        <td class="tg-c3ow">Rúbrica</td>
      </tr>';

     
      

// Populate table rows with fetched data
foreach ($result as $row) {
    $text .= '<tr>
    <td class="tg-0pky">' . $row["DataMovimento"]. '</td>
    <td class="tg-0pky">' . $row["OrigemDestinoMovimento"] . '</td>
    <td class="tg-0pky">' . $row["NumeroDocumento"] . '</td>
    <td class="tg-0pky">' . $row["NomeMedicamento"] . '</td>
    <td class="tg-0pky">' . $row["Dosagem"] . '</td>
    <td class="tg-0pky">' . $row["FormaFarmaceutica"] . '</td>
    <td class="tg-0pky">' . $row["Entradas"] . '</td>
    <td class="tg-0pky">' . $row["AjustesNegativos"] . '</td>
    <td class="tg-0pky">' . $row["AjustesPositivos"] . '</td>
    <td class="tg-0pky">' . $row["Desperdicios"] . '</td>
    <td class="tg-0pky">' . $row["Consumo"] . '</td>
    <td class="tg-0pky">' . $row["Dispensas"] . '</td>
    <td class="tg-0pky">' . $row["ExistingStock"] . '</td>
    <td class="tg-0pky">' . $row["Pedidos"] . '</td>
    <td class="tg-0pky">' . $row["Rubrica"] . '</td>
    </tr>';
}

$text .= '
    </tbody>
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>';

// Set author, title, and footer data
$pdf->SetAuthor('Jordao Cololo');
$pdf->SetTitle('receita_medica_'.$active_id.''.$hospital);
$pdf->SetFooterData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

$pdf->writeHTMLCell(0, 0, '', '', $text."\n", 0, 1, 0, true, '', true);

$pdf->lastPage();

// Output PDF to browser
ob_end_clean();
$pdf->Output('.pdf', 'I');
?>

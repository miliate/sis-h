<?php
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/tcpdf_barcodes_1d.php');

class MYPDF extends TCPDF {
    public function MultiRow($left, $right) {
        $page_start = $this->getPage();
        $y_start = $this->GetY();

        $this->MultiCell(35, 0, $left, 1, 'R', 1, 2, '', '', true, 0);

        $page_end_1 = $this->getPage();
        $y_end_1 = $this->GetY();

        $this->setPage($page_start);

        $this->MultiCell(0, 0, $right, 1, 'J', 0, 1, $this->GetX(), $y_start, true, 0);

        $page_end_2 = $this->getPage();
        $y_end_2 = $this->GetY();

        if (max($page_end_1, $page_end_2) == $page_start) {
            $ynew = max($y_end_1, $y_end_2);
        } elseif ($page_end_1 == $page_end_2) {
            $ynew = max($y_end_1, $y_end_2);
        } elseif ($page_end_1 > $page_end_2) {
            $ynew = $y_end_1;
        } else {
            $ynew = $y_end_2;
        }

        $this->setPage(max($page_end_1, $page_end_2));
        $this->SetXY($this->GetX(), $ynew);
    }
}

// Create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set font
$pdf->SetFont('helvetica', '', 7); // Font size further reduced

// Add a page
$pdf->AddPage('R');

// Set color for background
$pdf->SetFillColor(255, 255, 200);

// SQL query to fetch lab tests
$sql = '
    SELECT 
        Department,
        GroupName,
        Name
    FROM 
        lab_tests
    ORDER BY 
        Department, GroupName, Name';

$query = $this->db->query($sql);

$slq_patient = 'SELECT 
                    PID,
                    Name
                FROM patient
                WHERE PID = ?';

$query_patient = $this->db->query($slq_patient, array($pid));


foreach ($query_patient->result_array() as $row)  {
    $patient_name = $row['Name'];
    $patient_nid = $row['PID'];
}


$departments = [
    'Hematologia' => [],
    'Bioquímica Clínica' => [],
    'Microbiologia' => [],
    'Tecnicas Moleculares' => [],
    'Imuno / Serologia' => []
];

foreach ($query->result_array() as $row) {
    $department = $row['Department'];
    $group_name = $row['GroupName'];
    $name = $row['Name'];

    if (array_key_exists($department, $departments)) {
        if (!isset($departments[$department][$group_name])) {
            $departments[$department][$group_name] = [];
        }
        $departments[$department][$group_name][] = '<img src="images/uncheck.png" width="10px" height="10px" /> ' . $name;
    }
}

$text = '
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        text-align: left;
        vertical-align: top;
        font-size: 7pt; /* Font size reduced */
    }
    th {
        background-color: #f4f4f4;
    }
    .section-title {
        font-weight: bold;
        background-color: #e0e0e0;
        font-size: 8pt; /* Font size reduced */
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .full-width {
        width: 100%;
        border: none;
    }
</style>
';

$text .= '
    <div style="text-align: center;">
        <img src="images/moz.png" width="30px" height="30px" /> <!-- Reduced logo size -->
        <h4 style="font-size: 10pt;">REPÚBLICA DE MOÇAMBIQUE</h4> <!-- Font size reduced -->
        <h5 style="font-size: 9pt;">MINISTÉRIO DA SAÚDE</h5> <!-- Font size reduced -->
        <h5 style="font-size: 9pt;">HOSPITAL GERAL DE MAVALANE</h5> <!-- Font size reduced -->
        <h5 style="font-size: 9pt;">REQUISIÇÃO DE ANÁLISES LABORATORIAIS</h5> <!-- Font size reduced -->
    </div>
    
    <table style="border: none;">
        <tr>
            <td style="width: 50%; border: none;">
                <div class="section-title">1. Dados do Doente</div>
                <p><span>Apelido:</span> ____________________________________</p>
                <p><span>Nomes:</span> _____________________________________</p>
                <p><span>NID:</span> _______________________________________</p>
                <p>
                    <span>Sexo:</span> 
                    <img src="images/uncheck.png" width="10px" height="10px" /> M
                    <img src="images/uncheck.png" width="10px" height="10px" /> F
                    <span>Idade:</span> ___________
                    <img src="images/uncheck.png" width="10px" height="10px" /> Anos
                    <img src="images/uncheck.png" width="10px" height="10px" /> Meses
                </p>
                <p><span>Data de Nascimento:</span> ___________$patient_nid_______________</p>
                <p><span>Domicilio:</span> ___________________________________</p>
                <p><span>Endereço:</span> ___________________________________</p>
                <p><span>Cidade:</span> _____________________________________</p>
                <div class="section-title">2. Clínico Requisitante</div>
                <p><span>Nome:</span> ______________________________________</p>
                <p><span>Serviços:</span> ___________________________________</p>
                <p><span>Assinatura:</span> _________________________________</p>
                <p><span>Data:</span> ___/___/____</p>
            </td>
            <td style="border: none;">
                <div class="section-title">3. Caso</div>
                <p>
                    <img src="images/uncheck.png" width="10px" height="10px" /> Surto
                    <img src="images/uncheck.png" width="10px" height="10px" /> Seguimento
                    <img src="images/uncheck.png" width="10px" height="10px" /> Rastrei
                    <img src="images/uncheck.png" width="10px" height="10px" /> Outro
                </p>
                
                <table class="full-width">
                    <tr>
                        <td style="width: 50%; border: none;">
                            <div class="section-title">4. Início dos Sintomas</div>
                            <p>&nbsp;</p>
                        </td>
                        <td style="border: none;">
                            <div class="section-title">5. Condições do Doente</div>
                            <p>
                                <img src="images/uncheck.png" width="10px" height="10px" /> Hospitalizado
                                <img src="images/uncheck.png" width="10px" height="10px" /> Ambulatório
                            </p>
                        </td>
                    </tr>
                </table>

                <div class="section-title">6. Anotações Clínicas</div>
                <p>(Informação clínica, factores de risco, achados lab. anteriores, etc.)</p>
                
                <div class="section-title">7. Natureza da Amostra</div>
                <p><span>Nome do produ100005to / Amostra:</span> ______________________</p>
                <p><span>Proveniência da(s) Amostra(s):</span> __________________</p>
                <p><span>Nº de Identificação da Amostra:</span> __________________</p>
                <p><span>Data, Hora de Colheita:</span> ________________________</p>
            </td>
        </tr>
    </table>
    <br><br><br>
';

$text .= '<table border="1" cellpadding="4">'; 
$text .= '<thead><tr>';

foreach (array_keys($departments) as $department) {
    // We will handle "Técnicas Moleculares" specially inside "Hematologia"
    if ($department !== 'Tecnicas Moleculares') {
        $text .= '<th style="font-size: 7pt;">' . $department . '</th>'; 
    }
}

$text .= '</tr></thead>';
$text .= '<tbody><tr>';

foreach ($departments as $department => $groups) {
    if ($department === 'Tecnicas Moleculares') {
        // Skip this department for now and add its content under Hematologia
        continue;
    }
    
    $text .= '<td style="font-size: 7pt;">';
    if ($department === 'Hematologia') {
        // Special handling for "Hematologia" to also include "Técnicas Moleculares"
        $text .= '<div><strong>' . $department . ':</strong><br/>';
        foreach ($groups as $group_name => $tests) {
            $text .= '<strong>' . $group_name . ':</strong><br/>' . implode('<br/>', $tests) . '<br/><br/>';
        }
        // Append "Técnicas Moleculares" content here
        if (isset($departments['Tecnicas Moleculares'])) {
            foreach ($departments['Tecnicas Moleculares'] as $group_name => $tests) {
                $text .= '<strong>Técnicas Moleculares - ' . $group_name . ':</strong><br/>' . implode('<br/>', $tests) . '<br/><br/>';
            }
        }
        $text .= '</div>';
    } else {
        $content = '';
        foreach ($groups as $group_name => $tests) {
            $content .= '<strong>' . $group_name . ':</strong><br/>' . implode('<br/>', $tests) . '<br/><br/>';
        }
        $text .= $content;
    }
    $text .= '</td>';
}

$text .= '</tr></tbody></table>';

// Output the PDF
$pdf->Ln();
$pdf->SetTitle($patient_name .' ' . $patient_nid . ' - Hospital Geral de Mavalane');
$pdf->SetFooterData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

$pdf->writeHTMLCell(0, 0, '', '', $text, 0, 1, 0, true, '', true);

// Reset pointer to the last page
$pdf->lastPage();

// Close and output PDF document
ob_end_clean();
$pdf->Output('.pdf', 'I');
?>

<?php
/*
--------------------------------------------------------------------------------
HHIMS - Hospital Health Information Management System
Copyright (c) 2011 Information and Communication Technology

----------------------------------------------------------------------------------
*/


$this->load->library(
    'class/MDSReporter',
    array('orientation' => 'L', 'unit' => 'mm', 'format' => 'A4', 'footer' => true)
);
$pdf = $this->mdsreporter;
$pdf->addPage();

$pdf->writeTitle($hospital);
$pdf->writeSubTitle('Registro de Pacientes ' . $date);


$query = "
    SELECT
	SUBSTR(patient.CreateDate, 1, 16) as CreateTime,
    hospital_departments.name as Department,
    patient.PID,
	CONCAT(patient.Personal_Title, ' ', patient.Firstname,' ',patient.Name) AS Patient,
    patient.Gender,
    patient.DateOfBirth,
    patient.Address_Street,
    CONCAT(user.Name,' ',user.OtherName) AS Created_By
    FROM patient
    LEFT JOIN user ON user.UID = patient.CreateUser
    LEFT JOIN hospital_departments ON hospital_departments.department_id = patient.who_department_id
    WHERE patient.CreateDate like '". $date. "%'
    order by hospital_departments.name, patient.CreateDate";
unset($result);
$result = $this->db->query($query);
$count = $result->num_rows();
$row_num = 1;
if ($count != 0) {
    $pdf->Ln();
    $pdf->SetWidths(array(10, 30, 40, 20, 50, 5, 15, 40, 50));
    $pdf->Row(array('#','Horas','Departamento', 'NID', 'Paciente', 'G', 'Idade', utf8_decode('Endereço'), 'Criado Por'), TRUE);

    foreach ($result->result_array() as $row) {
        $age_str = get_age_str($row['DateOfBirth'], $row['CreateTime']);
        $pdf->Row(array($row_num ++, $row['CreateTime'], utf8_decode($row["Department"]), $row['PID'], utf8_decode($row['Patient']),$row['Gender'], $age_str, utf8_decode($row['Address_Street']), $row['Created_By']));
    }
    $pdf->MultiCell(0, 6, "Total : $count");
}
$pdf->Output('Registro de Pacientes ' . $date. '.pdf', 'I');
?>

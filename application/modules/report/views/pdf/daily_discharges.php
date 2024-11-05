<?php
/*
--------------------------------------------------------------------------------
HHIMS - Hospital Health Information Management System
Copyright (c) 2011 Information and Communication Technology Agency of Sri Lanka
<http: www.hhims.org/>
----------------------------------------------------------------------------------
This program is free software: you can redistribute it and/or modify it under the
terms of the GNU Affero General Public License as published by the Free Software 
Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along 
with this program. If not, see <http://www.gnu.org/licenses/> or write to:
Free Software  HHIMS
C/- Lunar Technologies (PVT) Ltd,
15B Fullerton Estate II,
Gamagoda, Kalutara, Sri Lanka
---------------------------------------------------------------------------------- 
Author: Mr. Thurairajasingam Senthilruban   TSRuban[AT]mdsfoss.org
Consultant: Dr. Denham Pole                 DrPole[AT]gmail.com
URL: http: www.hhims.org
----------------------------------------------------------------------------------
*/

$this->load->library(
    'class/MDSReporter',
    array('orientation' => 'L', 'unit' => 'mm', 'format' => 'A5', 'footer' => true)
);
$pdf = $this->mdsreporter;
$pdf->addPage();
$pdf->writeTitle($hospital);
$pdf->writeSubTitle('Discharged Patients ' . $date);

$sql = "select a.PID as pid,p.Firstname as patient,p.Name as patient2,a.Complaint as complaint,
u.Name as doctor,u.OtherName as doctor2,a.OutCome as outcome,w.`Name` as ward 
from admission as a,patient as p,`user` as u,ward as w 
where a.OnSetDate LIKE '$date%' and a.PID=p.PID and a.Doctor=u.UID and a.Ward=w.WID 
order by p.PID";
$result = $this->db->query($sql);
$count = $result->num_rows();

//echo $sql;

if ($count != 0) {
    $pdf->Ln();
    $pdf->SetWidths(array(15,35,35,50,40,15));
    $pdf->Row(array('PID','Name','Complaint','Outcome','Doctor','Ward'), TRUE);
    foreach ($result->result_array() as $row) {
        $pdf->Row(array($row['pid'], $row['patient2'].' '.$row["patient"], $row['complaint'],$row['outcome'],$row['doctor'].' '.$row['doctor2'],$row['ward']));
    }
}
$pdf->Output('discharges' . $date, 'I');
?>

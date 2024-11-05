<?php
if ((isset($patient_exams_list)) && (!empty($patient_exams_list))) {
    echo '<div class="panel panel-default">';
    echo '<div class="panel-heading"><b>' . lang('Past Examinations') . '</b></div>';
    echo '<div class="table-responsive" style="max-height: 160px; overflow-y: auto;">'; 
    echo '<table class="table table-condensed table-hover" style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
	
    echo '<thead>';
    echo '<tr>';
    echo '<th>' . lang('Date') . '</th>';
    echo '<th>' . lang('Weight Past Exam') . '</th>';
    echo '<th>' . lang('Height') . '</th>';
    echo '<th>' . lang('Sys_BP') . ' / ' . lang('Diast_BP') . '</th>';
    echo '<th>' . lang('Temperature') . '</th>';
    echo '</tr>';
    echo '</thead>';

    echo '<tbody>';
    for ($i = 0; $i < count($patient_exams_list); ++$i) {
        echo '<tr onclick="self.document.location=\'' . site_url("patient_examination/edit/" . $patient_exams_list[$i]["PATEXAMID"]) . '?CONTINUE=' . $continue . '\';">';
        echo '<td>';
        echo $patient_exams_list[$i]["CreateDate"];
        echo '</td>';
        echo '<td>';
        if (isset($patient_exams_list[$i]["Weight"]) && ($patient_exams_list[$i]["Weight"] > 0)) {
            echo $patient_exams_list[$i]["Weight"] . 'Kg';
        }
        echo '</td>';
        echo '<td>';
        if (isset($patient_exams_list[$i]["Height"]) && ($patient_exams_list[$i]["Height"] > 0) && ($patient_exams_list[$i]["Height"] < 3)) {
            echo $patient_exams_list[$i]["Height"] . 'm';
        }
        echo '</td>';
        echo '<td>';
        echo $patient_exams_list[$i]["sys_BP"] . '/' . $patient_exams_list[$i]["diast_BP"];
        echo '</td>';
        echo '<td>';
        if (isset($patient_exams_list[$i]["Temprature"]) && ($patient_exams_list[$i]["Temprature"] > 29) && ($patient_exams_list[$i]["Temprature"] < 46)) {
            echo $patient_exams_list[$i]["Temprature"] . '`C';
        }
        echo '</td>';
        echo '<td>';
        //echo 'By: '.$patient_exams_list[$i]["CreateUser"];
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>'; // End of new div for scroll
    echo '</div>';
}
?>
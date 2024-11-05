<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 29-Oct-15
 * Time: 9:53 AM
 */
if ((isset($patient_treatment_list)) && (!empty($patient_treatment_list))) {
    echo '<div class="panel panel-default"   >';
    echo '<div class="panel-heading" ><b>'. lang('Previous Treatment Orders'). '</b></div>';
    echo '<table class="table table-condensed table-hover"  style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
    for ($i = 0; $i < count($patient_treatment_list); ++$i) {
        echo '<tr onclick="self.document.location=\'' . site_url("/treatment_order/edit_created/" . $patient_treatment_list[$i]["OrderTreatmentID"]) . '?CONTINUE='. $continue . '\';">';
        echo '<td>';
        echo $patient_treatment_list[$i]["CreateDate"];
        echo '</td>';
        echo '<td>';
        echo $patient_treatment_list[$i]["treatment"]->Treatment;
        echo '</td>';
        echo '<td>';
        echo $patient_treatment_list[$i]["Remarks"];
        echo '</td>';
        echo '<td style="text-align: right">';
        switch ($patient_treatment_list[$i]["Status"]) {
            case 'Done':
                echo '<span class="glyphicon glyphicon-check"></span>';
                echo '<span style="color: green"> Done</span>';
                break;
            case 'Pending':
                echo '<span class="glyphicon glyphicon-time"></span>';
                echo '<span style="color: red"> Pending</span>';
                break;
        };
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '</div>';
}
<?php
if ((isset($patient_pa_order_list)) && (!empty($patient_pa_order_list))) {
    echo '<div class="panel panel-default" >';
    echo '<div class="panel-heading"  ><b>' .'Previous Pathological Anatomy Orders'. '</b></div>';

    echo '<table class="table table-condensed table-hover"   style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
    for ($i = 0; $i < count($patient_pa_order_list); ++$i) {
        if ($patient_pa_order_list[$i]->Result_Status === 'Done') {
            if ($sample_type[$i]->Name == "Biopsy"){
                echo '<tr onclick="self.document.location=\'' . site_url("pathological_anatomy_order/view_biopsy_result/" . $patient_pa_order_list[$i]->PA_order_ID) . '?CONTINUE=' . $continue . '\'; " >';
            } elseif ($sample_type[$i]->Name == "Cytology") {
                echo '<tr onclick="self.document.location=\'' . site_url("pathological_anatomy_order/view_cytology_result/" . $patient_pa_order_list[$i]->PA_order_ID) . '?CONTINUE=' . $continue . '\'; " >';
            } else {
                echo '<tr onclick="self.document.location=\'' . site_url("pathological_anatomy_order/view_cv_cytology_result/" . $patient_pa_order_list[$i]->PA_order_ID) . '?CONTINUE=' . $continue . '\'; " >';
            }
        } else {
            echo '<tr onclick="">';
        }
//        echo '<tr onclick="">';
        echo '<td>';
        echo $patient_pa_order_list[$i]->CreateDate;
        echo '</td>';
        echo '<td style="color: blue"><b>';
        echo $sample_type[$i]->Name;
        echo '</b></td>';
        echo '<td style="text-align: right">';
        if ($patient_pa_order_list[$i]->Result_Status == "Pending") {
            echo '<span class="glyphicon glyphicon-time"></span>';
            echo '<span style="color: red"> Pending</span>';
        } else {
            echo '<span class="glyphicon glyphicon-check"></span>';
            echo '<span style="color: green"> ' . $patient_pa_order_list[$i]->Result_Status . '</span>';
        }
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '</div>';
}
?>
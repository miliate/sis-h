<?php
if ((isset($patient_treatment_list)) && (!empty($patient_treatment_list))) {
    echo '<div class="panel panel-default">';
    echo '<div class="panel-heading"><b>' . lang('Previous Treatment Orders') . '</b></div>';
    echo '<div style="max-height: 160px; overflow-y: auto;">'; 
    
    echo '<table class="table table-condensed table-hover" style="font-size:0.95em; margin-bottom:0px; cursor:pointer;">';
    
    echo '<thead>';
    echo '<tr>';
    echo '<th style="position: sticky; top: 0; background-color: white;">' . lang('Service') . '</th>';
    echo '<th style="position: sticky; top: 0; background-color: white;">' . lang('Date and Time') . '</th>';
    echo '<th style="position: sticky; top: 0; background-color: white;">' . lang('Treatment Name') . '</th>';
    echo '<th style="position: sticky; top: 0; background-color: white;">' . lang('The Clinic') . '</th>';
    echo '<th style="position: sticky; top: 0; background-color: white;">' . lang('The Nurse') . '</th>';
    echo '<th style="position: sticky; top: 0; background-color: white; text-align: left;">' . lang('Status') . '</th>';
    echo '</tr>';
    echo '</thead>';

    echo '<tbody>';
    for ($i = 0; $i < count($patient_treatment_list); ++$i) {
       // echo '<tr onclick="self.document.location=\'' . site_url("/treatment_order/edit_created/" . $patient_treatment_list[$i]["OrderTreatmentID"]) . '?CONTINUE=' . $continue . '\';">';

        echo '<td width=10px>';
        echo '<a title="Click here to open the related record" class="btn btn-xs ';
        if ($this->uri->segment(3) == $patient_treatment_list[$i]["RefID"]) {
            echo ' btn-warning"';
        } else {
            echo ' btn-default"';
        }
        if ($patient_treatment_list[$i]["RefType"] == "OPD") {
            echo ' href="' . site_url("opd_visit/view/" . $patient_treatment_list[$i]["RefID"]) . '" ';
        } elseif ($patient_treatment_list[$i]["RefType"] == "ADM") {
            echo ' href="' . site_url("admission/view/" . $patient_treatment_list[$i]["RefID"]) . '" ';
        } else {
            echo ' href="#" ';
        }
        echo '>' . $patient_treatment_list[$i]["RefType"] . '</a>';
        echo '</td>';

        echo '<td>' . $patient_treatment_list[$i]["CreateDate"] . '</td>';
        echo '<td>' . $patient_treatment_list[$i]["treatment"]->Treatment . '</td>';
        echo '<td>' . $patient_treatment_list[$i]["Remarks"] . '</td>';
        echo '<td>' . $patient_treatment_list[$i]["Remarks_Nurse"] . '</td>';
        echo '<td style="text-align: left">';
        switch ($patient_treatment_list[$i]["Status"]) {
            case 'Done':
                echo '<span class="glyphicon glyphicon-check"></span>';
                echo '<span style="color: green">' . lang('Done') . '</span>';
                break;
            case 'Pending':
                echo '<span class="glyphicon glyphicon-time"></span>';
                echo '<span style="color: red">' . lang('Pending') . '</span>';
                break;
        }
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>'; 
    echo '</div>';
}
?>
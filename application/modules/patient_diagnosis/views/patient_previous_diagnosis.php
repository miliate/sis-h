<?php
if ((isset($previous_diagnosis_list)) && (!empty($previous_diagnosis_list))) {
    echo '<div class="panel panel-default">';
    echo '<div class="panel-heading"><b>' . lang('Previous Diagnosis') . '</b></div>';

    echo '<div style="max-height: 155px; overflow-y: auto;">';
    echo '<table class="table table-condensed table-hover" style="font-size:0.95em; margin-bottom:0px; cursor:pointer;">';

    echo '<thead>';
    echo '<tr>';
    echo '<th style="position: sticky; top: 0; background-color: white;">' . lang('Service') . '</th>';
    echo '<th style="position: sticky; top: 0; background-color: white;">' . lang('Date and Time') . '</th>';
    echo '<th style="position: sticky; top: 0; background-color: white;">' . lang('Diagnosis Type') . '</th>';
	echo '<th style="position: sticky; top: 0; background-color: white;">' . lang('Diagnosis') . '</th>';

    echo '</tr>';
    echo '</thead>';

    echo '<tbody>';
    for ($i = 0; $i < min(25, count($previous_diagnosis_list)); ++$i) {
        echo '<tr onclick="self.document.location=\'' . site_url("patient_diagnosis/edit_created/" . $previous_diagnosis_list[$i]->patient_diagnosis_id) . '?CONTINUE=' . $continue . '\'">';
        echo '<td width="10px">';
        echo '<a title="Click here to open the related record" class="btn btn-xs ';
        echo ($this->uri->segment(3) == $previous_diagnosis_list[$i]->RefID) ? 'btn-warning"' : 'btn-default"';

        switch ($previous_diagnosis_list[$i]->RefType) {
            case 'emr':
                echo ' href="' . site_url("emergency_visit/view/" . $previous_diagnosis_list[$i]->RefID) . '"';
                break;
            default:
                echo ' href="' . site_url("opd_visit/view/" . $previous_diagnosis_list[$i]->RefID) . '"';
                break;
        }

        echo '>' . $previous_diagnosis_list[$i]->RefType . '</a>';
        echo '</td>';
        echo '<td>' . $previous_diagnosis_list[$i]->CreateDate . '</td>';
        echo '<td>' . $previous_diagnosis_list[$i]->diagnosis_type_1_name . '</td>';
        echo '<td>' . (($previous_diagnosis_list[$i]->diagnosis_id !== null) ? $previous_diagnosis_list[$i]->diagnosis_icd10 : $previous_diagnosis_list[$i]->diagnosis) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    
    echo '</table>';
    echo '</div>'; 
    echo '</div>'; 
}
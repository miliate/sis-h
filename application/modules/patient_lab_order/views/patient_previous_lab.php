<?php
if ((isset($patient_lab_order_list)) && (!empty($patient_lab_order_list))) {
    echo '<div class="panel panel-default">';
    echo '<div class="panel-heading"><b>' . lang('Previous Lab Orders') . '</b></div>';
    echo '<div style="max-height: 155px; overflow-y: auto;">';  
    echo '<table class="table table-condensed table-hover" style="font-size:0.95em; margin-bottom:0px; cursor:pointer;">';

    echo '<thead>';
    echo '<tr>';
    echo '<th>' . lang('Service') . '</th>';
    echo '<th>' . lang('Date') . '</th>';
    echo '<th>' . lang('Prescribed Time') . '</th>';
    echo '<th>' . lang('Group') . '</th>';
    echo '<th>' . lang('The Clinic') . '</th>';
    echo '<th>' . lang('Status') . '</th>';
    echo '</tr>';
    echo '</thead>';

    for ($i = 0; $i < count($patient_lab_order_list); ++$i) {
        echo '<tr onclick="self.document.location=\'' . site_url("patient_lab_order/view_result/" . $patient_lab_order_list[$i]->LAB_ORDER_ID) . '?CONTINUE=' . $continue . '\';">';
        echo '<td width=10px>';
        echo '<a title="Click here to open the related record" class="btn btn-xs ';
        if ($this->uri->segment(3) == $patient_lab_order_list[$i]->RefID) {
            echo ' btn-warning"';
        } else {
            echo ' btn-default"';
        }
        if ($patient_lab_order_list[$i]->RefType == "OPD") {
            echo ' href="' . site_url("opd_visit/view/" . $patient_lab_order_list[$i]->RefID) . '" ';
        } elseif ($patient_lab_order_list[$i]->RefType == "ADM") {
            echo ' href="' . site_url("admission/view/" . $patient_lab_order_list[$i]->RefID) . '" ';
        } else {
            echo ' href="#" ';
        }
        echo '>' . $patient_lab_order_list[$i]->RefType . '</a>';
        echo '</td>';
        echo '<td>' . date('d-m-Y', strtotime($patient_lab_order_list[$i]->CreateDate)) . '</td>';
        echo '<td>' . date('H:i', strtotime($patient_lab_order_list[$i]->CreateDate)) . '</td>';
        echo '<td>';
        echo $patient_lab_order_list[$i]->group->Name; 
        echo '</td>';
        echo '<td>';
        if (!empty($patient_lab_order_list[$i]->order_by)) {
            echo $patient_lab_order_list[$i]->order_by->Title . ' ' . $patient_lab_order_list[$i]->order_by->Name . ' ' . $patient_lab_order_list[$i]->order_by->OtherName;
        }
        echo '</td>';
        echo '<td style="text-align: left">';
        if ($patient_lab_order_list[$i]->Status == "Pending") {
            echo '<span class="glyphicon glyphicon-time"></span>';
            echo '<span style="color: red"> Pending</span>';
        } else {
            echo '<span class="glyphicon glyphicon-check"></span>';
            echo '<span style="color: green"> ' . $patient_lab_order_list[$i]->Status . '</span>';
        }
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '</div>'; 
    echo '</div>';
}
?>
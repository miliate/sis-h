<?php
if ((isset($previous_prescription_list)) && (!empty($previous_prescription_list))) {
    echo '<div class="panel panel-default">';
    echo '<div class="panel-heading"><b>' . lang('Prescription') . '</b></div>';
    echo '<div style="max-height: 155px; overflow-y: auto;">'; 

    echo '<table class="table table-condensed table-hover" style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';

    echo '<thead>';
    echo '<tr>';
    echo '<th>' . lang('Department') . '</th>';
    echo '<th>' . lang('Date') . '</th>';
    echo '<th>' . lang('Prescribed Time') . '</th>';
    echo '<th>' . lang('Prescriber') . '</th>';
    echo '<th>' . lang('Status') . '</th>';
    echo '<th>' . lang('Order') . '</th>';
    echo '<th>' . lang('Actions') . '</th>';

    echo '</tr>';
    echo '</thead>';

    echo '<tbody>';
    for ($i = 0; $i < count($previous_prescription_list); ++$i) {
        echo '<tr>';

        echo '<td width=10px>';
        echo '<a title="Click here to open the related record" class="btn btn-xs ';
        if ($this->uri->segment(3) == $previous_prescription_list[$i]->RefID) {
            echo ' btn-warning"';
        } else {
            echo ' btn-default"';
        }
        if ($previous_prescription_list[$i]->RefType == "OPD") {
            echo ' href="' . site_url("opd_visit/view/" . $patient_lab_order_list[$i]->RefID) . '" ';
        }
        if ($previous_prescription_list[$i]->RefType == "ADM") {
            echo ' href="' . site_url("admission/view/" . $previous_prescription_list[$i]->RefID) . '" ';
        } else {
            echo ' href="#" ';
        }
        echo '>' . $previous_prescription_list[$i]->RefType . '</a>';
        echo '</td>';

        echo '<td>' . date('d-m-Y', strtotime($previous_prescription_list[$i]->CreateDate))  . '</td>';
        echo '<td>' . date('H:i', strtotime($previous_prescription_list[$i]->CreateDate))  . '</td>';
        echo '<td>';
        if (!empty($previous_prescription_list[$i]->order_by)) {
            echo '<b>' . $previous_prescription_list[$i]->order_by->Title . ' ' . $previous_prescription_list[$i]->order_by->Name . ' ' . $previous_prescription_list[$i]->order_by->OtherName . '</b>';
        }
        echo '</td>';

        echo '<td style="text-align: left">';
        switch ($previous_prescription_list[$i]->Status) {
            case 'Pending':
                echo '<span class="glyphicon glyphicon-time"></span>';
                echo '<span style="color: red"> ' . lang('Pending') . '</span>';
                break;
            case 'Dispensed':
                echo '<span class="glyphicon glyphicon-check"></span>';
                echo '<span style="color: green"> ' . lang('Dispense') . '</span>';
                break;
        }
        echo '</td>';
        echo '<td ></td>';
        echo '<td>';
        echo '<form class="print-form" action="' . site_url('/report/pdf/internalPrescription/print/') . '" method="post" target="_blank" style="display: inline;">';
        echo '<input type="hidden" name="print_prescription" value="' . $previous_prescription_list[$i]->PrescriptionID . '">';
        echo '<button type="submit" class="btn btn-primary btn-sm">' . lang('View') . '</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo '</div>';
}

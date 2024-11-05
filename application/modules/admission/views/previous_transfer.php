<?php
if ((isset($transfer_list)) && (!empty($transfer_list))) {
    echo '<div class="panel  panel-default" >';
    echo '<div class="panel-heading" " ><b>Previous Transfer</b></div>';
    echo '<table class="table table-condensed table-hover"  style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
    echo '<tr>';
    echo '<td>Date</td>';
    echo '<td>From</td>';
    echo '<td>To</td>';
    echo '</tr>';
    foreach ($transfer_list as $transfer) {
        echo '<tr>';
        echo '<td>'.$transfer->CreateDate.'</td>';
        echo '<td>'.$transfer->transfer_from->Name.'</td>';
        echo '<td>'.$transfer->transfer_to->Name.'</td>';
        echo '</tr>';
    }

    echo '</table>';
    echo '</div>';
}
?>		
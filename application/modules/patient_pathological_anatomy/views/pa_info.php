<?php
/**
 * Created by Trung Hoang.
 */
?>
<div class="panel panel-danger">
    <div class="panel-heading"><b><?php echo lang('Pathological Anatomy Visit') ?></b></div>
    <?php
    echo '<table class="table table-condensed" >';
    echo '<tr>';
    echo '<td colspan="2">';
    echo lang('Time'). ': ' . $pa_visits_info["DateTimeOfVisit"];
    echo '</td>';
    echo '<td>';
    if ($pa_visits_info["Doctor"] != NULL) {
        echo lang('Doctor'). ': ' . $pa_visits_info["Doctor"]->Title. ' '. $pa_visits_info["Doctor"]->Name. ' '. $pa_visits_info["Doctor"]->OtherName ;
    }
    //    echo "<input type='button' class='btn btn-xs btn-warning pull-right' onclick=self.document.location='" . site_url('form/edit/opd_visits/' . $opd_visits_info["OPDID"]) . "' value='Edit'>";
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td colspan="4">';
    echo lang('Complaint / Injury') . ': <b>' . $pa_visits_info["Complaint"] . '</b>';
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td colspan=2>';
    echo lang('Remarks'). ': ' . $pa_visits_info["Remarks"];
    echo '</td>';
    //    echo '<td >';
    //    echo 'CreatedBy: ' . character_limiter($opd_visits_info["CreateUser"], 15);
    //    echo '</td>';
    echo '<td >';
    if ($pa_visits_info["LastUpDateUser"] != "") {
        echo 'Last Access By: ' . character_limiter($pa_visits_info["LastUpDateUser"], 15);
    }
    echo '</td>';
    echo '</tr>';
//    if ($pa_visits_info['refer_to_adm_id'] > 0) {
//        echo '<tr class="warning">';
//        echo '<td><b>Referred to admission</b></td>';
//        echo '<td><b>Refer Status: </b>' . $refer_to_adm->Status . '</td>';
//        echo '<td colspan="2">';
//        if ($refer_to_adm->AdmissionID > 0) {
//            echo '<b>Admission ID: </b>' . $refer_to_adm->AdmissionID;
//        }
//        echo '</td>';
//        echo '</tr>';
//    }
//    if ($pa_visits_info['discharge_order'] > 0) { ?>
<!--        <span class="label label-warning" style="font-size: 100%">-->
<!--                <span class="glyphicon glyphicon-info-sign"></span>-->
<!--                &nbsp;Ordered discharge. Status: <span style="color: red">-->
<!--                --><?php
//                $this->load->model('m_discharge_order');
//                $discharge_order = $this->m_discharge_order->get($opd_visits_info['discharge_order']);
//                echo $discharge_order->Status;
//                ?>
<!--                </span>.-->
<!--            --><?php
//            if (Modules::run('permission/check_permission', 'order_discharge', 'edit') && $discharge_order->Status == 'Pending') {
//                echo 'Click <a href="' . base_url() . 'index.php/order_discharge/edit_created/' . $opd_visits_info['discharge_order'] . '">here</a> to edit discharge order.';
//            }
//            ?>
<!--            </span>-->
<!--        --><?php
//    }
    echo '</table>';
    ?>
</div>

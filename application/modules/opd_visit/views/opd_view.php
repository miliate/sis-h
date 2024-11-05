<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/opd', $OPDID, $PID, $opd_visits_info, $is_discharged); //runs the available left menu for preferance ?>
        </div>
        <div class="col-md-10 ">
            <?php
            if (!empty($notification["complaint_data"])) {
                if (empty($notification["notification_data"])) {
                    echo '<div class="alert alert-danger"><b>Notification alert: </b> Complaint:  <b>' . $notification["complaint_data"]["Name"] . '</b><br>';
                    echo 'Do you want to notify this?   ';
                    echo '<a class="btn btn-sm btn-default" href="' . site_url("notification/create/opd/" . $opd_visits_info["OPDID"] . "?CONTINUE=opd/view/" . $opd_visits_info["OPDID"]) . '">Yes</a>';
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-warning"><b>Notification for </b> Complaint,  <b>"' . $notification["complaint_data"]["Name"] . '"</b> created. ';
                    echo 'Do you want to    ';
                    echo '<a  target="_blank" href="' . site_url("notification/view/" . $notification["notification_data"]["NOTIFICATION_ID"]) . '">send</a>?&nbsp;&nbsp;&nbsp;';
                    echo 'Do you want to    ';
                    echo '<a  target="_blank" href="' . site_url("notification/edit/" . $notification["notification_data"]["NOTIFICATION_ID"]) . '">edit </a>?';
                    echo '</div>';
                }
            }
            ?>
            <!--Patient Info-->
            <?php echo Modules::run('patient/banner_full', $PID) ?>
            <!--End Patient Info-->

            <!-- OPD INFO-->
            <?php echo Modules::run('opd_visit/info', $OPDID); ?>
            <!-- END OPD INFO-->

            <!-- ICD10-->
            <?php echo Modules::run('patient_diagnosis/get_previous_diagnosis', 'opd', $opd_visits_info["OPDID"], 'opd_visit/view/' . $opd_visits_info["OPDID"], "HTML"); ?>
            <!-- END SOAP-->

            <!-- SOAP-->
            <?php echo Modules::run('patient_soap/get_previous_soap', 'opd', $opd_visits_info["OPDID"], 'opd_visit/view/' . $opd_visits_info["OPDID"], "HTML"); ?>
            <!-- END SOAP-->

            <!-- LAB-->
            <?php echo Modules::run('patient_lab_order/get_previous_lab', $PID, 'opd_visit/view/' . $opd_visits_info["OPDID"], "HTML"); ?>
            <!-- END LAB-->

            <!-- Radiology-->
            <?php echo Modules::run('patient_radiology_order/get_previous', $PID, 'opd_visit/view/' . $opd_visits_info["OPDID"], "HTML"); ?>
            <!-- END Radiology-->

            <!-- TREATMENT-->
            <?php echo Modules::run('treatment_order/get_previous_treatment_list', 'opd', $opd_visits_info["OPDID"], 'opd_visit/view/' . $opd_visits_info["OPDID"], "HTML"); ?>
            <!-- END TREATMENT-->

            <!-- Injection-->
            <?php echo Modules::run('patient_injection/get_previous_injection', $PID, 'opd_visit/view/' . $opd_visits_info["OPDID"], "HTML"); ?>
            <!-- END Injection-->

            <!-- PRESCRIPTION-->
            <?php
            echo Modules::run('patient_prescription/get_previous_prescription', 'opd', $opd_visits_info["OPDID"], 'opd_visit/view/' . $opd_visits_info["OPDID"], "HTML");
            ?>

            <!-- NOTES-->
            <?php echo Modules::run('patient_note/get_previous_notes_list', $PID, 'opd', $opd_visits_info["OPDID"], 'opd_visit/view/' . $opd_visits_info["OPDID"], "HTML"); ?>
            <!-- END NOTES-->

            <!-- ALLERGY-->
            <?php echo Modules::run('patient_allergy/get_previous_allergy', $PID, 'opd_visit/view/' . $opd_visits_info["OPDID"], "HTML"); ?>
            <!-- END ALLERGY-->

            <!-- PAST HISTORY-->
            <?php echo Modules::run('patient_history/get_previous_history', $PID, 'opd_visit/view/' . $opd_visits_info["OPDID"], "HTML"); ?>
            <!-- END PAST HISTORY-->

            <!-- EXAMINATION-->
            <?php echo Modules::run('patient_examination/get_previous_exams', $PID, 'opd_visit/view/' . $opd_visits_info["OPDID"], "HTML"); ?>
            <!-- END EXAMINATION-->

        </div>
    </div>
</div>

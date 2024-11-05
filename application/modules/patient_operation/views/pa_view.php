<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/pa', $PA_ID, $PID, $pa_visits_info); //runs the available left menu for preferance ?>
        </div>
        <div class="col-md-10 ">
            <!--Patient Info-->
            <?php echo Modules::run('patient/banner_full', $PID) ?>
            <!--End Patient Info-->

            <!-- PA INFO-->
            <?php echo Modules::run('patient_pathological_anatomy/info', $PA_ID); ?>
            <!-- END PA INFO-->

            <!-- PA INFO-->
            <?php echo Modules::run('pathological_anatomy_order/get_previous_pa', $PID, 'patient_pathological_anatomy/view/' . $pa_visits_info["PA_ID"], "HTML"); ?>
            <!-- END PA INFO-->

            <!-- LAB-->
            <?php echo Modules::run('patient_lab_order/get_previous_lab', $PID, 'patient_pathological_anatomy/view/' . $pa_visits_info["PA_ID"], "HTML"); ?>
            <!-- END LAB-->

            <!-- Radiology-->
            <?php echo Modules::run('patient_radiology_order/get_previous', $PID, 'patient_pathological_anatomy/view/' . $pa_visits_info["PA_ID"], "HTML"); ?>
            <!-- END Radiology-->

            <!-- Injection-->
            <?php echo Modules::run('patient_injection/get_previous_injection', $PID, 'patient_pathological_anatomy/view/' . $pa_visits_info["PA_ID"], "HTML"); ?>
            <!-- END Injection-->

            <!-- ALLERGY-->
            <?php echo Modules::run('patient_allergy/get_previous_allergy', $PID, 'patient_pathological_anatomy/view/' . $pa_visits_info["PA_ID"], "HTML"); ?>
            <!-- END ALLERGY-->

            <!-- PAST HISTORY-->
            <?php echo Modules::run('patient_history/get_previous_history', $PID, 'patient_pathological_anatomy/view/' . $pa_visits_info["PA_ID"], "HTML"); ?>
            <!-- END PAST HISTORY-->

            <!-- EXAMINATION-->
            <?php echo Modules::run('patient_examination/get_previous_exams', $PID, 'patient_pathological_anatomy/view/' . $pa_visits_info["PA_ID"], "HTML"); ?>
            <!-- END EXAMINATION-->


        </div>
    </div>
</div>

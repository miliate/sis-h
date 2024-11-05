<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/patient', $pid, $ref_id); ?>
        </div>
        <div class="col-md-8 col-md-offset-1">
            <?php
            echo Modules::run('patient/banner', $pid);
            ?>
            <?php
            $form_generator = new MY_Form(lang('Anamnese'));
            $form_generator->form_open_current_url();
            include dirname(__FILE__) . '/../../patient_diagnosis/views/chronic_disease.php';
            ?>

            <?php
            $form_generator->text_area('*' . lang('Main Complaint'), 'main_complaint', $default_main_complaint);
            $form_generator->text_area('*' . lang('Current Illness History'), 'current_illness_history', $default_current_illness_history);
            ?>

            <hr>

            <h4><?php echo lang('Past Medical History'); ?></h4>
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-12">
                    <!-- <label for="chronic_diseases" class="control-label"><?php echo lang('Chronic Diseases'); ?></label> -->
                    <?php
                    $form_generator->input(lang('Chronic Diseases'), 'chronic_diseases', '', '');
                    ?>
                </div>
            </div>

            <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-12">
                    <?php $form_generator->input(lang('Medication Allergy'), 'medication_allergy', '', ''); ?>
                </div>
            </div>

            <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-12">
                    <?php $form_generator->input(lang('Food Allergy'), 'food_allergy', '', ''); ?>
                </div>
            </div>

            <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-12">
                    <?php $form_generator->input(lang('Other Allergies'), 'other_allergies', '', ''); ?>
                </div>
            </div>

            <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-12">
                    <?php $form_generator->input(lang('Previous Diseases'), 'previous_diseases', '', ''); ?>
                </div>
            </div>

            <hr>

            <h5><strong><?php echo lang('Menstrual History'); ?></strong></h5>
            <br>
            <div class="form-group">
                <div class="col-sm-6">
                    <label class="col-sm-4 control-label" for="menarche"><?php echo lang('Menarche'); ?></label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control input-sm" name="menarche" id="menarche" placeholder="<?php echo lang('Age'); ?>" min="0" max="100" value="<?php echo $default_menarche; ?>">
                    </div>
                </div>

                <div class="col-sm-6">
                    <label class="col-sm-4 control-label" for="menopause"><?php echo lang('Menopause'); ?></label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control input-sm" name="menopause" id="menopause" placeholder="<?php echo lang('Age'); ?>" min="0" max="100" value="<?php echo $default_menopause; ?>">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6">
                    <label class="col-sm-4 control-label" for="second_menstruation_date"><?php echo lang('Date of Second Last Menstruation'); ?></label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control input-sm" name="second_menstruation_date" id="second_menstruation_date" placeholder="Insert Second Menstruation Date" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d', strtotime('-150 years')); ?>" value="<?php echo $default_second_menstruation_date; ?>">
                    </div>
                </div>

                <div class="col-sm-6">
                    <label class="col-sm-4 control-label" for="date_last_menstruation"><?php echo lang('Date of Last Menstruation'); ?></label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control input-sm" name="date_last_menstruation" id="date_last_menstruation" placeholder="Insert Date of Last Menstruation" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d', strtotime('-150 years')); ?>" value="<?php echo $default_date_last_menstruation; ?>">
                    </div>
                </div>
            </div>

            <?php
            $form_generator->input(lang('Cycle Periodicity'), 'cycle_periodicity', $default_cycle_periodicity);
            $form_generator->input(lang('Flow Characteristics'), 'flow_characteristics', $default_flow_characteristics);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>
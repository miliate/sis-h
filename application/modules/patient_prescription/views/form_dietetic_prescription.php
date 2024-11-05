<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/admission', $visits_info, $ref_id); ?>
        </div>

        <div class="col-md-10 ">

        <?php

        echo Modules::run('patient/banner', $pid);

        $form_generator = new MY_Form(lang('Dietetic Prescription'));
        $form_generator->form_open_current_url();

        ?>

        <div class="row">
            <div class="col-md-10">
                <?php $form_generator->dropdown(lang('Prescription'), 'prescription', $dietary_list, ''); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <?php $form_generator->input(lang('Remarks'), 'remarks', '', ''); ?>
            </div>
            <div class="col-md-2 text-center">
                <button type="submit" class="btn btn-primary w-100" id="adddiagnosis"><?php echo lang('Add'); ?></button>
            </div>
        </div>

        <br>

        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table class="table table-bordered mt-3" id="selectedPrescriptionsTable">
                <thead class="thead-light">
                    <tr>
                        <th scope="col"><?php echo lang('Date'); ?></th>
                        <th scope="col"><?php echo lang('Prescription'); ?></th>
                        <th scope="col"><?php echo lang('Remarks'); ?></th>
                        <th scope="col"><?php echo lang('Prescriber'); ?></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($dietetic_prescriptions as $item) {
                        echo '<tr>';
                            echo '<td>' . $item['CreateDate'] . '</td>';
                            echo '<td>' . $item['Prescription'] . '</td>';
                            echo '<td>' . $item['Remarks'] . '</td>';
                            echo '<td>' . $item['CreateUser'] . '</td>';
                            echo '<td id=' . $item['ID'] . '><button value=' . $item['ID'] . ' type="button" class="btn btn-danger btn-sm remove-row">' . lang('Suspend') . '</button></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php $form_generator->form_close(); ?>

    </div>
</div>

<script>
    $('document').ready(function() {
        $('.remove-row').on('click', function() {
            var prescriptionId = $(this).val();
            if (prescriptionId) {
                $.ajax({
                    url: '<?php echo site_url("patient_prescription/void_prescription_no_drugs"); ?>' + '/' + prescriptionId, 
                    type: 'GET', 
                    success: function(response) {
                        $('#selectedPrescriptionsTable tr').find('td[id="' + prescriptionId + '"]').closest('tr').remove();
                    }
                });
            }
        });
    });
</script>
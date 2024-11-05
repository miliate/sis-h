<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/admission', $visits_info, $ref_id); ?>
        </div>

        <div class="col-md-10 ">

        <?php

        echo Modules::run('patient/banner', $pid);

        $form_generator = new MY_Form(lang('Therapeutic Prescription'));
        $form_generator->form_open_current_url();

        ?>

        <div id="message1" class="alert alert-danger" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <span id="message_text"><?php echo lang('Prescription must have at least 4 characters'); ?></span>
        </div> 

        <div class="row">
            <div class="col-md-10">
                <?php $form_generator->input(lang('Prescription'), 'prescription', $default_prescription, ''); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <?php $form_generator->input(lang('Remarks'), 'remarks', '', ''); ?>
            </div>
            <div class="col-md-2 text-center">
                <button type="submit" class="btn btn-primary w-100"><?php echo lang('Add'); ?></button>
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

        $('form').on('submit', function(e) {
            var prescription = $('input[name="prescription"]').val(); 
            if (prescription.length < 4) {
                e.preventDefault(); 
                $('#message1').show(); 
            } else {
                $('#message1').hide(); 
            }
        });
    });
</script>
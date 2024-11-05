<div class="container col-md-12" style="padding-bottom: 16px">
    <h2><?php echo lang('Update Treatment Order'); ?></h2>
    <style>
        .custom-table th {
            text-align: left;
            padding-right: 0px;
            width: 200px;
        }
        .custom-table td {
            text-align: left;
        }
        .remarks-nurse-textarea {
            width: 50px;
        }
        strong {
            font-size: 15px;
        }
    </style>
    <?php
    echo Modules::run('patient/banner', $pid);

    $form_generator = new MY_Form(lang('Update Treatment Order'));
    $form_generator->form_open_current_url();
    ?>

    <table class="table custom-table">
        <thead>
            <tr>
                <th><strong><?php echo lang('Treatment'); ?></strong></th>
                <th><strong><?php echo lang('Remarks'); ?></strong></th>
                <th><strong><?php echo lang('Remarks Nurse'); ?></strong></th>
                <th><strong><?php echo lang('Status'); ?></strong></th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($treatments as $treatment): ?>
        <tr>
            <td><label><?php echo $treatment->Treatment; ?></label></td>
            <td><label><?php echo $treatment->Remarks; ?></label></td>
            <td>
                <?php 
                $readonly = (isset($treatment->Status) && $treatment->Status === 'Done') ? 'readonly' : '';
                $form_generator->text_area('', 'remarks_nurse[' . $treatment->OrderTreatmentID . ']', isset($treatment->Remarks_Nurse) ? $treatment->Remarks_Nurse : '','', $readonly); 
                ?>
            </td>
            <td>
            <?php
                $createDate = strtotime($treatment->CreateDate);
                $currentTime = time();
                $timeDifference = ($currentTime - $createDate) / 3600; // Convert difference to hours
                
                $disabled = ($timeDifference > 24) ? 'disabled' : '';
                $form_generator->dropdown('', 'status[' . $treatment->OrderTreatmentID . ']', array('Pending' => 'Pending', 'Done' => 'Done'), isset($treatment->Status) ? $treatment->Status : '', $disabled); 
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

    </table>

    <div class="row">
        <div class="col-md-12">
            <?php $form_generator->button_submit_reset(); ?>
            <?php $form_generator->form_close(); ?>
        </div>
    </div>

    <?php echo Modules::run('template/footer'); ?>
</div>

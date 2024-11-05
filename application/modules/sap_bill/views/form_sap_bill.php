`<div class="container-fluid">
<?php $pid=103345; ?>
<?php echo Modules::run('patient/banner',$default_PID); ?>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php

$paymode_options = array(
    'Cash' => 'Numerário',
    'POS' => 'POS',
    'Cheque'=>'Cheque',
);


            $form_generator = new MY_Form('Billing');
            $form_generator->form_open_current_url();
         //   $form_generator->input(lang('Department'), 'department', lang($default_department), '', 'readonly');
            $form_generator->input('*Bill Number', 'bill_number', $default_BillNumber, 'billNumber', 'readonly');
            //$form_generator->dropdown('*Pay Mode', 'pay_mode',$default_PayMode,'Pay Mode');
            $form_generator->dropdown('*Pay Mode', 'pay_mode', $paymode_options, $default_PayMode);
            $form_generator->input('*Total', 'total', $default_Total, 'Total');
            $form_generator->input('*Total Paid', 'total_paid', $default_TotalPaid, 'Total Paid');
            $form_generator->text_area('*Remarks', 'Remarks', $default_Remarks, 'Remarks');

            $form_generator->dropdown('Active', 'Active', array('1' => 'Yes', '0' => 'No'), $default_Active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

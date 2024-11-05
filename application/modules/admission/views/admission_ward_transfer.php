<div class="container-fluid">
    <div class="row">
    <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/admission', $admission, $adm_id); ?>
        </div>
        <div class="col-md-8">
            <?php
            echo Modules::run('admission/info', $adm_id);
            ?>
            <?php
            $form_generator = new MY_Form('Ward Transfer');
            $form_generator->form_open_current_url();

            $form_generator->dropdown('*Transfer from', 'transfer_from', $from_option, '', 'readonly');
            $form_generator->dropdown('*Transfer to', 'transfer_to', $ward_options, '');

            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>


</div>
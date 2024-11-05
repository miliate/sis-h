<?php echo validation_errors(); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form('Top Menu');
            $form_generator->form_open_current_url();
            $form_generator->input('*Name', 'name', '', 'Name');
            $form_generator->checkboxes('User Group', 'user_groups', array(
                'id1' => 'value1',
                'id2' => 'value2'
            ), array());
            $form_generator->button_submit_reset(1);
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>
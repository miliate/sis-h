<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/user_config'); //runs the available left menu for preferance ?>
        </div>
        <div class="col-md-10">
            <?php
            $form_generator = new MY_Form('Password');
            $form_generator->form_open_current_url();
            $form_generator->password('*' . lang('Old Password'), 'password', '', '');
            $form_generator->password('*' . lang('New Password'), 'new_password', '', '');
            $form_generator->password('*' . lang('Repeat New Password'), 'password_check', '', '');
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>
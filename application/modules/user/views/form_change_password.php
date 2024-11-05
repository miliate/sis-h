<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_name = "User";
            $form_generator = new MY_Form($form_name . ' Password Management');
            $form_generator->form_open_current_url();
            if (isset($id) && ($id > 0))
                $form_generator->input('*' . $this->lang->line('form_label_username'), 'username', $default_username, 'User name', 'disabled');
            $form_generator->password('*New Password' , 'new_password', '', 'New Password');
            $form_generator->password('Confirmation', 'password_check', '', 'New Password Confirmation');


            ?>
            <?php
            $form_generator->button_submit_reset($id);
            $form_generator->form_close();
            ?>
        </div>
    </div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php

            if ($this->session->flashdata('error')) {
                echo '<div id="message1" class="alert alert-danger">';
                echo '    <button type="button" class="close" data-dismiss="alert">&times;</button>';
                echo '    <span id="message_text">' . $this->session->flashdata('error') . '</span>';
                echo '</div>';
            }

            $form_generator = new MY_Form(lang('Room'));
            $form_generator->form_open_current_url();
            $form_generator->dropdown(lang('Ward'), 'WID', array_column($wards, 'Name', 'WID'), $default_WID);
            $form_generator->input('*' . lang('Room Name'), 'Name', $default_Name, lang('Room Name'));
            $form_generator->input(lang('Telephone'), 'Telephone', $default_Telephone, lang('Telephone'));
            $form_generator->dropdown(lang('Active'), 'Active', array('1' => lang('Yes'), '0' => lang('No')), $default_Active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>
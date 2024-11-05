<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/user_config'); //runs the available left menu for preferance ?>
        </div>
        <div class="col-md-10">
            <?php
            echo Modules::run('')
            ?>
            <?php
            $form_generator = new MY_Form(lang('Language'));
            $form_generator->form_open_current_url();

            $form_generator->dropdown(lang('Language'), 'language', array('English' => lang('English'), 'Portuguese' => lang('Portuguese')), $default_language);
            $form_generator->button_submit_reset();
            $form_generator->form_close();

            ?>
        </div>
    </div>
</div>
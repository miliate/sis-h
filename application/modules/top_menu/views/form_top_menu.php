<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form('Top Menu');
            $form_generator->form_open_current_url();
            // Asterisco vermelho para o campo 'Name'
            $form_generator->input('<span style="color:red">*</span>' . lang('Name'), 'name', $default_name, lang('Name'));
            $form_generator->checkboxes(lang('User_Group'), 'user_groups', $user_group_options, $selected_group);
            $form_generator->input(lang('Link'), 'link', $default_link, 'eg: home.php?page=home');
            // Asterisco vermelho para o campo 'Menu Order'
            $form_generator->input('<span style="color:red">*</span>' . lang('Menu Order'), 'menu_order', $default_menu_order, 'eg: 1');
            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);
            $form_generator->button_submit_reset($id);
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

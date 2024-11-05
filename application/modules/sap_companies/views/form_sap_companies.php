`<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form('Empresas');
            $form_generator->form_open_current_url();
            $form_generator->input('*'.lang('Company Name'), 'Name', $default_Name, 'Nome Completo');
            $form_generator->input('*Abreviatura', 'abrev', $default_Abrev, 'Nome Abreviado');
            $form_generator->input('*NUIT', 'registration_number', $default_Mobile, 'NUIT');
            $form_generator->dropdown('*Tipo de Empresa', 'type_id',$dropdown_Type,set_value('type_id',$default_TypeId));
            $form_generator->input('Numero de Entidade', 'mobile_number', $default_Mobile, 'Numero de Entidade');
            $form_generator->text_area('*Endereço', 'address', $default_Address, 'Morada');
            $form_generator->input('Tel./Fax.', 'phone_number', $default_Phone, 'Telelefone Fixo ou Fax');
            $form_generator->input('Cel.', 'mobile_number', $default_Mobile, 'Telemovel');
            $form_generator->input('E-mail', 'mobile_number', $default_Mobile, 'E-mail');
            
            $form_generator->text_area('Observa&ccedil;&otilde;es', 'Remarks', $default_Remarks, 'Mais Observações');
            $form_generator->dropdown(lang('Active'), 'Active', array('1' => lang('Yes'), '0' => lang('No')), $default_Active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            echo Modules::run('patient/banner', $pid);

            $form_generator = new MY_Form('Dados da Empresa Associada');
            $form_generator->form_open_current_url();
            $form_generator->dropdown('Tipo de Cliente', 'company_type_id',$dropdown_company_type_id, $default_company_type_id);
            $form_generator->dropdown('Nome da Empresa', 'company_id',$dropdown_company_id,$default_company_id);
            $form_generator->input('Numero do Contrato', 'default_member_number', $default_member_number, '');
            $form_generator->input('Numero do Cartao', 'default_member_reference', $default_member_reference, '');
            $dependent_dropdown = array(
                '' => '--',
                '0' => 'NAO',
                '1' => 'SIM',
            );
            $form_generator->dropdown('O Membro e Dependente?', 'default_member_is_dependent',$dependent_dropdown , $default_member_is_dependent);
            $relation_dropdown = array(
                '' => '--',
                'Contato' => 'Contrato',
                'Efectivo' => 'Efectivo',
            );
            $form_generator->dropdown('O Membro e Dependente?', 'default_relation_type',$relation_dropdown , $default_relation_type);
            $form_generator->input('Anp de Contrato', 'default_relation_year', $default_relation_year, '');


            $form_generator->text_area(lang('Remarks'), 'Remarks', $default_Remarks, lang('Any Remarks'));
            $form_generator->button_submit_reset($pid);
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

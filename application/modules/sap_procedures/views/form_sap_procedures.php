<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form('Procedures');
            $form_generator->form_open_current_url();
            $form_generator->input('*' . lang('Name'), 'Name', $default_Name, lang('Name'));
            $form_generator->dropdown('*' . lang('Procedure Type'), 'type_id', $dropdown_Type, set_value('type_id', $default_TypeId));
            $form_generator->input('*' . lang('Ref Price'), 'ref_price', $default_RefPrice, 'RefPrice');
            $form_generator->input('*' . lang('Price'), 'price', $default_Price, lang('Price'));
            $form_generator->text_area(lang('Remarks'), 'Remarks', $default_Remarks, lang('Remarks'));
            $form_generator->dropdown(lang('Active'), 'Active', array('1' => lang('Yes'), '0' => lang('No')), $default_Active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>
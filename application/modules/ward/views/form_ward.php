<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form(lang('Ward'));
            $form_generator->form_open_current_url();
            $form_generator->input('*' . lang('Ward Name'), 'Name', $default_Name, lang('Name'));
            $form_generator->input(lang('Ward Type'), 'Type', $default_Type, lang('Type'));
            $form_generator->input(lang('Telephone'), 'Telephone', $default_Telephone, lang('Telephone'));
            $form_generator->input(lang('BedCount'), 'BedCount', $default_BedCount, lang('BedCount'));
            $form_generator->text_area(lang('Remarks'), 'Remarks', $default_Remarks, lang('Remarks'));
            $form_generator->dropdown(lang('Active'), 'Active', array('1' => lang('Yes'), '0' => lang('No')), $default_Active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>
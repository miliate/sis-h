<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 ">
            <?php
            echo Modules::run('leftmenu/patient', $pid, $ref_id); 
            echo Modules::run('leftmenu/form_active_list', $pid); 
            ?>
        </div>
        <div class="col-md-8 col-md-offset-1">
           
        <?php
            echo Modules::run('patient/banner', $pid);
            echo Modules::run('patient/banner_full', $pid);
            ?>
            <?php

            $form_generator = new MY_Form(lang('Childbirth Clinical Form'));
            $form_generator->form_open_current_url();
          //  echo '<div class="col-md-12">';
            $form_generator->input(lang('Status'), 'status', lang('Pending'), '', 'readonly');
            $js = 'onmousedown="onmousedown=$(\'#' . 'entry_time' . '\').datepicker({changeMonth: true,changeYear: true,yearRange: \'c-40:c+40\',dateFormat: \'yy-mm-dd\',maxDate: \'+120D\', minDate: \'+0D\'});"';
            $form_generator->input(lang('VisitDate'), 'entry_time', $default_entry_time, '', $js);
            $form_generator->input('*'. lang('Come From'), 'come_from', $default_comefrom, lang('Come From'));

            $form_generator->dropdown(lang('Hospitalization Reason'), utf8_decode('reason'), $dropdown_reasons,
            set_value('reason',$default_reason));

            $form_generator->input(lang('Complaint / Injury'), 'complaint', $default_complaint, '');

            $form_generator->input('*'. lang('Name'), 'name', $default_name, 'Name of allergy');
            $form_generator->dropdown(lang('Status'), 'status', array('Past' => lang('Past'), 'Current' => lang('Current')), $default_status);
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, lang('Any Remarks'));
            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);
          /* echo '</div>
            <div class="col-md-4">';*/

           // echo '</div>';

            $form_generator->button_submit_reset($id);
            $form_generator->form_close();
            ?>

        </div>
    </div>
</div>


<script type="text/javascript">

  
   

</script>






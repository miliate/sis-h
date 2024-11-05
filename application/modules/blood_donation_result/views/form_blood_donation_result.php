<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
        <?php
            echo Modules::run('patient/banner', $pid);


            //echo '<i>'.$donation_id.'</i>';
            ?>

            <div class="panel panel-info">
               <div id="blood_donation"
                     style='padding: 5px;'><?php echo Modules::run('patient/blood_donation', $pid); ?>
               </div>
                <div id="blood_donation_result"
                     style='padding: 5px;'><?php echo Modules::run('patient/blood_donation_result', $pid); ?>
               </div>
            </div>

          


            <?php

            $form_generator = new MY_Form(lang('Donation Result'));
            $form_generator->form_open_current_url();
            $form_generator->input('Nº do Dador', 'donation_number', $donation_number,'', 'readonly');
            $form_generator->input('Nº do Frasco', 'sample_id', $default_sample_id, 'Número do Frasco');
            $form_generator->input_date(lang('Donation Date'), 'donation_date', $default_donation_date);
            $form_generator->input('HGB', 'hgb', $default_hgb, 'Hemoglobina');
            $form_generator->input('PESO', 'peso', $default_peso, 'Peso, em Kg');
            $form_generator->input('TA', 'ta', $default_ta, 'Tensão Arterial');

            $form_generator->dropdown('Teste de HIV', 'hiv',
                array(
                    'Negativo' => 'Negativo',
                    'Posetivo' => 'Positivo',
                    'Indeterminado' => 'Indeterminado'
                ),
                $default_hiv);

            $form_generator->dropdown('Hepatite B', 'hbv',
                array(
                    'Negativo' => 'Negativo',
                    'Posetivo' => 'Positivo'
                ),
                $default_hbv);

            $form_generator->dropdown('Hepatite C', 'hcv',
                array(
                    'Negativo' => 'Negativo',
                    'Posetivo' => 'Positivo'
                    
                ),
                $default_hcv);
            $form_generator->dropdown('Teste de Sífilis', 'rpr',
                array(
                    'Negativo' => 'Negativo',
                    'Posetivo' => 'Positivo'
                   
                ),
                $default_rpr);

            $js = 'onmousedown="onmousedown=$(\'#' . 'next_donation_date' . '\').datepicker({changeMonth: true,changeYear: true,yearRange: \'c-40:c+40\',dateFormat: \'yy-mm-dd\',maxDate: \'+365D\', minDate: \'+0D\'});"';
            $form_generator->input(lang('Next Donation Date'), 'next_donation_date', $default_next_donation_date,'', $js);
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, '');
            $form_generator->dropdown(lang('Active'), 'active', array('1' => 'Sim', '0' => 'N&atilde;o'), $default_active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">

</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form('Minha Lista de Medicamentos');
            $form_generator->form_open_current_url();
            $form_generator->dropdown('Medicamento', 'drug', $all_drug, $default_drug);
            $form_generator->dropdown('Active', 'active', array('1' => 'Yes', '0' => 'No'), $default_active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#drug").select2();
    });
</script>

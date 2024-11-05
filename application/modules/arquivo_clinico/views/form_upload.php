<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            echo Modules::run('patient/banner', $pid);
            ?>


            <?php
            
            $form_generator = new MY_Form(['enctype'=>'multipart/form-data']);
            
            echo '<input  type="file" multiple="true" name="files[]" class="form-control input-sm" id="files">';
            
            $form_generator->button_submit_reset();
            $form_generator->form_close();



            ?>
        </div>
    </div>
</div>


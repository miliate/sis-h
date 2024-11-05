<div>
    <div class="row">
        <!--        <div class="col-md-2 ">-->
        <!--            --><?php //echo Modules::run('leftmenu/pharmacy'); //runs the available left menu for preferance 
                            ?>
        <!--        </div>-->

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">

                    <?php $form_generator = new MY_Form(lang('patient_prescrition'));

                    $form_generator->get_pharmacy_dispense_menu();

                    ?>
                </div>
                <div id="patient_list">
                    <?php echo $pager; ?>
                </div>
            </div>
        </div>
    </div>
</div>
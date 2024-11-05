<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/arquivo_clinico',  $PID); //runs the available left menu for preferance ?>
        </div>

        <div class="col-md-10">
            <?php // echo Modules::run('patient/banner_full', $PID) ?>

            <div class="col-md-4"> <i class="fa fa-archive fa-xs" aria-hidden="true"> </i> 
            <?= $patient_info["PID"]; ?>
            <?= $patient_info["DateOfBirth"]; ?>
            </div>
            <div class="col-md-8">
             </div>



        </div>
            
 
           

    </div>
</div>

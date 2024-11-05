<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/preference'); //runs the available left menu for preferance ?>
        </div>
        <?php if (isset($pager)) {?>
        <div class="col-md-10 ">
            <?php
            echo "<div id='prefCont'></div>";
            echo $pager; // runs the preferance home module by default
            ?>
        </div>
        <?php } else { ?>
        <div>


        <div class="image-container">
    <img src="<?php echo base_url() ?>images/the_doctor.png" alt="Total Pacientes" width="100px">
    <!-- <span class="notify-badge top"> <?= number_format((float)30, 0, ',', '.'); ?> </span> -->
    <span class="notify-badge bottom">
        <?php  $total_patients = $this->m_patient->get_total_patients(); 
        echo number_format((float)$total_patients, 0, ',', '.'); ?><br>Pacientes</span>
   
</div>


        
        </div>
        <?php }?>

    </div>
</div>
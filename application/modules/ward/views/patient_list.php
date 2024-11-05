<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row">
    <div class="col-md-2 ">
        <div id="left-sidebar1">
            <div class="list-group">
                <a class="list-group-item active">Commands</a>
                <a href="<?php echo base_url().'index.php/ward/search' ?>" class="list-group-item"><span class="glyphicon glyphicon-backward"></span> Back to Ward List</a>
                <a href="<?php echo current_url() ?>" class="list-group-item"><span class="glyphicon glyphicon-refresh"></span> Current Patient List</a>
            </div>
        </div>
    </div>
    <div class="col-md-10 ">
        <div class="panel panel-default">
            <div class="panel-heading"><b><?php echo $ward_info["Name"]; ?> Ward</b></div>
            <div id="patient_list">
                <?php echo $pager; ?>
            </div>
        </div>
    </div>
</div>
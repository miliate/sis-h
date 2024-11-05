<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <table class="table table-striped">
                <tr>
                    <th><?= lang('Diagnosis') ?></th>
                    <td><?php echo ($diagnosis["diagnosis_id"] == null ) ? $diagnosis["diagnosis"] : $diagnosis["name"] ?></td>
                </tr>
                <tr>
                    <th><?= lang('Diagnosis Type') ?></th>
                    <td><?php echo $diagnosis["type"] ?></td>
                </tr>
                <tr>
                    <th><?= lang('Doctor') ?></th>
                    <td><?php echo $diagnosis["doctor"] ?></td>
                </tr>
                <tr>
                    <th><?= lang('Date') ?></th>
                    <td><?php echo $diagnosis["CreateDate"] ?></td>
                </tr>
            
            </div>
        </div>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h6 class="panel-title"><?php echo lang('Request Items'); ?></h6>
    </div>
    <div class="panel-body">
        <!-- Display additional request details -->
        <!-- Example: Display request items -->
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo lang('National Form Code'); ?></th>
                    <th><?php echo lang('Name'); ?></th>
                    <th><?php echo lang('Dosage'); ?></th>
                    <th><?php echo lang('Pharmaceutical Form'); ?></th>
                    <th><?php echo lang('Requested Quantity'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($request_items as $item): ?>
                    <tr>
                        <td><?php echo $item['fnm']; ?></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['dosage']; ?></td>
                        <td><?php echo $item['pharmaceutical_form']; ?></td>
                        <td><?php echo $item['requested_quantity']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="display: flex; justify-content: center;">
            <a href="<?php echo site_url('drug_stock/show_request'); ?>" class="btn btn-warning mx-2"><?php echo lang('Back'); ?></a>
            <a href="<?php echo site_url('drug_stock/edit_request_item/' . $request_id); ?>" class="btn btn-primary mx-2" style="margin: 0 10px;"> <?php echo lang('Edit Request'); ?></a>
        </div>
    </div>
</div>

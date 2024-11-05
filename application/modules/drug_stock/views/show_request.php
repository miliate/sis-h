<div class="panel panel-primary">
    <div class="panel-heading"><?php echo lang('Request') ?></div>
    <div class="panel-body">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo lang('Request Code'); ?></th>
                    <th><?php echo lang('Request Date'); ?></th>
                    <th><?php echo lang('Created By'); ?></th>
                    <th><?php echo lang('Request Type'); ?></th>
                    <th><?php echo lang('Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?php echo $request['id']; ?></td>
                        <td><?php echo $request['request_code']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($request['request_date'])); ?></td>
                        <td><?php echo $request['CreateUserName']; ?></td>
                        <td><?php echo lang(ucfirst($request['request_type'])); ?></td> 
  
                        <td>
                            <a href="<?php echo site_url('drug_stock/view_request_items/' . $request['id']); ?>" class="btn btn-info btn-sm">
                                <i class="fa fa-eye"></i> <?php echo lang('View Items'); ?>
                            </a>
                            <form action="<?php echo site_url('/report/pdf/drugRequest/print/'); ?>" method="post" target="_blank" style="display: inline;">
                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fa fa-print"></i> <?php echo lang('Print'); ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

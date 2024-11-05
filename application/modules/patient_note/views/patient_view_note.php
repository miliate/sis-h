<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
             <table class="table table-striped">
                <tr>
                    <th><?= lang('Doctor Name')  ?></th>
                    <td><?php echo $doctorName; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Remark Type')  ?></th>
                    <td><?php echo $examination->Type; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Doctor Remarks')  ?></th>
                    <td><?php echo $examination->notes; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Exam Date')  ?></th>
                    <td><?php echo $examination->CreateDate; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

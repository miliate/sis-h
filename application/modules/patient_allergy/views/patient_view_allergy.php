<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
             <table class="table table-striped">
                <tr>
                    <th><?= lang('Name of allergy')  ?></th>
                    <td><?php echo !empty($examination->Name) ? $examination->Name : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>

                </tr>
                <tr>
                    <th><?= lang('Allergy Status')  ?></th>
                    <td><?php echo !empty($examination->Status) ? $examination->Status : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>

                </tr>
                <tr>
                    <th><?= lang('Remarks')  ?></th>
                    <td><?php echo !empty($examination->Remarks) ? $examination->Remarks : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>

                </tr>
                <tr>
                    <th><?= lang('Exam Date')  ?></th>
                    <td><?php echo !empty($examination->CreateDate) ? $examination->CreateDate : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

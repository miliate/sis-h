<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            echo Modules::run('patient/banner', $pid);
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo lang('Radiology Test Requested') ?></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <td><b><?php echo lang('Ordered by') ?></b> : <?php echo $default_order_by; ?></td>
                                <td><b><?php echo lang('Created in') ?></b> : <?php echo $default_create_time; ?></td>
                                <td><b>Exam Date</b>:<?php echo $default_exam_date; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <form action="" method="post" role="form">
                    <?php echo validation_errors(); ?>
                    <input type="hidden" name="example" value="aaa">
                    <table class="table">
                        <thead>
                            <tr>
                                <td><b>#</b></td>
                                <td><b><?php echo lang('Name') ?></b></td>
                                <td><b><?php echo lang('Result') ?></b></td>
                                <td><b><?php echo lang('Ref. Value') ?></b></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            foreach ($radiology_order_items as $item) {
                                echo '<tr>';
                                echo '<td>' . $count++ . '</td>';
                                echo '<td>' . $item['Name'] . '</td>';
                                //                            if (!empty($item['Result'])) {
                                //                                echo '<td>' . $item['Result'] . '</td>';
                                //                            } else {
                                echo '<td><textarea type="text" name="result[' . $item['ID'] . ']" class="form-control input-sm">';
                                echo set_value('result[' . $item['ID'] . ']', $item['Result']);
                                //                                if (isset($this->input->post('result')[$item['ID']]))
                                //                                    echo $this->input->post('result')[$item['ID']];
                                echo '</textarea></td>';
                                //                            }
                                echo '<td>' . $item['RefValue'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                            <td colspan="4" align="center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-warning" onclick="window.history.back()">Back</button>
                            </td>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
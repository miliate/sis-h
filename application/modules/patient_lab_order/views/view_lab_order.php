<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php echo Modules::run('patient/banner', $pid); ?>

            <!-- Error -->
            <div id="message1" class="alert alert-danger" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span id="message_text"><?php echo lang('Error canceling exam'); ?></span>
            </div>
            <!-- success -->
            <div id="message2" class="alert alert-success" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span id="message_text"><?php echo lang('Exam successfully canceled'); ?></span>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo lang('Ordered Lab Test') ?></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <td><b><?php echo lang('Priority') ?></b>:<?php echo $default_priority; ?></td>
                                <td><b><?php echo lang('Lab Test Group') ?></b>:<?php echo $default_test_group; ?></td>
                                <td><b><?php echo lang('Created Time') ?></b>:<?php echo $default_create_time; ?></td>
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
                            foreach ($lab_order_items as $item) {
                                echo '<tr>';
                                echo '<td>' . $count++ . '</td>';
                                echo '<td>' . $item['Name'] . '</td>';
                                echo '<td>' . $item['TestResult'] . '</td>';
                                echo '<td>' . $item['RefValue'] . '</td>';
                                if ($item['Status'] == 'Pending') {
                                    echo '<td><button class="btn btn-danger btn_delete_exam" type="button" data-id="' . $item['ID'] . '">' . lang('Cancel') . '</button></td>';
                                }
                                echo '</tr>';
                            }
                            ?>
                            <td colspan="4" align="center">
                                <button type="button" class="btn btn-warning" onclick="window.history.back()">Back</button>
                            </td>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.btn_delete_exam').on('click', function() {
            var itemId = $(this).data('id');
            if (confirm("Tem certeza que deseja cancelar esses exames?")) {
                $.ajax({
                    url: '<?= site_url('patient_lab_order/void_exam') ?>' + '/' + itemId,
                    type: 'POST',
                    data: {
                        id: itemId
                    },
                    success: function(response) {
                        if (response['response'] == 'success') {
                            $('#message2').show();
                            location.reload();
                        } else {
                            $('#message1').show();
                        }
                    }
                });
            }
        });
    });
</script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/print_biopsy', $biopsy_id); //runs the available left menu for preferance ?>
        </div>
        <div class="col-md-8">
            <?php
            echo Modules::run('patient/banner', $pid);
            echo Modules::run('patient_pathological_anatomy/info', $pa_id);
            ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo lang('Biopsy Test Result')?></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <td><b><?php echo lang('Priority')?></b>:<?php echo $default_priority; ?></td>
                            <td><b><?php echo lang('Result Status')?></b>:<?php echo $default_status; ?></td>
                        </tr>
                        <tr>
                            <td><b><?php echo lang('Sample Requested By')?></b>:<?php echo $default_request_by; ?></td>
                            <td><b><?php echo lang('Sample Requested Date')?></b>:<?php echo $default_request_date; ?></td>
                        </tr>
                        <tr>
                            <td><b><?php echo lang('Sample Collected By')?></b>:<?php echo $default_collected_by; ?></td>
                            <td><b><?php echo lang('Sample Collected Date')?></b>:<?php echo $default_collected_date; ?></td>
                        </tr>
                        <tr>
                            <td><b><?php echo lang('Doctor who Requested')?></b>:<?php echo $default_request_doctor; ?></td>
                            <td><b><?php echo lang('Doctor in charge')?></b>:<?php echo $default_doctor_in_charge; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <form action="" method="post" role="form">
                    <?php echo validation_errors(); ?>
                    <input type="hidden" name="example" value="aaa">
                    <table class="table">
                        <tbody>
                            <tr">
                                <td class="right_border"><?php echo lang('Kind of Product to Analyze') ?></td>
                                <td><?php echo $default_kind_of_product; ?></td>
                            </tr>
                            <tr>
                                <td class="right_border"><?php echo lang('Collection Method') ?></td>
                                <td><?php echo $default_collection_method; ?></td>
                            </tr>
                            <tr>
                                <td class="right_border"><?php echo lang('Fixed On') ?></td>
                                <td><?php echo $default_fixed_on; ?></td>
                            </tr>
                            <tr>
                                <td class="right_border"><?php echo lang('Wound Centre') ?></td>
                                <td><?php echo $default_wound_centre; ?></td>
                            </tr>
                            <tr>
                                <td class="right_border"><?php echo lang('Exact place on where the fragment was removed') ?></td>
                                <td><?php echo $default_extracted; ?></td>
                            </tr>
                            <tr>
                                <td class="right_border"><?php echo lang('Do you have previous PA test?') ?></td>
                                <td><?php echo $default_previous_pa; ?></td>
                            </tr>
                            <tr>
                                <td class="right_border"><?php echo lang('If the answer is YES, indicate its previous Sample ID and the Result of Exam') ?></td>
                                <td><?php echo $default_old_result; ?></td>
                            </tr>
                            <tr>
                                <td class="right_border"><?php echo lang('Result for Macroscopic Exam') ?></td>
                                <td><?php echo $default_macroscopic; ?></td>
                            </tr>
                            <tr>
                                <td class="right_border"><?php echo lang('Result for Microscopic Exam') ?></td>
                                <td><?php echo $default_microscopic; ?></td>
                            </tr>
                            <tr>
                                <td class="right_border"><?php echo lang('Result for Pathological Anatomy Diagnosis') ?></td>
                                <td><?php echo $default_pa_diagnosis; ?></td>
                            </tr>
                            <tr>
                                <td class="right_border"><?php echo lang('Topography') ?></td>
                                <td><?php echo $default_topography; ?></td>
                            </tr>
                            <tr>
                                <td class="right_border"><?php echo lang('Morphology') ?></td>
                                <td><?php echo $default_morphology; ?></td>
                            </tr>
                            <tr>
                                <td class="right_border"><?php echo lang('Remarks') ?></td>
                                <td><?php echo $default_remarks; ?></td>
                            </tr>
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
<style>
    .right_border {
        border-right-style: solid;
        border-width: 1px;
    }
</style>
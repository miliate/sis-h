<div class="panel panel-info">
    <div class="panel-heading">
        <?php
        if (Modules::run('permission/check_permission', 'patient', 'edit')) {
            echo '<h4 class="panel-title pull-left"><b>' . lang('Patient Overview') . '</b></h4>';
            echo '<button class="btn btn-warning pull-right btn-sm" onclick="document.location=\'' . site_url('patient/edit') . '/' . $patient_info["PID"] . '/?CONTINUE=patient/view/' . $patient_info["PID"] . '\'">' . lang('Edit') . '</button>';
            echo '<div class="clearfix"></div>';
        } else {
            echo '<b>' . lang('Patient Overview') . '</b>';
        }
        ?>
    </div>
    <div class="table-responsive well" style="padding-top: 0px;padding-bottom: 0px;">
        <table class="table">
            <tbody>
                <tr>
                    <td><?php echo lang('Full Name') ?>:</td>
                    <td>
                        <b>
                            <?php

                            if (($patient_info["Age"]["years"] > 0) && ($patient_info["Age"]["years"] < 15)) {
                                echo "Crian&ccedil;a";
                            } else {
                                echo $patient_info["Personal_Title"];
                            }

                            if (!empty($patient_info["Firstname"])) {
                                echo ' ' . $patient_info["Firstname"];
                            }
                            echo ' ' . $patient_info["Name"];
                            ?>
                        </b>
                    </td>
                    <td><?php echo lang('Patient ID') ?>:</td>
                    <td><?php echo '1110141' . substr($patient_info["CreateDate"], 0, 4) . '<b>' . $patient_info["PID"] ?></b>
                        <?php if ($patient_info["PID2"] > 0) {
                            echo '<span class="label  label-success">11101441' . substr($patient_info["CreateDate"], 0, 4) . $patient_info["PID2"] . '</span>';
                        } else {
                        }; ?> </td>
                </tr>
                <tr>
                    <td><?php echo lang('Gender') ?></td>
                    <td><?php echo $patient_info["Gender"] ?></td>
                    <td><?php echo lang('Civil Status') ?>:</td>
                    <td><?php echo $patient_info["Personal_Civil_Status"] ?></td>
                </tr>
                <tr>
                    <td><?php echo lang('Date of Birth') ?>:</td>
                    <td><?php if ($patient_info["DateOfBirth"] == 0000 - 00 - 00) {
                            echo $patient_info["DateOfBirthReferred"];
                        } else {
                            echo $patient_info["DateOfBirth"];
                        } ?></td>
                    <td><?php echo lang('Age') ?>:</td>
                    <td><?php if ($patient_info["Age"]["years"] > 0) {
                            echo $patient_info["Age"]["years"] . " "  . lang('Year') . " &nbsp;";
                        }
                        echo $patient_info["Age"]["months"] . " " . lang('Month') . " e&nbsp;";
                        echo $patient_info["Age"]["days"] . " " . lang('Day') . "&nbsp;"; ?>
                        <?php
                        if ($patient_info["Age"]["years"] >= 60) {
                            echo "<i class='fa fa-wheelchair'></i> <div class='label label-sm label-danger'> " . lang('elderly') . "</div>";
                        } elseif ($patient_info["Age"]["years"] <= 14) {
                            echo "<i class='fa fa-child'></i> <div class='label label-sm label-danger'>" . lang('Minor') . "</div>";
                        }
                        ?>

                    </td>
                </tr>
                <tr>
                    <td><?php echo lang('Profession') ?>:</td>
                    <td><?php echo $patient_info['Profession'] ?></td>
                    <td><?php echo lang('Working place') ?>:</td>
                    <td><?php echo $patient_info['WorkingPlace'] ?></td>
                </tr>
                <tr>
                    <td><?php echo lang('Father\'s name') ?>:</td>
                    <td><?php echo $patient_info['FatherName'] ?></td>
                    <td><?php echo lang('Mother\'s name') ?>:</td>
                    <td><?php echo $patient_info['MotherName'] ?></td>
                </tr>
                <tr>
                    <td><?php echo lang('Remarks') ?>:</td>
                    <td><?php echo $patient_info['Remarks'] ?></td>
                    <td><?php echo lang('Address') ?>:</td>
                    <td><?php echo $patient_info['Address_Street'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
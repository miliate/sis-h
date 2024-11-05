<?php

if ((isset($patient_treatments)) && (!empty($patient_treatments))) {

    $cares = array(
        'Care' => 'Nursing Cares',
        'Procedure' => 'Nursing Procedures',
        'Treatment' => 'Treatment'
    );

?>
    <div class="row" style="padding-left: 20px;">
        <div class="col-md-10">
            <p class="h4"><?= lang($cares[$type]) ?></p>

        </div>
        <div class="col-md-2" style="padding: 8px;">
            <?php echo '<a class = "btn btn-success btn-xs" href="' . site_url("treatment_order/nursing_care/" . $ref_id . "/" . $type) . '">' . lang('Add') . '</a>'; ?>
        </div>
    </div>
    <div class="col-md-12">
        <table class=" table table-striped">
            <thead>
                <tr>
                    <th><?= lang('Date') ?></th>
                    <th><?= lang('Service') ?></th>
                    <th><?= lang('Name') ?></th>
                    <th><?= lang('Remarks Nurse') ?></th>
                    <th><?= lang('Status') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($patient_treatments as $treatment) {
                    echo "<tr>";
                    echo "<td>"
                        . $formatted_date = date('d-m-Y H:i', strtotime($treatment['CreateDate']));
                    "</td>";
                    echo "<td>" . $treatment['RefType'] . "</td>";
                    echo "<td>" . $treatment['Treatment'] . "</td>";
                    echo "<td>" . $treatment['Remarks_Nurse'] . "</td>";

                    echo "<td>" . lang($treatment['Status']) . "</td>";
                    echo "</tr>";
                }
                ?>
                </tr>

            </tbody>


        </table>
    </div>








<?php


}

?>
<?php

if ((isset($patient_notes)) && (!empty($patient_notes))) {

    $cares = array(
        'Care' => 'Nursing Cares',
        'Procedure' => 'Nursing Procedures',
        'Treatment' => 'Treatment'
    );

?>
    <div class="row" style="padding-left: 20px;">
        <div class="col-md-8">
            <p class="h4"><?= lang('Nursing Notes') ?></p>

        </div>
        <div class="col-md-3 " style="padding: 8px;">
            <?php echo '<a class = "btn btn-success btn-xs pull-right" href="' . site_url("patient_note/add_nurse_note/" . $pid . "/" . $ref_id) . '">' . lang('Add') . '</a>'; ?>
        </div>
    </div>
    <div class="col-md-12">
        <table class=" table table-striped">
            <thead>
                <tr>
                    <th><?= lang('Date') ?></th>
                    <th><?= lang('Note') ?></th>
                    <th><?= lang('Clinitian') ?></th>
                    <th></th>

                </tr>
            </thead>


            <tbody>


                <?php

                foreach ($patient_notes as $note) {
                    echo "<tr>";

                    echo "<td>"
                        . $formatted_date = date('d-m-Y H:i', strtotime($note['CreateDate']));
                    "</td>";
                    echo "<td>" . $note['notes'] . "</td>";
                    echo "<td>" . lang($note['user_role']) . "</td>";
                    echo "<td>" . $note['UserName'] . "</td>";

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
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th><?= lang('Weight in KG') ?></th>
                    <td><?php echo !empty($examination->Weight) ? $examination->Weight : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Height in M') ?></th>
                    <td><?php echo !empty($examination->Height) ? $examination->Height : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('sys BP') ?></th>
                    <td><?php echo !empty($examination->sys_BP) ? $examination->sys_BP : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('diast BP') ?></th>
                    <td><?php echo !empty($examination->diast_BP) ? $examination->diast_BP : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Temperature in *C') ?></th>
                    <td><?php echo !empty($examination->Temperature) ? $examination->Temperature : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Heart rate') ?></th>
                    <td><?php echo !empty($examination->heart_rate) ? $examination->heart_rate : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Respiratory frequency') ?></th>
                    <td><?php echo !empty($examination->respiratory_frequency) ? $examination->respiratory_frequency : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('General Status') ?></th>
                    <td><?php echo !empty($examination->general_status) ? $examination->general_status : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Abdomen Examination') ?></th>
                    <td><?php echo !empty($examination->abdomen) ? $examination->abdomen : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Neurological Examination') ?></th>
                    <td><?php echo !empty($examination->neurological_exams) ? $examination->neurological_exams : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Biotype') ?></th>
                    <td><?php echo !empty($examination->Biotype) ? $examination->Biotype : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Skin') ?></th>
                    <td><?php echo !empty($examination->Skin) ? $examination->Skin : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Mucous') ?></th>
                    <td><?php echo !empty($examination->Mucous) ? $examination->Mucous : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Body hair') ?></th>
                    <td><?php echo !empty($examination->BodyHair) ? $examination->BodyHair : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Nails') ?></th>
                    <td><?php echo !empty($examination->Nails) ? $examination->Nails : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Skull') ?></th>
                    <td><?php echo !empty($examination->Skull) ? $examination->Skull : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Hair') ?></th>
                    <td><?php echo !empty($examination->Hair) ? $examination->Hair : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Paranasal Sinuses') ?></th>
                    <td><?php echo !empty($examination->ParanasalSinuses) ? $examination->ParanasalSinuses : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Eyes') ?></th>
                    <td><?php echo !empty($examination->Eyes) ? $examination->Eyes : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Ears') ?></th>
                    <td><?php echo !empty($examination->Ears) ? $examination->Ears : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Nose') ?></th>
                    <td><?php echo !empty($examination->Nose) ? $examination->Nose : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Mouth') ?></th>
                    <td><?php echo !empty($examination->Mouth) ? $examination->Mouth : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Neck') ?></th>
                    <td><?php echo !empty($examination->Neck) ? $examination->Neck : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Thorax') ?></th>
                    <td><?php echo !empty($examination->Thorax) ? $examination->Thorax : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Respiratory Examination') ?></th>
                    <td><?php echo !empty($examination->RespiratoryExam) ? $examination->RespiratoryExam : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Cardiovascular examination') ?></th>
                    <td><?php echo !empty($examination->CardiovascularExam) ? $examination->CardiovascularExam : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Lower limbs') ?></th>
                    <td><?php echo !empty($examination->LowerLimbs) ? $examination->LowerLimbs : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('IMC') ?></th>
                    <td><?php echo !empty($examination->Imc) ? $examination->Imc : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('pulse') ?></th>
                    <td><?php echo !empty($examination->Pulse) ? $examination->Pulse : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>

                <tr>
                    <th><?= lang('Pulse Characteristics') ?></th>
                    <td><?php echo !empty($examination->pulse) ? $examination->PulseCharacteristics : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Pulse Value') ?></th>
                    <td><?php echo !empty($examination->pulse) ? $examination->PulseValue : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>

                <tr>
                    <th><?= lang('Motor Response') ?></th>
                    <td><?php echo !empty($examination->MotorResponse) ? $examination->MotorResponse : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Verbal Response') ?></th>
                    <td><?php echo !empty($examination->VerbalResponse) ? $examination->VerbalResponse : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Eye Opening') ?></th>
                    <td><?php echo !empty($examination->EyeOpening) ? $examination->EyeOpening : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Remarks') ?></th>
                    <td><?php echo !empty($examination->remarks) ? $examination->remarks : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Exam Date') ?></th>
                    <td><?php echo !empty($examination->ExamDate) ? $examination->ExamDate : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
            </table>

            </div>
        </div>
   

<?php     
$this->load->model('m_icd10');
$this->load->model('m_who_drug');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th><?= lang('Current Illness History') ?></th>
                    <td><?php echo !empty($examination->HistoryOfComplaint) ? $examination->HistoryOfComplaint : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('General Complaints') ?></th>
                    <td><?php echo !empty($examination->GeneralComplaints) ? $examination->GeneralComplaints : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Diet History') ?></th>
                    <td><?php echo !empty($examination->DietHistory) ? $examination->DietHistory : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                <tr>
                    <th><?= lang('Gastrointestinal') ?></th>
                    <td><?php echo !empty($examination->Gastrointestinal) ? $examination->Gastrointestinal : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Genitourinary') ?></th>
                    <td><?php echo !empty($examination->Genitourinary) ? $examination->Genitourinary : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Nervous System') ?></th>
                    <td><?php echo !empty($examination->NervousSystem) ? $examination->NervousSystem : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Hematolymphopoietic System') ?></th>
                    <td><?php echo !empty($examination->HematolymphopoieticSystem) ? $examination->HematolymphopoieticSystem : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Osteo-muscular-System') ?></th>
                    <td><?php echo !empty($examination->OsteoMioArticular) ? $examination->OsteoMioArticular : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Endocrine System') ?></th>
                    <td><?php echo !empty($examination->EndocrineSystem) ? $examination->EndocrineSystem : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Respiratory and Cardiovascular') ?></th>
                    <td><?php echo !empty($examination->RespiratoryCardiovascular) ? $examination->RespiratoryCardiovascular : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Direct Diagnosis') ?></th>
                    <td><?php 
                    $diagnosis_id = $examination->ChronicDiseases; 
                    $diagnosis_name = $this->m_icd10->get_name_by_code($diagnosis_id);
                    echo !empty($diagnosis_name) ? $diagnosis_name : '<span style="color: red;">' . lang('No Records') . '</span>';
                    ?></td>
                </tr>
                <tr>
                    <th><?= lang('Previous Diseases') ?></th>
                    <td><?php echo !empty($examination->PreviousDiseases) ? $examination->PreviousDiseases : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Allergy to Medication') ?></th>
                    <td><?php 
                    $drug_id = $examination->AllergyMedication;
                    $drug = $this->m_who_drug->get_drug_by_wd_id($drug_id);
                    if ($drug) {
                        echo $drug->fnm . ' ' . $drug->name . ' ' . $drug->pharmaceutical_form . ' ' . $drug->dosage . ' ' . $drug->presentation; 
                    } else {
                        echo '<span style="color: red;">' . lang('No Records') . '</span>';
                    }
                    ?></td>
                </tr>
                <tr>
                    <th><?= lang('Other Allergies') ?></th>
                    <td><?php echo !empty($examination->OtherAllergies) ? $examination->OtherAllergies : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Allergy to Food') ?></th>
                    <td><?php echo !empty($examination->AllergyFood) ? $examination->AllergyFood : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Alcohol Habits') ?></th>
                    <td><?php echo !empty($examination->AlcoholHabits) ? $examination->AlcoholHabits : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Smoking Habits') ?></th>
                    <td><?php echo !empty($examination->SmokingHabits) ? $examination->SmokingHabits : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Menarche') ?></th>
                    <td><?php echo !empty($examination->Menarche) ? $examination->Menarche : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Menopause') ?></th>
                    <td><?php echo !empty($examination->Menopause) ? $examination->Menopause : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Date of Last Menstruation') ?></th>
                    <td><?php echo !empty($examination->DateLastMenstruation) ? $examination->DateLastMenstruation : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Date of Second Last Menstruation') ?></th>
                    <td><?php echo !empty($examination->SecondMenstruationDate) ? $examination->SecondMenstruationDate : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Flow Characteristics') ?></th>
                    <td><?php echo !empty($examination->FlowCharacteristics) ? $examination->FlowCharacteristics : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Cycle Periodicity') ?></th>
                    <td><?php echo !empty($examination->CyclePeriodicity) ? $examination->CyclePeriodicity : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Doctor Name') ?></th>
                    <td><?php echo !empty($examination->Doctor) ? $examination->Doctor : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
                <tr>
                    <th><?= lang('Exam Date') ?></th>
                    <td><?php echo !empty($examination->CreateDate) ? $examination->CreateDate : '<span style="color: red;">' . lang('No Records') . '</span>'; ?></td>
                </tr>
            </table>

            </div>
        </div>
    </div>
</div>

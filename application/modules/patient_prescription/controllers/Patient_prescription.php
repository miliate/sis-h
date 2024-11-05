<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 30-Oct-15
 * Time: 3:06 PM
 */
class Patient_Prescription extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admission');
        $this->load->model('m_opd_visit');
        $this->load->model('m_emergency_admission');
        $this->load->model('m_patient_prescription');
        $this->load->model('m_patient_prescription_have_drug');
        $this->load->model('m_who_drug_count');
        $this->load->model('m_patient_medication');
        $this->load->model('m_patient_medication_have_drug');
        $this->load->model('m_patient');
        $this->load->model('m_taken_drugs');
        $this->load->model('m_ward');
        $this->load->model('m_user');
        $this->load->model('m_ward_rooms');
        $this->load->model('m_ward_beds');
        $this->load->model('m_user');
        $this->load->model('m_prescription_no_drugs');
        $this->load->model('m_dietary_list');
        $this->load_form_language();
    }

    public function check_pass2($pass2)
    {
        return true;
        //        require 'application/config/database.php';
        //        if ($pass2 != $db['default']['password_2'])
        //        {
        //            $this->form_validation->set_message('check_pass2', 'The password 2 you supplied does not match your existing password 2.');
        //            return FALSE;
        //        }
        //        else {
        //            return TRUE;
        //        }
    }

    private function get_visit_info($ref_type, $ref_id)
    {
        $visitData = array();
        switch (strtolower($ref_type)) {
            case 'opd':
                $visitData["visits_info"] = $this->m_opd_visit->get($ref_id);
                break;
            case 'emr':
                $visitData["visits_info"] = $this->m_emergency_admission->get($ref_id);
                $visitData["visits_info_array"] = $this->m_emergency_admission->get_many_by(array('EMRID' => $ref_id));
                break;
            case 'adm':
                $visitData["visits_info"] = $this->m_admission->get($ref_id);
                break;
        }


        return $visitData;
    }

    public function prescribe($ref_type, $ref_id, $visit_info = Null)
    {
        $data['ref_type'] = strtoupper($ref_type);
        $data['ref_id'] = $ref_id;
        $data['visit_info'] = $visit_info;
        $data['visits_info'] = $this->get_visit_info($ref_type, $ref_id)['visits_info'];
        //      $data['visits_info_array'] = $this->get_visit_info($ref_type, $ref_id)['visits_info_array'];
        $data["PID"] = $data["visits_info"]->PID;
        $data["visits_name"] = $this->m_patient->get($data["PID"]);
        $data["Name"] = $data["visits_name"]->Firstname . " " . $data["visits_name"]->Name;

        //        $this->form_validation->set_rules('password2', 'Password 2', 'trim|required|callback_check_pass2');
        $this->form_validation->set_rules('drug_id_selected[]', 'Drug', 'xss_clean|required');
        // $this->form_validation->set_rules('pharmaceutical_form_id_selected[]', 'Forma Farmaceutica', 'xss_clean|required');
        $this->form_validation->set_rules('frequency_id_selected[]', 'Frequency', 'xss_clean|required');
        $this->form_validation->set_rules('route_administration_id_selected[]', 'Via de Administracao', 'xss_clean|required');
        // $this->form_validation->set_rules('patient_type_display', 'Categoria do paciente', 'xss_clean|required');
        $this->form_validation->set_rules('input_dose_value[]', 'Dose', 'xss_clean|required');
        $this->form_validation->set_rules('input_dose_total_value[]', 'Total de Dose', 'xss_clean|required');
        $this->form_validation->set_rules('input_time_total_value[]', 'Tempo Total (dias)', 'xss_clean|required');
        $this->form_validation->set_rules('patient_type_select[]', 'Categoria do Paciente ', 'xss_clean');
        //      $this->form_validation->set_rules('order_confirm_password', 'Order Password', 'xss_clean|callback_confirm_password_check');

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            foreach ($this->input->post('drug_id_selected') as $key => $value) {
                $drug_id_selected[$key] = $value;
            }

            foreach ($this->input->post('route_administration_id_selected') as $key => $value) {
                $route_administration_id_selected[$key] = $value;
            }

            foreach ($this->input->post('input_dose_value') as $key => $value) {
                $input_dose_value[$key] = $value;
            }

            foreach ($this->input->post('frequency_id_selected') as $key => $value) {
                $frequency_id_selected[$key] = $value;
            }

            foreach ($this->input->post('input_time_total_value') as $key => $value) {
                $input_time_total_value[$key] = $value;
            }

            foreach ($this->input->post('input_dose_total_value') as $key => $value) {
                $input_dose_total_value[$key] = $value;
            }
            // foreach ($this->input->post('period_id_selected') as $key => $value) {
            //     $period_id_selected[$key] = $value;
            // }
            $patient_prescription = array(
                'PID' => $data["PID"],
                'RefType' => strtoupper($ref_type),
                'RefID' => $ref_id,
                'Status' => 'Pending',
                'Patient_type' => $this->input->post('patient_type_select'),
            );

            $patient_medication_prescription = array(
                'PID' => $data["PID"],
                'RefType' => strtoupper($ref_type),
                'RefID' => $ref_id,
                'Status' => 'Pending',
            );
            $patient_prescription_id = $this->m_patient_prescription->insert($patient_prescription);
            $patient_medication_id = $this->m_patient_medication->insert($patient_medication_prescription);
            $drug_list = array();
            $order = 1;
            foreach ($this->input->post('drug_id_selected') as $index => $drug) {
                $drug_order = array();
                $drug_order['PID'] = $data['PID'];
                $drug_order['PrescriptionID'] = $patient_prescription_id;
                $drug_order['Order'] = $order++;
                $drug_order['DrugID'] = $drug_id_selected[$index]; //The right way is not working in Ubuntu $this->input->post('drug_id_selected')[$index];
                $drug_order['Dose'] = $input_dose_value[$index];

                $drug_order['RouteAdministration'] = $route_administration_id_selected[$index];
                $drug_order['FrequencyID'] = $frequency_id_selected[$index]; //$this->input->post('frequency_id_selected')[$index];

                $drug_order['TimeTotal'] = $input_time_total_value[$index];
                $drug_order['DoseTotal'] = $input_dose_total_value[$index];
                array_push($drug_list, $drug_order);
            }
            foreach ($drug_list as $drug_order) {
                $this->m_patient_prescription_have_drug->insert($drug_order);
            }

            $drug_list = array();
            $order = 1;
            foreach ($this->input->post('drug_id_selected') as $index => $drug) {
                $drug_order = array();
                $drug_order['PID'] = $data['PID'];
                $drug_order['MedicationID'] = $patient_medication_id;
                $drug_order['Order'] = $order++;
                $drug_order['DrugID'] = $drug_id_selected[$index]; //The right way is not working in Ubuntu $this->input->post('drug_id_selected')[$index];
                $drug_order['Dose'] = $input_dose_value[$index];

                /*$drug_order['RouteAdministration'] = $route_administration_id_selected[$index];
                $drug_order['FrequencyID'] = $frequency_id_selected[$index];
                $drug_order['TimeTotal'] = $input_time_total_value[$index];
                $drug_order['DoseTotal'] = $input_dose_total_value[$index];*/
                array_push($drug_list, $drug_order);
            }
            foreach ($drug_list as $drug_order) {
                $this->m_patient_medication_have_drug->insert($drug_order);
            }
            switch ($ref_type) {
                default:
                    $this->redirect_if_no_continue('opd_visit/view/' . $ref_id);
            }
        }
    }
    public function view($prescription_id)
    {
        $this->load->model('m_who_drug');
        $this->load->model('m_drug_dosage');
        $this->load->model('m_drug_frequency');
        //  $patient_prescription = $this->m_patient_prescription->get($prescription_id);
        $data['default_drug'] = '';
        $data['dropdown_drug'] = $this->get_dropdown_drugs('result');

        $patient_prescription_have_drug = $this->m_patient_prescription_have_drug->get_many_by(array('PrescriptionID' => $prescription_id));
        $data['drug_list'] = array();
        foreach ($patient_prescription_have_drug as $raw_drug) {
            $tmp_data = array();
            $tmp_data['order'] = $raw_drug->Order;
            if ($raw_drug->DrugID > 0) {
                $drug = $this->m_who_drug->get($raw_drug->DrugID);
                $tmp_data['drug_info'] = $drug->name . ' ' . $drug->default_num . ' ' . $drug->formulation . '/' . $drug->dose;
            } else {
                $tmp_data['drug_info'] = '';
            }
            if ($raw_drug->DoseID > 0) {
                $tmp_data['dose'] = $this->m_drug_dosage->get($raw_drug->DoseID)->Dosage;
            } else {
                $tmp_data['dose'] = '';
            }
            if ($raw_drug->FrequencyID > 0) {
                $tmp_data['frequency'] = $this->m_drug_frequency->get($raw_drug->FrequencyID)->Frequency;
            } else {
                $tmp_data['frequency'] = '';
            }

            $tmp_data['period'] = $raw_drug->Period;
            array_push($data['drug_list'], $tmp_data);
        }
        $this->render('cardex', $data);
    }

    public function get_dropdown_drugs($type = 'json')
    {
        $prescription_id = (int)17;

        $this->load->model('m_patient_medication_have_drug');
        $result = $this->m_patient_medication_have_drug->get_many_by(array('MedicationID' => $prescription_id));
        if ($type == 'json') {
            print(json_encode($result));
        }
        return $result;
    }

    public function search()
    {
        if (!has_permission('prescribe_drug', 'view')) {
            $this->show_no_permission();
            return;
        }
        $this->set_top_selected_menu('patient_prescription');
        $qry = "SELECT
                patient_prescription.CreateDate,
                PrescriptionID,
                RefType,
                patient.PID,
                CONCAT(patient.Firstname,' ',patient.Name) AS Patient,
                CONCAT(user.Title, ' ', user.Name,' ',user.OtherName) AS Doctor,
                patient_prescription.Status
                FROM patient_prescription
                LEFT JOIN patient ON patient.PID = patient_prescription.PID
                LEFt JOIN user ON user.UID = patient_prescription.CreateUser";
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('PrescriptionID');
        $page->setCaption('');
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array(lang("Time"), lang("Order ID"), lang("Department"), lang("Patient ID"), lang("Name"), lang("Doctor"), lang("Status")));
        $page->setRowNum(25);
        $page->setColOption("CreateDate", $page->getDateSelector(date('Y-m-d')));
        $page->setColOption('PrescriptionID', array("hidden" => true));
        $page->setColOption('PID', array('width' => '50'));
        $page->setColOption('RefType', array(
            'stype' => 'select',
            'editoptions' => array(
                'value' => ':All;EMR:EMR;OPD:OPD;ADM:ADM'
            ),
            'width' => '50'
        ));
        $page->setColOption('Status', array(
            'stype' => 'select',
            'editoptions' => array(
                'value' => ':All;Pending:Pending;Dispensed:Dispensed'
            ),
            'width' => '70'
        ));
        $page->setAfterInsertRow('function(rowid, data){
        var alertText = \'\';
        for (property in data) {
            alertText +=data[property];
        }
        if (alertText.match(/^.*Pending/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
        }
        if (alertText.match(/^.*Dispensed/))
        {
            $(\'#\'+rowid).css({\'background\':\'#7deaea\'});
        }
       }');
        $page->gridComplete_JS
            = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                window.location='" . site_url("/patient_prescription/dispense") . "/'+rowId+'';
            });
            }";
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->load->vars($data);
        $this->qch_template->load_form_layout('search');
    }

    public function banner($prescription_id)
    {
        $patient_data = $this->m_patient_prescription->get_patient_data_by_pid($prescription_id);
        // Preencher o array $data com os dados do paciente
        $data['nome_do_doente'] = $patient_data->Firstname . ' ' . $patient_data->Name;
        $data['morada'] = $patient_data->Address_Street;
        $data['nid'] = $patient_data->PID;
        $data['sexo'] = $patient_data->Gender;
        $data['idade'] = $this->calculate_age($patient_data->DateOfBirth);
        $data['peso'] = $patient_data->Weight;
        $this->load->view('dispense_banner', $data);
    }

    // Exemplo de função para calcular idade
    private function calculate_age($birthdate)
    {
        $birthdate = new DateTime($birthdate);
        $today = new DateTime();
        $age = $today->diff($birthdate)->y;

        return $age;
    }



    public function dispense($prescription_id)
    {
        $this->load->model('m_who_drug');
        $this->load->model('m_who_drug_count');
        $this->load->model('m_drug_dosage');
        $this->load->model('m_drug_frequency');
        $this->load->model('m_patient_prescription');
        $this->load->model('m_patient_prescription_have_drug');

        $data['patient_prescription'] = $this->m_patient_prescription->get($prescription_id);
        $data['pid'] = $data['patient_prescription']->PID;
        $data['ref_type'] = $data['patient_prescription']->RefType;
        $data['ref_id'] = $data['patient_prescription']->RefID;

        $this->form_validation->set_rules('quantity[]', 'Quantidade', 'xss_clean');
        $this->form_validation->set_rules('note[]', 'Nota', 'xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $patient_prescription_have_drug = $this->m_patient_prescription_have_drug->get_many_by(array('PrescriptionID' => $prescription_id));
            $prescription = $this->m_patient_prescription->get_by_prescription_id($prescription_id);
            $doctor_name = $this->m_patient_prescription->get_doctor_name_by_prescription_id($prescription_id);
            $data['drug_list'] = array();
            $data['doctor_name']  = $doctor_name;
            $data['prescription_date'] =  $prescription[0]->CreateDate;

            foreach ($patient_prescription_have_drug as $raw_drug) {
                $tmp_data = array();
                $tmp_data['cost'] = $prescription[0]->Cost;
                $tmp_data['patient_type'] = $prescription[0]->Patient_type;
                $tmp_data['order'] = $raw_drug->Order;

                if ($raw_drug->DrugID > 0) {
                    // Retrieve drug details
                    $drug = $this->m_who_drug->get($raw_drug->DrugID);
                    // Concatenate drug information
                    $tmp_data['drug_info'] = $drug->name . ' ' . $drug->pharmaceutical_form . ' ' . $drug->dosage . ' ' . $drug->presentation;

                    // Retrieve the sum of existing stock for the drug
                    $drug_count = $this->m_who_drug_count->get_existing_stock_sum_by_wd_id($raw_drug->DrugID);

                    // Retrieve current drug details
                    $current_drug = $this->m_who_drug->get($raw_drug->DrugID);

                    // Retrieve and filter valid batches based on expiration date and stock
                    $valid_batches = $this->m_who_drug_count->get_valid_batches_by_wd_id($raw_drug->DrugID);

                    // Determine stock count
                    $stock = isset($current_drug->count) ? $current_drug->count : 0;

                    // Store valid batches and stock information in $tmp_data
                    $tmp_data['batch'] = $valid_batches;
                    $tmp_data['fnm'] = $drug->fnm;
                    $tmp_data['stock'] = $stock;
                } else {
                    $tmp_data['drug_info'] = '';
                }


                $tmp_data['routeAdministration'] = $raw_drug->RouteAdministration;
                $tmp_data['dose'] = $raw_drug->Dose;
                $tmp_data['timeTotal'] = $raw_drug->TimeTotal;
                $tmp_data['doseTotal'] = $raw_drug->DoseTotal;

                if ($raw_drug->FrequencyID > 0) {
                    $tmp_data['frequency'] = $this->m_drug_frequency->get($raw_drug->FrequencyID)->Frequency;
                } else {
                    $tmp_data['frequency'] = '';
                }

                if ($data['patient_prescription']->Status === 'Dispensed') {
                    $tmp_data['quantity'] = $raw_drug->Quantity;
                    $tmp_data['note'] = $raw_drug->Note;
                    $tmp_data['dispensed'] = $raw_drug->Dispensed;
                    $tmp_data['batch'] = array($raw_drug->Batch => $raw_drug->Batch);
                } else {
                    $tmp_data['quantity'] = '';
                    $tmp_data['note'] = '';
                    $tmp_data['dispensed'] = 'yes';
                }

                $tmp_data['period'] = $raw_drug->Period;

                array_push($data['drug_list'], $tmp_data);
            }

            $this->render('dispense_prescription', $data);
        } else {
            $insufficient_stock = false;
            $error_message = '';

            foreach ($this->input->post('quantity') as $order => $quantity) {
                $where = array('PrescriptionID' => $prescription_id, 'Order' => $order);

                if ($this->input->post('confirm_drug')[$order]) {
                    // Check stock before dispensing
                    $raw_drug = $this->m_patient_prescription_have_drug->get_by($where);

                    if ($raw_drug->DrugID > 0) {
                        $drug_id = $raw_drug->DrugID;
                        $batch = $this->input->post('drug_batch')[$order]; // Assuming you get batch from form input

                        // Check if the batch exists for the drug
                        $existing_batches = $this->m_who_drug_count->get_existing_batches_by_wd_id($drug_id);
                        if (!array_key_exists($batch, $existing_batches)) {
                            // Batch not found
                            $insufficient_stock = true;
                            $error_message = lang('invalid_batch');
                            break;
                        }

                        // Get the stock and deadline for the selected batch
                        $existingStock = $this->m_who_drug_count->get_existing_stock_by_batch_and_wd_id($batch, $drug_id);
                        $batch_deadline = $existing_batches[$batch]['batch_deadline'];

                        if ($existingStock->ExistingStock >= $quantity) {
                            // Update quantity and batch
                            $update = array('Quantity' => $quantity, 'Batch' => $batch);
                            $this->m_patient_prescription_have_drug->update_by($where, $update);
                            $this->m_who_drug_count->dispense_drug($drug_id, $batch, $quantity);
                        } else {
                            // Set insufficient stock flag and error message
                            $insufficient_stock = true;
                            $error_message = 'Estoque Insuficiente: Lote ' . $batch . ' apenas tem ' . $existingStock->ExistingStock . ' unidades disponíveis, para ' . $quantity . ' unidades requisitadas.';
                            break;
                        }
                    }
                }
            }

            if ($insufficient_stock) {
                $this->session->set_flashdata('error_message', $error_message);
                redirect('patient_prescription/dispense/' . $prescription_id);
            } else {
                foreach ($this->input->post('note') as $order => $note) {
                    $where = array('PrescriptionID' => $prescription_id, 'Order' => $order);
                    $update = array('Note' => $note);
                    $this->m_patient_prescription_have_drug->update_by($where, $update);
                }

                foreach ($this->input->post('confirm_drug') as $order => $dispensed) {
                    $where = array('PrescriptionID' => $prescription_id, 'Order' => $order);
                    $update = array('Dispensed' => $dispensed);
                    $this->m_patient_prescription_have_drug->update_by($where, $update);
                }

                $this->m_patient_prescription->update($prescription_id, array('Cost' => $this->input->post('cost')));
                $this->m_patient_prescription->update($prescription_id, array('Status' => 'Dispensed'));
                $this->redirect_if_no_continue('patient_prescription/search');
            }
        }
    }




    public function get_previous_prescription($ref_type, $ref_id, $continue, $mode = 'HTML')
    {
        $data = array();
        $data["previous_prescription_list"] = $this->m_patient_prescription->with('order_by')->order_by('CreateDate', 'DESC')->get_many_by(array('RefType' => $ref_type, 'RefID' => $ref_id));
        foreach ($data['previous_prescription_list'] as $prescription) {
            $prescription->prescription_have_drugs = $this->m_patient_prescription_have_drug->with('drug')->with('dose')->with('frequency')->get_many_by(array('PrescriptionID' => $prescription->PrescriptionID));
        }

        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_prescription');
        } else {
            return $data["previous_prescription_list"];
        }
    }



    public function cardex_prescription($ref_type, $ref_id, $edit = false)
    {

        $visitData = $this->get_visit_info($ref_type, $ref_id)['visits_info'];
        $pid = $visitData->PID;
        $patientPrescriptions = $this->m_patient_prescription->get_prescriptions_by_refid($pid,  $ref_id);
        if (!empty($patientPrescriptions) && !$edit) {
            redirect('patient_prescription/cardex/' . $pid . '/' . $ref_id);
        }
        $data = array('PID' => $pid, 'ref_type' => $ref_type, 'ref_id' => $ref_id);
        $this->session->set_userdata('ref_id', $ref_id);
        $data['cardex_data'] = $this->session->userdata('cardex_data');
        $data['ward_info'] = $this->m_admission->return_all($pid);
        $data['ref_id'] = $ref_id;
        $data['ref_type'] = $ref_type;
        $data['visits_info'] = $visitData;
        $data['pid'] = $pid;

        if (isset($pid)) {

            $data['prescriptions'] = $patientPrescriptions;
        }

        $this->render('cardex_prescription', $data);
    }


    public function get_prescription_by_patient_id($ref_type, $ref_id)
    {
        $visitData = $this->get_visit_info($ref_type, $ref_id)['visits_info'];
        $pid = $visitData->PID;
        $patientPrescriptions = $this->m_patient_prescription->get_prescriptions_by_refid($pid,   $ref_id);
        header('Content-type: application/json');
        echo json_encode($patientPrescriptions);
    }


    // public function cardex_prescription_quantitiy() {
    //     if ($this->input->is_ajax_request()) {
    //         $roomID = $this->input->post('roomID'); 
    //         $reportDate = $this->input->post('reportDate'); // Captura a data selecionada

    //         if (empty($roomID)) {
    //             echo json_encode(['status' => 'error', 'message' => 'O campo quarto é obrigatório.']);
    //             return;
    //         }

    //         // Passa a data para o método do model
    //         $drugs_prescribed_in_rooms = $this->m_patient_prescription_have_drug->get_raw_prescribed_drugs_in_rooms($roomID, $reportDate);

    //         // Manipula os dados para calcular a quantidade total
    //         $processed_data = [];
    //         foreach ($drugs_prescribed_in_rooms as $drug) {
    //             $drugName = $drug['DrugName'];
    //             $drugId = $drug['DrugId'];
    //             $dose = $drug['Dose'];

    //             // Calcula a frequência de doses por dia (contando as vírgulas)
    //             $frequency = substr_count($drug['Period'], ',') + 1;

    //             // Calcula a quantidade total de medicamento: Dose * Frequência
    //             $total_quantity = $dose * $frequency;

    //             // Verifica se o medicamento já foi adicionado ao array processado
    //             if (isset($processed_data[$drugId])) {
    //                 $processed_data[$drugId]['TotalQuantity'] += $total_quantity;
    //             } else {
    //                 // Se não existe, adiciona o medicamento ao array
    //                 $processed_data[$drugName] = [
    //                     'DrugName' => $drugName,
    //                     'PharmaceuticalForm' => $drug['PharmaceuticalForm'],
    //                     'DrugDosage' => $drug['DrugDosage'],
    //                     'TotalQuantity' => $total_quantity
    //                 ];
    //             }
    //         }

    //         // Reindexa o array para retornar um array simples
    //         $processed_data = array_values($processed_data);

    //         echo json_encode(['status' => 'success', 'data' => $processed_data]);
    //     } else {
    //         $this->load->model('m_ward_rooms');
    //         $data['rooms'] = $this->m_ward_rooms->get_active_rooms();
    //         $data['wards'] = $this->m_ward_rooms->get_all_ward();
    //         $this->qch_template->load_form_layout('cardex_prescription_quantitiy', $data);
    //     }
    // }
    public function cardex_prescription_quantitiy()
    {
        if ($this->input->is_ajax_request()) {
            $roomID = $this->input->post('roomID');
            $reportDate = $this->input->post('reportDate');

            if (empty($roomID)) {
                echo json_encode(['status' => 'error', 'message' => 'O campo quarto é obrigatório.']);
                return;
            }

            // Buscar os medicamentos prescritos para o quarto e data
            $drugs_prescribed_in_rooms = $this->m_patient_prescription_have_drug->get_raw_prescribed_drugs_in_rooms($roomID, $reportDate);

            // Buscar as quantidades já dispensadas
            $dispensed_drugs = $this->m_patient_prescription_have_drug->get_dispensed_quantities($roomID, $reportDate);

            // Manipula os dados para calcular a quantidade total
            $processed_data = [];
            foreach ($drugs_prescribed_in_rooms as $drug) {
                $drugName = $drug['DrugName'];
                $drugId = $drug['DrugId'];
                $dose = $drug['Dose'];

                // Calcula a frequência de doses por dia
                $frequency = substr_count($drug['Period'], ',') + 1;

                // Calcula a quantidade total de medicamento: Dose * Frequência
                $total_quantity = $dose * $frequency;

                // Verifica se o medicamento já foi adicionado ao array processado
                if (isset($processed_data[$drugId])) {
                    $processed_data[$drugId]['TotalQuantity'] += $total_quantity;
                } else {
                    // Verifica se já existe uma quantidade dispensada para este medicamento
                    $dispense_quantity = isset($dispensed_drugs[$drugId]) ? $dispensed_drugs[$drugId]['DispendQuantity'] : 0;

                    $processed_data[$drugName] = [
                        'DrugName' => $drugName,
                        'PharmaceuticalForm' => $drug['PharmaceuticalForm'],
                        'DrugDosage' => $drug['DrugDosage'],
                        'TotalQuantity' => $total_quantity,
                        'DispendQuantity' => $dispense_quantity,
                        'DrugId' => $drugId,
                    ];
                }
            }

            // Reindexa o array para retornar um array simples
            $processed_data = array_values($processed_data);

            echo json_encode(['status' => 'success', 'data' => $processed_data]);
        } else {
            $this->load->model('m_ward_rooms');
            $data['rooms'] = $this->m_ward_rooms->get_active_rooms();
            $data['wards'] = $this->m_ward_rooms->get_all_ward();
            $this->qch_template->load_form_layout('cardex_prescription_quantitiy', $data);
        }
    }

    public function get_rooms_by_ward($w)
    {
        if ($this->input->is_ajax_request()) {
            $rooms = $this->m_ward_rooms->get_all_names($w);

            if ($rooms) {
                $response = ['status' => 'success', 'data' => $rooms];
            } else {
                $response = ['status' => 'error', 'message' => 'Nenhum quarto encontrado.'];
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        } else {
            show_404();
        }
    }

    public function save_cardex_dispensed()
    {
        $userID = $this->session->userdata('uid');
        $dispensedData = $this->input->post('dispensedData');

        if (!empty($dispensedData)) {
            foreach ($dispensedData as $data) {
                $drugId = $data['drugId'];
                $roomID = $data['roomID'];
                $reportDate = $data['reportDate'];

                $existingRecord = $this->m_patient_prescription_have_drug->check_existing_dispensed($drugId, $roomID, $reportDate);

                if ($existingRecord) {
                    $this->m_patient_prescription_have_drug->update_existing_dispensed_cardex($drugId, $roomID, $reportDate);
                }
                $insertData = array(
                    'WardName' => $data['wardID'],
                    'WardRoom' => $data['roomID'],
                    'Date' => $data['reportDate'],
                    'DrugName' => $data['drugName'],
                    'DrugId' => $drugId,
                    'DrugFNM' => $data['drugFNM'],
                    'DrugDosage' => $data['drugDosage'],
                    'Quantity' => $data['totalQuantity'],
                    'DispendQuantity' => $data['dispenseQuantity'],
                    'LastUpDateUser' => $userID,
                    'LastUpDate' => date('Y-m-d'),
                    'Active' => '1',
                );
                $this->m_patient_prescription_have_drug->insert_dispensed_cardex($insertData);
            }

            $response = array('status' => 'success', 'message' => 'Data saved successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'No data to save.');
        }

        echo json_encode($response);
    }



    public function add_cardex()
    {

        $prescriptionString = $this->input->post('prescriptions');
        $prescriptionArray = json_decode($prescriptionString, true);

        $PID = $this->input->post('PID');
        $ref_type = $this->input->post('ref_type');
        $ref_id = $this->input->post('ref_id');
        $remarks = $this->input->post('Remarks');
        $ward = $this->input->post('ward_select');
        $room = $this->input->post('room_select');
        $bed = $this->input->post('bed_select');
        $prescribeBy = $this->session->userdata('uid');

        $prescription = array(
            'PID' => $PID,
            'RefType' => strtoupper($ref_type),
            'RefID' => $ref_id,
            'Status' => 'Pending',
            'Remarks' => $remarks,
            'Ward' => $ward,
            'Room' => $room,
            'Bed' => $bed,

        );

        if (isset($prescriptionArray)) {

            $prescription_id = $this->m_patient_prescription->insert($prescription);


            foreach ($prescriptionArray as $prescriptionItem) {
                unset($prescriptionItem['drugName']);
                $prescriptionItem['PID'] = $PID;
                $prescriptionItem['PrescriptionID'] = $prescription_id;
                $prescriptionItem['Active'] = 1;
                // $prescriptionItem['Note'] = $note;
                $this->m_patient_prescription_have_drug->insert($prescriptionItem);
            }
        }
    }

    public function add_cardex_items()
    {

        $prescriptionString = $this->input->post('prescriptionsItems');
        $prescriptionArray = json_decode($prescriptionString, true);

        $PID = $this->input->post('PID');
        $prescription_id = $this->input->post('PrescriptionID');


        if (isset($prescriptionArray)) {


            foreach ($prescriptionArray as $prescriptionItem) {
                unset($prescriptionItem['drugName']);
                $prescriptionItem['PID'] = $PID;
                $prescriptionItem['PrescriptionID'] = $prescription_id;
                $prescriptionItem['Active'] = 1;
                // $prescriptionItem['Note'] = $note;
                $this->m_patient_prescription_have_drug->insert($prescriptionItem);
            }
        }
    }



    public function cardex($PID = null, $prescriptionID = null)

    {
        $pid = htmlspecialchars($PID);
        $prescriptionId = htmlspecialchars($prescriptionID);
        $patientPrescriptions = $this->m_patient_prescription->get_prescriptions_by_refid($pid,   $prescriptionId);
        $ref_type = $patientPrescriptions[0]->RefType;
        $ref_id = $patientPrescriptions[0]->RefID;
        $visitData = $this->get_visit_info($ref_type, $ref_id)['visits_info'];
        $data['PID'] = $pid;
        $data['prescriptions'] = $patientPrescriptions;
        $data['ref_type'] = $patientPrescriptions[0]->RefType;
        $data['ref_id'] = $patientPrescriptions[0]->RefID;
        $data['visits_info'] = $visitData;


        $this->render('cardex_dispense', $data);
    }

    public function get_prescription_itens($prescriptionID)
    {
        $preacriptioinId = htmlspecialchars($prescriptionID);
        $itens = $this->m_patient_prescription_have_drug->get_prescription_drugs_by($preacriptioinId);


        header('Content-type: application/json');
        echo json_encode($itens);
    }

    public function take_drug()
    {
        $takenDrug = $this->input->post('takenDrug');


        $taken = json_decode($takenDrug, true);
        $taken['DispensedBy'] = $this->session->userdata('uid');

        try {
            $id = $this->m_taken_drugs->create($taken);
            echo $id;
        } catch (Exception $e) {
            echo 'Erro';
        }
    }


    public function taken_drugs($prescriptionId)
    {
        $takenDrugs = $this->m_taken_drugs->get_taken_drugs_by($prescriptionId);

        header('Content-type: application/json');
        echo json_encode($takenDrugs);
    }

    public function get_cardex_drug()
    {
        $cardexData = json_decode($this->input->post('cardexData'), true);
        $ref_type = json_decode($this->input->post('ref_type'));
        $ref_id = json_decode($this->input->post('ref_id'));


        // Optionally, store in session if you need immediate access
        $this->session->set_userdata('cardex_data', $cardexData);

        // Return a success response
        echo json_encode(['status' => 'success', 'message' => 'Cardex drugs processed successfully!']);
    }

    public function get_ward_details()
    {
        $ward_id = $this->input->get('ward_id');
        $room_id = $this->input->get('room_id');
        $bed_id = $this->input->get('bed_id');

        if ($ward_id) {
            $ward = $this->m_ward->get_name_by_wid($ward_id);
            $rooms = $this->m_ward_rooms->get_room_name_by_rid($room_id);
            $beds = $this->m_ward_beds->bed_number_by($bed_id);

            if ($ward && $rooms && $beds) {
                echo json_encode([
                    'ward' => [
                        'id' => $ward["WID"],
                        'name' => $ward["Name"]
                    ],
                    'rooms' => [
                        'id' =>  $rooms->RID,
                        'name' => $rooms->Name
                    ],
                    'beds' => [
                        'id' =>  $beds->BID,
                        'number' => $beds->BedNo
                    ],
                ]);
            } else {
                echo json_encode([
                    'ward' => null,
                    'rooms' => [],
                    'beds' => []
                ]);
            }
        } else {
            echo json_encode([
                'ward' => null,
                'rooms' => [],
                'beds' => []
            ]);
        }
    }

    public function suspendPrescribedDrug()
    {
 
        $prescriptionId = $this->input->post('prescriptionId');
        //  $existingRecord = $this->m_patient_prescription_have_drug->check_existing_dispensed($drugId, $roomID, $reportDate);

        $this->m_patient_prescription_have_drug->voidPrescriptionDrug($prescriptionId);
       
        echo json_encode(['status' => 'success', 'message' => 'prescribed drug was suspended successfully!']);
    }
    public function dietetic_prescription ($ref_type, $ref_id) 
    {
        $data = [];
        $data['visits_info'] = $this->get_visit_info($ref_type, $ref_id)['visits_info'];
        $data['pid'] = $data["visits_info"]->PID;
        $data['ref_id'] = $ref_id;
        $data['dietary_list'] = ['' => ''] + array_column(json_decode(json_encode($this->m_dietary_list->get_all()), true), 'name', 'id');
        $data['dietetic_prescriptions'] = $this->m_prescription_no_drugs->get_all_dietetic('Dietetic');

        foreach($data['dietetic_prescriptions'] as $key => $item) {
            $data['dietetic_prescriptions'][$key]['Prescription'] = $this->m_dietary_list->get_name_by_id(intval($item['Prescription']))[0]->name;
        }

        foreach($data['dietetic_prescriptions'] as $key => $item) {
            $data['dietetic_prescriptions'][$key]['CreateUser'] = $this->m_user->get_name_by_uid(intval($item['CreateUser']));
        }

        $this->form_validation->set_rules('prescription', 'Prescription', 'xss_clean|required');

        if ($this->form_validation->run($this) == FALSE) {
            $this->qch_template->load_form_layout('form_dietetic_prescription', $data);
        } else {
            $dietetic_data = array (
                'Type' => 'Dietetic',
                'PID' => $data['pid'],
                'RefID' => $ref_id,
                'Prescription' => $this->input->post('prescription'),
                'Remarks' => $this->input->post('remarks'),
            );
            $this->m_prescription_no_drugs->insert($dietetic_data);
            redirect('patient_prescription/dietetic_prescription/' . $ref_type . '/' . $ref_id);
        }
    }
    
    public function therapeutic_prescription ($ref_type, $ref_id) 
    {
        $data = [];
        $data['visits_info'] = $this->get_visit_info($ref_type, $ref_id)['visits_info'];
        $data['pid'] = $data["visits_info"]->PID;
        $data['ref_id'] = $ref_id;
        $data['default_prescription'] = '';
        $data['dietetic_prescriptions'] = $this->m_prescription_no_drugs->get_all_dietetic('Therapeutic');

        foreach($data['dietetic_prescriptions'] as $key => $item) {
            $data['dietetic_prescriptions'][$key]['CreateUser'] = $this->m_user->get_name_by_uid(intval($item['CreateUser']));
        }

        $this->form_validation->set_rules('prescription', 'Prescription', 'xss_clean|required');

        if ($this->form_validation->run($this) == FALSE) {
            $this->qch_template->load_form_layout('form_therapeutic_prescription', $data);
        } else {
            $dietetic_data = array (
                'Type' => 'Therapeutic',
                'PID' => $data['pid'],
                'RefID' => $ref_id,
                'Prescription' => $this->input->post('prescription'),
                'Remarks' => $this->input->post('remarks'),
            );
            $this->m_prescription_no_drugs->insert($dietetic_data);
            redirect('patient_prescription/therapeutic_prescription/' . $ref_type . '/' . $ref_id);
        }
    }

    public function void_prescription_no_drugs($id) 
    {
        if (!is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
            return;
        }
    
        $data = ['Active' => 0];
        $updated = $this->m_prescription_no_drugs->update($id, $data); 
    
        if ($updated) {
            echo json_encode(['status' => 'success', 'message' => 'Prescrição suspensa com sucesso']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Falha ao suspender a prescrição']);
        }
    }

}

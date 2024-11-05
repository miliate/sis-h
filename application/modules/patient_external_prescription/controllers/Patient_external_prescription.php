<?php
class Patient_External_Prescription extends FormController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_external_prescription');
        $this->load->model('m_patient_external_prescription_have_drug');
        $this->load->model('m_who_drug_count');
        $this->load->model('m_drug_dosage');
        $this->load->model('m_drug_frequency');
        $this->load->model('m_who_drug');
        $this->load_form_language();
    }

    public function dispense()
    {
        $data = [];
        $this->render('form_patient_external_prescription', $data);
    }

    public function dispense_drug()
    {
        $patient_name = $this->input->post('patient_name');

        $patient_nid = $this->input->post('patient_nid');
        $health_unit = $this->input->post('health_unit');
        $quantities = $this->input->post('total_dosage');
        $confirm_drugs = $this->input->post('confirm_drug');
        $notes = $this->input->post('note');
        $drug_ids = $this->input->post('drug_id');
        //$fnms = $this->input->post('fnm');
        $dose_ids = $this->input->post('dose');
        $frequency_ids = $this->input->post('frequency');
        $periods = $this->input->post('total_time');
        $patient_category = $this->input->post('patient_category');
        $amount_paid = $this->input->post('amount_paid');
        $route_adminstratios = $this->input->post('route_administration');
        $drug_batches = $this->input->post('drug_batch');
        $remarks = $this->input->post('prescription_obs');


        $create_user = $this->session->userdata('uid');

        if (!empty($confirm_drugs)) {
            $prescription_data = array(
                'PatientName' => $patient_name,
                'PID' => $patient_nid,
                'HealthUnit' => $health_unit,
                'Patient_type' => $patient_category,
                'Cost' => $amount_paid,
                'CreateUser' => $create_user,
                'Remarks' =>   $remarks,
                'CreateDate' => date('Y-m-d H:i:s')
            );

            $prescription_id = $this->m_patient_external_prescription->insert_prescription($prescription_data);

            foreach ($confirm_drugs as $index => $value) {
                if (empty($quantities[$index]) || $quantities[$index] <= 0) {
                    $this->session->set_flashdata('error', 'A quantidade para todos os medicamentos deve ser maior que zero.');
                    redirect('patient_external_prescription/dispense');
                    return;
                }

                $drug_data = array(
                    'prescription_id' => $prescription_id,
                    'DrugID' => $drug_ids[$index],
                    'DoseID' => $dose_ids[$index],
                    'RouteAdministration' => $route_adminstratios[$index],
                    'FrequencyID' => $frequency_ids[$index],
                    'Quantity' => $quantities[$index],
                    'Note' => $notes[$index],
                    'Period' => $periods[$index],
                    'Batch' => $drug_batches[$index]
                );

                $existingStock = $this->m_who_drug_count->get_existing_stock_by_batch_and_wd_id($drug_batches[$index], $drug_ids[$index]);

                if ($existingStock->ExistingStock >= $quantities[$index]) {
                    $this->m_patient_external_prescription_have_drug->insert_prescription_drug($drug_data);

                    $this->m_who_drug_count->dispense_drug($drug_ids[$index], $drug_batches[$index], $quantities[$index]);
                } else {

                    $error_message = 'Estoque Insuficiente: Lote ' . $drug_batches[$index] . ' apenas tem ' . $existingStock->ExistingStock . ' unidades disponíveis, para ' . $quantities[$index] . ' unidades requisitadas.';
                    break;
                }
            }

             $this->session->set_flashdata('success', 'Prescrição criada com sucesso.');
        }

        redirect('patient_external_prescription/dispense');
    }


    public function search()
    {
        if (!has_permission('prescribe_drug', 'view')) {
            $this->show_no_permission();
            return;
        }

        $this->set_top_selected_menu('patient_prescription');

        $qry = "SELECT
                    p.prescription_id,
                    p.CreateDate,
                    p.PID,
                    p.PatientName,
                    p.Patient_type,
                    p.HealthUnit,
                    p.Cost,
                    CONCAT(user.Title, ' ', user.Name,' ',user.OtherName) AS Technician
                FROM patient_external_prescription p
                LEFT JOIN patient_external_prescription_have_drug pd ON p.prescription_id = pd.prescription_id
                LEFT JOIN who_drug wd ON pd.DrugID = wd.wd_id
                LEFT JOIN user ON user.UID = p.CreateUser
                GROUP BY p.prescription_id";

        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list");
        $page->setDivClass('');
        $page->setRowid('prescription_id');
        $page->setCaption('');
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array(
            lang("ID"),
            lang("Date"),
            lang("NID"),
            lang("Patient Name"),
            lang("Patient Category"),
            lang("Health Unit"),
            lang("Amount Paid"),
            lang("Technician"),
        ));
        $page->setRowNum(25);
        $page->setColOption('prescription_id', array("hidden" => true));
        $page->setColOption("CreateDate", $page->getDateSelector(date('Y-m-d')));
        $page->setColOption('PID', array('width' => '80'));
        $page->setColOption('Patient_type', array('width' => '80'));
        $page->setColOption('HealthUnit', array('width' => '50'));
        $page->setColOption('Cost', array('width' => '50'));
        $page->setColOption('PatientName', array('width' => '50'));
        $page->setColOption('Technician', array('width' => '50'));

        $page->gridComplete_JS = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                window.location='" . site_url("/patient_external_prescription/view") . "/'+rowId+'';
            });
        }";
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->load->vars($data);
        $this->qch_template->load_form_layout('search');
    }


    public function get_drug_stock_and_batch()
    {
        $drug_id = $this->input->get('drug_id');

        if (!$drug_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Drug ID não recebido']));
            return;
        }

        $this->load->model('m_who_drug_count');

        $stock = $this->m_who_drug_count->get_existing_stock_sum_by_wd_id($drug_id);
        $bacthes = $this->m_who_drug_count->get_existing_batches_by_wd_id($drug_id);

        if ($stock === false) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Erro ao obter o estoque']));
            return;
        }

        $response = ['stock' => $stock, 'batches' => $bacthes];
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }


    public function view($prescription_id)
    {
        if (!has_permission('prescribe_drug', 'view')) {
            $this->show_no_permission();
            return;
        }

        $prescription = $this->m_patient_external_prescription->get_prescription_by_id($prescription_id);

        if (!$prescription) {
            show_error('Prescrição não encontrada.', 404);
            return;
        }

        $drugs = $this->m_patient_external_prescription_have_drug->get_drugs_by_prescription_id($prescription->prescription_id);

        $this->load->model('m_drug_dosage');
        $this->load->model('m_drug_frequency');
        $this->load->model('m_who_drug'); // Carrega o modelo de medicamentos

        foreach ($drugs as $drug) {
            $frequency = $this->m_drug_frequency->get($drug->FrequencyID);
            $drug_info = $this->m_who_drug->get_drug_name_and_fnm_by_wd_id($drug->DrugID); // Obtém informações adicionais do medicamento

            $drug->FrequencyName = $frequency ? $frequency->Frequency : 'Frequência não encontrada';
            $drug->PharmaceuticalForm = $drug_info ? $drug_info->pharmaceutical_form : 'Forma Farmacêutica não encontrada';
        }

        $data['prescription'] = $prescription;
        $data['drugs'] = $drugs;

        $this->render('view', $data);
    }

    public function view_select_health_unit($id = 'health_unit')
    {
        $all_units = $this->m_patient_external_prescription->get_all_health_units();
        $unit_options = array('' => '');
        foreach ($all_units as $unit) {
            $unit_options[$unit->name] = $unit->name;
        }
        $extra = 'class="form-control" id="' . $id . '"';
        echo form_dropdown('health_unit', $unit_options, '', $extra);
    }

    public function view_select_patient_category($id = 'patient_category')
    {
        $all_discounts = $this->m_patient_external_prescription->get_all_patient_discounts();
        $discount_options = ['' => ''];
        foreach ($all_discounts as $discount) {
            $discount_options[$discount->name] = $discount->name;
        }
        $extra = 'class="form-control" id="' . $id . '"';
        echo form_dropdown('patient_category', $discount_options, '', $extra);
    }
}

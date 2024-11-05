<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Drug_stock extends FormController
{

    public $data = array();
    var $FORM_NAME = 'form_drug_stock';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {
        parent::__construct();
        $this->load->model("m_who_drug");
        $this->load->model("m_who_drug_count");
        $this->load->model("m_who_drug_adjustment");
        $this->load->model('m_user');
        $this->load->model("m_request");
        $this->load->model("m_request_item");

        $this->load->model('m_patient_prescription_have_drug');

        $this->load_form_language();
    }

    public function index($drug_stock_id = NULL)
    {
        return;
    }

    public function get_dropdown_technician($type = 'json')
    {
        $result = $this->m_user->getPharmacistUsers();

        if ($type == 'json') {
            print(json_encode($result));
        }

        $usuario = array(
            "nome" => $this->session->userdata('name'),
            "uid" => $this->session->userdata('uid')
        );

        if (in_array($usuario['nome'], $result)) {
            return $result = $usuario;
        } else {
            return $result;
        }
    }

    public function get_technician_name($uid)
    {
        return $this->m_user->get_name_by_uid($uid);
    }

    public function drug_sales()
    {
        $data['dados'] = [];
        if ($this->session->userdata("user_group_name") == "Pharmacist") {
            $data['dropdown_technician'] = $this->get_dropdown_technician('result');
        } else {
            $pharmacist = array($this->session->userdata('uid') => $this->session->userdata('name')); 
            $data['dropdown_technician'] = $pharmacist;
        }
        $data['default_technician'] = '';

        if ($this->input->get()) {
            $uid = $this->input->get('tecnico');
            $tecnico = $this->get_technician_name($uid); // Assuming this is correct
            $data_inicio = $this->input->get('data_inicio');
            $data_fim = $this->input->get('data_fim');

            // Fetch prescriptions for the user within the date range
            $prescriptions = $this->m_patient_prescription_have_drug->get_prescriptions_by_user(21, $data_inicio, $data_fim);

            // Calculate total cost for the user within the date range
            $total_cost = $this->m_patient_prescription_have_drug->get_total_cost_by_user(21, $data_inicio, $data_fim);

            $dados = [];
            foreach ($prescriptions as $prescription) {
                // Get drug details for each prescription
                $drug_details = $this->m_who_drug->get_drug_name_and_fnm_by_wd_id($prescription->DrugID);

                // Check if the drug already exists in $dados array
                $found = false;
                foreach ($dados as &$item) {
                    if ($item['fnm'] == $drug_details->fnm && $item['name'] == $drug_details->name) {
                        // If found, increment the quantity and mark as found
                        $item['quantity'] += $prescription->Quantity;
                        $found = true;
                        break;
                    }
                }
                unset($item); // Unset reference to last element

                // If drug not found in $dados array, add it
                if (!$found) {
                    $dados[] = [
                        'fnm' => $drug_details->fnm,
                        'name' => $drug_details->name,
                        'quantity' => $prescription->Quantity
                    ];
                }
            }

            $data['dados'] = $dados;
            $data['tecnico'] = $tecnico;
            $data['data_inicio'] = $data_inicio;
            $data['data_fim'] = $data_fim;
            $data['total_cost'] = $total_cost;
        }

        // Load the view with the processed data
        $this->qch_template->load_form_layout('drug_stock_outs', $data);
    }

    private function update_stock_drug_list($drug_stock_id)
    {
        $data['who_drug_list'] = $this->mdrug_stock->get_who_drug_list();
        if (!empty($data['who_drug_list'])) {
            for ($i = 0; $i < count($data['who_drug_list']); ++$i) {
                if ($this->mdrug_stock->is_drug_exsist($drug_stock_id, $data['who_drug_list'][$i]["wd_id"]) == 0) {
                    $drug_data = array(
                        "drug_stock_id" => $drug_stock_id,
                        "who_drug_id" => $data["who_drug_list"][$i]["wd_id"],
                        "who_drug_count" => 101,
                        "Active" => 1
                    );
                    $this->load->model("mpersistent");
                    $this->mpersistent->create("drug_count", $drug_data);
                }
            }
        }
        return true;
    }

    public function create($wd_id = NULL)
    {
        // Retrieve current drug count from m_who_drug model
        $current_drug = $this->m_who_drug->get($wd_id);
        if (!$current_drug) {
            show_error('Drug ID not found.'); // Better error handling
            return;
        }

        // Retrieve existing stock sum and batches for the given wd_id from m_who_drug_count model
        $existing_drug = $this->m_who_drug_count->get_existing_stock_sum_by_wd_id($wd_id);

        // Initialize data array with default values
        $data = array(
            'id' => 0,
            'default_come_from' => null,
            'defautl_destination' => null,
            'default_doc_no' => '',
            'default_quantity' => '',
            'default_existing_stock' => '',
            'default_existing_stock_batch' => '',
            'default_signature' => '',
            'default_lote' => '',
            'default_batch_deadline' => '',
            'drug_name' => $current_drug->name,
            'drug_id' => $current_drug->wd_id,
            'current_drug_count' => $existing_drug,
            'dropdown_lotes' => $this->list_existing_batches($wd_id),
            'default_lotes' => ''
        );

        // Form validation rules based on movement type
        $mov = $this->input->post('mov');
        if ($mov === 'Entries' || $mov === 'Positive Adjustment') {
            $this->form_validation->set_rules('come_from', lang('Come From'), 'trim|xss_clean|required');
            $this->form_validation->set_rules('lote', lang('Batch'), 'trim|xss_clean|required');
            $this->form_validation->set_rules('batch_deadline', lang('Batch Deadline'), 'trim|xss_clean|required');
        } elseif ($mov === 'Waste' || $mov === 'Negative Adjustment' || $mov === 'Consumption') {
            //$this->form_validation->set_rules('destination', 'Destination', 'trim|xss_clean|required');
            $this->form_validation->set_rules('lotes', lang('Batch'), 'trim|xss_clean|required');
            $this->form_validation->set_rules('batch_deadlines', lang('Batch Deadline'), 'trim|xss_clean|required');
        }

        $this->form_validation->set_rules('doc_no', lang('Document Number'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('entries', lang('Entries'), 'trim|xss_clean');

        //$this->form_validation->set_rules('existing_stock', 'Existing Stock', 'trim|xss_clean');
        $this->form_validation->set_rules('signature', lang('Signature'), 'trim|xss_clean');

        // Validate form input
        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data); // Load form with validation errors
        } else {
        

            // Prepare data for insertion into m_who_drug_count table
            $count_data = array(
                'Type' => $mov,
                'ComeFrom' => $this->input->post('come_from'),
                'Destination' => $this->input->post('destination'),
                'DocNo' => $this->input->post('doc_no'),
                'Quantity' => $this->input->post('quantity'),
                'ExistingStock' => $this->input->post('quantity'),
                'CreateDate' => $date = date('Y-m-d H:i:s'),
                'CreateUser' => $this->session->userdata('uid'),
                'Signature' => $this->input->post('signature'),
                'wd_id' => $wd_id,
                'batch' => ($mov === 'Entries' || $mov === 'Positive Adjustment') ? $this->input->post('lote') : $this->input->post('lotes'),
                'batch_deadline' => ($mov === 'Entries' || $mov === 'Positive Adjustment') ? $this->input->post('batch_deadline') : $this->input->post('batch_deadlines')
            );

            $this->m_who_drug_count->update_or_insert_row($wd_id, $count_data);

            $this->session->set_flashdata('msg', 'REC: Drug added for drug_id: ' . $wd_id);
            $this->redirect_if_no_continue('/drug_stock/view/' . $wd_id);
        }
    }




    public function request()
    {
        $data = [];

        $last_code = $this->m_request->get_last_request_code();
        $new_code = $this->generate_new_request_code($last_code);

        $data['request_code'] = $new_code;

        $this->render('form_drug_request', $data);
    }

    private function generate_new_request_code($last_code)
    {
        if ($last_code === null) {
            return '00001';
        }

        $new_code = (int)$last_code + 1;
        return str_pad($new_code, 5, '0', STR_PAD_LEFT);
    }

    public function show_request()
    {
        $data['requests'] = $this->m_request->get_all_requests($this->session->userdata('department'));
        $this->render('show_request', $data);
    }

    public function view_request_items($request_id)
    {
        $request = $this->m_request->get_request_by_id($request_id);
        $request_items = $this->m_request_item->get_items_by_request_id($request_id);

        if (!$request) {
            show_404();
        }

        $data['request_id'] = $request_id;
        $data['request_items'] = array();

        // Verify each item has 'fnm' key before sorting
        foreach ($request_items as $item) {
            if (!array_key_exists('fnm', $item)) {
                // Handle cases where 'fnm' might be missing
                // For example, set a default value or skip the item
            }
        }

        // Sort items by 'fnm' (medication name)
        usort($request_items, function ($a, $b) {
            // Ensure 'fnm' is defined in both $a and $b before comparison
            if (!isset($a['fnm']) || !isset($b['fnm'])) {
                return 0; // Return 0 if 'fnm' is not defined in either $a or $b
            }
            return strcmp($a['fnm'], $b['fnm']);
        });
        foreach ($request_items as $item) {
            // Ensure that who_drug_id is set in each item
            if (!isset($item['who_drugs_id'])) {
                continue; // Skip items without who_drug_id
            }

            // Get drug details by who_drug_id
            $drug_details = $this->m_who_drug->get_drug_by_wd_id($item['who_drugs_id']);

            if ($drug_details) {
                $item_data = array(
                    'fnm' => $drug_details->fnm,
                    'name' => $drug_details->name,
                    'dosage' => $drug_details->dosage,
                    'pharmaceutical_form' => $drug_details->pharmaceutical_form,
                    'requested_quantity' => $item['requested_quantity']
                );

                $data['request_items'][] = $item_data;
            }
        }

        // $this->load->view('view_request_items', $data);
        $this->render('view_request_items', $data);
    }


    public function request_drug($wd_id)
    {
        // Suponha que $this->m_who_drug->get($wd_id) retorna um array de objetos
        return $this->m_who_drug->get_drug_row($wd_id);
    }

    public function dispense_drug()
    {
        
        $national_form_codes = $this->input->post('national_form_code');
        $id = $this->input->post('name');
        $dosages = $this->input->post('dosage');
        $pharmaceutical_forms = $this->input->post('pharmaceutical_form');
        $existing_stocks = $this->input->post('existing_stock');
        $requested_quantities = $this->input->post('requested_quantity');
        $request_code = $this->input->post('request_code');
        $request_type = $this->input->post('request_type');


        $data_request = array(
            'request_code' => $request_code,
            'request_type' => $request_type,
            'CreateUser' => $this->session->userdata('uid'),
            'ref_type' => $this->session->userdata('department')
        );
        $request_id = $this->m_request->insert_get_id($data_request);
        if (!empty($national_form_codes)) {
            foreach ($national_form_codes as $index => $code) {
                $data = array(
                    'who_drugs_id' => $id[$index],
                    'requested_quantity' => $requested_quantities[$index],
                    'request_id' => $request_id,
                );

                // Insert request item data
                $this->m_request_item->insert($data);
            }
        }
        redirect('drug_stock/view_request_items/' . $request_id);
    }


    public function edit_request_item($request_id)
    {
        $this->load->model('M_request');

        $data['request_details'] = $this->M_request->get_request_details($request_id);

        // var_dump($data['request_details']);

        if (empty($data['request_details'])) {
            show_404();
        }

        $this->render('form_edit_request_item', $data);
    }


    public function update_request()
    {
        // Recebe os dados enviados via POST
        $request_id = $this->input->post('request_id');
        $request_code = $this->input->post('request_code');
        $request_type = $this->input->post('request_type');
        $status = $this->input->post('status');
        $requested_by = $this->input->post('requested_by');
        $items = $this->input->post('items');

        try {

            foreach ($items as $item) {
                $item_data = array(
                    'request_id' => $request_id,
                    'who_drugs_id' => $item['who_drugs_id'],
                    'requested_quantity' => $item['requested_quantity']
                );

                $this->m_request->update_request_item($item_data);
            }

            $update_data = array(
                'request_type' => $request_type,
                'status' => $status
            );

            $this->m_request->update_request_status($request_code, $update_data);

            $response = array(
                'status' => 'success',
                'message' => 'Request updated successfully\t'
            );
            echo json_encode($response);
        } catch (Exception $e) {
            // Se ocorrer algum erro durante a transação
            $response = array(
                'status' => 'error',
                'message' => 'Error updating request. Please try again.'
            );
            echo json_encode($response);
        }

        // Responder com sucesso
        $response = array(
            'status' => 'success',
            'message' => 'Request updated successfully'
        );
        echo json_encode($response);
    }


    /* public function show_lote() {
        $current_lote = $this->m_who_drug->get($lote);
        return $current_lote;
    }*/

    function loteStock($type, $wd_id = NULL, $from = NULL, $to = NULL, $lot_number = NULL, $expiration_date = NULL)
    {

        switch ($type) {
            case 'view':
                $data['title'] = 'Validade dos lotes'; //deve ser traduzido para 2 linguas como('Drug Stocks')
                $data['url'] = site_url("drug_stock/Drug_stock/loteStock/print");
                $data['wd_id'] = $wd_id;
                $data['id'] = uniqid("__");
                $data['description'] = 'Lote expiration date.';
                $this->load->vars($data);
                $this->load->view('expiration_date_lote');
                break;
            case 'print':
                $hospital_name = $this->config->item('hospital_name');
                $data['hospital'] =  $hospital_name;
                $data['lot_number'] = $lot_number;
                $data['expiration_date'] = $expiration_date;
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['wd_id'] = $wd_id;
                $this->load->vars($data);
                $this->load->view('');
                break;
        }
    }

    public function add_stock()
    {
        $drug_id = $this->input->post("drug_id");
        $count = $this->input->post("count");
        $drug = $this->m_who_drug->get($drug_id);
        if (!$count || !$drug_id || empty($drug)) {
            echo -1;
            return;
        }
        $this->m_who_drug->update($drug_id, array('count' => $count + $drug->count));
        echo "success";
    }

    public function view()
    {
        if (!has_permission('drug_management', 'view')) {
            $this->show_no_permission();
            return;
        }
        $data['who_drugs'] = $this->m_who_drug->get_many_by(array('Active' => 1));
        //        var_dump($data['who_drug']);
        //        $data['drug_count_list'] = $this->mdrug_stock->get_drug_count_list($drug_stock_id);
        $this->load->vars($data);
        $this->qch_template->load_form_layout('drug_stock_view');
    }

    public function list_existing_batches($wd_id)
    {
        return $this->m_who_drug_count->get_existing_batches_by_wd_id($wd_id);
    }

    public function get_batch_details()
    {
        if ($this->input->is_ajax_request()) {
            $batch = $this->input->post('batch');
            $drug_id = $this->input->post('drug_id');

            $batch_deadline = $this->m_who_drug_count->get_batch_deadline_by_batch($batch);
            $existing_stock = $this->m_who_drug_count->get_existing_stock_by_batch($batch, $drug_id);

            if ($batch_deadline !== null && $existing_stock !== null) {
                echo json_encode(array(
                    'success' => true,
                    'batch_deadline' => $batch_deadline,
                    'existing_stock' => $existing_stock
                ));
            } else {
                echo json_encode(array('success' => false));
            }
        } else {
            show_404();
        }
    }


    public function add_question()
    {
        $quest_id = $_GET["quest_id"];
        $qid = $_GET["qid"];
        if (!$quest_id || !$qid) {
            echo -1;
            return;
        }
        $this->load->database();
        $this->load->model("mpersistent");
        if ($this->is_question_exsist($quest_id, $qid)) {
            echo 0;
            return;
        }
        $count = $this->count_all_question($quest_id);
        echo $this->mpersistent->create("qu_question", array("qu_questionnaire_id" => $quest_id, "qu_question_repos_id" => $qid, "active" => "1", "show_order" => $count + 1));
    }

    function is_question_exsist($quest_id, $qid)
    {
        $this->load->database();
        $this->load->model("mquestionnaire");
        $count = $this->mquestionnaire->count_question($quest_id, $qid);
        if ($count > 0) {
            return true;
        }
        return false;
    }

    function count_all_question($quest_id)
    {
        $this->load->database();
        $this->load->model("mquestionnaire");
        return $this->mquestionnaire->count_all_question($quest_id);
    }

    public function open($id = null)
    {
        $data = array();
        $this->load->database();
        $this->load->model("mquestionnaire");
        $data['questionnaire_info'] = $this->mquestionnaire->get_questionnaire_info($id);
        if (empty($data['questionnaire_info'])) {
            $data['error'] = "Questionnaire not found";
            $this->load->vars($data);
            $this->load->view('questionnaire_error');
            return;
        }
        $data['question_list'] = $this->mquestionnaire->get_question_list($id);
        if (isset($data['question_list']) && count($data['question_list'])) {
            for ($i = 0; $i < count($data['question_list']); ++$i) {
                if ($data['question_list'][$i]['question_type'] == "Select") {
                    $data['select' . $data['question_list'][$i]['qu_question_id']] = $this->mquestionnaire->get_select_data($data['question_list'][$i]['qu_question_id']);
                }
            }
        }
        $data["mode"] = "VIEW";
        $this->load->vars($data);
        $this->load->view('questionnaire_view');
    }

    public function save()
    {
        echo "Data Sent to server...";
    }

    public function drug_exits()
    {
        if (!has_permission('drug_management', 'view')) {
            $this->show_no_permission();
            return;
        }

        $this->qch_template->load_form_layout('drug_outs');
    }

    public function getOutsOn($startDate = null, $endDate = null)
    {

        $result = $this->m_who_drug->getDrugsCountOutsGroupByType($startDate, $endDate);
        header('Content-Type: application/json');

        echo json_encode($result);
    }

    public function drug_entries()
    {
        if (!has_permission('drug_management', 'view')) {
            $this->show_no_permission();
            return;
        }

        $this->qch_template->load_form_layout('drug_entry');
    }

    public function getDrugEntries($startDate = null, $endDate = null)
    {

        $result = $this->m_who_drug->getDrugEntryAndPositiveAdjustmentGroupedByDrug($startDate, $endDate);
        header('Content-Type: application/json');

        echo json_encode($result);
    }




    private function loadMDSPager($fName)
    {
        $path = 'application/forms/' . $fName . '.php';
        require $path;
        $frm = $form;
        $columns = $frm["LIST"];
        $table = $frm["TABLE"];
        $sql = "SELECT ";

        foreach ($columns as $column) {
            $sql .= $column . ',';
        }
        $sql = substr($sql, 0, -1);
        $sql .= " FROM $table ";
        $this->load->model('mpager');
        $this->mpager->setSql($sql);
        $this->mpager->setDivId('prefCont');
        $this->mpager->setSortorder('asc');
        //set colun headings
        $colNames = array();
        foreach ($frm["DISPLAY_LIST"] as $colName) {
            array_push($colNames, $colName);
        }
        $this->mpager->setColNames($colNames);

        //set captions
        $this->mpager->setCaption($frm["CAPTION"]);
        //set row id
        $this->mpager->setRowid($frm["ROW_ID"]);

        //set column models
        foreach ($frm["COLUMN_MODEL"] as $columnName => $model) {
            if (gettype($model) == "array") {
                $this->mpager->setColOption($columnName, $model);
            }
        }

        //set actions
        $action = $frm["ACTION"];
        $this->mpager->gridComplete_JS = "function() {
            var c = null;
            $('.jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                c = $(this).css('background');
                $(this).css({'background':'yellow','cursor':'pointer'});
            }).mouseout(function(e){
                $(this).css('background',c);
            }).click(function(e){
                var rowId = $(this).attr('id');
                window.location='$action'+rowId;
            });
            }";

        //report starts
        if (isset($frm["ORIENT"])) {
            $this->mpager->setOrientation_EL($frm["ORIENT"]);
        }
        if (isset($frm["TITLE"])) {
            $this->mpager->setTitle_EL($frm["TITLE"]);
        }

        //        $pager->setSave_EL($frm["SAVE"]);
        $this->mpager->setColHeaders_EL(isset($frm["COL_HEADERS"]) ? $frm["COL_HEADERS"] : $frm["DISPLAY_LIST"]);
        //report endss

        $data['pager'] = $this->mpager->render(false);
        $data["pre_page"] = $fName;
        $this->load->vars($data);
        $this->load->view('questionnaire');
        //        return "<h1>$sql";
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
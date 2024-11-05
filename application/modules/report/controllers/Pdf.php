<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pdf extends MY_Controller
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {

        parent::__construct();
        $this->load->library('session');
        $this->load->library('session');
        $this->load->model("m_who_drug_count");
        $this->load->model("m_who_drug");
        $this->load->model("m_who_drug_adjustment");
    }

    public function index()
    {
        //$this->load->view('patient');
        $this->report_home();
    }

    public function report_home()
    {

        $data = array();
        $this->load->vars($data);
        $this->load->view('report_home', 1);
    }

    private function getHospital()
    {
        $hospital_name = $this->config->item('hospital_name');
        $data['hospital'] =  $hospital_name;
//        return $this->session->userdata('Hospital');
        return $hospital_name;
    }

    private function getHid()
    {
        return '01110401';
    }

    private function getPid($active_id)
    {
   
       $this->db->select('PID')
        ->from('sap_bill')
        ->where('sap_bill.active_id',$active_id);
         $query = $this->db->get();
         $pid=$query->row_array();
        return $pid['PID'];
    }

    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * pharmacy stock balance
     */
    public function pharmacyBalance($type, $from = null, $to = null)
    {
        switch ($type) {
            case 'view':
                $data['title'] = 'Daily drugs dispensed';
                $data['url'] = site_url("report/pdf/pharmacyBalance/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Drugs dispensed daily';
                $this->load->vars($data);
                $this->load->view('date_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $this->load->vars($data);
                $this->load->view('pdf/pharmacy_balance');
                break;
        }
    }

    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * pharmacy current stock report
     */
    public function pharmacyCurrentStock($type, $stockId = null)
    {

        switch ($type) {
            case 'view':
                $data['title'] = 'Create Drug Order';
                $data['url'] = site_url("report/pdf/pharmacyCurrentStock/print");
                $data['id'] = uniqid("_");
                $query = $this->db->get("drug_stock");
                $options = "<select id='stock_from'>";
                foreach ($query->result() as $row) {
                    $options .= "<option value='" . $row->drug_stock_id . "'>" . $row->name . "</option>";
                }
                $options .= "</select>";
                $data['stocks'] = $options;
                $this->load->vars($data);
                $this->load->view('current_stock_selector');
                break;
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['drug_stock'] = $this->db->get_where("drug_stock", array("drug_stock_id" => $stockId))->row();

                $this->db->select("who_drug.name,who_drug_count");
                $this->db->from("drug_count");
                $this->db->join("who_drug", "drug_count.who_drug_id=who_drug.wd_id");
                $this->db->where("drug_stock_id", $stockId);
                $this->db->where("who_drug.Active", 1);
                $data["query"] = $this->db->get();
                $this->load->vars($data);
                $this->load->view('pdf/pharmacy_current_stock');
                break;
        }

    }

    /**
     * @param      $type
     * @param null $ops
     * pharmacy order drugs from stock
     */
    public function drugOrder($type, $minStock, $stockId)
    {

        switch ($type) {
            case 'view':
                $data['title'] = 'Create Drug Order';
                $data['url'] = site_url("report/pdf/drugOrder/print");
                $data['id'] = uniqid("_");

                $query = $this->db->get("drug_stock");
                $options = "<select id='stock_from'>";
                foreach ($query->result() as $row) {
                    $options .= "<option value='" . $row->drug_stock_id . "'>" . $row->name . "</option>";
                }
                $options .= "</select>";
                $data['stocks'] = $options;
                $this->load->vars($data);
                $this->load->view('stock_selector');
                break;
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['drug_stock'] = $this->db->get_where("drug_stock", array("drug_stock_id" => $stockId))->row();

                $this->db->select("who_drug.name,who_drug_count");
                $this->db->from("drug_count");
                $this->db->join("who_drug", "drug_count.who_drug_id=who_drug.wd_id");
                $this->db->where("drug_stock_id", $stockId);
                $this->db->where("Active", 1);
                $this->db->where("who_drug_count <", $minStock);
                $data["query"] = $this->db->get();
                $data["min_stock"] = $minStock;
                $this->load->vars($data);
                $this->load->view('pdf/pharmacy_drug_order');
                break;
        }

    }

    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * print previous prescriptions by date
     */
    public function prescriptions($type, $from = null, $to = null)
    {

        switch ($type) {
            case 'view':
                $data['title'] = 'OPD Prescriptions';
                $data['url'] = site_url("report/pdf/prescriptions/print");
                $data['id'] = uniqid("_");
//                $data['description'] = 'OPD';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/clinical_prescriptions');
                break;
        }

    }

    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * print previous prescriptions by drug
     */
    public function prescriptionsByDrug($type, $from = null, $to = null)
    {

        switch ($type) {
            case 'view':
                $data['title'] = 'Prescription By Drug';
                $data['url'] = site_url("report/pdf/prescriptionsByDrug/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print prescription by drug.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/drug_statistics');
                break;
        }

    }

    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * print encounter stats
     */
    public function encounters($type, $from = null, $to = null)
    {

        switch ($type) {
            case 'view':
                $data['title'] = 'Estatísticas de Visita';
                $data['url'] = site_url("report/pdf/encounters/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print the encounter statistics in given date period.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/patient_registry');
                break;
        }
    }

    public function inwardStatistic($type, $date = null, $ward = null)
    {
        $this->load->model('m_ward');
        switch ($type) {
            case 'view':
                $all_ward = $this->m_ward->order_by('Name', 'asc')->dropdown('WID', 'Name');
//                $all_ward['All'] = 'All';
                $data['ward'] = $all_ward;
                $data['title'] = 'Estatísticas internas';
                $data['url'] = site_url("report/pdf/inwardStatistic/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print the inward statistics in given date.';
                $this->load->vars($data);
                $this->load->view('date_selector_ward');
                break;
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['hid'] = $this->getHid();
                $data['ward'] = $ward;
                $data['date'] = $date;
                $ward = $this->m_ward->get($ward);
                if (empty($ward)) {
                    die('Wrong ward');
                }
                $data['ward_name'] = $ward->Name;
                $this->load->vars($data);
                $this->load->view('pdf/inward_statistic');
                break;
        }
    }

    public function biopsy_report($type, $biopsy_id)
    {
        $this->load->model('m_user');
        $this->load->model('m_doctor');
        $this->load->model('m_patient');
        $this->load->model('m_patient_active_list');
        $this->load->model('m_pa_visit');
        $this->load->model('m_pathological_anatomy_order');
        $this->load->model('m_pathological_anatomy_tests');
        $this->load->model('m_pa_biopsy_order');
        switch ($type) {
            case 'print':
                $biopsy_order = $this->m_pa_biopsy_order->get($biopsy_id);
                $PA_order = $this->m_pathological_anatomy_order->with('collected_by')->get($biopsy_order->PA_order_ID);
                $PA_visit = $this->m_pa_visit->get($PA_order->PA_ID);
                $active_list = $this->m_patient_active_list->get($PA_visit->ActiveListID);
                $patient = $this->m_patient->get($PA_order->PID);

                $data['hospital'] = $this->getHospital();
                $data['hid'] = $this->getHid();
                $data['PA_order'] = $PA_order;
                $data['biopsy_order'] = $biopsy_order;
                $technician = $PA_order->collected_by;
                $data['collected_by'] = $technician->Title. ' '. $technician->Name. ' '. $technician->OtherName;
                $data['PA_visit'] = $PA_visit;
                $data['active_list'] = $active_list;
                $data['patient'] = $patient;
                $this->load->vars($data);
                $this->load->view('pdf/biopsy_report');
                break;
        }
    }

    public function cytology_report($type, $cytology_id)
    {
        $this->load->model('m_user');
        $this->load->model('m_doctor');
        $this->load->model('m_patient');
        $this->load->model('m_patient_active_list');
        $this->load->model('m_pa_visit');
        $this->load->model('m_pathological_anatomy_order');
        $this->load->model('m_pathological_anatomy_tests');
        $this->load->model('m_pa_cytology_order');
        switch ($type) {
            case 'print':
                $cytology_order = $this->m_pa_cytology_order->get($cytology_id);
                $PA_order = $this->m_pathological_anatomy_order->with('collected_by')->get($cytology_order->PA_order_ID);
                $PA_visit = $this->m_pa_visit->get($PA_order->PA_ID);
                $active_list = $this->m_patient_active_list->get($PA_visit->ActiveListID);
                $patient = $this->m_patient->get($PA_order->PID);

                $data['default_value'] = array($cytology_order->Washes_Info, $cytology_order->Others_Liquid_Info,
                    $cytology_order->Ganglion_Info, $cytology_order->Soft_Tissues_Info, $cytology_order->Others_PAAF_Info,
                    $cytology_order->Clinical_Diagnosis_Liquid, $cytology_order->Clinical_Diagnosis_PAAF);

                $data['group1_options'] = array();
                $data['group2_options'] = array();
                $group = 1;
                $fields = $this->db->field_data('pa_cytology_order');
                foreach($fields as $col) {
                    if ($group == 1) {
                        if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                            $data['group1_options'][$col->name] = lang($col->name);
//                    array_push($data['group1_options'], $col->name);
                        }
                        if($col->name == 'Others_Liquid'){
                            $group = 2;
                        }
                    } else{
                        if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                            $data['group2_options'][$col->name] = lang($col->name);
//                    array_push($data['group2_options'], $col->name);
                        }
                    }
                }

                $group = 1;
                $data['checked1'] = array();
                $data['checked2'] = array();
                foreach($fields as $col) {
                    $field_name = $col->name;
                    $col_name = $cytology_order->$field_name;
                    if ($group == 1) {
                        if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                            if ($col_name == 1) {
                                array_push($data['checked1'], $col->name);
                            }
                        }
                        if($col->name == 'Others_Liquid'){
                            $group = 2;
                        }
                    } else{
                        if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                            if ($col_name == 1) {
                                array_push($data['checked2'], $col->name);
                            }
                        }
                    }
                }

                $data['hospital'] = $this->getHospital();
                $data['hid'] = $this->getHid();
                $data['PA_order'] = $PA_order;
                $data['cytology_order'] = $cytology_order;
                $technician = $PA_order->collected_by;
                $data['collected_by'] = $technician->Title. ' '. $technician->Name. ' '. $technician->OtherName;
                $data['PA_visit'] = $PA_visit;
                $data['active_list'] = $active_list;
                $data['patient'] = $patient;
                $this->load->vars($data);
                $this->load->view('pdf/cytology_report');
                break;
        }
    }

    public function cv_cytology_report($type, $cv_cytology_id)
    {
        $this->load->model('m_user');
        $this->load->model('m_doctor');
        $this->load->model('m_patient');
        $this->load->model('m_patient_active_list');
        $this->load->model('m_pa_visit');
        $this->load->model('m_pathological_anatomy_order');
        $this->load->model('m_pathological_anatomy_tests');
        $this->load->model('m_pa_cv_cytology_order');
        switch ($type) {
            case 'print':
                $cv_cytology_order = $this->m_pa_cv_cytology_order->get($cv_cytology_id);
                $PA_order = $this->m_pathological_anatomy_order->with('collected_by')->get($cv_cytology_order->PA_order_ID);
                $PA_visit = $this->m_pa_visit->get($PA_order->PA_ID);
                $active_list = $this->m_patient_active_list->get($PA_visit->ActiveListID);
                $patient = $this->m_patient->get($PA_order->PID);

                $data['default_value'] = array($cv_cytology_order->Contraception_Other_Info, $cv_cytology_order->Tratamento_Anterior_Other_Info);

                $data['group1_options'] = array();
                $data['group2_options'] = array();
                $group = 1;
                $fields = $this->db->field_data('pa_cytology_order');
                foreach($fields as $col) {
                    if ($group == 1) {
                        if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                            $data['group1_options'][$col->name] = lang($col->name);
//                    array_push($data['group1_options'], $col->name);
                        }
                        if($col->name == 'Others_Liquid'){
                            $group = 2;
                        }
                    } else{
                        if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                            $data['group2_options'][$col->name] = lang($col->name);
//                    array_push($data['group2_options'], $col->name);
                        }
                    }
                }

                $data['group1_options'] = array();
                $data['group2_options'] = array();
                $data['group3_options'] = array();
                $data['group4_options'] = array();
                $group = 1;
                $fields = $this->db->field_data('pa_cv_cytology_order');
                foreach($fields as $col) {
                    if ($group == 1) {
                        if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                            $data['group1_options'][$col->name] = lang($col->name);
                        }
                        if($col->name == 'Polyp'){
                            $group = 2;
                        }
                    } elseif ($group == 2){
                        if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                            $data['group2_options'][$col->name] = lang($col->name);
                        }
                        if($col->name == 'Chlamydia'){
                            $group = 3;
                        }
                    } elseif ($group == 3){
                        if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                            $data['group3_options'][$col->name] = lang($col->name);
                        }
                        if($col->name == 'Contraception_Other'){
                            $group = 4;
                        }
                    } elseif ($group == 4){
                        if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                            $data['group4_options'][$col->name] = lang($col->name);
                        }
                    }
                }

                $group = 1;
                $data['checked1'] = array();
                $data['checked2'] = array();
                $data['checked3'] = array();
                $data['checked4'] = array();
                foreach($fields as $col) {
                    $field_name = $col->name;
                    $col_name = $cv_cytology_order->$field_name;
                    if ($group == 1) {
                        if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                            if ($col_name == 1) {
                                array_push($data['checked1'], $col->name);
                            }
                        }
                        if($col->name == 'Polyp'){
                            $group = 2;
                        }
                    } elseif ($group == 2) {
                        if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                            if ($col_name == 1) {
                                array_push($data['checked2'], $col->name);
                            }
                        }
                        if($col->name == 'Chlamydia'){
                            $group = 3;
                        }
                    } elseif ($group == 3) {
                        if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                            if ($col_name == 1) {
                                array_push($data['checked3'], $col->name);
                            }
                        }
                        if($col->name == 'Contraception_Other'){
                            $group = 4;
                        }
                    } elseif ($group == 4) {
                        if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                            if ($col_name == 1) {
                                array_push($data['checked4'], $col->name);
                            }
                        }
                    }
                }

                $data['hospital'] = $this->getHospital();
                $data['hid'] = $this->getHid();
                $data['PA_order'] = $PA_order;
                $data['cv_cytology_order'] = $cv_cytology_order;
                $technician = $PA_order->collected_by;
                $data['collected_by'] = $technician->Title. ' '. $technician->Name. ' '. $technician->OtherName;
                $data['PA_visit'] = $PA_visit;
                $data['active_list'] = $active_list;
                $data['patient'] = $patient;
                $this->load->vars($data);
                $this->load->view('pdf/cv_cytology_report');
                break;
        }
    }

    public function service($type, $from = null, $to = null, $service = null)
    {
        $this->load->model('m_hospital_service');
        switch ($type) {
            case 'view':
                $all_service = $this->m_hospital_service->order_by('abrev', 'asc')->dropdown('service_id', 'abrev');
                $all_service['All'] = 'All';
                $data['services'] = $all_service;
                $data['title'] = 'Geração Serviços';
                $data['url'] = site_url("report/pdf/service/print");
                $data['id'] = uniqid("_");
                $data['description'] = '';
                $this->load->vars($data);
                $this->load->view('date_range_selector_service');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['service'] = $service;
                $service = $this->m_hospital_service->get($service);
                if (empty($service)) {
                    die('Wrong service');
                }
                $data['service_name'] = $service->abrev;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/service');
                break;
        }
    }

    public function registration($type, $from = null, $to = null)
    {

        switch ($type) {
            case 'view':
                $data['title'] = lang('Registration Statistics');
                $data['url'] = site_url("report/pdf/registration/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print the encounter statistics in given date period.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/patient_registration_stats');
                break;
        }
    }


    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * print visit details
     */
    public function visitDetails($type, $from = null, $to = null)
    {

        switch ($type) {
            case 'view':
                $data['title'] = 'Visit Details';
                $data['url'] = site_url("report/pdf/visitDetails/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print the visit details in given date period.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/patient_opd_registry');
                break;
        }
    }

    /**
     * @param      $type
     * @param null $from
     * @param null $to
     * @param null $vtype
     * @param null $sort
     * visit complaints during given time period
     */
    public function visitComplaints($type, $from = null, $to = null, $vtype = null, $sort = null)
    {

        switch ($type) {
            case 'view':
                $data['title'] = 'Visit Complaints';
                $data['url'] = site_url("report/pdf/visitComplaints/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print the visit complaints in given date period.';
                $this->load->vars($data);
                $this->load->view('visit_complaints_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['visitType'] = $vtype;
                $data['sort'] = $sort;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/visit_complaints_treated');
                break;
        }
    }

    /**
     * @param $type
     * @param $pid
     * print patient slip
     */
    public function patientSlip($type, $pid)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['pid'] = $pid;
                $this->load->vars($data);
                $this->load->view('pdf/patient_slip');
                break;
        }

    }

    /**
     * @param $type
     * @param $pid
     * print patient card with bar codes
     */
    public function patientCard($type, $pid)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['pid'] = $pid;
                $this->load->view('pdf/patient_cards', $data);
                break;
        }

    }


    /**
     * @param $type
     * @param $pid
     * Print patient summery
     */
    public function patientSummery($type, $pid)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['pid'] = $pid;
                $this->load->vars($data);
                $this->load->view('pdf/patient_summery');
                break;
        }

    }


    /**
     * @param $type
     * @param $aid
     * print admission bht of $aid
     */
    public function admissionBHT($type, $aid)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['adminId'] = $aid;
                $this->load->vars($data);
                $this->load->view('pdf/admission_bht');
                break;
        }

    }

    /**
     * @param $type
     * @param $aid
     * print admission transfer letter
     */
    public function admissionTransfer($type, $aid)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['adminId'] = $aid;
                $this->load->vars($data);
                $this->load->view('pdf/admission_transfer');
                break;
        }

    }

    /**
     * @param $type
     * @param $aid
     * print admission transfer letter
     */
    public function admissionDischarge($type, $aid)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['adminId'] = $aid;
                $this->load->vars($data);
                $this->load->view('pdf/discharge_ticket');
                break;
        }

    }


    /**
     * @param $type
     * @param $opdId
     * print opd prescription on patient overview
     */
    public function opdPrescription($type, $prisId)
    {

        switch ($type) {
            case 'print':
                $this->load->model('mpersistent');
                $this->load->model('mopd');
                $this->load->model('mpatient');
                $this->load->helper('string');
                $data['hospital'] = $this->getHospital();
                $data['prisId'] = $prisId;
                $data["opd_presciption_info"] = $this->mpersistent->open_id($prisId, "opd_presciption", "PRSID");
                $data["prescribe_items_list"] = $this->mopd->get_prescribe_items($prisId);
                if (isset($data["prescribe_items_list"])) {
                    for ($i = 0; $i < count($data["prescribe_items_list"]); ++$i) {
                        if ($data["prescribe_items_list"][$i]["drug_list"] == "who_drug") {
                            $drug_info = $this->mpersistent->open_id(
                                $data["prescribe_items_list"][$i]["DRGID"], "who_drug", "wd_id"
                            );

                        }
                        $data["prescribe_items_list"][$i]["drug_name"] = isset($drug_info["name"]) ? $drug_info["name"]
                            : '';
                    }
                }
                if ($data["opd_presciption_info"]["OPDID"] > 0) {
                    $data["opd_visits_info"] = $this->mopd->get_info($data["opd_presciption_info"]["OPDID"]);
                }
                if ($data["opd_visits_info"]["PID"] > 0) {
                    $data["patient"] = $this->mpersistent->open_id(
                        $data["opd_visits_info"]["PID"], "patient", "PID"
                    );
                }
                $this->load->vars($data);
                $this->load->view('pdf/opd_prescription');
                break;
        }

    }

    /**
     * @param $type
     * @param $opdId
     * print opd lab test on patient overview
     */
    public function opdLabtests($type, $opdId)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['opdId'] = $opdId;
                $this->load->vars($data);
                $this->load->view('pdf/opd_diagnostic_tests');
                break;
        }

    }

    /**
     * @param     $type
     * @param int $year
     * @param int $quarter
     * print hospital immr for given year and quarter
     */
    public function immr($type, $year = 2011, $quarter = 1)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['hospitalId'] = $this->session->userdata('HID');
                $data['year'] = $year;
                $data['quarter'] = $quarter;
                $this->load->vars($data);
                $this->load->view('pdf/immr');
                break;
            case 'view':
                $data['title'] = 'Hospital IMMR';
                $data['url'] = site_url("report/pdf/immr/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Print hospital immr report.';
                $this->load->helper('hdate');
                $data['quarter'] = currentQuarter();
                $this->load->vars($data);
                $this->load->view('immr_info_selector');
                break;
        }

    }

    public function hospitalPerformance($type, $from = null, $to = null)
    {

        switch ($type) {
            case 'view':
                $data['title'] = 'Hospital performances';
                $data['url'] = site_url("report/pdf/hospitalPerformance/print");
                $data['id'] = uniqid("_");
//                $data['description'] = 'Print the visit details in given date period.';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/hospital_indicator');
                break;
        }
    }

    /**
     * @param $type
     * @param $notificationId
     */
    public function notification($type, $notificationId)
    {

        switch ($type) {
            case 'print':
                $data = array();
                $notification = $this->load->model('mnotification');
                $notification->load($notificationId);
                $data['notification'] = $notification;
                if ($notification->getValue("Episode_Type") == 'admission') {
                    $admid = $notification->getValue("EPISODEID");
                    $admission = $this->load->model('madmission', 'admission');
                    $admission->openId($admid);
                    $data['admission'] = $admission;
                    $patient = $this->load->model('mpatient', 'patient')->load($admission->PID);
                    $this->patient = $patient;
                    $ward = $this->load->model('mward', 'ward')->OpenId($admission->getValue("Ward"));
                    $data['ward'] = $ward;
                    $doctor = $this->load->model('muser', 'doctor');
                    $doctor->openId($notification->getValue("ConfirmedBy"));
                    $data['epicode_type'] = "Admission";
                    $data['subject']
                        =
                        $notification->getValue("Disease") . " in " . $patient->getValue("Address_Village")
                        . " (NOTIFICATION)";
                } else {
                    if ($notification->getValue("Episode_Type") == 'opd') {
                        $opdid = $notification->getValue("EPISODEID");
                        $opd = $this->load->model('mopd', 'opd');
                        $opd->openId($opdid);
                        $data['opd'] = $opd;
                        $data['epicode_type'] = "Opd";
                        $patient = $this->load->model('mpatient', 'patient')->load($opd->PID);
                        $doctor = $this->load->model('muser', 'doctor');
                        $doctor->openId($notification->getValue("ConfirmedBy"));

                    } else {
                        echo " Episode not found";
                    }
                }
                $data['doctor'] = $doctor;
                $data['subject']
                    = $notification->getValue("Disease") . " in " . $patient->getValue("Address_Village")
                    . " (NOTIFICATION)";
                $data['hospital'] = $this->load->model('mhospital', 'hospital')->load($patient->HID);
                $data['patient'] = $patient;
                if ($notification->getValue("LabConfirm") == 1) {
                    $pat_lab_d = "Yes";
                } else {
                    $pat_lab_d = "No";
                }
                $data['pat_lab_d'] = $pat_lab_d;
                $data['user'] = $this->load->model('muser', 'user')->load($this->session->userdata('UID'));
                $this->load->vars($data);
                $this->load->view('pdf/notification');
                break;
        }

    }

    /**
     * @param $type
     * @param $opdId
     */
    public function clinicBook($type, $opdId)
    {

        switch ($type) {
            case 'print':
                $this->load->model('mpersistent');
                $this->load->model('mopd');
                $this->load->model('mpatient');
                $data["opd_visits_info"] = $this->mopd->get_info($opdId);

                if (isset($data["opd_visits_info"]["PID"])) {
                    $data["patient_info"] = $this->mpersistent->open_id(
                        $data["opd_visits_info"]["PID"], "patient", "PID"
                    );
                    $data["patient_allergy_list"] = $this->mpatient->get_allergy_list(
                        $data["opd_visits_info"]["PID"]
                    );
                    $data["patient_exams_list"] = $this->mpatient->get_exams_list(
                        $data["opd_visits_info"]["PID"]
                    );
                    $data["patient_history_list"] = $this->mpatient->get_history_list(
                        $data["opd_visits_info"]["PID"]
                    );
                    $data["patient_lab_order_list"] = $this->mpatient->get_lab_order_list(
                        $data["opd_visits_info"]["PID"]
                    );
                    $data["patient_prescription_list"] = $this->mpatient->get_prescription_list($opdId);
                    $data["patient_treatment_list"] = $this->mpatient->get_treatment_list($opdId);
                    foreach ($data["patient_prescription_list"] as $prescription) {
                        $data["prescribe_items_list"][$prescription['PRSID']] = $this->mopd->get_prescribe_items(
                            $prescription['PRSID']
                        );
                    }
                }
                $this->load->vars($data);
                $this->load->view('pdf/clinic_book');
                break;
        }

    }

    /**
     * @param $type
     * @param $appId
     */
    public function appointment($type, $appId)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['appId'] = $appId;
                $config = $this->config->item('report');
                $data['token_text'] = $config['token_text'];
                $this->load->vars($data);
                $this->load->view('pdf/token');
                break;
        }

    }

    /**
     * @param $type
     */
    public function outsidePrescription($type)
    {

        switch ($type) {
            case 'print':
                $items = $this->input->get('print');
                $pId = $this->input->get('pid');
                $this->load->model('mpatient');
                $this->load->model('mprescribe_items');
                $this->load->model('mpersistent');
                $this->load->model('mopd_prescription');
                $data['patient'] = $this->mpatient->load($pId);

                if (!$items) {
                    echo 'Please select items to print';
                    exit;
                }
                if (!isset($data['patient'])) {
                    echo 'Patient doesn\'t exixts';
                    exit;
                }

                foreach ($items as $id) {
                    unset($pItem);
                    $pItem = $this->mprescribe_items->load($id);
                    $presId = $pItem->PRES_ID;
                    if ($pItem->drug_list == "who_drug") {
                        $drug_info = $this->mpersistent->open_id(
                            $pItem->DRGID, "who_drug", "wd_id"
                        );

                    }
                    $pItem->drug_name = $drug_info["name"];
                    $pItem->drug_dose = $drug_info["dose"];
                    $pItem->drug_formulation = $drug_info["formulation"];
                    $data['items'][] = clone $pItem;
                }


                $data['prescription'] = $this->mopd_prescription->load($presId);
                $data['hospital'] = $this->getHospital();
                $data['date'] = date('Y-m-d H:i:s');
                $data['pageHeight'] = $this->getPrescriptionPageHeight(
                    $data['patient'], $data['hospital'], $data['date'], $data['items'], $data['prescription']
                );
                $this->load->vars($data);
                $this->load->view('pdf/prescription');
                break;
        }

    }

    /**
     * @param $type
     * @param $date
     */
    public function dailyVisits($type, $date)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $this->load->vars($data);
                $this->load->view('pdf/daily_visits');
                break;
        }
    }

    public function dailyReservation($type, $date)
    {

        switch ($type) {
            case 'print':
                $this->load->model('m_hospital_service');
                $data['all_service'] = $this->m_hospital_service->order_by(array('department_id' => 'asc', 'abrev' => 'asc'))->get_all();
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $this->load->vars($data);
                $this->load->view('pdf/daily_reservation');
                break;
        }
    }

    /**
     * @param $type
     * @param $date
     */
    public function dailyRegistration($type, $date)
    {
        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $this->load->vars($data);
                $this->load->view('pdf/daily_registration');
                break;
        }
    }

    public function dailyActiveList($department, $type, $date)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $data['department'] = $department;
                $this->load->vars($data);
               // $this->load->view('pdf/clinic_active_list');
                    $this->load->view('pdf/daily_active_list');
                break;
        }
    }

    /**
     * @param $type
     * @param $date
     */
    public function dailyAdmissions($type, $date)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $this->load->vars($data);
                $this->load->view('pdf/daily_admissions');
                break;
        }
    }

    /**
     * @param $type
     * @param $date
     */
    public function dailyDischarges($type, $date)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $this->load->vars($data);
                $this->load->view('pdf/daily_discharges');
                break;
        }
    }

    /**
     * @param $type
     * @param $date
     */
    public function patientBoletim($type, $pid)
    {
        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['hid'] = $this->getHid();
                $data['pid'] = $pid;

                $queryProv = "select * from patient where PID=$pid";
                $resultProv = $this->db->query($queryProv);
                $patientP = $resultProv->first_row();
                $date = $patientP->DateOfBirth;
                //check if the Patient is from Child
                $dob = new DateTime($date);
                $now = new DateTime();
                $idade = $now->diff($dob)->y;
                $this->load->vars($data);
                if ($idade < 15) {
                    $this->load->view('pdf/patient_boletim_sup');
                } else {
                    $this->load->view('pdf/patient_boletim');
                }
                break;
        }
    }

    public function patientBoletimOpd($type, $pid)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['hid'] = $this->getHid();
                $data['pid'] = $pid;
                $this->load->vars($data);
                $this->load->view('pdf/patient_boletim_opd');
                break;
        }
    }


    public function getAge($year, $month, $day)
    {
        $date = "$year-$month-$day";
//if(version_compare(PHP_VERSION, '5.3.0') >= 0){
        $dob = new DateTime($date);
        $now = new DateTime();
        return $now->diff($dob)->y;
//}
    }

    public function patientBoletimSup($type, $pid)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['hid'] = $this->getHid();
                $data['pid'] = $pid;

                $queryProv = "select * from patient where PID=$pid";
                $resultProv = $this->db->query($queryProv);
                $patientP = $resultProv->first_row();
                $date = $patientP->DateOfBirth;
                //check if the Patient is from Child
                $dob = new DateTime($date);
                $now = new DateTime();
                $idade = $now->diff($dob)->y;
                $this->load->vars($data);
                if ($idade < 15) {
                    $this->load->view('pdf/patient_boletim_sup');
                } else {
                    $this->load->view('pdf/patient_boletim');
                }
                break;
        }
    }

    public function clinicalProcess($type, $pid)
    {
        switch ($type) {
            case 'print':

                $this->load->model('m_patient');

                $result = $this->m_patient->get_patient_info($pid);
                $data['hospital'] = $this->getHospital();
                $data['hid'] = $this->getHid();
                $data['pid'] = $pid;
                $data['result_patient_info'] = $result;
              
                // Fetch patient birth date
                $queryProv = "SELECT * FROM patient WHERE PID = $pid";
                $resultProv = $this->db->query($queryProv);
                $patientP = $resultProv->first_row();
                $date = $patientP->DateOfBirth;
    
                // Calculate patient age
                $dob = new DateTime($date);
                $now = new DateTime();
                $idade = $now->diff($dob)->y;
    
                $this->load->vars($data);
    
                // Load the appropriate view based on age
                if ($idade < 15) {
                    $this->load->view('pdf/clinical_process');
                } else {
                    $this->load->view('pdf/clinical_process');
                }
                break;
        }
    }
    

    public function patientBoletimSap($type, $pid)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['hid'] = $this->getHid();
                $data['pid'] = $pid;
                $this->load->vars($data);
                $this->load->view('pdf/patient_boletim_sap');
                break;
        }
    }

    /**
     * @param $type
     * @param $date 27.11.2018
     */
    public function patientBill($type, $pid)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['hid'] = $this->getHid();
                $data['pid'] = $pid;
                $this->load->vars($data);
                $this->load->view('pdf/patient_bill');
                break;
        }
    }

    public function clinicBill($type, $active_id)
    {


        $pid=$this->getPid($active_id);
        $query2="select * from patient where PID=$pid";
        $result2=$this->db->query($query2);
        $patient=$result2->first_row();

        

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['hid'] = $this->getHid();
                $data['active_id'] = $active_id;
                $data['p_id'] = $this->getHid().''.substr($patient->CreateDate,0,4).''.$this->getPid($active_id);
                $data['p_name'] = $patient->Firstname.' '.$patient->Name;
                $data['p_telephone'] = $patient->Telephone;
                $data['p_address'] = $patient->Address_Street;
                $data['p_nuit'] = $patient->NUIT_ID;
                $this->load->vars($data);
                $this->load->view('pdf/consultant_bill');
                break;
        }
    }

    public function clinicBillUser($type, $active_id)
    {


$pid=$this->getPid($active_id);
$query2="select * from patient where PID=$pid";
$result2=$this->db->query($query2);
$patient=$result2->first_row();
   
        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['hid'] = $this->getHid();
                $data['active_id'] = $active_id;
                $data['p_id'] = $this->getHid().''.substr($patient->CreateDate,0,4).''.$this->getPid($active_id);
                $data['p_name'] = $patient->Firstname.' '.$patient->Name;
                $data['p_telephone'] = $patient->Telephone;
                $data['p_address'] = $patient->Address_Street;
                $data['p_nuit'] = $patient->NUIT_ID;
                $this->load->vars($data);
                $this->load->view('pdf/consultant_bill_user');
                break;
        }
    }

    /**
     * @param $type
     * @param $date
     */
    public function dailyClinics($type, $date)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $query = "SELECT
                              qu_quest_answer.*,
                              concat(p.Personal_Title, ' ', p.Firstname) AS name,
                              p.PID,p.HIN,
                              qu_questionnaire.name                                 AS qu_name,
                              c.name                                                AS clinic
                            FROM qu_quest_answer
                              LEFT JOIN `qu_questionnaire` ON qu_questionnaire.qu_questionnaire_id = qu_quest_answer.qu_questionnaire_id
                              LEFT JOIN clinic_patient AS cp
                                ON qu_quest_answer.link_id = cp.clinic_patient_id
                              LEFT JOIN clinic AS c
                                ON cp.clinic_id = c.clinic_id
                              LEFT JOIN patient AS p
                                ON cp.PID = p.PID
                            WHERE (1 = 1) AND (qu_quest_answer.link_type = 'clinic_patient')
                                  AND (qu_quest_answer.Active = 1) AND qu_quest_answer.CreateDate LIKE '$date%'
                            GROUP BY p.PID
                            ORDER BY p.Firstname";

                $data['result'] = $this->db->query($query);
                $this->load->vars($data);
                $this->load->view('pdf/daily_clinics');
                break;
        }
    }

    /**
     * @param $type
     * @param $pid
     */
    public function clinicToken($type, $cPid, $cId)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $this->load->model('mclinic_patient');
                $clinic = $this->mclinic_patient->load($cPid);
                $data['clinic'] = $clinic;
                $this->load->model('mpatient');
                $data['patient'] = $this->mpatient->load($clinic->PID);
                $this->load->model('mclinic');
                $data['clinic1'] = $this->mclinic->load($clinic->clinic_id);
                $this->load->vars($data);
                $this->load->view('pdf/clinic_token');
                break;
        }

    }

    /**
     * @param $type
     * @param $date
     */
    public function laboratoryTestsDone($type, $from = null, $to = null)
    {

        switch ($type) {
            case 'view':
                $data['title'] = 'Lab tests carried out';
                $data['url'] = site_url("report/pdf/laboratoryTestsDone/print");
                $data['id'] = uniqid("_");
                $data['description'] = 'Lab tests carried out';
                $this->load->vars($data);
                $this->load->view('date_range_selector');
                break;
            case 'print':
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['hospital'] = $this->getHospital();
                $this->load->vars($data);
                $this->load->view('pdf/labtests');
                break;
        }
    }

    public function admissionSummary($type, $aId)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['adminId'] = $aId;
                if (!isset($aId) || (!is_numeric($aId))) {
                    $data["error"] = "Admission visit not found";
                    $this->load->vars($data);
                    $this->load->view('admission_error');

                    return;
                }
                $this->load->model('mpersistent');
                $this->load->model('madmission');
                $this->load->model('mpatient');
                $data["admission_drug_order"] = null;
                $data["admission_drug_list"] = null;
                $data["admission_info"] = $this->madmission->get_info($aId);
                $data["admission_lab_order_list"] = $this->madmission->get_lab_order_list(
                    $data["admission_info"]["ADMID"]
                );
                $data["admission_drug_order"] = $this->madmission->get_drug_order($data["admission_info"]["ADMID"]);
                if (isset($data["admission_drug_order"]["admission_prescription_id"])) {
                    $data["admission_drug_list"] = $this->madmission->get_drug_order_list(
                        $data["admission_drug_order"]["admission_prescription_id"]
                    );
                }

                if ($data["admission_info"]["PID"] > 0) {
                    $data["patient_info"] = $this->mpersistent->open_id(
                        $data["admission_info"]["PID"], "patient", "PID"
                    );
                    $data["patient_allergy_list"] = $this->mpatient->get_allergy_list($data["admission_info"]["PID"]);
                } else {
                    $data["error"] = "Admission Patient  not found";
                    $this->load->vars($data);
                    $this->load->view('admission_error');

                    return;
                }
                if (empty($data["patient_info"])) {
                    $data["error"] = "Admission Patient not found";
                    $this->load->vars($data);
                    $this->load->view('admission_error');

                    return;
                }
                if (isset($data["patient_info"]["DateOfBirth"])) {
                    $data["patient_info"]["Age"] = Modules::run(
                        'patient/get_age', $data["patient_info"]["DateOfBirth"]
                    );
                }
                $data["PID"] = $data["admission_info"]["PID"];
                $data["ADMID"] = $aId;

                $this->load->vars($data);
                $this->load->view('pdf/admission_summary');
                break;
        }

    }

    public function printLabTests($type)
    {

        switch ($type) {
            case 'print':
                $data = array();
                $items = $this->input->get('print');
                $pId = $this->input->get('pid');
                if (!is_array($items) && !empty($items)) {
                    echo "Please select items to print";

                    return;
                }
                $this->load->model('mpersistent');
                $this->load->model('mlaboratory');
                $this->load->model('mpatient');

                $data['patient'] = $this->mpatient->load($pId);
                if (!$data['patient']) {
                    echo "Invalid patient id";

                    return;
                }
                foreach ($items as $item) {
                    $data["orederd_test_list"][] = $this->mlaboratory->get_ordered_lab_item($item);
                }
                $data['hospital'] = $this->getHospital();
                $data['date'] = date('Y-m-d H:i:s');
                $data['pageHeight'] = $this->getLabOrderPageHeight(
                    $data['patient'], $data['hospital'], $data['date'], $data["orederd_test_list"]
                );
                $this->load->vars($data);
                $this->load->view('pdf/laborder');
                break;
        }

    }


    public function midnightCensus($type, $date)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $this->load->model("m_ward");
                $data['wards'] = $this->mward->getCollection();
                $this->load->vars($data);
                $this->load->view('pdf/midnight_census');
                break;
        }
    }

    public function patientPrescription($type,$pid,$did,$date)
    {

        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['date'] = $date;
                $data['dispense_id']=$did;
                $data['pid']=$pid;
                $this->load->vars($data);
                $this->load->view('pdf/patient_prescription');
                break;
        }
    }

   // public function drugStock($type,$pid,$did,$sdate,$ldate)
//    function drugStock($type, $from = NULL, $to = NULL)
//     {
//         $this->load->model("m_who_drug");  
        
  
//         $result = $this->m_who_drug->get_drug_report($from, $to);
                
      
//         switch ($type) {
//             case 'view':
//                 $data['title'] = 'Estoques de Medicamentos';//deve ser traduzido para 2 linguas como('Drug Stocks')
//                 $data['url'] = site_url("report/pdf/drugStock/print");

//                 $data['id'] = uniqid("_");
//                 $data['description'] = 'Print the Drug Stocks in given date period.';
//                 $this->load->vars($data);
//                 $this->load->view('date_range_selector_drug_stock');
//                 break;
//             case 'print':
//                 $data['hospital'] = $this->getHospital();
//                 $data['from_date'] = $from;
//                 $data['to_date'] = $to;
//                 $data['result'] = $result;
              
//                 $this->load->vars($data);
//                 $this->load->view('pdf/drug_stock');
//                 break;
//         }
//     }

    function drugStock($type, $wd_id = NULL, $from = NULL, $to = NULL)
    {
        $this->load->model("m_who_drug");  
        
  
        $result = $this->m_who_drug->get_drug_report($from, $to, $wd_id);

        switch ($type) {
            case 'view':
                $data['title'] = 'Estoques de Medicamentos';//deve ser traduzido para 2 linguas como('Drug Stocks')
                $data['url'] = site_url("report/pdf/drugStock/print");
                $data['wd_id']=$wd_id;
                $data['id'] = uniqid("_");
                $data['description'] = 'Print the Drug Stocks in given date period.';
                $this->load->vars($data);
                $this->load->view('date_range_selector_drug_stock');
                break;
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['wd_id']=$wd_id;
                $data['result'] = $result;
                $this->load->vars($data);
                $this->load->view('pdf/drug_stock');
                break;
        }
    }


     // public function drugStock($type,$pid,$did,$sdate,$ldate)
   function drugRequest($type, $from = NULL, $to = NULL)
   {

       switch ($type) {
           case 'print':
               $data['hospital'] = $this->getHospital();
               $data['from_date'] = $from;
               $data['to_date'] = $to;
               $data['request_id']=$this->input->post('request_id');
               $this->load->vars($data);
               $this->load->view('pdf/drug_request');
               break;
       }
   }

   function requestAnalysis($type, $pid=100005)
   {
       switch ($type) {
           case 'print':
               $data['hospital'] = $this->getHospital();
               $data['pid']=$pid;
               $data['request_analysis'] = $this->input->post('request_analysis');
               $this->load->vars($data);
               $this->load->view('pdf/request_analysis'); 
               break;
       }
   }
   
   
   function externalPrescription($type, $from = NULL, $to = NULL)
    {
        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['print_prescription'] = $this->input->post('print_prescription');
                $this->load->vars($data);
                $this->load->view('pdf/external_prescription'); // View para prescrição externa
                break;
        }
    }


    function internalPrescription($type, $from = NULL, $to = NULL)
    {
        $this->load->model("m_patient_prescription");  
        $print_prescription = $this->input->post('print_prescription');
        $result = $this->m_patient_prescription->get_prescription_description($print_prescription);
        switch ($type) {
            case 'print':
                $data['hospital'] = $this->getHospital();
                $data['from_date'] = $from;
                $data['to_date'] = $to;
                $data['print_prescription']= $print_prescription;
                $data['result'] = $result;
                $this->load->vars($data);
                $this->load->view('pdf/internal_prescription');
                break;
        }
    }

    function list_existing_batches($wd_id) {
        $batch_deadline = $this->m_who_drug_count->get_batch_deadline($wd_id);
        return   $batch_deadline;
    }

    function batches_deadline($batch) {
        $deadline = $this->m_who_drug_count->get_batch_deadline_by_batch($batch);
        return $deadline;
    }

    function loteStock($type, $wd_id = NULL)
    {

        switch ($type) {
            case 'view':
                $data['title'] = 'Validade dos lotes';
                $data['wd_id']=$wd_id;
                $data['batches'] =  $this->list_existing_batches($wd_id);
                $data['id'] = uniqid("_");
                $this->load->vars($data);
                $this->load->view('expiration_date_lote');
                break;
        }
    }


    /**
     * @param $patient
     * @param $hospital
     * @param $date
     * @param $items
     * @param $prescription
     *
     * @return mixed
     */
    private function getPrescriptionPageHeight($patient, $hospital, $date, $items, $prescription)
    {

        $pageHeight = null;

        require_once 'application/libraries/class/MDSReporter.php';
        $pdf = new MDSReporter(array('orientation' => 'P', 'unit' => 'mm', 'format' => array(72, 210),
            'footer' => false));


        $name = $patient->Personal_Title . ' ' . $patient->Full_Name_Registered; //returns the fullname
        $reg_no = $patient->HIN;
        $gender = $patient->Gender;
        $pdf->addPage();
        $pdf->SetAutoPageBreak(false);


        $pdf->SetMargins(1, 1);
        $pdf->SetXY(0, 1);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(0, 2, $hospital, 0, 'R');
        $pdf->SetFont('Arial', 'BI', 5);
        $pdf->MultiCell(0, 4, "Prescription - " . $date, 0, 'R');
        $pdf->Image('images/rx.png', 0, -1, 8, 8);
        $pdf->SetFont('Arial', '', 8);
//$pdf->SetXY(8, 6);
        $pdf->MultiCell(0, 4, $name . '(' . $gender . ')', 0, 'L');
//$pdf->SetX(8);
        $pdf->MultiCell(0, 4, 'HIN: ' . $reg_no . ' Age: ' . $patient->getAge(), 0, 'L');

        $pdf->Line(5, 15, 68, 15);

        $pdf->setY(16);
        foreach ($items as $item) {
            $txt = $item->drug_name . ' ' . $item->drug_formulation . ' ' . $item->Dosage . ' ' . $item->Frequency . ' '
                . $item->HowLong;
            $pdf->MultiCell(0, 4, $txt, 0, 'L');
        }
        $lang['Name']='nomdfsa';
        $pdf->Ln();
        $pdf->MultiCell(0, 4, '.......................................................', 0, 'R');
        $pdf->MultiCell(0, 4, 'Prescribed by: ' . $prescription->PrescribeBy, 0, 'R');
//        $pdf->Output('prescription' . $date, 'I');
        $pageHeight = $pdf->GetY();
        unset($pdf);

//        echo $pageHeight;exit;
        return $pageHeight + 2;
    }

    private function getLabOrderPageHeight($patient, $hospital, $date, $orederd_test_list)
    {

        $pageHeight = null;

        require_once 'application/libraries/class/MDSReporter.php';
        $pdf = new MDSReporter(array('orientation' => 'P', 'unit' => 'mm', 'format' => array(72, 210),
            'footer' => false));


        $name = $patient->Personal_Title . ' ' . $patient->Full_Name_Registered; //returns the fullname
        $reg_no = $patient->HIN;
        $gender = $patient->Gender;
        $pdf->addPage();
        $pdf->SetAutoPageBreak(false);


        $pdf->SetMargins(1, 1);
        $pdf->SetXY(0, 1);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(0, 2, $hospital, 0, 'R');
        $pdf->SetFont('Arial', 'BI', 5);
        $pdf->MultiCell(0, 4, "Prescription - " . $date, 0, 'R');
        $pdf->Image('images/rx.png', 0, -1, 8, 8);
        $pdf->SetFont('Arial', '', 8);
//$pdf->SetXY(8, 6);
        $pdf->MultiCell(0, 4, $name . '(' . $gender . ')', 0, 'L');
//$pdf->SetX(8);
        $pdf->MultiCell(0, 4, 'HIN: ' . $reg_no . ' Age: ' . $patient->getAge(), 0, 'L');

        $pdf->Line(5, 15, 68, 15);

        $pdf->setY(16);
        $orderedBy = '';
        foreach ($orederd_test_list as $item) {
            $txt = $item->Name;
            $orderedBy = $item->CreateUser;
            $pdf->MultiCell(0, 4, $txt, 0, 'L');
        }

        $pdf->Ln();
        $pdf->MultiCell(0, 4, '.......................................................', 0, 'R');
        $pdf->MultiCell(0, 4, 'Ordered by: ' . $orderedBy, 0, 'R');
        $pageHeight = $pdf->GetY();
        unset($pdf);

//        echo $pageHeight;exit;
        return $pageHeight + 2;
    }

}

function get_age($dob, $compare_date = NULL)
{
    if ($dob == '0000-00-00') {
        return NULL;
    }

    $date1 = $dob;
    if ($compare_date == NULL) {
        $date2 = date('Y-m-d');
    } else {
        $date2 = $compare_date;
    }

    $diff = abs(strtotime($date2) - strtotime($date1));

    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

    return array('years' => $years, 'months' => $months, 'days' => $days);
}

function get_age_str($dob, $compare_date = NULL)
{
    $obj = get_age($dob, $compare_date);
    if (empty($obj)) {
        return '';
    } else {
        if ($obj['years'] >= 5) {
            return $obj['years'] . 'A';
        } elseif ($obj['years'] >= 1) {
            return $obj['years'] . 'A ' . $obj['months'] . 'M';
        } else {
            return $obj['years'] . 'M ' . $obj['days'] . 'D';
        }
    }
}


  

//////////////////////////////////////////

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Leftmenu extends LoginCheckController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {
        parent::__construct();
        $this->lang->load('menu/left_menu', $this->get_user_default_language());
    }

    public function preference()
    {
        $this->load->view('left_menu_preference');
    }

    public function pharmacy()
    {
        $this->load->view('left_menu_pharmacy');
    }

    public function lab()
    {
        $this->load->view('left_menu_lab');
    }

    public function procedure_room()
    {
        $this->load->view('left_menu_procedure_room');
    }

    public function questionnaire()
    {
        $this->load->view('left_menu_questionnaire');
    }

    public function ward()
    {
        $this->load->view('left_menu_ward');
    }

    public function chat()
    {
        $this->load->view('left_menu_chat');
    }

    public function report()
    {
        $this->load->view('left_menu_report');
    }

    public function print_biopsy($biopsy_id)
    {
        $data["biopsy_id"] = $biopsy_id;
        $this->load->vars($data);
        $this->load->view('left_menu_biopsy');
    }

    public function print_cv_cytology($cv_cytology_id)
    {
        $data["cv_cytology_id"] = $cv_cytology_id;
        $this->load->vars($data);
        $this->load->view('left_menu_cv_cytology');
    }

    public function print_cytology($cytology_id)
    {
        $data["cytology_id"] = $cytology_id;
        $this->load->vars($data);
        $this->load->view('left_menu_cytology');
    }

    public function notification()
    {
        $this->load->view('left_menu_notification');
    }

    public function registry()
    {
        $this->load->view('left_menu_registry');
    }


    public function patient($id = null, $module = null)
    {
        $this->load->model('m_patient');
        $patient = $this->m_patient->get($id);
        $data['id'] = $id;
        $data['module'] = $module;
        $data['patient'] = $patient;
        $this->load->vars($data);
        $this->load->view('left_menu_patient');
    }


    public function opd($opdid = null, $pid = null, $opd_info = null, $is_discharged = null)
    {

        $data['pid'] = $pid;
        $data['opdid'] = $opdid;
        $data['opd_info'] = $opd_info;
        $data['is_discharged'] = $is_discharged;
        $data['pid'] = $pid;
        $data["d_day"] = no_day_different(time(), strtotime(date($opd_info["DateTimeOfVisit"])));
        $this->load->vars($data);
        $this->load->view('left_menu_opd');
    }

    public function emr($emr_id = null, $pid = null, $emr_info = null)
    {
        $data['pid'] = $pid;
        $data['emrid'] = $emr_id;
        $data['emr_info'] = $emr_info;
        // Check if $emr_info is an object or an array and access DateTimeOfVisit accordingly
        if (is_object($emr_info)) {
            $dateTimeOfVisit = $emr_info->DateTimeOfVisit;
            $data['emr_info_object'] = $emr_info;
        } elseif (is_array($emr_info)) {
            $data['emr_info_array'] = $emr_info;
            $dateTimeOfVisit = $emr_info["DateTimeOfVisit"];
        } else {
            $dateTimeOfVisit = null; // Handle the case when $emr_info is null or invalid
        }
    
        // Calculate the day difference using the appropriate DateTimeOfVisit
        $data["d_day"] = no_day_different(time(), strtotime($dateTimeOfVisit));
    
        // Pass the data to the view
        $this->load->vars($data);
        $this->load->view('left_menu_emr');
    }
    

    public function pa($paid = null, $pid = null, $pa_info = null)
    {
        $data['pid'] = $pid;
        $data['paid'] = $paid;
        $data['pa_info'] = $pa_info;
//        $data['is_discharged'] = $is_discharged;
        $data['pid'] = $pid;
        $data["d_day"] = no_day_different(time(), strtotime(date($pa_info["DateTimeOfVisit"])));
        $this->load->vars($data);
        $this->load->view('left_menu_pa');
    }

    public function technician($paid = null, $pid = null, $pa_info = null)
    {
        $data['pid'] = $pid;
        $data['paid'] = $paid;
        $data['pa_info'] = $pa_info;
//        $data['is_discharged'] = $is_discharged;
        $data['pid'] = $pid;
        $data["d_day"] = no_day_different(time(), strtotime(date($pa_info["DateTimeOfVisit"])));
        $this->load->vars($data);
        $this->load->view('left_menu_technician');
    }

    public function clinic($clinic_id = null, $pid = null, $clinic_patient_info = null, $module = null)
    {

        $data['pid'] = $pid;
        $data['clinic_id'] = $clinic_id;
        $data['clinic_patient_info'] = $clinic_patient_info;
        $data['module'] = $module;
        $this->load->vars($data);
        $this->load->view('left_menu_clinic');
    }

    public function clinic_new($clinic_id = null, $pid = null, $clinic_patient_info = null, $module = null)
    {

        $data['pid'] = $pid;
        $data['clinic_id'] = $clinic_id;
        $data['clinic_patient_info'] = $clinic_patient_info;
        $data['module'] = $module;
        $this->load->vars($data);
        $this->load->view('left_menu_clinic_new');
    }

    public function clinic_patient()
    {
        //$this->load->vars($data);
        $this->load->view('left_menu_clinic_patient');
    }

    public function admission($admission = null, $pid = null)
    {
        $data['pid'] = $pid;
    
        if (is_array($admission)) {
            $data['admid'] = isset($admission["ADMID"]) ? $admission["ADMID"] : null;
        } elseif (is_object($admission)) {
            $data['admid'] = isset($admission->ADMID) ? $admission->ADMID : null;
        } else {
            $data['admid'] = null;
        }
    
        $data['admission'] = $admission;
        $this->load->view('left_menu_admission', $data);
    }
    

    public function triage()
    {
        $this->load->view('left_menu_triage');;
    }

    public function active_list($department)
    {
        $data['department'] = $department;
        $this->load->view('left_menu_active_list', $data);
    }

    public function form_active_list($department)
    {
        $data['department'] = $department;
        $this->load->view('left_menu_form_active_list', $data);
    }

    public function user_config()
    {
        $this->load->view('left_menu_user_config');
    }

    public function arquivo_clinico()
    {
        
        $this->load->view( 'left_menu_arquivo_clinico');
    }

    public function sap()
    {
        $this->load->view( 'left_menu_sap');
    }

    public function sap_edit($active_list, $patient)
    {
        $data['active_list'] = $active_list;
        $data['patient'] = $patient;
        
        $bill = $this->m_sap_bill->get_by(array('active_id' => $active_list));
        $data['bill_id'] = $bill->id;
        $this->load->view( 'left_menu_sap_edit', $data);
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
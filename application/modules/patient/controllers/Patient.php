<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Patient extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {
        parent::__construct();
        $this->load->model('my_crud');
        $this->load->model('m_patient');
        $this->load->model('m_patient_allergy');
        $this->load->model('m_emergency_admission');
        $this->load->model('m_who_countries');
        $this->load_form_language();
    }

    function getHid()
    {
        return '11101401';
    }

    public function get_full_info($patient_id)
    {
        $patient = $this->m_patient->with('district')->with('province')->get($patient_id);
        return $patient;
    }

    public function create()
    {
        $this->set_top_selected_menu('patient/create');
        if (!Modules::run('permission/check_permission', 'patient', 'create'))
            die('You do not have permission!');
        $data = array();
        $data['id'] = 0;
        $data['default_pid2'] = '';
        $data['default_pid2_checked'] = true;
        $data['default_title'] = '';
        $data['default_name'] = '';
        $data['default_other_name'] = '';
        $data['default_gender'] = '';
        $data['default_civil_status'] = '';
        $data['default_age_year'] = '';
        $data['default_age_month'] = '';
        $data['default_age_day'] = '';
        $data['default_date_of_birth'] = '';
        $data['default_year'] = '';
        $data['default_month'] = '';
        $data['default_day'] = '';
        $data['default_year_referred'] = '';
        $data['default_month_referred'] = '';
        $data['default_day_referred'] = '';
        $data['default_bi_id'] = '';
        $data['default_bi_id_checked'] = false;
        $data['default_nuit_id'] = '';
        $data['default_nuit_id_checked'] = false;
        $data['default_health_care_id'] = '';
        $data['default_health_care_id_checked'] = false;
        $data['default_gov_emp'] = 'NULL';
        $data['default_telephone'] = '';
        //$data['default_address'] = '';
        $data['default_village'] = '';
        $data['default_remarks'] = '';

        $data['default_address_id'] = '';
        $data['default_address_id_checked'] = false;

        $data['default_firstname'] = '';
        $data['default_profession'] = '';
        $data['default_working_place'] = '';
        $data['default_father_name'] = '';
        $data['default_mother_name'] = '';

        $data['default_reason'] = '';
        $data['default_entry_time'] = date("Y-m-d H:i:s");

        $data['default_contact_name'] = '';
        $data['default_contact_kinship'] = '';
        $data['default_contact_address'] = '';
        $data['default_contact_working'] = '';
        $data['default_contact_telephone'] = '';

        $data['default_province'] = '11';
        $data['default_province_birth'] = '11';
        $data['dropdown_provinces'] = $this->get_dropdown_provinces('result');
        $data['default_district'] = '146';
        $data['default_district_birth'] = '146';
        $data['dropdown_district'] = $this->get_district(11, 'return');
        $data['district_birth'] = $this->get_district(11, 'return');

        $data['default_health_unit'] = '1572';
        $data['dropdown_health_unit'] = $this->get_health_unit(146, 'return');
        $data['default_country'] = '144';
        $data['dropdown_countries'] = $this->get_dropdown_countries('result');
        $data['dropdown_id_type'] = $this->get_dropdown_id_type('result');
        $data['default_id_type'] = 'Nao possui Documento';
        $data['default_age'] = '';
        //add Departments and Services

        $data['dropdown_department'] = $this->get_dropdown_departments('return');

        if ($this->DEPARTMENT == 'OPD') {
            $data['dropdown_service'] = $this->get_dropdown_services(set_value('entry_department', 2), 'return');
            $data['default_department'] = '2';
            $data['default_service'] = '5';
        } else {
            $data['default_department'] = '1';
            $data['dropdown_service'] = $this->get_dropdown_services(set_value('entry_department', 1), 'return');
            $data['default_service'] = '1';
        }


        $this->set_common_validation();

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $this->insert();
            exit;
        }
    }


    public function get_dropdown_countries($type = 'json')
    {
        $this->load->model('m_who_countries');
        $result = $this->m_who_countries->order_by('name')->dropdown('CID', 'cou_name');
        if ($type == 'json') {
            print(json_encode($result));
        }
        return $result;
    }

    public function get_dropdown_provinces($type = 'json')
    {
        $this->load->model('m_who_provinces');
        $result = $this->m_who_provinces->order_by('name')->dropdown('province_code', 'name');
        if ($type == 'json') {
            print(json_encode($result));
        }
        return $result;
    }

    public function get_district($province_id = 4, $type = 'json')
    {
        $this->load->model('m_who_district');
        $result = $this->m_who_district->order_by('name')->get_many_by(array('province_code' => $province_id));
        if ($type == 'json') {
            print(json_encode($result));
        } else {
            foreach ($result as $item) {
                $drop_down[$item->district_code] = $item->name;
            }
            return $drop_down;
        }
    }

    public function get_health_unit($district_id = 55, $type = 'json')
    {
        $this->load->model('m_who_health_unit');
        $result = $this->m_who_health_unit->order_by('US')->get_many_by(array('CD' => $district_id));
        if ($type == 'json') {
            print(json_encode($result));
        } else {
            foreach ($result as $item) {
                $drop_down[$item->id] = $item->US;
            }
            return $drop_down;
        }
    }

    public function get_dropdown_departments($type = 'json')
    {
        $this->load->model('m_hospital_department');
        $result = $this->m_hospital_department->order_by('department_id')->dropdown('department_id', 'abrev');
        if ($type == 'json') {
            print(json_encode($result));
        }
        return $result;
    }

    public function get_dropdown_id_type($type = 'json')
    {
        $this->load->model('m_patient_id_type');
        $result = $this->m_patient_id_type->order_by('id_type')->dropdown('id_type', 'name');
        if ($type == 'json') {
            print(json_encode($result));
        }
        return $result;
    }

    public function get_dropdown_services($department_id = 56, $type = 'json')
    {
        $this->load->model('m_hospital_service');
        $result = $this->m_hospital_service->order_by('name')->get_many_by(array('department_id' => $department_id));

        if ($type == 'json') {
            print(json_encode($result));
        } else {
            $drop_down = array();
            $drop_down[''] = '';
            foreach ($result as $item) {
                $drop_down[$item->service_id] = $item->name;
            }
            return $drop_down;
        }
    }

    private function set_common_validation($id = 0)
    {
        $this->form_validation->set_rules('bi_id_checkbox', 'BI ID', 'trim');
        $this->form_validation->set_rules('nuit_id_checkbox', 'NUIT ID', 'trim');

        $this->form_validation->set_rules('patient_title', lang('Title'), 'trim|required');
        $this->form_validation->set_rules('name', lang('Name'), 'trim|xss_clean|required|strtoupper');
        $this->form_validation->set_rules('other_name', lang('Other Name'), 'trim|strtoupper');
        $this->form_validation->set_rules('gender', lang('Gender'), 'trim|required');
        $this->form_validation->set_rules('civil_status', 'Civil Status', 'trim');
        // $this->form_validation->set_rules('date_of_birth', 'Date Of Birth', 'trim');
        $this->form_validation->set_rules('telephone', 'Telephone', 'trim');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim');

        $this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
        $this->form_validation->set_rules('profession', 'Profession', 'trim');
        $this->form_validation->set_rules('working_place', 'Working Place', 'trim');
        $this->form_validation->set_rules('father_name', 'Father name', 'trim');
        $this->form_validation->set_rules('mother_name', 'Mother name', 'trim');
        $this->form_validation->set_rules('entry_department', lang('Department'), 'trim|required');
        $this->form_validation->set_rules('entry_service', lang('Service'), 'trim|required');

        if ($this->input->post('date_of_birth_checkbox')) {
            // patient do NOT know is birth date
            $this->form_validation->set_rules('date_of_birth', 'Date Of Birth', 'trim');
            $this->form_validation->set_rules('birth_year_referred', 'Year', 'trim|required');
            $this->form_validation->set_rules('birth_month_referred', 'Month', 'trim|required');
            $this->form_validation->set_rules('birth_day_referred', 'Day', 'trim|required');
        } else {
            $this->form_validation->set_rules('date_of_birth', 'Date Of Birth', 'trim|xss_clean|required');
        }

        if ($this->input->post('bi_id_checkbox') && $this->input->post('nuit_id_checkbox')) {
            //patient do NOT have any kind of National ID card
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            //            $this->form_validation->set_rules('village', 'Village', 'trim|required');
            $this->form_validation->set_rules('bi_id', 'BI ID', 'trim|min_length[9]');
            $this->form_validation->set_rules('nuit_id', 'NUIT ID', 'trim|min_length[9]');
        } else {
            $this->form_validation->set_rules('address', 'Address', 'trim');
            //            $this->form_validation->set_rules('village', 'Village', 'trim');
            $this->form_validation->set_rules('bi_id', 'BI ID', 'trim|min_length[9]');
            $this->form_validation->set_rules('nuit_id', 'NUIT ID', 'trim');

            //            $this->form_validation->set_rules('nuit_id', 'NUIT ID', 'callback_check_name');
            $this->form_validation->set_rules('nuit_id', 'NUIT ID', 'trim|min_length[9]|callback_check_national_id[' . $id . ']');
        }
    }

    /* public function check_dob()
    {
        if ($this->input->post('date_of_birth') == '') {
            if ($this->input->post('age_year') == '' and $this->input->post('age_month') == '' and $this->input->post('age_day') == '') {
                $this->form_validation->set_message('check_dob', lang('Patient age or date of birth is missing'));
                return false;
            }
        }
        return TRUE;
    }*/

    private function insert()
    {
        if (!$this->input->post('pid2_checkbox') && $this->input->post('pid2') && strlen($this->input->post('pid2')) > 0)
            $pid2 = $this->input->post('pid2');
        else
            $pid2 = NULL;


        if (!$this->input->post('bi_id_checkbox') && $this->input->post('bi_id') && strlen($this->input->post('bi_id')) > 0)
            $bi_id = $this->input->post('bi_id');
        else
            $bi_id = NULL;

        if (!$this->input->post('nuit_id_checkbox') && $this->input->post('nuit_id') && strlen($this->input->post('nuit_id')) > 0)
            $nuit_id = $this->input->post('nuit_id');
        else
            $nuit_id = NULL;

        if (!$this->input->post('gov_emp') && $this->input->post('gov_emp') && strlen($this->input->post('gov_emp')) > 0)
            $gov_emp = $this->input->post('gov_emp');
        else
            $gov_emp = 45555;

        if ($this->input->post('reason') == 'Other reason') {
            $reason = $this->input->post('hos_reason');
        } else {
            $reason = $this->input->post('reason');
        }

        $age = $this->input->post("age");


        if ($this->input->post("date_of_birth") == "") {
            $dob = 0000-00-00;
        } else {
            $dob = $this->input->post("date_of_birth");
        }

        $yearOfBirthReferred = intval($this->input->post('birth_year_referred'));
        $monthOfBirthReferred = intval($this->input->post('birth_month_referred'));
        $dayOfBirthReferred = intval($this->input->post('birth_day_referred'));


        function calcDateOfBirthReferred($year, $month, $day)
        {
            $dataAtual = new DateTime();
            $dataAtual->sub(new DateInterval("P{$year}Y{$month}M{$day}D"));
            return $dataAtual->format('Y-m-d');
        }

        $data = array(
            'PID2' => $this->input->post('pid2'),
            'Personal_Title' => $this->input->post('patient_title'),
            'Firstname' => strtoupper($this->input->post('firstname')),
            'Name' => strtoupper($this->input->post('name')),
            'OtherName' => strtoupper($this->input->post('other_name')),
            'Gender' => $this->input->post('gender'),
            'Personal_Civil_Status' => $this->input->post('civil_status'),
            'DateOfBirth' => $dob,
            'DateOfBirthReferred' => calcDateOfBirthReferred($yearOfBirthReferred, $monthOfBirthReferred, $dayOfBirthReferred),
            'BI_ID' => $bi_id,
            'NUIT_ID' => $nuit_id,
            'GOV_EMP' => $gov_emp,
            'Telephone' => $this->input->post('telephone'),
            'Address_Street' => $this->input->post('address_id'),
            //            'Address_Village' => $this->input->post('village'),
            'Remarks' => $this->input->post('remarks'),
            'who_national_id' => $this->input->post('who_national_id'),
            'who_province_id' => $this->input->post('province'),
            'who_district_id' => $this->input->post('district'),
            'who_health_unit_id' => $this->input->post('health_unit'),

            // Add Department and Service inputs
            'who_department_id' => $this->input->post('entry_department'),
            'who_service_id' => $this->input->post('entry_service'),

            // Adding new fields for birthplace
            'who_province_birth_id' => $this->input->post('province_birth'),
            'who_district_birth_id' => $this->input->post('district_birth'),

            'Profession' => $this->input->post('profession'),
            'WorkingPlace' => $this->input->post('working_place'),
            'FatherName' => $this->input->post('father_name'),
            'MotherName' => $this->input->post('mother_name'),
            'type_id' => $this->input->post('type_id'),

            //            'HospitalizationReason' => $reason,
            //            'EntryTime' => $this->input->post('entry_time'),

            //            'ContactPerson' => $this->input->post('contact_name'),
            //            'ContactKinship' => $this->input->post('contact_kinship'),
            //            'ContactAddress' => $this->input->post('contact_address'),
            //            'ContactWorkingPlace' => $this->input->post('contact_working_place'),
            //            'ContactTelephone' => $this->input->post('contact_telephone'),
        );
        $id = $this->m_patient->insert($data);
        //redirect
        $this->session->set_flashdata(
            'msg',
            'REC: ' . ucfirst(strtolower($this->input->post("name"))) . ' created'
        );
        $this->redirect_if_no_continue('/patient_contact/create/' . $id);
    }

    public function edit($id)
    {
        if (!Modules::run('permission/check_permission', 'patient', 'edit')) {
            die('You do not have permission');
        }
        $patient = $this->m_patient->get($id);
        if (empty($patient))
            die('Id not exist');
        $data['id'] = $id;
        $data['default_pid2'] = $patient->PID2;
        $data['default_pid2_checked'] = empty($patient->PID2);

        $data['default_title'] = $patient->Personal_Title;
        $data['default_name'] = $patient->Name;
        $data['default_other_name'] = $patient->OtherName;
        $data['default_gender'] = $patient->Gender;
        $data['default_civil_status'] = $patient->Personal_Civil_Status;


        $data['default_date_of_birth'] = $patient->DateOfBirth;

        if($patient->DateOfBirth == '0000-00-00') {
            $data['default_date_of_birth'] = $patient->DateOfBirthReferred;
            $data['default_year_referred'] = date('Y', strtotime($patient->DateOfBirthReferred));
            $data['default_month_referred'] = date('m', strtotime($patient->DateOfBirthReferred));
            $data['default_day_referred'] = date('d', strtotime($patient->DateOfBirthReferred));
        } else {
            $data['default_date_of_birth'] = $patient->DateOfBirth;
            $data['default_year_referred'] = '';
            $data['default_month_referred'] = '';
            $data['default_day_referred'] = '';
        }

        $data['default_year'] = $patient->DateOfBirth;
        $data['default_month'] = $patient->DateOfBirth;
        $data['default_day'] = $patient->DateOfBirth;


        $data['default_bi_id'] = $patient->BI_ID;
        $data['default_bi_id_checked'] = empty($patient->BI_ID);
        $data['default_nuit_id'] = $patient->NUIT_ID;
        $data['default_nuit_id_checked'] = empty($patient->NUIT_ID);
        $data['default_health_care_id'] = $patient->HEALTH_CARE_ID;
        $data['default_health_care_id_checked'] = empty($patient->HEALTH_CARE_ID);
        $data['default_gov_emp'] = $patient->GOV_EMP;;
        $data['default_telephone'] = $patient->Telephone;
        //$data['default_address'] = $patient->Address_Street;
        $data['default_remarks'] = $patient->Remarks;

        $data['default_address_id'] = $patient->Address_Street;
        $data['default_address_id_checked'] = empty($patient->Address_Street);

        $data['default_firstname'] = $patient->Firstname;
        $data['default_profession'] = $patient->Profession;
        $data['default_working_place'] = $patient->WorkingPlace;
        $data['default_father_name'] = $patient->FatherName;
        $data['default_mother_name'] = $patient->MotherName;

        $data['default_province_birth'] = $patient->who_province_birth_id;
        $data['default_district_birth'] = $patient->who_district_birth_id;
        $data['default_country'] = $patient->who_national_id;
        $data['dropdown_countries'] = $this->get_dropdown_countries('result');
        $data['district_birth'] ='';
        $data['default_id_type'] = $patient->type_id;
        $data['dropdown_id_type'] = $this->get_dropdown_id_type('result');

        if ($patient->who_province_birth_id) {
            $data['district_birth'] = $this->get_district($patient->who_province_birth_id, 'return');
        }
        //        $data['default_reason'] = $patient->HospitalizationReason;
        //        $data['default_entry_time'] = $patient->EntryTime;

        $data['default_province'] = $patient->who_province_id;
        $data['dropdown_provinces'] = $this->get_dropdown_provinces('result');
        $data['default_district'] = $patient->who_district_id;
        if ($patient->who_province_id) {
            $data['dropdown_district'] = $this->get_district($patient->who_province_id, 'return');
        } else {
            $data['dropdown_district'] = array();
        }
        $data['default_health_unit'] = $patient->who_health_unit_id;
        if ($patient->who_health_unit_id) {
            $data['dropdown_health_unit'] = $this->get_health_unit($patient->who_district_id, 'return');
        } else {
            $data['dropdown_health_unit'] = array();
        }

        //add Departments and Services Input
        $data['default_department'] = $patient->who_department_id;
        $data['dropdown_department'] = $this->get_dropdown_departments('result');

        $data['default_service'] = $patient->who_service_id;

        $department = set_value('entry_department', $patient->who_department_id);
        $data['dropdown_service'] = $this->get_dropdown_services($department, 'return');

        $data['default_age_year'] = '';
        $data['default_age_month'] = '';
        $data['default_age_day'] = '';

        if (isset($patient->DateOfBirth)) {
            $dob = $this->get_age($patient->DateOfBirth);
            $data['default_age_year'] = $dob['years'];
            $data['default_age_month'] = $dob['months'];
            $data['default_age_day'] = $dob['days'];
        }


        $this->set_common_validation($id);
        if ($this->form_validation->run($this) == FALSE) {
            $this->render('form_patient', $data);
        } else {
            $this->update($id);
        }
    }

    private function update($id)
    {

        if (!$this->input->post('pid2_checkbox') && $this->input->post('pid2') && strlen($this->input->post('pid2')) > 0)
            $pid2 = $this->input->post('pid2');
        else
            $pid2 = NULL;

        if (!$this->input->post('bi_id_checkbox') && $this->input->post('bi_id') && strlen($this->input->post('bi_id')) > 0)
            $bi_id = $this->input->post('bi_id');
        else
            $bi_id = NULL;
        if (!$this->input->post('nuit_id_checkbox') && $this->input->post('nuit_id') && strlen($this->input->post('nuit_id')) > 0)
            $nuit_id = $this->input->post('nuit_id');
        else
            $nuit_id = NULL;
        if (!$this->input->post('health_care_id_checkbox') && $this->input->post('health_care_id') && strlen($this->input->post('health_care_id')) > 0)
            $health_care_id = $this->input->post('health_care_id');
        else
            $health_care_id = NULL;
        if ($this->input->post('reason') == 'Other reason') {
            $reason = $this->input->post('hos_reason');
        } else {
            $reason = $this->input->post('reason');
        }


        $year = $this->input->post("age_year");
        $month = $this->input->post("age_month");
        $day = $this->input->post("age_day");
        if ($this->input->post("date_of_birth") == "") {
            $dob = date('Y-m-d', mktime(0, 0, 0, date("m") - $month, date("d") - $day, date("Y") - $year));
        } else {
            $dob = $this->input->post("date_of_birth");
        }
        //        var_dump($dob);

        $data = array(
            'PID2' => $this->input->post('pid2'),
            'Personal_Title' => $this->input->post('patient_title'),
            'Firstname' => strtoupper($this->input->post('firstname')),
            'Name' => strtoupper($this->input->post('name')),
            'OtherName' => strtoupper($this->input->post('other_name')),
            'Gender' => $this->input->post('gender'),
            'Personal_Civil_Status' => $this->input->post('civil_status'),
            'who_national_id' => $this->input->post('who_national_id'),
            'who_province_birth_id' => $this->input->post('province_birth'),
            'who_district_birth_id' => $this->input->post('district_birth'),
            'DateOfBirth' => $dob,
            'type_id' => $this->input->post('type_id'),
            'BI_ID' => $bi_id,
            'NUIT_ID' => $nuit_id,
            'GOV_EMP' => $this->input->post('gov_emp'),
            'HEALTH_CARE_ID' => $health_care_id,
            'Telephone' => $this->input->post('telephone'),
            'Address_Street' => $this->input->post('address_id'),
            //            'Address_Village' => $this->input->post('village'),
            'Remarks' => $this->input->post('remarks'),
            'who_national_id' => $this->input->post('who_national_id'),
            'who_province_id' => $this->input->post('province'),
            'who_district_id' => $this->input->post('district'),
            'who_health_unit_id' => $this->input->post('health_unit'),


            //Add Departments and Services
            'who_department_id' => $this->input->post('entry_department'),
            'who_service_id' => $this->input->post('entry_service'),

            'Profession' => $this->input->post('profession'),
            'WorkingPlace' => $this->input->post('working_place'),
            'FatherName' => $this->input->post('father_name'),
            'MotherName' => $this->input->post('mother_name'),

            //            'HospitalizationReason' => $reason,
            //            'EntryTime' => $this->input->post('entry_time'),
            //
            //
            //            'ContactPerson' => $this->input->post('contact_name'),
            //            'ContactKinship' => $this->input->post('contact_kinship'),
            //            'ContactAddress' => $this->input->post('contact_address'),
            //            'ContactWorkingPlace' => $this->input->post('contact_working_place'),
            //            'ContactTelephone' => $this->input->post('contact_telephone')
        );
        $this->m_patient->update($id, $data);

        $this->session->set_flashdata(
            'msg',
            'REC: ' . ucfirst(strtolower($this->input->post("name"))) . ' updated'
        );
        $this->redirect_if_no_continue('/patient/view/' . $id);
    }

    public
    function check_national_id($national_id, $pid)
    {
        //        var_dump('check national ID' . $national_id);
        if ($this->input->post('bi_id_checkbox')) {
            $bi_id = '';
        } else {
            $bi_id = $this->input->post('bi_id');
        }
        if ($this->input->post('nuit_id_checkbox')) {
            $nuit_id = '';
        } else {
            $nuit_id = $this->input->post('nuit_id');
        }
        if ($this->input->post('health_care_id_checkbox')) {
            $health_care_id = '';
        } else {
            $health_care_id = $this->input->post('health_care_id');
        }


        if (!empty($bi_id)) {
            $patient_have_id = $this->m_patient->get_by(array('BI_ID' => $bi_id));
            if (!empty($patient_have_id) && $patient_have_id->PID != $pid) {
                $this->form_validation->set_message('check_national_id', lang('BI is duplicated'));
                return false;
            }
        }
        if (!empty($nuit_id)) {
            $patient_have_id = $this->m_patient->get_by(array('NUIT_ID' => $nuit_id));
            if (!empty($patient_have_id) && $patient_have_id->PID != $pid) {
                $this->form_validation->set_message('check_national_id', lang('NUIT is duplicated'));
                return false;
            }
        }
        return TRUE;
    }

    public function index()
    {
        echo "nothing here";
    }

    public function banner($id)
    {
        if (!isset($id) || (!is_numeric($id))) {
            $data["error"] = "Patien not found";
            $this->load->vars($data);
            $this->load->view('patient_error');
            return;
        }
        $this->load->model('mpersistent');
        $this->load->model('m_admission');
        $data["patient_info"] = $this->mpersistent->open_id($id, "patient", "PID");
        $data['ward_info'] = $this->m_admission->return_all($id);
        if (empty($data["patient_info"])) {
            $data["error"] = "Patient not found";
            $this->load->vars($data);
            $this->load->view('patient_error');
        }

        $data["patient_info"]["Country_name"] = $this->m_who_countries->get_name_by_cid($data["patient_info"]["who_national_id"])->name;

        if (($data["patient_info"]["DateOfBirth"]) === '0000-00-00') {
            $data["patient_info"]["Age"] = $this->get_age_ref($data["patient_info"]["DateOfBirthReferred"]);
        } else {
            $data["patient_info"]["Age"] = $this->get_age($data["patient_info"]["DateOfBirth"]);
        }
        $data["patient_info"]["HIN"] = $this->print_hin($data["patient_info"]["HIN"]);

        $this->load->vars($data);
        $this->load->view('patient_banner');
    }

    public function get_age($dob)
    {
        if ($dob == 0000-00-00) {
            return array('years' => ' ', 'months' => ' ', 'days' => ' ');
        }

        $date1 = $dob;
        $date2 = date('Y/m/d');

        $diff = abs(strtotime($date2) - strtotime($date1));

        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

        return array('years' => $years, 'months' => $months, 'days' => $days);
    }

    public function get_age_ref($dob)
    {
        if ($dob !== 0000-00-00) {

            $date1 = $dob;
            $date2 = date('Y/m/d');
    
            $diff = abs(strtotime($date2) - strtotime($date1));
    
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    
            return array('years' => $years, 'months' => $months, 'days' => $days);
        }
    } 
    

    public function print_hin($hin)
    {
        return substr($hin, 0, 4) . '-' . substr($hin, 4, 6) . "-" . substr($hin, 10, 1);
    }

    public
    function banner_full($pid)
    {
        $data["patient_info"] = $this->m_patient->as_array()->get($pid);
        if (isset($data["patient_info"]["DateOfBirth"])) {
            if(($data["patient_info"]["DateOfBirth"]) == 0000-00-00) {
                
                $data["patient_info"]["Age"] = $this->get_age_ref($data["patient_info"]["DateOfBirthReferred"]);
            } else {
                $data["patient_info"]["Age"] = $this->get_age($data["patient_info"]["DateOfBirth"]);
            }
        }
        
        $this->load->vars($data);
        $this->load->view('patient_banner_full');
    }

    public
    function get_previous_lab($pid, $continue, $mode = 'HTML')
    {
        $this->load->model("mpatient");
        $data = array();
        $data["patient_lab_order_list"] = $this->mpatient->get_lab_order_list($pid);
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_lab');
        } else {
            return $data["patient_lab_order_list"];
        }
    }

    public
    function open_model($id)
    {
        $this->load->model('mpersistent');
        $this->mpersistent->load('patient');
        $this->mpersistent->open_id($id);
    }

    public
    function reffer_to_clinic($id = null)
    {
        if (!Modules::run('security/check_edit_access', 'clinic_patient', 'can_edit')) {
            $data["error"] = " User group '" . $this->session->userdata('UserGroup') . "' have no rights to edit this data";
            $this->load->vars($data);
            $this->load->view('patient_error');
            exit;
        }
        $this->load->model('mpersistent');
        $data["patient_info"] = $this->mpersistent->open_id($id, "patient", "PID");

        if (empty($data["patient_info"])) {
            $data["error"] = "Patient not found";
            $this->load->vars($data);
            $this->load->view('patient_error');
        }
        if (($data["patient_info"]["DateOfBirth"]) === '0000-00-00') {
            $data["patient_info"]["Age"] = $this->get_age_ref($data["patient_info"]["DateOfBirthReferred"]);
        } else {
            $data["patient_info"]["Age"] = $this->get_age($data["patient_info"]["DateOfBirth"]);
        }
        $data["patient_info"]["HIN"] = $this->print_hin($data["patient_info"]["HIN"]);
        $data["id"] = $id;
        $this->load->vars($data);
        $this->load->view('patient_clinic');
    }


    public
    function clinic($id = null)
    {
        if (!Modules::run('security/check_edit_access', 'clinic_patient', 'can_edit')) {
            $data["error"] = " User group '" . $this->session->userdata('UserGroup') . "' have no rights to edit this data";
            $this->load->vars($data);
            $this->load->view('patient_error');
            exit;
        }
        $this->load->model('mpersistent');
        $this->load->model('mclinic');
        $data["patient_info"] = $this->mpersistent->open_id($id, "patient", "PID");

        if (empty($data["patient_info"])) {
            $data["error"] = "Patient not found";
            $this->load->vars($data);
            $this->load->view('patient_error');
        }
        if (($data["patient_info"]["DateOfBirth"]) === '0000-00-00') {
            $data["patient_info"]["Age"] = $this->get_age_ref($data["patient_info"]["DateOfBirthReferred"]);
        } else {
            $data["patient_info"]["Age"] = $this->get_age($data["patient_info"]["DateOfBirth"]);
        }
        $data["patient_info"]["HIN"] = $this->print_hin($data["patient_info"]["HIN"]);

        $data["pid"] = $id;
        $data["clinic_list"] = $this->mclinic->get_clinic_list($data["patient_info"]["Gender"]);
        if (!empty($data["clinic_list"])) {
            for ($i = 0; $i < count($data["clinic_list"]); ++$i) {
                $data["clinic_list"][$i]["assigned_clinic"] = $this->mclinic->is_patient_assigned($id, $data["clinic_list"][$i]["clinic_id"]);
            }
        }
        $this->load->vars($data);
        $this->load->view('patient_clinic');
    }

    public function view($id = NULL, $ref_id = null)
    {
        $this->load->model('mpersistent');
        $this->load->model('mquestionnaire');
        $this->load->model('m_patient_contact');
        $this->load->helper('file');
        $data["patient_info"] = $this->mpersistent->open_id($id, "patient", "PID");
        $data["contact_person"] = $this->mpersistent->open_id($id, "patient_emr_contacts", "PID");

        if (empty($data["patient_info"])) {
            $data["error"] = "Patient not found";
            $this->load->vars($data);
            $this->load->view('patient_error');
        }

        //        if (empty($data["contact_person"])) {
        //            $data["contact_person"]["ContactPerson"] = '';
        //            $data["contact_person"]["ContactKinship"] = '';
        //            $data["contact_person"]["ContactAddress"] = '';
        //            $data["contact_person"]["ContactWorkingPlace"] = '';
        //            $data["contact_person"]["ContactTelephone"] = '';
        //        }

        if (($data["patient_info"]["DateOfBirth"]) === 0000-00-00) {
            $data["patient_info"]["Age"] = $this->get_age_ref($data["patient_info"]["DateOfBirthReferred"]);
        } else {
            $data["patient_info"]["Age"] = $this->get_age($data["patient_info"]["DateOfBirth"]);
        }
        if (get_file_info('./attach/' . $data["patient_info"]["HIN"] . '/' . $data["patient_info"]["HIN"] . '_portrait.jpg')) {
            $data["image"] = base_url() . 'attach/' . $data["patient_info"]["HIN"] . '/' . $data["patient_info"]["HIN"] . '_portrait.jpg';
        } else {
            $data["image"] = base_url() . '/images/patient.jpg';
        }
        $data["id"] = $id;
        $data["ref_id"] = $ref_id;
        $data["contact_person"] = $this->contact_person($id);
        $data["blood_donation"] = $this->blood_donation($id);
        $data["blood_donation_result"] = $this->blood_donation_result($id);
        $data["child_birth"] = $this->child_birth($id);

        if ($this->session->userdata('user_group_id') != 18 && $this->session->userdata('user_group_id') != 25) {
            $data["previous_emergency_visit"] = $this->previous_emergency_visit($id);
            $data["previous_opd_visits"] = $this->previousVisits($id);
            $data["admissions"] = $this->loadAdmission($id);
            $data["exams"] = $this->loadExam($id);
            $data["diagnosis"] = $this->loadDiagnosis($id);
            $data['patient_history'] = $this->loadHistory($id);
            $data["allergy"] = $this->loadAlergy($id);
            //        $data["lab_orders"] = $this->loadLabOrder($id);
            //        $data["prescriptions"] = $this->loadPrescription($id);
            $data["notes"] = $this->loadNotes($id);

            $this->qch_template->load_form_layout('patient_view', $data);
        } else {
            $this->qch_template->load_form_layout('registrar_view_patient', $data);
        }
    }

    public function contact_person($pid)
    {
        $qry = "SELECT PEMRCID, ContactPerson, ContactKinship, ContactAddress, ContactWorkingPlace, ContactTelephone  FROM patient_emr_contacts WHERE PID = " . $pid;
        $this->load->model('mpager', 'contact_person_page');
        $visit_page = $this->contact_person_page;
        $visit_page->setSql($qry);
        $visit_page->setDivId("contact_person"); //important
        $visit_page->setDivClass('');
        $visit_page->setRowid('PEMRCID');
        $visit_page->setCaption(lang("Contact Person"));
        //        $visit_page->setShowHeaderRow(false);
        $visit_page->setShowFilterRow(false);
        $visit_page->setShowPager(false);
        $visit_page->setColNames(array("PEMRCID", lang("Name"), lang("Kinship"), lang("Address"), lang("Working Place"), lang("Telephone")));
        $visit_page->setRowNum(10);
        $visit_page->setColOption("PEMRCID", array("search" => false, "hidden" => true));
        $visit_page->gridComplete_JS
            = "function() {

            var c = null;
            $('#contact_person .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                c = $(this).css('background');
                $(this).css({'background':'#FFFFFF','cursor':'pointer'});
            }).mouseout(function(e){
            $(this).css('background',c);
            }).click(function(e){
                var rowId = $(this).attr('id');
                window.location='" . base_url() . "index.php/patient_contact/edit/'+rowId+'?CONTINUE=patient/view/" . $pid . "';
            });

            }";
        $visit_page->setOrientation_EL("L");
        return $visit_page->render(false);
    }

    private function child_birth($pid)
    {
        $qry = "SELECT child_birth.ChildBirthID,
                        child_birth.ChildID,
                        CONCAT(A.Firstname,' ',A.Name) AS Name,
                        child_birth.MotherID,
                        CONCAT(B.Firstname,' ',B.Name) AS MotherName,
                        SUBSTR(child_birth.dob, 1, 10),
                        child_birth.weight,
                        child_birth.place_of_birth,
                        child_birth.birth_type,
                        child_birth.pregnant_time
                FROM child_birth, patient as A, patient as B
                WHERE A.PID = child_birth.ChildID AND B.PID = child_birth.MotherID AND (MotherID = " . $pid . " OR ChildID = " . $pid . ")";
        $this->load->model('mpager', 'child_birth');
        $visit_page = $this->child_birth;
        $visit_page->setSql($qry);
        $visit_page->setDivId("child_birth"); //important
        $visit_page->setDivClass('');
        $visit_page->setRowid('ChildBirthID');
        $visit_page->setCaption(lang('Child Birth'));
        //        $visit_page->setShowHeaderRow(false);
        $visit_page->setShowFilterRow(false);
        $visit_page->setShowPager(false);
        $visit_page->setColNames(
            array(
                "ChildBirthID", lang("Child ID"), lang("Child Name"), lang("Mother ID"), lang('Mother Name'),
                lang("Date of Birth"), lang("Weight"), lang("Place of Birth"),
                lang('Birth Type'), lang('Pregnant Time')
            )
        );
        $visit_page->setRowNum(10);
        $visit_page->setColOption("ChildBirthID", array("search" => false, "hidden" => true));
        //        if (Modules::run('permission/check_permission', 'blood_donation', 'edit')) {
        $visit_page->gridComplete_JS
            = "function() {

            var c = null;
            $('#child_birth .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                c = $(this).css('background');
                $(this).css({'background':'#FFFFFF','cursor':'pointer'});
            }).mouseout(function(e){
            $(this).css('background',c);
            }).click(function(e){
                var rowId = $(this).attr('id');
                window.location='" . base_url() . "index.php/child_birth/edit/'+rowId+'?CONTINUE=patient/view/" . $pid . "';
            });

            }";
        //        }
        $visit_page->setOrientation_EL("L");
        return $visit_page->render(false);
    }

    public function blood_donation($pid)
    {
        $qry = "SELECT blood_donation_id, donation_number,gs,rhesus, donation_type,
              prev_donation, number_of_donation, prev_place_of_donation,
              prev_donation_date, motivation , remarks
                FROM patient_blood_donation WHERE PID = " . $pid;
        $this->load->model('mpager', 'blood_donation');
        $visit_page = $this->blood_donation;
        $visit_page->setSql($qry);
        $visit_page->setDivId("blood_donation"); //important
        $visit_page->setDivClass('');
        $visit_page->setRowid('blood_donation_id');
        $visit_page->setCaption('Dados do Dador');
        //        $visit_page->setShowHeaderRow(false);
        $visit_page->setShowFilterRow(false);
        $visit_page->setShowPager(false);
        $visit_page->setColNames(
            array(
                "blood_donation_id", lang('Donation Number'), 'GS', 'Rhesus', lang("Donation Type"),
                lang("Prev Donation"), lang("Number of donation"), lang("Prev Place of Donation"),
                lang('Prev Donation Date'), lang('Motivation'), lang('Remarks')
            )
        );
        $visit_page->setRowNum(10);
        $visit_page->setColOption("blood_donation_id", array("search" => false, "hidden" => true));
        if (Modules::run('permission/check_permission', 'blood_donation', 'edit')) {
            $visit_page->gridComplete_JS
                = "function() {

                var c = null;
                $('#blood_donation .jqgrow').mouseover(function(e) {
                    var rowId = $(this).attr('id');
                    c = $(this).css('background');
                    $(this).css({'background':'#FFFFFF','cursor':'pointer'});
                }).mouseout(function(e){
                $(this).css('background',c);
                }).click(function(e){
                    var rowId = $(this).attr('id');
                    window.location='" . base_url() . "index.php/blood_donation/edit/'+rowId+'?CONTINUE=patient/view/" . $pid . "';
                });

                }";
        }
        $visit_page->setOrientation_EL("L");
        return $visit_page->render(false);
    }

    public function blood_donation_result($pid)
    {
        $this->load->model('m_patient_blood_donation');
        $blood_donation = $this->m_patient_blood_donation->get_by(array(
            'pid' => $pid
        ));
        if (empty($blood_donation)) {
            return '';
        }
        $qry = "SELECT patient_blood_donation_result_id, sample_id, donation_date, hgb, peso, ta, hiv, hbv, hcv, rpr, next_donation_date, remarks
                FROM patient_blood_donation_result WHERE active = 1 and donation_id = " . $blood_donation->blood_donation_id;
        $this->load->model('mpager', 'blood_donation_result');
        $visit_page = $this->blood_donation_result;
        $visit_page->setSql($qry);
        $visit_page->setDivId("blood_donation_result"); //important
        $visit_page->setDivClass('');
        $visit_page->setRowid('patient_blood_donation_result_id');
        $visit_page->setCaption('Doações de Sangue (' . $blood_donation->gs . $blood_donation->rhesus . ')');
        //        $visit_page->setShowHeaderRow(false);
        $visit_page->setShowFilterRow(false);
        $visit_page->setShowPager(false);
        $visit_page->setColNames(
            array(
                "patient_blood_donation_result_id", lang('Sample ID'), lang('Donation Date'), 'HGB', "PESO", "TA", "HIV", "HBV", "HCV", "RPR", lang("Next Donation Date"), lang('Remarks')
            )
        );
        $visit_page->setRowNum(10);
        $visit_page->setColOption("patient_blood_donation_result_id", array("search" => false, "hidden" => true));
        if (Modules::run('permission/check_permission', 'blood_donation', 'edit')) {
            $visit_page->gridComplete_JS
                = "function() {

            var c = null;
            $('#blood_donation_result .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                c = $(this).css('background');
                $(this).css({'background':'#FFFFFF','cursor':'pointer'});
            }).mouseout(function(e){
            $(this).css('background',c);
            }).click(function(e){
                var rowId = $(this).attr('id');
                window.location='" . base_url() . "index.php/blood_donation_result/edit/'+rowId+'?CONTINUE=patient/view/" . $pid . "';
            });

            }";
        }
        {
            $examination = $this->m_patient_examination->get($patexamid);
    
            // Load the view with the examination details
            $this->load->view('patient_examination/patient_view_exam', array('examination' => $examination));
        }
        $visit_page->setOrientation_EL("L");
        return $visit_page->render(false);
    }

    private function previous_emergency_visit($pid)
{
    $qry = "SELECT EMRID, 
                   SUBSTRING(DateTimeOfVisit, 1, 10) AS VisitDate, 
                   Severity, 
                   Complaint, 
                   Status, 
                   CONCAT('<button class=\"process-clinical-btn\" onclick=\"event.stopPropagation(); window.location=\'" . site_url("report/pdf/clinicalProcess/print") . "/',".$pid.", '\';\">".lang('clinical process')."</button>') AS Action 
            FROM emergency_admission 
            WHERE PID =  " . $pid;

    $this->load->model('mpager', 'emr_visit_page');
    $visit_page = $this->emr_visit_page;
    $visit_page->setSql($qry);
    $visit_page->setDivId("emr_cont");
    $visit_page->setDivClass('');

    $visit_page->setRowid('EMRID');
    $visit_page->setCaption(lang("Previous Emergency Visits"));

    $visit_page->setShowHeaderRow(false);
    $visit_page->setShowFilterRow(false);
    $visit_page->setShowPager(false);
    $visit_page->setColNames(array("", "Date", "Severity", "Complaint", "Status", "Action"));
    $visit_page->setRowNum(25);
    $visit_page->setColOption("EMRID", array("search" => false, "hidden" => true));
    $visit_page->setColOption("Action", array("search" => false, "sortable" => false, "formatter" => "raw"));

    $visit_page->gridComplete_JS = "function() {
        $('#emr_cont .jqgrow:first').css({'background-color': '#d0f0c0'});
        
        $('#emr_cont .jqgrow').mouseover(function(e) {
            $(this).css({'cursor':'pointer'});
        }).mouseout(function(e){
        }).click(function(e){
            if (!$(e.target).hasClass('process-clinical-btn')) {
                var rowId = $(this).attr('id');
                window.location='" . site_url("emergency_visit/view") . "/'+rowId;
            }
        });
    }";
    
    $visit_page->setOrientation_EL("L");
    return $visit_page->render(false);
}

    
    

    private
    function previousVisits($pid)
    {
        $qry
            = "SELECT opd_visits.OPDID , SUBSTRING(opd_visits.DateTimeOfVisit,1,10) as dte,opd_visits.Complaint,
	CONCAT(user.Title,user.OtherName )
	FROM opd_visits
	LEFT JOIN `user` ON user.UID = opd_visits.Doctor
	where (opd_visits.PID ='" . $pid . "')";
        $this->load->model('mpager', 'visit_page');
        $visit_page = $this->visit_page;
        $visit_page->setSql($qry);
        $visit_page->setDivId("opd_cont"); //important
        $visit_page->setDivClass('');
        $visit_page->setRowid('OPDID');
        $visit_page->setCaption(lang("Previous OPD Visits"));
        $visit_page->setShowHeaderRow(false);
        $visit_page->setShowFilterRow(false);
        $visit_page->setShowPager(false);
        $visit_page->setColNames(array("ID", "", "", ""));
        $visit_page->setRowNum(25);
        $visit_page->setColOption("OPDID", array("search" => false, "hidden" => true));
        $visit_page->setColOption("dte", array("search" => false, "hidden" => false, "width" => 75));
        $visit_page->gridComplete_JS
            = "function() {
        $('#opd_cont .jqgrow').mouseover(function(e) {
            var rowId = $(this).attr('id');
            $(this).css({'cursor':'pointer'});
        }).mouseout(function(e){
        }).click(function(e){
            var rowId = $(this).attr('id');
            window.location='" . site_url("opd_visit/view") . "/'+rowId;
        });
        }";
        $visit_page->setOrientation_EL("L");
        return $visit_page->render(false);
    }

    private
    function loadAdmission($pid)
    {
        $qry
            = "SELECT admission.ADMID , SUBSTRING(admission.AdmissionDate,1,10) as dte, admission.Complaint
	FROM admission
	where (admission.PID ='" . $pid . "')";
        $this->load->model('mpager', 'admission_page');
        $admission_page = $this->admission_page;
        $admission_page->setSql($qry);
        $admission_page->setDivId("adm_cont"); //important
        $admission_page->setDivClass('');
        $admission_page->setRowid('ADMID');
        $admission_page->setCaption(lang("Previous Admission Visits"));
        $admission_page->setShowHeaderRow(false);
        $admission_page->setShowFilterRow(false);
        $admission_page->setShowPager(false);
        $admission_page->setColNames(array("ID", "", "",));
        $admission_page->setRowNum(25);
        $admission_page->setColOption("ADMID", array("search" => false, "hidden" => true));
        $admission_page->setColOption("dte", array("search" => false, "hidden" => false, "width" => 75));
        $admission_page->gridComplete_JS
            = "function() {
        $('#adm_cont .jqgrow').mouseover(function(e) {
            var rowId = $(this).attr('id');
            $(this).css({'cursor':'pointer'});
        }).mouseout(function(e){
        }).click(function(e){
            var rowId = $(this).attr('id');
           window.location='" . site_url("admission/view") . "/'+rowId+'';        });
        }";
        $admission_page->setOrientation_EL("L");
        return $admission_page->render(false);
    }

    private 
    function loadDiagnosis($pid)
    {   
        $qry = "SELECT 
        pd.patient_diagnosis_id,
        pd.CreateDate as dte,
        icd.Code as code,
        COALESCE(icd.Name, pd.diagnosis) as diagnostic,
        dt.Name
        FROM patient_diagnosis pd
        LEFT JOIN diagnosis_type dt ON dt.Id = pd.diagnosis_type_1
        LEFT JOIN emergency_admission ema ON ema.EMRID = pd.RefID
        LEFT JOIN icd10 icd ON icd.ICDID = pd.diagnosis_id
        WHERE pd.PID = '" . $pid . "'";

        $this->load->model('mpager', 'diagnosis_page');
        $diagnosis_page = $this->diagnosis_page;
        $diagnosis_page->setSql($qry);
        $diagnosis_page->setDivId("diagnosis_cont"); //important
        $diagnosis_page->setDivClass('');
        $diagnosis_page->setRowid('patient_diagnosis_id');
        $diagnosis_page->setCaption("Diagnosis");
        $diagnosis_page->setShowHeaderRow(true);
        $diagnosis_page->setShowFilterRow(false);
        $diagnosis_page->setShowPager(false);
        $diagnosis_page->setColNames(array("ID", "Data", "Codigo", "Diagnostico", "Tipo de Diagnostico"));
        $diagnosis_page->setRowNum(25);
        $diagnosis_page->setColOption("patient_diagnosis_id", array("search" => false, "hidden" => true));
        $diagnosis_page->setColOption("dte", array("search" => false, "hidden" => false));
        $diagnosis_page->setColOption("code", array("search" => false, "hidden" => true));
        $diagnosis_page->setColOption("diagnostic", array("search" => false, "hidden" => false));
        $diagnosis_page->setColOption("Name", array("search" => false, "hidden" => false));
        $diagnosis_page->gridComplete_JS = "function() {
            $('#diagnosis_cont .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                $.ajax({
                    url: '" . site_url("patient_diagnosis/view") . "/' + rowId,
                    type: 'GET',
                    success: function(data) {
                        $('#modalContent').html(data);
                        $('#myModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                console.error('Erro ao carregar o modal:', error);
                alert('Ocorreu um erro ao carregar os dados do diagnóstico.');
            }
                });
            });
        }";
        $diagnosis_page->setOrientation_EL("L");
        return $diagnosis_page->render(false);
    }

    private
    function loadExam($pid)
    {
        $qry
            = "SELECT patient_exam.PATEXAMID ,
	SUBSTRING(patient_exam.ExamDate,1,10) as dte,
	CONCAT(patient_exam.sys_BP,' / ',patient_exam.diast_BP) as bp,
	CONCAT(patient_exam.Weight,'Kg.') as weight,
	CONCAT(patient_exam.Height,'m') as height,
	CONCAT(patient_exam.Temperature,'`C')
	FROM patient_exam
	where (patient_exam.PID ='" . $pid . "') and(patient_exam.Active = 1)";
        $this->load->model('mpager', 'exam_page');
        $exams_page = $this->exam_page;
        $exams_page->setSql($qry);
        $exams_page->setDivId("exam_cont"); //important
        $exams_page->setDivClass('');
        $exams_page->setRowid('PATEXAMID');
        $exams_page->setCaption(lang("Examinations"));
        $exams_page->setShowHeaderRow(false);
        $exams_page->setShowFilterRow(false);
        $exams_page->setShowPager(false);
        $exams_page->setColNames(array("ID", "", "", "", "", ""));
        $exams_page->setRowNum(25);
        $exams_page->setColOption("PATEXAMID", array("search" => false, "hidden" => true));
        $exams_page->setColOption("dte", array("search" => false, "hidden" => false, "width" => 75));
        $exams_page->setColOption("bp", array("search" => false, "hidden" => false, "width" => 100));
        $exams_page->setColOption("weight", array("search" => false, "hidden" => false, "width" => 70));
        $exams_page->gridComplete_JS = "function() {
            $('#exam_cont .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                $.ajax({
                    url: '" . site_url("patient_examination/patient_view_exam") . "/' + rowId,
                    type: 'GET',
                    success: function(data) {
                        $('#modalContent').html(data);
                        $('#myModal').modal('show');
                    }
                });
            });
        }";
        $exams_page->setOrientation_EL("L");
        return $exams_page->render(false);
    }

    private
    function loadAlergy($pid)
    {
        $qry
            = "SELECT patient_allergy.ALLERGYID ,
	SUBSTRING(patient_allergy.CreateDate,1,10) as dte,
	patient_allergy.Name,
	patient_allergy.Status
	FROM patient_allergy
	where (patient_allergy.PID ='" . $pid . "') and (patient_allergy.Active = 1)";
        $this->load->model('mpager', 'alergy_page');
        $alergy_page = $this->alergy_page;
        $alergy_page->setSql($qry);
        $alergy_page->setDivId("alergy_cont"); //important
        $alergy_page->setDivClass('');
        $alergy_page->setRowid('ALLERGYID');
        $alergy_page->setCaption(lang("Allergies"));
        $alergy_page->setShowHeaderRow(false);
        $alergy_page->setShowFilterRow(false);
        $alergy_page->setShowPager(false);
        $alergy_page->setColNames(array("ID", "", "", ""));
        $alergy_page->setRowNum(25);
        $alergy_page->setColOption("ALLERGYID", array("search" => false, "hidden" => true));
        $alergy_page->setColOption("dte", array("search" => false, "hidden" => false, "width" => 70));
        $alergy_page->gridComplete_JS = "function() {
            $('#alergy_cont .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                $.ajax({
                    url: '" . site_url("patient_allergy/patient_view_allergy") . "/' + rowId,
                    type: 'GET',
                    success: function(data) {
                        $('#modalContent').html(data);
                        $('#myModal').modal('show');
                    }
                });
            });
        }";
        $alergy_page->setOrientation_EL("L");
        return $alergy_page->render(false);
    }

    private
    function loadLabOrder($pid)
    {
        $qry = "SELECT lab_order.LAB_ORDER_ID ,
	SUBSTRING(lab_order.OrderDate,1,10) as dte,
	lab_test_group.Name as TestGroupName,
	lab_order.Status
	FROM lab_order
	LEFT JOIN lab_test_group ON lab_test_group.LABGRPTID = lab_order.TestGroupID
	WHERE (lab_order.PID ='" . $pid . "') and (lab_order.Active = 1)";
        $this->load->model('mpager', 'lab_order_page');
        $lab_order_page = $this->lab_order_page;
        $lab_order_page->setSql($qry);
        $lab_order_page->setDivId("lab_cont"); //important
        $lab_order_page->setDivClass('');
        $lab_order_page->setRowid('LAB_ORDER_ID');
        $lab_order_page->setCaption("Latest lab results");
        $lab_order_page->setShowHeaderRow(false);
        $lab_order_page->setShowFilterRow(false);
        $lab_order_page->setColNames(array("ID", "", "", ""));
        $lab_order_page->setRowNum(25);
        $lab_order_page->setColOption("LAB_ORDER_ID", array("search" => false, "hidden" => false, "width" => 30));
        $lab_order_page->setColOption("dte", array("search" => false, "hidden" => false, "width" => 80));
        $lab_order_page->setColOption("TestGroupName", array("search" => false, "hidden" => false, "width" => 120));
        $lab_order_page->setColOption("Status", array("search" => false, "hidden" => false, "width" => 70));

        $lab_order_page->gridComplete_JS = "function() {
		$('div[id ^= \"pager\"]').hide();
        $('#lab_cont .jqgrow').mouseover(function(e) {
            var rowId = $(this).attr('id');
            $(this).css({'cursor':'pointer'});
        }).mouseout(function(e){
        }).click(function(e){
            var rowId = $(this).attr('id');
            window.location='" . site_url("patient_lab_order/update") . "/'+rowId+'?CONTINUE=patient/view/$pid';
        });
        }";
        $lab_order_page->setOrientation_EL("L");
        return $lab_order_page->render(false);
    }

    private
    function loadPrescription($pid)
    {
        $qry
            = "SELECT
                SUBSTRING(patient_prescription_have_drug.CreateDate,1,10) as dte,
                who_drug.name as Name,
                patient_prescription_have_drug.Period as HowLong,
                drugs_dosage.Dosage as Dosage,
                drugs_frequency.Frequency as Frequency
            FROM patient_prescription_have_drug
			LEFT JOIN who_drug ON who_drug.wd_id = patient_prescription_have_drug.DrugID
			LEFT JOIN drugs_dosage ON drugs_dosage.DDSGID = patient_prescription_have_drug.DoseID
			LEFT JOIN drugs_frequency ON drugs_frequency.DFQYID = patient_prescription_have_drug.FrequencyID
			where PID = " . $pid;
        $this->load->model('mpager', 'prescription_page');
        $prescription_page = $this->prescription_page;
        $prescription_page->setSql($qry);
        $prescription_page->setDivId("pre_cont"); //important
        $prescription_page->setDivClass('');
        //$lab_order_page->setRowid('LAB_ORDER_ID');
        $prescription_page->setCaption("Medication history");
        $prescription_page->setShowHeaderRow(false);
        $prescription_page->setShowFilterRow(false);
        $prescription_page->setColNames(array("ID", "", "", "", ""));
        $prescription_page->setRowNum(25);
        //$lab_order_page->setColOption("LAB_ORDER_ID",array("search"=>false,"hidden" => false,"width"=>30));
        $prescription_page->setColOption("dte", array("search" => false, "hidden" => false, "width" => 50));
        $prescription_page->setColOption("Name", array("search" => false, "hidden" => false, "width" => 190));
        $prescription_page->setColOption("HowLong", array("search" => false, "hidden" => false, "width" => 70));
        $prescription_page->setColOption("Dosage", array("search" => false, "hidden" => false, "width" => 30));
        $prescription_page->setColOption("Frequency", array("search" => false, "hidden" => false, "width" => 40));


        $prescription_page->gridComplete_JS
            = "function() {
		$('div[id ^= \"pager\"]').hide();
        }";
        $prescription_page->setOrientation_EL("L");
        return $prescription_page->render(false);
    }

    private
    function loadNotes($pid)
    {
        $qry
            = "SELECT patient_notes.patient_notes_id ,
	SUBSTRING(patient_notes.CreateDate,1,10) as dte,
	Type,
	patient_notes.notes
	FROM patient_notes
	where (patient_notes.PID ='" . $pid . "') and (patient_notes.Active = 1) ";
        $this->load->model('mpager', 'patient_notes');
        $patient_notes = $this->patient_notes;
        $patient_notes->setSql($qry);
        $patient_notes->setDivId("notes_cont"); //important
        $patient_notes->setDivClass('');
        $patient_notes->setRowid('patient_notes_id');
        $patient_notes->setCaption(lang("Nursing Notes"));
        $patient_notes->setShowHeaderRow(false);
        $patient_notes->setShowFilterRow(false);
        $patient_notes->setShowPager(false);
        $patient_notes->setColNames(array("ID", "", "", ""));
        $patient_notes->setRowNum(25);
        $patient_notes->setColOption("patient_notes_id", array("search" => false, "hidden" => true));
        $patient_notes->setColOption("dte", array("search" => false, "hidden" => false, "width" => 70));
        $patient_notes->setColOption("notes", array("search" => false, "hidden" => false, "width" => 70));
        $patient_notes->gridComplete_JS = "function() {
            $('#notes_cont .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                $.ajax({
                    url: '" . site_url("patient_note/patient_view_note") . "/' + rowId,
                    type: 'GET',
                    success: function(data) {
                        $('#modalContent').html(data);
                        $('#myModal').modal('show');
                    }
                });
            });
        }";
        $patient_notes->setOrientation_EL("L");
        return $patient_notes->render(false);
    }

    public
    function notes($id = NULL)
    {
        if (!is_numeric($id)) {
            die("Patient ID not valid");
        }
        $this->load->model('mpersistent');
        $this->load->model('mpatient');
        $data["patient_info"] = $this->mpersistent->open_id($id, "patient", "PID");
        if (($data["patient_info"]["DateOfBirth"]) === '0000-00-00') {
            $data["patient_info"]["Age"] = $this->get_age_ref($data["patient_info"]["DateOfBirthReferred"]);
        } else {
            $data["patient_info"]["Age"] = $this->get_age($data["patient_info"]["DateOfBirth"]);
        }
        $data["patient_info"]["HIN"] = $this->print_hin($data["patient_info"]["HIN"]);
        $data["patient_notes_list"] = $this->mpatient->get_notes_list($id, "patient");
        $data["opd_notes_list"] = $this->mpatient->get_notes_list($id, "opd");
        //print_r($data["opd_notes_list"]);
        $this->load->vars($data);
        $this->load->view('patient_notes');
    }

    public
    function search()
    {
        if (!has_permission('patient', 'view')) {
            $this->show_no_permission();
            return;
        }
        $this->set_top_selected_menu('patient/search');

        $this->load->model('mpager');
        $pager2 = $this->mpager;
        $pager2->setSql(
            "SELECT PID, 
                    patient.Name, 
                    patient.Firstname, 
                    IF(patient.DateOfBirth = '0000-00-00', patient.DateOfBirthReferred, patient.DateOfBirth) AS DateOfBirth,
                    patient.Gender, 
                    patient.Address_Street, 
                    patient.CreateDate,
                    CONCAT(user.Name,' ',user.OtherName) AS Created_By
             FROM patient
             LEFT JOIN user ON user.UID = patient.CreateUser"
        );
        
        $pager2->setDivId('tablecont1'); //important
        $pager2->setDivStyle('width:100%;margin:0 auto;');
        $pager2->setRowid('PID');
        //        $pager2->setWidth("95%");
        $tools = "";
        $pager2->setCaption($tools);
        $pager2->setColNames(
            array("NID", lang("Surname"), lang("First Name"), lang("Date of Birth"), lang("Gender"), lang("Address"), lang('Time'), lang('Created By'))
        );
        $pager2->setColOption("PID", array("search" => true, "hidden" => false, "height" => 100, "width" => 100));
        $pager2->setColOption("Name", array("search" => true, "width" => 200));
        $pager2->setColOption("Firstname", array("search" => true, "width" => 200));
        $pager2->setColOption("DateOfBirth", $pager2->getDateSelector());
        // $pager2->setColOption("DateOfBirthReferred", $pager2->getDateSelector());
        $pager2->setColOption(
            "Gender",
            array("stype" => "select", "searchoptions" => array("value" => ":All;M:M;F:F"))
        );
        //"Single","Married","Divorced","Widow","UnKnown"
        //        $pager2->setColOption(
        //            "Personal_Civil_Status", array("stype" => "select",
        //                "searchoptions" => array("value" => ":Todos;Solteiro:Solteiro;Casado:Casado;Divorciado:Divorciado;Viuva:Viuva;Outro:Outro",
        //                    "defaultValue" => "Todos"))
        //        );
        //$pager2->setColOption("CreateDate", array("stype" => "text", "searchoptions" => array("dataInit_JS" => "datePicker_REFID","defaultValue"=>"")));
        $pager2->setSortname('PID');
        $pager2->gridComplete_JS
            = "function() {

            var c = null;
            $('.jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                c = $(this).css('background');
                $(this).css({'background':'#FFFFFF','cursor':'pointer'});
            }).mouseout(function(e){
            $(this).css('background',c);
            }).mousedown(function(e){
                var rowId = $(this).attr('id');
                window.location='" . base_url() . "index.php/patient/view/'+rowId;
            });

            }";
        $pager2->setOrientation_EL("L");
        $data['pager'] = $pager2->render(false);
        $this->qch_template->load_form_layout('patient_search', $data);
    }

    public
    function update_hin()
    {
        if ($this->session->userdata("UserGroup") != "Programmer") {
            echo "-NO ACCESS-";
            return;
        }
        $this->load->model("mpatient");
        $this->load->model("mpersistent");


        $data["Patient_list"] = $this->mpatient->get_all_patient();
        echo "UPDATING HIN " . count($data["Patient_list"]) . "<hr>";
        echo "<table border=1>";
        for ($i = 0; $i < count($data["Patient_list"]); ++$i) {
            echo "<tr>";
            echo "<td>";
            echo $data["Patient_list"][$i]["PID"];
            echo "</td>";
            echo "<td>";
            $HIN = $this->get_hin($data["Patient_list"][$i]["PID"]);
            $hstatus = $this->mpersistent->update("patient", "PID", $data["Patient_list"][$i]["PID"], array("HIN" => $HIN));
            //echo chunk_split($HIN, 4, '-');
            //echo $HIN."--";
            echo $this->print_hin($HIN);
            // substr($HIN, 9);
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    /*********************************END OF MD*******************/
    public
    function get_hin($s)
    {
        $hospital = $this->session->userdata("hospital_info");
        $h_code = $hospital["Code"];
        $pid = sprintf("%06s", $s);
        $hin = $h_code . $pid;
        $hin_number = $hin;
        $hin = $hin . "0";
        $sum = 0;
        $i = strlen($hin);     // Find the last character
        $odd_length = $i % 2;
        while ($i-- > 0) { // Iterate all digits backwards
            $sum += $hin[$i];    // Add the current digit
            // If the digit is even, add it again. Adjust for digits 10+ by subtracting 9.
            ($odd_length == ($i % 2)) ? ($hin[$i] > 4) ? ($sum += ($hin[$i] - 9)) : ($sum += $hin[$i]) : false;
        }
        return $hin_number . (10 - ($sum % 10)) % 10; //returns the luhn check digit
    }

    public
    function save()
    {
        //print_r($_POST);
        $frm = 'patient';
        if (!file_exists('application/forms/' . $frm . '.php')) {
            die("Form " . $frm . "  not found");
        }
        include 'application/forms/' . $frm . '.php';
        $data["form"] = $form;
        //print_r($data);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model("mpersistent");
        $this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
        for ($i = 0; $i < count($form["FLD"]); ++$i) {
            $this->form_validation->set_rules(
                $form["FLD"][$i]["name"],
                '"' . $form["FLD"][$i]["label"] . '"',
                $form["FLD"][$i]["rules"]
            );
        }
        $this->form_validation->set_rules($form["OBJID"]);
        $this->form_validation->set_rules("year", "Age", "numeric|xss_clean");
        $this->form_validation->set_rules("month", "Age", "numeric|xss_clean");
        $this->form_validation->set_rules("day", "Age", "numeric|xss_clean");

        if ($this->form_validation->run() == FALSE) {
            $this->load->vars($data);
            echo Modules::run('form/create', 'patient');
        } else {
            //$sve_data = array();
            //for ( $i=0; $i < count($form["FLD"]); ++$i ){
            //$sve_data[$form["FLD"][$i]["name"]] = $this->input->post($form["FLD"][$i]["name"]);
            //}
            $year = $this->input->post("year");
            $month = $this->input->post("month");
            $day = $this->input->post("day");

            if ($this->input->post("DateOfBirth") == "") {
                $dob = date('Y-m-d', mktime(0, 0, 0, date("m") - $month, date("d") - $day, date("Y") - $year));
            } else {
                $dob = $this->input->post("DateOfBirth");
            }
            $sve_data = array(
                'Personal_Title' => $this->input->post("Personal_Title"),
                'Full_Name_Registered' => ucfirst(strtolower($this->input->post("Full_Name_Registered"))),
                'Personal_Used_Name' => strtoupper($this->input->post("Personal_Used_Name")),
                'Gender' => $this->input->post("Gender"),
                'Personal_Civil_Status' => $this->input->post("Personal_Civil_Status"),
                'DateOfBirth' => $dob,
                'NIC' => $this->input->post("NIC"),
                'Telephone' => $this->input->post("Telephone"),
                'occupation' => $this->input->post("occupation"),
                'Address_Street' => $this->input->post("Address_Street"),
                'Address_Street1' => $this->input->post("Address_Street1"),
                'Address_Village' => $this->input->post("Address_Village"),
                'Address_District' => $this->input->post("Address_District"),
                'Address_DSDivision' => $this->input->post("Address_DSDivision"),
                'Remarks' => $this->input->post("Remarks"),
                'HID' => $this->session->userdata('HID')

            );
            $id = $this->input->post($form["OBJID"]);
            $status = false;

            if ($id > 0) {
                $status = $this->mpersistent->update($frm, $form["OBJID"], $id, $sve_data);
                $this->session->set_flashdata(
                    'msg',
                    'REC: ' . ucfirst(strtolower($this->input->post("Full_Name_Registered"))) . ' Updated'
                );
                if ($status) {
                    header("Status: 200");
                    if (isset($_POST["CONTINUE"])) {
                        header("Location: " . site_url($_POST["CONTINUE"]));
                        return;
                    } else {
                        header("Location: " . site_url($form["NEXT"] . '/' . $status));
                        return;
                    }
                }
            } else {
                $sve_data['LPID'] = $this->get_unique_id($this->input->post("DateOfBirth"));
                $status = $this->mpersistent->create($frm, $sve_data);
                $HIN = $this->get_hin($status);
                $hstatus = $this->mpersistent->update($frm, "PID", $status, array("HIN" => $HIN));
                $this->session->set_flashdata(
                    'msg',
                    'REC: ' . ucfirst(strtolower($this->input->post("Full_Name_Registered"))) . $HIN . ' created'
                );
                if ($status > 0) {
                    //echo Modules::run($form["NEXT"], $status);
                    header("Status: 200");
                    if (isset($_POST["CONTINUE"]) && $_POST["CONTINUE"] != '') {
                        header("Location: " . site_url($_POST["CONTINUE"]));
                        return;
                    } else {
                        header("Location: " . site_url($form["NEXT"] . '/' . $status));
                        return;
                    }
                }
            }
            echo "ERROR in saving";
        }
    }

    public
    function get_unique_id($dob)
    {
        $yyyy = substr($dob, 0, 4);
        $mm = substr($dob, 5, 2);
        $dd = substr($dob, 8, 2);
        //echo $yyyy.$mm.$dd.substr(number_format(str_replace(".","",microtime(true)*rand()),0,'',''),0,14);
        //echo $yyyy.$mm.$dd.time();
        //echo $yyyy.$mm.$dd.substr(number_format(str_replace(".","",microtime(true)*rand()),0,'',''),0,8);
        return
            $yyyy . $mm . $dd . substr(number_format(str_replace(".", "", microtime(true) * rand()), 0, '', ''), 0, 8);
    }

    public
    function nic_check($nic)
    {
        if ($nic == "") {
            return TRUE;
        }
        $reg = '/^(\d\d\d\d\d\d\d\d\d)[xXvV]$/';
        if (preg_match($reg, $nic) == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public
    function get_initial()
    {
        return ucwords($this->mpersistent->get_value("Personal_Used_Name"));
    }

    public
    function get_name()
    {
        return ucfirst($this->mpersistent->get_value("Full_Name_Registered"));
    }

    public
    function get_address()
    {
        $address = "";
        if (ucfirst($this->mpersistent->get_value("Address_Street")) != "") {
            $address
                .= ucfirst($this->mpersistent->get_value("Address_Street")) . "<br>";
        }
        if (ucfirst($this->mpersistent->get_value("Address_Street1")) != "") {
            $address
                .= ucfirst($this->mpersistent->get_value("Address_Street1")) . "<br>";
        }
        if (ucfirst($this->mpersistent->get_value("Address_Village")) != "") {
            $address
                .= ucfirst($this->mpersistent->get_value("Address_Village")) . "<br>";
        }
        if (ucfirst($this->mpersistent->get_value("Address_DSDivision")) != "") {
            $address
                .= ucfirst($this->mpersistent->get_value("Address_DSDivision")) . "<br>";
        }
        if (ucfirst($this->mpersistent->get_value("Address_District")) != "") {
            $address
                .= ucfirst($this->mpersistent->get_value("Address_District")) . "<br>";
        }
        return $address;
    }

    public
    function get_full_name()
    {
        $fName = "";
        $fName .= ucwords(
            $this->mpersistent->get_value("Personal_Title") . " " . $this->mpersistent->get_value("Personal_Used_Name")
        );
        $fName .= " " . $this->mpersistent->get_value("Full_Name_Registered") . " ";
        return $fName;
    }

    public
    function get_civil_status()
    {
        //if (!$this->Fields[$this->ObjField]) return NULL;
        return ucwords($this->mpersistent->get_value("Personal_Civil_Status"));
    }

    public
    function get_date_of_birth()
    {
        //if (!$this->Fields[$this->ObjField]) return NULL;
        return $this->mpersistent->get_value("DateOfBirth");
    }

    public
    function get_NIC()
    {
        //if (!$this->Fields[$this->ObjField]) return NULL;
        return $this->mpersistent->get_value("NIC");
    }

    public
    function get_gender()
    {
        //if (!$this->Fields[$this->ObjField]) return NULL;
        return $this->mpersistent->get_value("Gender");
    }

    private function show_form($data)
    {
        $this->load->vars($data);
        $this->load->view('form_patient');
    }

    private function loadHistory($pid)
    {
        if ($this->session->userdata('department') == "EMR") {
            $qry
                 = " SELECT HID, 
                   SUBSTRING(CreateDate,1,10) as dte,
                   COALESCE(NULLIF(Complaint, ''), 
                             (SELECT Complaint 
                              FROM emergency_admission 
                              WHERE PID = '". $pid ."' 
                              AND DATE(CreateDate) = DATE(medical_history.CreateDate) 
                              LIMIT 1)) AS Complaint,
                   HistoryOfComplaint,
                   CASE 
                       WHEN Complaint IS NULL OR Complaint = '' THEN 
                           (SELECT CONCAT(u.Name, ' ', u.OtherName) 
                            FROM user u 
                            JOIN emergency_admission ea ON ea.ObservationDoctorUID = u.UID 
                            WHERE ea.PID = '". $pid ."' 
                            AND DATE(ea.CreateDate) = DATE(medical_history.CreateDate) 
                            LIMIT 1)
                       ELSE Doctor 
                   END AS Doctor
            FROM medical_history
            where (PID ='" . $pid . "') and (Active = 1)";
        } else {
            $ref_id = 14;
            $ref_type = "ADM";
            $qry = "SELECT HID ,SUBSTRING(CreateDate,1,10) as dte, Complaint, HistoryOfComplaint, Doctor
            FROM medical_history
            where (PID ='" . $pid . "') and (Active = 1) and (Ref_type ='".$ref_type."') and (Ref_id ='".$ref_id."')";
        }
       
        $this->load->model('mpager', 'history_page');
        $history_page = $this->history_page;
        $history_page->setSql($qry);
        $history_page->setDivId("his_cont"); 
        $history_page->setDivClass('');
        $history_page->setRowid('HID');
        $history_page->setCaption(lang("Clinic History"));
        $history_page->setShowHeaderRow(true);
        $history_page->setShowFilterRow(false);
        $history_page->setShowPager(false);
        $history_page->setColNames(array("",lang("Date"), lang("Complaint"), lang("HistoryOfComplaint"), lang("Doctor")));
        $history_page->setRowNum(25);
        $history_page->setColOption("HID", array("search" => false, "hidden" => true));
        $history_page->setColOption("dte", array("search" => false, "hidden" => false));
        $history_page->setColOption("Complaint", array("search" => false, "hidden" => false));
        $history_page->setColOption("HistoryOfComplaint", array("search" => false, "hidden" => false));
        $history_page->setColOption("Doctor", array("search" => false, "hidden" => false));
        $history_page->gridComplete_JS = "function() {
           $('#his_cont .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                $.ajax({
                    url: '" . site_url("patient_history/patient_view_history") . "/' + rowId,

                    type: 'GET',
                    success: function(data) {
                        $('#modalContent').html(data);
                        $('#myModal').modal('show');
                    }
                });
            });
        }";
        $history_page->setOrientation_EL("L");
        
        return $history_page->render(false);
    }

    private
    function loadAttachment($pid)
    {
        $qry
            = "SELECT attachment.Attach_Hash ,
	SUBSTRING(attachment.CreateDate,1,10) as dte,
	attachment.Attach_Name,
	attachment.Attach_Type,
	attachment.Attach_Description
	FROM attachment
	where (attachment.PID ='" . $pid . "') and (attachment.Active = 1)";
        $this->load->model('mpager', 'attach_page');
        $attach_page = $this->attach_page;
        $attach_page->setSql($qry);
        $attach_page->setDivId("attach_cont"); //important
        $attach_page->setDivClass('');
        $attach_page->setRowid('Attach_Hash');
        $attach_page->setCaption("Files attached to the patient record");
        $attach_page->setShowHeaderRow(false);
        $attach_page->setShowFilterRow(false);
        $attach_page->setColNames(array("ID", "", "", "", ""));
        $attach_page->setRowNum(25);
        $attach_page->setColOption("Attach_Hash", array("search" => false, "hidden" => true, "width" => 30));
        $attach_page->setColOption("dte", array("search" => false, "hidden" => false, "width" => 60));
        $attach_page->setColOption("Attach_Name", array("search" => false, "hidden" => false, "width" => 70));
        $attach_page->setColOption("Attach_Type", array("search" => false, "hidden" => false, "width" => 60));
        $attach_page->gridComplete_JS
            = "function() {
		$('div[id ^= \"pager\"]').hide();
        $('#attach_cont .jqgrow').mouseover(function(e) {
            var rowId = $(this).attr('id');
            $(this).css({'cursor':'pointer'});
        }).mouseout(function(e){
        }).click(function(e){
            var rowId = $(this).attr('id');
            var params = 'menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width='+screen.availWidth+',height='+screen.availHeight;
		    var url = '" . site_url("attach/view/") . "/'+rowId;
			window.open('' + url + '', 'lookUpW', params);
        });
    }";
        $attach_page->setOrientation_EL("L");
        return $attach_page->render(false);
    }

    public function find_similar_patient_by_name()
    {
        $name = $this->input->get('term1');
        $firstname = $this->input->get('term2');
        $bi = $this->input->get('term3');
        $sql = "SELECT * FROM patient
                WHERE (Name LIKE '$name%' and Firstname LIKE '$firstname%')
                LIMIT 30";
        $query = $this->db->query($sql);
        $results = array();
        foreach ($query->result() as $row) {
            $result = array();
            $result['id'] = $row->PID;
            $result['name'] = $row->Personal_Title . ' ' . $row->Firstname . ' ' . $row->Name;
            $result['birthday'] = $row->DateOfBirth;
            $result['gender'] = $row->Gender;
            array_push($results, $result);
        }
        echo json_encode($results);
    }

    public function find_similar_patient_by_telephone()
    {
        $telephone = $this->input->get('term1');
        $sql = "SELECT * FROM patient
                WHERE Telephone LIKE '$telephone%' 
                LIMIT 30";
        $query = $this->db->query($sql);
        $results = array();
        foreach ($query->result() as $row) {
            $result = array();
            $result['id'] = $row->PID;
            $result['name'] = $row->Personal_Title . ' ' . $row->Firstname . ' ' . $row->Name;
            $result['birthday'] = $row->DateOfBirth;
            $result['gender'] = $row->Gender;
            array_push($results, $result);
        }
        echo json_encode($results);
    }

    public function find_similar_patient_by_bi_id()
    {
        $bi = $this->input->get('term1');
        $sql = "SELECT * FROM patient
                WHERE BI_ID LIKE '$bi%' 
                LIMIT 30";
        $query = $this->db->query($sql);
        $results = array();
        foreach ($query->result() as $row) {
            $result = array();
            $result['id'] = $row->PID;
            $result['name'] = $row->Personal_Title . ' ' . $row->Firstname . ' ' . $row->Name;
            $result['birthday'] = $row->DateOfBirth;
            $result['gender'] = $row->Gender;
            array_push($results, $result);
        }
        echo json_encode($results);
    }
}


function date_difference($startDate, $endDate)
{

    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    $years = $months = $days = 0;

    $two = $startDate;
    $one = $endDate;
    $invert = false;
    if ($one > $two) {
        list($one, $two) = array($two, $one);
        $invert = true;
    }

    $key = array("y", "m", "d", "h", "i", "s");
    $a = array_combine($key, array_map("intval", explode(" ", date("Y m d H i s", $one))));
    $b = array_combine($key, array_map("intval", explode(" ", date("Y m d H i s", $two))));

    $result = array();
    $result["y"] = $b["y"] - $a["y"];
    $result["m"] = $b["m"] - $a["m"];
    $result["d"] = $b["d"] - $a["d"];
    $result["h"] = $b["h"] - $a["h"];
    $result["i"] = $b["i"] - $a["i"];
    $result["s"] = $b["s"] - $a["s"];
    $result["invert"] = $invert ? 1 : 0;
    $result["days"] = intval(abs(($one - $two) / 86400));

    return array($result["y"], $result["m"], $result["d"]);
}

/*function get_age($dob, $compare_date = NULL)
{
   if ($dob == '0000-00-00') {
       return NULL;
   }

   $date1 = $dob;
   if ($compare_date == NULL) {
       $date2 = date('Y-m-d');
   }   else {
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
    }   else {
        if ($obj['years'] >= 5) {
            return $obj['years']. 'A';
        }   elseif ($obj['years'] >= 1) {
            return $obj['years']. 'A '. $obj['months']. 'M';
        }   else {
            return $obj['years']. 'M '. $obj['days']. 'D';
        }
    }
}*/


//////////////////////////////////////////

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
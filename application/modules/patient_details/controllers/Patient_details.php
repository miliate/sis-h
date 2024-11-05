<?php

class Patient_details extends FormController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_details');
    }

    public function create($pid, $ref_id = null)
    {
        $patient_details_data = $this->m_patient_details->get_patient_details_by_patient($pid);
        $data = array();
        $data['pid'] = $pid;
        $data['ref_id'] = $ref_id;

        if (sizeof($patient_details_data) > 0) {
            $patient_details = $patient_details_data[0];
            $data['id']=  $patient_details['id'];
            $data['patient_school_level'] = $patient_details['patient_school_level'];
            $data['patient_profession'] = $patient_details['patient_profession'];
            $data['patient_work'] = $patient_details['patient_work'];
            $data['patient_people_live'] = $patient_details['patient_people_live'];
            $data['patient_lives_alone'] = $patient_details['patient_lives_alone'];
            $data['patient_head_household'] = $patient_details['patient_head_household'];
            $data['patient_source_income'] = $patient_details['patient_source_income'];
            $data['patient_profile'] = $patient_details['patient_profile'];
        } else {
            $data['patient_school_level'] = "";
            $data['patient_profession'] = "";
            $data['patient_work'] = "";
            $data['patient_people_live'] = "";
            $data['patient_lives_alone'] = 2;
            $data['patient_head_household'] = 2;
            $data['patient_source_income'] = 2;
            $data['patient_profile'] = "";
        }

        $this->set_common_validation();

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            if(sizeof($patient_details_data) > 0){
                $data = array(
                    'id'=> $data['id'],
                    'active'=>false
                );
                $data_updated = $this->getUpdated($pid);
                $this->m_patient_details->update($data['id'], $data);
                $this->m_patient_details->insert($data_updated);
            }else {
                $data = $this->getUpdated($pid);
                $this->m_patient_details->insert($data);
            }

            //redirect
            $this->session->set_flashdata(
                'msg',
                'REC: ' . ucfirst(strtolower($pid . ' created'))
            );
            $this->redirect_if_no_continue('/active_list/create/' . $pid);
        }
    }

    public function edit($id)
    {
        if (!Modules::run('permission/check_permission', 'patient', 'edit'))
            die('You do not have permission!');
        $contact = $this->m_patient_contact->get($id);
        if (empty($contact)) {
            die('Id wrong');
        }
        $ip = $this->input->ip_address();
        $data = array();
        $data['pid'] = $id;
        $data['pid'] = $contact->PID;
        $data['default_contact_name'] = $contact->ContactPerson;
        $data['default_contact_kinship'] = $contact->ContactKinship;
        $data['default_contact_address'] = $contact->ContactAddress;
        $data['default_contact_working'] = $contact->ContactWorkingPlace;
        $data['default_contact_telephone'] = $contact->ContactTelephone;
        $data['default_contact_email'] = $contact->ContactEmail;
        $data['default_has_contact'] = false;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'PEMRCID' => $id,
                'ContactPerson' => $this->input->post('contact_name'),
                'ContactKinship' => $this->input->post('contact_kinship'),
                'ContactAddress' => $this->input->post('contact_address'),
                'ContactWorkingPlace' => $this->input->post('contact_working_place'),
                'ContactTelephone' => $this->input->post('contact_telephone'),
                'ContactEmail' => $this->input->post('contact_email'),
                'LastUpDateIP' => $ip
            );
            $this->m_patient_contact->update($id, $data);
            $this->session->set_flashdata(
                'msg',
                'Updated'
            );
            $this->redirect_if_no_continue('/patient/view/' . $contact->PID);
        }
    }

    public function set_common_validation()
    {
        $this->form_validation->set_rules('patient_school_level', 'School Level', 'trim|required');
    }

    /**
     * @param $pid
     * @return array
     */
    public function getUpdated($pid): array
    {
        return array(
            'PID' => $pid,
            'patient_school_level' => $this->input->post('patient_school_level'),
            'patient_profession' => $this->input->post('patient_profession'),
            'patient_work' => $this->input->post('patient_work'),
            'patient_lives_alone' => $this->input->post('patient_lives_alone'),
            'patient_head_household' => $this->input->post('patient_head_household'),
            'patient_people_live' => $this->input->post('patient_people_live'),
            'patient_source_income' => $this->input->post('patient_source_income'),
            'patient_profile' => $this->input->post('patient_profile'),
            'active' => true
        );
    }

}

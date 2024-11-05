<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 13-Oct-2021
 * Time: 9:10 AM
 */
class Sap_companies_patient extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_sap_companies_patient');
        $this->load->model('m_sap_companies_type');
        $this->load->model('m_sap_companies');
    }

    public function create($pid)
    {
//        if (!Modules::run('permission/check_permission', 'sap_patient_companies', 'create'))
//            die('You do not have permission!');
        $ip = $this->input->ip_address();
        $data = array();
        $data['pid'] = $pid;
        $data['default_company_type_id'] = '';
        $data['dropdown_company_type_id'] = $this->get_dropdown_company_type_id();
        $data['default_company_id'] = '';
        $data['dropdown_company_id'] = $this->get_dropdown_company();
        $data['default_member_number'] = '';
        $data['default_member_reference'] = '';
        $data['default_member_main_id'] = '';
        $data['default_member_is_dependent'] = '';
        $data['default_relation_type'] = '';
        $data['default_relation_year'] = '';
        $data['default_Remarks'] = '';



        $this->form_validation->set_rules('company_id', 'Empresa', 'trim|xss_clean|required');
        $this->form_validation->set_rules('company_type_id', 'Tipo de Empresa', 'trim|xss_clean|required');
        $this->form_validation->set_rules('member_number', 'Numero de Membro', 'trim');
        $this->form_validation->set_rules('member_reference', 'Ref. de Membro', 'trim');
        $this->form_validation->set_rules('member_main_id', 'Membro Principal', 'trim');
        $this->form_validation->set_rules('member_is_dependent', 'Membro Dependente', 'trim');
        $this->form_validation->set_rules('relation_type', 'Tipo de Relacao com a Empresa', 'trim');
        $this->form_validation->set_rules('relation_year', 'Ano de Contrato', 'trim|xss_clean|numeric|max_length[4]');

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'PID' => $pid,
                'company_id' => $this->input->post('company_id'),
                'company_type_id' => $this->input->post('company_type_id'),
                'member_number' => $this->input->post('member_number'),
                'member_reference' => $this->input->post('member_reference'),
                'member_main_id' => $this->input->post('member_main_id'),
                'member_is_dependent' => $this->input->post('member_is_dependent'),
                'relation_type' => $this->input->post('relation_type'),
                'relation_year' => $this->input->post('relation_year'),
                'Remarks' => $this->input->post('Remarks')
            );
            $this->m_patient_contact->insert($data);
            //redirect
            $this->session->set_flashdata(
                'msg', 'REC: ' . ucfirst('Dados do Paciente '.$pid.' actualizados com sucesso!')
            );
            $this->redirect_if_no_continue('/patient/view/'.$pid);
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

        $this->form_validation->set_rules('contact_name', 'Name', 'trim');
        $this->form_validation->set_rules('contact_kinship', 'Contact Kinship', 'trim');
        $this->form_validation->set_rules('contact_address', 'Contact address', 'trim');
        $this->form_validation->set_rules('contact_working_place', 'Working place', 'trim');
        $this->form_validation->set_rules('contact_telephone', 'Telephone', 'trim');
        $this->form_validation->set_rules('contact_email', 'Email', 'trim');

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
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/patient/view/' . $contact->PID);
        }
    }

    public function search($type = 'json')
    {
        return 'SIS-H';
    }

    public function get_dropdown_company_type_id($type = 'json')
    {
        $res = $this->m_sap_companies_type->order_by('name', 'asc')->dropdown('id', 'name');
        $res[''] = '';
        return $res;
    }

    public function get_dropdown_company($type_id=3, $type = 'json')
    {
        $result = $this->m_sap_companies->order_by('name')->get_many_by(array('type_id' => $type_id));
        if ($type == 'json') {
            print(json_encode($result));
        } else {
            foreach ($result as $item) {
                $drop_down[$item->id] = $item->name;
                
            }
            return $drop_down;
        }
    }


   
}

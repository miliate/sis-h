<?php

class Ward_beds extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_ward_beds');
        $this->load->model('m_ward_rooms');
        $this->load->model('m_ward');
    }

    public function create()
    {
        $result = array(''=>'');
        foreach ($this->m_ward->get_all_wards() as $row) {
            $result[$row['WID']] = $row['Name'];
        }
    
        $data = array();
        $data['id'] = 0;
        $data['defoult_bed'] = '';
        $data['default_ward_names'] = $result;
        $data['default_room_names'] = '';
        $data['default_Active'] = '';
    
        $this->set_common_validation();
    
        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $room_id = $this->input->post('rooms');
            $bed_number = $this->input->post('beds');
    
            if ($this->m_ward_beds->bed_exists($room_id, $bed_number)) {
                $this->session->set_flashdata('error', lang('BedNumberExists'));
                $this->load_form($data);
            } else {
                $data = array(
                    'RID' => $room_id,
                    'BedNo' => $bed_number,
                    'Active' => $this->input->post('Active'),
                    'status' => "Available",
                );
                $this->m_ward_beds->insert($data);
                $this->session->set_flashdata('msg', 'Cama criada com sucesso');
                $this->redirect_if_no_continue('/preference/load/ward_beds');
            }
        }
    }
    
    public function get_rooms_dropdown($id)
    {

        $rooms_names = array();
        foreach ($this->m_ward_rooms->get_all_names($id) as $row) {
            $rooms_names[$row['RID']] = $row['Name'];
        }

        if ($rooms_names == null) {
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['error' => 'Drug ID não recebido']));
        return;
        } 

        $response = ['rooms_names' => $rooms_names];
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function edit($id) 
    {
        $ward_beds = $this->m_ward_beds->get($id);
        if (empty($ward_beds))
            die('Id not exist');

        $data['id'] = $id;
        $data['default_ward_names'] = $this->m_ward->get_ward_by_wid($this->m_ward_rooms->get_all_by_rid($ward_beds->RID)->WID)->Name;
        $data['default_room_names'] = $this->m_ward_rooms->get_all_by_rid($ward_beds->RID)->Name;
        $data['defoult_bed'] = $ward_beds->BedNo;
        $data['default_Active'] = $ward_beds->Active;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Active' => $this->input->post('Active'),
            );
            $this->m_ward_beds->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/preference/load/ward_beds');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('rooms', 'rooms', 'trim|xss_clean|required');
        $this->form_validation->set_rules('beds', 'beds', 'trim|xss_clean|required');
        $this->form_validation->set_rules('Active', 'Active', 'trim|xss_clean');
    }

}
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ward_rooms extends FormController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_ward');
        $this->load->model('m_ward_rooms');
    }

    public function create()
    {
        $wards = $this->m_ward->get_all_wards();
    
        $data = array();
        $data['id'] = 0;
        $data['wards'] = $wards; 
        $data['default_WID'] = ''; 
        $data['default_Name'] = '';
        $data['default_Telephone'] = '';
        $data['default_Active'] = '';
    
        $this->set_common_validation();
    
        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $ward_id = $this->input->post('WID');
            $room_name = $this->input->post('Name');
    
            if ($this->m_ward_rooms->room_exists($ward_id, $room_name)) {
                $this->session->set_flashdata('error', lang('RoomNameExists'));
                $this->load_form($data);
            } else {
                $data = array(
                    'WID' => $ward_id,
                    'Name' => $room_name,
                    'Telephone' => $this->input->post('Telephone'),
                    'Active' => $this->input->post('Active'),
                );
                $this->m_ward_rooms->insert($data);
                $this->session->set_flashdata('msg', 'Quarto criado com sucesso');
                $this->redirect_if_no_continue('/preference/load/ward_rooms');
            }
        }
    }
    
    public function edit($id)
    {
        $room = $this->m_ward_rooms->get($id);

        if (empty($room)) {
            show_error('Este quarto não existe'); 
        }

        $data = array();
        $data['id'] = $id;
        $data['default_WID'] = $room->WID;
        $data['default_Name'] = $room->Name;
        $data['default_Telephone'] = $room->Telephone;
        $data['default_Active'] = $room->Active;

        $data['wards'] = $this->m_ward->get_all_wards();

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $update_data = array(
                'WID' => $this->input->post('WID'),
                'Name' => $this->input->post('Name'),
                'Telephone' => $this->input->post('Telephone'),
                'Active' => $this->input->post('Active')
            );

            $this->m_ward_rooms->update($id, $update_data);

            $this->session->set_flashdata('msg', 'Sala Atualizada com Sucesso');
            $this->redirect_if_no_continue('/preference/load/ward_rooms');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('WID', 'Ward ID', 'trim|xss_clean|required');
        $this->form_validation->set_rules('Name', 'Room Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('Telephone', 'Telephone', 'trim|xss_clean');
        $this->form_validation->set_rules('Active', 'Active', 'trim|xss_clean|required');
    }
}

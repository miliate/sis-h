<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 13-Oct-15
 * Time: 3:19 PM
 */
class Top_Menu extends LoginCheckController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user_group');
        $this->load->model('m_top_menu');
        $this->load->model('m_top_menu_has_user_group');
    }
    public function create() {
        $data = array();
        $data['id'] = 0;
        $data['default_name'] = '';
        $data['default_link'] = '';
        $data['default_menu_order'] = '';
        $data['default_active'] = '';
        $data['selected_group'] = array();

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->show_form($data);
        } else {
            $this->insert();
        }
    }

    public function edit($id)
    {
        $current_top_menu = $this->m_top_menu->get($id);
        if (empty($current_top_menu))
            die('Id not exist');
        $data = array();
        $data['id'] = $id;
        $data['default_name'] = $current_top_menu->Name;
        $data['default_link'] = $current_top_menu->Link;
        $data['default_menu_order'] = $current_top_menu->MenuOrder;
        $data['default_active'] = $current_top_menu->Active;
        $data['selected_group'] = array();
        foreach($this->m_top_menu_has_user_group->get_many_by('MID', $id) as $obj) {
            array_push($data['selected_group'], $obj->UGID);
        }
        $this->set_common_validation();
        if ($this->form_validation->run() == False) {
            $this->show_form($data);
        } else {
            $this->update($id);
        }
    }

    private function set_common_validation() {
        $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('user_groups[]', 'User Groups', 'xss_clean');
        $this->form_validation->set_rules('link', 'Link', 'trim|xss_clean');
        $this->form_validation->set_rules('menu_order', 'Menu Order', 'trim|xss_clean|required');
        $this->form_validation->set_rules('link', 'Link', 'trim|xss_clean');
    }

    private function show_form($data)
    {
        $data['user_group_options'] = $this->m_user_group->dropdown('UGID', 'Name');
        $this->qch_template->load_form_layout('form_top_menu', $data);
    }

    private function insert()
    {
        $data = array(
            'Name' => $this->input->post('name'),
            'Active' => $this->input->post('active'),
            'Link' => $this->input->post('link'),
            'MenuOrder' => $this->input->post('menu_order'),
            'Active' => $this->input->post('active'),
        );
        $id = $this->m_top_menu->insert($data);
        $selected_user_groups = $this->input->post('user_groups') === FALSE ? array() : $this->input->post('user_groups');
        $this->m_top_menu_has_user_group->update_user_groups($id, $selected_user_groups);
        //redirect
        $this->session->set_flashdata(
            'msg', 'REC: ' . ucfirst(strtolower($this->input->post("name"))) . ' created'
        );
        header("Status: 200");
        header("Location: " . site_url('preference/load/user_menu'));
    }

    private function update($id) {
        $data = array(
            'Name' => $this->input->post('name'),
            'Active' => $this->input->post('active'),
            'Link' => $this->input->post('link'),
            'MenuOrder' => $this->input->post('menu_order'),
            'Active' => $this->input->post('active'),
        );
        $this->m_top_menu->update($id, $data);
        $selected_user_groups = $this->input->post('user_groups') === FALSE ? array() : $this->input->post('user_groups');
        $this->m_top_menu_has_user_group->update_user_groups($id, $selected_user_groups);
        $this->session->set_flashdata(
            'msg', 'REC: ' . ucfirst(strtolower($this->input->post("name"))) . ' created'
        );
        $this->redirect_if_no_continue('preference/load/user_menu');
    }
}
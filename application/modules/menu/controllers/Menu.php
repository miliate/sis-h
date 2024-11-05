<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends LoginCheckController
{

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {
        parent::__construct();
        $this->load->model('m_top_menu_has_user_group');
        $this->lang->load('menu/top_menu', $this->get_user_default_language());
    }

    public function index($active_menu_link = '')
    {
        $ugid = $this->session->userdata('user_group_id');
        $data['top_menu'] = $this->m_top_menu_has_user_group->get_active_menu($ugid);
        $data['active_menu_link'] = $active_menu_link;
        $this->load->vars($data);
        $this->load->view('top_menu');
    }

    public function top()
    {
        $ugid = $this->session->userdata('user_group_id');
        $data['top_menu'] = $this->m_top_menu_has_user_group->get_active_menu($ugid);
        $data['active_menu_link'] = $this->session->userdata('selected_menu');
        $this->load->view('top_menu', $data);
    }
}
<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * Copyright (C) 2016 CNL, Inje University - cnl.inje.ac.kr
 */
require APPPATH . "third_party/MX/Controller.php";

class MY_Controller extends MX_Controller
{
    /**
     * HMVC: Automatically load lib, helper,...
     */
    public $autoload = array(
        'libraries' => array('session', 'form_validation', 'database', 'Qch_Template', 'MY_Form'),
        'helper' => array('url', 'form', 'security', 'text', 'permission', 'language', 'general'),
        'model' => array('my_crud'),
        'config' => array('my_config')
    );

    public function __construct()
    {
        parent::__construct();
        // callback in form validation for HMVC
        $this->form_validation->CI =& $this;
        $this->form_validation->set_error_delimiters('<p class="field_error" align="center" style="color:red">', '</p>');
    }

    public function get_user_default_language()
    {
        $lang = $this->session->userdata('default_language');
        if (empty($lang)) {
            $lang = 'English';
        }
        return strtolower($lang);
    }

    /**
     * If GET parameter CONTINUE is set, redirect to CONTINUE parameter.
     * Else, redirect to $uri
     */
    public function redirect_if_no_continue($uri)
    {
        if ($this->input->get('CONTINUE') === null) {
            redirect($uri);
        } else {
            redirect($this->input->get('CONTINUE'));
        }
    }

    public function show_no_permission()
    {
        print_r('You do not have permission');
    }
}

class NoLoginCheckController extends MY_Controller
{

}


class LoginCheckController extends MY_Controller
{
    var $SELECTED_MENU = null;
    static $is_set_language = False;

    public function __construct()
    {
//        $this->autoload['helper'] = array('url');
        parent::__construct();
        $this->check_login();
        // set selected menu
        $this->set_top_selected_menu();
        $this->set_default_language();
    }

    public function get_session($session_name)
    {
        return $this->session->userdata($session_name);
    }

    public function check_login()
    {
        if ($this->get_session('uid') == 0) {
            $this->session->sess_destroy();
            $new_page = base_url() . "index.php/login?NEXT=" . uri_string();
            header("Status: 200");
            header("Location: " . $new_page);
            exit;
        }
    }

    /**
     * If active_menu == NULL, this method set the selected menu based on attribute $SELECTED_MENU
     * @param null $active_menu
     */
    public function set_top_selected_menu($active_menu = null)
    {
        if ($this->SELECTED_MENU != null) {
            $this->session->set_userdata('selected_menu', $this->SELECTED_MENU);
        }
        if ($active_menu != null) {
            $this->session->set_userdata('selected_menu', $active_menu);
        }
    }

    /** Render form */
    public function render($view_name, $data)
    {
        $this->qch_template->load_form_layout($view_name, $data);
    }

    public function set_default_language()
    {
        if (self::$is_set_language == true) {
            return;
        } else {
            $default_language = $this->get_user_default_language();
            $this->config->set_item('language', $default_language);
            self::$is_set_language = true;
        }
        $this->lang->load('common');
    }
}

class FormController extends LoginCheckController
{
    var $FORM_NAME = null;
    var $SEARCH_VIEW = null;
    var $DEPARTMENT = null;

    public function __construct()
    {
        parent::__construct();
        $this->FORM_NAME = 'form_' . strtolower(get_class($this));
        $this->SEARCH_VIEW = 'search';
        $this->DEPARTMENT = $this->get_session('department');

    }

    public function load_form($data)
    {
        $this->render($this->FORM_NAME, $data);
    }

    public function render_search($data)
    {
        $this->render($this->SEARCH_VIEW, $data);
    }

    public function index()
    {
        if (method_exists($this, 'search')) {
            $this->search();
        } else {
            print_r('The "search" method was not implement');
        }
    }

    public function load_form_language()
    {
        $this->lang->load('form/' . strtolower(get_class($this)), $this->get_user_default_language());
    }

    public function check_pass2($pass2)
    {
        require 'application/config/database.php';
        if ($pass2 != $db['default']['password_2'])
        {
            $this->form_validation->set_message('check_pass2', 'The password 2 you supplied does not match your existing password 2.');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }


//    public function create()
//    {
//        $this->init_data_form();
//        $this->add_validation();
//        $this->save_data();
//    }
//
//    public function init_data_form()
//    {
//    }
//
//    public function add_validation()
//    {
//    }
//
//    public function save_data()
//    {
//    }
}
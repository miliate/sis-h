<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends NoLoginCheckController
{
    private $user = '';
    private $user_group = '';
    private $department = '';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_department');
        $this->load->model('m_user_has_user_group');
    }

    public function index()
    {
        //default method for login module
        $data['title'] = $this->config->item('title');
        $this->show_form($data);
    }

    public function auth()
    {
        // check login by using callback form validation
        $this->form_validation->set_rules('username', 'Username', 'trim|xss_clean|required|callback_check_login|callback_check_group_department');
        $this->form_validation->set_rules('password', 'Password', 'xss_clean');
        $this->form_validation->set_rules('department', 'Department', 'trim|xss_clean|required');
        $data = array();
        if ($this->form_validation->run() == FALSE) {
            $this->show_form($data);
        } else {
            //when run to here: logging in is successfully, user_group is determined based on department id
            $this->save_session();
            if ($this->input->post('NEXT')) {
                $new_page = base_url() . "index.php/" . $this->input->post('NEXT');
            } else {
                $new_page = base_url() . "index.php/hhims";
            }
            var_dump($new_page);
            header("Status: 200");
            header("Location: " . $new_page);
            exit;
        }
    }

    public function show_form($data)
    {
        $this->load->vars($data);
        $this->load->view('login_qch');
    }

    function check_login($username)
    {
        $password = $this->input->post('password');
        $this->user = $this->m_user->get_by(array('username' => $username, 'password' => md5($password)));
        if (empty($this->user)) {
            $this->form_validation->set_message('check_login', 'Invalid username or password');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_group_department()
    {
        $raw_data = $this->m_user_has_user_group->with('user_group')->get_many_by(array('UID' => $this->user->UID));
        $department_id = $this->input->post('department');
        $this->department = $this->m_department->get($department_id);
        foreach ($raw_data as $row) {
            if (!empty($row->user_group)) {
                if ($department_id === $row->user_group->DEPID) {
                    $this->user_group = $row->user_group;
                    break;
                }
            }
        }
        if (empty($this->user_group)) {
            $this->form_validation->set_message('check_group_department', 'Invalid department');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function save_session()
    {
        $this->session->set_userdata('uid', $this->user->UID);
        $this->session->set_userdata('title', $this->user->Title);
        $this->session->set_userdata('name', $this->user->Name);
        $this->session->set_userdata('other_name', $this->user->OtherName);
        $this->session->set_userdata('username', $this->user->UserName);
        $this->session->set_userdata('user_group_id', $this->user_group->UGID);
        $this->session->set_userdata('user_group_name', $this->user_group->Name);
        $this->session->set_userdata('default_language', $this->user->DefaultLanguage);
        $this->session->set_userdata('department', $this->department->Name);
        $this->session->set_userdata('department_id', $this->department->DEPID);
    }

    function logout()
    {
        $this->session->sess_destroy();
        $new_page = base_url() . "index.php";
        header("Location:" . $new_page);
    }
}

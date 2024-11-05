<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 10-Oct-15
 * Time: 12:08 PM
 */
class User extends FormController
{
    var $FORM_NAME = 'form_user';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mpersistent');
        $this->load->model('m_user');
        $this->load->model('m_user_has_user_group');
        $this->load_form_language();
    }

    private function set_common_validation($is_edit)
    {
        if (!$is_edit) {
            $this->form_validation->set_rules('username', '', 'trim|required|callback_no_spaces');
        }
        $this->form_validation->set_rules('title', lang('Tilte'), 'trim|required');
        $this->form_validation->set_rules('name', lang('Name'), 'trim|required');
        $this->form_validation->set_rules('other_name', lang('Other Name'), 'trim');
        $this->form_validation->set_rules('date_of_birth', lang('Date Of Birth'), 'trim');
        $this->form_validation->set_rules('active', lang('Active'), 'trim');
        $this->form_validation->set_rules('gender', lang('Gender'), 'trim');
        $this->form_validation->set_rules("user_groups[]", lang("User Groups"), "required");
        $this->form_validation->set_rules("address", lang("Address"), "trim");
        $this->form_validation->set_rules('village', lang('Village'), 'trim');
    }

    public function create()
    {
        $data = array();
        $data['id'] = 0;
        $data['default_username'] = '';
        $data['default_title'] = '';
        $data['default_name'] = '';
        $data['default_other_name'] = '';
        $data['default_date_of_birth'] = '';
        $data['default_active'] = '';
        $data['default_gender'] = '';
        $data['default_address'] = '';
        $data['default_village'] = '';
        $data['default_language'] = '';
        $data['default_userGroups'] = array();

        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.UserName]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules("password_check", "Password confirmation", "required|matches[password]");
        $this->set_common_validation(false);

        if ($this->form_validation->run() == FALSE) {
            $this->show_form($data);
        } else {
            $this->insert();
        }
    }

    public function edit($uid)
    {

        $data = array();
        $data['id'] = $uid;
        $user = $this->m_user->get($uid);
        $data['default_userGroups'] = $this->m_user_has_user_group->getUserGroups($uid);
        $data['uid'] = $uid;
        $data['default_username'] = $user->UserName;
        $data['default_title'] = $user->Title;
        $data['default_name'] = $user->Name;
        $data['default_other_name'] = $user->OtherName;
        $data['default_date_of_birth'] = $user->DateOfBirth;
        $data['default_active'] = $user->Active;
        $data['default_gender'] = $user->Gender;
        $data['default_address'] = $user->Address_Street;
        $data['default_village'] = $user->Address_Village;
        $data['default_language'] = $user->DefaultLanguage;

        $this->set_common_validation(true);

        if ($this->form_validation->run() == FALSE) {
            $this->show_form($data);
        } else {
            $this->update($uid);
        }
    }

    public function no_spaces($str)
    {
        if (preg_match('/\s/', $str)) {
            $this->form_validation->set_message('no_spaces', lang('Username_space'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    //Starts Passwords Management
    public function change_password($uid)
    {
        if (isset($uid) && ($uid > 0)) {
            $data = array();
            $data['id'] = $uid;
            $user = $this->m_user->get($uid);
            $data['default_userGroups'] = $this->m_user_has_user_group->getUserGroups($uid);
            $data['uid'] = $uid;
            $data['default_username'] = $user->UserName;
            $data['default_new_password'] = $user->Password;

            $this->form_validation->set_rules("new_password", "New Password", "required");
            $this->form_validation->set_rules("password_check", "Password confirmation", "required|matches[new_password]");

            var_dump($_POST);

            if ($this->form_validation->run() == FALSE) {
                $this->show_passw_form($data);
            } else {
                $this->update_pwd($uid);
            }

        }//No id user submited
        else {

            echo "<h1>User not found</h1>";
        }

    }

    private function show_passw_form($data)
    {
        $sql = "SELECT UGID, user_group.Name AS UG_NAME, department.DEPID AS DEPID, department.Name AS DEP_NAME FROM user_group INNER JOIN department ON user_group.DEPID = department.DEPID";
        $user_groups = $this->mpersistent->table_select($sql);
        $departments = array();
        foreach ($user_groups as $row) {
            array_push($departments, $row ['DEP_NAME']);
        }
        $departments = array_unique($departments);

        $data['departments'] = $departments;
        $data['userGroups'] = $user_groups;
        $this->render('form_change_password', $data);
    }


    private function update_pwd($uid)
    {
        $new_passw = array(
            'Password' => md5($this->input->post('new_password')),
        );
        echo $uid;
        $this->m_user->update($uid, $new_passw);
        //  $this->m_user_has_user_group->updateUserGroups($uid, $this->get_selected_user_groups());
        //redirect
        $this->session->set_flashdata(
            'msg', 'REC: ' . ucfirst(strtolower($this->input->post("username"))) . ' updated'
        );
        header("Status: 200");
        header("Location: " . site_url('preference/load/user'));
    }

    private function update_pwdOrder($uid)
    {
        $new_passw = array(
            'OrderPassword' => md5($this->input->post('new_password')),
        );
        echo $uid;
        $this->m_user->update($uid, $new_passw);
        //  $this->m_user_has_user_group->updateUserGroups($uid, $this->get_selected_user_groups());
        //redirect
        $this->session->set_flashdata(
            'msg', 'REC: ' . ucfirst(strtolower($this->input->post("username"))) . ' updated'
        );
        header("Status: 200");
        header("Location: " . site_url('preference/load/user'));
    }


    //End Passwords Management

    private function show_form($data)
    {
        $sql = "SELECT UGID, user_group.Name AS UG_NAME, department.DEPID AS DEPID, department.Name AS DEP_NAME FROM user_group INNER JOIN department ON user_group.DEPID = department.DEPID";
        $user_groups = $this->mpersistent->table_select($sql);
        $departments = array();
        foreach ($user_groups as $row) {
            array_push($departments, $row ['DEP_NAME']);
        }
        $departments = array_unique($departments);

        $data['departments'] = $departments;
        $data['userGroups'] = $user_groups;
        $this->load_form($data);
    }

    private function update($uid)
    {
        $new_user = array(
            'Name' => $this->input->post('name'),
            'OtherName' => $this->input->post('other_name'),
            'DateOfBirth' => $this->input->post('date_of_birth'),
            'Active' => $this->input->post('active'),
            'Gender' => $this->input->post('gender'),
//            'Password' => md5($this->input->post('password')),
            'Address_Street' => $this->input->post('address'),
            'Address_Village' => $this->input->post('village'),
            'DefaultLanguage' => $this->input->post('language'),
            'Title' => $this->input->post('title')
        );
        $this->m_user->update($uid, $new_user);
        $this->m_user_has_user_group->updateUserGroups($uid, $this->get_selected_user_groups());
        //redirect
        $this->session->set_flashdata(
            'msg', 'REC: ' . ucfirst(strtolower($this->input->post("username"))) . ' updated'
        );
        header("Status: 200");
        header("Location: " . site_url('preference/load/user'));
    }

    private function insert()
    {
        $new_user = array(
            'UserName' => $this->input->post('username'),
            'Name' => $this->input->post('name'),
            'OtherName' => $this->input->post('other_name'),
            'DateOfBirth' => $this->input->post('date_of_birth'),
            'Active' => $this->input->post('active'),
            'Gender' => $this->input->post('gender'),
            'Password' => md5($this->input->post('password')),
            'Address_Street' => $this->input->post('address'),
            'Address_Village' => $this->input->post('village'),
            'DefaultLanguage' => $this->input->post('language'),
            'Title' => $this->input->post('title'),

            'OrderPassword' => md5('qch.2016') // Password level 2 for all user
        );
        $userId = $this->m_user->insert($new_user);
        $this->m_user_has_user_group->insert_($userId, $this->get_selected_user_groups());
        //redirect
        $this->session->set_flashdata(
            'msg', 'REC: ' . ucfirst(strtolower($this->input->post("username"))) . ' created'
        );
        $this->redirect_if_no_continue('preference/load/user');
    }

    private function get_selected_user_groups()
    {
        $selected_groups = array();
        foreach ($this->input->post('user_groups') as $key => $value) {
            array_push($selected_groups, $value);
        }
        return $selected_groups;
    }
}
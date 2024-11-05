<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 4/5/16
 * Time: 12:59 PM
 */
class User_Config extends FormController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->set_top_selected_menu('user_config');
        $this->load_form_language();
    }

    public function index()
    {
        $this->change_language();
    }

    public function change_language()
    {
        $id = $this->session->userdata('uid');
        $data['default_language'] = $this->session->userdata('default_language');

        $this->form_validation->set_rules('language', 'Language', 'trim|xss_clean|required');

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('change_language', $data);
        } else {
            $this->session->set_userdata('default_language', $this->input->post('language'));
            $update_data = array(
                'DefaultLanguage' => $this->input->post('language'),
            );
            $this->m_user->update($id, $update_data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/user_config/change_language/');
        }
    }

    public function change_password()
    {
        $uid = $this->get_session('uid');
        $this->form_validation->set_rules("password", lang("Old Password"), "required|callback_old_password_check");
        $this->form_validation->set_rules("password_check", lang("Repeat New Password"), "required|matches[new_password]");
        $this->form_validation->set_rules("new_password", lang('New Password'), "required");

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('change_password');
        } else {
            $data = array(
                'Password' => md5($this->input->post('new_password')),
        );
            $this->m_user->update($uid, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/user_config/change_password/');
        }
    }

    public function old_password_check($old_password)
    {
        $old_password_hash = md5($old_password);
        $uid = $this->get_session('uid');
        $res = $this->m_user->get_by(array('UID' => $uid, 'Password' => $old_password_hash));
        if ($res == null) {
            $this->form_validation->set_message('old_password_check', 'Old password not match');
            return FALSE;
        }
        return TRUE;
    }
}
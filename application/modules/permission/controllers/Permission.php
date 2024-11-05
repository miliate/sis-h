<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 11/19/15
 * Time: 10:59 AM
 */
class Permission extends LoginCheckController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user_group_have_permission');
    }

    public function edit($ugid)
    {

        if (!Modules::run('permission/check_permission', 'system', 'edit')) {
            die('You do not have permission!');
        }
        $this->load->model('m_permission');
        $this->load->model('m_user_group');
        $data['user_group'] = $this->m_user_group->get($ugid);
        if (empty($data['user_group']))
            die('User group not found');
        $data['ugid'] = $ugid;

        $data['all_permission'] = $this->m_permission->order_by('Name', 'ASC')->get_all();
        $data['all_user_group_have_permission'] = $this->m_user_group_have_permission->get_many_by(array('UGID' => $ugid));
        $this->form_validation->set_rules('permission[]', 'Permission', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('view_permission', $data);
        } else {
            $this->m_user_group_have_permission->delete_by(array('UGID' => $ugid));
            foreach ($this->input->post('permission') as $permission_id => $permission_group ) {
                foreach ($permission_group as $type => $value) {
                    switch ($type) {
                        case 1:
                            $type_name = 'view';
                            break;
                        case 2:
                            $type_name = 'create';
                            break;
                        case 3:
                            $type_name = 'edit';
                            break;
                        default:
                            $type_name = 'print';
                    };
                    $this->m_user_group_have_permission->insert(array(
                        'UGID' => $ugid,
                        'PERID' => $permission_id,
                        'Type' => $type_name,
                        'Active' => True
                    ));
                }
            }
            $this->redirect_if_no_continue('preference/load/permission');
        }

        

    }

    public function check_permission($name, $type) {
        $ugid = $this->session->userdata('user_group_id');
        if (empty($ugid))
            return false;
        $this->load->database();
        $sql = 'SELECT * FROM user_group_have_permission
                LEFT JOIN permission ON permission.PERID = user_group_have_permission.PERID
                WHERE permission.Name = "'.$name.'" AND UGID = '. $ugid. ' AND Type = "'.$type. '" AND Active = 1';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return true;
        }
        return false;
    }
}
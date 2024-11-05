<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 30-Oct-15
 * Time: 3:06 PM
 */
class User_favour_drug extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user_favour_drug');
        $this->load->model('m_who_drug');
//        $this->load_form_language();
    }

    public function get_all_drug_dropdown()
    {
        $res = array();
        foreach ($this->m_who_drug->order_by('name', 'asc')->get_all() as $drug) {
            $res[$drug->wd_id] = $drug->name;
        }
        return $res;
    }

    public function create()
    {
        $data = array();
        $data['id'] = 0;
        $data['default_active'] = '';
        $data['default_drug'] = '';
        $data['all_drug'] = $this->get_all_drug_dropdown();

        $this->form_validation->set_rules('drug', 'Medicamento', 'trim|required|callback_check_drug_duplicate[0]');

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'who_drug_id' => $this->input->post('drug'),
                'user_id' => $this->get_session('uid'),
                'Active' => $this->input->post('active'),
            );
            $id = $this->m_user_favour_drug->insert($data);
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('user_favour_drug');
        }
    }

    public function check_drug_duplicate($drug, $id) {
        $drug_id = $this->input->post('drug');
        $uid = $this->get_session('uid');
        $favour = $this->m_user_favour_drug->get_by(array(
            'user_id' => $uid,
            'who_drug_id' => $drug_id
        ));
        if (empty($favour)) {
            return true;
        }   else {
            if ($favour->user_favour_drug_id == $id) {
                return true;
            } else {
                $this->form_validation->set_message('check_drug_duplicate', 'Existia');
                return false;
            }
        }
    }

    public function edit($id)
    {
        $favour = $this->m_user_favour_drug->get($id);
        if (empty($favour))
            die('Id not exist');
        $data = array();
        $data['id'] = 0;
        $data['default_active'] = $favour->Active;
        $data['default_drug'] = $favour->who_drug_id;
        $data['all_drug'] = $this->get_all_drug_dropdown();
        $this->form_validation->set_rules('drug', 'Medicamento', 'trim|required|callback_check_drug_duplicate['. $id .']');

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'who_drug_id' => $this->input->post('drug'),
                'Active' => $this->input->post('active'),
            );
            $id = $this->m_user_favour_drug->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('user_favour_drug');
        }
    }

    public function search()
    {
        if (!has_permission('drug_management', 'view')) {
            $this->show_no_permission();
            return;
        }
        $uid = $this->get_session('uid');
        $qry = "SELECT
                user_favour_drug.user_favour_drug_id,
                user_favour_drug.CreateDate,
                who_drug.name
                FROM user_favour_drug
                LEFT JOIN who_drug ON who_drug.wd_id = user_favour_drug.who_drug_id
                WHERE (user_favour_drug.Active = 1) AND (user_favour_drug.user_id = ".$uid.")";
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('user_favour_drug_id');
        $page->setCaption("");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array('ID', lang('Time'), 'Medicamento'));
        $page->setColOption("user_favour_drug_id", array("hidden" => true));
        $page->setRowNum(25);
        $page->setOrientation_EL("L");
        $page->gridComplete_JS
            = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                    var rowId = $(this).attr('id');
                    window.location='" . site_url("/user_favour_drug/edit") . "/'+rowId+'';
            });
            }";
        $data['pager'] = $page->render(false);
        $this->render_search($data);
    }

    public function view_select_favour_drug($id = 'drug_select')
    {
        function cmp($a, $b)
        {
            return strcmp($a->drug->name, $b->drug->name);
        }

        $uid = $this->get_session('uid');
        $all_favour = $this->m_user_favour_drug->with('drug')->get_many_by(array(
            'user_id' => $uid,
            'Active' => 1
        ));
        usort($all_favour, "cmp");
        $drug_option[0] = '';
        foreach ($all_favour as $favour) {
            $drug_info = $favour->drug->name;
            $drug_option[$favour->drug->wd_id] = $drug_info;
        }
        $extra = 'class="form-control" id="'. $id .'" size="10"';
        echo form_dropdown('drug_select', $drug_option, array(), $extra);
    }



}
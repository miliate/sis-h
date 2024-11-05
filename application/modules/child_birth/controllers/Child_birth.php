<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Child_birth extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient');
        $this->load->model('m_child_birth');
        $this->load_form_language();
    }

    public function search_child($pid)
    {
        $patient = $this->m_patient->get($pid);
        print(json_encode($patient));
    }

    public function add($mother_id, $child_id)
    {
        $data = array();
        $data['mother_id'] = $mother_id;
        $data['child_id'] = $child_id;
        $data['id'] = '';
        $data['default_dob'] = date("Y-m-d");;
        $data['default_weight'] = '';
        $data['default_place_of_birth'] = '';
        $data['default_birth_type'] = '';
        $data['default_birth_type_cause'] = '';
        $data['default_pregnant_time'] = '';
        $data['default_apgar_index'] = '';
        $data['default_cranial_perimeter'] = '';
        $data['default_length'] = '';
        $data['default_cranial_perimeter'] = '';
        $data['default_complaint_preg_time'] = '';
        $data['default_complaint_birth_time'] = '';
        $data['default_complaint_neo_time'] = '';
        $data['default_history_checks'] = array();
        $data['default_history_other'] = '';
        $data['default_history_n_alive'] = '';
        $data['default_history_n_dead'] = '';
        $data['default_history_cause_dead'] = '';
//        $data['default_active'] = '';
        $data['default_remarks'] = '';

        $this->set_common_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            if ($this->input->post('history_checks') == FALSE) {
                $history_check = '';
            } else {
                $history_check = implode(',', $this->input->post('history_checks'));
            }
            $data = array(
                'MotherID' => $mother_id,
                'ChildID' => $child_id,
                'dob' => $this->input->post('dob'),
                'weight' => $this->input->post('weight'),
                'place_of_birth' => $this->input->post('place_of_birth'),
                'birth_type' => $this->input->post('birth_type'),
                'birth_type_cause' => $this->input->post('birth_type_cause'),
                'pregnant_time' => $this->input->post('pregnant_time'),
                'apgar_index' => $this->input->post('apgar_index'),
                'cranial_perimeter' => $this->input->post('cranial_perimeter'),
                'length' => $this->input->post('length'),
                'complaint_preg_time' => $this->input->post('complaint_preg_time'),
                'complaint_birth_time' => $this->input->post('complaint_birth_time'),
                'complaint_neo_time' => $this->input->post('complaint_neo_time'),
                'history_checks' => $history_check,
                'history_other' => $this->input->post('history_other'),
                'history_n_alive' => $this->input->post('history_n_alive'),
                'history_n_dead' => $this->input->post('history_n_dead'),
                'history_cause_dead' => $this->input->post('history_cause_dead'),
                'Remarks' => $this->input->post('remarks'),
            );
            $this->m_child_birth->insert($data);
            $this->session->set_flashdata(
                'msg', 'Created '
            );
            $this->redirect_if_no_continue('/patient/view/' . $mother_id);
        }
    }

    public function set_common_rules()
    {
        $this->form_validation->set_rules('dob', lang('Date of Birth'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('weight', lang('Weight'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('place_of_birth', lang('Place of Birth'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('birth_type', lang('Birth Type'), 'trim|xss_clean|required');
    }

    public function edit($birth_child_id)
    {
        $birth_child = $this->m_child_birth->get($birth_child_id);
        if (empty($birth_child)) {
            die('Id wrong');
        }
        $data = array();
        $data['mother_id'] = $birth_child->MotherID;
        $data['child_id'] = $birth_child->ChildID;
        $data['id'] = $birth_child_id;
        $data['default_dob'] = $birth_child->dob;
        $data['default_weight'] = $birth_child->weight;
        $data['default_place_of_birth'] = $birth_child->place_of_birth;
        $data['default_birth_type'] = $birth_child->birth_type;
        $data['default_birth_type_cause'] = $birth_child->birth_type_cause;
        $data['default_pregnant_time'] = $birth_child->pregnant_time;
        $data['default_apgar_index'] = $birth_child->apgar_index;
        $data['default_cranial_perimeter'] = $birth_child->cranial_perimeter;
        $data['default_length'] = $birth_child->length;
        $data['default_cranial_perimeter'] = $birth_child->cranial_perimeter;
        $data['default_complaint_preg_time'] = $birth_child->complaint_preg_time;
        $data['default_complaint_birth_time'] = $birth_child->complaint_birth_time;
        $data['default_complaint_neo_time'] = $birth_child->complaint_neo_time;
        $data['default_history_checks'] = explode(',', $birth_child->history_checks);
        $data['default_history_other'] = $birth_child->history_other;
        $data['default_history_n_alive'] = $birth_child->history_n_alive;
        $data['default_history_n_dead'] = $birth_child->history_n_dead;
        $data['default_history_cause_dead'] = $birth_child->history_cause_dead;
//        $data['default_active'] = '';
        $data['default_remarks'] = $birth_child->Remarks;

        $this->set_common_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            if ($this->input->post('history_checks') == FALSE) {
                $history_check = '';
            } else {
                $history_check = implode(',', $this->input->post('history_checks'));
            }
            $data = array(
                'dob' => $this->input->post('dob'),
                'weight' => $this->input->post('weight'),
                'place_of_birth' => $this->input->post('place_of_birth'),
                'birth_type' => $this->input->post('birth_type'),
                'birth_type_cause' => $this->input->post('birth_type_cause'),
                'pregnant_time' => $this->input->post('pregnant_time'),
                'apgar_index' => $this->input->post('apgar_index'),
                'cranial_perimeter' => $this->input->post('cranial_perimeter'),
                'length' => $this->input->post('length'),
                'complaint_preg_time' => $this->input->post('complaint_preg_time'),
                'complaint_birth_time' => $this->input->post('complaint_birth_time'),
                'complaint_neo_time' => $this->input->post('complaint_neo_time'),
                'history_checks' => $history_check,
                'history_other' => $this->input->post('history_other'),
                'history_n_alive' => $this->input->post('history_n_alive'),
                'history_n_dead' => $this->input->post('history_n_dead'),
                'history_cause_dead' => $this->input->post('history_cause_dead'),
                'Remarks' => $this->input->post('remarks'),
            );
            $this->m_child_birth->update($birth_child_id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/patient/view/' . $birth_child->MotherID);
        }
    }

    public function get_previous_allergy($pid, $continue, $mode = 'HTML')
    {
        $data = array();
        $data["patient_allergy_list"] = $this->m_patient_allergy->as_array()->order_by('CreateDate', 'DESC')->get_many_by(array('PID' => $pid, 'Active' => 1));
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_allergy');
        } else {
            return $data["patient_allergy_list"];
        }
    }
}
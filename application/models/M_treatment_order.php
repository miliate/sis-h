<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 28-Oct-15
 * Time: 11:25 AM
 */
class m_treatment_order extends MY_CRUD
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'treatment_order';
        $this->primary_key = 'OrderTreatmentID';
        $this->belongs_to = array('treatment' => array('model' => 'm_treatment', 'primary_key' => 'TreatmentID'));
    }

    function get_treatment_row($pid)
    {
        $this->db->where('OrderTreatmentID', $pid);
        $query = $this->db->get($this->_table);
        return $query->row();
    }

    public function get_treatments_by_pid($pid)
    {
        // Get the RefID for the given OrderTreatmentID (pid)
        $this->db->select('RefID');
        $this->db->where('OrderTreatmentID', $pid);
        $query = $this->db->get($this->_table);
        $result = $query->row();
        $refID = $result->RefID;

        // Now, get all treatments with the same RefID
        $this->db->select('treatment_order.OrderTreatmentID,treatment_order.TreatmentOrderItemID, treatment_order.Remarks, treatment.Treatment ,treatment_order.Remarks_Nurse,treatment_order.Status');
        $this->db->from($this->_table);
        $this->db->join('treatment', 'treatment.TREATMENTID = treatment_order.TreatmentID');
        $this->db->where('treatment_order.RefID', $refID);
        $this->db->where('treatment_order.Active', 1);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_treatments_by_item_id($treatment_order_item_id)
    {
        $this->db->select('treatment_order.CreateDate,treatment_order.OrderTreatmentID, treatment_order.TreatmentOrderItemID, treatment_order.Remarks, treatment.Treatment, treatment_order.Remarks_Nurse, treatment_order.Status, patient.PID');
        $this->db->from($this->_table);
        $this->db->join('treatment', 'treatment.TREATMENTID = treatment_order.TreatmentID');
        $this->db->join('patient', 'patient.PID = treatment_order.PID', 'left'); // Ensure this join is included
        $this->db->where('TreatmentOrderItemID', $treatment_order_item_id);
        $query = $this->db->get();
        return $query->result();
    }


    public function insert_batch($data)
    {
        return $this->db->insert_batch($this->_table, $data);
    }

    public function get_by_type_and_date($ref_id, $type, $date)
    {

        $this->db->select('treatment_order.*,treatment.Treatment, treatment.Type');
        $this->db->from($this->_table);
        $this->db->join('treatment', 'treatment.TREATMENTID = treatment_order.TreatmentID');
        $this->db->where('treatment.Type', $type);
        $this->db->where('DATE(treatment_order.CreateDate)', $date);
        $this->db->where('treatment_order.RefID', $ref_id);
        $this->db->where('treatment_order.Active', '1');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function search_treatment_orders($department)
    {
        $this->db->select("
            treatment_order.TreatmentOrderItemID,
            treatment.Treatment AS TreatmentName,
            treatment_order.CreateDate,
            treatment_order.RefType,
            patient.PID,
            CONCAT(patient.Firstname, ' ', patient.Name) AS Patient,
            treatment_order.Status
        ");
        $this->db->from('treatment_order');
        $this->db->join('treatment', 'treatment.TREATMENTID = treatment_order.TreatmentID', 'left');
        $this->db->join('patient', 'patient.PID = treatment_order.PID', 'left');
        $this->db->join('user', 'user.UID = treatment_order.OrderBy', 'left');
        $this->db->where('treatment_order.Active', 1);
        $this->db->where('treatment_order.RefType', $department);
        $this->db->group_by([
            'treatment_order.TreatmentOrderItemID',
            'TreatmentName',
            'treatment_order.CreateDate',
            'treatment_order.RefType',
            'patient.PID',
            'Patient',
            'treatment_order.Status'
        ]);

        return $this->db->get_compiled_select();
    }
}

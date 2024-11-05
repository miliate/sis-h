<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 03-Nov-15
 * Time: 11:21 AM
 */
class m_patient_prescription_have_drug extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'patient_prescription_have_drug';
        $this->primary_key = 'ID';
        $this->belongs_to = array(
            'drug' => array('model' => 'm_who_drug', 'primary_key' => 'DrugID'),
            'dose' => array('model' => 'm_drug_dosage', 'primary_key' => 'DoseID'),
            'frequency' => array('model' => 'm_drug_frequency', 'primary_key' => 'FrequencyID'),
        );
    }

    public function get_prescriptions_by_user($uid, $start_date, $end_date)
    {
        $this->db->select('*');
        $this->db->from($this->_table); // Assuming the table name is 'prescriptions'
        $this->db->where('LastUpDateUser', $uid);
        $this->db->where('CreateDate >=', $start_date);
        $this->db->where('CreateDate <=', $end_date);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_prescription_drugs_by($prescriptionID)
    {
        $this->db->select('patient_prescription_have_drug.*, who_drug.fnm, who_drug.name, who_drug.pharmaceutical_form, who_drug.dosage, user.Name as UserName, user.OtherName');
        $this->db->from($this->_table);
        $this->db->where('PrescriptionID', $prescriptionID);
        $this->db->where('patient_prescription_have_drug.Active', 1);
        $this->db->join('who_drug', 'who_drug.wd_id = patient_prescription_have_drug.DrugID');
        $this->db->join('user', 'user.uid = patient_prescription_have_drug.CreateUser', 'left');

        return $this->db->get()->result();
    }

    public function get_total_cost_by_user($CreateUser, $startDate, $endDate)
    {
        $sql = "
            SELECT SUM(amount) AS total_amount FROM (
                SELECT pep.Cost AS amount 
                FROM patient_external_prescription pep
                WHERE pep.CreateUser = ?
                AND pep.CreateDate BETWEEN ? AND ?
                
                UNION ALL
                
                SELECT pp.Cost AS amount 
                FROM patient_prescription pp
                WHERE pp.LastUpDateUser = ?
                AND pp.CreateDate BETWEEN ? AND ?
            ) payments
        ";

        // Executando a consulta SQL com os parâmetros fornecidos
        $query = $this->db->query($sql, array($CreateUser, $startDate, $endDate, $CreateUser, $startDate, $endDate));
        // Obtendo o resultado da consulta
        $result = $query->row();
        // Retornando o valor total calculado
        return $result->total_amount;
    }

    public function voidPrescriptionDrug($prescriptionID)
    {

        if (!is_numeric($prescriptionID)) {
           
            return false;
        } 
        $this->db->where('ID', $prescriptionID);
        $update = $this->db->update('patient_prescription_have_drug', array('Active' => 0));

        return $update ? true : false;
    }

    public function get_raw_prescribed_drugs_in_rooms($roomID = null, $reportDate = null)
    {
        $this->db->select('
            wd.name AS DrugName, 
            wd.wd_id as DrugId,
            wd.fnm AS PharmaceuticalForm, 
            wd.dosage AS DrugDosage, 
            pphd.Dose, 
            pphd.Period
        ');
        $this->db->from('patient_prescription pp');
        $this->db->join('patient_prescription_have_drug pphd', 'pp.PrescriptionID = pphd.PrescriptionID');
        $this->db->join('admission a', 'pp.PID = a.PID');
        $this->db->join('ward_rooms wr', 'a.RoomNo = wr.RID');
        $this->db->join('who_drug wd', 'pphd.DrugID = wd.wd_id');

        $this->db->where('pp.RefType', 'ADM');
        $this->db->where('a.Active', 1);

        if (!empty($roomID)) {
            $this->db->where('wr.RID', $roomID);
        }

        if (!empty($reportDate)) {
            $this->db->where('DATE(pp.CreateDate)', $reportDate);
        } else {
            $this->db->where('DATE(pp.CreateDate)', date('Y-m-d'));
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_dispensed_quantities($roomID, $reportDate)
    {
        $this->db->select('DrugId, DispendQuantity');
        $this->db->from('cardex_dispensed');
        $this->db->where('WardRoom', $roomID);
        $this->db->where('Date', $reportDate);
        $this->db->where('Active', '1');

        $query = $this->db->get();
        $result = $query->result_array();

        $dispensed_quantities = [];
        foreach ($result as $row) {
            $dispensed_quantities[$row['DrugId']] = $row;
        }

        return $dispensed_quantities;
    }


    public function check_existing_dispensed($drugId, $roomID, $reportDate)
    {
        $this->db->select('Id');
        $this->db->from('cardex_dispensed');
        $this->db->where('DrugId', $drugId);
        $this->db->where('WardRoom', $roomID);
        $this->db->where('Date', $reportDate);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function update_existing_dispensed_cardex($drugId, $roomID, $reportDate)
    {
        $updateData = array(
            'Active' => '0',
        );
        $this->db->where('DrugId', $drugId);
        $this->db->where('WardRoom', $roomID);
        $this->db->where('Date', $reportDate);
        $this->db->update('cardex_dispensed', $updateData);
    }

    public function insert_dispensed_cardex($data)
    {
        $this->db->insert('cardex_dispensed', $data);
    }
}

<?php

/**
 * This model works with information in table "patient_prescription" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_patient_prescription extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'patient_prescription';
        $this->primary_key = 'PrescriptionID';
        $this->belongs_to = array(
            'order_by' => array('model' => 'm_user', 'primary_key' => 'CreateUser'),
        );
    }

    public function get_by_prescription_id($prescription_id)
    {
        $this->db->where('PrescriptionID', $prescription_id);
        $query = $this->db->get($this->_table); // Replace 'your_table_name' with the actual table name
        return $query->result();
    }

    public function get_prescriptions_by($pid = null, $prescriptionId  = null)
    {

        $this->db->select('patient_prescription.*, user.Name, user.OtherName');
        $this->db->join('user', 'user.UID = patient_prescription.CreateUser', 'left');

        if (!is_null($pid)) {
            $this->db->where('PID', $pid);
        }
        if (!is_null($prescriptionId)) {
            $this->db->where('PrescriptionID', $prescriptionId);
        }

        $this->db->order_by('CreateDate', 'desc');
        $query = $this->db->get($this->_table);
        return $query->result();
    }

    public function get_prescriptions_by_refid($pid = null, $ref_id  = null)
    {

        $this->db->select('patient_prescription.*, user.Name, user.OtherName');
        $this->db->join('user', 'user.UID = patient_prescription.CreateUser', 'left');

       
            $this->db->where('PID', $pid);
       
      
            $this->db->where('refId', $ref_id );
      

        $this->db->order_by('CreateDate', 'desc');
        $query = $this->db->get($this->_table);
        return $query->result();
    }


    public function get_patient_data_by_pid($pid)
    {
        $this->db->select('patient.Firstname, patient.Name, patient.OtherName, patient.PID, patient.Address_Street, patient.Gender, patient.DateOfBirth, patient_exam.Weight');
        $this->db->where('patient.PID', $pid);
        $this->db->join('patient_exam', 'patient_exam.PID = patient.PID', 'left');
        $query = $this->db->get('patient');
        return $query->row();
    }

    public function get_doctor_name_by_prescription_id($prescription_id)
    {
        // Select the required columns from the Prescription and user tables
        $this->db->select('user.Name, user.Othername');
        $this->db->from($this->_table);
        $this->db->join('user', 'user.UID = patient_prescription.CreateUser'); // Assuming Prescription table has a DoctorID field that references user table
        $this->db->where('patient_prescription.PrescriptionID', $prescription_id);
        $query = $this->db->get();

        // Return the doctor's name and othername as a concatenated string
        $result = $query->row();
        return $result ? $result->Name . ' ' . $result->Othername : null;
    }

    public function get_prescription_description($prescription_id){
        $sql = '
    SELECT 
        pphd.Quantity,
        pphd.CreateDate,
        wd.fnm,
        wd.name,
        wd.dosage,
        wd.pharmaceutical_form,
        u1.Name AS CreateUserName,
        u2.Name AS LastUpdateUserName,
        pphd.LastUpDate,
        p.PID AS PatientID,
        CONCAT(p.Firstname, " ", p.Name) AS PatientFullName,
        df.Frequency
    FROM 
        patient_prescription_have_drug pphd
    JOIN 
        who_drug wd ON pphd.DrugID = wd.wd_id
    JOIN 
        user u1 ON pphd.CreateUser = u1.UID
    Left JOIN 
        user u2 ON pphd.LastUpdateUser = u2.UID
    JOIN 
        patient p ON pphd.PID = p.PID
    JOIN 
        drugs_frequency df ON pphd.FrequencyID = df.DFQYID
    WHERE 
        pphd.PrescriptionID = ?';
        $query = $this->db->query($sql, array($prescription_id));
        return $query->result_array();
    }

    public function get_cardex_table_info($pid){
        $query = "SELECT
        PrescriptionID,
        patient_prescription.CreateDate,
        RefType,
        CONCAT(user.Name,' ',user.OtherName) AS Created_By
        FROM patient_prescription
        LEFT JOIN user ON patient_prescription.CreateUser = user.UID
        WHERE patient_prescription.Active = 1 AND patient_prescription.PID = '" . $pid . "'";

        return $query;
    } 
}


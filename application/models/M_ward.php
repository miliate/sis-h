<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 11-Nov-15
 * Time: 12:26 PM
 */
class m_ward extends MY_CRUD
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'ward';
        $this->primary_key = 'WID';
    }

    public function get_name_by_wid($WID)
    {
        $this->db->select('WID, Name');
        $this->db->from('ward'); // Assuming the table name is refer_to_adm
        $this->db->where('WID', $WID);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array(); // Returning the row as an associative array
        } else {
            return null; // Return null if no matching record is found
        }
    }

    public function get_ward_by_wid($WID)
    {
        $this->db->select('*');
        $this->db->from('ward');
        $this->db->where('WID', $WID);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_all_wards()
    {
        $this->db->select('*');
        $this->db->from('ward');
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_patient_list($wid)
    {
        $wid = stripslashes($wid);
        $wid = mysql_real_escape_string($wid);
        $dataset = array();
        $sql = " SELECT
	  admission.ADMID,
	  admission.BHT,
	  patient.HIN as HIN,
	  patient.Gender ,
	  patient.DateOfBirth ,
	  CONCAT(patient.Personal_Title, ' ' ,patient.Full_Name_Registered,' ', patient.Personal_Used_Name) as patient_name ,
	  admission.AdmissionDate,
	  admission.Complaint,
	  admission_prescription.admission_prescription_id
	  from admission
	  LEFT JOIN `patient` ON patient.PID = admission.PID
	  LEFT JOIN `admission_prescription` ON admission_prescription.ADMID = admission.ADMID
	  where (admission.Active =1) and (admission.Ward= '$wid')
	   and (admission.OutCome is null)
			";
        $Q = $this->db->query($sql);
        foreach ($Q->result_array() as $row) {
            $dataset[] = $row;
        }

        $Q->free_result();
        return $dataset;
    }

    function get_dispense_info($pitem_id, $dte)
    {
        $pitem_id = stripslashes($pitem_id);
        $pitem_id = mysql_real_escape_string($pitem_id);
        $dataset = array();
        $sql = " select admission_prescribe_items_dispense.* ";
        $sql .= " FROM  admission_prescribe_items_dispense ";
        $sql .= " WHERE admission_prescribe_items_dispense.prescribe_items_id = '$pitem_id'
			and admission_prescribe_items_dispense.given_date_time like '$dte%'
		";
        //
        $Q = $this->db->query($sql);
        if ($Q->num_rows() > 0) {
            foreach ($Q->result_array() as $row) {
                $dataset[] = $row;
            }
        }
        $Q->free_result();
        return $dataset;
    }

    function get_prescribe_items($prsid, $typ = null)
    {
        $prsid = stripslashes($prsid);
        $prsid = mysql_real_escape_string($prsid);
        $dataset = array();
        $sql = " select admission_prescribe_items.*, ";
        $sql .= "  who_drug.name, who_drug.formulation, who_drug.dose ";
        $sql .= " FROM  admission_prescribe_items ";
        $sql .= " LEFT JOIN `who_drug` ON who_drug.wd_id = admission_prescribe_items.DRGID  ";

        $sql .= " WHERE admission_prescribe_items.admission_prescription_id = '$prsid' ";
        if ($typ) {
            $sql .= " and admission_prescribe_items.type = '$typ' order by `type` desc";
        }

        //
        $Q = $this->db->query($sql);
        if ($Q->num_rows() > 0) {
            foreach ($Q->result_array() as $row) {
                $dataset[] = $row;
            }
        }
        $Q->free_result();
        return $dataset;
    }

    function get_total_active_record()
    {
        $data = array();
        $sql = " select count(WID) as total ";
        $sql .= " FROM ward ";
        //$sql .= " WHERE Active = 1 " ;

        $Q = $this->db->query($sql);
        if ($Q->num_rows() > 0) {
            $data = $Q->row_array();
        }
        $Q->free_result();

        return $data["total"];
    }

    function getAdmissionsByDate($date)
    {
        if ($this->WID) {
            $where = array('Ward' => $this->WID, 'AdmissionDate like' => $date . '%');
            return $this->db->get_where('admission', $where);
        } else {
            null;
        }
    }

    function getPreviousMidnightBalance($date)
    {
        if ($this->WID) {
            $date = date('Y-m-d', strtotime('-1 day', strtotime($date)));
            $where = array('Ward' => $this->WID, 'AdmissionDate like' => $date . '%');
            return $this->db->get_where('admission', $where);
        } else {
            null;
        }
    }

    function getTransfersIn($date)
    {
        if ($this->WID) {
            $where = array('TransferTo' => $this->WID, 'TransferDate like' => $date . '%');
            return $this->db->get_where('admission_transfer', $where);
        } else {
            null;
        }
    }

    function getTransfersOut($date)
    {
        if ($this->WID) {
            $where = array('TransferFrom' => $this->WID, 'TransferDate like' => $date . '%');
            return $this->db->get_where('admission_transfer', $where);
        } else {
            null;
        }
    }

    function getDischarges($date)
    {
        if ($this->WID) {
            $where = array('Ward' => $this->WID, 'AdmissionDate like' => $date . '%', 'OutCome !=' => '');
            return $this->db->get_where('admission', $where);
        } else {
            null;
        }
    }

    function getDeathsGt($date)
    {
        if ($this->WID) {
            $date = date('Y-m-d', strtotime('+2 day', strtotime($date)));
            $where = array('Ward' => $this->WID, 'AdmissionDate like' => $date . '%', 'OutCome =' => 'Died', 'DischargeDate <' => $date);
            return $this->db->get_where('admission', $where);
        } else {
            null;
        }
    }

    function getDeathsLt($date)
    {
        if ($this->WID) {
            $date = date('Y-m-d', strtotime('+2 day', strtotime($date)));
            $where = array('Ward' => $this->WID, 'AdmissionDate like' => $date . '%', 'OutCome =' => 'Died', 'DischargeDate >' => $date);
            return $this->db->get_where('admission', $where);
        } else {
            null;
        }
    }

    function getActiveWard()
    {
        if ($this->WID) {
            $where = array('Active' => 1);
            return $this->db->get_where('admission', $where);
        } else {
            null;
        }
    }


    public function get_active_wards()
    {
        $this->db->select('WID, Name, Telephone, Remarks');
        $this->db->from('ward');
        $this->db->where('Active', 1);
        $this->db->order_by('Name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_bed_info_by_ward($war)
    {
        // Counting the number of beds in the ward_beds table
        $this->db->select('COUNT(BID) AS BedCount');
        $this->db->from('ward_beds B');
        $this->db->join('ward_rooms R', 'B.RID = R.RID');
        $this->db->where('R.WID', $war);
        $this->db->where('B.Active', 1);
        $bed_count_query = $this->db->get();
        $bed_count = $bed_count_query->row()->BedCount;

        // Counting the number of occupied beds
        $this->db->select('COUNT(*) AS OccupiedCount');
        $this->db->from('ward_beds');
        $this->db->where('status', 'Unavailable');
        $occupied_count_query = $this->db->get();
        $occupied_count = $occupied_count_query->row()->OccupiedCount;

        // Calculate free beds
        $free_beds = $bed_count - $occupied_count;

        return [
            'bed_count' => $bed_count,
            'occupied_count' => $occupied_count,
            'free_beds' => $free_beds
        ];
    }

    public function get_bed_statistics_in_ward($ward_id) 
    {

        $this->db->select('COUNT(*) as total_beds');
        $this->db->from('ward_beds');
        $this->db->join('ward_rooms', 'ward_rooms.RID = ward_beds.RID');
        $this->db->join('ward', 'ward.WID = ward_rooms.WID');
        $this->db->where('ward_beds.Active', 1);
        $this->db->where('ward.WID', $ward_id);
        $total_beds_query = $this->db->get();
        $total_beds = $total_beds_query->row()->total_beds;

        $this->db->select('COUNT(*) as beds_un');
        $this->db->from('ward_beds');
        $this->db->join('ward_rooms', 'ward_rooms.RID = ward_beds.RID');
        $this->db->join('ward', 'ward.WID = ward_rooms.WID');
        $this->db->where('ward_beds.Active', 1);
        $this->db->where('ward.WID', $ward_id);
        $this->db->where('ward_beds.status', 'Unavailable');
        $unavailable_beds_query = $this->db->get();
        $beds_un = $unavailable_beds_query->row()->beds_un;

        $this->db->select('COUNT(*) as beds_av');
        $this->db->from('ward_beds');
        $this->db->join('ward_rooms', 'ward_rooms.RID = ward_beds.RID');
        $this->db->join('ward', 'ward.WID = ward_rooms.WID');
        $this->db->where('ward_beds.Active', 1);
        $this->db->where('ward.WID', $ward_id);
        $this->db->where('ward_beds.status', 'Available');
        $available_beds_query = $this->db->get();
        $beds_av = $available_beds_query->row()->beds_av;

        return array(
            'total_beds' => $total_beds,
            'beds_un' => $beds_un,
            'beds_av' => $beds_av
        );
    }

}

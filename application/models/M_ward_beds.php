<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 20-July-15
 * Time: 06:20 PM
 */
class m_ward_beds extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'ward_beds';
        $this->primary_key = 'BID';
    }

    public function get_all_beds_by_rid($rid) {
        $this->db->select('*');
        $this->db->from('ward_beds');
        $this->db->where('RID', $rid);
        $this->db->where('status', 'Available');
        $this->db->where('Active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function bed_exists($room_id, $bed_number)
    {
        $this->db->where('RID', $room_id);
        $this->db->where('BedNo', $bed_number);
        $query = $this->db->get('ward_beds');

        return $query->num_rows() > 0;
    }

    public function bed_number($room_id, $bed_number)
    {
        
        $this->db->where('RID', $room_id);
        $this->db->where('BID', $bed_number);
        $query = $this->db->get('ward_beds');

        return $query->row();
    }

    public function bed_number_by($bid)
    {
        $this->db->select('BID, BedNo');
        $this->db->where('BID', $bid);
        $query = $this->db->get('ward_beds');

        return $query->row();
    }


}
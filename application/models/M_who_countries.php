<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 1/11/16
 * Time: 1:18 PM
 */
class m_who_countries extends MY_CRUD {
    function __construct()
    {
        parent::__construct();
        $this->_table = 'who_countries';
        $this->primary_key = 'CID';
    }

    public function get_name_by_cid($cid) {
        $this->db->select('*');
        $this->db->from('who_countries');
        $this->db->where('CID', $cid);
        $query = $this->db->get();
        return $query->row();
    }

}

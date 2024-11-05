<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 10-Oct-15
 * Time: 5:36 PM
 */
class m_user_has_user_group extends My_Model
{
    function __construct()
    {
        parent::__construct();
        $this->_table = 'user_has_user_group';
        $this->belongs_to = array('user_group' => array('model' => 'm_user_group', 'primary_key' => 'UGID'));
    }

    function insert_($uid, $ugids)
    {
        $data = array();
        foreach ($ugids as $ugid) {
            array_push($data, array(
                'uid' => $uid,
                'ugid' => $ugid
            ));
        }
        $this->db->insert_batch($this->_table, $data);
    }

    function getUserGroups($uid)
    {
        $query = $this->db->get_where($this->_table, array('uid' => $uid));
        $result = array();
        foreach ($query->result() as $row) {
            array_push($result, $row->UGID);
        }
        return $result;
    }

    function updateUserGroups($uid, $groupIds) {
        $this->delete_by(array('UID' => $uid));
        $this->insert_($uid, $groupIds);
    }
}
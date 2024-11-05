<?php
/**
 * This model works with information in table "top_menu_has_user_group" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_top_menu_has_user_group extends MY_CRUD {
    function __construct() {
        parent::__construct ();
        $this->_table = 'top_menu_has_user_group';
        $this->belongs_to = array('top_menu' => array('model' => 'm_top_menu', 'primary_key' => 'MID'));
    }

    function insert_($uid, $ugids)
    {
        $data = array();
        foreach ($ugids as $ugid) {
            array_push($data, array(
                'MID' => $uid,
                'UGID' => $ugid
            ));
        }
        $this->db->insert_batch($this->_table, $data);
    }

    function update_user_groups($mid, $group_ids) {
        $this->delete_by('MID', $mid);
        if (!empty($group_ids))
            $this->insert_($mid, $group_ids);
    }

    function get_active_menu($ugid) {
        $sql = 'SELECT * FROM `top_menu_has_user_group` INNER JOIN `top_menu` ON top_menu_has_user_group.MID = top_menu.MID WHERE top_menu_has_user_group.UGID = '.$ugid.' AND Active = true ORDER BY MenuOrder';
        return $this->table_select($sql);
    }
}

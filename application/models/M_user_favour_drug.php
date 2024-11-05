<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 29-Oct-15
 * Time: 4:13 PM
 */
class m_user_favour_drug extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'user_favour_drug';
        $this->primary_key = 'user_favour_drug_id';
        $this->belongs_to = array(
            'drug' => array('model' => 'm_who_drug', 'primary_key' => 'who_drug_id'),
        );
    }
}
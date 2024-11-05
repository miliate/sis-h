<?php

class m_pharmaceutical_form extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'pharmaceutical_form';
        $this->primary_key = 'PFID';
    }
}

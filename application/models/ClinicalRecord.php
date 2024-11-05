<?php

class ClinicalRecord extends MY_CRUD
{
    private $table;
    private $createdBy;


    public function __construct()
    {
        parent::__construct();
        $this->load->model('Observation');

        $this->table = "clinical_records";
    }


    private $observations = [];

    public function createClinicaRecord($patientId, $careEventTypeId, $observations)
    {
        $createdBy = $this->session->userdata('uid');
        $createDatetime = date('Y-m-d H:i:s');


        $data = [
            'patient_id' => $patientId,
            'type_id' => $careEventTypeId,
            'created_by' => $createdBy,
            'create_datetime' => $createDatetime

        ];

        $this->db->insert("clinical_records", $data);
        $recordId = $this->db->insert_id();

        $this->addObservations($patientId, $recordId, $observations);
    }

    public function addObservations($patientId, $recordId, array $observations)
    {
        $obsData = [];

        foreach ($observations as $obs) {
            if ($obs instanceof Observation) {
                $obs->setPatientId($patientId);
                $obs->setCreatedBy($this->createdBy);
                $obs->setClinicalRecordId($recordId);


                $obsData[] = $obs->toArray();
            }
        }
        return $this->db->insert_batch('observations', $obsData);
    }



    public function getEncounterTypes()
    {
        $query = $this->db->get('encounter_types');
        return $query->result_array();
    }
}

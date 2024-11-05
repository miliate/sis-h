<?php


class Observation extends MY_CRUD
{

    private $clinicalRecordId;
    private $createdBy;
    private $patientId;
    private $refId;
    private $name;
    private $intValue;
    private $floatValue;
    private $stringValue;
    private $dateValue;
    private $datetimeValue;
    private $boolValue;
    private $observationTypeId;
    private $unit;
    private $groupId;
    private $createDatetime;
    private $table;
    private $typeName;

    public function __construct($type_name = null)
    {
        parent::__construct();
        $this->createDatetime = date('Y-m-d H:i:s');
        $this->table = "observations";

        $this->observationTypeId = $this->get_type_id_by_input_name($type_name);
    }



    private function get_type_id_by_input_name($input_name)
    {
        $query = $this->db->select('id')
            ->from('observations_type')
            ->where('name', $input_name)
            ->get();
        $result = $query->row();
        return $result ? $result->id : null;
    }


    public function toArray()
    {
        $data = [
            'clinical_record_id' => $this->clinicalRecordId,
            'created_by' => $this->createdBy,
            'patient_id' => $this->patientId,
            'ref_id' => $this->refId,
            'name' => $this->name,
            'int_value' => $this->intValue,
            'float_value' => $this->floatValue,
            'string_value' => $this->stringValue,
            'bool_value' => $this->boolValue,
            'datetime_value' => $this->datetimeValue,
            'group_id' => $this->groupId,
            'unit' => $this->unit

        ];

        return $data;
    }


    public function createObs($encounterId, $patientId, $obsData)
    {
        foreach ($obsData as &$obs) {
            $obs['encounter_id'] = $encounterId; // Adiciona o ID do encontro
            $obs['patient_id'] = $patientId; // Adiciona o ID do paciente
            $obs['create_datetime'] = date('Y-m-d H:i:s');
        }

        return $this->db->insert_batch('obs', $obsData);
    }

    // Método para recuperar observações por paciente
    public function getObservationsByPatient($patientId)
    {
        $this->db->where('patient_id', $patientId);
        $query = $this->db->get('obs');
        return $query->result_array();
    }


    public function setValue($value)
    {

        if (is_numeric($value)) {

            if (strpos($value, '.') !== false) {
                $this->floatValue = (float)$value;
            } else {
                $this->intValue = (int)$value;
            }
        } elseif ($value === 'true' || $value === 'false') {
            $this->boolValue = ($value === 'true');
        } elseif (is_string($value)) {

            $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $value);
            if ($dateTime !== false) {
                $this->datetimeValue = $dateTime;
            } else {
                $this->stringValue = $value;
            }
        } else {
            throw new InvalidArgumentException("Tipo de dado não suportado.");
        }
    }

    /**
     * Set the value of patientId
     *
     * @return  self
     */
    public function setPatientId($patientId)
    {
        $this->patientId = $patientId;

        return $this;
    }

    /**
     * Set the value of createdBy
     *
     * @return  self
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Set the value of clinicalRecordId
     *
     * @return  self
     */
    public function setClinicalRecordId($clinicalRecordId)
    {
        $this->clinicalRecordId = $clinicalRecordId;

        return $this;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the value of intValue
     *
     * @return  self
     */
    public function setIntValue($intValue)
    {
        $this->intValue = $intValue;

        return $this;
    }

    /**
     * Set the value of floatValue
     *
     * @return  self
     */
    public function setFloatValue($floatValue)
    {
        $this->floatValue = $floatValue;

        return $this;
    }

    /**
     * Set the value of stringValue
     *
     * @return  self
     */
    public function setStringValue($stringValue)
    {
        $this->stringValue = $stringValue;

        return $this;
    }


    /**
     * Set the value of dateValue
     *
     * @return  self
     */
    public function setDateValue($dateValue)
    {
        $this->dateValue = $dateValue;

        return $this;
    }

    /**
     * Set the value of datetimeValue
     *
     * @return  self
     */
    public function setDatetimeValue($datetimeValue)
    {
        $this->datetimeValue = $datetimeValue;

        return $this;
    }

    /**
     * Set the value of boolValue
     *
     * @return  self
     */
    public function setBoolValue($boolValue)
    {
        $this->boolValue = $boolValue;

        return $this;
    }

    /**
     * Set the value of unit
     *
     * @return  self
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Set the value of observationTypeId
     *
     * @return  self
     */
    public function setObservationTypeId($observationTypeId)
    {
        $this->observationTypeId = $observationTypeId;

        return $this;
    }


    /**
     * Set the value of groupId
     *
     * @return  self
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;

        return $this;
    }



    /**
     * Set the value of unit
     *
     * @return  self
     */
}

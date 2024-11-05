<?php

class Mpagercolumn extends CI_Model
{

    private $name = 'c1';
    private $index = 'i1';
    private $width = 40;
    private $align = 'left';
    private $sortable = true;
    private $ddtype_EL = 'DDTYPE';
    private $id_EL;
    private $table_EL = '';
    private $name_EL = '';
    private $stype = '';


    public function __toString()
    {

        return $this->getColumnModel();
    }

    public function getColumnModel($array = null)
    {

        if (!$array) {
            $options = $this;
        } else {
            $options = $array;
        }
        $model = '';
        $model .= '{';
        foreach ($options as $property => $value) {
        	
            $ar = explode("_", $property);
            if (count($ar) == 2) {
                $property = $ar[0];
                $sf       = $ar[1];

                switch ($sf) {
                    case 'JS':
                        $model .= "$property:";
                        $model .= str_replace('REFID', $this->id_EL, $value);
                        $model .= ',';
//                        echo "$property : $sf<br>";
                        continue 2;
                        break;
                    case 'JSAR':
                        $model .= "$property:";
                        $model .= $this->getArrayModel($value);
                        $model .= ',';
//                        echo "$property : $sf<br>";
                        continue 2;
                        break;
                    case 'EL':
//                        echo "$property : $sf<br>";
                        continue 2;
                        break;
                    default:
                        break;
                }
            }

            if (!empty($value)||$value===0) {
                $model .= "$property:";
                $model .= $this->getType($value);
            }

        }

        $model = substr($model, 0, -1);
        $model .= "}";
	        
        
        return $model;
    }

    private function getArrayModel($array)
    {

        $model = '[';
        foreach ($array as $value) {
            $model .= $this->getType($value);
        }
        $model = substr($model, 0, -1);
        $model .= ']';

        return $model;
    }

    private function getType($value)
    {

        $model = '';
        switch (gettype($value)) {
            case 'string':
                $model .= '"' . $value . '",';
                break;
            case 'integer':
                $model .= $value;
                $model .= ',';
                break;
            case 'boolean':
                $model .= $value ? 'true' : 'false';
                $model .= ',';
                break;
            case 'array':
//                    echo $property.'<br>';
                $model .= $this->getColumnModel($value);
                $model .= ',';
                break;
            case 'object':
                $model .= "$value,";
                break;
            default:
                $model .= "'D$value',";
                break;
        }

//        $model=substr($model, 0,-1);
        return $model;
    }

    public function getDataModel()
    {
    	
        $ddobj       = new stdClass();
        $ddobj->name = $this->name;
//        $ddobj->name = $this->encrypt($this->name);
        if ($this->stype == 'select') {
            $this->ddtype_EL = "DSTYPE";
        }
        $ddobj->value  = $this->ddtype_EL;
        $ddobj->table  = $this->table_EL;
        $ddobj->column = $this->name_EL;

        
        return json_encode($ddobj, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    public function __set($name, $value)
    {

        $this->$name = $value;
    }

    public function getName()
    {

        return $this->name;
    }

    public function setName($name)
    {

        $this->name = $name;
    }

    public function getIndex()
    {

        return $this->index;
    }

    public function setIndex($index)
    {

        $this->index = $index;
    }

    public function getWidth()
    {

        return $this->width;
    }

    public function setWidth($width)
    {

        $this->width = $width;
    }

    public function getAlign()
    {

        return $this->align;
    }

    public function setAlign($align)
    {

        $this->align = $align;
    }

    public function getSortable()
    {

        return $this->sortable;
    }

    public function setSortable($sortable)
    {

//        $this->sortable = $sortable ? 'true' : 'false';
        $this->sortable = $sortable;
    }

    public function setTable($table)
    {

        $this->table_EL = $table;
    }

    public function setName_EL($name_EL)
    {

        $this->name_EL = $name_EL;
    }

    public function getName_EL()
    {

        return $this->name_EL;
    }

    /**
     * @param mixed $id_EL
     */
    public function setIdEL($id_EL)
    {

        $this->id_EL = $id_EL;
    }
}

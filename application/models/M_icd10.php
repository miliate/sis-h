<?php

/**
 * Created by @jordao.cololo.
 * User: kivegun
 * Date: 11/7/16
 * Time: 8:31 PM
 */
class m_icd10 extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'icd10';
        $this->primary_key = 'ICDID';
    }

    public function get_names_by_prefix($prefix)
    {
        $searchParam = '%' . $prefix . '%';
        $sql = "SELECT ICDID, Code, Name FROM icd10 WHERE Name LIKE ? OR Code LIKE ? ORDER BY Code Asc LIMIT 300";
        $query = $this->db->query($sql, array($searchParam, $searchParam));
        return $query->result_array();
    }

    public function get_id_by_name($name)
    {
        $this->db->select('ICDID');
        $this->db->where('Name', $name);
        $query = $this->db->get('icd10');

        if ($query->num_rows() > 0) {
            return $query->row()->ICDID;
        } else {
            return 0; // Retorna null se não encontrar nenhum registro
        }
    }

    public function get_name_by_id($id) {
        $this->db->select('*');
        $this->db->where('ICDID', $id);
        $query = $this->db->get($this->_table);
        $result = $query->row();
        return $result;
    }

    public function get_name_by_code($code)
    {
        // Verificar se o $code é uma string com formato de array JSON
        if (is_string($code) && $this->is_json($code)) {
            // Decodificar a string JSON para um array
            $codes_array = json_decode($code, true);
    
            // Verificar se a decodificação foi bem-sucedida e se é realmente um array
            if (is_array($codes_array) && !empty($codes_array)) {
                // Fazer a consulta para todos os códigos no array
                $this->db->select('ICDID, Name');
                $this->db->where_in('ICDID', $codes_array);
                $query = $this->db->get($this->_table);
                $results = $query->result();
    
                // Mapear os resultados em um array de nomes, indexado pelo ICDID
                $names = [];
                foreach ($results as $row) {
                    $names[$row->ICDID] = $row->Name;
                }
    
                // Retornar o array de nomes, ou null se nenhum nome for encontrado
                return !empty($names) ? $names : null;
            }
        }
    
        // Caso seja um inteiro ou não seja um array JSON válido, manter o comportamento original
        if (is_numeric($code)) {
            $this->db->select('Name');
            $this->db->where('ICDID', $code);
            $query = $this->db->get($this->_table);
            $result = $query->row();
    
            return isset($result->Name) && !empty($result->Name) ? $result->Name : null;
        }
    
        // Se não for um código válido, retornar null
        return null;
    }

    private function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    
}

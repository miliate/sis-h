<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 23-Oct-15
 * Time: 2:41 PM
 */
class m_patient_examination extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'patient_exam';
        $this->primary_key = 'PATEXAMID';
    }

    public function get_patient_exam_info_by_emrid($emrid)
    {
        $sql = "SELECT pe.*, mh.Complaint , mh.CreateDate mhCreateDate, ea.CreateDate eaCreateDate
            FROM patient_exam pe
                INNER JOIN emergency_admission ea
                        ON ea.pid = pe.pid
                INNER JOIN medical_history mh
                        ON mh.pid = pe.pid
            WHERE ea.EMRID = ?
            ORDER BY pe.CreateDate DESC, mhCreateDate DESC ,eaCreateDate DESC
            LIMIT 1";

        $query = $this->db->query($sql, array($emrid));
        $result = $query->result_array();

        return $result ? $result[0] : null;
    }

    public function get_created_by($exam_id)
    {
        $this->db->select('CreateUser');
        $this->db->from('patient_exam');
        $this->db->where('PATEXAMID', $exam_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->CreateUser;
        }
        return false;
    }

    // Novo método de verificação
    //realiza a verificação do usuário que criou o registro.
    public function update_patient_exam($exam_id, $data)
    {
        // Obter o ID do usuário atual (você pode precisar ajustar isso conforme sua implementação)
        $current_user = $this->session->userdata('user_id'); // Exemplo de recuperação do ID da sessão

        // Obter o usuário que criou o registro
        $created_by = $this->get_created_by($exam_id);

        // Verificar se o usuário atual é o mesmo que criou o registro
        if ($created_by && $created_by == $current_user) {
            // Permitir atualização
            $this->db->where('PATEXAMID', $exam_id);
            return $this->db->update('patient_exam', $data);
        } else {
            // Bloquear atualização
            return false; // ou lançar um erro conforme sua necessidade
        }
    }

    public function get_patient_exam_by_pid($pid, $ref_type)
    {
        return "
            SELECT PATEXAMID, 
                   SUBSTRING(ExamDate, 1, 10) as dte, 
                   CONCAT(sys_BP, ' / ', diast_BP) as bp, 
                   CONCAT(Weight, 'Kg.') as weight, 
                   CONCAT(Height, 'm') as height, 
                   CONCAT(Temperature, '°C') as temperature,
                   user_role
            FROM patient_exam
            WHERE PID = '" . $pid . "' 
              AND Active = 1 
        ";
    }

    public function get_by_ref_id($pid, $ref_id)
    {
        $this->db->where('Ref_id', $ref_id);
        $this->db->where('PID', $pid);
        $query = $this->db->get('patient_exam');
        return $query->result_array();
    }
}

<?php
class Forms_Model extends CI_model
{

        public function insert_form($data)
        {
            $this->db->insert('form', $data);
        }

        public function fetch_form()
        {
            $this->db->select('form.*, modules.module_name');
            $this->db->from('form');
            $this->db->join('modules', 'form.module_id = modules.id', 'left');
            $query = $this->db->get();
            return $query->result_array();
        }

        public function delete_form($id)
        {
            $this->db->where('id', $id);
            $this->db->delete('form');
        }

        public function fetch_single_form($id)
        {
            $this->db->where('id', $id);
            $query = $this->db->get('form');
            return $query->row_array();
        }

        public function update_form($id, $data)
        {
            $this->db->where('id', $id);
            $this->db->update('form', $data);
        }

        public function fetch_modules()
    {
        $this->db->select('id, module_name');
        $query = $this->db->get('modules');
        return $query->result_array();
    }



}

?>
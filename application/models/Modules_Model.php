<?php
class Modules_Model extends CI_model
{

    public function insert_module($data)
    {
        $this->db->insert('modules', $data);
    }

    public function fetch_module()
    {
        $query = $this->db->get('modules');
        return $query->result_array();
    }

    public function delete_module($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('modules');
    }

    public function fetch_single_module($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('modules');
        return $query->row_array();
    }

    public function update_module($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('modules', $data);
    }

    public function getAllModules() {
        // Fetch all modules from the database
        $query = $this->db->get('modules');
        return $query->result_array();
    }

}

?>
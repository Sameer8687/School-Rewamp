<?php
class Template_model extends CI_Model
{

    function checkIfExist($temp_id)
    {

        $data = $this->db->query("select * from stvmsgt where stvmsgt_id =" . $temp_id . "")->row_array();

        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }

    function add_city($data)
    {
        $status = $this->db->insert('stvmsgt', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function update_data($data, $temp_id)
    {
        $this->db->where('stvmsgt_id', $temp_id);
        $status = $this->db->update('stvmsgt', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    function getAllRecords(){
        return $this->db->query("select * from stvmsgt")->result_array();
    }

    function getRecordsByID($id){
        return $this->db->query("select * from stvmsgt where stvmsgt_id = ".$id."")->row_array();
    }
}

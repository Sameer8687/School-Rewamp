<?php
class Event_model extends CI_model
{
    //subject master
    function add_event($data)
    {
        $status = $this->db->insert('stvevnt', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

   //get event data by id
    function getEventByID()
    {
        $this->db->where('stvevnt_id', $this->input->post('id'));
        return $this->db->get('stvevnt')->row_array();
    }

    //check availability
    function checkIfExistEvent($id)
    {
        $this->db->where('stvevnt_id', $id);
        $data = $this->db->get('stvevnt')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }

    function update_event($data, $id)
   {
        $this->db->where('stvevnt_id', $id);
        $status = $this->db->update('stvevnt', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
}
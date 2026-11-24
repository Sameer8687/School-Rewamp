<?php

class User_Model extends CI_Model
{

    // Fetch spruser_id and spruser_name for the dropdown
    public function fetch_users()
    {
        $this->db->select('spruser_id, spruser_name, spruser_username');
        $query = $this->db->get('spruser');
        return $query->result_array();
    }

    public function getUsersWithAccess()
    {
        // Fetch spruser_username from spruser table based on user_id in user_access
        $this->db->select('spruser.spruser_username, spruser.spruser_id');
        $this->db->from('user_access');
        $this->db->join('spruser', 'user_access.user_id = spruser.spruser_id');
        $this->db->group_by('user_access.user_id');
        $query = $this->db->get();

        return $query->result();
    }


    // Fetch forms based on selected module ID
    public function getFormsByModuleId($moduleId)
    {
        $this->db->select('id, form_name');
        $this->db->from('form');  // Assuming 'form' is the table where your form data is stored
        $this->db->where('module_id', $moduleId);

        $query = $this->db->get();
        return $query->result_array(); // Return the result as an array
    }


    // Fetch user information based on user ID
    public function fetch_user_info($userId)
    {
        $this->db->select('spruser_name, spruser_mobile, spruser_email, spruser_usertype');
        $this->db->where('spruser_id', $userId);
        return $this->db->get('spruser')->row_array();
    }


    // Fetch all modules for the module dropdown
    public function fetch_modules_name()
    {
        $this->db->select('id, module_name');
        return $this->db->get('modules')->result_array();
    }



    // Fetch user access data based on user ID
    public function fetch_access($userId)
    {
        $this->db->select('ua.*, m.module_name, f.form_name');
        $this->db->from('user_access ua');
        $this->db->join('modules m', 'm.id = ua.module_id', 'left');
        $this->db->join('form f', 'f.id = ua.form_id', 'left');
        $this->db->where('ua.user_id', $userId);
        return $this->db->get()->result_array();
    }

    // Delete a specific row from user_access based on user_id, module_id, and form_id
    public function delete_user_access_rows($deletedRows)
    {
        foreach ($deletedRows as $row) {
            // Ensure all required parameters are provided
            if (!empty($row['user_id']) && !empty($row['module_id']) && !empty($row['form_id'])) {
                // Prepare the delete query
                $this->db->where('user_id', $row['user_id']);
                $this->db->where('module_id', $row['module_id']);
                $this->db->where('form_id', $row['form_id']);
                $this->db->delete('user_access');
            }
        }
        return true; // Return true if the loop completes without errors
    }


    // Update user access data based on access ID
    public function update_data($accessId, $data)
    {
        $this->db->where('id', $accessId); // access ID refers to the row to be updated
        $this->db->update('user_access', $data);
    }

    // Insert user access data
    public function insert_data($data)
    {
        $this->db->insert('user_access', $data);
    }



    public function delete_access($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user_access');
    }



    // ========== user reprot ===========

    public function getUserReports()
    {
        $this->db->select('u.spruser_id, u.spruser_name, COUNT(ua.form_id) as form_count');
        $this->db->from('spruser u');
        $this->db->join('user_access ua', 'u.spruser_id = ua.user_id', 'inner');
        $this->db->group_by('u.spruser_id');
        $query = $this->db->get();
        return $query->result();
    }



    public function deleteUserAccess($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->delete('user_access');
    }


    // ================ access list =====================

    public function fetchAccessList($user_id)
    {
        // Query to join the tables based on user_id, module_id, and form_id
        $this->db->select('modules.module_name, form.form_name, user_access.access, user_access.from_date, user_access.to_Date, user_access.hold');
        $this->db->from('user_access');
        $this->db->join('modules', 'modules.id = user_access.module_id');
        $this->db->join('form', 'form.id = user_access.form_id');
        $this->db->where('user_access.user_id', $user_id);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function fetchSingleUserName($spruser_id)
    {
        // Query the spruser table to get spruser_name for the given spruser_id
        $this->db->select('spruser_name');
        $this->db->from('spruser');
        $this->db->where('spruser_id', $spruser_id);
        $query = $this->db->get();

        // Return the user's name
        if ($query->num_rows() > 0) {
            return $query->row()->spruser_name;
        } else {
            return 'Unknown User'; // Default if no user found
        }
    }
}

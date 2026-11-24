<?php
class Task_model extends CI_Model
{

    protected $taskDB;

    public function __construct()
    {


        parent::__construct();
        $this->taskDB = $this->load->database('taskDB', TRUE);
    }

    // Insert a new task into the database
    public function insert_task($data)
    {
        $this->taskDB->insert('tasks', $data); // Assuming 'tasks' is your table name
        return $this->taskDB->insert_id(); // Return the inserted ID
    }


    public function update_task($id, $data)
    {
        $this->taskDB->where('id', $id);
        return $this->taskDB->update('tasks', $data);
    }


    public function get_all_tasks($status = null, $title = null)
    {
        // Filter by status
        if ($status && $status !== 'all') {
            $this->taskDB->where_in('status', (array)$status);
        } elseif (!$status) {
            $this->taskDB->where_not_in('status', ['Complete', 'Cancelled', 'Close']);
        }

        if (!empty($title)) {
            $this->taskDB->like('title', $title); // <-- this enables alphabet-based filtering
        }

        return $this->taskDB->get('tasks')->result_array();
    }





    public function delete_task($task_id)
    {
        // Perform the delete operation
        $this->taskDB->where('id', $task_id);
        return $this->taskDB->delete('tasks');  // Assuming 'tasks' is your table name
    }

    public function get_task_by_id($id)
    {
        $query = $this->taskDB->get_where('tasks', ['id' => $id]); // Fetch task with specific ID
        return $query->row_array(); // Return single task
    }
}

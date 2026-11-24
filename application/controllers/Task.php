<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task extends CI_Controller
{
    protected $taskDB;

    public function __construct()

    {
        parent::__construct();
        $this->taskDB = $this->load->database('taskDB', TRUE);

        $this->load->model('Task_model');
    }

    public function index()
    {
        // $data['view_path'] = 'dashboard'; 
        $data['scripts'] = ['assets/js/task/list.js'];
        $this->load->view('dashboard', $data);
    }


    public function get_task_details($id)
    {
        $task = $this->Task_model->get_task_by_id($id);

        if ($task) {
            echo json_encode(['status' => 'success', 'task' => $task]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Task not found']);
        }
    }


    // POST: Add Task
    public function add_task()
    {
        // Load form validation library
        $this->load->library('form_validation');

        // Set validation rules
        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');

        // Initialize response array
        $response = [];

        if ($this->form_validation->run() == FALSE) {
            // Validation failed
            $response = [
                'status' => 'error',
                'validation_errors' => $this->form_validation->error_array()
            ];
        } else {
            $data = [
                'title'         => $this->input->post('title', TRUE),
                'start_date'    => $this->input->post('start_date', TRUE),
                'reminder_date' => $this->input->post('reminder_date', TRUE),
                'status'        => $this->input->post('status', TRUE) ?: 'pending',
            ];


            $insert_id = $this->Task_model->insert_task($data);


            if ($insert_id) {
                $response = [
                    'status' => 'success',
                    'message' => 'Task added successfully.',
                    'task_id' => $insert_id
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to add task.'
                ];
            }
        }

        // Always return JSON response
        echo json_encode($response);
    }


    public function update_task($id)
    {
        // Get the task data from POST
        $data = array(
            'title'        => $this->input->post('title'),
            'start_date'   => $this->input->post('start_date'),
            'reminder_date'  => $this->input->post('reminder_date'),
        );

        // Call model to update task
        $updated = $this->Task_model->update_task($id, $data);

        if ($updated) {
            $response = array('status' => 'success', 'message' => 'Task updated successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to update task or no changes made.');
        }

        echo json_encode($response);
    }



    public function get_tasks()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $status = isset($input['status']) ? $input['status'] : null;
        $title = isset($input['title']) ? $input['title'] : null;

        $tasks = $this->Task_model->get_all_tasks($status, $title);


        // $tasks = $this->Task_model->get_all_tasks($status);

        if (!empty($tasks)) {
            $response = ['status' => 'success', 'tasks' => $tasks];
        } else {
            $response = ['status' => 'error', 'message' => 'No tasks found'];
        }

        echo json_encode($response);
    }


    public function delete_task($task_id)
    {
        // Delete the task from the database
        $result = $this->Task_model->delete_task($task_id);

        if ($result) {
            $response = array('status' => 'success', 'message' => 'Task deleted successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete task.');
        }

        echo json_encode($response);
    }

    public function get_task_by_id($id)
    {
        $task = $this->Task_model->get_task_by_id($id); // Call model method to get a specific task
        if ($task) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'task' => $task
                ]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Task not found'
                ]));
        }
    }






    public function update_status()
    {
        $task_id = $this->input->post('id');
        $status = $this->input->post('status', TRUE);

        if ($task_id && $status) {
            // If status is 'restart', increment the restart_count
            if ($status === 'restart') {
                $this->taskDB->set('restart_count', 'restart_count + 1', FALSE);
            }

            // // If status is 'complete', delete the task
            // if ($status === 'complete') {
            //     $this->taskDB->where('id', $task_id);
            //     $this->taskDB->delete('tasks');
            //     echo json_encode(['success' => true, 'message' => 'Task completed and removed']);
            //     return;
            // }

            // Always update the status
            $this->taskDB->set('status', $status);
            $this->taskDB->where('id', $task_id);
            $updated = $this->taskDB->update('tasks');

            if ($updated) {
                echo json_encode(['success' => true, 'message' => 'Status updated']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Update failed']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
        }
    }


    public function update_reminder()
    {
        $task_id = $this->input->post('id');
        $reminder_date = $this->input->post('reminder_date', TRUE);

        if ($task_id && $reminder_date) {
            $this->taskDB->where('id', $task_id);
            $this->taskDB->update('tasks', ['reminder_date' => $reminder_date]);

            echo json_encode(['success' => true, 'message' => 'Reminder date updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
        }
    }
}

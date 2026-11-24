<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('setup/home');
    }
    public function web_app_visibility()
    {
        $data['visibility'] = $this->db->get('web_app_visibility')->row_array();
        $this->load->view('setup/web_app_visibility', $data);
    }

    // Add Details
    function add_web_app_visibility()
    {
        $exist = $this->db->get('web_app_visibility')->row_array();
        $postData = $this->input->post();
        $postData['last_action'] = $this->session->userdata('oliveSchoolDashboardUser');
        $postData['last_action_on'] = date('Y-m-d');
        if (empty($exist)) {
            $this->db->insert('web_app_visibility', $postData);
        } else {
            $this->db->update('web_app_visibility', $postData);
        }
        $this->session->set_flashdata('message', ['status' => 'success', 'message' => 'Settings Saved Successfully.']);
        redirect(base_url() . "setup/web_app_visibility");
    }
}

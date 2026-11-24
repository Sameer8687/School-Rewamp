<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public $allocatedForms;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Access_model');
        $this->fetchNav();
        // Make $forms available in all views
        $this->load->vars('allocatedForms', $this->allocatedForms);
    }

    function fetchNav()
    {
        $userid = $this->session->userdata('oliveSchoolDashboardUser');
        // die($userid);
        $this->allocatedForms = $this->Access_model->fetchForms($userid);
    }

    function getForms()
    {
        return $this->allocatedForms;
    }
}

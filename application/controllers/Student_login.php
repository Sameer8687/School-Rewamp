<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Student_login extends CI_Controller
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

		$this->load->model('Login_model');

		date_default_timezone_set("Asia/Kolkata");
	}



	public function index()
	{

		if ($this->input->get('auth') == "true") {

			$id = $this->input->get('student-id');

			$token = $this->input->get('token');

			$user = $this->Login_model->validateStudent($id);

			if ($user == null) {

				$data['message'] = "Invalid Student ID <span class='font-weight-bold'>$id</span>";

				$this->load->view('alert-login', $data);
			} else {

				// Student Exist

				if ($token == $user['spriden_auth_token']) {

					// Grant Login

					$userID = $user['id'];

					$this->session->set_userdata('StudentProfileSession', $userID);

					/*
							   Developer: Kamran Ansari
							   Date: 01-Sep-2023
							   ------------------ start --------------------------------
						   */
					// insert login details
					$this->Login_model->studentLoginHistory($userID);
					// ------------------ end --------------------------------

					redirect(base_url() . 'profile');
				} else {

					// Token not matched

					$data['message'] = "Authentication Token For Student ID <span class='font-weight-bold'>$id</span> not matched";

					$this->load->view('alert-login', $data);
				}
			}
		} else {

			// load login from inner
			$data['academic_years'] = $this->db->query("SELECT stvacdm_id, stvacdm_year FROM stvacdm")->result_array();
			$data['selected_acdm'] = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
			$this->load->view('student_login', $data);
		}
	}

	function validate()
	{

		$user = $this->Login_model->validateStudent($this->input->post('username'));

		if ($user == null) {

			$this->session->set_flashdata('Message', "Enter Valid Student ID");

			redirect(base_url() . 'Student_login');
		} else {

			$userID = $user['id'];

			$this->session->set_userdata('StudentProfileSession', $userID);
			$this->session->set_userdata('application_acdm', $this->input->post('acdm_year'));

			redirect(base_url() . 'profile');
		}
	}
}

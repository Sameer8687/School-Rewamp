<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Login extends CI_Controller
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

		if ($this->session->userdata('oliveSchoolDashboardUser'))

			redirect(base_url());
	}



	public function index()

	{

		$this->load->view('login');
	}

	function validate()
	{

		$user = $this->Login_model->validateUser();



		if ($user != null) {
			$data = array(

				'stvlgin_date' => date('Y-m-d'),

				'stvlgin_time' => date('H:i:s'),

				'stvlgin_userid' => $user['spruser_id'],

				'stvlgin_status' => "Logged In"

			);
			$this->Login_model->addLoginHistory($data);

			$this->session->set_userdata('oliveSchoolDashboardUser', $user['spruser_id']);
			// Set cookie value and it it in the database
			// Set the cookie for oliveschool.org.in and its subdomains
			$token = hash("sha256", $user['spruser_id']);
			setcookie("hrms_auth_token", $token, time() + (3 * 60 * 60), "/", ".oliveschool.org.in");
			$updateUser['spruser_auth_token'] = $token;
			$this->db->where('spruser_id', $user['spruser_id'])->update('spruser', $updateUser);
			redirect(base_url());
		} else {

			$this->session->set_flashdata('Message', "Invalid Username or Password");

			redirect(base_url() . 'login');
		}
	}
}

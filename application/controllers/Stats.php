<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stats extends CI_Controller
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
		$this->load->model('Stats_model');
		if (!$this->session->userdata('oliveSchoolDashboardUser'))
			redirect(base_url() . 'login');
	}
	function getAllFormsNames()
	{
		$data = $this->Stats_model->getAllFormsNames();
		echo json_encode($data);
	}
	function openForm()
	{
		$formName = $this->input->post('form_id');
		$userId = $this->session->userdata('oliveSchoolDashboardUser');

		//Get User Details
		$getUserData = $this->Stats_model->getUserDetails($userId);
		$userType = $getUserData['spruser_usertype'];

		//Get Form Name
		$getFormName = $this->Stats_model->getValidFormName($formName);

		if ($getFormName != "invalid") {
			if ($userType == "Admin") {
				echo $getFormName;
			} else {
				$userAccess = $this->Stats_model->getUserAccessDetails($userId, $getFormName);
				if ($userAccess != "noaccess") {
					echo $userAccess['stvuacs_formurl'];
				} else {
					echo "noaccess";
				}
			}
		} else {
			echo "fail";
		}
	}
	//function to update academic year

	function update_acdm_year()
	{
		$status = $this->Stats_model->update_acdm_year();
		if ($status) {
			$this->session->set_flashdata('msg', "Academic Year Updated Successfully.");
			$this->session->set_flashdata('class', "alert-success");
			redirect(base_url());
		}
	}

	//change password


	function change_password()
	{

		$current = $this->input->post('current');
		$new = $this->input->post('new');
		$confirm = $this->input->post('confirm');

		if ($new == $confirm) {
			$password = $this->Stats_model->getPassword($this->session->userdata('oliveSchoolDashboardUser'));
			if ($password != null) {
				if ($password['spruser_password'] == $current) {
					// change password here
					$data = array(
						'spruser_password' => $new
					);
					$status = $this->Stats_model->change_password($data, $this->session->userdata('oliveSchoolDashboardUser'));
					if ($status) {
						$this->session->set_flashdata('ps_msg', "Password has been changed successfully !");
						$this->session->set_flashdata('ps_cls', "alert-success");
						redirect(base_url('welcome/change_password'));
					} else {
						$this->session->set_flashdata('ps_msg', "Failed to change password");
						$this->session->set_flashdata('ps_cls', "alert-danger");
						redirect(base_url('welcome/change_password'));
					}
				} else {
					$this->session->set_flashdata('ps_msg', "Current password not matched");
					$this->session->set_flashdata('ps_cls', "alert-danger");
					redirect(base_url('welcome/change_password'));
				}
			}
		} else {
			$this->session->set_flashdata('ps_msg', "New password & Confirm password not matched");
			$this->session->set_flashdata('ps_cls', "alert-danger");
			redirect(base_url('welcome/change_password'));
		}
	}

	// function activity_report()
	// {
	// 	// $data['academic_year'] = $this->Stats_model->get_all_academic_years();
	// 	$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
	// 	//Get All Classes from DB
	// 	$lastLoginData = $this->Stats_model->last_login();
	// 	$data['lastLoginData'] = $lastLoginData;
	// 	$data['activities'] = $this->Stats_model->fetchActivities($this->input->get('from'),$this->input->get('from'));
	// 	$data['from_date'] = $this->input->get('from') != '' ? $this->input->get('from') : date('Y-m-d');
	// 	$data['to_date'] = $this->input->get('to') != '' ? $this->input->get('to') : date('Y-m-d');

	// 	$this->load->view('stats/tracking-stats', $data);
	// }

	public function activity_report()
{
    $data['academic_year'] = $this->Stats_model->get_all_academic_years();
    $userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
    $lastLoginData = $this->Stats_model->last_login();
    $data['lastLoginData'] = $lastLoginData;

    // Get filters
    $year_id = $this->input->get('year') ? $this->input->get('year') : '';
    $from_date = $this->input->get('from');
    $to_date = $this->input->get('to');

    // Default year if not selected
    if (empty($year_id) && !empty($data['academic_year'])) {
        $year_id = $data['academic_year'][0]['stvacdm_id'];
    }

    // Get academic year info
    $year_info = $this->Stats_model->get_academic_year_by_id($year_id);

    if (!empty($year_info)) {
        $year_start = $year_info['start_date'];
        $year_end = $year_info['end_date'];
    } else {
        $year_start = date('Y-06-01');
        $year_end = date('Y-04-30');
    }

    // Restrict range
    if (empty($from_date) || $from_date < $year_start) {
        $from_date = $year_start;
    }
    if (empty($to_date) || $to_date > $year_end) {
        $to_date = $year_end;
    }

    // Fetch optimized data
    $data['activities'] = $this->Stats_model->fetchActivities($from_date, $to_date, $year_id);

    $data['year_dates_json'] = $this->prepare_year_dates_json($data['academic_year']);
    $data['from_date'] = $from_date;
    $data['to_date'] = $to_date;
    $data['year'] = $year_id;

    $this->load->view('stats/tracking-stats', $data);
}

private function prepare_year_dates_json($academic_years)
{
    $year_dates = [];
    foreach ($academic_years as $acdm) {
        $year_info = $this->Stats_model->get_academic_year_by_id($acdm['stvacdm_id']);
        if ($year_info) {
            $year_dates[$acdm['stvacdm_id']] = [
                'start' => $year_info['start_date'],
                'end' => $year_info['end_date']
            ];
        }
    }
    return json_encode($year_dates);
}

}

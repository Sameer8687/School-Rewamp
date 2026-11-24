<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Timetable extends CI_Controller
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
		$this->load->model('Timetable_model');
		date_default_timezone_set('Asia/Kolkata');
	}

	//add Time
	function create_time()
	{
		//Check avaialable
		$time_id = $this->input->post('time_id');
		$if_exist = $this->Timetable_model->checkIfExistTime($time_id);
		if ($if_exist > 0) {
			$data = array(
				'stvtbtm_start_time' => $this->input->post('start_time'),
				'stvtbtm_end_time' => $this->input->post('end_time')
			);
			//add teacher assigned data into database
			$status = $this->Timetable_model->update_time_data($data, $time_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'create-time');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'create-time');
			}
		} else {
			$data = array(
				'stvtbtm_start_time' => $this->input->post('start_time'),
				'stvtbtm_end_time' =>$this->input->post('end_time'),
				'stvtbtm_status' => $this->input->post('status_time'),
				'stvtbtm_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
			);
			//add teacher assigned data into database
			$status = $this->Timetable_model->create_time($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'create-time');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'create-time');
			}
		}
	}
	//get schoold data by id
	function getTimeDataByID()
	{
		$data = $this->Timetable_model->getTimeDataByID();
		echo json_encode($data);
	}



	//Add Timetable into db
	function create_timetable(){
		$data = $this->input->post('timetable');
		$status = $this->Timetable_model->create_timetable($data, $this->input->post('std'), $this->input->post('div'));
		sleep(1);
		if ($status){
			echo "success";
		}else {
			echo "fail";
		}
		// for ($i = 0; $i < count($data); $i++) {
		// 	$new_data = array(
		// 		'stvttbl_time' => $data[$i][0],
		// 		'stvttbl_monday' => $data[$i][1],
		// 		'stvttbl_tuesday' => $data[$i][2],
		// 		'stvttbl_wednesday' => $data[$i][3],
		// 		'stvttbl_thurday' => $data[$i][4],
		// 		'stvttbl_friday' => $data[$i][5],
		// 		'stvttbl_saturday' => $data[$i][6]
		// 	);
		// }
	}

	function getTimetableByStd(){
		echo json_encode($this->Timetable_model->getTimetableByStd());
	}

	function updt_timetable_cell()
	{
		$id = $this->input->post('id');
		$data = array(
			$this->input->post('table_column')  => $this->input->post('value')
		);
		$status = $this->Timetable_model->updt_timetable_cell($data, $id);
		if ($status){
			echo "success";
		}else {
			echo "fail";
		}
	}
}

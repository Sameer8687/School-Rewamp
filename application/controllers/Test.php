<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Test extends CI_Controller

{
	public function __construct()
	{
		parent::__construct();

		@ini_set('max_execution_time', 0);
		@set_time_limit(0);
	}
	public function import_attendance()
	{
		
		$this->load->model('Process_model');

		$result = $this->Process_model->move_attendance_from_temp(500);

		echo "<pre>";
		print_r($result);
		echo "</pre>";
	}
}

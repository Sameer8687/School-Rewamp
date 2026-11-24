<?php

defined('BASEPATH') or exit('No direct script access allowed');



class MsgTemplate extends CI_Controller

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

        $this->load->model('Template_model');

        $this->load->model('Stats_model');

        date_default_timezone_set('Asia/Kolkata');

    }



    function index()

    {

        // $form_access_data = array (

        // 	'stvfrmh_userid' => $this->session->userdata('oliveSchoolDashboardUser'),

        // 	'stvfrmh_date' => date('Y-m-d'),

        // 	'stvfrmh_time' => date('h:i:s'),

        // 	'stvfrmh_form_code' => "admission-form"

        // );

        // $this->Stats_model->add_form_data($form_access_data);

        //Get User Details

        $userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

        $lastLoginData = $this->Stats_model->last_login();

        $data['lastLoginData'] = $lastLoginData;

        $data['user'] = $userdetail;





        $data['all_records'] = $this->Template_model->getAllRecords();



        //Get All Classes from DB



        $this->load->view('msgtemp/msgtemp', $data);

    }





    function msgtemplate()

	{

		//Check avaialable

		$temp_id = $this->input->post('temp_id');

        $temp_id = $temp_id != '' ? $temp_id : 0;

		$if_exist = $this->Template_model->checkIfExist($temp_id);

		if ($if_exist > 0) {

			$data = array(

				'stvmsgt_text' => $this->input->post('messg'),

				'stvmsgt_updated_at' => date('Y-m-d'),

				'stvmsgt_updated_by' => $this->session->userdata('oliveSchoolDashboardUser')

			);

			//add teacher assigned data into database

			$status = $this->Template_model->update_data($data, $temp_id);

			if ($status) {

				$this->session->set_flashdata('message', "Data Updated Successfully !!!");

				$this->session->set_flashdata('alert_class', "alert-success");

				redirect(base_url() . 'MsgTemplate/index');

			} else {

				$this->session->set_flashdata('message', "Failed to Update Data !!!");

				$this->session->set_flashdata('alert_class', "alert-danger");

				redirect(base_url() . 'MsgTemplate/index');

			}

		} else {

			$data = array(

				'stvmsgt_text' => $this->input->post('messg'),

				'stvmsgt_created_by' => $this->session->userdata('oliveSchoolDashboardUser'),

				'stvmsgt_created_at' => date('Y-m-d')

			);

			//add teacher assigned data into database

			$status = $this->Template_model->add_city($data);

			if ($status) {

				$this->session->set_flashdata('message', "Data Added Successfully !!!");

				$this->session->set_flashdata('alert_class', "alert-success");

				redirect(base_url() . 'MsgTemplate/index');

			} else {

				$this->session->set_flashdata('message', "Failed to Add Data !!!");

				$this->session->set_flashdata('alert_class', "alert-danger");

				redirect(base_url() . 'MsgTemplate/index');

			}

		}

	}



    function getRecordByID(){

        $id = $this->input->post('id');

        echo json_encode($this->Template_model->getRecordsByID($id));

    }

}


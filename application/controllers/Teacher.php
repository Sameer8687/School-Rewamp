<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Teacher extends CI_Controller
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
		$this->load->model('Teacher_model');
		if (!$this->session->userdata('oliveSchoolDashboardUser'))
			redirect(base_url() . 'login');
	}


	//Register Teacher Form
	function register_teacher()
	{
		$new_docs = '';
		//Create dynamic generated student id
		$student_id = $this->input->post('student_id');
		//Get Already created Student ID
		$existed_student_id = $this->input->post('student_id_row');
		//Get Aadhar number in variable and initialize other variable 
		$student_image_path = "";
		$aadhar_no = $this->input->post('aadhar_number');
		//Get documents in variable
		$doc_names = $this->input->post('doc');
		if ($doc_names != "") {
			$new_docs = implode(",", $doc_names);
		}
		//Check user already available in database
		$is_available = $this->Teacher_model->check_if_exist($existed_student_id);
		//if user exist then update otherwise add new data
		if ($is_available > 0) {
			//echo "Update Data";
			//------------- Get Form data in Array -----------------------------------------------
			$update_data = array(
				'SPRIDEN_student_id' => $this->input->post('student_id'),
				'SPRIDEN_surname' => $this->input->post('surname'),
				'SPRIDEN_student_name' => $this->input->post('student_name'),
				'SPRIDEN_father_name' => $this->input->post('father_name'),
				'SPRIDEN_mother_name' => $this->input->post('mother_name'),
				'SPRIDEN_religion' => $this->input->post('religion'),
				'SPRIDEN_gender' => $this->input->post('gender'),
				'SPRIDEN_nationality' => $this->input->post('nationality'),
				'SPRIDEN_mother_toungue' => $this->input->post('mother_tongue'),
				'SPRIDEN_birth_place' => $this->input->post('birth_place'),
				'SPRIDEN_aadhar_no' => $this->input->post('aadhar_number'),
				'SPRIDEN_birthdate' => $this->input->post('date_of_birth'),
				'SPRIDEN_main_address' => $this->input->post('main_address'),
				'SPRIDEN_alt_address' => $this->input->post('alternate_addresss'),
				'SPRIDEN_father_quali' => $this->input->post('father_qualification'),
				'SPRIDEN_mother_quali' => $this->input->post('mother_qualification'),
				'SPRIDEN_office_add' => $this->input->post('office_address'),
				'SPRIDEN_last_updated' => date('Y-m-d'),
				'SPRIDEN_updated_by' => $this->session->userdata('oliveSchoolDashboardUser'),
				'SPRIDEN_ten_school' => $this->input->post('ten_school'),
				'SPRIDEN_ten_board' => $this->input->post('ten_board'),
				'SPRIDEN_ten_year' => $this->input->post('ten_year'),
				'SPRIDEN_twelve_college' => $this->input->post('twe_school'),
				'SPRIDEN_twelve_board' => $this->input->post('twe_board'),
				'SPRIDEN_twelve_year' => $this->input->post('twe_year'),
				'SPRIDEN_ug_college' => $this->input->post('ug_school'),
				'SPRIDEN_ug_uni' => $this->input->post('ug_board'),
				'SPRIDEN_ug_year' => $this->input->post('ug_year'),
				'SPRIDEN_pg_college' => $this->input->post('pg_school'),
				'SPRIDEN_pg_uni' => $this->input->post('pg_board'),
				'SPRIDEN_pg_year' => $this->input->post('pg_year'),
				'SPRIDEN_phd_college' => $this->input->post('phd_school'),
				'SPRIDEN_phd_uni' => $this->input->post('phd_board'),
				'SPRIDEN_phd_year' => $this->input->post('phd_year')
			);
			//update user data into database
			$status = $this->Teacher_model->update_teacher($update_data, $existed_student_id);
			if ($status) {
				$this->session->set_flashdata('message', "Teacher Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'teacher-account');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Teacher Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'teacher-account');
			}
		} else {
			//Get File name
			$file_name = "student_image_control";
			//check image selected or not. if selected do upload and save
			if (isset($_FILES[$file_name]["name"])) {
				$config['upload_path'] = './Teacher_images/';
				$config['allowed_types'] = 'jpg|jpeg|gif|png';
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload($file_name)) {
					$student_image_path = "";
				} else {
					$img_data = $this->upload->data();
					$student_image_path = $img_data["file_name"];
				}
			} else {
				$student_image_path = "";
			}
			// ------------- Get Form data in Array for inserting first time -----------------------------------------------
			$data = array(
				'SPRIDEN_student_id' => $student_id,
				'SPRIDEN_surname' => $this->input->post('surname'),
				'SPRIDEN_student_name' => $this->input->post('student_name'),
				'SPRIDEN_father_name' => $this->input->post('father_name'),
				'SPRIDEN_mother_name' => $this->input->post('mother_name'),
				'SPRIDEN_religion' => $this->input->post('religion'),
				'SPRIDEN_gender' => $this->input->post('gender'),
				'SPRIDEN_nationality' => $this->input->post('nationality'),
				'SPRIDEN_mother_toungue' => $this->input->post('mother_tongue'),
				'SPRIDEN_birth_place' => $this->input->post('birth_place'),
				'SPRIDEN_aadhar_no' => $this->input->post('aadhar_number'),
				'SPRIDEN_birthdate' => $this->input->post('date_of_birth'),
				'SPRIDEN_main_address' => $this->input->post('main_address'),
				'SPRIDEN_alt_address' => $this->input->post('alternate_addresss'),
				'SPRIDEN_father_quali' => $this->input->post('father_qualification'),
				'SPRIDEN_mother_quali' => $this->input->post('mother_qualification'),
				'SPRIDEN_office_add' => $this->input->post('office_address'),
				'SPRIDEN_documents' => $new_docs,
				'SPRIDEN_photo_path' => $student_image_path,
				'SPRIDEN_user_type' => "tc",
				'SPRIDEN_date_created' => date('Y-m-d'),
				'SPRIDEN_ten_school' => $this->input->post('ten_school'),
				'SPRIDEN_ten_board' => $this->input->post('ten_board'),
				'SPRIDEN_ten_year' => $this->input->post('ten_year'),
				'SPRIDEN_twelve_college' => $this->input->post('twe_school'),
				'SPRIDEN_twelve_board' => $this->input->post('twe_board'),
				'SPRIDEN_twelve_year' => $this->input->post('twe_year'),
				'SPRIDEN_ug_college' => $this->input->post('ug_school'),
				'SPRIDEN_ug_uni' => $this->input->post('ug_board'),
				'SPRIDEN_ug_year' => $this->input->post('ug_year'),
				'SPRIDEN_pg_college' => $this->input->post('pg_school'),
				'SPRIDEN_pg_uni' => $this->input->post('pg_board'),
				'SPRIDEN_pg_year' => $this->input->post('pg_year'),
				'SPRIDEN_phd_college' => $this->input->post('phd_school'),
				'SPRIDEN_phd_uni' => $this->input->post('phd_board'),
				'SPRIDEN_phd_year' => $this->input->post('phd_year'),
				'spriden_student_status' => 'A',
				'spriden_tc_pass' => 'Olive@123'
			);

			//save user data into database
			$status = $this->Teacher_model->register_teacher($data);
			if ($status) {
				$this->session->set_flashdata('message', "Teacher Registered Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'teacher-account');
			} else {
				$this->session->set_flashdata('message', "Failed to Registred Teacher Account !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'teacher-account');
			}
		}
	}

	//Search Student Name in Database
	function search_teacher_names()
	{
		$teacher_name = $this->input->post('name');
		$teacher_info = $this->Teacher_model->search_teacher_names($teacher_name);
		echo json_encode($teacher_info);
	}
	function get_selected_teacher_data()
	{
		$teacher_id = $this->input->post('student_id');
		$teacher_info = $this->Teacher_model->get_selected_student_data($teacher_id);
		$data['info'] = $teacher_info;
		echo json_encode($data);
	}

	//Remark Generation
	function add_remark()
	{
		$data = array(
			'stvrmrk_teacher_id' => $this->session->userdata('oliveSchoolDashboardUser'),
			'stvrmrk_student_id' => $this->input->post('student_id'),
			'stvrmrk_std' => "",
			'stvrmrk_date' => date('Y-m-d'),
			'stvrmrk_remark' => $this->input->post('remark_data'),
			'stvrmrk_status' => "New"
		);
		//save Remark data into database
		$status = $this->Teacher_model->add_remark($data);
		if ($status) {
			$this->session->set_flashdata('message', "Remark Added Successfully !!!");
			$this->session->set_flashdata('alert_class', "alert-success");
			redirect(base_url() . 'remark');
		} else {
			$this->session->set_flashdata('message', "Failed to Add Remark !!!");
			$this->session->set_flashdata('alert_class', "alert-danger");
			redirect(base_url() . 'remark');
		}
	}
	function getSelectedIdDataForFeedback()
	{
		$data = $this->Teacher_model->getSelectedIdDataForFeedback($this->input->post('id'));
		echo json_encode($data);
	}

	function update_student_remark_feedback()
	{
		$student_id = $this->input->post('student_id');
		$data = array(
			'stvrmrk_date_of_reply' => date('Y-m-d'),
			'stvrmrk_reply' => $this->input->post('feedback'),
			'stvrmrk_status' => "Replied"
		);
		$status = $this->Teacher_model->update_student_remark_feedback($data, $student_id);
		sleep(1);
		if ($status) {
			echo "success";
		} else {
			echo "fail";
		}
	}



	//Function to assign subject teacher
	function getSubjectByStd()
	{
		echo json_encode($this->Teacher_model->getSubjectByStd($this->input->post('class_name')));
	}

	//add fees
	function assign_subject_teacher()
	{
		//Check avaialable
		$assigned_id = $this->input->post('assigned_id');
		$if_exist = $this->Teacher_model->checkIfExistTeacherSubject($assigned_id);
		if ($if_exist > 0) {
			$data = array(
				'stvstcr_teacher_id' => $this->input->post('teacher'),
				'stvstcr_std' => $this->input->post('class'),
				'stvstcr_div' => $this->input->post('division'),
				'stvstcr_subject' => $this->input->post('subject_code'),
				'stvstcr_acdm' => $this->input->post('acdm_year')
			);
			//add teacher assigned data into database
			$status = $this->Teacher_model->update_subject_teacher($data, $assigned_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'subject-teacher');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'subject-teacher');
			}
		} else {
			//check subject is assigned already or not
			$subject_assigned = $this->Teacher_model->already_subject_assigned($this->input->post('class'), $this->input->post('division'), $this->input->post('subject_code'));
			if ($subject_assigned == null) {
				$data = array(
					'stvstcr_teacher_id' => $this->input->post('teacher'),
					'stvstcr_std' => $this->input->post('class'),
					'stvstcr_div' => $this->input->post('division'),
					'stvstcr_subject' => $this->input->post('subject_code'),
					'stvstcr_acdm' => $this->input->post('acdm_year'),
					'stvstcr_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
				);
				//add teacher assigned data into database
				$status = $this->Teacher_model->assign_subject_teacher($data);
				if ($status) {
					$this->session->set_flashdata('message', "Data Added Successfully !!!");
					$this->session->set_flashdata('alert_class', "alert-success");
					redirect(base_url() . 'subject-teacher');
				} else {
					$this->session->set_flashdata('message', "Failed to Add Data !!!");
					$this->session->set_flashdata('alert_class', "alert-danger");
					redirect(base_url() . 'subject-teacher');
				}
			} else {
				$this->session->set_flashdata('message', "Subject is Already assigned in this class");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'subject-teacher');
			}
		}
	}
	//get schoold data by id
	function getSubjectTeacherDataByID()
	{
		$data = $this->Teacher_model->getSubjectTeacherDataByID();
		echo json_encode($data);
	}

	function delete_data()
	{
		$status = $this->Teacher_model->delete_data();
		echo $status;
	}
	//------------ Changes on 25 Oct 2021
	function lesson()
	{
		$this->load->model('Stats_model');
		$this->load->model('MasterForm_model');
		$this->load->model('Lesson_model');
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Login from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$classes = $this->Stats_model->getAllClasses();
		$data['classes'] = $classes;
		$data['filter_view'] = "lesson";
		if ($this->uri->segment(3)) {
			$data['lesson_record'] = $this->Lesson_model->lesson_data($this->uri->segment(3));
			$data['subjects'] = $this->MasterForm_model->getAllSubjetcsByStd($data['lesson_record']['stvlmst_std']);
			//echo $data['lesson_record']['stvlmst_sub'];
			$this->load->view('Teacher/add-lesson-review', $data);
		} else {

			$data['lesson'] = $this->Lesson_model->all_lessons_for_sup();
			$this->load->view('Teacher/lesson-management', $data);
			// 			print_r($data['lesson']);
		}
	}
	function add_review_sup()
	{
		$this->load->model('Lesson_model');

		$id = $this->input->post('hidden_header_id');
		if ($this->Lesson_model->add_review()) {
			$this->session->set_flashdata('msg', "Review Updated Succesfully !");
			$this->session->set_flashdata('class', "alert-success");
		} else {
			$this->session->set_flashdata('msg', "Failed");
			$this->session->set_flashdata('class', "alert-danger");
		}
		redirect(base_url('teacher/lesson/' . $id));
	}

	function account_details()
	{
		//Get All Login from DB
		$this->load->model('Stats_model');
		$this->load->model('Teacher_model');
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['all_teachers'] = $this->Teacher_model->fetchAllTeachers();
		$data['filter_view']="account_info";
		$this->load->view('Teacher/account-info', $data);
	}


}

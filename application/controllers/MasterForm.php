<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MasterForm extends CI_Controller
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
		$this->load->model('MasterForm_model');
		$this->load->model('Stats_model');
		$this->load->model('Teacher_model');  
	}
	// ========================= CLASS FORMS ========================================
	//add Class Assigned teacher
	public function assign_teacher()
	{
		$std = $this->input->post('class');
		$div = $this->input->post('division');
		$id = $this->input->post('hidden_header_id');

		$academic_year = $this->input->post('academic_year');

		// Check if class already has a teacher
		$existed = $this->MasterForm_model->checkAssignedTeacher($std, $div);

		$data = array(
			'SPATESD_academic_year' => $academic_year,
			'SPATESD_teacher_id' => $this->input->post('teacher'),
			'SPATESD_std' => $std,
			'SPATESD_div' => $div,
			'SPATESD_assigned_date' => date('Y-m-d')
		);

		//================ INSERT ==================
		if ($id == 0) {

			if ($existed != null) {

				$message = $existed['SPRIDEN_surname'] . ' ' . $existed['SPRIDEN_student_name'] . ' Assigned To ' . $std . '-' . $div;

				$this->session->set_flashdata('message', $message);
				$this->session->set_flashdata('alert_class', 'alert-danger');

				redirect(base_url() . 'assign-teacher?acdm=' . $academic_year);
			}

			$status = $this->MasterForm_model->assign_teacher($data);

			if ($status) {

				$this->session->set_flashdata('message', 'Class Teacher Assigned Successfully');
				$this->session->set_flashdata('alert_class', 'alert-success');

			} else {

				$this->session->set_flashdata('message', 'Failed to Assign Teacher');
				$this->session->set_flashdata('alert_class', 'alert-danger');
			}

			redirect(base_url() . 'assign-teacher?acdm=' . $academic_year);
		}

		//================ UPDATE ==================

		if ($existed != null && $existed['SPATESD_id'] != $id) {

			$message = $existed['SPRIDEN_surname'] . ' ' . $existed['SPRIDEN_student_name'] . ' Assigned To ' . $std . '-' . $div;

			$this->session->set_flashdata('message', $message);
			$this->session->set_flashdata('alert_class', 'alert-danger');

			redirect(base_url() . 'assign-teacher?acdm=' . $academic_year);
		}

		$status = $this->MasterForm_model->update_assign_teacher($data, $id);

		if ($status) {

			$this->session->set_flashdata('message', 'Data Updated Successfully');
			$this->session->set_flashdata('alert_class', 'alert-success');

		} else {

			$this->session->set_flashdata('message', 'Failed To Update');
			$this->session->set_flashdata('alert_class', 'alert-danger');
		}

		redirect(base_url() . 'assign-teacher?acdm=' . $academic_year);
	}

	function tecaherClassDetails()
	{
		echo json_encode($this->MasterForm_model->tecaherClassDetails($this->input->get('header-id')));
	}

	function deleteTeacherRecord()
	{
		$status = $this->MasterForm_model->deleteTeacherRecord($this->input->get('header-id'));
		if ($status) {
			echo "success";
		} else {
			echo "fail";
		}
	}

	// ========================= MASTER FORMS ========================================
	//---------------------------- Subject Master----------------------
	//add subject
	function add_subject()
	{
		//Check avaialable
		$subject_id = $this->input->post('subject_id');
		$if_exist = $this->MasterForm_model->checkIfExistSubject($subject_id);
		if ($if_exist > 0) {
			$data = array(
				'stvsubj_subject' => $this->input->post('subject'),
				'stvsubj_std' => $this->input->post('class'),
				'stvsubj_acdm' => $this->input->post('acdm_year')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->update_subject_data($data, $subject_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvsubj');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvsubj');
			}
		} else {
			$data = array(
				'stvsubj_subject' => $this->input->post('subject'),
				'stvsubj_std' => $this->input->post('class'),
				'stvsubj_acdm' => $this->input->post('acdm_year'),
				'stvsubj_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->add_subject($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvsubj');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvsubj');
			}
		}
	}
	//get subject data by id

	function getSubjectDataByID()
	{
		$data = $this->MasterForm_model->getSubjectDataByID();
		echo json_encode($data);
	}

	// Exam Master ---------------------------------------------------
	//add Exam
	function add_exam_data()
	{
		//Check avaialable
		$exam_id = $this->input->post('exam_id');
		$if_exist = $this->MasterForm_model->checkIfExistExam($exam_id);
		if ($if_exist > 0) {
			$data = array(
				'stvsubj_subject' => $this->input->post('subject'),
				'stvsubj_std' => $this->input->post('class')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->update_exam_data($data, $exam_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvsubj');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvsubj');
			}
		} else {
			$data = array(
				'stvexam_name' => $this->input->post('exam_name'),
				'stvexam_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->add_exam_data($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvexam');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvexam');
			}
		}
	}
	// Exam Master ---------------------------------------------------
	//add Exam
	function add_hold_data()
	{
		//Check avaialable
		$hold_id = $this->input->post('hold_id');
		$if_exist = $this->MasterForm_model->checkIfExistHold($hold_id);
		if ($if_exist > 0) {
			$data = array(
				'stvsubj_subject' => $this->input->post('subject'),
				'stvsubj_std' => $this->input->post('class')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->update_hold_data($data, $hold_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvhold');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvhold');
			}
		} else {
			$data = array(
				'stvhold_type' => $this->input->post('hold_name'),
				'stvhold_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->add_hold_data($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvhold');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvhold');
			}
		}
	}

	// --------------------------- Fees MAster-------------------------------
	//add fees
	function add_stvfees_data()
	{
		//Check avaialable
		$fees_id = $this->input->post('fees_id');
		$if_exist = $this->MasterForm_model->checkIfExistFees($fees_id);
		if ($if_exist > 0) {
			$data = array(
				'stvfees_class' => $this->input->post('standard'),
				'stvfees_totalfees' => $this->input->post('amount')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->update_fees_data($data, $fees_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvfees');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvfees');
			}
		} else {
			$data = array(
				'stvfees_class' => $this->input->post('standard'),
				'stvfees_totalfees' => $this->input->post('amount'),
				'stvfees_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->add_stvfees_data($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvfees');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvfees');
			}
		}
	}
	//get schoold data by id
	function getFeesDataByID()
	{
		$data = $this->MasterForm_model->getFeesDataByID();
		echo json_encode($data);
	}
	//---------------School Master ---------------------------------
	//add School
	function add_school_data()
	{
		$school_id = $this->input->post('school_id');
		$if_exist = $this->MasterForm_model->checkIfExistSchool($school_id);
		if ($if_exist > 0) {
			$data = array(
				'STVPSCH_name' => $this->input->post('name'),
				'STVPSCH_city' => $this->input->post('city'),
				'STVPSCH_states' => $this->input->post('state'),
				'STVPSCH_address' => $this->input->post('address'),
				'STVPSCH_email' => $this->input->post('email'),
				'STVPSCH_phone' => $this->input->post('phone')
			);
			//add  data into database
			$status = $this->MasterForm_model->update_school_data($data, $school_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvpsch');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvpsch');
			}
		} else {
			$data = array(
				'STVPSCH_name' => $this->input->post('name'),
				'STVPSCH_city' => $this->input->post('city'),
				'STVPSCH_states' => $this->input->post('state'),
				'STVPSCH_address' => $this->input->post('address'),
				'STVPSCH_email' => $this->input->post('email'),
				'STVPSCH_phone' => $this->input->post('phone'),
				'STVPSCH_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
			);
			//add  data into database
			$status = $this->MasterForm_model->add_school_data($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvpsch');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvpsch');
			}
		}
	}
	//get schoold data by id
	function getSchoolDataByID()
	{
		$data = $this->MasterForm_model->getSchoolDataByID();
		echo json_encode($data);
	}

	//---------------College Master ---------------------------------
	//add College
	function add_college_data()
	{
		$college_id = $this->input->post('college_id');
		$if_exist = $this->MasterForm_model->checkIfExistCollege($college_id);
		if ($if_exist > 0) {
			$data = array(
				'STVCOLL_name' => $this->input->post('name'),
				'STVCOLL_city' => $this->input->post('city'),
				'STVCOLL_state' => $this->input->post('state'),
				'STVCOLL_address' => $this->input->post('address'),
				'STVCOLL_email' => $this->input->post('email'),
				'STVCOLL_phone' => $this->input->post('phone')
			);
			//add  data into database
			$status = $this->MasterForm_model->update_college_data($data, $college_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvcolg');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvcolg');
			}
		} else {
			$data = array(
				'STVCOLL_name' => $this->input->post('name'),
				'STVCOLL_city' => $this->input->post('city'),
				'STVCOLL_state' => $this->input->post('state'),
				'STVCOLL_address' => $this->input->post('address'),
				'STVCOLL_email' => $this->input->post('email'),
				'STVCOLL_phone' => $this->input->post('phone'),
				'STVCOLL_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
			);
			//add  data into database
			$status = $this->MasterForm_model->add_College_data($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvcolg');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvcolg');
			}
		}
	}
	//---------------University Master ---------------------------------
	//add College
	function add_university_data()
	{
		$college_id = $this->input->post('university_id');
		$if_exist = $this->MasterForm_model->checkIfExistUniversity($college_id);
		if ($if_exist > 0) {
			$data = array(
				'STVUNIV_name' => $this->input->post('name'),
				'STVUNIV_city' => $this->input->post('city'),
				'STVUNIV_state' => $this->input->post('state'),
				'STVUNIV_address' => $this->input->post('address'),
				'STVUNIV_email' => $this->input->post('email'),
				'STVUNIV_phone' => $this->input->post('phone')
			);
			//add  data into database
			$status = $this->MasterForm_model->update_University_data($data, $college_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvuniv');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvuniv');
			}
		} else {
			$data = array(
				'STVUNIV_name' => $this->input->post('name'),
				'STVUNIV_city' => $this->input->post('city'),
				'STVUNIV_state' => $this->input->post('state'),
				'STVUNIV_address' => $this->input->post('address'),
				'STVUNIV_email' => $this->input->post('email'),
				'STVUNIV_phone' => $this->input->post('phone'),
				'STVUNIV_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
			);
			//add  data into database
			$status = $this->MasterForm_model->add_University_data($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvuniv');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvuniv');
			}
		}
	}
	//get schoold data by id
	function getUniversityDataByID()
	{
		$data = $this->MasterForm_model->getUniversityDataByID();
		echo json_encode($data);
	}

	//------------ Major Master ---------------------------------------
	//add major
	function add_major()
	{
		//Check avaialable
		$major_id = $this->input->post('major_id');
		$if_exist = $this->MasterForm_model->checkIfExistMajor($major_id);
		if ($if_exist > 0) {
			$data = array(
				'STVMAJR_name' => $this->input->post('major'),
				'STVMAJR_details' => $this->input->post('details')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->update_major_data($data, $major_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvmajr');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvmajr');
			}
		} else {
			$data = array(
				'STVMAJR_name' => $this->input->post('major'),
				'STVMAJR_details' => $this->input->post('details'),
				'STVMAJR_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->add_major($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvmajr');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvmajr');
			}
		}
	}
	//get subject data by id

	function getMajorDataByID()
	{
		$data = $this->MasterForm_model->getMajorDataByID();
		echo json_encode($data);
	}


	// --------------------------- Shift MAster-------------------------------
	//add fees
	function add_shift_data()
	{
		//Check avaialable
		$shift_id = $this->input->post('shift_id');
		$if_exist = $this->MasterForm_model->checkIfExistShift($shift_id);
		if ($if_exist > 0) {
			$data = array(
				'STVSHIF_code' => $this->input->post('shift_code'),
				'STVSHIF_from' => $this->input->post('start_from'),
				'STVSHIF_to' => $this->input->post('end_to')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->update_shift_data($data, $shift_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvshif');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvshif');
			}
		} else {
			$data = array(
				'STVSHIF_code' => $this->input->post('shift_code'),
				'STVSHIF_from' => $this->input->post('start_from'),
				'STVSHIF_to' => $this->input->post('end_to'),
				'STVSHIF_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->add_shift_data($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvshif');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvshif');
			}
		}
	}
	//get schoold data by id
	function getShiftDataByID()
	{
		$data = $this->MasterForm_model->getShiftDataByID();
		echo json_encode($data);
	}


	// --------------------------- Position MAster-------------------------------
	//add fees
	function add_position_data()
	{
		//Check avaialable
		$position_id = $this->input->post('position_id');
		$if_exist = $this->MasterForm_model->checkIfExistPosition($position_id);
		if ($if_exist > 0) {
			$data = array(
				'STVPOSN_code' => $this->input->post('code'),
				'STVPOSN_name' => $this->input->post('name')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->update_position_data($data, $position_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvposn');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvposn');
			}
		} else {
			$data = array(
				'STVPOSN_code' => $this->input->post('code'),
				'STVPOSN_name' => $this->input->post('name'),
				'STVPOSN_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->add_position_data($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvposn');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvposn');
			}
		}
	}
	//get Position data by id
	function getPositionDataByID()
	{
		$data = $this->MasterForm_model->getPositionDataByID();
		echo json_encode($data);
	}
	// --------------------------- Position MAster-------------------------------
	//add fees
	function add_grades_data()
	{
		//Check avaialable
		$position_id = $this->input->post('grade_id');
		$if_exist = $this->MasterForm_model->checkIfExistGrade($position_id);
		if ($if_exist > 0) {
			$data = array(
				'STVGRDS_grade' => $this->input->post('name'),
				'STVGRDS_point' => $this->input->post('point'),
				'STVGRDS_marks_from' => $this->input->post('mark_from'),
				'STVGRDS_marks_to' => $this->input->post('mark_to'),
				'STVGRDS_notes' => $this->input->post('details')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->update_grades_data($data, $position_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvgrds');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvgrds');
			}
		} else {
			$data = array(
				'STVGRDS_grade' => $this->input->post('name'),
				'STVGRDS_point' => $this->input->post('point'),
				'STVGRDS_marks_from' => $this->input->post('mark_from'),
				'STVGRDS_marks_to' => $this->input->post('mark_to'),
				'STVGRDS_notes' => $this->input->post('details'),
				'STVGRDS_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->add_grades_data($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvgrds');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvgrds');
			}
		}
	}
	//get Position data by id
	function getGradesDataByID()
	{
		$data = $this->MasterForm_model->getGradesDataByID();
		echo json_encode($data);
	}

	// --------------------------- Position MAster-------------------------------
	//add fees
	function add_academic_year_data()
	{
		//Check avaialable
		$position_id = $this->input->post('academic_id');
		$if_exist = $this->MasterForm_model->checkIfExistAcademicYear($position_id);
		if ($if_exist > 0) {
			$first = $this->input->post('session_start');
			$second = $this->input->post('session_end');
			$acdm_year = substr($first, 0, 4) . "-" . substr($second, 2, 2);
			$data = array(
				'stvacdm_start' => $this->input->post('session_start'),
				'stvacdm_end' => $this->input->post('session_end'),
				'stvacdm_year' => $acdm_year,
				'stvacdm_notes' => $this->input->post('notes')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->update_academic_year_data($data, $position_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvacdm');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvacdm');
			}
		} else {
			$first = $this->input->post('session_start');
			$second = $this->input->post('session_end');
			$acdm_year = substr($first, 0, 4) . "-" . substr($second, 2, 2);
			$data = array(
				'stvacdm_start' => $this->input->post('session_start'),
				'stvacdm_end' => $this->input->post('session_end'),
				'stvacdm_year' => $acdm_year,
				'stvacdm_notes' => $this->input->post('notes'),
				'stvacdm_added_by' => $this->session->userdata('oliveSchoolDashboardUser'),
				'status' => "0"
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->add_academic_year_data($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvacdm');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvacdm');
			}
		}
	}
	//get Position data by id
	function getAcademicYearDataByID()
	{
		$data = $this->MasterForm_model->getAcademicYearDataByID();
		echo json_encode($data);
	}

	//---------------------------- state Master----------------------
	//add state
	function add_state()
	{
		//Check avaialable
		$state_id = $this->input->post('state_id');
		$if_exist = $this->MasterForm_model->checkIfExistState($state_id);
		if ($if_exist > 0) {
			$data = array(
				'stvstts_state_code' => $this->input->post('state_code'),
				'stvstts_state_name' => $this->input->post('state_name')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->update_state_data($data, $state_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvstts');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvstts');
			}
		} else {
			$data = array(
				'stvstts_state_code' => $this->input->post('state_code'),
				'stvstts_state_name' => $this->input->post('state_name'),
				'stvstts_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->add_state($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvstts');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvstts');
			}
		}
	}
	//get state data by id

	function getStateDataByID()
	{
		$data = $this->MasterForm_model->getStateDataByID();
		echo json_encode($data);
	}


	//---------------------------- City Master----------------------
	//add city
	function add_city()
	{
		//Check avaialable
		$city_id = $this->input->post('city_id');
		$if_exist = $this->MasterForm_model->checkIfExistCity($city_id);
		if ($if_exist > 0) {
			$data = array(
				'STVCITY_code' => $this->input->post('city_code'),
				'STVCITY_name' => $this->input->post('city_name'),
				'STVCITY_state_code' => $this->input->post('state_code')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->update_city_data($data, $city_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvcity');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvcity');
			}
		} else {
			$data = array(
				'STVCITY_code' => $this->input->post('city_code'),
				'STVCITY_name' => $this->input->post('city_name'),
				'STVCITY_state_code' => $this->input->post('state_code'),
				'STVCITY_added_by' => $this->session->userdata('oliveSchoolDashboardUser')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->add_city($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvcity');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvcity');
			}
		}
	}
	//get city data by id

	function getCityDataByID()
	{
		$data = $this->MasterForm_model->getCityDataByID();
		echo json_encode($data);
	}



	// --------------------------- Position MAster-------------------------------
	//add fees
	function add_form_access()
	{
		//Check avaialable
		$position_id = $this->input->post('access_id');
		$if_exist = $this->MasterForm_model->checkIfExistAccess($position_id);
		if ($if_exist > 0) {
			$data = array(
				'stvuacs_userid' => $this->input->post('user_code'),
				'stvuacs_formurl' => $this->input->post('form_code')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->update_access_data($data, $position_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvfacs');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvfacs');
			}
		} else {
			$data = array(
				'stvuacs_userid' => $this->input->post('user_code'),
				'stvuacs_formurl' => $this->input->post('form_code')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->add_form_access($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvfacs');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvfacs');
			}
		}
	}
	//get Position data by id
	function getAccessDataByID()
	{
		$data = $this->MasterForm_model->getAccessDataByID();
		echo json_encode($data);
	}


	// --------------------------- User MAster-------------------------------
	//add fees
	function add_dashboard_user()
	{
		//Check avaialable
		$userdb_id = $this->input->post('dbuser_id');
		$if_exist = $this->MasterForm_model->checkIfExistDBuser($userdb_id);
		if ($if_exist > 0) {
			$data = array(
				'spruser_name' => $this->input->post('nameofuser'),
				'spruser_username' => $this->input->post('username'),
				'spruser_password' => $this->input->post('password'),
				'spruser_mobile' => $this->input->post('mobileno'),
				'spruser_email' => $this->input->post('email'),
				'spruser_usertype' => $this->input->post('usertype')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->update_dbuser_data($data, $userdb_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvusrs');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvusrs');
			}
		} else {
			$data = array(
				'spruser_name' => $this->input->post('nameofuser'),
				'spruser_username' => $this->input->post('username'),
				'spruser_password' => $this->input->post('password'),
				'spruser_mobile' => $this->input->post('mobileno'),
				'spruser_email' => $this->input->post('email'),
				'spruser_usertype' => $this->input->post('usertype'),
				'spruser_created_at' => date('Y-m-d')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->add_dashboard_user($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvusrs');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvusrs');
			}
		}
	}
	//get Position data by id
	function getDBUserDataByID()
	{
		$data = $this->MasterForm_model->getDBUserDataByID();
		echo json_encode($data);
	}

	// ===================== CLASS CREATION ===================================
	//get students for class
	function getStudentForClass()
	{
		$data = $this->MasterForm_model->getStudentForClass();
		if ($data != 0) {
			echo json_encode($data);
		} else {
			echo json_encode("fail");
		}
	}




	//=================================== LOGIN HISTORY=====================================================
	//---Login Data History
	function findLoginDataByUsername()
	{
		$data = $this->MasterForm_model->findLoginDataByUsername();
		$response = array();
		$color_name = '';
		for ($i = 0; $i < count($data); $i++) {
			if ($data[$i]['stvlgin_status'] == "Logged In") {
				$color_name = "text-success";
			} else {
				$color_name = "text-danger";
			}
			$servDate = $data[$i]['stvlgin_date'];
			$timestamp = strtotime($servDate);
			$newDate = date("d M Y", $timestamp);
			$login_time = date('g:i:s A', strtotime($data[$i]['stvlgin_time']));
			$response[$i]['message'] = 'Has been <span class="' . $color_name . '">' . $data[$i]['stvlgin_status'] . '</span> at ' . $newDate . ' ' . $login_time;
			$response[$i]['name'] = $data[$i]['spruser_name'];
			$response[$i]['userType'] = $data[$i]['spruser_usertype'];
		}
		echo json_encode($response);
	}
	function findLoginDataByDate()
	{
		$data = $this->MasterForm_model->findLoginDataByDate();
		$response = array();
		$color_name = '';
		for ($i = 0; $i < count($data); $i++) {
			if ($data[$i]['stvlgin_status'] == "Logged In") {
				$color_name = "text-success";
			} else {
				$color_name = "text-danger";
			}
			$servDate = $data[$i]['stvlgin_date'];
			$timestamp = strtotime($servDate);
			$newDate = date("d M Y", $timestamp);
			$login_time = date('g:i:s A', strtotime($data[$i]['stvlgin_time']));
			$response[$i]['message'] = 'Has been <span class="' . $color_name . '">' . $data[$i]['stvlgin_status'] . '</span> at ' . $newDate . ' ' . $login_time;
			$response[$i]['name'] = $data[$i]['spruser_name'];
			$response[$i]['userType'] = $data[$i]['spruser_usertype'];
		}
		echo json_encode($response);
	}
	function findLoginDataByBoth()
	{
		$data = $this->MasterForm_model->findLoginDataByBoth();
		$response = array();
		$color_name = '';
		for ($i = 0; $i < count($data); $i++) {
			if ($data[$i]['stvlgin_status'] == "Logged In") {
				$color_name = "text-success";
			} else {
				$color_name = "text-danger";
			}
			$servDate = $data[$i]['stvlgin_date'];
			$timestamp = strtotime($servDate);
			$newDate = date("d M Y", $timestamp);
			$login_time = date('g:i:s A', strtotime($data[$i]['stvlgin_time']));
			$response[$i]['message'] = 'Has been <span class="' . $color_name . '">' . $data[$i]['stvlgin_status'] . '</span> at ' . $newDate . ' ' . $login_time;
			$response[$i]['name'] = $data[$i]['spruser_name'];
			$response[$i]['userType'] = $data[$i]['spruser_usertype'];
		}
		echo json_encode($response);
	}
	// changes made from here --- 28 Sep 2021


	//---------------------------- Subject Master----------------------
	//add subject
	function add_lesson()
	{
		//Check avaialable
		$lesson_id = $this->input->post('lesson_id');
		$if_exist = $this->MasterForm_model->checkIfExistLesson($lesson_id);
		if ($if_exist > 0) {
			$data = array(
				'stvlmst_std' => $this->input->post('class'),
				'stvlmst_sub' => $this->input->post('subject'),
				'stvlmst_lesson' => $this->input->post('lesson'),
				'stvlmst_updated_at' => date('Y-m-d')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->update_lesson_data($data, $lesson_id);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvlmst');
			} else {
				$this->session->set_flashdata('message', "Failed to Update Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvlmst');
			}
		} else {
			$data = array(
				'stvlmst_std' => $this->input->post('class'),
				'stvlmst_sub' => $this->input->post('subject'),
				'stvlmst_lesson' => $this->input->post('lesson'),
				'stvlmst_created_at' => date('Y-m-d')
			);
			//add teacher assigned data into database
			$status = $this->MasterForm_model->add_lesson($data);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvlmst');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvlmst');
			}
		}
	}
	//get subject data by id

	function getLessonDataByID()
	{
		$data = $this->MasterForm_model->getLessonDataByID();
		echo json_encode($data);
	}


	function getAllSubjetcsByStd()
	{
		echo json_encode($this->MasterForm_model->getAllSubjetcsByStd($this->input->get('std')));
	}
	// to here --- 28 Sep 2021
	// 25-Dec-2021=======
	function addDriver()
	{
		$ids = $this->input->post('id');
		$isDriverExisted = $this->MasterForm_model->checkDriverPresent($ids);
		$id = $this->MasterForm_model->getDriverNumber();
		if ($id == 0) {
			$order_no = 1;
			$dNumber = "DR" . date("Y") . sprintf("%04d", $order_no) . rand(1, 100);
		} else {
			$order_no = $id + 1;
			$dNumber = "DR" . date("Y") . sprintf("%04d", $order_no) . rand(1, 100);
		}
		if ($isDriverExisted > 0) {
			$status = $this->MasterForm_model->updateDriver($ids);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvldrm');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvldrm');
			}
		} else {
			$status = $this->MasterForm_model->addDriver($dNumber);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvldrm');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvldrm');
			}
		}
	}
	function getDataByID()
	{
		$id = $this->input->get('id');
		$data = $this->MasterForm_model->getDataByID($id);
		echo json_encode($data);
	}
	function addVehicle()
	{
		$ids = $this->input->post('header_id');
		$isVehicleExisted = $this->MasterForm_model->checkVehiclePresent($ids);
		// $dNumber1['dNumber'] = $this->MasterForm_model->getDriverNum();
		$id = $this->MasterForm_model->getVehicleNumber();
		if ($id == 0) {
			$order_no = 1;
			$vNumber = "VCL" . date("Y") . sprintf("%04d", $order_no) . rand(1, 100);
		} else {
			$order_no = $id + 1;
			$vNumber = "VCL" . date("Y") . sprintf("%04d", $order_no) . rand(1, 100);
		}

		if ($isVehicleExisted > 0) {

			$status = $this->MasterForm_model->updateVehicle($ids);
			if ($status) {
				$this->session->set_flashdata('message', "Data Updated Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvlvm');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvlvm');
			}
		} else {
			$status = $this->MasterForm_model->addVehicle($vNumber);
			if ($status) {
				$this->session->set_flashdata('message', "Data Added Successfully !!!");
				$this->session->set_flashdata('alert_class', "alert-success");
				redirect(base_url() . 'stvlvm');
			} else {
				$this->session->set_flashdata('message', "Failed to Add Data !!!");
				$this->session->set_flashdata('alert_class', "alert-danger");
				redirect(base_url() . 'stvlvm');
			}
		}
	}
	function getVehicleDataByID()
	{
		$data = $this->MasterForm_model->getVehicleDataByID($this->input->get('id'));
		echo json_encode($data);
	}
	function addStructure()
	{
		$class = $this->input->post('class');
		$acdm = $this->input->post('acdm_yr');
		$fees = $this->input->post('fees');

		$status = $this->MasterForm_model->addStructure($class, $acdm, $fees);
		if ($status) {
			$this->session->set_flashdata('message', "Data Saved Successfully !!!");
			$this->session->set_flashdata('alert_class', "alert-success");
		} else {
			$this->session->set_flashdata('message', "Failed to Saved Data !!!");
			$this->session->set_flashdata('alert_class', "alert-danger");
		}
		redirect(base_url() . 'structure');
	}

	public function studentStdDiv()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Login from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['acdm_year'] = $this->MasterForm_model->get_all_academic_years();
		$data['getstudents'] = $this->MasterForm_model->getstudents();
		$this->load->view('MasterForms/updateStdDiv', $data);
	}

	public function getStdDivDataByID()
	{
		$id = $this->input->get('id');
		$result = $this->MasterForm_model->studentDetails($id);
		echo json_encode($result);
	}

	public function updateClassDetails()
	{
		$student_id = $this->input->post('STVCLAS_student_id');
		$old_acdm = $this->input->post('STVCLAS_acdm_old');
		$new_acdm = $this->input->post('STVCLAS_acdm');
		$code = $this->input->post('STVCLAS_code');
		$standard = $this->input->post('STVCLAS_standard');
		$class = $this->input->post('STVCLAS_class');
		$old_standard = $this->input->post('old_standard');
		$old_class = $this->input->post('old_class');
		$result = $this->MasterForm_model->updateClassDetails(
			$student_id,
			$old_acdm,
			$new_acdm,
			$code,
			$standard,
			$class,
			$old_standard,
			$old_class
		);
		echo json_encode([
			'status' => $result,
			'message' => $result ? 'Student updated successfully.' : 'Update failed.'
		]);
	}
}

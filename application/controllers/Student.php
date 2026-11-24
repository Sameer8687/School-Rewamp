<?php defined('BASEPATH') or exit('No direct script access allowed');
class Student extends MY_Controller
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
		$this->load->model('StudentProfile_model');
		$this->load->model('MasterForm_model');

		$this->load->model('Stats_model');

		$this->load->model('Student_model');

		$this->load->model('StudentDetails_model');
		$this->load->model('Report_model');

		date_default_timezone_set('Asia/Kolkata');
		if (!$this->session->userdata('oliveSchoolDashboardUser'))
			redirect(base_url() . 'login');
	}



	//Generate student ID 

	function genearetID()
	{

		$sentacdmyear = $this->input->get('academic-year');

		static $nos = 0001;

		$main_id = '';

		$acdm_year = '';

		if ($sentacdmyear == 0) {

			$academicYear = $this->Stats_model->getAcademicYear();
		} else {

			$academicYear['stvacdm_year'] = $sentacdmyear;
		}

		$acdm_year = $academicYear['stvacdm_year'];

		$acdm_substr = substr($acdm_year, 0, 4);

		$maxId = $this->Student_model->get_MaxID_By_Acdm($acdm_substr);

		if ($academicYear != 0) {



			$lastID_substr = substr($maxId, 1, 4);

			if ($lastID_substr == $acdm_substr) {

				$newstring = substr($maxId, -4);

				$newstring += 1;

				$newstring = sprintf('%04d', $newstring);

				$main_id = "S" . $acdm_substr . $newstring;

				echo $main_id;
			} else {

				$nos = sprintf('%04d', $nos);

				$main_id = "S" . $acdm_substr . $nos;

				echo $main_id;
			}
		} else {

			echo "fail";
		}
	}

	//Register Student Form

	function register_student()
	{

		$new_docs = '';

		//Create dynamic generated student id

		$student_id = '';

		//Get Already created Student ID

		$existed_student_id = $this->input->post('student_id_row');

		$pre_student_id = $this->input->post('pre_student_id');

		//Get Aadhar number in variable and initialize other variable 

		$student_image_path = "";

		$aadhar_no = $this->input->post('aadhar_number');

		//Get documents in variable

		$doc_names = $this->input->post('doc');

		if ($doc_names != "") {

			$new_docs = implode(",", $doc_names);
		}

		//Check user already available in database

		$is_available = $this->Student_model->check_if_exist($existed_student_id);

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

				'SPRIDEN_admission_class' => $this->input->post('admission_class'),

				'SPRIDEN_last_school' => $this->input->post('last_school'),

				'SPRIDEN_main_address' => $this->input->post('main_address'),

				'SPRIDEN_alt_address' => $this->input->post('alternate_addresss'),

				'SPRIDEN_father_quali' => $this->input->post('father_qualification'),

				'SPRIDEN_mother_quali' => $this->input->post('mother_qualification'),

				'SPRIDEN_office_add' => $this->input->post('office_address'),

				'SPRIDEN_fees_type' => $this->input->post('fees_type'),

				'SPRIDEN_fees_details' => $this->input->post('fees_details'),

				'SPRIDEN_last_updated' => date('Y-m-d'),

				'SPRIDEN_updated_by' => $this->session->userdata('oliveSchoolDashboardUser')

			);

			//update user data into database

			$status = $this->Student_model->update_student($update_data, $existed_student_id, $pre_student_id);

			if ($status) {

				$this->session->set_flashdata('message', "Student Data Updated Successfully !!!");

				$this->session->set_flashdata('alert_class', "alert-success");

				redirect(base_url() . 'admission-form');
			} else {

				$this->session->set_flashdata('message', "Failed to Update Student Data !!!");

				$this->session->set_flashdata('alert_class', "alert-danger");

				redirect(base_url() . 'admission-form');
			}
		} else {

			//Get File name

			$file_name = "student_image_control";

			//check image selected or not. if selected do upload and save

			if (isset($_FILES[$file_name]["name"])) {

				$config['upload_path'] = './Student_Images/';

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

				'SPRIDEN_admission_class' => $this->input->post('admission_class'),

				'SPRIDEN_last_school' => $this->input->post('last_school'),

				'SPRIDEN_main_address' => $this->input->post('main_address'),

				'SPRIDEN_alt_address' => $this->input->post('alternate_addresss'),

				'SPRIDEN_father_quali' => $this->input->post('father_qualification'),

				'SPRIDEN_mother_quali' => $this->input->post('mother_qualification'),

				'SPRIDEN_office_add' => $this->input->post('office_address'),

				'SPRIDEN_documents' => $new_docs,

				'SPRIDEN_photo_path' => $student_image_path,

				'SPRIDEN_fees_type' => $this->input->post('fees_type'),

				'SPRIDEN_fees_details' => $this->input->post('fees_details'),

				'SPRIDEN_user_type' => "st",

				'SPRIDEN_date_created' => date('Y-m-d'),

				'spriden_admission_year' => date('Y'),

				'spriden_student_status' => "A"



			);





			//save user data into database

			$status = $this->Student_model->register_student($data);

			if ($status) {

				$this->session->set_flashdata('message', "Student Registered Successfully !!!");

				$this->session->set_flashdata('alert_class', "alert-success");

				redirect(base_url() . 'admission-form');
			} else {

				$this->session->set_flashdata('message', "Failed to Registred Student !!!");

				$this->session->set_flashdata('alert_class', "alert-danger");

				redirect(base_url() . 'admission-form');
			}
		}
	}



	//Search Student Name in Database

	function search_student_names()
	{

		$student_name = $this->input->post('name');

		$student_info = $this->Student_model->search_student_names($student_name);

		echo json_encode($student_info);
	}

	function get_selected_student_data()
	{

		$student_id = $this->input->post('student_id');

		$student_info = $this->Student_model->get_selected_student_data($student_id);

		$visiting_data = $this->Student_model->get_previous_transaction($student_id);



		$data['info'] = $student_info;

		$data['visiting'] = $visiting_data;

		echo json_encode($data);
	}



	//Search Student Name for fees

	function search_from_spofees()
	{

		$student_name = $this->input->post('name');

		$student_info = $this->Student_model->search_from_spofees($student_name);

		echo json_encode($student_info);
	}



	function books_records()
	{

		$this->load->model('MasterForm_model');

		//Get User Details

		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		//Get All Students from DB

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;

		$data['user'] = $userdetail;

		$data['classes'] = $this->Stats_model->getAllClasses();

		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		$data['academic_details'] = $this->Stats_model->getAcademicDetails();

		$data['all_records'] = array();

		if ($this->uri->total_segments() == 4) {

			$data['all_records'] = $this->Student_model->student_comments_api($this->uri->segment(3), 'books', $this->uri->segment(4));
		}
		$data['filter_view'] = "books_records";
		$this->load->view('Student/student-books-records', $data);
	}



	function get_search_from_spofees()
	{

		$student_id = $this->input->post('student_id');

		$student_BooksData = $this->Student_model->getSelectedStudentBooksData($student_id);

		$student_info = $this->Student_model->get_search_from_spofees($student_id);

		// if ($student_BooksData != null) {

		// 	$updater_id = $student_BooksData['STVBOOK_update_by'];

		// 	$updater_name = $this->Student_model->get_updater_name($updater_id);

		// }

		$data['info'] = $student_info;

		$data['books'] = $student_BooksData;

		// $data['update_by'] = $updater_name;

		echo json_encode($data);
	}



	//Add / Update Student Books Data

	function add_books_and_result_data()
	{

		$student_id = $this->input->post('student_id');

		$is_available = $this->Student_model->check_books_data_available($student_id);



		if ($is_available > 0) {

			$update_books = array(

				'STVBOOK_standard' => $this->input->post('student_standard'),

				'STVBOOK_result' => $this->input->post('result_given'),

				'STVBOOK_books' => $this->input->post('books_given'),

				'STVBOOK_teacher_name' => $this->input->post('teacher_name'),

				'STVBOOK_paid_amonut' => $this->input->post('amount_paid'),

				'STVBOOK_bal_amount' => $this->input->post('balance_amt'),

				'STVBOOK_due_date' => $this->input->post('due_date'),

				'STVBOOK_details' => $this->input->post('details'),

				'STVBOOK_area' => $this->input->post('area'),

				'STVBOOK_updated_date' => date('Y-m-d'),

				'STVBOOK_update_by' => $this->session->userdata('oliveSchoolDashboardUser')

			);

			$status = $this->Student_model->update_books_and_result_data($update_books, $student_id);

			if ($status) {

				$this->session->set_flashdata('message', "Data Updated Successfully !!!");

				$this->session->set_flashdata('alert_class', "alert-success");

				redirect(base_url() . 'books-and-result');
			} else {

				$this->session->set_flashdata('message', "Failed to Update Data !!!");

				$this->session->set_flashdata('alert_class', "alert-danger");

				redirect(base_url() . 'books-and-result');
			}
		} else {

			$add_books = array(

				'STVBOOK_student_id' => $student_id,

				'STVBOOK_standard' => $this->input->post('student_standard'),

				'STVBOOK_result' => $this->input->post('result_given'),

				'STVBOOK_books' => $this->input->post('books_given'),

				'STVBOOK_teacher_name' => $this->input->post('teacher_name'),

				'STVBOOK_paid_amonut' => $this->input->post('amount_paid'),

				'STVBOOK_bal_amount' => $this->input->post('balance_amt'),

				'STVBOOK_paid_date' => date('Y-m-d'),

				'STVBOOK_due_date' => $this->input->post('due_date'),

				'STVBOOK_details' => $this->input->post('details'),

				'STVBOOK_area' => $this->input->post('area'),

				'STVBOOK_user_id' => $this->session->userdata('oliveSchoolDashboardUser')

			);

			$status = $this->Student_model->add_books_and_result_data($add_books);

			if ($status) {

				$this->session->set_flashdata('message', "Data Added Successfully !!!");

				$this->session->set_flashdata('alert_class', "alert-success");

				redirect(base_url() . 'books-and-result');
			} else {

				$this->session->set_flashdata('message', "Failed to Add Data !!!");

				$this->session->set_flashdata('alert_class', "alert-danger");

				redirect(base_url() . 'books-and-result');
			}
		}
	}



	//Function to Get Fees Details

	function get_fees_data_of_student()
	{

		$student_id = $this->input->post('student_id');

		$student_FeesData = $this->Student_model->get_fees_data_of_student($student_id);

		$student_info = $this->Student_model->get_search_from_spofees($student_id);

		$data['info'] = $student_info;

		$data['fees'] = $student_FeesData;

		echo json_encode($data);
	}



	//Add Fees Data 

	function add_fees_data()
	{

		$acdm = $this->Student_model->getCurrentAcademicYear();

		$add_fees = array(

			'sprfees_studentid' => $this->input->post('student_id_hidden'),

			'sprfees_standard' => $this->input->post('student_standard'),

			'sprfees_amount' => $this->input->post('amount_paid'),

			// 'sprfees_feesdate' => date('Y-m-d'),
			// update by kamran 21-08-26
			'sprfees_feesdate' => $this->input->post('sprfees_feesdate'),

			'sprfees_timestamp' => date('d-M-Y h:i:s'),

			'sprfees_recivedby' => $this->session->userdata('oliveSchoolDashboardUser'),

			'sprfees_notes' => $this->input->post('details'),

			'sprfees_due_date' => $this->input->post('due_date'),

			'sprfees_status' => "Unpaid",

			'academic_year' => $this->input->post('academic_year_hidden'),

			'sprfees_source' => "Fees Form",

			'sprfees_exumption' => $this->input->post('con_amnt'),

			'sprfees_ex_notes' => $this->input->post('con_note'),

			'payment_mode' => $this->input->post('payment_mode')

		);



		$status = $this->Student_model->add_fees_data($add_fees);

		if ($status) {

			$this->session->set_flashdata('message', "Data Added Successfully !!!");

			$this->session->set_flashdata('alert_class', "alert-success");

			redirect(base_url() . 'fees');
		} else {

			$this->session->set_flashdata('message', "Failed to Add Data !!!");

			$this->session->set_flashdata('alert_class', "alert-danger");

			redirect(base_url() . 'fees');
		}
	}



	//Add Class New

	function createNewClass()
	{

		$std = $this->input->post('std');

		$division = $this->input->post('division');

		$post_student_id = $this->input->post('student_ids');

		$status = $this->Student_model->addNewClass($std, $division, $post_student_id);

		sleep(2);

		if ($status) {

			echo "success";
		} else {

			echo "fail";
		}
	}



	//Get Data for Attendance

	function getStudentForAttendanceByAll()
	{

		$data = $this->Student_model->getDataForClass();

		if ($data != null) {

			echo json_encode($data);
		} else {

			echo json_encode("fail");
		}
	}



	//fill Attendance Student

	function fillStudentsAttendance()
	{

		$std = $this->input->post('std');

		$division = $this->input->post('division');

		$dateofatte = $this->input->post('dateOfAtt');

		$post_student_id = $this->input->post('student_ids');

		$post_att_status = $this->input->post('attendanceStatus');

		$status = $this->Student_model->fillStudentsAttendance($dateofatte, $std, $division, $post_student_id, $post_att_status);

		sleep(1);

		if ($status) {

			echo "success";
		} else {

			echo "fail";
		}
	}



	//Functions to create Communication Master

	function add_communication_dt()
	{

		$data = array(

			'stvpcnf_student_id' => $this->input->post('student_id_hidden'),

			'stvpcnf_parent_typ' => $this->input->post('parent'),

			'stvpcnf_mobile' => $this->input->post('mobileno'),

			'stvpcnf_mobile2' => $this->input->post('mobileno2'),

			'stvpcnf_mobile3' => $this->input->post('mobileno3'),

			'stvpncf_sms_no' => $this->input->post('sms_no'),

			'stvpncf_emergency_no' => $this->input->post('emergency_no'),

			'stvpcnf_whatsapp' => $this->input->post('wha_no'),

			'stvpcnf_facebook' => $this->input->post('facebook'),

			'stvpcnf_twitter' => $this->input->post('twitter'),

			'stvpcnf_gmail' => $this->input->post('gmail'),

			'stvpncf_added_by' => $this->session->userdata('oliveSchoolDashboardUser'),

			'stvpncf_date_added' => date('Y-m-d')

		);

		$status = $this->Student_model->add_communication_dt($data);

		sleep(1);

		if ($status) {

			echo "success";
		} else {

			echo "fail";
		}
	}



	function add_visiting_data()
	{



		//Check avaialable

		$row_id = $this->input->post('student_row_hidden');

		$if_exist = $this->Student_model->checkIfExistStudentData($row_id);

		if ($if_exist > 0) {

			$data = array(

				'visiting_student_id' => $this->input->post('student_id_hidden'),

				'visiting_student_class' => $this->input->post('admission_class'),

				'visiting_student_fees_paid' => $this->input->post('amount'),

				'visiting_date' => date('Y-m-d'),

				'visiting_fees_status_of' => $this->input->post('fees_status'),

				'visiting_fees_due_date' => $this->input->post('due_date'),

				'visiting_book_fees' => $this->input->post('book_fee_due'),

				'visiting_added_by' => $this->session->userdata('oliveSchoolDashboardUser')

			);

			//add teacher assigned data into database

			$status = $this->Student_model->update_student_data($data, $row_id);

			if ($status) {

				$this->session->set_flashdata('message', "Data Updated Successfully !!!");

				$this->session->set_flashdata('alert_class', "alert-success");

				redirect(base_url() . 'visiting');
			} else {

				$this->session->set_flashdata('message', "Failed to Update Data !!!");

				$this->session->set_flashdata('alert_class', "alert-danger");

				redirect(base_url() . 'visiting');
			}
		} else {

			$data = array(

				'visiting_student_id' => $this->input->post('student_id_hidden'),

				'visiting_student_class' => $this->input->post('admission_class'),

				'visiting_student_fees_paid' => $this->input->post('amount'),

				'visiting_date' => date('Y-m-d'),

				'visiting_fees_status_of' => $this->input->post('fees_status'),

				'visiting_fees_due_date' => $this->input->post('due_date'),

				'visiting_book_fees' => $this->input->post('book_fee_due'),

				'visiting_added_by' => $this->session->userdata('oliveSchoolDashboardUser')

			);

			//add teacher assigned data into database

			$status = $this->Student_model->add_visiting_data($data);

			if ($status) {

				$this->session->set_flashdata('message', "Data Added Successfully ");

				$this->session->set_flashdata('alert_class', "alert-success");

				redirect(base_url() . 'visiting');
			} else {

				$this->session->set_flashdata('message', "Failed to Add Data !!!");

				$this->session->set_flashdata('alert_class', "alert-danger");

				redirect(base_url() . 'visiting');
			}
		}
	}

	function getStudentDataVisitingByID()
	{

		$data = $this->Student_model->getStudentDataVisitingByID();

		echo json_encode($data);
	}



	function detect()
	{

		$whitelist = array(

			'127.0.0.1',

			'::1'

		);



		if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {

			echo "Online";
		} else {

			echo "Not Online";
		}
	}





	//==================================== Attendance Details

	//class wise

	function getClassWiseAttendanceDetails()
	{

		echo json_encode($this->Student_model->getClassWiseAttendanceDetails());
	}



	//student names lists

	function getStudentNamesForAttendanceDetails()
	{

		echo json_encode($this->Student_model->getStudentNamesForAttendanceDetails());
	}



	//student names lists

	function getStudentNamesForAttendanceByStad()
	{

		echo json_encode($this->Student_model->getStudentNamesForAttendanceByStad());
	}



	//get student attendance details

	function getStudentAttendanceByID()
	{

		$present = 0;

		$absent = 0;

		$data['attendance_details'] = $this->Student_model->get_attendance_data($this->input->post('student_id'));

		// Attendance P And A Days

		for ($i = 0; $i < count($data['attendance_details']); $i++) {

			if ($data['attendance_details'][$i]['SPRATND_status'] == "P") {

				$present++;
			} else {

				$absent++;
			}
		}

		$data['present_days'] = $present;

		$data['absent_days'] = $absent;

		$data['personal'] = $this->Student_model->get_personal_details($this->input->post('student_id'));

		echo json_encode($data);
	}



	//display all std attendance

	function display_all_std_att_by_day()
	{

		echo json_encode($this->Student_model->display_all_std_att_by_day($this->input->post('get_date')));
	}







	//======================= HOLD Data ================================

	//add hold data

	function add_student_hold_data()
	{

		$data = array(

			'sprhold_hold_id' => $this->input->post('hold_type'),

			'sprhold_student_id' => $this->input->post('student_id_hidden'),

			'sprhold_from' => $this->input->post('hold_from'),

			'sprhold_to' => $this->input->post('hold_to'),

			'sprhold_date' => date('Y-m-d'),

			'sprhold_status' => "Yes",

			'sprhold_added_by' => $this->session->userdata('oliveSchoolDashboardUser')

		);

		//add teacher assigned data into database

		$status = $this->Student_model->add_student_hold_data($data);

		if ($status) {

			$this->session->set_flashdata('message', "Data Added Successfully ");

			$this->session->set_flashdata('alert_class', "alert-success");

			redirect(base_url() . 'sprhold');
		} else {

			$this->session->set_flashdata('message', "Failed to Add Data !!!");

			$this->session->set_flashdata('alert_class', "alert-danger");

			redirect(base_url() . 'sprhold');
		}
	}



	function filterVisitingData()
	{

		echo json_encode($this->Student_model->filterVisitingData());
	}





	//Update Student Class

	function updateStudentClass()
	{

		$status = $this->Student_model->updateStudentClass();

		sleep(1);

		if ($status) {

			echo "success";
		} else {

			echo "fail";
		}
	}





	//+++++++++++++ Visting data for update

	function getVisitingDataForUpdate()
	{

		echo json_encode($this->Student_model->getVisitingDataForUpdate());
	}



	function update_visiting_data()
	{

		$id = $this->input->post('id');

		$data = $this->input->post('data');

		$this->db->where('visiting_student_id', $id);

		$status = $this->db->update('visiting', $data);

		if ($status) {

			return true;
		} else {

			return false;
		}
	}



	function deleteVisitingDataByID()
	{

		$status = $this->Student_model->deleteVisitingDataByID();

		if ($status) {

			echo "success";
		} else {

			echo "fail";
		}
	}



	// -------------   NEW ADMISSION FORM --------------------------------------------------



	function add_student_new_data()
	{

		$acdm_year_data = $this->Student_model->getCurrentAcademicYear();

		if ($this->input->post('gender') == "Male") {

			$div = "A";
		} else {

			$div = "B";
		}

		$doc_array = array();

		$doc_array[0] = $this->input->post('main_docs') != '' ? $this->input->post('main_docs') : 0;

		$doc_array[1] = $this->input->post('student_aadhar') != '' ? $this->input->post('student_aadhar') : 0;

		$doc_array[2] = $this->input->post('father_aadhar') != '' ? $this->input->post('father_aadhar') : 0;

		$doc_array[3] = $this->input->post('passport_photo') != '' ? $this->input->post('passport_photo') : 0;

		$doc_str = implode("-", $doc_array);



		$adm_cls = $this->input->post('admission_class');

		$config['upload_path'] = './uploads/students/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
		$config['max_size'] = 2048;
		$config['encrypt_name'] = TRUE; // To avoid name collisions

		$this->load->library('upload', $config);
		$student_image_file = '';
		if (!$this->upload->do_upload('userfile')) {
			$student_image_file = '';
		} else {
			$upload_data = $this->upload->data();
			$student_image_file = $upload_data['file_name'];
		}


		$cm_height = '';

		// Step 1: Trim any extra spaces
		$input_height = trim($this->input->post('height'));

		// Step 2: Remove any non-numeric characters, except the dot (.)
		$input_height = preg_replace('/[^0-9.]/', '', $input_height);

		// Step 3: Validate the format (must contain exactly one dot)
		if (substr_count($input_height, '.') == 1) {
			// Split the input into feet and inches
			list($feet, $inches) = explode('.', $input_height);

			// Step 4: Convert to integers
			$feet = (int) $feet;
			$inches = (int) $inches;

			// Step 5: Calculate total inches and convert to cm
			$total_inches = ($feet * 12) + $inches;
			$cm_height = $total_inches * 2.54;
		}

		$data = array(

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

			'SPRIDEN_admission_class' => $this->input->post('admission_class'),

			'SPRIDEN_last_school' => $this->input->post('last_school'),

			'SPRIDEN_main_address' => $this->input->post('main_address'),

			'SPRIDEN_documents' => $doc_str,

			'SPRIDEN_user_type' => "st",

			'SPRIDEN_date_created' => date('Y-m-d'),

			'spriden_admission_year' => date('Y'),

			'spriden_student_status' => "A",

			'cast' => $this->input->post('caste'),

			'sub_cast' => $this->input->post('sub_caste'),

			'taluka' => $this->input->post('taluka'),

			'district' => $this->input->post('district'),

			'state' => $this->input->post('state'),

			'rte_status' => $this->input->post('rte_student'),

			'spriden_admission_notes' => $this->input->post('admission_note'),
			'admsn_acdm_year' => $this->input->post('acdm_year'),

			// Updated columns 
			'height' => $this->input->post('height'),
			'height_in_cm' => $cm_height,
			'weight' => $this->input->post('weight'),
			'student_name_aadhar' => $this->input->post('student_name_as_per_aadhar'),
			'father_aadhar_no' => $this->input->post('father_aadhar_number'),
			'father_name_aadhar' => $this->input->post('father_name_as_per_aadhar'),
			'mother_name_aadhar' => $this->input->post('mother_name_as_per_aadhar'),
			'mother_aadhar_no' => $this->input->post('mother_aadhar_no'),
			'SPRIDEN_pan_no' => $this->input->post('SPRIDEN_pan_no'),
			'SPRIDEN_apaar_no' => $this->input->post('SPRIDEN_apaar_no'),
			'address_landmark' => $this->input->post('address_landmark'),
			'near_by_mosque' => $this->input->post('nearby_masjid'),
			'student_profile_photo' => $student_image_file,


		);

		//save user data into database

		$this->Student_model->register_student1($data, $acdm_year_data, $div, $adm_cls);
	}



	function admission_form()
	{

		if ($this->uri->segment(3)) {

			$student_id = $this->uri->segment(3);

			$data['student_details'] = $this->Student_model->get_spriden_data($student_id);
			$data['academic'] = [];
			if (isset($data['student_details']['admsn_acdm_year']))
				$data['academic'] = $this->db->where('stvacdm_id', $data['student_details']['admsn_acdm_year'])->get('stvacdm')->row_array();
			$data['contact'] = $this->Student_model->getContactDetails($student_id);

			$this->load->view('Student/student-form', $data);
		}
	}



	// -------- CHANGES MADE IN FEES FORM ------------------------------

	function findStudentNameForFees()
	{
		echo json_encode($this->Student_model->findStudentNameForFees($this->input->post('name'), $this->input->post('academic_year'), $this->input->post('include_flag')));
	}



	//Function to Get Fees Details

	// function all_fees_details_of_student()

	// {

	// 	$student_id = $this->input->post('student_id');
	// 	$current_academic_year = $this->input->post('academic_year');

	// 	$student_FeesData = $this->Student_model->get_fees_data_of_student($student_id, $this->input->post('academic_year'));
	// 	$all_fees_data = $this->Student_model->get_all_fees_data_of_student($student_id);

	// 	$student_info = $this->Student_model->student_details_to_display_for_fees($student_id, $this->input->post('academic_year'));





	// 	$data['info'] = $student_info;

	// 	$data['fees'] = $student_FeesData;
	// 	$data['all_fees'] = $all_fees_data;

	// 	$ex_amt = 0;
	// 	foreach ($student_FeesData as $fees_data) {
	// 		$ex_amt += (int)$fees_data['sprfees_exumption'];
	// 	}


	// 	$data['fees_details'] = $this->Student_model->student_current_fees_structure($student_info['STVCLAS_standard'], $current_academic_year);

	// 	$this_year_balance = $this->Student_model->student_this_year_balance($current_academic_year, $student_info['id']);

	// 	$total_to_minus = (float)$this_year_balance['total'] + $ex_amt;

	// 	$data['total_balance'] = $data['fees_details']['stvfstr_fees'] - $total_to_minus;

	// 	$data['card_status'] =  $this->Student_model->student_fees_card_status($student_id);

	// 	echo json_encode($data);
	// }

	function all_fees_details_of_student()
	{



		$student_id = $this->input->post('student_id');
		$current_academic_year = $this->input->post('academic_year');



		$student_FeesData = $this->Student_model->get_fees_data_of_student($student_id, $this->input->post('academic_year'));
		$all_fees_data = $this->Student_model->get_all_fees_data_of_student($student_id);

		$student_info = $this->Student_model->student_details_to_display_for_fees($student_id, $this->input->post('academic_year'));
		//$current_academic_year = $this->Student_model->getCurrentAcademicYear();
		$data['info'] = $student_info;
		$data['fees'] = $student_FeesData;
		$data['all_fees'] = $all_fees_data;
		$data['contact_details'] = $this->db->query("select stvpcnf_mobile as mobile, stvpcnf_whatsapp as whatsapp from stvpcnf where stvpcnf_parent_typ = 'Father' AND stvpcnf_student_id = $student_id")->row_array();

		$ex_amt = 0;
		foreach ($student_FeesData as $fees_data) {
			$ex_amt += (int) $fees_data['sprfees_exumption'];
		}

		$data['fees_details'] = $this->Student_model->student_current_fees_structure($student_info['STVCLAS_standard'], $current_academic_year);

		$this_year_balance = $this->Student_model->student_this_year_balance($current_academic_year, $student_info['id']);

		$total_to_minus = (float) $this_year_balance['total'] + $ex_amt;

		$data['total_balance'] = $data['fees_details']['stvfstr_fees'] - $total_to_minus;

		$data['card_status'] = $this->Student_model->student_fees_card_status($student_id);

		$data['prev_balances'] = $this->Student_model->get_balance_info($student_id);

		echo json_encode($data);
	}

	function updateCardIssueStatus()
	{

		$this->Student_model->updateCardIssueStatus($this->input->post('student_id'), $this->input->post('status'));
	}



	// Changes made from here -------------------------------- 11-Aug-2021----------------



	function view_records()
	{

		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;

		$data['user'] = $userdetail;

		if ($this->uri->segment(3)) {

			$student_id = $this->uri->segment(3);

			$data['details'] = $this->Student_model->recordsForFeesDetails($student_id);

			$this->load->view('student/view-student-fees-records', $data);
		}
	}



	// Changes made to here -------------------------------- 11-Aug-2021------------------



	// Changes made from here -------------------------------- 14-Aug-2021----------------



	function view_student_details()
	{

		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;

		$data['user'] = $userdetail;

		if ($this->uri->segment(3)) {

			$student_id = $this->uri->segment(3);

			$data['fee_details'] = $this->Student_model->recordsForFeesDetails($student_id);

			$data['personal_details'] = $this->StudentDetails_model->getPersonalDetails($student_id);

			$data['class_details'] = $this->StudentDetails_model->getClassDetails($student_id);

			$data['fees_records'] = $this->StudentDetails_model->fees_records($student_id);

			$data['balance_fees'] = $this->StudentDetails_model->balance_fees($student_id);

			$this->load->view('student/view-student-all-details', $data);



			// echo "<pre>";

			// print_r($data);

		}
	}

	// Changes made to here -------------------------------- 14-Aug-2021------------------





	// ======================= gr wise data 24-feb-2022 ====================================





	function grWiseData()
	{

		$this->load->model('StudentUpdate_model');

		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;

		$data['user'] = $userdetail;

		$data['students'] = $this->StudentUpdate_model->student_list();

		$this->load->view('student/gr_wise_list', $data);
	}



	//======================== end of gr wise code ======================================





	// Changes made from here -------------------------------- 16-Aug-2021----------------

	function student_list()
	{
		$this->load->model('MasterForm_model');
		$this->load->model('StudentUpdate_model');
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
		$curr_acdm = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		$data['current_acdm'] = $this->input->get('acdm-year') ? $this->input->get('acdm-year') : $curr_acdm['stvschool_acdm_year'];
		$data['current_status'] = $this->input->get('status') ? $this->input->get('status') : "A";
		$data['selected_tab'] = $this->input->get('tab') ? $this->input->get('tab') : "tabid-1"; //Update 04-09-24
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;
		// $data['students'] = [];
		// $data['students_jc'] = [];
		$data['user'] = $userdetail;
		// $type = $this->input->get('type');
		// if ($type == 'ssc') {
		$data['students'] = $this->StudentUpdate_model->student_list_nursery_to_ssc($data['current_acdm'], $data['current_status']);
		// } else {
		$data['students_jc'] = $this->StudentUpdate_model->student_list_jc($data['current_acdm'], $data['current_status']);
		// }
		$data['all_status'] = $this->db->query("select distinct (case when spriden_student_status is null then 'IS_NULL' else spriden_student_status end)  spriden_student_status from spriden")->result_array();
		$data['filter_view'] = 'update_students';
		$this->load->view('Student/student_list', $data);
	}




	// Updated 12 Jun 24 - Kamran Ansari
	function update_details()
	{

		if ($this->uri->segment(3)) {
			$selected_acdm = $this->input->get("acdm-year");
			$student_id = $this->uri->segment(3);

			$this->load->model('StudentUpdate_model');

			$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

			$lastLoginData = $this->Stats_model->last_login();

			$data['lastLoginData'] = $lastLoginData;

			$data['user'] = $userdetail;
			//<!-- Kamran -->
			$data['acdm_year'] = $selected_acdm;
			//<!-- Kamran -->
			$data['personal_details'] = $this->StudentDetails_model->getPersonalDetails($student_id);

			$data['class_details'] = $this->StudentDetails_model->getClassDetails($student_id, $selected_acdm);

			$data['admclass'] = $this->StudentDetails_model->admission_class($student_id);

			$data['contact_details'] = $this->StudentDetails_model->getContactDetails($student_id);

			$data['classes'] = $this->Stats_model->getAllClasses();
			//<!-- Kamran -->
			$data['record'] = $this->Report_model->student_report_to_remark_lc($student_id, $data['acdm_year'] = $selected_acdm);

			$data['student_std'] = $data['record']['current_std'];

			if ($data['student_std'] == 'XI' || $data['student_std'] == 'XII') {
				$data['record'] = $this->Report_model->student_report_to_remark_lc_jc($student_id, $data['acdm_year'] = $selected_acdm);
			} else {
				$data['record'] = $this->Report_model->student_report_to_remark_lc($student_id, $data['acdm_year'] = $selected_acdm);
			}

			$data['lc_no'] = $this->Report_model->generate_lc_no($data['student_std']);
			//<!-- Kamran -->
			// print_r($data['classes']);die;


			// print_r($data['personal_details']);

			if ($data['personal_details'] == null) {

				$this->load->view('Student/no-data-found', $data);
			} else {

				$this->load->view('Student/update-student', $data);
			}
		}
	}

	function update_student_details_by_form()
	{
		$this->load->model('StudentUpdate_model');
		$id = $this->input->post('hidden_student_id');
		$acdm = $this->input->post('hidden_acdm_year');
		$status = $this->StudentUpdate_model->update_student_details_by_form($id);
		if ($status) {
			$this->session->set_flashdata('message', "Student Information Updated Successfully");
			$this->session->set_flashdata('alert_class', "alert-success");
			redirect(base_url('Student/update_details/' . $id . '?acdm-year=' . $acdm));
		} else {
			$this->session->set_flashdata('message', "Failed to Update");
			$this->session->set_flashdata('alert_class', "alert-danger");
			redirect(base_url('Student/update_details/' . $id . '?acdm-year=' . $acdm));
		}
	}

	// Updated 30 Sep 24 - Azz Shaikh
	function changeStudentStatus()
	{
		$this->load->model('StudentUpdate_model');
		if (strtolower($this->input->method()) == 'post') {
			$id = $this->input->post('id');
			$status = $this->input->post('status');
			$reason = $this->input->post('reason');
		} else {
			$id = $this->input->get('id');
			$status = $this->input->get('status');
			$reason = $this->input->get('reason');
		}
		echo json_encode($this->StudentUpdate_model->changeStudentStatus($id, $status, $reason));
	}



	function StudentUniqueId()
	{

		$this->load->model('StudentUpdate_model');

		echo json_encode($this->StudentUpdate_model->StudentUniqueId($this->input->get('id')));
	}

	// Changes made to here -------------------------------- 16-Aug-2021------------------



	function student_list_by_class()
	{

		echo json_encode($this->Student_model->student_list_by_class());
	}



	function add_rte()
	{

		$status = $this->Student_model->add_rte();

		$this->session->set_flashdata('msg', $status);

		redirect(base_url('welcome/rte_students'));
	}





	// -------------------- Student Contact Details Page --------------------------------



	function student_contact()
	{

		$data['classes'] = $this->Stats_model->getAllClasses();

		$this->load->view('Student/contact-details', $data);
	}

	// -------------------- Student Contact Details Page --------------------------------



	function readmission()
	{

		if ($this->session->userdata('oliveSchoolDashboardUser')) {

			$this->load->model('Stats_model');

			$this->load->model('MasterForm_model');

			$this->load->model('StudentDetails_model');

			$classes = $this->Stats_model->getAllClasses();

			$data['classes'] = $classes;

			$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

			$data['academic_details'] = $this->Stats_model->getAcademicDetails();

			$data['all_students'] = $this->StudentDetails_model->studentListAll();

			$this->load->view('Student/readmission', $data);
		} else {

			redirect(base_url('login'));
		}
	}





	function fetchReadmissionDetails()
	{

		$this->load->model('Stats_model');

		$this->load->model('MasterForm_model');

		$this->load->model('StudentDetails_model');
		$source = $this->input->get('source');

		$id = $this->input->get('student-id');
		$academic_year = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		$academic_year = $academic_year['stvschool_acdm_year'];
		$acdm_year = $source == 'db' ? $academic_year : $this->input->get('acdm-year');

		$data['student_details'] = $this->Student_model->get_spriden_data($id);

		$data['class_details'] = $this->Student_model->class_details($id, $acdm_year);

		$data['fees_details'] = $this->Student_model->fees_details_prev($id);

		echo json_encode($data);
	}

	function addReadmission()
	{

		$status = $this->Student_model->insertReAdmission();

		if ($status) {

			echo json_encode("Success!!");
		} else {

			echo json_encode("fail");
		}
	}



	// tailor details

	function tailor_details()
	{

		//Get User Details

		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		//Get All Students from DB

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;

		$data['user'] = $userdetail;

		$data['classes'] = $this->Stats_model->getAllClasses();
		$data['filter_view'] = 'tailor_details';
		$this->load->view('Student/tailor-details', $data);
	}

	function tailor_details_api()
	{

		echo json_encode($this->Student_model->tailor_details_api($this->input->get('std'), $this->input->get('div')));
	}



	function add_tailor_dt()
	{

		$this->Student_model->add_tailor_dt();
	}

	// student gr details

	function student_grlist()
	{

		//Get User Details

		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		//Get All Students from DB

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;

		$data['user'] = $userdetail;

		$data['all_records'] = $this->Student_model->all_gr_numbers();

		$this->load->view('Student/student-gr', $data);
	}

	// student comments by sir

	// tailor details

	function student_comments()
	{

		//Get User Details

		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		//Get All Students from DB

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;

		$data['user'] = $userdetail;

		$data['classes'] = $this->Stats_model->getAllClasses();
		$data['filter_view'] = 'student_comments';
		$this->load->view('Student/student-comments', $data);
	}

	function student_comments_api()
	{

		echo json_encode($this->Student_model->student_comments_api($this->input->get('std'), $this->input->get('mode'), $this->input->get('acdm-year')));
	}



	function add_stu_cmnt_dt()
	{

		$this->Student_model->add_stu_cmnt_dt();
	}

	function add_stu_books_data()
	{

		$this->Student_model->add_stu_books_data();
	}



	/// boooks 

	function books()
	{

		$this->load->model('MasterForm_model');

		//Get User Details

		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		//Get All Students from DB

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;

		$data['user'] = $userdetail;

		$data['classes'] = $this->Stats_model->getAllClasses();

		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		$data['academic_details'] = $this->Stats_model->getAcademicDetails();
		$data['filter_view'] = "books";
		$this->load->view('Student/student-books', $data);
	}



	// student gr details

	public function student_gr_contact()
	{
		$data['user'] = $this->Stats_model->getUserDetails(
			$this->session->userdata('oliveSchoolDashboardUser')
		);

		$data['lastLoginData'] = $this->Stats_model->last_login();

		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
		$data['classes'] = $this->Stats_model->getAllClasses();

		$data['selected_class'] = $this->uri->segment(3);
		$data['selected_div'] = $this->uri->segment(4);
		$data['selected_year'] = $this->uri->segment(5);

		$data['all_records'] = [];

		if (!empty($data['selected_class'])) {

			$data['all_records'] = $this->Student_model->student_gr_contact(
				$data['selected_class'],
				$data['selected_div'],
				$data['selected_year']
			);
		}

		$this->load->view('Student/student-gr-contact', $data);
	}

	function remarks()
	{

		$this->load->model('MasterForm_model');

		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		//Get Books & Result Data with Joins

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;

		$data['user'] = $userdetail;

		$data['classes'] = $this->Stats_model->getAllClasses();

		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		$data['academic_details'] = $this->Stats_model->getAcademicDetails();

		$data['students_remarks'] = array();

		if ($this->uri->total_segments() == 4) {

			$data['students_remarks'] = $this->Student_model->get_remarks($this->uri->segment(3), $this->uri->segment(4));
		}
		$data['filter_view'] = "remarks_records";
		$this->load->view('Teacher/remarks_records', $data);
	}

	// Student I Card  System

	function i_card()
	{

		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		//Get All Login from DB

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;

		$data['user'] = $userdetail;

		$classes = $this->Stats_model->getAllClasses();

		$data['classes'] = $classes;

		$data['list'] = array();

		$data['std_selected'] = '0';

		$data['div_selected'] = '0';

		if ($this->uri->total_segments() >= 3) {

			$data['list'] = $this->Student_model->student_icard_by_class($this->uri->segment(3), $this->uri->segment(4));

			$data['std_selected'] = $this->uri->segment(3);

			$data['div_selected'] = $this->uri->segment(4);
		}
		$data['filter_view'] = "student_icard";
		$this->load->view("Student/student_i-card", $data);
	}

	function student_icard_dt()
	{

		echo json_encode($this->Student_model->student_icard_by_class_dt($this->input->get('id')));
	}

	function add_image()
	{

		$uploadPath = './stu_img_icard';

		$config['upload_path'] = $uploadPath;

		$config['allowed_types'] = 'jpg|jpeg|png|gif|jfif|webp';



		//load and initialize upload library 

		$this->load->library('upload', $config);

		//=============================UPLOAD CODE ==============================

		if (!empty($_FILES['files']['name']) && count(array_filter($_FILES['files']['name'])) > 0) {

			$filesCount = count($_FILES['files']['name']);

			for ($i = 0; $i < $filesCount; $i++) {

				$_FILES['file']['name'] = $_FILES['files']['name'][$i];

				$_FILES['file']['type'] = $_FILES['files']['type'][$i];

				$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];

				$_FILES['file']['error'] = $_FILES['files']['error'][$i];

				$_FILES['file']['size'] = $_FILES['files']['size'][$i];

				$this->upload->initialize($config);

				// upload file to server

				if ($this->upload->do_upload('file')) {

					$fileData = $this->upload->data();

					$uploadData[$i] = $fileData['file_name'];
				} else {

					$filePathStr = null;
				}
			}

			$errorUploadType = !empty($errorUploadType) ? '<br>File Type Error:' . trim($errorUploadType, ' | ') : '';

			if (!empty($uploadData)) {

				// Insert files Data into database

				$filePathStr = $uploadData;
			} else {

				//error uploading

				$filePathStr = null;
			}
		} else {

			//error uploading

			$filePathStr = null;
		}

		$status = $this->Student_model->add_image($filePathStr, $this->input->post('stud_id'));

		$std = $this->input->post('admission_class');

		$div = $this->input->post('division');

		if ($status) {

			$this->session->set_flashdata('msg', "Image Uploaded Successfully...!");

			$this->session->set_flashdata('class', "alert alert-success");
		} else {

			$this->session->set_flashdata('msg', "Failed To Upload Image ...!");

			$this->session->set_flashdata('class', "alert alert-danger");
		}

		redirect(base_url() . 'student/i_card/' . $std . '/' . $div);
	}



	function student_card()
	{

		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		//Get All Login from DB

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;

		$data['user'] = $userdetail;

		$classes = $this->Stats_model->getAllClasses();

		$data['classes'] = $classes;

		$data['list'] = array();

		$data['std_selected'] = 0;

		$data['div_selected'] = 0;

		if ($this->uri->total_segments() >= 3) {

			$data['list'] = $this->Student_model->stud_image($this->uri->segment(3), $this->uri->segment(4));

			$data['std_selected'] = $this->uri->segment(3);

			$data['div_selected'] = $this->uri->segment(4);
		}
		$data['filter_view'] = "Student_card";
		$this->load->view('Student/student_card', $data);
	}

	function icard()
	{

		$header_id = $this->uri->segment(3);

		$data['list'] = $this->Student_model->stud_image($this->uri->segment(3), $this->uri->segment(4));

		$html = $this->load->view('Student/i-card_pdf', $data, true);

		require_once __DIR__ . '/vendor/autoload.php';

		$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'margin_left' => 5, 'margin_right' => 5, 'margin_top' => 5, 'margin_bottom' => 5, 'margin_header' => 5, 'margin_footer' => 5]);

		$mpdf->autoScriptToLang = true;

		$mpdf->autoLangToFont = true;

		$mpdf->SetHTMLFooter();

		$mpdf->SetProtection(array('print'));

		$mpdf->WriteHTML($html);

		$file_name = "Student_ICard" . $header_id . "-" . time();

		$mpdf->Output($file_name . '.pdf', "I");
	}

	function select_card()
	{



		$list = $this->input->post('lists');

		$std = $this->input->post('std');

		$div = $this->input->post('div');

		$header_id = $this->uri->segment(3);

		$data['list'] = $this->Student_model->select_card($std, $div, $list, 'selected');

		$html = $this->load->view('Student/i-card_pdf', $data, true);

		require_once __DIR__ . '/vendor/autoload.php';

		$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'margin_left' => 5, 'margin_right' => 5, 'margin_top' => 5, 'margin_bottom' => 5, 'margin_header' => 5, 'margin_footer' => 5]);

		$mpdf->autoScriptToLang = true;

		$mpdf->autoLangToFont = true;

		$mpdf->SetHTMLFooter();

		$mpdf->SetProtection(array('print'));

		$mpdf->WriteHTML($html);

		$file_name = "Student_ICard" . $header_id . "-" . time();

		$mpdf->Output("student_icard_pdf/" . $file_name . '.pdf', "F");

		echo base_url('student_icard_pdf/' . $file_name . '.pdf');
	}


	function feesreceipt($id)
	{
		$data['profile'] = $this->Student_model->fetchStudentDetails($id);
		$data['fees_data'] = $this->Student_model->fetchFeesDetails($id);
		//$this->load->view('Student/fees_receipt', $data);
		$html = $this->load->view('Student/fees_receipt', $data, true);
		require_once __DIR__ . '/vendor/autoload.php';
		$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'margin_left' => 5, 'margin_right' => 5, 'margin_top' => 5, 'margin_bottom' => 5, 'margin_header' => 5, 'margin_footer' => 5]);
		$mpdf->autoScriptToLang = true;
		$mpdf->autoLangToFont = true;
		$mpdf->SetHTMLFooter();
		$mpdf->SetProtection(array('print'));
		$mpdf->WriteHTML($html);
		$file_name = "Fees_Recipt" . "-" . time();
		$mpdf->Output("student_icard_pdf/" . $file_name . '.pdf', "I");
	}

	// ============================== NEW TEMPLATE FUNCTIONS - Added  by Aasif Mirza ===================================

	public function Profile()
	{
		$data['view_path'] = 'pages/Student/profile';
		$data['page_title'] = 'Student Profile';
		$data['entity'] = ['Olive', 'English School & Jr. College'];
		$data['entity_logo'] = base_url() . '/images/logoNewDev.png';
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$data['user'] = $userdetail;
		$data['records'] = $this->StudentProfile_model->getStudentList();
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
		$data['max_acdm'] = $this->StudentProfile_model->getMaxAcdm();
		$this->load->view('template', $data);
	}

	function getDetails()
	{
		$id = $this->input->post('id');
		$acdm = $this->input->post('acdm');
		echo json_encode($this->StudentProfile_model->getStudentData($id, $acdm));
	}

	function getQuizDetails()
	{
		$id = $this->input->post('id');
		$acdm = $this->input->post('acdm');
		echo json_encode($this->StudentProfile_model->getQuizData($id, $acdm));
	}

	function getExamDetails()
	{
		$id = $this->input->post('id');
		$acdm = $this->input->post('acdm');
		echo json_encode($this->StudentProfile_model->getExamData($id, $acdm));
	}

	function getRemarkDetails()
	{
		$id = $this->input->post('id');
		$acdm = $this->input->post('acdm');
		echo json_encode($this->StudentProfile_model->getRemarkData($id, $acdm));
	}

	function getAttendanceDetails()
	{
		$id = $this->input->post('id');
		$acdm = $this->input->post('acdm');
		echo json_encode($this->StudentProfile_model->getAttendanceData($id, $acdm));
	}


	// function all_fees_details_of_student()

	// {
	// 	$student_id = $this->input->post('student_id');
	// 	$current_academic_year = $this->input->post('academic_year');

	// 	$student_FeesData = $this->Student_model->get_fees_data_of_student($student_id, $this->input->post('academic_year'));
	// 	$all_fees_data = $this->Student_model->get_all_fees_data_of_student($student_id, $current_academic_year);

	// 	$student_info = $this->Student_model->student_details_to_display_for_fees($student_id, $this->input->post('academic_year'));
	// 	//$current_academic_year = $this->Student_model->getCurrentAcademicYear();
	// 	$data['info'] = $student_info;
	// 	$data['fees'] = $student_FeesData;
	// 	$data['all_fees'] = $all_fees_data;

	// 	$ex_amt = 0;
	// 	foreach ($student_FeesData as $fees_data) {
	// 		$ex_amt += (int)$fees_data['sprfees_exumption'];
	// 	}

	// 	$data['fees_details'] = $this->Student_model->student_current_fees_structure($student_info['STVCLAS_standard'], $current_academic_year);

	// 	$this_year_balance = $this->Student_model->student_this_year_balance($current_academic_year, $student_info['id']);

	// 	$total_to_minus = (float)$this_year_balance['total'] + $ex_amt;

	// 	$data['total_balance'] = $data['fees_details']['stvfstr_fees'] - $total_to_minus;

	// 	$data['card_status'] =  $this->Student_model->student_fees_card_status($student_id);

	// 	$data['prev_balances'] = $this->Student_model->get_balance_info($student_id);

	// 	echo json_encode($data);
	// }


	// ============================================ END =================================================================


	function students()
	{

		$this->load->model('StudentUpdate_model');
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
		$curr_acdm = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		$data['current_acdm'] = $this->input->get('acdm-year') ? $this->input->get('acdm-year') : $curr_acdm['stvschool_acdm_year'];
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$data['classes'] = $this->Stats_model->getAllClasses();
		$data['current_status'] = $this->input->get('status') ? $this->input->get('status') : '';
		$data['current_class'] = $this->input->get('std') ? $this->input->get('std') : '';
		$data['current_div'] = $this->input->get('div') ? $this->input->get('div') : '';

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['students'] = $this->StudentUpdate_model->getStudentDetails($data['current_acdm'], $data['current_status'], $data['current_class'], $data['current_div']);


		$data['all_status'] = $this->db->query("select distinct (case when spriden_student_status is null then 'IS_NULL' else spriden_student_status end)  spriden_student_status from spriden")->result_array();
		$data['filter_view'] = 'student-report';
		$this->load->view('Student/student-report', $data);
	}

	// ------------------- Ansari Kamran ----- 07/07/2025 ------------------------
	// public function feesDetails()
	// {
	// 	$data['user'] = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
	// 	$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
	// 	$this->load->view('Fees/feesDetails', $data);
	// }

	//--------------------Sameer Qazi ------14-9-2026--------------------------

	public function feesDetails()
	{
		$data['user'] = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		$current_academic_year = $this->Stats_model->getCurrentAcademicYear();

		$data['selected_academic_year'] = !empty($current_academic_year)
			? $current_academic_year['stvacdm_id']
			: '';

		$this->load->view('Fees/feesDetails', $data);
	}

	public function getFeedDetails()
	{
		$std = $this->input->post('std');
		$div = $this->input->post('div');
		$acdm = $this->input->post('acdm');

		$result = $this->Student_model->get_student_fee_history($std, $div, $acdm);
		echo json_encode($result);
	}

	public function promotion()
	{
		$data['user'] = $this->Stats_model->getUserDetails(
			$this->session->userdata('oliveSchoolDashboardUser')
		);
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		$current_academic_year = $this->Stats_model->getCurrentAcademicYear();
		$data['selected_academic_year'] = !empty($current_academic_year)
			? $current_academic_year['stvacdm_id']
			: '';

		$data['classes'] = $this->Stats_model->getAllClasses();

		$this->load->view('Student/promotion', $data);
	}

	// load students
	public function getPromotionStudents()
	{
		$year = $this->input->post('year');
		$class = $this->input->post('class');
		$div = $this->input->post('div');
		$students = $this->Student_model->getcurstudents($year, $class, $div);
		echo json_encode($students);
	}

	// promote students
	public function promoteStudents()
	{
		$students = $this->input->post('students');
		$rollnos = $this->input->post('rollnos');
		$std = $this->input->post('std');
		$div = $this->input->post('div');
		$acdm = $this->input->post('acdm');

		$result = $this->Student_model->promoteStudents(
			$students,
			$rollnos,
			$acdm,
			$std,
			$div
		);

		echo json_encode([
			'status' => 'success',
			'message' => 'Promotion Completed Successfully.',
			'promoted' => $result['promoted'],
			'skipped' => $result['skipped'],
			'students' => $result['students']
		]);
	}
}





// For Searching full name from spriden table

/* 

select concat(spriden.SPRIDEN_surname, " " , spriden.SPRIDEN_student_name, " " ,spriden.SPRIDEN_father_name) as Name, stvclas.* from spriden,stvclas where concat(spriden.SPRIDEN_surname, " " , spriden.SPRIDEN_student_name, " " ,spriden.SPRIDEN_father_name) LIKE 'a%' and spriden.SPRIDEN_student_id = stvclas.STVCLAS_student_id

*/

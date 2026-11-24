<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Forms extends CI_Controller
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
		// $this->load->model('College_Form');
		date_default_timezone_set('Asia/Kolkata');
	}

	function jr_college()
	{
		$form_name = $this->input->get('mode') !== 'v2' ? 'Form_college' : 'jr_college_form';
		$this->load->view($form_name);
		
	}
	
	function add_college_form()
	{
		$this->load->model('College_Form');
		$this->College_Form->add();
		// $this->load->view('Form_college');
		// redirect(base_url('welcome/Form_clg'));
	}
	function submit()
	{
		$this->load->view('Form_submit');
	}
	// ==========================================================================================
	public function fetching()
	{
		$data['student_details'] = $this->College_Form->get_student_details();
		$this->load->view('Fetch_clg', $data);
	}
	public function edit($id)
	{
		$this->load->model('College_Form'); // Model ko load karna na bhoolein
		$data['student'] = $this->College_Form->get_student_by_id($id);
		$this->load->view('edit_formclg', $data);
	}

	public function view($id = null)
	{
		if (!isset($id)) {
			echo "Tu ID PRovide kar re bhaya";
			exit();
		}
		$this->load->model('College_Form'); // Model ko load karna na bhoolein
		$data['student'] = $this->College_Form->get_student_by_id($id);
		$this->load->view('view_formclg', $data);
	}

	public function update()
	{
		$this->load->model('College_Form'); // Model ko load karna na bhoolein
		$id = $this->input->post('id');
		$student_data = array(
			'db_branch' => $this->input->post('db_branch'),
			'db_stream' => $this->input->post('db_stream'),
			'db_admissiontype' => $this->input->post('db_admissiontype'),
			'db_class' => $this->input->post('db_class'),
			'db_medium' => $this->input->post('db_medium'),
			'firstname' => $this->input->post('firstname'),
			'Middlename' => $this->input->post('Middlename'),
			'lastname' => $this->input->post('lastname'),
			'date' => $this->input->post('date'),
			'db_gender' => $this->input->post('db_gender'),
			'db_place' => $this->input->post('db_place'),
			'db_religion' => $this->input->post('db_religion'),
			'db_caste' => $this->input->post('db_caste'),
			'db_adharnumber' => $this->input->post('db_adharnumber'),
			'db_nationality' => $this->input->post('db_nationality'),
			'choice_subj_hin_mar' => $this->input->post('choice_subj_hin_mar'),
			'choice_math_geog_bio' => $this->input->post('choice_math_geog_bio'),
			'commerce_optional_sub' => $this->input->post('commerce_optional_sub'),
		);
		$this->College_Form->update_student($id, $student_data);
		redirect('welcome/fetching');
	}
}
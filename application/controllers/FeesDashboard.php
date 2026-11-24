<?php
defined('BASEPATH') or exit('No direct script access allowed');
class FeesDashboard extends CI_Controller
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
        // $this->load->model('DB_model');
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('DB_model');
        $this->load->model('MasterForm_model');
        $this->load->model('Stats_model');
    }

    // =============== Fees Alert Report
    function Fees_db()
    {
        $data['academic_years'] = $this->DB_model->get_all_academic_years();
        $student_id = $this->input->post('student_id');
        $ye = 6;
        $data['stats'] = array();
        $data['from_date'] = 0;
        $data['to_date'] = 0;
        $data['records'] = $this->DB_model->date();
        $data['Feeses'] = $this->DB_model->fetch_class();
        $data['Paid_chart'] = $this->DB_model->paid_chart($ye);
        $data['Chart_Stud'] = $this->DB_model->chart_stud($ye);
        $data['act_stud'] = $this->DB_model->chart_stud($ye);
        $data['total_exemption'] = $this->DB_model->total_exemption();
        $data['Details'] = $this->DB_model->fetch_data($ye);
        $data['studs'] = $this->DB_model->fetch_rte();
        $data['rtes'] = $this->DB_model->Total_rte();
        $data['classwise_chart'] = $this->DB_model->classwise_chart($ye);
        $data['students'] = $this->DB_model->Total();
        $data['fees'] = $this->DB_model->Total();
        $data['Totals'] = $this->DB_model->Total();
        $data['classes'] = $this->DB_model->fetch_class();
        $data['balanced'] = $this->DB_model->Total();
        $data['fees'] = $this->DB_model->Paid();
        // $data['students'] = $this->DB_model->Total();
        $data['status'] = 1;
        $data['fedetails'] = $this->DB_model->stud_fetch($student_id, $ye);
        if ($this->uri->total_segments() == 4) {
            $data['stats'] = $this->Report_model->exemption_fees($this->uri->segment(3), $this->uri->segment(4));
            $data['from_date'] = $this->uri->segment(3);
            $data['to_date'] = $this->uri->segment(4);
        }
        $this->load->view('DB/Fees_db', $data);
    }
    function Paid()
    {
        $this->DB_model->Paid();
    }
    function Paid_chart()
    {
        $year = $this->input->get('year');
        echo json_encode($this->DB_model->paid_chart($year));
    }

    function monthfilter()
    {
        $year = $this->input->post('year');
        echo json_encode($this->DB_model->monthfilter($year));
    }

    function filter()
    {
        $year = $this->input->get('year');
        $from_date = $this->input->get('from');
        $to_date = $this->input->get('to');
        if ($to_date == null) {
            echo json_encode($this->DB_model->classwise_chart($year));
        } else {
            echo json_encode($this->DB_model->filter($from_date, $to_date));
        }
    }
    function paid_filter()
    {
        $year = $this->input->get('year');
        $from_date = $this->input->get('from');
        $to_date = $this->input->get('to');
        if ($to_date == null) {
            echo json_encode($this->DB_model->paid_chart($year));
        } else {
            echo json_encode($this->DB_model->filter($from_date, $to_date));
        }
    }
    function paids_filter()
    {
        $year = $this->input->get('year');
        $from_date = $this->input->get('from');
        $to_date = $this->input->get('to');
        if ($to_date == null) {
            echo json_encode($this->DB_model->paid_chart($year));
        } else {
            echo json_encode($this->DB_model->chart8filter($from_date, $to_date));
        }
    }

    function stud_data()
    {
        $year = $this->input->post('year');
        echo json_encode($this->DB_model->fetch_data($year));
    }

    function student_dataChart()
    {
        $year = 6;
        echo json_encode($this->DB_model->student_dataChart($year));
    }

    function stud_fetch()
    {
        $acdm_year = 6;
        $student_id = $this->input->post('id');
        $name = $this->input->post('name');
        $stud_id = $this->DB_model->fetch_stud_id($student_id, $name);
        $stud_id = $stud_id['id'];
        echo json_encode($this->DB_model->stud_fetch($stud_id, $acdm_year));
    }
    function daily_data()
    {
        echo json_encode($this->DB_model->fetch_class());
    }

    // ============================================ Kamran =====================================================
    function classwisefee()
    {
        $data['academic_years'] = $this->DB_model->get_all_academic_years();
        $student_id = $this->input->post('student_id');
        $ye = 7;
        $data['stats'] = array();
        $data['from_date'] = 0;
        $data['to_date'] = 0;
        $data['records'] = $this->DB_model->date();
        $data['Feeses'] = $this->DB_model->fetch_class();
        $data['Paid_chart'] = $this->DB_model->paid_chart($ye);
        $data['Chart_Stud'] = $this->DB_model->chart_stud($ye);
        $data['act_stud'] = $this->DB_model->chart_stud($ye);
        $data['total_exemption'] = $this->DB_model->total_exemption();
        $data['Details'] = $this->DB_model->fetch_data($ye);
        // <!-- update by ansari kamran -->
        $data['academic_details'] = $this->DB_model->fetchCurrentAcdm();
        $data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
        $data['studs'] = $this->DB_model->fetch_rte();
        $data['rtes'] = $this->DB_model->Total_rte();
        $data['all_classes'] = $this->Stats_model->getAllClasses();
        $data['classwise_chart'] = $this->DB_model->classwise_chart($ye);
        $data['students'] = $this->DB_model->Total();
        $data['fees'] = $this->DB_model->Total();
        $data['Totals'] = $this->DB_model->Total();
        $data['classes'] = $this->DB_model->fetch_class();
        $data['balanced'] = $this->DB_model->Total();
        $data['fees'] = $this->DB_model->Paid();
        // $data['students'] = $this->DB_model->Total();
        $data['status'] = 1;
        $data['fedetails'] = $this->DB_model->stud_fetch($student_id, $ye);
        $std = $this->input->get('std');
        $div = $this->input->get('div');
        $acmd = $this->input->get('acmd');
        $action = $this->input->get('action');

        if ($action == 'search') {
            $data['all_records'] = $this->DB_model->stud_dtails($std, $div, $acmd);
            $data['div_filter'] = $div;
            $data['class_filter'] = $std;
            $data['filter_acdm'] = $acmd;
        } else {
            $data['all_records'] = [];
            $data['div_filter'] = '';
            $data['class_filter'] = '';
            $data['filter_acdm'] = '';
        }

        if ($this->uri->total_segments() == 4) {
            $data['stats'] = $this->Report_model->exemption_fees($this->uri->segment(3), $this->uri->segment(4));
            $data['from_date'] = $this->uri->segment(3);
            $data['to_date'] = $this->uri->segment(4);
        }
        $this->load->view('DB/classwisefees', $data);
    }

    // TABLE DATA
    function student_details()
    {
        $std = $this->input->get('std');
        $div = $this->input->get('div');
        $acdm = $this->input->get('year');
        echo json_encode($this->DB_model->stud_dtails($std, $div, $acdm));
    }

    function all_students()
    {
        $std = $this->input->get('std');
        $div = $this->input->get('div');
        $year = $this->input->get('year');
        echo json_encode($this->DB_model->all_students($std, $div, $year));
    }

    function rte_student()
    {
        $std = $this->input->get('std');
        $div = $this->input->get('div');
        $year = $this->input->get('year');
        echo json_encode($this->DB_model->rte_student($std, $div, $year));
    }

    function total_fees()
    {
        $std = $this->input->get('std');
        $div = $this->input->get('div');
        $year = $this->input->get('year');
        echo json_encode($this->DB_model->total_fees($std, $div, $year));
    }

    function paid_fees()
    {
        $std = $this->input->get('std');
        $div = $this->input->get('div');
        $year = $this->input->get('year');
        echo json_encode($this->DB_model->paid_fees($std, $div, $year));
    }

    function balance_fees()
    {
        $std = $this->input->get('std');
        $div = $this->input->get('div');
        $year = $this->input->get('year');
        echo json_encode($this->DB_model->balance_fees($std, $div, $year));
    }

    function exp_student()
    {
        $std = $this->input->get('std');
        $div = $this->input->get('div');
        $year = $this->input->get('year');
        echo json_encode($this->DB_model->exp_student($std, $div, $year));
    }

    function balance_fee()
    {
        $std = $this->input->get('std');
        $div = $this->input->get('div');
        $year = $this->input->get('year');
        echo json_encode($this->DB_model->balance_fee($std, $div, $year));
    }

    function fetchchartdata()
    {
        $payload = $this->input->raw_input_stream;
        $data = json_decode($payload, true);
        $range = isset($data['range']) ? $data['range'] : '0-0';
        $std = isset($data['std']) ? $data['std'] : '';
        $div = isset($data['div']) ? $data['div'] : '';
        $acdm = isset($data['acdm']) ? $data['acdm'] : '';
        echo json_encode($this->DB_model->fetchFeesRangeDetails($range, $std, $div, $acdm));
    }
}
<?php
class College_Form extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function mathbiogeoconcator($math, $bio, $geo)
    {
        $str = '';
        $multiple = false;
        if ($math != '') {
            $str = $math;
            $multiple = true;
        }
        if ($geo != '') {
            if ($multiple)
                $str .= "-" . $geo;
            else {
                $str = $geo;
                $multiple = true;
            }
        }
        if ($bio != '') {
            if ($multiple)
                $str .= "-" . $bio;
            else {
                $str = $bio;
                $multiple;
            }
        }
        return $str;
    }

    function add()
    {
        $id = $this->input->post('id');
        $exist = $this->db->query("SELECT * FROM clg_stu_det WHERE Id=$id")->row_array();

        $math_selected = $this->input->post('geog_maths_biology_m');
        $geo_selected = $this->input->post('geog_maths_biology_g');
        $bio_selected = $this->input->post('geog_maths_biology_b');
        $choice_math_geog_bio = $this->mathbiogeoconcator($math_selected, $bio_selected, $geo_selected);
        //    ===========================Insert code =====================================================
        // insert data in student table
        $stud_data = array(
            'Application_id' => "OLV" . sprintf("%06d", rand(0, 99999)),
            'db_branch' => $this->input->post('db_branch'),
            'db_stream' => $this->input->post('db_stream'),
            'db_admissiontype' => $this->input->post('db_admissiontype'),
            'db_class' => $this->input->post('db_class'),
            'db_division' => $this->input->post('db_division'),
            'db_medium' => $this->input->post('db_medium'),
            'firstname' => $this->input->post('firstname'),
            'Middlename' => $this->input->post('Middlename'),
            'lastname' => $this->input->post('lastname'),
            'date' => $this->input->post('date'),
            'db_nationality' => $this->input->post('db_nationality'),
            'db_place' => $this->input->post('db_place'),
            'db_gender' => $this->input->post('db_gender'),
            'db_mothertounge' => $this->input->post('db_mothertounge'),
            'db_religion' => $this->input->post('db_religion'),
            'db_caste' => $this->input->post('db_caste'),
            'db_adharnumber' => $this->input->post('db_adharnumber'),
            'choice_subj_hin_mar' => $this->input->post('marathi_hindi'),
            'choice_math_geog_bio' => $choice_math_geog_bio,
            // =================================================================================================
            'commerce_optional_sub' => $this->input->post('commerce_sub'),
            'commerce_sub_h_m' => $this->input->post('commerce_h_m'),
            //   ==================================================================================================

        );

        if ($exist == '') {
            // insert data in table
            $this->db->insert('clg_stu_det', $stud_data);
        } else {
            // update data in table
            unset($stud_data['Application_id']);
            $this->db->where('Id', $id);
            $this->db->update('clg_stu_det', $stud_data);
        }
        // get last inserted id
        $student_inserted_id = $this->db->insert_id();
        // ========================================================================================
        // insert parent data
        $parent_data = array(
            'application_ref_id' => $student_inserted_id,
            'db_fatherfirstname' => $this->input->post('db_fatherfirstname'),
            'db_fathermiddlename' => $this->input->post('db_fathermiddlename'),
            'db_fatherlastname' => $this->input->post('db_fatherlastname'),
            'db_fatheradharnumber' => $this->input->post('db_fatheradharnumber'),
            'db_fatherqualification' => $this->input->post('db_fatherqualification'),
            'db_fatheroccupation' => $this->input->post('db_fatheroccupation'),
            'db_fathersannualincome' => $this->input->post('db_fathersannualincome'),
            'db_mothersname' => $this->input->post('db_mothersname'),
            'db_mothersqualificaiton' => $this->input->post('db_mothersqualificaiton'),
            'db_mothersoccupation' => $this->input->post('db_mothersoccupation'),
            'db_mothersannualincome' => $this->input->post('db_mothersannualincome'),
            'db_parentsmobile' => $this->input->post('db_parentsmobile'),
            // 'db_parentsemail' => $this->input->post('db_parentsemail'),
        );
        // $this->db->insert('clg_par_in', $parent_data);
        if ($exist == '') {
            // insert data in table
            $this->db->insert('clg_par_in', $parent_data);
        } else {
            // update data in table
            unset($parent_data['application_ref_id']);
            $this->db->where('application_ref_id', $id);
            $this->db->update('clg_par_in', $parent_data);
        }
        // =========================================================================================
        $comunnication_data = array(
            'application_id_ref' => $student_inserted_id,
            'db_permenantaddress' => $this->input->post('db_pemrenantaddress'),
            'db_landmark' => $this->input->post('db_landmark'),
            'db_pincode' => $this->input->post('db_pincode'),
            'db_telephonenumber' => $this->input->post('db_telephonenumber'),
            'db_mobilenumber' => $this->input->post('db_mobilenumber'),
            'db_email' => $this->input->post('db_email'),
            'db_lastschool' => $this->input->post('db_lastschool'),
            'db_lastudiseno' => $this->input->post('db_lastudiseno'),
            'student_id' => $this->input->post('student_id'),
        );
        if ($exist == null) {
            // insert data in table
            $this->db->insert('clg_com_det', $comunnication_data);
        } else {
            // update data in table
            unset($comunnication_data['application_id_ref']);
            $this->db->where('application_id_ref', $id);
            $this->db->update('clg_com_det', $comunnication_data);
        }
        // $this->db->insert('clg_com_det', $comunnication_data);

        // ===========================================================================================
        $examination_data = array(
            'application_id_ref' => $student_inserted_id,
            'db_lastclass' => $this->input->post('db_lastclass'),
            'db_lastboard' => $this->input->post('db_lastboard'),
            'db_lastyear' => $this->input->post('db_lastyear'),
            'db_lastseatno' => $this->input->post('db_lastseatno'),
            'db_lastmarks' => $this->input->post('db_lastmarks'),
            'db_lastoutof' => $this->input->post('db_lastoutof'),
            'db_lastpercentage' => $this->input->post('db_lastpercentage'),
            'db_lastappeared' => $this->input->post('db_lastappeared'),
        );
        if ($exist == '') {
            // insert data in table
            $this->db->insert('clg_exm_det', $examination_data);
        } else {
            // update data in table
            unset($examination_data['application_id_ref']);
            $this->db->where('application_id_ref', $id);
            $this->db->update('clg_exm_det', $examination_data);
        }
        // $this->db->insert('clg_exm_det', $examination_data);
        // ==============================================================================================

        $upload_path = './uploads/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }

        // Handle file uploads
        $birth_certificate_docu = $this->_do_upload('birth_certificate_docu', $upload_path);
        $school_leaving_docu = $this->_do_upload('school_leaving_docu', $upload_path);
        $progress_report_docu = $this->_do_upload('progress_report_docu', $upload_path);
        $passport_photo_docu = $this->_do_upload('passport_photo_docu', $upload_path);
        $passport_photo_parents_docu = $this->_do_upload('passport_photo_parents_docu', $upload_path);
        $caste_certificate_docu = $this->_do_upload('caste_certificate_docu', $upload_path);
        $aadhar_card_docu = $this->_do_upload('aadhar_card_docu', $upload_path);

        // Collect form data
        $data = array(
            'application_id_ref' => $student_inserted_id,
            'birth_certificate' => $this->input->post('birth_certificate'),
            'birth_certificate_docu' => $birth_certificate_docu,
            'school_leaving_certi' => $this->input->post('school_leaving'),
            'school_leaving_docu' => $school_leaving_docu,
            'progress_report_card' => $this->input->post('progress_report'),
            'progress_report_docu' => $progress_report_docu,
            'passport_photo_student' => $this->input->post('passport_photo_student'),
            'passport_photo_docu' => $passport_photo_docu,
            'nationality_domicle' => $this->input->post('nationality_domicile'),
            'nationality_domicle_docx' => $this->input->post('nationality_domicile_docu'),
            'caste_certificate' => $this->input->post('caste_certificate'),
            'caste_certificate_docu' => $caste_certificate_docu,
            'aadhar_card' => json_encode($this->input->post('aadhar_card')),
            'aadhar_card_docu' => $aadhar_card_docu,
        );
        if ($exist == '') {
            // insert data in table
            $this->db->insert('clg_doc_det', $data);
        } else {
            // update data in table
            unset($data['application_id_ref']);
            $this->db->where('application_id_ref', $id);
            $this->db->update('clg_doc_det', $data);
        }
        // $this->db->insert('clg_doc_det', $data);
    }

    // ===================================================================================
    private function _do_upload($field_name, $upload_path)
    {
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx';
        $config['max_size'] = 2048;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field_name)) {
            return NULL;
        } else {
            return $this->upload->data('file_name');
        }
    }
    // ============================Fetch code=============================================

    // fetch_models
    public function get_student_details()
    {
        $query = $this->db->get('clg_stu_det');
        return $query->result_array();
    }

    public function get_student_by_id($id)
    {
        $this->db->where('Id', $id);
        $query = $this->db->get('clg_stu_det');
        return $query->row_array();
    }

    public function update_student($id, $student_data)
    {
        $this->db->where('Id', $id);
        return $this->db->update('clg_stu_det', $student_data);
    }

    public function delete_application($id)
    {
        $this->db->where('Id', $id);
        return $this->db->delete('clg_stu_det');
    }

    // function getData($id)
    // {
    //     $sql = "SELECT clg_stu_det.*, clg_par_in.*, clg_com_det.*, clg_exm_det.*, clg_doc_det.* FROM clg_stu_det LEFT JOIN clg_par_in ON clg_par_in.application_ref_id = clg_stu_det.Id LEFT JOIN clg_com_det ON clg_com_det.application_id_ref = clg_stu_det.Id LEFT JOIN clg_exm_det ON clg_exm_det.application_id_ref = clg_stu_det.Id LEFT JOIN clg_doc_det ON clg_doc_det.application_id_ref = clg_stu_det.Id WHERE clg_stu_det.Id = Id";

    //     return $this->db->query($sql)->row_array();
    // }

    function getData($id)
    {
        $sql = "SELECT clg_stu_det.*, clg_par_in.*, clg_com_det.*, clg_exm_det.*, clg_doc_det.* 
            FROM clg_stu_det 
            LEFT JOIN clg_par_in ON clg_par_in.application_ref_id = clg_stu_det.Id 
            LEFT JOIN clg_com_det ON clg_com_det.application_id_ref = clg_stu_det.Id 
            LEFT JOIN clg_exm_det ON clg_exm_det.application_id_ref = clg_stu_det.Id 
            LEFT JOIN clg_doc_det ON clg_doc_det.application_id_ref = clg_stu_det.Id 
            WHERE clg_stu_det.Id = ?";

        return $this->db->query($sql, array($id))->row_array();
    }

    // =========================== edit details changed=================================================
    public function get_student_clg($id)
    {
        return $this->db->get_where('clg_stu_det', array('Id' => $id))->row_array();
    }

    // ====================================Update code changed===========================================
    public function update_student_clg($id, $data)
    {
        $this->db->where('Id', $id);
        $this->db->update('clg_stu_det', $data);
    }
}
<?php
class MasterForm_model extends CI_model
{
    //Add Teacher Into DB Assign Class
    function assign_teacher($data)
    {
        $status = $this->db->insert('spatesd', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    //Get Assigned Teachers
    function get_all_assigned_teachers($academic_year_id = null)
    {
        $this->db->select('spatesd.*, spriden.SPRIDEN_student_id, spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name')
            ->from('spatesd')
            ->join('spriden', 'spatesd.SPATESD_teacher_id = spriden.SPRIDEN_student_id');

        if (!empty($academic_year_id)) {
            $this->db->where('spatesd.SPATESD_academic_year', $academic_year_id);
        }

        return $this->db->get()->result_array();
    }

    //=============================== MATER FORMS =============================================
    //----------------Subject Master ------------------------------
    //check availability
    function checkIfExistSubject($id)
    {
        $this->db->where('stvsubj_id', $id);
        $data = $this->db->get('stvsubj')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //subject master
    function add_subject($data)
    {
        $status = $this->db->insert('stvsubj', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function get_all_subjects()
    {
        return $this->db->select('stvsubj.*, spruser.*')
            ->from('stvsubj')
            ->join('spruser', 'stvsubj.stvsubj_added_by = spruser.spruser_id and stvsubj_acdm=(select stvschool_acdm_year from stvschool) order by stvsubj_std')
            ->get()->result_array();
    }
    //get school data by id
    function getSubjectDataByID()
    {
        $this->db->where('stvsubj_id', $this->input->post('id'));
        return $this->db->get('stvsubj')->row_array();
    }
    //Update data
    function update_subject_data($data, $id)
    {
        $this->db->where('stvsubj_id', $id);
        $status = $this->db->update('stvsubj', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    //---------------------------- Fees master-----------------------------
    //check availability
    function checkIfExistFees($id)
    {
        $this->db->where('id', $id);
        $data = $this->db->get('stvfees')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //add fees master
    function add_stvfees_data($data)
    {
        $status = $this->db->insert('stvfees', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    //All Classes For Fees
    function getAllClassesForFees()
    {
        return $this->db->select('stvfees.*, spruser.*')
            ->from('stvfees')
            ->join('spruser', 'stvfees.stvfees_added_by = spruser.spruser_id')
            ->get()->result_array();
    }
    //get school data by id
    function getFeesDataByID()
    {
        $this->db->where('id', $this->input->post('id'));
        return $this->db->get('stvfees')->row_array();
    }
    //Update data
    function update_fees_data($data, $id)
    {
        $this->db->where('id', $id);
        $status = $this->db->update('stvfees', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    //-----------------------------------School-------------
    //check availability
    function checkIfExistSchool($id)
    {
        $this->db->where('STVPSCH_code', $id);
        $data = $this->db->get('stvpsch')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //Add School data
    function add_school_data($data)
    {
        $status = $this->db->insert('stvpsch', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    //Get School Data All
    function get_all_school_data()
    {
        return $this->db->select('stvpsch.*, spruser.*')
            ->from('stvpsch')
            ->join('spruser', 'stvpsch.STVPSCH_added_by = spruser.spruser_id')
            ->get()->result_array();
    }
    //get school data by id
    function getSchoolDataByID()
    {
        $this->db->where('STVPSCH_code', $this->input->post('id'));
        return $this->db->get('stvpsch')->row_array();
    }
    //Update data
    function update_school_data($data, $id)
    {
        $this->db->where('STVPSCH_code', $id);
        $status = $this->db->update('stvpsch', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    //-----------------------------------College-------------
    //check availability
    function checkIfExistCollege($id)
    {
        $this->db->where('STVCOLL_code', $id);
        $data = $this->db->get('stvcoll')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //Add School data
    function add_College_data($data)
    {
        $status = $this->db->insert('stvcoll', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    //Get School Data All
    function get_all_college_data()
    {
        return $this->db->select('stvcoll.*, spruser.*')
            ->from('stvcoll')
            ->join('spruser', 'stvcoll.STVCOLL_added_by = spruser.spruser_id')
            ->get()->result_array();
    }
    //get school data by id
    function getCollegeDataByID()
    {
        $this->db->where('STVCOLL_code', $this->input->post('id'));
        return $this->db->get('stvcoll')->row_array();
    }
    //Update data
    function update_college_data($data, $id)
    {
        $this->db->where('STVCOLL_code', $id);
        $status = $this->db->update('stvcoll', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    //-----------------------------------University-------------
    //check availability
    function checkIfExistUniversity($id)
    {
        $this->db->where('STVUNIV_code', $id);
        $data = $this->db->get('stvuniv')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //Add University data
    function add_University_data($data)
    {
        $status = $this->db->insert('stvuniv', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    //Get University Data All
    function get_all_university_data()
    {
        return $this->db->select('stvuniv.*, spruser.*')
            ->from('stvuniv')
            ->join('spruser', 'stvuniv.STVUNIV_added_by = spruser.spruser_id')
            ->get()->result_array();
    }
    //get University data by id
    function getUniversityDataByID()
    {
        $this->db->where('STVUNIV_code', $this->input->post('id'));
        return $this->db->get('stvuniv')->row_array();
    }
    //Update data
    function update_University_data($data, $id)
    {
        $this->db->where('STVUNIV_code', $id);
        $status = $this->db->update('stvuniv', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    //----------------Subject Master ------------------------------
    //check availability
    function checkIfExistMajor($id)
    {
        $this->db->where('STVMAJR_code', $id);
        $data = $this->db->get('stvmajr')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //Major master
    function add_major($data)
    {
        $status = $this->db->insert('stvmajr', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function get_all_majors()
    {
        return $this->db->select('stvmajr.*, spruser.*')
            ->from('stvmajr')
            ->join('spruser', 'stvmajr.STVMAJR_added_by = spruser.spruser_id')
            ->get()->result_array();
    }
    //get school data by id
    function getMajorDataByID()
    {
        $this->db->where('STVMAJR_code', $this->input->post('id'));
        return $this->db->get('stvmajr')->row_array();
    }
    //Update data
    function update_major_data($data, $id)
    {
        $this->db->where('STVMAJR_code', $id);
        $status = $this->db->update('stvmajr', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    //----------------Subject Master ------------------------------
    //check availability
    function checkIfExistShift($id)
    {
        $this->db->where('STVSHIF_id', $id);
        $data = $this->db->get('stvshif')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //Major master
    function add_shift_data($data)
    {
        $status = $this->db->insert('stvshif', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function get_all_shifts()
    {
        return $this->db->select('stvshif.*, spruser.*')
            ->from('stvshif')
            ->join('spruser', 'stvshif.STVSHIF_added_by = spruser.spruser_id')
            ->get()->result_array();
    }
    //get school data by id
    function getShiftDataByID()
    {
        $this->db->where('STVSHIF_id', $this->input->post('id'));
        return $this->db->get('stvshif')->row_array();
    }
    //Update data
    function update_shift_data($data, $id)
    {
        $this->db->where('STVSHIF_id ', $id);
        $status = $this->db->update('stvshif', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    //----------------Position Master ------------------------------
    //check availability
    function checkIfExistPosition($id)
    {
        $this->db->where('STVPOSN_id', $id);
        $data = $this->db->get('stvposn')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //Major master
    function add_position_data($data)
    {
        $status = $this->db->insert('stvposn', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function get_all_positions()
    {
        return $this->db->select('stvposn.*, spruser.*')
            ->from('stvposn')
            ->join('spruser', 'stvposn.STVPOSN_added_by = spruser.spruser_id')
            ->get()->result_array();
    }
    //get school data by id
    function getPositionDataByID()
    {
        $this->db->where('STVPOSN_id ', $this->input->post('id'));
        return $this->db->get('stvposn')->row_array();
    }
    //Update data
    function update_position_data($data, $id)
    {
        $this->db->where('STVPOSN_id ', $id);
        $status = $this->db->update('stvposn', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }


    //----------------Grades Master ------------------------------
    //check availability
    function checkIfExistGrade($id)
    {
        $this->db->where('STVGRDS_id', $id);
        $data = $this->db->get('stvgrds')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //Major master
    function add_grades_data($data)
    {
        $status = $this->db->insert('stvgrds', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function get_all_grades()
    {
        return $this->db->select('stvgrds.*, spruser.*')
            ->from('stvgrds')
            ->join('spruser', 'stvgrds.STVGRDS_added_by = spruser.spruser_id')
            ->get()->result_array();
    }
    //get school data by id
    function getGradesDataByID()
    {
        $this->db->where('STVGRDS_id ', $this->input->post('id'));
        return $this->db->get('stvgrds')->row_array();
    }
    //Update data
    function update_grades_data($data, $id)
    {
        $this->db->where('STVGRDS_id ', $id);
        $status = $this->db->update('stvgrds', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    //----------------Academic Year Master ------------------------------
    //check availability
    function checkIfExistAcademicYear($id)
    {
        $this->db->where('stvacdm_id', $id);
        $data = $this->db->get('stvacdm')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //Major master
    function add_academic_year_data($data)
    {
        $status = $this->db->insert('stvacdm', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function get_all_academic_years()
    {
        return $this->db->select('stvacdm.*, spruser.*')
            ->from('stvacdm')
            ->join('spruser', 'stvacdm.stvacdm_added_by = spruser.spruser_id', 'left')
            ->get()->result_array();
    }
    //get school data by id
    function getAcademicYearDataByID()
    {
        $this->db->where('stvacdm_id', $this->input->post('id'));
        return $this->db->get('stvacdm')->row_array();
    }
    //Update data
    function update_academic_year_data($data, $id)
    {
        $this->db->where('stvacdm_id', $id);
        $status = $this->db->update('stvacdm', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }


    //----------------State Year Master ------------------------------
    //check availability
    function checkIfExistState($id)
    {
        $this->db->where('stvstts_id', $id);
        $data = $this->db->get('stvstts')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //Major master
    function add_state($data)
    {
        $status = $this->db->insert('stvstts', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function get_all_states()
    {
        return $this->db->select('stvstts.*, spruser.*')
            ->from('stvstts')
            ->join('spruser', 'stvstts.stvstts_added_by = spruser.spruser_id')
            ->get()->result_array();
    }
    //get school data by id
    function getStateDataByID()
    {
        $this->db->where('stvstts_id', $this->input->post('id'));
        return $this->db->get('stvstts')->row_array();
    }
    //Update data
    function update_state_data($data, $id)
    {
        $this->db->where('stvstts_id', $id);
        $status = $this->db->update('stvstts', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    //----------------City Year Master ------------------------------
    //check availability
    function checkIfExistCity($id)
    {
        $this->db->where('STVCITY_id', $id);
        $data = $this->db->get('stvcity')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //Major master
    function add_city($data)
    {
        $status = $this->db->insert('stvcity', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function get_all_cities()
    {
        return $this->db->select('stvcity.*, spruser.*, stvstts.*')
            ->from('stvcity')
            ->join('spruser', 'stvcity.STVCITY_added_by = spruser.spruser_id')
            ->join('stvstts', 'stvstts.stvstts_id  = stvcity.STVCITY_state_code')
            ->get()->result_array();
    }
    //get school data by id
    function getCityDataByID()
    {
        $this->db->where('STVCITY_id', $this->input->post('id'));
        return $this->db->get('stvcity')->row_array();
    }
    //Update data
    function update_city_data($data, $id)
    {
        $this->db->where('STVCITY_id', $id);
        $status = $this->db->update('stvcity', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    //----------------Form Access Master ------------------------------
    //check availability
    function checkIfExistAccess($id)
    {
        $this->db->where('stvuacs_id', $id);
        $data = $this->db->get('stvuacs')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //Major master
    function add_form_access($data)
    {
        $status = $this->db->insert('stvuacs', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function getAllFormAccessData()
    {
        return $this->db->select('stvuacs.*, spruser.*,')
            ->from('stvuacs')
            ->join('spruser', 'stvuacs.stvuacs_userid = spruser.spruser_id')
            ->get()->result_array();
    }
    //get school data by id
    function getAccessDataByID()
    {
        $this->db->where('stvuacs_id', $this->input->post('id'));
        return $this->db->get('stvuacs')->row_array();
    }
    //Update data
    function update_access_data($data, $id)
    {
        $this->db->where('stvuacs_id', $id);
        $status = $this->db->update('stvuacs', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }


    //----------------Users Master ------------------------------
    //check availability
    function checkIfExistDBuser($id)
    {
        $this->db->where('spruser_id', $id);
        $data = $this->db->get('spruser')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //Major master
    function add_dashboard_user($data)
    {
        $status = $this->db->insert('spruser', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    //get school data by id
    function getDBUserDataByID()
    {
        $this->db->where('spruser_id', $this->input->post('id'));
        return $this->db->get('spruser')->row_array();
    }
    //Update data
    function update_dbuser_data($data, $id)
    {
        $this->db->where('spruser_id', $id);
        $status = $this->db->update('spruser', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }


    //----------------Exam Master ------------------------------
    //check availability
    function checkIfExistExam($id)
    {
        $this->db->where('stvexam_id', $id);
        $data = $this->db->get('stvexam')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //subject master
    function add_exam_data($data)
    {
        $status = $this->db->insert('stvexam', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function get_all_exam()
    {
        return $this->db->select('stvexam.*, spruser.*')
            ->from('stvexam')
            ->join('spruser', 'stvexam.stvexam_added_by = spruser.spruser_id')
            ->get()->result_array();
    }
    //get school data by id
    function getExamDataByID()
    {
        $this->db->where('stvexam_id', $this->input->post('id'));
        return $this->db->get('stvexam')->row_array();
    }
    //Update data
    function update_exam_data($data, $id)
    {
        $this->db->where(
            'stvexam_id',
            $id
        );
        $status = $this->db->update('stvexam', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }


    //----------------Exam Master ------------------------------
    //check availability
    function checkIfExistHold($id)
    {
        $this->db->where('stvhold_id', $id);
        $data = $this->db->get('stvhold')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //Hold master
    function add_hold_data($data)
    {
        $status = $this->db->insert('stvhold', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function get_all_holds()
    {
        return $this->db->select('stvhold.*, spruser.*')
            ->from('stvhold')
            ->join('spruser', 'stvhold.stvhold_added_by = spruser.spruser_id')
            ->get()->result_array();
    }
    //get school data by id
    function getHoldDataByID()
    {
        $this->db->where('stvhold_id', $this->input->post('id'));
        return $this->db->get('stvhold')->row_array();
    }
    //Update data
    function update_hold_data($data, $id)
    {
        $this->db->where(
            'stvhold_id',
            $id
        );
        $status = $this->db->update('stvhold', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }















    //===================== CLASS CREATION ======================================
    //get all students for class
    function getStudentForClass()
    {
        $this->db->where('SPRIDEN_admission_class', $this->input->post('className'));
        $this->db->where('SPRIDEN_user_type', "st");
        $data = $this->db->get('spriden')->result_array();
        if ($data == null) {
            return 0;
        } else {
            return $data;
        }
    }



    //=============================================================================
    //Login History
    function getAllLoginData()
    {
        return $this->db->select('stvlgin.*, spruser.*')
            ->from('stvlgin')
            ->join('spruser', 'stvlgin.stvlgin_userid = spruser.spruser_id')
            ->get()->result_array();
    }
    function findLoginDataByUsername()
    {
        return $this->db->select('stvlgin.*, spruser.*')
            ->from('stvlgin')
            ->where('stvlgin_userid', $this->input->post('username'))
            ->join('spruser', 'stvlgin.stvlgin_userid = spruser.spruser_id')
            ->get()->result_array();
    }
    function findLoginDataByDate()
    {
        return $this->db->select('stvlgin.*, spruser.*')
            ->from('stvlgin')
            ->where('stvlgin_date', $this->input->post('dateOfLogin'))
            ->join('spruser', 'stvlgin.stvlgin_userid = spruser.spruser_id')
            ->get()->result_array();
    }
    function findLoginDataByBoth()
    {
        return $this->db->select('stvlgin.*, spruser.*')
            ->from('stvlgin')
            ->where('stvlgin_userid', $this->input->post('username'))
            ->where('stvlgin_date', $this->input->post('dateOfLogin'))
            ->join('spruser', 'stvlgin.stvlgin_userid = spruser.spruser_id')
            ->get()->result_array();
    }

    public function checkAssignedTeacher($std, $div)
    {
        return $this->db
            ->select('spatesd.*,spriden.SPRIDEN_surname,spriden.SPRIDEN_student_name')
            ->from('spatesd')
            ->join('spriden', 'spriden.SPRIDEN_student_id=spatesd.SPATESD_teacher_id')
            ->where('SPATESD_std', $std)
            ->where('SPATESD_div', $div)
            ->get()
            ->row_array();
    }

    function update_assign_teacher($data, $id)
    {
        $this->db->where('SPATESD_id', $id);
        if ($this->db->update('spatesd', $data)) {
            return true;
        } else {
            return false;
        }
    }

    function tecaherClassDetails($id)
    {
        $this->db->where('SPATESD_id', $id);
        return $this->db->get('spatesd')->row_array();
    }

    function deleteTeacherRecord($id)
    {
        $this->db->where('SPATESD_id', $id);
        if ($this->db->delete('spatesd')) {
            return true;
        } else {
            return false;
        }
    }
    function checkTeacherPresent($id)
    {
        $data = $this->db->query("SELECT * FROM spatesd WHERE SPATESD_id = $id")->row_array();
        if ($data == null) {
            return 1;
        } else {
            return 0;
        }
    }
    // changes made from here --- 28 Sep 2021

    //check availability
    function checkIfExistLesson($id)
    {
        $this->db->where('stvlmst_id', $id);
        $data = $this->db->get('stvlmst')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //Hold master
    function add_lesson($data)
    {
        $status = $this->db->insert('stvlmst', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function get_all_lessons()
    {
        return $this->db->select('stvlmst.*, stvsubj.*')
            ->from('stvlmst')
            ->join('stvsubj', 'stvsubj.stvsubj_id = stvlmst.stvlmst_sub')
            ->get()->result_array();
    }
    //get school data by id
    function getLessonDataByID()
    {
        $this->db->where('stvlmst_id', $this->input->post('id'));
        $data['main'] = $this->db->get('stvlmst')->row_array();
        $data['sub'] = $this->getAllSubjetcsByStd($data['main']['stvlmst_std']);
        return $data;
    }
    //Update data
    function update_lesson_data($data, $id)
    {
        $this->db->where(
            'stvlmst_id',
            $id
        );
        $status = $this->db->update('stvlmst', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    function getAllLessons()
    {
        return $this->db->get('stvlmst')->result_array();
    }

    function getAllSubjetcsByStd($std)
    {
        $this->db->where('stvsubj_std', $std);
        return $this->db->get('stvsubj')->result_array();
    }
    // to here ----------------- 29 Sep 2021
    // driver and vehicle 25-Dec-2021 ==============

    function allData()
    {
        $sql = $this->db->get('stvdriver')->result_array();
        return $sql;
    }
    function showList()
    {
        $this->db->select('*');
        $this->db->from('stvdriver');
        $this->db->join('stvvehicle', 'stvdriver.id = stvvehicle.id');
        $sql = $this->db->get()->result_array();
        return $sql;

        $sql = "SELECT d_id.name FROM stvdriver d_id,stvvehicle where id = d_id";
        return $this->db->query($sql)->result_array();
    }

    function getDriverNumber()
    {
        $sql = "SELECT MAX(id) id FROM stvdriver";
        $number = $this->db->query($sql)->row_array();
        if ($number == NULL) {
            return 0;
        } else {
            return $number['id'];
        }
    }
    function updateDriver($ids)
    {
        $data = array(
            'name' => $this->input->post('name'),
            'address' => $this->input->post('address'),
            'mobile' => $this->input->post('mobile')
        );
        $this->db->where('id', $ids);
        $status = $this->db->update('stvdriver', $data);
        return $status;
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function getDataByID($ids)
    {
        $this->db->where('id', $ids);
        return $this->db->get('stvdriver')->row_array();
    }
    function addDriver($dNumber)
    {
        $data = array(
            'name' => $this->input->post('name'),
            'address' => $this->input->post('address'),
            'mobile' => $this->input->post('mobile'),
            'd_number' => $dNumber
        );
        $status = $this->db->insert('stvdriver', $data);
        return $status;
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function checkDriverPresent($id)
    {
        return $this->db->where('id', $id)->get('stvdriver')->row_array();
    }
    function getVehicleNumber()
    {
        $sql = "SELECT MAX(id) id FROM stvvehicle";
        $number = $this->db->query($sql)->row_array();
        if ($number == NULL) {
            return 0;
        } else {
            return $number['id'];
        }
    }
    function updateVehicle($ids)
    {
        $data = array(
            'vehicle_route' => $this->input->post('route'),
            'vehicle_type' => $this->input->post('type')
        );

        $this->db->where('id', $ids);
        $status = $this->db->update('stvvehicle', $data);

        return $status;
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function getDriverDetails()
    {
        return $this->db->select('id,name')->from('stvdriver')->get()->result_array();
    }
    function allDriver()
    {
        $this->db->select('*');
        $this->db->from('stvdriver');
        $this->db->join('stvvehicle', 'stvdriver.id = stvvehicle.id');
        // execute query  
        return $this->db->get()->result_array();
    }
    function getVehicleDataByID($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('stvvehicle')->row_array();
    }
    function getDriverNum()
    {
        $dNumber = $this->db->query("SELECT stdd.d_number From stvdriver stdd,stvvehicle WHERE stdd.id = stvvehicle.id")->result_array();
        return $dNumber;
    }
    function addVehicle($vNumber)
    {


        $data = array(
            'vehicle_route' => $this->input->post('route'),
            'vehicle_type' => $this->input->post('type'),
            'v_number' => $vNumber,
            'd_id' => $this->input->post('name')
        );

        $status = $this->db->insert('stvvehicle', $data);

        return $status;
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function checkVehiclePresent($id)
    {
        return $this->db->where('id', $id)->get('stvvehicle')->row_array();
    }
    //  structure

    function getClass()
    {
        return $this->db->query("SELECT distinct stvfees_class from stvfees")->result_array();
    }
    function getAcdmYear()
    {
        return $this->db->query("SELECT stvacdm_id,stvacdm_year from stvacdm")->result_array();
    }
    function getData()
    {
        return $this->db->query("SELECT * from stvfstr")->result_array();
    }
    function clsCheck($cls, $acdm)
    {
        return $this->db->query("SELECT * from stvfstr WHERE stvfstr_std = '$cls' and stvfstr_acdm_year = '$acdm'")->row_array();
    }
    //  add

    function addStructure($class, $acdm, $fees)
    {


        $check['list'] = $this->db->query("SELECT * from stvfstr WHERE stvfstr_std = '$class' and stvfstr_acdm_year = '$acdm'")->row_array();

        $id = $check['list']['stvfstr_id'];

        if ($check['list'] == null) {

            return $this->db->query("INSERT INTO stvfstr (stvfstr_std,stvfstr_fees,stvfstr_acdm_year)
			VALUES('$class',$fees,$acdm)");
        } else {

            return $this->db->query("UPDATE stvfstr SET stvfstr_std = '$class',stvfstr_fees = '$fees' ,
			stvfstr_acdm_year = '$acdm' WHERE stvfstr_id = '$id'");
        }
    }

    public function getstudents()
    {
        $sql = "SELECT id, spriden_student_id 
                FROM spriden 
                WHERE spriden_student_id IS NOT NULL 
                AND spriden_student_id != ''
                ORDER BY spriden_student_id ASC";
        return $this->db->query($sql)->result_array();
    }

    public function studentDetails($sid)
    {
        $sql = "SELECT 
                    s.id,
                    s.SPRIDEN_student_id,
                    CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name,
                    c.STVCLAS_code,
                    c.STVCLAS_student_id,
                    c.STVCLAS_rollno,
                    c.STVCLAS_standard,
                    c.STVCLAS_class,
                    c.STVCLAS_userid,
                    c.STVCLAS_acdm,
                    c.updated_flag,
                    ac.stvacdm_year
                FROM spriden s
                LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id
                LEFT JOIN stvacdm ac ON ac.stvacdm_id = c.STVCLAS_acdm
                WHERE s.SPRIDEN_student_id = '$sid'
                ORDER BY c.STVCLAS_acdm DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function updateClassDetails($student_id, $old_acdm, $new_acdm, $code, $standard, $class, $old_standard, $old_class)
    {
        $this->db->where('STVCLAS_student_id', $student_id);
        $this->db->where('STVCLAS_acdm', $old_acdm);
        $this->db->where('STVCLAS_code', $code);
        $data = [];
        if ($standard != $old_standard) {
            $data['STVCLAS_standard'] = $standard;
        }
        if ($class != $old_class) {
            $data['STVCLAS_class'] = $class;
        }
        if ($new_acdm != $old_acdm) {
            $data['STVCLAS_acdm'] = $new_acdm;
        }
        if (empty($data)) {
            return true;
        }
        return $this->db->update('stvclas', $data);
    }
}

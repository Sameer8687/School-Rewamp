<?php 
use Mpdf\Tag\Input;
defined('BASEPATH') or exit('No direct script access allowed');
class Aadhar_detail_model extends CI_Model
{
    public function Aadhar_save($file_name)
    {
        $aadhar_number = $this->input->post('aadhar_number');
        $name_on_aadhar = $this->input->post('name_on_aadhar');
        $date_of_birth = $this->input->post('date_of_birth');
        $aadhar_front_img = $file_name;
        $aadhar_back_img = $this->input->post('aadhar_back_img');
        $created_at = date('m/d/Y h:i:s', time());
        $userid = $this->session->userdata('StudentProfileSession');
        $s_id = $this->input->post('s_id');
        $__id = $this->input->post('user_id');
        $student_details = $this->db->get("spriden")->row_array();
        $stud_id = $this->db->query("select concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) as name from spriden where SPRIDEN_student_id = '$s_id'")->row_array();
        $name = $stud_id['name'];
        $aadhar_details = $this->db->get('aadhar_details')->row_array();
        $user_id = $this->db->query("SELECT user_id FROM aadhar_details WHERE user_id='$__id'")->row_array();
        $user_id = $user_id['user_id'];
        if ($user_id != null) {
            $this->db->query("UPDATE aadhar_details SET date_of_birth = '$date_of_birth',aadhar_front_img ='$aadhar_front_img' WHERE user_id= $__id");
        } else {
            if ($this->db->query("INSERT INTO aadhar_details (user_type,user_id,aadhar_number, name_on_aadhar, date_of_birth, aadhar_front_img,aadhar_back_img,created_at, created_by) VALUES ('Student','$__id','$aadhar_number', '$name_on_aadhar', '$date_of_birth','$aadhar_front_img', '$aadhar_back_img', '$created_at','$userid');")) {
            }
        }
        $query_string = "?student-id=$__id&sid=$s_id&name=$name-&token=";
        redirect(base_url('index.php/Data/Student_Adhar/student' . $query_string));
    }

    function data($id)
    {
        return $this->db->query("SELECT s.id,s.SPRIDEN_student_id,CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS student_name,a.* FROM spriden s LEFT JOIN aadhar_details a ON s.id = a.user_id WHERE s.id = '$id'")->row_array();
    }
}
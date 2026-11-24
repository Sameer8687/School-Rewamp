<?php
class Timetable_model extends CI_model
{
	//----------------Exam Master ------------------------------
	//check availability
	function checkIfExistTime($id)
	{
		$this->db->where('stvtbtm_id ', $id);
		$data = $this->db->get('stvtbtm')->row_array();
		if ($data == null) {
			return 0;
		} else {
			return count($data);
		}
	}
	//subject master
	function create_time($data)
	{
		$status = $this->db->insert('stvtbtm', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}
	function get_all_time()
	{
		return $this->db->select('stvtbtm.*, spruser.*')
			->from('stvtbtm')
			->join('spruser', 'stvtbtm.stvtbtm_added_by = spruser.spruser_id')
			->get()->result_array();
	}


	//get school data by id
	function getTimeDataByID()
	{
		$this->db->where('stvtbtm_id ', $this->input->post('id'));
		return $this->db->get('stvtbtm')->row_array();
	}
	//Update data
	function update_time_data($data, $id)
	{
		$this->db->where(
			'stvtbtm_id ',
			$id
		);
		$status = $this->db->update('stvtbtm', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}


	//create timetable
	function create_timetable($data, $std, $div)
	{
		for ($i=0; $i<count($data); $i++){
			$new_data = array (
				'stvttbl_time' => $data[$i][0],
				'stvttbl_monday' => $data[$i][1],
				'stvttbl_tuesday' => $data[$i][2],
				'stvttbl_wednesday' => $data[$i][3],
				'stvttbl_thurday' => $data[$i][4],
				'stvttbl_friday' => $data[$i][5],
				'stvttbl_saturday' => $data[$i][6],
				'stvttbl_std' => $std,
				'stvttbl_div' => $div
			);
			$status = $this->db->insert('stvttbl', $new_data);
		}
		if ($status){
			return true;
		}else {
			return false;
		}
	}

	function getTimetableByStd()
	{
		$this->db->where('stvttbl_std', $this->input->post('class_name'));
		$this->db->where('stvttbl_div', $this->input->post('div_name'));
		return $this->db->get('stvttbl')->result_array();
	}

	function getTimetableForTeachersAndStudents($std, $div)
	{
		$this->db->where('stvttbl_std', $std);
		$this->db->where('stvttbl_div', $div);
		return $this->db->get('stvttbl')->result_array();
	}

	function updt_timetable_cell($data, $id)
	{
		$this->db->where('stvttbl_id', $id);
		$status = $this->db->update('stvttbl', $data);
		if ($status){
			return true;
		}else {
			return false;
		}
	}
}

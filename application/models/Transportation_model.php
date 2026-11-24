<?php

class Transportation_model extends CI_model

{


    function add()
    {



        // $sr_no = $this->input->post('sr_no');
        $Driver_name = $this->input->post('Driver_name');
        $Mobile_no = $this->input->post('Mobile_no');

        $Whatsapp_no = $this->input->post('Whatsapp_no');
        $Address = $this->input->post('Address');
        $mobile = $this->input->post('mobile');
        $Vehicle = $this->input->post('Vehicle');



        if ($this->db->query("INSERT INTO driver_master (Driver_name, Mobile_no,Whatsapp_no, Address,mobile , Vehicle) VALUES ('$Driver_name', '$Mobile_no','$Whatsapp_no', '$Address', '$mobile', '$Vehicle')")) {
            redirect(base_url('index.php/Transportation/Driver_master'));
        } else {
            echo json_encode("fail");
        }
    }
    function getrow($id)
    {
        $this->db->where('sr_no', $id);
        $row = $this->db->get('Editmaster')->row_Array();
    }
    function update()
    {
        $id = $this->input->post('sr_id');
        $Driver_name = $this->input->post('Driver_name');
        $Mobile_no = $this->input->post('Mobile_no');

        $Whatsapp_no = $this->input->post('Whatsapp_no');
        $Address = $this->input->post('Address');
        $mobile = $this->input->post('mobile');
        $Vehicle = $this->input->post('Vehicle');

        $update = $this->db->query("UPDATE driver_master SET Driver_name= '$Driver_name',Mobile_no='$Mobile_no',Whatsapp_no='$Whatsapp_no',Address='$Address',mobile='$mobile',Vehicle='$Vehicle' where sr_no='$id'");

        if ($update) {
            // echo 'success';
            redirect(base_url('index.php/Transportation/Driver_master'));
        } else {
            echo 'failed';
        }
    }
    function Vehicle_ad()
    {
        $Vehicle_name = $this->input->post('Vehicle_name');
        $Wheel_type = $this->input->post('Wheel_type');
        // $Vehicle_type = $this->input->post('Vehicle_type');
        $Vehicle = $this->input->post('Vehicle');
        $no_seat = $this->input->post('no_seat');


        if ($this->db->query("INSERT INTO vehicle_master (Vehicle_name, Wheel_type,Vehicle,no_seat) VALUES ('$Vehicle_name', '$Wheel_type','$Vehicle','$no_seat')")) {
            redirect(base_url('index.php/Transportation/Vehicle_master'));
        } else {
            echo json_encode("fail");
        }
    }
    function getrow_v($id)
    {
        $this->db->where('sr_no', $id);
        $rows = $this->db->get('Vmaster')->row_Array();
    }
    function Vehicle_update()
    {
        $id = $this->input->post('sr_id');
        $Vehicle_name = $this->input->post('Vehicle_name');
        $Wheel_type = $this->input->post('Wheel_type');
        // $Vehicle_type = $this->input->post('Vehicle_type');
        $Vehicle = $this->input->post('Vehicle');
        $no_seat = $this->input->post('no_seat');

        $update = $this->db->query("UPDATE vehicle_master SET Vehicle_name='$Vehicle_name',Wheel_type='$Wheel_type',Vehicle='$Vehicle',no_seat='$no_seat' where sr_no='$id'");

        if ($update) {
            redirect(base_url('index.php/Transportation/vehicle_master'));
        } else {
            echo 'failed';
        }
    }
    function Save()
    {
        $max_rows = $this->input->post('max_rows');
        $class = $this->input->post('Class');
        $divison = $this->input->post('DIVISION');
        for ($i = 1; $i <= $max_rows; $i++) {
            // $sr_no = $this->input->post('sr_no');
            $spriden_id = $this->input->post('spriden_id' . $i);
            $driver_id  = $this->input->post('driver_id' . $i);
            $detail_id  = $this->input->post('detail_id' . $i);
            $created_at = date('Y-m-d');
            $updated_at = date('Y-m-d');
            $exist = $this->db->query("select * from driver_details where spriden_id = $spriden_id")->row_array();
            if ($exist == null) {
                // insert data
                if ($driver_id != '' && $driver_id != 'Select') {
                    // if the driver id is selected then only add data other wise skip the row
                    $this->db->query("INSERT INTO driver_details (spriden_id,driver_id,created_at,updated_at) VALUES ('$spriden_id','$driver_id','$created_at',$updated_at)");
                }
            } else {
                // update data 
                $detail_id = $exist['id'];
                $this->db->query("update driver_details set driver_id = '$driver_id',updated_at = '$updated_at' where id='$detail_id'");
            }
        }
        redirect(base_url('index.php/Transportation/student_details/' . $class . '/' . $divison));
    }


    public function details_fetch($class, $division)
    {
        $sql = "SELECT s.id,s.SPRIDEN_student_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as 'name', concat(c.STVCLAS_standard , ' - ',c.STVCLAS_class) as class, (select `id` from driver_details where `spriden_id` = s.id )as detail_id, (select `driver_id` from driver_details where `spriden_id` = s.id )as driver_id FROM spriden s, stvclas c where s.id = c.STVCLAS_student_id and c.STVCLAS_standard = '$class' and c.STVCLAS_class = '$division'";
        // $sql = "select sr_no,Driver_name from driver_master ";
        return $this->db->query($sql)->result_array();
    }

    function fetch_data()
    {
        $sql = "SELECT s.id,s.SPRIDEN_student_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as 'name', concat(c.STVCLAS_standard , ' - ',c.STVCLAS_class) as class, (select 'detail_id' from driver_details where 'student_id'= s.id )as detail_id, (select 'driver_id' from driver_details where 'student_id'= s.id )as driver_id FROM SPRIDEN s, stvclas c where s.id = c.STVCLAS_student_id and c.STVCLAS_standard = 'V' and c.STVCLAS_class = 'A';";
        $this->db->query($sql)->result_array();
    }
    public function getdrivrDetails()
    {
        return $this->db->query("select sr_no,Driver_name from driver_master")->result_array();
    }


    public function driver_fetch()
    {
        $sr_no = "SELECT * FROM driver_master";
        return $this->db->query($sr_no)->result_array();
    }
    public function vehicle_fetch()
    {
        $sr_no = "SELECT * FROM vehicle_master";
        return $this->db->query($sr_no)->result_array();
    }


    public  function delete($id)
    {
        $this->db->where('sr_no', $id);
        return $this->db->delete('driver_master');
    }
    public function vehicle_delete($id)
    {
        $this->db->where('sr_no', $id);
        return $this->db->delete('vehicle_master');
    }
}

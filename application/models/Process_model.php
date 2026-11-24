<?php

class Process_model extends CI_model
{
    public function move_attendance_from_temp($limit = 100)
    {
        // Get only pending temp records
        $temp_records = $this->db
            ->where('status', 'pending')
            ->order_by('id', 'ASC')
            ->limit($limit)
            ->get('spratnd_temp')
            ->result_array();

        $inserted_count = 0;
        $skipped_count  = 0;

        if (!empty($temp_records)) {
            foreach ($temp_records as $row) {

                // Check if attendance already exists in original table
                $exists = $this->db
                    ->where('SPRATND_date', $row['SPRATND_date'])
                    ->where('SPRATND_student_id', $row['SPRATND_student_id'])
                    ->get('spratnd')
                    ->row_array();

                if ($exists) {
                    // Record already exists, so skip and update temp status
                    $this->db
                        ->where('id', $row['id'])
                        ->update('spratnd_temp', array(
                            'status' => 'skipped'
                            // 'remark' => 'Attendance already exists'
                        ));

                    $skipped_count++;
                } else {
                    // Prepare insert data for original table
                    $insert_data = array(
                        'SPRATND_teacher_id' => $row['SPRATND_teacher_id'],
                        'SPRATND_student_id' => $row['SPRATND_student_id'],
                        'SPRATND_date'       => $row['SPRATND_date'],
                        'SPRATND_status'     => $row['SPRATND_status'],
                        'SPRATND_std'        => $row['SPRATND_std'],
                        'SPRATND_div'        => $row['SPRATND_div'],
                        'SPRATND_acdm_year'  => $row['SPRATND_acdm_year'],
                        'SPRATND_comment'    => $row['SPRATND_comment']
                    );

                    $insert = $this->db->insert('spratnd', $insert_data);

                    if ($insert) {
                        // Insert success, update temp status
                        $this->db
                            ->where('id', $row['id'])
                            ->update('spratnd_temp', array(
                                'status' => 'inserted'
                                // 'remark' => 'Inserted successfully'
                            ));

                        $inserted_count++;
                    } else {
                        // Optional: mark failed
                        $this->db
                            ->where('id', $row['id'])
                            ->update('spratnd_temp', array(
                                'status' => 'failed'
                                // 'remark' => 'Insert failed'
                            ));
                    }
                }
            }
        }

        

        return array(
            'status'         => $this->db->trans_status(),
            'inserted_count' => $inserted_count,
            'skipped_count'  => $skipped_count
        );
    }
}

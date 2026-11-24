<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manage_fees_model extends CI_Model
{
    public function get_bank_transactions($from_date, $to_date)
    {
        if ($from_date == $to_date) {
            $sql = "SELECT id, value_date, txn_posted_date, mode, description, transaction_amount
                    FROM fees_bank_up_transactions
                    WHERE value_date = ?
                    ORDER BY value_date DESC, id DESC";

            return $this->db->query($sql, array($from_date))->result();
        }

        $sql = "SELECT id, value_date, txn_posted_date, mode, description, transaction_amount
                FROM fees_bank_up_transactions
                WHERE value_date BETWEEN ? AND ?
                ORDER BY value_date DESC, id DESC";

        return $this->db->query($sql, array($from_date, $to_date))->result();
    }

    public function get_daily_fees($from_date, $to_date)
    {
        if ($from_date == $to_date) {
            $sql = "SELECT
                        f.sprfees_pk,
                        f.sprfees_studentid,
                        f.sprfees_amount,
                        f.sprfees_feesdate,
                        f.payment_mode,
                        concat_ws(' ', s.SPRIDEN_surname, s.SPRIDEN_student_name, s.SPRIDEN_father_name) as name
                    FROM sprfees f
                    LEFT JOIN spriden s ON s.id = f.sprfees_studentid
                    WHERE f.sprfees_feesdate = ?
                    ORDER BY f.sprfees_feesdate DESC, f.sprfees_pk DESC";

            return $this->db->query($sql, array($from_date))->result();
        }

        $sql = "SELECT
                    f.sprfees_pk,
                    f.sprfees_studentid,
                    f.sprfees_amount,
                    f.sprfees_feesdate,
                    f.payment_mode,
                    concat_ws(' ', s.SPRIDEN_surname, s.SPRIDEN_student_name, s.SPRIDEN_father_name) as name
                FROM sprfees f
                LEFT JOIN spriden s ON s.id = f.sprfees_studentid
                WHERE f.sprfees_feesdate BETWEEN ? AND ?
                ORDER BY f.sprfees_feesdate DESC, f.sprfees_pk DESC";

        return $this->db->query($sql, array($from_date, $to_date))->result();
    }

    public function update_fees_date($sprfees_pk, $student_id, $update_date, $current_date)
    {
        $sql = "UPDATE sprfees
                SET sprfees_feesdate = ?
                WHERE sprfees_pk = ?
                AND sprfees_studentid = ?
                AND sprfees_feesdate = ?";

        $this->db->query($sql, array(
            $update_date,
            $sprfees_pk,
            $student_id,
            $current_date
        ));

        return $this->db->affected_rows() > 0;
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fees_bank_upload_model extends CI_Model
{
    private $table = 'fees_bank_up_transactions';

    public function insert_transaction($data)
    {
        $old_debug = $this->db->db_debug;
        $this->db->db_debug = false;

        $ok = $this->db->insert($this->table, $data);
        $error = $this->db->error();

        $this->db->db_debug = $old_debug;

        if ($ok) {
            return array(
                'status' => 'inserted',
                'message' => 'Inserted'
            );
        }

        if (!empty($error['code']) && (int)$error['code'] === 1062) {
            return array(
                'status' => 'duplicate',
                'message' => 'Duplicate transaction.'
            );
        }

        return array(
            'status' => 'failed',
            'message' => !empty($error['message']) ? $error['message'] : 'Insert failed.'
        );
    }

    public function get_rows_by_group($group_id)
    {
        return $this->db
            ->where('group_id', $group_id)
            ->order_by('txn_posted_date', 'ASC')
            ->order_by('id', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    public function get_all_transactions()
    {
        return $this->db
            ->order_by('uploaded_at', 'DESC')
            ->order_by('id', 'DESC')
            ->get('fees_bank_up_transactions')
            ->result_array();
    }

    public function get_filtered_transactions($filters = array())
    {
        if (!empty($filters['transaction_id'])) {
            $this->db->like('transaction_id', trim($filters['transaction_id']));
        }

        if (!empty($filters['value_date'])) {
            $this->db->where('value_date', $filters['value_date']);
        }

        if (!empty($filters['uploaded_at'])) {
            $this->db->where('DATE(uploaded_at)', $filters['uploaded_at']);
        }

        return $this->db
            ->order_by('uploaded_at', 'DESC')
            ->order_by('id', 'DESC')
            ->get('fees_bank_up_transactions')
            ->result_array();
    }
    public function transaction_id_exists($transaction_id)
    {
        return $this->db
            ->where('transaction_id', $transaction_id)
            ->limit(1)
            ->count_all_results('fees_bank_up_transactions') > 0;
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manage_fees extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Manage_fees_model');

        if (!$this->session->userdata('oliveSchoolDashboardUser')) {
            redirect(base_url() . 'login');
        }
    }

    public function transactions()
    {
        $today = date('Y-m-d');

        $data['from_date'] = $today;
        $data['to_date'] = $today;
        $data['bank_transactions'] = $this->Manage_fees_model->get_bank_transactions($today, $today);
        $data['daily_fees'] = $this->Manage_fees_model->get_daily_fees($today, $today);

        $this->load->view('manage_fees/transactions', $data);
    }

    public function reconciliation_report()
    {
        $today = date('Y-m-d');

        $data['from_date'] = $today;
        $data['to_date'] = $today;
        $data['bank_transactions'] = $this->Manage_fees_model->get_bank_transactions($today, $today);
        $data['daily_fees'] = $this->Manage_fees_model->get_daily_fees($today, $today);

        $this->load->view('manage_fees/reconciliation_report', $data);
    }

    public function get_bank_transactions()
    {
        $dates = $this->validated_dates();
        if (!$dates['status']) {
            return $this->json_response($dates);
        }

        $rows = $this->Manage_fees_model->get_bank_transactions($dates['from_date'], $dates['to_date']);

        return $this->json_response(array(
            'status' => true,
            'html' => $this->bank_rows_html($rows),
            'total' => $this->format_amount($this->bank_total($rows))
        ));
    }

    public function get_daily_fees()
    {
        $dates = $this->validated_dates();
        if (!$dates['status']) {
            return $this->json_response($dates);
        }

        $rows = $this->Manage_fees_model->get_daily_fees($dates['from_date'], $dates['to_date']);

        return $this->json_response(array(
            'status' => true,
            'html' => $this->daily_rows_html($rows),
            'total' => $this->format_amount($this->daily_total($rows))
        ));
    }

    public function get_reconciliation_report()
    {
        $dates = $this->validated_dates();
        if (!$dates['status']) {
            return $this->json_response($dates);
        }

        $bank_rows = $this->Manage_fees_model->get_bank_transactions($dates['from_date'], $dates['to_date']);
        $daily_rows = $this->Manage_fees_model->get_daily_fees($dates['from_date'], $dates['to_date']);

        return $this->json_response(array(
            'status' => true,
            'bank_html' => $this->bank_rows_html($bank_rows),
            'bank_total' => $this->format_amount($this->bank_total($bank_rows)),
            'daily_html' => $this->daily_report_rows_html($daily_rows),
            'daily_total' => $this->format_amount($this->daily_total($daily_rows))
        ));
    }

    public function update_fees_date($sprfees_pk = null)
    {
        $student_id = $this->input->post('student_id');
        $update_date = $this->input->post('update_date');
        $current_date = $this->input->post('current_date');

        if (!$sprfees_pk || !$student_id || !$update_date || !$current_date) {
            return $this->json_response(array(
                'status' => false,
                'message' => 'All date update fields are required'
            ));
        }

        if (!$this->valid_date($update_date) || !$this->valid_date($current_date)) {
            return $this->json_response(array(
                'status' => false,
                'message' => 'Please select a valid date'
            ));
        }

        $updated = $this->Manage_fees_model->update_fees_date($sprfees_pk, $student_id, $update_date, $current_date);

        return $this->json_response(array(
            'status' => $updated,
            'message' => $updated ? 'Fees date updated successfully' : 'Unable to update fees date'
        ));
    }

    private function validated_dates()
    {
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        if (!$from_date || !$to_date) {
            return array('status' => false, 'message' => 'From Date and To Date are required');
        }

        if (!$this->valid_date($from_date) || !$this->valid_date($to_date)) {
            return array('status' => false, 'message' => 'Please select valid dates');
        }

        if (strtotime($from_date) > strtotime($to_date)) {
            return array('status' => false, 'message' => 'From Date should not be greater than To Date');
        }

        return array('status' => true, 'from_date' => $from_date, 'to_date' => $to_date);
    }

    private function valid_date($date)
    {
        $parsed = DateTime::createFromFormat('Y-m-d', $date);
        return $parsed && $parsed->format('Y-m-d') === $date;
    }

    private function bank_rows_html($rows)
    {
        if (empty($rows)) {
            return '<tr><td colspan="4" class="text-center">No records found</td></tr>';
        }

        $html = '';
        foreach ($rows as $row) {
            $html .= '<tr>';
            $html .= '<td>' . html_escape($row->value_date) . '</td>';
            $html .= '<td>' . html_escape($row->txn_posted_date) . '</td>';
            $html .= '<td>' . html_escape($row->mode) . '</td>';
            $html .= '<td class="text-right">' . $this->format_amount($row->transaction_amount) . '</td>';
            $html .= '</tr>';
        }

        return $html;
    }

    private function daily_rows_html($rows)
    {
        if (empty($rows)) {
            return '<tr><td colspan="4" class="text-center">No records found</td></tr>';
        }

        $html = '';
        foreach ($rows as $row) {
            $html .= '<tr>';
            $html .= '<td>' . html_escape($row->name) . '</td>';
            $html .= '<td class="text-right">' . $this->format_amount($row->sprfees_amount) . '</td>';
            $html .= '<td><input type="date" class="form-control form-control-sm daily-fees-date" value="' . html_escape($row->sprfees_feesdate) . '" data-id="' . html_escape($row->sprfees_pk) . '" data-student-id="' . html_escape($row->sprfees_studentid) . '" data-current-date="' . html_escape($row->sprfees_feesdate) . '"></td>';
            $html .= '<td>' . html_escape($row->payment_mode) . '</td>';
            $html .= '</tr>';
        }

        return $html;
    }

    private function daily_report_rows_html($rows)
    {
        if (empty($rows)) {
            return '<tr><td colspan="4" class="text-center">No records found</td></tr>';
        }

        $html = '';
        foreach ($rows as $row) {
            $html .= '<tr>';
            $html .= '<td>' . html_escape($row->name) . '</td>';
            $html .= '<td class="text-right">' . $this->format_amount($row->sprfees_amount) . '</td>';
            $html .= '<td>' . html_escape($row->sprfees_feesdate) . '</td>';
            $html .= '<td>' . html_escape($row->payment_mode) . '</td>';
            $html .= '</tr>';
        }

        return $html;
    }

    private function bank_total($rows)
    {
        $total = 0;
        foreach ($rows as $row) {
            $total += (float) $row->transaction_amount;
        }

        return $total;
    }

    private function daily_total($rows)
    {
        $total = 0;
        foreach ($rows as $row) {
            $total += (float) $row->sprfees_amount;
        }

        return $total;
    }

    private function format_amount($amount)
    {
        return 'Rs. ' . number_format((float) $amount, 2);
    }

    private function json_response($response)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}

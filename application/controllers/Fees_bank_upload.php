<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class Fees_bank_upload extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('Fees_bank_upload_model', 'bankModel');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('upload', 'session'));
    }

    public function index()
    {
        $this->load->view('fees_bank_upload/index');
    }

    public function upload()
    {
        if (empty($_FILES['bank_file']['name'])) {
            return $this->json_response(false, 'Please select file.');
        }

        $upload_path = FCPATH . 'uploads/bank_files/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $config = array(
            'upload_path'   => $upload_path,
            'allowed_types' => 'xls|xlsx',
            'max_size'      => 10240,
            'encrypt_name'  => true
        );

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('bank_file')) {
            return $this->json_response(false, strip_tags($this->upload->display_errors()));
        }

        $file = $this->upload->data();
        $file_path = $file['full_path'];

        $group_id = $this->generate_uuid();

        $inserted = 0;
        $failed = 0;
        $duplicates = 0;
        $total_read = 0;
        $errors = array();
        $seen_transactions = array();

        try {
            $spreadsheet = IOFactory::load($file_path);
            $sheet = $spreadsheet->getActiveSheet();

            $headerInfo = $this->get_header_map($sheet);

            if (!$headerInfo['status']) {
                return $this->json_response(false, $headerInfo['message']);
            }

            $header_row = $headerInfo['header_row'];
            $map = $headerInfo['map'];

            $highestRow = $sheet->getHighestRow();

            for ($row = $header_row + 1; $row <= $highestRow; $row++) {

                $transaction_id = $this->clean_text(
                    $this->get_cell_value($sheet, $map['transaction_id'], $row)
                );

                if ($transaction_id === '') {
                    continue;
                }

                $total_read++;

                if ($this->bankModel->transaction_id_exists($transaction_id)) {
                    $duplicates++;
                    continue;
                }

                if (isset($seen_transactions[$transaction_id])) {
                    $duplicates++;
                    continue;
                }

                $seen_transactions[$transaction_id] = true;
                $value_date_raw = $this->get_cell_value($sheet, $map['value_date'], $row);
                $value_date_fmt = $this->get_cell_formatted_value($sheet, $map['value_date'], $row);

                $posted_date_raw = $this->get_cell_value($sheet, $map['txn_posted_date'], $row);
                $posted_date_fmt = $this->get_cell_formatted_value($sheet, $map['txn_posted_date'], $row);

                $cheque_no = null;
                if (!empty($map['cheque_no'])) {
                    $cheque_no = $this->clean_cheque_no(
                        $this->get_cell_value($sheet, $map['cheque_no'], $row)
                    );
                }

                $description = $this->clean_text(
                    $this->get_cell_value($sheet, $map['description'], $row)
                );

                $mode = null;
                if (!empty($map['mode'])) {
                    $mode = $this->clean_text(
                        $this->get_cell_value($sheet, $map['mode'], $row)
                    );
                }

                $amount = $this->clean_amount(
                    $this->get_cell_value($sheet, $map['transaction_amount'], $row)
                );

                $value_date = $this->parse_excel_date($value_date_raw, $value_date_fmt);
                $txn_posted_date = $this->parse_excel_datetime($posted_date_raw, $posted_date_fmt);

                if (!$value_date || !$txn_posted_date || $description === '' || $amount === null) {
                    $failed++;
                    $errors[] = array(
                        'row' => $row,
                        'transaction_id' => $transaction_id,
                        'message' => 'Required data missing or invalid date/amount format.'
                    );
                    continue;
                }

                $uploaded_by = $this->session->userdata('user_id');
                $uploaded_by = !empty($uploaded_by) ? $uploaded_by : null;

                $data = array(
                    'group_id'           => $group_id,
                    'transaction_id'     => $transaction_id,
                    'value_date'         => $value_date,
                    'txn_posted_date'    => $txn_posted_date,
                    'cheque_no'          => $cheque_no,
                    'description'        => $description,
                    'mode'               => $mode,
                    'transaction_amount' => $amount,
                    'uploaded_by'        => $uploaded_by
                );

                $result = $this->bankModel->insert_transaction($data);

                if ($result['status'] === 'inserted') {
                    $inserted++;
                } elseif ($result['status'] === 'duplicate') {
                    $duplicates++;
                } else {
                    $failed++;
                    $errors[] = array(
                        'row' => $row,
                        'transaction_id' => $transaction_id,
                        'message' => $result['message']
                    );
                }
            }

            return $this->json_response(true, 'File uploaded successfully.', array(
                'group_id'    => $group_id,
                'total_read'  => $total_read,
                'inserted'    => $inserted,
                'failed'      => $failed,
                'duplicates'  => $duplicates,
                'errors'      => array_slice($errors, 0, 20),
                'details_url' => site_url('fees-bank-upload/rows/' . $group_id)
            ));
        } catch (Exception $e) {
            return $this->json_response(false, $e->getMessage());
        }
    }
    public function report()
    {
        $filters = array(
            'transaction_id' => $this->input->get('transaction_id', true),
            'value_date'     => $this->input->get('value_date', true),
            'uploaded_at'    => $this->input->get('uploaded_at', true)
        );

        $data['filters'] = $filters;
        $data['transactions'] = $this->bankModel->get_filtered_transactions($filters);

        $this->load->view('fees_bank_upload/report', $data);
    }
    public function rows($group_id = null)
    {
        if (empty($group_id)) {
            show_404();
        }

        $data['group_id'] = $group_id;
        $data['transactions'] = $this->bankModel->get_rows_by_group($group_id);

        $this->load->view('fees_bank_upload/rows', $data);
    }

    private function get_header_map($sheet)
    {
        $highestRow = min($sheet->getHighestRow(), 20);
        $highestCol = $sheet->getHighestColumn();

        $required = array(
            'transaction_id',
            'value_date',
            'txn_posted_date',
            'description',
            'transaction_amount'
        );

        $aliases = array(
            'transaction_id'     => array('transactionid'),
            'value_date'         => array('valuedate'),
            'txn_posted_date'    => array('txnposteddate', 'transactionposteddate', 'posteddate'),
            'cheque_no'          => array('chequeno', 'chequeno'),
            'description'        => array('description', 'particulars', 'narration'),
            'mode'               => array('crdr', 'mode'),
            'transaction_amount' => array('transactionamountinr', 'transactionamount', 'amount')
        );

        for ($row = 1; $row <= $highestRow; $row++) {
            $map = array();

            foreach (range('A', $highestCol) as $col) {
                $header = $this->normalize_header($sheet->getCell($col . $row)->getValue());

                if ($header === '') {
                    continue;
                }

                foreach ($aliases as $field => $names) {
                    if (in_array($header, $names)) {
                        $map[$field] = $col;
                    }
                }
            }

            $missing = array();

            foreach ($required as $field) {
                if (empty($map[$field])) {
                    $missing[] = $field;
                }
            }

            if (empty($missing)) {
                if (empty($map['cheque_no'])) {
                    $map['cheque_no'] = null;
                }

                if (empty($map['mode'])) {
                    $map['mode'] = null;
                }

                return array(
                    'status' => true,
                    'header_row' => $row,
                    'map' => $map
                );
            }
        }

        return array(
            'status' => false,
            'message' => 'Valid header row not found. Required headers are Transaction ID, Value Date, Txn Posted Date, Description, Transaction Amount(INR).'
        );
    }

    private function get_cell_value($sheet, $col, $row)
    {
        if (empty($col)) {
            return null;
        }

        $cell = $sheet->getCell($col . $row);

        try {
            return $cell->getCalculatedValue();
        } catch (Exception $e) {
            return $cell->getValue();
        }
    }

    private function get_cell_formatted_value($sheet, $col, $row)
    {
        if (empty($col)) {
            return null;
        }

        return $sheet->getCell($col . $row)->getFormattedValue();
    }

    private function normalize_header($value)
    {
        $value = strtolower(trim((string)$value));
        return preg_replace('/[^a-z0-9]/', '', $value);
    }

    private function clean_text($value)
    {
        if ($value === null) {
            return '';
        }

        return trim(preg_replace('/\s+/', ' ', (string)$value));
    }

    private function clean_cheque_no($value)
    {
        $value = $this->clean_text($value);

        if ($value === '' || $value === '-') {
            return null;
        }

        return $value;
    }

    private function clean_amount($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = str_replace(array(',', '₹', 'INR', ' '), '', (string)$value);

        if (!is_numeric($value)) {
            return null;
        }

        return number_format((float)$value, 2, '.', '');
    }

    private function parse_excel_date($raw, $formatted = null)
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        if (is_numeric($raw)) {
            return ExcelDate::excelToDateTimeObject($raw)->format('Y-m-d');
        }

        $text = $this->clean_text($formatted ?: $raw);

        $formats = array('d/m/Y', 'd-m-Y', 'Y-m-d', 'm/d/Y');

        foreach ($formats as $format) {
            $dt = DateTime::createFromFormat($format, $text);
            if ($dt instanceof DateTime) {
                return $dt->format('Y-m-d');
            }
        }

        return null;
    }

    private function parse_excel_datetime($raw, $formatted = null)
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        if (is_numeric($raw)) {
            return ExcelDate::excelToDateTimeObject($raw)->format('Y-m-d H:i:s');
        }

        $text = $this->clean_text($formatted ?: $raw);

        if (preg_match('/\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}\s+\d{1,2}:\d{2}(:\d{2})?\s*(AM|PM)?/i', $text, $m)) {
            $text = trim($m[0]);
        }

        $formats = array(
            'd/m/Y h:i:s A',
            'd/m/Y h:i A',
            'd/m/Y H:i:s',
            'd/m/Y H:i',
            'd-m-Y h:i:s A',
            'd-m-Y H:i:s',
            'Y-m-d H:i:s'
        );

        foreach ($formats as $format) {
            $dt = DateTime::createFromFormat($format, $text);
            if ($dt instanceof DateTime) {
                return $dt->format('Y-m-d H:i:s');
            }
        }

        return null;
    }

    private function generate_uuid()
    {
        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    private function json_response($status, $message, $data = array())
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array_merge(array(
                'status' => $status ? 'success' : 'error',
                'message' => $message
            ), $data)));
    }
}

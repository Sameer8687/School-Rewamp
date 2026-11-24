<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('parseBalanceInstallment')) {

    function parseBalanceInstallment($balanceInstallmentString)
    {
        $result = [];

        if (
            $balanceInstallmentString === null ||
            $balanceInstallmentString === false ||
            trim((string)$balanceInstallmentString) === ''
        ) {
            return $result;
        }

        $records = explode(',', trim((string)$balanceInstallmentString));

        foreach ($records as $record) {

            $record = trim($record);

            if ($record == '') {
                continue;
            }

            $parts = explode('->', $record);

            $parts = array_map('trim', $parts);

            while (count($parts) < 5) {
                $parts[] = '';
            }

            if (count($parts) > 5) {
                $parts = array_slice($parts, 0, 5);
            }

            $academicYearId   = $parts[0];
            $academicYearName = $parts[1];

            $totalPaid = str_replace(',', '', $parts[2]);
            $totalFees = str_replace(',', '', $parts[3]);
            $balance   = str_replace(',', '', $parts[4]);

            $totalPaid = is_numeric($totalPaid) ? (float)$totalPaid : 0;
            $totalFees = is_numeric($totalFees) ? (float)$totalFees : 0;
            $balance   = is_numeric($balance) ? (float)$balance : ($totalFees - $totalPaid);

            if (
                $academicYearId == '' &&
                $academicYearName == '' &&
                $totalPaid == 0 &&
                $totalFees == 0 &&
                $balance == 0
            ) {
                continue;
            }

            $result[] = [
                'academic_year_id'   => $academicYearId,
                'academic_year_name' => $academicYearName,
                'total_paid'         => $totalPaid,
                'total_fees'         => $totalFees,
                'balance'            => $balance
            ];
        }

        return $result;
    }
}
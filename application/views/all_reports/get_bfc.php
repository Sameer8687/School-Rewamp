<?php
defined('BASEPATH') or exit('No direct script access allowed');

/* ---------- FUNCTIONS ---------- */

function numberToWords($num)
{
    $num = (int) $num;

    if ($num <= 0) {
        return '';
    }

    $ones = [
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen'
    ];

    $tens = [
        2 => 'Twenty',
        3 => 'Thirty',
        4 => 'Forty',
        5 => 'Fifty',
        6 => 'Sixty',
        7 => 'Seventy',
        8 => 'Eighty',
        9 => 'Ninety'
    ];

    if ($num < 20) {
        return $ones[$num];
    }

    if ($num < 100) {

        $ten = floor($num / 10);
        $one = $num % 10;

        return (isset($tens[$ten]) ? $tens[$ten] : '') .
            ($one ? ' ' . $ones[$one] : '');
    }

    if ($num < 1000) {

        return $ones[floor($num / 100)] . ' Hundred' .
            (($num % 100) ? ' and ' . numberToWords($num % 100) : '');
    }

    if ($num < 100000) {

        return numberToWords(floor($num / 1000)) . ' Thousand' .
            (($num % 1000) ? ' ' . numberToWords($num % 1000) : '');
    }

    if ($num < 10000000) {

        return numberToWords(floor($num / 100000)) . ' Lakh' .
            (($num % 100000) ? ' ' . numberToWords($num % 100000) : '');
    }

    return '';
}

function dobInWords($dateInput)
{
    if (empty($dateInput) || $dateInput == '0000-00-00') {
        return '';
    }

    $timestamp = strtotime($dateInput);

    if ($timestamp === false) {
        return '';
    }

    $day = (int) date('j', $timestamp);
    $month = (int) date('n', $timestamp);
    $year = (int) date('Y', $timestamp);

    $ordinals = [
        1 => "First",
        2 => "Second",
        3 => "Third",
        4 => "Fourth",
        5 => "Fifth",
        6 => "Sixth",
        7 => "Seventh",
        8 => "Eighth",
        9 => "Ninth",
        10 => "Tenth",
        11 => "Eleventh",
        12 => "Twelfth",
        13 => "Thirteenth",
        14 => "Fourteenth",
        15 => "Fifteenth",
        16 => "Sixteenth",
        17 => "Seventeenth",
        18 => "Eighteenth",
        19 => "Nineteenth",
        20 => "Twentieth",
        21 => "Twenty First",
        22 => "Twenty Second",
        23 => "Twenty Third",
        24 => "Twenty Fourth",
        25 => "Twenty Fifth",
        26 => "Twenty Sixth",
        27 => "Twenty Seventh",
        28 => "Twenty Eighth",
        29 => "Twenty Ninth",
        30 => "Thirtieth",
        31 => "Thirty First"
    ];

    $months = [
        1 => "January",
        2 => "February",
        3 => "March",
        4 => "April",
        5 => "May",
        6 => "June",
        7 => "July",
        8 => "August",
        9 => "September",
        10 => "October",
        11 => "November",
        12 => "December"
    ];

    return $ordinals[$day] . ' ' . $months[$month] . ' ' . numberToWords($year);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Bonafide Certificate</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">

    <?php include 'application/views/cssPages.php'; ?>

    <style>
        body {
            background: #f5f5f5;
        }

        .certificate {
            background: #fff;
            border: 2px solid #000;
            padding: 30px;
        }

        .border-line {
            border-bottom: 1px solid #000;
            padding: 2px 10px;
            font-weight: 600;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #fff;
            }

            .certificate {
                box-shadow: none;
            }
        }
    </style>

</head>

<body>

    <?php include 'application/views/topNav.php'; ?>
    <?php include 'application/views/sidebar.php'; ?>

    <div class="container mt-4">

        <!-- PRINT BUTTON -->
        <div class="no-print mb-3">
            <button class="btn btn-primary btn-sm" onclick="window.print()">
                Print
            </button>
        </div>

        <!-- CERTIFICATE -->
        <div class="certificate">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center">

                <img src="<?= base_url('images/logoblack.png') ?>"
                    style="height:120px; width:110px; border:2px solid black; border-radius:10px;">

                <div class="text-center">
                    <h2 class="fw-bold">Olive English School</h2>
                    <p class="mb-1">National Highway No.3, Near Traffic Police Station, Dhule</p>

                    <h5 class="bg-dark text-white px-3 py-2 rounded">
                        BONAFIDE AND CHARACTER CERTIFICATE
                    </h5>
                </div>

                <div style="height:120px; width:110px; border:1px solid black;"></div>

            </div>

            <!-- GR NO + DATE -->
            <div class="d-flex justify-content-between mt-4">
                <div>
                    <strong>Gr No :</strong>
                    <span class="border-line"><?= $record['spriden_gr_number'] ?></span>
                </div>

                <div>
                    <strong>Date :</strong>
                    <span class="border-line"><?= date('d/m/Y') ?></span>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="mt-4" style="font-size:16px; line-height:28px;">

                <p>
                    This is to certify that Master / Miss
                    <span class="border-line">
                        <?= $record['SPRIDEN_student_name'] . ' ' . $record['SPRIDEN_father_name'] . ' ' . $record['SPRIDEN_surname'] ?>
                    </span>
                    is / was a bonafide student of this school.
                </p>

                <p>
                    He / She is studying in Standard
                    <span class="border-line"><?= $record['stvclas_standard'] ?></span>
                    Division
                    <span class="border-line"><?= $record['stvclas_class'] ?></span>
                    in the academic year
                    <span class="border-line"><?= $record['acdm_year'] ?></span>.
                </p>

                <p>
                    His / Her date of birth is
                    <span class="border-line">
                        <?= date('d/m/Y', strtotime($record['spriden_birthdate'])) ?>
                    </span>
                    (In words):
                    <span class="border-line">
                        <?= dobInWords($record['spriden_birthdate']) ?>
                    </span>
                </p>

                <p>
                    He / She is regular in attendance and bears good moral character.
                </p>

            </div>

            <!-- SIGNATURE -->
            <div class="text-end mt-5">
                <h5>Principal</h5>
            </div>

        </div>

    </div>

    <?php include 'application/views/jsPages.php'; ?>

</body>

</html>
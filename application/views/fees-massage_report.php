<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Student Lists</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php include 'application/views/cssPages.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<style>
    .buttons-excel {
        padding: 0px 5px 0px 5px !important;
        background-color: #53769c !important;
        color: #fff !important;
    }

    .buttons-pdf {
        padding: 0px 5px 0px 5px !important;
        background-color: #53769c !important;
        color: #fff !important;
    }

    .buttons-print {
        padding: 0px 5px 0px 5px !important;
        background-color: #53769c !important;
        color: #fff !important;
    }

    .paginate_button {
        padding: 0px 5px 0px 5px !important;
    }

    .dataTables_info {
        padding: 0px 5px 0px 5px !important;
    }

    div.dataTables_wrapper div.dataTables_filter input {
        padding: 0px !important;
        padding-left: 5px !important;
        width: 75% !important;
    }

    .no-gutterss .col-md-2 {
        margin-left: 0px !important;
        margin-right: 0px !important;
    }

    .table-responsive::-webkit-scrollbar {
        height: 10px !important;
        color: #53769c !important;
    }

    .dataTables_wrapper .dt-buttons {
        float: right;
    }

    div.dataTables_wrapper div.dataTables_paginate {
        margin: 0;
        white-space: nowrap;
        text-align: right;
        padding: 0;
    }

    table.dataTable {
        clear: both;
        margin-top: 0px !important;
        margin-bottom: 0px !important;
        max-width: none !important;
        border-collapse: separate !important;
        border-spacing: 0;
        padding: 0px;
    }

    .pcoded-content {
        padding: 10px !important;
    }

    .pc-container {
        position: relative;
        top: 35px;
    }

    .form-group {
        margin-bottom: 0.5rem !important;
    }

    ::-webkit-scrollbar {
        width: 5px;
        height: 1px;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #53769c;
        border-radius: 20px;
    }

    .card {
        margin-bottom: 0px !important;
    }

    .card-body {
        padding: 5px !important;
        padding: var(--bs-card-spacer-y) var(--bs-card-spacer-x);
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: 5px;
        margin-left: 5px;
        --bs-gutter-x: 1rem !important;
    }

    .sm-mt-5 {
        margin-top: 0px;
    }

    @media screen and (max-width: 768px) {
        .sm-mt-5 {
            margin-top: 40px !important;
        }
    }

    /* WhatsApp button style */
    .wa-btn {
        color: #fff !important;
        background-color: #25D366;
        border: none;
        padding: 2px 6px;
        border-radius: 3px;
        text-decoration: none;
        font-size: 11px;
    }

    .wa-btn:hover {
        color: #fff !important;
        background-color: #128C7E;
    }
</style>

<body class="">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <?php include 'application/views/sidebar.php'; ?>
    <?php include 'application/views/topNav.php'; ?>
    <div class="pc-container">
        <div class="pcoded-content">
            <div class="row p-2">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center">All Fees Report</h5>
                    </div>
                    <div class="card-body" style="overflow-x: auto;">
                        <style>
                            .table tr td,
                            .table tr th {
                                padding: 5px !important;
                                font-size: 11px !important;
                            }

                            .text-capitalize {
                                background-color: #53769c !important;
                                color: #fff !important;
                            }
                        </style>
                        <table id="simpletable" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-capitalize text-center">#</th>
                                    <th class="text-capitalize text-center">Student ID</th>
                                    <th class="text-capitalize text-center">Name</th>
                                    <th class="text-capitalize text-center">Class</th>
                                    <th class="text-capitalize text-center">Balance</th>
                                    <th class="text-capitalize text-center">Paid</th>
                                    <th class="text-capitalize text-center">Total</th>
                                    <th class="text-capitalize text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table">
                                <?php
                                $i = 1;

                                $first = $student_fees[0];

                                $name = $first['SPRIDEN_student_name'] . ' ' . $first['SPRIDEN_surname'];
                                $mobile = $first['stvpcnf_whatsapp'];

                                $message = "This is a reminder regarding the fees for your ward, $name.\n\n";

                                foreach ($student_fees as $row) {

                                    $year = $row['stvacdm_year'];
                                    $class = $row['STVCLAS_standard'] . '-' . $row['STVCLAS_class'];

                                    $message .= "Year $year (Class $class)\n";
                                    $message .= "Total Fees: {$row['stvfstr_fees']}\n";
                                    $message .= "Paid Amount: {$row['total_paid']}\n";
                                    $message .= "-----------------------------\n";
                                    $message .= "Balance Fees: {$row['balance']}\n\n";
                                }

                                $message .= "Kindly clear the pending fees before the result on 1st May.\n\n";
                                $message .= "बराय मेहरबानी 1 मई को रिजल्ट है, इस से पहले बाकी फीस अदा करें।\n\n";
                                $message .= "Thanks for your co-operation.\n";
                                $message .= "Olive English School.\n";
                                $message .= "Dhule.";

                                $encodedMessage = urlencode($message);

                                // table loop
                                foreach ($student_fees as $fees) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $i++; ?></td>
                                        <td class="text-center"><?= $fees['SPRIDEN_student_id'] ?></td>
                                        <td class="text-center">
                                            <?= $fees['SPRIDEN_student_name'] . ' ' . $fees['SPRIDEN_surname'] .' '. $fees['SPRIDEN_father_name']; ?></td>
                                        <td class="text-center">
                                            <?= $fees['STVCLAS_standard'] . '-' . $fees['STVCLAS_class']; ?></td>
                                        <td class="text-center"><?= $fees['balance']; ?></td>
                                        <td class="text-center"><?= $fees['total_paid']; ?></td>
                                        <td class="text-center"><?= $fees['stvfstr_fees']; ?></td>
                                        <td class="text-center">
                                            <?php if (!empty($fees['stvpcnf_whatsapp'])): ?>
                                                <a class="wa-btn"
                                                    href="https://wa.me/91<?= $mobile ?>?text=<?= $encodedMessage ?>"
                                                    target="_blank">
                                                    Send WhatsApp
                                                </a>
                                            <?php else: ?>
                                                <span class="text-danger" style="font-size:10px;">No Number</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
    <?php include 'application/views/jsPages.php'; ?>

</body>

</html>
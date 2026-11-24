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
            <div class="row sm-mt-5">

                <div class="col-md-2">
                    <select class="form-control form-control-sm" name="std_filter_" id="std_filter_">
                        <option value="">Select</option>
                        <?php foreach ($classes as $class): ?>
                            <option <?= $current_class == $class['stvfees_class'] ? 'selected' : '' ?>
                                value="<?= $class['stvfees_class'] ?>"><?= $class['stvfees_class'] ?></option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-control form-control-sm" name="div_filter_" id="div_filter_">
                        <option value="">Select</option>
                        <option <?= $current_div == 'A' ? 'selected' : '' ?> value="A">A</option>
                        <option <?= $current_div == 'B' ? 'selected' : '' ?> value="B">B</option>
                        <option <?= $current_div == 'C' ? 'selected' : '' ?> value="C">C</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-control form-control-sm" name="cur_acdm" id="cur_acdm">
                        <option value="">Select</option>
                        <?php foreach ($academic_years as $academic_year): ?>
                            <option <?= $current_acdm == $academic_year['stvacdm_id'] ? 'selected' : '' ?>
                                value="<?= $academic_year['stvacdm_id']; ?>"><?= $academic_year['stvacdm_year']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-sm btn-outline-secondary" type="button"
                        onclick="filter_data()">Apply</button>
                </div>
            </div>

            <div class="row p-2">
                <div class="card">
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
                                    <th class="text-capitalize text-center">Sr.No.</th>
                                    <th class="text-capitalize text-center">Student ID</th>
                                    <th class="text-capitalize text-center">Name</th>
                                    <th class="text-capitalize text-center">class</th>
                                    <th class="text-capitalize text-center">Balance</th>
                                    <th class="text-capitalize text-center">paid</th>
                                    <th class="text-capitalize text-center">Total</th>
                                    <th class="text-capitalize text-center">Whatsapp</th>
                                </tr>
                            </thead>
                            <style>
                                #simpletable tbody tr td {
                                    padding: 5px;
                                    font-size: 12px;
                                    font-weight: 500;
                                }
                            </style>
                            <tbody id="table">
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
    </div>
    </div>
    <?php include 'application/views/jsPages.php'; ?>

    <script>
        var BASE_URL = '<?php echo base_url(); ?>';
        var dt = null; // Global datatable variable

        function filter_data() {

            let std = $('#std_filter_').val();
            let div = $('#div_filter_').val();
            let cur_acdm = $('#cur_acdm').val();

            // 🔥 FIX: Use POST and send data object
            $.ajax({
                url: BASE_URL + 'Welcome/send_msg',
                type: "POST", // Changed to POST
                dataType: "json",
                // 🔥 FIX: Send the filter data
                data: {
                    std: std,
                    div: div,
                    cur_acdm: cur_acdm
                },
                success: function (response) {

                    $('#simpletable').DataTable().clear().destroy(); // Fix for reinitialise error

                    let tbody = "";
                    $.each(response, function (index, item) {

                        let balance_amount = item.balance ?? 0;
                        let paid_amount = item.total_paid ?? 0;
                        let total_fees = item.total_fees ?? 0;

                        tbody += `
        <tr>
            <td>${index + 1}</td>
            <td>${item.SPRIDEN_student_id}</td>
            <td>${item.name}</td>
            <td>${item.STVCLAS_standard}-${item.STVCLAS_class}</td>
            <td>${balance_amount}</td> 
            <td>${paid_amount}</td>
            <td>${total_fees}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="openFeesDetail('${item.id}')" target="_blank">View </button>
            </td>
        </tr>
    `;
                    });

                    $("#simpletable tbody").html(tbody);

                    // Reinitialize DataTable
                    $('#simpletable').DataTable({
                        "pageLength": 25,
                        "ordering": true
                    });

                }
            });
        }

        function openFeesDetail(id) {
            // Open in new tab with GET parameter
            window.open(BASE_URL + 'Welcome/fees_details_view?id=' + id, '_blank');
        }
    </script>

</body>

</html>
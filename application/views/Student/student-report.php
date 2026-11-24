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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php include 'application/views/cssPages.php'; ?>
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
            <div class="row">
                <div class="col-md-2">
                    <select class="form-control form-control-sm" name="acdm_year_filter_" id="acdm_year_filter_">
                        <option value="">Select</option>
                        <?php foreach ($academic_years as $academic_year) : ?>
                            <option <?= $current_acdm == $academic_year['stvacdm_id'] ? 'selected' : ''  ?> value="<?= $academic_year['stvacdm_id']; ?>"><?= $academic_year['stvacdm_year']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control form-control-sm" name="status_filter_" id="status_filter_">
                        <option value="">Select</option>
                        <option <?= $current_status == 'ALL' ? 'selected' : '' ?> value="ALL">All</option>
                        <?php foreach ($all_status as $sts) : ?>
                            <option <?= $current_status == $sts['spriden_student_status'] ? 'selected' : ''  ?> value="<?= $sts['spriden_student_status']; ?>"><?= $sts['spriden_student_status']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control form-control-sm" name="std_filter_" id="std_filter_">
                        <option value="">Select</option>
                        <?php foreach ($classes as $class): ?>
                            <option <?= $current_class == $class['stvfees_class'] ? 'selected' : ''  ?> value="<?= $class['stvfees_class'] ?>"><?= $class['stvfees_class'] ?></option>
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
                <div class="col-md-3">
                    <button class="btn btn-sm btn-outline-secondary" type="button" onclick="filter_data()">Apply</button>
                </div>
            </div>

            <div class="row p-2">
                <div class="card">
                    <div class="card-body">
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
                                    <th class="text-capitalize">Sr.No.</th>
                                    <th class="text-capitalize">ID</th>
                                    <th class="text-capitalize">Name</th>
                                    <th class="text-capitalize">Std</th>
                                    <th class="text-capitalize">Division</th>
                                    <th class="text-capitalize">Status</th>
                                    <th class="text-capitalize">Gr.No</th>
                                    <th class="text-capitalize">Reg.No</th>
                                    <th class="text-capitalize">Aadhar.No</th>
                                    <th class="text-capitalize">DOB</th>
                                </tr>
                            </thead>
                            <style>
                                #simpletable tbody tr td {
                                    padding: 5px;
                                    font-size: 12px;
                                    font-weight: 500;
                                }
                              
                            </style>
                            <tbody>
                                <?php
                                $i = 1;
                                $flag = 0;
                                foreach ($students as $student) {
                                    $status_name = '';
                                    $status_color = '';
                                    if ($student['spriden_student_status'] == "A") {
                                        $status_name = 'A';
                                        $status_color = 'success';
                                    } else if ($student['spriden_student_status'] == "I") {
                                        $status_name = 'I';
                                        $status_color = 'danger';
                                    } else if ($student['spriden_student_status'] == "N") {
                                        $status_name = 'N';
                                        $status_color = 'primary';
                                    } else if ($student['spriden_student_status'] == null) {
                                        $status_name = 'null';
                                        $status_color = 'warning';
                                    } else {
                                        $status_name = '-';
                                        $status_color = 'secondary';
                                    }
                                ?>
                                    <tr class="text-center">
                                        <td><?= $i; ?></td>
                                        <td><?= $student['id'] . " - " . $student['SPRIDEN_student_id']; ?></td>
                                        <td class="text-left"><?= $student['getfullname(s.id)']; ?></td>
                                        <td><?= $student['STVCLAS_standard']; ?></td>
                                        <td><?= $student['STVCLAS_class']; ?></td>
                                        <td class="badge bg-<?= $status_color ?> text-white rounded" ><?= $status_name ?></td>
                                        <td><?= $student['spriden_gr_number'] ?></td>
                                        <td><?= $student['SPRIDEN_reg_number'] ?></td>
                                        <td><?= $student['SPRIDEN_aadhar_no'] ?></td>
                                        <td><?= $student['SPRIDEN_birthdate'] ?></td>
                                    </tr>
                                <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    </div>
    </div>
    <?php include 'application/views/jsPages.php'; ?>
    <script>
        function filter_data(tabid) {
            let acdm_year = document.querySelector('#acdm_year_filter_').value;
            let status = document.querySelector('#status_filter_').value;
            let std = document.querySelector('#std_filter_').value;
            let div = document.querySelector('#div_filter_').value;
            if (acdm_year != '' && status != '' && std != '' && div != '') {
                window.location = `<?= base_url() ?>student/students?acdm-year=${acdm_year}&status=${status}&std=${std}&div=${div}`;
            }
        }
    </script>
</body>

</html>
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - LC Report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <?php include 'application/views/cssPages.php'; ?>
</head>
<style>
    .pc-container {
        top: 0px !important;
    }

    .pc-container .pcoded-content {
        margin-top: 0px !important;
        padding-top: 15px !important;
    }
</style>

<body>
    <?php include 'application/views/topNav.php'; ?>
    <?php include 'application/views/sidebar.php'; ?>
    <div class="pc-container">
        <div class="pcoded-content">
            <div class="row mobile-wali-row">
                <div class="col-md-12">
                    <div class="card" style="margin-bottom:0;height:520px;overflow:auto;">
                        <input type="hidden" id="hidden_status">

                        <div class="card-header">

                            <div class="col-md-3">
                                <div class="row">
                                    <label class="col-md-5">Academic Year</label>

                                    <select name="year" id="year" class="form-control form-control-sm col-md-7">
                                        <option value="">Select Year</option>

                                        <?php foreach ($years as $y) { ?>
                                            <option value="<?= $y['stvacdm_id']; ?>"
                                                <?= ($this->input->get('year') == $y['stvacdm_id']) ? 'selected' : ''; ?>>
                                                <?= $y['stvacdm_year']; ?>
                                            </option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="row">
                                    <label class="col-md-5">Standard</label>

                                    <select name="std" id="std" class="form-control form-control-sm col-md-7">
                                        <option value="">Select Class</option>

                                        <?php foreach ($classes as $c) { ?>
                                            <option value="<?= $c['stvfees_class']; ?>"
                                                <?= ($this->input->get('std') == $c['stvfees_class']) ? 'selected' : ''; ?>>
                                                <?= $c['stvfees_class']; ?>
                                            </option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-sm btn-primary" onclick="filterReport()">
                                    <i class="fa fa-search"></i> View
                                </button>
                            </div>

                        </div>

                        <div cla ss="card-body" style="padding:3px;">
                            <style>
                                .tab le tr td,
                                .table tr th {
                                    padding: 5px !important;
                                    font-size: 11px !important;
                                    cursor: pointer
                                }

                                .text-capitalize {
                                    background: #53769c !important;
                                    color: #fff !important
                                }

                                .dt-button.buttons-excel,
                                .dt- button.buttons-pdf {
                                    background: #263544 !important;
                                    color: #fff !important;
                                    padding: 2px 10px !important
                                }
                            </style>
                            <div class=" print-container">
                                <div class="col-md-12 mt-2">
                                    <div class="dt-responsive table-responsive">
                                        <table id="lcReportTable" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student ID</th>
                                                    <th>Name</th>
                                                    <th>Class</th>
                                                    <th>Status</th>
                                                    <th>Mobile</th>
                                                    <th>WhatsApp</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1;
                                                foreach ($records as $row) { ?>
                                                    <input type="hidden" name="year" id="year"
                                                        value="<?= $row['STVCLAS_acdm'] ?>">
                                                    <tr>
                                                        <td><?= $i++ ?></td>
                                                        <td><?= $row['SPRIDEN_student_id'] ?></td>
                                                        <td><?= $row['name'] ?></td>
                                                        <td><?= $row['STVCLAS_standard'] ?></td>
                                                        <td><?= $row['spriden_student_status'] ?></td>
                                                        <td><?= $row['stvpcnf_mobile'] ?></td>
                                                        <td><?= $row['stvpcnf_whatsapp'] ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-success"
                                                                data-id="<?= $row['id'] ?>"
                                                                data-acdm="<?= $row['STVCLAS_acdm'] ?>"
                                                                data-token="<?= $row['token'] ?? '' ?>"
                                                                onclick="viewLC(this)" target="_blank">View</button>
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
                </div>
            </div>
        </div>
    </div>

    <?php include 'application/views/jsPages.php'; ?>
    <script>
        $(document).ready(function () {
            // DataTable init with pagination & header filters
            var table = $('#lcReportTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [],
                dom: 'Bfrtip',
                buttons: ['excel', 'pdf', 'print'
                ],
            });
        });

        function filterReport() {

            var year = document.getElementById("year").value;
            var std = document.getElementById("std").value;

            window.location.href =
                "<?= base_url('Lc_certificate'); ?>?year=" +
                encodeURIComponent(year) +
                "&std=" +
                encodeURIComponent(std);
        }

        function viewLC(button) {
            var id = button.getAttribute('data-id');
            var acdm = button.getAttribute('data-acdm');
            var token = button.getAttribute('data-token');
            
            window.location.href = "<?= base_url() ?>Lc_certificate/view_lc?student-id=" + id + "&year=" + acdm + "&token=" + token;
        }

    </script>
</body>

</html>
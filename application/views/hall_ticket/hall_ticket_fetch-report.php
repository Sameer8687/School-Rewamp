<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Issue LC</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Phoenixcoded" />
    <!---Include CSS Files-->
    <?php include 'application/views/cssPages.php'; ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/id-card.css" />
    <style>
        .pc-container {
            top: 0px !important;
        }

        .border {
            border: 1px solid;
        }

        .table tr td,
        .table tr th {
            padding: 5px !important;
            font-size: 11px !important;
            cursor: pointer;
        }
    </style>
</head>

<body class="">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <?php include 'application/views/sidebar.php'; ?> <?php include
            'application/views/topNav.php'; ?>
    <div class="pc-container">
        <div class="pcoded-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Hall Ticket Report</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">

                                        <!-- 1 -->
                                        <div class="col-md-2">
                                            <div class="row">
                                                <label for="" class="col-md-5">Class <span
                                                        class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7" id="class">
                                                    <option value="">Select Class</option>
                                                    <?php foreach ($classes as $class): ?>
                                                        <option value="<?= $class['stvfees_class'] ?>">
                                                            <?= $class['stvfees_class'] ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- 2 -->
                                        <div class="col-md-2">
                                            <div class="row">
                                                <label for="" class="col-md-5">Div <span
                                                        class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7" id="div">
                                                    <option value="">Select Division</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="row">
                                                <label for="" class="col-md-5">Exam <span
                                                        class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7" id="exam">
                                                    <option value="">Select Exam</option>
                                                    <?php foreach ($tests as $tes): ?>
                                                        <option value="<?= $tes['stvexam_id'] ?>">
                                                            <?= $tes['stvexam_name'] ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-5">Academic Year <span
                                                        class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7" id="acdm">
                                                    <option value="">Select Academic Year</option>

                                                    <?php foreach ($academic_years as $academic_year): ?>
                                                        <option <?= $selected_academic_year == $academic_year['stvacdm_id'] ? 'selected' : ''; ?> value="<?= $academic_year['stvacdm_id'] ?>">
                                                            <?= $academic_year['stvacdm_year']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- 3 -->
                                        <div class="col-md-2">

                                            <button class="btn btn-sm btn-outline-primary" id="btnView">View</button>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                id="btnReset">Reset</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="simpletable" data-custom="true"
                                            class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Class</th>
                                                    <th class="text-center">Shift</th>
                                                    <th class="text-center">Exam Name</th>
                                                    <th class="text-center">Created By</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
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

    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>
    <script>
        $(document).ready(function () {
            $('#btnView').on('click', function () {
                let acdm = $('#acdm').val();
                let std = $('#class').val();
                let div = $('#div').val();
                let exam = $('#exam').val();

                $.ajax({
                    url: '<?= base_url("hall_ticket/getHallTicketRecords") ?>',
                    type: 'POST',
                    data: { acdm: acdm, std: std, div: div, exam: exam },
                    dataType: 'json',
                    beforeSend: function () {
                        $('#reportBody').html('<tr><td colspan="6" class="text-center text-info">Loading...</td></tr>');
                    },
                    success: function (response) {
                        let html = '';
                        if (response.length > 0) {
                            $.each(response, function (i, record) {
                                html += `<tr>
                            <td class="text-center">${i + 1}</td>
                            <td class="text-center">${record.stvhtkt_class}</td>
                            <td class="text-center">${record.shift}</td>
                            <td class="text-center">${record.stvexam_name}</td>
                            <td class="text-center">${record.spruser_name}</td>
                            <td class="text-center">
                                <a href="<?= base_url('hall_ticket') ?>?__id=${record.stvhtkt_id}" class="text-info mr-2">Edit</a>
                                <a href="javascript:void(0)" class="text-danger" onclick="deleteRecord(${record.stvhtkt_id})">Delete</a>
                            </td>
                        </tr>`;
                            });
                        } else {
                            html = '<tr><td colspan="6" class="text-center">No records found</td></tr>';
                        }
                        $('#reportBody').html(html);
                    },
                    error: function () {
                        $('#reportBody').html('<tr><td colspan="6" class="text-center text-danger">Error fetching data</td></tr>');
                    }
                });
            });

            $('#btnReset').on('click', function () {
                $('#acdm, #class, #div, #exam').val('');
                $('#reportBody').html('<tr><td colspan="6" class="text-center">No records found</td></tr>');
            });
        });
    </script>
    <script>
        $("#table-gr").DataTable({
            "pageLength": 25,
            dom: "Bfrtip",
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        function deleteRecord(id) {
            if (confirm('Do you want to delete this hall ticket record?')) {
                window.location = "<?= base_url() ?>hall_ticket/delete_hall_ticket/" + id;
            }
        }
    </script>
</body>

</html>
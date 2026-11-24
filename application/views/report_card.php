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
                                <h5 class="card-title">Students Report Card</h5>
                            
                        </div>
                        <div class="card-body">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <div class="row">
                                                <label for="" class="col-md-5">Class
                                                    <span class="float-right">:</span></label>

                                                <select name="class" id="class"
                                                    class="form-control form-control-sm col-md-7">
                                                    <option value="0">Select Class</option>
                                                    <?php foreach ($classes as $clas) { ?>
                                                        <option value="<?= $clas['stvfees_class'] ?>">
                                                            <?= $clas['stvfees_class'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="row">
                                                <label for="" class="col-md-5">Div
                                                    <span class="float-right">:</span></label>

                                                <select name="division" id="division"
                                                    class="form-control form-control-sm col-md-7">

                                                    <option value="0">Division</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>

                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="row">
                                                <label for="" class="col-md-5">Exam
                                                    <span class="float-right">:</span></label>

                                                <select name="exam_name" id="exam_name"
                                                    class="form-control form-control-sm col-md-7">
                                                    <option value="">Select Year</option>
                                                    <?php foreach ($all_exams as $all_exam) { ?>
                                                        <option value="<?= $all_exam['stvexam_id']; ?>">
                                                            <?= $all_exam['stvexam_name']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-6">Academic year
                                                    <span class="float-right">:</span></label>

                                                <select name="acdm_year" id="acdm_year"
                                                    class="form-control form-control-sm col-md-6">
                                                    <option value="">Select Year</option>
                                                    <?php foreach ($academic_years as $acdm) { ?>
                                                        <option <?php echo $selected_academic_year == $acdm['stvacdm_id'] ? 'selected' : ''; ?>
                                                            value="<?= $acdm['stvacdm_id'] ?>">
                                                            <?= $acdm['stvacdm_year'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-2">

                                            <button class="btn btn-sm btn-outline-secondary" id="btn">View</button>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10"
                                        id="table_container">
                                        <table id="example" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">#</th>
                                                    <th style="width: 30%;">Name</th>
                                                    <th style="width: 10%;">ID</th>
                                                    <th style="width: 5%;">Subjects</th>
                                                    <th style="width: 5%;">Total</th>
                                                    <th style="width: 5%;">(%)</th>
                                                   
                                                    <th style="width: 10%;">Action</th>
                                                </tr>
                                            </thead>
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
        $('#btn').click(function () {

            var std = $('#class').val();
            var div = $('#division').val();
            var exam = $('#exam_name').val();
            var acdm_year = $('#acdm_year').val();

            if (std == "0") {
                alert("Select Class");
                return false;
            }

            if (acdm_year == "") {
                alert("Select Academic Year");
                return false;
            }

            $.ajax({
                url: "<?php echo base_url(); ?>index.php/Report_card/get_report_card_data",
                method: "POST",
                data: { std: std, div: div, exam: exam, acdm_year: acdm_year },
                dataType: "JSON",

                success: function (response) {

                    let data = response.report_card_data;

                    if (data.length == 0) {
                        $('#table_container').html('<p>No Data Found</p>');
                        return;
                    }

                    let subjects = [];

                    $.each(data[0], function (key, value) {

                        if (key != 'sprmark_student_id' && key != 'student_name' && key != 'exam_type') {
                            subjects.push(key);
                        }

                    });


                    // ---------- TABLE START ----------
                    let html = '';

                    html += '<table id="simpletable" class="table table-bordered table-striped table-sm">';

                    // ---------- THEAD ----------
                    html += '<thead>';
                    html += '<tr>';

                    html += '<th>#</th>';
                    html += '<th>Name</th>';

                    $.each(subjects, function (i, sub) {
                        html += '<th>' + sub + '</th>';
                    });

                    html += '<th>Total</th>';
                    html += '<th>(%)</th>';
                    html += '<th>Action</th>';

                    html += '</tr>';
                    html += '</thead>';


                    // ---------- TBODY ----------
                    html += '<tbody>';

                    $.each(data, function (index, row) {

                        html += '<tr>';

                        html += '<td>' + (index + 1) + '</td>';
                        html += '<td>' + row.student_name + '</td>';

                        let total = 0;
                        let total_marks = 0;

                        $.each(subjects, function (i, sub) {

                            let val = row[sub];

                            if (!val || val === '-') {
                                html += '<td>-</td>';
                                return;
                            }

                            html += '<td>' + val + '</td>';

                            // SAFE SPLIT
                            let parts = val.split('/');

                            if (parts.length === 2) {
                                let mark = parseFloat(parts[0]) || 0;
                                let tmark = parseFloat(parts[1]) || 0;
                                console.log('marks: ' + mark);
                                console.log('total marks: ' + tmark);

                                total += mark;
                                total_marks += tmark;
                            }
                        });

                        // SAFE PERCENT CALCULATION
                        let percent = 0;
                        if (total_marks > 0) {
                            percent = ((total / total_marks) * 100).toFixed(2);
                        }

                        html += '<td>' + total + '/' + total_marks + '</td>';
                        html += '<td>' + percent + '%</td>';

                        html += '<td><a href="<?= base_url('Report_card/view_report_card') ?>/'
                            + row.id + '/' + acdm_year + '/' + std + '/' + div + '/' + exam +
                            '" target="_blank">View</a></td>';

                        html += '</tr>';

                    });

                    html += '</tbody>';
                    html += '</table>';

                    // render table
                    $('#table_container').html(html);

                }

            });

        });
    </script>

</html>
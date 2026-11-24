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
                            <h5 class="card-title">Syllabus Report</h5>

                        </div>
                        <div class="card-body">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">

                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-6">Teachers Name
                                                    <span class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-6" name="teacher_id"
                                                    id="teacher_id">
                                                    <option value="">Select</option>
                                                    <?php
                                                    foreach ($teachers as $teacher) {
                                                        ?>
                                                        <option value="<?php echo $teacher['SPRIDEN_student_id']; ?>">
                                                            <?php echo $teacher['SPRIDEN_surname'] . " " . $teacher['SPRIDEN_student_name'] . " " . $teacher['SPRIDEN_father_name']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="row">
                                                <label for="" class="col-md-5">Class
                                                    <span class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7" name="std"
                                                    id="std" onchange="GetSub(this)">
                                                    <option value="">Select</option>
                                                    <?php foreach ($classes as $classs) { ?>
                                                        <option value="<?php echo $classs['stvfees_class']; ?>">
                                                            <?php echo $classs['stvfees_class']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="row">
                                                <label for="" class="col-md-5">Div
                                                    <span class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7" name="div"
                                                    id="div">
                                                    <option value="">Select</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="row">
                                                <label for="" class="col-md-5">Sub
                                                    <span class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7" name="sub"
                                                    id="sub">
                                                    <option value="">Select</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-2">

                                            <button class="btn btn-sm btn-outline-secondary" type="button"
                                                onclick="GetData()" id="view_button">View</button>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="table-gr" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Title</th>
                                                    <th>Unit</th>
                                                    <th>Std</th>
                                                    <th>Subject</th>
                                                    <th>Div</th>
                                                    <th>Teaching_date</th>
                                                    <th>Status</th>
                                                    <th>Teacher_id</th>

                                                </tr>
                                            </thead>

                                            <tbody id="slbs_report">
                                                
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
        $("#table-gr").DataTable({
            "pageLength": 25,
            dom: "Bfrtip",
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    </script>

    <script>
        let GetSub = (e) => {
            let std = e.value;

            // Validate input
            if (!std) {
                $("#sub").html('<option value="">Select</option>');
                return;
            }

            $.ajax({
                url: "<?= base_url('index.php/Report/fetch_sub') ?>",
                method: "GET",
                data: {
                    std: std
                },
                dataType: "json",
                beforeSend: function () {
                    // Show loading state
                    $("#sub").html('<option value="">Loading...</option>');
                },
                success: function (data) {
                    let str = '<option value="">Select</option>';

                    if (data && data.length > 0) {
                        data.forEach(element => {
                            str += `<option value="${element.stvsubj_id}">${element.stvsubj_subject}</option>`;
                        });
                    } else {
                        str = '<option value="">No subjects found</option>';
                    }

                    $("#sub").html(str);
                },
            });
        }
    </script>

    <script>
        let GetData = () => {

            let teacher = document.getElementById('teacher_id').value;
            let std = document.getElementById('std').value;
            let sub = document.getElementById('sub').value;
            let div = document.getElementById('div').value;

            $.ajax({
                url: "<?= base_url('index.php/report/fetch_data') ?>",
                method: "GET",
                data: {
                    teacher: teacher,
                    std: std,
                    sub: sub,
                    div: div
                },
                dataType: "json",
                success: function (data) {

                    let str = ``;
                    let rowIdx = 0;
                    if (data.length > 0) {

                        data.forEach(element => {
                            str += ` <tr id="R${++rowIdx}">
                                                        <td>${rowIdx}</td>

                                                        <td>${element.title}</td>
                                                        <td>Unit-${element.unit}</td>
                                                        <td>${element.std}</td>
                                                        <td>${element.subject}</td>
                                                        <td>${element.div}</td>
                                                        <td>${element.teaching_date}</td>
                                                        <td>${element.status}</td>
                                                        <td>${element.teacher_id}</td>
                                                    </tr>`
                        });
                    } else {
                        str = `<tr><td colspan="8" class="text-center">No data available</td></tr>`
                    }

                    $("#slbs_report").html(str);
                }
            })

        }
    </script>

</html>
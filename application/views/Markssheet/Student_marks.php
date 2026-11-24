<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Subject Master</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <!---Include CSS Files-->
    <?php include 'application/views/cssPages.php'; ?>
</head>

<body class="">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!--Include Sidebar -->
    <?php include 'application/views/sidebar.php'; ?>
    <!--Include Top Navbar-->

    <?php include 'application/views/topNav.php'; ?>
    <!-- [ Main Content ] start -->

    <div class="pc-container">
        <form action="<?php echo base_url(); ?>index.php/Marks/insert_marks" method="post">
            <input type="hidden" name="row_max" id="row_max" value="0">
            <div class="pcoded-content">
                <!-- [ breadcrumb ] start -->

                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->

                <div class="row" id="alertRow">
                    <div class="col-md-12">
                        <?php
                        $message = $this->session->userdata('message');
                        $alert_class = $this->session->userdata('alert_class');
                        if ($message != "") {
                        ?>
                            <div class="alert <?= $alert_class; ?>">
                                <?= $message; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row margin-top-minus-40 mobile-margin-top-minus-10" style="margin-right:0px; ">
                    <div class="col-md-12">
                        <div class="card pb-0 mb-0">
                            <div class="card-header mb-0 pb-2 ">

                                <div class="row w-100">
                                    <div class="col-md-6">
                                        <h5 class="pt-2">Students Marks</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-secondary btn-sm float-right"> <i class="fas fa-bars"></i> <span id="dynamicLabelAddButton">Add Data</span></button>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body pb-0 mb-0">
                                <style>
                                    label {
                                        text-align: right;
                                    }

                                    .no-margin {
                                        margin-top: -10px;
                                    }
                                </style>
                                <div class="row" style="margin-right: 10px;">
                                    <div class="col-md-5">
                                        <div class="form-group row">
                                            <label for="" class="col-md-5">Academic Year<span class="text-danger">*</span>
                                                :</label>
                                            <select name="year" id="year" class="form-control form-control-sm col-md-6">
                                                <option value="">Select Academic Year</option>


                                                <?php
                                                foreach ($academic_years as $academic_year) :
                                                ?>
                                                    <option <?php echo $selected_academic_year == $academic_year['stvacdm_id'] ? 'selected' : ''; ?>
                                                        value="<?php echo $academic_year['stvacdm_id']; ?>">
                                                        <?php echo $academic_year['stvacdm_year']; ?></option>
                                                <?php endforeach; ?>


                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group row"> <label for="" class="col-md-5">STD<span class="text-danger">*</span>
                                                :</label>
                                            <select class="form-control form-control-sm col-md-6" name="class" id="class" required>
                                                <option value="">Select STD</option>
                                                <?php
                                                foreach ($classes as $class) {
                                                ?>
                                                    <option value="<?= $class['stvfees_class']; ?>">
                                                        <?= $class['stvfees_class']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="" class="col-md-5">Division<span class="text-danger">*</span>
                                                :</label>
                                            <select required class="form-control form-control-sm col-md-6" name="division" id="division">
                                                <option selected disabled value="">Select Division
                                                </option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                            </select>
                                        </div>
                                    </div>


                                </div>

                                <div class="row" style="margin-right: 10px;">

                                    <div class="col-md-5">
                                        <div class="form-group row">
                                            <label for="" class="col-md-5">Exam Name<span class="text-danger">*</span>
                                                :</label>
                                            <select name="exam" id="exam" class="form-control form-control-sm col-md-7">
                                                <option value="">Select exam</option>

                                                <?php
                                                foreach ($exams as $ex) :
                                                ?>
                                                    <option value="<?= $ex['stvexam_id']; ?>">
                                                        <?= $ex['stvexam_name']; ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                    </div>


                                    <!-- <?php print_r($sub['0']); ?> -->
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="" class="col-md-6">Subject Name<span class="text-danger">*</span>
                                                :</label>
                                            <select name="subject" id="subject" class="form-control form-control-sm col-md-6">
                                                <option value="">Select Subject</option>

                                                <?php

                                                foreach ($stvsubj as $subj) :
                                                ?>
                                                    <option value="<?= $subj['stvsubj_id']; ?>">
                                                        <?= $subj['stvsubj_subject']; ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group row" style="padding-left: 60px;">
                                            <button id="find_marks" type="button" class="btn btn-sm btn-outline-secondary" value="">View </button>
                                        </div>
                                        <!-- <div class="select-box" style="width: 65%;">
                                        </div> -->
                                    </div>
                                </div>
                                <div class="row" style="margin-right: 10px;">
                                    <div class=" col-md-4">
                                        <div class="form-group row">
                                            <label for="" class="col-md-6">Total Marks
                                                :</label>
                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="Total_marks" id="Total_marks" style="border: none; background-color:#f9fcff30; font-weight: bold;">
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="" class="col-md-5">Marks
                                                :</label>
                                            <input readonly type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="marks" id="passing_marks" style="border: none; background-color:#f9fcff30; font-weight: bold;">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- [ Search List ] Start -->
                            <div class="search-list-container-panel">
                                <div class="list-group" id="dynamic_name_list"></div>
                            </div>
                            <!-- [ Search List ] End -->
                        </div>
                    </div>
                </div>
                <div class="row mt-1" style="margin-right: 0px;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- <input type="hidden" name="total_marks" id="total_marks"
                                            value="<?= $stats != null ? $stats['marks'] : 0 ?>">
                                        <input type="hidden" name="marks" id="marks"
                                            value="<?= $stats != null ? count($stats['records']) : 0 ?>"> -->
                                        <div class="dt-responsive table-responsive margin-top-minus-10">
                                            <table id="simpletable" class="table table-striped table-bordered nowrap">
                                                <thead>

                                                    <tr>
                                                        <th>Sr.No.</th>
                                                        <th>Student ID</th>
                                                        <th>Name</th>
                                                        <th>Marks</th>

                                                    </tr>
                                                </thead>
                                                <style>
                                                    #simpletable tbody tr td {
                                                        padding: 5px;
                                                        font-size: 12px;
                                                        font-weight: 500;
                                                    }

                                                    #simpletable tbody tr td:hover {
                                                        cursor: pointer;
                                                    }
                                                </style>
                                                <tbody id="tbody">



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
        </form>
        <!-- [ Main Content ] end -->

    </div>
    </div>
    <!-- [ Main Content ] end -->
    </div>
    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>
    <script>
        $('.rowToUpdateData').on('click', function() {

            var rowID = $(this).attr('data-name');

            $.ajax({

                url: "<?= base_url(); ?>index.php/MasterForm/getSubjectDataByID",

                method: "POST",

                dataType: "JSON",

                data: {

                    id: rowID

                },

                success: function(data) {

                    if (data != "fail") {

                        $('#subject').val(data.stvsubj_subject);

                        $('#school_id').val(rowID);

                        $('#class').val(data.stvsubj_std);

                        $('#dynamicLabelAddButton').html("Update Data")
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('dynamicLabelAddButton').on('click', function() {
                $("dynamicLabelAddButton").attr("disabled", "disabled");
                var exam = $('exam').val();
                var stu = $('class').val();
                var subject = $('subject').val();
                var marks = $('marks').val();
                if (exam != "" && stu != "" && subject != "" && marks != "") {
                    $.ajax({
                        url: "<?= base_url(); ?>index.php/Marks/insert_marks",

                        type: "POST",

                        data: {
                            exam: exam,
                            stu: stu,
                            subject: subject,
                            marks: marks
                        },
                        cache: false,
                        success: function(data) {

                            if (data != "fail") {

                                $('#subject').val(data.stvsubj_subject);

                                $('#school_id').val(rowID);

                                $('#class').val(data.stvsubj_std);

                                $('#dynamicLabelAddButton').html("Update Data")
                            }
                        }
                    });
                }
            });
        });
    </script>

    <script>
        // $('#class').change(function() {
        //     var year = $('#year').val();
        //     var std = $('#class').val();
        //     $.ajax({
        //         url: "<?= base_url('index.php/Marks/get_all_academic_years') ?>",
        //         method: "GET",
        //         dataType: "JSON",
        //         data: {
        //             year: year,
        //             std: std
        //         },
        //         success: function(data) {

        //             // if (data != null) {
        //             //     var html = '<option>Select exam</option>';
        //             //     for (var count = 0; count < data.length; count++) {
        //             //         html += '<option value="' + data[count].stvexam_id + '">' + data[count]
        //             //             .stvexam_name + '</option>';
        //             //     }
        //             //     $('#exam').html(html);
        //             // }
        //         }
        //     })
        // });

        $('#class').change(function() {

            var std = $('#class').val();

            $.ajax({

                url: "<?= base_url('index.php/Marks/getAllSubjetcsBystd') ?>?std=" + std,

                method: "GET",

                dataType: "JSON",

                success: function(data) {

                    if (data != null) {

                        var html = '<option>Select Subject</option>';

                        for (var count = 0; count < data.length; count++) {

                            html += '<option value="' + data[count].stvsubj_id + '">' + data[count]
                                .stvsubj_subject + '</option>';

                        }

                        $('#subject').html(html);

                    }

                }



            })

        });




        $('#find_marks').click(function() {
            var year = $('#year').val();
            var std = $('#class').val();
            var divi = $('#division').val();
            var exam = $('#exam').val();
            var subject = $('#subject').val();

            $.ajax({
                url: "<?= base_url('index.php/Marks/marks') ?>",
                method: "GET",
                dataType: "JSON",
                data: {
                    year: year,
                    std: std,
                    divi: divi,
                    exam: exam,
                    subject: subject
                },
                beforeSend: function() {},
                success: function(data) {
                    var str = '';
                    var index = 0;
                    for (var i = 0; i < data.length; i++) {
                        str += `<tr> <td>${index++}</td> 
                  
                              <td><select name="student_id${index}" style="border:none;appearance: none;">
                              <option value="${data[i].st_id}">${data[i].spriden_student_id}</option></select></td>                                  
                <td>${data[i].name}</td>
                <td><input type="text" value="${data[i].marks}" name="marks${index}" id="marks${index}">
                <input type="hidden" value="${data[i].id}" name="id${index}" id="marks${index}">
                </td>          
                </tr>`
                    }
                    $("#row_max").val(index);
                    $("#tbody").html(str)
                }
            });

        });

        $("#find_marks").click(function() {

            var subject = $("#subject").val();

            $.ajax({

                url: "<?= base_url('index.php/Marks/exam_setup') ?>",
                method: "POST",
                dataType: "JSON",
                data: {
                    exam_setup: subject,
                },
                success: function(data) {
                    $("#Total_marks").val(data[0]['Total_marks'])
                    $("#passing_marks").val(data[0]['passing_marks'])
                }

            })

        })

        ("#dynamicLabelAddButton").click(function() {
            var year = $('#year').val();
            var std = $('#class').val();
            var divi = $('#division').val();
            var exam = $('#exam').val();
            var student_id = $("#")

            $.ajax({

                url: "<?= base_url('index.php/Marks/marks') ?>",
                method: "GET",
                dataType: "JSON",
                data: {
                    year: year,
                    std: std,
                    divi: divi,
                    exam: exam
                },

            })
        })
    </script>




</body>

</html>
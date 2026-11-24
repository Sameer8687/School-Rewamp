<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Teacher Home | Olive English School</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />

    <?php include 'application/views/cssPages.php'; ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/teacher-module.css">
</head>

<body class="">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <?php include 'sidebar.php'; ?>
    <!-- [ Header ] start -->
    <?php include 'topNav.php'; ?>
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <form action="" id="classCreateForm" method="post">
            <div class="pcoded-content">
                <div class="row mobile-margin-top-minus-10">
                    <!-- <div class="col-md-12 mb-2">
                        <a href="<?php echo base_url(); ?>dashboard" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-left"></i> Back</a>
                        <button id="createClassButton" type="submit" class="btn btn-info btn-sm"> <i class="fas fa-user-plus"></i> Mark Attendance</button>
                    </div> -->
                </div>
                <!-- [ Main Content ] start -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header ">
                                <h5 class="pb-0 pt-0">Student Remark</h5>
                            </div>
                            <div class="card-body p-2">
                                <style>
                                    #studentTable tr:hover {
                                        cursor: pointer;
                                    }

                                    #studentTable tr td,
                                    #studentTable th td {
                                        padding: 5px;
                                        font-size: 12px;
                                    }
                                </style>
                                <div class="table-responsive p-0 m-0">
                                    <table class="table table-bordered" id="studentTable">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>Class</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody">
                                            <?php
                                            for ($i = 0; $i < count($all_students); $i++) {
                                            ?>
                                                <tr data-toggle="modal" data-target="#exampleModal" class="addFeedbackByClick" data-id="<?php echo $all_students[$i]['STVCLAS_code'] ?>">
                                                    <td>
                                                        <?php echo $all_students[$i]['STVCLAS_rollno'] ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $all_students[$i]['SPRIDEN_surname'] . " " . $all_students[$i]['SPRIDEN_student_name'] . " " . $all_students[$i]['SPRIDEN_father_name']  ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $all_students[$i]['STVCLAS_standard'] ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ Main Content ] end -->
            </div>
        </form>
    </div>

    <!--Student Remark Modal-->
    <!-- Modal -->
    <style>
        #exampleModal .modal-dialog,
        #exampleModal .modal-dialog .modal-content,
        #exampleModal .modal-dialog .modal-content .modal-header {
            
            border-color: #53769c;
        }

        #exampleModal .modal-dialog .modal-content .modal-header {
            padding-top: 10px;
            padding-bottom: 10px;
            background: #ff9966;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to left, #ff5e62, #ff9966);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to left, #ff5e62, #ff9966);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        }

        #exampleModal .modal-dialog .modal-content .modal-header {
            text-align: center !important;
        }

        #exampleModal .modal-dialog .modal-content .modal-body .right-label {
            text-align: right;
        }

        #exampleModal .modal-dialog .modal-content .modal-body table tr {
            padding: 0px;
        }

        #exampleModal .modal-dialog .modal-content .modal-body table tr td {
            border: none;
            padding: 4px;
            font-size: 12px;
            font-weight: 400;
        }
    </style>
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Student Remark</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr>
                            <td class="right-label">Student ID : </td>
                            <td><input id="modal_stu_stuid" type="text" style="border: none;" readonly class="form-control form-control-sm"></td>
                        </tr>
                        <tr>
                            <td class="right-label">Roll No : </td>
                            <td><input id="modal_stu_roll" type="text" style="border: none;" readonly class="form-control form-control-sm"></td>
                        </tr>
                        <tr>
                            <td class="right-label">Student Name : </td>
                            <td><input id="modal_stu_name" type="text" style="border: none;" readonly class="form-control form-control-sm"></td>
                        </tr>

                        <tr>
                            <td class="right-label">Class : </td>
                            <td><input id="modal_stu_class" type="text" style="border: none;" readonly class="form-control form-control-sm"></td>
                            <td><input id="modal_stu_class_hidden" type="hidden" style="border: none;" readonly class="form-control form-control-sm"></td>
                        </tr>

                        <tr>
                            <td class="right-label">Remark : </td>
                            <td>
                                <textarea class="form-control form-control-sm" name="student_remark" id="student_remark" cols="30" rows="3"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="right-label"></td>
                            <td>
                                <button class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close"> <i class="fas fa-window-close"></i> Cancel</button>
                                <button class="btn btn-success btn-sm" type="button" id="add_remarkButton"> <i class="fas fa-edit"></i> Add Remark</button>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
    </div>
    <!-- Required Js -->
    <?php include 'application/views/jsPages.php'; ?>

    <script>
        $(document).on('click', '.addFeedbackByClick', function() {
            var rowID = $(this).attr("data-id");
            $.ajax({
                url: "<?= base_url(); ?>index.php/Dashboard/get_student_for_remark",
                data: {
                    rowID: rowID
                },
                method: "POST",
                dataType: "JSON",
                success: function(data) {
                    $('#modal_stu_roll').val(data.STVCLAS_rollno);
                    $('#modal_stu_stuid').val(data.SPRIDEN_student_id);
                    $('#modal_stu_name').val(data.SPRIDEN_surname + " " + data.SPRIDEN_student_name + " " + data.SPRIDEN_father_name);
                    $('#modal_stu_class').val(data.STVCLAS_standard + " - " + data.STVCLAS_class);
                    $('#modal_stu_class_hidden').val(data.STVCLAS_standard);
                }
            });
        });
        $('#add_remarkButton').click(function() {
            var student_id = $('#modal_stu_stuid').val();
            var remark_data = $('#student_remark').val();
            var std = $('#modal_stu_class_hidden').val();
            $.ajax({
                url: "<?= base_url(); ?>index.php/Dashboard/add_remark",
                data: {
                    student_id: student_id,
                    remark_data: remark_data,
                    std: std
                },
                method: "POST",
                beforeSend: function() {
                    $('#add_remarkButton').html('<i class="fa fa-spinner fa-spin"></i> Please Wait');
                },
                success: function(data) {
                    if (data != "fail") {
                        $('#add_remarkButton').html('<i class="fas fa-edit"></i> Add Remark');
                        alert("Remark Added Successfully");
                        window.location.reload();
                    } else {
                        $('#add_remarkButton').html('<i class="fas fa-edit"></i> Add Remark');
                        alert("Failed");

                    }
                }
            });
        });
    </script>
</body>

</html>
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
                    <div class="col-md-12 mb-2">
                        <a href="<?php echo base_url(); ?>dashboard" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-left"></i> Back</a>
                        <button id="createClassButton" type="submit" class="btn btn-info btn-sm"> <i class="fas fa-user-plus"></i> Mark Attendance</button>
                    </div>
                </div>
                <!-- [ Main Content ] start -->
                <div class="row teacher-module-container" style="margin: 0px; padding: 0px;">
                    <div class="col-md-12" style="margin: 0px; padding: 0px;">
                        <div class="card">
                            <div class="card-header pt-2">
                                <div class="grid2x2">
                                    <div class="box">
                                        <div class="form-group ml-1">
                                            <label for="">Date</label>
                                            <input type="date" class="form-control form-control-sm" name="attendancedate" id="attendancedate" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    <div class="box">
                                        <div class="form-group ml-1">
                                            <label for="">Standard</label>
                                            <select name="class" id="class" class="form-control form-control-sm">
                                                <option value="" selected disabled>Select Standard</option>
                                                <?php
                                                foreach ($allocated_class as $all_class) {
                                                ?>
                                                    <option value="<?= $all_class['SPATESD_std']; ?>"><?= $all_class['SPATESD_std']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="box mobile-margin-top-minus-20">
                                        <div class="form-group ml-1">
                                            <label for="">Division</label>
                                            <select name="division" id="division" class="form-control form-control-sm">
                                                <?php
                                                foreach ($allocated_class as $all_class) {
                                                ?>
                                                    <option value="<?= $all_class['SPATESD_div']; ?>"><?= $all_class['SPATESD_div']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="box mobile-margin-top-minus-20">
                                        <div class="form-group ml-1">
                                            <label for=""></label>
                                            <button type="button" id="findStudentsForAtt" class="btn btn-secondary btn-sm myBitton">Find Student</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-2 pt-1">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="studentTable">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Name</th>
                                                        <th>
                                                            <input type="checkbox" id="selectAll" value="allPresent"> P
                                                            <input type="checkbox" id="deselectAll" value="allAbsent"> A
                                                        </th>
                                                    </tr>
                                                </thead>
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
                <!-- [ Main Content ] end -->
            </div>
        </form>
    </div>
    <!-- [ Main Content ] end -->
    </div>
    <!-- Required Js -->
    <?php include 'application/views/jsPages.php'; ?>
    <script>
        $(document).ready(function() {
            var student_ids = [];
            $('#selectAll').click(function() {
                var isChecked = $(this).prop("checked");
                if (isChecked == true) {
                    $('#deselectAll').prop('checked', false);
                    $('#studentTable tr:has(td)').find('input[type="checkbox"]').prop('checked', isChecked);
                }
                //$('#studentTable tr:has(td)').find('input[type="checkbox"]').prop('checked', isChecked);
            });
            $('#deselectAll').click(function() {
                var isChecked = $(this).prop("checked");
                if (isChecked == true) {
                    $('#selectAll').prop('checked', false);
                    $('#studentTable tr:has(td)').find('input[type="checkbox"]').prop('checked', false);
                }
                //$('#studentTable tr:has(td)').find('input[type="checkbox"]').prop('checked', isChecked);
            });
        });
    </script>
    <script>
        $('#findStudentsForAtt').click(function() {
            var className = $('#class').val();
            var division = $('#division').val();
            var html = '';
            if (className == null && division == null) {
                alert("Select Class And Division");
                return false;
            } else if (className != null && division == null) {
                //Get Only By Std
            } else {
                //Get by All
                $.ajax({
                    url: "<?php echo base_url(); ?>index.php/Student/getStudentForAttendanceByAll",
                    method: "POST",
                    data: {
                        class: className,
                        divi: division
                    },
                    dataType: "JSON",
                    success: function(data) {
                        if (data != "fail") {
                            $('#selectAll').prop('checked', true);
                            for (var count = 0; count < data.length; count++) {
                                html += '<tr>';
                                html += '<td>' + data[count].STVCLAS_rollno + '</td>';
                                html += '<td>' + data[count].SPRIDEN_surname + ' ' + data[count].SPRIDEN_student_name + ' ' + data[count].SPRIDEN_father_name + '</td>';
                                html += '<td><input type="checkbox" value="' + data[count].SPRIDEN_student_id + '" checked name="student_row[]" id="" class="studentRowSelector"></td>';
                                html += '</tr>';
                            }
                            $('#tbody').html(html);
                        } else {
                            console.log("No Data");
                        }
                    }
                })
            }
        });
    </script>
    <script>
        $('#classCreateForm').on('submit', function(e) {
            student_ids = [];
            attendanceStatus = [];
            var std = $('#class option:selected').val();
            var division = $('#division option:selected').val();
            var dateOfAtt = $('#attendancedate').val();
            e.preventDefault();
            $('#tbody').find('input[type="checkbox"]').each(function() {
                if ($(this).prop("checked") == true) {
                    student_ids.push($(this).val());
                    attendanceStatus.push('P');
                } else {
                    student_ids.push($(this).val());
                    attendanceStatus.push('A');
                }
            });
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/Dashboard/fillStudentsAttendance",
                data: {
                    student_ids: student_ids,
                    std: std,
                    division: division,
                    attendanceStatus: attendanceStatus,
                    dateOfAtt: dateOfAtt
                },
                method: "POST",
                beforeSend: function() {
                    $('#createClassButton').html('<i class="fas fa-spinner fa-spin"></i> Please Wait');
                },
                success: function(data) {
                    $('#createClassButton').html('<i class="fas fa-user-plus"></i> Mark Attendance');
                    if (data != "fail") {
                        alert("Attendence Marked Successfully !!!");
                        window.location.reload();
                    } else {
                        swal("Failed to Mark Attendance");
                    }
                }
            });
        });
    </script>
</body>

</html>
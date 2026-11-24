<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title> | Olive English School</title>

    <!-- Meta -->

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />





    <?php include 'application/views/cssPages.php'; ?>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/teacher-module.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/new-db.css">

    <!-- Boxicons CSS -->

    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

</head>

<style>
    .pc-container .pcoded-content {
        padding-left: 30px;
        padding-right: 30px;
        padding-top: 6px;
    }

    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding: 23px;
    }
</style>

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




    <form id="insert_data">
        <input type="hidden" name="max_rows" id="max_rows" value="">
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Student
                        Attendance</h5>

                </div>

                <style>
                    #studentAttendanceTable tr td,

                    #studentAttendanceTable tr th {

                        padding: 0px;

                        font-size: 10px;

                        border: 1px solid #77777710 !important;

                    }
                    .table {
    width: 100%;
    margin-bottom: 0;
    color: #293240;
}
                </style>
                <!-- -------------changes----------------------- -->

                <!-- -----------changes----------- -->


                <div class="modal-body p-0 pb-0 mb-0">

                    <div class="grid2x2 p-1">

                        <div class="box">

                            <div class="form-group ml-1">

                                <label for="">Subject</label>
                                <select required class="form-control form-control-sm col-md-8" name="sub" id="sub">

                                    <option selected disabled value="">Select Subject</option>
                                    <?php
                                    foreach ($all_subjects as $sub) :
                                    ?>
                                        <option value="<?= $sub['stvsubj_id'] ?>"><?= $sub['stvsubj_subject'] ?></option>
                                    <?php endforeach; ?>
                                </select>


                            </div>

                        </div>

                        <div class="box">

                            <div class="form-group ml-1">

                                <label for="">Type</label>

                                <select class="form-control form-control-sm col-md-8" name="type" id="type">

                                    <option selected value="">Select Type</option>

                                    <option value="Oral">Oral</option>

                                    <option value="Written">Written</option>

                                </select>

                            </div>

                        </div>

                        <div class="box mobile-margin-top-minus-20">

                            <div class="form-group ml-1">

                                <label for="">Date</label>
                                <input class="form-control form-control-sm col-md-8" type="date" name="t_date" id="t_date" min="<?= date('Y-m-d') ?>">

                            </div>

                        </div>

                        <div class="box mobile-margin-top-minus-20">
                            <!-- -----------changes----------- -->

                            <div class="form-group ml-1">
                                <label for=""></label>
                                <button type="button" class="btn btn-sm myBitton my-cust-bg" id="view_btn" style="border-radius: 50px;color:#fff; background-color: #318eb9 !important;margin-right: 35px;"><i class="fas fa-eye mt-1"></i> View</button>
                                <!-- id="markAttendanceButton" -->
                                <button type="submit" class="btn btn-sm myBitton my-cust-bg" id="save_btn" style="border-radius: 50px;background-color: #26A69A !important; color:#fff;"><i class="fas fa-user-plus"></i> Save</button>

                            </div>
                            <!-- -------------changes----------------------- -->
                        </div>

                    </div>

                    <div class="table-responsive pb-0 mb-0 mobile-margin-top-minus-20">

                        <table class="table table-bordered pb-0 " id="studentAttendanceTable">

                            <thead>

                                <tr>

                                    <th>#</th>

                                    <th>Students</th>

                                    <th>Grade</th>

                                    <th>Comment</th>



                                </tr>

                            </thead>

                            <tbody id="track">



                            </tbody>

                        </table>

                    </div>

                </div>

            </div>
        </div>
    </form>
    <input type="hidden" name="" id="getFullScreenMode">

    <!-- Required Js -->

    <?php include 'application/views/jsPages.php'; ?>

    <script>
        // my js script
        $(document).ready(function() {
            $('#view').on('click', function() {
                var std = document.getElementById('sub').value;
                $.ajax({
                    url: "<?= base_url() ?>index.php/welcome/viewClass",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        std: std
                    },
                    success: function(result) {
                        displayData(result); // Call the function to display data
                    }
                });
            });

            // Function to display data in the table
        })
    </script>

    <script>
        $("#insert_data").on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "<?= base_url('dashboard/add_data') ?>",
                method: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(res) {
                    alert(res)
                    location.reload();
                }

            })

        })
    </script>

    <script>
        document.getElementById("view_btn").addEventListener("click", function() {
            console.log('clicked');
            let sub = document.getElementById('sub').value;
            let type = document.getElementById('type').value;
            let date = document.getElementById('t_date').value;

            $.ajax({
                url: "<?= base_url('dashboard/fetch_data') ?>",
                method: "GET",
                data: {
                    sub: sub,
                    type: type,
                    date: date,
                },
                dataType: "json",
                success: function(data) {
                    let str = ''
                    let i = 0
                    data.forEach(element => {
                        let comment = element.action_Comment == null ? '' : element.action_Comment
                        str += `<tr  id="${++i}">
                                
                                <input type="hidden" name="action_id${i}" id="action_id${i}" value="${element.action_id}">
                                <input type="hidden" name="student_id${i}" id="student_id${i}" value="${element.id}">
                                 <td>${i}</td>   
                                 <td>${element.name}</td>   
                                 <td><select  name="grade${i}" id="grade${i}">
                                 <option value="">Grade</option>
                                 <option value="100"${element.action_Grade == 100  ? 'selected' : ''} >100%</option>
                                 <option value="75" ${element.action_Grade == 75  ? 'selected' : ''}>75%</option>
                                 <option value="50" ${element.action_Grade == 50  ? 'selected' : ''}>50%</option>
                                 <option value="25" ${element.action_Grade == 25  ? 'selected' : ''}>25%</option>
                                 <option value="0" ${element.action_Grade == 0  ? 'selected' : ''}>0%</option>
                                 </select></td>   
                                 <td><input  type="text-box" name="comment${i}" id="comment${i}" value="${comment}" style="width: 100%;height: 18px !important;"></td>   
                                </tr>`
                    })

                    $("#max_rows").val(i)
                    $("#track").html(str)

                }
            })
        });
    </script>


</body>



</html>
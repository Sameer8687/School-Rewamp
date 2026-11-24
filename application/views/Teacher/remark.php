<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Olive English School - Teacher Registration Form</title>

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

        <form action="<?php echo base_url(); ?>Teacher/add_remark" enctype="multipart/form-data" method="post">

            <div class="pcoded-content">

                <!-- [ breadcrumb ] start -->

                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->



                <div class="row margin-top-minus-40  mb-2">

                    <div class="col-md-7">

                        <!-- <a href="" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a> -->

                        <button class="btn btn-secondary btn-sm"> <i class="fas fa-clipboard-check"></i> <span id="dynamicLabelAddButton">Add Remark</span></button>

                    </div>

                    <div class="col-md-5 student-form-search">

                        <input type="text" autocomplete="off" class="form-control form-control-sm" id="search_student_name" placeholder="Search Student Name">

                        <span class="left-icon"><i class="fas fa-search"></i></span>

                        <span class="right-icon" onclick="clearFormInputIcon()"><i class="fas fa-times"></i></span>

                    </div>

                </div>

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

                <div class="row">

                    <div class="col-md-12">

                        <div class="card">

                            <div class="card-header mb-0 pb-0  ">

                                <h5 class="margin-top-minus-10 mb-0 pb-0">Student Remark</h5>
                                

                            </div>

                            <div class="card-body pb-3">

                                <div class="row margin-top-minus-20">

                                    <div class="col-md-4">

                                        <style>

                                            .cust-cont {

                                                border: none;



                                            }



                                            .custom-details-row {

                                                margin-left: -48px;

                                            }



                                            @media only screen and (max-width: 1300px) and (min-width: 1200px) {

                                                .custom-details-row {

                                                    margin-left: -45px;

                                                }

                                            }



                                            /* 1920 - 1080 */

                                            @media screen and (min-width: 1900px) {

                                                .custom-details-row {

                                                    margin-left: -68px;

                                                }

                                            }

                                        </style>

                                        <div class="form-group row">

                                            <label for="" class="col-md-5 text-right">Student ID<span class="text-danger">*</span> :</label>

                                            <input readonly type="text" class="form-control form-control-sm col-md-7 cust-cont" name="student_id" id="student_id" required>

                                        </div>

                                    </div>

                                    <div class="col-md-8">

                                        <div class="form-group row">

                                            <label for="" class="col-md-2 text-right">Name :</label>

                                            <input readonly type="text" class="form-control form-control-sm col-md-10 cust-cont" name="student_name" id="student_name">

                                        </div>

                                    </div>

                                </div>

                                <div class="form-group row mobile-margin-top-minus-20">

                                    
                                
                                        

                                            <label for="" class="col-md-2 text-right">Remark<span class="text-danger">*</span> :</label>

                                            <input type="text" class="form-control form-control-sm col-md-10" name="remark_data" id="remark_data" required>

                                       

                                       

                                   

                                </div>

                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="dt-responsive table-responsive ">

                                            <table id="simpletable" class="table table-striped table-bordered nowrap">

                                                <thead>

                                                    <tr>

                                                        <th>Sr.No.</th>

                                                        <th>ID</th>

                                                        <th>Student Name</th>

                                                        <th>Remark</th>

                                                        <th>Date</th>

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

                                                <tbody>

                                                    <?php

                                                    $i = 1;

                                                    foreach ($all_remark_data as $all_remark) {

                                                    ?>

                                                        <tr data-toggle="modal" data-target="#exampleModal" data-name="<?= $all_remark['stvrmrk_id']; ?>" class="rowToUpdateData">

                                                            <td><?= $i; ?></td>

                                                            <td><?= $all_remark['stvrmrk_student_id']; ?></td>

                                                            <td><?= $all_remark['SPRIDEN_surname'] . " " . $all_remark['SPRIDEN_student_name'] . " " . $all_remark['SPRIDEN_father_name']; ?></td>

                                                            <td><?= $all_remark['stvrmrk_remark']; ?></td>

                                                            <td><?= $all_remark['stvrmrk_date']; ?></td>



                                                        </tr>

                                                    <?php $i++;

                                                    } ?>

                                                </tbody>

                                            </table>

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

            </div>

        </form>

        <!-- [ Main Content ] end -->

    </div>







    </div>

    <!-- [ Main Content ] end -->

    </div>



    <!-- Modal -->

    <div class="modal fade" id="exampleModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header p-3" style="background-color: #53769c;">

                    <h5 class="modal-title text-white" id="exampleModalLabel">Remark Feedback</h5>

                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12">

                            <p class="margin-top-minus-10">

                                Student ID :

                                <input type="text" id="modal_student_id" style="border: none;" readonly>

                                <input type="hidden" id="modal_row_id" style="border: none;">

                            </p>

                            <p class="margin-top-minus-10">

                                Name : <span id="modal_student_name"></span>

                            </p>

                            <p class="margin-top-minus-10">

                                Remark :

                            </p>

                            <p class="margin-top-minus-10" id="remark_data_display">



                            </p>

                            <hr>

                        </div>

                        <div class="col-md-12">

                            <div class="form-group">

                                <label for="">Write Feedback : </label>

                                <textarea id="feedback_data" name="feedback_data" id="" cols="30" rows="5" class="form-control form-control-sm"></textarea>

                            </div>

                        </div>

                        <div class="col-md-12">

                            <button id="add_feedback_button" type="button" class="btn btn-info btn-sm">Add Feedback</button>

                            <button data-dismiss="modal" aria-label="Close" type="button" class="btn btn-danger btn-sm">Close</button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>







    <!---Include JS Files-->

    <?php include 'application/views/jsPages.php'; ?>





    <script>

        $(document).ready(function() {



            // -------------------------- Search Student Names  -----------------------------

            $('#search_student_name').keyup(function() {

                var names = $('#search_student_name').val().trim();

                if (names != '') {



                    $.ajax({

                        url: "<?php echo base_url(); ?>index.php/Student/search_student_names",

                        method: "POST",

                        data: {

                            name: names

                        },

                        dataType: "JSON",

                        success: function(data) {

                            if (data != '') {

                                var html = '';

                                for (var count = 0; count < data.length; count++) {

                                    html += '<a id="' + data[count].SPRIDEN_student_id + '" href="#" style="border:none; border-bottom: 1px solid #77777720; border-radius: 0px; padding: 5px; font-size: 12px;" class="list-group-item list-group-item-action getDataOfStudent"> <span class="text-info">' + data[count].SPRIDEN_surname + " " + data[count].SPRIDEN_student_name + " " + data[count].SPRIDEN_father_name + '</span><span class="text-danger"> ( Standard :  ' + data[count].SPRIDEN_admission_class + ' ) </span>' + '</a>';

                                }

                                $('#dynamic_name_list').html(html);

                                $('.search-list-container-panel').css('display', 'block');



                            } else {

                                var notAvailable = '<a href="#" style="border-radius: 0px; border:none; padding: 5px; font-size: 12px;" class="list-group-item list-group-item-action noDataOfStudent"> <span class="text-info">Data Not Available</span></a>';

                                $('#dynamic_name_list').html(notAvailable);

                            }

                        }

                    });

                } else {

                    clearFormInputIcon();

                    $('#dynamicLabelAddButton').html("Add Student");

                    $('#dynamic_name_list').html('');

                    $('.search-list-container-panel').css('display', 'none');

                }

            });



            // Get Details After Clicking on Student Names

            $(document).on('click', '.getDataOfStudent', function() {

                var names = $(this).text();

                var student_ids = $(this).attr('id');



                $('#show-list').html('');

                $.ajax({

                    url: "<?php echo base_url(); ?>index.php/Student/get_selected_student_data",

                    method: "POST",

                    data: {

                        student_id: student_ids

                    },

                    dataType: "JSON",

                    success: function(data) {

                        if (data != '') {

                            //--------- Assign Value to PERONAL DETAILS text box ------ 

                            $('#student_id').val(data['info'].SPRIDEN_student_id);

                            $('#student_name').val(data['info'].SPRIDEN_surname + " " + data['info'].SPRIDEN_student_name + " " + data['info'].SPRIDEN_father_name);



                            //--------- Dynamically Change The LAbels ------

                            $('#dynamic_name_list').html('');

                            $('.search-list-container-panel').css('display', 'none');

                        } else {

                            clearFormInputIcon();

                            $('#dynamic_name_list').html('');

                            $('.search-list-container-panel').css('display', 'none');

                        }

                    }

                });

            });



            $(document).on('click', '.noDataOfStudent', function() {

                clearFormInputIcon();

                $('#search_student_name').val('');

                $('#dynamic_name_list').html('');

                $('.search-list-container-panel').css('display', 'none');

            });

        });



        function clearFormInputIcon() {

            $('#search_student_name').val('');

            //--------- Assign Value to PERONAL DETAILS text box ------ 

            $('#student_id').val('');

            $('#student_name').val('');

            $('#dynamic_name_list').html('');

            $('.search-list-container-panel').css('display', 'none');

        }

    </script>



    <script>

        $('.rowToUpdateData').on('click', function() {

            var rowID = $(this).attr('data-name');

            $.ajax({

                url: "<?= base_url(); ?>index.php/Teacher/getSelectedIdDataForFeedback",

                method: "POST",

                dataType: "JSON",

                data: {

                    id: rowID

                },

                success: function(data) {

                    if (data != "fail") {

                        $('#modal_student_id').val(data.stvrmrk_student_id);

                        $('#modal_row_id').val(rowID);

                        $('#modal_student_name').html(data.SPRIDEN_surname + " " + data.SPRIDEN_student_name + " " + data.SPRIDEN_father_name);

                        $('#remark_data_display').html(data.stvrmrk_remark);

                    }

                }

            });

        });

        $('#add_feedback_button').click(function() {

            var student_id = $('#modal_row_id').val();

            var feedback = $('#feedback_data').val();

            if (feedback == "") {

                alert("Provide Feedback");

                return false;

            } else {

                $.ajax({

                    url: "<?php echo base_url(); ?>index.php/Teacher/update_student_remark_feedback",

                    method: "POST",

                    data: {

                        student_id: student_id,

                        feedback: feedback

                    },

                    beforeSend: function() {

                        $('#add_feedback_button').html('<i class="fa fa-spinner fa-spin"></i> Please Wait');

                    },

                    success: function(data) {

                        if (data != "fail") {

                            $('#add_feedback_button').html('Add Feedback');

                            alert("Feedback Added !!!");

                            window.location.reload();

                        }

                    }

                });

            }

        });

    </script>

</body>



</html>
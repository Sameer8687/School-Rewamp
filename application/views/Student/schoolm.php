<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Student Fees Form</title>
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
        <form action="<?php echo base_url(); ?>TCM/add_data" method="post">
            <div class="pcoded-content">
                <!-- [ breadcrumb ] start -->
                <?php include 'application/views/Student/links.php'; ?>
                <!-- [ breadcrumb ] end -->
                <!-- [ Main Content ] start -->

                <div class="row margin-top-minus-40 mobile-margin-top-minus-40 mb-1">
                    <div class="col-md-7">
                        <a href="<?php echo base_url(); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a>
                        <button class="btn btn-secondary btn-sm"> <i class="fas fa-dollar-sign"></i> <span id="dynamicLabelAddButton">Save</span></button>

                    </div>
                    <div class="col-md-5 student-form-search">
                        <input type="text" autocomplete="off" class="form-control form-control-sm" id="search_student_name" placeholder="Search Student Name">
                        <span class="left-icon"><i class="fas fa-search"></i></span>
                        <span class="right-icon" onclick="clearFormInputIcon()"><i class="fas fa-times"></i></span>
                    </div>
                </div>
                <div class="row mt-2" id="alertRow">
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
                        <div class="card fees-card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    Student Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-4 text-left">Student ID<span class="text-danger">*</span> <span class="float-right">:</span></label>
                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="student_id" id="student_id">
                                            <input type="hidden" autocomplete="off" class="form-control form-control-sm col-md-6" required name="student_id_hidden" id="student_id_hidden">
                                        </div>

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-4 text-left">Surname<span class="text-danger">*</span> <span class="float-right">:</span></label>
                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="surname" id="surname">
                                        </div>
                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-4 text-left">Name<span class="text-danger">*</span> <span class="float-right">:</span></label>
                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="name" id="name">
                                        </div>
                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-4 text-left">Father Name<span class="text-danger">*</span> <span class="float-right">:</span></label>
                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="father_name" id="father_name">
                                        </div>
                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-4 text-left">Class<span class="text-danger">*</span> <span class="float-right">:</span></label>
                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="class" id="class">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-4 text-left">Balance Fees<span class="text-danger">*</span> <span class="float-right">:</span></label>
                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="balance_fees" id="balance_fees">
                                        </div>

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-4 text-left">Mobile <span class="text-danger">*</span> <span class="float-right">:</span></label>
                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="mobile" id="mobile">
                                        </div>
                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-4 text-left">Teacher<span class="text-danger">*</span> <span class="float-right">:</span></label>
                                            <select class="form-control form-control-sm col-md-6" name="teahcer_name" id="teahcer_name" required>
                                                <option value="">Select Teacher</option>
                                                <?php
                                                foreach ($teachers as $teacher) {
                                                ?>
                                                    <option value="<?php echo $teacher['SPRIDEN_student_id']; ?>">
                                                        <?php echo $teacher['name']; ?>
                                                    </option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-4 text-left">Landmark <span class="text-danger">*</span> <span class="float-right">:</span></label>
                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="landmark" id="landmark">
                                        </div>
                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-4 text-left">Division<span class="text-danger">*</span> <span class="float-right">:</span></label>
                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="division" id="division">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-2 text-left">Note<span class="text-danger">*</span> <span class="float-right">:</span></label>
                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-9" required name="notes" id="notes">
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
                <div class="row" style="margin-top: -20px;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                <table id="simpletable" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>Sr.No.</th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Std</th>
                                            <th style="display:none;">Class</th>
                                            <th>Division</th>
                                            <th>Total Fees Paid</th>
                                            <th>Balance</th>
                                            <th>Notes</th>
                                            <th>Teacher</th>
                                            <th>Landmark</th>

                                        </tr>
                                    </thead>
                                    <style>
                                        #simpletable thead tr th {
                                            padding: 5px;
                                            font-size: 12px;
                                            font-weight: 500;
                                            text-transform: capitalize;
                                        }

                                        #simpletable tbody tr td {
                                            padding: 2px 5px 2px 5px;
                                            font-size: 12px;
                                            font-weight: normal;
                                            text-transform: capitalize;
                                        }
                                    </style>
                                    <tbody>
                                        <?php $i = 1;
                                        $flag = 0;
                                        foreach ($feesDetails as $fees) {
                                            if ($fees['STVCLAS_standard'] == "I") {
                                                $classStd =  "First";
                                            } else if ($fees['STVCLAS_standard'] == "II") {
                                                $classStd =  "Second";
                                            } else if ($fees['STVCLAS_standard'] == "III") {
                                                $classStd =  "Third";
                                            } else if ($fees['STVCLAS_standard'] == "IV") {
                                                $classStd =  "Fourth";
                                            } else if ($fees['STVCLAS_standard'] == "V") {
                                                $classStd =  "Fifth";
                                            } else if ($fees['STVCLAS_standard'] == "VI") {
                                                $classStd =  "Sixth";
                                            } else if ($fees['STVCLAS_standard'] == "VII") {
                                                $classStd =  "Seventh";
                                            } else if ($fees['STVCLAS_standard'] == "VIII") {
                                                $classStd =  "Eighth";
                                            } else if ($fees['STVCLAS_standard'] == "IX") {
                                                $classStd =  "Ninth";
                                            } else if ($fees['STVCLAS_standard'] == "Sr.Kg.") {
                                                $classStd =  "Sr.Kg.";
                                            } else if ($fees['STVCLAS_standard'] == "Jr.Kg.") {
                                                $classStd =  "Jr.Kg.";
                                            } else if ($fees['STVCLAS_standard'] == "Nursery") {
                                                $classStd =  "Nursery";
                                            } else {
                                                $classStd = "";
                                            }



                                        ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= $fees['SPRIDEN_student_id']; ?></td>
                                                <td><?= $fees['name']; ?></td>
                                                <td><?= $fees['STVCLAS_standard']; ?></td>
                                                <td class="d-none"><?= $classStd; ?></td>
                                                <td><?= $fees['STVCLAS_class']; ?></td>
                                                <td><?= $fees['fees_paid']; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= $fees['stvtcmr_notes']; ?></td>
                                                <td><?= $fees['teacher_name']; ?></td>
                                                <td><?= $fees['stvtcmr_landmark']; ?></td>
                                            </tr>
                                        <?php $i++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
        $(document).ready(function() {

            // -------------------------- Search Student Names  -----------------------------
            $('#search_student_name').keyup(function() {
                var names = $('#search_student_name').val().trim();
                if (names != '') {
                    $.ajax({
                        url: "<?php echo base_url(); ?>index.php/TCM/search_from_spofees",
                        method: "POST",
                        data: {
                            name: names
                        },
                        dataType: "JSON",
                        success: function(data) {
                            if (data != '') {
                                var html = '';
                                for (var count = 0; count < data.length; count++) {
                                    html += '<a id="' + data[count].spIdInc + '" href="#" style="border:none; border-bottom: 1px solid #77777720; border-radius: 0px; padding: 5px; font-size: 12px;" class="list-group-item list-group-item-action getDataOfStudent"> <span class="text-info">' + data[count].SPRIDEN_surname + " " + data[count].SPRIDEN_student_name + " " + data[count].SPRIDEN_father_name + '</span><span class="text-danger"> ( Standard :  ' + data[count].sprfees_standard + ' ) </span>' + '</a>';
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
                    $('#dynamicLabelAddButton').html("Add Fees Data");
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
                    url: "<?php echo base_url(); ?>index.php/TCM/get_fees_data_of_student",
                    method: "POST",
                    data: {
                        student_id: student_ids
                    },
                    dataType: "JSON",
                    success: function(data) {
                        if (data != '') {
                            console.log(data);
                            $('#student_id').val(data.SPRIDEN_student_id);
                            $('#division').val(data.STVCLAS_class);
                            $('#class').val(data.STVCLAS_standard);
                            $('#name').val(data.SPRIDEN_student_name);
                            $('#mobile').val(data.phone);
                            $('#father_name').val(data.SPRIDEN_father_name);
                            $('#surname').val(data.SPRIDEN_surname);

                            $('#student_id_hidden').val(data.id);
                            console.log(data.fees_paid);
                            var fees_paid = data.fees_paid;
                            if (fees_paid == null) {
                                fees_paid = 0;
                            } else {
                                console.log(fees_paid);
                            }
                            var bal = 10000 - fees_paid;
                            $('#balance_fees').val(bal);
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
                $('#dynamicLabelAddButton').html("Add Fees Data");
                $('#dynamic_name_list').html('');
                $('.search-list-container-panel').css('display', 'none');
            });
        });

        function clearFormInputIcon() {

        }
    </script>
</body>

</html>
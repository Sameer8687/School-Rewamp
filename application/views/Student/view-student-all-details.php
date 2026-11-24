<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Student Books & Result Form</title>
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
        <form action="<?php echo base_url(); ?>welcome/fees_report" method="post">
            <div class="pcoded-content">
                <!-- [ breadcrumb ] start -->
                <?php include 'application/views/Student/links.php'; ?>
                <!-- [ breadcrumb ] end -->
                <!-- [ Main Content ] start -->

                <div class="row margin-top-minus-30  mb-2">
                    <div class="col-md-7">
                        <a href="<?php echo base_url('fees-report'); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a>

                    </div>
                </div>
                <div class="row" id="alertsRow">
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
                <style>
                    .table thead tr th,
                    .table tbody tr td {
                        padding: 5px !important;
                        font-size: 12px !important;
                        font-weight: normal;
                        text-transform: capitalize;
                    }

                    .details-row label {
                        font-weight: normal;
                    }

                    .text-area,
                    .text-area:focus {
                        padding: 0px !important;
                        box-shadow: none !important;
                        outline: none !important;
                        border: none !important;
                    }

                    .bg-label {
                        background-color: #e9ecef !important;
                    }

                    .margin-top-minus-15 {
                        margin-top: -15px !important;
                    }
                </style>
                <div class="row details-row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" style="background-color: #53769c;">
                                <h5 class="card-title text-white">Student Information</h5>
                            </div>
                            <div class="card-body">

                                <!--Personal Details-->
                                <div class="row">
                                    <div class="col-md-12 margin-top-minus-15 " style="margin-bottom: 10px;">
                                        <h6>Personal Details</h6>
                                        <hr>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">ID:</label>
                                            <label for="" class="col-md-9 bg-label"><?= $personal_details['id']; ?></label>
                                        </div>
                                        <div class="form-group row margin-top-minus-15 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">Surname:</label>
                                            <label for="" class="col-md-9 bg-label"><?= $personal_details['SPRIDEN_surname']; ?></label>
                                        </div>
                                        <div class="form-group row margin-top-minus-15 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">Student Name:</label>
                                            <label for="" class="col-md-9 bg-label"><?= $personal_details['SPRIDEN_student_name']; ?></label>
                                        </div>
                                        <div class="form-group row margin-top-minus-15 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">Father Name:</label>
                                            <label for="" class="col-md-9 bg-label"><?= $personal_details['SPRIDEN_father_name']; ?></label>
                                        </div>
                                        <div class="form-group row margin-top-minus-15 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">Mother Name:</label>
                                            <label for="" class="col-md-9 bg-label"><?= $personal_details['SPRIDEN_mother_name']; ?></label>
                                        </div>
                                        <div class="form-group row margin-top-minus-15 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">Aadhar Number:</label>
                                            <label for="" class="col-md-9 bg-label"><?= $personal_details['SPRIDEN_aadhar_no']; ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row margin-top-minus-10  mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">Student ID:</label>
                                            <label for="" class="col-md-9 bg-label"><?= $personal_details['SPRIDEN_student_id']; ?></label>
                                        </div>
                                        <div class="form-group row margin-top-minus-15 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">Date of Birth:</label>
                                            <label for="" class="col-md-9 bg-label"><?= $personal_details['SPRIDEN_birthdate'] == '0000-00-00' ? " - " : date('d M Y', strtotime($personal_details['SPRIDEN_birthdate'])); ?></label>
                                        </div>
                                        <div class="form-group row margin-top-minus-15 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">Birth Place:</label>
                                            <label for="" class="col-md-9 bg-label"><?= $personal_details['SPRIDEN_birth_place']; ?></label>
                                        </div>
                                        <div class="form-group row margin-top-minus-15 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">Gender:</label>
                                            <label for="" class="col-md-9 bg-label"><?= $personal_details['SPRIDEN_gender']; ?></label>
                                        </div>
                                        <div class="form-group row margin-top-minus-15 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">Address:</label>
                                            <label for="" class="col-md-9 bg-label"><?= $personal_details['SPRIDEN_main_address']; ?></label>
                                        </div>
                                    </div>
                                </div>

                                <!--Class Details-->
                                <div class="row">
                                    <div class="col-md-12 " style="margin-bottom: 10px;">
                                        <h6>Class Details</h6>
                                        <hr>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">Class:</label>
                                            <label for="" class="col-md-9 bg-label"><?= $class_details['STVCLAS_standard']; ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">Division:</label>
                                            <label for="" class="col-md-9 bg-label"><?= $class_details['STVCLAS_class']; ?></label>
                                        </div>
                                    </div>
                                </div>

                                <!--Fees Details-->
                                <div class="row">
                                    <div class="col-md-12 " style="margin-bottom: 10px;">
                                        <h6>Fees Details - (Current Academic Year)</h6>
                                        <hr>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">Total Fees:</label>
                                            <label for="" class="col-md-9 bg-label">10,000</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3">Balance Fees:</label>
                                            <label for="" class="col-md-9 bg-label"><?= $balance_fees['total']; ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col-md-12 " style="margin-bottom: 10px;">
                                        <h6>Fees Details - (All Records)</h6>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-sm table-bordered table-striped">
                                            <thead>
                                                <tr class="text-center">
                                                    <th class="font-weight-bold">Sr.No</th>
                                                    <th class="font-weight-bold">Class</th>
                                                    <th class="font-weight-bold">Amount</th>
                                                    <th class="font-weight-bold">Fees Date</th>
                                                    <th class="font-weight-bold">Due Date</th>
                                                    <th class="font-weight-bold">Academic Year</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1;
                                                foreach ($fees_records as $fees_record) { ?>
                                                    <tr class="text-center">
                                                        <td><?= $i; ?></td>
                                                        <td><?= $fees_record['sprfees_standard']; ?></td>
                                                        <td><?= $fees_record['sprfees_amount']; ?></td>
                                                        <td>
                                                            <?php
                                                            if ($fees_record['sprfees_feesdate'] == null || $fees_record['sprfees_feesdate'] == "0000-00-00") {
                                                                echo " ";
                                                            } else {
                                                                echo date('d M Y', strtotime($fees_record['sprfees_feesdate']));
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($fees_record['sprfees_due_date'] == null || $fees_record['sprfees_due_date'] == "0000-00-00") {
                                                                echo " ";
                                                            } else {
                                                                echo date('d M Y', strtotime($fees_record['sprfees_due_date']));
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?= $fees_record['stvacdm_year']; ?></td>
                                                    </tr>
                                                <?php $i++;
                                                } ?>
                                            </tbody>
                                        </table>
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

</body>

</html>
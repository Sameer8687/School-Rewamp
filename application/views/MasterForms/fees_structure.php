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


    <style>
        .pc-container .pcoded-content {
            padding-top: 40px;
        }
    </style>
    <!-- [ Main Content ] start -->

    <div class="pc-container">

        <form action="<?php echo base_url(); ?>MasterForm/addStructure" method="post">

            <div class="pcoded-content">

                <!-- [ breadcrumb ] start -->



                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->





                <div class="row margin-top-minus-40 mobile-margin-top-minus-10 mobile-wali-row" id="alertRow">

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

                        <div class="card pb-0 mb-0">

                            <div class="card-header mb-0 pb-2 ">
                                <div class="row w-100">
                                    <div class="col-md-9">
                                        <h5 style="padding-top:9px !important;">Add Fees Structure</h5>
                                    </div>
                                    <div class="col-md-3"><button class="float-right btn btn-secondary btn-sm"> <i class="fas fa-bars"></i> <span id="dynamicLabelAddButton">Add Data</span></button>
                                    </div>
                                </div>




                            </div>

                            <div class="card-body pb-0 mb-0">

                                <style>
                                    label {

                                        text-align: right;

                                    }



                                    .margin {

                                        margin-top: -15px;

                                    }



                                    .no-margin {

                                        margin-top: -19px;

                                    }
                                </style>

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group row margin">

                                            <label for="" class="col-md-4">Class<span class="text-danger">*</span> :</label>

                                            <select name="class" id="class" required class="form-control form-control-sm col-md-6">

                                                <option value="">Select Class</option>

                                                <?php foreach ($classes as $class) { ?>

                                                    <option value="<?= $class['stvfees_class'] ?>"><?= $class['stvfees_class'] ?></option>

                                                <?php } ?>

                                            </select>

                                            <input type="hidden" class="form-control form-control-sm col-md-10" name="exam_id" id="school_id">

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group row margin">

                                            <label for="" class="col-md-4">Fees<span class="text-danger">*</span> :</label>

                                            <input type="text" required autocomplete="off" class="form-control form-control-sm col-md-6" name="fees" id="fees" required>

                                            <input type="hidden" class="form-control form-control-sm col-md-8" name="exam_id" id="school_id">

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group row no-margin">

                                            <label for="" class="col-md-4">Academic Year<span class="text-danger">*</span> :</label>

                                            <select name="acdm_yr" required id="acdm_yr" class="form-control form-control-sm col-md-6">

                                                <option value="">Select Academic Year</option>

                                                <?php foreach ($acadmic as $acdm) { ?>

                                                    <option value="<?= $acdm['stvacdm_id'] ?>"><?= $acdm['stvacdm_year'] ?></option>

                                                <?php } ?>

                                            </select>

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

                <div class="row mt-1">

                    <div class="col-md-12">

                        <div class="card">

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="dt-responsive table-responsive margin-top-minus-10">

                                            <table id="simpletable" class="table table-striped table-bordered nowrap">

                                                <thead>

                                                    <tr>

                                                        <th>Sr.No.</th>

                                                        <th>Class</th>

                                                        <th>Fees</th>

                                                        <th>Academic Year</th>

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

                                                    <?php $i = 1;

                                                    foreach ($records as $record) { ?>

                                                        <tr>

                                                            <td><?= $i; ?></td>

                                                            <td><?= $record['stvfstr_std'] ?></td>

                                                            <td><?= $record['stvfstr_fees'] ?></td>

                                                            <td><?= $record['stvfstr_acdm_year'] ?></td>

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
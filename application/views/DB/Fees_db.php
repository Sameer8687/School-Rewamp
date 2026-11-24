<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fees Dashboard</title>
    <link rel="stylesheet" href="r.css" />
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets1/images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- plugin css -->

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.3.1/css/all.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets1/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>


    <!-- Layout config Js -->
    <script src="<?php echo base_url(); ?>assets1/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="<?php echo base_url(); ?>assets1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo base_url(); ?>assets1/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url(); ?>assets1/css/app.min.css" rel="stylesheet" type="text/css" />

</head>
<style>
    .row {
        --bs-gutter-x: 1.5rem;
        --bs-gutter-y: 0;
        /* display: flex; */
        flex-wrap: wrap;
        margin-top: calc(-1 * var(--bs-gutter-y));
        margin-right: calc(0 * var(--bs-gutter-x)) !important;
        margin-left: calc(0 * var(--bs-gutter-x)) !important;
    }
</style>

<body style="background: #e7e7e7; overflow-x:hidden;">
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid" style="margin: 0px;padding: 0px;">
                <!-- start page title -->

                <!-- <div class="row"> -->
                <!-- <div class="col-md-12"> -->
                <nav class="navbar navbar-expand-lg navbar-dark bg-body-tertiary" style="background-color: #3680a2; padding:5px;">
                    <div class="container-fluid">
                        <div class="col-md-3">
                            <a class="navbar-brand" href="#"><i class="fa fa-chart-line me-2"></i>Dashboard</a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                        </div>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent" style="padding-left: 20%;">
                            <div class="col-md-9">
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                                    <li class="nav-item" id="filters">
                                        <select name="fetchval" id="fetchval" onchange="MonthData()" style=" text-align: center;border-radius: 23px; border-color: lavender; height:4vh;">
                                            <option value="1">1Month</option>
                                            <option value="6">6Month</option>
                                            <option value="12">1Year</option>
                                        </select>
                                    </li>
                                    <li class="nav-item mx-2">
                                        <input type="date" name="from_date" id="from_date" class=" form-control form-control-sm" style="border-radius: 20px;
                                            height: 1vh; width: 13vw; margin-top: -4px;" value="<?= $records['stvacdm_start']; ?>" />
                                    </li>
                                    <li class="nav-item mx-2">
                                        <input type="date" name="to_date" id="to_date" class=" form-control form-control-sm" style="border-radius: 20px;
                                            height: 1vh; width: 13vw; margin-top: -4px;" value="<?= $records['stvacdm_end']; ?>" />
                                    </li>
                                    <li class="nav-item mx-2">

                                        <select name="acdm_year" id="acdm_year" style=" text-align: center;border-radius: 23px; border-color: lavender; height:4vh;">
                                            <option value="">Select Academic Year</option>
                                            <?php foreach ($academic_years as $academic_year) {

                                                if ($academic_year['stvacdm_id'] == $academic_year['stvacdm_id']) {

                                                    $selected = 'selected';
                                                } else {

                                                    $selected = '';
                                                }

                                            ?>

                                                <option <?= $selected; ?> value="<?= $academic_year['stvacdm_id']; ?>">
                                                    <?= $academic_year['stvacdm_year']; ?></option>

                                            <?php } ?>

                                            <!-- <option <?= $record['stvacdm_year'] == $academic_year['stvacdm_id'] ? 'selected' : '' ?> value="<?= $record['stvacdm_id'] ?>">
                                                    <?= $record['stvacdm_year']; ?></option> -->

                                        </select>

                                    </li>
                                    <li class="nav-item">
                                        <button id="find-students" type="button" class="btn btn-sm btn-outline-light" style="margin-top:-10%;" value="" onclick="fetchProject()">View </button>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <div class="d-flex align-items-center">
                </div>
            </div>
            <!-- end page title -->


            <section>
                <div class="row" style="padding-top:5px;">
                    <div class="col-md-4">
                        <h4 class="fs-18 p-1 flex-grow-1 text-light b-900" style="font-family: 'Playfair Display', serif;background-color:#3680a2;">
                            Total Student & Fees Overview
                        </h4>
                        <div class="card" style="height: 60%; margin-top: -4px;">


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12 mini-widget pb-3 pb-lg-0">
                                        <div class="d-flex align-items-end">
                                            <?php
                                            foreach ($students as $stud) :
                                            ?>
                                                <div class="flex-grow-1" style="border-right: 1px solid var(--in-border-color); padding-left: 5%;">
                                                    <div class="container3" style="margin-top: 0%;" onclick="chart_details('All')">

                                                        <h5 class="text-muted fs-14 mt-2 mb-0">
                                                            All Student
                                                        </h5>
                                                        <h2 class="mb-0 fs-18">
                                                            <span class="counter-value" data-target="197"><?= $stud['total_students']; ?></span>
                                                        </h2>
                                                        <lord-icon src="https://cdn.lordicon.com/yrxnwkni.json" trigger="hover" colors="primary:#4be1ec,secondary:#cb5eee" style="width:50px;height:50px">
                                                        </lord-icon>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>



                                            <div class="flex-grow-1">

                                                <div class="container" style="margin-top: -3%;" onclick="chart_details('RTE')">

                                                    <h5 class="text-muted fs-14 mt-2 mb-0" style="text-align: center;">
                                                        RTE Student
                                                    </h5>
                                                    <?php
                                                    foreach ($studs as $stud) :
                                                    ?>
                                                        <h2 class="mb-0 fs-18" style="text-align: center;">
                                                            <span class="counter-value" data-target="197"><?= $stud['rte_stuents']; ?></span>
                                                        </h2>
                                                    <?php endforeach; ?>

                                                    <lord-icon src="https://cdn.lordicon.com/amxnacxd.json" trigger="hover" colors="primary:#4be1ec,secondary:#cb5eee" style="width:50px;height:50px;padding-left: 26%;">
                                                    </lord-icon>
                                                    <!-- <h5 class="text-muted fs-14 mt-2 mb-0">
                                                        No Of Student
                                                    </h5> -->

                                                    <div class="container2"></div>
                                                </div>

                                                <div class="modal" id="fivmodal" style="padding-right: 25%;">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content" style="width: 65vw; overflow-y: scroll; height: 90vh;">



                                                            <div class="modal-header">
                                                                <h4 class="mod
                                                                al-title">
                                                                    Total Student Detail
                                                                </h4>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>


                                                            <div class="modal-body" style="padding-left: 12%;">
                                                                <div class="col-md-12 mt-2" style="width: 50vw;">

                                                                    <div class="dt-responsive table-responsive">

                                                                        <table id="simpletable" class="table table-sm table-striped table-bordered nowrap">

                                                                            <thead>


                                                                                <tr style="background-color: #53769c;color: #fff;">
                                                                                    <th class="text-capitalize" style="display: none;">Class
                                                                                        Number</th>
                                                                                    <th class="text-capitalize">
                                                                                        Student ID</th>
                                                                                    <th class="text-capitalize">
                                                                                        Name</th>
                                                                                    <th class="text-capitalize">
                                                                                        Standard</th>
                                                                                </tr>

                                                                            </thead>

                                                                            <style>
                                                                                .table tr td,

                                                                                .table tr th {

                                                                                    cursor: pointer;

                                                                                }

                                                                                .dt-button.buttons-excel,
                                                                                .dt-button.buttons-pdf {

                                                                                    background-color: #263544 !important;
                                                                                    color: #fff !important;
                                                                                    padding: 2px 10px 2px 10px !important;
                                                                                }
                                                                            </style>

                                                                            <tbody id="tbody">
                                                                                <?php
                                                                                foreach ($rtes as $rte) :
                                                                                ?>
                                                                                    <tr>

                                                                                        <td> <?= $rte['student_id']; ?>
                                                                                            <button style="margin-top: 0px;" type="button hidden" class="btn" onclick="Rtemodal('<?= $rte['student_id']; ?> ')">

                                                                                            </button>
                                                                    </div>

                                                                    </td>
                                                                    <td> <?= $rte['student_name']; ?>
                                                                    </td>
                                                                    <td> <?= $rte['standard']; ?>
                                                                    </td>


                                                                    </tr>
                                                                <?php endforeach; ?>

                                                                </tbody>

                                                                </table>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- RTE END  -->



                                        </div>
                                        <?php
                                        foreach ($students as $stud) :
                                        ?>
                                            <!-- <div class="d-flex align-items-end"> -->
                                            <div class="flex-grow-1">
                                                <div class="container" style="border-left: 1px solid var(--in-border-color);" onclick="chart_details('T')">
                                                    <h5 class="text-muted fs-14 mt-2 mb-0" style="text-align: center;">Total
                                                        Fees
                                                    </h5>

                                                    <h2 class="mb-0 fs-18" style="text-align: center;">
                                                        <?php if ($this->session->userdata('oliveMobileSystemUser')) { ?>
                                                            <span class="counter-value" data-target="634"><?= $stud['total_fees']; ?></span>
                                                        <?php } else { ?>
                                                            <span class="counter-value">********</span>
                                                        <?php } ?>
                                                    </h2>

                                                    <lord-icon src="https://cdn.lordicon.com/tbfrtmlu.json" trigger="hover" colors="primary:#4be1ec,secondary:#cb5eee" style="width:50px;height:50px;margin-left:30px">
                                                    </lord-icon>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- END ROW  -->

                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <h4 class="fs-18 p-1 flex-grow-1 text-light b-900" style="font-family: 'Playfair Display', serif;background-color:#3680a2;">
                        Fees Overview
                    </h4>
                    <div class="card dash-mini" style="height: 60%;margin-top: -4px;">

                        <div class="modal" id="fivmodal" style="padding-right: 25%;">
                            <div class="modal-dialog">
                                <div class="modal-content" style="width: 65vw; overflow-y: scroll; height: 90vh;">
                                    <div class="modal-header">
                                        <h4 class="modal-title">
                                            Fees Detail
                                        </h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body" style="padding-left: 12%;">
                                        <div class="col-md-12 mt-2" style="width: 50vw;">

                                            <div class="dt-responsive table-responsive">

                                                <table id="simpletable" class="table table-sm table-striped table-bordered nowrap">
                                                    <thead>

                                                        <tr style="background-color: #53769c;color: #fff;">
                                                            <th class="text-capitalize" style="display: none;">Class
                                                                Number</th>

                                                            <th class="text-capitalize">
                                                                Student ID</th>
                                                            <th class="text-capitalize">
                                                                Name</th>
                                                            <th class="text-capitalize">
                                                                Standard</th>
                                                            <th class="text-capitalize">
                                                                Total Fees</th>
                                                            <th class="text-capitalize">
                                                                Paid Fees</th>
                                                        </tr>

                                                    </thead>

                                                    <style>
                                                        .table tr td,

                                                        .table tr th {

                                                            cursor: pointer;

                                                        }

                                                        .dt-button.buttons-excel,
                                                        .dt-button.buttons-pdf {

                                                            background-color: #263544 !important;
                                                            color: #fff !important;
                                                            padding: 2px 10px 2px 10px !important;
                                                        }
                                                    </style>

                                                    <tbody id="tbody">
                                                        <?php
                                                        foreach ($Details as $detail) :
                                                        ?>
                                                            <tr>

                                                                <td> <?= $detail['id']; ?>

                                                                </td>
                                                                <td> <?= $detail['name']; ?>
                                                                </td>
                                                                <td> <?= $detail['STVCLAS_standard']; ?>
                                                                </td>
                                                                <td> <?= $detail['stvfstr_fees']; ?>
                                                                </td>
                                                                <td> <?= $detail['paid_fees']; ?>
                                                                </td>

                                                            </tr>
                                                        <?php endforeach; ?>

                                                    </tbody>

                                                </table>

                                            </div>



                                        </div>
                                    </div>


                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#myModal">Close</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row" style="margin-top: -3%;">
                                <div class="col-md-4 mini-widget py-3 py-lg-0">
                                    <div class="d-flex align-items-end">
                                        <?php
                                        foreach ($fees as $fee) : ?>
                                            <div class="flex-grow-1" style="margin-bottom: 14%;">
                                                <h2 class="mb-0 fs-14">
                                                    <span class="counter-value" data-target="197"> Paid
                                                        Fees</span>
                                                </h2>

                                                <?php if ($this->session->userdata('oliveMobileSystemUser')) { ?>
                                                    <h5 class="text-muted fs-16 mt-2 mb-0">
                                                        <span class="counter-value" data-target="197"><?= $fee['total_fees_paid'] ?></span>
                                                    </h5>
                                                <?php } else { ?>
                                                    <h4><span class="counter-value">********</span></h4>
                                                <?php } ?>
                                                <div class="container" style="height: 0vh;">


                                                    <button type="button hidden" class="btn" data-bs-toggle="modal" data-bs-target="#fivmodal">

                                                    </button>
                                                </div>
                                                <div class="flex-grow-1 text-end dash-widget" style="margin-bottom: -4%;margin-top: -6%;">
                                                    <div id="chart6" data-colors='["--in-red", "--in-light"]' class="apex-charts">
                                                    </div>
                                                </div>
                                            </div>

                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="col-md-4 mini-widget py-3 py-lg-0">
                                    <div class="d-flex align-items-end">
                                        <?php foreach ($balanced as $balance) : ?>
                                            <div class="flex-grow-1" style="margin-bottom: 14%;">
                                                <h2 class="mb-0 fs-14">
                                                    <span class="counter-value" data-target="634">Balanced</span>
                                                </h2>
                                                <!-- <h1 class="text-muted fs-18 mt-2 mb-0">
                                            <span class="counter-value" data-target="634">
                                                <?php $balance_lbl = $balance['total_fees'] - $fee['total_fees'];

                                                echo $balance_lbl; ?></span>
                                        </h1> -->

                                                <?php if ($this->session->userdata('oliveMobileSystemUser')) { ?>
                                                    <h5 class="text-muted fs-16 mt-2 mb-0">
                                                        <span class="counter-value" data-target="634">
                                                            <?php $balance_lbl = $balance['total_fees'] - $fee['total_fees_paid'];

                                                            echo $balance_lbl; ?></span>
                                                    </h5>
                                                <?php } else { ?>
                                                    <h4><span class="counter-value">********</span></h4>
                                                <?php } ?>
                                                <div class="flex-grow-1 text-end dash-widget" style="margin-bottom: -4%;margin-top: -6%;">
                                                    <div id="chart7" data-colors='["--in-primary", "--in-light"]' class="apex-charts">
                                                    </div>
                                                </div>
                                            </div>

                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="col-md-4 mini-widge py-3 py-lg-0">
                                    <div class="d-flex align-items-end">

                                        <div class="flex-grow-1" style="margin-bottom: 14%;">
                                            <h2 class="mb-0 fs-14">
                                                <span class="counter-value" data-target="512">Exemption</span>
                                            </h2>
                                            <?php if ($this->session->userdata('oliveMobileSystemUser')) { ?>

                                                <h5 class="text-muted fs-16 mt-2 mb-0">
                                                    <span class="counter-value" data-target="512"> 45667</span>
                                                </h5>
                                            <?php } else { ?>
                                                <h4><span class="counter-value">********</span></h4>
                                            <?php } ?>
                                            <div class="flex-grow-1 text-end dash-widget" style="margin-bottom:-4%;margin-top: -6%;">
                                                <div id="chart10" data-colors='["--in-primary", "--in-light"]' class="apex-charts">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- ======================================================= -->
                <div class="col-md-4">
                    <h4 class="fs-18 p-1  flex-grow-1 text-light b-900" style="font-family: 'Playfair Display', serif;background-color:#3680a2;">
                        Structure</h4>
                    <div class="card" style="margin-top: -4px;">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div>
                                    <div class="structure-list d-flex justify-content-between border-bottom">
                                        <p class="mb-0">
                                            <i class="las la-dot-circle fs-18 text-primary me-2"></i>Total

                                        </p>
                                        <?php foreach ($Totals as $Total) : ?>
                                            <div>
                                                <?php if ($this->session->userdata('oliveMobileSystemUser')) { ?>
                                                    <span class="pe-2 pe-sm-5"><?= $Total['total_fees'] ?></span>
                                                <?php } else { ?>
                                                    <span class="counter-value">********</span>
                                                <?php } ?>
                                                <!-- <span class="badge bg-primary"> + 0.2% </span> -->

                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <?php foreach ($fees as $fee) : ?>
                                        <div class="structure-list d-flex justify-content-between border-bottom">
                                            <p class="mb-0">
                                                <i class="las la-dot-circle fs-18 text-primary me-2"></i>Paid
                                            </p>

                                            <div>
                                                <?php if ($this->session->userdata('Username')) { ?>

                                                    <?= $fee['sprfees_status'] ?>
                                                <?php } else { ?>
                                                    <span class="counter-value">********</span>
                                                <?php } ?>
                                                <!-- <span class="badge bg-primary"> - 0.7% </span> -->
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php foreach ($balanced as $balance) : ?>
                                        <div class="structure-list d-flex justify-content-between border-bottom">
                                            <p class="mb-0">
                                                <i class="las la-dot-circle fs-18 text-primary me-2"></i>Balanced
                                            </p>
                                            <div>
                                                <?php if ($this->session->userdata('oliveMobileSystemUser')) { ?>
                                                    <?php $balance_lbl = $balance['total_fees'] - $fee['total_fees_paid'];

                                                    echo $balance_lbl; ?> <?php } else { ?>
                                                    <span class="counter-value" style="padding-left: 5px;">********</span>
                                                <?php } ?>

                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                </div>
                                <div style="margin-top: -5%;" id="chart8" data-colors='["--in-primary", "--in-primary-rgb, 0.7", "--in-light"]' class="apex-charts" dir="ltr"></div>

                            </div>



                        </div>
                    </div>
                </div>

        </div>

        <!-- end row -->
        </section>









        <section>
            <div class="row">
                <div class="col-xl-4">
                    <h4 class="fs-18 p-1 text-light font-size-100" style="font-family: 'Playfair Display', serif;background-color:#3680a2; margin-top:-43px;">
                        Payment Activity Overview
                    </h4>
                    <div class="card" style="margin-top: -4px; height:195px;">
                        <div class="card-body">
                            <div id="chart5" class="apex-charts" data-colors='["--in-primary", "--in-light"]' dir="ltr">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <h4 class="fs-18 p-1 text-light font-size-100" style="font-family: 'Playfair Display', serif;background-color:#3680a2; margin-top:-43px;">
                        Class wise Fees
                    </h4>
                    <div class="card" style="margin-top: -4px; height:195px;">

                        <div class="card-body py-1">

                            <div id="kschart">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <h4 class="fs-18 p-1 text-light font-size-100" style="font-family: 'Playfair Display', serif;background-color:#3680a2; margin-top:-43px;">
                        Student Overview
                    </h4>
                    <div class="card" style="margin-top:-4px;  height:195px;">

                        <div class="card-body pt-0">
                            <div id="chart9" data-colors='["--in-primary", "--in-light"]' class="apex-charts" dir="ltr">
                            </div>
                            <?php
                            foreach ($act_stud as $stud) :
                            ?>
                                <div class="row text-center" style="margin-top: -6%;">
                                    <div class="col-4 border-end">
                                        <div class="my-1">
                                            <p class="text-muted text-truncate mb-2">
                                                <small>Active Student</small>
                                            </p>
                                            <h4 class="mt-2 mb-0 fs-20"><?= $stud['TOTAL_STUDENT']; ?></h4>
                                        </div>
                                    </div>
                                    <div class="col-4  border-end">
                                        <div class="my-1">
                                            <p class="text-muted text-truncate mb-2">
                                                <small>RTE Student</small>
                                            </p>
                                            <h4 class="mt-2 mb-0 fs-20"><?= $stud['RTE']; ?></h4>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="my-1">
                                            <p class="text-muted text-truncate mb-2">
                                                <small> Inactive Students</small>
                                            </p>
                                            <h4 class="mt-2 mb-0 fs-20"><?= $stud['INACTIVE']; ?></h4>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>

            <!-- end row -->
        </section>



        <section>



            <div class="row" id="kismatji-wala-card'">
                <div class="col-md-12">
                    <div class="card" style="margin-top: -20px;">
                        <div class="card-body" style="height: 25vh; overflow-y:scroll;">
                            <!-- <button class="d-none" id="backData" onclick="backData()"> -->
                            <div class="card" style="display: block; background-color: #53769c;color: #fff;margin-bottom: 8px;">
                                <i class="fas fa-arrow-alt-circle-left d-none" id="backData" onclick="backData()" style="color: #fff;"></i>
                                <span id="sname-label"></span>
                            </div>
                            <!-- </button> -->

                            <table id="simpletable" class="table table-sm table-striped
                                                                                table-bordered nowrap" style="text-align: center; margin-top:2px;">
                                <thead>
                                    <tr style="background-color: #53769c;color: #fff;" id="th_data">

                                    </tr>
                                </thead>
                                <style>
                                    .table tr td,

                                    .table tr th {

                                        cursor: pointer;

                                    }

                                    #simpletable tbody tr td {
                                        padding: 5px;
                                        font-size: 12px;
                                        font-weight: 500;
                                    }


                                    .dt-button.buttons-excel,
                                    .dt-button.buttons-pdf {

                                        background-color: #263544 !important;
                                        color: #fff !important;
                                        padding: 2px 10px 2px 10px !important;
                                    }
                                </style>

                                <tbody id="chart_fetch3">

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>


        </section>
        <!-- <?php echo $monthwise_chart[''] ?> -->





        <!-- end row -->
    </div>
    <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    </div>
    <!-- JAVASCRIPT -->

    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/simplebar/simplebar.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/node-waves/waves.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/feather-icons/feather.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/js/plugins.js"></script>

    <!-- apexcharts -->
    <script src="<?php echo base_url(); ?>assets1/libs/apexcharts/apexcharts.min.js"></script>

    <!-- Vector map-->
    <script src="<?php echo base_url(); ?>assets1/libs/jsvectormap/js/jsvectormap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/jsvectormap/maps/world-merc.js"></script>

    <!-- Dashboard init -->
    <script src="<?php echo base_url(); ?>assets1/js/pages/dashboard.init.js"></script>

    <!-- App js -->
    <script src="<?php echo base_url(); ?>assets1/js/app.js"></script>
    <script>
        $("#simpletable").DataTable({
            "pageLength": 25,
            dom: "Bfrtip",

        });
    </script>
    <script>
        $(document).ready(function() {
            $('.btn modal').click(function() {
                var userid = $(this).data('id');
                $.ajax({
                    url: '',
                    type: 'POST',
                    data: {},
                    success: function(response) {
                        $('').html(response);
                        $('').modal('');
                    }
                });
            });
        });
    </script>

    <script>
        function display5chart(series, value3, value4, value5) {
            let cate = series;
            let option = {
                series: [{
                        name: 'Total fees',

                        data: value3
                    },
                    {
                        name: 'Paid fees',

                        data: value4
                    },
                    {
                        name: 'Balanced fees',

                        data: value5
                    }

                ],
                chart: {
                    type: 'bar',
                    height: 200,
                    stacked: true,
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            let point_at = config.dataPointIndex;
                            chartValue(point_at, cate);

                        },
                    },
                },

                stroke: {
                    width: 0,
                    colors: ['#fff']
                },
                dataLabels: {
                    formatter: (val) => {
                        "" + val + ""
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                    }
                },
                xaxis: {
                    categories: cate,
                },
                fill: {
                    opacity: 1
                },
                colors: ['#80c7fd', '#008FFB', '#80f1cb', '#00E396'],
                yaxis: {
                    labels: {
                        formatter: (val) => {
                            return val + 'K'
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left'
                }

            };
            document.querySelector("#chart5").innerHTML = '';
            let display5chart = new ApexCharts(document.querySelector("#chart5"), option);
            display5chart.render();
        }
        let MonthBarChart = (from, to, year) => {
            // document.querySelector("#chart5").innerHTML = '';
            $.ajax({
                url: "<?= base_url('index.php/FeesDashboard/filter') ?>",
                method: "GET",
                dataType: "JSON",
                data: {
                    from: from,
                    to: to,
                    year: year
                },
                beforeSend: function() {
                    $("#chart5").html(
                        '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
                    );
                },
                success: function(data) {
                    console.log(data);
                    let series = [];
                    let value3 = [];
                    let value4 = [];
                    let value5 = [];

                    data.forEach(element => {
                        series.push(element.stvfstr_std);
                        value3.push(parseFloat(element.total_fees));
                        value4.push(parseFloat(element.fees_paid));
                        value5.push(parseFloat(element.total_fees) - parseFloat(element.fees_paid));
                    });

                    display5chart(series, value3, value4, value5)

                }
            })

        }
        let chartValue = (point_at, cate) => {


            let std = cate[point_at];

            let html = ''


            let th = ['#', 'ID', 'Name', 'Standard', 'Total Fees', 'Paid Fees']

            th.forEach(element => {

                html += `<th>${element}</th>`
            })

            $("#th_data").html(html)

            $.ajax({
                url: "<?= base_url('index.php/FeesDashboard/student_dataChart') ?>",
                method: "GET",
                dataType: "json",
                data: {
                    std: std


                },
                beforeSend: function() {
                    $("#chart5").html(
                        '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
                    );
                },
                success: function(data) {

                    let str = ''
                    let i = 0
                    let tc = 'class="text-center"'

                    data.forEach(element => {

                        str += `<tr>
                                        <td>${++i}</td>
                                        <td>${element.SPRIDEN_student_id}</td>
                                        <td>${element.name}</td>
                                        <td>${element.STVCLAS_standard}</td>
                                        <td>${element.stvfstr_fees}</td>
                                        <td>${element.paid_fees}</td>
                                        </tr>`;

                    })

                    $("#chart_fetch3").html(str)


                }

            })
        }


        function display6chart(val1, val2) {

            let options = {
                series: [val1, val2],
                chart: {
                    type: "donut",
                    height: 90
                },
                colors: vectorMapWorldLineColors,
                fill: {
                    opacity: 1
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: "11px",
                        fontFamily: "DM Sans,sans-serif",
                        colors: void 0,
                    },
                    background: {
                        enabled: !0,
                        foreColor: "#fff",
                        padding: 10,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: "#fff",
                        opacity: 1,
                    },
                }
            };
            document.querySelector("#chart6").innerHTML = ''
            var display6chart = new ApexCharts(document.querySelector("#chart6"), options);
            display6chart.render();

        }



        let paidBarChart = (from, to, year) => {

            $.ajax({
                url: "<?= base_url('index.php/FeesDashboard/paid_filter') ?>",
                method: "GET",
                dataType: "JSON",
                data: {
                    from: from,
                    to: to,
                    year: year,
                },
                beforeSend: function() {
                    $("#chart6").html(
                        '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
                    );
                },
                success: function(data) {
                    console.log(data);
                    var series = [];
                    let val1 = parseFloat(data[0].total_fees);
                    let val2 = parseFloat(data[0].fees_paid);

                    display6chart(val1, val2)
                }
            })

        }



        let paidBarCharts = (from, to, year) => {

            var chart_data = <?php echo json_encode($Paid_chart) ?>;
            var series = [];
            // var value6 = [];
            // var value7 = [];
            let val3 = parseFloat(chart_data[0].total_fees);
            let val4 = parseFloat(chart_data[0].fees_paid);
            // value10.push(parseFloat(chart_data[0].total_fees) - parseFloat(chart_data[0].fees_paid));
            // let val3 = parseFloat(chart_data[0].total_fees - total_fees_paid);
            const balanceFees = val3 - val4;

            // series.push(parseFloat(element.year));

            // value7.push(parseFloat(element.total_fees_paid));





            $(function() {
                var options = {
                    series: [val4, balanceFees],
                    chart: {
                        type: "donut",
                        height: 90
                    },
                    colors: vectorMapWorldLineColors,
                    fill: {
                        opacity: 1
                    },
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: true,

                        style: {
                            fontSize: "11px",
                            fontFamily: "DM Sans,sans-serif",
                            colors: void 0,
                        },
                        background: {
                            enabled: !0,
                            foreColor: "#fff",
                            padding: 10,
                            borderRadius: 2,
                            borderWidth: 1,
                            borderColor: "#fff",
                            opacity: 1,
                        },
                    }
                };

                var chart = new ApexCharts(document.querySelector("#chart7"), options);
                chart.render();
            });
        }

        function display8chart(val5, val6, balanceFeeses) {

            let options = {
                series: [val5, val6, balanceFeeses],
                chart: {
                    height: 130,
                    type: "donut"
                },
                labels: ["Total", "Paid", "Balanced"],
                colors: ['#80c7fd', '#008ffb', '#80f1cb'],
                plotOptions: {
                    pie: {
                        startAngle: 25,
                        donut: {
                            size: "78%"
                        }
                    }
                },
                legend: {
                    show: !1
                },
                dataLabels: {
                    style: {
                        fontSize: "11px",
                        fontFamily: "DM Sans,sans-serif",
                        colors: void 0,
                    },
                    background: {
                        enabled: !0,
                        foreColor: "#fff",
                        padding: 10,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: "#fff",
                        opacity: 1,
                    },
                },
                responsive: [{
                    breakpoint: 600,
                    options: {
                        chart: {
                            height: 115
                        },
                        legend: {
                            show: !1
                        }
                    },
                }, ],
            };
            document.querySelector("#chart8").innerHTML = ''
            var display8chart = new ApexCharts(document.querySelector("#chart8"), options);
            display8chart.render();

        }



        let paid1BarCharts = (from, to, year) => {

            $.ajax({
                url: "<?= base_url('index.php/FeesDashboard/paids_filter') ?>",
                method: "GET",
                dataType: "JSON",
                data: {
                    from: from,
                    to: to,
                    year: year
                },
                beforeSend: function() {
                    $("#chart8").html(
                        '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
                    );
                },
                success: function(data) {


                    let val5 = parseFloat(data[0].total_fees);
                    let val6 = parseFloat(data[0].fees_paid);

                    const balanceFeeses = val5 - val6;


                    display8chart(val5, val6, balanceFeeses)
                }
            })



        }


        $(document).ready(function() {

            var chart_data = <?php echo json_encode($total_exemption) ?>;
            var series = [];
            // var value6 = [];
            // var value7 = [];
            let exem = parseFloat(chart_data[0].total_fees);
            let exem1 = parseFloat(chart_data[0].total_exemption);


            // series.push(parseFloat(element.year));

            // value7.push(parseFloat(element.total_fees_paid));





            $(function() {
                var options = {
                    series: [exem, exem1],
                    chart: {
                        type: "donut",
                        height: 90
                    },
                    colors: vectorMapWorldLineColors,
                    fill: {
                        opacity: 1
                    },
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: true,

                        style: {
                            fontSize: "11px",
                            fontFamily: "DM Sans,sans-serif",
                            colors: void 0,
                        },
                        background: {
                            enabled: !0,
                            foreColor: "#fff",
                            padding: 10,
                            borderRadius: 2,
                            borderWidth: 1,
                            borderColor: "#fff",
                            opacity: 1,
                        },
                    }
                };
                document.querySelector("#chart10").innerHTML = '';
                var chart = new ApexCharts(document.querySelector("#chart10"), options);
                chart.render();
            });
        });





        $(document).ready(function() {

            var chart_data = <?php echo json_encode($Chart_Stud) ?>;
            var series = [];

            var val7 = parseFloat(chart_data[0].TOTAL_STUDENT);
            var val8 = parseFloat(chart_data[0].RTE);
            var val9 = parseFloat(chart_data[0].INACTIVE)

            $(function() {
                var options = {
                    series: [val7, val8, val9],

                    chart: {
                        height: 170,
                        type: 'radialBar',
                    },
                    plotOptions: {
                        radialBar: {
                            offsetY: 0,
                            offsetX: 30,
                            startAngle: 0,
                            endAngle: 270,
                            hollow: {
                                margin: 5,
                                // size: '30%',
                                background: 'transparent',
                                image: undefined,
                            },
                            dataLabels: {
                                name: {
                                    show: false,
                                },
                                value: {
                                    show: false,
                                }
                            }
                        }
                    },
                    colors: ['#146c94', '#19a7ce', '#afd3e2'],
                    labels: ['Active Student', 'RTE Student', 'INactive Student'],
                    legend: {
                        show: true,
                        floating: true,
                        fontSize: '10px',
                        // position: 'relative',
                        position: 'left',
                        // left: 50,
                        offsetX: 0,
                        offsetY: 0,
                        labels: {
                            useSeriesColors: true,
                        },
                        markers: {
                            size: 0
                        },

                        itemMargin: {
                            vertical: 3
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            legend: {
                                show: false
                            }
                        }
                    }]
                };
                document.querySelector("#chart9").innerHTML = ''
                var chart = new ApexCharts(document.querySelector("#chart9"), options);
                chart.render();
            });
        });








        function displayChart(chartData) {

            const ctx = document.getElementById('chart-container').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    // Chart options
                }
            });
        }

        let MonthData = () => {
            let year = document.getElementById('acdm_year').value
            let month = $("#fetchval").val();
            let user = '<?= $this->session->userdata('oliveMobileSystemUser') ?>'
            let span = '<span class="counter-value">********</span>'
            document.querySelector("#kschart").innerHTML = '';

            $.ajax({
                url: "<?= base_url('index.php/FeesDashboard/monthfilter') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    request: month,
                    year: year
                },
                beforeSend: function() {
                    $("#kschart").html(
                        '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
                    );
                },
                success: function(data) {

                    const series = [];
                    const value = [];
                    const value1 = [];
                    const value2 = [];
                    data.forEach(element => {
                        series.push(element.STVCLAS_standard);
                        value.push(parseFloat(element.class_fees));
                        value1.push(parseFloat(element.total_paid));
                        value2.push(parseFloat(element.class_fees) - parseFloat(
                            element.total_paid)); // Calculate the difference
                    });

                    console.log(value, value1, value2);

                    generateKChart(series, value, value1, value2);

                }

            });
        };

        MonthData()



        function generateKChart(series, value, value1, value2) {
            const options = {
                series: [{
                    name: 'Total Fees',
                    data: value
                }, {
                    name: 'Paid Fees',
                    data: value1
                }, {
                    name: 'Balance Fees',
                    data: value2
                }],
                chart: {
                    type: 'bar',
                    height: 240
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['#146C94', '#19A7CE', '#AFD3E2'],
                },
                xaxis: {
                    categories: series,
                },
                yaxis: {
                    title: {
                        text: ''
                    }
                },
                colors: ['#146C94', '#19A7CE', '#AFD3E2'],
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "" + val + " "
                        }
                    }
                }
            };
            document.querySelector("#kschart").innerHTML = '';
            const chart = new ApexCharts(document.querySelector("#kschart"),
                options);
            chart.render();
        }


        let fetchProject = () => {

            let from = document.getElementById('from_date').value;
            let to = document.getElementById('to_date').value;
            let year = document.getElementById('acdm_year').value;



            MonthBarChart(from, to, year);
            // Month1BarChart(from, to);
            paidBarChart(from, to, year);
            paidBarCharts(from, to, year);
            paid1BarCharts(from, to, year);
        }

        fetchProject();







        let chart_details = (id) => {
            let backData = document.getElementById('backData')
            backData.classList.add('d-none')
            let date = $("#from_date").val()
            let type = id
            let html = ''
            let user = '<?= $this->session->userdata('oliveMobileSystemUser') ?>'
            let span = '<span class="counter-value">********</span>'
            if (type == 'All') {

                let th = ['#', 'Class', 'No of Students', 'Total Fees']

                th.forEach(element => {
                    html += `<th>${element}</th>`
                })

            } else if (type == 'RTE') {
                let th = ['#', 'Student ID', 'Name', 'Standard']

                th.forEach(element => {
                    html += `<th>${element}</th>`
                })

            } else if (type == 'T') {
                let th = ['#', 'Class', 'No of Students', 'Total Fees']

                th.forEach(element => {
                    html += `<th>${element}</th>`
                })
            }


            $("#th_data").html(html)
            $('#sname-label').html('')


            $.ajax({
                url: "<?= base_url('index.php/FeesDashboard/daily_data') ?>",
                method: "GET",
                dataType: "json",
                data: {
                    date: date,
                    type: type
                },
                beforeSend: function() {
                    $("#chart5").html(
                        '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
                    );
                },
                success: function(data) {

                    let str = ''
                    let i = 0
                    let tc = 'class="text-center"'
                    data.forEach(element => {

                        let bg = element.spratnd_status == 'A' ? 'danger' : 'info'
                        if (type == 'All') {
                            str += `<tr onclick="TrData('${element.standard}','All',)">
                        <td ${tc}>${++i}</td>
                     <td ${tc}>${element.standard}</td>
                        <td ${tc}>${element.no_of_students}</td>
                        <td ${tc}><?php if ($this->session->userdata('oliveMobileSystemUser')) { ?>
                        ${element.total_fees}<?php } else { ?><span class="counter-value">********</span><?php } ?></td>
                    </tr>`
                        } else if (type == 'RTE') {
                            str += `<tr>
                        <td ${tc}>${++i}</td>
                     <td ${tc}>${element.student_id}</td>
                        <td ${tc}>${element.student_name}</td>
                        <td ${tc}>${element.standard}</td>
                    </tr>`
                        } else {
                            str += `<tr onclick="TrData('${element.standard}','T')">
                        <td ${tc}>${++i}</td>
                     <td ${tc}>${element.standard}</td>
                        <td ${tc}>${element.no_of_students}</td>
                        <td ${tc}><?php if ($this->session->userdata('oliveMobileSystemUser')) { ?>
                        ${element.total_fees}<?php } else { ?><span class="counter-value">********</span><?php } ?></td>
                    </tr>`
                        }

                    })

                    $("#chart_fetch3").html(str)
                    document.getElementById('kismatji-wala-card')
                }
            })

        }

        let TrData = (std, type, sname = '') => {
            let backData = document.getElementById('backData')
            backData.classList.remove('d-none')
            let year = document.getElementById('acdm_year').value
            let user = '<?= $this->session->userdata('oliveMobileSystemUser') ?>'
            let span = '<span class="counter-value">********</span>'
            let html = ''


            if (type == 'All') {
                let th = ['#', 'ID', 'Name', 'Standard', 'Total Fees', 'Paid Fees']
                th.forEach(element => {
                    html += `<th>${element}</th>`
                })
            } else if (type == 'RTE') {
                // FOR RTE STUDENT
                let th = ['#', 'ID', 'Name', 'Standard', 'Total Fees', 'Paid Fees']
                th.forEach(element => {
                    html += `<th>${element}</th>`
                })
            } else {
                let th = ['#', 'ID', 'Name', 'Standard', 'Total Fees', 'Paid Fees']
                th.forEach(element => {
                    html += `<th>${element}</th>`
                })
            }
            $("#th_data").html(html)
            $('#sname-label').html(sname)


            $.ajax({
                url: "<?= base_url('index.php/FeesDashboard/stud_data') ?>",
                method: "POST",
                data: {
                    std: std,
                    type: type,
                    year: year,

                },
                dataType: "JSON",
                success: function(data) {
                    let str = ''
                    let i = 0
                    let tc = 'class="text-center"'
                    data.forEach(element => {

                        let bg = element.spratnd_status == 'A' ? 'danger' : 'info'
                        if (type == 'All') {

                            str += `<tr onclick="FeesDetail('${element.SPRIDEN_student_id}','All','${element.name}')">

                        <td ${tc}>${++i}</td>
                        <td ${tc}>${element.SPRIDEN_student_id}</td>
                        <td ${tc}>${element.name}</td>
                        <td ${tc}>${element.STVCLAS_standard}</td>
                        <td ${tc}>${element.stvfstr_fees}</td>
                        <td ${tc}>${element.paid_fees}</td>
                    </tr>`

                        } else if (type == 'RTE') {
                            //For Rte Student
                        } else {
                            str += `<tr onclick="FeesDetail('${element.SPRIDEN_student_id}','T','${element.name}')">

                        <td ${tc}>${++i}</td>
                     <td ${tc}>${element.SPRIDEN_student_id}</td>
                        <td ${tc}>${element.name}</td>
                        <td ${tc}>${element.STVCLAS_standard}</td>
                        <td ${tc}>${element.stvfstr_fees}</td>
                        <td ${tc}>${element.paid_fees}</td>
                    </tr>`
                        }

                    })

                    $("#chart_fetch3").html(str)
                    $('#sname-label').html('')


                }
            });

        }


        let FeesDetail = (id, type, sname = '') => {



            let html = ''
            let user = '<?= $this->session->userdata('oliveMobileSystemUser') ?>'
            let span = '<span class="counter-value">********</span>'
            if (type == 'All') {

                let th = ['#', 'Date', 'Amount', 'Concession', 'Cons Note', 'Acdm. Year', 'Received By', 'Note']

                th.forEach(element => {
                    html += `<th>${element}</th>`
                })

            } else {
                let th = ['#', 'Date', 'Amount', 'Concession', 'Cons Note', 'Acdm. Year', 'Received By', 'Note']

                th.forEach(element => {
                    html += `<th>${element}</th>`
                })
            }

            $("#th_data").html(html)
            $('#sname-label').html(sname)

            $.ajax({
                url: "<?= base_url('index.php/FeesDashboard/stud_fetch') ?>",
                method: "POST",
                data: {
                    id: id,
                    type: type,
                },
                dataType: "JSON",
                success: function(data) {

                    let str = ''
                    let i = 0
                    let tc = 'class="text-center"'
                    data.forEach(element => {

                        let bg = element.spratnd_status == 'A' ? 'danger' : 'info'

                        if (type == 'All') {

                            str += `<tr>
                        <td ${tc}>${++i}</td>
                        <td>${element.sprfees_feesdate}</td>
                                 <td>${element.sprfees_amount}</td>
                                 <td>${element.sprfees_exumption}</td>
                                 <td>${element.sprfees_ex_notes}</td>
                                 <td>${element.stvacdm_year}</td>
                                 <td>${element.spruser_name}</td>
                                 <td>${element.sprfees_notes}</td>
                    </tr>`

                        } else {

                            str += `<tr>
                        <td ${tc}>${++i}</td>
                        <td>${element.sprfees_feesdate}</td>
                                 <td>${element.sprfees_amount}</td>
                                 <td>${element.sprfees_exumption}</td>
                                 <td>${element.sprfees_ex_notes}</td>
                                 <td>${element.stvacdm_year}</td>
                                 <td>${element.spruser_name}</td>
                                 <td>${element.sprfees_notes}</td>
                    </tr>`

                        }

                    })

                    $("#chart_fetch3").html(str)
                }


            });


        }

        var backData = (std, type, ) => {

            if (type != null) {
                FeesDetail(std, type)
            } else {
                chart_details('All')
            }

        }
    </script>

</body>

</html>
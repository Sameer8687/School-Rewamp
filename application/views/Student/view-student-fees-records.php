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
                </style>
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" style="background-color: #53769c !important; ">
                                <h5 class="card-title text-white">Student Fees Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row" style="margin-top: -20px; ">
                                            <label for="" class="col-md-2 text-left" style="font-weight: normal;">ID <span class="float-right">:</span></label>
                                            <label for="" class="col-md-5 text-left" style="font-weight: normal;"><?= $details['stu_details']['SPRIDEN_student_id']; ?></label>
                                        </div>
                                        <div class="form-group row" style="margin-top: -20px; ">
                                            <label for="" class="col-md-2 text-left" style="font-weight: normal;">Name <span class="float-right">:</span></label>
                                            <label for="" class="col-md-5 text-left" style="font-weight: normal;"><?= $details['stu_details']['name']; ?></label>
                                        </div>

                                        <div class="form-group row" style="margin-top: -20px;">
                                            <label for="" class="col-md-2 text-left" style="font-weight: normal;">Class <span class="float-right">:</span></label>
                                            <label for="" class="col-md-5 text-left" style="font-weight: normal;"><?= $details['stu_details']['STVCLAS_standard'] . " - " . $details['stu_details']['STVCLAS_class']; ?> (Current Class)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="margin-top: -20px; ">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr class="text-center">
                                                    <th style="background-color: #53769c; color: #fff;">#</th>
                                                    <th style="background-color: #53769c; color: #fff;">Class</th>
                                                    <th style="background-color: #53769c; color: #fff;">Academic Year</th>
                                                    <th style="background-color: #53769c; color: #fff;">Amount Fees</th>
                                                    <th style="background-color: #53769c; color: #fff;">Fees Date</th>
                                                    <th style="background-color: #53769c; color: #fff;">Due Date</th>
                                                    <th style="background-color: #53769c; color: #fff;">Notes</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($details['fees_records'] != null) {
                                                    $i = 1;
                                                    foreach ($details['fees_records'] as $record) {
                                                ?>
                                                        <tr class="text-center">
                                                            <td><?= $i; ?></td>
                                                            <td><?= $record['sprfees_standard']; ?></td>
                                                            <td><?= $record['stvacdm_year']; ?></td>
                                                            <td><?= $record['sprfees_amount']; ?></td>
                                                            <td><?= $record['sprfees_feesdate']; ?></td>
                                                            <td><?= $record['sprfees_due_date']; ?></td>
                                                            <td class="text-left"><?= $record['sprfees_notes']; ?></td>
                                                        </tr>
                                                    <?php
                                                        $i++;
                                                    }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="7" class="text-center">No Data Available</td>
                                                    </tr>
                                                <?php } ?>
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


    <script>
        $(document).on('click', '.view-record', function() {
            var stu_id = $(this).attr("data-id");
            var url = "<?= base_url(); ?>student/view_records/" + stu_id;
            window.location = url;
        });
    </script>
</body>

</html>
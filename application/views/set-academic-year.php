<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />

    <!---Include CSS Files-->
    <?php include 'cssPages.php'; ?>

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
    <?php include 'sidebar.php'; ?>

    <!--Include Top Navbar-->
    <?php include 'topNav.php'; ?>

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pcoded-content">
            <!-- [ Main Content ] start -->
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Set Academic Year</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url('Stats/update_acdm_year') ?>" method="POST">
                                <div class="form-group row mobile-margin-top-minus-15">
                                    <label for="" class="col-md-3 text-right">Select Year<span class="text-danger">*</span> :</label>
                                    <select class="form-control form-control-sm col-md-7" required name="year" id="year">
                                        <option value="" selected disabled>Select</option>
                                        <?php foreach ($all_acdm_years as $all_acdm_year) { ?>
                                            <option value="<?= $all_acdm_year['stvacdm_id'] ?>"><?= $all_acdm_year['stvacdm_year'] ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-sm btn-primary">Set Academic Year</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <a href="<?= base_url('stvacdm'); ?>">Add New Academic Year</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->
    </div>


    <!---Include JS Files-->
    <?php include 'jsPages.php'; ?>
</body>

</html>
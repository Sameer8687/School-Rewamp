<?php

defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Kolkata');



?>

<!DOCTYPE html>

<html lang="en">



<head>
    <base href="<?= base_url() ?>">

    <title>Olive English School - Setup</title>

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

    


    <!--Include Sidebar -->

    <?php include 'application/views/sidebar.php'; ?>



    <!--Include Top Navbar-->

    <?php include 'application/views/topNav.php'; ?>



    <!-- [ Main Content ] start -->

    <div class="pc-container">

        <div class="pcoded-content">

            <!-- [ Main Content ] start -->

            <div class="row margin-top-minus-40">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5 class="card-title">Web Application Setup</h5>
                                    <ul>
                                        <li>
                                            <a href="setup/web_app_visibility">Web App Visibility Setup</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
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

    <?php include 'application/views/jsPages.php'; ?>


</body>



</html>
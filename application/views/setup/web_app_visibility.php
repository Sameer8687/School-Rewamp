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
    <?php
    function showField($array, $key, $default = '')
    {
        if (isset($array[$key]))
            return $array[$key];
        return $default;
    }
    $student_profile = showField($visibility, 'student_profile', 'no');
    $marks_visibility = showField($visibility, 'marks_visibility', 'no');
    ?>

    <!-- [ Main Content ] start -->

    <div class="pc-container">

        <div class="pcoded-content">

            <!-- [ Main Content ] start -->
            <form action="setup/add_web_app_visibility" method="POST">
                <div class="row margin-top-minus-40">
                    <?php
                    $response = $this->session->flashdata('message');
                    if (is_array($response) && !empty($response)):
                        $color = $response['status'] ?? 'info';
                        $message = $response['message'] ?? '';
                    ?>
                        <div class="col-md-12">
                            <div class="alert alert-<?= $color ?>">
                                <?= $message; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h5 class="card-title">Student Profile Page Setup</h5>
                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <input type="checkbox" <?= $student_profile == 'yes' ? "checked" : '' ?> value="yes" id="chbx_student_profile" class="mr-2" onchange="showValue(this)">
                                                <input type="hidden" name="student_profile" id="student_profile" value="<?= $student_profile ?>">
                                                <span>
                                                    Student Profile Open
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <input type="checkbox" <?= $marks_visibility == 'yes' ? "checked" : '' ?> value="yes" id="chbx_marks_visibility" class="mr-2" onchange="showValue(this)">
                                                <input type="hidden" name="marks_visibility" id="marks_visibility" value="<?= $marks_visibility ?>">
                                                <span>
                                                    Show Marks
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <button class="btn btn-sm btn-info">Save Settings</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>

        <!-- [ Main Content ] end -->

    </div>

    </div>

    <!-- [ Main Content ] end -->

    </div>





    <!---Include JS Files-->

    <?php include 'application/views/jsPages.php'; ?>
    <script>
        function showValue(element) {
            let fieldId = element.getAttribute("id");
            let isChecked = element.checked ? "yes" : "no";
            let inputElementID = fieldId.replace("chbx_", "");
            if (document.getElementById(inputElementID))
                document.getElementById(inputElementID).value = isChecked;

        }
    </script>

</body>



</html>
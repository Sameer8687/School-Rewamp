<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Student Registration Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />

    <!---Include CSS Files-->
    <?php include 'application/views/cssPages.php'; ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/student-profile.css">

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

        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <?php include 'application/views/Student/links.php'; ?>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row margin-top-minus-10">
                <?php
                foreach ($students as $student) {
                ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="main-container">
                                    <div class="profile-info">
                                        <div class="info">
                                            <p class="name">

                                                <?= $student['SPRIDEN_surname']. " ". $student['SPRIDEN_student_name']. " ". $student['SPRIDEN_father_name'] ?>
                                            </p>
                                            <p class="student-id">ID : <?= $student['SPRIDEN_student_id']; ?> <br> <span class="">Std : <?= $student['SPRIDEN_admission_class']; ?></span></p>
                                            <p class="address">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <?= $student['SPRIDEN_main_address']; ?>
                                            </p>
                                            <hr>
                                            <div class="icon-container">
                                                <a href="tel:+918805629207"><i class="fas fa-phone-volume call"></i> </a>
                                                <a href="sms:+918805629207&body=Message%20From%20Olive%20English%20School"><i class="fas fa-sms sms"></i> </a>
                                                <a target="_blank" href="https://api.whatsapp.com/send?phone=+918805629207&text=Message%20From%20Olive%20English%20School"><i class="fab fa-whatsapp wha"></i> </a>
                                            </div>

                                        </div>
                                        <div class="profile">
                                            <img class="mt-4" src="<?php echo base_url(); ?>images/user.png" alt="">
                                            <a href class="btn btn-secondary btn-sm mt-3 w-100"><i class="fas fa-user-graduate"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
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
<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Update Student Details</title>
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
        <form id="form_data_data" action="<?php echo base_url(); ?>Student/update_student_details_by_form" enctype="multipart/form-data" method="post">
            <div class="pcoded-content">
                <!-- [ breadcrumb ] start -->
                <?php include 'application/views/Student/links.php'; ?>
                <!-- [ breadcrumb ] end -->
                <!-- [ Main Content ] start -->

                <div class="row margin-top-minus-40 mobile-margin-top-minus-40 mb-2">
                    <div class="col-md-7">
                        <a href="<?= base_url('Student/student_list') ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a>
                        
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-2 student-form-search">
                        <input type="text" class="form-control form-control-sm" id="search_student_name" placeholder="Enter Student ID" autocomplete="off">
                        <span class="left-icon"><i class="fas fa-search"></i></span>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-sm btn-outline-secondary" type="button" id="findStudent">Find</button>
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
                <div class="row justify-content-center" >
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <img src="<?= base_url('images/empty.png') ?>" class="img-fluid" alt="">
                                <h4 class="text-primary">Oops !!!</h4>
                                <p class="">Data not found</p>
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
        $('#findStudent').click(function(e) {
            var id = $('#search_student_name').val().trim();
            window.location = '<?= base_url('student/update_details/') ?>' + id;
        });
    </script>
</body>

</html>
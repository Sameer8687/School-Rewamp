<!DOCTYPE html>

<html lang="en">





<!-- Mirrored from html.phoenixcoded.net/nextro-able/bootstrap/default/auth-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 08 Oct 2020 11:09:44 GMT -->



<head>



    <title>Olive English School - Student Login</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />



    <!--Include CSS-->

    <?php include 'application/views/cssPages.php'; ?>

</head>



<!-- [ auth-signin ] start -->

<div class="auth-wrapper">

    <div class="auth-content">

        <div class="card">

            <div class="row align-items-center text-center">

                <div class="col-md-12">

                    <div class="card-body">

                        <img src="<?php echo base_url(); ?>images/logoNewDev.png" class="login-logo" alt=""
                            class="img-fluid mb-4">



                        <form action="<?php echo base_url(); ?>public/student/validate/aadhar_info" method="post">

                            <h6 class="pb-2" style="color: #53769c;">Enter Student Credentials</h6>
                            <div class="input-group mb-3">

                                <div class="input-group-prepend">

                                    <span class="input-group-text"><i data-feather="award"></i></span>

                                </div>

                                <input type="text" name="student_id" class="form-control"
                                    placeholder="Student ID">

                            </div>

                            <?php

                            $message = $this->session->userdata('Message');

                            if ($message != "") {

                                ?>

                                <div class="alert alert-danger">

                                    <?php echo $message; ?>

                                </div>

                            <?php } ?>

                            <style>
                                .myLoginButton {

                                    background-color: #53769c;

                                }



                                .myLoginButton:hover {

                                    background-color: #47678a;

                                    border-color: #47678a;

                                }
                            </style>

                            <button class="btn btn-block btn-primary mb-4 myLoginButton">Log In</button>





                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- [ auth-signin ] end -->



<!-- Required Js -->

<?php include 'application/views/jsPages.php'; ?>



</body>



</html>
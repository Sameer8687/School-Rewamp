<!DOCTYPE html>

<html lang="en">

<head>



    <title>Olive English School - Login</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />



    <!--Include CSS-->

    <?php include 'application/views/pages/cssPages.php'; ?>

</head>



<!-- [ auth-signin ] start -->

<div class="auth-wrapper">

    <div class="auth-content">

        <div class="card">

            <div class="row align-items-center text-center">

                <div class="col-md-12">

                    <div class="card-body">

                        <img src="<?php  echo base_url(); ?>images/logoNewDev.png" class="login-logo" alt="" class="img-fluid mb-4">



                        <form action="<?php echo base_url(); ?>Login/validate" method="post">

                            <div class="input-group mb-3">

                                <div class="input-group-prepend">

                                    <span class="input-group-text"><i data-feather="user"></i></span>

                                </div>

                                <input type="text" name="username" class="form-control" placeholder="Username">

                            </div>

                            <div class="input-group mb-4">

                                <div class="input-group-prepend">

                                    <span class="input-group-text"><i data-feather="lock"></i></span>

                                </div>

                                <input type="password" name="password" class="form-control" placeholder="Password">

                            </div>

                            <?php

                            $message = $this->session->userdata('Message');

                            if ($message != "") {

                            ?>

                                <div class="alert alert-danger">

                                    <?php echo $message; ?>

                                </div>

                                <?php } ?>

                                <div class="form-group text-left mt-2">

                                    <div class="custom-control custom-checkbox">

                                        <input type="checkbox" class="custom-control-input input-primary" id="customCheckdefh2" checked="">

                                        <label class="custom-control-label" for="customCheckdefh2">Remember Me</label>

                                    </div>

                                </div>

                                <style>

                                    .myLoginButton {

                                        background-color: #53769c;

                                    }

                                    

                                    .myLoginButton:hover {

                                        background-color: #47678a;

                                        border-color: #47678a;

                                    }

                                </style>

                                <button class="btn btn-block btn-primary mb-4 myLoginButton">Login</button>

                                <p class="mb-2 text-muted">Forgot password? <a href="<?php echo base_url(); ?>" class="f-w-400">Reset</a></p>



                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- [ auth-signin ] end -->



<!-- Required Js -->

<?php include 'application/views/pages/jsPages.php'; ?>



</body>



</html>
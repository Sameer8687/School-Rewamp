<!DOCTYPE html>

<html lang="en">





<!-- Mirrored from html.phoenixcoded.net/nextro-able/bootstrap/default/auth-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 08 Oct 2020 11:09:44 GMT -->



<head>



    <title>Olive English School - Teacher Login</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />



    <!--Include CSS-->

    <?php include 'cssPages.php'; ?>

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



                        <form action="<?php echo base_url(); ?>Teacher_login/validate" method="post">

                            <h6 class="pb-2" style="color: #53769c;">Teacher Login</h6>

                            <div class="input-group mb-2">

                                <div class="input-group-prepend">

                                    <span class="input-group-text"><i data-feather="award"></i></span>

                                </div>

                                <input type="text" name="username" id="teacher_id" required class="form-control"
                                    placeholder="Teacher ID">

                            </div>
                            <div class="input-group mb-2">

                                <div class="input-group-prepend">

                                    <span class="input-group-text"><i data-feather="key"></i></span>

                                </div>

                                <input type="password" name="password" required class="form-control"
                                    placeholder="Password">

                            </div>

                            <div class="input-group mb-3">

                                <div class="input-group-prepend">

                                    <span class="input-group-text"><i data-feather="check-square"></i></span>

                                </div>

                                <select name="class_type" id="class_type" required class="form-control">

                                    <option value="">Select Class</option>

                                    <!-- <option value="Random">Random Class</option> -->

                                    <!-- <option selected value="Assigned">Assigned Class</option> -->

                                </select>

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

                            <button class="btn btn-block btn-primary mb-4 myLoginButton">Login</button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- [ auth-signin ] end -->



<!-- Required Js -->

<?php include 'jsPages.php'; ?>

<!-- here 28 oct -->

<script>
    $(document).ready(function () {

        var baseUrl = "<?php echo base_url(); ?>";

        // Fetch classes when teacher ID field loses focus
        $('#teacher_id').on('blur change', function () {

            var teacherId = $.trim($(this).val());
            var $classSelect = $('#class_type');

            if (teacherId === '') {
                $classSelect.html('<option value="">Select Class</option>');
                return;
            }

            $classSelect.html('<option value="">Loading...</option>').prop('disabled', true);

            $.ajax({
                url: baseUrl + 'teacher_login/getAssignedClasses',
                method: 'POST',
                data: { teacher_id: teacherId },
                dataType: 'JSON',
                success: function (res) {

                    var options = '<option value="">Select Class</option>';

                    if (res.status && res.data.length > 0) {
                        $.each(res.data, function (i, row) {
                            var label = row.SPATESD_std +
                                (row.SPATESD_div ? ' - ' + row.SPATESD_div : '');
                            var val = row.SPATESD_std +
                                (row.SPATESD_div ? '-' + row.SPATESD_div : '');
                            options += '<option value="' + val + '">' + label + '</option>';
                        });
                    } else {
                        options += '<option value="">No class assigned</option>';
                    }

                    $classSelect.html(options).prop('disabled', false);
                },
                error: function () {
                    $classSelect.html('<option value="">Error loading classes</option>')
                        .prop('disabled', false);
                }
            });
        });
    });
</script>



</body>



</html>
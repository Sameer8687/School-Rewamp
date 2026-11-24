<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Olive English School - User Master</title>

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

        <form action="<?php echo base_url(); ?>MasterForm/add_dashboard_user" method="post">

            <div class="pcoded-content">

                <!-- [ breadcrumb ] start -->

                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->

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

                <div class="row margin-top-minus-40 mobile-margin-top-minus-10">

                    <div class="col-md-12">

                        <div class="card pb-0 mb-0">

                            <div class="card-header mb-0 pb-2 ">

                                <div class="row w-100">
                                    <div class="col-md-6">
                                        <h5 class="pt-2">Add Dashboard User</h5>
                                    </div>
                                    <div class="col-md-6">
                                    <button class="btn btn-secondary btn-sm float-right"> <i class="fas fa-bars"></i> <span id="dynamicLabelAddButton">Add Data</span></button>
                                    </div>
                                </div>

                            </div>

                            <div class="card-body pb-0 mb-0">

                                <style>
                                    label {

                                        text-align: right;

                                    }



                                    .no-margin {

                                        margin-top: -10px;

                                    }
                                </style>

                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="form-group row margin-top-minus-10">

                                            <label for="" class="col-md-5">User Type<span class="text-danger">*</span> :</label>

                                            <select name="usertype" id="usertype" class="form-control form-control-sm col-md-7">

                                                <option selected disabled value="">Select User Type</option>

                                                <option value="Admin">Admin</option>

                                                <option value="Co-Admin">Co-Admin</option>

                                            </select>

                                            <input type="hidden" class="form-control form-control-sm col-md-7" name="dbuser_id" id="school_id">

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row margin-top-minus-10">

                                            <label for="" class="col-md-5">Name<span class="text-danger">*</span> :</label>

                                            <input type="text" class="form-control form-control-sm col-md-7" name="nameofuser" id="nameofuser" required>

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row margin-top-minus-10">

                                            <label for="" class="col-md-5">Mobile No. :</label>

                                            <input type="text" class="form-control form-control-sm col-md-7" name="mobileno" id="mobileno">

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row margin-top-minus-20">

                                            <label for="" class="col-md-5">Username<span class="text-danger">*</span> :</label>

                                            <input type="text" class="form-control form-control-sm col-md-7" name="username" id="username" required>

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row margin-top-minus-20">

                                            <label for="" class="col-md-5">Password<span class="text-danger">*</span> :</label>

                                            <input type="text" class="form-control form-control-sm col-md-7" name="password" id="password" required>

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row margin-top-minus-20">

                                            <label for="" class="col-md-5">Email ID :</label>

                                            <input type="email" class="form-control form-control-sm col-md-7" name="email" id="email">

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- [ Search List ] Start -->

                            <div class="search-list-container-panel">

                                <div class="list-group" id="dynamic_name_list"></div>

                            </div>

                            <!-- [ Search List ] End -->

                        </div>

                    </div>

                </div>

                <div class="row mt-1">

                    <div class="col-md-12">

                        <div class="card">

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="dt-responsive table-responsive margin-top-minus-10">

                                            <table id="simpletable" class="table table-striped table-bordered nowrap">

                                                <thead>

                                                    <tr>

                                                        <th>Sr.No.</th>

                                                        <th>Name</th>

                                                        <th>User Name</th>

                                                        <th>Mobile No</th>

                                                        <th>Email</th>

                                                        <th>User Type</th>

                                                    </tr>

                                                </thead>

                                                <style>
                                                    #simpletable tbody tr td {

                                                        padding: 5px;

                                                        font-size: 12px;

                                                        font-weight: 500;

                                                    }



                                                    #simpletable tbody tr td:hover {

                                                        cursor: pointer;

                                                    }
                                                </style>

                                                <tbody>

                                                    <?php

                                                    $i = 1;

                                                    foreach ($users as $us) {

                                                    ?>

                                                        <tr data-name="<?= $us['spruser_id']; ?>" class="rowToUpdateData">

                                                            <td><?= $i; ?></td>

                                                            <td><?= $us['spruser_name']; ?></td>

                                                            <td><?= $us['spruser_username']; ?></td>

                                                            <td><?= $us['spruser_mobile']; ?></td>

                                                            <td><?= $us['spruser_email']; ?></td>

                                                            <td><?= $us['spruser_usertype']; ?></td>



                                                        </tr>

                                                    <?php $i++;
                                                    } ?>

                                                </tbody>

                                            </table>

                                        </div>

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
        $('.rowToUpdateData').on('click', function() {

            var rowID = $(this).attr('data-name');

            $.ajax({

                url: "<?= base_url(); ?>index.php/MasterForm/getDBUserDataByID",

                method: "POST",

                dataType: "JSON",

                data: {
                    id: rowID
                },

                success: function(data) {

                    if (data != "fail") {

                        $('#school_id').val(rowID);

                        $('#usertype').val(data.spruser_usertype);

                        $('#nameofuser').val(data.spruser_name);

                        $('#mobileno').val(data.spruser_mobile);

                        $('#username').val(data.spruser_username);

                        $('#password').val(data.spruser_password);

                        $('#email').val(data.spruser_email);



                        $('#dynamicLabelAddButton').html("Update Data")

                    }

                }

            });

        });
    </script>

</body>



</html>
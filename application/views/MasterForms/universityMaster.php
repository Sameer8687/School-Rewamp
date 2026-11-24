<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Olive English School - University Master</title>

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

        <form action="<?php echo base_url(); ?>MasterForm/add_university_data" method="post">

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
                                        <h5 class="pt-2">Add University</h5>
                                    </div>
                                    <div class="col-md-6">
                                    <button class="btn btn-secondary btn-sm float-right"> <i class="fas fa-building"></i> <span id="dynamicLabelAddButton">Add Data</span></button>
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

                                        <div class="form-group row">

                                            <label for="" class="col-md-5">Name<span class="text-danger">*</span> :</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="name" id="name" required>

                                            <input type="hidden" class="form-control form-control-sm col-md-7" name="university_id" id="school_id">

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-5">Address :</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="address" id="address">

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-5">Phone No :</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="phone" id="phone">

                                        </div>

                                    </div>

                                </div>

                                <div class="row margin-top-minus-20">

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-5">State :</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="state" id="state">

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-5">City :</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="city" id="city">

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-5">E-Mail :</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="email" id="email">

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

                                                        <th>Address</th>

                                                        <th>City</th>

                                                        <th>State</th>

                                                        <th>Phone</th>

                                                        <th>Email</th>

                                                        <th>Added By</th>

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

                                                    foreach ($universities as $uni) {

                                                    ?>

                                                        <tr data-name="<?= $uni['STVUNIV_code']; ?>" class="rowToUpdateData">

                                                            <td><?= $i; ?></td>

                                                            <td><?= $uni['STVUNIV_name']; ?></td>

                                                            <td><?= $uni['STVUNIV_address']; ?></td>

                                                            <td><?= $uni['STVUNIV_city']; ?></td>

                                                            <td><?= $uni['STVUNIV_state']; ?></td>

                                                            <td><?= $uni['STVUNIV_phone']; ?></td>

                                                            <td><?= $uni['STVUNIV_email']; ?></td>

                                                            <td><?= $uni['spruser_name'] . " - " . $uni['spruser_usertype']; ?></td>

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

                url: "<?= base_url(); ?>index.php/MasterForm/getUniversityDataByID",

                method: "POST",

                dataType: "JSON",

                data: {

                    id: rowID

                },

                success: function(data) {

                    if (data != "fail") {

                        $('#name').val(data.STVUNIV_name);

                        $('#school_id').val(rowID);

                        $('#address').val(data.STVUNIV_address);

                        $('#phone').val(data.STVUNIV_phone);

                        $('#state').val(data.STVUNIV_state);

                        $('#city').val(data.STVUNIV_city);

                        $('#email').val(data.STVUNIV_email);



                        $('#dynamicLabelAddButton').html("Update Data")

                    }

                }

            });

        });
    </script>



</body>



</html>
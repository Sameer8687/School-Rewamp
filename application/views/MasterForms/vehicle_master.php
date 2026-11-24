<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Olive English School - College Master</title>

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

        <form action="<?php echo base_url(); ?>MasterForm/addVehicle" method="post">

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
                                        <h5 class="pt-2">Add Vehicle</h5>
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

                                    <div class="col-md-6">

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-5">Driver Name<span class="text-danger">*</span> :</label>

                                            <select class="form-control form-control-sm col-md-5" name="name" id="name">

                                                <option value="">Select Class</option>

                                                <?php

                                                foreach ($name as $n) {

                                                ?>

                                                    <option value="<?php echo $n['id']; ?>">

                                                        <?php echo $n['name']; ?></option>

                                                <?php } ?>



                                            </select>

                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-5">Vehicle Route<span class="text-danger">*</span> :</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="route" id="route">

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-5">Vehicle Type<span class="text-danger">*</span> :</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="type" id="type">

                                            <input type="hidden" autocomplete="off" class="form-control form-control-sm col-md-7" name="header_id" id="header_id">

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

                                                        <th>Vehicle No.</th>

                                                        <th>Driver Name</th>

                                                        <th>Vehicle Route</th>

                                                        <th>Vehicle Type</th>

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



                                                    foreach ($vehicles as $vehicle) {

                                                    ?>

                                                        <tr data-id="<?= $vehicle['id']; ?>" class="rowToUpdateData">

                                                            <td><?= $i; ?></td>

                                                            <td><?= $vehicle['v_number']; ?></td>

                                                            <td><?= $vehicle['name']; ?></td>

                                                            <td><?= $vehicle['vehicle_route']; ?></td>

                                                            <td><?= $vehicle['vehicle_type']; ?></td>

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

            var rowID = $(this).attr('data-id');

            $.ajax({

                url: "<?= base_url(); ?>index.php/MasterForm/getVehicleDataByID?id=" + rowID,

                method: "POST",

                dataType: "JSON",

                data: {

                    id: rowID

                },

                success: function(data) {

                    if (data != "fail") {

                        $('#route').val(data.vehicle_route);

                        $('#header_id').val(data.id);

                        $('#type').val(data.vehicle_type);

                        $('#dynamicLabelAddButton').html("Update Data")

                    }

                }

            });

        });
    </script>



</body>



</html>
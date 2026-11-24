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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.0-beta1/js/bootstrap.bundle.min.js"></script>

</head>
<style>
    .table thead tr th,

    .table tbody tr td {

        padding: 5px !important;

        font-size: 12px !important;

        border: 1px solid #77777730;

    }



    .table tbody tr td.no-padding {

        padding: 0 !important;

    }

    .table tbody tr td .form-control.no-border {

        border: none !important;

        outline: none !important;

    }
</style>


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

        <form id="" action="<?= base_url('index.php/Transportation/Vehicle_ad') ?>" method="post">

            <div class="pcoded-content">

                <!-- [ breadcrumb ] start -->

                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->



                <div class="row margin-top-minus-40 mobile-margin-top-minus-20 mb-2">

                    <div class="col-md-7">

                        <!-- <a href="<?php echo base_url(); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a> -->





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

                <div class="row">

                    <div class="col-md-12">

                        <div class="card fees-card">

                            <div class="card-header mb-0 pb-2 " style="background-color: #53769c !important; ">

                                <h5 class="text-white pl-2" style="font-weight: normal !important; text-decoration: underline;">
                                </h5>
                                <div class="row w-100">
                                    <div class="col-md-6">
                                        <h5 class="pt-2 text-white">Add Vehicle</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-secondary btn-sm float-right" type="submit"> <i class="fas fa-dollar-sign"></i> <span id="add">Add</span></button>
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

                                            <label for="" class="col-md-5">Vehicle Name<span class="text-danger"></span>
                                                :</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-5" name="Vehicle_name">
                                            <!-- 
                                            <input type="hidden" autocomplete="off" class="form-control form-control-sm col-md-7" name="header_id" id="header_id"> -->

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-5">Wheel Type<span class="text-danger">*</span>
                                                :</label>

                                            <select class="form-control form-control-sm col-md-5" name="Wheel_type">

                                                <option value="">Select Wheel Type</option>
                                                <option value="3 Wheeler"> 3 Wheeler </option>
                                                <option value="4 Wheeler"> 4 Wheeler </option>

                                            </select>

                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <!-- <div class="col-md-6">

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-5">Vehicle Type<span class="text-danger">*</span> :</label>

                                            <select class="form-control form-control-sm col-md-5" name="Vehicle_type">

                                                <option value="0">Select Vehicle Type</option>
                                                <option value="Rikshaw">Rikshaw</option>
                                                <option value="Bus">Bus</option>
                                                <option value="Van">Van</option>
                                                <option value="Cab">Cab</option>
                                                <option value="Tempo Van">Tempo Van</option>
                                            </select>

                                        </div>

                                    </div> -->

                                    <div class="col-md-6">

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-5">Vehicle <span class="text-danger">*</span>
                                                :</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-5" name="Vehicle">

                                            <!-- <input type="hidden" autocomplete="off" class="form-control form-control-sm col-md-7" name="header_id" id="header_id"> -->

                                        </div>

                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-5">No of Seat <span class="text-danger">*</span>
                                                :</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-5" name="no_seat">

                                            <!-- <input type="hidden" autocomplete="off" class="form-control form-control-sm col-md-7" name="header_id" id="header_id"> -->

                                        </div>

                                    </div>


        </form>
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

                                <table class="table table-striped table-bordered nowrap">

                                    <thead>


                                        <tr>

                                            <th>Sr.No.</th>

                                            <th>Vehicle Name</th>

                                            <th>Wheel Type</th>

                                            <!-- <th>Vehicle Type</th> -->
                                            <th>Vehicle</th>
                                            <th>No_of_Seat</th>
                                            <th>Update</th>
                                            <th>Delete</th>

                                        </tr>

                                        </tr>

                                    </thead>
                                    <tbody>

                                        <?php $i = 0 ?>
                                        <?php
                                        // $rows= get_row();
                                        foreach ($rows as $row) : ?>
                                            <?php $i = ++$i ?>
                                            <form id="Vmaster" action="<?= base_url('index.php/Transportation/updateV') ?>" method="post">
                                                <tr>
                                                    <input type="hidden" value="<?= $row['sr_no'] ?>" name="sr_id" id="sr_id">
                                                    <td id="id">
                                                        <?php echo $i ?></td>
                                                    <td>
                                                        <input style="border: none;" type="text" autocomplete="off" class="form-control form-control-sm col-md-12" name="Vehicle_name" id="Vehicle_name" value="<?= $row['Vehicle_name'] ?>">
                                                    </td>
                                                    <td>
                                                        <input style="border: none;" type="text" autocomplete="off" class="form-control form-control-sm col-md-12" name="Wheel_type" id="Wheel_type" value="<?php echo $row['Wheel_type'] ?>">
                                                    </td>

                                                    <td>
                                                        <input style="border: none;" type="text" autocomplete="off" class="form-control form-control-sm col-md-12" name="Vehicle" id="Vehicle" value="<?php echo $row['Vehicle'] ?>">
                                                    </td>
                                                    <td>
                                                        <input style="border: none;" type="text" autocomplete="off" class="form-control form-control-sm col-md-12" name="no_seat" id="no_seat" value="<?= $row['no_seat'] ?>">
                                                    </td>
                                                    <td style="text-align:center;"><button href="" id="" type="submit" class="btn btn-sm btn-success">Edit</button>
                                                    </td>

                                                    <td><a href="vehicle_delete/<?= $row['sr_no'] ?>" class="btn btn-sm btn-danger ">Delete</a></td>





                                                </tr>
                                            </form>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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

                            </div>

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

    <script>
        $(document).on("submit", "#Vehicle_master", function(e) {
            e.preventDefault();

            $.ajax({
                url: "<?php echo base_url('index.php/Transportation/Vehicle_ad') ?>",
                type: "POST",
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {

                    console.log(response);

                }
            })

        });


        function deleteRow(row) {
            var d = row.parentNode.parentNode.rowIndex;
            document.getElementByIdd('table').deleteRow(d);
        }
    </script>

    <script>
        // $('#Vmaster').on("submit", function(e) {
        //     e.preventDefault();
        //     // var $('#moble').val()

        //     $.ajax({
        //         url: "<?php echo base_url('index.php/Transportation/updateV') ?>" + $rows - > sr_no,
        //         type: "POST",
        //         data: $(this).serializeArray(),
        //         dataType: 'json',
        //         success: function(response) {

        //             console.log(response);

        //         }
        //     })

        // })
    </script>


</body>

</html>
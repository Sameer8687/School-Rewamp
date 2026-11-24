<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Olive English School - Student Fees Form</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />



    <!---Include CSS Files-->

    <?php include 'application/views/cssPages.php'; ?>

    <!-- Boxicons CSS -->

    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

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

        <form id="" action="<?= base_url('index.php/Transportation/add') ?>" method="post">

            <div class="pcoded-content">

                <!-- [ breadcrumb ] start -->

                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->


                <div class="row " id="alertRow">



                </div>

                <div class="row margin-top-minus-40 mobile-margin-top-minus-10">

                    <div class="col-md-12">

                        <div class="card fees-card">

                            <div class="card-header" style="background-color: #53769c !important; ">
                                <div class="row w-100">
                                    <div class="col-md-6">
                                        <h5 class="pt-2" style="color: #fff;">Driver Master</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-secondary btn-sm float-right" type="submit"> <i class="fas fa-dollar-sign"></i> <span id="add">Add</span></button>
                                    </div>
                                </div>



                            </div>

                            <div class="card-body" style="padding:35px;">

                                <style>
                                    label {

                                        text-align: right;

                                    }

                                    .no-margin {

                                        margin-top: -10px;

                                    }
                                </style>

                                <style>
                                    .mini-label label {

                                        font-size: 12px !important;

                                        font-weight: normal !important;

                                    }
                                </style>

                                <div class="row mini-label" style="margin-top: -10px;">

                                    <div class="col-md-6">

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-4 text-left">Driver Name</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-5" name="Driver_name">

                                        </div>

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-4 text-left">Mobile No:</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-5" name="Mobile_no">

                                        </div>

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-4 text-left">Whatsapp No:</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-5" name="Whatsapp_no">

                                        </div>
                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-4 text-left">Address

                                                :</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-4" value="" name="Address" id="Address">

                                        </div>

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-4 text-left">Mobile No 2:</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-4" name="mobile" id="mobile">

                                        </div>

                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-4 text-left">Vehicle:<span class="text-danger">*</span></label>

                                            <select name="Vehicle" id="Vehicle" class="form-control form-control-sm cust-border-bottom col-md-4">

                                                <option value="0">Select Vehicle Type</option>
                                                <option value="Rikshaw">Rikshaw</option>
                                                <option value="Bus">Bus</option>
                                                <option value="Van">Van</option>
                                                <option value="Cab">Cab</option>
                                                <option value="Tempo Van">Tempo Van</option>


                                            </select>


                                        </div>

        </form>

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


                                <table class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>

                                            <th>Sr.No.</th>

                                            <th>Driver Name</th>

                                            <th>Mobile No</th>

                                            <th>Whatsapp No</th>

                                            <th>Address</th>

                                            <th>Mobile No 2</th>

                                            <th>Vehicle</th>
                                            <th>Update</th>
                                            <th>Delete</th>



                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php $i = 0 ?>
                                        <?php foreach ($rows as $row) : ?>
                                            <?php $i = ++$i ?>
                                            <form id="Editmaster" action="<?= base_url('index.php/Transportation/updatemodal') ?>" method="post">
                                                <input type="hidden" value="<?= $row['sr_no'] ?>" name="sr_id" id="sr_id">
                                                <tr>
                                                    <td id="id"> <?php echo $i ?></td>
                                                    <td><input style="border: none;" type="text" autocomplete="off" class="form-control form-control-sm col-md-12" name="Driver_name" id="Driver_name" value="<?= $row['Driver_name'] ?>">
                                                    </td>
                                                    <td><input style="border: none;" type="text" autocomplete="off" class="form-control form-control-sm col-md-12" name="Mobile_no" id="Mobile_no" value="<?php echo $row['Mobile_no'] ?>"></td>
                                                    <td><input style="border: none;" type="text" autocomplete="off" class="form-control form-control-sm col-md-12" name="Whatsapp_no" id="Whatsapp_no" value="<?php echo $row['Whatsapp_no'] ?>"></td>
                                                    <td><input style="border: none;" type="text" autocomplete="off" class="form-control form-control-sm col-md-12" name="Address" id="Address" value="<?php echo $row['Address'] ?>"></td>
                                                    <td><input style="border: none;" type="text" autocomplete="off" class="form-control form-control-sm col-md-12" name="mobile" id="mobile" value="<?php echo $row['mobile'] ?>"></td>
                                                    <td><input style="border: none;" type="text" autocomplete="off" class="form-control form-control-sm col-md-12" name="Vehicle" id="Vehicle" value="<?php echo $row['Vehicle'] ?>"></td>
                                                    <td><button href="" id="" type="submit" class="btn btn-sm btn-success">Edit</button>
                                                    </td>

                                                    <td><a href="delete/<?= $row['sr_no'] ?>" class="btn btn-sm btn-danger ">Delete</a></td>





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

                                    .td .inp {
                                        width: fit-content;
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
        $(document).on("submit", "#Driver_master", function(e) {
            e.preventDefault();

            $.ajax({
                url: "<?php echo base_url('index.php/Transportation/add') ?>",
                type: "POST",
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {

                    console.log(response);

                }
            })

        })


        function deleteRow(row) {
            var d = row.parentNode.parentNode.rowIndex;
            document.getElementByIdd('table').deleteRow(d);
        }
    </script>

    <script>
        // $('#Editmaster').on("submit", function(e) {
        //     e.preventDefault();
        //     // var $('#moble').val()

        //     $.ajax({
        //         url: "<?php echo base_url('index.php/Transportation/updatemodal') ?>" + $rows - > sr_no,
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
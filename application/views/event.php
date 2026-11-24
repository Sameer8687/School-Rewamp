<?php

defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Kolkata');



?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Olive English School - Dashboard</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />



    <!---Include CSS Files-->

    <?php include 'cssPages.php'; ?>



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

    <?php include 'sidebar.php'; ?>



    <!--Include Top Navbar-->

    <?php include 'topNav.php'; ?>



    <!-- [ Main Content ] start -->

    <div class="pc-container">

        <form action="<?php echo base_url(); ?>Event/add_event" method="post">

            <div class="pcoded-content">

                <!-- [ Main Content ] start -->

                <div class="row margin-top-minus-40 mb-2">

                    <div class="col-md-7">

                        <!-- <a href="" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a> -->

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

                        <div class="card">

                            <div class="card-header p-2">
                                <div class="row w-100">
                                    <div class="col-md-6">
                                        <h5 class="pt-2">Event Management</h5>
                                    </div>
                                    <div class="col-md-6">
                                    <button class="btn btn-secondary btn-sm float-right"> <i class="fas fa-calendar-alt"></i> <span id="dynamicLabelAddButton">Add Event</span></button>
                                    </div>
                                </div>

                            </div>

                            <div class="card-body mb-0 pb-0">

                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-5 text-right">Start Date<span class="text-danger">*</span> :</label>

                                            <input type="date" class="form-control form-control-sm col-md-7" id="start_date" name="start_date" required>

                                            <input type="hidden" class="form-control form-control-sm col-md-7" id="school_id" name="event_id">

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-5 text-right">End Date<span class="text-danger">*</span> :</label>

                                            <input type="date" class="form-control form-control-sm col-md-7" id="end_date" name="end_date" required>

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-5 text-right">Title<span class="text-danger">*</span> :</label>

                                            <input type="text" class="form-control form-control-sm col-md-7" id="title" name="title" required>

                                        </div>

                                    </div>

                                    <style>
                                        .custom-details-row {

                                            margin-left: -48px;

                                        }



                                        @media only screen and (max-width: 1300px) and (min-width: 1200px) {

                                            .custom-details-row {

                                                margin-left: -45px;

                                            }

                                        }



                                        /* 1920 - 1080 */

                                        @media screen and (min-width: 1900px) {

                                            .custom-details-row {

                                                margin-left: -68px;

                                            }

                                        }
                                    </style>

                                    <div class="col-md-12 margin-top-minus-10">

                                        <div class="form-group row custom-details-row margin-top-minus-10">

                                            <label for="" class="col-md-2 text-right">Details :</label>

                                            <input type="text" class="form-control form-control-sm col-md-10" name="details" id="details">

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row margin-top-minus-20">

                    <div class="col-md-12">

                        <div class="card">

                            <div class="card-header">

                                <h5>

                                    Events

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="dt-responsive table-responsive margin-top-minus-10">

                                            <table id="simpletable" class="table table-striped table-bordered nowrap">

                                                <thead>

                                                    <tr>

                                                        <th>Sr.No.</th>

                                                        <th>Start Date</th>

                                                        <th>End Date</th>

                                                        <th>Title</th>

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

                                                    foreach ($all_events as $all_event) {

                                                    ?>

                                                        <tr data-name="<?= $all_event['stvevnt_id']; ?>" class="rowToUpdateData">

                                                            <td><?= $i; ?></td>

                                                            <td><?= date('d M Y', strtotime($all_event['stvevnt_start_date'])); ?></td>

                                                            <td><?= date('d M Y', strtotime($all_event['stvevnt_end_date'])); ?></td>

                                                            <td><?= $all_event['stvevnt_title']; ?></td>





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

    <?php include 'jsPages.php'; ?>



    <script>
        $('.rowToUpdateData').on('click', function() {

            var rowID = $(this).attr('data-name');

            $.ajax({

                url: "<?= base_url(); ?>index.php/Event/getEventByID",

                method: "POST",

                dataType: "JSON",

                data: {

                    id: rowID

                },

                success: function(data) {

                    if (data != "fail") {

                        $('#start_date').val(data.stvevnt_start_date);

                        $('#school_id').val(rowID);

                        $('#end_date').val(data.stvevnt_end_date);

                        $('#title').val(data.stvevnt_title);

                        $('#details').val(data.stvevnt_details);



                        $('#dynamicLabelAddButton').html("Update Data")

                    }

                }

            });

        });
    </script>

</body>



</html>
<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Olive English School </title>

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

        <form action="<?php echo base_url(); ?>MsgTemplate/msgtemplate" method="post">

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

                                <h5 class="margin-top-minus-10 mb-0 pb-0"></h5>

                                <div class="row w-100">

                                    <div class="col-md-6">

                                        <h5 class="pt-2">Add Message Template</h5>

                                    </div>

                                    <div class="col-md-6">
                                    <button class="btn btn-secondary btn-sm float-right"> <i class="fas fa-bars"></i> <span id="dynamicLabelAddButton">Add Data</span></button>
                                    </div>

                                </div>

                            </div>

                            <div class="card-body pb-0 mb-0">

                                <style>
                                    .no-margin {

                                        margin-top: -10px;

                                    }
                                </style>

                                <div class="row" style="margin-top: -15px;">

                                    <div class="col-md-12">

                                        <div class="form-group row">

                                            <label for="" class="col-md-3">Message Template<span class="text-danger">*</span> :</label>

                                            <textarea class="form-control form-control-sm col-md-9" id="messg" name="messg" rows="3" cols="50"></textarea>

                                        </div>

                                        <input type="hidden" class="form-control form-control-sm col-md-7" name="temp_id" id="temp_id">

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

                                                                <th>Message Template</th>



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

                                                            foreach ($all_records as $all_record) {

                                                            ?>

                                                                <tr data-name="<?= $all_record['stvmsgt_id']; ?>" class="rowToUpdateData">

                                                                    <td><?= $i; ?></td>

                                                                    <td><?= $all_record['stvmsgt_text']; ?></td>

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

                url: "<?= base_url(); ?>index.php/MsgTemplate/getRecordByID",

                method: "POST",

                dataType: "JSON",

                data: {

                    id: rowID

                },

                success: function(data) {

                    if (data != "fail") {

                        $('#messg').val(data.stvmsgt_text);

                        $('#temp_id').val(data.stvmsgt_id);

                        $('#dynamicLabelAddButton').html("Update Data");

                    }

                }

            });

        });
    </script>

</body>



</html>
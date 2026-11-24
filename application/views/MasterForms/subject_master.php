<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Olive English School - Subject Master</title>

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

        <form action="<?php echo base_url(); ?>MasterForm/add_subject" method="post">

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
                                        <h5 class="pt-2">Add Subject</h5>
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

                                        <div class="form-group row">

                                            <label for="" class="col-md-5">Subject<span class="text-danger">*</span> :</label>

                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="subject" id="subject" required>

                                            <input type="hidden" class="form-control form-control-sm col-md-7" name="subject_id" id="school_id">

                                        </div>

                                    </div>

                                    <div class="col-md-3">

                                        <div class="form-group row">

                                            <label for="" class="col-md-5">Standard<span class="text-danger">*</span> :</label>

                                            <select class="form-control form-control-sm col-md-7" name="class" id="class" required>

                                                <option selected disabled value="">Select Standard</option>

                                                <?php

                                                foreach ($classes as $class) {

                                                ?>

                                                    <option value="<?php echo $class['stvfees_class']; ?>"><?php echo $class['stvfees_class']; ?></option>

                                                <?php } ?>



                                            </select>

                                        </div>

                                    </div>
                                    <div class="col-md-5">

                                        <div class="form-group row">

                                            <label for="" class="col-md-5 d-flex">
                                                Academic Year<span class="text-danger">*</span> :
                                            </label>

                                            <select
                                                class="form-control form-control-sm col-md-7 d-flex"
                                                name="acdm_year"
                                                id="acdm_year"
                                                required>

                                                <option value="">Select Academic Year</option>

                                                <?php foreach ($academic_years as $academic_year) { ?>

                                                    <option
                                                        <?= ($selected_academic_year == $academic_year['stvacdm_id']) ? 'selected' : ''; ?>
                                                        value="<?= $academic_year['stvacdm_id']; ?>">

                                                        <?= $academic_year['stvacdm_year']; ?>

                                                    </option>

                                                <?php } ?>

                                            </select>

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

                                                        <th>Subject Name</th>

                                                        <th>Standard</th>

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

                                                    foreach ($subjects as $subject) {

                                                    ?>

                                                        <tr data-name="<?= $subject['stvsubj_id']; ?>" class="rowToUpdateData">

                                                            <td><?= $i; ?></td>

                                                            <td><?= $subject['stvsubj_subject']; ?></td>

                                                            <td><?= $subject['stvsubj_std']; ?></td>

                                                            <td><?= $subject['spruser_name'] . " - " . $subject['spruser_usertype']; ?></td>



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

                url: "<?= base_url(); ?>index.php/MasterForm/getSubjectDataByID",

                method: "POST",

                dataType: "JSON",

                data: {

                    id: rowID

                },

                success: function(data) {

                    if (data != "fail") {

                        $('#subject').val(data.stvsubj_subject);

                        $('#school_id').val(rowID);

                        $('#class').val(data.stvsubj_std);
                        $('#acdm_year').val(data.stvsubj_acdm);



                        $('#dynamicLabelAddButton').html("Update Data")

                    }

                }

            });

        });
    </script>

</body>



</html>
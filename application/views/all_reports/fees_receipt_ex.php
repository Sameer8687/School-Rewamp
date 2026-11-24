<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Olive English School - Student Books & Result Form</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />



    <!---Include CSS Files-->

    <?php include 'application/views/cssPages.php'; ?>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" />

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



        .dt-button.buttons-print,

        .dt-button.buttons-copy,

        .dt-button.buttons-csv,

        .dt-button.buttons-excel,

        .dt-button.buttons-pdf {




            background-color: #53769c !important;

            color: #fff !important;

            padding: 0px 10px 0px 10px !important;

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

        <form action="" id="submit-form" method="post">

            <div class="pcoded-content">

                <!-- [ breadcrumb ] start -->

                <?php include 'application/views/all_reports/links.php'; ?>

                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->



                <div class="row margin-top-minus-40 mobile-margin-top-minus-40 mb-2">



                </div>

                <div class="print-container">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="card">

                                <div class="card-body p-2">

                                    <div class="row">

                                        <div class="col-md-12">

                                            <!-- <h6>Filter Report</h6> -->

                                            <div class="row">

                                                <div class="col-md-3">

                                                    <div class="form-group">

                                                        <label for="">From Date <span class="float-right">:</span></label>
                                                        <input type="date" name="from_date" id="from_date" class=" form-control form-control-sm" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">To Date <span class="float-right">:</span></label>
                                                        <input type="date" name="to_date" id="to_date" class="form-control form-control-sm" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="" style="opacity: 0;">Click on the</label>
                                                        <button id="find-students" type="button" class="btn btn-sm btn-outline-secondary" style="margin:27px;" value="">View Data</button>
                                                        <button onclick="download_excel()" type="button" class="btn btn-sm btn-outline-secondary" style="margin:27px;" value="">Download Excel</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <!-- [ Main Content ] end -->

    </div>

    </div>

    <!-- [ Main Content ] end -->

    </div>

    </div>

    <!---Include JS Files-->

    <?php include 'application/views/jsPages.php'; ?>

    <script src=" https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js">
    </script>

    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js">
    </script>

    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js">
    </script>

    <script>
        $('#find-students').click(function() {
            let from_date = $('#from_date').val();
            let to_date = $('#to_date').val();

            window.location = `<?= base_url() ?>report/fees_recipts/${from_date}/${to_date}`;
        });

        function download_excel(){
            let from_date = $('#from_date').val();
            let to_date = $('#to_date').val();

            window.location = `<?= base_url() ?>report/fees_recipts/${from_date}/${to_date}/excel`;
        }
    </script>

</body>

</html>
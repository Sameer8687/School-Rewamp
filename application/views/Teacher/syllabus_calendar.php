<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title> | Olive English School</title>

    <!-- Meta -->

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />





    <?php include 'application/views/cssPages.php'; ?>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/teacher-module.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/new-db.css">

    <!-- Boxicons CSS -->

    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

</head>



<body class="">

    <!-- [ Pre-loader ] start -->

    <div class="loader-bg">

        <div class="loader-track">

            <div class="loader-fill"></div>

        </div>

    </div>

    <!-- [ Pre-loader ] End -->

    <?php include 'sidebar.php'; ?>

    <!-- [ Header ] start -->

    <?php include 'topNav.php'; ?>

    <!-- [ Header ] end -->



    <!-- [ Main Content ] start -->

    <div class="pc-container">

        <div class="pcoded-content">
            <input type="hidden" id="getFullScreenMode">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Std</label>
                        <select name="std" id="std" class="form-control form-control-sm">
                            <option value="">Select</option>
                            <?php foreach ($classes as $classs) {  ?>
                                <option <?= $classs['stvfees_class'] == $class ? 'selected' : '' ?> value="<?php echo $classs['stvfees_class']; ?>"><?php echo $classs['stvfees_class']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Div</label>
                        <select name="div" id="div" class="form-control form-control-sm">
                            <option value="">Select</option>
                            <option <?= $div == 'A' ? 'selected' : '' ?> value="A">A</option>
                            <option <?= $div == 'B' ? 'selected' : '' ?> value="B">B</option>
                            <option <?= $div == 'C' ? 'selected' : '' ?> value="C">C</option>

                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <label for=""><small>Click to View</small></label>
                    <button class="btn btn-sm btn-success pb-0 w-100" type="button" id="view_button">View</button>
                </div>
            </div>
            <style>
                .table thead tr th,
                .table tbody tr td {
                    font-size: 10px !important;
                    padding: 5px !important;
                }

                .table-bordered .fixed-column {
                    position: sticky;
                    left: -0.1px;
                    background-color: white;
                    z-index: 2;
                }
            </style>


            <!-- <div><?= $start ?></div>
            <div><?= $end ?></div> -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table  table-bordered">
                            <thead>
                                <tr style="text-align: center;">
                                    <td class="fixed-column" style="width:9%;"></td>
                                    <?php
                                    $x = $start;
                                    $y = $end;

                                    $startDateObj = new DateTime($start);
                                    $endDateObj = new DateTime($end);

                                    // Create an array to store the list of dates
                                    $dateList = array();

                                    // Use a loop to iterate through the dates and add them to the array
                                    for ($date = clone $startDateObj; $date <= $endDateObj; $date->modify('+1 day')) {
                                        $dateList[] = $date->format('Y-m-d');
                                    }
                                    ?>

                                    <?php foreach ($dateList as $date) :
                                    ?>
                                        <td style="padding: 0.2rem 0.2rem;font-size: 13px !important;">
                                            <?= date('l', strtotime($date)) ?> <br>
                                            <?= date('d M Y', strtotime($date)) ?>
                                        </td>

                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($subjects as $subj) :
                                ?>
                                    <tr>
                                        <td class="fixed-column" style="font-size: 13px !important;"><?= $subj['stvsubj_subject'] ?></td>
                                        <!-- <td style="font-size: 13px !important;"><?= $subj['stvsubj_subject'] ?></td> -->
                                        <?php
                                        foreach ($dateList as $date) :
                                            $current_lessions = [];
                                            foreach ($syllabus as $syl) {
                                                if ($date == $syl['teaching_date'] && $syl['subject'] == $subj['stvsubj_id']) {
                                                    $current_lessions[] = $syl;
                                                }
                                            }
                                        ?>
                                            <td style="padding: 0.2rem 0.2rem;">
                                                <ul style="margin-top: 0;margin-bottom: 0rem;">
                                                    <?php
                                                    foreach ($current_lessions as $crls) :
                                                    ?>
                                                        <li style="font-size: 13px !important;"><?= "$crls[title] : $crls[status]" ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </td>

                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>




    <!-- Required Js -->

    <?php include 'application/views/jsPages.php'; ?>


    <script>

        $("#view_button").click(function() {
            let std = $("#std").val();
            var div = document.getElementById("div").value;
            let base_url = '<?= base_url() ?>';
            window.location.href = `${base_url}Dashboard/syllabus_calendar?std=${std}&div=${div}`;

           
        });
    </script>

</body>

</html>
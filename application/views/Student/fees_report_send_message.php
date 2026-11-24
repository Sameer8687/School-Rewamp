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

    <!-- Boxicons CSS -->

    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>



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

            <div class="pcoded-content">

                <!-- [ breadcrumb ] start -->

               

                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->

                <div class="row mobile-wali-row" id="alertsRow">

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

                <div class="row" style="margin-top: -50px;">

                    <div class="col-md-12">

                        <div class="card">

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="form-group row">

                                            <label for="" class="col-md-2 text-left">Message Template

                                                :</label>

                                            <select class="form-control form-control-sm col-md-5" name="template_name" id="template_name" required>

                                                <option value="">Select Template</option>

                                                <?php

                                                foreach ($message_templates as $message_template) {

                                                    if (strlen($message_template['stvmsgt_text']) > 50) {

                                                        $str = substr($message_template['stvmsgt_text'], 0, 50) . " ...";

                                                    } else {

                                                        $str = $message_template['stvmsgt_text'];

                                                    }



                                                ?>

                                                    <option value="<?php echo $message_template['stvmsgt_id']; ?>">

                                                        <?php echo $str; ?></option>

                                                <?php } ?>

                                            </select>

                                            <div class="col-md-5 text-right">

                                                <a href="<?= base_url('MsgTemplate/index'); ?>">Add New Message Template</a>

                                            </div>

                                        </div>

                                        <div class="form-group row" style="margin-top: -15px;">

                                            <label for="" class="col-md-2 text-left"></label>

                                            <textarea name="message_template" id="message_template" cols="30" rows="3" class="form-control form-control-sm col-md-10"></textarea>

                                            <input type="hidden" name="total_rows" id="total_rows" value="<?= count($feesDetails); ?>">

                                        </div>



                                        <hr>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="dt-responsive table-responsive">

                                            <table id="simpletable" class="table table-striped table-bordered nowrap">

                                                <thead>

                                                    <tr>

                                                        <th>Sr.No.</th>

                                                        <th>ID</th>

                                                        <th>Name</th>

                                                        <th>Std</th>

                                                        <th style="display: none;">Class</th>

                                                        <th>Division</th>

                                                        <th>Total Fees Paid</th>

                                                        <th>Balance</th>

                                                        <th>Due Date</th>

                                                        <th>Send Message</th>



                                                    </tr>

                                                </thead>

                                                <style>

                                                    #simpletable tbody tr td {

                                                        padding: 5px;

                                                        font-size: 12px;

                                                        font-weight: 500;

                                                    }

                                                </style>

                                                <tbody>



                                                    <?php $i = 1;

                                                    $flag = 0;

                                                    foreach ($feesDetails as $fees) {

                                                        //Convert and Display dates ----------------------

                                                        $servDate = $fees['due_date'];

                                                        $timestamp = strtotime($servDate);

                                                        $due_date = date("d M Y", $timestamp);



                                                        //Check if Due date gone change color

                                                        $expire_due_date = $fees['due_date'];

                                                        $expire = strtotime($expire_due_date);

                                                        $today = strtotime("today midnight");



                                                        //Get Next 2 Days

                                                        $previous_two_days_in_seconds = "s";

                                                        $previous_two_days = date("d M Y", strtotime(date('Y-m-d', strtotime($fees['due_date'] . ' -2 days'))));

                                                        $date = $fees['due_date'];

                                                        $two_days_ago = date('Y-m-d', strtotime($date . ' - 2 days'));



														if ($fees['STVCLAS_standard'] == "I"){

															$classStd =  "First";

														}else if ($fees['STVCLAS_standard'] == "II"){

															$classStd =  "Second";

														}else if ($fees['STVCLAS_standard'] == "III"){

															$classStd =  "Third";

														}else if ($fees['STVCLAS_standard'] == "IV"){

															$classStd =  "Fourth";

														}else if ($fees['STVCLAS_standard'] == "V"){

															$classStd =  "Fifth";

														}else if ($fees['STVCLAS_standard'] == "VI"){

															$classStd =  "Sixth";

														}else if ($fees['STVCLAS_standard'] == "VII"){

															$classStd =  "Seventh";

														}else if ($fees['STVCLAS_standard'] == "VIII"){

															$classStd =  "Eighth";

														}else if ($fees['STVCLAS_standard'] == "IX"){

															$classStd =  "Ninth";

														}else if ($fees['STVCLAS_standard'] == "Sr.Kg."){

															$classStd =  "Sr.Kg.";

														}else if ($fees['STVCLAS_standard'] == "Jr.Kg."){

															$classStd =  "Jr.Kg.";

														}else if ($fees['STVCLAS_standard'] == "Nursery"){

															$classStd =  "Nursery";

														}else {

															$classStd = "";

														}

                                                    ?>

                                                        <tr>

                                                            <td><?php echo $i; ?></td>

                                                            <td><?php echo $fees['SPRIDEN_student_id']; ?></td>

                                                            <td><?php echo $fees['name']; ?></td>

                                                            <td><?php echo $fees['STVCLAS_standard']; ?></td>

                                                            <td style="display: none;"><?php echo $classStd; ?></td>

                                                            <td><?php echo $fees['STVCLAS_class']; ?></td>

                                                            <td><?php echo $fees['total_paid']; ?></td>

                                                            <?php

                                                            if ($fees['balance'] > 0 && $flag != 1) {

                                                                $td_class = "bg-dansger texst-white";

                                                            } else {

                                                                $td_class = '';

                                                            }

                                                            ?>

                                                            <td class="<?= $td_class; ?>">

                                                                <?php echo $fees['balance']; ?></td>

                                                            <td><?php echo $due_date; ?></td>

                                                            <td style="cursor: pointer;" class="view-record text-center" data-id="<?php echo $fees['id']; ?>">

                                                                <?php if ($fees['Whatsapp'] != null) { ?>

                                                                    <a target="_blank" data-number="<?= $fees['Whatsapp']; ?>" class="" id="send-link<?= $i; ?>" href=""> <i class='bx bxl-whatsapp'></i> Send</a>



                                                                <?php } ?>

                                                            </td>

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

        <!-- [ Main Content ] end -->

    </div>







    </div>

    <!-- [ Main Content ] end -->

    </div>











    <!---Include JS Files-->

    <?php include 'application/views/jsPages.php'; ?>





    <script>

        $('#template_name').change(function() {

            var ids = $('#template_name').val();

            $.ajax({

                url: "<?= base_url(); ?>FeesAlert/message_template?id=" + ids,

                method: "GET",

                dataType: "JSON",

                success: function(data) {

                    $('#message_template').val(data.stvmsgt_text);

                    assign_message();

                }

            })

        });



        $('#message_template').blur(function() {

            assign_message();

        });



        function assign_message() {

            var msg_str = $('#message_template').val();

            var total_links = $('#total_rows').val();



            for (var count = 1; count <= total_links; count++) {

                var ids = "#send-link" + count;

                var href_val = $(ids).attr("href");

                if (href_val != undefined) {

                    var num = $(ids).attr("data-number");

                    var url = "https://wa.me/" + num + "?text=" + msg_str;

                    $(ids).attr("href", url);

                }





            }

        }

    </script>

</body>



</html>


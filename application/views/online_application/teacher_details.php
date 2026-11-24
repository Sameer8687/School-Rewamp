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

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/id-card.css">

    <style>

        .cursor-pointer{
            cursor: pointer;
        }
        @media print {

            body * {

                visibility: hidden;

            }



            .print-container {

                margin-top: -200px;

            }



            .print-container,

            .print-container * {

                visibility: visible;

            }

        }

        .modal-body {
        display: flex;
        flex-wrap: nowrap;
    }


    .right_modal {
        position: relative;
    }

    .right_modal img {
        position: absolute;
        top: 0px;
        right: 10px;
        max-width: 100%;
        max-height: 85% !important;
        border-radius: 5px;
        padding: 5px;
        border: 2px solid black;
    }

    .right_modal div {
        position: absolute;
        top: 231px;
        right: 10px;
    }

    .right_modal {
        display: flex;
        justify-content: center;
        align-items: center;
    }


    .modal-body {
        border: 2px solid;
        margin: 2px;
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
<div class="row mb-2"><div class="col-md-4">
<a href="<?=base_url('Online_Application/list')?>" class="mx-2">Back to List <i class="fa fa-arrow-right"></i></a>
</div></div>
    <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 left_modal">
                            <!-- User information goes here -->
                            <section class="mb-4">
                                <div class="row mb-3 ml-0">
                                    <div class="text-start">
                                    <h5>Personal Details</h5>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Applicant Name</strong></p>
                                    </div>
                                    <div class="col-md-1">
                                        <strong>:</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $user_details['header']['full_name'] ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>DOB</strong> </p>
                                    </div>
                                    <div class="col-md-1">
                                        <strong>:</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $user_details['header']['dob'] ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Phone</strong> </p>
                                    </div>
                                    <div class="col-md-1">
                                        <strong>:</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $user_details['header']['phone'] ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>WhatsApp No</strong> </p>
                                    </div>
                                    <div class="col-md-1">
                                        <strong>:</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $user_details['header']['alt_phone'] ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Email</strong> </p>
                                    </div>
                                    <div class="col-md-1">
                                        <strong>:</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $user_details['header']['email'] ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Address</strong></p>
                                    </div>
                                    <div class="col-md-1">
                                        <strong>:</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $user_details['header']['address'] ?>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="col-md-6 right_modal">
                            <!-- Placeholder for user photo -->
                            <img src="https://oliveschool.org.in/photos/<?= $user_details['header']['photo'] ?>" alt="User Photo">
                            <div class="mt-3">
                                <p><strong>Resume:</strong> <a href="https://oliveschool.org.in/resumes/<?= $user_details['header']['resume'] ?>" target="_blank"> Click to View</a></p>
                            </div>
                        </div>
                        <div class="col-md-12 mt-5">
                            <!-- Education, Experience, etc., can be added here -->
                            <section class="mb-4">
                                <div class="row mb-3 ml-1">
                                    <h5>Education Qualification</h5>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Degree</th>
                                            <th>Year</th>
                                            <th>Percentage</th>
                                            <th>Board/University</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $index = 0;
                                        foreach ($user_details['lines'] as $line) : ?>
                                            <?php if ($line['type'] == 'education') : ?>
                                                <tr>
                                                    <td><?= ++$index ?></td>
                                                    <td><?= $line['attrbute_1'] ?></td>
                                                    <td><?= $line['attrbute_2'] ?></td>
                                                    <td><?= $line['attrbute_3'] ?></td>
                                                    <td><?= $line['attrbute_4'] ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </section>
                            <section class="mb-4">
                                <div class="row mb-3 ml-1">
                                    <h5>Certifications</h5>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Course/Compture Knowledge</th>
                                            <th>Year Of Completion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $index = 0;
                                        foreach ($user_details['lines'] as $line) : ?>
                                            <?php if ($line['type'] == 'certification') : ?>
                                                <tr>
                                                    <td><?= ++$index ?></td>
                                                    <td><?= $line['attrbute_1'] ?></td>
                                                    <td><?= $line['attrbute_2'] ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </section>
                            <section class="mb-4">
                                <div class="row mb-3 ml-1">
                                    <h5>Experience</h5>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>School/College</th>
                                            <th>Spacial Subject</th>
                                            <th>Experience Summary</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $index = 0;
                                        foreach ($user_details['lines'] as $line) : ?>
                                            <?php if ($line['type'] == 'experience') : ?>
                                                <tr>
                                                    <td><?= ++$index ?></td>
                                                    <td><?= $line['attrbute_1'] ?></td>
                                                    <td><?= $line['attrbute_2'] ?></td>
                                                    <td><?= $line['attrbute_4'] ?></td>
                                                    <td><?= $line['attribute5'] ?></td>
                                                    <td><?= $line['attrbute_3'] ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </section>
                            <section class="mb-4">
                                <div class="row mb-3 ml-1">
                                    <h5>Documents</h5>
                                    <div class="col-md-12">
                                        <ul class="mt-3">
                                            <li class="mx-1 mb-2 w-10" style="padding: 5px 28px;border-radius: 20px;border:outset;">
                                                <a href="https://oliveschool.org.in/Documents/degree/<?= $user_details['header']['degree'] ?>" target="_blank">Degree</a>
                                            </li>
                                            <li class="mx-1 mb-2 w-10" style="padding: 5px 28px;border-radius: 20px;border:outset;">
                                                <a href="https://oliveschool.org.in/Documents/experience/<?= $user_details['header']['experience'] ?>" target="_blank">Experience</a>
                                            </li>
                                            <li class="mx-1 mb-2 w-10" style="padding: 5px 28px;border-radius: 20px;border:outset;">
                                                <a href="https://oliveschool.org.in/Documents/marksheet/<?= $user_details['header']['marksheet'] ?>" target="_blank">Marksheet</a>
                                            </li>
                                            <li class="mx-1 mb-2 w-10" style="padding: 5px 28px;border-radius: 20px;border:outset;">
                                                <a href="https://oliveschool.org.in/Documents/cer_files/<?= $user_details['header']['certificate'] ?>" target="_blank">Certificate</a>
                                            </li>
                                            <?php
                                            foreach ($user_details['lines'] as $line) : ?>
                                                <?php if ($line['type'] == 'other_doc') : ?>
                                                    <li class="mx-1 mb-2 w-10" style="padding: 5px 28px;border-radius: 20px;border:outset;">
                                                        <a href="https://oliveschool.org.in/Documents/others/<?= $line['attrbute_2'] ?>" target="_blank"><?= $line['attrbute_1'] ?></a>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </section>
                            <section class="mb-4">
                                <div class="row mb-3 ml-1">
                                    <h5>Hobbies</h5>
                                    <div class="col-md-12">
                                        <ul class="row mt-3">
                                            <?php $index = 0;
                                            foreach ($user_details['lines'] as $line) : ?>
                                                <?php if ($line['type'] == 'hobbie') : ?>
                                                    <li class="mx-1 mb-2" style="padding: 5px 28px;border-radius: 20px;border:outset;"><?= $line['attrbute_1'] ?></li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </section>
                            <section class="mb-4">
                                <div class="row mb-3 ml-1">
                                    <h5>About You</h5>
                                </div>
                                <p><?= $user_details['header']['about_u'] ?></p>

                            </section>
                        </div>
                    </div>
                </div>
        
        <!-- [ Main Content ] end -->
    </div>
    </div>
    <!-- [ Main Content ] end -->
    </div>
    </div>
    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
   



</body>



</html>
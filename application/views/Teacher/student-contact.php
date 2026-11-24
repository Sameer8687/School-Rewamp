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

    <style>
        .table thead tr th,
        .table tbody tr td {
            padding: 5px !important;
            font-size: 10px !important;
        }

        .table tbody tr td.no-padding {
            padding: 0px !important;
        }

        .table tbody tr td input {
            font-size: 10px !important;
        }
    </style>

    <!-- [ Main Content ] start -->

    <div class="pc-container">

        <div class="pcoded-content">
            <?php $msg = $this->session->userdata('msg');
            if (isset($msg)) :
            ?>
                <div class="alert alert-<?= $msg['class'] ?>"><?= $msg['msg'] ?></div>
            <?php endif; ?>
            <form action="<?= base_url() ?>dashboard/save_contacts" method="post">
                <button class="btn btn-sm btn-success mb-2">Save Contact Information</button>
                <input type="hidden" name="max_rows" id="max_rows" value="<?= count($all_students) ?>">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Whatsapp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($all_students as $student) : ?>
                            <tr>
                                <td><?= $student['STVCLAS_rollno'] ?></td>
                                <td><?= "$student[SPRIDEN_surname] $student[SPRIDEN_student_name] $student[SPRIDEN_father_name]" ?></td>
                                <td class="no-padding">
                                    <input type="text" class="form-control form-control-sm" name="mobile<?= $i ?>" id="mobile<?= $i ?>" value="<?= $student['mobile'] ?>">
                                    <input type="hidden" name="id<?= $i ?>" id="id<?= $i ?>" value="<?= $student['id'] ?>">
                                </td>
                                <td class="no-padding">
                                    <input type="text" class="form-control form-control-sm" name="whatsapp<?= $i ?>" id="whatsapp<?= $i ?>" value="<?= $student['whatsapp'] ?>">
                                </td>
                            </tr>
                        <?php $i++;
                        endforeach; ?>
                    </tbody>
                </table>
            </form>
        </div>

    </div>


    <!-- Required Js -->

    <?php include 'application/views/jsPages.php'; ?>
</body>



</html>
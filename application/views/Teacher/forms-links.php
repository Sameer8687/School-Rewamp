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
<style>
    .hover-card {
        transition: all 0.3s ease;
        border-radius: 10px;
    }

    .hover-card:hover {
        background-color: #f0f9fc;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
    }

    .btn-outline-info {
        border-color: #17a2b8;
        color: #17a2b8;
    }

    .btn-outline-info:hover {
        background-color: #17a2b8;
        color: white;
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
    <?php include 'sidebar.php'; ?>
    <!-- [ Header ] start -->
    <?php include 'topNav.php'; ?>
    <!-- [ Header ] end -->
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pcoded-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header text-center"
                            style="background-color: #e6f4f9; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                            <h5 class="card-title mb-0 fw-semibold text-primary">
                                <i class="bi bi-clipboard-check me-2"></i> Assigned Forms
                            </h5>
                        </div>
                        <div class="card-body p-4 bg-white rounded-bottom">
                            <ul class="list-group list-group-flush">
                                <?php foreach ($forms as $form): ?>
                                    <li
                                        class="list-group-item border-0 px-3 py-3 d-flex justify-content-between align-items-center hover-card">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0 fw-semibold text-dark"><?= $form['name'] ?></h6>
                                                <small class="text-muted">Click to open form</small>
                                            </div>
                                        </div>
                                        <a href="<?= base_url() . $form['link'] ?>"
                                            class="btn btn-outline-info btn-sm rounded-pill px-3">
                                            Open <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Required Js -->
    <?php include 'application/views/jsPages.php'; ?>
</body>
</html>
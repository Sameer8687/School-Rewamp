<?php include('application/views/pages/includes/navbar.php') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/new/template.css'); ?>">
<div class="container-fluid no-print">
    <div class="row background p-3">
        <div class="col-md-8 col-sm-8 col-lg-8 ms-1 noto-sans-jp-font">
            <h2 class="noto-sans-jp-font">Hello, <?= $user['spruser_name'] ?></h2>
            <p>Welcome to the <?= isset($page_title) ? $page_title : '' ?>, your one-stop page for academics, personal information and more.</p>
            <div class="wrap">
                <div class="search">
                    <input type="text" class="searchTerm getDataOfStudent" placeholder=" Please Type Name of Student" id="name" name="name" list="stud_name">
                    <datalist id="stud_name">
                        <?php foreach ($records as $rec) : ?>
                            <option style="color: #0c0cef;" data-id="<?= $rec['id'] ?>" value="<?= $rec['name'] ?>"><?= $rec['std'] . ' - ' . $rec['class'] ?></option>
                        <?php endforeach; ?>
                    </datalist>
                    <button type="submit" class="searchButton">
                        <i class="fa fa-search"></i>
                    </button>

                </div>
                <div class="mt-3 d-flex">
                    <p class="">Quick Actions</p>
                    <button class="btn btn-mini text-white ms-3">View Fees Report</button>
                    <button class="btn btn-mini text-white ms-3">View Fees Report</button>
                    <button class="btn btn-mini text-white ms-3">View Fees Report</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-lg-4"></div>
    </div>
    <div class="row">
        <div class="col-md-11"></div>
        <div class="col-md-1 text-end">
            <i id="toggleButton" class="bi bi-menu-button-wide-fill"></i>
        </div>
    </div>
</div>
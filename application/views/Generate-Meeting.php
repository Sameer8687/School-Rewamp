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
        <form action="<?php echo base_url(); ?>welcome/meeting_add" method="post">
            <div class="pcoded-content">
                <!-- [ Main Content ] start -->
                <div class="row" id="alertRow">
                    <div class="col-md-12">
                        <?php
                        $message = $this->session->userdata('message');
                        $alert_class = $this->session->userdata('alert_class');
                        if (!empty($message)) { ?>
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
                                        <h5 class="pt-2">Generate Parents Meeting</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-secondary btn-sm float-right" type="submit">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span id="dynamicLabelAddButton">Add Meeting</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body mb-0 pb-0">
                                <!-- Meeting Form -->
                                <div class="row g-3 mb-2">
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="row align-items-center">
                                            <label for="m_date" class="col-lg-5 col-md-4 col-form-label text-md-end">
                                                Meeting Date<span class="text-danger">*</span>:
                                            </label>
                                            <div class="col-lg-7 col-md-8">
                                                <input type="date" class="form-control form-control-sm" id="m_date"
                                                    name="m_date" required>
                                                <input type="hidden" id="school_id" name="event_id">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="row align-items-center">
                                            <label for="m_time" class="col-lg-5 col-md-4 col-form-label text-md-end">
                                                Meeting Time<span class="text-danger">*</span>:
                                            </label>
                                            <div class="col-lg-7 col-md-8">
                                                <input type="time" class="form-control form-control-sm" id="m_time"
                                                    name="m_time" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="row align-items-center">
                                            <label for="title" class="col-lg-5 col-md-4 col-form-label text-md-end">
                                                Title<span class="text-danger">*</span>:
                                            </label>
                                            <div class="col-lg-7 col-md-8">
                                                <input type="text" class="form-control form-control-sm" id="title"
                                                    name="title" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Agenda, Class, and Year -->
                                <div class="row g-3 mb-2">
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="row align-items-center">
                                            <label for="agenda" class="col-lg-5 col-md-4 col-form-label text-md-end">
                                                Agenda<span class="text-danger">*</span>:
                                            </label>
                                            <div class="col-lg-7 col-md-8">
                                                <input type="text" class="form-control form-control-sm" name="agenda"
                                                    id="agenda">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="row align-items-center">
                                            <label for="m_class" class="col-lg-5 col-md-4 col-form-label text-md-end">
                                                Class<span class="text-danger">*</span>:
                                            </label>
                                            <div class="col-lg-7 col-md-8">
                                                <select class="form-control form-control-sm" id="m_class"
                                                    name="m_class">
                                                    <option value="0">Select Class</option>
                                                    <?php foreach ($classes as $clas) { ?>
                                                        <option value="<?= $clas['stvfees_class'] ?>">
                                                            <?= $clas['stvfees_class'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="row align-items-center">
                                            <label for="acdm_year" class="col-lg-5 col-md-4 col-form-label text-md-end">
                                                Academic Year<span class="text-danger">*</span>:
                                            </label>
                                            <div class="col-lg-7 col-md-8">
                                                <select class="form-control form-control-sm" id="acdm_year"
                                                    name="acdm_year">
                                                    <option value="">Select Year</option>
                                                    <?php foreach ($academic_years as $acdm) { ?>
                                                        <option value="<?= $acdm['stvacdm_id'] ?>"
                                                            <?= ($acdm['stvacdm_id'] == $selected_academic_year) ? 'selected' : '' ?>>
                                                            <?= $acdm['stvacdm_year'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Meeting List -->
                <div class="row margin-top-minus-20">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Meetings</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive margin-top-minus-10">
                                    <table id="simpletable" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>Sr.No.</th>
                                                <th>Class</th>
                                                <th>Title</th>
                                                <th>Agenda</th>
                                                <th>Time</th>
                                                <th>Date</th>
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
                                            <?php $i = 1;
                                            foreach ($all_meetings as $meeting) { ?>
                                                <tr data-name="<?= $meeting['prameet_id']; ?>" class="rowToUpdateData">
                                                    <td><?= $i++ ?></td>
                                                    <td><?= $meeting['prameet_class'] ?></td>
                                                    <td><?= $meeting['prameet_vanue'] ?></td>
                                                    <td><?= $meeting['prameet_agenda'] ?></td>
                                                    <td><?= $formatted_time = date("h:i", strtotime($meeting['prameet_time'])) ?></td>
                                                    <td><?= $formatted_date = date("d-M-Y", strtotime($meeting['prameet_date'])) ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php include 'jsPages.php'; ?>
    <script>
        $('.rowToUpdateData').on('click', function() {
            var rowID = $(this).data('name');
            $.ajax({
                url: "<?= base_url(); ?>welcome/getmeetingByID",
                method: "POST",
                dataType: "json",
                data: {
                    id: rowID
                },
                success: function(data) {
                    if (data !== "fail") {
                        $('#m_date').val(data.prameet_date);
                        $('#school_id').val(rowID);
                        $('#m_time').val(data.prameet_time);
                        $('#title').val(data.prameet_vanue);
                        $('#agenda').val(data.prameet_agenda);
                        $('#m_class').val(data.prameet_class);
                        $('#acdm_year').val(data.prameet_academic_year);
                        $('#dynamicLabelAddButton').text("Update Data");
                    }
                },
                error: function() {
                    alert('Error fetching meeting data.');
                }
            });
        });
    </script>
</body>

</html>
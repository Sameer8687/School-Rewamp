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

                <div class="col-3">
                    <div class="form-group">
                        <label for="">Std :</label>
                        <select class="form-control form-control-sm" name="std" id="std">
                            <option value="">Select</option>
                            <?php foreach ($classes as $classs): ?>
                                <option <?= $class == $classs['stvfees_class'] ? 'selected' : ''; ?> value="<?= $classs['stvfees_class']; ?>"><?= $classs['stvfees_class']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-group">
                        <label for="">Div :</label>
                        <select class="form-control form-control-sm" name="div" id="div">
                            <option value="">Select</option>
                            <option value="A" <?= $div == 'A' ? 'selected' : ''; ?>>A</option>
                            <option value="B" <?= $div == 'B' ? 'selected' : ''; ?>>B</option>
                            <option value="C" <?= $div == 'C' ? 'selected' : ''; ?>>C</option>
                        </select>
                    </div>
                    <!-- <input type="hidden" name="link_type" id="link_type" value="<?= $type ?>"> -->
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Meet For :</label>
                        <select class="form-control form-control-sm" name="meet" id="meet">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>
                <div class="col-3">
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
            </style>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr style="text-align: center;">

                                    <td style="font-weight: bold; text-align: left;width:auto;">Name</td>

                                    <td style="font-weight: bold; text-align: left;">Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $A = 1;
                                $total_students = count($students);
                                for ($i = 0; $i < $total_students; $i++) {
                                    $next = $i + 1;
                                    ?>
                                    <tr>
                                    <td><?= $A++?></td>
                                        <td data-toggle="modal" class="addFeedbackByClick">

                                            <?php echo $students[$i]['SPRIDEN_surname'] . " " . $students[$i]['SPRIDEN_student_name'] . " " . $students[$i]['SPRIDEN_father_name'] ?>

                                        </td>

                                        <td>
                                        <?php
                                        $message = "Dear Parent, you are invited to the upcoming parents' meeting at our school. Please join us to discuss your child's progress and activities. We look forward to seeing you!";
                                        ?>
                                        <a target="_blank"
                                            href="https://api.whatsapp.com/send?text=<?= urlencode($message) ?>&phone=+91<?= $students[$i]['stvpcnf_whatsapp'] ?>">
                                            <i class='bx bxl-whatsapp'></i> Share Link
                                        </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
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
        var view_button = document.getElementById("view_button");
        let base_url = '<?= base_url() ?>';
        view_button.addEventListener("click", function () {
            var meet = document.getElementById("meet").value;
            var std = document.getElementById("std").value;
            var div = document.getElementById("div").value;
            let url = `${base_url}Dashboard/parents_meeting?meet=${meet}&std=${std}&div=${div}`;
            window.location = url;
        });
    </script>

    <script>
        document.getElementById('std').addEventListener('change', function () {
            const selectedClass = this.value;
            const meetDropdown = document.getElementById('meet');
            meetDropdown.innerHTML = '<option value="">Select</option>';
            if (selectedClass) {
                fetch('<?= base_url("Dashboard/fetchMeetings") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ std: selectedClass }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            data.meetings.forEach(meet => {
                                const option = document.createElement('option');
                                option.value = meet.prameet_vanue;
                                option.textContent = meet.prameet_vanue;
                                meetDropdown.appendChild(option);
                            });
                        } else {
                            alert('No meetings found for the selected class.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>
</body>

</html>
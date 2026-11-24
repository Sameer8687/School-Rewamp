<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Issue LC</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Phoenixcoded" />
    <!---Include CSS Files-->
    <?php include 'application/views/cssPages.php'; ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/id-card.css" />
    <style>
        .pc-container {
            top: 0px !important;
        }

        .border {
            border: 1px solid;
        }

        .table tr td,
        .table tr th {
            padding: 5px !important;
            font-size: 11px !important;
            cursor: pointer;
        }
    </style>
</head>

<body class="">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <?php include 'application/views/sidebar.php'; ?> <?php include
            'application/views/topNav.php'; ?>
    <div class="pc-container">
        <div class="pcoded-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Login History</h5>
                        </div>
                        <div class="card-body">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">Class
                                                    <span class="float-right">:</span></label>

                                                <select name="user_names" id="user_names"
                                                    class="form-control form-control-sm col-md-7">
                                                    <option selected disabled value="">Select User</option>
                                                    <?php foreach ($allusers as $username) { ?>
                                                        <option value="<?= $username['spruser_id']; ?>">
                                                            <?= $username['spruser_name'] . " - " . $username['spruser_usertype']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">Date
                                                    <span class="float-right">:</span></label>

                                                <input type="date" class="form-control form-control-sm col-md-7"
                                                    name="finddate" id="finddate">
                                            </div>
                                        </div>
                                        <div class="col-md-4">

                                            <button class="btn btn-sm btn-outline-secondary" id="findButton"
                                                type="button" onclick="findLoginData()">View Data</button>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="simpletable" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>User Type</th>
                                                    <th>Activity Log</th>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody">
                                                <?php $i = 1;
                                                foreach ($login_data as $login) {
                                                    $servDate = $login['stvlgin_date'];
                                                    $timestamp = strtotime($servDate);
                                                    $newDate = date("d M Y", $timestamp);
                                                    $login_time = date('g:i:s A', strtotime($login['stvlgin_time']));
                                                    if ($login['stvlgin_status'] == "Logged In") {
                                                        $color_name = "text-success";
                                                    } else {
                                                        $color_name = "text-danger";
                                                    }

                                                    $message = 'Has been <span class="' . $color_name . '">' . $login['stvlgin_status'] . '</span> at ' . $newDate . ' ' . $login_time; ?>

                                                    <tr>
                                                        <td><?= $i; ?></td>
                                                        <td><?= $login['spruser_name']; ?></td>
                                                        <td><?= $login['spruser_usertype']; ?></td>
                                                        <td><?= $message; ?></td>
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
    </div>

    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>
    <script>
        function refreshPage() {
            window.location.reload();
        }

        function findLoginData() {
            var username = $('#user_names option:selected').val();
            var dateOfLogin = $('#finddate').val();
            var html = '';
            if (username == "" && dateOfLogin == "") {
                alert("Select Username or Date");
                return false;
            } else if (username != "" && dateOfLogin == "") {
                //If Only User Selected
                $.ajax({
                    url: "<?= base_url(); ?>index.php/MasterForm/findLoginDataByUsername",
                    data: {
                        username: username
                    },
                    dataType: "JSON",
                    method: "POST",
                    success: function (data) {
                        if (data != "fail") {
                            if (data != "") {
                                for (var i = 0; i < data.length; i++) {
                                    var newCount = i + 1;
                                    html += '<tr>';
                                    html += '<td>' + newCount + '</td>';
                                    html += '<td>' + data[i].name + '</td>';
                                    html += '<td>' + data[i].userType + '</td>';
                                    html += '<td>' + data[i].message + '</td>';
                                    html += '</tr>';
                                }
                            } else {
                                html += '<tr>';
                                html += '<td colspan="4" class="text-center">No data available in table</td>';
                                html += '</tr>';
                            }
                            $('#tbody').html(html);
                        }
                    }
                });
            } else if (username == "" && dateOfLogin != "") {
                //If Only Date Selected
                $.ajax({
                    url: "<?= base_url(); ?>index.php/MasterForm/findLoginDataByDate",
                    data: {
                        dateOfLogin: dateOfLogin
                    },
                    dataType: "JSON",
                    method: "POST",
                    success: function (data) {
                        if (data != "fail") {
                            if (data != "") {
                                for (var i = 0; i < data.length; i++) {
                                    var newCount = i + 1;
                                    html += '<tr>';
                                    html += '<td>' + newCount + '</td>';
                                    html += '<td>' + data[i].name + '</td>';
                                    html += '<td>' + data[i].userType + '</td>';
                                    html += '<td>' + data[i].message + '</td>';
                                    html += '</tr>';
                                }
                            } else {
                                html += '<tr>';
                                html += '<td colspan="4" class="text-center">No data available in table</td>';
                                html += '</tr>';
                            }
                            $('#tbody').html(html);
                        }
                    }
                });
            } else {
                //If Both Values
                $.ajax({
                    url: "<?= base_url(); ?>index.php/MasterForm/findLoginDataByBoth",
                    data: {
                        username: username,
                        dateOfLogin: dateOfLogin
                    },
                    dataType: "JSON",
                    method: "POST",
                    success: function (data) {
                        if (data != "fail") {
                            if (data != "") {
                                for (var i = 0; i < data.length; i++) {
                                    var newCount = i + 1;
                                    html += '<tr>';
                                    html += '<td>' + newCount + '</td>';
                                    html += '<td>' + data[i].name + '</td>';
                                    html += '<td>' + data[i].userType + '</td>';
                                    html += '<td>' + data[i].message + '</td>';
                                    html += '</tr>';
                                }
                            } else {
                                html += '<tr>';
                                html += '<td colspan="4" class="text-center">No data available in table</td>';
                                html += '</tr>';
                            }
                            $('#tbody').html(html);
                        }
                    }
                });
            }
        }
    </script>
</body>

</html>
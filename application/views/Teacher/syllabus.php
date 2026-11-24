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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <!-- <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'> -->

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
        .btn-info {
            background-color: #26a69a !important;
        }
    </style>
    <style>
        table tr .hello {
            padding: 0.4rem 0.6rem;
        }
    </style>
    <!-- [ Main Content ] start -->
    <form id="FormSyllabus">
        <div class="pc-container">

            <div class="row" style=" margin-right: 0px !important; margin-left: 0px !important;">

                <div class="col-md-12">

                    <div class="card" style=" margin-bottom: 5px;">

                        <div class="card-header" style=" background-color: #26a69a;color:#fff;">

                            <h6><i class='bx bx-book-open'></i> Add Syllabus</h6>

                        </div>

                        <div class="card-body pb-1 pt-2">

                            <div class="row">

                                <div class="col-md-12">

                                    <form action="" id="lesson-form">

                                        <table class="table table-sm table-borderless">

                                            <tr>

                                                <td id="hello" style="width: 30%;border-top: none;">Standard <span style="float:right">:</span> </td>

                                                <td id="hello" style="width: 70%;border-top: none;">

                                                    <select class="form-control form-control-sm" name="std" id="std" onchange="GetSub(this)">
                                                        <option value="">Select</option>
                                                        <?php foreach ($classes as $classs) {  ?>
                                                            <option <?= $classs['stvfees_class'] == $class ? 'selected' : '' ?> value="<?php echo $classs['stvfees_class']; ?>"><?php echo $classs['stvfees_class']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>

                                                    <input type="hidden" name="hidden_header_id" id="hidden_header_id" value="">

                                                </td>

                                            </tr>

                                            <tr>

                                                <td id="hello" style="width: 30%;border-top: none;">Division <span style="float:right">:</span> </td>

                                                <td id="hello" style="width: 70%;border-top: none;">

                                                    <select required name="division" class="form-control form-control-sm" id="division">

                                                        <option value="" selected disabled> Select Division</option>

                                                        <option value="A">A</option>

                                                        <option value="B">B</option>

                                                        <option value="C">C</option>

                                                    </select>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td id="hello" style="width: 30%;border-top: none;">Subject <span style="float:right">:</span> </td>

                                                <td id="hello" style="width: 70%;border-top: none;">


                                                    <select class="form-control form-control-sm" name="sub" id="sub">
                                                        <option value=""> Select </option>
                                                    </select>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td id="hello" style="width: 30%;border-top: none;">Unit <span style="float:right">:</span> </td>

                                                <td id="hello" style="width: 70%;border-top: none;">

                                                    <select class="form-control form-control-sm" name="unit" id="unit">
                                                        <option value="">Select</option>
                                                        <option value="1">Unit-1</option>
                                                        <option value="2">Unit-2</option>
                                                        <option value="3">Unit-3</option>
                                                        <option value="4">Unit-4</option>

                                                    </select>

                                                </td>

                                            </tr>


                                            <tr>

                                                <td id="hello" style="width: 30%;border-top: none;">Title <span style="float:right">:</span> </td>

                                                <td id="hello" style="width: 70%;border-top: none;">

                                                    <input class="form-control form-control-sm" type="text" name="title" id="title">

                                                </td>

                                            </tr>

                                            <tr>

                                                <td id="hello" style="width: 30%;border-top: none;">Teaching Date <span style="float:right">:</span> </td>

                                                <td id="hello" style="width: 70%;border-top: none;">

                                                    <input class="form-control form-control-sm" type="date" name="teaching_date" id="teaching_date">

                                                </td>

                                            </tr>


                                            <tr>

                                                <td id="hello" style="width: 30%;border-top: none;">Status <span style="float:right">:</span> </td>

                                                <td id="hello" style="width: 70%;border-top: none;">

                                                    <select class="form-control form-control-sm " name="status" id="status">
                                                        <option value="">Select</option>
                                                        <option value="Start">Start</option>
                                                        <option value="Inprocess">Inprocess</option>
                                                        <option value="Completed">Completed</option>
                                                    </select>

                                                </td>

                                            </tr>


                                            <tr>

                                                <td id="hello" style="width: 30%;border-top: none;">



                                                </td>

                                                <td id="hello" style="width: 70%;border-top: none; text-align: right;">

                                                    <button style="display: none;" class="btn btn-sm" type="button" onclick="window.location.reload()" id="refresh">New</button>

                                                    <button class="btn btn-sm btn-info" type="submit" id="view_button"><i class='bx bxs-save'></i> Add </button>

                                                </td>

                                            </tr>

                                        </table>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                    <style>
                        #lesson-table-body tr td.add-lesson-review {

                            cursor: pointer;

                        }

                        ::-webkit-scrollbar {
                            width: 3px;
                            height: 3px;
                        }

                        ::-webkit-scrollbar-thumb {
                            background-color: #26a69a;
                            border-radius: 20px;
                        }
                    </style>

                    <div class="card mt-2" style=" margin-bottom: 0px;">

                        <div class="card-body p-0 ">

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="table-responsive p-0">
                                        <div class="card" style="padding: 0px; height: 270px;overflow-y: auto;margin-bottom: 0px !important;">

                                            <style>
                                                table thead tr #hello {
                                                    background-color: #26a69a !important;
                                                    color: #fff;
                                                    padding: 0.4rem 0.6rem !important;
                                                    position: sticky;
                                                    top: 0;
                                                }

                                                table tbody tr #hello {

                                                    padding: 0.2rem 0.6rem !important;
                                                }
                                            </style>
                                            <table class="table table-bordered table-sm m-0" style=" margin-bottom: 0px;">

                                                <thead>

                                                    <tr>
                                                        <th id="hello">#</th>
                                                        <th id="hello">Title</th>
                                                        <th id="hello">Unit</th>
                                                        <th id="hello">Std</th>
                                                        <th id="hello">Subject</th>
                                                        <th id="hello">Div</th>
                                                        <th id="hello">Teaching_date</th>
                                                        <th id="hello">Status</th>
                                                        <th id="hello">Teacher_id</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    <?php $i = 1;
                                                    foreach ($all_sly as $sly) { ?>
                                                        <tr onclick="readTRData(this)" data-id="<?= $sly['id'] ?>">

                                                            <td id="hello"><?= $i ?></td>
                                                            <td id="hello"><?= $sly['title'] ?></td>
                                                            <td id="hello"><?= $sly['unit'] ?></td>
                                                            <td id="hello"><?= $sly['std'] ?></td>
                                                            <td id="hello"><?= $sly['subject'] ?></td>
                                                            <td id="hello"><?= $sly['div'] ?></td>
                                                            <td id="hello"><?= $sly['teaching_date'] ?></td>
                                                            <td id="hello"><?= $sly['status'] ?></td>
                                                            <td id="hello"><?= $sly['teacher_id'] ?></td>
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
    </form>

    <!-- Required Js -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

    <?php include 'application/views/jsPages.php'; ?>



    <script>
        let GetSub = (e) => {

            let std = e.value;

            $.ajax({
                url: "<?= base_url('index.php/dashboard/fetch_sub') ?>",
                method: "GET",
                data: {
                    std: std
                },
                dataType: "json",
                success: function(data) {

                    let str = `<option value =""> Select </option>`;

                    data.forEach(element => {
                        str += `<option value = "${element.stvsubj_id}" > ${element.stvsubj_subject} </option>`
                    });

                    $("#sub").html(str);
                }
            })

        }
    </script>
    <script>
        $("#FormSyllabus").on('submit', function(e) {

            e.preventDefault();

            $.ajax({
                url: "<?= base_url('index.php/dashboard/insert_syllabus') ?>",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(res) {
                    if (res['status'] == 1) {
                        alert(res['message'])
                        window.location.reload();

                    } else if (res['status'] == 0) {
                        alert(res['message'])
                    }
                }
            })
        })
    </script>




    <script>
        function readTRData(element) {
            let id = element.getAttribute('data-id');
            let childs = element.querySelectorAll("td");
            let title_name = childs[1].textContent;
            let unit_name = childs[2].textContent;
            let std_name = childs[3].textContent;
            let sub_name = childs[4].textContent;
            let div_name = childs[5].textContent;
            let teaching_name = childs[6].textContent;
            let status_name = childs[7].textContent;
            $.ajax({
                url: "<?= base_url('index.php/dashboard/fetch_sub') ?>",
                method: "GET",
                data: {
                    std: std_name
                },
                dataType: "json",
                success: function(data) {

                    let str = `<option value =""> Select </option>`;

                    data.forEach(element => {
                        let selected = element.stvsubj_subject == sub_name ? 'selected' : '';
                        str += `<option ${selected} value = "${element.stvsubj_id}" > ${element.stvsubj_subject} </option>`
                    });

                    $("#sub").html(str);
                }
            })
           
            document.getElementById("title").value = title_name;
            document.getElementById("unit").value = unit_name;
            document.getElementById("std").value = std_name;
            // document.getElementById("sub").value = sub_name;

            document.getElementById("division").value = div_name;
            document.getElementById("teaching_date").value = teaching_name;
            document.getElementById("status").value = status_name;
            document.getElementById("hidden_header_id").value = id;

        }
    </script>


</body>



</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    <link rel="stylesheet" href="<?= base_url('css/custom/class-result-report1.css') ?>">

    <style>
        /* Remove the navbar's default margin-bottom and rounded borders */
        .navbar {
            margin-bottom: 0;
            border-radius: 0;
            background-color: #53769c;
        }

        /* Add a gray background color and some padding to the footer */
        footer {
            background-color: #f2f2f2;
            padding: 25px;
        }

        .carousel-inner img {
            width: 100%;
            /* Set width to 100% */
            margin: auto;
            min-height: 200px;
        }

        /* Hide the carousel text when the screen is less than 600 pixels wide */
        @media (max-width: 600px) {
            .carousel-caption {
                display: none;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#" style="color:#fff;"></a>
            </div>

        </div>
    </nav>



    <div class="container text-center">



        <section class="1">
            <?php $balance = $total_fees['total_fees'] - $total_fees['total_fees_paid']; ?>
            <div class="row mt-3">
                <div class="col-lg-3 col-sm-3">
                    <div class="card qwe mb-2">
                        <div class="card-header p-3 pt-2">

                            Total Fees
                            <h4 class="mb-0"><?= $total_fees['total_fees'] ?></h4>

                        </div>

                        <hr class="dark horizontal my-0" />

                    </div>

                </div>
                <div class="col-lg-3 col-sm-3">
                    <div class="card qwe mb-2">
                        <div class="card-header p-3 pt-2 bg-transparent">

                            Paid Fees
                            <h4 class="mb-0"><?= $total_fees['total_fees_paid'] ?></h4>

                        </div>

                        <hr class="horizontal my-0 dark" />

                    </div>


                </div>
                <div class="col-lg-3 col-sm-3">

                    <div class="card qwe mb-2">
                        <div class="card-header p-3 pt-2">

                            Balanced
                            <h4 class="mb-0"><?= $balance ?></h4>

                        </div>

                        <hr class="dark horizontal my-0" />

                    </div>

                </div>



                <div class="col-lg-3 col-sm-3">

                    <div class="card qwe">
                        <div class="card-header p-3 pt-2 bg-transparent">
                            Exemption

                            <h4 class="mb-0"><?= $total_fees['total_fees_exemption'] ?></h4>

                        </div>

                        <hr class="horizontal my-0 dark" />

                    </div>

                </div>
            </div>
        </section>



















        <section class="2">
            <style>
                .mb-1 {
                    margin-bottom: 6px;
                }

                .th-sm {
                    background-color: #53769c !important;
                    padding: 0.1rem 0.2rem !important;
                    position: sticky;
                    top: 0;
                }

                ::-webkit-scrollbar {
                    width: 5px;
                }

                ::-webkit-scrollbar-thumb {
                    background-color: #53769c;
                    border-radius: 10px;
                }
            </style>
            <div class="row" style=" margin-top: 12px;">
                <div class="col-sm-4">
                    <div class="card qwe">
                        <div class="card-body" style="height: 227px; background-color: #f7f7f7;">
                            <div class="row">
                                <div class="col-sm-12 mb-1">
                                    <select name="fetchval" id="fetchval" class=" form-control" onchange="MonthData()" style="border-color: red;">
                                        <option value="1">1Month</option>
                                        <option value="6">6Month</option>
                                        <option value="12">1Year</option>
                                    </select>
                                    <input type="hidden" name="total_students" id="total_students">
                                </div>
                                <div class="col-sm-12 mb-1">
                                    <input type="date" name="from_date" id="from_date" class="form-control" value="<?= $records['stvacdm_start']; ?>" />
                                    <input type="hidden" name="total_students" id="total_students">
                                </div>
                                <div class="col-sm-12 mb-1">
                                    <input type="date" name="to_date" id="to_date" class=" form-control" value="<?= $records['stvacdm_start']; ?>" />
                                    <input type="hidden" name="total_students" id="total_students">
                                </div>
                                <div class="col-sm-12 mb-1">
                                    <select name="acdm_year" id="acdm_year" class=" form-control">
                                        <option value="">Select Academic Year</option>
                                        <?php foreach ($academic_years as $academic_year) {

                                            if ($academic_year['stvacdm_id'] == $academic_year['stvacdm_id']) {

                                                $selected = 'selected';
                                            } else {

                                                $selected = '';
                                            }

                                        ?>

                                            <option <?= $selected; ?> value="<?= $academic_year['stvacdm_id']; ?>">
                                                <?= $academic_year['stvacdm_year']; ?></option>

                                        <?php } ?>

                                    </select>
                                    <input type="hidden" name="total_students" id="total_students">
                                </div>
                                <div class="col-sm-12 mb-1">
                                    <button id="find-students" type="button" class="btn btn-sm w-50" style="background-color: #87c25b !important;
												color: #fff !important;" value="" onclick="fetchProject()">View </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="card qwe">
                        <div class="card-body" style="height: 227px; overflow: auto; padding: 0px;">

                            <table id="dtDynamicVerticalScrollExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="th-sm">#
                                        </th>
                                        <th class="th-sm">Class
                                        </th>
                                        <th class="th-sm">Class Fees
                                        </th>
                                        <th class="th-sm">Paid Fees
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="kschart">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>






















        <section class="3">
            <div class="row mt-3">
                <div class="col-lg-3 col-sm-3">
                    <div class="card qwe mb-2">
                        <?php $bal = $total_stud['TOTAL_STUDENT'] - $total_stud['INACTIVE']; ?>
                        <div class="card-header p-3 pt-2" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                            All Student
                            <h4 class="mb-0"><?= $total_stud['TOTAL_STUDENT'] ?></h4>

                        </div>

                        <hr class="dark horizontal my-0" />

                    </div>

                </div>


                <div class="col-lg-3 col-sm-3 mt-sm-0 mt-4">
                    <div class="card qwe mb-2">
                        <div class="card-header p-3 pt-2 bg-transparent" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal4">
                            Active Student
                            <h4 class="mb-0"><?= $bal ?></h4>

                        </div>

                        <hr class="horizontal my-0 dark" />

                    </div>


                </div>


                <div class="col-lg-3 col-sm-3 mt-sm-0 mt-4">

                    <div class="card qwe mb-2">
                        <div class="card-header p-3 pt-2" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal5">
                            RTE Student
                            <h4 class="mb-0"><?= $total_stud['RTE'] ?></h4>

                        </div>

                        <hr class="dark horizontal my-0" />

                    </div>

                </div>



                <div class="col-lg-3 col-sm-3 mt-sm-0 mt-4">

                    <div class="card qwe">
                        <div class="card-header p-3 pt-2 bg-transparent" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal6">
                            Inactive Students
                            <h4 class="mb-0"><?= $total_stud['INACTIVE'] ?></h4>

                        </div>

                        <hr class="horizontal my-0 dark" />

                    </div>

                </div>
            </div>

        </section>




















        <section class="4">
            <div class="row" style=" margin-top: 12px;">
                <div class="col-sm-8">
                    <div class="card qwe">
                        <div class="card-body" style="height: 227px; overflow: auto; padding: 0px;">
                            <table id="dtDynamicVerticalScrollExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="th-sm">#
                                        </th>
                                        <th class="th-sm">Class
                                        </th>
                                        <th class="th-sm">Total Fees
                                        </th>

                                        <th class="th-sm">Paid Fees
                                        </th>
                                        <th class="th-sm">Balance Fees
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($class_fetch as $class) : ?>
                                        <?php $bal = $class['total_fees'] - $class['fees_paid']; ?>

                                        <tr class="clickable-row3" style="cursor: pointer;" data-std="<?= $class['stvfstr_std'] ?>">

                                            <td><?= $i ?></td>

                                            <td> <?= $class['stvfstr_std'] ?></td>


                                            <td><?= $class['total_fees'] ?></td>
                                            <td><?= $class['fees_paid'] ?></td>

                                            <td>
                                                <?= $bal ?>
                                            </td>

                                        </tr>

                                    <?php $i++;
                                    endforeach; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card qwe">
                        <div class="card-body" style="height: 227px; overflow: auto;">


                        </div>
                    </div>
                </div>

            </div>
        </section>












        <section>
            <!-- Modal 1 -->
            <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable" style="max-width: 700px !important;">
                    <div class="modal-content" style=" border-radius: 0px !important;">
                        <div class="modal-header" style="padding: 0px;background-image: linear-gradient(#ac3d39, #e51f18, #ac3d39); border-radius:0px;">
                            <h5 class="modal-title" id="exampleModalLabel">Fees Summary Class Wise</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin: 0px !important;"></button>
                        </div>
                        <div class="modal-body" style="padding: 0rem !important;">
                            <table id="dtDynamicVerticalScrollExample" style="cursor: pointer;" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="th-sm">#
                                        </th>
                                        <th class="th-sm">Class
                                        </th>
                                        <th class="th-sm">No of Students
                                        </th>
                                        <th class="th-sm">Total Fees
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($fatch_class as $class) : ?>

                                        <tr class="clickable-row" data-std="<?= $class['standard'] ?>">

                                            <td><?= $i ?></td>

                                            <td> <?= $class['standard'] ?></td>

                                            <td><?= $class['no_of_students'] ?></td>

                                            <td>
                                                <?= $class['total_fees'] ?>
                                            </td>

                                        </tr>

                                    <?php $i++;
                                    endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer justify-content-center" style="padding: 0.25rem !important;">
                            <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background-color: #87c25b !important;
    color: #fff !important;">Close</button>
                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                        </div>
                    </div>
                </div>
            </div>





            <!-- Modal 2 -->

            <div class="modal fade" id="exampleModalLong2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable" style="max-width: 700px !important;">
                    <div class="modal-content" style=" border-radius: 0px !important;">
                        <div class="modal-header" style="padding: 0px;background-image: linear-gradient(#ac3d39, #e51f18, #ac3d39); border-radius:0px;">
                            <h5 class="modal-title" id="exampleModalLabel">Fees Detail Class Wise</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin: 0px !important;"></button>
                        </div>
                        <div class="modal-body" style="padding: 0rem !important;">
                            <table id="dtDynamicVerticalScrollExample" style="cursor: pointer;" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                <thead id="th_data">
                                    <tr>
                                        <th class="th-sm">#</th>
                                        <th class="th-sm">ID</th>
                                        <th class="th-sm">Name</th>
                                        <th class="th-sm">Std</th>
                                        <th class="th-sm">Total Fees</th>
                                        <th class="th-sm">Paid Fees</th>
                                    </tr>
                                </thead>
                                <tbody id="chart_fetch3">

                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer justify-content-center" style="padding: 0.25rem !important;">
                            <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background-color: #87c25b !important;
    color: #fff !important;">Close</button>
                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                        </div>
                    </div>
                </div>
            </div>



            <!-- Modal 3 -->

            <div class="modal fade" id="exampleModalLong3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable" style="max-width: 700px !important;">
                    <div class="modal-content" style=" border-radius: 0px !important;">
                        <div class="modal-header" style="padding: 0px;background-image: linear-gradient(#ac3d39, #e51f18, #ac3d39); border-radius:0px;">
                            <h5 class="modal-title" id="exampleModalLabel">Fees Detail Student Wise</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin: 0px !important;"></button>
                        </div>
                        <div class="modal-body" style="padding: 0rem !important;">
                            <table id="dtDynamicVerticalScrollExample" style="cursor: pointer;" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                <thead id="th_data4">

                                </thead>
                                <tbody id="chart_fetch4">

                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer justify-content-center" style="padding: 0.25rem !important;">
                            <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background-color: #87c25b !important;
    color: #fff !important;">Close</button>
                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                        </div>
                    </div>
                </div>
            </div>


            <!-- Button trigger modal -->

            <!-- Modal 4 -->
            <div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable" style="max-width: 700px !important;">
                    <div class="modal-content" style=" border-radius: 0px !important;">
                        <div class="modal-header" style="padding: 0px;background-image: linear-gradient(#ac3d39, #e51f18, #ac3d39); border-radius:0px;">
                            <h5 class="modal-title" id="exampleModalLabel">Active Student List</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin: 0px !important;"></button>
                        </div>
                        <div class="modal-body" style="padding: 0rem !important;">
                            <table id="dtDynamicVerticalScrollExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="th-sm">#
                                        </th>
                                        <th class="th-sm">Student Id
                                        </th>
                                        <th class="th-sm">Name
                                        </th>
                                        <th class="th-sm">Class
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    // print_r($fatch_rte);
                                    foreach ($fatch_act as $act) : ?>

                                        <tr>

                                            <td><?= $i ?></td>

                                            <td><?= $act['SPRIDEN_student_id'] ?></td>

                                            <td><?= $act['name'] ?></td>

                                            <td>
                                                <?= $act['STVCLAS_standard'] ?>
                                            </td>

                                        </tr>

                                    <?php $i++;
                                    endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer justify-content-center" style="padding: 0.25rem !important;">
                            <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background-color: #87c25b !important;
    color: #fff !important;">Close</button>
                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal 5 -->
            <div class="modal fade" id="exampleModal5" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable" style="max-width: 700px !important;">
                    <div class="modal-content" style=" border-radius: 0px !important;">
                        <div class="modal-header" style="padding: 0px;background-image: linear-gradient(#ac3d39, #e51f18, #ac3d39); border-radius:0px;">
                            <h5 class="modal-title" id="exampleModalLabel">RTE Student List</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin: 0px !important;"></button>
                        </div>
                        <div class="modal-body" style="padding: 0rem !important;">
                            <table id="dtDynamicVerticalScrollExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="th-sm">#
                                        </th>
                                        <th class="th-sm">Student Id
                                        </th>
                                        <th class="th-sm">Name
                                        </th>
                                        <th class="th-sm">Standard
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    // print_r($fatch_rte);
                                    foreach ($fatch_rte as $rte) : ?>

                                        <tr>

                                            <td><?= $i ?></td>

                                            <td><?= $rte['student_id'] ?></td>

                                            <td><?= $rte['student_name'] ?></td>

                                            <td>
                                                <?= $rte['standard'] ?>
                                            </td>

                                        </tr>

                                    <?php $i++;
                                    endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer justify-content-center" style="padding: 0.25rem !important;">
                            <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background-color: #87c25b !important;
    color: #fff !important;">Close</button>
                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                        </div>
                    </div>
                </div>
            </div>




            <!-- Modal 6 -->
            <div class="modal fade" id="exampleModal6" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable" style="max-width: 700px !important;">
                    <div class="modal-content" style=" border-radius: 0px !important;">
                        <div class="modal-header" style="padding: 0px;background-image: linear-gradient(#ac3d39, #e51f18, #ac3d39); border-radius:0px;">
                            <h5 class="modal-title" id="exampleModalLabel">Inactive Student List</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin: 0px !important;"></button>
                        </div>
                        <div class="modal-body" style="padding: 0rem !important;">
                            <table id="dtDynamicVerticalScrollExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="th-sm">#
                                        </th>
                                        <th class="th-sm">Student Id
                                        </th>
                                        <th class="th-sm">Name
                                        </th>
                                        <th class="th-sm">Class
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    // print_r($fatch_rte);
                                    foreach ($fatch_inact as $inact) : ?>

                                        <tr class="clickable-row9" style="cursor: pointer;" data-stud="<?= $inact['SPRIDEN_student_id'] ?>">

                                            <td><?= $i ?></td>

                                            <td><?= $inact['SPRIDEN_student_id'] ?></td>

                                            <td><?= $inact['name'] ?></td>

                                            <td>
                                                <?= $inact['STVCLAS_standard'] ?>
                                            </td>

                                        </tr>

                                    <?php $i++;
                                    endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer justify-content-center" style="padding: 0.25rem !important;">
                            <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background-color: #87c25b !important;
    color: #fff !important;">Close</button>
                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal 7 -->
            <div class="modal fade" id="exampleModal7" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable" style="max-width: 700px !important;">
                    <div class="modal-content" style=" border-radius: 0px !important;">
                        <div class="modal-header" style="padding: 0px;background-image: linear-gradient(#ac3d39, #e51f18, #ac3d39); border-radius:0px;">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin: 0px !important;"></button>
                        </div>
                        <div class="modal-body" style="padding: 0rem !important;">
                            <table id="dtDynamicVerticalScrollExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="th-sm">#
                                        </th>
                                        <th class="th-sm">Student Id
                                        </th>
                                        <th class="th-sm">Name
                                        </th>
                                        <th class="th-sm">Class
                                        </th>
                                        <th class="th-sm">total Fees
                                        </th>
                                        <th class="th-sm">Paid Fees
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="chart_fetch7">

                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer justify-content-center" style="padding: 0.25rem !important;">
                            <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background-color: #87c25b !important;
    color: #fff !important;">Close</button>
                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal 8 -->
            <div class="modal fade" id="exampleModal8" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable" style="max-width: 700px !important;">
                    <div class="modal-content" style=" border-radius: 0px !important;">
                        <div class="modal-header" style="padding: 0px;background-image: linear-gradient(#ac3d39, #e51f18, #ac3d39); border-radius:0px;">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin: 0px !important;"></button>
                        </div>
                        <div class="modal-body" style="padding: 0rem !important;">
                            <table id="dtDynamicVerticalScrollExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="th-sm">#
                                        </th>
                                        <th class="th-sm">Student Id
                                        </th>
                                        <th class="th-sm">Name
                                        </th>
                                        <th class="th-sm">Leave Date
                                        </th>
                                        <th class="th-sm">Leave Reason
                                        </th>
                                        <th class="th-sm">LC Date
                                        </th>
                                        <th class="th-sm">Leave Year
                                        </th>
                                        <th class="th-sm">Next School
                                        </th>
                                        <th class="th-sm">Remark
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="chart_fetch8">

                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer justify-content-center" style="padding: 0.25rem !important;">
                            <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background-color: #87c25b !important;
    color: #fff !important;">Close</button>
                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </section>




    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
    <script>
        $(document).on('click', '.clickable-row', function() {
            let year = document.getElementById('acdm_year').value
            let std = $(this).attr("data-std");
            let user = '<?= $this->session->userdata('oliveMobileSystemUser') ?>'
            $.ajax({
                url: "<?= base_url('index.php/Report/student_dataChart') ?>",
                method: "GET",
                dataType: "json",
                data: {
                    std: std,
                },
                success: function(data) {
                    let str = ''
                    let i = 0
                    let tc = 'class="text-center"'
                    data.forEach(element => {
                        str += `<tr class="clickable-row2" data-id="${element.SPRIDEN_student_id}">
                                  <td>${++i}</td>
                                  <td>
                                  ${element.SPRIDEN_student_id}</td>
                                  <td>${element.name}</td>
                                  <td>${element.STVCLAS_standard}</td>
                                  <td>${element.stvfstr_fees}</td>
                                  <td>${element.paid_fees}</td>
                                  </tr>`;

                    });
                    $("#chart_fetch3").html(str)
                }
            })
            var myModal = new bootstrap.Modal(document.getElementById('exampleModalLong2'), {
                keyboard: false
            })
            myModal.show();

        });







        $(document).on('click', '.clickable-row2', function() {

            // console.log(types);
            let year = document.getElementById('acdm_year').value
            let studid = $(this).attr("data-id");
            let user = '<?= $this->session->userdata('oliveMobileSystemUser') ?>'
            // console.log(year, user);
            let html = ''

            let th = ['#', 'Date', 'Amount', 'Concession', 'Cons Note', 'Acdm. Year', 'Received By', 'Note']

            th.forEach(element => {
                html += `<th>${element}</th>`
            })

            $("#th_data4").html(html)



            $.ajax({
                url: "<?= base_url('index.php/FeesDashboard/stud_fetch') ?>",
                method: "POST",
                data: {
                    id: studid,

                },
                dataType: "JSON",
                success: function(data) {
                    let str = ''
                    let i = 0
                    let tc = 'class="text-center"'
                    data.forEach(element => {

                        let bg = element.spratnd_status == 'A' ? 'danger' : 'info'

                        str += `<tr>
                        <td ${tc}>${++i}</td>
                        <td>${element.sprfees_feesdate}</td>
                                 <td>${element.sprfees_amount}</td>
                                 <td>${element.sprfees_exumption}</td>
                                 <td>${element.sprfees_ex_notes}</td>
                                 <td>${element.stvacdm_year}</td>
                                 <td>${element.spruser_name}</td>
                                 <td>${element.sprfees_notes}</td>
                    </tr>`

                    })

                    $("#chart_fetch4").html(str)
                }


            });


            console.log('clicked');
            // console.log('hii');

            var myModal = new bootstrap.Modal(document.getElementById('exampleModalLong3'), {
                keyboard: false
            })
            myModal.show();

        });
    </script>




    <script>
        let MonthData = () => {
            let year = document.getElementById('acdm_year').value
            let month = $("#fetchval").val();
            let user = '<?= $this->session->userdata('oliveMobileSystemUser') ?>'



            $.ajax({
                url: "<?= base_url('index.php/Report/monthfilter') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    request: month,
                    year: year
                },
                success: function(data) {
                    let str = ''
                    let i = 0
                    data.forEach(element => {
                        str += `
            <tr>
              <td>${++i}</td>
              <td>${element.STVCLAS_standard}</td>
              <td>${element.class_fees}</td>      
              
              <td>${element.total_paid}</td>
            </tr>`
                    });
                    $("#kschart").html(str)

                }
            });
        };

        MonthData()



        let fetchProject = () => {

            let from = document.getElementById('from_date').value;
            let to = document.getElementById('to_date').value;
            let year = document.getElementById('acdm_year').value;

            // Month1BarChart(from, to);
            paidBarChart(from, to, year);
            // paidBarCharts(from, to, year);
            // paid1BarCharts(from, to, year);
        }

        let paidBarChart = (from, to, year) => {

            $.ajax({
                url: "<?= base_url('index.php/FeesDashboard/paid_filter') ?>",
                method: "GET",
                dataType: "JSON",
                data: {
                    from: from,
                    to: to,
                    year: year,
                },
                success: function(data) {
                    let str = ''
                    let i = 0
                    data.forEach(element => {
                        str += `
            <tr>
              <td>${++i}</td>
              <td>${element.stvfstr_std}</td>
              <td>${element.stvfstr_fees}</td>      
              
              <td>${element.fees_paid}</td>
              
            </tr>`
                    });
                    $("#kschart").html(str)
                }
            })

        }

        fetchProject();




        $(document).on('click', '.clickable-row3', function() {
            let year = document.getElementById('acdm_year').value
            let std = $(this).attr("data-std");
            let user = '<?= $this->session->userdata('oliveMobileSystemUser') ?>'
            $.ajax({
                url: "<?= base_url('index.php/Report/student_dataChart') ?>",
                method: "GET",
                dataType: "json",
                data: {
                    std: std,
                },
                success: function(data) {
                    let str = ''
                    let i = 0
                    let tc = 'class="text-center"'
                    data.forEach(element => {
                        str += `<tr>
                                  <td>${++i}</td>
                                  <td>
                                  ${element.SPRIDEN_student_id}</td>
                                  <td>${element.name}</td>
                                  <td>${element.STVCLAS_standard}</td>
                                  <td>${element.stvfstr_fees}</td>
                                  <td>${element.paid_fees}</td>
                                  </tr>`;

                    });
                    $("#chart_fetch7").html(str)
                }
            })
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal7'), {
                keyboard: false
            })
            myModal.show();

        });



        $(document).on('click', '.clickable-row9', function() {
            let year = document.getElementById('acdm_year').value
            let id = $(this).attr("data-stud");
            let user = '<?= $this->session->userdata('oliveMobileSystemUser') ?>'
            $.ajax({
                url: "<?= base_url('index.php/Report/leaving') ?>",
                method: "POST",
                data: {
                    id: id,
                },
                dataType: "JSON",
                success: function(data) {
                    let str = ''
                    let i = 0
                    data.forEach(element => {
                        str += `<tr>
                                  <td>${++i}</td>
                                  <td>${element.SPRIDEN_student_id}</td>
                                  <td>${element.name}</td>
                                  <td>${element.leaving_date}</td>
                                  <td>${element.leaving_reason}</td>
                                  <td>${element.lc_date}</td>
                                  <td>${element.leaving_year}</td>
                                  <td>${element.student_next_school}</td>
                                  <td>${element.remarks}</td>
                      </tr>`
                    })
                    $("#chart_fetch8").html(str)
                }
            });
            console.log('clicked');
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal8'), {
                keyboard: false
            })
            myModal.show();

        });
    </script>
</body>

</html>
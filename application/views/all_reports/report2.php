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
                            <h5 class="card-title">All Students</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="print-container">
                                <div class="col-md-12 pt-2">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="table" data-custom="true"
                                            class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th class="text-capitaliz d-none">Class Hiden</th>
                                                    <th class="text-capitaliz">Class</th>
                                                    <th class="text-capitaliz">Status </th>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody">
                                                <?php
                                                foreach ($standard as $std) {
                                                    if ($std['STVCLAS_standard'] == "I") {
                                                       $classStd = "First";
                                                    } else if ($std['STVCLAS_standard'] == "II") {
                                                        $classStd = "Second";
                                                    } else if ($std['STVCLAS_standard'] == "III") {
                                                        $classStd = "Third";
                                                    } else if ($std['STVCLAS_standard'] == "IV") {
                                                        $classStd = "Fourth";
                                                    } else if ($std['STVCLAS_standard'] == "V") {
                                                        $classStd = "Fifth";
                                                    } else if ($std['STVCLAS_standard'] == "VI") {
                                                        $classStd = "Sixth";
                                                    } else if ($std['STVCLAS_standard'] == "VII") {
                                                        $classStd = "Seventh";
                                                    } else if ($std['STVCLAS_standard'] == "VIII") {
                                                        $classStd = "Eighth";
                                                    } else if ($std['STVCLAS_standard'] == "IX") {
                                                        $classStd = "Ninth";
                                                    } else if ($std['STVCLAS_standard'] == "Sr.Kg.") {
                                                        $classStd = "Sr.Kg.";
                                                    } else if ($std['STVCLAS_standard'] == "Jr.Kg.") {
                                                        $classStd = "Jr.Kg.";
                                                    } else if ($std['STVCLAS_standard'] == "Nursery") {
                                                        $classStd = "Nursery";
                                                    } else {
                                                        $classStd = "";
                                                    }

                                                    $bcls_array = array();
                                                    $gcls_array = array();
                                                    $boy_array = array();
                                                    $girl_array = array();
                                                    foreach ($all_records as $record) {
                                                        if ($record['STVCLAS_standard'] == $std['STVCLAS_standard']) {
                                                            if ($record['spriden_student_status'] == "A") {
                                                                $status = "Active";
                                                            } else if ($record['spriden_student_status'] == "N") {
                                                                $status = "No Show";
                                                            } else if ($record['spriden_student_status'] == "I") {
                                                                $status = "In Active";
                                                            } else if ($record['spriden_student_status'] == "null") {
                                                                $status = "Null";
                                                            } else if ($record['spriden_student_status'] == "New Admission") {
                                                                $status = "New Admission";
                                                            } else {
                                                                $status = "";
                                                            }

                                                            if ($record['STVCLAS_class'] == "A") {
                                                                array_push($bcls_array, $status);
                                                                array_push($boy_array, $record['total']);
                                                            } else {
                                                                array_push($gcls_array, $status);
                                                                array_push($girl_array, $record['total']);
                                                            }
                                                        }
                                                    } 
                                                    ?>

                                                    <tr>
                                                        <td class="d-none"><?= $classStd ?></td>
                                                        <td class="text-center font-weight-bold">
                                                            <?= $std['STVCLAS_standard'] ?></td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <?php if (count($bcls_array) > 0) { ?>
                                                                        <p class="text-info" style="margin: 0; padding:0;">Boys
                                                                        </p>
                                                                        <?php
                                                                        for ($i = 0; $i < count($bcls_array); $i++) {
                                                                            echo $bcls_array[$i] .
                                                                                " : " . $boy_array[$i] . "<br>";
                                                                        }
                                                                    }
                                                                    ?>
                                                               </div>

                                                                <div class="col-md-6">
                                                                    <?php if (count($gcls_array) > 0) { ?>
                                                                        <p class="text-danger" style="margin: 0; padding:0;">
                                                                            Girls</p>
                                                                        <?php
                                                                        for ($i = 0; $i < count($gcls_array); $i++) {
                                                                            echo $gcls_array[$i] .
                                                                                " : " . $girl_array[$i] . "<br>";
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </td>
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
            </div>
        </div>
    </div>

    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>
</body>

</html>
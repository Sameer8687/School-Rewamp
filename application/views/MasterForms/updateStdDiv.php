<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School</title>
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
    <?php include 'application/views/sidebar.php'; ?>
    <?php include 'application/views/topNav.php'; ?>
    <div class="pc-container">
        <div class="pcoded-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">

                            <div class="col-md-3">
                                <h5 class="card-title"> Report</h5>
                            </div>
                            <div class="col-md-9">
                                <h4 id="student_name" class="mb-0 text-primary"></h4>
                            </div>

                        </div>
                        <div class="card-body p-2">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">Student ID<span
                                                        class="float-right">:</span></label>
                                                <input type="text" name="student_id"
                                                    class="form-control form-control-sm col-md-7" id="student_id">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-sm btn-secondary" id="btnView">View</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="simpletable" data-custom="true"
                                            class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th class="text-capitalize">#</th>
                                                    <th class="text-capitalize">ID</th>
                                                    <th class="text-capitalize">Std</th>
                                                    <th class="text-capitalize">Div</th>
                                                    <th class="text-capitalize">Year</th>
                                                    <th class="text-capitalize">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody">
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
        var academicYears = <?= json_encode($acdm_year); ?>;
        $(document).ready(function () {
            $("#btnView").click(function () {
                var id = $("#student_id").val();
                if (id == "") {
                    alert("Please Select Student ID");
                    return;
                }
                $.ajax({
                    url: "<?= base_url('MasterForm/getStdDivDataByID'); ?>",
                    type: "GET",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function (response) {
                        var html = '';
                        if (response.length > 0) {
                            $("#student_name").html(
                                response[0].name
                            );
                            $.each(response, function (i, row) {
                                var acdmDropdown = '<select class="form-control form-control-sm acdm" data-old="' + row.STVCLAS_acdm + '">';
                                $.each(academicYears, function (k, year) {
                                    var selected = '';
                                    if (year.stvacdm_id == row.STVCLAS_acdm) {
                                        selected = 'selected';
                                    }
                                    acdmDropdown += '<option value="' + year.stvacdm_id + '" ' + selected + '>' + year.stvacdm_year + '</option>';
                                });
                                acdmDropdown += '</select>';
                                html += '<tr>';
                                html += '<td>' + (i + 1) + '</td>';
                                html += '<td>' + row.SPRIDEN_student_id + '</td>';
                                html += '<td><input type="text" class="form-control form-control-sm standard" value="' + row.STVCLAS_standard + '" data-old="' + row.STVCLAS_standard + '"></td>';
                                html += '<td><input type="text" class="form-control form-control-sm division" value="' + row.STVCLAS_class + '" data-old="' + row.STVCLAS_class + '"></td>';
                                html += '<td>' + acdmDropdown + '</td>';
                                html += '<td><button class="btn btn-sm btn-primary updateBtn" disabled data-studentid="' + row.STVCLAS_student_id + '" data-code="' + row.STVCLAS_code + '">Update</button></td>';
                                html += '</tr>';
                            });
                        } else {
                            $("#student_name").html('');
                        }
                        $("#tbody").html(html);
                    },
                });
            });
        });

        $(document).on('keyup change', '.standard,.division,.acdm', function () {
            var tr = $(this).closest('tr');
            var stdChanged = tr.find('.standard').val() != tr.find('.standard').data('old');
            var divChanged = tr.find('.division').val() != tr.find('.division').data('old');
            var yearChanged = tr.find('.acdm').val() != tr.find('.acdm').data('old');
            tr.find('.updateBtn').prop('disabled', !(stdChanged || divChanged || yearChanged));
        });

        $(document).on('click', '.updateBtn', function () {
            var btn = $(this);
            var tr = btn.closest('tr');
            $.ajax({
                url: "<?= base_url('MasterForm/updateClassDetails') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    STVCLAS_student_id: btn.data('studentid'),
                    STVCLAS_code: btn.data('code'),
                    STVCLAS_standard: tr.find('.standard').val(),
                    STVCLAS_class: tr.find('.division').val(),
                    STVCLAS_acdm: tr.find('.acdm').val(),
                    STVCLAS_acdm_old: tr.find('.acdm').data('old'),
                    old_standard: tr.find('.standard').data('old'),
                    old_class: tr.find('.division').data('old')
                },
                success: function (response) {
                    alert(response.message);
                    if (response.status) {
                        tr.find('.standard').data('old', tr.find('.standard').val());
                        tr.find('.division').data('old', tr.find('.division').val());
                        tr.find('.acdm').data('old', tr.find('.acdm').val());
                        btn.prop('disabled', true);
                    }
                }
            });
        });
    </script>
</body>

</html>
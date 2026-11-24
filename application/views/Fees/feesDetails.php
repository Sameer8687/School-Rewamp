<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Student Fees Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <!---Include CSS Files-->
    <?php include 'application/views/cssPages.php'; ?>
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
    <!--Include Sidebar -->
    <?php include 'application/views/sidebar.php'; ?>
    <!--Include Top Navbar-->
    <?php include 'application/views/topNav.php'; ?>
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pcoded-content">
            <div class="row margin-top-minus-40 mobile-margin-top-minus-40 mb-2">
                <div class="col-md-7">
                    <a href="<?= base_url(); ?>" class="btn btn-danger btn-sm"> <i
                            class="fas fa-arrow-alt-circle-left"></i> Back</a>
                    <button onclick="window.print();" class="btn btn-secondary btn-sm" id="print-button" type="button">
                        <i class="fas fa-print"></i> <span id="dynamicLabelAddButton">Print</span></button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                Fees Details
                            </h5>
                        </div>
                        <style>
                            .border {
                                border: 1px solid;
                            }
                        </style>
                        <div class="card-body">
                            <style>
                                .table tr td,
                                .table tr th {
                                    padding: 5px !important;
                                    font-size: 11px !important;
                                    cursor: pointer;
                                }
                            </style>
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-10">
                                            <div class="row">
                                                <label for="" class="col-md-2">Academic Year <span
                                                        class="float-right">:</span></label>

                                                <select name="acdm_year" id="acdm_year"
                                                    class="form-control form-control-sm col-md-2">

                                                    <option value="">Select Year</option>

                                                    <?php foreach ($academic_years as $acdm) { ?>
                                                        <option value="<?= $acdm['stvacdm_id'] ?>"
                                                            <?= ($selected_academic_year == $acdm['stvacdm_id']) ? 'selected' : ''; ?>>
                                                            <?= $acdm['stvacdm_year'] ?>
                                                        </option>
                                                    <?php } ?>

                                                </select>
                                                <label for="" class="col-md-2">Standard <span
                                                        class="float-right">:</span></label>
                                                <select name="standard" id="standard"
                                                    class="form-control form-control-sm col-md-2">
                                                    <option value="">Select</option>
                                                    <?php foreach (['Nursery', 'Jr.Kg.', 'Sr.Kg.', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'] as $cls): ?>
                                                        <option value="<?= $cls ?>"><?= $cls ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <label for="" class="col-md-2">Division <span
                                                        class="float-right">:</span></label>
                                                <select name="division" id="division"
                                                    class="form-control form-control-sm col-md-2">
                                                    <option value="">Select Division</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="simpletable" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Status</th>
                                                    <th>RTE</th>
                                                    <th>Academic Year</th>
                                                    <th>Standard</th>
                                                    <th>Class</th>
                                                    <th>Total Fees</th>
                                                    <th>Total Paid</th>
                                                    <th>Exempted</th>
                                                    <th>Balance</th>
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

    <?php include 'application/views/jsPages.php'; ?>
    <script>
        $(document).ready(function() {
            function fetchFeeDetails() {
                let acdm = $('#acdm_year').val();
                let std = $('#standard').val();
                let div = $('#division').val();

                if (acdm && std && div) {
                    $.ajax({
                        url: "<?= base_url('student/getFeedDetails') ?>",
                        type: "POST",
                        data: {
                            acdm: acdm,
                            std: std,
                            div: div
                        },
                        dataType: "json",
                        success: function(response) {
                            let html = "";

                            if (response.length > 0) {
                                let grouped = {};
                                response.forEach(row => {
                                    if (!grouped[row.id]) {
                                        grouped[row.id] = {
                                            student: {
                                                name: row.name,
                                                status: row.status,
                                                rte_status: row.rte_status
                                            },
                                            records: []
                                        };
                                    }
                                    grouped[row.id].records.push(row);
                                });

                                Object.keys(grouped).forEach(id => {
                                    let student = grouped[id].student;
                                    let records = grouped[id].records;
                                    let rowspan = records.length;
                                    let first = true;

                                    records.forEach(record => {
                                        html += "<tr>";
                                        if (first) {
                                            html += `<td rowspan="${rowspan}">${id}</td>`;
                                            html += `<td rowspan="${rowspan}">${student.name}</td>`;
                                            html += `<td rowspan="${rowspan}">${student.status}</td>`;
                                            html += `<td rowspan="${rowspan}">${student.rte_status}</td>`;
                                            first = false;
                                        }
                                        html += `<td>${record.academic_year}</td>`;
                                        html += `<td>${record.standard}</td>`;
                                        html += `<td>${record.class}</td>`;
                                        html += `<td>${parseFloat(record.stvfstr_fees).toLocaleString()}</td>`;
                                        html += `<td>${parseFloat(record.total_paid).toLocaleString()}</td>`;
                                        html += `<td>${parseFloat(record.exempted).toLocaleString()}</td>`;
                                        html += `<td>${parseFloat(record.balance_amount).toLocaleString()}</td>`;
                                        html += "</tr>";
                                    });
                                });

                            } else {
                                html = `<tr><td colspan="11" class="text-center">No data found</td></tr>`;
                            }

                            $('#tbody').html(html);
                        }
                    });
                } else {
                    $('#tbody').html('');
                }
            }

            $('#acdm_year, #standard, #division').change(fetchFeeDetails);
        });
    </script>

</body>

</html>
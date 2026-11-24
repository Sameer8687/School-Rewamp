<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Class Wise Fees</title>
    <link rel="stylesheet" href="r.css" />
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets1/images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- plugin css -->

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.3.1/css/all.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets1/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet"
        type="text/css" />
    <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>


    <!-- Layout config Js -->
    <script src="<?php echo base_url(); ?>assets1/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="<?php echo base_url(); ?>assets1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo base_url(); ?>assets1/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url(); ?>assets1/css/app.min.css" rel="stylesheet" type="text/css" />

    <script>
        function send_data() {
            const std = document.getElementById('standard').value;
            const div = document.getElementById('division').value;
            const year = document.getElementById('acdm_year').value;

            fetch_TotalStudents(std, div, year);
            fetch_TotalFees(std, div, year);
            fetch_TotalRTE(std, div, year);
            fetch_paidFees(std, div, year);
            fetch_balanceFees(std, div, year);
            fetch_exemptionFees(std, div, year);
            table(std, div, year);
            paid1BarCharts(std, div, year);
        }
        // // Call send_data function when the page loads
        window.onload = send_data;


        function fetch_TotalStudents(std, div, year) {

            $.ajax({
                url: '<?php echo base_url() ?>FeesDashboard/all_students',
                method: 'get',
                dataType: 'JSON',
                data: {
                    std: std,
                    div: div,
                    year: year
                },
                beforeSend: function() {
                    $("#all_students").html(
                        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                    );
                },
                success: function(data) {
                    console.log(data);
                    let tot = data.total || 0;
                    $("#all_students").html(`<span class="counter-value" data-target="${tot}">${tot}</span>`);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(`Error fetching total students: ${textStatus}, ${errorThrown}`);
                    $("#all_students").html('<span class="text-danger">Error loading data</span>');
                }
            });
        }

        function fetch_TotalRTE(std, div, year) {

            $.ajax({
                url: '<?php echo base_url() ?>FeesDashboard/rte_student',
                method: 'get',
                dataType: 'JSON',
                data: {
                    std: std,
                    div: div,
                    year: year
                },
                beforeSend: function() {
                    $("#rte_student").html(
                        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                    );
                },
                success: function(data) {
                    console.log(data);
                    let tot = data.total || 0;
                    $("#rte_student").html(`<span class="counter-value" data-target="${tot}">${tot}</span>`);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(`Error fetching total RTE: ${textStatus}, ${errorThrown}`);
                    $("#rte_student").html('<span class="text-danger">Error loading data</span>');
                }
            });
        }

        function fetch_TotalFees(std, div, year) {

            $.ajax({
                url: '<?php echo base_url() ?>FeesDashboard/total_fees',
                method: 'get',
                dataType: 'JSON',
                data: {
                    std: std,
                    div: div,
                    year: year
                },
                beforeSend: function() {
                    $("#total_fees").html(
                        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                    );
                },
                success: function(data) {
                    console.log(data);
                    let tot = data.total_fees || 0;
                    $("#total_fees").html(`<span class="counter-value" data-target="${tot}">${tot}</span>`);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(`Error fetching total fees: ${textStatus}, ${errorThrown}`);
                    $("#total_fees").html('<span class="text-danger">Error loading data</span>');
                }
            });
        }

        function fetch_paidFees(std, div, year) {

            $.ajax({
                url: '<?php echo base_url() ?>FeesDashboard/paid_fees',
                method: 'get',
                dataType: 'JSON',
                data: {
                    std: std,
                    div: div,
                    year: year
                },
                beforeSend: function() {
                    $("#paid_fees").html(
                        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                    );
                },
                success: function(data) {
                    console.log(data);
                    let tot = data.total_paid || 0;
                    $("#paid_fees").html(`<span class="counter-value" data-target="${tot}">${tot}</span>`);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(`Error fetching paid fees: ${textStatus}, ${errorThrown}`);
                    $("#paid_fees").html('<span class="text-danger">Error loading data</span>');
                }
            });
        }

        function fetch_balanceFees(std, div, year) {

            $.ajax({
                url: '<?php echo base_url() ?>FeesDashboard/balance_fees',
                method: 'get',
                dataType: 'JSON',
                data: {
                    std: std,
                    div: div,
                    year: year
                },
                beforeSend: function() {
                    $("#balance_fees").html(
                        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                    );
                },
                success: function(data) {
                    console.log(data);
                    let tot = data.total_balance || 0;
                    $("#balance_fees").html(`<span class="counter-value" data-target="${tot}">${tot}</span>`);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(`Error fetching balance fees: ${textStatus}, ${errorThrown}`);
                    $("#balance_fees").html('<span class="text-danger">Error loading data</span>');
                }
            });
        }

        function fetch_exemptionFees(std, div, year) {

            $.ajax({
                url: '<?php echo base_url() ?>FeesDashboard/exp_student',
                method: 'get',
                dataType: 'JSON',
                data: {
                    std: std,
                    div: div,
                    year: year
                },
                beforeSend: function() {
                    $("#exp_student").html(
                        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                    );
                },
                success: function(data) {
                    console.log(data);
                    let tot = data.total_exemption || 0;
                    $("#exp_student").html(`<span class="counter-value" data-target="${tot}">${tot}</span>`);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(`Error fetching exemption fees: ${textStatus}, ${errorThrown}`);
                    $("#exp_student").html('<span class="text-danger">Error loading data</span>');
                }
            });
        }

        // <!-- update by ansari kamran -->
        function table(std, div, year) {

            $.ajax({
                url: '<?= base_url() ?>FeesDashboard/student_details',
                method: 'get',
                dataType: 'JSON',
                data: {
                    std: std,
                    div: div,
                    year: year
                },
                beforeSend: function() {
                    $("#exp_student").html(
                        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                    );
                },
                success: function(data) {
                    console.log(data);

                    let rows = '';
                    let v_paid = 0,
                        v_exem = 0,
                        v_fees = 0,
                        v_bal = 0;

                    data.forEach(student => {
                        v_paid += parseFloat(student.total_paid) || 0;
                        v_exem += parseFloat(student.total_exemption) || 0;
                        v_fees += parseFloat(student.total_fees) || 0;
                        v_bal += parseFloat(student.balance_fees) || 0;

                        rows += `
                <tr>
                    <td class="add-remark">${student.SPRIDEN_student_id}</td>
                    <td class="add-remark">${student.name}</td>
                    <td class="add-remark">${student.rte_status}</td>
                    <td class="add-remark">${student.total_paid}</td>
                    <td class="add-remark">${student.total_exemption}</td>
                    <td class="add-remark">${student.total_fees}</td>
                    <td class="add-remark">${student.balance_fees}</td>
                </tr>
                `;
                    });

                    const tfoot = `
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total</td>
                    <td class="fw-bold">${v_paid.toLocaleString('en-IN')}</td>
                    <td class="fw-bold">${v_exem.toLocaleString('en-IN')}</td>
                    <td class="fw-bold">${v_fees.toLocaleString('en-IN')}</td>
                    <td class="fw-bold">${v_bal.toLocaleString('en-IN')}</td>
                </tr>
                `;

                    // Render the rows and tfoot separately
                    $("#students_all").html(rows + tfoot);

                    // Prepare and display the total summary inside the h4 tag
                    const totalSummary = `
                                        <tr>
                                            <td colspan="4"></td>
                                            <td style="padding-left: 20px">Paid: ${v_paid.toLocaleString('en-IN')}</td>
                                            <td style="padding-left: 20px">Exem: ${v_exem.toLocaleString('en-IN')}</td>
                                            <td style="padding-left: 20px">Total: ${v_fees.toLocaleString('en-IN')}</td>
                                            <td style="padding-left: 20px">Bal: ${v_bal.toLocaleString('en-IN')}</td>
                                        </tr>
                                        `;

                    // Append the summary row directly to the table
                    $(".fees-table").html(totalSummary);
                }
            });
        }

        // <!-- update by ansari kamran -->
        function generateKChart(series, value1) {
            var options = {
                series: [{
                    data: value1
                }],
                chart: {
                    type: 'bar',
                    height: 430,
                    events: {
                        click: function(event, chartContext, opts) {
                            if (opts.seriesIndex !== undefined && opts.dataPointIndex !== undefined) {
                                const seriesIndex = opts.seriesIndex;
                                const dataPointIndex = opts.dataPointIndex;
                                let range = series[dataPointIndex];
                                let std = document.getElementById("standard").value;
                                let div = document.getElementById("division").value;
                                let acdm = document.getElementById("acdm_year").value;

                                const payload = {
                                    range,
                                    std,
                                    div,
                                    acdm
                                }

                                // Define the URL to send the request to
                                const url = "<?= base_url() ?>" +
                                    'FeesDashboard/fetchchartdata'; // replace with your URL
                                const method = 'POST';
                                const dataType = 'json';

                                $.ajax({
                                    url: url,
                                    method: method,
                                    data: JSON.stringify(payload),
                                    contentType: 'application/json',
                                    dataType: dataType,
                                    beforeSend: function() {
                                        $("#exp_student").html(
                                            '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                                        );
                                    },
                                    success: function(data) {
                                        console.log(data);

                                        let thead = document.getElementById("app");
                                        thead.innerHTML = `
                                                            <tr style="background-color: #53769c; color: #fff; position: sticky; top: 0; z-index: 1;">
                                                                <td>Id</td>
                                                                <td>Name</td>
                                                                <td>Mobile</td>
                                                                <td>L-P</td>
                                                                <td>Paid</td>
                                                                <td>Exm</td>
                                                                <td>Total</td>
                                                                <td>Balance</td>
                                                            </tr>
                                                        `;
                                        let rows = '';
                                        let v_paid = 0,
                                            v_exem = 0,
                                            v_fees = 0,
                                            v_bal = 0;
                                        data.forEach(student => {
                                            v_paid += parseFloat(student.total_paid) || 0;
                                            v_exem += parseFloat(student.total_exemption) || 0;
                                            v_fees += parseFloat(student.total_fees) || 0;
                                            v_bal += parseFloat(student.balance) || 0;
                                            let mobile_num = student.stvpcnf_whatsapp;
                                            let message_wha =
                                                `अस्सलामु अलैकुम पैरेंट्स,
                                            आपका बच्चा / बच्ची ${student.name} STD. ${student.STVCLAS_standard} ${student.STVCLAS_class} में पढाई करता / करती है. उसकी फीस आपने ${student.total_paid} रुपये जमा की है. ${student.balance} रूपये जमा करवाना बाकि है. इसलिए उसकी फीस स्कूल में आकर जमा करवा दीजिये ताकी उसकी पढाई में कोई दिक्कत ना हो।
            
                                            Assalamualaikum Parents,
                                            Your Son/Daughter ${student.name} studies in STD. ${student.STVCLAS_standard}, ${student.STVCLAS_class}. Till now you have paid Fees Amount of Rs ${student.total_paid}. Rs ${student.balance} is yet to be paid. So please pay the remaining fees amount of your child to avoid inconvenience in his/her studies.`;
                                            rows += `
                                        <tr>
                                            <td class="add-remark">${student.SPRIDEN_student_id}</td>
                                            <td class="add-remark">${student.name}</td>
                                            <td class="add-remark">${student.stvpcnf_whatsapp}</td>
                                            <td class="add-remark">${student.last_paid}</td>
                                            <td class="add-remark">${student.total_paid}</td>
                                            <td class="add-remark">${student.total_exemption}</td>
                                            <td class="add-remark">${student.total_fees}</td>
                                            <td class="add-remark">${student.balance} &nbsp;
                                            <a target="_blank" href="https://api.whatsapp.com/send?phone=+91${mobile_num}&text=${encodeURIComponent(message_wha)}"><i class='bx bxl-whatsapp' style='font-size: 19px; font-weight: 500;'></i></a>
                                            </td>
                                        </tr>
                                    `;
                                        });
                                        const tfoot = `
                                                       <tr>
                                                        <td colspan="4" class="text-end fw-bold">Total</td>
                                                        <td class="fw-bold">${v_paid.toLocaleString('en-IN')}</td>
                                                        <td class="fw-bold">${v_exem.toLocaleString('en-IN')}</td>
                                                        <td class="fw-bold">${v_fees.toLocaleString('en-IN')}</td>
                                                        <td class="fw-bold">${v_bal.toLocaleString('en-IN')}</td>
                                                       </tr>
                                                        `;

                                        $("#students_all").html(rows + tfoot);

                                        const totalSummary = `
                                                            <tr>
                                                                <td colspan="4"></td>
                                                                <td style="padding-left: 20px">Paid: ${v_paid.toLocaleString('en-IN')}</td>
                                                                <td style="padding-left: 20px">Exem: ${v_exem.toLocaleString('en-IN')}</td>
                                                                <td style="padding-left: 20px">Total: ${v_fees.toLocaleString('en-IN')}</td>
                                                                <td style="padding-left: 20px">Bal: ${v_bal.toLocaleString('en-IN')}</td>
                                                            </tr>
                                                            `;

                                        // Append the summary row directly to the table
                                        $(".fees-table").html(totalSummary);
                                    }
                                });
                            }
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        dataLabels: {
                            position: 'top',
                        },
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetX: -5,
                    style: {
                        fontSize: '12px',
                        colors: ['#fff']
                    }
                },
                stroke: {
                    show: true,
                    width: 1,
                    colors: ['#fff']
                },
                tooltip: {
                    shared: true,
                    intersect: false
                },
                xaxis: {
                    categories: series,
                },
            };

            const chartContainer = document.getElementById("kschart");
            chartContainer.innerHTML = '';

            const chart = new ApexCharts(chartContainer, options);
            chart.render();
        }


        function paid1BarCharts(std, div, year) {

            $.ajax({
                url: "<?= base_url('index.php/FeesDashboard/balance_fee') ?>",
                method: "GET",
                dataType: "JSON",
                data: {
                    std: std,
                    div: div,
                    year: year
                },
                beforeSend: function() {
                    $("#exp_student").html(
                        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                    );
                },
                success: function(data) {
                    console.log(data);

                    let series = [];
                    let value1 = [];

                    data.forEach(element => {
                        series.push(element.balance_range);
                        value1.push(parseFloat(element.student_count));
                    });

                    generateKChart(series, value1);
                }
            });
        }
    </script>
</head>
<style>
    /* Ensure your CSS variables are defined somewhere in your CSS */

    .form-control {
        border-radius: 50px;
    }

    .btn {
        border-radius: 50px;
    }

    .navbar-brand {
        padding-left: 10px;
    }

    .card {
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    }
</style>

<body style="background: #e7e7e7; overflow-x:hidden;">
    <div class="main-content">
        <div class="page-content">

            <section>
                <nav class="navbar navbar-expand-sm navbar-light bg-light"
                    style="padding: 0px !important; background-color:#3d80a1 !important;">

                    <a class="navbar-brand text-white" href="#"><i class="fa fa-chart-line me-2"></i>Class Wise Fees</a>
                    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-center" id="collapsibleNavId">

                        <div class="d-flex my-2 my-lg-0">

                            <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                                <li class="nav-item ">

                                    <label style="color:#fff; padding-top: 12px ;">Std <span> :</span></label>

                                </li>
                                <li class="nav-item p-2" id="filters">


                                    <select class="form-control form-control-sm" name="std" id="standard">
                                        <option>Std</option>
                                        <?php foreach ($all_classes as $clas) { ?>
                                            <option <?= isset($class_filter) && $class_filter == $clas['stvfees_class'] ? 'selected' : ($clas['stvfees_class'] == 'I' ? 'selected' : '') ?>
                                                value="<?= $clas['stvfees_class'] ?>">
                                                <?= $clas['stvfees_class'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                </li>
                                <li class="nav-item ">

                                    <label style="color:#fff; padding-top: 12px ;">Div <span> :</span></label>

                                </li>
                                <li class="nav-item p-2">
                                    <select class="form-control form-control-sm" name="div" id="division">
                                        <option>Div</option>
                                        <option selected <?= $div_filter == 'A' ? 'selected' : '' ?> value="A">A</option>
                                        <option <?= $div_filter == 'B' ? 'selected' : '' ?> value="B">B</option>
                                        <option <?= $div_filter == 'C' ? 'selected' : '' ?> value="C">C</option>
                                    </select>

                                </li>
                                <li class="nav-item">

                                    <label style="color:#fff; padding-top: 12px ;">Year <span> :</span></label>

                                </li>
                                <!-- update by ansari kamran -->
                                <li class="nav-item p-2">
                                    <?php $selected_acdm = $academic_details['stvschool_acdm_year'];
                                    if ($filter_acdm != '')
                                        $selected_acdm = $filter_acdm;


                                    ?>
                                    <select class="form-control form-control-sm" name="acdm_year" id="acdm_year">
                                        <option value="">Select Year</option>

                                        <?php foreach ($academic_years as $acdm) {

                                            $select = '';
                                            if ($selected_acdm == $acdm['stvacdm_id']) {
                                                $select = 'selected';
                                            }
                                        ?>
                                            <option selected <?= $select ?> value="<?= $acdm['stvacdm_id'] ?>">
                                                <?= $acdm['stvacdm_year'] ?></option>
                                        <?php } ?>
                                    </select>

                                </li>
                                <!-- update by ansari kamran -->

                                <li class="nav-item p-2">

                                    <button type="button" onclick="send_data()"
                                        class="btn btn-sm btn-outline-light">View</button>
                                </li>


                            </ul>
                        </div>
                    </div>

                </nav>
            </section>

            <!-- New -->
            <section>
                <div class="row p-3">
                    <div class="col-md-2">

                        <div class="card">
                            <div class="row text-center pt-1">
                                <div class="col-sm-12 d-flex align-items-center justify-content-center">
                                    <div>
                                        <lord-icon src="https://cdn.lordicon.com/yrxnwkni.json" trigger="hover"
                                            colors="primary:#4be1ec,secondary:#cb5eee" style="width:70px;height:70px">
                                        </lord-icon>
                                    </div>
                                    <div>
                                        <h5>All student</h5>
                                        <h5 id="all_students"><span class="counter-value" data-target="197">--</span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card">
                            <div class="row text-center pt-1">
                                <div class="col-sm-12 d-flex align-items-center justify-content-center">
                                    <div>
                                        <lord-icon src="https://cdn.lordicon.com/amxnacxd.json" trigger="hover"
                                            colors="primary:#4be1ec,secondary:#cb5eee" style="width:70px;height:70px;">
                                        </lord-icon>
                                    </div>
                                    <div>
                                        <h5>RTE Student</h5>

                                        <h5 id="rte_student"><span class="counter-value" data-target="197">--</span>
                                        </h5>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card">
                            <div class="row text-center pt-1">

                                <div class="col-md-12 d-flex align-items-center justify-content-center">
                                    <div>
                                        <lord-icon src="https://cdn.lordicon.com/tbfrtmlu.json" trigger="hover"
                                            colors="primary:#4be1ec,secondary:#cb5eee" style="width:70px;height:70px;">
                                        </lord-icon>
                                    </div>
                                    <div>
                                        <h5>Total Fees</h5>
                                        <h5 id="total_fees">
                                            <span class="counter-value" data-target="634">--</span>
                                        </h5>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card">
                            <div class="row text-center pt-1">

                                <div class="col-md-12 d-flex align-items-center justify-content-center">
                                    <div>
                                        <lord-icon src="https://cdn.lordicon.com/tbfrtmlu.json" trigger="hover"
                                            colors="primary:#4be1ec,secondary:#cb5eee" style="width:70px;height:70px;">
                                        </lord-icon>
                                    </div>
                                    <div>
                                        <h5>Paid Fees</h5>

                                        <h5 id="paid_fees"><span class="counter-value" data-target="197">--</span></h5>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card">
                            <div class="row text-center pt-1">

                                <div class="col-md-12 d-flex align-items-center justify-content-center">
                                    <div>
                                        <lord-icon src="https://cdn.lordicon.com/tbfrtmlu.json" trigger="hover"
                                            colors="primary:#4be1ec,secondary:#cb5eee" style="width:70px;height:70px;">
                                        </lord-icon>
                                    </div>
                                    <div>
                                        <h5>Balanced</h5>

                                        <h5 id="balance_fees"><span class="counter-value" data-target="634">--</span>
                                        </h5>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card">
                            <div class="row text-center pt-1">
                                <div class="col-md-12 d-flex align-items-center justify-content-center">
                                    <div>
                                        <lord-icon src="https://cdn.lordicon.com/tbfrtmlu.json" trigger="hover"
                                            colors="primary:#4be1ec,secondary:#cb5eee" style="width:70px;height:70px;">
                                        </lord-icon>
                                    </div>
                                    <div>
                                        <h5 id="">Exemption</h5>
                                        <h5 id="exp_student"><span class="counter-value" data-target="512"> --</span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>


            <section>

                <div class="row p-3">
                    <div class="col-md-6">
                        <h4 class="fs-18 p-1 flex-grow-1 text-light b-900 d-flex"
                            style="font-family: 'Playfair Display', serif; background-color: #7b8185;">
                            <div class="col-sm-4">Payment Activity Overview</div>
                            <div class="col-sm-8">
                                <table>
                                    <thead class="fees-table">

                                    </thead>
                                </table>
                            </div>
                        </h4>

                        <div class="card" style="height: 450px !important;">
                            <div class="card-body" style="overflow-x: auto;overflow-y: auto; padding: 0px;">
                                <table id="simpletable" class="table table-sm table-striped table-bordered nowrap">
                                    <thead id="app">
                                        <tr style="background-color: #53769c;color: #fff;position: sticky; top: 0; z-index: 1;">
                                            <td>Id</td>
                                            <td>Name</td>
                                            <td>Rte</td>
                                            <td>Paid</td>
                                            <td>Exm</td>
                                            <td>Total</td>
                                            <td>Balance</td>
                                        </tr>
                                    </thead>
                                    <tbody id="students_all">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h4 class="fs-18 p-1 flex-grow-1 text-light b-900"
                            style="font-family: 'Playfair Display', serif;background-color: #7b8185;">
                            Class wise Fees
                        </h4>
                        <div class="card">
                            <div class="row text-center pt-1">
                                <div id="kschart">
                                </div>
                            </div>
                        </div>
                    </div>
            </section>

        </div>

        <!-- end row -->


    </div>

    <!-- JAVASCRIPT -->

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/simplebar/simplebar.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/node-waves/waves.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/feather-icons/feather.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/js/plugins.js"></script>

    <!-- apexcharts -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> -->
    <script src="<?php echo base_url(); ?>assets1/libs/apexcharts/apexcharts.min.js"></script>

    <!-- Vector map-->
    <script src="<?php echo base_url(); ?>assets1/libs/jsvectormap/js/jsvectormap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/jsvectormap/maps/world-merc.js"></script>

    <!-- Dashboard init -->
    <script src="<?php echo base_url(); ?>assets1/js/pages/dashboard.init.js"></script>

    <!-- App js -->
    <script src="<?php echo base_url(); ?>assets1/js/app.js"></script>
    <script>
        $("#simpletable").DataTable({
            "pageLength": 25,
            dom: "Bfrtip",

        });
    </script>

</body>

</html>
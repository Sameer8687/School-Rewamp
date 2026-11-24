<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Attendence Dashboard</title>
    <link rel="stylesheet" href="r.css" />
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets1/images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- plugin css -->

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.3.1/css/all.min.css" rel="stylesheet">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link href="<?php echo base_url(); ?>assets1/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>


    <!-- Layout config Js -->
    <script src="<?php echo base_url(); ?>assets1/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="<?php echo base_url(); ?>assets1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo base_url(); ?>assets1/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo base_url(); ?>assets1/css/app.min.css" rel="stylesheet" type="text/css" />

</head>

<style>
    * {
        /* width: 100%; */
        /* height: 10vh; */
    }

    .margin-left {
        margin-left: 100px !important;
        margin-top: -50px !important;
    }

    .bg-main {
        background-color: #3680a2;
    }

    .ihatekismatji {
        overflow-x: hidden;
        background: #e7e7e7;
        overflow-y: hidden;
    }

    @media only screen and (max-width: 600px) {
        .ihatekismatji {
            overflow-y: auto;
        }
    }
</style>

<body class="ihatekismatji">

    <div class=" main-content">
        <div class="page-content">
            <nav class="navbar navbar-expand-lg navbar-dark bg-body-tertiary" style="background-color: #3680a2; padding:2px;">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"><i class="fa fa-chart-line me-2"></i>Dashboard</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="card-header" style="color: #fff;">
                        <span><strong id="Month_text">Jan - </strong><?= date('y') ?></span>
                    </div>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="padding-left: 35%;">
                            <li class="nav-item" style="margin-top: 4px;">
                                <select type="" class="form-select form-select-sm" id="teacher" onchange="teacher()" aria-describedby="emailHelp">
                                    <?php
                                    foreach ($Teachers as $teacher) :
                                    ?>
                                        <option value="<?= $teacher['SPRIDEN_student_id']; ?>">
                                            <?= $teacher['name']; ?></option>
                                    <?php endforeach; ?>

                                </select>
                            </li>
                            <li class="nav-item mx-2" style="margin-top: 4px;">
                                <input type="date" class="form-control form-control-sm" id="daily_attendance" value="<?= $day = date('Y-m-d') ?>" aria-describedby="emailHelp" onchange="daily_attendance()">
                            </li>
                            <li class="nav-item mx-2" style="margin-top: 4px;">
                                <select type="" class="form-select form-select-sm" id="monthWise" onclick="monthWise()" aria-describedby="emailHelp">
                                    <?php $select = date('m'); ?>
                                    <option value="">Select Month</option>
                                    <option value="01" <?= $select == '01' ? 'Selected' : '' ?>>Jan</option>
                                    <option value="02" <?= $select == '02' ? 'Selected' : '' ?>>Feb</option>
                                    <option value="03" <?= $select == '03' ? 'Selected' : '' ?>>March</option>
                                    <option value="04" <?= $select == '04' ? 'Selected' : '' ?>>Apr</option>
                                    <option value="05" <?= $select == '05' ? 'Selected' : '' ?>>May</option>
                                    <option value="06" <?= $select == '06' ? 'Selected' : '' ?>>Jun</option>
                                    <option value="07" <?= $select == '07' ? 'Selected' : '' ?>>Jul</option>
                                    <option value="08" <?= $select == '08' ? 'Selected' : '' ?>>Aug</option>
                                    <option value="09" <?= $select == '09' ? 'Selected' : '' ?>>Sep</option>
                                    <option value="10" <?= $select == '10' ? 'Selected' : '' ?>>Oct</option>
                                    <option value="11" <?= $select == '11' ? 'Selected' : '' ?>>Nov</option>
                                    <option value="12" <?= $select == '12' ? 'Selected' : '' ?>>Dec</option>
                                </select>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#" id="reportLink"><i class="fa-solid fa-list-check"></i> Report</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- end page title -->
            <div class="container-fluid" style="height: 100vh;">
                <!-- radial chart start -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="row" style="margin-top: 0px;">
                            <div class="col-md-12">
                                <h4 class="fs-18 mt-1  p-1 flex-grow-1 text-light" style="font-family: 'Playfair Display', serif;background-color:#3680a2;">
                                    <i class="fa fa-user me-1"></i>Datewise Attendence
                                </h4>

                            </div>
                            <div class="col-md-4" onclick="chart_details('All')">
                                <div class="card shadow">
                                    <div class="card-body shadow-lg" style="margin-top:-8px;">

                                        <h5 class="text-muted fs-14 mt-1 mb-0">
                                            Total Student
                                            <h3 class="mb-0 fs-18">
                                                <span class="counter-value" data-target="197" id="tot_stu_stat">0</span>

                                            </h3>
                                            <div id="chart6" data-colors='["--in-red", "--in-light"]' class="apex-charts margin-left">

                                            </div>
                                        </h5>


                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" onclick="chart_details('P')">
                                <div class="card shadow">
                                    <div class="card-body shadow-lg" style="margin-top:-8px;">
                                        <h5 class="text-muted fs-14 mt-1 mb-0">
                                            Present Student
                                            <h3 class="mb-0 fs-18">
                                                <span class="counter-value" data-target="197" id="present_stud_stat">0</span>
                                            </h3>
                                            <div id="chart7" data-colors='["--in-red", "--in-light"]' class="apex-charts margin-left">
                                            </div>
                                        </h5>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" onclick="chart_details('A')">
                                <div class="card shadow">
                                    <div class="card-body shadow-lg" style="margin-top:-8px;">
                                        <h5 class="text-muted fs-14 mt-1 mb-0">
                                            Absent Student
                                            <h3 class="mb-0 fs-18">
                                                <span class="counter-value" data-target="197" id="absent_stud_stat">0</span>
                                            </h3>
                                            <div id="chart8" data-colors='["--in-primary", "--in-light"]' class="apex-charts margin-left">
                                            </div>
                                        </h5>

                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="row" style="margin-top: -22px;">
                            <div class="col-md-12">
                                <h4 class=" fs-18 mt-1  p-1 flex-grow-1 text-light" style="font-family: 'Playfair Display', serif;background-color:#3680a2;">
                                    Monthwise Attendance
                                </h4>
                                <div class="card justify-content-end">

                                    <!-- newly added -->

                                    <div id="loader" style="display: none; font-size:x-large;">
                                        <center> Please Wait... </center>
                                    </div>

                                    <!-- end added -->

                                    <div class="card-body shadow-lg" style="height: 180px !important;">
                                        <div id="chart10" data-colors='["--in-red", "--in-light"]' class="apex-charts"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- radial chart start -->


                    <!-- barchart start  -->
                    <!-- <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class=" fs-18 mt-1  p-1 flex-grow-1 text-light" style="font-family: 'Playfair Display', serif;background-color:#3680a2;">
                                    Students
                                </h4>
                                <div class="card justify-content-end">
                                    <div class="card-body shadow-lg" style="margin-top:-13px;">
                                        <div id="chart9" data-colors='["--in-red", "--in-light"]' class="apex-charts">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- barchart start  -->

                </div>
                <div class="row" id="kismatji-wala-card" style="margin-top: -17px; height:40vh !important;">
                    <div class="col-md-12">
                        <div class="card" style="overflow-y: scroll; height:35vh;">
                            <div class="card-body shadow-lg">
                                <table class="table table-sm table-striped table-bordered nowrap">
                                    <thead class="text-center text-light bg-main">
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Class</th>
                                            <th>Div</th>
                                            <th>Attendence</th>
                                            <th class="d-none" id="action_d">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody id=student_detail>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->


    </div>
    <!-- end row -->








    <!-- end row -->
    </div>
    <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    </div>
    <!-- JAVASCRIPT -->

    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/simplebar/simplebar.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/node-waves/waves.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/feather-icons/feather.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/js/plugins.js"></script>

    <!-- apexcharts -->
    <script src="<?php echo base_url(); ?>assets1/libs/apexcharts/apexcharts.min.js"></script>

    <!-- Vector map-->
    <script src="<?php echo base_url(); ?>assets1/libs/jsvectormap/js/jsvectormap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets1/libs/jsvectormap/maps/world-merc.js"></script>

    <!-- Dashboard init -->
    <script src="<?php echo base_url(); ?>assets1/js/pages/dashboard.init.js"></script>

    <!-- App js -->
    <script src="<?php echo base_url(); ?>assets1/js/app.js"></script>

</body>

<script>
    function updateReportLink() {
        let teacher_id = document.getElementById('teacher').value;
        let date = document.getElementById('daily_attendance').value;
        let month = document.getElementById('monthWise').value;
        let year = new Date(date).getFullYear();

        let reportLink = document.getElementById('reportLink');
        reportLink.href = `<?= base_url('index.php/Attend_DB/report_db') ?>?month=${month}&year=${year}&teacher_id=${teacher_id}`;
    }

    document.getElementById('teacher').addEventListener('change', updateReportLink);
    document.getElementById('daily_attendance').addEventListener('change', updateReportLink);
    document.getElementById('monthWise').addEventListener('change', updateReportLink);

    // Initialize the link on page load
    updateReportLink();
</script>


<script>
    function display6chart(data) {

        let total_student = data[0].Total_students
        document.getElementById('tot_stu_stat').innerText = total_student

        var options = {
            series: [total_student],
            colors: ['#8860EF'],
            chart: {
                type: 'radialBar',
                height: 150,
                offsetY: 1,
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    color: '#fff',
                    startAngle: -90,
                    endAngle: 90,
                    track: {
                        background: "#3680a2",
                        strokeWidth: '95%',
                        margin: 2, // margin is in pixels
                        dropShadow: {
                            enabled: true,
                            top: 2,
                            left: 0,
                            color: '#999',
                            opacity: 1,
                            blur: 2
                        }
                    },
                    dataLabels: {
                        name: {
                            show: false,

                        },
                        value: {
                            offsetY: -2,
                            fontSize: '22px',

                        }
                    }
                }
            },
            fill: {
                type: "gradient",
                gradient: {
                    shade: "dark",
                    type: "vertical",
                    gradientToColors: ["#87D4F9"],
                    stops: [0, 100]
                }
            },
            grid: {
                padding: {
                    top: -20
                }
            },

            labels: ['Average Results'],
        };
        document.querySelector("#chart6").innerHTML = ''
        var chart = new ApexCharts(document.querySelector("#chart6"), options);
        chart.render();

    }


    let paidBarChart = (teacher_id, date) => {

        $.ajax({
            url: "<?= base_url('index.php/Attend_DB/total_student') ?>",
            method: "GET",
            data: {
                teacher_id,
                date
            },
            dataType: "json",
            success: function(data) {
                display6chart(data)
            }
        })

    }


    ////////////////////////// Chart7 ///////////////////////////////////////////

    function display7chart(data) {
        let present_stud = data[0].present_student
        document.getElementById('present_stud_stat').innerText = present_stud
        var options = {
            series: [present_stud],
            chart: {
                type: 'radialBar',
                height: 150,
                offsetY: 1,
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -90,
                    endAngle: 90,
                    track: {
                        background: "#e7e7e7",
                        strokeWidth: '97%',
                        margin: 2, // margin is in pixels
                        dropShadow: {
                            enabled: true,
                            top: 2,
                            left: 0,
                            color: '#999',
                            opacity: 1,
                            blur: 2
                        }
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            offsetY: -2,
                            fontSize: '22px'
                        }
                    }
                }
            },
            grid: {
                padding: {
                    top: -20
                }
            },
            fill: {
                type: "gradient",
                gradient: {
                    shade: "dark",
                    type: "vertical",
                    gradientToColors: ["#87D4F9"],
                    stops: [0, 100]
                }
            },
            labels: ['Average Results'],
        };
        document.querySelector("#chart7").innerHTML = ''
        var chart = new ApexCharts(document.querySelector("#chart7"), options);
        chart.render();

    }


    let paidBar1Chart = (date, teacher_id) => {

        $.ajax({
            url: "<?= base_url('index.php/Attend_DB/present_student') ?>",
            method: "GET",
            dataType: "json",
            data: {
                date: date,
                teacher_id: teacher_id
            },
            success: function(data) {

                display7chart(data)
            }
        })

    }


    ////////////////////////////// Chart8 ////////////////////////////////////////////////////////

    function display8chart(data) {

        let Absent_stud = data[0].absent_student
        document.getElementById('absent_stud_stat').innerText = Absent_stud

        var options = {
            series: [Absent_stud],
            colors: ['#F62F63'],
            chart: {
                type: 'radialBar',
                height: 150,
                offsetY: 1,
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -90,
                    endAngle: 90,
                    track: {
                        background: "#e7e7e7",
                        strokeWidth: '97%',
                        margin: 2, // margin is in pixels
                        dropShadow: {
                            enabled: true,
                            top: 2,
                            left: 0,
                            color: '#999',
                            opacity: 1,
                            blur: 2
                        }
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            offsetY: -2,
                            fontSize: '22px'
                        }
                    }
                }
            },
            grid: {
                padding: {
                    top: -20
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    shadeIntensity: 0.4,
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 50, 53, 91]
                },
            },
            labels: ['Average Results'],
        };
        document.querySelector("#chart8").innerHTML = ''
        var chart = new ApexCharts(document.querySelector("#chart8"), options);
        chart.render();

    }


    let paidBar2Chart = (date, teacher_id) => {

        $.ajax({
            url: "<?= base_url('index.php/Attend_DB/Absent_student') ?>",
            method: "GET",
            dataType: "JSON",
            data: {
                date: date,
                teacher_id: teacher_id
            },
            success: function(data) {

                display8chart(data)
            }
        })

    }
</script>
<script>
    // function display9chart(series, val, val1, val2) {
    //     let cate = series;
    //     var options = {
    //         series: [{
    //             name: 'TEAM A',
    //             type: 'column',
    //             data: val
    //         }, {
    //             name: 'TEAM B',
    //             type: 'area',
    //             data: val1
    //         }, {
    //             name: 'TEAM C',
    //             type: 'line',
    //             data: val2
    //         }],
    //         chart: {
    //             height: 278,
    //             type: 'line',
    //             stacked: false,
    //         },
    //         stroke: {
    //             width: [0, 2, 5],
    //             curve: 'smooth'
    //         },
    //         plotOptions: {
    //             bar: {
    //                 columnWidth: '10%'
    //             }
    //         },

    //         fill: {
    //             opacity: [0.85, 0.25, 1],
    //             gradient: {
    //                 inverseColors: false,
    //                 shade: 'light',
    //                 type: "vertical",
    //                 opacityFrom: 0.85,
    //                 opacityTo: 0.55,
    //                 stops: [0, 100, 100, 100]
    //             }
    //         },
    //         // labels: ['01/01/2003', '02/01/2003', '03/01/2003', '04/01/2003', '05/01/2003', '06/01/2003', '07/01/2003',
    //         //     '08/01/2003', '09/01/2003', '10/01/2003', '11/01/2003'
    //         // ],
    //         markers: {
    //             size: 0
    //         },
    //         xaxis: {
    //             categories: cate,
    //         },
    //         yaxis: {
    //             title: {
    //                 text: 'Points',
    //             },
    //             min: 0
    //         },
    //         tooltip: {
    //             shared: true,
    //             intersect: false,
    //             y: {
    //                 formatter: function(y) {
    //                     if (typeof y !== "undefined") {
    //                         return y.toFixed(0) + " points";
    //                     }
    //                     return y;

    //                 }
    //             }
    //         }
    //     };

    //     document.querySelector("#chart9").innerHTML = ''

    //     var chart = new ApexCharts(document.querySelector("#chart9"), options);
    //     chart.render();
    // }

    // let Chart9 = (month, teacher_id) => {

    //     $.ajax({
    //         url: "<?= base_url('index.php/Attend_DB/monthwise') ?>",
    //         method: "GET",
    //         dataType: "JSON",
    //         data: {
    //             month: month,
    //             teacher_id: teacher_id
    //         },
    //         beforeSend: function() {
    //             $("#chart9").html(
    //                 '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
    //             );
    //         },
    //         success: function(data) {

    //             console.log(data);

    //             var series = [];
    //             let val = []
    //             let val1 = []
    //             let val2 = []
    //             // let absent = []

    //             data.forEach(element => {
    //                 series.push(element.Month);
    //                 val.push(parseFloat(element.Present));
    //                 val1.push(parseFloat(element.Absent));
    //                 val2.push(parseFloat(element.Total_stud));
    //             });

    //             display9chart(series, val, val1, val2)

    //         }
    //     })

    // }
    // Chart9();
</script>
<script>
    // newly added
    function display10chart(series, total, present, absent) {
        
        let cate = series;

        var options = {
            series: [{
                    name: 'All Students',
                    data: total
                },
                {
                    name: 'Present Students',
                    data: present
                },
                {
                    name: 'Absent Students',
                    data: absent
                }
            ],
            chart: {
                type: 'bar',
                height: 180,
                stacked: true,
                zoom: {
                    enabled: true
                },
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        // let point_at = config.dataPointIndex;
                        // chartValue(point_at, cate);
                        // let action = document.getElementById('action_d');
                        // let get = action.getAttribute('class');

                        // if (get == '') {
                        //     action.setAttribute('class', 'd-none');
                        // } else {
                        //     action.setAttribute('class', 'd-none');
                        // }
                        let selectedDate = config.w.config.xaxis.categories[config.dataPointIndex];
                let selectedSeries = config.seriesIndex;

                        
                if (selectedSeries === 0) {
                    chart10_details('All', selectedDate);
                } else if (selectedSeries === 1) {
                    chart10_details('P', selectedDate);
                } else if (selectedSeries === 2) {
                    chart10_details('A', selectedDate);
                }
                    }
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: 0,
                        offsetY: 0
                    }
                }
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 0,
                    dataLabels: {}
                }
            },
            xaxis: {
                categories: cate
            },
            legend: {
                position: 'right',
                offsetY: 40
            },
            fill: {
                opacity: 1
            }
        };

        document.querySelector("#chart10").innerHTML = '';
        var chart = new ApexCharts(document.querySelector("#chart10"), options);
        chart.render();
    }
// 
    function chart10_details(type, selectedDate) {
    var tc_id = $('#teacher').val();
    let action = document.getElementById('action_d');

    if (type == 'A') {
        action.classList.remove('d-none');
    } else {
        action.classList.add('d-none');
    }

    $.ajax({
        url: "<?= base_url('index.php/Attend_DB/getDailyStudentDetails') ?>",
        method: "GET",
        dataType: "json",
        data: {
            date: selectedDate,
            type: type,
            teacher_id: tc_id
        },
        beforeSend: function() {
            $("#student_detail").html('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');
        },
        success: function(data) {
            let str = '';
            let i = 0;
            data.forEach(element => {
                let bg = element.spratnd_status == 'A' ? 'danger' : 'info';
                str += `<tr>
                            <td class="text-center">${++i}</td>
                            <td class="text-center">${element.spratnd_date}</td>
                            <td class="text-center">${element.SPRIDEN_student_id}</td>
                            <td>${element.student_name}</td>
                            <td class="text-center">${element.spratnd_std}</td>
                            <td class="text-center">${element.spratnd_div}</td>
                            <td class="text-center"><span class="badge bg-${bg}">${element.spratnd_status}</span></td>
                            <td class="${type == 'A' ? '' : 'd-none'} text-center" id="btn_d">
                                <span class="text-danger"><a target="_blank" href="https://api.whatsapp.com/send?phone=+919834585882&text=Ab Matin"><i class="bx bxl-whatsapp me-1 pt-2"></i>Send Message</a></span>
                            </td>
                        </tr>`;
            });

            $("#student_detail").html(str);
        },
        error: function(error) {
            console.error("Error fetching data:", error);
            $("#student_detail").html('<tr><td colspan="8" class="text-center text-danger">Error loading data</td></tr>');
        }
    });
}
// 
    function Chart10(month, year, teacher_id) {

        document.getElementById('loader').style.display = 'block';
        document.getElementById('chart10').style.display = 'none';

        $.ajax({
            url: "<?= base_url('index.php/Attend_DB/DayData') ?>",
            method: "GET",
            dataType: "JSON",
            data: {
                month: month,
                year: year,
                teacher_id: teacher_id
            },
            success: function(data) {
                console.log("Received data:", data);

                var series = [],
                    total = [],
                    present = [],
                    absent = [];

                data.forEach(element => {
                    console.log("Element Day Data:", element);
                    series.push(element.spratnd_date);
                    total.push(parseFloat(element.total));
                    present.push(parseFloat(element.total_present));
                    absent.push(parseFloat(element.total_absent));
                });

                // Hide loader and show chart
                document.getElementById('loader').style.display = 'none';
                document.getElementById('chart10').style.display = 'block';

                display10chart(series, total, present, absent);
            },
            error: function(error) {
                console.error("Error fetching data:", error);

                // Hide loader in case of error
                document.getElementById('loader').style.display = 'none';
            }
        });
    }

    // end added


    let chartValue = (point_at, cate, teacher_id, month) => {

        document.getElementById('kismatji-wala-card')
        // let date = '2023-06-20';
        var tec_id = $('#teacher').val();
        let date = cate[point_at];
        // let teacher_ids = $("#teacher").val();
        let months = month;
        $.ajax({
            url: "<?= base_url('index.php/Attend_DB/DateWise') ?>",
            method: "GET",
            dataType: "json",
            data: {
                date: date,
                month: month,
                teacher_id: tec_id
            },
            beforeSend: function() {
                $("#chart6").html(
                    '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
                );
            },
            success: function(data) {

                let str = ''
                let i = 0
                let tc = 'class="text-center"'
                data.forEach(element => {
                    let bg = element.spratnd_status == 'A' ? 'danger' : 'info'

                    str += `<tr style="">
                            <td ${tc}>${++i}</td>
                         <td ${tc}>${element.SPRATND_date}</td>
                            <td ${tc}>${element.SPRIDEN_student_id}</td>
                            <td>${element.student_name}</td>
                            <td ${tc}>${element.spratnd_std}</td>
                             <td ${tc}>${element.spratnd_div}</td>
                             <td ${tc}><span class="badge bg-${bg}"> ${element.spratnd_status}</span></td>
                        </tr>`
                })

                $("#student_detail").html(str)

            }

        })
    }
</script>
<script>
    let daily_attendance = () => {
        let date = $('#daily_attendance').val();
        let teacher_id = $("#teacher").val();

        // newly added 24 july
        let year = new Date(date).getFullYear();
        let month = $('#monthWise').val();
        Chart10(month, year, teacher_id);
        // end added 24 july

        paidBarChart(teacher_id, date);
        paidBar1Chart(date, teacher_id);
        paidBar2Chart(date, teacher_id);


    }


    let teachers = () => {
        let teacher_id = $("#teacher").val();
        let month = $('#monthWise').val()

        // newly added 24 july
        let date = $('#daily_attendance').val();
        let year = new Date(date).getFullYear();

        // Chart9(month, teacher_id);
        Chart10(month, year, teacher_id);

        // newly added 24 july

        chartValue(month, teacher_id);
    }
    let teacher = () => {
        let teacher_id = $("#teacher").val();
        let date = $('#daily_attendance').val()

        paidBarChart(teacher_id, date);
        paidBar1Chart(date, teacher_id);
        paidBar2Chart(date, teacher_id);
        teachers();


    }
    teacher()
    daily_attendance();
    // X
    // let monthWise = () => {
    //     let month = $('#monthWise').val()
    //     let teacher_id = $("#teacher").val();


    //     Chart9(month, teacher_id);
    //     Chart10(month, teacher_id);
    // }

    // let teachers = () => {
    //     let teacher_id = $("#teacher").val();
    //     let month = $('#monthWise').val()


    //     Chart9(month, teacher_id);
    //     Chart10(month, teacher_id);

    // }
    // teachers()
    // monthWise();

    //    X

    let monthWise = () => {

        let month = $("#monthWise").val()
        let month_name = $("#monthWise option:selected").html()

        Chart10(month);

        $("#Month_text").text(month_name + ' - ')

        // let month = $('#monthWise').val()
        let teacher_id = $("#teacher").val();

        // newly added 24 july

        let date = $('#daily_attendance').val();
        let year = new Date(date).getFullYear();

        // Chart9(month, teacher_id);
        Chart10(month, year, teacher_id);

        // end added 24 july

        chartValue(month, teacher_id);

    }


    teachers()
    monthWise();

    let chart_details = (id) => {

        var tc_id = $('#teacher').val();
        let date = $("#daily_attendance").val()
        let type = id
        let action = document.getElementById('action_d')

        if (type == 'A') {

            action.classList.remove('d-none')
        } else {
            action.classList.add('d-none')
        }

        $.ajax({
            url: "<?= base_url('index.php/Attend_DB/daily_data') ?>",
            method: "GET",
            dataType: "json",
            data: {
                date: date,
                type: type,
                teacher_id: tc_id
            },
            beforeSend: function() {
                $("#chart6").html(
                    '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
                );
            },
            success: function(data) {

                let str = ''
                let i = 0
                let tc = 'class="text-center"'
                let td = document.getElementById('action_d')
                let get = td.getAttribute('class')
                let display = get == '' ? '' : 'd-none'
                data.forEach(element => {

                    let bg = element.spratnd_status == 'A' ? 'danger' : 'info'

                    str += `<tr>
                            <td ${tc}>${++i}</td>
                         <td ${tc}>${element.spratnd_date}</td>
                            <td ${tc}>${element.SPRIDEN_student_id}</td>
                            <td>${element.student_name}</td>
                            <td ${tc}>${element.spratnd_std}</td>
                             <td ${tc}>${element.spratnd_div}</td>
                             <td ${tc}><span class="badge bg-${bg}"> ${element.spratnd_status}</span></td>
                            <td class="${display} text-center" id="btn_d">
                                <span class="text-danger "><a target="_blank" href="https://api.whatsapp.com/send?phone=+919834585882&text=Ab Matin"><i class="bx bxl-whatsapp me-1 pt-2"></i>Send Message</a></span>
                             </td>

                        </tr>`
                })

                $("#student_detail").html(str)
                document.getElementById('kismatji-wala-card')
            }
        })


    }
</script>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>classdb_css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
</head>

<body style="background-color: #e7e7e7e7;">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand text-light" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="border: none"></span>
            </button>
            <div class="collapse navbar-collapse text-light" id="navbarSupportedContent">
                <ul class="navbar-nav me-right mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="middle-box">
        <div class="left">
            <div class="left-top">
                <!-- 1 -->
                <div class="card stats">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="select-year">
                                    <select class="form-control" name="year" id="year" onchange="run()">
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
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="select-year">
                                    <select class="form-control" name="exam" id="exam_id_value" onchange="run()">
                                        <option value="">Select Exam</option>

                                        <?php
                                        foreach ($Exams as $exam) :
                                        ?>
                                        <option value="<?= $exam['stvexam_id']; ?>">
                                            <?= $exam['stvexam_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="select-grade">
                                    <select name="subject_id" id="subject_id" onchange="run()" class="form-control">
                                        <option value="">Select Subject</option>
                                        <?php

                                        foreach ($Subs as $sub) :
                                        ?>
                                        <option value="<?= $sub['stvsubj_id']; ?>" data-value="">
                                            <?= $sub['stvsubj_subject']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 2 -->
                <div class="card stats" style="background-color: aliceblue;">
                    <div class="card-body" style="display: flex;">
                        <div class="img"><img src="<?php echo base_url(); ?>classdb_css/middles.png" alt=""
                                style="width: 125px" /></div>
                        <div class="content">


                            <h5 style="color: #8EAC50;">Students Count <span></span><span id="Name"></span></h5>
                            <h4 style="margin-top: 5%; color:#7A9D54;">
                                <?= $Totals['Total_students']; ?>
                            </h4>
                        </div>
                        <div class="graph"></div>
                    </div>
                </div>
                <!-- 3 -->
                <div class="card stats" style="background-color: antiquewhite;">
                    <div class="card-body">
                        <div class="content">
                            <h5 style="color: #EF6262;">Students Attendace</h5>
                            <h4 style="color: #ED7B7B"><?= $Attends['percentage']; ?> %</h4>

                        </div>
                        <div class="graph" id="stat"></div>
                    </div>
                </div>
            </div>
            <div class="card averages">
                <!-- <div class="row"> -->
                <div class="col-md-1"></div>
                <div class="col-md-6 d-flex">
                    <h5>
                        <span style="color: #ff4f5a;">Student Attendace </span><span>:</span><span style="color: tomato"
                            id="per"><?= $Attends['percentage']; ?>%</span>
                    </h5>
                    <span class="material-symbols-outlined" style="color: tomato">
                        bid_landscape
                    </span>
                </div>
                <div class="col-md-5 d-flex">
                    <h5 id="exam_average">
                        <span style="color: #3680a2">Exam Average</span><span>:</span><span style="color: #3680a2"
                            id="exam_per">0.00%</span>
                    </h5>

                    <span class="material-symbols-outlined" style="color: #3680a2">
                        bid_landscape
                    </span>
                </div>
                <!-- </div> -->
            </div>
            <div class="left-middle">
                <!-- 1 -->
                <div class="card r-wali-eight">
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="card" style="margin-top: -6%;background-color: #166399;color: #fff;">
                                <h5>Class Average Score</h5>
                            </div>
                            <div class="col-md-4" style="margin-top: 5%;">
                                <div class="" id="chart2"></div>
                            </div>
                            <div class="col-md-4" style="margin-top: 5%;">
                                <div class="" id="chart3"></div>
                            </div>
                            <div class="col-md-4" style="margin-top: 5%;">
                                <div class="" id="chart4"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 2 -->
                <div class="card r-wali-eight-1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="select-grade">
                                <select name="student" id="student_id_value" onchange="standard(this)"
                                    class="form-control form-control-sm">
                                    <option value="">Select Students</option>
                                    <?php
                                    foreach ($users as $user) :
                                    ?>
                                    <option value="<?= $user['id']; ?>" data-value="<?= $user['SPRIDEN_student_id'] ?>">
                                        <?= $user['student_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="z-wali-eight"></div>
                </div>
            </div>
            <div class="left-last">
                <div class="card " style="height: 245px" id="last-chart">
                </div>
            </div>
        </div>
        <div class="right">
            <div class="right-top">
                <div class="row1" style="margin-top: -4px">
                    <div class="col-sm-6 mb-3 mb-sm-0" id="Class-top">
                        <div class="card" style="background-color:#3b81ff;">
                            <div class="card-body" style="margin-bottom: -13px;">
                                <h6 class="card-title" style="margin-top: -13px;">
                                    <img src="<?php echo base_url(); ?>classdb_css/B-profile-icon-png.png" alt=""
                                        style="width: 40px" /><span class="percent text-light">85.59%</span>
                                    <p class="card-text text-light">Shaikh AB.Azim</p>
                                </h6>
                                <p class="card-text"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6" id="stud_top_attend">
                        <div class="card" style="background-color:#ff4f5a;">
                            <div class="card-body" style="margin-bottom: -13px">
                                <h6 class="card-title" style="margin-top: -13px">
                                    <img src="<?php echo base_url(); ?>classdb_css/B-profile-icon-png.png" alt=""
                                        style="width: 40px" /> <span class="percent text-light">87.59%</span>
                                    <p class="card-text text-light">Shaikh AB.Azim</p>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="best">
                    <div>
                        <span>Best in Marks</span>
                    </div>
                    <div>
                        <span>Best in Attendace</span>
                    </div>
                </div>
                <div class="row2">
                    <div class="col-sm-6 mb-3 mb-sm-0" id="improvement">
                        <div class="card" style="background-color: #fed264;">
                            <div class="card-body" style="margin-bottom: -13px">
                                <h6 class="card-title" style="margin-top: -13px">
                                    <img src="<?php echo base_url(); ?>classdb_css/B-profile-icon-png.png" alt=""
                                        style="width: 40px" /><span class="percent text-light">87.59%</span>
                                    <p class="card-text text-light">Shaikh AB.Azim</p>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6" id="attends">
                        <div class="card" style="background-color: #9c7ab6;">
                            <div class="card-body" style="margin-bottom: -13px">
                                <h6 class="card-title" style="margin-top: -13px">
                                    <img src="<?php echo base_url(); ?>classdb_css/B-profile-icon-png.png" alt=""
                                        style="width: 40px" /><span class="percent text-light">75.59%</span>
                                    <p class="card-text text-light">Shaikh AB.Azim</p>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="most">
                    <div>
                        <span>Most Improved in Marks</span>
                    </div>
                    <div>
                        <span>Most Improved in Attendace</span>
                    </div>
                </div>

            </div>

            <div class="right-mid">

                <div class="row row-cols-1 row-cols-md-3 g-4" id="top-student" style="margin-top: -4%;"></div>
            </div>
            <div class="right-last">
                <div class="card right-card" style="height: 24vh;">
                    <div class="card-body" style="margin-top: -29px;margin-bottom: -4%;">
                        <div class="row mt-3">
                            <div class="card" style="background-color: #ff4f5a;color: #fff;">
                                <h5>Class Low Score</h5>
                            </div>
                            <div class="col-md-4">
                                <div class="" id="chart7"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="" id="chart8"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="" id="chart9"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
let run = () => {

    let year = document.getElementById('year').value
    let ex_id = document.getElementById('exam_id_value').value
    let sub_id = document.getElementById('subject_id').value;

    $.ajax({
        url: "<?= base_url('index.php/Class_DB/top_stud') ?>",
        method: "GET",
        data: {
            year: year,
            exam: ex_id,
            sub: sub_id,
        },
        dataType: "json",
        success: function(data) {
            let str = ""
            let rowIdx = 0
            let color = ['#166399', '#9c7ab6', '#a22d59']
            data['subject_data'].forEach(element => {
                str += ` <div class="col-size" >
                        <div class="card h-85" style="width: 105%; background-color: ${color[rowIdx++]}">
                            <img src="<?php echo base_url(); ?>classdb_css/B-profile-icon-png.png" class="card-img-top"
                                alt="..." />
                            <div class="card-body">
                                <h6 class="card-title text-light">${element.percentage} <span>VI-A</span></h6>
                                <p class="card-text text-light">Name <span>:${element.name}</span></p>
                            </div>
                            <div class="card-footer">
                                <small class="text-body-secondary"></small>
                            </div>
                        </div>
                    </div>`

            })

            $("#top-student").html(str)

            let str1 = `<div class="card" style="background-color:#3b81ff;">
                            <div class="card-body" style="margin-bottom: -13px;">
                                <h6 class="card-title" style="margin-top: -13px;">
                                    <img src="<?php echo base_url(); ?>classdb_css/B-profile-icon-png.png" alt=""
                                        style="width: 40px" /><span class="percent text-light">${data['ClassTop_one'][0].percentage}%</span>
                                    <p class="card-text text-light">${data['ClassTop_one'][0].name}</p>
                                </h6>
                                <p class="card-text"></p>
                            </div>
                        </div>`

            $("#Class-top").html(str1)

            let str2 = `<div class="card" style="background-color:#ff4f5a;">
                            <div class="card-body" style="margin-bottom: -13px">
                                <h6 class="card-title" style="margin-top: -13px">
                                    <img src="<?php echo base_url(); ?>classdb_css/B-profile-icon-png.png" alt=""
                                        style="width: 40px" /> <span class="percent text-light">${data['stud_top_attend'][0].attend_percentage}%</span>
                                    <p class="card-text text-light">${data['stud_top_attend'][0].student_name}</p>
                                </h6>
                            </div>
                        </div>`

            $("#stud_top_attend").html(str2)


            if (data['average_score'].length > 0) {
                let subject1 = data['average_score'][0].subject_name
                let per1 = data['average_score'][0].percentage
                let total_marks_sub1 = data['average_score'][0].total_marks
                let passing_marks_sub1 = data['average_score'][0].passing_marks
                let obtaine_marks1 = data['average_score'][0].obtained_marks


                let subject2 = data['average_score'][1].subject_name
                let per2 = data['average_score'][1].percentage
                let total_marks_sub2 = data['average_score'][1].total_marks
                let passing_marks_sub2 = data['average_score'][1].passing_marks
                let obtaine_marks2 = data['average_score'][1].obtained_marks

                let subject3 = data['average_score'][2].subject_name
                let per3 = data['average_score'][2].percentage
                let total_marks_sub3 = data['average_score'][2].total_marks
                let passing_marks_sub3 = data['average_score'][2].passing_marks
                let obtaine_marks3 = data['average_score'][2].obtained_marks

                displaysub1(subject1, per1)
                displaysub2(subject2, per2)
                displaysub3(subject3, per3)

            }
            // ----------------------------------------------------------------------------------------------------------------------------------

            if (data['weak_subject'].length > 0) {

                let subject1 = data['weak_subject'][0].subject_name
                let total_marks_sub1 = data['weak_subject'][0].total_marks
                let passing_marks_sub1 = data['weak_subject'][0].passing_marks
                let obtaine_marks1 = data['weak_subject'][0].obtained_marks


                let subject2 = data['weak_subject'][1].subject_name
                let total_marks_sub2 = data['weak_subject'][1].total_marks
                let passing_marks_sub2 = data['weak_subject'][1].passing_marks
                let obtaine_marks2 = data['weak_subject'][1].obtained_marks

                let subject3 = data['weak_subject'][2].subject_name
                let total_marks_sub3 = data['weak_subject'][2].total_marks
                let passing_marks_sub3 = data['weak_subject'][2].passing_marks
                let obtaine_marks3 = data['weak_subject'][2].obtained_marks

                displaysub4(subject1, obtaine_marks1, total_marks_sub1)
                displaysub5(subject2, obtaine_marks2, total_marks_sub2)
                displaysub6(subject3, obtaine_marks3, total_marks_sub3)


            }
            if (data['all_submark'].length > 0) {
                var series = [];
                let total_marks = [];
                let obtain_marks = [];
                let passing_marks = [];


                data['all_submark'].forEach(element => {
                    series.push(element.name);
                    passing_marks.push(parseFloat(element.passing_marks));
                    total_marks.push(parseFloat(element.total_marks));
                    obtain_marks.push(parseFloat(element.obtain_marks));
                });

                displayLastchart(series, passing_marks, total_marks, obtain_marks)
            }

            if (data['Subject_chart'].length > 0) {
                var series = [];
                let subject_marks = [];



                data['Subject_chart'].forEach(element => {
                    series.push(element.subject_name);
                    subject_marks.push(parseFloat(element.total_obtained_marks));
                });

                displaySubjectchart(series, subject_marks)
            }
        }
    })




}
</script>

<script>
let displaysub1 = (subject, per) => {

    var options = {
        series: [per],
        chart: {
            height: 170,
            type: "radialBar",
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    size: "70%",
                },
            },
        },
        labels: [subject],
    };
    document.getElementById('chart2').innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#chart2"), options);
    chart.render();

}

let displaysub2 = (subject, per) => {

    var options = {
        series: [per],
        chart: {
            height: 170,
            type: "radialBar",
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    size: "70%",
                },
            },
        },
        labels: [subject],
    };
    document.getElementById('chart3').innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#chart3"), options);
    chart.render();

}
let displaysub3 = (subject, per) => {

    var options = {
        series: [per],
        chart: {
            height: 170,
            type: "radialBar",
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    size: "70%",
                },
            },
        },
        labels: [subject],
    };
    document.getElementById('chart4').innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#chart4"), options);
    chart.render();
}
</script>

<script>
let displaysub4 = (subject, obtain, total) => {

    let per = parseFloat(obtain / total * 100);
    var options = {
        series: [per],
        chart: {
            height: 150,
            type: "radialBar",
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    size: "70%",


                },
            },
        },
        value: {
            show: true,
            color: 'red', // Change this to the color you want
        },
        labels: [subject],
    };
    document.getElementById('chart7').innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#chart7"), options);
    chart.render();

}
let displaysub5 = (subject, obtain, total) => {
    let per = parseFloat(obtain / total * 100);
    var options = {
        series: [per],
        chart: {
            height: 150,
            type: "radialBar",
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    size: "70%",
                },
            },
        },
        labels: [subject],
    };
    document.getElementById('chart8').innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#chart8"), options);
    chart.render();

}

let displaysub6 = (subject, obtain, total) => {
    let per = parseFloat(obtain / total * 100);
    var options = {
        series: [per],
        chart: {
            height: 150,
            type: "radialBar",
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    size: "70%",
                },
            },
        },
        labels: [subject],
    };
    document.getElementById('chart9').innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#chart9"), options);
    chart.render();

}
</script>


<script>
let last_con = document.querySelector("#last-chart");

function displayLastchart(students, passing_marks, total_marks, obtain_marks) {
    var options = {
        series: [{
            name: 'Obtained',
            type: 'column',
            data: obtain_marks
        }, {
            name: 'Total',
            type: 'area',
            data: total_marks
        }, {
            name: 'Passing',
            type: 'line',
            data: passing_marks
        }],
        chart: {
            height: last_con.offsetHeight - 22,
            type: 'line',
            stacked: false,
        },
        stroke: {
            width: [0, 2, 5],
            curve: 'smooth'
        },
        plotOptions: {
            bar: {
                columnWidth: '50%'
            }
        },

        fill: {
            opacity: [0.85, 0.25, 1],
            gradient: {
                inverseColors: false,
                shade: 'light',
                type: "vertical",
                opacityFrom: 0.85,
                opacityTo: 0.55,
                stops: [0, 100, 100, 100]
            }
        },
        labels: students,
        markers: {
            size: 0
        },
        xaxis: {
            type: 'category'
        },
        yaxis: {
            title: {
                text: 'Marks',
            },
            min: 0
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function(y) {
                    if (typeof y !== "undefined") {
                        return y.toFixed(0) + " Marks";
                    }
                    return y;

                }
            }
        }
    };
    document.getElementById('last-chart').innerHTML = ""
    var chart = new ApexCharts(document.querySelector("#last-chart"), options);
    chart.render();
}
</script>

<script>
let top_con = document.querySelector(".r-wali-eight-1");

function displaySubjectchart(series, subject_marks) {
    var options = {
        series: [
            // {
            //         name: "Income",
            //         type: "column",
            //         data: [1.4, 2, 2.5, 1.5, 2.5],
            //     },
            {
                name: "Marks",
                type: "column",
                data: subject_marks,
            },
            // {
            //     name: "Revenue",
            //     type: "line",
            //     data: [20, 29, 37, 36, 44],
            // },
        ],
        chart: {
            height: [top_con.offsetHeight - 45],
            type: "line",
            stacked: false,
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: [1, 1, 4],
        },
        title: {
            offsetX: 110,
        },
        xaxis: {
            categories: series,
        },
        yaxis: [{
                axisTicks: {
                    show: true,
                },
                axisBorder: {
                    show: true,
                    color: "#008FFB",
                },
                labels: {
                    style: {
                        colors: "#008FFB",
                    },
                },
                title: {
                    // text: "Income (thousand crores)",
                    // style: {
                    //     color: "#008FFB",
                    // },
                },
                tooltip: {
                    enabled: true,
                },
            },
            {
                seriesName: "Income",
                opposite: true,
                axisTicks: {
                    show: true,
                },
                axisBorder: {
                    show: true,
                    color: "#00E396",
                },
                labels: {
                    style: {
                        colors: "#00E396",
                    },
                },
            },
            {
                seriesName: "Revenue",
                opposite: true,
                axisTicks: {
                    show: true,
                },
                axisBorder: {
                    show: true,
                    color: "#FEB019",
                },
                labels: {
                    style: {
                        colors: "#FEB019",
                    },
                },
                title: {
                    // text: "Revenue (thousand crores)",
                    // style: {
                    //     color: "#FEB019",
                    // },
                },
            },
        ],
        tooltip: {
            fixed: {
                enabled: true,
                position: "topLeft", // topRight, topLeft, bottomRight, bottomLeft
                offsetY: 30,
                offsetX: 60,
            },
        },
        legend: {
            horizontalAlign: "left",
            offsetX: 40,
        },
    };

    let chart1 = new ApexCharts(
        document.querySelector(".z-wali-eight"),
        options
    );
    chart1.render();
}
let standard = (element) => {
    let year = document.getElementById('year').value
    let ex_id = document.getElementById('exam_id_value').value
    let stud_id = element.value

    $.ajax({
        url: "<?= base_url('index.php/Class_DB/GetSems') ?>",
        method: "GET",
        data: {
            year: year,
            exam: ex_id,
            id: stud_id
        },
        dataType: "json",
        success: function(data) {
            let str3 = `<div class="card" style="background-color: #fed264;">
                            <div class="card-body" style="margin-bottom: -13px">
                                <h6 class="card-title" style="margin-top: -13px">
                                    <img src="<?php echo base_url(); ?>classdb_css/B-profile-icon-png.png" alt=""
                                        style="width: 40px" /><span class="percent text-light">${data['improvement'].improvement_status}</span>
                                    <p class="card-text text-light">${data['improvement'].name}</p>
                                </h6>
                            </div>
                        </div>`
            $("#improvement").html(str3)

            let str4 = `<div class="card" style="background-color: #9c7ab6;">
                            <div class="card-body" style="margin-bottom: -13px">
                                <h6 class="card-title" style="margin-top: -13px">
                                    <img src="<?php echo base_url(); ?>classdb_css/B-profile-icon-png.png" alt="" style="width: 40px" /><span class="percent text-light">${data['attends'].percentage}%</span>
                                    <p class="card-text text-light">${data['attends'].name}</p>
                                </h6>
                            </div>
                        </div>`
            $("#attends").html(str4)
            if (data['exam_average']) {

                let exam_average = data['exam_average'].percentage

                console.log(exam_average);
                $("#exam_per").text(exam_average + '%')

            }

            // let str5 =
            //     `<span style="color: #3680a2">Exam Average</span><span>:</span><span style="color: #3680a2" id="exam_per">${data['exam_average'].percentage}%</span>`
            // $("exam_average").html(str5)

            let series = []
            let marks = []
            let total_marks = []
            let passing_marks = []

            // let subject1 = data['GetSems'][0].subject_name
            // let per1 = data['GetSems'][0].percentage
            // let total_marks_sub1 = data['GetSems'][0].total_marks
            // let passing_marks_sub1 = data['GetSems'][0].passing_marks
            // let obtaine_marks1 = data['GetSems'][0].obtained_marks

            if (data['GetSems'].length > 0) {
                data['GetSems'].forEach((item) => {
                    series.push(item.stvsubj_subject)
                    marks.push(parseInt(item.marks))
                    total_marks.push(parseInt(item.Total_marks))
                    passing_marks.push(parseInt(item.passing_marks))
                });
            }

            console.log(series);
            console.log(marks);
            console.log(total_marks);
            console.log(passing_marks);



            displaySubjectchart(series, marks, total_marks, passing_marks)
        }
    })
}

function displaySub_studchart(series, marks, total_marks, passing_marks) {
    var options = {
        series: [{
                name: "Income",
                type: "column",
                data: passing_marks,
            },
            {
                name: "Marks",
                type: "column",
                data: marks,
            },
            {
                name: "Revenue",
                type: "line",
                data: total_marks,
            },
        ],
        chart: {
            height: [top_con.offsetHeight - 50],
            type: "line",
            stacked: false,
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: [1, 1, 4],
        },
        title: {
            offsetX: 110,
        },
        xaxis: {
            categories: series,
        },
        yaxis: [{
                axisTicks: {
                    show: true,
                },
                axisBorder: {
                    show: true,
                    color: "#008FFB",
                },
                labels: {
                    style: {
                        colors: "#008FFB",
                    },
                },
                title: {
                    text: "Income (thousand crores)",
                    style: {
                        color: "#008FFB",
                    },
                },
                tooltip: {
                    enabled: true,
                },
            },
            {
                seriesName: "Income",
                opposite: true,
                axisTicks: {
                    show: true,
                },
                axisBorder: {
                    show: true,
                    color: "#00E396",
                },
                labels: {
                    style: {
                        colors: "#00E396",
                    },
                },
            },
            {
                seriesName: "Revenue",
                opposite: true,
                axisTicks: {
                    show: true,
                },
                axisBorder: {
                    show: true,
                    color: "#FEB019",
                },
                labels: {
                    style: {
                        colors: "#FEB019",
                    },
                },
                title: {
                    text: "Revenue (thousand crores)",
                    style: {
                        color: "#FEB019",
                    },
                },
            },
        ],
        tooltip: {
            fixed: {
                enabled: true,
                position: "topLeft", // topRight, topLeft, bottomRight, bottomLeft
                offsetY: 30,
                offsetX: 60,
            },
        },
        legend: {
            horizontalAlign: "left",
            offsetX: 40,
        },
    };

    let chart1 = new ApexCharts(
        document.querySelector(".z-wali-eight"),
        options
    );
    chart1.render();
}
</script>


</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Dashboard_css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand text-light" href="#"><i class="fa-solid fa-chart-column"></i>Result Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="border: none"></span>
            </button>
            <div class="collapse navbar-collapse text-light" id="navbarSupportedContent">
                <ul class="navbar-nav me-right mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active text-light" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="#">Charts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="#">Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="#">Settings</a>
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
                                    <select class="form-control" name="exam" id="exam" onchange="run()">
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
                                    <select name="student" id="student_id_value" onchange="run()" class="form-control">
                                        <option value="">Select Students</option>
                                        <?php
                                        foreach ($users as $user) :
                                        ?>
                                        <option value="<?= $user['id']; ?>"
                                            data-value="<?= $user['SPRIDEN_student_id'] ?>">
                                            <?= $user['student_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 2 -->
                <div class="card stats">
                    <div class="card-body">
                        <div class="content" style="display: grid;">
                            <h7 style="font-weight: 700;">Students Name <span>:</span><span id="Name"></span></h7>
                            <h7 style="margin-top: 5%; font-weight:700;">
                                Student ID <span>:</span><span id="Student_Id"></span>
                            </h7>
                        </div>
                        <div class="graph"></div>
                    </div>
                </div>
                <!-- 3 -->
                <div class="card stats">
                    <div class="card-body">
                        <div class="content stud d-flex">
                            <h7 style="font-weight: 700;">Students Attendace</h7>
                            <div class="graph" id="stat"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card Aver-card ">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-6 d-flex">
                        <h5>
                            Student Attendace <span>:</span><span style="color: tomato" id="per">0.00%</span>
                        </h5>
                        <span class="material-symbols-outlined" style="color: tomato">
                            bid_landscape
                        </span>
                    </div>
                    <div class="col-md-5 d-flex">
                        <h5>
                            Exam Average <span>:</span><span style="color: #3680a2" id="exam_per">0.00%</span>
                        </h5>

                        <span class="material-symbols-outlined" style="color: #3680a2">
                            bid_landscape
                        </span>
                    </div>
                </div>
            </div>
            <div class="left-middle">
                <!-- 1 -->
                <div class="card r-wali-eight">
                    <div class="card-body" style="padding: 0px;">
                        <div class="Avs text-light" style="background-color: #3680a2">
                            <h7 style="height: 4vh"> Weak Subject </h7>
                        </div>
                        <!-- <div id="chart1"></div> -->
                        <div class="row mt-3">
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
                <!-- 2 -->
                <div class="card r-wali-eight">
                    <div class="card-body" style="padding: 0px;">
                        <div class="select-year" style="display: flex">
                            <select class="form-control form-control-sm" name="exam_1" id="exam_1" onchange="GetSem()">
                                <option value="">Select Exam</option>

                                <?php
                                foreach ($Exams as $exam) :
                                ?>
                                <option value="<?= $exam['stvexam_id']; ?>">
                                    <?= $exam['stvexam_name']; ?></option>
                                <?php endforeach; ?>
                            </select>


                            <select class="form-control form-control-sm" name="exam_2" id="exam_2" onchange="GetSem()">
                                <option value="">Select Exam</option>

                                <?php
                                foreach ($Exams as $exam) :
                                ?>
                                <option value="<?= $exam['stvexam_id']; ?>">
                                    <?= $exam['stvexam_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div id="compare_semes"></div>
                    </div>
                </div>
            </div>
            <div class="left-last">
                <div class="card last-chart">
                    <div class="card-body" style="padding: 0px;">
                        <div class="col-md-6">
                            <div class="select-year">
                                <select class="form-control form-control-sm" name="exam" id="exam"
                                    onchange="standard()">
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
                        <div id="chart3">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="right">
            <div class="right-top">
                <div class="card right-card">
                    <div class="card-body">
                        <table class="table table-sm table-striped table-bordered nowrap">
                            <thead class="text-center">
                                <tr>
                                    <th style="background-color: #3680a2 !important; color: #fff !important">#</th>
                                    <th style="background-color: #3680a2 !important; color: #fff !important">Teacher
                                        Name</th>
                                    <th style="background-color: #3680a2 !important; color: #fff !important">sub</th>
                                    <th style="background-color: #3680a2 !important; color: #fff !important">Remark</th>
                                </tr>
                            </thead>

                            <tbody id="student_detail"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="right-last">
                <div class="card right-card">
                    <div class="card-body" style="padding: 0px;">
                        <div class="Avs text-light" style="background-color: #3680a2">
                            <h6 style="padding-left: 5px;">Average Score</h6>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="" id="chart4"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="" id="chart5"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="" id="chart6"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


<script>
let run = () => {
    document.getElementById("Name").innerHTML = document.querySelector('#student_id_value option:checked')
        .textContent
        .trim();
    document.getElementById("Student_Id").innerHTML = document.querySelector('#student_id_value option:checked')
        .getAttribute(
            "data-value");

    let year = document.getElementById('year').value
    let exam = document.getElementById('exam').value
    let stud_id = document.getElementById('student_id_value').value

    $.ajax({
        url: "<?= base_url('index.php/result_db/getData') ?>",
        method: "GET",
        data: {
            id: stud_id,
            year: year,
            exam: exam
        },
        dataType: "json",
        success: function(data) {

            if (data['subject_data'].length > 0) {

                let series = []
                let marks = []
                let total_marks = []
                let passing_marks = []

                data['subject_data'].forEach(element => {
                    series.push(element.stvsubj_subject)
                    marks.push(parseInt(element.marks))
                    total_marks.push(parseInt(element.Total_marks))
                    passing_marks.push(parseInt(element.passing_marks))

                })

                displayMarks(series, marks, total_marks, passing_marks)

            }



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
            //---------------------------------------------------------------------------------------------------------------
            if (data['weak_subject'].length > 0) {
                let subject1 = data['weak_subject'][0].subject_name
                let per1 = data['weak_subject'][0].percentage
                let total_marks_sub1 = data['weak_subject'][0].total_marks
                let passing_marks_sub1 = data['weak_subject'][0].passing_marks
                let obtaine_marks1 = data['weak_subject'][0].obtained_marks


                let subject2 = data['weak_subject'][1].subject_name
                let per2 = data['weak_subject'][1].percentage
                let total_marks_sub2 = data['weak_subject'][1].total_marks
                let passing_marks_sub2 = data['weak_subject'][1].passing_marks
                let obtaine_marks2 = data['weak_subject'][1].obtained_marks

                let subject3 = data['weak_subject'][2].subject_name
                let per3 = data['weak_subject'][2].percentage
                let total_marks_sub3 = data['weak_subject'][2].total_marks
                let passing_marks_sub3 = data['weak_subject'][2].passing_marks
                let obtaine_marks3 = data['weak_subject'][2].obtained_marks

                displaysub4(subject1, obtaine_marks1)
                displaysub5(subject2, obtaine_marks2)
                displaysub6(subject3, obtaine_marks3)


            }

            //---------------------------------------------------------------------------------------------------------------

            if (data['student_attendance'].length > 0) {
                let Days = data['student_attendance'][0].days
                let present_days = data['student_attendance'][0].total_present
                let absent_days = data['student_attendance'][0].total_absent
                let per = data['student_attendance'][0].percentage
                diplaystat(Days, present_days, absent_days)
                $("#per").text(per + ' %')

            }
            // if (data['weak_subject'].length > 0) {
            //     let mrk = []
            //     let subject_name = []

            //     data['weak_subject'].forEach(element => {

            //         mrk.push(parseInt(element.obtained_marks))
            //         subject_name.push(element.subject_name)
            //     })
            //     displayWeak(mrk, subject_name)

            // }

            if (data['remarks'].length > 0) {
                let str = ''
                let i = 0
                data['remarks'].forEach(element => {

                    str += ` <tr>
                            <td>${++i}</td>
                            <td> <button type="button hidden" class="btn" data-bs-toggle="modal" data-bs-target="#fivmodal">${element.teacher}</button></td>
                            <td>${element.stvsubj_subject}</td>
                            <td>${element.remarks}</td>
                        </tr>`
                })

                $("#student_detail").html(str)
            }
            if (data['exam_average']) {

                let exam_average = data['exam_average'].percentage

                console.log(exam_average);
                $("#exam_per").text(exam_average + '%')

            }
        }
    })

}

let standard = () => {
    let year = document.getElementById('year').value
    let exam = document.getElementById('exam').value
    let stud_id = document.getElementById('student_id_value').value
    $.ajax({
        url: "<?= base_url('index.php/result_db/getData') ?>",
        method: "GET",
        data: {
            id: stud_id,
            year: year,
            exam: exam
        },
        dataType: "json",
        success: function(data) {

            if (data['subject_data'].length > 0) {

                let series = []
                let marks = []
                let total_marks = []
                let passing_marks = []

                data['subject_data'].forEach(element => {
                    series.push(element.stvsubj_subject)
                    marks.push(parseInt(element.marks))
                    total_marks.push(parseInt(element.Total_marks))
                    passing_marks.push(parseInt(element.passing_marks))

                })

                displayMarks(series, marks, total_marks, passing_marks)
            }
        }
    })
}

let displayMarks = (series, marks, total_marks, passing_marks) => {

    var options = {
        series: [{
                name: "Marks",
                data: marks,
            },
            {
                name: "Total Marks",
                data: total_marks,
            },
            {
                name: "Passing Marks",
                data: passing_marks,
            },
        ],
        chart: {
            type: "bar",
            height: 180,
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "55%",
                endingShape: "rounded",
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 2,
            colors: ["transparent"],
        },
        title: {
            text: "Examinition Result",
        },
        xaxis: {
            categories: series,
        },
        yaxis: {
            title: {
                text: "",
            },
        },
        fill: {
            opacity: 1,
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return "" + val + " ";
                },
            },
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    height: 250
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    document.querySelector("#chart3").innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#chart3"), options);
    chart.render();
}

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
    document.getElementById('chart4').innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#chart4"), options);
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
    document.getElementById('chart5').innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#chart5"), options);
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
    document.getElementById('chart6').innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#chart6"), options);
    chart.render();

}
//-------------------------------------------------------------------------------------------------------

let displaysub4 = (subject, per) => {

    var options = {
        series: [per],
        chart: {
            height: 190,
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
    document.getElementById('chart7').innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#chart7"), options);
    chart.render();

}
let displaysub5 = (subject, per) => {

    var options = {
        series: [per],
        chart: {
            height: 190,
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
let displaysub6 = (subject, per) => {

    var options = {
        series: [per],
        chart: {
            height: 190,
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


//--------------------------------------------------------------------------------------------------------
let diplaystat = (Days, present_days, absent_days) => {
    let days = parseInt(Days)
    let present = parseInt(present_days)
    let absent = parseInt(absent_days)

    var options = {
        series: [days, present, absent],
        chart: {
            width: 250,
            type: 'donut',
        },
        labels: ['Days', 'Present', 'Absent'],
        plotOptions: {
            pie: {
                startAngle: -90,
                endAngle: 270
            }
        },
        dataLabels: {
            enabled: false
        },
        fill: {
            type: 'gradient',
        },
        legend: {
            formatter: function(val, opts) {
                return val + " - " + opts.w.globals.series[opts.seriesIndex]
            }
        },
        title: {
            text: ''
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 185
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    document.getElementById('stat').innerHTML = ''

    var chart = new ApexCharts(document.querySelector("#stat"), options);
    chart.render();

}
</script>
<script>
let GetSem = () => {

    let Student_id = document.getElementById('student_id_value').value
    let year = document.getElementById('year').value
    let exam_1 = document.getElementById('exam_1').value
    let exam_2 = document.getElementById('exam_2').value

    if (exam_1 && exam_2 != null) {
        $.ajax({
            url: "<?= base_url('index.php/result_db/getsems') ?>",
            method: "GET",
            data: {
                id: Student_id,
                year: year,
                exam_1: exam_1,
                exam_2: exam_2
            },
            dataType: 'json',
            success: function(data) {

                let series = [];
                let marks1 = [];
                let marks2 = [];



                data.forEach(element => {
                    series.push(element.subject);
                    marks1.push(parseInt(element.marks1))
                    marks2.push(parseInt(element.marks2))
                })

                const newmarks1 = marks1.map(item => (item === NaN ? '0' : item));
                const newmarks2 = marks2.map(item => (item === NaN ? '0' : item));

                Diplaysems(series, newmarks1, newmarks2)

            }

        })
    }
}


let Diplaysems = (series, marks1, marks2) => {

    var options = {
        series: [{
                name: 'Exam 1',
                data: marks1 // Numbers instead of strings (assumed to be percentages)
            },
            {
                name: 'Exam 2',
                data: marks2 // Numbers instead of strings (assumed to be percentages)
            }
        ],
        chart: {
            type: 'bar',
            height: 200,
            stacked: true,
        },
        colors: ['#008FFB', '#FF4560'],
        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: '80%',
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: 1,
            colors: ['#fff'],
        },
        grid: {
            xaxis: {
                lines: {
                    show: false,
                },
            },
        },
        tooltip: {
            shared: false,
            x: {
                formatter: function(val) {
                    return val + '%'; // Format x-axis value as a percentage
                },
            },
            y: {
                formatter: function(val) {
                    return val + '%'; // Format y-axis value as a percentage
                },
            },
        },
        title: {
            text: 'Marks Comparison',
        },
        xaxis: {
            title: {
                text: 'Percent',
            },
            labels: {
                formatter: function(val) {
                    return Math.abs(Math.round(val)) + '%';
                },
            },
            // min: 0, // Set the minimum value for x-axis
            // max: 100, // Set the maximum value for x-axis
            series,
        },
        yaxis: {
            title: {
                text: 'Subjects',
            },
            categories: series,
        },
    };

    document.getElementById('compare_semes').innerHTML = ''
    var chart = new ApexCharts(document.querySelector("#compare_semes"), options);
    chart.render();

}

// let displayWeak = (mrk, subject_name) => {

//     var options = {
//         series: mrk,
//         chart: {
//             width: 400,
//             type: "donut",
//         },
//         labels: subject_name,
//         plotOptions: {
//             pie: {
//                 startAngle: -90,
//                 endAngle: 270,
//             },
//         },
//         dataLabels: {
//             enabled: false,
//         },
//         fill: {
//             type: "gradient",
//         },
//         legend: {
//             formatter: function(val, opts) {
//                 return val + " - " + opts.w.globals.series[opts.seriesIndex];
//             },
//         },
//         title: {
//             text: "",
//         },
//         responsive: [{
//             breakpoint: 480,
//             options: {
//                 chart: {
//                     width: 200,
//                 },
//                 legend: {
//                     position: "bottom",
//                 },
//             },
//         }, ],
//     };

//     document.querySelector("#chart1").innerHTML = ''
//     var chart = new ApexCharts(document.querySelector("#chart1"), options);
//     chart.render();
// }
</script>

</html>
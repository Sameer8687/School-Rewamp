<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <style>
        .fc-event-title {
            font-size: 14px;
            font-weight: bold;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25em;
            margin: 0;
        }

        .fc .fc-button {
            padding: 0px 3px !important;
        }

        .fc .fc-daygrid-body-unbalanced .fc-daygrid-day-events {
            position: relative;
            min-height: 0.1em;
        }

        /* .fc-view-harness {
            display: none !important;
        }

        .fc-view-harness-active {
            display: none !important;
        } */


        /* .table>:not(caption)>*>* {
            padding: 0.1rem 1rem !important;
        } */

        body {
            height: 100vh;
            width: 98%;
            margin: auto;
            padding: 0px;
        }

        .noto-sans-jp-font {
            font-family: "Noto Sans JP", sans-serif;
            font-optical-sizing: auto;
            font-weight: 350;
            font-style: normal;
        }

        .navbar {
            background-color: white !important;
            width: 101%;
            padding-left: 0;
            padding-right: 0;
            margin-left: -10px;
            margin-right: -10px;
        }


        .navbar .container-fluid {
            padding-left: 0px !important;
            padding-right: 0px !important;
            width: 100% !important;
        }

        .navbar-toggler-icon {
            width: 0.4em;
        }

        .nav-link {
            display: block;
            padding: 0px !important;
            font-size: var(--bs-nav-link-font-size);
            font-weight: var(--bs-nav-link-font-weight);
            color: var(--bs-nav-link-color);
            text-decoration: none;
            background: 0 0;
            border: 0;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out;
        }

        .background {
            background: rgb(2, 0, 36);
            background: linear-gradient(270deg, rgba(2, 0, 36, 1) 0%, rgba(9, 9, 121, 1) 44%, rgba(0, 212, 255, 1) 100%);
            color: white
        }

        .search {
            width: 100%;
            position: relative;
            display: flex;
        }

        .searchTerm {
            width: 100%;
            border: rgb(2, 0, 36);
            border: 3px solid linear-gradient(360deg, rgba(2, 0, 36, 1) 100%, rgba(9, 9, 121, 1) 100%, rgba(0, 212, 255, 1) 100%);
            ;
            border-right: none;
            padding: 5px;
            height: 36px;
            border-radius: 5px 0 0 5px;
            outline: none;
            color: #00B4CC;
        }

        .searchTerm:focus {
            color: #00B4CC;
        }

        .searchButton {
            width: 40px;
            height: 36px;
            border: 1px solid #0c0cef;
            background-color: #0c0cef;
            text-align: center;
            color: #fff;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            font-size: 20px;
        }

        button.btn.btn-mini.text-white.ms-3 {
            padding: 0px 5px;
            height: 30px;
            font-size: smaller;
            background: #0D19FF;
            background: linear-gradient(90deg, #0D19FF, #0D19FF);
        }
    </style>
</head>

<body style="background-color:#f8f8f8">

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <img src="<?= base_url('images/logoNewDev.png') ?>" height=50>
            </a>
            <span class="ms-2 d-flex">
                <h4><big>Olive</big><span style="color:gray"> English School & Jr. College</span></h4>
            </span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <style>
                a#home-tab.nav-link.active.nav {
                    padding: 2px 2px;

                }

                a#profile-tab.nav-link.nav {
                    padding: 2px 2px;

                }

                a#messages-tab.nav-link.nav {
                    padding: 2px 2px;

                }
            </style>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link nav" href="#"></a>
                    </li>
                    <li class="nav-item pe-2">
                        <a class="nav-link nav" href="#">Link</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="row background">
        <div class="row mt-3 mb-3">
            <div class="col-md-8 col-sm-8 col-lg-8 ms-5 noto-sans-jp-font">
                <h2 class="noto-sans-jp-font">Hello, Abdul Ahad</h2>
                <p>Welcome to the Student Profile, your one-stop page for academics, personal information and more.</p>
                <div class="wrap">
                    <div class="search">
                        <input type="text" class="searchTerm" placeholder=" Please Type Name of Student" id="name" name="name" list="stud_name">
                        <datalist id="stud_name">
                            <?php foreach ($records as $rec) : ?>
                                <option style="color: #0c0cef;" data-id="<?= $rec['id'] ?>" value="<?= $rec['name'] . '  ' . $rec['std'] . ' - ' . $rec['class'] ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                        <button type="submit" class="searchButton">
                            <i class="fa fa-search"></i>
                        </button>

                    </div>
                    <div class="mt-3 d-flex">
                        <p class="">Quick Actions</p>
                        <button class="btn btn-mini text-white ms-3">View Fees Report</button>
                        <button class="btn btn-mini text-white ms-3">View Fees Report</button>
                        <button class="btn btn-mini text-white ms-3">View Fees Report</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-lg-4"></div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-8 ps-5 pt-4">
            <div class="card">
                <div class="row p-2" style="color:#060555;">
                    <div class="col-md-8">
                        <h5><span id="display_name">Student Details</span></h5>
                    </div>
                    <div class="col-md-2">Acad.Year</div>
                    <div class="col-md-2">
                        <select class="form-control form-control-sm" id="acdm" name="acdm">
                            <?php foreach ($academic_years as $acdm) : ?>
                                <option <?= $max_acdm['max_acdm'] == $acdm['stvacdm_id'] ? 'selected' : '' ?> value=<?= $acdm['stvacdm_id'] ?>> <?= $acdm['stvacdm_year'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- <div class="col-md-2">
                        <h5><span id="display_std"></span></h5>
                    </div>
                    <div class="col-md-2">
                        <h5><span id="display_roll_no"></span></h5>
                    </div> -->
                </div>
                <style>
                    a#home-tab.nav-link.active.page {
                        padding: 2px 0px;

                    }

                    a#profile-tab.nav-link.page {
                        padding: 2px 0px;

                    }

                    a#messages-tab.nav-link.page {
                        padding: 2px 0px;

                    }
                </style>
                <div class="row d-none" id="navtabcontent">
                    <div class="col-md-12 p-2">
                        <div class="col-md-12 p-2">
                            <ul class="nav nav-pills nav-fill" id="myTab" role="tablist" style="background-color:#f8f8f8;" cv>
                                <li class="nav-item">
                                    <a class="nav-link active page" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Class Summary</a>
                                </li>
                                <li class="nav-item page">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Academic Summary</a>
                                </li>
                                <li class="nav-item page">
                                    <a class="nav-link" id="messages-tab" data-toggle="tab" href="#messages" role="tab" aria-controls="messages" aria-selected="false">Financial Summary</a>
                                </li>
                            </ul>
                        </div>
                        <style>
                            .demo {
                                font-family: 'Noto Sans', sans-serif;
                            }

                            .table.tab tbody tr td {
                                padding: 0.1rem .5rem !important;

                            }

                            .table.tab thead tr th {
                                background-color: #f8f8f8 !important;
                            }

                            .table thead tr th {
                                background-color: #f8f8f8 !important;
                            }

                            th {
                                font-size: 13px;
                            }

                            td {
                                font-size: 13px;
                            }


                            @media only screen and (max-width:767px) {
                                .panel .panel-heading .title {
                                    text-align: center;
                                    margin: 0 0 10px;
                                }

                                .panel .panel-heading .btn_group {
                                    text-align: center;
                                }


                            }
                        </style>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active p-3" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                            <div class="panel-body table-responsive">
                                                <table class="table tab">
                                                    <thead>
                                                        <tr>
                                                            <th>Spriden ID</th>
                                                            <th>Student ID</th>
                                                            <th>Standard</th>
                                                            <th>Division</th>
                                                            <th>Roll No.</th>
                                                            <th>Class Teacher</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="class_tbody">

                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade p-3" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                                <div class="row" id="navtabinnercontent">
                                    <div class="col-md-12 p-2">
                                        <div class="col-md-12 p-2">
                                            <ul class="nav nav-pills nav-fill" id="myInnerTab" role="tablist" style="background-color:#f8f8f8;" cv>
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="quiz-tab" data-toggle="tab" href="#quiz" role="tab" aria-controls="quiz" aria-selected="true">Quiz Result</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="exam-tab" data-toggle="tab" href="#exam" role="tab" aria-controls="exam" aria-selected="false">Exam Result</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="remark-tab" data-toggle="tab" href="#remark" role="tab" aria-controls="remark" aria-selected="false">Student Remarks</a>
                                                </li>

                                            </ul>
                                        </div>
                                        <style>
                                            .demo {
                                                font-family: 'Noto Sans', sans-serif;
                                            }

                                            /* .panel {
                                background: linear-gradient(to right, #2980b9, #2c3e50);
                                padding: 0;
                                border-radius: 10px;
                                border: none;
                                box-shadow: 0 0 0 5px rgba(0, 0, 0, 0.05), 0 0 0 10px rgba(0, 0, 0, 0.05);
                            }



                            .panel .panel-body {
                                padding: 0;
                            }

                            .panel .panel-body .table thead tr th {
                                color: #fff;
                                background-color: rgba(255, 255, 255, 0.2);
                                font-size: 12px;
                                font-weight: 400;
                                text-transform: uppercase;
                                padding: 2px;
                                border: none;
                            }

                            .panel .panel-body .table tbody tr td {
                                color: #000;
                                font-size: 12px;
                                padding: 10px 12px;
                                vertical-align: middle;
                                border: none;
                            } */

                                            th {
                                                font-size: 13px;
                                            }

                                            td {
                                                font-size: 13px;
                                            }


                                            @media only screen and (max-width:767px) {
                                                .panel .panel-heading .title {
                                                    text-align: center;
                                                    margin: 0 0 10px;
                                                }

                                                .panel .panel-heading .btn_group {
                                                    text-align: center;
                                                }


                                            }
                                        </style>
                                        <div class="tab-content" id="myInnerTabContent">
                                            <div class="tab-pane fade show active p-3" id="quiz" role="tabpanel" aria-labelledby="quiz-tab">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="panel">
                                                            <div class="panel-body table-responsive">
                                                                <table class="table tab">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Quiz name</th>
                                                                            <th>Subject</th>
                                                                            <th>Total Questions</th>
                                                                            <th>Marks Scored</th>
                                                                            <th>Status</th>
                                                                            <th>Exam Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="quiz_tbody">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade p-3" id="exam" role="tabpanel" aria-labelledby="exam-tab">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="panel">
                                                            <div class="panel-body table-responsive">
                                                                <table class="table tab">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Exam name</th>
                                                                            <th>Subject</th>
                                                                            <th>Total Marks</th>
                                                                            <th>Marks Scored</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="exam_tbody">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade p-3" id="remark" role="tabpanel" aria-labelledby="remark-tab">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="panel">
                                                            <div class="panel-body table-responsive">
                                                                <table class="table tab">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Remark By</th>
                                                                            <th>Date</th>
                                                                            <th>Remark</th>
                                                                            <th>Remark Reply</th>
                                                                            <th>Reply Date</th>
                                                                            <th>Remark Type</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="remark_tbody">

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
                            <div class="tab-pane fade p-3" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                            <div class="panel-body table-responsive">

                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Year</th>
                                                            <th>Class</th>
                                                            <th>Total Fees</th>
                                                            <th>Paid</th>
                                                            <th>Balance</th>
                                                            <th>Exumption</th>
                                                            <th>Class</th>
                                                            <th>Fees</th>
                                                            <th>Date</th>
                                                            <th>Exem.</th>
                                                            <th>Paid To</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="fees_tbody" class="tbody-striped">

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
        </div>
        <div class="col-md-4 pe-5 pt-4">
            <div class="card">
                <div class="row p-2">
                    <div class="col-md-12">
                        <!-- <h5> Helpful Links</h5> -->
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {


            function fetchStudentDetails(studentId, acdm) {
                $.ajax({
                    url: '<?= base_url('/StudentProfile/getDetails') ?>', // Replace with your actual controller URL
                    type: 'POST',
                    data: {
                        id: studentId,
                        acdm: acdm
                    },
                    success: function(response) {
                        console.log('Success:', response);
                        if (response != '') {
                            var parsedResponse = JSON.parse(response);
                            var class_tbody = document.getElementById("class_tbody");
                            var student_name = document.getElementById("display_name");

                            var html_code = '';
                            html_code += `<tr>
                                    <td>${parsedResponse.id}</td>
                                    <td>${parsedResponse.stud_id}</td>
                                    <td>${parsedResponse.std}</td>
                                    <td>${parsedResponse.class}</td>
                                    <td>${parsedResponse.roll_no}</td>
                                    <td>${parsedResponse.class_teacher}</td>
                                 </tr>`;

                            student_name.innerHTML = parsedResponse.name;
                            class_tbody.innerHTML = html_code;

                            // Trigger the first AJAX request 
                            fetchQuizDetails(parsedResponse.id, acdm);

                            // Trigger the second AJAX request after the first one succeeds
                            fetchExamDetails(parsedResponse.id, acdm);

                            // Trigger the third AJAX request after the second one succeeds
                            fetchRemarkDetails(parsedResponse.id, acdm);

                            // Trigger the fourth AJAX request after the third one succeeds
                            fetchFeesDetails(parsedResponse.id, acdm);

                            fetchAttendanceDetails(parsedResponse.id, acdm);

                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        // Handle error response
                    }
                });
            }

            function fetchQuizDetails(stud_id, acdm) {
                $.ajax({
                    url: '<?= base_url('/StudentProfile/getQuizDetails') ?>', // Replace with your actual controller URL
                    type: 'POST',
                    data: {
                        id: stud_id,
                        acdm: acdm
                    },
                    success: function(response) {
                        console.log('Quiz response:', response);
                        if (response != '') {
                            var parsedResponse = JSON.parse(response);
                            var quiz_tbody = document.getElementById("quiz_tbody");
                            var html = '';
                            var lastQuizName = ''; // Variable to track the last displayed quiz name

                            parsedResponse.forEach(function(quiz) {
                                var quizNameCell = '';

                                // Check if the current quiz name is different from the last one
                                if (quiz.quiz_name !== lastQuizName) {
                                    quizNameCell = `<td class="text-success">${quiz.quiz_name}</td>`;
                                    lastQuizName = quiz.quiz_name; // Update the last quiz name
                                } else {
                                    quizNameCell = '<td></td>'; // Empty cell if the quiz name is the same
                                }

                                html += `<tr>
                        ${quizNameCell}
                        <td>${quiz.stvsubj_subject}</td>
                        <td>${quiz.total_questions}</td>
                        <td>${quiz.marks_scored}</td>
                        <td>${quiz.status}</td>
                        <td>${quiz.exam_date}</td>
                     </tr>`;
                            });

                            quiz_tbody.innerHTML = html;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        // Handle error response
                    }
                });
            }


            function fetchExamDetails(stud_id, acdm) {
                $.ajax({
                    url: '<?= base_url('/StudentProfile/getExamDetails') ?>', // Replace with your actual controller URL
                    type: 'POST',
                    data: {
                        id: stud_id,
                        acdm: acdm
                    },
                    success: function(response) {
                        console.log('exam response:', response);
                        if (response != '') {
                            var parsedResponse = JSON.parse(response);
                            var exam_tbody = document.getElementById("exam_tbody");
                            var html = '';
                            var lastExamName = ''; // Variable to track the last displayed exam name

                            parsedResponse.forEach(function(exam) {
                                var examNameCell = '';

                                // Check if the current exam name is different from the last one
                                if (exam.stvexam_name !== lastExamName) {
                                    examNameCell = `<td class="text-success">${exam.stvexam_name}</td>`;
                                    lastExamName = exam.stvexam_name; // Update the last exam name
                                } else {
                                    examNameCell = '<td></td>'; // Empty cell if the exam name is the same
                                }

                                html += `<tr>
                        ${examNameCell}
                        <td>${exam.stvsubj_subject}</td>
                        <td>${exam.sprmark_total_marks}</td>
                        <td>${exam.sprmark_mark}</td>
                     </tr>`;
                            });

                            exam_tbody.innerHTML = html;
                        }
                    },

                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        // Handle error response
                    }
                });
            }

            function fetchRemarkDetails(stud_id, acdm) {
                $.ajax({
                    url: '<?= base_url('/StudentProfile/getRemarkDetails') ?>', // Replace with your actual controller URL
                    type: 'POST',
                    data: {
                        id: stud_id,
                        acdm: acdm
                    },
                    success: function(response) {
                        console.log('remark response:', response);
                        if (response != '') {
                            var parsedResponse = JSON.parse(response);
                            var remark_tbody = document.getElementById("remark_tbody");
                            var html = '';
                            var lastremarkName = ''; // Variable to track the last displayed remark name

                            parsedResponse.forEach(function(remark) {
                                var remarkNameCell = '';

                                // Check if the current remark name is different from the last one
                                if (remark.remark_by !== lastremarkName) {
                                    remarkNameCell = `<td class="text-success">${remark.remark_by}</td>`;
                                    lastremarkName = remark.remark_by; // Update the last remark name
                                } else {
                                    remarkNameCell = '<td></td>'; // Empty cell if the remark name is the same
                                }

                                html += `<tr>
                        ${remarkNameCell}
                        <td>${remark.stvrmrk_date}</td>
                        <td>${remark.stvrmrk_remark}</td>
                        <td>${remark.stvrmrk_reply}</td>
                        <td>${remark.stvrmrk_date_of_reply}</td>
                        <td>${remark.stvrmrk_status}</td>
                     </tr>`;
                            });

                            remark_tbody.innerHTML = html;
                        }
                    },

                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        // Handle error response
                    }
                });
            }


            function fetchAttendanceDetails(stud_id, acdm) {
                $.ajax({
                    url: '<?= base_url('/StudentProfile/getAttendanceDetails') ?>', // Replace with your actual controller URL
                    type: 'POST',
                    data: {
                        id: stud_id,
                        acdm: acdm
                    },
                    success: function(response) {
                        console.log('att response:', response);
                        var data = JSON.parse(response);
                        initializeCalendar(data);

                    },

                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        // Handle error response
                    }
                });
            }

            function initializeCalendar(attendanceData) {
                var events = attendanceData.map(function(attendance) {
                    return {
                        title: attendance.SPRATND_status,
                        start: attendance.SPRATND_date,
                        color: attendance.SPRATND_status === 'P' ? 'green' : 'red'
                    };
                });

                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: events
                });
                calendar.render();
            }



            function fetchFeesDetails(stud_id, acdm) {
                $.ajax({
                    url: '<?= base_url('/StudentProfile/all_fees_details_of_student') ?>', // Replace with your actual controller URL
                    type: 'POST',
                    data: {
                        student_id: stud_id,
                        academic_year: acdm
                    },
                    success: function(res) {
                        console.log('Fees response:', res);
                        if (res != '') {
                            var parsedResponse = JSON.parse(res);
                            //fees details

                            var html = '';
                            var data = parsedResponse; // Ensure parsedResponse is used as data

                            for (var i = 0; i < data['all_fees'].length; i++) {
                                let installments = data['all_fees'][i].installments.split(",");
                                let rowspan = parseFloat(installments.length) + 1;

                                var newI = i + 1;
                                html += '<tr>';
                                html += '<td rowspan="' + rowspan + '">' + newI + '</td>';
                                html += '<td rowspan="' + rowspan + '">' + data['all_fees'][i].stvacdm_year + '</td>';
                                html += '<td rowspan="' + rowspan + '">' + data['all_fees'][i].sprfees_standard + '</td>';
                                html += '<td rowspan="' + rowspan + '">' + data['all_fees'][i].class_fees + '</td>';
                                html += '<td rowspan="' + rowspan + '">' + data['all_fees'][i].total_paid + '</td>';
                                html += '<td rowspan="' + rowspan + '">' + data['all_fees'][i].final_balance + '</td>';
                                html += '<td rowspan="' + rowspan + '">' + data['all_fees'][i].total_exumpted + '</td>';
                                html += '</tr>';
                                for (var ins = 0; ins < installments.length; ins++) {
                                    let ins_details = installments[ins].split("->");
                                    html += '<tr>';
                                    html += '<td>' + ins_details[0] + '</td>';
                                    html += '<td>' + ins_details[1] + '</td>';
                                    html += '<td>' + ins_details[2] + '</td>';
                                    html += '<td>' + ins_details[3] + '</td>';
                                    html += '<td>' + ins_details[4] + '</td>';
                                    html += '</tr>';
                                }
                            }

                            $('#fees_tbody').html(html);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        // Handle error response
                    }
                });
            }

            $('#name').on('input', function() {
                var inputVal = $(this).val();
                var option = $('#stud_name option').filter(function() {
                    return this.value === inputVal;
                });
                var studentId = option.data('id');
                var acdm = document.getElementById("acdm").value;

                var elem = document.getElementById('navtabcontent');
                // var bloc = document.getElementsByClassName('fc-view-harness');
                elem.classList.remove('d-none');
                // bloc.classList.remove('d-none');
                fetchStudentDetails(studentId, acdm);

            });

            $('#acdm').on('change', function() {
                var acdm = $(this).val();
                var inputVal = $('#name').val();
                var option = $('#stud_name option').filter(function() {
                    return this.value === inputVal;
                });
                var studentId = option.data('id');
                if (studentId) {
                    fetchStudentDetails(studentId, acdm);
                }
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            // var bloc = document.getElementsByClassName('fc-view-harness-active');
            // bloc.classList.add('d-none');
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: "<?= base_url('attendance/get_attendance_data/582') ?>", // Replace with actual student ID
                        method: "GET",
                        dataType: "json",
                        success: function(data) {
                            var events = [];
                            data.forEach(function(attendance) {
                                events.push({
                                    title: attendance.status,
                                    start: attendance.date,
                                    color: attendance.status === 'P' ? 'green' : 'red'
                                });
                            });
                            successCallback(events);
                        }
                    });
                }
            });
            calendar.render();
        });
    </script>
</body>

</html>
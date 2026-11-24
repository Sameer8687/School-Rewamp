<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Attendance Dashboard</title>
    <link rel="stylesheet" href="r.css" />
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets1/images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.3.1/css/all.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="<?php echo base_url(); ?>assets1/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet"
        type="text/css" />
    <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Added jQuery CDN -->
    <script src="<?php echo base_url(); ?>assets1/js/layout.js"></script>
    <link href="<?php echo base_url(); ?>assets1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets1/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets1/css/app.min.css" rel="stylesheet" type="text/css" />
</head>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .print-container,
        .print-container * {
            visibility: visible;
        }

        .print-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }

        .card {
            border: none;
        }

        #print-button {
            display: none;
        }

        #back-button {
            display: none;
        }
    }

    .bg-main {
        background-color: #3680a2;
    }
</style>

<body>
    <div class="container py-3">
        <div class="card shadow print-container">
            <div class="card-body shadow-lg">
                <div id="summary-table">
                    <table class="table table-sm table-striped table-bordered nowrap">
                        <thead class="text-center text-light bg-main">
                            <tr>
                                <th>#</th>
                                <th>Month</th>
                                <th>Year</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Total Days</th>
                                <th>Total Present</th>
                                <th>Total Absent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                            $i = 1;
                            foreach ($all_details as $all_detail) { ?>
                                <tr>
                                    <td class="text-center"><?= $i; ?></td>
                                    <td class="text-center"><?= $months[(int) $all_detail['month'] - 1]; ?></td>
                                    <td class="text-center"><?= $all_detail['year']; ?></td>
                                    <td class="text-center"><?= $all_detail['SPRIDEN_student_id'] ?></td>
                                    <td><?= $all_detail['name'] ?></td>
                                    <td><?= $all_detail['total_days'] ?></td>
                                    <td>
                                        <a href="#" class="details-link"
                                            data-student-id="<?= $all_detail['SPRIDEN_student_id'] ?>"
                                            data-month="<?= $all_detail['month'] ?>" data-year="<?= $all_detail['year'] ?>"
                                            data-type="P"
                                            data-teacher-id="<?= $teacher_id ?>"><?= $all_detail['total_present'] ?></a>
                                    </td>
                                    <td>
                                        <a href="#" class="details-link"
                                            data-student-id="<?= $all_detail['SPRIDEN_student_id'] ?>"
                                            data-month="<?= $all_detail['month'] ?>" data-year="<?= $all_detail['year'] ?>"
                                            data-type="A"
                                            data-teacher-id="<?= $teacher_id ?>"><?= $all_detail['total_absent'] ?></a>
                                    </td>
                                </tr>
                                <?php $i++;
                            } ?>
                        </tbody>

                    </table>
                </div>
                <div id="details-table" class="d-none">
                    <button class="btn btn-secondary btn-sm mb-3" id="back-button"><i class="fas fa-arrow-left"></i>
                        Back</button>
                    <table class="table table-sm table-striped table-bordered nowrap">
                        <thead class="text-center text-light bg-main">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Div</th>
                                <th>Attendance</th>
                            </tr>
                        </thead>
                        <tbody id="student_detail">
                        </tbody>
                    </table>
                </div>
                <button onclick="window.print();" class="btn btn-secondary btn-sm" id="print-button" type="button"> <i
                        class="fas fa-print"></i>Print</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const detailsLinks = document.querySelectorAll('.details-link');
            const summaryTable = document.getElementById('summary-table');
            const detailsTable = document.getElementById('details-table');
            const backButton = document.getElementById('back-button');

            detailsLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const studentId = this.getAttribute('data-student-id');
                    const month = this.getAttribute('data-month');
                    const year = this.getAttribute('data-year');
                    const type = this.getAttribute('data-type');
                    const teacherId = this.getAttribute('data-teacher-id'); // Make sure to include this attribute in your HTML
                    fetchDetails(studentId, month, year, type, teacherId);
                });
            });

            backButton.addEventListener('click', function () {
                detailsTable.classList.add('d-none');
                summaryTable.classList.remove('d-none');
            });

            function fetchDetails(studentId, month, year, type, teacherId) {
                $.ajax({
                    url: "<?= base_url('index.php/Attend_DB/getSingleStudentDetails') ?>",
                    method: "GET",
                    dataType: "json",
                    data: {
                        student_id: studentId,
                        month: month,
                        year: year,
                        type: type,
                        teacher_id: teacherId // Include teacher_id in the data
                    },
                    beforeSend: function () {
                        $("#student_detail").html('<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>');
                    },
                    success: function (data) {
                        let str = '';
                        let i = 0;
                        if (data.length === 0) {
                            str = '<tr><td colspan="7" class="text-center text-dark" style="font-size:large;">No absences found for the selected student.</td></tr>';
                        } else {
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
                                </tr>`;
                            });
                        }

                        $("#student_detail").html(str);
                        detailsTable.classList.remove('d-none');
                        summaryTable.classList.add('d-none');
                    },
                    error: function (error) {
                        console.error("Error fetching data:", error);
                        $("#student_detail").html('<tr><td colspan="7" class="text-center text-danger">Error loading data</td></tr>');
                    }
                });
            }
        });

    </script>
</body>

</html>
<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title> | Olive English School</title>

    <!-- Meta -->

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />





    <?php include 'application/views/cssPages.php'; ?>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/teacher-module.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/new-db.css">

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

    <!-- [ Pre-loader ] End -->

    <?php include 'Teacher/sidebar.php'; ?>

    <!-- [ Header ] start -->

    <?php include 'Teacher/topNav.php'; ?>

    <!-- [ Header ] end -->



    <!-- [ Main Content ] start -->

    <div class="pc-container">

        <div class="pcoded-content">
            <input type="hidden" id="getFullScreenMode">
            <div class="row">

                <div class="col-2">
                    <div class="form-group">
                        <label for="">Year:</label>
                        <select id="academic_year" class="form-control form-control-sm">
                            <option value="">Select Academic Year</option>
                            <?php foreach ($academic_years as $year): ?>
                                <option value="<?= $year->stvacdm_id ?>" <?= ($year->stvacdm_id == $current_year) ? 'selected' : '' ?>>
                                    <?= $year->stvacdm_year ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label for="">Class :</label>
                        <select name="class" id="class" class="form-control form-control-sm" required>
                            <option value="">Select</option>
                            <option value="Nursery">Nursery</option>
                            <option value="Jr.Kg.">Jr.Kg.</option>
                            <option value="Sr.Kg.">Sr.Kg.</option>
                            <option value="I">I</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                            <option value="V">V</option>
                            <option value="VI">VI</option>
                            <option value="VII">VII</option>
                            <option value="VIII">VIII</option>
                            <option value="IX">IX</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label for="">Div :</label>
                        <select id="div" class="form-control form-control-sm">
                            <option value="">Select Div</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label for="">Div :</label>
                        <select name="subject" id="subject" class="form-control form-control-sm" required>
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label for="">Lesson :</label>
                        <select name="lesson_name" id="lesson_name" class="form-control form-control-sm" required>
                            <option value="">Select Lesson</option>
                        </select>
                    </div>
                </div>

            </div>
            <style>
                .table thead tr th,
                .table tbody tr td {
                    font-size: 10px !important;
                    padding: 5px !important;
                }
            </style>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="lessonTable">
                            <thead>
                                <tr>
                                    <th class="text-center">Sr No</th>
                                    <th class="text-center">Pre-request</th>
                                    <th class="text-center">Worksheet</th>
                                    <th class="text-center">Q&A</th>
                                    <th class="text-center">Quiz</th>
                                    <th class="text-center">Lesson</th>
                                    <th class="text-center">Learning Outcome</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Required Js -->
    <?php include 'application/views/jsPages.php'; ?>

    <script>
        var base_url = "<?= base_url(); ?>";

        $(document).ready(function () {
            $('#academic_year').on('change', function () {
                $('#modal_academic_year').val($(this).val());
            });
            $('#class').on('change', function () {
                var selectedClass = $(this).val();
                var academicYear = $('#academic_year').val();
                $('#subject').html('<option value="">Select</option>');
                if (selectedClass && academicYear) {
                    $.ajax({
                        url: base_url + 'index.php/Lesson_approval/get_subjects',
                        type: 'GET',
                        data: {
                            class: selectedClass,
                            academic_year: academicYear
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.length > 0) {
                                $.each(response, function (index, subject) {
                                    $('#subject').append('<option value="' + subject
                                        .stvsubj_id + '">' + subject
                                            .stvsubj_subject + '</option>');
                                });
                            } else {
                                $('#subject').html(
                                    '<option value="">No subjects found</option>');
                            }
                        }
                    });
                }
            });

            $('#subject').on('change', function () {
                let selectedClass = $('#class').val();
                let selectedSubject = $(this).val();
                let academicYear = $('#academic_year').val();
                $('#lesson_name').html('<option value="">Loading...</option>');
                if (selectedClass && selectedSubject && academicYear) {
                    $.ajax({
                        url: base_url + 'index.php/Lesson_approval/get_lessons_by_class_subject',
                        type: 'GET',
                        data: {
                            class: selectedClass,
                            subject: selectedSubject,
                            academic_year: academicYear
                        },
                        dataType: 'json',
                        success: function (data) {
                            let options = '<option value="">Select Lesson</option>';
                            if (data.length > 0) {
                                $.each(data, function (index, lesson) {
                                    options +=
                                        `<option value="${lesson.lesson_name}">${lesson.lesson_name}</option>`;
                                });
                            } else {
                                options += '<option value="">No lessons found</option>';
                            }
                            $('#lesson_name').html(options);
                        },
                        error: function () {
                            $('#lesson_name').html(
                                '<option value="">Error loading lessons</option>');
                        }
                    });
                } else {
                    $('#lesson_name').html('<option value="">Select Lesson</option>');
                }
            });

            $('#lessonModal').on('show.bs.modal', function () {
                $('#lessonForm')[0].reset();
                $('#subject').html('<option value="">Select</option>');
                $('#lesson_name').html('<option value="">Select Lesson</option>');
                $('#lesson_id').val('');
                $('#modalTitle').text('Add Lesson');
            });

            $('#subject').on('change', function () {
                let selectedClass = $('#class').val();
                let selectedSubject = $(this).val();
                let academicYear = $('#academic_year').val();

                // Load lesson names dropdown
                $('#lesson_name').html('<option value="">Loading...</option>');
                if (selectedClass && selectedSubject && academicYear) {
                    $.ajax({
                        url: base_url + 'index.php/Lesson_approval/get_lessons_by_class_subject',
                        type: 'GET',
                        data: {
                            class: selectedClass,
                            subject: selectedSubject,
                            academic_year: academicYear
                        },
                        dataType: 'json',
                        success: function (data) {
                            let options = '<option value="">Select Lesson</option>';
                            if (data.length > 0) {
                                $.each(data, function (index, lesson) {
                                    options +=
                                        `<option value="${lesson.lesson_name}">${lesson.lesson_name}</option>`;
                                });
                            } else {
                                options += '<option value="">No lessons found</option>';
                            }
                            $('#lesson_name').html(options);
                        },
                        error: function () {
                            $('#lesson_name').html(
                                '<option value="">Error loading lessons</option>');
                        }
                    });
                } else {
                    $('#lesson_name').html('<option value="">Select Lesson</option>');
                }
            });
            $('#academic_year').trigger('change');
        });

        // Trigger jab sab dropdown select ho jaye
        $('#lesson_name, #div').on('change', function () {
            const selectedClass = $('#class').val();
            const division = $('#div').val();
            const subject = $('#subject').val();
            const lesson = $('#lesson_name').val();
            if (selectedClass && division && subject && lesson) {
                console.log('Fetching lesson data...', selectedClass, division, subject, lesson);
                $.ajax({
                    url: base_url + 'index.php/Lesson_approval/fetch_lesson_data',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        class: selectedClass,
                        division: division,
                        subject: subject,
                        lesson: lesson
                    },
                    success: function (response) {
                        console.log('show data', response);
                        let html = '';
                        if (response.length > 0) {
                            $.each(response, function (index, item) {
                                html += `<tr>
                                            <td class="text-center">${index + 1}</td>
                                            <td class="text-center"><input type="checkbox" data-lesson="${item.lesson_id}" data-column="pre_request_status"                                         onchange="handleCheckboxChange(this)" ${item.pre_request_status === 'active' ? 'checked' : ''}></td>
                                            <td class="text-center"><input type="checkbox" data-lesson="${item.lesson_id}" data-column="worksheet_status"                                       onchange="handleCheckboxChange(this)" ${item.worksheet_status === 'active' ? 'checked' : ''}></td>
                                            <td class="text-center"><input type="checkbox" data-lesson="${item.lesson_id}" data-column="question_status"                                        onchange="handleCheckboxChange(this)" ${item.question_status === 'active' ? 'checked' : ''}></td>
                                            <td class="text-center"><input type="checkbox" data-lesson="${item.lesson_id}" data-column="quiz_status"                                        onchange="handleCheckboxChange(this)" ${item.quiz_status === 'active' ? 'checked' : ''}></td>
                                            <td class="text-center"><input type="checkbox" data-lesson="${item.lesson_id}" data-column="lesson_status"                                      onchange="handleCheckboxChange(this)" ${item.lesson_status === 'active' ? 'checked' : ''}></td>
                                            <td class="text-center"><input type="checkbox" data-lesson="${item.lesson_id}" data-column="learning_outcome_status"                                        onchange="handleCheckboxChange(this)" ${item.learning_outcome_status === 'active' ? 'checked' : ''}></td>
                                        </tr>`;

                            });
                        } else {
                            html = `<tr><td colspan="7" class="text-center">No data found</td></tr>`;
                        }
                        $('#lessonTable tbody').html(html);
                    }
                });
            }
        });

        function handleCheckboxChange(checkbox) {
            const lessonId = $(checkbox).data('lesson');
            const column = $(checkbox).data('column');
            const value = checkbox.checked ? 'active' : 'inactive';
            updateStatus(lessonId, column, value);
        }


        function updateStatus(lessonId, column, value) {
            $.ajax({
                url: base_url + 'index.php/Lesson_approval/update_status',
                type: 'POST',
                dataType: 'json',
                data: {
                    lesson_id: lessonId,
                    column_name: column,
                    column_value: value
                },
                success: function (response) {
                    if (response.success) {
                        alert('Status updated successfully');
                    } else {
                        alert('Failed to update status.');
                    }
                },
                error: function () {
                    alert('Error while updating status.');
                }
            });
        }


        // $(document).on('change', 'input[type=radio]', function () {
        //     let lesson_id = $(this).data('lesson');
        //     let column = $(this).data('column');
        //     let value = $(this).val(); // active or inactive
        //     $.ajax({
        //         url: base_url + 'index.php/Lesson_approval/update_lesson_status',
        //         type: 'POST',
        //         data: {
        //             lesson_id: lesson_id,
        //             column: column,
        //             value: value
        //         },
        //         success: function (res) {
        //             console.log('Updated', lesson_id, column, value);
        //         },
        //         error: function () {
        //             alert('Failed to update status');
        //         }
        //     });
        // });
    </script>
</body>

</html>
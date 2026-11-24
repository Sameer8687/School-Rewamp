<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Lesson</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <style>
        .pc-container {
            top: 30px;
        }

        .form-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            table-layout: fixed;
            /* Ensures the table is evenly distributed */
        }

        .form-table th {
            padding: 1px 1px !important;
            border: 1px solid #ddd;
            text-align: center;
            word-wrap: break-word;
            /* Forces long content to wrap */
        }

        .form-table td {
            padding: 0px 0px !important;
            border: 1px solid #ddd;
            text-align: center;
            word-wrap: break-word;
        }

        .form-table th {
            background-color: #53769c !important;
            color: #fff !important;
        }

        .form-table td select,
        .form-table td input {
            width: 100%;
            box-sizing: border-box;
        }

        .add-row-btn {
            margin-top: 10px;
            cursor: pointer;
        }

        #moduleFormContainer {
            margin-top: 20px;
        }
    </style>
    <?php include 'application/views/cssPages.php'; ?>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'topNav.php'; ?>

    <div class="pc-container">
        <div class="pcoded-content">
            <div class="row mt-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center" style="margin-top: -10px;">
                            <i class="fa fa-list" aria-hidden="true"></i>
                            <h4 class="mb-0 ml-2">Access List</h4>
                        </div>
                        <form id="lessonForm" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="academic_year" id="modal_academic_year">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label><strong>Add Lesson's</strong></label>
                                    <select id="academic_year" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php foreach ($academic_years as $year): ?>
                                            <option value="<?= $year->stvacdm_id ?>"><?= $year->stvacdm_year ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label><strong>Class</strong></label>
                                    <select name="class" id="class" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php foreach (['Nursery', 'Jr.Kg.', 'Sr.Kg.', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'] as $cls): ?>
                                            <option value="<?= $cls ?>"><?= $cls ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label><strong>Subject</strong></label>
                                    <select name="subject" id="subject" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-sm btn-primary w-100">Save Lessons</button>
                                </div>
                            </div>
                        </form>
                        <button type="button" id="addRowBtn" class="btn btn-sm btn-outline-info mb-2">
                            <i class="fas fa-plus"></i> Add Row
                        </button>
                        <div class="table-responsive" style=" margin-top: -10px;">
                            <table id="lessonTable"
                                class="table table-striped table-bordered nowrap bg-white form-table">
                                <thead>
                                    <tr>
                                        <th>Lesson No</th>
                                        <th>Lesson Name</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="lesson_no[]" class="form-control form-control-sm"
                                                required></td>
                                        <td><input type="text" name="lesson_name[]" class="form-control form-control-sm"
                                                required></td>
                                        <td>
                                            <select name="type[]" class="form-control form-control-sm" required>
                                                <option value="lms">LMS</option>
                                                <option value="timetable">Time Table</option>
                                                <option value="exam">Exam</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-lesson"><i
                                                    class="fas fa-trash"></i> Remove</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'application/views/jsPages.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var base_url = "<?= base_url() ?>";

        $(document).ready(function () {
            // Set academic year in hidden field
            $('#academic_year').on('change', function () {
                $('#modal_academic_year').val($(this).val());
                loadLessons(); // Load lessons when academic year changes
            });

            // Subject load on class change
            $('#class').change(function () {
                var selectedClass = $(this).val();
                var academicYear = $('#academic_year').val();
                $('#subject').html('<option value="">Select</option>');

                if (selectedClass && academicYear) {
                    $.post('index.php/Lesson_management/get_subjects', {
                        class: selectedClass,
                        academic_year: academicYear
                    }, function (res) {
                        try {
                            let subjects = JSON.parse(res);
                            if (subjects.length > 0) {
                                subjects.forEach(function (subject) {
                                    $('#subject').append(`<option value="${subject.id}">${subject.name}</option>`);
                                });
                            } else {
                                $('#subject').append(`<option value="">No Subject Found</option>`);
                            }
                        } catch (e) {
                            console.error('Error parsing subjects:', e);
                            $('#subject').append(`<option value="">Error loading subjects</option>`);
                        }
                    }).fail(function () {
                        $('#subject').append(`<option value="">Failed to load subjects</option>`);
                    });
                }
            });

            // Add row
            $('#addRowBtn').click(function () {
                let newRow = `
            <tr>
                <td><input type="text" name="lesson_no[]" class="form-control form-control-sm" required></td>
                <td><input type="text" name="lesson_name[]" class="form-control form-control-sm" required></td>
                <td>
                    <select name="type[]" class="form-control form-control-sm" required>
                        <option value="lms">LMS</option>
                        <option value="timetable">Time Table</option>
                        <option value="exam">Exam</option>
                    </select>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-lesson">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </td>
            </tr>`;
                $('#lessonTable tbody').append(newRow);
            });

            // Remove row
            $(document).on('click', '.remove-lesson', function () {
                const row = $(this).closest('tr');
                const lesson_id = $(this).data('id');

                if (!lesson_id) {
                    // New row - just remove from DOM
                    row.remove();
                    return;
                }

                // Existing lesson - confirm deletion
                if (confirm("Are you sure you want to delete this lesson from the database?")) {
                    $.post('index.php/Lesson_management/delete_lesson', {
                        lesson_id: lesson_id
                    }, function (res) {
                        try {
                            const response = JSON.parse(res);
                            if (response.status === 'success') {
                                row.remove();
                            } else {
                                alert("Failed to delete lesson: " + response.message);
                            }
                        } catch (e) {
                            alert("Error parsing response");
                        }
                    }).fail(function () {
                        alert("Error occurred while deleting the lesson.");
                    });
                }
            });

            // Submit form via AJAX
            $('#lessonForm').submit(function (e) {
                e.preventDefault();
                const academic_year = $('#academic_year').val();
                const subject = $('#subject').val();
                const class_name = $('#class').val();

                if (!academic_year || !subject || !class_name) {
                    alert("Please fill Academic Year, Class and Subject.");
                    return;
                }

                // Collect lesson data
                const lessons = [];
                let hasErrors = false;

                $('#lessonTable tbody tr').each(function () {
                    const lesson_no = $(this).find('input[name="lesson_no[]"]').val().trim();
                    const lesson_name = $(this).find('input[name="lesson_name[]"]').val().trim();
                    const type = $(this).find('select[name="type[]"]').val();

                    if (lesson_no && lesson_name) {
                        lessons.push({
                            lesson_no: lesson_no,
                            lesson_name: lesson_name,
                            type: type
                        });
                    } else if (lesson_no || lesson_name) {
                        alert("Please fill both Lesson No and Lesson Name for all rows");
                        hasErrors = true;
                        return false; // break loop
                    }
                });

                if (hasErrors) return;

                if (lessons.length === 0) {
                    alert("Please add at least one lesson.");
                    return;
                }

                const formData = {
                    academic_year: academic_year,
                    class: class_name,
                    subject: subject,
                    lessons: lessons
                };

                $.post('index.php/Lesson_management/save_lessons', formData, function (res) {
                    try {
                        const response = JSON.parse(res);
                        if (response.status === 'success') {
                            alert('Lessons saved successfully!');
                            loadLessons();
                        } else {
                            alert('Failed to save lessons: ' + response.message);
                        }
                    } catch (e) {
                        alert('Invalid response from server');
                    }
                }).fail(function () {
                    alert('Failed to save lessons.');
                });
            });

            // Load lessons when subject changes
            $('#subject').change(loadLessons);

            // Function to load lessons
            function loadLessons() {
                let academic_year = $('#academic_year').val();
                let class_name = $('#class').val();
                let subject = $('#subject').val();

                if (academic_year && class_name && subject) {
                    $.ajax({
                        url: 'index.php/Lesson_management/fetch_lessons',
                        type: 'POST',
                        data: {
                            academic_year: academic_year,
                            class: class_name,
                            subject: subject
                        },
                        success: function (response) {
                            try {
                                const lessons = typeof response === 'string' ? JSON.parse(response) : response;
                                let tbody = '';

                                if (lessons && lessons.length > 0) {
                                    lessons.forEach((lesson) => {
                                        tbody += `
                                    <tr>
                                        <td><input type="text" name="lesson_no[]" class="form-control form-control-sm" value="${lesson.lesson_no}" required></td>
                                        <td><input type="text" name="lesson_name[]" class="form-control form-control-sm" value="${lesson.lesson_name}" required></td>
                                        <td>
                                            <select name="type[]" class="form-control form-control-sm" required>
                                                <option value="lms" ${lesson.type === 'lms' ? 'selected' : ''}>LMS</option>
                                                <option value="timetable" ${lesson.type === 'timetable' ? 'selected' : ''}>Time Table</option>
                                                <option value="exam" ${lesson.type === 'exam' ? 'selected' : ''}>Exam</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-lesson" data-id="${lesson.id}">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                    });
                                } else {
                                    tbody = `
                                <tr>
                                    <td><input type="text" name="lesson_no[]" class="form-control form-control-sm" required></td>
                                    <td><input type="text" name="lesson_name[]" class="form-control form-control-sm" required></td>
                                    <td>
                                        <select name="type[]" class="form-control form-control-sm" required>
                                            <option value="lms">LMS</option>
                                            <option value="timetable">Time Table</option>
                                            <option value="exam">Exam</option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger remove-lesson">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </td>
                                </tr>
                            `;
                                }

                                $('#lessonTable tbody').html(tbody);
                            } catch (e) {
                                console.error('Error parsing lessons:', e);
                                alert('Error loading lessons data');
                            }
                        },
                        error: function () {
                            alert('Failed to load lessons.');
                        }
                    });
                }
            }
        });
    </script>
</body>

</html>
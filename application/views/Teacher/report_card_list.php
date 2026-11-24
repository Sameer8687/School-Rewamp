<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report Cards</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-top: 20px;
        }

        .empty-state i {
            font-size: 48px;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .empty-state h4 {
            color: #495057;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #6c757d;
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .card-body .row>div {
                margin-bottom: 10px;
            }

            #studentsTable th,
            #studentsTable td {
                padding: 8px 5px;
                font-size: 12px;
            }

            #studentsTable .btn {
                padding: 3px 6px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-header text-white" style="background-color: #0f3b7c;">
                <form id="reportFilterForm">
                    <div class="row">
                        <div class="col-md-2">
                            <h5 class="mb-1 mt-2">Report Card Filter</h5>
                        </div>
                        <div class="col-md-3 mb-1 mt-1">
                            <select class="form-select" id="academicYear" required>
                                <option value="">Select Academic Year</option>
                                <?php if (isset($academic_years) && is_array($academic_years)): ?>
                                    <?php foreach ($academic_years as $year): ?>
                                        <option value="<?= $year['stvacdm_id'] ?>" 
                                            <?= (isset($acdm) && $year['stvacdm_id'] == $acdm) ? 'selected' : '' ?>>
                                            <?= $year['stvacdm_year'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-3 mb-1 mt-1">
                            <select class="form-select" id="class" <?= !isset($class) ? 'disabled' : '' ?> required>
                                <option value="">Select Class</option>
                                <?php if (isset($class)): ?>
                                    <option value="<?= $class ?>" selected><?= $class ?></option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-2 mb-1 mt-1">
                            <select class="form-select" id="division" <?= !isset($div) ? 'disabled' : '' ?> required>
                                <option value="">Select Division</option>
                                <?php if (isset($div)): ?>
                                    <option value="<?= $div ?>" selected><?= $div ?></option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-2 mb-1 mt-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-info w-100" id="searchBtn">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3" id="resultsCard">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="studentsTable">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-white bg-primary">#</th>
                            <th class="text-white bg-primary">Roll#</th>
                            <th class="text-white bg-primary">Name</th>
                            <th class="text-white bg-primary">Eng.</th>
                            <th class="text-white bg-primary">Hindi</th>
                            <th class="text-white bg-primary">Marathi</th>
                            <th class="text-white bg-primary">Sci. 1</th>
                            <th class="text-white bg-primary">Sci. 2</th>
                            <th class="text-white bg-primary">Alg.</th>
                            <th class="text-white bg-primary">Geom.</th>
                            <th class="text-white bg-primary">History</th>
                            <th class="text-white bg-primary">Geog.</th>
                            <th class="text-white bg-primary">Total</th>
                            <th class="text-white bg-primary">%</th>
                            <th class="text-white bg-primary">Action</th>
                        </tr>
                    </thead>
                    <tbody id="studentsList">
                        <!-- Empty state by default -->
                        <tr>
                            <!-- Updated colspan to match total columns (15) -->
                            <td colspan="15" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-filter"></i>
                                    <h4>No Data Available</h4>
                                    <p>Please select academic year, class and division to view student reports</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Load classes when academic year changes
            $('#academicYear').change(function() {
                const year = $(this).val();
                if (year) {
                    $('#class').prop('disabled', true).html('<option value="">Loading...</option>');
                    $('#division').prop('disabled', true).html('<option value="">Select Class First</option>');

                    $.get('<?= base_url("Dashboard/getClasses") ?>', {
                            acdm_year: year
                        },
                        function(classes) {
                            let options = '<option value="">Select Class</option>';
                            classes.forEach(cls => {
                                options += `<option value="${cls.class}">${cls.class}</option>`;
                            });
                            $('#class').html(options).prop('disabled', false);
                        }, 'json'
                    ).fail(function() {
                        $('#class').html('<option value="">Error loading classes</option>');
                    });
                }
            });

            // Load divisions when class changes
            $('#class').change(function() {
                const year = $('#academicYear').val();
                const cls = $(this).val();

                if (cls && year) {
                    $('#division').prop('disabled', true).html('<option value="">Loading...</option>');

                    $.get('<?= base_url("Dashboard/getDivisions") ?>', {
                            acdm_year: year
                        },
                        function(divisions) {
                            let options = '<option value="">Select Division</option>';
                            divisions.forEach(div => {
                                options += `<option value="${div.division}">${div.division}</option>`;
                            });
                            $('#division').html(options).prop('disabled', false);
                        }, 'json'
                    ).fail(function() {
                        $('#division').html('<option value="">Error loading divisions</option>');
                    });
                }
            });

            // Form submission
            $('#reportFilterForm').submit(function(e) {
                e.preventDefault();

                const year = $('#academicYear').val();
                const cls = $('#class').val();
                const div = $('#division').val();

                if (year && cls && div) {
                    $('#searchBtn').html('<i class="fas fa-spinner fa-spin"></i> Searching...').prop('disabled', true);

                    $.get('<?= base_url("Dashboard/report_list") ?>', {
                            acdm: year,
                            class: cls,
                            div: div
                        },
                        function(data) {
                            if (data.length > 0) {
                                let html = '';
                                data.forEach((student, index) => {
                                    const getStyledMark = (subject, mark) => {
                                        const passMark = ['English', 'Marathi', 'Hindi'].includes(subject) ? 35 : 18;
                                        const markValue = Number(mark ?? 0);
                                        const statusClass = markValue >= passMark ? 'text-success' : 'text-danger';
                                        return `<td class="${statusClass}" style="font-weight:bold;">${markValue}</td>`;
                                    };

                                    // Extract and normalize marks
                                    const subjects = ['Algebra', 'English', 'Geography', 'Geometry', 'Hindi', 'History', 'Marathi', 'Science_1', 'Science_2'];
                                    let totalMarks = 0;

                                    subjects.forEach(subj => {
                                        totalMarks += Number(student[subj] ?? 0);
                                    });

                                    // Calculate percentage assuming max 600 (as per original logic)
                                    const percentage = ((totalMarks / 600) * 100).toFixed(2);

                                    html += `<tr>
                                                <td style="font-weight:bold;">${index + 1}</td>
                                                <td style="font-weight:bold;">${student.STVCLAS_rollno}</td>
                                                <td style="font-weight:bold;">${student.full_name}</td>
                                                ${getStyledMark('English', student.English)}
                                                ${getStyledMark('Hindi', student.Hindi)}
                                                ${getStyledMark('Marathi', student.Marathi)}
                                                ${getStyledMark('Science_1', student.Science_1)}
                                                ${getStyledMark('Science_2', student.Science_2)}
                                                ${getStyledMark('Algebra', student.Algebra)}
                                                ${getStyledMark('Geometry', student.Geometry)}
                                                ${getStyledMark('History', student.History)}
                                                ${getStyledMark('Geography', student.Geography)}
                                                
                                                <td style="font-weight:bold;">${totalMarks}/600</td>
                                                <td style="font-weight:bold;">${percentage}%</td>
                                                <td>
                                                    <a style="text-decoration:none" target="_blank" href="<?= base_url('Dashboard/Report_card') ?>/${student.s_id}/${year}/${cls}/${div}" > 
                                                        <i class="fas fa-eye"></i> View Report
                                                    </a>
                                                </td>
                                            </tr>`;
                                });

                                $('#studentsList').html(html);
                            } else {
                                $('#studentsList').html(`
                                <tr>
                                    <td colspan="15" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fas fa-user-graduate"></i>
                                            <h4>No Students Found</h4>
                                            <p>No students match the selected filters</p>
                                        </div>
                                    </td>
                                </tr>
                            `);
                            }

                            $('#searchBtn').html('<i class="fas fa-search"></i> Search').prop('disabled', false);
                        }, 'json'
                    ).fail(function() {
                        $('#studentsList').html(`
                        <tr>
                            <td colspan="15" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <h4>Error Loading Data</h4>
                                    <p>Please try again later</p>
                                </div>
                            </td>
                        </tr>
                    `);
                        $('#searchBtn').html('<i class="fas fa-search"></i> Search').prop('disabled', false);
                    });
                }
            });

            // Auto-submit logic if parameters are present in URL
            <?php if (isset($acdm) && isset($class) && isset($div)): ?>
                // Manually enable dropdowns since values are pre-filled by PHP
                $('#class').prop('disabled', false);
                $('#division').prop('disabled', false);
                
                // Submit form immediately
                $('#reportFilterForm').submit();
            <?php endif; ?>
        });
    </script>
</body>
</html>
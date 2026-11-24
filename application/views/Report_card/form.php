<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLIVE ENGLISH SCHOOL & JR. COLLEGE, DHULE</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.2;
        }

        .container {
            width: 95%;
            margin: 0 auto;
            padding: 5px 10px;
            border: 2px solid #000;
            position: relative;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .no-print {
            display: block;
        }

        .btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-back {
            background-color: #f44336;
        }

        .btn-export {
            background-color: #2196F3;
        }

        .school-header {
            font-weight: bold;
            text-align: center;
            font-size: 14px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .student-info {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 8px;
        }

        .info-item {
            display: flex;
            align-items: center;
        }

        .info-label {
            white-space: nowrap;
            margin-right: 5px;
        }

        .info-value {
            flex-grow: 1;
            border-bottom: 1px solid #000;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 0 5px;
            font-weight: bold;
            text-align: center;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .bold {
            font-weight: bold;
            background-color: #f5f5f5;
        }

        .subtotal {
            font-weight: bold;
            background-color: #e9e9e9;
        }

        .footer-section {
            margin-top: 20px;
            font-size: 12px;
            width: 100%;
        }

        .footer-line {
            display: flex;
            width: 80%;
            justify-content: space-between;
        }

        .signature-line {
            margin-top: 20px;
            font-size: 12px;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            body {
                font-size: 11px;
            }

            .container {
                border: none;
                padding: 0;
            }

            table {
                page-break-inside: avoid;
            }

            .student-info {
                grid-template-columns: repeat(4, 25%);
            }

            .footer-section {
                margin-top: 20px;
                font-size: 12px;
                width: 100%;
            }

            .footer-line {
                display: flex;
                width: 80%;
                justify-content: space-between;
            }

            .signature-line {
                margin-top: 20px;
                font-size: 12px;
                width: 100%;
                display: flex;
                justify-content: space-between;
            }

            /* Hide buttons when printing or generating PDF */
            .no-print {
                display: none !important;
            }
        }

        @media screen and (max-width: 768px) {
            .student-info {
                grid-template-columns: repeat(2, 1fr);
            }

            .info-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-value {
                width: 100%;
            }

            .footer-line,
            .signature-line {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                padding: 6px 10px;
                font-size: 12px;
            }
        }

        @media screen and (max-width: 480px) {
            .student-info {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 10px;
            }

            th,
            td {
                padding: 2px;
            }
        }
    </style>
    <!-- Include html2pdf library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="button-container no-print">
            <button class="btn btn-back" onclick="goBack()">Back</button>
            <button class="btn btn-export" onclick="exportToPDF()">Export as PDF</button>
        </div>

        <div class="school-header">
            <h4>OLIVE ENGLISH SCHOOL & JR. COLLEGE, DHULE</h4>
        </div>

        <!-- Rest of your report card content remains the same -->
        <div class="student-info">
            <div class="info-item">
                <span class="info-label">Std.:</span>
                <span class="info-value"><?= ($student['STD'] ?? '') . (!empty($student['DIVISION']) ? '-' . $student['DIVISION'] : '') ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Roll No.:</span>
                <span class="info-value"><?= $student['ROLL_NO'] ?? '_______' ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Student Name:</span>
                <span class="info-value"><?= $student['Full_name'] ?? '____________________________' ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">DOB:</span>
                <span class="info-value"><?= !empty($student['SPRIDEN_birthdate']) ? date('d/m/Y', strtotime($student['SPRIDEN_birthdate'])) : '___________' ?></span>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 15%">Subject</th>
                        <th colspan="3">I<sup>st</sup> Semester</th>
                        <th colspan="3">II<sup>nd</sup> Semester</th>
                        <th rowspan="2">Total<br>A+B</th>
                        <th rowspan="2">Average<br>A+B</th>
                    </tr>
                    <tr>
                        <th>Theory</th>
                        <th>UT / Int Assessment</th>
                        <th>Total</th>
                        <th>Theory</th>
                        <th>UT / Int Assessment</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sem1_grand_total = 0;
                    $sem2_grand_total = 0;
                    $subject_count = 0;
                    $fixed_max_per_semester = 600; // Fixed at 600 per semester
                    $fixed_max_total = 1200; // Fixed total for both semesters

                    $subject_groups = [
                        'Main Subjects' => [
                            'English',
                            'Hindi',
                            'Marathi',
                            'Urdu',
                            'Islamic Studies'
                        ],
                        'Mathematics' => [
                            'Maths',
                            'Maths 1',
                            'Maths 2',
                            'Algebra',
                            'Geometry',
                            'Algebra MCQ',
                            'Geomatry MCQ',
                            'Numbers',
                            'ALL'
                        ],
                        'Science' => [
                            'Science',
                            'Science 1',
                            'Science 2',
                            'Sci I MCQ',
                            'Sci II',
                            'Sci II MCQ',
                            'Physics',
                            'Chemistry',
                            'Biology',
                            'EVS',
                            'EVS 1',
                            'EVS 2',
                            'EVS I',
                            'EVS II',
                            'EVS Oral',
                            'EVS Oracl'
                        ],
                        'Social Studies' => [
                            'History',
                            'Geography',
                            'History & Civics',
                            'History MCQ',
                            'Geography MCQ',
                            'Geography'
                        ],
                        'Computer Studies' => [
                            'Computer',
                            'IT'
                        ],
                        'Creative Arts' => [
                            'Drawing',
                            'Craft',
                            'Copy Writing'
                        ],
                        'Extracurricular Activities' => [
                            'Water Security',
                            'Scout',
                            'Guide',
                            'G.K.'
                        ],
                        'Language Fundamentals' => [
                            'Alphabets'
                        ]
                    ];

                    foreach ($subject_groups as $group => $subjects) {
                        $group_printed = false;
                        $group_sem1_total = 0;
                        $group_sem2_total = 0;
                        $group_count = 0;

                        foreach ($subjects as $subject_name) {
                            $subject = null;
                            foreach ($marks as $mark) {
                                if ($mark['subject_name'] === $subject_name) {
                                    $subject = $mark;
                                    break;
                                }
                            }

                            if (!$subject) continue;

                            // Skip if no marks for this subject
                            if (
                                empty($subject['sem1_written']) && empty($subject['sem1_oral']) &&
                                empty($subject['sem2_written']) && empty($subject['sem2_oral'])
                            ) continue;

                            $sem1_total = $subject['sem1_total'] ?? 0;
                            $sem2_total = $subject['sem2_total'] ?? 0;
                            $grand_total = $subject['grand_total'] ?? 0;
                            $average = $subject['average'] ?? 0;

                            $sem1_grand_total += $sem1_total;
                            $sem2_grand_total += $sem2_total;
                            $group_sem1_total += $sem1_total;
                            $group_sem2_total += $sem2_total;
                            $group_count++;
                            $subject_count++;

                            if (!$group_printed) {
                                echo '<tr class="bold"><td colspan="9">' . htmlspecialchars($group) . '</td></tr>';
                                $group_printed = true;
                            }
                    ?>
                            <tr>
                                <td style="text-align: left"><?= htmlspecialchars($subject_name) ?></td>
                                <td><?= !empty($subject['sem1_written']) ? htmlspecialchars($subject['sem1_written']) : '-' ?></td>
                                <td><?= !empty($subject['sem1_oral']) ? htmlspecialchars($subject['sem1_oral']) : '-' ?></td>
                                <td><?= $sem1_total > 0 ? htmlspecialchars($sem1_total) : '-' ?></td>
                                <td><?= !empty($subject['sem2_written']) ? htmlspecialchars($subject['sem2_written']) : '-' ?></td>
                                <td><?= !empty($subject['sem2_oral']) ? htmlspecialchars($subject['sem2_oral']) : '-' ?></td>
                                <td><?= $sem2_total > 0 ? htmlspecialchars($sem2_total) : '-' ?></td>
                                <td><?= $grand_total > 0 ? htmlspecialchars($grand_total) : '-' ?></td>
                                <td><?= $average > 0 ? htmlspecialchars(number_format($average, 1)) : '-' ?></td>
                            </tr>
                        <?php
                        }

                        if ($group_count > 0) {
                        ?>
                            <tr class="subtotal">
                                <td style="text-align: right">Subtotal:</td>
                                <td colspan="2"></td>
                                <td><?= $group_sem1_total > 0 ? htmlspecialchars($group_sem1_total) : '-' ?></td>
                                <td colspan="2"></td>
                                <td><?= $group_sem2_total > 0 ? htmlspecialchars($group_sem2_total) : '-' ?></td>
                                <td><?= ($group_sem1_total + $group_sem2_total) > 0 ? htmlspecialchars($group_sem1_total + $group_sem2_total) : '-' ?></td>
                                <td><?= ($group_count > 0) ? htmlspecialchars(number_format((($group_sem1_total + $group_sem2_total) / ($fixed_max_per_semester * 2 / count($subject_groups)) * 100), 1) . '%') : '-' ?></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>

                    <tr class="bold">
                        <td colspan="3">Grand Total</td>
                        <td><?= $sem1_grand_total > 0 ? htmlspecialchars($sem1_grand_total) . ' / ' . $fixed_max_per_semester : '-' ?></td>
                        <td colspan="3"></td>
                        <td><?= $sem2_grand_total > 0 ? htmlspecialchars($sem2_grand_total) . ' / ' . $fixed_max_per_semester : '-' ?></td>
                        <td><?= ($subject_count > 0) ? htmlspecialchars(number_format((($sem1_grand_total + $sem2_grand_total) / $fixed_max_total * 100), 1) . '%') : '-' ?></td>
                    </tr>

                    <!-- Grade Subjects -->
                    <tr class="bold">
                        <td colspan="9">Grade Subjects</td>
                    </tr>
                    <?php
                    $grade_subjects = ['Health Edu.', 'Water Security', 'Scout', 'Guide'];
                    foreach ($grade_subjects as $grade_subject) {
                        $subject_data = null;
                        foreach ($marks as $mark) {
                            if ($mark['subject_name'] === $grade_subject) {
                                $subject_data = $mark;
                                break;
                            }
                        }
                    ?>
                        <tr>
                            <td style="text-align: left"><?= htmlspecialchars($grade_subject) ?></td>
                            <td colspan="2"></td>
                            <td><?= !empty($subject_data['sem1_grade']) ? htmlspecialchars($subject_data['sem1_grade']) : '-' ?></td>
                            <td colspan="2"></td>
                            <td><?= !empty($subject_data['sem2_grade']) ? htmlspecialchars($subject_data['sem2_grade']) : '-' ?></td>
                            <td colspan="2"></td>
                        </tr>
                    <?php
                    }
                    ?>

                    <!-- Additional Information -->
                    <tr>
                        <td style="text-align: left">Application</td>
                        <td colspan="8"></td>
                    </tr>
                    <tr>
                        <td style="text-align: left">Remark</td>
                        <td colspan="8"></td>
                    </tr>
                    <tr>
                        <td style="text-align: left">Grade & Percentage Attendance</td>
                        <td colspan="8"></td>
                    </tr>
                </tbody>
            </table>

            <div class="footer-section">
                <div class="footer-line">
                    <span>Passed / Condoned & Promoted to Std. ____________________</span>
                    <span>Detained in Std. ____________________</span>
                    <span>School re-opens on ____________________</span>
                </div>
                <div class="signature-line">
                    <span>Class Teacher Name: ________________________</span>
                    <span>Principal: _________________________________</span>
                    <span>Principal's Sign & School Stamp</span>
                </div>
            </div>
        </div>
    </div>
    <script>
        function goBack() {
            window.location.href = '<?= base_url('Dashboard/getReportCardData') ?>';
        }

        function exportToPDF() {
            // Temporarily hide buttons before generating PDF
            const buttons = document.querySelector('.button-container');
            buttons.style.display = 'none';

            // Get the student name for the filename
            const studentName = '<?= $student["Full_name"] ?? "ReportCard" ?>';
            const filename = studentName.replace(/[^a-z0-9]/gi, '_').toLowerCase() + '_report_card.pdf';

            // Configuration for html2pdf
            const element = document.body;
            const opt = {
                margin: 5,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    logging: true,
                    useCORS: true
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'landscape',
                    compress: true
                }
            };

            // Generate PDF
            html2pdf().set(opt).from(element).save().then(() => {
                // Restore buttons after PDF generation is complete
                buttons.style.display = 'flex';
            });
        }
    </script>
</body>

</html>
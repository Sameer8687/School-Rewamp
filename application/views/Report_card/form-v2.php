<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card - OLIVE ENGLISH SCHOOL & JR. COLLEGE, DHULE</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            line-height: 1.3;
            color: #333;
            background-color: #f9f9f9;
            overflow-x: hidden !important;
            /* ✅ Prevent horizontal scroll */
        }

        .container {
            width: 90%;
            margin: 5px auto;
            padding: 10px;
            border: 3px solid #2c5282;
            border-radius: 8px;
            position: relative;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow-x: hidden;

        }

        .header-container {
            display: flex;
            justify-content: center;
            /* Center the whole header block */
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #2c5282;
            text-align: center;
        }

        .logo-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }


        .logo {
            height: 70px;
            width: auto;
        }

        .school-title {
            text-align: center;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c5282;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-title {
            font-size: 16px;
            color: #e53e3e;
            font-weight: bold;
            margin: 3px 0;
        }

        .academic-year {
            font-size: 13px;
            color: #4a5568;
            margin: 0;
        }

        body .button-container {
            display: flex;
            justify-content: flex-end;
            /* ✅ Align buttons to the right */
            gap: 10px;
            /* Optional: space between buttons */
            margin: 10px;
            /* Optional: spacing below the buttons */
        }


        .no-print {
            display: block;
        }

        .btn {
            padding: 8px 15px;
            background: linear-gradient(to bottom, #4299e1, #2b6cb0);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .btn-back {
            background: linear-gradient(to bottom, #f56565, #c53030);
        }

        .btn-export {
            background: linear-gradient(to bottom, #48bb78, #2f855a);
        }

        .student-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, max-content));
            gap: 8px;
            margin-bottom: 10px;
            background-color: #ebf8ff;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #bee3f8;
        }

        .info-item {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
            min-width: fit-content;
            gap: 30px;
        }


        .info-label {
            white-space: nowrap;
            margin-right: 5px;
            font-weight: bold;
            color: #2b6cb0;
            gap: 30px;

        }

        .info-value {
            flex-grow: 1;
            border-bottom: 1px solid #90cdf4;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 0 3px;
            font-weight: bold;
            text-align: center;
            color: #2d3748;
        }

        .table-responsive {
            width: 100%;
            overflow-x: hidden;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 15px;
            border: 2px solid #2c5282;
        }

        th,
        td {
            border: 1px solid #a0aec0;
            padding: 1px 3px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        th {
            background: linear-gradient(to bottom, #4299e1, #3182ce);
            color: white;
            font-size: 13px;
            border: 1px solid #2b6cb0;
        }

        .subject-group {
            text-align: left;
            font-weight: bold;
            background: linear-gradient(to right, #ebf8ff, #bee3f8);
            color: #e17d28;
            /* padding-left: 10px !important; */
            /* border-left: 3px solid #3182ce; */
        }

        .subtotal {
            font-weight: bold;
            background-color: #e2e8f0;
        }

        .grand-total {
            font-weight: bold;
            background: linear-gradient(to right, #2c5282, #2b6cb0);
            color: white;
        }

        .footer-section {
            margin-top: 12px;
            font-size: 14px;
            width: 100%;
            background-color: #f7fafc;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
            font-weight: 500;
        }

        .footer-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 7px;
            width: 95%;
        }

        .signature-line {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
        }

        .signature {
            width: 30%;
            text-align: center;
        }

        .signature-space {
            border-bottom: 1px solid #718096;
            height: 25px;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        /* Mark coloring */
        /* Mark coloring */
        .excellent {
            color: rgb(25, 134, 76);
            font-weight: bold;
            background-color: #f0fff4;
        }

        .poor {
            color: #e53e3e;
            font-weight: bold;
            background-color: #fff5f5;
        }

        .back-page {
            margin-top: 15px !important;
        }



        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
                marks: crop cross;
                prince-rotate-body-page: 180deg;
                /* For PrinceXML */
            }

            /* For standard browsers */

            .back-page {
                margin-top: 15px !important;
            }

            body {
                font-size: 15px;
                background-color: white;
            }

            .container {
                border: none;
                padding: 0;
                margin: 0;
                width: 100%;
                box-shadow: none;
            }

            table {
                page-break-inside: avoid;
                font-size: 15px;
            }

            th,
            td {
                padding: 0.5px 3px;
            }

            /* Hide buttons when printing */
            .no-print {
                display: none !important;
            }

            /* Ensure background colors print */
            th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: #3182ce !important;
                color: white !important;
            }

            .grand-total {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: #2c5282 !important;
                color: white !important;
            }

            .subject-group {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: #ebf8ff !important;
            }

            .report-card {
                margin-top: 30px !important;
            }

            .header-container {
                margin-top: 20px !important;
            }

            .logo-title {
                margin-top: 30px !important;
            }
        }

        @media screen and (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 9px;
            }

            .student-info {
                grid-template-columns: repeat(2, 1fr);
            }

            .logo-title {
                flex-direction: column;
                align-items: flex-start;
            }

            .school-title {
                text-align: left;
            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>

<body>
    <?php
    $acdm = $this->uri->segment(4);
    $class = $this->uri->segment(5);
    $div = $this->uri->segment(6);
    ?>
    <div class="button-container no-print">
        <button class="btn btn-back" onclick="goBack('<?= $acdm ?>', '<?= $class ?>', '<?= $div ?>')"> Back </button>
        <button class="btn btn-export" onclick="exportToPDF()">Export as PDF</button>
    </div>
    <div class="container">
        <style>
            @page {
                size: A4 landscape;
                margin: 0;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 20px;
                background-color: #f9f9f9;
            }

            .report-card {
                display: flex;
                justify-content: space-between;
                gap: 20px;
                max-width: 100%;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                background: white;
                border-radius: 10px;
                overflow: hidden;

            }

            .left-section,
            .right-section {
                width: 48%;
                padding: 15px 25px;
                min-height: 500px;
                box-sizing: border-box;
            }

            .left-section {
                background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
                border-left: 8px solid #4a6baf;
            }

            .right-section {
                background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                border-right: 8px solid #e74c3c;
            }

            .grades-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                margin: 20px 0;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .grades-table th {
                background: linear-gradient(to right, #4a6baf, #3a56a0);
                color: white;
                padding: 8px;
                font-weight: 600;
                text-align: center;
                font-size: 14px;
            }

            .grades-table td {
                padding: 6px;
                text-align: center;
                font-size: 14px;
                border-bottom: 1px solid #e0e0e0;
            }

            .grades-table tr:nth-child(even) {
                background-color: #f8f9fa;
            }

            .grades-table tr:hover {
                background-color: #e9f0fd;
            }

            .note-box {
                border-radius: 8px;
                padding: 15px;
                margin-top: 30px;
                font-size: 14px;
                background: white;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                border-left: 4px solid #27ae60;
            }

            .note-box strong {
                color: #27ae60;
                font-size: 16px;
            }

            .note-box ol {
                padding-left: 20px;
                margin: 10px 0 0 0;
            }

            .note-box li {
                margin-bottom: 8px;
                color: #555;
            }

            .school-header {
                text-align: center;
                margin-bottom: 20px;
            }

            .logo {
                width: 120px;
                height: 120px;
                /* border-radius: 50%; */
                margin: 0 auto 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                /* border: 3px solid #4a6baf; */
                /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
                background: white;
                overflow: hidden;
            }

            .logo img {
                max-width: 90%;
                max-height: 90%;
            }

            .school-title {
                margin-bottom: 20px;
            }



            .report-title {
                font-size: 20px;
                font-weight: 700;
                color: #e17d28;
                margin: 0;
                line-height: 1.3;
            }

            .school-name {
                font-size: 24px;
                font-weight: 800;
                color: #388cd6;
                margin: 10px 0;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .academic-year {
                font-size: 16px;
                color: #6d99bf;
                font-weight: 600;
                margin-bottom: 15px;
            }

            .info {
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                margin-top: 20px;
            }

            .info p {
                margin: 12px 0;
                font-size: 15px;
                padding-bottom: 8px;
                border-bottom: 1px dashed #dbd4d4;
                color: #34495e;
            }

            .info strong {
                color: #2c3e50;
                min-width: 120px;
                display: inline-block;
            }

            .front-footer {
                margin-top: 30px;
                text-align: center;
                color: #7f8c8d;
                font-size: 14px;
                padding-top: 15px;
                border-top: 1px solid #eee;
            }

            .front-footer hr {
                border: none;
                height: 1px;
                background: linear-gradient(to right, transparent, #7f8c8d, transparent);
                margin: 10px 0;
            }

            .watermark {
                position: absolute;
                opacity: 0.1;
                font-size: 120px;
                color: #4a6baf;
                transform: rotate(-30deg);
                z-index: -1;
                pointer-events: none;
            }
        </style>

        <div class="report-card">
            <!-- LEFT SECTION -->
            <div class="left-section">
                <div class="watermark" style="top: 150px; left: 50px;">GRADES</div>

                <table class="grades-table">
                    <thead>
                        <tr>
                            <th>Percentages</th>
                            <th>Grade</th>
                            <th>Out of</th>
                            <th>Passing</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>75% and above</td>
                            <td>A - Excellent</td>
                            <td>100</td>
                            <td>35</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>80</td>
                            <td>28</td>
                        </tr>
                        <tr>
                            <td>60% - 74%</td>
                            <td>B - Very Good</td>
                            <td>75</td>
                            <td>26</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>50</td>
                            <td>17</td>
                        </tr>
                        <tr>
                            <td>50% - 59%</td>
                            <td>C - Good</td>
                            <td>40</td>
                            <td>14</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>25</td>
                            <td>09</td>
                        </tr>
                        <tr>
                            <td>35% - 49%</td>
                            <td>D - Satisfactory</td>
                            <td>20</td>
                            <td>07</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>15</td>
                            <td>05</td>
                        </tr>
                        <tr>
                            <td>0% - 34%</td>
                            <td>E - Not Satisfactory</td>
                            <td>10</td>
                            <td>04</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>05</td>
                            <td>02</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box">
                    <p><strong>N. B.</strong></p>
                    <ol>
                        <li>All students must be present on the re-opening day.</li>
                        <li>This progress report, duly countersigned by the guardian, must be shown to the class teacher on the school re-opening day.</li>
                        <li>No changes should be made to this report card without the notice of the school management.</li>
                    </ol>
                </div>
            </div>

            <!-- RIGHT SECTION -->
            <div class="right-section">
                <div class="watermark" style="top: 150px; right: 50px;">PROGRESS</div>

                <div class="school-header">
                    <div class="logo">
                        <!-- Replace with your actual logo -->
                        <img src="<?= base_url('assets/images/olive.jpg') ?>" alt="School Logo">
                    </div>
                    <div class="school-title">
                        <h1 class="school-name">OLIVE ENGLISH MEDIUM SCHOOL <br>& JUNIOR COLLEGE, DHULE</h1>
                        <div class="report-title">ANNUAL PROGRESS CARD <?= $acdm_year['stvacdm_year'] ?></div>
                        <!-- <div class="academic-year">Academic Year: <?= $acdm_year['stvacdm_year'] ?></div> -->
                    </div>
                </div>
                <div class="info">

                    <p><strong>Name :</strong> <span><strong style="color: linear-gradient(to right, #4a6baf, #3a56a0);"><?= !empty($student['Full_name']) ? $student['Full_name'] : '-' ?></strong></span></p>
                    <p><strong>Standard:</strong><span><strong style="color: linear-gradient(to right, #4a6baf, #3a56a0);"> <?= !empty($student['STD']) ? $student['STD'] : '-' ?></span></strong></p>
                    <p><strong>Division :</strong> <span><strong style="color: linear-gradient(to right, #4a6baf, #3a56a0);"><?= !empty($student['DIVISION']) ? $student['DIVISION'] : '-' ?></strong></span></p>
                    <p><strong>Roll No. :</strong><span><strong style="color: linear-gradient(to right, #4a6baf, #3a56a0);"><?= !empty($student['ROLL_NO']) ? $student['ROLL_NO'] : '-' ?></strong></span></p>
                    <p><strong>Date of Birth :</strong><span><strong style="color: linear-gradient(to right, #4a6baf, #3a56a0);"><?= !empty($student['SPRIDEN_birthdate']) ? date('d/m/Y', strtotime($student['SPRIDEN_birthdate'])) : $student['SPRIDEN_birthdate'] ?></strong></span></p>
                    <p><strong>Gen. Reg. No. :</strong> <span><strong style="color: linear-gradient(to right, #4a6baf, #3a56a0);"><?= !empty($student['spriden_gr_number']) ? $student['spriden_gr_number'] : '-' ?></strong></span></p>
                </div>

                <div class="front-footer">
                    <p style="color: #4a6baf; font-weight: 600;">Address: NH-3, Behind Traffic Police Station, Dhule - 424001</p>
                    <p style="font-size: 10px; color: #95a5a6;">"Education is the most powerful weapon which you can use to change the world." </p>
                </div>
            </div>
        </div>

        <div class="header-container back-page">
            <div class="logo-title">
                <img class="logo" src="<?= base_url('assets/images/olive.jpg') ?>" alt="School Logo">
                <div class="school-title">
                    <h1 class="school-name">OLIVE ENGLISH MEDIUM SCHOOL & JR. COLLEGE, DHULE</h1>
                    <div class="report-title">ANNUAL REPORT CARD <?= $acdm_year['stvacdm_year'] ?></span></div>
                    <!-- <div class="academic-year">Academic Year: <?= $acdm_year['stvacdm_year'] ?></div> -->
                </div>
            </div>
        </div>
        <div class="student-info" style="display: none;">
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
                        <th rowspan="2" style="width: 18%;">Subject</th>
                        <th colspan="2">Theory</th>
                        <th colspan="3">UT/Internal</th>
                        <th colspan="3">Total Marks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sem2_grand_total = 0;
                    $subject_count = 0;
                    $fixed_max_per_semester = 600;

                    // Grace marks system (10 marks total)
                    $total_grace_marks = 10;
                    $grace_marks_used = 0;
                    $grace_marks_allocation = [];

                    $subject_groups = [
                        'Main Subjects' => ['English', 'Hindi', 'Marathi', 'Urdu', 'Islamic Studies'], // 100 marks (passing 35)
                        'Mathematics' => ['Maths', 'Maths 1', 'Maths 2', 'Algebra', 'Geometry', 'Algebra MCQ', 'Geomatry MCQ', 'Numbers', 'ALL'], // 50 marks (passing 18)
                        'Science' => ['Science', 'Science 1', 'Science 2', 'Sci I MCQ', 'Sci II', 'Sci II MCQ', 'Physics', 'Chemistry', 'Biology', 'EVS', 'EVS 1', 'EVS 2', 'EVS I', 'EVS II', 'EVS Oral', 'EVS Oracl'],
                        'Social Studies' => ['History', 'Geography', 'History & Civics', 'History MCQ', 'Geography MCQ'],
                        'Computer Studies' => ['Computer', 'IT'],
                        'Creative Arts' => ['Drawing', 'Craft', 'Copy Writing'],
                        'Extracurricular' => ['Water Security', 'Scout', 'Guide', 'G.K.'],
                        'Language' => ['Alphabets']
                    ];

                    // 1. First pass: Identify all failing subjects and needed grace marks
                    $failing_subjects = [];
                    foreach ($subject_groups as $group => $subjects) {
                        foreach ($subjects as $subject_name) {
                            $subject = null;
                            foreach ($marks as $mark) {
                                if ($mark['subject_name'] === $subject_name) {
                                    $subject = $mark;
                                    break;
                                }
                            }

                            if (!$subject || (empty($subject['sem2_written']) && empty($subject['sem2_oral']))) continue;

                            $sem2_total = $subject['sem2_total'] ?? 0;
                            $is_main_subject = in_array($subject_name, $subject_groups['Main Subjects']);
                            $max_marks = $is_main_subject ? 100 : 50;
                            $passing_marks = $is_main_subject ? 35 : 18; // 35 for 100, 18 for 50

                            if ($sem2_total < $passing_marks) {
                                $needed_grace = $passing_marks - $sem2_total;
                                $failing_subjects[] = [
                                    'name' => $subject_name,
                                    'marks' => $sem2_total,
                                    'needed_grace' => $needed_grace,
                                    'is_main' => $is_main_subject,
                                    'max_marks' => $max_marks,
                                    'passing_marks' => $passing_marks
                                ];
                            }
                        }
                    }

                    // 2. Sort failing subjects by needed grace (smallest first) to maximize passed subjects
                    usort($failing_subjects, function ($a, $b) {
                        return $a['needed_grace'] - $b['needed_grace'];
                    });

                    // 3. Allocate grace marks to subjects needing the least marks first
                    foreach ($failing_subjects as $subject) {
                        if ($grace_marks_used >= $total_grace_marks) break;

                        $marks_needed = $subject['needed_grace'];
                        $marks_available = $total_grace_marks - $grace_marks_used;

                        if ($marks_needed <= $marks_available) {
                            $grace_marks_allocation[$subject['name']] = $marks_needed;
                            $grace_marks_used += $marks_needed;
                        }
                    }

                    // 4. Prepare combined subjects data (Algebra+Geometry, History+Geography, Science1+Science2)
                    $combined_marks = [];
                    foreach (['Algebra', 'Geometry', 'History', 'Geography', 'Science 1', 'Science 2'] as $combined_subject) {
                        foreach ($marks as $mark) {
                            if ($mark['subject_name'] === $combined_subject) {
                                $combined_marks[$combined_subject] = $mark['sem2_total'] ?? 0;
                                break;
                            }
                        }
                    }

                    // Calculate combined totals with grace marks
                    $combined_totals = [
                        'Maths' => ($combined_marks['Algebra'] + ($grace_marks_allocation['Algebra'] ?? 0)) +
                            ($combined_marks['Geometry'] + ($grace_marks_allocation['Geometry'] ?? 0)),
                        'Social Studies' => ($combined_marks['History'] + ($grace_marks_allocation['History'] ?? 0)) +
                            ($combined_marks['Geography'] + ($grace_marks_allocation['Geography'] ?? 0)),
                        'Science' => ($combined_marks['Science 1'] + ($grace_marks_allocation['Science 1'] ?? 0)) +
                            ($combined_marks['Science 2'] + ($grace_marks_allocation['Science 2'] ?? 0))
                    ];

                    // 5. Final pass to display all subjects
                    $failed_subjects_count = 0;
                    $failed_subjects_list = [];
                    $combined_subjects_processed = [];

                    foreach ($subject_groups as $group => $subjects) {
                        $group_printed = false;
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
                            if (empty($subject['sem2_written']) && empty($subject['sem2_oral'])) continue;

                            $sem2_total = $subject['sem2_total'] ?? 0;
                            $is_main_subject = in_array($subject_name, $subject_groups['Main Subjects']);
                            $max_marks = $is_main_subject ? 100 : 50;
                            $passing_marks = $is_main_subject ? 35 : 18;

                            // Get grace marks for this subject
                            $grace_marks = $grace_marks_allocation[$subject_name] ?? 0;
                            $adjusted_total = $sem2_total + $grace_marks;

                            // Prepare display text
                            $display_marks = $sem2_total > 0 ? htmlspecialchars($sem2_total) . ' / ' . $max_marks : '-';
                            if ($grace_marks > 0) {
                                $display_marks = htmlspecialchars($sem2_total) .
                                    '<span class="grace-marks">+' . $grace_marks . '</span> = ' .
                                    $adjusted_total . ' / ' . $max_marks;
                            }

                            // Determine pass/fail status and color
                            $mark_class = '';
                            $is_failed = false;

                            if (in_array($subject_name, ['Algebra', 'Geometry'])) {
                                if (!in_array('Maths', $combined_subjects_processed)) {
                                    $is_failed = $combined_totals['Maths'] < 35;
                                    if ($is_failed) {
                                        $failed_subjects_count++;
                                        $failed_subjects_list[] = 'Maths';
                                        $combined_subjects_processed[] = 'Maths';
                                    }
                                }
                                $mark_class = $combined_totals['Maths'] >= 35 ? 'excellent' : 'poor';
                            } elseif (in_array($subject_name, ['History', 'Geography'])) {
                                if (!in_array('Social Studies', $combined_subjects_processed)) {
                                    $is_failed = $combined_totals['Social Studies'] < 35;
                                    if ($is_failed) {
                                        $failed_subjects_count++;
                                        $failed_subjects_list[] = 'Social Studies';
                                        $combined_subjects_processed[] = 'Social Studies';
                                    }
                                }
                                $mark_class = $combined_totals['Social Studies'] >= 35 ? 'excellent' : 'poor';
                            } elseif (in_array($subject_name, ['Science 1', 'Science 2'])) {
                                if (!in_array('Science', $combined_subjects_processed)) {
                                    $is_failed = $combined_totals['Science'] < 35;
                                    if ($is_failed) {
                                        $failed_subjects_count++;
                                        $failed_subjects_list[] = 'Science';
                                        $combined_subjects_processed[] = 'Science';
                                    }
                                }
                                $mark_class = $combined_totals['Science'] >= 35 ? 'excellent' : 'poor';
                            } else {
                                // Regular subjects
                                $is_failed = $adjusted_total < $passing_marks;
                                if ($is_failed) {
                                    $failed_subjects_count++;
                                    $failed_subjects_list[] = $subject_name;
                                }
                                $mark_class = $adjusted_total >= $passing_marks ? 'excellent' : 'poor';
                            }

                            // Add to totals (using adjusted marks if grace applied)
                            $sem2_grand_total += ($grace_marks > 0) ? $adjusted_total : $sem2_total;
                            $group_sem2_total += ($grace_marks > 0) ? $adjusted_total : $sem2_total;
                            $group_count++;

                            if (!$group_printed) {
                                echo '<tr><td class="subject-group" colspan="9">' . htmlspecialchars($group) . '</td></tr>';
                                $group_printed = true;
                            }
                    ?>
                            <tr>
                                <td style="text-align: left;"><?= htmlspecialchars($subject_name) ?></td>
                                <td colspan="2"><?= !empty($subject['sem2_written']) ? htmlspecialchars($subject['sem2_written']) : '-' ?></td>
                                <td colspan="3"><?= !empty($subject['sem2_oral']) ? htmlspecialchars($subject['sem2_oral']) : '-' ?></td>
                                <td colspan="3" class="<?= $mark_class ?>">
                                    <?= $display_marks ?>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>

                    <!-- Grace marks summary -->
                    <?php if ($grace_marks_used > 0): ?>
                        <tr class="grace-summary">
                            <td style="text-align: right;">Grace Marks Used:</td>
                            <td colspan="5"></td>
                            <td colspan="3"><?= $grace_marks_used ?> out of <?= $total_grace_marks ?></td>
                        </tr>
                    <?php endif; ?>

                    <tr class="grand-total">
                        <td style="text-align: right;">Grand Total:</td>
                        <td colspan="5"></td>
                        <td colspan="3"><?= $sem2_grand_total > 0 ? htmlspecialchars($sem2_grand_total) . ' / ' . $fixed_max_per_semester : '-' ?></td>
                    </tr>

                    <!-- Grade Subjects -->
                    <tr>
                        <td class="subject-group" colspan="9">Grade Subjects</td>
                    </tr>
                    <?php
                    $grade_subjects = ['Health Edu.', 'Water Security', 'Scout/Guide'];
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
                            <td style="text-align: left;"><?= htmlspecialchars($grade_subject) ?></td>
                            <td colspan="5"></td>
                            <td colspan="3" class="text-success"><?= !empty($subject_data['sem2_grade']) ? htmlspecialchars($subject_data['sem2_grade']) : 'A' ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

            <?php
            // Promotion status determination
            $promotionStatus = '';
            $promotionClass = '';

            if ($failed_subjects_count > 0) {
                $promotionStatus = $student['STD'] . ' - ' . $student['DIVISION'];
                $promotionClass = 'text-danger text-center';
            } else {
                $promotionStatus = 'X - ' . $student['DIVISION'];
                $promotionClass = 'text-success text-center';
            }
            ?>

            <div class="footer-section">
                <div class="footer-line">
                    <?php if ($failed_subjects_count > 0): ?>
                        <span style="letter-spacing: 3px;font-size: large;"><strong class="<?= $promotionClass ?>">Failed & Detained in Std. <?= htmlspecialchars($promotionStatus) ?></strong></span>
                        <!-- <span>Passed & Promoted to Std. <strong>-</strong></span> -->
                        <!-- <span>School reopens on <strong>16-06-2025</strong></span> -->
                        <span><strong style="letter-spacing: 3px;font-size: large;" class="text-danger">Please contact school immediately</strong></span>

                    <?php else: ?>
                        <span style="letter-spacing: 3px;font-size: large;"><strong class="<?= $promotionClass ?>">Passed & Promoted to Std. <?= htmlspecialchars($promotionStatus) ?></strong></span>
                        <!-- <span>Failed & Detained in Std. <strong>-</strong></span> -->
                        <span style="font-size: large;">School reopens on <strong>Tuesday 20/05/25</strong></span>

                    <?php endif; ?>
                </div>
                <div class="signature-line">
                    <div class="signature">
                        <div class="signature-space"><?=$student['TEACHER']?></div>
                        <div>Class Teacher</div>
                    </div>
                    <!-- <div class="signature">
                        <div class="signature-space"></div>
                        <div>Principal</div>
                    </div> -->
                    <div class="signature">
                        <div class="signature-space"><img style="margin-top: -35px;" height="60" src="<?=base_url('assets/images/principal.png')?>"></div>
                        <div>Principal's Sign & Stamp</div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* Color coding */
            .excellent {
                color: #28a745;
                /* Green for passing */
                font-weight: bold;
                background-color: #f0fff4;
            }

            .poor {
                color: #dc3545;
                /* Red for failing */
                font-weight: bold;
                background-color: #fff5f5;
            }

            .grace-marks {
                color: #6f42c1;
                /* Purple for grace marks */
                font-weight: bold;
            }

            .grace-summary td {
                font-weight: bold;
                background-color: #f8f9fa;
            }

            .text-success {
                color: #28a745;
                font-weight: bold;
            }

            .text-danger {
                color: #dc3545;
                font-weight: bold;
            }
        </style>
    </div>

    </div>
    <script>
        function goBack(acdm, classVal, div) {
            window.location.href = '<?= base_url('Dashboard/getReportCardData') ?>?acdm=' + acdm + '&class=' + classVal + '&div=' + div;
        }

        function exportToPDF() {
            const buttons = document.querySelector('.button-container');
            buttons.style.display = 'none';

            const studentName = '<?= $student["Full_name"] ?? "ReportCard" ?>';
            const filename = studentName.replace(/[^a-z0-9]/gi, '_').toLowerCase() + '_report_card.pdf';

            const element = document.body;
            const opt = {
                margin: 0,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    letterRendering: true,
                    useCORS: true,
                    allowTaint: true
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'landscape'
                }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                buttons.style.display = 'flex';
            });
        }
    </script>
</body>

</html>
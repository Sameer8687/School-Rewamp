<!DOCTYPE html>
<html>
<head>
    <title>Report Card</title>
    <style>
        body { font-family: Arial; font-size: 14px; }
        .main-box { border: 2px solid black; padding: 10px; }
        .school-title { text-align: center; font-weight: bold; font-size: 18px; }
        .student-info { margin-top: 10px; }
        .semester-table { width: 100%; }
        .semester-table td { vertical-align: top; width: 50%; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid black; padding: 4px; text-align: center; }
        .footer { margin-top: 20px; }
        .sign { width: 33%; text-align: center; float: left; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="main-box">
        <div class="school-title">OLIVE ENGLISH SCHOOL & JR. COLLEGE, DHULE</div>

        <div class="student-info">
            Std : <?php echo $student['STD'] ?? ''; ?> <br>
            Roll No : <?php echo $student['ROLL_NO'] ?? ''; ?> <br>
            Full Name : <?php echo $student['Full_name'] ?? ''; ?> <br>
            DOB : <?php echo $student['SPRIDEN_birthdate'] ?? ''; ?>
        </div>

        <h3 style="text-align:center;">ACHIEVEMENT OF THE STUDENT</h3>

        <table class="semester-table">
            <tr>
                <td>
                    <h4 style="text-align:center;">First Semester</h4>
                    <table class="table">
                        <tr>
                            <th>Sr No</th>
                            <th>Subject</th>
                            <th>Grade</th>
                            <th>Descriptive Notes</th>
                        </tr>
                        <?php $i = 1; foreach ($marks as $row): ?>
                            <?php if (!empty($row['subject_name'])): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $row['subject_name']; ?></td>
                                    <td><?php echo $row['sem1_grade']; ?></td>
                                    <td></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <tr><td colspan="4">Attendance</td></tr>
                    </table>
                </td>

                <td>
                    <h4 style="text-align:center;">Second Semester</h4>
                    <table class="table">
                        <tr>
                            <th>Sr No</th>
                            <th>Subject</th>
                            <th>Grade</th>
                            <th>Descriptive Notes</th>
                        </tr>
                        <?php $i = 1; foreach ($marks as $row): ?>
                            <?php if (!empty($row['subject_name'])): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $row['subject_name']; ?></td>
                                    <td><?php echo $row['sem2_grade']; ?></td>
                                    <td></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <tr><td colspan="4">Attendance</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="footer">
            <div class="sign">Class Teacher's sign</div>
            <div class="sign">Principal's sign</div>
            <div class="sign">Parent's sign</div>
            <div class="clear"></div>
        </div>

        <br><br>
        Promoted to Std ________
        School re-opens on _________________
        Timing  ______________
        School Stamp
    </div>
</body>
</html>
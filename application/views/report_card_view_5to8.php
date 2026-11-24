<!DOCTYPE html>
<html>
<head>
    <title>Report Card</title>
    <style>
        body { font-family: Arial; font-size: 13px; }
        .main { border: 2px solid black; padding: 10px; }
        .title { text-align: center; font-weight: bold; font-size: 18px; }
        .student { margin-top: 10px; }
        .flex { display: flex; }
        .term { width: 40%; }
        .grade-box { width: 20%; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 4px; text-align: center; }
        .sign { width: 33%; float: left; text-align: center; margin-top: 10px; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="main">
        <div class="title">OLIVE ENGLISH SCHOOL & JR. COLLEGE, DHULE</div>

        <div class="student">
            Std : <?php echo $student['STD'] ?? ''; ?><br>
            Roll No : <?php echo $student['ROLL_NO'] ?? ''; ?><br>
            Full Name : <?php echo $student['Full_name'] ?? ''; ?><br>
            DOB : <?php echo $student['SPRIDEN_birthdate'] ?? ''; ?>
        </div>

        <h3 style="text-align:center">ACHIEVEMENT OF THE STUDENT</h3>

        <div class="flex">
            <!-- TERM 1 -->
            <div class="term">
                <h4 style="text-align:center">TERM I</h4>
                <table>
                    <tr><th>Sr</th><th>Subject</th><th>Grade</th><th>Remarks</th></tr>
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
                </table>
            </div>

            <!-- TERM 2 -->
            <div class="term">
                <h4 style="text-align:center">TERM II</h4>
                <table>
                    <tr><th>Sr</th><th>Subject</th><th>Grade</th><th>Remarks</th></tr>
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
                </table>
            </div>

            <!-- GRADING SCALE -->
            <div class="grade-box">
                <h4 style="text-align:center">Grades</h4>
                <table>
                    <tr><th>%</th><th>Grade</th></tr>
                    <tr><td>91% to 100%</td><td>A-1</td></tr>
                    <tr><td>81% to 90%</td><td>A-2</td></tr>
                    <tr><td>71% to 80%</td><td>B-1</td></tr>
                    <tr><td>61% to 70%</td><td>B-2</td></tr>
                    <tr><td>51% to 60%</td><td>C-1</td></tr>
                    <tr><td>41% to 50%</td><td>C-2</td></tr>
                    <tr><td>33% to 40%</td><td>D</td></tr>
                    <tr><td>21% to 32%</td><td>E-1</td></tr>
                    <tr><td>20% & below</td><td>E-2</td></tr>
                </table>
            </div>
        </div>

        <div class="sign">Teacher's Sign</div>
        <div class="sign">Principal's Sign</div>
        <div class="sign">Parent's Sign</div>
        <div class="clear"></div>

        <!-- ATTENDANCE -->
        <h4>ATTENDANCE</h4>
        <table>
            <tr>
                <th>Months</th><th>Jun</th><th>Jul</th><th>Aug</th><th>Sep</th><th>Oct</th><th>Nov</th><th>Dec</th><th>Jan</th><th>Feb</th><th>Mar</th><th>Apr</th>
            </tr>
            <tr><td>Working Days</td><?php for($k=0;$k<11;$k++) echo "<td></td>"; ?></tr>
            <tr><td>Attendance</td><?php for($k=0;$k<11;$k++) echo "<td></td>"; ?></tr>
        </table>

        <br>
        Promoted to Std ________ 
        School re-opens on ___________________ 
        Timing ________________ 
        School Stamp
    </div>
</body>
</html>
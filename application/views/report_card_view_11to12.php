<!DOCTYPE html>
<html>
<head>
    <title>Report Card</title>
    <style>
        body { font-family: Arial; font-size: 13px; }
        .main { border: 2px solid black; padding: 10px; }
        .title { text-align: center; font-weight: bold; font-size: 18px; }
        .top-info { margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 4px; text-align: center; }
        .left { text-align: left; }
        .sign { width: 33%; float: left; text-align: center; margin-top: 10px; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="main">
        <div class="title">OLIVE ENGLISH SCHOOL & JR. COLLEGE, DHULE</div>

        <div class="top-info">
            Std : <?php echo $student['STD'] ?? ''; ?>
            <span style="margin-left:40px;">Roll No : <?php echo $student['ROLL_NO'] ?? ''; ?></span>
            <span style="margin-left:40px;">Full Name : <?php echo $student['Full_name'] ?? ''; ?></span>
            <span style="float:right;">DOB <?php echo $student['SPRIDEN_birthdate'] ?? ''; ?></span>
        </div>

        <br>

        <table>
            <tr>
                <th rowspan="2">Subject</th>
                <th colspan="3">1st Semester</th>
                <th colspan="3">2nd Semester</th>
                <th rowspan="2">Total<br>A+B</th>
                <th rowspan="2">Average<br>(A+B)/2</th>
            </tr>
            <tr>
                <th>Theory</th>
                <th>UT / Int</th>
                <th>Total</th>
                <th>Theory</th>
                <th>UT / Int</th>
                <th>Total</th>
            </tr>

            <?php
            $grand_total = 0;
            foreach ($marks as $row):
                if (empty($row['subject_name'])) continue;
                $row_total = ($row['sem1_total'] ?? 0) + ($row['sem2_total'] ?? 0);
            ?>
                <tr>
                    <td class="left"><?php echo $row['subject_name']; ?></td>

                    <!-- Sem 1 -->
                    <td><?php echo $row['sem1_written']; ?></td>
                    <td><?php echo $row['sem1_oral']; ?></td>
                    <td><strong><?php echo $row['sem1_total']; ?></strong></td>

                    <!-- Sem 2 -->
                    <td><?php echo $row['sem2_written']; ?></td>
                    <td><?php echo $row['sem2_oral']; ?></td>
                    <td><strong><?php echo $row['sem2_total']; ?></strong></td>

                    <td><strong><?php echo $row_total; ?></strong></td>
                    <td><strong><?php echo number_format($row['average'], 2); ?></strong></td>
                </tr>
            <?php endforeach; ?>

            <tr>
                <td class="left"><b>Grand Total</b></td>
                <td colspan="8"></td>
            </tr>

            <tr><td class="left">Application</td><td colspan="8"></td></tr>
            <tr><td class="left">Remark</td><td colspan="8"></td></tr>
            <tr><td class="left">Grade & Percentage</td><td colspan="8"></td></tr>
            <tr><td class="left">Attendance</td><td colspan="8"></td></tr>
        </table>

        <br>

        <div class="sign">Class Teacher's Sign</div>
        <div class="sign">Parent's Sign</div>
        <div class="sign">Principal</div>
        <div class="clear"></div>

        <br><br>

        Passed / Promoted to Std ________ <span style="margin-left:10px;">Detained in Std _________</span>
        <span style="margin-left:10px;">School re-opens on _______________________</span>

        <br><br>

        Class Teacher ___________________________
        <span style="margin-left:50px;">Principal ________________________________________</span>
    </div>
</body>
</html>
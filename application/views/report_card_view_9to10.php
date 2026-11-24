<!DOCTYPE html>
<html>
<head>
    <title>Report Card</title>
    <style>
        body { font-family: Arial; font-size: 13px; }
        .main { border: 2px solid black; padding: 10px; }
        .title { text-align: center; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 4px; text-align: center; }
        .header-info { margin-bottom: 10px; }
        .sign { width: 16%; float: left; text-align: center; margin-top: 10px; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="main">
        <div class="header-info">
            Full Name : <?php echo $student['Full_name'] ?? ''; ?>
            <span style="float:right">Roll No: <?php echo $student['ROLL_NO'] ?? ''; ?></span>
        </div>

        <table>
            <tr>
                <th rowspan="2">Subject</th>
                <th colspan="2">Ist Semester (A)</th>
                <th colspan="2">IInd Semester (B)</th>
                <th rowspan="2">Average<br>(A+B)/2</th>
            </tr>
            <tr>
                <th>Theory</th>
                <th>Total</th>
                <th>Theory</th>
                <th>Total</th>
            </tr>

            <?php
            $grand_total = 0;
            foreach ($marks as $row):
                if (empty($row['subject_name'])) continue;
            ?>
                <tr>
                    <td><?php echo $row['subject_name']; ?></td>

                    <!-- Sem 1 -->
                    <td><?php echo $row['sem1_marks']; ?></td>
                    <td><strong><?php echo $row['sem1_total']; ?></strong></td>

                    <!-- Sem 2 -->
                    <td><?php echo $row['sem2_marks']; ?></td>
                    <td><strong><?php echo $row['sem2_total']; ?></strong></td>

                    <!-- Average -->
                    <td><strong><?php echo number_format($row['average'], 2); ?>%</strong></td>
                </tr>
            <?php endforeach; ?>

            <tr>
                <td><b>Grand Total</b></td>
                <td colspan="7"></td>
            </tr>
            <tr>
                <td><b>Grade & Percentage</b></td>
                <td colspan="7"></td>
            </tr>
        </table>

        <div class="sign">Teacher's Sign</div>
        <div class="sign">Principal's Sign</div>
        <div class="sign">Parent's Sign</div>
        <div class="clear"></div>

        <br>

        <!-- ATTENDANCE -->
        <table>
    <tr>
        <th>Month</th>
        <th>Jun</th><th>Jul</th><th>Aug</th><th>Sep</th>
        <th>Oct</th><th>Nov</th><th>Dec</th>
        <th>Jan</th><th>Feb</th><th>Mar</th>
        <th>Apr</th><th>May</th>
    </tr>

    <tr>
        <td>Working Days</td>
        <?php for($k=0;$k<12;$k++) echo "<td></td>"; ?>
    </tr>

    <tr>
        <td>Attendance</td>
        <?php for($k=0;$k<12;$k++) echo "<td></td>"; ?>
    </tr>

    <tr>
        <td>Parent's Sign.</td>
        <!-- <?php for($k=0;$k<12;$k++) echo "<td></td>"; ?> -->
            <td colspan="3"></td>
            <td colspan="3"></td>
            <td colspan="3"></td>
            <td colspan="3"></td>
    </tr>
</table>

        <br>
        Promoted to Std ________________
        School re-opens on ___________________
        Timing _______________
        
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Result UI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .border-table td,
        .border-table th {
            border: 1px solid #000;
        }


        .border-table {
            border-collapse: collapse;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <!-- <?php print_r($marks);
        ?> -->
        <div class="text-center mb-3">
            <h4><b>Olive English School & Junior College</b></h4>
            <h6>XI Science Annual Result 2025-26</h6>
            <h6><b>Statement of Marks</b></h6>
        </div>

        <!-- Student Info -->
        <table class="table border-table text-center">
            <tr>
                <th>Roll No.</th>
                <th>Name of Student</th>
                <th>Exam Month & Year</th>
            </tr>
            <tr>
                <td>01</td>
                <td><?php echo $student['Full_name'] ?? ''; ?></td>
                <td>March-2026</td>
            </tr>
        </table>

        <!-- MARKS TABLE -->
        <table class="table border-table text-center">

            <!-- SUBJECT ROW -->
            <tr>
                <th>Subject</th>
                <?php foreach ($marks as $row): ?>
                    <td><?= $row['subject_name']; ?></td>
                <?php endforeach; ?>
                <th>Total</th>
                <th>Percentage</th>
            </tr>

            <!-- MAX MARKS -->
            <tr>
                <th>Max Marks</th>
                <?php
                $grand_total = 0;
                foreach ($marks as $row):
                    $max = $row['prelim_total'];  // Direct key access
                    $grand_total += $max;
                    ?>
                    <td><?= $max; ?></td>
                <?php endforeach; ?>
                <td><?= $grand_total; ?></td>
                <td>Out of <?= $grand_total; ?></td>
            </tr>

            <!-- OBTAINED MARKS -->
            <tr>
                <th>Marks Obtain</th>
                <?php
                $total = 0;
                foreach ($marks as $row):
                    $obt = $row['prelim_marks'];  // Direct key access
                    $total += $obt;
                    ?>
                    <td><?= $obt; ?></td>
                <?php endforeach; ?>
                <td><?= $total; ?></td>
                <td>
                    <?php
                    $percentage = ($grand_total > 0) ? ($total / $grand_total) * 100 : 0;
                    echo number_format($percentage, 2) . '%';
                    ?>
                </td>
            </tr>

        </table>

        <!-- Remarks -->
        <table class="table border-table text-center">
            <tr>
                <th>Remark</th>
                <th>Eligible to Std XII Science</th>
            </tr>
            <tr>
                <td>Progress: Good</td>
                <td>Yes</td>
            </tr>
            <tr>
                <td>Conduct: Good</td>
                <td></td>
            </tr>
        </table>

        <div class="d-flex justify-content-between mt-5">
            <div>Date: <?= date('d-m-Y'); ?></div>
            <div>Class Teacher</div>
            <div>Principal</div>
        </div>

    </div>


</body>

</html>
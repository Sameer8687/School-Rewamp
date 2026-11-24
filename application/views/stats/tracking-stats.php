<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Activity Tracking Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f7f7f7; }
        .bg-legacy { background-color: #26577C; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-legacy">
    <div class="container-fluid py-0">
        <a class="navbar-brand text-white" href="#">Teacher Activity Tracking Report</a>
    </div>
</nav>

<div class="container-fluid mt-3">
    <div class="row mb-3">
        <div class="col-md-3">
            <label>Year:</label>
            <select id="year" class="form-control">
                <?php foreach ($academic_year as $acdm): ?>
                    <option value="<?= $acdm['stvacdm_id'] ?>" <?= ($acdm['stvacdm_id'] == $year) ? 'selected' : '' ?>>
                        <?= $acdm['stvacdm_year'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label>From Date:</label>
            <input type="date" id="from_date" class="form-control" value="<?= $from_date ?>">
        </div>

        <div class="col-md-3">
            <label>To Date:</label>
            <input type="date" id="to_date" class="form-control" value="<?= $to_date ?>">
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button id="filter_button" class="btn btn-primary w-100">View Data</button>
        </div>
    </div>

    <?php if (!empty($activities)): ?>
        <?php foreach ($activities as $activity): ?>
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5><?= "{$activity['teacher_id']} - {$activity['teacher_name']}" ?></h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <thead class="bg-legacy text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Activity Date</th>
                                    <th>Activity Time</th>
                                    <th>Activity Type</th>
                                    <th>Action Date</th>
                                    <th>Total Students</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; foreach ($activity['activities'] as $act): ?>
                                    <tr>
                                        <td><?= $count++ ?></td>
                                        <td><?= date('d-M-Y', strtotime($act['activity_date'])) ?></td>
                                        <td><?= $act['activity_time'] ?></td>
                                        <td><span class="badge bg-light text-dark"><?= strtoupper($act['activity_sorce']) ?> <span class="text-success"><?= $act['class'] ?></span></span></td>
                                        <td><?= $act['action_date'] ? date('d-M-Y', strtotime($act['action_date'])) : '' ?></td>
                                        <td><?= $act['attendance_counts'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning text-center">No records found for the selected filters.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const yearDates = <?= $year_dates_json ?>;
    document.getElementById("year").addEventListener("change", function () {
        const year = this.value;
        if (yearDates[year]) {
            document.getElementById("from_date").value = yearDates[year].start;
            document.getElementById("to_date").value = yearDates[year].end;
        }
    });

    document.getElementById("filter_button").addEventListener("click", function () {
        const year = document.getElementById("year").value;
        const from_date = document.getElementById("from_date").value;
        const to_date = document.getElementById("to_date").value;
        window.location = `<?= base_url() ?>stats/activity_report?year=${year}&from=${from_date}&to=${to_date}`;
    });
</script>
</body>
</html>
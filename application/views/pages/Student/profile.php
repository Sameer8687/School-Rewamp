<!-- <link rel="stylesheet" href="<?= base_url('assets/css/new/studentprofile.css'); ?>"> -->
<style>
    @media print {
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }

        h2 {
            color: #0d6643;
            margin: 20px 0;
            text-align: center;
        }

        h3,
        h4 {
            color: #12689b;
            margin: 20px 0;
            text-align: center;
        }

        .panel-body {
            margin: 0;
            page-break-inside: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            /* Adjusted margin */
        }

        th,
        td {
            padding: 4px;
            /* Adjusted padding */
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #12689b !important;
            /* background-color: #0d6643 !important; */
            color: #fff !important;
        }

        /* Hide horizontal scrollbars in print view */
        .no-scroll {
            overflow-x: hidden !important;
        }

        tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        /* Prevent page breaks inside tables and other elements */
        table,
        thead,
        tbody,
        tfoot {
            page-break-inside: avoid !important;
        }

        /* Force the entire content to fit as much as possible on the same page */
        .no-page-break {
            page-break-after: auto !important;
            page-break-before: auto !important;
            page-break-inside: auto !important;
        }
    }
</style>
<div class="row">
    <div class="col-md-12 ps-3" id="mainContent">
        <div class="card" style="height:auto">
            <div class="row p-2" style="color:#060555;">
                <div class="col-md-7">
                    <h5><span id="display_name">Student Details</span></h5>
                </div>
                <div class="col-md-2 text-end">Academic Year</div>
                <div class="col-md-2">
                    <select class="form-control form-control-sm" id="acdm" name="acdm">
                        <?php foreach ($academic_years as $acdm) : ?>
                            <option <?= $max_acdm['max_acdm'] == $acdm['stvacdm_id'] ? 'selected' : '' ?> value=<?= $acdm['stvacdm_id'] ?>> <?= $acdm['stvacdm_year'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-1 text-end">
                    <button class="btn btn-sm btn-primary" id="printBtn">Print</button>
                </div>
            </div>

            <div class="row d-none" id="navtabcontent">
                <div class="col-md-12 p-2">
                    <div class="col-md-12 p-2">
                        <ul class="nav nav-pills nav-fill" id="myTab" role="tablist" style="background-color:#f8f8f8;" cv>
                            <li class="nav-item">
                                <a class="nav-link active page" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Class Summary</a>
                            </li>
                            <li class="nav-item page">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Academic Summary</a>
                            </li>
                            <li class="nav-item page">
                                <a class="nav-link" id="messages-tab" data-toggle="tab" href="#messages" role="tab" aria-controls="messages" aria-selected="false">Financial Summary</a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active p-3" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel">
                                        <div class="panel-body table-responsive">
                                            <table class="table tab">
                                                <thead>
                                                    <tr>
                                                        <th>Spriden ID</th>
                                                        <th>Student ID</th>
                                                        <th>Standard</th>
                                                        <th>Division</th>
                                                        <th>Roll No.</th>
                                                        <th>Class Teacher</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="class_tbody">

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row">
                                <div class="col-md-12">
                                    <div id="calendar"></div>
                                     
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5><span id="display_att"></span></h5>
                                </div>
                            </div>
                            <div class="row" id="att">
                                <div class="col-md-12">
                                    <div class="panel">
                                        <div class="panel-body table-responsive">
                                            <table class="table tab">
                                                <thead>
                                                    <tr>
                                                        <th>Month</th>
                                                        <th>Dates Present On</th>
                                                        <th>Dates Absent On</th>
                                                        <th>Total Present</th>
                                                        <th>Total Absent</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="att_tbody">

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade p-3" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                            <div class="row" id="navtabinnercontent">
                                <div class="col-md-12 p-2">
                                    <div class="col-md-12 p-2">
                                        <ul class="nav nav-pills nav-fill" id="myInnerTab" role="tablist" style="background-color:#f8f8f8;" cv>
                                            <li class="nav-item">
                                                <a class="nav-link active" id="quiz-tab" data-toggle="tab" href="#quiz" role="tab" aria-controls="quiz" aria-selected="true">Quiz Result</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="exam-tab" data-toggle="tab" href="#exam" role="tab" aria-controls="exam" aria-selected="false">Exam Result</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="remark-tab" data-toggle="tab" href="#remark" role="tab" aria-controls="remark" aria-selected="false">Student Remarks</a>
                                            </li>

                                        </ul>
                                    </div>

                                    <div class="tab-content" id="myInnerTabContent">
                                        <div class="tab-pane fade show active p-3" id="quiz" role="tabpanel" aria-labelledby="quiz-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel">
                                                        <div class="panel-body table-responsive">
                                                            <table class="table tab">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Quiz name</th>
                                                                        <th>Subject</th>
                                                                        <th>Total Questions</th>
                                                                        <th>Marks Scored</th>
                                                                        <th>Status</th>
                                                                        <th>Exam Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="quiz_tbody">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade p-3" id="exam" role="tabpanel" aria-labelledby="exam-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel">
                                                        <div class="panel-body table-responsive">
                                                            <table class="table tab">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Exam name</th>
                                                                        <th>Subject</th>
                                                                        <th>Total Marks</th>
                                                                        <th>Marks Scored</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="exam_tbody">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade p-3" id="remark" role="tabpanel" aria-labelledby="remark-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel">
                                                        <div class="panel-body table-responsive">
                                                            <table class="table tab">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Remark By</th>
                                                                        <th>Date</th>
                                                                        <th>Remark</th>
                                                                        <th>Remark Reply</th>
                                                                        <th>Reply Date</th>
                                                                        <th>Remark Type</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="remark_tbody">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="tab-pane fade p-3" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel">
                                        <div class="panel-body table-responsive no-scroll">

                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Year</th>
                                                        <th>Class</th>
                                                        <th>Total Fees</th>
                                                        <th>Paid</th>
                                                        <th>Balance</th>
                                                        <th>Class</th>
                                                        <th>Fees</th>
                                                        <th>Date</th>
                                                        <th>Exem.</th>
                                                        <th>Paid To</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="fees_tbody" class="tbody-striped">

                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('application/views/pages/includes/sidebar.php') ?>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/js/new/studentprofile.js') ?>"></script>

<script>
    document.getElementById('printBtn').addEventListener('click', function() {
        var content = `
    <div class="no-page-break">
        <h2> <img src="<?= $entity_logo ?>" height=40> Olive English Medium School & Jr. College</h2>
        <div>
            <h3>Class Summary</h3>
            ${document.querySelector('#home .panel-body').outerHTML}
             <h3>Attendance Details</h3>
            ${document.querySelector('#att .panel-body').outerHTML}
        </div>
        <div>
            <h3>Academic Summary</h3>
            <h4>Quiz Result</h4>
            ${document.querySelector('#quiz .panel-body').outerHTML}
            <h4>Exam Result</h4>
            ${document.querySelector('#exam .panel-body').outerHTML}
            <h4>Student Remarks</h4>
            ${document.querySelector('#remark .panel-body').outerHTML}
        </div>
        <div class="no-scroll">
           <h3>Financial Summary</h3>
                ${document.querySelector('#messages .panel-body').outerHTML}
            </div>
    </div>
    `;
        // 12689b
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">');
        printWindow.document.write('<style>@media print { body { font-family: Arial, sans-serif !important; margin: 20px !important; color: #333 !important; } h2 { color: #0d6643 !important; margin: 20px 0 !important; text-align: center !important; } h3, h4 { color: #12689b !important; margin: 20px 0 !important; text-align: center !important; } .panel-body { page-break-inside: avoid !important; } table { width: 100% !important; border-collapse: collapse !important; margin: 20px 0 !important; } th, td { padding: 8px !important; border: 1px solid #ddd !important; text-align: left !important; } th { background-color: #12689b !important; color: #fff !important; } tbody tr:nth-child(odd) { background-color: #f2f2f2 !important; } .no-page-break { page-break-after: auto !important; page-break-before: auto !important; page-break-inside: auto !important; } }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');

        printWindow.document.close();
        printWindow.print();
    });
</script>
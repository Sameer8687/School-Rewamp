<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Issue LC</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Phoenixcoded" />
    <!---Include CSS Files-->
    <?php include 'application/views/cssPages.php'; ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/id-card.css" />
    <style>
        .pc-container {
            top: 0px !important;
        }

        .border {
            border: 1px solid;
        }

        .table tr td,
        .table tr th {
            padding: 5px !important;
            font-size: 11px !important;
            cursor: pointer;
        }
    </style>
</head>

<body class="">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <?php include 'application/views/sidebar.php'; ?> <?php include
            'application/views/topNav.php'; ?>
    <div class="pc-container">
        <div class="pcoded-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"> Students Absentee Report</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">

                                        <!-- 1 -->
                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-5">Academic Year <span
                                                        class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7" name="year"
                                                    id="year">

                                                    <?php foreach ($academic_year as $acdm) { ?>
                                                        <option value="<?= $acdm['stvacdm_id'] ?>">
                                                            <?= $acdm['stvacdm_year'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>

                                        <!-- 2 -->
                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-5">Class <span
                                                        class="float-right">:</span></label>

                                                <select name="std" id="std"
                                                    class="form-control form-control-sm col-md-7">
                                                    <option value="">Select Class</option>
                                                    <option value="Nursery">Nursery</option>
                                                    <option value="Jr.Kg">Jr.Kg</option>
                                                    <option value="Sr.Kg">Sr.Kg</option>
                                                    <option value="I">I</option>
                                                    <option value="II">II</option>
                                                    <option value="III">III</option>
                                                    <option value="IV">IV</option>
                                                    <option value="V">V</option>
                                                    <option value="VI">VI</option>
                                                    <option value="VII">VII</option>
                                                    <option value="VIII">VIII</option>
                                                    <option value="IX">IX</option>
                                                    <option value="X">X</option>
                                                    <option value="XI">XI</option>
                                                    <option value="XII">XII</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-5">Division <span
                                                        class="float-right">:</span></label>

                                                <select name="div" id="div"
                                                    class="form-control form-control-sm col-md-7">
                                                    <option value="">Select Division</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                </select>

                                            </div>
                                        </div>

                                        <!-- 3 -->
                                        <div class="col-md-3">

                                            <button id="filter-btn" type="button"
                                                class="btn btn-sm btn-outline-secondary">Search</button>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="example" data-custom="true"
                                            class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student Name</th>
                                                    <th>Jan</th>
                                                    <th>Feb</th>
                                                    <th>Mar</th>
                                                    <th>Apr</th>
                                                    <th>May</th>
                                                    <th>Jun</th>
                                                    <th>Jul</th>
                                                    <th>Aug</th>
                                                    <th>Sep</th>
                                                    <th>Oct</th>
                                                    <th>Nov</th>
                                                    <th>Dec</th>
                                                    <th>Yearly Absentee</th>
                                                    <th>Absentee Current Month</th>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
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

    <!-- modal -->
    <div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="studentModalLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background:#53769c;color:#fff;">
                    <h5 class="modal-title" id="studentModalLabel">Attendance Calendar</h5>
                    <button type="button" class="close text-white" id="modalCloseBtn" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div id="attendanceCalendar" style="width: 100%; max-width: 280px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>
    </div>

    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>

    <script>
        $('#example').DataTable({
            pageLength: 50,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });


        $('#filter-btn').click(function () {
            var year = $('#year').val();
            var std = $('#std').val();
            var div = $('#div').val();

            if (!year || !std || !div) {
               $('#tbody').html(
    '<tr><td colspan="16" class="text-center">Please select Year, Class and Division</td></tr>'
);
            }

            $.ajax({
                url: '<?php echo base_url('Absent_student/get_absent_details'); ?>',
                type: 'POST',
                data: { year: year, std: std, div: div },
                success: function (response) {
                    renderTable(response, year, std, div);

                    // save filters + data in localStorage
                    localStorage.setItem('absentFilters', JSON.stringify({
                        year: year,
                        std: std,
                        div: div,
                        data: response
                    }));
                },
                error: function () {
                    $('#tbody').html(
    '<tr><td colspan="16" class="text-center">No Data Found</td></tr>'
);
                }
            });
        });

        function renderTable(response, year, std, div) {
            $('#tbody').empty();
            var data = [];
            try {
                data = typeof response === 'object' ? response : JSON.parse(response);
            } catch (e) {
                console.error('Error parsing response:', e);
                $('#tbody').html(
    '<tr><td colspan="16" class="text-center">Error loading data</td></tr>'
);
            }

            if (data && data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                    var streaks = data[i].continuous_absent || [];
                    var streakText = "-";

                    // 🔹 Handle continuous absent streaks
                    if (streaks.length > 0) {
                        var streakStrings = [];
                        var now = new Date();
                        var currentMonth = now.getMonth();
                        var currentYear = now.getFullYear();

                        for (var j = 0; j < streaks.length; j++) {
                            if (streaks[j].days && streaks[j].dates) {
                                var filteredDates = streaks[j].dates.filter(function (dt) {
                                    var d = new Date(dt);
                                    return d.getMonth() === currentMonth && d.getFullYear() === currentYear;
                                });

                                if (filteredDates.length >= 2) {
                                    streakStrings.push(
                                        `<span class="badge badge-danger mx-1">${filteredDates.length} days</span>`
                                    );
                                }
                            }
                        }
                        if (streakStrings.length > 0) {
                            streakText = streakStrings.join("");
                        }
                    }

                    // 🔹 Monthly absentee values (set 0 if missing)
                    var jan = parseInt(data[i].jan || 0);
                    var feb = parseInt(data[i].feb || 0);
                    var mar = parseInt(data[i].mar || 0);
                    var apr = parseInt(data[i].apr || 0);
                    var may = parseInt(data[i].may || 0);
                    var jun = parseInt(data[i].jun || 0);
                    var jul = parseInt(data[i].jul || 0);
                    var aug = parseInt(data[i].aug || 0);
                    var sep = parseInt(data[i].sep || 0);
                    var oct = parseInt(data[i].oct || 0);
                    var nov = parseInt(data[i].nov || 0);
                    var dec = parseInt(data[i].dec || 0);

                    // 🔹 Total absentee (if you want to recalc from months)
                    var totalAbsents = [jan, feb, mar, apr, may, jun, jul, aug, sep, oct, nov, dec]
                        .reduce((a, b) => a + (isNaN(b) ? 0 : b), 0);

                    var row = $('<tr>').addClass('student-row')
                        .attr('data-id', data[i].id)
                        .attr('data-year', year)
                        .attr('data-name', data[i].student_name || 'Unknown')
                        .append(
                            $('<td>').text(i + 1),
                            $('<td>').text(data[i].student_name || ''),
                            $('<td>').text(jan),
                            $('<td>').text(feb),
                            $('<td>').text(mar),
                            $('<td>').text(apr),
                            $('<td>').text(may),
                            $('<td>').text(jun),
                            $('<td>').text(jul),
                            $('<td>').text(aug),
                            $('<td>').text(sep),
                            $('<td>').text(oct),
                            $('<td>').text(nov),
                            $('<td>').text(dec),
                            $('<td>').text(totalAbsents),  // show calculated total
                            $('<td>').addClass('text-center').html(streakText)
                        );

                    $('#tbody').append(row);
                }

                // Add click event to student rows
                attachRowClickEvents();

            } else {
                $('#tbody').html('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');
            }
        }


        function attachRowClickEvents() {
            $('.student-row').off('click').on('click', function () {
                var studentId = $(this).data('id');
                var year = $(this).data('year');
                var studentName = $(this).data('name');

                console.log('Clicked student:', studentId, year, studentName);

                // Show loading in modal
                $('#studentModalLabel').text('Loading...');
                $('#attendanceCalendar').html('<div class="text-center p-3"><i class="bx bx-loader bx-spin"></i> Loading calendar...</div>');

                // Remove any existing datepicker to avoid conflicts
                if ($('#attendanceCalendar').hasClass('hasDatepicker')) {
                    $('#attendanceCalendar').datepicker('destroy');
                }

                $('#studentModal').modal('show');

                // Fetch attendance data
                $.ajax({
                    url: '<?php echo base_url('Absent_student/absent_student'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: studentId,
                        year: year
                    },
                    success: function (response) {
                        console.log('Absent dates:', response);
                        renderAttendanceCalendar(response, studentName);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching attendance data:', error);
                        $('#attendanceCalendar').html('<div class="text-center text-danger p-3">Error loading calendar data</div>');
                    }
                });
            });
        }

        function renderAttendanceCalendar(absentDates, studentName) {
            $('#studentModalLabel').text('Attendance - ' + studentName);
            $('#attendanceCalendar').empty();

            if (!absentDates || absentDates.length === 0) {
                $('#attendanceCalendar').html('<div class="text-center text-success p-3">No absent days found</div>');
                return;
            }

            // 🔹 Extract all absent dates and phone number
            var formatted = absentDates.map(function (d) { return d.date.split(' ')[0]; });
            var phoneNumber = absentDates.length > 0 ? absentDates[0].phone : '';

            formatted.sort();

            // 🔹 Build absentSet for highlighting dates
            var absentSet = {};
            formatted.forEach(d => absentSet[d] = true);

            // 🔹 Group continuous absent days into ranges
            var ranges = [];
            var start = formatted[0];
            var prev = formatted[0];

            for (var i = 1; i <= formatted.length; i++) {
                var current = formatted[i];
                var prevDate = new Date(prev);
                prevDate.setDate(prevDate.getDate() + 1);
                var expectedNext = prevDate.toISOString().split('T')[0];

                if (current !== expectedNext) {
                    ranges.push({ start: start, end: prev });
                    start = current;
                }
                prev = current;
            }

            console.log("Absent Ranges:", ranges);

            // 🔹 Helper: find range for selected date
            function getRangeForDate(dateStr) {
                for (var i = 0; i < ranges.length; i++) {
                    var r = ranges[i];
                    if (dateStr >= r.start && dateStr <= r.end) {
                        return r;
                    }
                }
                return null;
            }

            // 🔹 Initialize jQuery UI Datepicker
            $('#attendanceCalendar').datepicker({
                dateFormat: 'yy-mm-dd',
                showOtherMonths: true,
                selectOtherMonths: true,
                changeMonth: true,
                changeYear: true,
                beforeShowDay: function (date) {
                    var dateString = $.datepicker.formatDate('yy-mm-dd', date);
                    if (absentSet[dateString]) {
                        return [true, 'absent-day', 'Absent'];
                    }
                    return [true, '', ''];
                },
                onSelect: function (selectedDate) {
                    if (absentSet[selectedDate]) {
                        var range = getRangeForDate(selectedDate);
                        var msg = '';

                        if (range.start === range.end) {
                            msg = `Hello, this is to inform you that ${studentName} was absent on ${range.start}.`;
                        } else {
                            msg = `Hello, this is to inform you that ${studentName} was absent continuously from ${range.start} to ${range.end}.`;
                        }

                        if (phoneNumber && phoneNumber.trim() !== '') {
                            var encodedMsg = encodeURIComponent(msg);
                            var whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodedMsg}`;
                            window.open(whatsappUrl, '_blank');
                        } else {
                            alert("WhatsApp number not available for this student.");
                        }
                    }
                }
            });

            $('#attendanceCalendar').datepicker('setDate', new Date());
        }

        $(document).ready(function () {
            // Load saved filters
            var saved = localStorage.getItem('absentFilters');
            if (saved) {
                try {
                    var obj = JSON.parse(saved);
                    $('#year').val(obj.year);
                    $('#std').val(obj.std);
                    $('#div').val(obj.div);
                    renderTable(obj.data, obj.year, obj.std, obj.div);
                } catch (e) {
                    console.error('Error loading saved filters:', e);
                }
            }

            // Close button event - page refresh karega
            $('#modalCloseBtn').click(function () {
                $('#studentModal').modal('hide');
                location.reload(); // Page refresh
            });

            // Refresh page button event
            $('#refreshPageBtn').click(function () {
                $('#studentModal').modal('hide');
                location.reload(); // Page refresh
            });

            // Modal hidden event - ab kuch nahi karega
            $('#studentModal').on('hidden.bs.modal', function () {
                // Kuch bhi action nahi hoga, bas modal close hoga
                console.log('Modal closed');
            });

            // Background par click hone se rokne ke liye
            $('#studentModal').on('click', function (e) {
                if (e.target === this) {
                    e.stopPropagation();
                }
            });
        });

        // ESC key press se bhi modal close na ho iske liye
        $(document).on('keydown', function (e) {
            if (e.keyCode === 27 && $('#studentModal').hasClass('show')) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    </script>

</body>

</html>
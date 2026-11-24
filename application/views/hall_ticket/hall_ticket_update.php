<?php
function renderSelectOptions($data, $value, $name, $selected = '')
{
    $html = '<option>Select Option</option>';
    foreach ($data as $record) {
        $selected_option = '';
        if ($selected == $record[$value])
            $selected_option = 'selected';
        $html .= "<option $selected_option value='$record[$value]'>$record[$name]</option>";
    }
    return $html;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Student Fees Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <!-- Include CSS Files -->
    <?php include 'application/views/cssPages.php'; ?>
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
</head>

<body>
    <!-- Include Sidebar -->
    <?php include 'application/views/sidebar.php'; ?>
    <!-- Include Top Navbar -->
    <?php include 'application/views/topNav.php'; ?>

    <div class="pc-container">
        <form id="examForm" action="<?php echo base_url(); ?>Hall_ticket/add_data" method="POST">
            <div class="pcoded-content">
                <div class="row margin-top-minus-40 mobile-margin-top-minus-10">
                    <?php if ($this->session->userdata('alert')): ?>
                        <div class="col-md-12">
                            <div class="alert alert-<?= $this->session->userdata('alert_color') ?>">
                                <p class="mb-0"><?= $this->session->userdata('alert'); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-12 mb-0">
                        <div class="card fees-card">
                            <div class="card-header" style="background-color: #53769c !important;">
                                <div class="row w-100">
                                    <div class="col-md-6">
                                        <h5 class="pt-2 text-white">Hall Ticket Data</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-secondary btn-sm float-right" id="submit-btn">
                                            <i class="fas fa-dollar-sign"></i>
                                            <span id="dynamicLabelAddButton">Add Data</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pb-">
                                <div class="row mini-label mb-0">
                                    <div class="col-md-6">
                                        <div class="form-group row mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3 text-left">Class <span class="float-right">:</span></label>
                                            <div class="col-md-5">
                                                <select class="form-control form-control-sm ml-1" name="class" id="class" onchange="getSubject(),fetchRow()" required>
                                                    <option value="">Select Class</option>
                                                    <?php foreach ($classes as $class) : ?>
                                                        <option <?= $class['stvfees_class'] == $headers['stvhtkt_class'] ? 'selected' : '' ?> value="<?= $class['stvfees_class'] ?>"><?= $class['stvfees_class'] ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3 text-left">Exam <span class="float-right">:</span></label>
                                            <div class="col-md-5">
                                                <select class="form-control form-control-sm ml-1" name="exam" id="exam" required onchange="fetchRow()">
                                                    <option value="">Select Exam</option>
                                                    <?php foreach ($exams as $exam) : ?>
                                                        <option <?= $exam['stvexam_id'] == $headers['stvhtkt_exam'] ? 'selected' : '' ?> value="<?= $exam['stvexam_id'] ?>"><?= $exam['stvexam_name'] ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">
                                            <label for="" class="col-md-3 text-left">Shift <span class="float-right">:</span> </label>
                                            <div class="col-md-8">
                                                <input type="radio" name="shift" id="morning" value="morning">&nbsp; <span style="margin-top:4px;"> Morning</span>&nbsp;
                                                <input type="radio" name="shift" id="afternoon" value="afternoon" class="ml-3">&nbsp; <span style="margin-top:4px;"> Afternoon</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-sm btn-secondary margin-top-minus-10" type="button" onclick="generate(this)">Generate</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 margin-top-minus-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h5>Exam Timetable List</h5>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <button type="button" onclick="addRow()" class="btn btn-sm btn-primary mb-1 d-none" id="add-btn">Add Row</button>
                                    </div>
                                </div>
                                <input type="hidden" name="total_rows" value="0" id="total_rows">
                                <input type="hidden" name="header_id" value="0" id="header_id">
                                <table class="table data-wala table-sm table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th class="text-capitalize p-1 font-weight-normal text-center">#</th>
                                            <th class="text-capitalize p-1 font-weight-normal text-center">Sub</th>
                                            <th class="text-capitalize p-1 font-weight-normal text-center">Date</th>
                                            <th class="text-capitalize p-1 font-weight-normal text-center">Start</th>
                                            <th class="text-capitalize p-1 font-weight-normal text-center">End</th>
                                            <th class="text-capitalize p-1 font-weight-normal text-center">Exam</th>
                                            <th class="text-capitalize p-1 font-weight-normal text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="timetableBody">
                                        <?php $i = 1;
                                        foreach ($lines as $line) : ?>
                                            <tr>
                                                <td><?= $i ?>
                                                    <input type="hidden" name="line_id[]" id="line_id_" value="<?= $line['stvhtkt_line_id'] ?>">
                                                    <!-- <input type="hidden" name="total_rows[]" id="total_rows_" value="<?= $line['stvhtkt_line_id'] ?>"> -->
                                                </td>
                                                <td>
                                                    <select class="form-control form-control-sm" name="subject[]" required>
                                                        <?= renderSelectOptions($subjects, 'stvsubj_id', 'stvsubj_subject', $line['stvhtkt_subject']) ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control form-control-sm" value="<?= $line['stvhtkt_date'] ?>" name="exam_date[]" required>
                                                </td>
                                                <td>
                                                    <input type="time" class="form-control form-control-sm" value="<?= $line['stvhtkt_time_start'] ?>" name="time_start[]" required>
                                                </td>
                                                <td>
                                                    <input type="time" class="form-control form-control-sm" value="<?= $line['stvhtkt_time_end'] ?>" name="time_end[]" required>
                                                </td>
                                                <td>
                                                    <select class="form-control form-control-sm" name="type[]" required>
                                                        <option <?= $line['stvhtkt_type'] ?> value="Written">Written</option>
                                                        <option <?= $line['stvhtkt_type'] ?> value="Oral">Oral</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="button" onclick="deleteRow(this)" class="btn btn-danger btn-sm">Delete</button>
                                                </td>
                                            </tr>
                                        <?php $i++;
                                        endforeach; ?>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Include JS Files -->
    <?php include 'application/views/jsPages.php'; ?>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function addRow() {
                let rowId = parseFloat(document.getElementById("total_rows").value) || 0;
                rowId++;
                const tableBody = document.getElementById('timetableBody');

                // Create a new row
                const newRow = document.createElement('tr');
                newRow.setAttribute('data-id', rowId);

                // Add HTML content to the new row
                newRow.innerHTML = `
            <td class="p-0 m-0 text-center">${rowId}
            </td>
            <td class="p-0 m-0">
                <select class="form-control form-control-sm" name="subject[]" required>
                    <option value="">Select Subject</option>
                </select>
            </td>
            <td class="p-0 m-0">
                <input type="date" class="form-control form-control-sm" name="exam_date[]" required>
            </td>
            <td class="p-0 m-0">
                <input type="time" class="form-control form-control-sm" name="time_start[]" required>
            </td>
            <td class="p-0 m-0">
                <input type="time" class="form-control form-control-sm" name="time_end[]" required>
            </td>
            <td class="p-0 m-0">
                <select class="form-control form-control-sm" name="type[]" required>
                    <option value="Written">Written</option>
                    <option value="Oral">Oral</option>
                </select>
            </td>
            <td class="p-0 m-0 text-center">
                <button type="button" onclick="deleteRow(this)" class="btn btn-danger btn-sm">Delete</button>
            </td>
        `;

                // Append the new row to the table body
                tableBody.appendChild(newRow);

                // Fetch and populate subject options for the new row
                getSubject(function(options) {
                    newRow.querySelector('select[name="subject[]"]').innerHTML = options;
                });
                document.getElementById("total_rows").value = parseInt(rowId) || 0;
            }

            function getSubject(callback) {
                var std_name = document.getElementById('class').value;
                $.ajax({
                    url: "<?= base_url() ?>Hall_ticket/getLOVS",
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        std_name: std_name,
                        type: 'subjects'
                    },
                    success: function(data) {
                        if (data) {
                            var options = '<option value="">Select Subject</option>';
                            for (var count = 0; count < data.length; count++) {
                                options += '<option value="' + data[count].stvsubj_id + '">' + data[count].stvsubj_subject + '</option>';
                            }
                            callback(options);
                        } else {
                            callback('<option value="">No Subjects Available</option>');
                        }
                    }
                });
            }

            function deleteRow(button) {
                const row = button.closest('tr');
                row.remove();
                updateRowNumbers();
            }

            function updateRowNumbers() {
                const rows = document.querySelectorAll('#timetableBody tr');
                rows.forEach((row, index) => {
                    const id = index + 1;
                    row.querySelector('td').textContent = id;
                    row.setAttribute('data-id', id);
                    // Update hidden inputs if necessary
                });
                document.getElementById("total_rows").value = parseInt(rows.length) || 0;
            }

            function setShiftTimes() {
                const shift = document.querySelector('input[name="shift"]:checked');
                const startTime = shift && shift.value === 'morning' ? '07:15' : '12:00';
                const endTime = shift && shift.value === 'morning' ? '12:15' : '17:00';

                document.querySelectorAll('input[name="time_start[]"]').forEach(input => {
                    input.value = startTime;
                });
                document.querySelectorAll('input[name="time_end[]"]').forEach(input => {
                    input.value = endTime;
                });
            }

            // Event listeners for shift radio buttons
            document.querySelectorAll('input[name="shift"]').forEach(radio => {
                radio.addEventListener('change', setShiftTimes);
            });

            window.addRow = addRow; // Expose addRow function to global scope
            window.deleteRow = deleteRow; // Expose deleteRow function to global scope

            // Initialize DataTable
            $("#example").DataTable({
                "pageLength": 25,
                dom: "Bfrtip",
            });
        });


        function showSubjects(subjects, selected) {
            let options = "<option>Select Subject</option>";
            subjects.forEach((sub, index) => {
                const selected_sub = selected == index ? 'selected' : '';
                options += `<option ${selected_sub} value="${sub.stvsubj_id}">${sub.stvsubj_subject}</option>`;
            });
            return options;
        }
        //  Fetching and Updating Existing Rows
        function fetchRow() {
            var selectedClass = document.getElementById('class').value;
            var exam = document.getElementById('exam').value;
            var div = document.getElementById('div').value;
            var acdm = document.getElementById('acdm').value;

            if (selectedClass && exam && div && acdm) {
                $.ajax({
                    url: "<?= base_url() ?>Hall_ticket/fetchRow",
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        class: selectedClass,
                        exam: exam,
                        div: div,
                        acdm: acdm
                    },
                    success: function(data) {
                        const tableBody = document.getElementById('timetableBody');
                        tableBody.innerHTML = ''; // Clear existing rows

                        if (data && data.length > 0) {
                            console.log(data);
                            // Iterate over data and create rows
                            data.forEach((item, index) => {
                                const row = document.createElement('tr');
                                row.setAttribute('data-id', item.line_id);

                                row.innerHTML = `
                            <td class="p-0 m-0 text-center">${index + 1}
                            </td>
                            <td class="p-0 m-0">
                                <select class="form-control form-control-sm" name="subject[]" required></select>
                            </td>
                            <td class="p-0 m-0">
                                <input type="date" class="form-control form-control-sm" name="exam_date[]" value="${item.exam_date}" required>
                            </td>
                            <td class="p-0 m-0">
                                <input type="time" class="form-control form-control-sm" name="time_start[]" value="${item.time_start}" required>
                            </td>
                            <td class="p-0 m-0">
                                <input type="time" class="form-control form-control-sm" name="time_end[]" value="${item.time_end}" required>
                            </td>
                            <td class="p-0 m-0">
                                <select class="form-control form-control-sm" name="type[]" required>
                                    <option value="Written" ${item.type === 'Written' ? 'selected' : ''}>Written</option>
                                    <option value="Oral" ${item.type === 'Oral' ? 'selected' : ''}>Oral</option>
                                </select>
                            </td>
                            <td class="p-0 m-0 text-center">
                                <button type="button" onclick="deleteRow(this)" class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        `;

                                tableBody.appendChild(row);

                                // Populate subject options if necessary
                                getSubject(function(options) {
                                    row.querySelector('select[name="subject[]"]').innerHTML = options;
                                });
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                        alert('An error occurred while fetching data.');
                    }
                });
            }
        }

        // Added By Shaikh Ab Azim
        // Function to check which radio button is selected
        function getSelectedShift() {
            // Get all radio buttons with the name 'shift'
            const shifts = document.getElementsByName('shift');
            // Loop through each radio button
            for (let i = 0; i < shifts.length; i++) {
                if (shifts[i].checked) {
                    // If a radio button is checked, return its value
                    return shifts[i].value;
                }
            }
            // If none are checked, return null or a default value
            return null;
        }
        async function generate(element) {
            // get params
            const std = document.getElementById("class").value;
            const shift = getSelectedShift();
            console.log('SHift', shift);

            if (std == '') {
                alert("Select Standard");
                return;
            }
            if (shift == null) {
                alert("Select Shift");
                return;
            }
            try {
                const url = `<?= base_url() ?>Hall_ticket/fetchSubjectsList?std=${encodeURIComponent(std)}`;
                // Make the fetch request
                const response = await fetch(url, {
                    method: 'GET', // or 'POST', depending on your API
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                // Check if the response is successful
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                // Parse the JSON response
                const data = await response.json();
                // Handle the response data
                generateTable(data);

            } catch (error) {
                // Handle errors
                alert('Fetch error:', error);
            }
        }

        function generateTable(data) {
            let lines = '';
            let index = 0;
            let timing = {};
            let shift = getSelectedShift();
            if (shift != '') {
                if (shift == 'morning') {
                    timing = {
                        start: '07:15',
                        end: '12:00',
                    }
                } else {
                    timing = {
                        start: '12:15',
                        end: '17:00',
                    }
                }
            }
            document.getElementById("add-btn").classList.add("d-none");
            if (data?.length > 0) {
                // add lines 
                data.forEach((line, seq) => {
                    lines += `<tr>
                            <td class="p-0 m-0 text-center">${++index}</td>
                            <td class="p-0 m-0">
                                <select class="form-control form-control-sm" name="subject[]" required>${showSubjects(data, seq)}</select>
                            </td>
                            <td class="p-0 m-0">
                                <input type="date" class="form-control form-control-sm" name="exam_date[]" value="" required>
                            </td>
                            <td class="p-0 m-0">
                                <input type="time" class="form-control form-control-sm" name="time_start[]" value="${timing?.start}" required>
                            </td>
                            <td class="p-0 m-0">
                                <input type="time" class="form-control form-control-sm" name="time_end[]" value="${timing?.end}" required>
                            </td>
                            <td class="p-0 m-0">
                                <select class="form-control form-control-sm" name="type[]" required>
                                    <option value="">Select</option>
                                    <option value="Written">Written</option>
                                    <option value="Oral">Oral</option>
                                </select>
                            </td>
                            <td class="p-0 text-center">
                                <button type="button" onclick="deleteRow(this)" class="btn btn-danger btn-sm">Delete</button>
                            </td>
                    </tr>`;
                });
                document.getElementById("add-btn").classList.remove("d-none");
            }
            document.getElementById("timetableBody").innerHTML = lines;
            document.getElementById("total_rows").value = parseInt(data?.length) || 0;
        }


        $('#examForm').submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: "<?= base_url() ?>/Hall_ticket/add_data",
                method: "POST",
                dataType: "JSON",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    document.getElementById("dynamicLabelAddButton").innerHTML = 'Please Wait';
                    document.getElementById("submit-btn").disabled = true;
                },
                success: function(res) {
                    document.getElementById("submit-btn").disabled = false;
                    // set header id and display data
                    console.log(res.status);

                    if (res.status == 'success') {
                        alert(`Exam Time Table has been ${res.mode} successfully!`);
                        document.getElementById("header_id").value = res?.header_id || 0;
                        document.getElementById("dynamicLabelAddButton").innerHTML = 'Update Data';
                    } else {
                        document.getElementById("dynamicLabelAddButton").innerHTML = 'Add Data';
                    }
                }
            })
        })
    </script>
</body>

</html>
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
                                <h5 class="card-title">Generate Notes</h5>
                        </div>
                        <div class="card-body">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-5">Class
                                                    <span class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7" name="cur_acdm"
                                                    id="cur_acdm">
                                                    <option value="">Select</option>
                                                    <?php foreach ($academic_years as $academic_year): ?>
                                                    <option
                                                        <?= $current_acdm == $academic_year['stvacdm_id'] ? 'selected' : '' ?>
                                                        value="<?= $academic_year['stvacdm_id']; ?>">
                                                        <?= $academic_year['stvacdm_year']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-5">Academic year
                                                    <span class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7" name="std_filter_"
                                                    id="marks_class">
                                                    <option value="">Select</option>
                                                    <?php foreach ($classes as $class): ?>
                                                    <option
                                                        <?= $current_class == $class['stvfees_class'] ? 'selected' : '' ?>
                                                        value="<?= $class['stvfees_class'] ?>">
                                                        <?= $class['stvfees_class'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-5">Academic year
                                                    <span class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7" name="div_filter_"
                                                    id="div_filter_">
                                                    <option value="">Select</option>
                                                    <option <?= $current_div == 'A' ? 'selected' : '' ?> value="A">A
                                                    </option>
                                                    <option <?= $current_div == 'B' ? 'selected' : '' ?> value="B">B
                                                    </option>
                                                    <option <?= $current_div == 'C' ? 'selected' : '' ?> value="C">C
                                                    </option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-3">

                                            <button id="saveBtn" type="button"
                                                class="btn btn-sm btn-outline-secondary">Save</button>


                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <div class="row">
                                            <div class="col-md-6 pb-1">
                                                <button type="button"
                                                    class="btn btn-success btn-sm add-row-btn float-start" id="addRow">
                                                    + Add
                                                </button>
                                            </div>
                                        </div>
                                        <table id="simpletable" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th style="padding: 5px;">Sr.No.</th>
                                                    <th style="padding: 5px;">Subject</th>
                                                    <th style="padding: 5px;">Doc Title</th>
                                                    <th style="padding: 5px;">Doc Type</th>
                                                    <th style="padding: 5px;">Doc File</th>
                                                    <th style="width: 70px; padding: 5px;">Action</th>
                                                </tr>
                                            </thead>

                                            <tbody id="moduleFormContainerInner">
                                                <!-- Dynamic rows will be added here -->
                                            </tbody>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>
    <script>
    let selectedSubjects;
    // Function to handle class change and update subjects dynamically
    $('#marks_class').change(function() {
        var std = $('#marks_class').val();
        if (!std) {
            alert("Please select a class first.");
            return;
        }
        $.ajax({
            url: "<?php echo base_url(); ?>Welcome/get_subject",
            data: {
                std: std
            },
            method: "POST",
            dataType: "JSON",
            success: function(data) {
                selectedSubjects = data.subjects
                if (data.subjects.length > 0) {
                    var options = '<option value="" selected disabled>Select Subject</option>';
                    data.subjects.forEach(function(subject) {
                        options +=
                            `<option value="${subject.stvsubj_id}">${subject.stvsubj_subject}</option>`;
                    });
                    // Find all subject selects and update their options
                    $('#moduleFormContainerInner').find('select[name="subject[]"]').each(
                function() {
                        $(this).html(options);
                    });
                } else {
                    alert("No subjects available for the selected class.");
                    $('#moduleFormContainerInner').find('select[name="subject[]"]').each(
                function() {
                        $(this).html(
                            '<option value="" selected disabled>Select Subject</option>'
                            );
                    });
                }
            },
            error: function() {
                alert("An error occurred while fetching subjects. Please try again.");
            }
        });
    });

    // Add new row to the table
    document.getElementById('addRow').addEventListener('click', function() {
        let selectOptions = '<option value="">Choose</option>';
        if (selectedSubjects && selectedSubjects.length > 0) {
            selectedSubjects.forEach(function(subject) {
                selectOptions +=
                    `<option value="${subject.stvsubj_id}">${subject.stvsubj_subject}</option>`;
            });
        }

        const tableBody = document.getElementById('moduleFormContainerInner');
        const rowCount = tableBody.rows.length + 1;
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${rowCount}</td>
            <td>
                <select class="form-control form-control-sm" name="subject[]">${selectOptions}</select>
            </td>
            <td><input type="text" name="doc_title[]" class="form-control form-control-sm" placeholder="Enter Doc Title"></td>
            <td>
                <select name="doc_type[]" class="form-control form-control-sm">
                    <option value="" disabled selected>Select Doc Type</option>
                    <option value="Lecture Notes">Lecture Notes</option>
                    <option value="Assignments">Assignments</option>
                    <option value="Summary Notes">Worksheets</option>
                    <option value="Practice Papers">Practice Papers</option>
                    <option value="Exam Preparation Material">Exam Preparation Material</option>
                    <option value="Summary Notes">Summary Notes</option>
                </select>
            </td>
            <td><input type="file" name="doc_file[]" class="form-control form-control-sm p-0"></td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row-btn">Remove</button>
            </td>
        `;
        tableBody.appendChild(newRow);
        newRow.querySelector('.remove-row-btn').addEventListener('click', function() {
            newRow.remove();
        });
    });
    </script>

    <script>
    $('#saveBtn').on('click', function() {
        var formData = new FormData();
        formData.append('cur_acdm', $('#cur_acdm').val());
        formData.append('marks_class', $('#marks_class').val());
        formData.append('div_filter_', $('#div_filter_').val());

        // Collect dynamic row data
        $('#moduleFormContainerInner tr').each(function() {
            var subject = $(this).find('select[name="subject[]"]').val();
            var docTitle = $(this).find('input[name="doc_title[]"]').val();
            var docType = $(this).find('select[name="doc_type[]"]').val();
            var docFile = $(this).find('input[name="doc_file[]"]')[0].files[
            0]; // Use index [0] for file inputs.

            // Check if all fields for this row have values before appending
            if (subject && docTitle && docType && docFile) {
                formData.append('subject[]', subject);
                formData.append('doc_title[]', docTitle);
                formData.append('doc_type[]', docType);
                formData.append('doc_file[]', docFile);
            } else {
                alert('Please fill all fields for each row before saving.');
                return false; // Stop the loop and function if any row is incomplete
            }
        });

        // AJAX request
        $.ajax({
            url: "<?php echo base_url('welcome/save_notes'); ?>",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(response) {
                alert(response.message); // Adjust this as needed to handle the server's response
                // window.location.reload();
            },
            error: function() {
                alert("An error occurred while saving the data. Please try again.");
            },
        });
    });
    </script>

</html>
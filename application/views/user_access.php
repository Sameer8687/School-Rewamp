<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - User Access Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <style>
        .pc-container {
            top: 50px;
        }

        .form-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .form-table th,
        .form-table td {
            padding: 2px 10px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .form-table th {
            background-color: #53769c !important;
            color: #fff !important;
        }

        .form-table td select,
        .form-table td input {
            width: 100%;
            box-sizing: border-box;
        }

        .add-row-btn {

            cursor: pointer;
        }


        .user-info-container {
            background-color: #f9f9f9;
            border: 1px solid #dedede;
        }

        .user-info-container .row div {
            padding: 0px 20px;
        }

        .user-info-container i {
            color: #007bff;
            margin-right: 5px;
        }

        /* .shade {
    box-shadow: 0 0.125rem 0.25rem rgba(0.1, 0.1, 0.1, 0.145) !important;
} */

        .form-group.row.mt-4.align-items-center {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        /* Adjust margins for spacing between elements */
        #userid,
        #sameAsUserid {
            margin-right: 15px;
        }

        /* Ensure proper alignment when 'Same As' button is clicked */
        #sameAsButton {
            margin-right: 15px;
        }

        /* Hide or show dropdown based on 'Same As' */
        #UseLabel.d-none,
        #sameAsUserid.d-none {
            display: none;
        }
    </style>

    <?php include 'application/views/cssPages.php'; ?>

</head>
<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'topNav.php'; ?>

    <div class="pc-container">
        <div class="pcoded-content" style="padding-top: 10px;">

            |<a href="<?php echo base_url(); ?>Welcome/user_report"> View Report</a>|<a
                href="<?php echo base_url(); ?>Welcome/mode"> Add Modules </a>|<a
                href="<?php echo base_url(); ?>Welcome/forms"> Add Forms </a>|

            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card rounded" style="margin-bottom: 10px !important;">
                        <div class="card-body" style="padding-bottom: 0px !important;">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fa fa-user" style="font-size: 20px; color: #007bff;"></i>
                                <h4 class="mb-0 ml-2 font-weight-bold">User Access</h4>
                            </div>

                            <!-- User Form -->
                            <div class="form-group row mt-4 align-items-center">
                                <div class="col-md-10 d-flex">
                                    <!-- User Name Dropdown -->
                                    <label for="userid" class="mr-2" style="margin-top:3px;">User Name <span
                                            class="text-danger">*</span>:</label>
                                    <select class="form-control form-control-sm mr-3" style="width: 16%;" name="userid"
                                        id="userid">
                                        <option value="">Select</option>
                                    </select>

                                    <!-- Same As Button -->
                                    <button type="button" id="sameAsButton" class="btn btn-link btn-sm mr-3"
                                        style="text-decoration: none;">Same As</button>

                                    <!-- Users Dropdown (Same As) -->
                                    <label class="d-none mr-2" style="margin-top:3px;" name="UseLabel" id="UseLabel"
                                        for="sameAsUserid">Users:</label>
                                    <select class="form-control form-control-sm d-none" style="width: 16%;"
                                        name="sameAsUserid" id="sameAsUserid">
                                        <option value="">Select Users</option>
                                    </select>
                                </div>

                                <!-- Right side: Generate button (col-md-2) -->
                                <div class="col-md-2 text-right">
                                    <button type="button" id="viewButton" class="btn btn-info btn-sm">Generate</button>
                                </div>
                            </div>


                            <!-- User Information Display -->
                            <div class="form-group row mt-3" id="userInfo" style="display: none;">
                                <div class="col-md-12">
                                    <div class="user-info-container p-3 rounded shade" style="border: #f5f5f5;">
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <strong><i class="fa fa-user-circle"></i> Name:</strong>
                                                <span id="userName"></span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong><i class="fa fa-envelope"></i> Email:</strong>
                                                <span id="userEmail"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong><i class="fa fa-phone"></i> Mobile:</strong>
                                                <span id="userMobile"></span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong><i class="fa fa-user-tag"></i> User Type:</strong>
                                                <span id="userType"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Modules and Forms Table -->
            <div class="card" id="moduleFormContainer" style="display: none;">
                <div class="card-body">
                    <div class="table-responsive">
                        <form method="post" action="<?php echo base_url('Welcome/insertData'); ?>">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-success btn-sm add-row-btn"
                                            style="float:left;" id="addRow">+ Add</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary btn-sm" id="saveButton"
                                            style="float:right;">Save</button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="userid" value="<?php echo isset($userId) ? $userId : ''; ?>" />

                            <table id="simpletable_kis"
                                class="table table-striped table-bordered nowrap bg-white form-table">
                                <thead>
                                    <tr>
                                        <th style="padding-top: 5px; padding-bottom: 5px;">Module Name</th>
                                        <th style="padding-top: 5px; padding-bottom: 5px;">Form Name</th>
                                        <th style="padding-top: 5px; padding-bottom: 5px;">Access</th>
                                        <th style="padding-top: 5px; padding-bottom: 5px;">From Date</th>
                                        <th style="padding-top: 5px; padding-bottom: 5px;">To Date</th>
                                        <th style="padding-top: 5px; padding-bottom: 5px;">Hold</th>
                                        <th style="width: 70px; padding-top: 5px; padding-bottom: 5px;">Action</th>
                                    </tr>
                                </thead>

                                <tbody id="moduleFormContainerInner">

                                    <tr>
                                        <input type="hidden" name="access_id[]" value="" />
                                        <!-- Hidden input for access_id -->

                                        <td style="padding: 2px 2px;">
                                            <select class="form-control form-control-sm" required name="module_id[]">
                                                <option value="">Select Module</option>
                                                <?php foreach ($allModules as $mod): ?>
                                                    <option value="<?= $mod['id']; ?>"><?= $mod['module_name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td style="padding: 2px 2px;">
                                            <select class="form-control form-control-sm" required name="form_id[]">
                                                <option value="">Select Form</option>
                                            </select>
                                        </td>
                                        <td style="padding: 2px 2px;">
                                            <select class="form-control form-control-sm" name="access[]">
                                                <option value="Read Write">Read Write</option>
                                                <option value="Read Only">Read Only</option>
                                            </select>
                                        </td>
                                        <td style="padding: 2px 2px;">
                                            <input type="date" name="from_date[]" class="form-control form-control-sm">
                                        </td>
                                        <td style="padding: 2px 2px;">
                                            <input type="date" name="to_date[]" class="form-control form-control-sm">
                                        </td>
                                        <td style="padding: 2px 2px;">
                                            <select class="form-control form-control-sm" name="hold[]">
                                                <option value="Active" class="text-success">Active</option>
                                                <option value="Inactive" class="text-danger">Inactive</option>
                                            </select>
                                        </td>
                                        <td style="padding: 2px 2px;">
                                            <button type="button" class="btn btn-danger btn-sm deleteRow"><i
                                                    class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <!-- No Data Message positioned after the table rows -->
                            <div id="noDataMessage"
                                style="display:none; text-align: center; color:red; padding: 0px 0;">
                                Not found any records for selected user name
                            </div>

                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <?php include 'application/views/jsPages.php'; ?>

    <script>

        document.getElementById('sameAsButton').addEventListener('click', function () {
            var sameAsDropdown = document.getElementById('sameAsUserid');
            var UserLabelDropdown = document.getElementById('UseLabel');

            if (sameAsDropdown.classList.contains('d-none')) {
                sameAsDropdown.classList.remove('d-none');
                UserLabelDropdown.classList.remove('d-none');

                // Fetch users via AJAX when 'Same As' is clicked
                fetchUsersForSameAs();
            } else {
                sameAsDropdown.classList.add('d-none');
                UserLabelDropdown.classList.add('d-none');
                sameAsDropdown.value = ''; // Clear the selected value in Same As dropdown
            }
        });

        function fetchUsersForSameAs() {
            var sameAsDropdown = document.getElementById('sameAsUserid');

            // Clear existing options
            sameAsDropdown.innerHTML = '<option value="">Select Users</option>';

            // Make AJAX request to fetch users
            fetch('<?= base_url('Welcome/getSameAsUsers'); ?>')
                .then(response => response.json())
                .then(data => {
                    data.forEach(user => {
                        var option = document.createElement('option');
                        option.value = user.spruser_id;
                        option.text = user.spruser_username;
                        sameAsDropdown.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching users:', error));
        }

        $(document).ready(function () {
            // Fetch users for the dropdown
            $.ajax({
                url: "<?php echo base_url('Welcome/fetchUsers'); ?>",
                method: "GET",
                dataType: "json",
                success: function (data) {
                    $.each(data, function (key, value) {
                        $('#userid').append('<option value="' + value.spruser_id + '">' + value.spruser_username + '</option>');
                        $('#sameAsUserid').append('<option value="' + value.spruser_id + '">' + value.spruser_username + '</option>');
                    });
                }
            });



            // Fetch user information and access data on Generate button click
            $('#viewButton').click(function () {
                var userId = $('#userid').val();
                var sameAsUserId = $('#sameAsUserid').val(); // Get the value from 'Same As' dropdown

                // If Same As dropdown is hidden, clear the sameAsUserId value
                if ($('#sameAsUserid').hasClass('d-none')) {
                    sameAsUserId = ''; // Clear the value if dropdown is hidden
                }

                // Use the "Same As" user for fetching access data, otherwise fall back to the regular user
                var userIdToFetchAccessData = sameAsUserId || userId;

                if (userId != "") {
                    // First, fetch user info for 'Username' dropdown
                    $.ajax({
                        url: "<?php echo base_url('Welcome/fetchUserInfo'); ?>",
                        method: "POST",
                        data: {
                            user_id: userId
                        },
                        dataType: "json",
                        success: function (data) {
                            // Populate user information based on 'userId'
                            $('input[name="userid"]').val(userId); // Always save data for 'Username' user
                            $('#userName').text(data.spruser_name);
                            $('#userEmail').text(data.spruser_email);
                            $('#userMobile').text(data.spruser_mobile);
                            $('#userType').text(data.spruser_usertype);
                            $('#userInfo').show();
                            $('#moduleFormContainerInner').html(''); // Clear previous rows

                            $('#moduleFormContainer').show();

                            // Now fetch access data using 'userIdToFetchAccessData' (which might be the 'Same As' user)
                            // fetchAccessData(userIdToFetchAccessData);
                        }
                    });
                } else {
                    alert("Please select a Username!");
                }
            });

            // Fetch forms based on selected module ID
            $(document).on('change', '[name="module_id[]"]', function () {
                var moduleId = $(this).val();
                var formSelect = $(this).closest('tr').find('[name="form_id[]"]');
                if (moduleId != "") {
                    $.ajax({
                        url: "<?php echo base_url('Welcome/fetchFormsByModuleId'); ?>",
                        method: "POST",
                        data: {
                            module_id: moduleId
                        },
                        dataType: "json",
                        success: function (data) {
                            formSelect.html('<option value="">Select Form</option>');
                            $.each(data, function (key, value) {
                                formSelect.append('<option value="' + value.id + '">' + value.form_name + '</option>');
                            });
                        }
                    });
                }
            });

            // Add new row
            $('#addRow').click(function () {
                addRow();
            });

            // Delete row
            $(document).on('click', '.deleteRow', function () {
                $(this).closest('tr').remove();
            });




            // On document ready, fetch all modules once
            var allModules = [];

            $(document).ready(function () {
                $.ajax({
                    url: "<?php echo base_url('Welcome/fetchModules'); ?>", // A new function to fetch all modules
                    method: "GET",
                    dataType: "json",
                    success: function (modules) {
                        allModules = modules;
                    }
                });
            });


            // Fetch user access data when 'Generate' button is clicked
            document.getElementById('viewButton').addEventListener('click', async function () {
                var userIdToFetch = document.getElementById('sameAsUserid').value || document.getElementById('userid').value;

                clearTable();
                document.getElementById('moduleFormContainerInner').innerHTML = "<tr><td colspan='6'>Please Wait...</td></tr>";

                try {
                    await new Promise(resolve => setTimeout(resolve, 300)); // Add delay
                    const response = await fetch('<?= base_url('Welcome/fetchUserAccessData'); ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ user_id: userIdToFetch })
                    });

                    const data = await response.json();
                    if (data.length > 0) {
                        populateTableWithAccessData(data, allModules);
                        document.getElementById('noDataMessage').style.display = 'none';
                    } else {
                        document.getElementById('noDataMessage').style.display = 'block';
                    }
                } catch (error) {
                    console.error('Error fetching user access data:', error);
                }
            });

            // Form submission check
            document.querySelector('form').addEventListener('submit', function (event) {
                let hasEmptyRow = false;
                let hasEmptyModuleName = false;
                let hasEmptyFormName = false;

                const rows = document.querySelectorAll('#moduleFormContainerInner tr');
                rows.forEach(row => {
                    const moduleNameSelect = row.querySelector('select[name="module_id[]"]');
                    const formNameSelect = row.querySelector('select[name="form_id[]"]');

                    // Check if the row is empty (both dropdowns are empty)
                    if (!moduleNameSelect.value && !formNameSelect.value) {
                        hasEmptyRow = true;
                    }

                    // Check if module name is empty
                    if (!moduleNameSelect.value) {
                        hasEmptyModuleName = true;
                    }

                    // Check if form name is empty
                    if (!formNameSelect.value) {
                        hasEmptyFormName = true;
                    }
                });

                // Alerts based on conditions
                if (document.getElementById('noDataMessage').style.display === 'block') {
                    alert('Please add a row!');
                    event.preventDefault(); // Prevent form submission
                } else if (hasEmptyRow) {
                    alert('Please select a module name & form name!');
                    event.preventDefault(); // Prevent form submission
                }
            });

            // Function to clear table
            function clearTable() {
                var tableBody = document.getElementById('moduleFormContainerInner');
                tableBody.innerHTML = ''; // Clear existing rows
            }

            // Function to populate table with fetched data and add module/form dropdowns
            function populateTableWithAccessData(data, allModules) {
                var tableBody = document.getElementById('moduleFormContainerInner');
                clearTable();

                data.forEach(row => {
                    var moduleOptions = allModules.map(module =>
                        `<option value="${module.id}" ${row.module_id == module.id ? 'selected' : ''}>${module.module_name}</option>`
                    ).join('');

                    var rowHtml = `
            <tr>
            <input type="hidden" name="access_id[]" value="${row.id}" />
                                           
                <td style="padding: 2px 2px;">
                    <select class="form-control form-control-sm moduleSelect" required name="module_id[]">
                        ${moduleOptions}
                    </select>
                </td>
                <td style="padding: 2px 2px;">
                    <select class="form-control form-control-sm formSelect" required name="form_id[]">
                        <option value="${row.form_id}">${row.form_name}</option>
                    </select>
                </td>
                <td style="padding: 2px 2px;">
                    <select class="form-control form-control-sm" name="access[]">
                        <option value="Read Write" ${row.access === 'Read Write' ? 'selected' : ''}>Read Write</option>
                        <option value="Read Only" ${row.access === 'Read Only' ? 'selected' : ''}>Read Only</option>
                    </select>
                </td>
                <td style="padding: 2px 2px;">
                    <input type="date" name="from_date[]" class="form-control form-control-sm" value="${row.from_date}">
                </td>
                <td style="padding: 2px 2px;">
                    <input type="date" name="to_date[]" class="form-control form-control-sm" value="${row.to_date}">
                </td>
                <td style="padding: 2px 2px;">
                    <select class="form-control form-control-sm" name="hold[]">
                        <option value="Active"  class="text-success" ${row.hold === 'Active' ? 'selected' : ''}>Active</option>
                        <option value="Inactive"  class="text-danger" ${row.hold === 'Inactive' ? 'selected' : ''}>Inactive</option>
                    </select>
                </td>
                <td style="padding: 2px 2px;">
                    <button type="button" class="btn btn-danger btn-sm deleteRow">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>`;

                    tableBody.insertAdjacentHTML('beforeend', rowHtml);
                });
            }



            // Add a new row function
            function addRow() {
                // Hide the "No records found" message when a new row is added
                document.getElementById('noDataMessage').style.display = 'none';

                // Get the current date in the format YYYY-MM-DD
                var today = new Date().toISOString().split('T')[0];

                var rowHtml = `
                <tr>
                 <input type="hidden" name="access_id[]" value="" />
                    <td style="padding: 2px 2px;">
                        <select class="form-control form-control-sm" required name="module_id[]">
                            <option value="">Select Module</option>
                            <?php foreach ($allModules as $mod): ?>
                                                    <option value="<?= $mod['id']; ?>"><?= $mod['module_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td style="padding: 2px 2px;">
                        <select class="form-control form-control-sm" required name="form_id[]">
                            <option value="">Select Form</option>
                        </select>
                    </td>
                    <td style="padding: 2px 2px;">
                        <select class="form-control form-control-sm" name="access[]">
                            <option value="Read Write">Read Write</option>
                            <option value="Read Only">Read Only</option>
                        </select>
                    </td>
                  <td style="padding: 2px 2px;">
            <input type="date" name="from_date[]" class="form-control form-control-sm" value="${today}">
        </td>
                    <td style="padding: 2px 2px;">
                        <input type="date" name="to_date[]" class="form-control form-control-sm">
                    </td>
                    <td style="padding: 2px 2px;">
                        <select class="form-control form-control-sm" name="hold[]">
                            <option value="Active" class="text-success">Active</option>
                            <option value="Inactive" class="text-danger">Inactive</option>
                        </select>
                    </td>
                    <td style="padding: 2px 2px;">
                        <button type="button" class="btn btn-danger btn-sm deleteRow">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
                $('#moduleFormContainerInner').prepend(rowHtml);
            }

        });


        $(document).ready(function () {
            var deletedRows = [];  // Array to track deleted rows

            // Delete row in the UI and track it for later deletion in the database
            $(document).on('click', '.deleteRow', function () {
                var row = $(this).closest('tr');
                var moduleId = row.find('[name="module_id[]"]').val();
                var formId = row.find('[name="form_id[]"]').val();
                var userId = $('#userid').val(); // Get the selected user ID from #userid dropdown


                // Check if the current user is the one selected in #userid
                if (userId) {
                    // Track the deleted row's data for future deletion
                    deletedRows.push({
                        user_id: userId,
                        module_id: moduleId,
                        form_id: formId
                    });

                    // Remove the row from the table in the UI
                    row.remove();
                } else {

                }
            });

            // Save button click event
            $('#saveButton').click(function () {


                // If there are deleted rows, send an AJAX request to delete them from the database
                if (deletedRows.length > 0) {
                    $.ajax({
                        url: "<?php echo base_url('Welcome/deleteUserAccessRows'); ?>", // New endpoint for bulk deletion
                        method: "POST",
                        contentType: "application/json",
                        data: JSON.stringify(deletedRows), // Send all deleted rows data
                        success: function (response) {
                            if (response.success) {
                                console.log('Deleted rows successfully.');
                                deletedRows = []; // Clear the tracking array
                            } else {
                                console.error('Error deleting rows:', response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX error:', error);
                        }
                    });
                }
            });


        });
    </script>

</body>
</html>
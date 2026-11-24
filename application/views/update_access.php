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
            padding: 8px 10px;
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

         /* Default select styling */
    .form-table select {
        width: 100%;
        box-sizing: border-box;
        color: #000; /* Default text color */
    }

    /* Custom styles for the dropdown options */
    .form-table select option[data-status="Active"] {
        color: green;
    }

    .form-table select option[data-status="Inactive"] {
        color: red;
    }

    /* Styling for dropdowns with dynamic color */
    .colored-option {
        color: green; /* Default to Active color */
    }

    .user-info-container {
        background-color: #f9f9f9;
        border: 1px solid #dedede;
        margin-top: 10px;
    }
    .user-info-container .row div {
        padding: 0px 20px;
    }
    .user-info-container i {
        color: #007bff;
        margin-right: 5px;
    }
    .user-info-container strong {
        font-weight: bold;
        color: #333;
    }
    .user-info-container span {
        color: #555;
    }

    
    /* .shade {
    box-shadow: 0 0.125rem 0.25rem rgba(0.1, 0.1, 0.1, 0.145) !important;
} */

    </style>
    <?php include 'application/views/cssPages.php'; ?>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'topNav.php'; ?>

    <div class="pc-container">
        <div class="pcoded-content" style="padding-top: 10px;">
            <!-- Links -->
            |<a href="<?php echo base_url(); ?>Welcome/user_report"> View Report</a> |
            <a href="<?php echo base_url(); ?>Welcome/user"> User Access</a> |

          <!-- User Information -->
<div class="row mt-2">
    <div class="col-md-12">
        <div class="card rounded" style="margin-bottom: 10px !important;">
            <div class="card-body" style="padding-bottom: 0px !important;">
                <div class="d-flex align-items-center mb-3">
                    <i class="fa fa-user" style="font-size: 20px; color: #007bff;"></i>
                    <h4 class="mb-0 ml-2 font-weight-bold">Edit User Access</h4>
                </div>

                <!-- User Information Display -->
                <div class="form-group row mt-3" id="userInfo">
                    <div class="col-md-12">
                        <div class="user-info-container p-3 rounded shade" style="border: #f5f5f5;">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong><i class="fa fa-user-circle"></i> User Name:</strong>
                                    <span><?php echo $userInfo['spruser_name']; ?></span>
                                </div>
                                <div class="col-md-4">
                                    <strong><i class="fa fa-envelope"></i> Email:</strong>
                                    <span><?php echo $userInfo['spruser_email']; ?></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong><i class="fa fa-phone"></i> Mobile No:</strong>
                                    <span><?php echo $userInfo['spruser_mobile']; ?></span>
                                </div>
                                <div class="col-md-4">
                                    <strong><i class="fa fa-user-tag"></i> User Type:</strong>
                                    <span><?php echo $userInfo['spruser_usertype']; ?></span>
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
            <div class="card" id="moduleFormContainer">
                <div class="card-body">
                    <div class="table-responsive">
                        <form method="post" action="<?php echo base_url('Welcome/updateData'); ?>">
                           
                                <div class="col-md-12" style="margin-bottom: -15px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-success btn-sm add-row-btn"
                                            style="float:left;" id="addRow">+ Add</button>
                                    </div>
                                    <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-sm mb-2" id="saveButton"
                                    style="float:right;">Save</button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="userid" value="<?php echo $userId; ?>" />
                            <input type="hidden" id="deletedRows" name="deleted_rows" value="" />

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
                                    <?php foreach ($userAccess as $access): ?>
                                        <tr>

                                            <input type="hidden" name="access_id[]" value="<?= $access['id']; ?>" />
                                            <!-- Hidden input for access_id -->


                                            <td style="padding: 4px 4px;">
                                                <select class="form-control form-control-sm moduleSelect" required
                                                    name="module_id[]">
                                                    <option value="">Select Module</option>
                                                    <?php foreach ($allModules as $mod): ?>
                                                        <option value="<?= $mod['id']; ?>" <?= $mod['id'] == $access['module_id'] ? 'selected' : ''; ?>>
                                                            <?= $mod['module_name']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td style="padding: 4px 4px;">
                                                <select class="form-control form-control-sm formSelect" required
                                                    name="form_id[]">
                                                    <option value="<?= $access['form_id']; ?>">
                                                        <?= $access['form_name']; ?>
                                                    </option>
                                                </select>
                                            </td>
                                            <td style="padding: 4px 4px;">
                                                <select class="form-control form-control-sm" name="access[]">
                                                    <option value="Read Write" <?= $access['access'] == 'Read Write' ? 'selected' : ''; ?>>Read Write</option>
                                                    <option value="Read Only" <?= $access['access'] == 'Read Only' ? 'selected' : ''; ?>>Read Only</option>
                                                </select>
                                            </td>
                                            <td style="padding: 4px 4px;">
                                                <input type="date" name="from_date[]" class="form-control form-control-sm"
                                                    value="<?= $access['from_date']; ?>">
                                            </td>
                                            <td style="padding: 4px 4px;">
                                                <input type="date" name="to_date[]" class="form-control form-control-sm"
                                                    value="<?= $access['to_date']; ?>">
                                            </td>
                                            <td style="padding: 4px 4px;"> 
                                                <select class="form-control form-control-sm" name="hold[]">
                                                    <option value="Active" data-status="Active" <?= $access['hold'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                                                    <option value="Inactive" data-status="Inactive" <?= $access['hold'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                                                </select>
                                            </td>
                                            <td style="padding: 4px 4px;">
                                                <button type="button" class="btn btn-danger btn-sm deleteRow">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Include JS files -->
    <?php include 'application/views/jsPages.php'; ?>

    <script>
   $(document).ready(function () {
  // Function to get current date in YYYY-MM-DD format
  function getCurrentDate() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    return yyyy + '-' + mm + '-' + dd;
  }

  // Add row functionality
  $('#addRow').on('click', function () {
    var newRow = `
      <tr>
        <input type="hidden" name="access_id[]" value="" />
        <td style="padding: 4px 4px;">
          <select class="form-control form-control-sm moduleSelect" required name="module_id[]">
            <option value="">Select Module</option>
            <?php foreach ($allModules as $mod): ?>
              <option value="<?= $mod['id']; ?>"><?= $mod['module_name']; ?></option>
            <?php endforeach; ?>
          </select>
        </td>
        <td style="padding: 4px 4px;">
          <select class="form-control form-control-sm formSelect" required name="form_id[]">
            <option value="">Select Form</option>
          </select>
        </td>
        <td style="padding: 4px 4px;">
          <select class="form-control form-control-sm" name="access[]">
            <option value="Read Write" selected>Read Write</option>
            <option value="Read Only">Read Only</option>
          </select>
        </td>
        <td style="padding: 4px 4px;">
          <input type="date" name="from_date[]" class="form-control form-control-sm" value="${getCurrentDate()}">
        </td>
        <td style="padding: 4px 4px;">
          <input type="date" name="to_date[]" class="form-control form-control-sm" value="">
        </td>
        <td style="padding: 4px 4px;">
          <select class="form-control form-control-sm" name="hold[]">
            <option value="Active" data-status="Active" selected>Active</option>
            <option value="Inactive" data-status="Inactive">Inactive</option>
          </select>
        </td>
        <td style="padding: 4px 4px;">
          <button type="button" class="btn btn-danger btn-sm deleteRow">
            <i class="fa fa-trash"></i>
          </button>
        </td>
      </tr>`;

    // Prepend the new row to the table
    $('#moduleFormContainerInner').prepend(newRow);

    // Re-apply the color update for the new select element
    updateSelectColors();
  });

  // Existing code to handle color changes for select elements
  function updateSelectColors() {
    $('select[name="hold[]"]').each(function () {
      var selectedValue = $(this).val();
      $(this).find('option').each(function () {
        var color = $(this).data('status') === 'Active' ? 'green' : 'red';
        if ($(this).val() == selectedValue) {
          $(this).css('color', color); // Set color for selected option
        } else {
          $(this).css('color', color); // Set color for non-selected options
        }
      });
    });
  }

  // Initial call to update colors
  updateSelectColors();

  // Update colors on change
  $(document).on('change', 'select[name="hold[]"]', function () {
    updateSelectColors();
  });

  // Handle row deletion and storing deleted IDs
  var deletedRowIds = [];
  $(document).on('click', '.deleteRow', function () {
    var row = $(this).closest('tr');
    var accessId = row.find('input[name="access_id[]"]').val();

    if (accessId) {
      deletedRowIds.push(accessId);
    }

    row.remove();
  });

  // Set deleted rows before form submission
  $('#saveButton').on('click', function () {
    $('#deletedRows').val(JSON.stringify(deletedRowIds));
  });
});

</script>


    <script>
        $(document).ready(function () {


            // Fetch forms based on selected module
            $(document).on('change', '.moduleSelect', function () {
                var moduleId = $(this).val();
                var formSelect = $(this).closest('tr').find('.formSelect');

                $.ajax({
                    url: '<?php echo base_url("Welcome/getForms"); ?>',
                    type: 'POST',
                    data: { moduleId: moduleId },
                    success: function (response) {
                        formSelect.html(response);
                    }
                });
            });

        });
    </script>

    <!-- <script>
        $(document).ready(function () {
            var deletedRowIds = [];

            // Delete row functionality
            $(document).on('click', '.deleteRow', function () {
                var row = $(this).closest('tr');
                var accessId = row.find('input[name="access_id[]"]').val();

                // Store the deleted access ID in the array
                if (accessId) {
                    deletedRowIds.push(accessId);
                }

                // Remove the row from the UI
                row.remove();
            });

            // Before form submission, set the deleted rows in the hidden input
            $('#saveButton').on('click', function () {
                $('#deletedRows').val(JSON.stringify(deletedRowIds));
            });
        });

    </script> -->

</body>

</html>
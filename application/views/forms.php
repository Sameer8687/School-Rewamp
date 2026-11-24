<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Forms</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include 'application/views/cssPages.php'; ?>
    <style>
        .pc-container {
            top: 50px;
        }


        .card-body {
            padding: 20px;
        }

        /* Align form labels and inputs for consistency */
        .form-group.row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group.row .col-md-6 {
            display: flex;
            align-items: center;
        }

        .col-md-6 label {
            flex: 1;
            margin-right: 10px;
        }

        .col-md-6 input,
        .col-md-6 select {
            flex: 2;
            width: auto;
            /* Removes any width restrictions */
        }

        /* Font weight for labels */
        .col-form-label {
            font-weight: 600;
        }

        /* Consistent padding for form fields */
        .form-control-sm {
            padding: 0px 12px;
        }

        /* Styling for header and icons */
        .fa-list-alt {
            color: #007bff;
            font-size: 24px;
            margin-right: 10px;
        }

        /* Submit button styling */
        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .card {
            border-radius: 4px;
            /* border: 1px solid #e3e3e3; */
        }

        .text-danger {
            color: #dc3545;
        }

        .col-form-label {
            padding-top: 0px !important;
            padding-bottom: 0px !important;
        }

        #simpletable tbody tr td {
            padding: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        #simpletable tbody tr {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'topNav.php'; ?>

    <div class="pc-container">
        <div class="pcoded-content" style="padding-top: 10px;">
            |<a href="<?php echo base_url(); ?>Welcome/mode"> Add Modules </a>|<a
                href="<?php echo base_url(); ?>Welcome/user"> User Access </a>|<a
                href="<?php echo base_url(); ?>Welcome/user_report"> View Report</a>
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card rounded" style="margin-bottom: 10px !important;">
                        <div class="card-body">
                            <form id="moduleForm" action="<?php echo base_url(); ?>Welcome/forms"
                                enctype="multipart/form-data" method="post">
                                <!-- Header with Icon -->
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-list-alt" style="font-size: 20px; color: #007bff;"></i>
                                        <h4 class="mb-0 ml-2 font-weight-bold">Forms</h4>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                </div>

                                <!-- Form Section -->
                                <input type="hidden" name="form_id" id="form_id">
                                <div class="adjusting">
                                    <!-- Module Name and Form Name Fields in One Row -->
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="moduleid" class="col-form-label font-weight-bold">Module Name
                                                <span class="text-danger">*</span>:</label>
                                            <select autocomplete="off" class="form-control form-control-sm" required
                                                name="moduleid" id="moduleid">
                                                <option value="">-- Select Module --</option>
                                                <?php foreach ($modules as $module): ?>
                                                    <option value="<?php echo $module['id']; ?>">
                                                        <?php echo $module['module_name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="formname" class="col-form-label font-weight-bold">Form Name
                                                <span class="text-danger">*</span>:</label>
                                            <input type="text" autocomplete="off" class="form-control form-control-sm"
                                                required name="formname" id="formname" placeholder="Enter form name..">
                                        </div>
                                    </div>
                                    <!-- Form Code and Form Link Fields in One Row -->
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="formcode" class="col-form-label font-weight-bold">Form Code
                                                <span class="text-danger"></span>:</label>
                                            <input type="text" autocomplete="off" class="form-control form-control-sm"
                                                 name="formcode" id="formcode" placeholder="Enter form code..">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="formlink" class="col-form-label font-weight-bold">Form Link
                                                <span class="text-danger">*</span>:</label>
                                            <input type="text" autocomplete="off" class="form-control form-control-sm"
                                                required name="formlink" id="formlink" placeholder="Enter form link..">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="formtype" class="col-form-label font-weight-bold">Form Type
                                                <span class="text-danger">*</span>:</label>
                                            <select class="form-control form-control-sm" required name="formtype"
                                                id="formtype">
                                                <option value="">-- Select Form Type --</option>
                                                <option value="Master Form">Master Form</option>
                                                <option value="Report">Report</option>
                                                <option value="Form">Form</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dt-responsive table-responsive">
                                <table id="simpletable" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Sr.No.</th>
                                            <th class="text-left">Form Name</th>
                                            <th>Form Code</th>
                                            <th>Form Type</th>
                                            <th>Form Link</th>
                                            <th>Module Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1; ?>
                                        <?php foreach ($forms as $form): ?>
                                            <tr>
                                                <td class="text-center"><?php echo $count++; ?></td>
                                                <td><?php echo $form['form_name']; ?></td>
                                                <td><?php echo $form['form_code']; ?></td>
                                                <td><?php echo $form['form_type']; ?></td>
                                                <td><?php echo $form['form_link']; ?></td>
                                                <td><?php echo $form['module_name']; ?></td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0);" class="btn btn-warning btn-sm edit-btn"
                                                        data-id="<?php echo $form['id']; ?>"><i class="fa fa-edit"></i></a>
                                                    <a href="<?php echo base_url('Welcome/deleteForm/' . $form['id']); ?>"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this form?');"><i
                                                            class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'application/views/jsPages.php'; ?>

    <script>
        $(document).ready(function () {
            // Fetch data for the form when editing
            $('#simpletable').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                $.ajax({
                    url: '<?php echo base_url(); ?>Welcome/fetchSingleForm/' + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#form_id').val(data.id);
                        $('#formname').val(data.form_name);
                        $('#formcode').val(data.form_code);
                        $('#formtype').val(data.form_type);
                        $('#formlink').val(data.form_link);
                        $('#moduleid').val(data.module_id);
                    }
                });
            });
        });
    </script>
</body>

</html>
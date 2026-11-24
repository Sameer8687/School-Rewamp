<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Module Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include 'cssPages.php'; ?>
    <style>
        .pc-container {
            top: 50px;
        }

        .but {
            float: right;
        }

        .card-body {
            padding: 20px;
        }

        /* Align form labels and inputs for consistency */
        .form-group.row {
            margin-bottom: 15px;
        }

        /* Font weight for labels */
        .col-form-label {
            font-weight: 600;
        }

        /* Consistent padding for form fields */
        .form-control-sm {
            padding: 6px 12px;
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
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'topNav.php'; ?>

    <div class="pc-container">
        <div class="pcoded-content" style="padding-top: 10px;">
            |<a href="<?php echo base_url(); ?>Welcome/forms"> Add Forms </a>|<a
                href="<?php echo base_url(); ?>Welcome/user"> User Access </a>|<a
                href="<?php echo base_url(); ?>Welcome/user_report"> View Report</a>
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card rounded" style="margin-bottom: 10px !important;">
                        <div class="card-body" style="padding-bottom: 0px;">
                            <!-- Form Section -->
                            <form id="moduleForm" action="<?php echo base_url(); ?>Welcome/mode"
                                enctype="multipart/form-data" method="post">
                                <!-- Header with Icon -->
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-list-alt" style="font-size: 20px; color: #007bff;"></i>
                                        <h4 class="mb-0 ml-2 font-weight-bold">Module Form</h4>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                </div>
                                <input type="hidden" name="module_id" id="module_id">

                                <!-- Module Name Field -->
                                <div class="form-group row">
                                    <label for="modename" class="col-md-3 col-form-label font-weight-bold">Module Name
                                        <span class="text-danger">*</span>:</label>
                                    <div class="col-md-9">
                                        <input type="text" autocomplete="off" class="form-control form-control-sm"
                                            required name="modename" id="modename" style="width: 45%;"
                                            placeholder="Enter module name">
                                    </div>
                                </div>

                                <!-- Icon Name Field -->
                                <div class="form-group row">
                                    <label for="iconname" class="col-md-3 col-form-label font-weight-bold">Icon Name
                                        <span class="text-danger"></span>:</label>
                                    <div class="col-md-9">
                                        <input type="text" autocomplete="off" class="form-control form-control-sm"
                                            name="iconname" id="iconname" style="width: 45%;"
                                            placeholder="Enter icon name">
                                    </div>
                                </div>

                                <!-- Module Description Field -->
                                <div class="form-group row">
                                    <label for="modedesc" class="col-md-3 col-form-label font-weight-bold">Module
                                        Description <span class="text-danger"></span>:</label>
                                    <div class="col-md-9">
                                        <textarea autocomplete="off" class="form-control form-control-sm" rows="3"
                                            name="modedesc" id="modedesc" style="width: 60%;"
                                            placeholder="Enter module description"></textarea>
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
                                            <th class="text-left">Module Name</th>
                                            <th class="text-left">Icon Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Fetch module data
                                        $modules = $this->Modules_Model->fetch_module();
                                        $index = 1;
                                        foreach ($modules as $module) {
                                            echo '<tr>' .
                                                '<td class="text-center">' . $index++ . '</td>' .
                                                '<td>' . htmlspecialchars($module['module_name']) . '</td>' .
                                                '<td>' . htmlspecialchars($module['icon']) . '</td>' .
                                                '<td class="text-center">' .
                                                '<button type="button" class="btn btn-warning btn-sm edit-btn" data-id="' . $module['id'] . '"><i class="fa fa-edit"></i></button> ' .
                                                '<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' . $module['id'] . '"><i class="fa fa-trash"></i></button>' .
                                                '</td>' .
                                                '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'jsPages.php'; ?>
    <script>
        $(document).ready(function () {
            // Fetch data for the form when editing
            $('#simpletable').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                $.ajax({
                    url: '<?php echo base_url(); ?>Welcome/fetchSingleModule/' + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#module_id').val(data.id);
                        $('#modename').val(data.module_name);
                        $('#iconname').val(data.icon);
                        $('#modedesc').val(data.module_description);
                    }
                });
            });

            // Handle delete button click
            $('#simpletable').on('click', '.delete-btn', function () {
                if (confirm('Are you sure you want to delete this module?')) {
                    window.location.href = '<?php echo base_url(); ?>Welcome/deleteModule/' + $(this).data('id');
                }
            });
        });
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - User Report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include 'application/views/cssPages.php'; ?>
<style>
       .pc-container {
            top: 50px;
        }

        /* Enhanced table design */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            /* box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); */
        }

        thead th {
            background-color: #007bff;
            color: #fff;
            text-transform: uppercase;
            font-size: 14px;
            font-weight: 600;
            padding: 10px;
            border-bottom: 2px solid #007bff;
        }

        tbody tr {
            background-color: #f9f9f9;
            transition: all 0.3s;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
            /* box-shadow: 0 1px 5px rgba(0, 0, 0, 0.15); */
        }

        tbody td {
            padding: 12px;
            font-size: 14px;
            color: #333;
        }

        tbody td:first-child {
            border-left: 5px solid #007bff;
        }

        .btn {
            font-size: 12px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            .dt-responsive {
                overflow-x: auto;
            }
        }

        /* Additional spacing and alignment */
        .card {
            border: none;
            /* box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); */
            border-radius: 8px;
        }

        .card-body {
            padding: 20px;
        }

        .d-flex i {
            font-size: 24px;
            color: #007bff;
        }

        h4.mb-0 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .ml-2 {
            margin-left: 8px;
        }

        .mt-2 {
            margin-top: 12px;
        }

        .mt-3 {
            margin-top: 16px;
        }
</style>
    </head>

<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'topNav.php'; ?>

    <div class="pc-container">
        <div class="pcoded-content" style="padding-top: 10px;">
            |<a href="<?php echo base_url(); ?>Welcome/user"> User Access </a>|<a href="<?php echo base_url(); ?>Welcome/mode"> Add Modules </a>| <a href="<?php echo base_url(); ?>Welcome/forms"> Add Forms </a>
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center" style="margin-top: -10px;">
                                <i class="fa fa-file" aria-hidden="true"></i>
                                <h4 class="mb-0 ml-2">Report</h4>
                            </div>

                            <div class="dt-responsive table-responsive mt-3">
                                <table id="simpletable" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center" style="width: 50px;">Sr.No.</th>
                                            <th class="text-left">User Name</th>
                                            <th class="text-center">Forms</th>
                                            <th class="text-center" style="width: 210px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($user_reports)) {
                                            $sr_no = 1;
                                            foreach ($user_reports as $report) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $sr_no++; ?></td>
                                                    <td class="text-left"><?php echo $report->spruser_name; ?></td>
                                                    <td class="text-center"><?php echo $report->form_count; ?></td>
                                                    <td class="text-center">
                                                        <a href="<?php echo base_url('Welcome/access_List/' . $report->spruser_id); ?>" class="btn btn-sm btn-info">View</a>
                                                        <a href="<?php echo base_url('Welcome/updateAccess/' . $report->spruser_id); ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <a href="<?php echo base_url('Welcome/delete_user/' . $report->spruser_id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td colspan="4" class="text-center">No data available</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                        
    <?php include 'application/views/jsPages.php'; ?>

    
</body>

</html>
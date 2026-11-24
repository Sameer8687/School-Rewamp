<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Access Form List</title>
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
        table-layout: fixed; /* Ensures the table is evenly distributed */
    }

    .form-table th,
    .form-table td {
        padding: 8px 10px;
        border: 1px solid #ddd;
        text-align: center;
        vertical-align: middle; /* Ensures content is vertically centered */
        word-wrap: break-word; /* Forces long content to wrap */
    }

    .form-table th {
        background-color: #53769c !important;
        color: #fff !important;
    }

    .form-table td select,
    .form-table td input {
        width: 100%; /* Ensures select and input fields fill their cells */
        box-sizing: border-box; /* Ensures padding does not exceed cell boundaries */
    }

    .add-row-btn {
        margin-top: 10px;
        cursor: pointer;
    }

    #moduleFormContainer {
        margin-top: 20px;
    }

</style>
    <?php include 'application/views/cssPages.php'; ?>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'topNav.php'; ?>

    <div class="pc-container">
        <div class="pcoded-content">
        |<a href="<?php echo base_url(); ?>Welcome/user_report"> View Report </a>|<a href="<?php echo base_url(); ?>Welcome/user"> User Access </a>
        <div class="row mt-2">
        <div class="card">
        <div class="card-body">
        <div class="d-flex align-items-center" style="margin-top: -10px;">
                        <i class="fa fa-list" aria-hidden="true"></i>
    <h4 class="mb-0 ml-2">Access List</h4>
</div>
            <div class="table-responsive" style=" margin-top: -10px;">
                <table id="simpletable_kis" class="table table-striped table-bordered nowrap bg-white form-table">
                    <thead>
                        <tr>
                            <th style="padding-top: 4px; padding-bottom: 4px;">Module Name</th>
                            <th style="padding-top: 4px; padding-bottom: 4px;">Form Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($access_list)): ?>
                            <?php 
                            $current_module = '';
                            $rowspan_count = 0;

                            // Group the access list by module name
                            $module_forms = [];
                            foreach ($access_list as $access) {
                                $module_forms[$access['module_name']][] = $access['form_name'];
                            }

                            // Loop through each module and its forms
                            foreach ($module_forms as $module_name => $forms): ?>
                                <tr>
                                    <td rowspan="<?php echo count($forms); ?>"><?php echo $module_name; ?></td>
                                    <td><?php echo array_shift($forms); ?></td> <!-- Display the first form in the same row as the module -->
                                </tr>
                                <?php foreach ($forms as $form_name): ?> <!-- Loop through remaining forms -->
                                    <tr>
                                        <td><?php echo $form_name; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2">No data available for this user.</td>
                            </tr>
                        <?php endif; ?>
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

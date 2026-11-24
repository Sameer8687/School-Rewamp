<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bank File Upload</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <?php include 'application/views/cssPages.php'; ?>

    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <style>
        .bank-upload-box {
            border: 1px dashed #b8c4d6;
            border-radius: 12px;
            padding: 30px;
            background: #f8fafc;
            text-align: center;
        }

        .bank-upload-icon {
            width: 62px;
            height: 62px;
            border-radius: 50%;
            background: #eef4ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 15px;
        }

        .bank-upload-title {
            font-size: 16px;
            font-weight: 700;
            color: #273041;
            margin-bottom: 6px;
        }

        .bank-upload-subtitle {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 18px;
        }

        .summary-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px;
            background: #fff;
            height: 100%;
        }

        .summary-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .screen-loader {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.78);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .loader-box {
            background: #ffffff;
            border-radius: 12px;
            padding: 25px 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            text-align: center;
            font-weight: 600;
            color: #111827;
        }

        .spinner {
            width: 38px;
            height: 38px;
            border: 4px solid #dbeafe;
            border-top-color: #2563eb;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 12px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .error-box {
            color: #dc2626;
            font-size: 12px;
            font-weight: 600;
            margin-top: 12px;
            display: none;
        }

        .group-id-text {
            font-size: 12px;
            color: #6b7280;
            word-break: break-all;
        }
    </style>
</head>

<body class="">

    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <?php include 'application/views/sidebar.php'; ?>
    <?php include 'application/views/topNav.php'; ?>

    <div class="screen-loader" id="screenLoader">
        <div class="loader-box">
            <div class="spinner"></div>
            Uploading and saving bank transactions...
        </div>
    </div>

    <div class="pc-container">
        <div class="pcoded-content">

            <div class="row margin-top-minus-40 mobile-margin-top-minus-40 mb-2">
                <div class="col-md-7">
                    <a href="<?= base_url(); ?>" class="btn btn-danger btn-sm">
                        <i class="fas fa-arrow-alt-circle-left"></i> Back
                    </a>

                    <a href="<?= site_url('fees-bank-upload/report'); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-list"></i> Bank Upload Report
                    </a>
                </div>
            </div>

            <div class="row" id="uploadDiv">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Bank Transaction File Upload
                            </h5>
                        </div>

                        <div class="card-body">
                            <form id="bankUploadForm" enctype="multipart/form-data">

                                <div class="bank-upload-box">
                                    <div class="bank-upload-icon">
                                        <i class='bx bx-upload'></i>
                                    </div>

                                    <div class="bank-upload-title">
                                        Upload Bank Transaction File
                                    </div>

                                    <div class="bank-upload-subtitle">
                                        Allowed file types: XLS, XLSX
                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="col-md-5">
                                            <input type="file"
                                                name="bank_file"
                                                id="bank_file"
                                                class="form-control form-control-sm p-0"
                                                accept=".xls,.xlsx"
                                                required>
                                        </div>

                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary btn-sm w-100" id="btnUpload">
                                                <i class="fas fa-upload"></i> Upload
                                            </button>
                                        </div>
                                    </div>

                                    <div class="error-box" id="errorBox"></div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="resultBox" style="display:none;">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Upload Summary
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="alert alert-success mb-0">
                                        File uploaded successfully. Please check below upload summary.
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3 mb-2">
                                    <div class="summary-card">
                                        <div class="summary-label">Total Rows Read</div>
                                        <div class="summary-value" id="resTotal">0</div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-2">
                                    <div class="summary-card">
                                        <div class="summary-label">Inserted Rows</div>
                                        <div class="summary-value text-success" id="resInserted">0</div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-2">
                                    <div class="summary-card">
                                        <div class="summary-label">Failed Rows</div>
                                        <div class="summary-value text-danger" id="resFailed">0</div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-2">
                                    <div class="summary-card">
                                        <div class="summary-label">Duplicate Rows</div>
                                        <div class="summary-value text-warning" id="resDuplicate">0</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="font-weight-bold mb-1">Group ID</label>
                                    <div class="group-id-text" id="resGroupId"></div>
                                </div>
                            </div>

                            <a href="#" id="detailsLink" class="btn btn-primary btn-sm">
                                <i class="fas fa-list"></i> View Uploaded Transactions
                            </a>

                            <a href="<?= site_url('fees-bank-upload'); ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-upload"></i> Upload Another File
                            </a>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include 'application/views/jsPages.php'; ?>

    <script>
        $('#bankUploadForm').on('submit', function(e) {
            e.preventDefault();

            let fileInput = $('#bank_file')[0];

            if (!fileInput.files.length) {
                $('#errorBox').text('Please select file.').show();
                return;
            }

            let formData = new FormData(this);

            $('#errorBox').hide().text('');
            $('#screenLoader').css('display', 'flex');
            $('#uploadDiv input, #uploadDiv button').prop('disabled', true);

            $.ajax({
                url: "<?= site_url('fees-bank-upload/upload'); ?>",
                type: "POST",
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,

                success: function(res) {
                    if (res.status === 'success') {
                        $('#uploadDiv').hide();

                        $('#resGroupId').text(res.group_id);
                        $('#resTotal').text(res.total_read);
                        $('#resInserted').text(res.inserted);
                        $('#resFailed').text(res.failed);
                        $('#resDuplicate').text(res.duplicates);

                        $('#detailsLink').attr('href', res.details_url);

                        $('#resultBox').show();
                    } else {
                        $('#errorBox').text(res.message || 'Upload failed.').show();
                        $('#uploadDiv input, #uploadDiv button').prop('disabled', false);
                    }
                },

                error: function() {
                    $('#errorBox').text('Something went wrong while uploading file.').show();
                    $('#uploadDiv input, #uploadDiv button').prop('disabled', false);
                },

                complete: function() {
                    $('#screenLoader').hide();
                }
            });
        });
    </script>

</body>

</html>
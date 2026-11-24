<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Olive English School - Issue LC</title>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui"
    />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Phoenixcoded" />
    <!---Include CSS Files-->
    <?php include 'application/views/cssPages.php'; ?>
    <link
      rel="stylesheet"
      href="<?php echo base_url(); ?>assets/css/id-card.css"
    />
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
                <h5 class="card-title">L.C Approval Report</h5>
              </div>
              <div class="card-body p-1">
                <div class="print-container">
                  <div class="col-md-12">
                    <div class="row mb-3">
                      <div class="col-md-4">
                        <div class="row">
                          <label for="" class="col-md-5"
                            >Academic Year
                            <span class="float-right">:</span></label
                          >

                          <input
                            type="date"
                            name="todate"
                            id="todate"
                            class="form-control form-control-sm col-md-7"
                            value="<?= $to_date != '' ? $to_date : '' ?>"
                          />
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="row">
                          <label for="" class="col-md-5"
                            >Select Class
                            <span class="float-right">:</span></label
                          >
                          <input
                            type="date"
                            name="fromdate"
                            id="fromdate"
                            class="form-control form-control-sm col-md-7"
                            value="<?= $from_date != '' ? $from_date : '' ?>"
                          />
                        </div>
                      </div>
                      <div class="col-md-3">
                        <button
                          class="btn btn-sm btn-outline-secondary"
                          id="apply"
                          onclick="filter_data()"
                        >
                          Apply
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div
                      class="dt-responsive table-responsive margin-top-minus-10"
                    >
                      <table
                        id="simpletable"
                        class="table table-striped table-bordered nowrap"
                      >
                        <thead>
                          <tr class="text-center">
                            <th class="text-capitalize">Sr.No</th>
                            <th class="text-capitalize">Student ID</th>
                            <th class="text-left text-capitalize">Name</th>
                            <th class="text-left text-capitalize">Class</th>
                            <th class="text-capitalize">Requested By</th>
                            <th class="text-capitalize">Requested At</th>
                            <th class="text-capitalize">Action</th>
                          </tr>
                        </thead>

                        <tbody id="tbody">
                          <?php $i = 1; foreach ($students as $student): ?>
                          <tr>
                            <td><?= $i ?></td>
                            <td>
                              <?= $student['student_id'] ?>
                              <input
                                type="hidden"
                                id="hidden_id"
                                value="<?= $student['student_id'] ?>"
                              />
                            </td>

                            <td><?= $student['Name'] ?></td>

                            <td>
                              <?= $student['class'] ?>
                              <input
                                type="hidden"
                                id="hidden_class"
                                value="<?= $student['class'] ?>"
                              />
                            </td>
                            <td><?= $student['requested_by'] ?></td>

                            <td>
                              <?= date('d-M-y',
                              strtotime($student['requested_at'])) ?>
                            </td>
                            <input
                              type="hidden"
                              id="hidden_date"
                              value="<?= date('d-M-y', strtotime($student['requested_at'])) ?>"
                            />
                            <td>
                              <?php if ($student['status'] == 'rejected' ||
                              $student['status'] == 'requested') { ?>
                              <!-- Name mein student id lagaya taaki har row ka radio button alag rahe -->
                              <input
                                type="radio"
                                autocomplete="off"
                                onclick="updateLCData('<?= $student['student_id'] ?>','<?= $student['class'] ?>','<?= $student['academic_year'] ?>', 'approved')"
                                name="approval_<?= $student['student_id'] ?>"
                                value="approved"
                              />
                              Yes

                              <input
                                type="radio"
                                autocomplete="off"
                                onclick="updateLCData('<?= $student['student_id'] ?>','<?= $student['class'] ?>','<?= $student['academic_year'] ?>', 'rejected')"
                                name="approval_<?= $student['student_id'] ?>"
                                value="rejected"
                              />
                              No <?php } elseif ($student['status'] ==
                              'approved') { ?>
                              <a class="text-muted" href="javascript:void(0)"
                                >LC Approved</a
                              >
                              <?php } ?>
                            </td>

                            <!-- <input type="hidden" value="<?= $lc_no['lc_no']; ?>" id="hidden_lc_number"> -->
                          </tr>
                          <?php $i++; endforeach; ?>
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

    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>

    <script>
      function filter_data() {
        let to_date = document.getElementById("todate").value;
        let from_date = document.getElementById("fromdate").value;
        if (to_date !== "" && from_date !== "") {
          window.location.href = `<?= base_url() ?>Report/lc_isshu_report?to_date=${to_date}&from_date=${from_date}`;
        }
      }
    </script>

    <script>
      // Function name 'data' se badal ke 'updateLCData' kar diya
      function updateLCData(stud_id, stud_class, acdm_year, option_selected) {
        // option_selected mein already 'approved' ya 'rejected' aa raha hai onclick se

        $.ajax({
          url: '<?= base_url("Report/manage_lc_request") ?>',
          method: "POST",
          data: {
            stud_id: stud_id,
            stud_class: stud_class,
            status: option_selected, // Yahi 'approved' ya 'rejected' jayega
            acdm_year: acdm_year,
          },
          beforeSend: function () {
            // User ko pata chale ki processing ho rahi hai
            $('input[name="approval_' + stud_id + '"]').prop("disabled", true);
          },
          success: function (res) {
            if (option_selected == "approved") {
              alert("LC Approved Successfully!");
            } else {
              alert("LC Rejected!");
            }
            // Page reload hoga aur naya status show hoga
            window.location.reload();
          },
          error: function () {
            alert("Something went wrong!");
            $('input[name="approval_' + stud_id + '"]').prop("disabled", false);
          },
        });
      }
    </script>
	
  </body>
</html>
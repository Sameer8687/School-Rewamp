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
                  <h5 class="card-title">Balance Fees Report</h5>
                </div>
                <div class="card-body p-2">
                  <div class="print-container">
                    <div class="col-md-12">
                      <div class="row mb-3">
                        <div class="col-md-2">
                          <div class="row">
                            <label for="" class="col-md-5"
                              >Class <span class="float-right">:</span></label
                            >

                            <select
                              class="form-control form-control-sm col-md-7"
                              required
                              name="standard"
                              id="standard"
                            >
                              <option value="" selected disabled>
                                Select Class
                              </option>
                              <?php foreach ($classes as $class) { ?>
                              <option
                                value="<?php echo $class['stvfees_class']; ?>"
                              >
                                <?php echo $class['stvfees_class']; ?>
                              </option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-3">
                          <div class="row">
                            <label for="" class="col-md-5"
                              >Select Class
                              <span class="float-right">:</span></label
                            >

                            <select
                              class="form-control form-control-sm col-md-7"
                              required
                              name="status"
                              id="status"
                            >
                              <option value="" selected disabled>
                                Select Status
                              </option>
                              <option value="A">Active</option>
                              <option value="N">NO Show</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <button
                            type="button"
                            class="btn btn-sm btn-outline-secondary"
                            id="search"
                          >
                            Search
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
                            <tr>
                              <th class="text-capitalize">#</th>
                              <th class="text-capitalize">Student ID</th>
                              <th class="text-capitalize">Student Name</th>
                              <th class="text-capitalize">Class</th>
                              <th class="text-capitalize">Balance Fees</th>
                              <th class="text-capitalize">Status</th>
                            </tr>
                          </thead>

                          <tbody id="fees-tbody"></tbody>
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
      $("#search").click(function () {
        var std = $("#standard").val();
        var Status = $("#status").val();
        if (std != null) {
          $.ajax({
            url: "<?= base_url('index.php/Report/getStudentRecords'); ?>",
            method: "POST",
            data: {
              std: std,
              status: Status,
            },
            dataType: "JSON",
            success: function (data) {
              if (data != null) {
                var html = "";
                var next = 0;
                var total_final = 0;
                var color = "";
                for (var count = 0; count < data.length; count++) {
                  next = count + 1;
                  if (data[count].fees_paid == null) {
                    vbal = 0;
                  } else {
                    vbal = parseInt(data[count].fees_paid);
                  }
                  if (
                    data[count].STVCLAS_standard == "Nursery" ||
                    data[count].STVCLAS_standard == "Jr.Kg."
                  ) {
                    total_final = 7000 - vbal;
                  } else if (data[count].STVCLAS_standard == "Sr.Kg.") {
                    total_final = 8000 - vbal;
                  } else {
                    total_final = 10000 - vbal;
                  }

                  if (total_final == 10000) {
                    color = "#FF1700";
                  } else if (total_final > 1000 && total_final < 10000) {
                    color = "#297F87";
                  } else if (total_final < 1000) {
                    color = "#34BE82";
                  }

                  html += "<tr>";
                  html += "<td>" + next + "</td>";
                  html += "<td>" + data[count].SPRIDEN_student_id + "</td>";
                  html += "<td>" + data[count].name + "</td>";
                  html += "<td>" + data[count].class_name + "</td>";
                  html +=
                    '<td style="color: ' +
                    color +
                    '">' +
                    new Intl.NumberFormat().format(total_final) +
                    "</td>";
                  html += "<td>" + data[count].SPRIDEN_student_status + "</td>";
                  html += "</tr>";
                }

                $("#fees-tbody").html(html);
              } else {
                console.log("Data Not Available");
              }
            },
          });
        } else {
          alert("Select Standard");
        }
      });
    </script>
  </body>
</html>

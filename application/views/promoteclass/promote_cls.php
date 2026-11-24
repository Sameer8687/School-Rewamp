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

  <?php include 'application/views/sidebar.php'; ?>
  <?php include 'application/views/topNav.php'; ?>
  <div class="pc-container">
    <div class="pcoded-content">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Division Update</h5>
            </div>
            <div class="card-body p-2">
              <div class="print-container">
                <div class="col-md-12">
                  <div class="row mb-3">
                    <!-- 1 -->
                    <div class="col-md-4">
                      <div class="row">
                        <label for="" class="col-md-5">Class<span class="float-right">:</span></label>

                        <select required class="form-control form-control-sm col-md-7" name="curr_class"
                          id="curr_class">
                          <option selected disabled value=""> Select Standard </option>
                          <?php foreach ($classes as $class) { ?>
                            <option value="<?php echo $class['stvfees_class']; ?>">
                              <?php echo $class['stvfees_class']; ?>
                            </option>
                          <?php } ?>
                        </select>

                        <input type="hidden" name="max_rows" id="max_rows" value="0" />
                      </div>
                    </div>

                    <!-- 3 -->
                    <div class="col-md-4">
                      <button type="button" class="btn btn-sm btn-outline-secondary" id="view"
                        style="border-radius: 50px">View Class</button>

                      <button type="submit" class="btn btn-sm btn-outline-primary" id="update"
                        style="border-radius: 50px"> Update Class </button>
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="dt-responsive table-responsive margin-top-minus-10">
                    <table id="simpletable" data-custom="true" class="table table-striped table-bordered nowrap">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>ID</th>
                          <th>Student Name</th>
                          <th>Gender</th>
                          <th>Class</th>
                          <th>Division</th>
                          <th>Status</th>
                          <th>A/I</th>
                        </tr>
                      </thead>

                      <tbody id="data-body"></tbody>
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
    // my js script
    $(document).ready(function () {
      $("#view").on("click", function () {
        var std = document.getElementById("curr_class").value;
        $.ajax({
          url: "<?= base_url() ?>index.php/welcome/viewClass",
          type: "POST",
          dataType: "JSON",
          data: {
            std: std,
          },
          success: function (result) {
            displayData(result); // Call the function to display data
          },
        });
      });

      // Function to display data in the table
      function displayData(data) {

        if ($.fn.DataTable.isDataTable('#simpletable')) {
          $('#simpletable').DataTable().destroy();
        }

        var tableBody = $("#data-body");
        tableBody.empty();

        let i = 0;

        data.forEach((element) => {

          let status = element.spriden_student_status == "A" ? "checked" : "";

          let A = element.STVCLAS_class == "A" ? "selected" : "";
          let B = element.STVCLAS_class == "B" ? "selected" : "";
          let C = element.STVCLAS_class == "C" ? "selected" : "";
          let no_class = element.STVCLAS_class == "*" ? "selected" : "";
          let is_updated = element.updated_flag == "1" ? "alert-success" : "";

          tableBody.append(`
            <tr class="${is_updated}">
                <td>${++i}</td>
                <td>${element.id}</td>
                <td>${element.name}</td>
                <td>${element.SPRIDEN_gender}</td>
                <td>${element.STVCLAS_standard}</td>
                <td>
                    <select name="div${i}">
                        <option value="A" ${A}>A</option>
                        <option value="B" ${B}>B</option>
                        <option value="C" ${C}>C</option>
                        <option value="*" ${no_class}>*</option>
                    </select>
                </td>
                <td>
                    <input type="checkbox" id="status${i}" name="status${i}" value="${element.id}">
                </td>
                <td>
                    <input type="checkbox" id="active${i}" name="active${i}" value="A" ${status}>
                </td>

                <input type="hidden" name="update_id${i}" value="${element.id}">
            </tr>
        `);

          let sts = document.getElementById("status" + i);
          let act = document.getElementById("active" + i);

          sts.addEventListener("click", function () {
            act.checked = sts.checked;
          });
        });

        $("#max_rows").val(i);

        $('#simpletable').DataTable({
          destroy: true,
          responsive: true,
          pageLength: 25,
          ordering: true,
          searching: true,

          dom: 'Bfrtip',

          buttons: [
            {
              extend: 'copy',
              text: 'Copy'
            },
            {
              extend: 'excel',
              text: 'Excel',
              title: 'Division Update'
            },
            {
              extend: 'pdf',
              text: 'PDF',
              title: 'Division Update',
              orientation: 'landscape',
              pageSize: 'A4'
            },
            {
              extend: 'print',
              text: 'Print',
              title: 'Division Update'
            }
          ],

          lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
          ]
        });
      }
    });
  </script>

  <script>
    // my js script
    $("#update").on("click", function (e) {
      e.preventDefault();

      var formData = new FormData();

      formData.append("curr_class", $("#curr_class").val());
      formData.append("max_rows", $("#max_rows").val());

      var max_rows = $("#max_rows").val();

      for (var i = 1; i <= max_rows; i++) {

        formData.append("update_id" + i, $("input[name='update_id" + i + "']").val());
        formData.append("div" + i, $("select[name='div" + i + "']").val());

        if ($("#status" + i).is(":checked")) {
          formData.append("status" + i, "on");
        }

        if ($("#active" + i).is(":checked")) {
          formData.append("active" + i, "A");
        }
      }

      $.ajax({
        url: "<?= base_url() ?>index.php/welcome/updateclass",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
          alert("Update Successful");
          console.log(res);
          location.reload();
        },
        error: function (xhr) {
          console.log(xhr.responseText);
        }
      });
    });
  </script>
</body>

</html>
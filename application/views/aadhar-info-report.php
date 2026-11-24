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
                <h5 class="card-title">Student Aadhar Info Report</h5>
              </div>
              <div class="card-body p-2">
                <div class="print-container">
                  <div class="col-md-12">
                    <div class="row mb-3">

						<!-- 1 -->
                      	<div class="col-md-4">
                      		<div class="row">
                      		  <label for="" class="col-md-5">Class <span class="float-right">:</span></label>
							  
							  <select class="form-control form-control-sm col-md-7" name="std_filter_" id="marks_class">
                        <option value="">Select</option>
                        <?php foreach ($classes as $class): ?>
                            <option <?= $current_class == $class['stvfees_class'] ? 'selected' : '' ?>
                                value="<?= $class['stvfees_class'] ?>"><?= $class['stvfees_class'] ?></option>
                        <?php endforeach; ?>

                    </select>

                      		</div>
                      	</div>

						<!-- 2 -->
                      	<div class="col-md-4">
                      		<div class="row">
                      		  <label for="" class="col-md-5">Division <span class="float-right">:</span></label>

							  <select class="form-control form-control-sm col-md-7" name="div_filter_" id="div_filter_">
                        <option value="">Select</option>
                        <option <?= $current_div == 'A' ? 'selected' : '' ?> value="A">A</option>
                        <option <?= $current_div == 'B' ? 'selected' : '' ?> value="B">B</option>
                        <option <?= $current_div == 'C' ? 'selected' : '' ?> value="C">C</option>
                    </select>

                      		</div>
                      	</div>

                      <!-- 3 -->
                      	<div class="col-md-4">
						
                        	<button id="find-students" type="button" class="btn btn-sm btn-outline-secondary" onclick="filter_data()">View </button>

                      	</div>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div
                      class="dt-responsive table-responsive margin-top-minus-10">
                      <table id="simpletable" data-custom="true" class="table table-striped table-bordered nowrap">
                        <thead>
                          <tr>
                                        <th>#</th>
                                        <th>Student Id</th>
                                        <th>Standerd</th>
                                        <th>Division</th>
                                        <th>Student Name</th>
                                        <th>Student Aadhar</th>
                                        <th>Student Dob</th>
                                        <th>Father name</th>
                                        <th>Father Aadhar</th>
                                        <th>Father Dob</th>
                                        <th>Mother Name</th>
                                        <th>Mother Aadhar</th>
                                        <th>Mother Dob</th>
                                        <th>Whatsapp Mobile</th>
                                    </tr>
                        </thead>

                        <tbody id="tbody">
                          <?php if (!empty($getnote)) {
                                        $index = 1;
                                        foreach ($getnote as $note): ?>
                                            <tr>
                                                <td><?= $index++ ?></td>
                                                <td><?= $note['SPRIDEN_student_id'] ?? '' ?></td>
                                                <td><?= $note['STVCLAS_standard'] ?? '' ?></td>
                                                <td><?= $note['STVCLAS_class'] ?? '' ?></td>
                                                <td><?= $note['student_name'] ?? '' ?></td>
                                                <td><?= $note['student_aadhar_number'] ?? '' ?></td>
                                                <td><?= $note['student_dob'] ?? '' ?></td>
                                                <td><?= $note['father_name'] ?? '' ?></td>
                                                <td><?= $note['father_aadhar_number'] ?? '' ?></td>
                                                <td><?= $note['father_dob'] ?? '' ?></td>
                                                <td><?= $note['mother_name'] ?? '' ?></td>
                                                <td><?= $note['mother_aadhar_number'] ?? '' ?></td>
                                                <td><?= $note['mother_dob'] ?? '' ?></td>
                                                <td><?= $note['fat_mob_no'] ?? '' ?></td>
                                            </tr>
                                        <?php endforeach;
                                    } else { ?>
                                        <tr>
                                            <td colspan="14" class="text-center">No data found.</td>
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
        </div>
      </div>
    </div>

    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>

	<script>
    function filter_data() {
        const std = $('#marks_class').val();
        const div = $('#div_filter_').val();

        if (!std || !div) {
            alert("Please select all the filters.");
            return;
        }

        // Simply redirect to the same page with GET parameters
        window.location.href = "<?= base_url('Welcome/get_aadhar_data') ?>?std=" + std + "&div=" + div;
    }
</script>
  </body>
</html>
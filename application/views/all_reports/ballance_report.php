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
                <h5 class="card-title">Balance Report</h5>
              </div>
              <div class="card-body p-2">
                <div class="print-container">
                  <div class="col-md-12">
                    <div class="row mb-3">

						<!-- 1 -->
                      	<div class="col-md-4">
                      		<div class="row">
                      		  <label for="" class="col-md-5">Academic Year <span class="float-right">:</span></label>

							  <select name="class" class="form-control form-control-sm col-md-7"
                                            id="class">
                                            <option value="">Select</option>
                                            <option <?= $vls == 'All' ? 'selected' : '' ?> value="All">All</option>
                                            <option <?= $vls == 'Readmission' ? 'selected' : '' ?> value="Readmission">
                                                Readmission</option>
                                        </select>

                      		</div>
                      	</div>

                    </div>
                  </div>

                  <div class="col-md-12">
                    <div
                      class="dt-responsive table-responsive margin-top-minus-10">
                      <table id="simpletable" data-custom="true" class="table table-striped table-bordered nowrap">
                        <thead>
                          <tr>
                                                        <th class="text-capitalize">#</th>
                                                        <th class="text-capitalize">ID</th>
                                                        <th class="text-capitalize">Student ID</th>
                                                        <th class="text-capitalize">Student Name</th>
                                                        <th class="text-capitalize">Class</th>
                                                        <th class="text-capitalize">Total Fees</th>
                                                        <th class="text-capitalize">Total Paid</th>
                                                        <th class="text-capitalize">Total Exumpted</th>
                                                        <th class="text-capitalize">Balance</th>
                                                    </tr>
                        </thead>

                        <tbody id="tbody">
                          <?php $i = 1;
                                                    foreach ($all_records as $record) { ?>
                                                    <tr>
                                                        <td><?= $i; ?></td>
                                                        <td><?= $record['ID'] ?></td>
                                                        <td><?= $record['SPRIDEN_student_id'] ?></td>
                                                        <td><?= $record['name'] ?></td>
                                                        <td><?= $record['STVCLAS_standard'] ?></td>
                                                        <td><?= $record['class_fees'] ?></td>
                                                        <td><?= $record['total_paid'] ?></td>
                                                        <td><?= $record['total_exumpted'] ?></td>
                                                        <td><?= $record['bal'] ?></td>
                                                        <!-- <td><?= $record['stvradm_date'] == '' || $record['stvradm_date'] == null ? '' : date('d-M-Y', strtotime($record['stvradm_date'])) ?></td> -->
                                                    </tr>
                                                    <?php $i++;
                                                    } ?>
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
    let class_name = document.getElementById('class');
    let base_url = '<?= base_url() ?>';

    class_name.addEventListener('change', function() {
        var vls = class_name.value;
        window.location = `${base_url}report/balance_rep?vls=${vls}`;
    })
    </script>
  </body>
</html>
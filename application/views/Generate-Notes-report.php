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
                <h5 class="card-title">Generate Notes Report</h5>
              </div>
              <div class="card-body p-2">
                <div class="print-container">
                  <div class="col-md-12">
                    <div class="row mb-3">

						<!-- 1 -->
                      	<div class="col-md-3">
                      		<div class="row">
                      		  <label for="" class="col-md-5">Academic Year <span class="float-right">:</span></label>
							  
							  <select class="form-control form-control-sm col-md-7" name="cur_acdm" id="cur_acdm">
                        <option value="">Select</option>
                        <?php foreach ($academic_years as $academic_year): ?>
                            <option <?= $current_acdm == $academic_year['stvacdm_id'] ? 'selected' : '' ?>
                                value="<?= $academic_year['stvacdm_id']; ?>"><?= $academic_year['stvacdm_year']; ?></option>
                        <?php endforeach; ?>
                    </select>

                      		</div>
                      	</div>

						<!-- 2 -->
                      	<div class="col-md-2">
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

						<div class="col-md-2">
                      		<div class="row">
                      		  <label for="" class="col-md-5">Div <span class="float-right">:</span></label>

							  <select class="form-control form-control-sm col-md-7" name="div_filter_" id="div_filter_">
                        <option value="">Select</option>
                        <option <?= $current_div == 'A' ? 'selected' : '' ?> value="A">A</option>
                        <option <?= $current_div == 'B' ? 'selected' : '' ?> value="B">B</option>
                        <option <?= $current_div == 'C' ? 'selected' : '' ?> value="C">C</option>
                    </select>

                      		</div>
                      	</div>

						<div class="col-md-2">
                      		<div class="row">
                      		  <label for="" class="col-md-5">Subject <span class="float-right">:</span></label>

							  <select class="form-control form-control-sm col-md-7" name="subject" id="marks_subject">
                        <option value="">Select</option>
                    </select>
                      		</div>
                      	</div>

                      <!-- 3 -->
                      	<div class="col-md-2">
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
                                    <th>Title</th>
                                    <th>DocType</th>
                                    <th>Document</th>
                                    <th>Action</th>
                                </tr>
                        </thead>

                        <tbody id="tbody">
                          <?php
                                $index = 1; // Initialize index for numbering rows
                                foreach ($getnote as $note): ?>
                                    <tr id="row_<?= $note['id'] ?>">
                                        <td><?= $index++ ?></td>
                                        <td><?= $note['doc_title'] ?></td>
                                        <td><?= $note['doc_type'] ?></td>
                                        <td>
                                            <?php
                                            // Dynamic URL generation in PHP
                                            $file_name = $note['attachment'];
                                            $url = "/documents/" . $file_name;
                                            ?>
                                            <a href="<?= base_url($url) ?>" target="_blank">View Document</a>
                                        <td>
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="deleteNote(<?= $note['id'] ?>)">Delete</button>
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
      </div>
    </div>

	<!-- Modal -->
	 <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this note?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmDelete" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>

	<script>
        // fetch subject on class
        $('#marks_class').change(function () {
            let std = $('#marks_class').val();
            if (!std) {
                alert("Please select a class first.");
                return;
            }

            $('#marks_subject').html('<option>Loading...</option>');
            $.ajax({
                url: "<?php echo base_url(); ?>Welcome/get_subject",
                data: { std: std },
                method: "POST",
                dataType: "JSON",
                success: function (data) {
                    if (data.subjects.length > 0) {
                        let options = '<option value="" selected disabled>Select Subject</option>';
                        data.subjects.forEach(subject => {
                            options += `<option value="${subject.stvsubj_id}">${subject.stvsubj_subject}</option>`;
                        });
                        $('#marks_subject').html(options);
                    } else {
                        $('#marks_subject').html('<option value="" selected disabled>No subjects available</option>');
                    }
                },
                error: function () {
                    alert("An error occurred while fetching subjects.");
                    $('#marks_subject').html('<option value="" selected disabled>Error loading subjects</option>');
                }
            });
        });

        // delete data 
        let noteToDelete = null;
        function deleteNote(noteId) {
            noteToDelete = noteId;
            $('#deleteModal').modal('show');
        }

        $('#confirmDelete').click(function () {
            if (!noteToDelete) return;

            $.ajax({
                url: "<?php echo base_url(); ?>Welcome/delete_note",
                method: "POST",
                data: { id: noteToDelete },
                dataType: "JSON",
                success: function (response) {
                    if (response.success) {
                        $(`#row_${noteToDelete}`).remove();
                        alert("Note deleted successfully.");
                    } else {
                        alert("Failed to delete the note.");
                    }
                    $('#deleteModal').modal('hide');
                    noteToDelete = null;
                },
                error: function () {
                    alert("An error occurred. Please try again.");
                    $('#deleteModal').modal('hide');
                    noteToDelete = null;
                }
            });
        });

        function filter_data() {
            const cur_acdm = $('#cur_acdm').val();
            const std = $('#marks_class').val();
            const div = $('#div_filter_').val();
            const sub = $('#marks_subject').val();
            const baseUrl = "<?= base_url() ?>"
            if (!cur_acdm || !std || !div || !sub) {
                alert("Please select all the filters.");
                return;
            }

            $.ajax({
                url: "<?php echo base_url(); ?>Welcome/filter_data",
                method: "POST",
                data: { cur_acdm, std, div, sub },
                dataType: "JSON",
                beforeSend: function () {
                    $('#notes_table').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
                },
                success: function (response) {
                    if (Array.isArray(response) && response.length > 0) {
                        let rows = response.map((note, index) => {
                            const fileName = note.attachment; // Assuming `note.attachment` is available
                            const url = "/documents/" + fileName; // Base URL for documents
                            const base = baseUrl + url; // Base URL for documents
                            return `
                                    <tr id="row_${note.id}">
                                        <td>${index + 1}</td>
                                        <td>${note.doc_title}</td>
                                        <td>${note.doc_type}</td>
                                        <td>
                                            <a href="${base}" target="_blank">View Document</a>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteNote(${note.id})">Delete</button>
                                        </td>
                                    </tr>
                                    `;
                        }).join('');
                        // Append rows to the table body with ID "notes_table"
                        $('#notes_table').html(rows);
                    } else {
                        $('#notes_table').html('<tr><td colspan="5" class="text-center">No data found.</td></tr>');
                    }
                },
                error: function () {
                    alert("Failed to fetch data. Please try again.");
                    $('#notes_table').html('<tr><td colspan="5" class="text-center">Error loading data.</td></tr>');
                }
            });
        }
    </script>
  </body>
</html>
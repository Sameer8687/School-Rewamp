<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

	<title>Olive English School - Student Fees Form</title>

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<meta name="description" content="" />

	<meta name="keywords" content="">

	<meta name="author" content="Phoenixcoded" />



	<!---Include CSS Files-->

	<?php include 'application/views/cssPages.php'; ?>



</head>

<style>
	.pc-container {
		top: 0px !important;
	}

	/* Teacher combobox – looks identical to the old form-control-sm select */
	.teacher-combo-wrap {
		position: relative;
		padding: 0;
	}

	.teacher-combo-wrap .teacher-input {
		background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E");
		background-repeat: no-repeat;
		background-position: right .5rem center;
		background-size: 8px 10px;
		padding-right: 1.5rem;
		cursor: text;
	}

	.teacher-dropdown {
		position: absolute;
		top: 100%;
		left: 0;
		right: 0;
		margin-top: 2px;
		background: #fff;
		border: 1px solid #ced4da;
		border-radius: 4px;
		max-height: 220px;
		overflow-y: auto;
		z-index: 9999;
		box-shadow: 0 3px 8px rgba(0, 0, 0, .12);
		font-size: 12px;
		display: none;
	}

	.teacher-dropdown .teacher-option {
		padding: 5px 8px;
		cursor: pointer;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.teacher-dropdown .teacher-option:hover,
	.teacher-dropdown .teacher-option.active {
		background: #f1f1f1;
	}

	.teacher-dropdown .teacher-empty {
		padding: 5px 8px;
		color: #999;
		font-style: italic;
	}
</style>

<body class="">

	<!-- [ Pre-loader ] start -->

	<div class="loader-bg">

		<div class="loader-track">

			<div class="loader-fill"></div>

		</div>

	</div>

	<!-- [ Pre-loader ] End -->



	<!--Include Sidebar -->

	<?php include 'application/views/sidebar.php'; ?>



	<!--Include Top Navbar-->

	<?php include 'application/views/topNav.php'; ?>



	<!-- [ Main Content ] start -->

	<div class="pc-container">

		<form action="<?php echo base_url(); ?>MasterForm/assign_teacher" method="post">

			<div class="pcoded-content">

				<div class="row" id="alertRow">

					<div class="col-md-12">

						<?php

						$message = $this->session->userdata('message');

						$alert_class = $this->session->userdata('alert_class');

						if ($message != "") {

							?>

							<div class="alert <?= $alert_class; ?>">

								<?= $message; ?>

							</div>

						<?php } ?>

					</div>

				</div>

				<div class="row">

					<div class="col-md-12">

						<div class="card pb-0 mb-0">

							<div class="card-header mb-0 p-2 ">
								<div class="row w-100">
									<div class="col-md-6">
										<h5 class="pt-2">Assign Teacher</h5>
									</div>
									<div class="col-md-6"><button class="btn btn-secondary btn-sm float-right"> <i
												class="fas fa-user-tie"></i> <span id="dynamicLabelAddButton">Assign
												Teacher</span></button></div>
								</div>



							</div>

							<div class="card-body pb-0 mb-0">

								<style>
									label {

										text-align: right;

									}

									.no-margin {

										margin-top: -10px;

									}
								</style>

								<div class="row margin-top-minus-20">

									<!-- Teacher (type-to-filter combobox, looks like the old select) -->
									<div class="col-md-3">
										<div class="form-group row">
											<label class="col-md-5">Teacher<span class="text-danger">*</span> :</label>

											<div class="col-md-7 teacher-combo-wrap">
												<input type="text" class="form-control form-control-sm teacher-input"
													id="teacher_input" placeholder="Select Teacher Name"
													autocomplete="off" required>
												<!-- actual value submitted with the form -->
												<input type="hidden" name="teacher" id="teacher" value="">

												<div class="teacher-dropdown" id="teacher_dropdown">
													<?php foreach ($teachers as $teacher) {
														$tname = $teacher['SPRIDEN_surname'] . " " . $teacher['SPRIDEN_student_name'] . " " . $teacher['SPRIDEN_father_name'];
														?>
														<div class="teacher-option"
															data-value="<?= $teacher['SPRIDEN_student_id']; ?>"
															data-text="<?= htmlspecialchars($tname, ENT_QUOTES); ?>">
															<?= $tname; ?>
														</div>
													<?php } ?>
												</div>
											</div>

											<input type="hidden" name="hidden_header_id" id="hidden_header_id"
												value="0">
										</div>
									</div>

									<!-- Standard -->
									<div class="col-md-3">
										<div class="form-group row">
											<label class="col-md-5">Standard<span class="text-danger">*</span> :</label>
											<select class="form-control form-control-sm col-md-7" name="class"
												id="class" required>
												<option selected disabled value="">Select Class</option>
												<?php foreach ($classes as $class) { ?>
													<option value="<?= $class['stvfees_class']; ?>">
														<?= $class['stvfees_class']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
									</div>

									<!-- Division -->
									<div class="col-md-3">
										<div class="form-group row">
											<label class="col-md-5">Division :</label>
											<select class="form-control form-control-sm col-md-7" name="division"
												id="division">
												<option selected disabled value="">Select Division</option>
												<option value="A">A</option>
												<option value="B">B</option>
												<option value="C">C</option>
											</select>
										</div>
									</div>

									<!-- Academic Year (inline, part of the form) -->
									<div class="col-md-3">
										<div class="form-group row">
											<label class="col-md-5">Year<span class="text-danger">*</span>
												:</label>
											<select class="form-control form-control-sm col-md-7" name="academic_year"
												id="academic_year" required onchange="filterByAcademicYear(this.value)">
												<option value="">Select Academic Year</option>
												<?php foreach ($academic_years as $ay) {
													$selected = ($selected_academic_year == $ay['stvacdm_id']) ? 'selected' : '';
													?>
													<option <?= $selected ?> value="<?= $ay['stvacdm_id']; ?>">
														<?= $ay['stvacdm_year']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
									</div>

								</div>

							</div>

							<!-- [ Search List ] Start -->

							<div class="search-list-container-panel">

								<div class="list-group" id="dynamic_name_list"></div>

							</div>

							<!-- [ Search List ] End -->

						</div>

					</div>

				</div>



				<div class="row mt-1">

					<div class="col-md-12">

						<div class="card">

							<div class="card-body">

								<div class="row">

									<div class="col-md-12">

										<div class="dt-responsive table-responsive margin-top-minus-10">

											<table id="simpletable" class="table table-striped table-bordered nowrap">

												<thead>

													<tr>

														<th>Sr.No.</th>
														<th>ID</th>

														<th>Teacher Name</th>

														<th>Standard</th>

														<th>Division</th>

														<th>Date</th>

														<th>Action</th>

													</tr>

												</thead>

												<style>
													#simpletable tbody tr td {

														padding: 5px;

														font-size: 12px;

														font-weight: 500;

													}
												</style>

												<tbody>

													<?php

													$i = 1;

													foreach ($assigned_teachers as $assigned_teacher) {

														$servDate = $assigned_teacher['SPATESD_assigned_date'];

														$timestamp = strtotime($servDate);

														$new_date = date("d M Y", $timestamp);

														?>

														<tr style="cursor: pointer;">

															<td class="update-data"
																data-id="<?= $assigned_teacher['SPATESD_id']; ?>"><?= $i; ?>
															</td>
															<td class="update-data"
																data-id="<?= $assigned_teacher['SPATESD_id']; ?>">
																<?= $assigned_teacher['SPRIDEN_student_id']; ?>
															</td>

															<td class="update-data"
																data-id="<?= $assigned_teacher['SPATESD_id']; ?>">
																<?= $assigned_teacher['SPRIDEN_surname'] . " " . $assigned_teacher['SPRIDEN_student_name'] . " " . $assigned_teacher['SPRIDEN_father_name']; ?>
															</td>

															<td class="update-data"
																data-id="<?= $assigned_teacher['SPATESD_id']; ?>">
																<?= $assigned_teacher['SPATESD_std']; ?>
															</td>

															<td class="update-data"
																data-id="<?= $assigned_teacher['SPATESD_id']; ?>">
																<?= $assigned_teacher['SPATESD_div']; ?>
															</td>

															<td class="update-data"
																data-id="<?= $assigned_teacher['SPATESD_id']; ?>">
																<?= $new_date; ?>
															</td>

															<td class="delete-teacher text-danger"
																data-id="<?= $assigned_teacher['SPATESD_id']; ?>">

																<i class="fa fa-trash" aria-hidden="true"></i> Delete

															</td>



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

		</form>

		<!-- [ Main Content ] end -->

	</div>







	</div>

	<!-- [ Main Content ] end -->

	</div>











	<!---Include JS Files-->

	<?php include 'application/views/jsPages.php'; ?>

	<script>

		$(document).ready(function () {

			var $input = $('#teacher_input');
			var $hidden = $('#teacher');
			var $dd = $('#teacher_dropdown');
			var $allOpts = $dd.find('.teacher-option');
			var $wrap = $input.closest('.teacher-combo-wrap');

			// filter dropdown options by LIKE-style match (anywhere, case-insensitive)
			function filterOptions(term) {
				term = (term || '').toLowerCase();
				var visible = 0;

				$allOpts.each(function () {
					var txt = ($(this).data('text') || '').toLowerCase();
					if (term === '' || txt.indexOf(term) > -1) {
						$(this).show();
						visible++;
					} else {
						$(this).hide();
					}
				});

				$dd.find('.teacher-empty').remove();
				if (visible === 0) {
					$dd.append('<div class="teacher-empty">No matching teacher</div>');
				}
			}

			function openDropdown() {
				filterOptions($input.val());
				$dd.show();
			}

			function closeDropdown() {
				$dd.hide();
				$dd.find('.teacher-option').removeClass('active');
			}

			// Open on focus / click
			$input.on('focus click', function () {
				openDropdown();
			});

			// Live filter as user types
			$input.on('input', function () {
				// clear previous selection while typing
				$hidden.val('');
				filterOptions($input.val());
				$dd.show();
			});

			// Pick an option
			$dd.on('mousedown', '.teacher-option', function (e) {
				// mousedown avoids blur closing before click
				e.preventDefault();
				var val = $(this).data('value');
				var text = $(this).data('text');

				$hidden.val(val);
				$input.val(text);
				closeDropdown();
			});

			// Keyboard navigation
			$input.on('keydown', function (e) {
				var $vis = $dd.find('.teacher-option:visible');
				var idx = $vis.index($dd.find('.teacher-option.active'));

				if (e.key === 'ArrowDown') {
					e.preventDefault();
					if (!$dd.is(':visible')) { openDropdown(); return; }
					idx = (idx + 1) >= $vis.length ? 0 : idx + 1;
					$vis.removeClass('active').eq(idx).addClass('active')
					[0].scrollIntoView({ block: 'nearest' });
				} else if (e.key === 'ArrowUp') {
					e.preventDefault();
					idx = (idx - 1) < 0 ? $vis.length - 1 : idx - 1;
					$vis.removeClass('active').eq(idx).addClass('active')
					[0].scrollIntoView({ block: 'nearest' });
				} else if (e.key === 'Enter') {
					if ($dd.is(':visible') && $dd.find('.teacher-option.active').length) {
						e.preventDefault();
						$dd.find('.teacher-option.active').trigger('mousedown');
					}
				} else if (e.key === 'Escape') {
					closeDropdown();
				}
			});

			// Close when clicking outside
			$(document).on('mousedown', function (e) {
				if (!$(e.target).closest($wrap).length) {
					closeDropdown();
				}
			});

			// Helper used by the edit-row handler
			window.setTeacherValue = function (id) {
				var $opt = $allOpts.filter('[data-value="' + id + '"]');
				if ($opt.length) {
					$input.val($opt.data('text'));
					$hidden.val(id);
				} else {
					$input.val('');
					$hidden.val('');
				}
			};
		});

		$(document).on('click', '.update-data', function () {

			var ids = $(this).attr("data-id");

			$.ajax({

				url: "<?= base_url('index.php/MasterForm/tecaherClassDetails') ?>?header-id=" + ids,

				method: "GET",

				dataType: "JSON",

				success: function (data) {
					if (data != null) {
						$('#hidden_header_id').val(data.SPATESD_id);
						$('#division').val(data.SPATESD_div);
						$('#class').val(data.SPATESD_std);

						if (typeof window.setTeacherValue === 'function') {
							window.setTeacherValue(data.SPATESD_teacher_id);
						}
					}
				}

			});

		})

		$(document).on('click', '.delete-teacher', function () {

			var ids = $(this).attr("data-id");

			if (confirm("Do you want to delete the record?")) {

				$.ajax({

					url: "<?= base_url('index.php/MasterForm/deleteTeacherRecord') ?>?header-id=" + ids,

					method: "GET",

					success: function (data) {

						if (data != "fail") {

							alert("Data Deleted Successfully");

							window.location.reload();

						}

					}

				});

			}

		})


		function filterByAcademicYear(yearId) {
			if (yearId) {
				window.location.href = "<?= base_url('assign-teacher') ?>?acdm=" + yearId;
			} else {
				window.location.href = "<?= base_url('assign-teacher') ?>";
			}
		}



	</script>





</body>



</html>
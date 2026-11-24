<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

	<title>Olive English School - Subject Teacher Master</title>

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<meta name="description" content="" />

	<meta name="keywords" content="">

	<meta name="author" content="Phoenixcoded" />



	<!---Include CSS Files-->

	<?php include 'application/views/cssPages.php'; ?>

	<!-- Boxicons CSS -->

	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>



</head>



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

		<form action="<?php echo base_url(); ?>Teacher/assign_subject_teacher" method="post">

			<div class="pcoded-content">

				<!-- [ breadcrumb ] start -->

				<!-- [ breadcrumb ] end -->

				<!-- [ Main Content ] start -->



				<div class="row margin-top-minus-40 mb-2">

					<div class="col-md-7">

						<!-- <a href="<?php echo base_url(); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a> -->

						<!-- <button class="btn btn-secondary btn-sm"> <i class="fas fa-bars"></i> <span id="dynamicLabelAddButton">Add Data</span></button> -->



					</div>

					<!-- <div class="col-md-5 student-form-search">

                        <input type="text" class="form-control form-control-sm" id="search_student_name" placeholder="Search Student Name">

                        <span class="left-icon"><i class="fas fa-search"></i></span>

                        <span class="right-icon" onclick="clearFormInputIcon()"><i class="fas fa-times"></i></span>

                    </div> -->

				</div>

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

							<div class="card-header mb-0 pb-2 ">
								<div class="row w-100">
									<div class="col-md-6">
										<h5 class="pt-2">Assign Subject Teacher</h5>
									</div>
									<div class="col-md-6"><button class="btn btn-secondary btn-sm float-right"><i class="fas fa-bars"></i> <span id="dynamicLabelAddButton"> Add Data</span></button>
									</div>
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

									<div class="col-md-4">

										<div class="form-group row">

											<label for="" class="col-md-5">Teacher<span class="text-danger">*</span> :</label>

											<select class="form-control form-control-sm col-md-7" name="teacher" id="teacher" required>

												<option selected disabled value="">Select Teacher Name</option>

												<?php

												foreach ($teachers as $teacher) {

												?>

													<option value="<?php echo $teacher['SPRIDEN_student_id']; ?>"><?php echo $teacher['SPRIDEN_surname'] . " " . $teacher['SPRIDEN_student_name'] . " " . $teacher['SPRIDEN_father_name']; ?></option>

												<?php } ?>



											</select>

											<input type="hidden" class="form-control form-control-sm col-md-7" name="assigned_id" id="school_id">

										</div>

									</div>

									<div class="col-md-4">

										<div class="form-group row">

											<label for="" class="col-md-5">Standard<span class="text-danger">*</span> :</label>

											<select class="form-control form-control-sm col-md-7" name="class" id="class" required>

												<option selected disabled value="">Select Class</option>

												<?php

												foreach ($classes as $class) {

												?>

													<option value="<?php echo $class['stvfees_class']; ?>"><?php echo $class['stvfees_class']; ?></option>

												<?php } ?>



											</select>

										</div>

									</div>

									<div class="col-md-4">

										<div class="form-group row">

											<label for="" class="col-md-5">Division :</label>

											<select class="form-control form-control-sm col-md-7" name="division" id="division">

												<option selected disabled value="">Select Division</option>

												<option value="A">A</option>

												<option value="B">B</option>

												<option value="C">C</option>

											</select>

										</div>

									</div>
								</div>

								<div class="row">
									<div class="col-md-4 margin-top-minus-10">

										<div class="form-group row">

											<label for="" class="col-md-5">Subject :</label>

											<select class="form-control form-control-sm col-md-7" name="subject_code" id="subject_code">

												<option selected disabled value="">Select</option>



											</select>

										</div>

									</div>
									<div class="col-md-5 margin-top-minus-10">

										<div class="form-group row ">

											<label for="" class="col-md-5 d-flex">Academic Year<span class="text-danger">*</span> :</label>

											<select class="form-control form-control-sm col-md-7 d-flex" name="acdm_year" id="acdm_year" required>

												<option value="">Select Academic Year</option>

												<?php foreach ($academic_years as $academic_year) {

													if ($selected_academic_year == $academic_year['stvacdm_id']) {

														$selected = 'selected';
													} else {

														$selected = '';
													}

												?>

													<option <?= $selected; ?> value="<?= $academic_year['stvacdm_id']; ?>"><?= $academic_year['stvacdm_year']; ?></option>

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

														<th>Teacher Name</th>

														<th>Standard</th>

														<th>Division</th>

														<th>Subject</th>

														<th>Academic Year</th>

														<th></th>

													</tr>

												</thead>

												<style>
													#simpletable tbody tr td {

														padding: 5px;

														font-size: 12px;

														font-weight: 500;

													}



													#simpletable tbody tr td:hover {

														cursor: pointer;

													}
												</style>

												<tbody>

													<?php $i = 1;

													foreach ($subject_teachers as $subject_teacher) { ?>

														<tr>

															<td data-name="<?= $subject_teacher['stvstcr_id']; ?>" class="rowToUpdateData"><?= $i; ?></td>

															<td data-name="<?= $subject_teacher['stvstcr_id']; ?>" class="rowToUpdateData"><?= $subject_teacher['SPRIDEN_surname'] . " " . $subject_teacher['SPRIDEN_student_name'] . " " . $subject_teacher['SPRIDEN_father_name']; ?></td>

															<td data-name="<?= $subject_teacher['stvstcr_id']; ?>" class="rowToUpdateData"><?= $subject_teacher['stvstcr_std']; ?></td>

															<td data-name="<?= $subject_teacher['stvstcr_id']; ?>" class="rowToUpdateData"><?= $subject_teacher['stvstcr_div']; ?></td>

															<td data-name="<?= $subject_teacher['stvstcr_id']; ?>" class="rowToUpdateData"><?= $subject_teacher['stvsubj_subject']; ?></td>

															<td data-name="<?= $subject_teacher['stvstcr_acdm']; ?>" class="rowToUpdateData"><?= $subject_teacher['acdm_year']; ?></td>

															<td id="td-<?= $subject_teacher['stvstcr_id']; ?>" data-id="<?= $subject_teacher['stvstcr_id']; ?>" class="delte-data text-center text-danger"><i class="fas fa-trash"></i></td>

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
		$('#class').change(function() {

			var class_name = $('#class').val();

			$.ajax({

				url: "<?= base_url(); ?>index.php/Teacher/getSubjectByStd",

				method: "POST",

				dataType: "JSON",

				data: {

					class_name: class_name

				},

				success: function(data) {

					if (data != "fail") {

						var html = '<option selected disabled value="">Select Subject</option>';

						for (var count = 0; count < data.length; count++) {

							html += '<option value="' + data[count].stvsubj_id + '">' + data[count].stvsubj_subject + '</option>';

						}

						$('#subject_code').html(html);

					}

				}

			});

		});
	</script>

	<script>
		$('.rowToUpdateData').on('click', function() {

			var rowID = $(this).attr('data-name');

			$.ajax({

				url: "<?= base_url(); ?>index.php/Teacher/getSubjectTeacherDataByID",

				method: "POST",

				dataType: "JSON",

				data: {

					id: rowID

				},

				success: function(data) {

					if (data['stvstcr'] != "fail") {

						$('#school_id').val(rowID);

						$('#teacher').val(data['stvstcr'].stvstcr_teacher_id);

						$('#class').val(data['stvstcr'].stvstcr_std);

						$('#division').val(data['stvstcr'].stvstcr_div);

						$('#acdm_year').val(data['stvsubj'].stvsubj_acdm);



						$('#dynamicLabelAddButton').html("Update Data")

						var html = '';

						for (var count = 0; count < data['subj'].length; count++) {

							html += '<option value="' + data['subj'][count].stvsubj_id + '">' + data['subj'][count].stvsubj_subject + '</option>';

						}

						$('#subject_code').html(html);

						$('#subject_code').val(data['stvstcr'].stvstcr_subject);

					}

				}

			});

		});



		$(document).on('click', '.delte-data', function() {

			var ids = $(this).attr("data-id");

			if (confirm("Do you want to delete data?")) {

				$.ajax({

					url: "<?= base_url('index.php/Teacher/delete_data') ?>?header-id=" + ids,

					method: "GET",

					beforeSend: function() {

						$('#td-' + ids).html("<i class='bx bx-loader bx-spin'></i>");

					},

					success: function(data) {

						if (data != "fail") {

							alert("Record has been deleted");

							window.location.reload();

						}

					}

				})

			}

		});
	</script>

</body>



</html>
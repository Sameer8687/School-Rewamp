<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

	<title>Olive English School - Teacher Registration Form</title>

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<meta name="description" content="" />

	<meta name="keywords" content="">

	<meta name="author" content="Phoenixcoded" />



	<!---Include CSS Files-->

	<?php include 'application/views/cssPages.php'; ?>



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

		<form action="<?php echo base_url(); ?>Teacher/register_teacher" enctype="multipart/form-data" method="post">

			<div class="pcoded-content">

				<!-- [ breadcrumb ] start -->

				<!-- [ breadcrumb ] end -->

				<!-- [ Main Content ] start -->

				<div class="row margin-top-minus-50 mobile-margin-top-minus-10 mb-2">

					<div class="col-md-7">

						<button class="btn btn-secondary btn-sm"> Add Remark</button>

					</div>

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

				<style>

					#add-table tr td {

						padding: 2px !important;

						border: none;

					}



					/* Extra small devices (phones, 600px and down) */

					@media only screen and (max-width: 600px) {}



					/* Small devices (portrait tablets and large phones, 600px and up) */

					@media only screen and (min-width: 600px) {}



					/* Medium devices (landscape tablets, 768px and up) */

					@media only screen and (min-width: 768px) {}



					/* Large devices (laptops/desktops, 992px and up) */

					@media only screen and (min-width: 992px) {}



					/* Extra large devices (large laptops and desktops, 1200px and up) */

					@media only screen and (min-width: 1200px) {

						.select-list-width {

							width: 30%;

						}

					}



					.lesson-table thead tr th,

					.lesson-table tbody tr td {

						text-transform: capitalize;

						font-size: 12px;

						border: 1px solid #77777730;

						padding: 5px !important;

						cursor: pointer;

					}

				</style>

				<div class="row">

					<div class="col-md-12">

						<div class="card">

							<div class="card-body">

								<h5 class="mb-3">Basic Tabs</h5>

								<ul class="nav nav-tabs mb-3" id="myTab" role="tablist">

									<li class="nav-item">

										<a class="nav-link active text-capitalize" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Daily Lessons</a>

									</li>

									<li class="nav-item">

										<a class="nav-link text-capitalize" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">All Lessons</a>

									</li>

								</ul>

								<div class="tab-content" id="myTabContent">

									<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

										<div class="row">

											<div class="col-md-12">

												<table class="table table-sm table-bordered lesson-table">

													<thead>

														<tr>

															<th>#</th>

															<th>Teacher ID</th>

															<th>Teacher Name</th>

															<th>Standard</th>

															<th>Subject</th>

															<th>Created At</th>

														</tr>

													</thead>

													<tbody>

														<?php $i = 1;

														if ($lesson['daily_lessons'] != null) {

															foreach ($lesson['daily_lessons'] as $lesson2) { ?>

																<tr class="remark-lesson" data-id="<?= $lesson2['stvlmst_id'] ?>">

																	<td><?= $i; ?></td>

																	<td><?= $lesson2['SPRIDEN_student_id'] ?></td>

																	<td><?= $lesson2['name'] ?></td>

																	<td><?= $lesson2['stvlmst_std'] ?></td>

																	<td><?= $lesson2['stvsubj_subject'] ?></td>

																	<td><?= $lesson2['stvlmst_start_date'] ?></td>

																</tr>

														<?php $i++;

															}

														} else {

															echo "<tr><td colspan='6' class='text-center'>No data available</td></tr>";

														} ?>

													</tbody>

												</table>

											</div>

										</div>

									</div>

									<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

										<div class="col-md-12">

											<table id="simpletable" class="table table-striped table-bordered nowrap">

												<thead>

													<tr>

														<th>#</th>

														<th>Teacher ID</th>

														<th>Teacher Name</th>

														<th>Standard</th>

														<th>Subject</th>

														<th>Created At</th>

													</tr>

												</thead>

												<style>

													#simpletable thead tr th {

														padding: 5px;

														font-size: 12px;

														font-weight: 500;

														text-transform: capitalize;

													}



													#simpletable tbody tr td {

														padding: 2px 5px 2px 5px;

														font-size: 12px;

														font-weight: normal;

														text-transform: capitalize;

														cursor: pointer;

													}

												</style>

												<tbody>

													<?php $i = 1;

													foreach ($lesson['all_lessons'] as $lesson1) { ?>

														<tr class="remark-lesson" data-id="<?= $lesson1['stvlmst_id'] ?>">

															<td><?= $i; ?></td>

															<td><?= $lesson1['SPRIDEN_student_id'] ?></td>

															<td><?= $lesson1['name'] ?></td>

															<td><?= $lesson1['stvlmst_std'] ?></td>

															<td><?= $lesson1['stvsubj_subject'] ?></td>

															<td><?= $lesson1['stvlmst_start_date'] ?></td>

														</tr>

													<?php $i++;

													}

													?>

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

	<!---Include JS Files-->

	<?php include 'application/views/jsPages.php'; ?>



	<script>

		$('#class').change(function() {

			var std = $('#class').val();

			$.ajax({

				url: "<?= base_url('MasterForm/getAllSubjetcsByStd') ?>?std=" + std,

				method: "GET",

				dataType: "JSON",

				success: function(data) {

					if (data != null) {

						var html = '<option>Select Subject</option>';

						for (var count = 0; count < data.length; count++) {

							html += '<option value="' + data[count].stvsubj_id + '">' + data[count].stvsubj_subject + '</option>';

						}

						$('#subject').html(html);

					}

				}



			})

		});



		$('#subject').change(function() {

			var std = $('#class').val();

			var sub = $('#subject').val();



			if (std == '' && sub == '') {

				$('#alert').removeClass("alert-success");

				$('#alert').addClass("alert-danger");

				$('#alert').html("Select Class & Subject");

				$('#alert').fadeIn();

			} else if (std != '' && sub == '') {

				$('#alert').removeClass("alert-success");

				$('#alert').addClass("alert-danger");

				$('#alert').html("Select Subject");

				$('#alert').fadeIn();

			} else if (std == '' && sub != '') {

				$('#alert').removeClass("alert-success");

				$('#alert').addClass("alert-danger");

				$('#alert').html("Select Class ");

				$('#alert').fadeIn();

			} else {

				// get lessons

				$.ajax({

					url: "<?= base_url('index.php/lesson/all_lessons') ?>?class=" + std + "&sub=" + sub,

					method: "GET",

					dataType: "JSON",

					beforeSend: function() {},

					success: function(data) {

						if (data != null) {

							generate_table(data)

						} else {

							$('#alert').removeClass("alert-success");

							$('#alert').addClass("alert-danger");

							$('#alert').html("Lessons not available");

							$('#alert').fadeIn();

						}

					}

				});

			}

		});



		$(document).on('click', '.remark-lesson', function() {

			var ids = $(this).attr("data-id");

			window.location = '<?= base_url('teacher/lesson/') ?>' + ids;

		})

	</script>

</body>



</html>


<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

	<title>Olive English School - App Track</title>

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<meta name="description" content="" />

	<meta name="keywords" content="">

	<meta name="author" content="Phoenixcoded" />



	<!---Include CSS Files-->

	<?php include 'application/views/cssPages.php'; ?>

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/id-card.css">

	<style>
		@media print {

			body * {

				visibility: hidden;

			}



			.print-container {

				margin-top: -200px;

			}



			.print-container,

			.print-container * {

				visibility: visible;

			}

		}
	</style>

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

		<form action="" id="classCreateForm" method="post">

			<div class="pcoded-content">

				<!-- [ breadcrumb ] start -->

				<?php include 'application/views/all_reports/links.php'; ?>

				<!-- [ breadcrumb ] end -->

				<!-- [ Main Content ] start -->



				<div class="row margin-top-minus-40 mobile-margin-top-minus-40 mb-2">

					<div class="col-md-7">

						<a href=" base_url(); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a>

						<button onclick="window.print();" class="btn btn-secondary btn-sm" id="print-button" type="button"> <i class="fas fa-print"></i> <span id="dynamicLabelAddButton">Print</span></button>

					</div>

				</div>

				<div class="row">

					<div class="col-md-12">

						<div class="card">

							<div class="card-header">

								<h5 class="card-title">

									Application Tracking Report

									<span class="text-primary" style="position: absolute;

												 right: 10px"></span>

								</h5>

							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-6 d-flex">


										<label for="" class="col-md-2 text-right d-flex">User<span class="text-danger">*</span> :</label>

										<select class="form-control form-control-sm d-flex" id="user" name="user" value="<?= $user ?>">
											<option>Select</option>
											<option <?= $user == 'tc' ? 'selected' : '' ?> value="tc">Teacher</option>
											<option <?= $user == 'st' ? 'selected' : '' ?> value="st">Student</option>
										</select>

									</div>

									<div class="col-md-6 d-flex">



										<label for="" class="col-md-2 text-right">Date <span class="text-danger">*</span> :</label>

										<input type="date" class="form-control form-control-sm col-md-5" name="date" id="date" value="<?= $date ?>" />

										<div class="col-md-5">

											<button type="button" id="search" class="btn btn-sm btn-primary">Search</button>




										</div>

									</div>
								</div>

								<style>
									.table tr td,

									.table tr th {

										padding: 5px !important;

										font-size: 11px !important;

									}
								</style>

								<div class="print-container">

									<div class="col-md-12">

										<div class="dt-responsive table-responsive margin-top-minus-10">

											<table id="simpletable" class="table table-striped table-bordered nowrap">

												<thead>

													<tr>

														<th class="text-capitalize">#</th>

														<th class="text-capitalize"> ID</th>

														<th class="text-capitalize">Name</th>

														<th class="text-capitalize">Date </th>

														<th class="text-capitalize">App Opened</th>

													</tr>

												</thead>

												<tbody id="fees-tbody">
													<?php
													$i = 1;
													foreach ($fetch as $fet) :
													?>
														<tr>

															<td><?= $i++ ?></td>
															<td><?= $fet['student_id'] ?></td>
															<td><?= $fet['name'] ?></td>
															<td><?= $fet['action_date'] ?></td>
															<td><?= $fet['counter'] ?></td>
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

		</form>

		<!-- [ Main Content ] end -->

	</div>

	</div>

	<!-- [ Main Content ] end -->

	</div>

	</div>

	<!---Include JS Files-->


	<?php include 'application/views/jsPages.php'; ?>

	<script>
		var dat = document.getElementById('date');
		var use = document.getElementById('user');
		// var burl = <?= base_url() ?>;
		// console.log(burl);

		filter_but = document.getElementById('search');
		filter_but.addEventListener("click", function() {
			var date = dat.value;
			var user = use.value;
			window.location = `http://localhost/olive_school/report/app_track?action=search&date=${date}&user=${user}`;

		});

		
	</script>

</body>



</html>

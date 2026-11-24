<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

	<title> | Olive English School</title>

	<!-- Meta -->

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<meta name="description" content="" />

	<meta name="keywords" content="">

	<meta name="author" content="Phoenixcoded" />





	<?php include 'application/views/cssPages.php'; ?>

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/teacher-module.css">

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/new-db.css">

	<!-- Boxicons CSS -->

	<link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

</head>

<style>
	.student_style_table .student_details_div {
		height: 550px !important;
	}

	.pc-container {
		position: relative;
		top: 30px !important;
		margin-left: 280px;
		min-height: calc(100vh - 70px);
	}
</style>

<body class="">


	<!-- <?php include 'application/views/teacher/topNav.php'; ?> -->

	<!-- [ Header ] end -->

	<div class="container" style="margin-top: 7%;">


		<form method="post" action="<?= base_url('dashboard/set_class_division') ?>">
			<div class="row" style="padding: 5px;">

				<div class="col-md-12">

					<div class="card">

						<div class="card-header bg-student-details">

							<h5 class="text-white">Student Details</h5>


							<button class="btn btn-success btn-sm" style="margin-left: 15px;" type="submit" id="update_rollNoButton"> <i class="fas fa-edit"></i> Update class</button>

						</div>

						<style>
							#studentTable tr:hover {

								cursor: pointer;

								color: #318eb9;

								font-weight: normal;

								transition: all 0.2s ease-in-out;

							}



							#studentTable tr td,

							#studentTable th td {

								padding: 5px;

								font-size: 12px;

							}

							.form-control {
								border: none;
								padding: 0px;
							}

							.classes {
								display: flex;
								margin-right: 0px;
								margin-left: 0px;
								padding: 2px;
								justify-content: space-evenly;

							}
						</style>

						<div class="card-body student_style_table p-0">
							<div class="row classes">
								<div class=""> <b> Std : <span><?= $class ?></span></b></div>
								<div class=""> <b> Div : <span><?= $div ?></span> </b></div>
								<input name="hidden_div" id="hidden_div" value="<?= $div ?>" type="hidden">
								<input name="hidden_class" id="hidden_class" value="<?= $class ?>" type="hidden">
								<input name="total_students" id="total_students" value="<?= count($re_stud) ?>" type="hidden">

							</div>
							<div class="student_details_div">


								<table class="table table-bordered table-striped pb-0 mb-0" id="studentTable">

									<tbody id="tbody">

										<tr style="text-align: center;">

											<td style="font-weight: bold;">Sr.No.</td>

											<td style="font-weight: bold; width:65%">Student Name</td>

											<td style="font-weight: bold; text-align: left;">Div</td>

											<td style="font-weight: bold; text-align: left;">Exist in Class</td>

											<td style="font-weight: bold; text-align: left;">Student Status</td>

										</tr>

										<?php

										if ($re_stud != null) {

											$i = 1;
											$counter = 1;

											foreach ($re_stud as $stud) { //foreach start

										?>

												<tr>

													<td style="text-align: center; padding: 2px;">
														<?= $i; ?>
													</td>

													<td>
														<input type="hidden" id="sid" name="sid<?= $counter ?>" value="<?= $stud['id'] ?>">
														<input type="hidden" id="div" name="div<?= $counter ?>" value="<?= $div ?>">
														<input type="hidden" id="class" name="class<?= $counter ?>" value="<?= $class ?>">
														<?= $stud['name'] ?>
													</td>

													<td>

														<?= $stud['division'] ?>

													</td>

													<td>
														<input name="status_class<?= $counter ?>" <?= $div == $stud['division'] ? 'checked' : '' ?> type="checkbox" class="remove-checkbox" data-student-id="<?php echo  $i; ?>">
													</td>
													<td>
														<label for="">
															<input name="status_student<?= $counter ?>" type="checkbox" class="remove-checkbox" data-student-id="<?php echo  $i; ?>" <?= $stud['spriden_student_status'] == 'A' ? 'checked' : '' ?>>

														</label>
													</td>

												</tr>

										<?php $i++; $counter++;
											} //foreach end 

										} // not null

										?>

									</tbody>
									<!-- <?php echo $div ?> -->
								</table>
								<!-- <input type="hidden" name="total_students" value="<?= $total_students ?>"> -->

							</div>

						</div>

					</div>

				</div>

			</div>
		</form>

	</div>

	<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>

	<!-- [ Main Content ] end -->

</body>



</html>
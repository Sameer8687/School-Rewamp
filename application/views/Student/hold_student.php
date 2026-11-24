<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Student Registration Form</title>
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
		<form action="<?php echo base_url(); ?>Student/add_student_hold_data" enctype="multipart/form-data" method="post">
			<div class="pcoded-content">
				<!-- [ breadcrumb ] start -->

				<!-- [ breadcrumb ] end -->
				<!-- [ Main Content ] start -->

				<div class="row margin-top-minus-40 mobile-margin-top-minus-10 mb-2">
					<div class="col-md-7">
						<!-- <a href="" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a> -->
						<button class="btn btn-secondary btn-sm"> <i class="fas fa-user-graduate"></i> <span id="dynamicLabelAddButton">Hold Student</span></button>

					</div>
					<div class="col-md-5 student-form-search">
						<input type="text" class="form-control form-control-sm" id="search_student_name" placeholder="Search Student Name" autocomplete="off">
						<span class="left-icon"><i class="fas fa-search"></i></span>
						<span class="right-icon" onclick="clearFormInputIcon()"><i class="fas fa-times"></i></span>
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
				<div class="row">
					<div class="col-md-12">
						<div class="card fees-card">
							<div class="card-header">
								<h5>Student Details</h5>
							</div>
							<div class="card-body pb-2">
								<div class="row margin-top-minus-20">
									<div class="col-md-4">
										<div class="form-group row mobile-margin-top-minus-20">
											<label for="" class="col-md-6 text-right">ID <span class="text-danger">*</span> :</label>
											<input type="text" readonly class="form-control form-control-sm col-md-6" required name="student_id" id="student_id">
											<input type="hidden" class="form-control form-control-sm col-md-7" name="student_id_hidden" id="student_id_hidden" readonly>
										</div>
									</div>
									<div class="col-md-8">
										<div class="form-group row mobile-margin-top-minus-15">
											<label for="" class="col-md-3 text-right">Student Name :</label>
											<input type="text" readonly class="form-control form-control-sm col-md-9" name="student_name" id="student_name">
										</div>
									</div>
								</div>
								<div class="row margin-top-minus-20">
									<div class="col-md-4">
										<div class="form-group mobile-margin-top-minus-15 row">
											<label for="" class="col-md-6 text-right">Hold Type <span class="text-danger">*</span> :</label>
											<select class="form-control form-control-sm col-md-6" name="hold_type" id="hold_type" required>
												<option value="" selected disabled>Select Hold</option>
												<?php
												foreach ($all_hold_types as $all_hold_type) {
												?>
													<option value="<?php echo $all_hold_type['stvhold_id']; ?>">
														<?php echo $all_hold_type['stvhold_type']; ?></option>
												<?php } ?>

											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group mobile-margin-top-minus-15 row">
											<label for="" class="col-md-6 text-right">From <span class="text-danger">*</span> :</label>
											<input type="date" class="form-control form-control-sm col-md-6" required name="hold_from" id="hold_from">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group mobile-margin-top-minus-15 row">
											<label for="" class="col-md-6 text-right">To :</label>
											<input type="date" class="form-control form-control-sm col-md-6" name="hold_to" id="hold_to">
										</div>
									</div>
								</div>
								<style>
									#pre-tra-row {
										display: none;
									}
								</style>
								<style>
									.pre-tra {
										margin-bottom: 0;
										color: #525f71;
										font-size: 14px;
										font-weight: 600;
									}
								</style>
								<div class="row">
									<div class="col-md-12">
										<h5 class="pre-tra">Students on Hold</h5>
										<hr>
										<div class="dt-responsive table-responsive margin-top-minus-10">
											<table id="simpletable" class="table table-striped table-bordered nowrap">
												<thead>
													<tr>
														<th>No.</th>
														<th>ID</th>
														<th>Student Name</th>
														<th>Type</th>
														<th>From</th>
														<th>To</th>
														<th>Status</th>
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
													<?php
													$i = 1;
													foreach ($all_hold_data as $all_holds) {
													?>
														<tr>
															<td><?= $i; ?></td>
															<td><?= $all_holds['SPRIDEN_student_id']; ?></td>
															<td><?= $all_holds['name']; ?></td>
															<td><?= $all_holds['stvhold_type']; ?></td>
															<td><?= date('d M Y', strtotime($all_holds['sprhold_from'])); ?></td>
															<td><?= date('d M Y', strtotime($all_holds['sprhold_to'])); ?></td>
															<td>
																<?php
																if ($all_holds['sprhold_status'] == "Yes")
																	echo "Hold";
																else
																	$all_holds['sprhold_status']
																?>
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
							<!-- [ Search List ] Start -->
							<div class="search-list-container-panel">
								<div class="list-group" id="dynamic_name_list"></div>
							</div>
							<!-- [ Search List ] End -->
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
		$(document).ready(function() {




			$('#besicwizard').bootstrapWizard({
				withVisible: false,
				'tabClass': 'nav nav-tabs',
				'nextSelector': '.button-next',
				'previousSelector': '.button-previous',
			});
			// -------------------------- Search Student Names  -----------------------------
			$('#search_student_name').keyup(function() {
				var names = $('#search_student_name').val().trim();
				if (names != '') {

					$.ajax({
						url: "<?php echo base_url(); ?>index.php/Student/search_student_names",
						method: "POST",
						data: {
							name: names
						},
						dataType: "JSON",
						success: function(data) {
							if (data != '') {
								var html = '';
								for (var count = 0; count < data.length; count++) {
									html += '<a id="' + data[count].id +
										'" href="#" style="border:none; border-bottom: 1px solid #77777720; border-radius: 0px; padding: 5px; font-size: 12px; font-weight:bold;" class="list-group-item list-group-item-action getDataOfStudent"> <span class="text-info">' +
										data[count].SPRIDEN_surname + " " + data[count]
										.SPRIDEN_student_name + " " + data[count]
										.SPRIDEN_father_name +
										'</span><span class="text-danger"> ( Standard :  ' +
										data[count].STVCLAS_standard + ' ) </span>' +
										'</a>';
								}
								$('#dynamic_name_list').html(html);
								$('.search-list-container-panel').css('display', 'block');

							} else {
								var notAvailable =
									'<a href="#" style="border-radius: 0px; border:none; padding: 5px; font-size: 12px; font-weight:bold;" class="list-group-item list-group-item-action noDataOfStudent"> <span class="text-info">Data Not Available</span></a>';
								$('#dynamic_name_list').html(notAvailable);
							}
						}
					});
				} else {
					clearFormInputIcon();
					$('#dynamicLabelAddButton').html("Add Student");
					$('#dynamic_name_list').html('');
					$('.search-list-container-panel').css('display', 'none');
				}
			});

			// Get Details After Clicking on Student Names
			$(document).on('click', '.getDataOfStudent', function() {
				var names = $(this).text();
				var student_ids = $(this).attr('id');

				$('#show-list').html('');
				$.ajax({
					url: "<?php echo base_url(); ?>index.php/Student/get_selected_student_data",
					method: "POST",
					data: {
						student_id: student_ids
					},
					dataType: "JSON",
					success: function(data) {
						if (data != '') {
							//--------- Assign Value to PERONAL DETAILS text box ------ 
							$('#student_id').val(data['info'].SPRIDEN_student_id);
							$('#student_id_hidden').val(data['info'].id);
							$('#student_name').val(data['info'].SPRIDEN_surname + " " + data['info'].SPRIDEN_student_name + " " + data['info'].SPRIDEN_father_name);
							$('#dynamic_name_list').html('');
							$('.search-list-container-panel').css('display', 'none');


						} else {
							clearFormInputIcon();
							$('#dynamic_name_list').html('');
							$('.search-list-container-panel').css('display', 'none');
						}
					}
				});
			});

			$(document).on('click', '.noDataOfStudent', function() {
				clearFormInputIcon();
				$('#search_student_name').val('');
				$('#dynamicLabelAddButton').html("Add Student");
				$('#dynamic_name_list').html('');
				$('.search-list-container-panel').css('display', 'none');
			});
		});

		function clearFormInputIcon() {
			$('#search_student_name').val('');
			//--------- Assign Value to PERONAL DETAILS text box ------ 
			$('#student_id').val('');
			$('#student_name').val('');

			//--------- Dynamically Change The LAbels ------
			$('#dynamicLabelAddButton').html("Add Student");
			$('#dynamic_name_list').html('');
			$('.search-list-container-panel').css('display', 'none');

			$('#pre-tra-tab-body').html('');
			$('#pre-tra-row').slideUp();
		}
	</script>


</body>

</html>
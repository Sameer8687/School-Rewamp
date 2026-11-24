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
		<form action="<?php echo base_url(); ?>Student/add_fees_data" method="post">
			<div class="pcoded-content">
				<!-- [ breadcrumb ] start -->
				<?php include 'application/views/Student/links.php'; ?>
				<!-- [ breadcrumb ] end -->
				<!-- [ Main Content ] start -->

				<div class="row margin-top-minus-40 mobile-margin-top-minus-40 mb-1">
					<div class="col-md-7">
						<a href="<?php echo base_url(); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a>
						<button class="btn btn-secondary btn-sm"> <i class="fas fa-dollar-sign"></i> <span id="dynamicLabelAddButton">Add Fees Data</span></button>

					</div>
					<div class="col-md-5 student-form-search">
						<input type="text" autocomplete="off" class="form-control form-control-sm" id="search_student_name" placeholder="Search Student Name">
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
							<div class="card-header pb-2">
								<h5 class="margin-top-minus-10 mb-0 pb-0">Add Student Fees For Academic Year <?= $academic_details['stvacdm_year']; ?>
								</h5>
								<input type="hidden" name="academic_year_hidden" value="<?= $academic_details['stvacdm_id']; ?>">
							</div>
							<div class="card-body">
								<style>
									label {
										text-align: right;
									}

									.no-margin {
										margin-top: -10px;
									}
								</style>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-10 mobile-margin-top-minus-20">
											<label for="" class="col-md-5 text-right">ID<span class="text-danger">*</span> :</label>
											<input type="text" class="form-control form-control-sm col-md-7" required name="student_id" id="student_id" readonly>
											<input type="hidden" class="form-control form-control-sm col-md-7" name="student_id_hidden" id="student_id_hidden" readonly>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-10 mobile-margin-top-minus-20">
											<label for="" class="col-md-5 text-right">Standard<span class="text-danger">*</span> :</label>
											<input type="text" class="form-control form-control-sm col-md-7" required name="student_standard" id="student_standard" >
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-10 mobile-margin-top-minus-20">
											<label for="" class="col-md-5 text-right">Name :</label>
											<input type="text" class="form-control form-control-sm col-md-7" name="student_name" id="student_name" readonly>
										</div>
									</div>
								</div>
								<div class="row mobile-display-none">
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-20">
											<label for="" class="col-md-5 text-right">Total Fees :</label>
											<label style="text-align: left;" for="" class="col-md-7"><span class="text-primary" id="dynamic_total_fees_label" style="font-weight: 600;"></span> Rs. </label>
										</div>
									</div>
									<div class="col-md-4"></div>
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-20">
											<label for="" class="col-md-5 text-right">Balance Fees :</label>
											<label style="text-align: left;" for="" class="col-md-7"><span class="text-primary" id="dynamic_fees_label" style="font-weight: 600;"></span> Rs. </label>
										</div>
									</div>
								</div>
								<div class="row desktop-display-none mobile-margin-top-minus-10">
									<div class="col-md-12">
										<label style="text-align: left;" for="" class="">Total Fees :<span class="text-primary" id="dynamic_total_fees_label_sm" style="font-weight: 600;"></span> Rs. </label>
										<label style="text-align: left; float: right;" for="" class="">Balance Fees :<span class="text-primary" id="dynamic_fees_label_sm" style="font-weight: 600;"></span> Rs. </label>
									</div>
								</div>
								<div class="row justify-content-center margin-top-minus-20">
									<div class="col-md-12">
										<div id="div-fees-table">
											<style>
												#div-fees-table #feesTable thead tr th {
													text-transform: capitalize;
												}
											</style>
											<div class="table-responsive">
												<table class="table table-bordered" id="feesTable">
													<thead>
														<tr>
															<th>#</th>
															<th>Date</th>
															<th>Amount</th>
															<th>Academic Year</th>
															<th>Received By</th>
															<th>Note</th>
														</tr>
													</thead>
													<tbody id="tbody">

													</tbody>
												</table>

											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group row">
											<label for="" class="col-md-5 text-right">Amount :</label>
											<input type="text" class="form-control form-control-sm col-md-7" name="amount_paid" id="amount_paid">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row mobile-margin-top-minus-15">
											<label for="" class="col-md-5 text-right">Paid By :</label>
											<input type="text" class="form-control form-control-sm col-md-7" name="paid_by" id="paid_by" value="<?php echo $user['spruser_username']; ?>">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row mobile-margin-top-minus-15">
											<label for="" class="col-md-5 text-right">Due Date :</label>
											<input type="date" class="form-control form-control-sm col-md-7" name="due_date" id="due_date">
										</div>
									</div>
								</div>
								<div class="row no-margin">
									<style>
										.custom-details-row {
											margin-left: -48px;
										}

										@media only screen and (max-width: 1300px) and (min-width: 1200px) {
											.custom-details-row {
												margin-left: -45px;
											}
										}
/* 1600 - 900 */
										@media only screen and (max-width: 1600px) and (min-width: 1301px) {
											.custom-details-row {
												margin-left: -57px;
											}

											#div-fees-table {
												margin-left: 155px;
												width: 1070px;
											}
										}
										/* 1920 - 1080 */
										@media screen and (min-width: 1900px) {
											.custom-details-row {
												margin-left: -68px;
											}
										}

										@media only screen and (max-width: 600px) {
											.custom-details-row {
												margin-left: -15px;
											}
										}
									</style>
									<div class="col-md-12">
										<div class="form-group row custom-details-row margin-top-minus-10">
											<label for="" class="col-md-2 text-right">Details :</label>
											<input type="text" class="form-control form-control-sm col-md-10" name="details" id="details">
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

			// -------------------------- Search Student Names  -----------------------------
			$('#search_student_name').keyup(function() {
				var names = $('#search_student_name').val().trim();
				if (names != '') {
					$.ajax({
						url: "<?php echo base_url(); ?>index.php/Student/search_from_spofees",
						method: "POST",
						data: {
							name: names
						},
						dataType: "JSON",
						success: function(data) {
							if (data != '') {
								var html = '';
								for (var count = 0; count < data.length; count++) {
									html += '<a id="' + data[count].spIdInc + '" href="#" style="border:none; border-bottom: 1px solid #77777720; border-radius: 0px; padding: 5px; font-size: 12px;" class="list-group-item list-group-item-action getDataOfStudent"> <span class="text-info">' + data[count].SPRIDEN_surname + " " + data[count].SPRIDEN_student_name + " " + data[count].SPRIDEN_father_name + '</span><span class="text-danger"> ( Standard :  ' + data[count].sprfees_standard + ' ) </span>' + '</a>';
								}
								$('#dynamic_name_list').html(html);
								$('.search-list-container-panel').css('display', 'block');

							} else {
								var notAvailable = '<a href="#" style="border-radius: 0px; border:none; padding: 5px; font-size: 12px;" class="list-group-item list-group-item-action noDataOfStudent"> <span class="text-info">Data Not Available</span></a>';
								$('#dynamic_name_list').html(notAvailable);
							}
						}
					});
				} else {
					clearFormInputIcon();
					$('#dynamicLabelAddButton').html("Add Fees Data");
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
					url: "<?php echo base_url(); ?>index.php/Student/get_fees_data_of_student",
					method: "POST",
					data: {
						student_id: student_ids
					},
					dataType: "JSON",
					success: function(data) {
						if (data != '') {
							console.log(data);
							//--------- Assign Value to PERONAL DETAILS text box ------ 
							$('#student_id').val(data['info'].SPRIDEN_student_id);
							$('#student_id_hidden').val(data['info'].spIdInc);
							$('#student_standard').val(data['info'].sprfees_standard);
							$('#student_name').val(data['info'].SPRIDEN_surname + " " + data['info'].SPRIDEN_student_name + " " + data['info'].SPRIDEN_father_name);
							var intBal = parseInt(data['info'].Balance_amt);
							if (intBal > 0) {
								$('#dynamic_fees_label').html(data['info'].Balance_amt);
								$('#dynamic_fees_label').addClass("text-danger");
								$('#dynamic_fees_label').removeClass("text-success");
								//small
								$('#dynamic_fees_label_sm').html(data['info'].Balance_amt);
								$('#dynamic_fees_label_sm').addClass("text-danger");
								$('#dynamic_fees_label_sm').removeClass("text-success");
							} else {
								$('#dynamic_fees_label').html("Full Fees Paid");
								$('#dynamic_fees_label').removeClass("text-danger");
								$('#dynamic_fees_label').addClass("text-success");
								//small
								$('#dynamic_fees_label_sm').html("Full Fees Paid");
								$('#dynamic_fees_label_sm').removeClass("text-danger");
								$('#dynamic_fees_label_sm').addClass("text-success");
							}
							$('#dynamic_total_fees_label').html(data['info'].total_fees);
							$('#dynamic_total_fees_label_sm').html(data['info'].total_fees);

							//--------- Assign Value to Table ------
							var html = '';
							for (var i = 0; i < data['fees'].length; i++) {
								var newI = i + 1;
								var date = data['fees'][i].sprfees_feesdate;
								date = date.split("-").reverse().join("-");
								html += '<tr>';
								html += '<td>' + newI + '</td>';
								html += '<td>' + date + '</td>';
								html += '<td>' + data['fees'][i].sprfees_amount + '</td>';
								html += '<td>' + data['fees'][i].stvacdm_year + '</td>';
								html += '<td>' + data['fees'][i].sprfees_recivedby + '</td>';
								html += '<td>' + data['fees'][i].sprfees_notes + '</td>';
								html += '</tr>';
							}
							$('#tbody').html(html);

							//--------- Dynamically Change The LAbels ------
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
				$('#dynamicLabelAddButton').html("Add Fees Data");
				$('#dynamic_name_list').html('');
				$('.search-list-container-panel').css('display', 'none');
			});
		});

		function clearFormInputIcon() {
			$('#student_id').val('');
			$('#student_id_hidden').val('');
			$('#student_standard').val('');
			$('#student_name').val('');
			$('#dynamic_fees_label').html('');
			$('#dynamic_total_fees_label').html('');
			$('#dynamic_fees_label_sm').html('');
			$('#dynamic_total_fees_label_sm').html('');
			$('#search_student_name').val('');

			//--------- Assign Value to BOOKS & RESULT text box ------
			$('#books_given').val('');
			$('#result_given').val('');
			$('#teacher_name').val('');
			$('#amount_paid').val('');
			$('#balance_amt').val('');
			$('#due_date').val('');
			$('#details').val('');
			$('#area').val('');

			$('#tbody').html('');

			//--------- Dynamically Change The LAbels ------
			$('#dynamicLabelAddButton').html("Add Fees Data");
			$('#dynamic_name_list').html('');
			$('.search-list-container-panel').css('display', 'none');
		}
	</script>
</body>

</html>

<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Student Books & Result Form</title>
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
		<form action="<?php echo base_url(); ?>Student/add_books_and_result_data" method="post">
			<div class="pcoded-content">
				<!-- [ breadcrumb ] start -->
				
				<!-- [ breadcrumb ] end -->
				<!-- [ Main Content ] start -->

				<div class="row margin-top-minus-40 mobile-margin-top-minus-0  mb-2">
					<div class="col-md-7">
						<!-- <a href="<?php echo base_url(); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a> -->
						<button class="btn btn-secondary btn-sm"> <i class="fas fa-book"></i> <span id="dynamicLabelAddButton">Add Data</span></button>
						<label for="" class="mx-2">Balance Fees: <span class="" id="dynamic_fees_label" style="font-weight: 600;"></span> Rs. </label>
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
						<div class="card">
							<div class="card-body">
								<ul class="nav nav-tabs" id="myTab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active " id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Add Books & Result Data</a>
									</li>
									<li class="nav-item">
										<a class="nav-link " id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">View Books & Result Details</a>
									</li>
								</ul>
								<style>
									label {
										text-align: right;
									}

									.no-margin {
										margin-top: -10px;
									}
								</style>
								<div class="tab-content booksTabContents" id="myTabContent">
									<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

										<div class="row mt-3">
											<div class="col-md-4">
												<div class="form-group row ">
													<label for="" class="col-md-5 text-right">ID<span class="text-danger">*</span> :</label>
													<input type="text" class="form-control form-control-sm col-md-7" required name="student_id" id="student_id" readonly>
													<input type="hidden" class="form-control form-control-sm col-md-7" name="student_id_hidden" id="student_id_hidden" readonly>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group row mobile-margin-top-minus-15">
													<label for="" class="col-md-5 text-right">Standard<span class="text-danger">*</span> :</label>
													<input type="text" class="form-control form-control-sm col-md-7" required name="student_standard" id="student_standard" readonly>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group row mobile-margin-top-minus-15">
													<label for="" class="col-md-5 text-right">Name :</label>
													<input type="text" class="form-control form-control-sm col-md-7" name="student_name" id="student_name" readonly>
												</div>
											</div>
										</div>
										<div class="row no-margin">
											<div class="col-md-4">
												<div class="form-group row margin-top-minus-10 mobile-margin-top-minus-10">
													<label for="" class="col-md-5 text-right">Books<span class="text-danger">*</span> :</label>
													<select name="books_given" id="books_given" required class="form-control form-control-sm col-md-7">
														<option value="">Select</option>
														<option value="Yes">Yes</option>
														<option value="No">No</option>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">
													<label for="" class="col-md-5 text-right">Result<span class="text-danger">*</span> :</label>
													<select name="result_given" id="result_given" required class="form-control form-control-sm col-md-7">
														<option value="">Select</option>
														<option value="Yes">Yes</option>
														<option value="No">No</option>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">
													<label for="" class="col-md-5 text-right">Teacher :</label>
													<input type="text" class="form-control form-control-sm col-md-7" name="teacher_name" id="teacher_name">
												</div>
											</div>
										</div>
										<div class="row no-margin">
											<div class="col-md-4">
												<div class="form-group row margin-top-minus-10 mobile-margin-top-minus-10">
													<label for="" class="col-md-5 text-right">Amount :</label>
													<input type="text" class="form-control form-control-sm col-md-7" name="amount_paid" id="amount_paid">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">
													<label for="" class="col-md-5 text-right">Balance :</label>
													<input type="text" class="form-control form-control-sm col-md-7" name="balance_amt" id="balance_amt">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">
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

												@media only screen and (max-width: 600px) {
													.custom-details-row {
														margin-left: -15px;
													}
												}

												@media only screen and (max-width: 1300px) and (min-width: 1200px) {
													.custom-details-row {
														margin-left: -45px;
													}
												}

												/* 1920 - 1080 */
												@media screen and (min-width: 1900px) {
													.custom-details-row {
														margin-left: -68px;
													}
												}
											</style>
											<div class="col-md-12">
												<div class="form-group row custom-details-row margin-top-minus-10">
													<label for="" class="col-md-2 text-right">Details :</label>
													<input type="text" class="form-control form-control-sm col-md-10" name="details" id="details">
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group row custom-details-row margin-top-minus-20 mobile-margin-top-minus-15">
													<label for="" class="col-md-2 text-right">Area :</label>
													<input type="text" class="form-control form-control-sm col-md-10" name="area" id="area">
												</div>
											</div>
										</div>

									</div>
									<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
										<div class="row ">
											<div class="col-md-12">
												<div class="dt-responsive table-responsive">
													<style>
														#simpletable tbody tr td {
															padding: 5px;
															font-size: 12px;
														}
													</style>
													<table id="simpletable" class="table table-striped table-bordered nowrap">
														<thead>
															<tr>
																<th>Name</th>
																<th>Std</th>
																<th>Books</th>
																<th>Result</th>
																<th>Amount</th>
																<th>Balance</th>
																<th>Paid Date</th>
																<th>Due Date</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($students_books as $book) {
																//Convert and Display dates ----------------------
																$servDate = $book['STVBOOK_paid_date'];
																$timestamp = strtotime($servDate);
																$paid_date = date("d M Y", $timestamp);

																$servDate1 = $book['STVBOOK_due_date'];
																$timestamp1 = strtotime($servDate1);
																$due_date = date("d M Y", $timestamp1);

																//Check if Due date gone change color
																$expire_due_date = $book['STVBOOK_due_date'];
																$expire = strtotime($expire_due_date);
																$today = strtotime("today midnight");

																//Get Next 2 Days
																$previous_two_days_in_seconds = "s";
																$previous_two_days = date("d M Y", strtotime(date('Y-m-d', strtotime($book['STVBOOK_due_date'] . ' -2 days'))));
																$date = $book['STVBOOK_due_date'];
																$two_days_ago = date('Y-m-d', strtotime($date . ' - 2 days'));

																if ($today > $expire && $book['STVBOOK_bal_amount'] > 0) {

															?>
																	<tr class="bg-danger text-white">
																	<?php } elseif (strtotime(date('Y-m-d')) == strtotime($two_days_ago) && $book['STVBOOK_bal_amount'] > 0) { ?>
																	<tr class="bg-warning text-white">
																	<?php  } else { ?>
																	<tr>
																	<?php } ?>
																	<td><?php echo $book['SPRIDEN_surname'] . " " . $book['SPRIDEN_student_name'] . " " . $book['SPRIDEN_father_name'] ?></td>
																	<td><?php echo $book['STVBOOK_standard']; ?></td>
																	<td><?php echo $book['STVBOOK_books']; ?></td>
																	<td><?php echo $book['STVBOOK_result']; ?></td>
																	<td><?php echo $book['STVBOOK_paid_amonut']; ?></td>
																	<td><?php echo $book['STVBOOK_bal_amount']; ?></td>
																	<td><?php echo $paid_date; ?></td>
																	<td><?php echo $due_date; ?></td>
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
					$('#dynamicLabelAddButton').html("Add Data");
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
					url: "<?php echo base_url(); ?>index.php/Student/get_search_from_spofees",
					method: "POST",
					data: {
						student_id: student_ids
					},
					dataType: "JSON",
					success: function(data) {
						if (data != '') {
							//--------- Assign Value to PERONAL DETAILS text box ------ 
							$('#student_id').val(data['info'].SPRIDEN_student_id);
							$('#student_standard').val(data['info'].sprfees_standard);
							$('#student_name').val(data['info'].SPRIDEN_surname + " " + data['info'].SPRIDEN_student_name + " " + data['info'].SPRIDEN_father_name);
							var intBal = parseInt(data['info'].Balance_amt);
							if (intBal > 0) {
								$('#dynamic_fees_label').html(data['info'].Balance_amt);
								$('#dynamic_fees_label').addClass("text-danger");
								$('#dynamic_fees_label').removeClass("text-success");
							} else {
								$('#dynamic_fees_label').html("Full Fees Paid");
								$('#dynamic_fees_label').removeClass("text-danger");
								$('#dynamic_fees_label').addClass("text-success");
							}

							//--------- Assign Value to BOOKS & RESULT text box ------
							if (data['books'] != null) {
								$('#books_given').val(data['books'].STVBOOK_books);
								$('#result_given').val(data['books'].STVBOOK_result);
								$('#teacher_name').val(data['books'].STVBOOK_teacher_name);
								$('#amount_paid').val(data['books'].STVBOOK_paid_amonut);
								$('#balance_amt').val(data['books'].STVBOOK_bal_amount);
								$('#due_date').val(data['books'].STVBOOK_due_date);
								$('#details').val(data['books'].STVBOOK_details);
								$('#area').val(data['books'].STVBOOK_area);
								$('#dynamicLabelAddButton').html("Update Data");
								$('#last-record-row').css("display", "block");
								var date = data['books'].STVBOOK_updated_date;
								date = date.split("-").reverse().join("-");
								$('#last_updated_on').html(date);
								$('#last_updated_by').html(data['update_by'].spruser_name);
								$('#last_updated_by_user').html(data['update_by'].spruser_usertype);
							}

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
				$('#dynamicLabelAddButton').html("Add Data");
				$('#dynamic_name_list').html('');
				$('.search-list-container-panel').css('display', 'none');
			});
		});

		function clearFormInputIcon() {
			$('#student_id').val('');
			$('#student_standard').val('');
			$('#student_name').val('');
			$('#dynamic_fees_label').html('');
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

			//--------- Dynamically Change The LAbels ------
			$('#dynamicLabelAddButton').html("Add Data");
			$('#dynamic_name_list').html('');
			$('.search-list-container-panel').css('display', 'none');
		}
	</script>
</body>

</html>

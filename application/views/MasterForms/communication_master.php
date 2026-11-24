<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Communication Master</title>
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
		<form action="" method="post" id="communicationForm">
			<div class="pcoded-content">
				<!-- [ breadcrumb ] start -->
				
				<!-- [ breadcrumb ] end -->
				<!-- [ Main Content ] start -->

				<div class="row margin-top-minus-40 mobile-margin-top-minus-10 mb-2">
					<div class="col-md-7">
						<!-- <a href="<?php echo base_url(); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a> -->
						<button class="btn btn-secondary btn-sm" type="submit" id="addDetailsFormButton"> <i class="fas fa-phone-volume"></i> <span id="dynamicLabelAddButton">Add Details</span></button>

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
						<div class="card pb-0 mb-0">
							<div class="card-header mb-0 pb-2 ">
								<h5 class="margin-top-minus-10 mb-0 pb-0">Add Communication Details</h5>
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
								<div class="row">
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-10">
											<label for="" class="col-md-5">Student ID<span class="text-danger">*</span> :</label>
											<input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="student_id" id="student_id" readonly required style="border:none">
											<input type="hidden" autocomplete="off" class="form-control form-control-sm col-md-7" name="student_id_hidden" id="student_id_hidden" readonly required style="border:none">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-10">
											<label for="" class="col-md-5">Name :</label>
											<input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="student_name" id="student_name" readonly style="border:none;">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-10">
											<label for="" class="col-md-5">Parent Type :</label>
											<input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="parent" id="parent" required>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-20">
											<label for="" class="col-md-5">Mobile 1:</label>
											<input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="mobileno" id="mobileno">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-20">
											<label for="" class="col-md-5">Mobile 2 :</label>
											<input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="mobileno2" id="mobileno2">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-20">
											<label for="" class="col-md-5">Mobile 3 :</label>
											<input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="mobileno3" id="mobileno3">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-20">
											<label for="" class="col-md-5">SMS No. :</label>
											<input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="sms_no" id="sms_no">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-20">
											<label for="" class="col-md-5">Emergency No :</label>
											<input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="emergency_no" id="emergency_no">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-20">
											<label for="" class="col-md-5">Whatsapp No :</label>
											<input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="wha_no" id="wha_no">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-20">
											<label for="" class="col-md-5">E-mail :</label>
											<input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="gmail" id="gmail">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-20">
											<label for="" class="col-md-5">Facebook :</label>
											<input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="facebook" id="facebook">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row margin-top-minus-20">
											<label for="" class="col-md-5">Twitter :</label>
											<input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="twitter" id="twitter">
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
		$('.rowToUpdateData').on('click', function() {
			var rowID = $(this).attr('data-name');
			$.ajax({
				url: "<?= base_url(); ?>index.php/MasterForm/getDBUserDataByID",
				method: "POST",
				dataType: "JSON",
				data: {
					id: rowID
				},
				success: function(data) {
					if (data != "fail") {
						$('#school_id').val(rowID);
						$('#usertype').val(data.spruser_usertype);
						$('#nameofuser').val(data.spruser_name);
						$('#mobileno').val(data.spruser_mobile);
						$('#username').val(data.spruser_username);
						$('#password').val(data.spruser_password);
						$('#email').val(data.spruser_email);

						$('#dynamicLabelAddButton').html("Update Data")
					}
				}
			});
		});
	</script>
	<script>
		$(document).ready(function() {
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
									html += '<a id="' + data[count].id + '" href="#" style="border:none; border-bottom: 1px solid #77777720; border-radius: 0px; padding: 5px; font-size: 12px;" class="list-group-item list-group-item-action getDataOfStudent"> <span class="text-info">' + data[count].SPRIDEN_surname + " " + data[count].SPRIDEN_student_name + " " + data[count].SPRIDEN_father_name + '</span><span class="text-danger"> ( Standard :  ' + data[count].SPRIDEN_admission_class + ' ) </span>' + '</a>';
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
			$('#student_id_hidden').val('');
			$('#surname').val('');
			$('#student_name').val('');
			$('#father_name').val('');
			$('#mother_name').val('');
			$('#aadhar_number').val('');

			//--------- Dynamically Change The LAbels ------
			$('#dynamic_name_list').html('');
			$('.search-list-container-panel').css('display', 'none');

			//--------- ----------------- -------------
			$('#parent').val('');
			$('#mobileno').val('');
			$('#sms_no').val('');
			$('#emergency_no').val('');
			$('#wha_no').val('');
			$('#gmail').val('');
			$('#facebook').val('');
			$('#twitter').val('');
			$('#mobileno2').val('');
			$('#mobileno3').val('');
		}

		function clearFormInputForData() {
			$('#parent').val('');
			$('#mobileno').val('');
			$('#sms_no').val('');
			$('#emergency_no').val('');
			$('#wha_no').val('');
			$('#gmail').val('');
			$('#facebook').val('');
			$('#twitter').val('');
			$('#mobileno2').val('');
			$('#mobileno3').val('');
		}
	</script>

	<script>
		$('#communicationForm').on('submit', function(e) {
			e.preventDefault();
			var ids = $('#student_id').val();
			if (ids != "") {
				$.ajax({
					url: "<?php echo base_url(); ?>index.php/Student/add_communication_dt",
					method: "POST",
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData: false,
					beforeSend: function() {
						$('#addDetailsFormButton').html('<i class="fas fa-spinner fa-spin"></i> Please Wait');
					},
					success: function(data) {
						if (data != "fail") {
							$('#addDetailsFormButton').html('<i class="fas fa-phone-volume"></i> <span id="dynamicLabelAddButton">Add Details</span>');
							clearFormInputForData();
							swal("Communication Details Added Successfully !!!");
						}
					}
				});
			} else {
				swal("Student ID Not Found");
				return false;
			}
		})
	</script>
</body>

</html>
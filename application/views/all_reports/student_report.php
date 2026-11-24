<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

	<title>Olive English School - Update Student Details</title>

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

	<?php include 'application/views/topNav.php'; ?>

	<!--Include Sidebar -->

	<?php include 'application/views/sidebar.php'; ?>



	<!--Include Top Navbar-->





	<!-- [ Main Content ] start -->

	<div class="pc-container">

		<form action="<?= base_url() ?>Report/add_report" method="post">

			<div class="pcoded-content">

				<!-- [ breadcrumb ] start -->

				<?php include 'application/views/Student/links.php'; ?>

				<!-- [ breadcrumb ] end -->

				<!-- [ Main Content ] start -->



				<div class="row margin-top-minus-40 mobile-margin-top-minus-40 mb-2">

					<div class="col-md-7">

						<a href="<?= base_url('Report/report_stats') ?>" class="btn btn-danger btn-sm"> <i
								class="fas fa-arrow-alt-circle-left"></i> Back</a>

						<button class="btn btn-secondary btn-sm" type="submit"> <i class="fas fa-user-graduate"></i>
							<span>Update Details</span></button>

					</div>

					<div class="col-md-2"></div>

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

						<div class="pc-wizard card" id="besicwizard">

							<div class="card-body">

								<div class="row justify-content-center">

									<div class="col-xl-12 col-md-12 mt-3">

										<div class="tab-content">

											<div class="tab-pane active show" id="b-w-tab1">

												<div class="row">

													<div class="col-md-6">

														<input type="hidden" name="header_id" id="header_id"
															value="<?= $record['id'] ?>">

														<div
															class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

															<label for="" class="col-md-4 text-left">Student ID <span
																	class="float-right">:</span></label>

															<input type="text" autocomplete="off"
																class="form-control form-control-sm col-md-4"
																name="student_id" readonly id="student_id"
																value="<?= $record['SPRIDEN_student_id'] ?>">

															<input type="hidden" name="action_type"
																value="<?= $action ?>">

														</div>

														<div
															class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

															<label for="" class="col-md-4 text-left">Name <span
																	class="float-right">:</span></label>

															<input type="text" autocomplete="off"
																class="form-control form-control-sm col-md-4"
																name="student_name" id="student_name"
																value="<?= $record['name'] ?>">

														</div>

														<div
															class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

															<label for="" class="col-md-4 text-left">Mobile <span
																	class="float-right">:</span></label>

															<input type="text" autocomplete="off"
																class="form-control form-control-sm col-md-4"
																name="stvpcnf_mobile" id="stvpcnf_mobile"
																value="<?= $record['mobile'] ?>">

														</div>

													</div>

													<div class="col-md-6">

														<div
															class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

															<label for="" class="col-md-4 text-left">Due Date <span
																	class="float-right">:</span></label>

															<input type="date" autocomplete="off"
																class="form-control form-control-sm col-md-4" required
																name="due_date" id="due_date"
																value="<?= $record['due_date'] ?>">

														</div>

														<div
															class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

															<label for="" class="col-md-4 text-left">Note <span
																	class="text-danger">*</span><span
																	class="float-right">:</span></label>

															<input type="text" autocomplete="off"
																class="form-control form-control-sm col-md-4"
																name="note" id="note" value="<?= $record['note'] ?>">

														</div>

														<div
															class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">

															<label for="" class="col-md-4 text-left">Whatsapp No <span
																	class="float-right">:</span></label>

															<input type="text" autocomplete="off"
																class="form-control form-control-sm col-md-4"
																name="stvpcnf_whatsapp" id="stvpcnf_whatsapp"
																value="<?= $record['whatsapp'] ?>">

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

									<?php

									$fees = 0;

									if ($record['STVCLAS_standard'] == "Nursery" || $record['STVCLAS_standard'] == 'Jr.Kg.') {

										$fees = 7000;
									} elseif ($record['STVCLAS_standard'] == "Sr.Kg.") {

										$fees = 8000;
									} else {

										$fees = 10000;
									}


									// $bal = $fees - ($record['exumtion'] + $record['total_fees']);
									$bal = $record['bharna'];



									if ($bal == 10000) {

										$color = 'text-danger';
									} else if ($bal > 1000 && $bal < 10000) {

										$color = 'text-info';
									} else {

										$color = 'text-success';
									}

									?>
									<hr>

									<div class="row ">
										<!-- <div class="row print "> -->
										<div class="col-md-12 print-box">
											<h5 class="text-center">Fees Alert Message</h5>

											<!-- <p class="text-center font-weight-bold">नोटिस </p> -->
											<p style="font-size: 20px;">
												अस्सलामु अलैकुम पैरेंट्स,<br>
												आपका बच्चा / बच्ची <span
													class="font-weight-bold"><?= $record['name'] ?></span> STD. <span
													class="font-weight-bold"><?= $record['standard'] ?></span> में पढाई
												करता / करती है. उसकी फीस आपने <span
													class="font-weight-bold"><?= $record['total_fees_paid'] ?></span>
												रुपये जमा की है. इस महीने की क़िस्त <span
													class="font-weight-bold"><?= $record['bharna'] ?></span> रूपये जमा
												करवाना बाकि है. इसीलिए जल्द से जल्द उसकी फीस स्कूल में आकर जमा करवा
												दीजिये ताकी उसकी पढाई में कोई दिक्कत ना हो।
											</p>

										</div>

										<!-- </div> -->
										<!-- <div class="row print  "> -->
										<div class="col-md-12 print-box">
											<!-- <p class="text-center font-weight-bold">Notice </p> -->
											<p style="font-size: 20px;">
												Assalamualaikum Parents,<br>
												Your Son/Daughter <span
													class="font-weight-bold"><?= $record['name'] ?></span> studies in
												STD. <span class="font-weight-bold"><?= $record['standard'] ?></span>.
												Till now you have paid Fees Amount of Rs <span
													class="font-weight-bold"><?= $record['total_fees_paid'] ?></span>.
												This months fees installment of Rs <span
													class="font-weight-bold"><?= $record['bharna'] ?></span> is yet to
												be paid. So please pay the remaining fees amount of your child as soon
												as possible to avoid inconvenience in his/her studies.
											</p>
											<hr>
										</div>

										<!-- </div> -->
									</div>

								</div>

							</div>

						</div>

						<style>
							#id-alert-row {

								display: none;

							}
						</style>

						<div class="row" id="id-alert-row">

							<div class="col-md-12">

								<div class="card pb-0 mb-0" style="background-color: #99999910;">

									<div class="card-body p-3 mb-0 pb-0">

										<p class="pb-0 mb-0" id="id-alert-text">Please Set Academic year First !!!</p>

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

	<!-- <script>

		$('#findStudent').click(function() {

			var id = $('#search_student_name').val().trim();

			if (id != '') {

				$.ajax({

					url: "<?= base_url('Student/StudentUniqueId') ?>?id=" + id,

					method: "GET",

					dataType: "JSON",

					success: function(data) {

						if (data != null) {

							window.location = '<?= base_url('student/update_details/') ?>' + data.id;

						} else {

							alert("Data Not Available");

						}

					}

				});

			}



		});

	</script> -->

</body>



</html>
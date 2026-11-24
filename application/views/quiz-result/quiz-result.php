<?php

defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Kolkata');



?>

<!DOCTYPE html>

<html lang="en">



<head>

	<title>Olive English School -</title>

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<meta name="description" content="" />

	<meta name="keywords" content="">

	<meta name="author" content="Phoenixcoded" />



	<!---Include CSS Files-->

	<!-- Favicon icon -->

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous" />

	<style>
		.d-flex {
			display: flex;
			flex-wrap: wrap;

		}

		.item {
			flex-basis: 50%;
		}

		.b-color {
			background-color: #fff !important;
			border: none;


		}
	</style>
</head>

<body style="background: #fbfbfb;">
	<section>
		<div class="container">

			<!-- =================================1==================================================== -->

			<div class="row mt-4">

				<div class="col-md-12">

					<div class="card table-card myfeesCard">

						<div class="card-header borderless fees-card-bg text-white" style="background-color: #0097a7;">

							<h5 class="text-white">Personal Details</h5>

						</div>

						<style>
							@media screen and (max-width: 600px) {

								div.text {

									font-size: 10px;

								}

							}
						</style>

						<div class="card-body pb-0">

							<style>
								#feesTable tr td,

								#feesTable tr th {

									padding: 5px;

									font-size: 12px;

									background-color: #E0F7FA;

									color: #006064;

									border-color: #80CBC4;

								}
							</style>

							<div class="form-group row d-flex ">
								<label for="studentid" class="col-sm-3 col-md-2 col-form-label">Student Id <span class="float-right">:</span></label>
								<div class="col-sm-6 col-md-4">
									<input type="text" class="form-control b-color" name="studentid" id="studentid" value="<?= $record['student_id'] ?>" disabled>
								</div>
							</div>




							<div class="form-group row d-flex">
								<label for="name" class="col-sm-3 col-md-2 col-form-label">Student Name<span class="float-right">:</span></label>
								<div class="col-sm-9 col-md-10">
									<input type="text" name="name" id="name" class="form-control b-color" value="<?= $record['name'] ?>" readonly>
								</div>


							</div>




							<div class="form-group row d-flex">
								<label for="class" class="col-sm-3 col-md-2 col-form-label">Class<span class="float-right">:</span></label>
								<div class="col-sm-9 col-md-4">
									<input type="text" name="class" id="class" class="form-control b-color" value="<?= $record['class'] ?>" readonly>
								</div>


								<label for="div" class="col-sm-3 col-md-2 col-form-label">Division<span class="float-right">:</span></label>
								<div class="col-sm-9 col-md-4">
									<input type="text" name="div" id="div" class="form-control b-color" value="<?= $record['division'] ?>" readonly>
								</div>
							</div>




							<div class="form-group row d-flex">
								<label for="qname" class="col-sm-3 col-md-2 col-form-label">Quiz Name<span class="float-right">:</span></label>
								<div class="col-sm-9 col-md-4">
									<input type="text" name="qname" id="qname" class="form-control b-color" value="<?= $record['quiz_name'] ?>" readonly>
								</div>
								<label for="score" class="col-sm-3 col-md-2 col-form-label">Score<span class="float-right">:</span></label>
								<div class="col-sm-9 col-md-4">
									<input type="text" name="score" id="score" class="form-control b-color" value="<?= $record['score'] ?>" readonly>
								</div>
							</div>



						</div>

					</div>

				</div>

			</div>

			<!-- ==================================1=================================================== -->



			<!-- ==================================2=================================================== -->

			<div class="row mt-3">

				<div class="col-md-12">

					<div class="card table-card myfeesCard">

						<div class="card-header borderless fees-card-bg text-white" style="background-color: #0097a7;">

							<h5 class="text-white">Result</h5>

						</div>

						<style>
							@media screen and (max-width: 600px) {

								div.text {

									font-size: 10px;

								}

							}
						</style>

						<div class="card-body pb-0">

							<style>
								#feesTable tr td,

								#feesTable tr th {

									padding: 5px;

									font-size: 12px;

									background-color: #E0F7FA;

									color: #006064;

									border-color: #80CBC4;

								}
							</style>

							<?php $i = 1; ?>
							<?php foreach ($que_data as $question) : ?>
								<div class="table-responsive" id="table-res-fees" style="border-bottom: 1px solid #c1c1c1; margin-top: 10px;">

									<div>
										<h5>
											<?= $i ?>.
											<?= $question['text'] ?>
										</h5>
									</div>


									<div class="container d-flex">
										<?php $options = $question['options']; ?>
										<?php for ($j = 0; $j < 4; $j++) :

										?>

											<?php $option = isset($options[$j]) ? $options[$j] : null;

											$color = '';
											$correct_option_id = $option['is_correct'] == 1 ? $option['option_id'] : null;
											$correct_class = $option['is_correct'] == 1 ? 'text-primary' : '';
											if ($option['selected_option'] == $correct_option_id) {
												$color = 'text-success';
											} else if($option['selected_option'] == $option['option_id'] && $correct_option_id != $option['option_id'] ) {
												$color = 'text-danger';
											} ?>
											<!-- <div class="row  "> -->
											<div class="col-md-6 item" <?= $j % 2 != 0 ? 'style="display:none;"' : '' ?> id="<?= $j ?>">
												<?php if ($j % 2 == 0) : ?>
													<input class="col-md-1" <?= $option['selected_option'] == $option['option_id'] ? 'checked' : '' ?> type="radio" value="<?= $option['option_id'] ?>">
													<label for="" class="<?= $color ?> <?= $correct_class ?> font-weight-bold">
														<?= $option['optionText'] ?>
													</label>
												<?php else : ?>
											</div>



											<div class="col-md-6 item" id="<?= $j ?>">

												<input class="col-md-1" <?= $option['selected_option'] == $option['option_id']  ? 'checked' : '' ?> type="radio" value="<?= $option['option_id'] ?>">
												<label class="<?= $color ?> <?= $correct_class ?> font-weight-bold float-end" for="">
													<?= $option['optionText'] ?>
												</label>
											<?php endif; ?>
											</div>
											<!-- </div> -->
										<?php endfor; ?>
									</div>

								</div>

								<?php $i++; ?>
							<?php endforeach; ?>

						</div>

					</div>

				</div>

			</div>
			<!-- ==================================2=================================================== -->


		</div>

	</section>
	<!-- -----comtainer end----- -->



	<!-- <div class="container mt-3">
		<div class="card">
			<div class="bg-success text-white text-center">
				<h2>Result</h2>
			</div>
			<?php $i = 1; ?>
			<?php foreach ($que_data as $question) : ?>
				<div class="card-body">
					<div>
						<h4>
							<?= $i ?>.
							<?= $question['text'] ?>
						</h4>
					</div>
					<div class="container d-flex">
						<?php $options = $question['options']; ?>
						<?php for ($j = 0; $j < 4; $j++) : ?>

							<?php $option = isset($options[$j]) ? $options[$j] : null; ?>
						
							<div class="col-md-6 item" <?= $j % 2 != 0 ? 'style="display:none;"' : '' ?> id="<?= $j ?>">
								<?php if ($j % 2 == 0) : ?>
									<input class="col-md-1" <?= $option['option_checked'] == 1 ? 'checked' : '' ?> type="radio" value="">
									<label <?= $option['is_correct'] == 1 ? 'style="color:green;"' : ($option['option_checked'] == 1 ? 'style="color:red;"' : '') ?> for="">
										<?= $option['optionText'] ?>
									</label>
								<?php else : ?>
							</div>

							<div class="col-md-6 item" id="<?= $j ?>">

								<input class="col-md-1" <?= $option['option_checked'] == 1 ? 'checked' : '' ?> type="radio" value="">
								<label <?= $option['is_correct'] == 1 ? 'style="color:green;"' : ($option['option_checked'] == 1 ? 'style="color:red;"' : '') ?> class="float-end" for="">
									<?= $option['optionText'] ?>
								</label>
							<?php endif; ?>
							</div>
							
						<?php endfor; ?>
					</div>
				</div>
				<?php $i++; ?>
			<?php endforeach; ?>

		</div>
	</div> -->


</body>



</html>
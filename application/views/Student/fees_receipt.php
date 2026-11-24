<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Fees Receipt - <?= $profile['name'] ?></title>
	<style>
		.table {
			border-collapse: collapse;
			width: 100%;
		}

		.text-center {
			text-align: center;
		}

		.text-right {
			text-align: right;
			padding: 2px;
		}

		.font-normal {
			font-weight: normal;
		}

		.col-first-head {
			width: 15%;
		}

		.col-second-head {
			width: 85%;
		}

		.mt-2 {
			margin-top: 20px;
		}

		.mt-1 {
			margin-top: 10px;
		}

		.bordered tr td {
			border: 1px solid #77777710;
			font-size: 12px;
		}
		.main-box {

display: flex;

flex-direction: row;

justify-content: center;

width: 85mm;

height: 54mm;

border: 1px solid #77777750;

}

		img {
			height: 50px;
		}
	</style>
</head>

<body>
	<div style="width: 100%;" class="row">

		<div style="border: 1px solid #777777; width:49%; float: left;">
			<table class="table ">
				<tr>
					<td class="text-center">
						<img src="https://school.oliveschool.org.in/images/logoNewDev.png" alt="" srcset="">
						<h4>Olive English School & Junior College</h4>
						<h6 class="font-normal">Fees Transaction Receipt</h6>

					</td>
				</tr>
				<tr>
					<td class="text-right" style="font-size:10px; font-weight: bold;">Date : <?= date('d-M-Y') ?></td>
				</tr>
				<tr>
					<td>
						<table class="table" style="margin-top:20px;">
							<tr>
								<!-- <td class="col-first-head">Student ID <span style="float: right;">:</span>
							</td> -->
								<td class="col-second-head" style="font-size: 12px;"><?= $profile['SPRIDEN_student_id'] ?></td>
							</tr>
							<tr>
								<!-- <td class="col-first-head">Student Name <span style="float: right;">:</span> </td> -->
								<td class="col-second-head" style="font-size: 12px;"><?= $profile['name'] ?></td>
							</tr>
							<tr>
								<!-- <td class="col-first-head">Class <span style="float: right;">:</span> </td> -->
								<td class="col-second-head" style="font-size: 12px;"><?= $profile['class'] ?></td>
							</tr>
							<!-- <tr>
							<td class="col-first-head">Total Fees <span style="float: right;">:</span> </td>
							<td class="col-second-head"><?= number_format($profile['total_fees']) ?></td>
						</tr> -->
						</table>
					</td>
				</tr>
				<tr>
					<td class="text-center">
						<h6 class="mt-2 ">Fees Amount Paid</h6>
						<table class="table bordered mt-1">
							<tr>
								<td class="">#</td>
								<td class="">Amount</td>
								<td class="">Date</td>
								<!-- <td class="">Due Date</td>
							<td class="">Source</td> -->
								<td class="">Rec By</td>
								<!-- <td class="">Note</td> -->
							</tr>
							<?php $i = 1;
							foreach ($fees_data as $fees) : ?>
								<tr>
									<td class=""><?= $i; ?></td>
									<td class=""><?= number_format($fees['sprfees_amount']) ?></td>
									<td class=""><?= $fees['sprfees_feesdate'] ?></td>
									<!-- <td class=""><?= $fees['sprfees_due_date'] == '' || $fees['sprfees_due_date'] == '0000-00-00' ? '' : date('d-M-Y', strtotime($fees['sprfees_due_date'])) ?></td> -->
									<!-- <td><?= $fees['sprfees_source'] ?></td> -->
									<td><?= $fees['rec_by'] ?></td>
									<!-- <td><?= $fees['sprfees_notes'] ?></td> -->
								</tr>
							<?php $i++;
							endforeach; ?>
						</table>
					</td>
				</tr>
				<!-- <tr>
				<td style="padding-top:30px;">
					<table class="table">
						<tr>
							<td style="width: 100%; text-align: right;">
								<p>---------------------------</p>
								<h6>Receiver Signature</h6>
							</td>
						</tr>
					</table>
				</td>
			</tr> -->
			</table>
		</div>
		<div style="border: 1px solid #777777; width:49%; float: right;">

			<table class="table">
				<tr>
					<td class="text-center float-right">
						<img src="https://school.oliveschool.org.in/images/logoNewDev.png" alt="" srcset="">
						<h4>Olive English School & Junior College</h4>
						<h6 class="font-normal">Fees Transaction Receipt</h6>

					</td>
				</tr>
				<tr>
					<td class="text-right" style="font-size:10px; font-weight: bold;">Date : <?= date('d-M-Y') ?></td>
				</tr>
				<tr>
					<td>
						<table class="table" style="margin-top:20px;">
							<tr>
								<!-- <td class="col-first-head">Student ID <span style="float: right;">:</span>
							</td> -->
								<td class="col-second-head" style="font-size: 12px;"><?= $profile['SPRIDEN_student_id'] ?></td>
							</tr>
							<tr>
								<!-- <td class="col-first-head">Student Name <span style="float: right;">:</span> </td> -->
								<td class="col-second-head" style="font-size: 12px;"><?= $profile['name'] ?></td>
							</tr>
							<tr>
								<!-- <td class="col-first-head">Class <span style="float: right;">:</span> </td> -->
								<td class="col-second-head" style="font-size: 12px;"><?= $profile['class'] ?></td>
							</tr>
							<!-- <tr>
							<td class="col-first-head">Total Fees <span style="float: right;">:</span> </td>
							<td class="col-second-head"><?= number_format($profile['total_fees']) ?></td>
						</tr> -->
						</table>
					</td>
				</tr>
				<tr>
					<td class="text-center">
						<h6 class="mt-2 ">Fees Amount Paid</h6>
						<table class="table bordered mt-1">
							<tr>
								<td class="">#</td>
								<td class="">Amount</td>
								<td class="">Date</td>
								<!-- <td class="">Due Date</td> -->
								<!-- <td class="">Source</td> -->
								<td class="">Rec By</td>
								<!-- <td class="">Note</td> -->
							</tr>
							<?php $i = 1;
							foreach ($fees_data as $fees) : ?>
								<tr>
									<td class=""><?= $i; ?></td>
									<td class=""><?= number_format($fees['sprfees_amount']) ?></td>
									<td class=""><?= $fees['sprfees_feesdate'] ?></td>
									<!-- <td class=""><?= $fees['sprfees_due_date'] == '' || $fees['sprfees_due_date'] == '0000-00-00' ? '' : date('d-M-Y', strtotime($fees['sprfees_due_date'])) ?></td> -->
									<!-- <td><?= $fees['sprfees_source'] ?></td> -->
									<td><?= $fees['rec_by'] ?></td>
									<!-- <td><?= $fees['sprfees_notes'] ?></td> -->
								</tr>
							<?php $i++;
							endforeach; ?>
						</table>
					</td>
				</tr>
				<!-- <tr>
				<td style="padding-top:30px;">
					<table class="table">
						<tr>
							<td style="width: 100%; text-align: right;">
								<p>---------------------------</p>
								<h6>Receiver Signature</h6>
							</td>
						</tr>
					</table>
				</td>
			</tr> -->
			</table>
		</div>
	</div>
</body>

</html>

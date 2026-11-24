<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/email-template.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css2?family=EB+Garamond&display=swap" rel="stylesheet">
</head>

<body>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8 mt-5">
				<div class="card mt-5">
					<div class="card-header text-center">
						<h5 class="text-white"> <img src="<?= base_url(); ?>images/logoNewDevWhite.png" alt=""> Olive English School - Fees Details</h5>
					</div>
					<div class="card-body">
						<table class="table my-table">
							<tr>
								<td class="text-right">Student Name : </td>
								<td class="">Shaikh Ajimoddin</td>
							</tr>
							<tr>
								<td class="text-right">Class : </td>
								<td class="">VI - A</td>
							</tr>
							<tr>
								<td class="text-right">Amount : </td>
								<td class="">4000</td>
							</tr>
							<tr>
								<td class="text-right">Fees Status : </td>
								<td class="">Full</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>



	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
</body>

</html>

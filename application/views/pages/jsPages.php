<!-- Required Js -->

<script src="<?php echo base_url(); ?>assets/js/vendor-all.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/plugins/feather.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/pcoded.min.js"></script>


<script src="<?php echo base_url(); ?>assets/js/plugins/clipboard.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/uikit.min.js"></script>



<!-- Apex Chart -->

<script src="<?php echo base_url(); ?>assets/js/plugins/apexcharts.min.js"></script>

<!-- custom-chart js -->

<script src="<?php echo base_url(); ?>assets/js/pages/dashboard-sale.js"></script>



<!-- sweet alert Js -->

<script src="<?php echo base_url(); ?>assets/js/plugins/sweetalert.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/pages/ac-alert.js"></script>



<!--Wizard-->

<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.bootstrap.wizard.min.js"></script>

<!--Custom JS-->

<script src="<?php echo base_url(); ?>assets/js/custom.js"></script>

<script src="<?php echo base_url(); ?>assets/js/custom-autocomplete-list.js"></script>



<!-- datatable Js -->

<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/plugins/dataTables.bootstrap4.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/pages/data-basic-custom.js"></script>





<!--Script to Logout From Dashboard Using ajax-->

<script>
	function clearOutput() {

		$('#search_form_name_list').val('');

		$('#dynamic_formname_list').html('');

		$('.list-container-list-holder').css('display', 'none');

	}
</script>

<script>
	$(document).ready(function() {

		$('.logoutEvent').on('click', function() {

			swal({

					title: "Logout",

					text: "Are You Sure to Log out of Dashboard ?",

					icon: "warning",

					buttons: true,

					dangerMode: true,

				})

				.then((willDelete) => {

					if (willDelete) {

						$.ajax({

							url: "<?php echo base_url(); ?>index.php/Welcome/logout",

							success: function(data) {

								if (data != "fail") {

									window.location = data;

								} else {

									swal("Failed to logout");

								}

							}

						});

					} else {



					}

				});

		});

		$('#clear_search_form_Data').click(function() {

			clearOutput();

		});

		$(document).on('click', '.noDataOfAvail', function() {

			clearOutput();

		});

		$('#search_form_name_list').keyup(function(e) {

			var base_url = '<?php echo base_url(); ?>';

			var form_Name = $(this).val().trim();

			var form_name1 = $('#search_form_name_list').val().trim();

			if (form_Name != "") {

				if (e.key === 'Enter' || e.keyCode === 13) {

					$.ajax({

						url: "<?php echo base_url(); ?>index.php/Stats/openForm",

						data: {

							form_id: form_name1

						},

						method: "POST",

						success: function(data) {

							if (data != "fail" && data != "noaccess") {

								window.location = base_url + data;

							} else if (data == "noaccess") {

								swal("You Don't have Access to This Form");

								$(this).val("");

							} else {

								swal("Form not exist with This name");

								$(this).val("");

							}

						}

					});

				} else {

					$.ajax({

						url: "<?php echo base_url(); ?>index.php/Stats/getAllFormsNames",

						data: {

							form_Name: form_Name

						},

						dataType: "JSON",

						method: "POST",

						success: function(data) {

							if (data != '') {

								var html = '';

								for (var count = 0; count < data.length; count++) {

									html += '<a id="' + data[count].stvfrms_url_name + '" href="#" style="border:none; border-bottom: 1px solid #77777720; border-radius: 0px;" class="list-group-item list-group-item-action openFormForAccess p-2"> <span class="text-info">' + data[count].stvfrms_url_name + '</span><span class="text-danger"> - ' + data[count].stvfrms_form_name + ' </span>' + '</a>';

								}

								$('#dynamic_formname_list').html(html);

								$('.list-container-list-holder').css('display', 'block');

							} else {

								var notAvailable = '<a href="#" style="border-radius: 0px; border:none; border-bottom: 1px solid #77777720;" class="list-group-item list-group-item-action noDataOfAvail"> <span class="text-info">Data Not Available</span></a>';

								$('#dynamic_formname_list').html(notAvailable);

							}

						}

					});

				}

			} else {

				clearOutput();

			}



		});

		$(document).on('click', '.openFormForAccess', function() {

			var form_id = $(this).attr('id');

			var base_url = '<?php echo base_url(); ?>';

			var action = 0;

			$.ajax({

				url: "<?php echo base_url(); ?>index.php/Stats/openForm",

				data: {

					form_id: form_id

				},

				method: "POST",

				success: function(data) {

					if (data != "fail" && data != "noaccess") {

						window.location = base_url + data;

					} else if (data == "noaccess") {

						swal("You Don't have Access to This Form");

						$(this).val("");

					} else {

						swal("Form not exist with This name");

						$(this).val("");

					}

				}

			});

		});



	});
</script>

<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>

<script>
		$(document).ready(function() {
    $('#simpletable').DataTable( {
        dom: 'Bfrtip',
		"pageLength": 25,
        buttons: [
            'excel', 'pdf'
        ]
    } );
} );
</script>

<script>

	function getFullScreenElement() {

		return document.fullscreenElement ||

			document.webkitFullscreenElement ||

			document.mozFullscreenElement ||

			document.msFullscreenElement;

	}



	function toggleFullscreen() {

		if (getFullScreenElement()) {

			document.exitFullscreen();

		} else {

			document.documentElement.requestFullscreen().catch(console.log);

		}

	}

	document.getElementById("getFullScreenMode").addEventListener("click", () => {

		toggleFullscreen();

	});



	$(document).on('click', '.view-student-all-details-by-id', function() {

		var ids = $(this).attr("data-student-id");

		window.location = "<?= base_url(); ?>Student/view_student_details/" + ids;

	});

	$(document).on('click', '.go-back', function() {

		window.location = "<?= base_url(); ?>";

	});



</script>

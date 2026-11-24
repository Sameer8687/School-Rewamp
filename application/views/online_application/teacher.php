<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Olive English School - Issue LC</title>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui"
    />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Phoenixcoded" />
    <!---Include CSS Files-->
    <?php include 'application/views/cssPages.php'; ?>
    <link
      rel="stylesheet"
      href="<?php echo base_url(); ?>assets/css/id-card.css"
    />
    <style>
      .pc-container {
        top: 0px !important;
      }
      .border {
        border: 1px solid;
      }

      .table tr td,
      .table tr th {
        padding: 5px !important;
        font-size: 11px !important;
        cursor: pointer;
      }
    </style>
  </head>

  <body class="">
    <div class="loader-bg">
      <div class="loader-track">
        <div class="loader-fill"></div>
      </div>
    </div>

    <?php include 'application/views/sidebar.php'; ?> <?php include
    'application/views/topNav.php'; ?>
    <div class="pc-container">
      <div class="pcoded-content">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">New Teacher Application</h5>
              </div>
              <div class="card-body p-2">
                <div class="print-container">
                  <div class="col-md-12">
                    <div class="row mb-3">

						<!-- 1 -->
                      	<div class="col-md-4">
                      		<div class="row">
                      		  <label for="" class="col-md-5">From Date <span class="float-right">:</span></label>
							  
							  <input class="form-control form-control-sm col-md-7" type="date" name="from_date" id="from_date" value="">

                      		</div>
                      	</div>

						<!-- 2 -->
                      	<div class="col-md-4">
                      		<div class="row">
                      		  <label for="" class="col-md-5">To Date <span class="float-right">:</span></label>
                         
<input class="form-control form-control-sm col-md-7" type="date" name="to_date" id="to_date" value="">

                      		</div>
                      	</div>

                      <!-- 3 -->
                      	<div class="col-md-4">
						
<button class="btn btn-sm btn-outline-secondary" type="button" id="view_button">View</button>

                      	</div>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div
                      class="dt-responsive table-responsive margin-top-minus-10">
                      <table id="simpletable" data-custom="true" class="table table-striped table-bordered nowrap">
                        <thead>
                          <tr>
                                                    <th>#</th>
                                                    <th>Applicaiton ID</th>
                                                    <th>Full Name</th>
                                                    <th>City</th>
                                                    <th>Status</th>
                                                    <th>Phone</th>
                                                    <th>Action</th>
                                                </tr>
                        </thead>

                        <tbody id="tbody">
                          <?php $i = 1;

                                                // print_r($application);
                                                foreach ($applications as $app) : ?>
                                                    <tr>
                                                        <td><?= $i ?></td>

                                                        <td>
                                                            <?= $app['application_id'] ?>
                                                        </td>
                                                        <td>
                                                            <div><?= $app['full_name'] ?></div>
                                                            <small class="text-primary">(<?= $app['email'] ?>)</small>
                                                        </td>

                                                        <td><?= $app['city'] ?>, <?= $app['state'] ?></td>

                                                        <td class="text-center"><span class="badge text-white bg-<?= $app['application_status'] == 'Draft' ? 'danger' : 'success' ?>"><?= $app['application_status'] ?></span></td>
                                                        <td><?= $app['phone'] ?></td>
                                                        <td>
                                                            <div class="">
                                                                <i class="fa fa-edit cursor-pointer text-primary" onclick="UpdateStatus('<?= $app['id'] ?>','<?= $app['application_status'] ?>','<?=$app['comments']?>')"></i>
                                                                <i class="fa fa-eye mx-2 text-info cursor-pointer" onclick="window.location.href = '<?= base_url('Online_Application/list/' . $app['id']) ?>'"></i>
                                                                <i class="fa fa-trash text-danger cursor-pointer" onclick="DelApp('<?= $app['id'] ?>','<?= $app['application_id'] ?>')"></i>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php $i++;
                                                endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
     <div class="modal fade" id="updateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5 text-center" id="staticBackdropLabel">Update Status</h1>
                        <i class="fa fa-plus mt-2" onclick="$('#updateModal').modal('hide')"></i>
                </div>
                <div class="modal-body">
                    <form onsubmit="UpdateStat(event)">
                        <input type="hidden" name="teacher_id" id="teacher_id" value="">
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <p><strong>Status</strong></p>
                            </div>
                            <div class="col-md-1">
                                <strong>:</strong>
                            </div>
                            <div class="col-md-6">
                                <select name="status" id="status" class="form-control ">
                                    <option value="Selected">Selected</option>
                                    <option value="Draft">Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Comment</strong></p>
                            </div>
                            <div class="col-md-1">
                                <strong>:</strong>
                            </div>
                            <div class="col-md-6">
                                <textarea name="comment" class="form-control" id="comment"></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <p><strong>Action</strong></p>
                            </div>
                            <div class="col-md-1">
                                <strong>:</strong>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-sm w-100 btn-primary" type="submit" id="update_btn">Update</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>

    <script>
        $("#table-gr").DataTable({
            "pageLength": 25,
            dom: "Bfrtip",
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    </script>

    <script>
        function UpdateStat(event) {
            event.preventDefault();
            var formData = new FormData(event.target);
            $.ajax({
                url: "<?= base_url('Online_Application/update_status') ?>",
                method: 'POST',
                data: formData,
                dataType: 'JSON',
                processData: false,
                contentType: false,
                success: function(res) {
                    // Handle the response
                    if (res['status'] == 1) {
                        alert("Appliation Status is updated");
                        window.location.reload()
                    } else {
                        alert("Application status is not update please try again.....")
                    }
                },
                error: function(xhr, status, error) {
                    // Handle any errors that may occur
                    console.error(xhr.responseText);
                }
            });
        };
    </script>

    <script>
        function DelApp(id, application_id) {
            if (confirm("Do you want to delete this type of Applcation " + '(' + application_id + ')')) {
                $.ajax({
                    url: "<?= base_url('Online_Application/delete_application') ?>",
                    method: "GET",
                    data: {
                        id
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res['status'] == 1) {
                            alert("Application is Deleted")
                            window.location.reload();
                        } else if (res['status'] == 0) {
                            alert("System has error please try again....")
                        }
                    }
                })
            } else {
                alert("You cancled deleting application")
            }
        }
    </script>

    <script>
        function UpdateStatus(id, status, comment) {
            $("#updateModal").modal('show');
            let teacher_id = document.getElementById('teacher_id').value = id;
            let modal_status = document.getElementById('status')
            document.getElementById('comment').value = comment
            modal_status.value = status;
        }
    </script>

    <script>
        var view_button = document.getElementById("view_button");
        let base_url = '<?= base_url() ?>';
        view_button.addEventListener("click", function() {
            // Retrieve the value of the 'type' select element inside the event listener
            var typess = document.getElementById("type").value;
            var from_d = document.getElementById("from_date").value;
            var to_d = document.getElementById("to_date").value;
            console.log(typess);
            console.log(from_d);
            console.log(to_d);
            window.location = `${base_url}Report/login_tracking?type=${typess}&from=${from_d}&to=${to_d}`;
        });
    </script>
  </body>
</html>
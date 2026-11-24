<link rel="stylesheet" href="<?= base_url('assets/css/new/fees_form.css'); ?>">
<div class="row">
    <div class="col-md-12 ps-3" id="mainContent">
        <form action="<?php echo base_url(); ?>index.php/Student/add_fees_data" method="post">
            <div class="card">
                <div class="row p-3" style="color:#060555;">
                    <div class="col-md-3">
                        <h5><span id="display_name">Fees Details</span></h5>
                    </div>
                    <div class="col-md-7">

                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary btn-sm" disabled id="add-fees-btn"> <i class="fas fa-dollar-sign"></i> <span id="dynamicLabelAddButton">Add Fees Data</span></button>
                    </div>


                </div>

                <div class="row ps-3">
                    <div class="col-md-12">
                        <h6><span id="display_details"></span></h6>

                    </div>
                </div>
                <div class="row desktop d-none ">

                    <div class="col-md-12">

                        <label style="text-align: left;" for="" class="">Total Fees :<span class="text-primary" id="dynamic_total_fees_label_sm" style="font-weight: 600;"></span> Rs. </label>

                        <label style="text-align: left; float: right;" for="" class="">Balance Fees :<span class="text-primary" id="dynamic_fees_label_sm" style="font-weight: 600;"></span> Rs. </label>

                    </div>

                </div>
                <div class="row p-3">
                    <div class="col-md-12">
                        <div class="row d-flex p-1">
                            <div class="col-md-2">Academic Year</div>
                            <div class="col-md-2">
                                <select name="academic_year_hidden" id="academic_year_hidden" required class="form-control form-control-sm cust-border-bottom col-md-3" onchange="update_fees_form()" data-mode="initial">
                                    <?php foreach ($academic_years as $acdm) : ?>
                                        <option <?= $max_acdm['max_acdm'] == $acdm['stvacdm_id'] ? 'selected' : '' ?> value=<?= $acdm['stvacdm_id'] ?>> <?= $acdm['stvacdm_year'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-2">
                                <label>Balance Fees</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" readonly class="form-control form-control-sm" name="dynamic_fees_label" id="dynamic_fees_label" style="background-color:#f8f8f8">
                            </div>


                        </div>



                        <div class="row d-flex p-1">
                            <div class="col-md-2">
                                <label>Total Fees</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" readonly class="form-control form-control-sm" name="dynamic_total_fees_label" id="dynamic_total_fees_label" style="background-color:#f8f8f8">
                            </div>
                            <div class="col-md-2"></div>

                            <div class="col-md-2">
                                <label>Concession / Other</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control form-control-sm" name="con_amnt" id="con_amnt">
                            </div>


                        </div>



                        <div class="row d-flex p-1">
                            <div class="col-md-2">
                                <label>Concession Note</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control form-control-sm" name="con_note" id="con_note">
                            </div>
                            <div class="col-md-2"></div>

                            <div class="col-md-2">
                                <label>Amount Paid</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control form-control-sm" name="amount_paid" id="amount_paid">
                            </div>


                        </div>



                        <div class="row d-flex p-1">
                            <div class="col-md-2">
                                <label>Details</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control form-control-sm" name="details" id="details">
                            </div>
                            <div class="col-md-2"></div>

                            <div class="col-md-2">
                                <label>Due Date</label>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control form-control-sm" name="due_date" id="due_date">
                            </div>


                        </div>
                        <div class="row d-flex p-1">
                            <div class="col-md-2">

                                <label>Payment Mode</label>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control form-control-sm col-md-3" required name="payment_mode" id="payment_mode">
                                    <option value="">Select</option>
                                    <option selected value="cash">Cash</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-2">
                                <label>Fees Card Issued</label>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" autocomplete="off" class="update-book-issue" name="book_issues" id="book_issues_yes" value="Yes"> Yes

                                <input value="No" type="radio" autocomplete="off" class="update-book-issue" name="book_issues" id="book_issues_no"> No <br>

                                <small class="font-weight-bold text-danger" id="card-loader-small"></small>
                            </div>
                        </div>
                        <div class="row d-flex p-1">

                            <div class="col-md-2">
                                <label>Generate Receipt</label>
                            </div>
                            <div class="col-md-2">

                                <a class="text-success" href="javascript:void(0)" id="gen-rec">Generate Fees Receipt</a>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- ======================== HIDDEN INPUTS =========================== -->
                <input type="hidden" class="form-control form-control-sm col-md-7" required name="student_id" id="student_id" readonly>

                <input type="hidden" class="form-control form-control-sm col-md-7" name="student_id_hidden" id="student_id_hidden" readonly>

                <input type="hidden" class="form-control form-control-sm col-md-7" required name="student_standard" id="student_standard" readonly>

                <input type="hidden" class="form-control form-control-sm col-md-7" name="student_name" id="student_name" readonly>
            </div>
        </form>


        <div class="row mt-2">
            <div class="col-md-12">
                <div class="card ">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr class="" >
                                <th>#</th>
                                <th>Year</th>
                                <th>Class</th>
                                <th>Total Fees</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Exumption</th>
                                <th>Class</th>
                                <th>Fees</th>
                                <th>Date</th>
                                <th>Exem.</th>
                                <th>Paid To</th>
                            </tr>
                        </thead>
                        <tbody id="fees_tbody" class="">
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
    <?php include_once('application/views/pages/includes/sidebar.php') ?>
</div>

<style>
    .simple {
        overflow-x: auto;
    }
</style>


<!-- ----- MODAL ----- -->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 113% !important;">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Previous Fees</h1>
            </div>
            <div class="modal-body" id="modal-body">
                <div class="row">
                    <table class="table table-striped table-sm table-bordered" id="feesballance" style="visibility: hidden;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Class</th>
                                <th>Acdm</th>
                                <th>Paid Fees</th>
                                <th>Exumption</th>
                                <th>Total</th>
                                <th>Balance</th>

                            </tr>
                        </thead>
                        <tbody id="ttbody" class="tbody-striped">

                        </tbody>
                    </table>

                </div>
            </div>
            <div class="modal-footer text-center justify-content-center">
                <button type="button" id="closeButton" class="btn btn-primary">Pay Previous Fees</button>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/js/new/fees_form.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/css/new/all_status_report.css'); ?>">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap5.min.css">
<div class="row">
    <div class="col-md-12 p-3" id="mainContent">
        <!-- <div class="card"> -->
        <div class="row mb-2">
            <div class="col-md-2">
                <label for="">Class</label>
                <select name="class" id="class" class="form-control form-control-sm">
                    <option value="0">Select Class</option>
                    <option <?= $class_filter == 'ALL' ? 'selected' : '' ?> value="ALL">ALL</option>
                    <?php foreach ($classes as $clas) { ?>
                        <option <?= $class_filter == $clas['stvfees_class'] ? 'selected' : '' ?> value="<?= $clas['stvfees_class'] ?>">
                            <?= $clas['stvfees_class'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-2">

                <label for="">Div</label>
                <select name="division" id="division" class="form-control form-control-sm">
                    <option value="0">Division</option>
                    <option <?= $division_filter == 'A' ? 'selected' : '' ?> value="A">A</option>
                    <option <?= $division_filter == 'B' ? 'selected' : '' ?> value="B">B</option>
                    <option <?= $division_filter == 'C' ? 'selected' : '' ?> value="C">C</option>

                </select>
            </div>

            <div class="col-md-2">

                <label for="">Due Date</label>
                <input type="date" class="form-control form-control-sm border" name="due_date" id="due_date" value="<?= $filter_option; ?>" />
            </div>

            <div class="col-md-2">
                <label for="" style="width: 100px;">Include RTE</label>
                <select name="rte_status" id="rte_status" class="form-control form-control-sm">
                    <option <?= $checkBox == 'yes' ? 'selected' : '' ?> value="yes">Yes</option>
                    <option <?= $checkBox == 'no' ? 'selected' : '' ?> value="no">No</option>
                </select>
            </div>
            <!-- kamran -->
            <div class="col-md-2">

                <label for="">Academic Year</label>

                <select name="acdm_year" id="acdm_year" class="form-control form-control-sm">
                    <option value="">Select Year</option>
                    <?php foreach ($academic_years as $acdm) { ?>
                        <option <?= $filter_acdm == $acdm['stvacdm_id'] ? 'selected' : '' ?> value="<?= $acdm['stvacdm_id'] ?>">
                            <?= $acdm['stvacdm_year'] ?></option>
                    <?php } ?>
                </select>

            </div>
            <div class="col-md-1" style="padding-top: 1.4rem;">

                <button class="btn btn-sm" id="btn" style="background-color:#53769c !important; color:#fff;padding: 2px 5px 2px 5px;">View</button>
            </div>
            <!-- kamran -->
        </div>
        <!-- </div> -->
        <div class="card" style="height:auto">
            <div class="row p-2" style="">
                <div class="col-md-12">
                    <div class="dt-responsive table-responsive margin-top-minus-10 ">
                        <table id="example" class="table">
                            <thead>
                                <style>
                                    /* .text-capitalize {
                                        background-color: #53769c !important;
                                        color: #fff !important;
                                    } */
                                </style>
                                <tr class="">



                                    <th class="text-capitalize" style="display: none;">Class Number</th>
                                    <th class="text-capitalize" style="display: none;">Division</th>
                                    <th class="text-capitalize" style="display: none;">Class Code</th>

                                    <th class="text-capitalize">ID</th>

                                    <th class="text-capitalize">Student Name </th>


                                    <th class="text-capitalize">RTE</th>

                                    <th class="text-capitalize">Total Fees </th>
                                    <th class="text-capitalize">Paid </th>

                                    <th class="text-capitalize">Exemp </th>

                                    <th class="text-capitalize">Bal </th>

                                    <th class="text-capitalize">Action</th>

                                    <th class="text-capitalize">Last Paid </th>

                                    <th class="text-capitalize">Due Date </th>

                                    <th class="text-capitalize">Next Date</th>

                                    <th class="text-capitalize">Mobile No</th>

                                    <th class="text-capitalize">Remark </th>

                                </tr>

                            </thead>

                            <tbody id="tbody">

                                <?php $i = 1;

                                $total_fees_all = 0;
                                $total_fees_paid_by = 0;
                                $total_fees_bal = 0;
                                $total_ex_val = 0;

                                foreach ($all_records as $record) {
                                    // $record['bharna'] = $record['bharna'] == '' ? 0 : $record['bharna'];
                                    $record['exumtion'] = $record['exumtion'] == '' ? 0 : $record['exumtion'];

                                    $exumption = (float)$record['exumtion'];
                                    $total_fees_of_class = $record['total_fees_of_class'] == '' ? 0 : (float) $record['total_fees_of_class'];
                                    $total_paid = $record['total_fees_paid'] == '' ? 0 : (float)$record['total_fees_paid'];

                                    $bharna = (($total_fees_of_class - $total_paid) - $exumption);

                                    $total_fees_all += (float) $total_fees_of_class;
                                    $total_fees_paid_by += (float) $total_paid;
                                    $total_fees_bal += (float) $bharna;
                                    $total_ex_val += (float) $exumption;



                                    if ($record['STVCLAS_standard'] == "I") {

                                        $classStd = "First";
                                    } else if ($record['STVCLAS_standard'] == "II") {

                                        $classStd = "Second";
                                    } else if ($record['STVCLAS_standard'] == "III") {

                                        $classStd = "Third";
                                    } else if ($record['STVCLAS_standard'] == "IV") {

                                        $classStd = "Fourth";
                                    } else if ($record['STVCLAS_standard'] == "V") {

                                        $classStd = "Fifth";
                                    } else if ($record['STVCLAS_standard'] == "VI") {

                                        $classStd = "Sixth";
                                    } else if ($record['STVCLAS_standard'] == "VII") {

                                        $classStd = "Seventh";
                                    } else if ($record['STVCLAS_standard'] == "VIII") {

                                        $classStd = "Eighth";
                                    } else if ($record['STVCLAS_standard'] == "IX") {

                                        $classStd = "Ninth";
                                    } else if ($record['STVCLAS_standard'] == "X") {

                                        $classStd = "Tenth";
                                    } else if ($record['STVCLAS_standard'] == "XI") {

                                        $classStd = "Eleventh";
                                    } else if ($record['STVCLAS_standard'] == "XII") {

                                        $classStd = "Twelfth";
                                    } else if ($record['STVCLAS_standard'] == "Sr.Kg.") {

                                        $classStd = "Sr.Kg.";
                                    } else if ($record['STVCLAS_standard'] == "Jr.Kg.") {

                                        $classStd = "Jr.Kg.";
                                    } else if ($record['STVCLAS_standard'] == "Nursery") {

                                        $classStd = "Nursery";
                                    } else {

                                        $classStd = "";
                                    }

                                    $last_paid_date = $record['last_paid'] != null || $record['last_paid'] != "" ? date('d M y', strtotime($record['last_paid'])) : "";

                                    $due_date_time = $record['due_date'] != null || $record['due_date'] != "" ? date('d M y', strtotime($record['due_date'])) : "";

                                    $fees = 0;

                                    if ($record['STVCLAS_standard'] == "Nursery" || $record['STVCLAS_standard'] == 'Jr.Kg.') {

                                        $fees = 7000;
                                    } elseif ($record['STVCLAS_standard'] == "Sr.Kg.") {

                                        $fees = 8000;
                                    } else {

                                        $fees = 10000;
                                    }



                                    $bal = $fees - ($record['exumtion'] + $record['total_fees_paid']);



                                    if ($bal == 10000) {

                                        $color = 'text-danger';
                                    } else if ($bal > 1000 && $bal < 10000) {

                                        $color = 'text-info';
                                    } else {

                                        $color = 'text-success';
                                    }

                                ?>

                                    <tr>

                                        <td style="display: none;">
                                            <?= $record['class_number']; ?>
                                        </td>
                                        <td style="display: none;">
                                            <?= $record['STVCLAS_class']; ?>
                                        </td>
                                        <td style="display: none;">
                                            <?= $classStd; ?>
                                        </td>

                                        <td data-id="<?= $record['id'] ?>" class="add-remark">
                                            <?= "<span class='font-weight-bold'>" . $record['id'] . "</span>" . " - " . $record['SPRIDEN_student_id'] ?>
                                        </td>

                                        <td data-id="<?= $record['id'] ?>" class="add-remark">
                                            <?= $record['name'] . ' ( ' . $record['STVCLAS_standard'] . ' - ' . $record['STVCLAS_class'] . ' ) ' ?>
                                        </td>

                                        <!-- <td><?= $record['STVCLAS_standard'] ?></td> -->

                                        <!-- <td><?= $record['STVCLAS_class'] ?></td> -->
                                        <td data-id="<?= $record['id'] ?>" class="add-remark">
                                            <?= $record['rte_status'] ?>
                                        </td>

                                        <td>
                                            <?= $record['total_fees_of_class'] ?>
                                        </td>

                                        <td data-id="<?= $record['id'] ?>" class="add-remark">
                                            <?= $total_paid ?>
                                        </td>

                                        <td data-id="<?= $record['id'] ?>" class="add-remark">
                                            <?= $record['exumtion'] ?>
                                        </td>

                                        <td data-id="<?= $record['id'] ?>" class="add-remark">
                                            <?= $bharna ?>
                                        </td>
                                        <?php
                                        $mobile_num = $record['whatsapp'];
                                        $message_wha = "अस्सलामु अलैकुम पैरेंट्स,
   आपका बच्चा / बच्ची " . $record['name'] . " STD. " . $record['STVCLAS_standard'] . " " . $record['STVCLAS_class'] . " में पढाई करता / करती है. उसकी फीस आपने " . $record['total_fees_paid'] . " रुपये जमा की है. " . $bharna . " रूपये जमा करवाना बाकि है. इसलिए उसकी फीस स्कूल में आकर जमा करवा दीजिये ताकी उसकी पढाई में कोई दिक्कत ना हो।
   \n
   


   Assalamualaikum Parents,
   Your Son/Daughter " . $record['name'] . " studies in STD. " . $record['STVCLAS_standard'] . " " . $record['STVCLAS_class'] . ". Till now you have paid Fees Amount of Rs " . $record['total_fees_paid'] . ". Rs " . $bharna . " is yet to be paid. So please pay the remaining fees amount of your child to avoid inconvenience in his/her studies.";

                                        ?>
                                        <td class="font-weight-bold text-success text-center" style="font-weight:900; font-size: 20px;">
                                            <?php if ($mobile_num != '' && $mobile_num != null) {
                                                $mobile_num = str_replace(" ", "", $mobile_num);
                                            ?>

                                                <a target="_blank" href="https://api.whatsapp.com/send?phone=+91<?= $mobile_num ?>&text=<?= $message_wha ?>">
                                                    <i class='bx bxl-whatsapp'></i>
                                                </a>
                                            <?php } ?>
                                        </td>

                                        <td data-id="<?= $record['id'] ?>" class="add-remark">
                                            <?= $last_paid_date; ?>
                                        </td>

                                        <td data-id="<?= $record['id'] ?>" class="add-remark">
                                            <?= $due_date_time; ?>
                                        </td>



                                        <td data-id="<?= $record['id'] ?>" class="add-remark">
                                            <?= date('d M y', strtotime($record['next_date'])); ?>
                                        </td>

                                        <td data-id="<?= $record['id'] ?>" class="add-remark"><a href="tel:<?= $record['phone']; ?>">
                                                <?= $record['phone']; ?>
                                            </a></td>



                                        <td data-id="<?= $record['id'] ?>" class="add-remark">
                                            <?= $record['note']; ?>
                                        </td>

                                    </tr>

                                <?php $i++;
                                } ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th>
                                        <?= number_format($total_fees_all) ?>
                                    </th>
                                    <th>
                                        <?= number_format($total_fees_paid_by) ?>
                                    </th>
                                    <th>
                                        <?= number_format($total_ex_val) ?>
                                    </th>
                                    <th>
                                        <?= number_format($total_fees_bal) ?>
                                    </th>
                                    <th colspan="6"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('application/views/pages/includes/sidebar.php') ?>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<!-- <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap5.min.js"></script> -->
<!-- <script>
    new DataTable('#example', {
        layout: {
            top1Start: {
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            }
        }
    });
</script> -->

<!-- <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script> -->

<script type="text/javascript" src="<?= base_url('assets/js/new/all_status_report.js') ?>"></script>
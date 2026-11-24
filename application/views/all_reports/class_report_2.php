<!DOCTYPE html>
<html lang="en">

<head>
  <title>Olive English School</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <link rel="stylesheet" href="<?= base_url('css/custom/class-result-report1.css') ?>">

  <style>
    .text-kismatji-r {
      color: #87c25b;
    }

    .text-kismatji-s {
      color: #53769c;
    }

    .rose {
      position: sticky;
      top: 1px;
      background-color: #53769c;
      color: #fff;
      z-index: 2;
    }

    .pd {
      padding: 0px !important;
    }

    /* Remove the navbar's default margin-bottom and rounded borders */
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
      background-color: #53769c;
    }

    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }

    .carousel-inner img {
      width: 100%;
      /* Set width to 100% */
      margin: auto;
      min-height: 200px;
    }

    .form-control {
      border: 1px solid #ce464680 !important;
    }

    /* Hide the carousel text when the screen is less than 600 pixels wide */
    @media (max-width: 600px) {
      .carousel-caption {
        display: none;
      }
    }

    .mb-1 {
      margin-bottom: 6px;
    }

    .text-start {
      color: #53769c;
    }

    .th-sm {
      background-color: #53769c !important;
      padding: 0.1rem 0.2rem !important;
      position: sticky;
      top: 0;
    }

    ::-webkit-scrollbar {
      width: 5px;
    }

    ::-webkit-scrollbar-thumb {
      background-color: #53769c;
      border-radius: 10px;
    }
  </style>
</head>

<body>

  <nav class="navbar">
    <div class="container-fluid">
      <div class="navbar-header" style="color: #fff; font-size: 22px; font: width 600px;">
        Classwise Fees Status
      </div>

    </div>
  </nav>
  <div class="container text-center">



    <section class="1">
      <div class="row" style=" margin-top: 7px;">
        <div class="col-lg-3 col-sm-3">
          <div class="card qwe mb-2">
            <div class="card-header p-3 pt-2">

              <h6 class="mb-0 text-kismatji-r">Total Fees</h6>
              <h2 class="mb-0" id="lbl-total-fees">0.00</h2>

            </div>

          </div>

        </div>
        <div class="col-lg-3 col-sm-3">
          <div class="card qwe mb-2">
            <div class="card-header p-3 pt-2 bg-transparent">
              <h6 class="mb-0 text-kismatji-s">Paid Fees</h6>
              <h2 class="mb-0" id="lbl-paid-fees">0.00</h2>
            </div>
          </div>


        </div>
        <div class="col-lg-3 col-sm-3">

          <div class="card qwe mb-2">
            <div class="card-header p-3 pt-2">
              <h6 class="mb-0 text-kismatji-r">Balance Fees</h6>
              <h2 class="mb-0" id="lbl-balance-fees">0.00</h2>
            </div>
          </div>

        </div>



        <div class="col-lg-3 col-sm-3">

          <div class="card qwe">
            <div class="card-header p-3 pt-2 bg-transparent">
              <h6 class="mb-0 text-kismatji-s">Exumpted Fees</h6>
              <h2 class="mb-0" id="lbl-Exemption-fees">0.00</h2>
            </div>
          </div>

        </div>
      </div>
    </section>




    <section class="2">
      <div class="row">
        <div class="col-lg-3 col-sm-3">
          <div class="card qwe mb-2">

            <div class="card-header p-3 pt-2 bg-transparent" style="cursor: pointer;">
              <h6 class="mb-0 text-kismatji-s"> All Student</h6>
              <h2 class="mb-0" id="lbl-all-student">0.00</h2>
            </div>
          </div>

        </div>


        <div class="col-lg-3 col-sm-3 mt-sm-0 mt-4">
          <div class="card qwe mb-2">
            <div class="card-header p-3 pt-2 " style="cursor: pointer;">
              <h6 class="mb-0 text-kismatji-r"> Active Student</h6>
              <h2 class="mb-0" id="lbl-active-student">0.00</h2>
            </div>
          </div>


        </div>


        <div class="col-lg-3 col-sm-3 mt-sm-0 mt-4">

          <div class="card qwe mb-2">
            <div class="card-header p-3 pt-2 bg-transparent" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal1">
              <h6 class="mb-0 text-kismatji-s"> RTE Student</h6>
              <h2 class="mb-0" id="lbl-RTE-student">0.00</h2>
            </div>
          </div>

        </div>



        <div class="col-lg-3 col-sm-3 mt-sm-0 mt-4">

          <div class="card qwe">
            <div class="card-header p-3 pt-2 " style="cursor: pointer;">
              <h6 class="mb-0 text-kismatji-r"> Previous Balance Fees</h6>
              <h2 class="mb-0" id="lbl-inactive-student">0.00</h2>
            </div>
          </div>

        </div>
      </div>

    </section>



    <section class="3">
      <div class="row">
        <div class="col-sm-4">
          <div class="card qwe">
            <div class="card-body" style="background-color: #f7f7f7;height: 227px;">
              <div class="row" style="align-items: center;">


                <div class="col-sm-5 mb-1 text-start">
                  Class <span style="float: right;">:</span>

                </div>

                <div class="col-sm-7 mb-1">
                  <select name="standard" id="standard" class=" form-control" style="border-color: red;" value="<?= $filter_option; ?>">

                    <option value="0">Select Class</option>

                    <?php foreach ($classes as $cls) : ?>

                      <option value="<?= $cls['stvfees_class'] ?>"><?= $cls['stvfees_class'] ?>
                      </option>

                    <?php endforeach; ?>

                  </select>

                </div>

                <div class="col-sm-5 mb-1 text-start">
                  Division <span style="float: right;">:</span>

                </div>

                <div class="col-sm-7 mb-1">
                  <select name="division" id="division" class=" form-control" style="border-color: red;">
                    <option value="">Select Division</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                  </select>

                </div>

                <div class="col-sm-5 mb-1 text-start">
                  From Date <span style="float: right;">:</span>

                </div>

                <div class="col-sm-7 mb-1">
                  <input type="date" name="from_date" id="from_date" class="form-control" value="<?= $start_date ?>" />

                </div>

                <div class="col-sm-5 mb-1 text-start">
                  To Date <span style="float: right;">:</span>

                </div>

                <div class="col-sm-7 mb-1">
                  <input type="date" name="to_date" id="to_date" class=" form-control" value="<?= $end_date ?>" />

                </div>

                <div class="col-sm-12 mb-1 text-end">
                  <button onclick="applyFilter(this)" type="button" class="btn btn-sm mt-2" style="background-color: #87c25b !important;
												color: #fff !important;" value="">View </button>
                </div>
              </div>
            </div>
          </div>
          <div class="card qwe mt-2">
            <div class="card-body" style="height: 227px; overflow: auto; padding: 0px;">



            </div>
          </div>
        </div>

        <div class="col-sm-8">
          <div class="card qwe">
            <div class="card-body" style="height: 465px; overflow: auto; padding: 0px;">
              <style>
                .installment-div {
                  font-size: 9px !important;
                }

                .installment-div small {
                  font-size: 9px !important;
                }

                .tbd {
                  padding: 0.1rem 0.1rem !important;

                }
              </style>
              <table id="exampletable" class="table table-striped table-bordered nowrap m-0">
                <thead>

                  <tr class="rose">
                    <th class="pd">S.No</th>
                    <th class="pd">Student Name</th>
                    <th class="pd">Last balance</th>
                    <th class="pd">Fees Paid</th>
                    <th class="pd">Installment </th>
                  </tr>

                </thead>

                <tbody id="tbody-table-student">
                </tbody>

              </table>
            </div>
          </div>
        </div>


      </div>

    </section>


    <section>
      <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 700px !important;">
          <div class="modal-content" style=" border-radius: 0px !important;">
            <div class="modal-header" style="padding: 0px;background-image: linear-gradient(#ac3d39, #e51f18, #ac3d39); border-radius:0px;">
              <h5 class="modal-title" id="exampleModalLabel">RTE Student Class Wise</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin: 0px !important;"></button>
            </div>
            <div class="modal-body" style="padding: 0rem !important;">
              <table id="dtDynamicVerticalScrollExample" style="cursor: pointer;" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th class="th-sm">#</th>
                    <th class="th-sm">Id</th>
                    <th class="th-sm">Name Of Student</th>
                    <th class="th-sm">Standard</th>
                    <th class="th-sm">Division</th>
                  </tr>
                </thead>
                <tbody id="tbody-table-RTE">

                </tbody>
              </table>
            </div>
            <div class="modal-footer justify-content-center" style="padding: 0.25rem !important;">
              <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background-color: #87c25b !important;color: #fff !important;">Close</button>
              <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
          </div>
        </div>
      </div>
    </section>


  </div>
  <script src="https://kit.fontawesome.com/df9af021d5.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
  </script>

  <script>
    $(document).on('click', '.clickable-row', function() {
      let year = document.getElementById('acdm_year').value
      let std = $(this).attr("data-std");

      $.ajax({
        url: "<?= base_url('index.php/Report/fatch_rte_modal') ?>",
        method: "GET",
        dataType: "json",
        data: {
          std: std,
          div: div,
        },
        success: function(data) {
          let str = ''
          let i = 0
          let tc = 'class="text-center"'
          data.forEach(element => {
            str += `<tr>
                                  <td>${++i}</td>
                                  <td>${element.SPRIDEN_student_id}</td>
                                  <td>${element.name}</td>
                                  <td>${element.STVCLAS_standard}</td>
                                  <td>${element.STVCLAS_class}</td>
                                  </tr>`;

          });
          $("#chart_fetch3").html(str)
        }
      })
      var myModal = new bootstrap.Modal(document.getElementById('exampleModalLong2'), {
        keyboard: false
      })
      myModal.show();

    });
  </script>

  <script>
    let spinner = `<i class="fa-solid fa-spinner fa-spin"></i>`;
    nfObject = new Intl.NumberFormat("en-US");

    function toggleLoader(id, action) {
      if (action === 'set')
        document.getElementById(id).innerHTML = spinner;
      else
        document.getElementById(id).innerHTML = '';
    }

    function displayNumericalValue(id, value, format = 'commas') {
      if (format == 'commas') {
        // document.getElementById(id).innerHTML = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        document.getElementById(id).innerHTML = nfObject.format(value);
      } else {
        document.getElementById(id).innerHTML = value;
      }
    }

    function textToAnimate(id) {
      $('#' + id).each(function() {
        $(this).prop('Counter', 0).animate({
          Counter: parseFloat($(this).text().replace(/\,/g, ''))
        }, {
          duration: 3000,
          easing: 'swing',
          step: function(now) {
            $(this).text(nfObject.format(Math.ceil(now)))
          }
        })
      })
    }


    // ---------- Total Fees ----------
    async function fetchTotalFessAndStudent(std, div, from_Date, to_Date) {
      let element_name = 'lbl-total-fees';
      // set loader
      toggleLoader(element_name, 'set');
      // fetch data
      const url =
        `<?= base_url() ?>Report/fatch_rep2?std=${std}&div=${div}&from_date=${from_Date}&to_date=${to_Date}`;
      const response = await fetch(url);
      const data = await response.json();
      if (data) {
        // stop loader
        toggleLoader(element_name, 'remove');
        // display data
        displayNumericalValue(element_name, data?.total_fees_of_students);
        textToAnimate(element_name);
      } else {
        displayNumericalValue(element_name, 0);
      }
    }


    // ---------- Paid Fees ----------
    async function fetchpaidFessAndStudent(std, div, from_Date, to_Date) {
      let element_name = 'lbl-paid-fees';
      // set loader
      toggleLoader(element_name, 'set');
      // fetch data
      const url =
        `<?= base_url() ?>Report/fetch_paid?std=${std}&div=${div}&from_date=${from_Date}&to_date=${to_Date}`;
      const response = await fetch(url);
      const data = await response.json();
      if (data) {
        // stop loader
        toggleLoader(element_name, 'remove');
        // display data
        displayNumericalValue(element_name, data?.total_fees_paid);
        textToAnimate(element_name);
      } else {
        displayNumericalValue(element_name, 0);
      }
    }


    // ---------- Balance Fees ----------
    async function fetchbalanceFessAndStudent(std, div, from_Date, to_Date) {
      let element_name = 'lbl-balance-fees';
      // set loader
      toggleLoader(element_name, 'set');
      // fetch data
      const url =
        `<?= base_url() ?>Report/fetch_balance?std=${std}&div=${div}&from_date=${from_Date}&to_date=${to_Date}&type=new`;
      const response = await fetch(url);
      const data = await response.json();

      if (data && data.total_fees_paid && data.total_fees_of_students) {


        // stop loader
        toggleLoader(element_name, 'remove');
        // extract values from data object
        let paid = data.total_fees_paid;
        let total = data.total_fees_of_students;
        let balance = total - paid;

        displayNumericalValue(element_name, balance);
        textToAnimate(element_name);
      } else {
        displayNumericalValue(element_name, 0);
      }
    }

    // ---------- Exam Fees ----------
    async function fetchExemptionFessAndStudent(std, div, from_Date, to_Date) {
      let element_name = 'lbl-Exemption-fees';
      // set loader
      toggleLoader(element_name, 'set');
      // fetch data
      const url =
        `<?= base_url() ?>Report/fetch_exemption?std=${std}&div=${div}&from_date=${from_Date}&to_date=${to_Date}`;
      const response = await fetch(url);
      const data = await response.json();
      if (data) {
        // stop loader
        toggleLoader(element_name, 'remove');
        // display data
        displayNumericalValue(element_name, data?.sprfees_exumption);
        textToAnimate(element_name);
      } else {
        displayNumericalValue(element_name, 0);
      }
    }


    // ---------- All Student ----------
    async function fetchstudFessAndStudent(std, div, from_Date, to_Date) {
      let element_name = 'lbl-all-student';
      // set loader
      toggleLoader(element_name, 'set');
      // fetch data
      const url =
        `<?= base_url() ?>Report/fatch_rep2?std=${std}&div=${div}&from_date=${from_Date}&to_date=${to_Date}`;
      const response = await fetch(url);
      const data = await response.json();
      if (data) {
        // stop loader
        toggleLoader(element_name, 'remove');
        // display data
        displayNumericalValue(element_name, data?.total_students);
        textToAnimate(element_name);
      } else {
        displayNumericalValue(element_name, 0);
      }
    }

    // ---------- Active Student ----------
    async function fetchactiveFessAndStudent(std, div, from_Date, to_Date) {
      let element_name = 'lbl-active-student';
      // set loader
      toggleLoader(element_name, 'set');
      // fetch data
      const url =
        `<?= base_url() ?>Report/fatch_rep2?std=${std}&div=${div}&from_date=${from_Date}&to_date=${to_Date}`;
      const response = await fetch(url);
      const data = await response.json();
      if (data) {
        // stop loader
        toggleLoader(element_name, 'remove');
        // display data
        displayNumericalValue(element_name, data?.total_students);
        textToAnimate(element_name);
      } else {
        displayNumericalValue(element_name, 0);
      }
    }


    // ---------- RTE Student ----------
    async function fetchRTEFessAndStudent(std, div, from_Date, to_Date) {
      let element_name = 'lbl-RTE-student';
      // set loader
      toggleLoader(element_name, 'set');
      // fetch data
      const url =
        `<?= base_url() ?>Report/fetch_trte?std=${std}&div=${div}&from_date=${from_Date}&to_date=${to_Date}`;
      const response = await fetch(url);
      const data = await response.json();
      if (data) {
        // stop loader
        toggleLoader(element_name, 'remove');
        // display data
        displayNumericalValue(element_name, data?.RTE);
        textToAnimate(element_name);
      } else {
        displayNumericalValue(element_name, 0);
      }
    }


    // ---------- Total Fees ----------



    async function fetchTolFessAndStudent(totalLastBalance) {

      let element_name = 'lbl-inactive-student';

      // set loader
      toggleLoader(element_name, 'set');

      // Simulate an asynchronous operation (e.g., data fetching)
      await simulateAsyncOperation();

      let arg = parseFloat(totalLastBalance);

      // stop loader
      toggleLoader(element_name, 'remove');

      // extract values from data object
      displayNumericalValue(element_name, arg);

      // return element_name;
      textToAnimate(element_name);

    }

    // Simulate an asynchronous operation (replace this with your actual asynchronous code)
    function simulateAsyncOperation() {
      return new Promise(resolve => setTimeout(resolve, 3000)); // Simulating a 1-second delay
    }





    // ---------- Table Student ----------

    async function fetchtableFessAndStudent(std, div, from_Date, to_Date) {
      let element_name = 'tbody-table-student';
      // set loader

      // fetch data
      const url = `<?= base_url() ?>Report/fetch_table?std=${std}&div=${div}&from_date=${from_Date}&to_date=${to_Date}`;
      const response = await fetch(url);
      const data = await response.json();
      if (data) {
        // stop loader
        // display data
        let str = '';
        let i = 0;
        let totalLastBalance = 0; // Variable to store the total of tab.total_last_balance
        let totalFeesPaid = 0;

        data.forEach(tab => {
          let all_installments = [];
          if (tab.installments != null)
            all_installments = tab.installments.split(",");

          let invalid = ['', ' ', '-', null, undefined, false]
          // Calculate total_last_balance
          let val_to = invalid.includes(tab.total_last_balance) ? 0 : parseFloat(tab.total_last_balance)
          totalLastBalance += val_to;
          
          let feesPaid = invalid.includes(tab.total_fees_paid) ? 0 : parseFloat(tab.total_fees_paid);
      totalFeesPaid += feesPaid;

          str += `<tr>
                <td class="tbd">${++i}</td>
                <td class="tbd" style="text-align: start;">${tab.name}</td>
                <td class="tbd">${tab.total_last_balance}</td>
                <td class="tbd">${tab.total_fees_paid}</td>
                <td class="tbd">
                <div class="d-flex">`
                all_installments.forEach(installment => {
                      let inner_data = installment.split('->');
                      str += `<div class="alert border border-secondary p-0 m-0 installment-div" style="padding-right:5px !important; padding-left:5px !important;margin-right:2px !important;">
                        <small>${inner_data[1]}</small><br>
                        ${inner_data[0]} / -
                  </div>`;
                    });

          str += `</div></td>
            </tr>`;
        });


        // document.getElementById("lbl-inactive-student").innerHTML = totalLastBalance;
        fetchTolFessAndStudent(totalLastBalance);

        // Display the total_last_balance
        str += `<tr>
            <td class="tbd" colspan="2" style="text-align: end;">Total </td>
            <td class="tbd">${totalLastBalance} /-</td>
            <td class="tbd">${totalFeesPaid} /-</td>
            <td class="tbd"></td>
        </tr>`;

        // Update the HTML content with the generated table rows
        document.getElementById(element_name).innerHTML = str;
      }
    }



    async function fetchtbodyAndStudent(std, div, from_Date, to_Date) {
      let element_name = 'tbody-table-RTE';
      // set loader

      // fetch data
      const url = `<?= base_url() ?>Report/fatch_rte?std=${std}&div=${div}&from_date=${from_Date}&to_date=${to_Date}`;
      const response = await fetch(url);
      const data = await response.json();
      if (data) {
        console.log(data);
        

        let str = '';
        let i = 0;
        data.forEach(stud => {
          str += `<tr>
            <td class="tbd">${++i}</td>
            <td class="tbd" style="text-align: start;">${stud.SPRIDEN_student_id}</td>
            <td class="tbd">${stud.name}</td>
            <td class="tbd">${stud.STVCLAS_standard}</td>
            <td class="tbd">${stud.STVCLAS_class}</td>
          </tr>`;
        });

        // Update the HTML content with the generated table rows
        document.getElementById(element_name).innerHTML = str;

      }
    }


    function applyFilter(element) {
      // get filter values
      let std = document.getElementById("standard").value;
      let div = document.getElementById("division").value;
      let from_Date = document.getElementById("from_date").value;
      let to_Date = document.getElementById("to_date").value;
      // call function which are defined and will fetch data
      fetchTotalFessAndStudent(std, div, from_Date, to_Date);
      fetchpaidFessAndStudent(std, div, from_Date, to_Date);
      fetchbalanceFessAndStudent(std, div, from_Date, to_Date);
      fetchExemptionFessAndStudent(std, div, from_Date, to_Date);
      fetchstudFessAndStudent(std, div, from_Date, to_Date);
      fetchactiveFessAndStudent(std, div, from_Date, to_Date);
      fetchRTEFessAndStudent(std, div, from_Date, to_Date);
      fetchtableFessAndStudent(std, div, from_Date, to_Date);
      fetchtbodyAndStudent(std, div, from_Date, to_Date);
      fetchTolFessAndStudent(totalLastBalance);
    }
  </script>



</body>

</html>
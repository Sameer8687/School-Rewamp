<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
    var baseUrl = "<?= base_url() ?>";
    document.getElementById('toggleButton').addEventListener('click', function() {
        var mainContent = document.getElementById('mainContent');
        var sidebar = document.getElementById('sidebar');
        var toggleButton = document.getElementById('toggleButton');

        if (sidebar.classList.contains('d-none')) {
            // Show sidebar
            sidebar.classList.remove('d-none');
            mainContent.classList.remove('col-md-12');
            mainContent.classList.add('col-md-9');
            toggleButton.innerHTML = '';
        } else {
            // Hide sidebar
            sidebar.classList.add('d-none');
            mainContent.classList.remove('col-md-9');
            mainContent.classList.add('col-md-12');
            toggleButton.innerHTML = '';
        }
    });

    function getLink(element) {
        let link_section = document.getElementById("links");
        let link_html = "";
        var sidebar = document.getElementById("sidebar");

        if (element == "student") {
            sidebar.classList.remove("d-none");
            mainContent.classList.remove("col-md-12");
            mainContent.classList.add("col-md-9");
            link_html = `
                <ul class="p-1"><h6>Academics</h6>
                <li class="pc-item ms-4"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>remark">Student Remark</a></li>
                <li class="pc-item ms-4"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>student/student_comments">Student Comments</a></li>
                </ul>
                <ul class="p-1"><h6>Admissions</h6>
                <li class="pc-item ms-4"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>admission-form"> Admission Form</a></li>
                <li class="pc-item ms-4"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>readmission"> Re-Admission Form</a></li>
                </ul>               
                <ul class="p-1"><h6>Student</h6>
                <li class="pc-item ms-4"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>welcome/rte_students"> RTE Students</a></li>
                <li class="pc-item ms-4"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>student/student_list"> Update Student</a></li>
                <li class="pc-item ms-4"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/report_stats_lc"> Issue LC</a></li>
                <li class="pc-item ms-4"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/report_stats_bf"> Issue Bonafide</a></li>
                </ul>
                <ul class="p-1"><h6>General</h6>
                <li class="pc-item ms-4"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>student/books">Books</a></li>
                <li class="pc-item ms-4"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>student/books_records">Books Report</a></li>
              
                <li class="pc-item ms-4"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>student/tailor_details">Tailor Details</a></li>
                
                
                </ul>
            `;
        } else if (element == "teacher") {
            sidebar.classList.remove("d-none");
            mainContent.classList.remove("col-md-12");
            mainContent.classList.add("col-md-9");
            link_html = `	
                <ul class="p-1"><h6>Academic</h6>
                <li class="pc-item ms-4"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>teacher/lesson">Lesson Management</a></li>
                </ul>
                <ul class="p-1"><h6>Account</h6>
                <li class="pc-item ms-4"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>teacher-account">Add Account</a></li>               
                <li class="pc-item ms-4"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>account-details">Account Info</a></li>
                </ul>
               
            `;
        } else if (element == "fees") {
            sidebar.classList.remove("d-none");
            mainContent.classList.remove("col-md-12");
            mainContent.classList.add("col-md-9");
            link_html = `
                <li class="pc-item"><a class="pc-link" href="<?php echo base_url(); ?>index.php/Fees_form"> Fees Form</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>fees-report"> Fees Report</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>visiting"> Visiting</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>FeesAlert/fees_report"> Send Fees Alert </a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>structure">Structure Master</a></li>
            `;
        } else if (element == "report") {
            sidebar.classList.remove("d-none");
            mainContent.classList.remove("col-md-12");
            mainContent.classList.add("col-md-9");
            link_html = `
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>all_reports"> All Status Report</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>fees_report"> Daily Fees Report</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>balance_fees"> Balance Fees Report</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/fees_date_wise"> Datewise Fees Report </a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/fees_instment_report"> Fees Installment Report</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/fees_alert"> Fees Alert Receipt</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/report"> Report</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/reports"> Report2</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/rte_report"> RTE Student Report</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/admissionreport"> Admission Report</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/admission_summary"> Adm. Summary Report</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/admission_report"> New Admission Report</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/readmission"> Readmission Report</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/exemption_fees"> Exemption </a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/fees_recipts"> Exemption Receipt </a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/balance_rep"> Balance Report</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>report/current_inactive"> Inactive Students Report</a></li>
            `;
        } else if (element == "admin") {
            sidebar.classList.remove("d-none");
            mainContent.classList.remove("col-md-12");
            mainContent.classList.add("col-md-9");
            link_html = `
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>admin"> Admin Dashboard</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>admin/settings"> Settings</a></li>
                <li class="pc-item"><a class="pc-link" style="color:#08258b !important" href="<?php echo base_url(); ?>admin/users"> User Management</a></li>
            `;
        }

        link_section.innerHTML = link_html;
    }
</script>
</body>

</html>
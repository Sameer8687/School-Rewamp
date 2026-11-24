<!-- [ Mobile header ] start -->
<div class="pc-mob-header pc-header">
    <div class="pcm-logo">
        <img src="<?php echo base_url(); ?>images/logoNewDevWhite.png" style="height: 40px;" alt=""
            class="logo logo-lg">
        <span>Olive English School</span>
    </div>

    <div class="pcm-toolbar">

        <a href="#!" class="pc-head-link" id="mobile-collapse" onclick="toggleSidebar()">

            <div class="hamburger hamburger--arrowturn">

                <div class="hamburger-box">

                    <div class="hamburger-inner"></div>

                </div>

            </div>

            <!-- <i data-feather="menu"></i> -->

        </a>

        <a href="#!" class="pc-head-link" id="header-collapse">

            <i data-feather="more-vertical"></i>

        </a>

    </div>
    <!-- <div class="pcm-toolbar">
        
        <a href="#!" class="pc-head-link" id="header-collapse">
            <i data-feather="more-vertical"></i>
        </a>
    </div> -->
</div>
<style>
    .pc-sidebar {
        position: fixed;
        top: 0;
        left: -280px;
        width: 250px;
        height: 100%;
        background-color: #333;
        transition: left 0.5s ease;
        z-index: 1;
    }

    #pc-collapse {
        position: fixed;
        top: 2px;
        left: 1px;
        transition: left 0.3s ease;
        z-index: 2;
        background-color: none;
        color: #fff;
        width: 30px;
        height: 30px;
        font-size: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 4px;
        cursor: pointer;
    }


    .pc-sidebar.active {
        left: 0;
    }

    .pc-sidebar.active+#pc-collapse {
        left: 260px;
        /* Adjust based on sidebar width + spacing */
    }

    .pc-head-link {
        width: 30px;
        height: 30px;
        color: white;
        font-size: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 4px;
        cursor: pointer;
        z-index: 2;
    }

    .hamburger--arrowturn .hamburger-inner,
    .hamburger--arrowturn .hamburger-inner::before,
    .hamburger--arrowturn .hamburger-inner::after {
        background-color: white;
        /* Lines and arrow default white */
    }

    /* Active state for close arrow */
    .hamburger--arrowturn.is-active .hamburger-inner,
    .hamburger--arrowturn.is-active .hamburger-inner::before,
    .hamburger--arrowturn.is-active .hamburger-inner::after {
        background-color: white;
        /* Close arrow color white */
    }

    .navbar-content {
        overflow-y: auto;
        height: calc(100% - 50px);
        /* Adjust to fit content properly */
    }

    @media (max-width: 768px) {
        .pc-sidebar {
            width: 200px;
        }

        .pc-sidebar.active+#pc-collapse {
            left: 210px;
        }

        #pc-collapse {
            left: 10px;
            /* Ensure toggle stays visible */
        }
    }
</style>
<!-- [ navigation menu ] start -->
<nav class="pc-sidebar ">
    <div class="navbar-wrapper">
        <a href="#!" onclick="toggleSidebar()" class="pc-head-link text-white" id="pc-collapse">
            <div class="hamburger hamburger--arrowturn">
                <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                </div>
            </div>
        </a>
        <div class="m-header text-center">
            <a href="<?php echo base_url(); ?>" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="<?php echo base_url(); ?>images/logoNewDevWhite.png" style="height: 50px; margin: auto;"
                    class="img-fluid" alt="" class="logo logo-lg">
                <span class="text-white" style="margin-left: 10px;">Olive English School</span>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Section</label>
                    <span>Student Section</span>
                </li>
                <li class="pc-item"><a href="<?php echo base_url(); ?>dashboard" class="pc-link "><span
                            class="pc-micon"><i data-feather="grid"></i></span><span
                            class="pc-mtext">Dashboard</span></a></li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon"><i data-feather="users"></i></span><span
                            class="pc-mtext">Student</span><span class="pc-arrow"><i
                                data-feather="chevron-right"></i></span></a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="<?php echo base_url(); ?>attendance">Mark
                                Attendance</a></li>
                        <li class="pc-item"><a class="pc-link"
                                href="<?php echo base_url(); ?>view-student-attendance">View Attendance</a></li>
                        <li class="pc-item"><a class="pc-link" href="<?php echo base_url(); ?>student-remark">
                                Remark</a></li>
                        <li class="pc-item"><a class="pc-link" href="<?php echo base_url(); ?>student-marks"> Marks
                                Assesment</a></li>
                    </ul>
                </li>
                <li class="pc-item"><a href="<?php echo base_url(); ?>student-class" class="pc-link "><span
                            class="pc-micon"><i data-feather="command"></i></span><span
                            class="pc-mtext">Class</span></a></li>
                <li class="pc-item"><a href="<?php echo base_url(); ?>student-event" class="pc-link "><span
                            class="pc-micon"><i data-feather="bookmark"></i></span><span
                            class="pc-mtext">Event</span></a></li>
                <!-------------------------------------------------History Setting ----------------------------------------->
                <li class="pc-item pc-caption">
                    <label>Security</label>
                    <span>Histoty & All Logs</span>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link "><span class="pc-micon"><i data-feather="server"></i></span><span
                            class="pc-mtext">History</span><span class="pc-arrow"><i
                                data-feather="chevron-right"></i></span></a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="<?php echo base_url(); ?>stvlogh">Login History</a>
                        </li>
                        <li class="pc-item"><a class="pc-link" href="<?php echo base_url(); ?>stvfrmh">Form Access
                                History</a></li>

                    </ul>
                </li>
                <li class="pc-item"><a href="<?php echo base_url(); ?>student-event" class="pc-link "><span
                            class="pc-micon"><i data-feather="key"></i></span><span class="pc-mtext">Change
                            Password</span></a></li>
                <!-------------------------------------------------Account Setting ----------------------------------------->
                <li class="pc-item pc-caption">
                    <label>Account Setting</label>
                    <span>Customize your account & Dashboard</span>
                </li>
                <li class="pc-item"><a href="<?php echo base_url(); ?>account" class="pc-link "><span
                            class="pc-micon"><i data-feather="airplay"></i></span><span class="pc-mtext">Account
                            Setting</span></a></li>
                <li class="pc-item"><a href="<?php echo base_url(); ?>dashboard/logout" class="pc-link"><span
                            class="pc-micon"><i data-feather="power"></i></span><span class="pc-mtext">Log
                            out</span></a></li>

            </ul>
        </div>
    </div>
</nav>
<!-- [ navigation menu ] end -->
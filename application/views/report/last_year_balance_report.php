<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Last Year Balance Report</title>

    <?php include 'application/views/cssPages.php'; ?>

    <link rel="stylesheet"
        href="<?= base_url('assets/css/fees-report-common.css'); ?>">

    <style>
        .balance-filter-form {
            grid-template-columns:
                minmax(180px, 1.3fr)
                minmax(105px, .65fr)
                minmax(105px, .65fr)
                auto;
        }

        #reportTable_wrapper .dataTables_info,
        #reportTable_wrapper .dataTables_paginate {
            font-size: 10px;
        }

        #reportTable_wrapper .dataTables_paginate {
            padding: 10px 12px;
        }

        #reportTable_wrapper .dataTables_info {
            padding: 14px 12px;
        }

   

        #reportTable_wrapper .dataTables_paginate .paginate_button.disabled {
            color: #9ca3af !important;
            background: #f7f7f7 !important;
        }

        #reportTableContainer .dataTables_scrollBody {
            border-bottom: 0 !important;
        }

        #reportTableContainer.table-loading {
            pointer-events: none;
        }

        #reportTableContainer.table-loading::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: 50;
            background: rgba(255, 255, 255, .74);
            border-radius: 14px;
        }

        /* #reportTableContainer.table-loading::after {
            content: '';
            position: absolute;
            top: 70px;
            left: 50%;
            z-index: 51;
            width: 34px;
            height: 34px;
            margin-left: -17px;
            border: 4px solid #dbe3ff;
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: report-spin .7s linear infinite;
        } */

        /* @keyframes report-spin {
            to {
                transform: rotate(360deg);
            }
        } */

        /* Exact table styling used by the finalized fee reports */
        #reportTableContainer {
            position: relative;
            padding: 0;
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 14px;
        }

        #reportTableContainer .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        #reportTableContainer .dataTables_wrapper {
            width: 100%;
        }

        #reportTableContainer table.report-table,
        #reportTableContainer table.dataTable {
            width: 100% !important;
            min-width: 980px;
            margin: 0 !important;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            white-space: nowrap;
        }

        #reportTableContainer table.report-table thead th,
        #reportTableContainer table.dataTable thead th {
            padding: 11px 9px !important;
            color: #000 !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            line-height: 1.2 !important;
            text-transform: uppercase;
            letter-spacing: .04em;
            background: #f7f7f7 !important;
            border: 0 !important;
            border-bottom: 1px solid #e5e7eb !important;
            box-shadow: none !important;
        }

        #reportTableContainer table.report-table tbody td,
        #reportTableContainer table.dataTable tbody td {
            padding: 2px !important;
            color: var(--ink) !important;
            font-size: 11px !important;
            line-height: 1.45 !important;
            vertical-align: middle !important;
            background: #fff;
            border: 0 !important;
            border-bottom: 1px solid #edf0f4 !important;
            box-shadow: none !important;
        }

       
        #reportTableContainer table.report-table tfoot th,
        #reportTableContainer table.dataTable tfoot th {
            padding: 9px 8px !important;
            color: #7f1d1d !important;
            font-size: 11px !important;
            font-weight: 900 !important;
            line-height: 1.2 !important;
            background: #fff1f2 !important;
            border: 0 !important;
            border-top: 2px solid #fecdd3 !important;
        }

        #reportTableContainer .student-id-badge {
            display: inline-block;
            padding: 4px 7px;
            color: #4338ca;
            font-size: 10px;
            font-weight: 800;
            line-height: 1.2;
            border-radius: 6px;
        }

        #reportTableContainer .amount {
            text-align: right !important;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
        }

        #reportTableContainer .student-total {
            color: #991b1b !important;
            font-weight: 600 !important;
            background: #fff1f2 !important;
        }

        #reportTableContainer .whatsapp-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 4px 8px;
            color: #15803d !important;
            font-size: 10px;
            font-weight: 800;
            line-height: 1.2;
            text-decoration: none !important;
          
        }

        /* #reportTableContainer .whatsapp-button:hover {
            color: #166534 !important;
            background: #dcfce7;
        } */

        #reportTableContainer .whatsapp-unavailable {
            color: #9ca3af;
            font-size: 10px;
            font-weight: 700;
        }

        #reportTableContainer .dataTables_empty {
            padding: 28px !important;
            color: var(--muted) !important;
            text-align: center !important;
        }

        #reportTableContainer .pagination-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 12px;
            background: #fff;
            border-top: 0;
        }

        #reportTableContainer .pagination-info,
        #reportTableContainer .dataTables_info {
            padding: 0 !important;
            color: var(--muted);
            font-size: 10px !important;
        }

        #reportTableContainer .pagination-links,
        #reportTableContainer .dataTables_paginate {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 0 !important;
            flex-wrap: wrap;
        }

        #reportTableContainer .dataTables_paginate .paginate_button {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 30px !important;
            height: 30px !important;
            margin: 0 !important;
            padding: 0 8px !important;
            color: #374151 !important;
            font-size: 10px !important;
            font-weight: 700 !important;
            line-height: 30px !important;
            text-decoration: none !important;
            background: #fff !important;
            border: 1px solid #dfe4eb !important;
            border-radius: 6px !important;
            box-shadow: none !important;
        }

        #reportTableContainer table.dataTable.no-footer {
            border-bottom: 0 !important;
        }

        @media (max-width: 900px) {
            .balance-filter-form {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 560px) {
            .balance-filter-form {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php
    $filters = is_array($filters ?? null)
        ? $filters
        : [];

    $classes = is_array($filters['classes'] ?? null)
        ? $filters['classes']
        : [];

    $divisions = is_array($filters['divisions'] ?? null)
        ? $filters['divisions']
        : [];
    ?>

    <main class="report-shell">
        <div id="reportContent">
            <?php
            $current_report = 'balance';
            $report_title = 'Last Year Balance Report';

            include APPPATH . 'views/report/fees_report_navbar.php';
            ?>

            <section class="dashboard-row">
                <div class="summary-card indigo">
                    <span class="summary-label">Students</span>

                    <div class="summary-content">
                        <span id="studentCount"
                            class="summary-value">
                            0
                        </span>

                        <i class="fa fa-user-graduate summary-icon"></i>
                    </div>
                </div>

                <div class="summary-card amber">
                    <span class="summary-label">Total Fees</span>

                    <div class="summary-content">
                        <span id="totalFeeValue"
                            class="summary-value">
                            ₹0
                        </span>

                        <i class="fa fa-file-invoice-dollar summary-icon"></i>
                    </div>
                </div>

                <div class="summary-card green">
                    <span class="summary-label">Paid Fees</span>

                    <div class="summary-content">
                        <span id="paidFeeValue"
                            class="summary-value">
                            ₹0
                        </span>

                        <i class="fa fa-hand-holding-usd summary-icon"></i>
                    </div>
                </div>

                <div class="summary-card red">
                    <span class="summary-label">Balance</span>

                    <div class="summary-content">
                        <span id="balanceValue"
                            class="summary-value">
                            ₹0
                        </span>

                        <i class="fa fa-money-bill-wave summary-icon"></i>
                    </div>
                </div>

                <div class="filter-card">
                    <div class="filter-header">
                        <span>Filters</span>

                        <span id="recordStatus">
                            Loading...
                        </span>
                    </div>

                    <div class="filter-form balance-filter-form">
                        <input
                            type="text"
                            id="studentSearch"
                            class="control"
                            placeholder="Search student name or ID...">

                        <select
                            id="classFilter"
                            class="control">
                            <option value="">All Classes</option>

                            <?php foreach ($classes as $class): ?>
                                <option value="<?= html_escape($class); ?>">
                                    <?= html_escape($class); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select
                            id="divisionFilter"
                            class="control">
                            <option value="">All Divisions</option>

                            <?php foreach ($divisions as $division): ?>
                                <option value="<?= html_escape($division); ?>">
                                    <?= html_escape($division); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button
                            type="button"
                            id="resetFilters"
                            class="btn-reset">
                            Reset
                        </button>
                    </div>
                </div>
            </section>

            <section
                class="table-panel"
                id="reportTableContainer">

                <div class="table-responsive">
                    <table
                        id="reportTable"
                        class="report-table"
                        style="width:100%">

                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Division</th>
                                <th>Mobile</th>
                                <th class="text-right">Total Fee</th>
                                <th class="text-right">Paid Fee</th>
                                <th class="text-right">Balance</th>
                                <th class="text-center">WhatsApp</th>
                            </tr>
                        </thead>

                        <tbody></tbody>

                        <tfoot>
                            <tr>
                                <th colspan="6"
                                    class="text-right">
                                    Filtered Total
                                </th>

                                <th id="footerTotalFee"
                                    class="text-right">
                                    ₹0
                                </th>

                                <th id="footerTotalPaid"
                                    class="text-right">
                                    ₹0
                                </th>

                                <th id="footerTotalBalance"
                                    class="text-right">
                                    ₹0
                                </th>

                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
        </div>


    </main>

    <?php include 'application/views/jsPages.php'; ?>

    <script>
        $(document).ready(function () {
            'use strict';

            if ($.fn.dataTable.isDataTable('#reportTable')) {
                $('#reportTable').DataTable().destroy();
            }

            let searchTimer = null;

            const tableContainer =
                $('#reportTableContainer');

            function setLoading(isLoading) {
                tableContainer.toggleClass(
                    'table-loading',
                    isLoading
                );
            }

            function formatAmount(value) {
                return '₹' +
                    Number(value || 0).toLocaleString(
                        'en-IN',
                        {
                            maximumFractionDigits: 0
                        }
                    );
            }

            function escapeHtml(value) {
                return $('<div>')
                    .text(value || '')
                    .html();
            }

            function normalizeWhatsApp(mobileNumber) {
                let digits = String(mobileNumber || '')
                    .replace(/\D+/g, '')
                    .replace(/^0+/, '');

                if (digits.length === 10) {
                    return '91' + digits;
                }

                if (
                    digits.length === 12 &&
                    digits.substring(0, 2) === '91'
                ) {
                    return digits;
                }

                return '';
            }

            function createWhatsAppUrl(row) {
                const studentName =
                    String(row.name || '').trim();

                const studentTotal =
                    Number(row.LastYearBalance || 0);

                const whatsappNumber =
                    normalizeWhatsApp(row.MobileNumber);

                if (
                    whatsappNumber === '' ||
                    studentTotal <= 0
                ) {
                    return '';
                }

                const formattedAmount =
                    studentTotal.toLocaleString(
                        'en-IN',
                        {
                            maximumFractionDigits: 0
                        }
                    );

                const message =
                    "Assalamualaikum\n\n" +
                    "Dear Parent,\n\n" +
                    "This is to inform you that the last academic year fees balance for your child " +
                    studentName +
                    " is still pending.\n\n" +
                    "Pending Fees Details\n" +
                    "=============================\n\n" +
                    "Last Year Balance: ₹" +
                    formattedAmount +
                    "\n\n" +
                    "Kindly visit the school office and clear the pending dues at the earliest.\n\n" +
                    "आपके बच्चे " +
                    studentName +
                    " की पिछले साल की फीस अभी तक बाकी है।\n\n" +
                    "कुल बकाया राशि: ₹" +
                    formattedAmount +
                    "\n\n" +
                    "कृपया स्कूल ऑफिस में आकर बाकी फीस जल्द से जल्द जमा करें।\n\n" +
                    "आपके सहयोग के लिए धन्यवाद।\n\n" +
                    "Regards,\nOlive English School\nDhule";

                return (
                    'https://wa.me/' +
                    whatsappNumber +
                    '?text=' +
                    encodeURIComponent(message)
                );
            }

            function updateSummary(summary) {
                const students =
                    Number(summary.students || 0);

                const totalFee =
                    Number(summary.totalFee || 0);

                const totalPaid =
                    Number(summary.totalPaid || 0);

                const totalBalance =
                    Number(summary.totalBalance || 0);

                $('#studentCount').text(
                    students.toLocaleString('en-IN')
                );

                $('#totalFeeValue').text(
                    formatAmount(totalFee)
                );

                $('#paidFeeValue').text(
                    formatAmount(totalPaid)
                );

                $('#balanceValue').text(
                    formatAmount(totalBalance)
                );

                $('#footerTotalFee').text(
                    formatAmount(totalFee)
                );

                $('#footerTotalPaid').text(
                    formatAmount(totalPaid)
                );

                $('#footerTotalBalance').text(
                    formatAmount(totalBalance)
                );
            }

            const reportTable =
                $('#reportTable').DataTable({
                    // processing: true,
                    serverSide: true,
                    responsive: false,
                    autoWidth: false,
                    scrollX: false,
                    scrollCollapse: false,
                    pageLength: 20,

                    lengthMenu: [
                        [20, 50, 100],
                        [20, 50, 100]
                    ],

                    order: [
                        [3, 'asc']
                    ],

                    dom:
                        'rt' +
                        '<"pagination-row"' +
                        '<"pagination-info"i>' +
                        '<"pagination-links"p>' +
                        '>',

                    language: {
                        emptyTable:
                            'No last year balance records found.',
                        zeroRecords:
                            'No matching records found.',
                        info:
                            'Showing _START_ to _END_ of _TOTAL_ records',
                        infoEmpty:
                            'Showing 0 to 0 of 0 records',
                        paginate: {
                            first: '&laquo;',
                            previous: '&lsaquo;',
                            next: '&rsaquo;',
                            last: '&raquo;'
                        }
                    },

                    ajax: {
                        url: "<?= base_url(
                            'Report/last-year-balance-data'
                        ); ?>",

                        type: 'POST',

                        data: function (data) {
                            data.search_value =
                                $('#studentSearch').val();

                            data.class_filter =
                                $('#classFilter').val();

                            data.division_filter =
                                $('#divisionFilter').val();

                            if (
                                data.order &&
                                data.order.length
                            ) {
                                data.order_column =
                                    data.order[0].column;

                                data.order_direction =
                                    data.order[0].dir;
                            } else {
                                data.order_column = 3;
                                data.order_direction = 'asc';
                            }

                            return data;
                        },

                        beforeSend: function () {
                            setLoading(true);
                        },

                        dataSrc: function (json) {
                            const summary =
                                json.summary || {};

                            updateSummary(summary);

                            $('#recordStatus').text(
                                Number(
                                    json.recordsFiltered || 0
                                ).toLocaleString('en-IN') +
                                ' result(s)'
                            );

                            return json.data || [];
                        },

                        complete: function () {
                            setLoading(false);
                        },

                        error: function (xhr) {
                            setLoading(false);

                            console.error(
                                xhr.responseText
                            );

                            $('#recordStatus').text(
                                'Unable to load data'
                            );

                            updateSummary({});
                        }
                    },

                    columns: [
                        {
                            data: 'serial',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'StudentID',

                            render: function (data) {
                                return (
                                    '<span class="student-id-badge">' +
                                    escapeHtml(data) +
                                    '</span>'
                                );
                            }
                        },
                        {
                            data: 'name',

                            render: function (data) {
                                return escapeHtml(data);
                            }
                        },
                        {
                            data: 'Class',
                            className: 'text-center',

                            render: function (data) {
                                return escapeHtml(data);
                            }
                        },
                        {
                            data: 'Division',
                            className: 'text-center',

                            render: function (data) {
                                return escapeHtml(data);
                            }
                        },
                        {
                            data: 'MobileNumber',
                            className: 'text-center',

                            render: function (data) {
                                return escapeHtml(data || '');
                            }
                        },
                        {
                            data: 'LastYearFee',
                            className: 'amount',

                            render: function (data) {
                                return formatAmount(data);
                            }
                        },
                        {
                            data: 'LastYearPaid',
                            className: 'amount',

                            render: function (data) {
                                return formatAmount(data);
                            }
                        },
                        {
                            data: 'LastYearBalance',
                            className:
                                'amount student-total',

                            render: function (data) {
                                return formatAmount(data);
                            }
                        }
,
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            className: 'text-center',

                            render: function (data, type, row) {
                                const whatsappUrl =
                                    createWhatsAppUrl(row);

                                if (whatsappUrl === '') {
                                    return (
                                        '<span class="whatsapp-unavailable"></span>'
                                    );
                                }

                                return (
                                    '<a href="' +
                                    whatsappUrl +
                                    '" target="_blank" ' +
                                    'rel="noopener noreferrer" ' +
                                    'class="whatsapp-button">' +
                                    '<i class="fab fa-whatsapp"></i> Send' +
                                    '</a>'
                                );
                            }
                        }
                    ],

                    columnDefs: [
                        {
                            targets: 0,
                            width: '45px'
                        },
                        {
                            targets: 1,
                            width: '110px'
                        },
                        {
                            targets: 2,
                            width: '250px'
                        },
                        {
                            targets: [3, 4],
                            width: '85px'
                        },
                        {
                            targets: 5,
                            width: '120px'
                        },
                        {
                            targets: [6, 7, 8],
                            width: '115px'
                        },
                        {
                            targets: 9,
                            width: '95px'
                        }
                    ]
                });

            $('#studentSearch').on(
                'input',
                function () {
                    clearTimeout(searchTimer);

                    searchTimer = setTimeout(
                        function () {
                            reportTable.ajax.reload();
                        },
                        450
                    );
                }
            );

            $('#classFilter, #divisionFilter').on(
                'change',
                function () {
                    reportTable.ajax.reload();
                }
            );

            $('#resetFilters').on(
                'click',
                function () {
                    $('#studentSearch').val('');
                    $('#classFilter').val('');
                    $('#divisionFilter').val('');

                    reportTable
                        .order([3, 'asc'])
                        .ajax
                        .reload();
                }
            );
        });
    </script>
</body>

</html>
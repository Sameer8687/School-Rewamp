<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Previous Fees Report</title>

    <?php include 'application/views/cssPages.php'; ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/fees-report-common.css'); ?>">

</head>

<body>
    <?php
    $records = is_array($records ?? null) ? $records : [];
    $academicYears = is_array($academic_years ?? null) ? $academic_years : [];
    $summary = is_array($summary ?? null) ? $summary : [];
    $yearTotals = is_array($summary['yearTotals'] ?? null) ? $summary['yearTotals'] : [];

    $search = (string) ($search ?? '');
    $page = max(1, (int) ($page ?? 1));
    $perPage = (int) ($per_page ?? 20);
    $totalPages = max(1, (int) ($total_pages ?? 1));
    $recordsFiltered = (int) ($records_filtered ?? 0);
    $startRecord = (int) ($start_record ?? 0);
    $endRecord = (int) ($end_record ?? 0);

    $formatAmount = static function ($value) {
        return '₹' . number_format((float) $value, 0);
    };

    $buildPageUrl = static function ($pageNumber) use ($search, $perPage) {
        return site_url('Report/all_previous_fees_report') . '?' . http_build_query([
            'search' => $search,
            'per_page' => $perPage,
            'page' => max(1, (int) $pageNumber),
        ]);
    };

    $normalizeWhatsApp = static function ($mobile) {
        $digits = preg_replace('/\D+/', '', (string) $mobile);
        $digits = ltrim((string) $digits, '0');

        if (strlen($digits) === 10) {
            return '91' . $digits;
        }

        if (strlen($digits) === 12 && substr($digits, 0, 2) === '91') {
            return $digits;
        }

        return '';
    };

    $startPage = max(1, $page - 2);
    $endPage = min($totalPages, $page + 2);
    ?>

    <main class="report-shell">
        <div id="reportContent">
            <?php
            $current_report = 'previous';
            $report_title = 'Previous Fees Report';

            include APPPATH . 'views/report/fees_report_navbar.php';
            ?>
            <section class="dashboard-row">
                <div class="summary-card indigo">
                    <span class="summary-label">Students</span>

                    <div class="summary-content">
                        <span class="summary-value">
                            <?= number_format((int) ($summary['students'] ?? 0)); ?>
                        </span>
                        <i class="fa fa-user-graduate summary-icon"></i>
                    </div>
                </div>

                <div class="summary-card red">
                    <span class="summary-label">Total Outstanding</span>
                    <div class="summary-content">
                        <span class="summary-value">
                            <?= $formatAmount($summary['totalPending'] ?? 0); ?>
                        </span>
                        <i class="fa fa-money-bill-wave summary-icon"></i>
                    </div>
                </div>

                <div class="summary-card amber">
                    <span class="summary-label">Academic Years</span>
                    <div class="summary-content">
                        <span class="summary-value">
                            <?= number_format(count($academicYears)); ?>
                        </span>
                        <i class="fa fa-calendar-alt summary-icon"></i>
                    </div>
                </div>

                <div class="summary-card green">
                    <span class="summary-label">Average Per Student</span>
                    <div class="summary-content">
                        <span class="summary-value">
                            <?= $formatAmount($summary['averagePending'] ?? 0); ?>
                        </span>
                        <i class="fa fa-chart-line summary-icon"></i>
                    </div>
                </div>

                <div class="filter-card">
                    <div class="filter-header">
                        <span>Filters</span>
                        <span><?= number_format($recordsFiltered); ?> result(s)</span>
                    </div>

                    <form method="get" action="<?= site_url('Report/all_previous_fees_report'); ?>" class="filter-form">
                        <input type="text" name="search" value="<?= html_escape($search); ?>" class="control"
                            placeholder="Search student name, ID or mobile...">



                        <button type="submit" class="btn-filter">Search</button>

                        <a href="<?= site_url('Report/all_previous_fees_report'); ?>" class="btn-reset">Reset</a>

                        <a href="<?= site_url('Report/export_all_previous_fees_excel') . '?search=' . urlencode($search); ?>"
                            class="btn-filter" style="text-decoration:none;">
                            <i class="fa fa-file-excel"></i>Excel
                        </a>
                    </form>
                </div>
            </section>

            <section class="table-panel" id="reportTableContainer">
                <div class="table-responsive">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Mobile No.</th>

                                <?php foreach ($academicYears as $year): ?>
                                    <th class="text-right">
                                        <?= html_escape($year['academic_year_name'] ?? ''); ?>
                                    </th>
                                <?php endforeach; ?>

                                <th class="text-right">Total</th>
                                <th class="text-center">WhatsApp</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!$records): ?>
                                <tr>
                                    <td colspan="<?= count($academicYears) + 6; ?>" class="table-empty">
                                        No previous fees records found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($records as $row): ?>
                                    <?php
                                    $studentName = trim((string) ($row['name'] ?? ''));
                                    $mobileNumber = (string) ($row['MobileNumber'] ?? '');
                                    $whatsappNumber = $normalizeWhatsApp($mobileNumber);
                                    $studentTotal = (float) ($row['TotalPending'] ?? 0);

                                    $pendingDetails = '';

                                    foreach ($academicYears as $year) {
                                        $yearId = (int) ($year['academic_year_id'] ?? 0);
                                        $pendingAmount = (float) ($row['Pending_' . $yearId] ?? 0);

                                        if ($pendingAmount <= 0) {
                                            continue;
                                        }

                                        $pendingDetails .=
                                            'Academic Year: ' . ($year['academic_year_name'] ?? '') . "\n" .
                                            'Pending Fees: ₹' . number_format($pendingAmount, 0) . "\n" .
                                            "-----------------------------\n";
                                    }

                                    $message =
                                        "Assalamualaikum\n\n" .
                                        "Dear Parent,\n\n" .
                                        'This is to inform you that previous academic year fees for your child ' .
                                        $studentName . " are still pending.\n\n" .
                                        "Pending Fees Details\n" .
                                        "=============================\n\n" .
                                        $pendingDetails .
                                        "\nTotal Outstanding: ₹" . number_format($studentTotal, 0) . "\n\n" .
                                        "Kindly visit the school office and clear the pending fees at the earliest.\n\n" .
                                        'आपके बच्चे ' . $studentName . " की पिछली कक्षाओं की फीस अभी बाकी है।\n\n" .
                                        'कुल बकाया फीस: ₹' . number_format($studentTotal, 0) . "\n\n" .
                                        "कृपया स्कूल ऑफिस में आकर बाकी फीस जल्द से जल्द जमा करें।\n\n" .
                                        "आपके सहयोग के लिए धन्यवाद।\n\n" .
                                        "Regards,\nOlive English School\nDhule";

                                    $whatsappUrl = $whatsappNumber !== '' && $studentTotal > 0
                                        ? 'https://wa.me/' . $whatsappNumber . '?text=' . rawurlencode($message)
                                        : '';
                                    ?>

                                    <tr>
                                        <td class="text-center"><?= (int) ($row['serial'] ?? 0); ?></td>
                                        <td> <span class="student-id-badge">
                                                <?= html_escape($row['SPRIDEN_student_id'] ?? ''); ?>
                                            </span>
                                        </td>
                                        <td><?= html_escape($studentName); ?></td>
                                        <td><?= html_escape($mobileNumber); ?></td>

                                        <?php foreach ($academicYears as $year): ?>
                                            <?php
                                            $yearId = (int) ($year['academic_year_id'] ?? 0);
                                            $pendingAmount = (float) ($row['Pending_' . $yearId] ?? 0);
                                            ?>
                                            <td class="amount">
                                                <span class="<?= $pendingAmount > 0 ? ' pending-amount' : 'zero-value'; ?>">
                                                    <?= $formatAmount($pendingAmount); ?>
                                                </span>
                                            </td>
                                        <?php endforeach; ?>

                                        <td class="amount student-total">
                                            <?= $formatAmount($studentTotal); ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if ($whatsappUrl !== ''): ?>
                                                <a href="<?= html_escape($whatsappUrl); ?>" target="_blank"
                                                    rel="noopener noreferrer" class="whatsapp-button">
                                                    <i class="fab fa-whatsapp"></i> Send
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Filtered Total</th>

                                <?php foreach ($academicYears as $year): ?>
                                    <?php $yearId = (int) ($year['academic_year_id'] ?? 0); ?>
                                    <th class="text-right">
                                        <?= $formatAmount($yearTotals[$yearId] ?? 0); ?>
                                    </th>
                                <?php endforeach; ?>

                                <th class="text-right">
                                    <?= $formatAmount($summary['totalPending'] ?? 0); ?>
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="pagination-row">
                    <div class="pagination-info">
                        Showing <?= number_format($startRecord); ?> to <?= number_format($endRecord); ?>
                        of <?= number_format($recordsFiltered); ?> records
                    </div>

                    <div class="pagination-links">
                        <a href="<?= $page > 1 ? html_escape($buildPageUrl(1)) : '#'; ?>"
                            class="page-link <?= $page <= 1 ? 'disabled' : ''; ?>">
                            &laquo;
                        </a>

                        <a href="<?= $page > 1 ? html_escape($buildPageUrl($page - 1)) : '#'; ?>"
                            class="page-link <?= $page <= 1 ? 'disabled' : ''; ?>">
                            &lsaquo;
                        </a>

                        <?php for ($pageNumber = $startPage; $pageNumber <= $endPage; $pageNumber++): ?>
                            <a href="<?= html_escape($buildPageUrl($pageNumber)); ?>"
                                class="page-link <?= $pageNumber === $page ? 'active' : ''; ?>">
                                <?= $pageNumber; ?>
                            </a>
                        <?php endfor; ?>

                        <a href="<?= $page < $totalPages ? html_escape($buildPageUrl($page + 1)) : '#'; ?>"
                            class="page-link <?= $page >= $totalPages ? 'disabled' : ''; ?>">
                            &rsaquo;
                        </a>

                        <a href="<?= $page < $totalPages ? html_escape($buildPageUrl($totalPages)) : '#'; ?>"
                            class="page-link <?= $page >= $totalPages ? 'disabled' : ''; ?>">
                            &raquo;
                        </a>
                    </div>
                </div>
            </section>
        </div>


    </main>

    <?php include 'application/views/jsPages.php'; ?>

    <script>
        (function () {
            'use strict';

            const reportContent = document.getElementById('reportContent');

            if (!reportContent) {
                return;
            }

            let activeController = null;

            function getTableContainer() {
                return document.getElementById('reportTableContainer');
            }

            function setTableLoading(isLoading) {
                const tableContainer = getTableContainer();

                if (!tableContainer) {
                    return;
                }

                tableContainer.classList.toggle('table-loading', isLoading);
            }

            function createRequestUrl(url) {
                const requestUrl = new URL(url, window.location.origin);
                requestUrl.searchParams.set('ajax', '1');
                return requestUrl.toString();
            }

            async function loadReport(url, updateHistory = true) {
                if (!url || url === '#') {
                    return;
                }

                if (activeController) {
                    activeController.abort();
                }

                activeController = new AbortController();
                setTableLoading(true);

                try {
                    const response = await fetch(createRequestUrl(url), {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        signal: activeController.signal
                    });

                    if (!response.ok) {
                        throw new Error('Unable to load report. HTTP status: ' + response.status);
                    }

                    const html = await response.text();
                    const parsedDocument = new DOMParser().parseFromString(html, 'text/html');
                    const updatedContent = parsedDocument.getElementById('reportContent');

                    if (!updatedContent) {
                        throw new Error('AJAX response does not contain #reportContent.');
                    }

                    reportContent.innerHTML = updatedContent.innerHTML;

                    if (updateHistory) {
                        const browserUrl = new URL(url, window.location.origin);
                        browserUrl.searchParams.delete('ajax');

                        window.history.pushState({},
                            '',
                            browserUrl.pathname + browserUrl.search
                        );
                    }
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        console.error('Report AJAX error:', error);
                        alert('Unable to load the report. Please try again.');
                    }
                } finally {
                    setTableLoading(false);
                    activeController = null;
                }
            }

            document.addEventListener('click', function (event) {
                const paginationLink = event.target.closest('#reportContent .page-link');

                if (paginationLink) {
                    event.preventDefault();

                    if (
                        paginationLink.classList.contains('disabled') ||
                        paginationLink.getAttribute('href') === '#'
                    ) {
                        return;
                    }

                    loadReport(paginationLink.href);
                    return;
                }

                const resetLink = event.target.closest('#reportContent .btn-reset');

                if (resetLink) {
                    event.preventDefault();
                    loadReport(resetLink.href);
                }
            });

            document.addEventListener('submit', function (event) {
                const form = event.target.closest('#reportContent .filter-form');

                if (!form) {
                    return;
                }

                event.preventDefault();

                const formData = new FormData(form);
                formData.set('page', '1');

                const requestUrl = new URL(form.action, window.location.origin);

                for (const [key, value] of formData.entries()) {
                    requestUrl.searchParams.set(key, value);
                }

                loadReport(requestUrl.toString());
            });

            window.addEventListener('popstate', function () {
                loadReport(window.location.href, false);
            });
        })();
    </script>
</body>

</html>
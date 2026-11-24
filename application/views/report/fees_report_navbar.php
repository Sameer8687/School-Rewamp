<?php
$currentReport = $current_report ?? '';
$reportTitle = $report_title ?? 'Fees Report';
?>

<div class="report-navbar">
    <div class="report-title">
        <i class="fa fa-chart-line"></i>
        <span><?= html_escape($reportTitle); ?></span>
    </div>

    <div class="report-menu">
        <a href="<?= site_url('dashboard'); ?>">
            <i class="fa fa-home"></i> Dashboard
        </a>

        <a
            href="<?= site_url('Report/all_previous_fees_report'); ?>"
            class="<?= $currentReport === 'previous' ? 'active' : ''; ?>">
            Previous Fees
        </a>

        <a
            href="<?= site_url('Report/current_zero_paid_report'); ?>"
            class="<?= $currentReport === 'zero' ? 'active' : ''; ?>">
            Current Zero Paid
        </a>

        <a
            href="<?= site_url('Report/last_year_balance_report'); ?>"
            class="<?= $currentReport === 'balance' ? 'active' : ''; ?>">
            Last Year Balance
        </a>

        <a
            href="<?= site_url('Report/last_year_fees_report'); ?>"
            class="<?= $currentReport === 'last' ? 'active' : ''; ?>">
            Last Year Fees
        </a>
    </div>
</div>
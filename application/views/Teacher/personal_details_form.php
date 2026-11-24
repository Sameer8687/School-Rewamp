<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title> Professional Profile | Olive English School</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <?php include 'application/views/cssPages.php'; ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/teacher-module.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/new-db.css">

    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-white" style="font-family: 'Inter', -apple-system, sans-serif;">
    <?php include 'sidebar.php'; ?>
    <?php include 'topNav.php'; ?>

    <div class="pc-container" id="main-wrapper">
        <div class="pcoded-content">

            <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                <div>
                    <h5 class="mb-0" style="font-weight: 700; color: #111827; font-size: 18px;">Professional Profile
                    </h5>
                    <p class="text-muted mb-0 d-none d-sm-block" style="font-size: 12px;">Manage your expert
                        credentials, skills, and certifications.</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-ghost"
                        onclick="window.location.reload();">Discard</button>
                    <button type="submit" form="saasProfileForm" class="btn btn-sm btn-primary-saas"
                        id="submitBtn">Update</button>
                </div>
            </div>

            <form id="saasProfileForm" action="<?= base_url('dashboard/update_tc_profile'); ?>" method="POST"
                class="flex-grow-1 form-container">
                <input type="hidden" name="profile_id" value="<?= $profile->id ?? '' ?>">

                <div class="row gx-3 mb-2">
                    <div class="col-md-4">
                        <label class="label-saas">Email Address</label>
                        <input type="email" name="email" class="input-field"
                            value="<?= htmlspecialchars($profile->email ?? '') ?>" placeholder="teacher@school.edu">
                    </div>
                    <div class="col-md-4">
                        <label class="label-saas">Phone Number</label>
                        <input type="tel" name="phone" class="input-field"
                            value="<?= htmlspecialchars($profile->phone ?? '') ?>" placeholder="1234567890">
                    </div>
                    <div class="col-md-4">
                        <label class="label-saas">Location</label>
                        <input type="text" name="location" class="input-field"
                            value="<?= htmlspecialchars($profile->location ?? '') ?>" placeholder="City, Country">
                    </div>
                </div>

                <div class="row gx-3 mb-2 pb-2 border-bottom">
                    <div class="col-12">
                        <label class="label-saas">About (Short Bio)</label>
                        <textarea name="about_me" class="input-field" rows="2" style="resize: none; min-height: 60px;"
                            placeholder="Brief introduction..."><?= htmlspecialchars($profile->about_me ?? '') ?></textarea>
                    </div>
                </div>

                <div class="row gx-3 mb-2 pb-2 border-bottom">
                    <div class="col-md-3">
                        <label class="label-saas">Joining Date</label>
                        <input type="date" name="joining_date" class="input-field"
                            value="<?= htmlspecialchars($profile->joining_date ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="label-saas">Working Hours</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="time" name="work_start" class="input-field"
                                value="<?= htmlspecialchars($profile->work_start ?? '') ?>">
                            <span class="text-muted px-1">to</span>
                            <input type="time" name="work_end" class="input-field"
                                value="<?= htmlspecialchars($profile->work_end ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="label-saas">Designation</label>
                        <input type="text" name="designation" class="input-field"
                            value="<?= htmlspecialchars($profile->designation ?? '') ?>"
                            placeholder="e.g. Senior Teacher">
                    </div>
                    <div class="col-md-3">
                        <label class="label-saas">Department</label>
                        <input type="text" name="department" class="input-field"
                            value="<?= htmlspecialchars($profile->department ?? '') ?>" placeholder="e.g. Mathematics">
                    </div>
                </div>

                <div class="row h-100 gx-3">
                    <div class="col-lg-4 section-col border-right">
                        <div class="d-flex justify-content-between align-items-center mb-3 pr-2">
                            <h6 class="section-title mb-0">Education</h6>
                            <button type="button" class="btn-add" onclick="addEduRow()">+ Add New</button>
                        </div>
                        <div id="edu-container" class="custom-scroll pr-2 scroll-area">
                            <?php if (!empty($education)):
                                foreach ($education as $edu): ?>
                                    <div class="edu-card compact mb-2 position-relative">
                                        <button type="button" class="btn-delete"
                                            onclick="confirmDeleteAndRemove(this, 'education', '<?= $edu->id ?>', '<?= base_url('dashboard/delete_education') ?>')">×</button>
                                        <input type="hidden" name="edu_id[]" value="<?= $edu->id ?>">
                                        <input type="text" name="edu_title[]" class="input-minimal fw-600"
                                            value="<?= htmlspecialchars($edu->degree_title) ?>" placeholder="Degree Title">
                                        <div class="d-flex justify-content-between mt-1">
                                            <input type="text" name="edu_inst[]" class="input-minimal-sub w-75"
                                                value="<?= htmlspecialchars($edu->institution) ?>" placeholder="Institution">
                                            <input type="text" name="edu_year[]" class="input-minimal-sub w-25 text-right"
                                                value="<?= htmlspecialchars($edu->passing_year) ?>" placeholder="Year">
                                        </div>
                                    </div>
                                <?php endforeach; endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-4 section-col border-right">
                        <div class="mb-2">
                            <h6 class="section-title mb-3">Expertise Focus</h6>
                            <label class="label-saas">Primary Subject Speciality</label>
                            <input type="text" name="speciality" class="input-field fw-600"
                                placeholder="e.g. Pure Mathematics"
                                value="<?= htmlspecialchars($profile->speciality ?? '') ?>">
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="section-title mb-0">Core Skills</h6>
                            <span class="text-muted" style="font-size: 10px;">Press Enter</span>
                        </div>

                        <div class="skill-tag-container mb-2">
                            <div class="d-flex flex-wrap gap-1 mb-2" id="skill-badge-list">
                                <?php if (!empty($skills)):
                                    foreach ($skills as $sk): ?>
                                        <span class="tag">
                                            <?= htmlspecialchars($sk->tag_name) ?>
                                            <input type="hidden" name="skills[]" value="<?= htmlspecialchars($sk->tag_name) ?>">
                                            <input type="hidden" name="skill_id[]" value="<?= $sk->id ?>">
                                            <i
                                                onclick="confirmDeleteAndRemove(this, 'skill', '<?= $sk->id ?>', '<?= base_url('dashboard/delete_tag') ?>')">×</i>
                                        </span>
                                    <?php endforeach; endif; ?>
                            </div>
                            <input type="text" id="skill-input" class="input-field" placeholder="Add skill...">
                        </div>

                        <div class="mt-2">
                            <label class="label-saas">Secondary Languages</label>
                            <input type="text" name="languages" class="input-field"
                                value="<?= htmlspecialchars($profile->secondary_languages ?? '') ?>"
                                placeholder="e.g. English, Spanish">
                        </div>
                    </div>

                    <div class="col-lg-4 section-col">
                        <div class="section-block mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="section-title mb-0">Recognition</h6>
                                <button type="button" class="btn-add" onclick="addAward()">+ New</button>
                            </div>
                            <div id="awards-list" class="custom-scroll scroll-area-small">
                                <?php if (!empty($awards)):
                                    foreach ($awards as $aw): ?>
                                        <div class="award-pill mb-2 position-relative pr-4">
                                            <button type="button" class="btn-delete"
                                                onclick="confirmDeleteAndRemove(this, 'award', '<?= $aw->id ?>', '<?= base_url('dashboard/delete_award') ?>')">×</button>
                                            <input type="hidden" name="award_id[]" value="<?= $aw->id ?>">
                                            <input type="text" name="award_name[]" class="input-minimal fw-600 d-block"
                                                value="<?= htmlspecialchars($aw->award_name) ?>" placeholder="Award Title">
                                            <input type="text" name="award_meta[]" class="input-minimal-sub"
                                                value="<?= htmlspecialchars($aw->award_meta) ?>"
                                                placeholder="Organization • Year">
                                        </div>
                                    <?php endforeach; endif; ?>
                            </div>
                        </div>

                        <div class="section-block pt-3 border-top">
                            <h6 class="section-title mb-2">Professional Certifications</h6>
                            <div id="cert-badge-list"
                                class="d-flex flex-wrap gap-2 mb-2 custom-scroll scroll-area-small">
                                <?php if (!empty($certs)):
                                    foreach ($certs as $ct): ?>
                                        <span class="tag-cert">
                                            <?= htmlspecialchars($ct->tag_name) ?>
                                            <input type="hidden" name="certs[]" value="<?= htmlspecialchars($ct->tag_name) ?>">
                                            <input type="hidden" name="cert_id[]" value="<?= $ct->id ?>">
                                            <i
                                                onclick="confirmDeleteAndRemove(this, 'certification', '<?= $ct->id ?>', '<?= base_url('dashboard/delete_tag') ?>')">×</i>
                                        </span>
                                    <?php endforeach; endif; ?>
                            </div>
                            <input type="text" id="cert-input" class="input-field" placeholder="Add certification...">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        :root {
            --primary-dark: #111827;
            --border-light: #f3f4f6;
            --text-muted: #6b7280;
            --danger: #ef4444;
            --accent: #2563eb;
        }

        html,
        body {
            height: 100vh;
            overflow: hidden;
            margin: 0;
        }

        .pc-container {
            height: 100vh;
            overflow: hidden;
            background: #fff;
        }

        .pcoded-content {
            height: calc(100vh - 70px);
            overflow-y: auto;
            padding: 20px 24px;
        }

        .section-col {
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .scroll-area {
            flex-grow: 1;
            overflow-y: auto;
            max-height: calc(100vh - 280px);
        }

        .scroll-area-small {
            max-height: 180px;
            overflow-y: auto;
        }

        .border-right {
            border-right: 1px solid var(--border-light);
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .label-saas {
            font-size: 11px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 4px;
            display: block;
        }

        .input-field {
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 6px 10px;
            font-size: 13px;
            background: #fafafa;
            transition: all 0.2s;
        }

        .input-field:focus {
            outline: none;
            border-color: var(--accent);
            background: #fff;
        }

        .input-minimal {
            border: none;
            background: transparent;
            width: 100%;
            font-size: 14px;
            outline: none;
            padding: 0px 0;
        }

        .input-minimal-sub {
            font-size: 12px;
            color: var(--text-muted);
            border: none;
            background: transparent;
            outline: none;
        }

        .tag,
        .tag-cert {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .tag {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #dbeafe;
        }

        .tag-cert {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .tag i,
        .tag-cert i {
            cursor: pointer;
            font-style: normal;
            font-weight: bold;
            opacity: 0.6;
        }

        .edu-card.compact {
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #fff;
            transition: 0.2s;
        }

        .award-pill {
            border-left: 2px solid var(--primary-dark);
            padding-left: 10px;
        }

        .btn-primary-saas {
            background: var(--primary-dark);
            color: white;
            border: none;
            padding: 6px 16px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
        }

        .btn-add {
            color: var(--accent);
            background: none;
            border: none;
            font-weight: 700;
            font-size: 11px;
            cursor: pointer;
        }

        .btn-delete {
            position: absolute;
            top: 4px;
            right: 8px;
            background: none;
            border: none;
            color: #d1d5db;
            font-size: 18px;
            cursor: pointer;
            z-index: 1;
        }

        .btn-delete:hover {
            color: var(--danger);
        }

        .custom-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }
    </style>

    <script>
        // Use SweetAlert2 for Toast notifications
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        function confirmDeleteAndRemove(btn, type, id, deleteUrl) {
            Swal.fire({
                title: 'Are you sure?',
                text: `This ${type} will be permanently removed.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#111827',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const element = btn.closest('.edu-card, .award-pill, .tag, .tag-cert');

                    if (id) {
                        // Backend delete
                        fetch(deleteUrl, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: `id=${id}`
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    removeElement(element);
                                    Toast.fire({ icon: 'success', title: 'Deleted successfully' });
                                } else {
                                    Swal.fire('Error', 'Could not delete item.', 'error');
                                }
                            })
                            .catch(() => Swal.fire('Error', 'Server communication failed.', 'error'));
                    } else {
                        // Frontend only delete (for newly added rows)
                        removeElement(element);
                        Toast.fire({ icon: 'info', title: 'Item removed' });
                    }
                }
            });
        }

        function removeElement(el) {
            el.style.opacity = '0';
            el.style.transform = 'scale(0.9)';
            el.style.transition = 'all 0.2s ease';
            setTimeout(() => el.remove(), 200);
        }

        function handleInput(inputId, listId, className, nameAttr, idAttr) {
            const input = document.getElementById(inputId);
            const list = document.getElementById(listId);
            input.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const val = this.value.trim();
                    if (val && !isDuplicate(list, val)) {
                        const span = document.createElement('span');
                        span.className = className;
                        span.innerHTML = `${val} 
                            <input type="hidden" name="${nameAttr}" value="${val}">
                            <input type="hidden" name="${idAttr}" value="">
                            <i onclick="confirmDeleteAndRemove(this, '${className === 'tag' ? 'skill' : 'certification'}', '', '')">×</i>`;
                        list.appendChild(span);
                        this.value = '';
                    }
                }
            });
        }

        function isDuplicate(list, value) {
            return Array.from(list.querySelectorAll('span')).some(item => item.textContent.replace('×', '').trim() === value);
        }

        function addEduRow() {
            const container = document.getElementById('edu-container');
            const div = document.createElement('div');
            div.className = 'edu-card compact mb-2 position-relative';
            div.innerHTML = `
                <button type="button" class="btn-delete" onclick="confirmDeleteAndRemove(this, 'education', '', '')">×</button>
                <input type="hidden" name="edu_id[]" value="">
                <input type="text" name="edu_title[]" class="input-minimal fw-600" placeholder="Degree Title">
                <div class="d-flex justify-content-between mt-1">
                    <input type="text" name="edu_inst[]" class="input-minimal-sub w-75" placeholder="Institution">
                    <input type="text" name="edu_year[]" class="input-minimal-sub w-25 text-right" placeholder="Year">
                </div>`;
            container.appendChild(div);
            div.scrollIntoView({ behavior: 'smooth', block: 'end' });
        }

        function addAward() {
            const container = document.getElementById('awards-list');
            const div = document.createElement('div');
            div.className = 'award-pill mb-2 position-relative pr-4';
            div.innerHTML = `
                <button type="button" class="btn-delete" onclick="confirmDeleteAndRemove(this, 'award', '', '')">×</button>
                <input type="hidden" name="award_id[]" value="">
                <input type="text" name="award_name[]" class="input-minimal fw-600 d-block" placeholder="New Recognition">
                <input type="text" name="award_meta[]" class="input-minimal-sub" placeholder="Organization • Year">`;
            container.appendChild(div);
        }

        document.getElementById('saasProfileForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Updating...';
        });

        document.addEventListener('DOMContentLoaded', () => {
            handleInput('skill-input', 'skill-badge-list', 'tag', 'skills[]', 'skill_id[]');
            handleInput('cert-input', 'cert-badge-list', 'tag-cert', 'certs[]', 'cert_id[]');

            // Handle Flash Messages with SweetAlert
            <?php if ($this->session->flashdata('success')): ?>
                Toast.fire({ icon: 'success', title: '<?= $this->session->flashdata('success') ?>' });
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                Swal.fire('Error', '<?= $this->session->flashdata('error') ?>', 'error');
            <?php endif; ?>
        });
    </script>
</body>

</html>
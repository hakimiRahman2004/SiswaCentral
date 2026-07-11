<?php
$title = 'Applicants';
?>
<div class="animate-fade-in max-w-6xl mx-auto">
    <div class="page-header">
        <h1 class="page-title">Applicants — <?= e($scholarship['name'] ?? '') ?></h1>
        <p class="page-desc">Manage applicants for this scholarship</p>
    </div>

    <?php if (!empty($applicants)): ?>
    <div class="card overflow-hidden">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Student</th><th>Contact</th><th>CGPA</th><th>Course / University</th><th>Income</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($applicants as $a): ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold shrink-0" style="background: var(--color-primary-50); color: var(--color-primary-600);">
                                    <?= e(strtoupper(substr($a['student_name'] ?? 'U', 0, 1))) ?>
                                </div>
                                <span class="font-medium text-surface-800"><?= e($a['student_name'] ?? '') ?></span>
                            </div>
                        </td>
                        <td class="text-xs text-surface-500"><?= e($a['student_email'] ?? '') ?><?= $a['student_phone'] ? '<br>' . e($a['student_phone']) : '' ?></td>
                        <td class="font-semibold text-surface-800"><?= e($a['cgpa'] ?? '-') ?></td>
                        <td class="text-xs text-surface-500"><?= e($a['course'] ?? '-') ?><br><span class="text-surface-400"><?= e($a['university'] ?? '') ?></span></td>
                        <td class="text-xs text-surface-500"><?= $a['student_income'] ? 'RM' . e(number_format((float)$a['student_income'])) : '-' ?></td>
                        <td><span class="badge <?= match($a['status'] ?? '') {'approved'=>'badge-success','rejected'=>'badge-danger','screening'=>'badge-warning','shortlisted'=>'badge-primary','interview'=>'badge-info','submitted'=>'badge-surface', default=>'badge-surface'} ?>"><?= e(ucfirst($a['status'] ?? '')) ?></span></td>
                        <td>
                            <div class="flex gap-1">
                                <a href="<?= url('/provider/applications/' . e($a['id'])) ?>" class="btn btn-ghost btn-sm">Detail</a>
                                <form method="POST" action="<?= url('/provider/applications/' . e($a['id']) . '//status') ?>" class="flex gap-1 items-center">
                                    <?= csrfField() ?>
                                    <select name="status" class="input text-xs" style="width:auto;padding:0.25rem 0.5rem;height:auto">
                                        <option value="screening" <?= ($a['status']??'')==='screening'?'selected':'' ?>>Screening</option>
                                        <option value="shortlisted" <?= ($a['status']??'')==='shortlisted'?'selected':'' ?>>Shortlisted</option>
                                        <option value="interview" <?= ($a['status']??'')==='interview'?'selected':'' ?>>Interview</option>
                                        <option value="approved" <?= ($a['status']??'')==='approved'?'selected':'' ?>>Approved</option>
                                        <option value="rejected" <?= ($a['status']??'')==='rejected'?'selected':'' ?>>Rejected</option>
                                    </select>
                                    <button type="submit" class="btn btn-ghost btn-sm">Update</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <p class="empty-title">No applicants yet</p>
        <p class="empty-desc">Applications will appear here once students start applying.</p>
    </div>
    <?php endif; ?>
</div>

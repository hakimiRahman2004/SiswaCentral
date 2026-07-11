<?php
/** @var array<int, array<string, mixed>> $documents */
/** @var array $usage */
$title = 'My Documents';
$docTypes = ['ic' => 'IC / NRIC', 'passport' => 'Passport', 'transcript' => 'Academic Transcript', 'income' => 'Income Statement', 'resume' => 'Resume / CV', 'certificate' => 'Certificate', 'other' => 'Other'];
$existingTypes = array_unique(array_map(fn($d) => $d['type'], $documents ?? []));

// Suggest next missing type
$nextType = 'ic';
foreach (array_keys($docTypes) as $t) {
    if (!in_array($t, $existingTypes)) { $nextType = $t; break; }
}
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">My Documents</h1>
        <p class="page-desc">Upload and manage your scholarship documents — upload once, use everywhere</p>
    </div>

    <?php $usage = $usage ?? []; ?>
    <!-- Storage Meter -->
    <div class="card p-5 mb-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="section-title">Storage</h2>
            <?php if (!($usage['isPremium'] ?? false)): ?>
                <a href="<?= url('/subscription') ?>" class="btn btn-soft btn-sm">Upgrade for more</a>
            <?php endif; ?>
        </div>
        <?php $docPct = ($usage['documents']['max'] ?? 0) > 0 ? min(100, round(($usage['documents']['used'] ?? 0) / $usage['documents']['max'] * 100)) : 0; ?>
        <div class="progress-bar">
            <div class="progress-bar-fill <?= $docPct >= 80 ? 'warning' : ($docPct >= 50 ? 'accent' : 'primary') ?>" style="width: <?= $docPct ?>%"></div>
        </div>
        <p class="text-xs text-surface-500 mt-2"><?= ($usage['documents']['used'] ?? 0) ?> of <?= ($usage['documents']['max'] ?? '∞') ?> documents used</p>
    </div>

    <!-- Upload Form -->
    <div class="card p-5 mb-6">
        <h2 class="section-title mb-4">Upload New Document</h2>

        <!-- Document type progress -->
        <div class="flex flex-wrap gap-1.5 mb-4">
            <?php foreach ($docTypes as $key => $label): ?>
                <?php $hasType = in_array($key, $existingTypes); ?>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium <?= $hasType ? 'text-success-700 bg-success-50' : 'text-surface-500 bg-surface-100' ?>">
                    <?php if ($hasType): ?>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    <?php endif; ?>
                    <?= e($label) ?>
                </span>
            <?php endforeach; ?>
        </div>

        <form method="POST" action="<?= url('/documents/upload') ?>" enctype="multipart/form-data">
            <?= csrfField() ?>
            <div class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="input-label">Document type</label>
                    <select name="type" class="input">
                        <?php foreach ($docTypes as $key => $label): ?>
                        <option value="<?= e($key) ?>" <?= $key === $nextType ? 'selected' : '' ?>><?= e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex-1 min-w-48">
                    <label class="input-label">File</label>
                    <input type="file" name="document" class="input text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                    Upload
                </button>
            </div>
        </form>
    </div>

    <!-- Documents List -->
    <?php if (!empty($documents)): ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Type</th><th>File Name</th><th>Size</th><th>Uploaded</th><th></th></tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $d): ?>
                <tr>
                    <td><span class="badge badge-primary"><?= e($docTypes[$d['type']] ?? ucfirst($d['type'])) ?></span></td>
                    <td class="font-medium text-surface-800">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/></svg>
                            <?= e($d['file_name'] ?? '') ?>
                        </div>
                    </td>
                    <td class="text-surface-500"><?= e(round(($d['file_size'] ?? 0) / 1024)) ?> KB</td>
                    <td class="text-surface-500 text-xs"><?= date('d M Y', strtotime($d['created_at'])) ?></td>
                    <td>
                        <form method="POST" action="<?= url('/documents/' . e($d['id']) . '//delete') ?>" onsubmit="return confirm('Delete this document?')">
                            <?= csrfField() ?>
                            <button type="submit" class="btn btn-ghost btn-sm" style="color: var(--color-danger-500);">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z"/></svg>
        <p class="empty-title">No documents uploaded</p>
        <p class="empty-desc">Upload your IC, transcript, and other documents above. You only need to upload them once.</p>
    </div>
    <?php endif; ?>
</div>

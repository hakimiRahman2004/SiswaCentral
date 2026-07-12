<?php
/** @var array $scholarship */
/** @var array $profile */
/** @var array $user */
/** @var array $documents */
$existingDetails = null; // for draft loading
?>
<div class="animate-fade-in">
    <div class="max-w-3xl mx-auto">
        <a href="<?= url('/scholarships/' . e($scholarship['id'])) ?>" class="btn btn-ghost btn-sm mb-4">&larr; Back to Scholarship</a>

        <div class="page-header mb-6">
            <h1 class="page-title">Apply for Scholarship</h1>
            <p class="page-desc"><?= e($scholarship['name']) ?> &middot; <?= e($scholarship['company_name'] ?? '') ?></p>
        </div>

        <!-- Scholarship Summary -->
        <div class="card p-4 mb-6 flex flex-wrap items-center gap-4">
            <div class="flex-1">
                <p class="text-xs text-surface-500">Amount</p>
                <p class="font-bold text-lg text-surface-800">RM<?= e(number_format((float)($scholarship['amount'] ?? 0))) ?></p>
            </div>
            <div class="flex-1">
                <p class="text-xs text-surface-500">Min. CGPA</p>
                <p class="font-bold text-surface-800"><?= e($scholarship['min_cgpa'] ?? 'N/A') ?></p>
            </div>
            <div class="flex-1">
                <p class="text-xs text-surface-500">Max Income</p>
                <p class="font-bold text-surface-800"><?= $scholarship['max_income'] ? 'RM' . e(number_format((float)$scholarship['max_income'])) : 'N/A' ?></p>
            </div>
            <div class="flex-1">
                <p class="text-xs text-surface-500">Deadline</p>
                <p class="font-bold text-surface-800"><?= date('d M Y', strtotime($scholarship['deadline'])) ?></p>
            </div>
        </div>

        <form method="POST" action="<?= url('/applications/new/' . e($scholarship['id'])) ?>" id="applicationForm">
            <?= csrfField() ?>

            <!-- Section 1: Personal Information -->
            <div class="card p-6 mb-4">
                <h2 class="section-title mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-primary-50 text-primary-700 text-xs font-bold flex items-center justify-center">1</span>
                    Personal Information
                </h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="input-label">Full Name</label>
                        <input type="text" value="<?= e($user['name'] ?? '') ?>" class="input" disabled>
                    </div>
                    <div>
                        <label class="input-label">Email</label>
                        <input type="email" value="<?= e($user['email'] ?? '') ?>" class="input" disabled>
                    </div>
                    <div>
                        <label class="input-label">Phone</label>
                        <input type="text" value="<?= e($profile['phone'] ?? '') ?>" class="input" disabled>
                    </div>
                    <div>
                        <label class="input-label">Gender</label>
                        <input type="text" value="<?= e(ucfirst($profile['gender'] ?? '')) ?>" class="input" disabled>
                    </div>
                    <div>
                        <label class="input-label">Date of Birth</label>
                        <input type="text" value="<?= $profile['date_of_birth'] ? date('d M Y', strtotime($profile['date_of_birth'])) : '' ?>" class="input" disabled>
                    </div>
                    <div>
                        <label class="input-label">Address</label>
                        <input type="text" value="<?= e($profile['address'] ?? '') ?>" class="input" disabled>
                    </div>
                </div>
                <p class="text-xs text-surface-400 mt-3">Auto-filled from your <a href="<?= url('/profile') ?>" class="text-primary-600 underline">profile</a>. Edit there if needed.</p>
            </div>

            <!-- Section 2: Academic Information -->
            <div class="card p-6 mb-4">
                <h2 class="section-title mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-primary-50 text-primary-700 text-xs font-bold flex items-center justify-center">2</span>
                    Academic Information
                </h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="input-label">CGPA <span class="text-danger-500">*</span></label>
                        <input type="text" name="cgpa" value="<?= e(old('cgpa', $profile['cgpa'] ?? '')) ?>" class="input" required placeholder="e.g. 3.75" step="0.01" min="0" max="4">
                    </div>
                    <div>
                        <label class="input-label">Course / Program <span class="text-danger-500">*</span></label>
                        <input type="text" name="course" value="<?= e(old('course', $profile['course'] ?? '')) ?>" class="input" required placeholder="e.g. Computer Science">
                    </div>
                    <div>
                        <label class="input-label">University / Institution <span class="text-danger-500">*</span></label>
                        <input type="text" name="university" value="<?= e(old('university', $profile['university'] ?? '')) ?>" class="input" required placeholder="e.g. Universiti Malaya">
                    </div>
                    <div>
                        <label class="input-label">Household Income (RM/month)</label>
                        <input type="number" name="income" value="<?= e(old('income', $profile['income'] ?? '')) ?>" class="input" placeholder="e.g. 3500">
                    </div>
                </div>
            </div>

            <!-- Section 3: Achievements -->
            <div class="card p-6 mb-4">
                <h2 class="section-title mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-primary-50 text-primary-700 text-xs font-bold flex items-center justify-center">3</span>
                    Achievements & Activities
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="input-label">Academic & Non-Academic Achievements <span class="text-danger-500">*</span></label>
                        <textarea name="achievements" rows="4" class="input" required placeholder="List your achievements, awards, honors, certifications..."><?= e(old('achievements', '')) ?></textarea>
                        <p class="text-xs text-surface-400 mt-1">Include awards, competitions, certifications, Dean's List, etc.</p>
                    </div>
                    <div>
                        <label class="input-label">Co-Curricular Activities</label>
                        <textarea name="co_curricular" rows="3" class="input" placeholder="List your involvement in clubs, societies, volunteering, sports..."><?= e(old('co_curricular', '')) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Section 4: Motivation Letter -->
            <div class="card p-6 mb-4">
                <h2 class="section-title mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-primary-50 text-primary-700 text-xs font-bold flex items-center justify-center">4</span>
                    Motivation Letter
                </h2>
                <div>
                    <label class="input-label">Why do you deserve this scholarship? <span class="text-danger-500">*</span></label>
                    <textarea name="motivation_letter" rows="8" class="input" required placeholder="Write a compelling motivation letter explaining why you deserve this scholarship, your goals, and how it will help you..."><?= e(old('motivation_letter', '')) ?></textarea>
                    <p class="text-xs text-surface-400 mt-1">Minimum 50 characters. Be specific about your goals and how this scholarship will help.</p>
                </div>
            </div>

            <!-- Section 5: Emergency Contact -->
            <div class="card p-6 mb-4">
                <h2 class="section-title mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-primary-50 text-primary-700 text-xs font-bold flex items-center justify-center">5</span>
                    Emergency Contact
                </h2>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="input-label">Contact Name</label>
                        <input type="text" name="emergency_name" value="<?= e(old('emergency_name', '')) ?>" class="input" placeholder="Full name">
                    </div>
                    <div>
                        <label class="input-label">Contact Phone</label>
                        <input type="text" name="emergency_phone" value="<?= e(old('emergency_phone', '')) ?>" class="input" placeholder="+60 12-345 6789">
                    </div>
                    <div>
                        <label class="input-label">Relationship</label>
                        <select name="emergency_relationship" class="input">
                            <option value="">Select...</option>
                            <option value="parent">Parent</option>
                            <option value="guardian">Guardian</option>
                            <option value="sibling">Sibling</option>
                            <option value="spouse">Spouse</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 6: Supporting Documents -->
            <?php if (!empty($documents)): ?>
            <div class="card p-6 mb-4">
                <h2 class="section-title mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-primary-50 text-primary-700 text-xs font-bold flex items-center justify-center">6</span>
                    Supporting Documents
                </h2>
                <p class="text-sm text-surface-500 mb-3">Select documents from your vault to attach to this application.</p>
                <div class="space-y-2">
                    <?php foreach ($documents as $doc): ?>
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-surface-200 hover:border-primary-300 cursor-pointer transition-colors">
                        <input type="checkbox" name="document_ids[]" value="<?= e($doc['id']) ?>" class="rounded border-surface-300 text-primary-600 focus:ring-primary-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-surface-800"><?= e($doc['file_name'] ?? $doc['type']) ?></p>
                            <p class="text-xs text-surface-400"><?= e(ucfirst(str_replace('_', ' ', $doc['type'] ?? ''))) ?> &middot; <?= e(round(($doc['file_size'] ?? 0) / 1024)) ?> KB</p>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
                <p class="text-xs text-surface-400 mt-2">Need to upload more? <a href="<?= url('/documents') ?>" class="text-primary-600 underline">Go to Document Vault</a></p>
            </div>
            <?php endif; ?>

            <!-- Preview & Submit -->
            <div class="card p-6 mb-6">
                <h2 class="section-title mb-4">Review & Submit</h2>
                <div class="flex items-start gap-3 p-4 rounded-lg bg-surface-50 mb-4">
                    <svg class="w-5 h-5 text-surface-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="text-sm text-surface-600">
                        <p class="font-medium text-surface-800 mb-1">Before you submit:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Ensure all information is accurate and complete</li>
                            <li>Your profile data will be shared with the scholarship provider</li>
                            <li>You can withdraw your application while it's still pending or under review</li>
                        </ul>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary btn-lg flex-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        Submit Application
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

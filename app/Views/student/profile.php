<?php
/** @var array<string, mixed> $profile */
$title = 'My Profile';
$user = \App\Core\Auth::user();
?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">My Profile</h1>
        <p class="page-desc">Your academic and personal information used for scholarship matching</p>
    </div>

    <!-- Profile Summary Card -->
    <div class="card p-6 mb-6">
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-surface-100">
            <div class="avatar avatar-xl" style="background: var(--color-primary-100); color: var(--color-primary-700);"><?= e(strtoupper(substr($user['name'] ?? 'U', 0, 1))) ?></div>
            <div>
                <h2 class="text-lg font-bold font-heading text-surface-900"><?= e($user['name'] ?? '') ?></h2>
                <p class="text-sm text-surface-500"><?= e($user['email'] ?? '') ?></p>
                <span class="badge badge-primary mt-1">Student</span>
            </div>
        </div>

        <form method="POST" action="<?= url('/profile') ?>">
            <?= csrfField() ?>

            <!-- Personal Info -->
            <h3 class="section-title mb-4">Personal Information</h3>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="input-label">Phone</label>
                    <input type="text" name="phone" value="<?= e(old('phone', $profile['phone'] ?? '')) ?>" class="input" placeholder="+60 12-345 6789">
                    <?php if ($err = error('phone')): ?><p class="input-error-text"><?= e($err) ?></p><?php endif; ?>
                </div>
                <div>
                    <label class="input-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="<?= e(old('date_of_birth', $profile['date_of_birth'] ?? '')) ?>" class="input">
                </div>
                <div>
                    <label class="input-label">Gender</label>
                    <select name="gender" class="input">
                        <option value="">Select...</option>
                        <option value="male" <?= (old('gender', $profile['gender'] ?? '') === 'male') ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= (old('gender', $profile['gender'] ?? '') === 'female') ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="input-label">Address</label>
                    <textarea name="address" rows="2" class="input" placeholder="Your current address"><?= e(old('address', $profile['address'] ?? '')) ?></textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="input-label">About You</label>
                    <textarea name="about" rows="3" class="input" placeholder="Brief description about yourself — this helps providers learn more about you"><?= e(old('about', $profile['about'] ?? '')) ?></textarea>
                </div>
            </div>

            <!-- Academic Info -->
            <h3 class="section-title mb-4 mt-8">Academic Information</h3>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="input-label">CGPA</label>
                    <input type="text" name="cgpa" value="<?= e(old('cgpa', $profile['cgpa'] ?? '')) ?>" class="input" placeholder="e.g. 3.50">
                </div>
                <div>
                    <label class="input-label">Course / Major</label>
                    <input type="text" name="course" value="<?= e(old('course', $profile['course'] ?? '')) ?>" class="input" placeholder="e.g. Computer Science">
                </div>
                <div>
                    <label class="input-label">University</label>
                    <input type="text" name="university" value="<?= e(old('university', $profile['university'] ?? '')) ?>" class="input" placeholder="e.g. Universiti Malaya">
                </div>
                <div>
                    <label class="input-label">Household Income (RM/month)</label>
                    <input type="text" name="income" value="<?= e(old('income', $profile['income'] ?? '')) ?>" class="input" placeholder="e.g. 5000">
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-surface-100 flex items-center gap-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    Save Profile
                </button>
            </div>
        </form>
    </div>
</div>

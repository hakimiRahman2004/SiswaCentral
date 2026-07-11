<?php
$layout = 'clean';
$title = 'Create Account';
?>
<div class="min-h-screen flex">
    <!-- Left: Form -->
    <div class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm">
            <!-- Logo -->
            <div class="text-center mb-10">
                <a href="<?= url('/') ?>" class="inline-flex items-center gap-2.5 text-2xl font-bold font-heading text-primary-700 tracking-tight mb-2">
                    <svg class="w-8 h-8" viewBox="0 0 32 32" fill="none">
                        <rect width="32" height="32" rx="8" fill="url(#rg-gradient)"/>
                        <path d="M16 7c-1.5 3-4.5 5-8 5v1c3.5 0 6.5 2 8 5 1.5-3 4.5-5 8-5v-1c-3.5 0-6.5-2-8-5z" fill="white" opacity="0.9"/>
                        <path d="M16 14c-1 2-3 3.5-5.5 3.5v.5c2.5 0 4.5 1.5 5.5 3.5 1-2 3-3.5 5.5-3.5v-.5c-2.5 0-4.5-1.5-5.5-3.5z" fill="white" opacity="0.6"/>
                        <defs><linearGradient id="rg-gradient" x1="0" y1="0" x2="32" y2="32" gradientUnits="userSpaceOnUse"><stop stop-color="#292929"/><stop offset="1" stop-color="#0e0e0e"/></linearGradient></defs>
                    </svg>
                    SISWACentral
                </a>
                <p class="text-surface-500 text-sm">Create your account to get started</p>
            </div>

            <?php if ($error = flash('error')): ?>
                <div class="alert alert-error" role="alert">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/register') ?>" class="space-y-4">
                <?= csrfField() ?>

                <div>
                    <label for="name" class="input-label">Full name</label>
                    <input id="name" name="name" type="text" value="<?= e(old('name')) ?>"
                           class="input <?= error('name') ? 'input-error' : '' ?>"
                           placeholder="Ahmad bin Ali" required autofocus autocomplete="name">
                    <?php if ($err = error('name')): ?>
                        <p class="input-error-text"><?= e($err) ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="email" class="input-label">Email address</label>
                    <input id="email" name="email" type="email" value="<?= e(old('email')) ?>"
                           class="input <?= error('email') ? 'input-error' : '' ?>"
                           placeholder="you@example.com" required autocomplete="email">
                    <?php if ($err = error('email')): ?>
                        <p class="input-error-text"><?= e($err) ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="password" class="input-label">Password</label>
                    <input id="password" name="password" type="password"
                           class="input <?= error('password') ? 'input-error' : '' ?>"
                           placeholder="Create a password (min. 8 characters)" required autocomplete="new-password">
                    <?php if ($err = error('password')): ?>
                        <p class="input-error-text"><?= e($err) ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="role" class="input-label">I am a...</label>
                    <select id="role" name="role" class="input" required
                            onchange="document.getElementById('provider-doc').classList.toggle('hidden', this.value !== 'provider')">
                        <option value="student" <?= old('role') === 'student' ? 'selected' : '' ?>>Student</option>
                        <option value="provider" <?= old('role') === 'provider' ? 'selected' : '' ?>>Scholarship Provider</option>
                    </select>
                </div>

                <div id="provider-doc" class="hidden space-y-1">
                    <label for="document" class="input-label">Business registration document</label>
                    <div class="relative">
                        <input id="document" name="document" type="file" accept=".pdf,.jpg,.png"
                               class="input text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                    </div>
                    <p class="input-hint">Upload your company registration document (PDF, JPG, or PNG)</p>
                    <?php if ($err = error('document')): ?>
                        <p class="input-error-text"><?= e($err) ?></p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary w-full btn-lg mt-2">
                    Create Account
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-surface-500">
                    Already have an account?
                    <a href="<?= url('/login') ?>" class="font-semibold text-primary-600 hover:text-primary-700 transition-colors">Sign in</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Right: Steps panel -->
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 items-center justify-center p-12 relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-white/5"></div>

        <div class="max-w-sm relative z-10">
            <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-8 ring-1 ring-white/20">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0zM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            </div>

            <h2 class="text-2xl font-bold font-heading text-white mb-8 text-center tracking-tight">Get started in 3 simple steps</h2>

            <div class="space-y-6">
                <div class="flex items-start gap-4 bg-white/5 backdrop-blur-sm rounded-xl p-4 ring-1 ring-white/10">
                    <div class="w-9 h-9 bg-primary-500 rounded-lg flex items-center justify-center shrink-0 text-white font-bold text-sm">1</div>
                    <div>
                        <h3 class="text-white font-semibold text-sm">Create Your Profile</h3>
                        <p class="text-primary-300 text-xs mt-0.5">Tell us about yourself and set up your academic profile</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 bg-white/5 backdrop-blur-sm rounded-xl p-4 ring-1 ring-white/10">
                    <div class="w-9 h-9 bg-primary-500 rounded-lg flex items-center justify-center shrink-0 text-white font-bold text-sm">2</div>
                    <div>
                        <h3 class="text-white font-semibold text-sm">Discover Scholarships</h3>
                        <p class="text-primary-300 text-xs mt-0.5">Browse and find scholarships that match your profile</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 bg-white/5 backdrop-blur-sm rounded-xl p-4 ring-1 ring-white/10">
                    <div class="w-9 h-9 bg-primary-500 rounded-lg flex items-center justify-center shrink-0 text-white font-bold text-sm">3</div>
                    <div>
                        <h3 class="text-white font-semibold text-sm">Apply &amp; Track</h3>
                        <p class="text-primary-300 text-xs mt-0.5">Submit applications and track their progress in real-time</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

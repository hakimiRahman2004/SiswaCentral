<?php
$layout = 'clean';
$title = 'Sign In';
?>
<div class="min-h-screen flex">
    <!-- Left: Form -->
    <div class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm">
            <!-- Logo -->
            <div class="text-center mb-10">
                <a href="<?= url('/') ?>" class="inline-flex items-center gap-2.5 text-2xl font-bold font-heading text-primary-700 tracking-tight mb-2">
                    <svg class="w-8 h-8" viewBox="0 0 32 32" fill="none">
                        <rect width="32" height="32" rx="8" fill="url(#lg-gradient)"/>
                        <path d="M16 7c-1.5 3-4.5 5-8 5v1c3.5 0 6.5 2 8 5 1.5-3 4.5-5 8-5v-1c-3.5 0-6.5-2-8-5z" fill="white" opacity="0.9"/>
                        <path d="M16 14c-1 2-3 3.5-5.5 3.5v.5c2.5 0 4.5 1.5 5.5 3.5 1-2 3-3.5 5.5-3.5v-.5c-2.5 0-4.5-1.5-5.5-3.5z" fill="white" opacity="0.6"/>
                        <defs><linearGradient id="lg-gradient" x1="0" y1="0" x2="32" y2="32" gradientUnits="userSpaceOnUse"><stop stop-color="#292929"/><stop offset="1" stop-color="#0e0e0e"/></linearGradient></defs>
                    </svg>
                    SISWACentral
                </a>
                <p class="text-surface-500 text-sm">Welcome back — sign in to your account</p>
            </div>

            <?php if ($error = flash('error')): ?>
                <div class="alert alert-error" role="alert">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/login') ?>" class="space-y-5">
                <?= csrfField() ?>

                <div>
                    <label for="email" class="input-label">Email address</label>
                    <input id="email" name="email" type="email" value="<?= e(old('email')) ?>"
                           class="input <?= error('email') ? 'input-error' : '' ?>"
                           placeholder="you@example.com" required autofocus autocomplete="email">
                    <?php if ($err = error('email')): ?>
                        <p class="input-error-text"><?= e($err) ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="password" class="input-label">Password</label>
                    <input id="password" name="password" type="password"
                           class="input <?= error('password') ? 'input-error' : '' ?>"
                           placeholder="Enter your password" required autocomplete="current-password">
                    <?php if ($err = error('password')): ?>
                        <p class="input-error-text"><?= e($err) ?></p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary w-full btn-lg">
                    Sign in
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-surface-500">
                    Don't have an account?
                    <a href="<?= url('/register') ?>" class="font-semibold text-primary-600 hover:text-primary-700 transition-colors">Create one</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Right: Hero panel -->
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 items-center justify-center p-12 relative overflow-hidden">
        <!-- Decorative circles -->
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-white/5"></div>
        <div class="absolute top-1/3 left-1/4 w-32 h-32 rounded-full bg-white/[0.03]"></div>

        <div class="max-w-md text-center relative z-10">
            <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-8 ring-1 ring-white/20">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.905 59.905 0 0 1 12 3.493a59.902 59.902 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342"/></svg>
            </div>
            <h2 class="text-3xl font-bold font-heading text-white mb-4 tracking-tight">One Platform for All Scholarships</h2>
            <p class="text-primary-200 text-sm leading-relaxed max-w-sm mx-auto">
                SISWACentral connects students with scholarship providers across Malaysia. Apply once, discover thousands of opportunities.
            </p>
            <div class="mt-10 flex items-center justify-center gap-8">
                <div class="text-center">
                    <p class="text-2xl font-bold text-white font-heading">500+</p>
                    <p class="text-primary-300 text-xs mt-1">Scholarships</p>
                </div>
                <div class="w-px h-10 bg-white/20"></div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-white font-heading">10K+</p>
                    <p class="text-primary-300 text-xs mt-1">Students</p>
                </div>
                <div class="w-px h-10 bg-white/20"></div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-white font-heading">50+</p>
                    <p class="text-primary-300 text-xs mt-1">Providers</p>
                </div>
            </div>
        </div>
    </div>
</div>

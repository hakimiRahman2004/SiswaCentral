<?php $layout = 'landing'; ?>
<div class="overflow-hidden">

  <!-- ═══ NAV ═══════════════════════════════════════════ -->
  <nav class="fixed top-0 inset-x-0 z-50 h-14 flex items-center bg-surface-900/80 backdrop-blur-lg border-b border-white/[0.06]">
    <div class="max-w-6xl mx-auto px-5 w-full flex items-center justify-between">
      <a href="<?= url('/') ?>" class="flex items-center gap-2 font-heading font-bold text-sm tracking-tight text-white group">
        <span class="w-7 h-7 rounded-md bg-gradient-to-br from-primary-500 to-accent-600 flex items-center justify-center text-white text-[10px] font-extrabold transition-transform duration-300 group-hover:rotate-12 group-hover:scale-110">S</span>
        <span class="text-white/90">SISWA</span><span class="text-accent-400">Central</span>
      </a>
      <div class="hidden md:flex items-center gap-7">
        <a href="#features" class="text-[13px] text-white/50 hover:text-white/90 transition-all duration-200 relative after:absolute after:bottom-[-2px] after:left-0 after:h-[1.5px] after:w-0 after:bg-primary-400 after:transition-all after:duration-300 hover:after:w-full">Features</a>
        <a href="#how" class="text-[13px] text-white/50 hover:text-white/90 transition-all duration-200 relative after:absolute after:bottom-[-2px] after:left-0 after:h-[1.5px] after:w-0 after:bg-primary-400 after:transition-all after:duration-300 hover:after:w-full">How it works</a>
        <a href="#pricing" class="text-[13px] text-white/50 hover:text-white/90 transition-all duration-200 relative after:absolute after:bottom-[-2px] after:left-0 after:h-[1.5px] after:w-0 after:bg-primary-400 after:transition-all after:duration-300 hover:after:w-full">Pricing</a>
        <span class="w-px h-4 bg-white/10"></span>
        <a href="<?= url('/login') ?>" class="text-[13px] font-medium text-white/70 hover:text-white transition-colors">Sign in</a>
        <a href="<?= url('/register') ?>" class="btn btn-sm btn-primary text-[13px] hover:shadow-glow-primary transition-shadow duration-300">Get started</a>
      </div>
      <button id="navToggle" class="md:hidden p-1.5 rounded-md text-white/40 hover:bg-white/10" aria-label="Menu">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
      </button>
    </div>
    <div id="mobileMenu" class="hidden absolute top-full left-0 right-0 bg-surface-900 border-t border-white/[0.06] px-5 py-4 space-y-2.5 md:hidden">
      <a href="#features" class="block text-sm text-white/60 py-1.5">Features</a>
      <a href="#how" class="block text-sm text-white/60 py-1.5">How it works</a>
      <a href="#pricing" class="block text-sm text-white/60 py-1.5">Pricing</a>
      <hr class="border-white/[0.06]">
      <a href="<?= url('/login') ?>" class="block text-sm font-medium text-white/80 py-1.5">Sign in</a>
      <a href="<?= url('/register') ?>" class="block text-center btn btn-sm btn-primary w-full mt-1">Get started</a>
    </div>
  </nav>

  <!-- ═══ HERO ══════════════════════════════════════════ -->
  <section class="relative min-h-[90vh] flex items-center bg-surface-900 overflow-hidden pt-14">
    <!-- Grid overlay -->
    <div class="absolute inset-0 opacity-[0.04]" style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 28px 28px;"></div>
    <!-- Glows -->
    <div class="absolute top-[-15%] left-[-10%] w-[60%] h-[60%] rounded-full bg-primary-600/10 blur-[120px] animate-float-slow"></div>
    <div class="absolute bottom-[-10%] right-[-5%] w-[40%] h-[40%] rounded-full bg-accent-600/10 blur-[100px] animate-float-medium" style="animation-delay: 0.5s;"></div>
    <!-- Subtle gradient at bottom -->
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-surface-50 to-transparent"></div>

    <div class="max-w-6xl mx-auto px-5 w-full relative z-10 py-20 lg:py-28">
      <div class="max-w-3xl mx-auto text-center">
        <!-- Pill -->
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-accent-500/10 text-accent-400 border border-accent-500/20 mb-7 animate-stagger-1">
          <span class="w-1.5 h-1.5 rounded-full bg-accent-400 animate-pulse-soft"></span>
          One profile, unlimited opportunities
        </span>
        <!-- Headline -->
        <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold font-heading text-white leading-[1.04] tracking-tight animate-stagger-2">
          Stop filling forms.<br>
          <span class="bg-gradient-to-r from-accent-300 via-accent-100 to-accent-400 bg-clip-text text-transparent bg-[length:200%_100%] animate-shimmer-slow">Start applying.</span>
        </h1>
        <p class="mt-5 text-[17px] sm:text-lg text-white/40 max-w-xl mx-auto leading-relaxed animate-stagger-3">
          Store your documents, build your profile, and apply to every scholarship that fits — all from one place. No repetition. No paperwork.
        </p>
        <!-- CTA -->
        <div class="flex flex-wrap items-center justify-center gap-3 mt-10 animate-stagger-4">
          <a href="<?= url('/register') ?>" class="btn btn-primary btn-lg shadow-lg shadow-primary-600/25 hover:shadow-xl hover:shadow-primary-600/30 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">Create free account</a>
          <a href="#features" class="btn btn-ghost btn-lg text-white/50 hover:text-white hover:bg-white/5 group">
            Explore features
            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
          </a>
        </div>
        <!-- Social proof -->
        <div class="flex items-center justify-center gap-6 mt-12 pt-8 border-t border-white/[0.06] animate-stagger-4" style="animation-delay: 0.5s;">
          <div class="flex -space-x-1.5">
            <div class="w-8 h-8 rounded-full border-2 border-surface-900 bg-gradient-to-br from-primary-400 to-primary-600 animate-float-medium" style="animation-delay: 0s;"></div>
            <div class="w-8 h-8 rounded-full border-2 border-surface-900 bg-gradient-to-br from-accent-400 to-accent-600 animate-float-medium" style="animation-delay: 0.3s;"></div>
            <div class="w-8 h-8 rounded-full border-2 border-surface-900 bg-gradient-to-br from-teal-400 to-teal-600 animate-float-medium" style="animation-delay: 0.6s;"></div>
            <div class="w-8 h-8 rounded-full border-2 border-surface-900 bg-gradient-to-br from-primary-500 to-primary-700 animate-float-medium" style="animation-delay: 0.9s;"></div>
          </div>
          <p class="text-sm text-white/40">
            <span class="text-white/80 font-semibold">10,000+</span> students and <span class="text-white/80 font-semibold">200+</span> providers
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ TRUST BAR ════════════════════════════════════ -->
  <section class="bg-white border-b border-surface-100">
    <div class="max-w-6xl mx-auto px-5 py-8">
      <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-surface-400 text-center mb-5">Trusted by students at</p>
      <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-4">
        <span class="text-lg font-bold font-heading text-surface-400/60 transition-all duration-300 hover:text-surface-600 hover:scale-105 cursor-default">Universiti Malaya</span>
        <span class="text-lg font-bold font-heading text-surface-400/60 transition-all duration-300 hover:text-surface-600 hover:scale-105 cursor-default">UKM</span>
        <span class="text-lg font-bold font-heading text-surface-400/60 transition-all duration-300 hover:text-surface-600 hover:scale-105 cursor-default">USM</span>
        <span class="text-lg font-bold font-heading text-surface-400/60 transition-all duration-300 hover:text-surface-600 hover:scale-105 cursor-default">Taylor's</span>
        <span class="text-lg font-bold font-heading text-surface-400/60 transition-all duration-300 hover:text-surface-600 hover:scale-105 cursor-default">Sunway</span>
        <span class="text-lg font-bold font-heading text-surface-400/60 transition-all duration-300 hover:text-surface-600 hover:scale-105 cursor-default">UTM</span>
      </div>
    </div>
  </section>

  <!-- ═══ STATS STRIP ══════════════════════════════════ -->
  <section class="bg-white border-b border-surface-100">
    <div class="max-w-6xl mx-auto px-5">
      <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-surface-100">
        <div class="py-10 text-center group cursor-default">
          <p class="text-3xl md:text-4xl font-extrabold font-heading text-surface-900 tracking-tight group-hover:scale-110 transition-transform duration-300">500+</p>
          <p class="text-[13px] text-surface-500 mt-1.5 font-medium group-hover:text-surface-700 transition-colors">Scholarships</p>
        </div>
        <div class="py-10 text-center group cursor-default">
          <p class="text-3xl md:text-4xl font-extrabold font-heading text-primary-600 tracking-tight group-hover:scale-110 transition-transform duration-300">10K</p>
          <p class="text-[13px] text-surface-500 mt-1.5 font-medium group-hover:text-surface-700 transition-colors">Students</p>
        </div>
        <div class="py-10 text-center group cursor-default">
          <p class="text-3xl md:text-4xl font-extrabold font-heading text-surface-900 tracking-tight group-hover:scale-110 transition-transform duration-300">200+</p>
          <p class="text-[13px] text-surface-500 mt-1.5 font-medium group-hover:text-surface-700 transition-colors">Providers</p>
        </div>
        <div class="py-10 text-center group cursor-default">
          <p class="text-3xl md:text-4xl font-extrabold font-heading text-primary-600 tracking-tight group-hover:scale-110 transition-transform duration-300">RM2M+</p>
          <p class="text-[13px] text-surface-500 mt-1.5 font-medium group-hover:text-surface-700 transition-colors">Awarded</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ FEATURES ═════════════════════════════════════ -->
  <section id="features" class="py-20 lg:py-28 bg-white">
    <div class="max-w-6xl mx-auto px-5">
      <div class="max-w-xl mb-16">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-primary-50 text-primary-700 border border-primary-100 mb-4">Features</span>
        <h2 class="text-3xl lg:text-4xl font-extrabold font-heading text-surface-900 tracking-tight">Everything in<span class="text-primary-600 block mt-0.5">one dashboard</span></h2>
        <p class="text-surface-500 mt-3 leading-relaxed text-[15px]">No more jumping between tabs and forms. One profile, one vault, one view of every opportunity.</p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 stagger-children">
        <div class="feature-card group hover:-translate-y-1 hover:shadow-md hover:border-primary-200 transition-all duration-300">
          <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0zM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
          </div>
          <h3 class="text-base font-bold font-heading text-surface-900 mb-1.5 group-hover:text-primary-700 transition-colors">One profile</h3>
          <p class="text-sm text-surface-500 leading-relaxed">Enter your CGPA, course, and background once. Every application pre-fills from your profile.</p>
        </div>
        <div class="feature-card group hover:-translate-y-1 hover:shadow-md hover:border-accent-200 transition-all duration-300">
          <div class="w-10 h-10 rounded-lg bg-accent-100 text-accent-600 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25z"/></svg>
          </div>
          <h3 class="text-base font-bold font-heading text-surface-900 mb-1.5 group-hover:text-accent-700 transition-colors">Secure vault</h3>
          <p class="text-sm text-surface-500 leading-relaxed">Upload your IC, transcript, and certificates once. Attach them to any application in one click.</p>
        </div>
        <div class="feature-card group hover:-translate-y-1 hover:shadow-md hover:border-teal-200 transition-all duration-300">
          <div class="w-10 h-10 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125z"/></svg>
          </div>
          <h3 class="text-base font-bold font-heading text-surface-900 mb-1.5 group-hover:text-teal-700 transition-colors">Smart matching</h3>
          <p class="text-sm text-surface-500 leading-relaxed">Our algorithm matches your profile against every scholarship. You only see what you qualify for.</p>
        </div>
        <div class="feature-card group hover:-translate-y-1 hover:shadow-md hover:border-success-200 transition-all duration-300">
          <div class="w-10 h-10 rounded-lg bg-success-100 text-success-600 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
          </div>
          <h3 class="text-base font-bold font-heading text-surface-900 mb-1.5 group-hover:text-success-700 transition-colors">Real-time tracking</h3>
          <p class="text-sm text-surface-500 leading-relaxed">Follow every application from submitted to shortlisted to approved. Never wonder where you stand.</p>
        </div>
        <div class="feature-card group hover:-translate-y-1 hover:shadow-md hover:border-warning-200 transition-all duration-300">
          <div class="w-10 h-10 rounded-lg bg-warning-100 text-warning-600 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
          </div>
          <h3 class="text-base font-bold font-heading text-surface-900 mb-1.5 group-hover:text-warning-700 transition-colors">Always free</h3>
          <p class="text-sm text-surface-500 leading-relaxed">Every feature — profiles, vault, matching, tracking — is completely free for students. Zero cost.</p>
        </div>
        <div class="feature-card group hover:-translate-y-1 hover:shadow-md hover:border-surface-300 transition-all duration-300">
          <div class="w-10 h-10 rounded-lg bg-surface-150 text-surface-600 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
          </div>
          <h3 class="text-base font-bold font-heading text-surface-900 mb-1.5 group-hover:text-surface-700 transition-colors">Multi-provider</h3>
          <p class="text-sm text-surface-500 leading-relaxed">Apply to scholarships from universities, foundations, and corporations — all from one account.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ HOW IT WORKS ══════════════════════════════════ -->
  <section id="how" class="py-20 lg:py-28 bg-surface-50">
    <div class="max-w-6xl mx-auto px-5">
      <div class="text-center max-w-xl mx-auto mb-14">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-primary-50 text-primary-700 border border-primary-100 mb-4">How it works</span>
        <h2 class="text-3xl lg:text-4xl font-extrabold font-heading text-surface-900 tracking-tight">Three steps to<span class="text-primary-600 block mt-0.5">your next opportunity</span></h2>
      </div>

      <div class="max-w-3xl mx-auto space-y-8">
        <!-- Step 1 -->
        <div class="flex items-start gap-5 bg-white rounded-xl p-5 border border-surface-200 shadow-xs transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md hover:border-primary-200 group">
          <span class="w-10 h-10 rounded-lg bg-primary-600 text-white flex items-center justify-center text-sm font-bold font-heading shrink-0 transition-transform duration-300 group-hover:scale-110">1</span>
          <div>
            <h3 class="text-base font-bold font-heading text-surface-900 group-hover:text-primary-700 transition-colors">Create your academic profile</h3>
            <p class="text-sm text-surface-500 mt-1 leading-relaxed">Tell us about yourself — your CGPA, course, university, and financial background. We organize it into a complete profile that travels with every application.</p>
          </div>
        </div>
        <!-- Step 2 -->
        <div class="flex items-start gap-5 bg-white rounded-xl p-5 border border-surface-200 shadow-xs transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md hover:border-accent-200 group">
          <span class="w-10 h-10 rounded-lg bg-accent-600 text-white flex items-center justify-center text-sm font-bold font-heading shrink-0 transition-transform duration-300 group-hover:scale-110">2</span>
          <div>
            <h3 class="text-base font-bold font-heading text-surface-900 group-hover:text-accent-700 transition-colors">Upload your documents once</h3>
            <p class="text-sm text-surface-500 mt-1 leading-relaxed">Upload your IC, academic transcript, income statement, and resume to your secure vault. One upload works for every application you submit.</p>
          </div>
        </div>
        <!-- Step 3 -->
        <div class="flex items-start gap-5 bg-white rounded-xl p-5 border border-surface-200 shadow-xs transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md hover:border-primary-200 group">
          <span class="w-10 h-10 rounded-lg bg-primary-600 text-white flex items-center justify-center text-sm font-bold font-heading shrink-0 transition-transform duration-300 group-hover:scale-110">3</span>
          <div>
            <h3 class="text-base font-bold font-heading text-surface-900 group-hover:text-primary-700 transition-colors">Apply and track in real time</h3>
            <p class="text-sm text-surface-500 mt-1 leading-relaxed">Browse scholarships matched to your profile, attach your stored documents, and submit. Track every application from submission through to approval.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ PROVIDER SPOTLIGHT ════════════════════════════ -->
  <section class="py-20 lg:py-28 bg-white">
    <div class="max-w-6xl mx-auto px-5">
      <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
        <div>
          <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-primary-50 text-primary-700 border border-primary-100 mb-4">For providers</span>
          <h2 class="text-3xl lg:text-4xl font-extrabold font-heading text-surface-900 tracking-tight">Run your<span class="text-primary-600 block mt-0.5">scholarship program</span></h2>
          <p class="text-surface-500 mt-3 leading-relaxed text-[15px]">A complete platform to create, publish, review, and award scholarships — designed for universities, foundations, and corporate partners.</p>
          <div class="mt-8 space-y-4">
            <div class="flex items-start gap-3.5">
              <span class="w-5 h-5 rounded-md bg-primary-100 text-primary-600 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>
              </span>
              <p class="text-sm text-surface-600">Streamlined pipeline to screen, shortlist, and approve applicants</p>
            </div>
            <div class="flex items-start gap-3.5">
              <span class="w-5 h-5 rounded-md bg-primary-100 text-primary-600 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>
              </span>
              <p class="text-sm text-surface-600">Real-time analytics on applicants, demographics, and trends</p>
            </div>
            <div class="flex items-start gap-3.5">
              <span class="w-5 h-5 rounded-md bg-primary-100 text-primary-600 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>
              </span>
              <p class="text-sm text-surface-600">Role-based team access for your review committee</p>
            </div>
          </div>
          <a href="<?= url('/register') ?>" class="btn btn-primary btn-lg mt-8">Register as provider</a>
        </div>
        <div class="hidden lg:block">
          <div class="bg-surface-50 rounded-xl border border-surface-200 p-6 relative transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-surface-200">
              <div class="w-9 h-9 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center text-sm font-bold">UM</div>
              <div>
                <p class="text-sm font-semibold text-surface-900">Universiti Malaya</p>
                <p class="text-xs text-surface-500">Verified provider</p>
              </div>
              <span class="ml-auto badge badge-success">Verified</span>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-4">
              <div class="p-3.5 rounded-lg bg-white border border-surface-200">
                <p class="text-xs text-surface-500 font-medium">Applications</p>
                <p class="text-2xl font-bold font-heading text-surface-900 mt-0.5">147</p>
              </div>
              <div class="p-3.5 rounded-lg bg-white border border-surface-200">
                <p class="text-xs text-surface-500 font-medium">Shortlisted</p>
                <p class="text-2xl font-bold font-heading text-surface-900 mt-0.5">38</p>
              </div>
            </div>
            <div class="p-4 rounded-lg bg-white border border-surface-200">
              <p class="text-xs font-medium text-surface-500 mb-3">Applications this month</p>
              <div class="flex items-end gap-1.5 h-10">
                <span class="w-5 bg-primary-200 rounded-t" style="height:30%"></span>
                <span class="w-5 bg-primary-300 rounded-t" style="height:45%"></span>
                <span class="w-5 bg-primary-400 rounded-t" style="height:60%"></span>
                <span class="w-5 bg-primary-500 rounded-t" style="height:85%"></span>
                <span class="w-5 bg-primary-400 rounded-t" style="height:65%"></span>
                <span class="w-5 bg-primary-300 rounded-t" style="height:40%"></span>
                <span class="w-5 bg-primary-200 rounded-t" style="height:25%"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ TESTIMONIAL ═══════════════════════════════════ -->
  <section class="py-20 lg:py-28 bg-surface-50">
    <div class="max-w-6xl mx-auto px-5">
      <div class="max-w-2xl mx-auto text-center">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-primary-50 text-primary-700 border border-primary-100 mb-4">Testimonial</span>
        <!-- Quote -->
        <svg class="w-8 h-8 text-primary-200 mx-auto mb-4 animate-float-medium" fill="currentColor" viewBox="0 0 32 32"><path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H8c0-1.1.9-2 2-2V8zm14 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-1.1.9-2 2-2V8z"/></svg>
        <blockquote class="text-xl lg:text-2xl text-surface-800 leading-relaxed font-medium font-heading tracking-tight">
          "SISWACentral made my scholarship search so much easier. I uploaded my documents once and applied to five scholarships in one afternoon. The matching feature helped me find opportunities I didn't even know existed."
        </blockquote>
        <div class="flex items-center justify-center gap-1.5 mt-6">
          <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
        </div>
        <div class="flex items-center justify-center gap-3 mt-5">
          <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-xs font-bold">AN</div>
          <div class="text-left">
            <p class="text-sm font-semibold text-surface-800">Aina Nasir</p>
            <p class="text-xs text-surface-400">Computer Science, Universiti Malaya</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ PRICING ═══════════════════════════════════════ -->
  <section id="pricing" class="py-20 lg:py-28 bg-white">
    <div class="max-w-6xl mx-auto px-5">
      <div class="text-center max-w-xl mx-auto mb-14">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-primary-50 text-primary-700 border border-primary-100 mb-4">Pricing</span>
        <h2 class="text-3xl lg:text-4xl font-extrabold font-heading text-surface-900 tracking-tight">Free for students.<br><span class="text-primary-600">Fair for providers.</span></h2>
      </div>

      <div class="grid md:grid-cols-3 gap-5 max-w-4xl mx-auto">
        <!-- Starter -->
        <div class="pricing-card hover:-translate-y-1 transition-all duration-300">
          <p class="text-[11px] font-bold uppercase tracking-[0.1em] text-surface-500">Starter</p>
          <div class="mt-3"><span class="text-3xl font-extrabold font-heading text-surface-900 tracking-tight">Free</span></div>
          <p class="text-[13px] text-surface-500 mt-1">For small organisations</p>
          <hr class="my-6 border-surface-100">
          <ul class="space-y-3 text-sm text-surface-600 flex-1">
            <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>Up to 3 scholarships</li>
            <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>1 staff account</li>
            <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>Basic insights</li>
          </ul>
          <a href="<?= url('/register') ?>" class="btn btn-outline w-full mt-7 transition-all duration-200 hover:border-primary-400 hover:text-primary-700">Get started</a>
        </div>
        <!-- Professional -->
        <div class="pricing-card featured hover:-translate-y-1 transition-all duration-300">
          <span class="pricing-badge">Most popular</span>
          <p class="text-[11px] font-bold uppercase tracking-[0.1em] text-primary-600">Professional</p>
          <div class="mt-3 flex items-baseline gap-1">
            <span class="text-3xl font-extrabold font-heading text-surface-900 tracking-tight">RM99</span>
            <span class="text-sm text-surface-400">/month</span>
          </div>
          <p class="text-[13px] text-surface-500 mt-1">For growing organisations</p>
          <hr class="my-6 border-surface-100">
          <ul class="space-y-3 text-sm text-surface-600 flex-1">
            <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>Up to 50 scholarships</li>
            <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>5 staff accounts</li>
            <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>Analytics dashboard</li>
            <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>Priority support</li>
          </ul>
          <a href="<?= url('/register') ?>" class="btn btn-primary w-full mt-7 hover:shadow-lg hover:shadow-primary-600/30 transition-all duration-200">Subscribe now</a>
        </div>
        <!-- Enterprise -->
        <div class="pricing-card hover:-translate-y-1 transition-all duration-300">
          <p class="text-[11px] font-bold uppercase tracking-[0.1em] text-surface-500">Enterprise</p>
          <div class="mt-3 flex items-baseline gap-1">
            <span class="text-3xl font-extrabold font-heading text-surface-900 tracking-tight">RM299</span>
            <span class="text-sm text-surface-400">/month</span>
          </div>
          <p class="text-[13px] text-surface-500 mt-1">For large organisations</p>
          <hr class="my-6 border-surface-100">
          <ul class="space-y-3 text-sm text-surface-600 flex-1">
            <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>Unlimited scholarships</li>
            <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>20 staff accounts</li>
            <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>API access</li>
            <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>Dedicated support</li>
          </ul>
          <a href="<?= url('/register') ?>" class="btn btn-outline w-full mt-7 transition-all duration-200 hover:border-primary-400 hover:text-primary-700">Contact sales</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ CTA ═══════════════════════════════════════════ -->
  <section class="py-20 lg:py-24 bg-surface-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 24px 24px;"></div>
    <div class="absolute top-[-10%] left-[-5%] w-[40%] h-[40%] rounded-full bg-primary-600/10 blur-[100px]"></div>
    <div class="absolute bottom-[-10%] right-[-5%] w-[40%] h-[40%] rounded-full bg-accent-600/10 blur-[100px]"></div>
    <div class="max-w-2xl mx-auto px-5 text-center relative">
      <h2 class="text-3xl lg:text-4xl font-extrabold font-heading text-white tracking-tight leading-tight">Ready to simplify your<br>scholarship search?</h2>
      <p class="mt-4 text-white/40 text-[15px] max-w-md mx-auto">Join thousands of students already using SISWACentral. Create your free account in under two minutes.</p>
      <div class="flex flex-wrap items-center justify-center gap-3 mt-8">
        <a href="<?= url('/register') ?>" class="btn btn-primary btn-lg shadow-lg shadow-primary-600/25 hover:shadow-xl hover:shadow-primary-600/30 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 animate-pulse-glow">Create free account</a>
        <a href="<?= url('/login') ?>" class="btn btn-ghost btn-lg text-white/50 hover:text-white hover:bg-white/5 transition-all duration-200">Sign in</a>
      </div>
    </div>
  </section>

  <!-- ═══ FOOTER ═══════════════════════════════════════ -->
  <footer class="bg-surface-900 border-t border-white/[0.06] py-12">
    <div class="max-w-6xl mx-auto px-5">
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
        <div class="sm:col-span-2 lg:col-span-1">
          <a href="<?= url('/') ?>" class="flex items-center gap-2 font-heading font-bold text-sm tracking-tight group">
            <span class="w-7 h-7 rounded-md bg-gradient-to-br from-primary-500 to-accent-600 flex items-center justify-center text-white text-[10px] font-extrabold transition-transform duration-300 group-hover:rotate-12 group-hover:scale-110">S</span>
            <span class="text-white/90">SISWA</span><span class="text-accent-400">Central</span>
          </a>
          <p class="mt-3 text-[13px] text-white/30 max-w-xs leading-relaxed">Malaysia's centralized scholarship platform. One profile, one upload, unlimited opportunities.</p>
        </div>
        <div>
          <p class="text-[11px] font-bold uppercase tracking-[0.1em] text-white/30 mb-4">Platform</p>
          <ul class="space-y-2.5">
            <li><a href="#features" class="text-[13px] text-white/40 hover:text-white/70 transition-colors">Features</a></li>
            <li><a href="#how" class="text-[13px] text-white/40 hover:text-white/70 transition-colors">How it works</a></li>
            <li><a href="#pricing" class="text-[13px] text-white/40 hover:text-white/70 transition-colors">Pricing</a></li>
          </ul>
        </div>
        <div>
          <p class="text-[11px] font-bold uppercase tracking-[0.1em] text-white/30 mb-4">Account</p>
          <ul class="space-y-2.5">
            <li><a href="<?= url('/login') ?>" class="text-[13px] text-white/40 hover:text-white/70 transition-colors">Sign in</a></li>
            <li><a href="<?= url('/register') ?>" class="text-[13px] text-white/40 hover:text-white/70 transition-colors">Create account</a></li>
          </ul>
        </div>
        <div>
          <p class="text-[11px] font-bold uppercase tracking-[0.1em] text-white/30 mb-4">Legal</p>
          <ul class="space-y-2.5">
            <li><a href="#" class="text-[13px] text-white/40 hover:text-white/70 transition-colors">Privacy policy</a></li>
            <li><a href="#" class="text-[13px] text-white/40 hover:text-white/70 transition-colors">Terms of service</a></li>
          </ul>
        </div>
      </div>
      <hr class="border-white/[0.06] my-8">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <p class="text-[13px] text-white/30">&copy; <?= date("Y") ?> SISWACentral. All rights reserved.</p>
        <div class="flex items-center gap-4">
          <a href="#" class="text-white/20 hover:text-white/50 transition-all duration-200 hover:scale-110">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
          </a>
          <a href="#" class="text-white/20 hover:text-white/50 transition-all duration-200 hover:scale-110">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069z"/></svg>
          </a>
          <a href="#" class="text-white/20 hover:text-white/50 transition-all duration-200 hover:scale-110">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>
          </a>
        </div>
      </div>
    </div>
  </footer>
</div>

<script>
(function(){
  'use strict';
  var m = document.getElementById('mobileMenu'), mb = document.getElementById('navToggle');
  if (mb && m) {
    mb.addEventListener('click', function(){ m.classList.toggle('hidden'); });
  }
  document.querySelectorAll('a[href^="#"]').forEach(function(a){
    a.addEventListener('click', function(e){
      var t = document.querySelector(this.getAttribute('href'));
      if (t) { e.preventDefault(); window.scrollTo({ top: t.getBoundingClientRect().top + window.pageYOffset - 56, behavior: 'smooth' }); }
      if (m && !m.classList.contains('hidden')) m.classList.add('hidden');
    });
  });
  // Reveal on scroll
  if (!window.matchMedia('(prefers-reduced-motion:reduce)').matches && 'IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function(entries){
      entries.forEach(function(e){
        if (e.isIntersecting) {
          e.target.style.opacity = '1';
          e.target.style.transform = 'translateY(0)';
          observer.unobserve(e.target);
        }
      });
    }, { threshold: 0.06, rootMargin: '0px 0px -40px 0px' });
    document.querySelectorAll('section').forEach(function(s){
      s.style.opacity = '0';
      s.style.transform = 'translateY(16px)';
      s.style.transition = 'opacity 0.5s cubic-bezier(0.16,1,0.3,1), transform 0.5s cubic-bezier(0.16,1,0.3,1)';
      observer.observe(s);
    });
  }
})();
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? config('app.name')) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;450;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/output.css') ?>">
</head>
<body class="bg-surface-50 text-surface-800 antialiased">
    <?php include __DIR__ . '/../partials/navbar.php' ?>

    <div class="flex min-h-screen pt-16">
        <?php include __DIR__ . '/../partials/sidebar.php' ?>

        <main id="mainContent" class="flex-1 lg:ml-64 min-h-screen">
            <div class="content-section py-6 lg:py-8">
                <?php if ($flash = flash('success')): ?>
                    <div class="alert alert-success" role="alert">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span><?= e($flash) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($flash = flash('error')): ?>
                    <div class="alert alert-error" role="alert">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span><?= e($flash) ?></span>
                    </div>
                <?php endif; ?>
                <?= $content ?? '' ?>
            </div>
        </main>
    </div>

    <script src="<?= asset('js/app.js') ?>"></script>
<script>
(function() {
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', function() {
            sidebar.classList.toggle('hidden');
            sidebar.classList.toggle('lg:block');
        });
    }
})();
</script>
</body>
</html>

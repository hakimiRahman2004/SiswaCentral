<?php $title = 'Feedback'; ?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1 class="page-title">Submit Feedback</h1>
        <p class="page-desc">Rate your experience and leave a comment</p>
    </div>
    <div class="card-hover p-6 max-w-2xl">
        <form method="POST" action="<?= url('/provider/feedback') ?>" class="space-y-4">
            <?= csrfField() ?>

            <!-- Star Rating -->
            <div>
                <label class="input-label">Rating</label>
                <div class="flex items-center gap-1 mt-1" id="star-rating" role="radiogroup" aria-label="Rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <label class="cursor-pointer p-1" tabindex="0">
                        <input type="radio" name="rating" value="<?= $i ?>" class="hidden peer" required>
                        <svg class="w-8 h-8 text-surface-300 peer-checked:text-yellow-400 hover:text-yellow-400 transition-colors star-svg" data-val="<?= $i ?>" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5z"/>
                        </svg>
                    </label>
                    <?php endfor; ?>
                </div>
                <p class="text-xs text-surface-400 mt-1" id="rating-label">Select a rating</p>
            </div>

            <!-- Comment -->
            <div>
                <label class="input-label">Comment</label>
                <textarea name="message" class="input" rows="5" placeholder="Tell us what you think..." required></textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn btn-primary">Submit Feedback</button>
                <a href="<?= url('/provider/dashboard') ?>" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('star-rating').addEventListener('change', function(e) {
    if (e.target.matches('input[type="radio"]')) {
        const val = e.target.value;
        document.querySelectorAll('.star-svg').forEach(function(svg) {
            svg.classList.toggle('text-yellow-400', parseInt(svg.dataset.val) <= parseInt(val));
            svg.classList.toggle('text-surface-300', parseInt(svg.dataset.val) > parseInt(val));
        });
        document.getElementById('rating-label').textContent = val + ' / 5';
    }
});
</script>
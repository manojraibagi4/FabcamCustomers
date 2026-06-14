<?php if (!empty($_SESSION['flash'])): ?>
<?php $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
<div class="alert alert-<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?> alert-dismissible fade show mb-4" role="alert">
  <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

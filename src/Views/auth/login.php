<div class="fab-auth-card">
  <div class="fab-auth-logo">
    <img src="<?= BASE_URL ?>/images/fabcam-logo.png" alt="Fabcam Technologies" class="fab-auth-logo-img">
  </div>
  <p class="fab-auth-tagline">License &amp; Customer Management Portal</p>

  <?php if (!empty($_GET['timeout'])): ?>
  <div class="alert alert-warning py-2 mb-3">
    Your session expired due to inactivity. Please log in again.
  </div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
  <div class="alert alert-danger py-2 mb-3">
    <?php foreach ($errors as $e): ?>
      <div><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <form method="POST" action="<?= BASE_URL ?>/login">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">

    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" class="form-control" id="email" name="email"
             value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>"
             autocomplete="email" autofocus required>
    </div>
    <div class="mb-4">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password"
             autocomplete="current-password" required>
    </div>
    <button type="submit" class="btn btn-accent w-100">
      <i class="bi bi-box-arrow-in-right me-2"></i>Sign in</button>
  </form>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'Login', ENT_QUOTES, 'UTF-8') ?> — Fabcam Technologies</title>
  <link rel="icon" type="image/png" href="<?= BASE_URL ?>/images/fabcam-logo.png">
  <meta name="csrf-token" content="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
  <link rel="stylesheet" href="<?= BASE_URL ?>/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/bootstrap-icons/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css">
</head>
<body class="fab-auth-bg">
  <div style="width:100%;max-width:400px;margin:0 auto;padding-top:16px;">
    <?php require __DIR__ . '/../partials/flash.php'; ?>
  </div>
  <?php require $viewPath; ?>
  <script src="<?= BASE_URL ?>/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= BASE_URL ?>/js/app.js"></script>
</body>
</html>

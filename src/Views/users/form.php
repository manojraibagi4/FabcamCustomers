<?php
$isEdit = !empty($user['id']);
$action = $isEdit ? BASE_URL.'/users/edit/'.(int)$user['id'] : BASE_URL.'/users/add';
?>
<div class="fab-page-header">
  <h1 class="fab-page-title"><?= $isEdit ? 'Edit User' : 'Add User' ?></h1>
  <a href="<?= BASE_URL ?>/users" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger mb-3">
  <?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></div><?php endforeach; ?>
</div>
<?php endif; ?>

<div class="fab-card" style="max-width:540px">
  <form method="POST" action="<?= $action ?>">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
    <div class="mb-3">
      <label class="form-label">Full Name <span class="text-danger">*</span></label>
      <input type="text" name="name" class="form-control"
             value="<?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email <span class="text-danger">*</span></label>
      <input type="email" name="email" class="form-control"
             value="<?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password <?= $isEdit ? '<small class="text-muted-fab">(leave blank to keep current)</small>' : '<span class="text-danger">*</span>' ?></label>
      <input type="password" name="password" class="form-control" <?= $isEdit ? '' : 'required' ?>>
    </div>
    <div class="row g-3 mb-3">
      <div class="col-6">
        <label class="form-label">Role</label>
        <select name="role" class="form-select">
          <option value="sales" <?= ($user['role'] ?? '') === 'sales' ? 'selected' : '' ?>>Sales</option>
          <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
      </div>
      <div class="col-6 d-flex align-items-end">
        <div class="form-check mb-1">
          <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                 <?= ($user['is_active'] ?? 1) ? 'checked' : '' ?>>
          <label class="form-check-label" for="is_active">Active</label>
        </div>
      </div>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-accent"><i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Update User' : 'Add User' ?></button>
      <a href="<?= BASE_URL ?>/users" class="btn btn-outline-secondary"><i class="bi bi-x me-1"></i>Cancel</a>
    </div>
  </form>
</div>

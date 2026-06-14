<?php
$isEdit = !empty($product['id']);
$action = $isEdit ? BASE_URL.'/products/edit/'.(int)$product['id'] : BASE_URL.'/products/add';
?>
<div class="fab-page-header">
  <h1 class="fab-page-title"><?= $isEdit ? 'Edit Product' : 'Add Product' ?></h1>
  <a href="<?= BASE_URL ?>/products" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
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
      <label class="form-label">Product Name <span class="text-danger">*</span></label>
      <input type="text" name="product_name" class="form-control"
             value="<?= htmlspecialchars($product['product_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Module</label>
      <input type="text" name="module" class="form-control"
             value="<?= htmlspecialchars($product['module'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="mb-4">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($product['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-accent"><i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Update Product' : 'Add Product' ?></button>
      <a href="<?= BASE_URL ?>/products" class="btn btn-outline-secondary"><i class="bi bi-x me-1"></i>Cancel</a>
    </div>
  </form>
</div>

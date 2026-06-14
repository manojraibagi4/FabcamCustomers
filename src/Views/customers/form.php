<?php
$isEdit = !empty($customer['id']);
$action = $isEdit ? BASE_URL . '/customers/edit/' . (int)$customer['id'] : BASE_URL . '/customers/add';
function val(array $c, string $k): string {
    return htmlspecialchars($c[$k] ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<div class="fab-page-header">
  <h1 class="fab-page-title"><?= $isEdit ? 'Edit Customer' : 'Add Customer' ?></h1>
  <a href="<?= BASE_URL ?>/customers" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger mb-3">
  <?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></div><?php endforeach; ?>
</div>
<?php endif; ?>

<div class="fab-card" style="max-width:700px">
  <form method="POST" action="<?= $action ?>">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">

    <div class="fab-section-label">Company Details</div>
    <div class="row g-3 mb-3">
      <div class="col-12">
        <label class="form-label">Company Name <span class="text-danger">*</span></label>
        <input type="text" name="company_name" class="form-control" value="<?= val($customer,'company_name') ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Contact Person</label>
        <input type="text" name="contact_person" class="form-control" value="<?= val($customer,'contact_person') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Mobile</label>
        <input type="text" name="mobile" class="form-control" value="<?= val($customer,'mobile') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= val($customer,'email') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">GST Number</label>
        <input type="text" name="gst_number" class="form-control" value="<?= val($customer,'gst_number') ?>">
      </div>
      <div class="col-12">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="3"><?= val($customer,'address') ?></textarea>
      </div>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-accent"><i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Update Customer' : 'Add Customer' ?></button>
      <a href="<?= BASE_URL ?>/customers" class="btn btn-outline-secondary"><i class="bi bi-x me-1"></i>Cancel</a>
    </div>
  </form>
</div>

<?php
$isEdit = !empty($license['id']);
$action = $isEdit ? BASE_URL . '/licenses/edit/' . (int)$license['id'] : BASE_URL . '/licenses/add';
function lval(array $l, string $k): string {
    return htmlspecialchars($l[$k] ?? '', ENT_QUOTES, 'UTF-8');
}
function lsel(array $l, string $k, string $v): string {
    return ($l[$k] ?? '') == $v ? ' selected' : '';
}
?>
<div class="fab-page-header">
  <h1 class="fab-page-title"><?= $isEdit ? 'Edit License' : 'Add License' ?></h1>
  <a href="<?= BASE_URL ?>/licenses" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger mb-3">
  <?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></div><?php endforeach; ?>
</div>
<?php endif; ?>

<div class="fab-card" style="max-width:800px">
  <form method="POST" action="<?= $action ?>">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">

    <div class="fab-section-label">License Assignment</div>
    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <label class="form-label">Customer <span class="text-danger">*</span></label>
        <select name="customer_id" class="form-select" data-searchable required>
          <option value="">— Select Customer —</option>
          <?php foreach ($customers as $c): ?>
          <option value="<?= (int)$c['id'] ?>" <?= lsel($license,'customer_id',$c['id']) ?>>
            <?= htmlspecialchars($c['customer_id'].' — '.$c['company_name'], ENT_QUOTES, 'UTF-8') ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Product <span class="text-danger">*</span></label>
        <select name="product_id" class="form-select" required>
          <option value="">— Select Product —</option>
          <?php foreach ($products as $p): ?>
          <option value="<?= (int)$p['id'] ?>" <?= lsel($license,'product_id',$p['id']) ?>>
            <?= htmlspecialchars($p['product_name'], ENT_QUOTES, 'UTF-8') ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">License Type</label>
        <select name="license_type" class="form-select">
          <?php foreach (['single','multi','server','cloud'] as $t): ?>
          <option value="<?= $t ?>" <?= lsel($license,'license_type',$t) ?>><?= ucfirst($t) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Machine Name</label>
        <input type="text" name="machine_name" class="form-control" placeholder="e.g. WS-001, SERVER-01" value="<?= lval($license,'machine_name') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">License Status</label>
        <select name="license_status" class="form-select">
          <?php foreach (['active','expired','grace','revoked'] as $s): ?>
          <option value="<?= $s ?>" <?= lsel($license,'license_status',$s) ?>><?= ucfirst($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="fab-section-label">Technical Details</div>
    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <label class="form-label">Server Code</label>
        <input type="text" name="server_code" class="form-control" value="<?= lval($license,'server_code') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Lock Code</label>
        <input type="text" name="lock_code" class="form-control" value="<?= lval($license,'lock_code') ?>">
      </div>
    </div>

    <div class="fab-section-label">Purchase &amp; Dates</div>
    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <label class="form-label">Purchase Price (₹)</label>
        <input type="number" name="purchase_price" class="form-control" step="0.01" min="0" value="<?= lval($license,'purchase_price') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Purchase Date</label>
        <input type="date" name="purchase_date" class="form-control" value="<?= lval($license,'purchase_date') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Expiry Date <span class="text-danger">*</span></label>
        <input type="date" name="expiry_date" class="form-control" value="<?= lval($license,'expiry_date') ?>" required>
      </div>
    </div>

    <div class="fab-section-label">AMC Details</div>
    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <label class="form-label">AMC Cost (₹)</label>
        <input type="number" name="amc_cost" class="form-control" step="0.01" min="0" value="<?= lval($license,'amc_cost') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">AMC Renewal Date</label>
        <input type="date" name="renewal_date" class="form-control" value="<?= lval($license,'renewal_date') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">AMC Status</label>
        <select name="amc_status" class="form-select">
          <?php foreach (['active'=>'Active','expired'=>'Expired','not_applicable'=>'Not Applicable'] as $v=>$l): ?>
          <option value="<?= $v ?>" <?= lsel($license,'amc_status',$v) ?>><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label">Remarks</label>
      <textarea name="remarks" class="form-control" rows="3"><?= lval($license,'remarks') ?></textarea>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-accent"><i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Update License' : 'Add License' ?></button>
      <a href="<?= BASE_URL ?>/licenses" class="btn btn-outline-secondary"><i class="bi bi-x me-1"></i>Cancel</a>
    </div>
  </form>
</div>

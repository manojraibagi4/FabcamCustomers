<div class="fab-page-header">
  <h1 class="fab-page-title">Import</h1>
  <a href="<?= BASE_URL ?>/export" class="btn btn-outline-secondary">
    <i class="bi bi-file-earmark-arrow-down me-1"></i>Download Template
  </a>
</div>

<?php if ($result !== null): ?>
<!-- ── Results ── -->
<?php
  $hasErrors = !empty($result['errors']);
  $total     = $result['licenses_inserted'] + $result['licenses_updated'];
?>
<div class="alert <?= $hasErrors ? 'alert-warning' : 'alert-success' ?> mb-4" role="alert">
  <div class="fw-semibold mb-1"><?= $hasErrors ? 'Import completed with warnings' : 'Import successful' ?></div>
  <div style="font-size:14px">
    <?= (int)$result['customers_created'] ?> customer(s) created &nbsp;·&nbsp;
    <?= (int)$result['customers_updated'] ?> updated &nbsp;·&nbsp;
    <?= (int)$result['licenses_inserted'] ?> license(s) inserted &nbsp;·&nbsp;
    <?= (int)$result['licenses_updated'] ?> updated
    <?php if ($result['rows_skipped']): ?>
      &nbsp;·&nbsp; <?= (int)$result['rows_skipped'] ?> row(s) skipped
    <?php endif; ?>
  </div>
</div>

<?php if ($hasErrors): ?>
<div class="fab-card mb-4 p-0">
  <div class="px-4 py-3 border-bottom fw-semibold" style="font-size:14px">
    <i class="bi bi-exclamation-triangle text-warning me-1"></i>Warnings / Skipped Rows
  </div>
  <ul class="mb-0 py-2 px-4" style="font-size:13px;line-height:2">
    <?php foreach ($result['errors'] as $err): ?>
    <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>
<?php endif; ?>

<!-- ── Upload form ── -->
<div class="row g-4">
  <div class="col-lg-6">
    <div class="fab-card">
      <div class="fab-section-label">Upload Excel File</div>
      <form method="POST" action="<?= BASE_URL ?>/import/process" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">

        <div class="mb-4">
          <label class="form-label">Select File <span class="text-danger">*</span></label>
          <input type="file" name="import_file" class="form-control" accept=".xlsx,.xls" required>
          <div class="form-text">Accepts <code>.xlsx</code> or <code>.xls</code> files exported from this system.</div>
        </div>

        <button type="submit" class="btn btn-accent">
          <i class="bi bi-upload me-1"></i>Import
        </button>
      </form>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="fab-card">
      <div class="fab-section-label">How It Works</div>
      <ul class="mb-3 ps-3" style="font-size:13px;line-height:2.2">
        <li>Use the <strong>Download Template</strong> button to get an export file as a starting point.</li>
        <li><strong>Customer ID</strong> (FAB-XXXX): if found, the customer's details are <em>updated</em>; if not found, a new customer is created with that ID.</li>
        <li><strong>Product</strong> must exactly match an existing product name — rows with unknown products are skipped.</li>
        <li><strong>Server Code</strong>: if provided, an existing license with the same customer + server code is <em>updated</em>; otherwise a new license is inserted.</li>
        <li>The <strong>Days Left</strong> column is ignored — it is calculated automatically.</li>
        <li>Completely empty rows are silently skipped.</li>
      </ul>

      <div class="fab-section-label mt-3">Required Columns</div>
      <div class="d-flex gap-2 flex-wrap" style="font-size:12px">
        <?php foreach (['Company Name', 'Expiry Date', 'Product'] as $col): ?>
        <span class="badge" style="background:#fef3c7;color:#92400e;font-weight:500"><?= $col ?></span>
        <?php endforeach; ?>
      </div>

      <div class="fab-section-label mt-3">Valid Values</div>
      <table class="table table-sm table-borderless mb-0" style="font-size:12px">
        <tr><td class="text-muted-fab" style="width:130px">License Type</td><td>Single, Multi, Server, Cloud</td></tr>
        <tr><td class="text-muted-fab">License Status</td><td>Active, Expired, Grace, Revoked</td></tr>
        <tr><td class="text-muted-fab">AMC Status</td><td>Active, Expired, Not applicable</td></tr>
        <tr><td class="text-muted-fab">Date format</td><td>YYYY-MM-DD</td></tr>
      </table>
    </div>
  </div>
</div>

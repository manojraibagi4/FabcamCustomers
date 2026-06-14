<div class="fab-page-header">
  <h1 class="fab-page-title">Export</h1>
</div>

<div class="fab-card mb-4" style="max-width:700px">
  <div class="fab-section-label">Customer &amp; License Export</div>
  <p class="text-muted-fab mb-4" style="font-size:14px">
    Exports one row per license with full customer details attached. Apply optional filters to narrow the data before downloading.
  </p>

  <form method="GET" action="<?= BASE_URL ?>/export/download">

    <div class="row g-3 mb-4">

      <div class="col-sm-6">
        <label class="form-label">License Status</label>
        <select name="status" class="form-select">
          <option value="">All Statuses</option>
          <?php foreach (['active' => 'Active', 'expired' => 'Expired', 'grace' => 'Grace', 'revoked' => 'Revoked'] as $v => $l): ?>
          <option value="<?= $v ?>"><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-sm-6">
        <label class="form-label">AMC Status</label>
        <select name="amc_status" class="form-select">
          <option value="">All AMC Statuses</option>
          <?php foreach (['active' => 'AMC Active', 'expired' => 'AMC Expired', 'not_applicable' => 'Not Applicable'] as $v => $l): ?>
          <option value="<?= $v ?>"><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-sm-6">
        <label class="form-label">Product</label>
        <select name="product_id" class="form-select">
          <option value="">All Products</option>
          <?php foreach ($products as $p): ?>
          <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['product_name'], ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-sm-6">
        <label class="form-label">Customer</label>
        <select name="customer_id" class="form-select" data-searchable>
          <option value="">All Customers</option>
          <?php foreach ($customers as $c): ?>
          <option value="<?= (int)$c['id'] ?>">
            <?= htmlspecialchars($c['customer_id'] . ' — ' . $c['company_name'], ENT_QUOTES, 'UTF-8') ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>

    </div>

    <div class="d-flex align-items-center gap-3">
      <button type="submit" class="btn btn-accent">
        <i class="bi bi-file-earmark-excel me-1"></i>Download Excel (.xlsx)
      </button>
      <span class="text-muted-fab" style="font-size:13px">Includes customer info + all license fields in one sheet</span>
    </div>

  </form>
</div>

<!-- Column preview -->
<div class="fab-card" style="max-width:700px">
  <div class="fab-section-label">Exported Columns</div>
  <div class="row g-2">
    <div class="col-md-6">
      <div class="text-muted-fab mb-1" style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.05em">Customer</div>
      <ul class="mb-0 ps-3" style="font-size:13px;line-height:2">
        <li>Customer ID</li>
        <li>Company Name</li>
        <li>Contact Person</li>
        <li>Mobile</li>
        <li>Email</li>
        <li>GST Number</li>
        <li>Address</li>
      </ul>
    </div>
    <div class="col-md-6">
      <div class="text-muted-fab mb-1" style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.05em">License</div>
      <ul class="mb-0 ps-3" style="font-size:13px;line-height:2">
        <li>Product</li>
        <li>License Type &amp; Machine Name</li>
        <li>Server Code &amp; Lock Code</li>
        <li>Purchase Date &amp; Expiry Date</li>
        <li>Days Left &amp; License Status</li>
        <li>Purchase Price &amp; AMC Cost</li>
        <li>Renewal Date &amp; AMC Status</li>
        <li>Remarks</li>
      </ul>
    </div>
  </div>
</div>

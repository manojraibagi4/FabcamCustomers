<?php
function daysClass(int $d): string {
    if ($d < 0)   return 'days-expired';
    if ($d <= 7)  return 'days-critical';
    if ($d <= 30) return 'days-warning';
    return 'days-ok';
}
?>
<div class="fab-page-header">
  <h1 class="fab-page-title">Licenses</h1>
  <a href="<?= BASE_URL ?>/licenses/add" class="btn btn-accent"><i class="bi bi-plus-lg me-1"></i>Add License</a>
</div>

<!-- Filters -->
<form method="GET" action="<?= BASE_URL ?>/licenses" class="d-flex gap-2 mb-3 flex-wrap">
  <select name="status" class="form-select" style="width:auto">
    <option value="">All Statuses</option>
    <?php foreach (['active','expired','grace','revoked'] as $s): ?>
    <option value="<?= $s ?>" <?= ($filters['status'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
    <?php endforeach; ?>
  </select>
  <select name="amc_status" class="form-select" style="width:auto">
    <option value="">All AMC Statuses</option>
    <?php foreach (['active' => 'AMC Active', 'expired' => 'AMC Expired', 'not_applicable' => 'Not Applicable'] as $v => $l): ?>
    <option value="<?= $v ?>" <?= ($filters['amc_status'] ?? '') === $v ? 'selected' : '' ?>><?= $l ?></option>
    <?php endforeach; ?>
  </select>
  <select name="product_id" class="form-select" style="width:auto">
    <option value="">All Products</option>
    <?php foreach ($products as $p): ?>
    <option value="<?= (int)$p['id'] ?>" <?= (int)($filters['product_id'] ?? 0) === (int)$p['id'] ? 'selected' : '' ?>>
      <?= htmlspecialchars($p['product_name'], ENT_QUOTES, 'UTF-8') ?>
    </option>
    <?php endforeach; ?>
  </select>
  <button type="submit" class="btn btn-accent"><i class="bi bi-funnel me-1"></i>Filter</button>
  <a href="<?= BASE_URL ?>/licenses" class="btn btn-filter-clear"><i class="bi bi-x-lg me-1"></i>Clear</a>
</form>

<div class="fab-table-wrap">
  <?php if (empty($licenses)): ?>
  <div class="px-4 py-4 text-muted-fab text-center">No licenses found.</div>
  <?php else: ?>
  <div class="table-responsive">
    <table class="fab-table">
      <thead>
        <tr>
          <th>Customer</th>
          <th>Product</th>
          <th>Type</th>
          <th>Server Code</th>
          <th>Machine Name</th>
          <th>Expiry Date</th>
          <th>Days Left</th>
          <th>Status</th>
          <th>AMC</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($licenses as $lic): ?>
        <?php $days = (int)$lic['days_left']; ?>
        <tr>
          <td>
            <a href="<?= BASE_URL ?>/customers/view/<?= (int)$lic['customer_id'] ?>" class="fw-semibold">
              <?= htmlspecialchars($lic['company_name'], ENT_QUOTES, 'UTF-8') ?>
            </a>
            <div class="text-muted-fab" style="font-size:12px"><?= htmlspecialchars($lic['cust_code'], ENT_QUOTES, 'UTF-8') ?></div>
          </td>
          <td><?= htmlspecialchars($lic['product_name'], ENT_QUOTES, 'UTF-8') ?></td>
          <td class="text-capitalize"><?= htmlspecialchars($lic['license_type'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><code><?= htmlspecialchars($lic['server_code'] ?? '—', ENT_QUOTES, 'UTF-8') ?></code></td>
          <td><?= htmlspecialchars($lic['machine_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($lic['expiry_date'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
          <td>
            <?php if ($lic['expiry_date']): ?>
            <span class="days-badge <?= daysClass($days) ?>"><?= $days ?> days</span>
            <?php else: ?>—<?php endif; ?>
          </td>
          <td><span class="badge badge-<?= htmlspecialchars($lic['license_status'], ENT_QUOTES, 'UTF-8') ?>"><?= ucfirst($lic['license_status']) ?></span></td>
          <td>
            <?php
            $amc = $lic['amc_status'];
            $cls = $amc === 'active' ? 'amc-active' : ($amc === 'expired' ? 'amc-expired' : 'na');
            echo '<span class="badge badge-'.$cls.'">'.htmlspecialchars(str_replace('_',' ',ucfirst($amc)), ENT_QUOTES, 'UTF-8').'</span>';
            ?>
          </td>
          <td class="action-links">
            <a href="<?= BASE_URL ?>/licenses/view/<?= (int)$lic['id'] ?>" class="btn btn-sm btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
            <a href="<?= BASE_URL ?>/licenses/edit/<?= (int)$lic['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
            <form method="POST" action="<?= BASE_URL ?>/licenses/delete/<?= (int)$lic['id'] ?>" class="d-inline">
              <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"
                      data-confirm="Delete this license? This cannot be undone."><i class="bi bi-trash3"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

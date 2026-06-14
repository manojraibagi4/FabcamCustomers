<?php
function daysClass(int $days): string {
    if ($days < 0)  return 'days-expired';
    if ($days <= 7) return 'days-critical';
    if ($days <= 30) return 'days-warning';
    return 'days-ok';
}
?>
<div class="fab-page-header">
  <h1 class="fab-page-title">Dashboard</h1>
</div>

<!-- Stat cards -->
<div class="row g-3 mb-4">
  <div class="col-sm-6 col-xl">
    <div class="fab-stat-card">
      <div class="fab-stat-value"><?= (int)($stats['total_customers'] ?? 0) ?></div>
      <div class="fab-stat-label">Total Customers</div>
    </div>
  </div>
  <div class="col-sm-6 col-xl">
    <div class="fab-stat-card stat-active">
      <div class="fab-stat-value"><?= (int)($stats['active_licenses'] ?? 0) ?></div>
      <div class="fab-stat-label">Active Licenses</div>
    </div>
  </div>
  <div class="col-sm-6 col-xl">
    <div class="fab-stat-card stat-expiring">
      <div class="fab-stat-value"><?= (int)($stats['expiring_soon'] ?? 0) ?></div>
      <div class="fab-stat-label">Expiring in 30 Days</div>
    </div>
  </div>
  <div class="col-sm-6 col-xl">
    <div class="fab-stat-card stat-expired">
      <div class="fab-stat-value"><?= (int)($stats['expired_licenses'] ?? 0) ?></div>
      <div class="fab-stat-label">Expired Licenses</div>
    </div>
  </div>
  <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
  <div class="col-sm-6 col-xl">
    <div class="fab-stat-card stat-amc">
      <div class="fab-stat-value" style="font-size:1.35rem">&#8377; <?= number_format((float)($stats['total_amc_revenue'] ?? 0), 2) ?></div>
      <div class="fab-stat-label">Total AMC Revenue</div>
    </div>
  </div>
  <?php endif; ?>
</div>

<!-- Expiring soon table -->
<div class="fab-card p-0">
  <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
    <h6 class="mb-0 fw-semibold">Licenses Expiring Within 30 Days</h6>
    <a href="<?= BASE_URL ?>/licenses?status=active" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i>View All</a>
  </div>
  <?php if (empty($expiring)): ?>
  <div class="px-4 py-4 text-muted-fab text-center">No licenses expiring soon.</div>
  <?php else: ?>
  <div class="table-responsive">
    <table class="fab-table">
      <thead>
        <tr>
          <th>Customer</th>
          <th>Product</th>
          <th>License Type</th>
          <th>Expiry Date</th>
          <th>Days Left</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($expiring as $lic): ?>
        <?php $days = (int)$lic['days_left']; ?>
        <tr>
          <td>
            <a href="<?= BASE_URL ?>/customers/view/<?= (int)$lic['customer_id'] ?>">
              <?= htmlspecialchars($lic['company_name'], ENT_QUOTES, 'UTF-8') ?>
            </a>
            <div class="text-muted-fab" style="font-size:12px"><?= htmlspecialchars($lic['cust_code'], ENT_QUOTES, 'UTF-8') ?></div>
          </td>
          <td><?= htmlspecialchars($lic['product_name'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><span class="text-capitalize"><?= htmlspecialchars($lic['license_type'], ENT_QUOTES, 'UTF-8') ?></span></td>
          <td><?= htmlspecialchars($lic['expiry_date'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><span class="days-badge <?= daysClass($days) ?>"><?= $days ?> days</span></td>
          <td><span class="badge badge-<?= htmlspecialchars($lic['license_status'], ENT_QUOTES, 'UTF-8') ?>"><?= ucfirst($lic['license_status']) ?></span></td>
          <td class="action-links">
            <a href="<?= BASE_URL ?>/licenses/view/<?= (int)$lic['id'] ?>" class="btn btn-sm btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
            <a href="<?= BASE_URL ?>/licenses/edit/<?= (int)$lic['id'] ?>" class="btn btn-sm btn-outline-primary" title="Renew / Edit"><i class="bi bi-arrow-clockwise"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

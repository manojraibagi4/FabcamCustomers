<?php
function daysClass(int $days): string {
    if ($days < 0)   return 'days-expired';
    if ($days <= 7)  return 'days-critical';
    if ($days <= 30) return 'days-warning';
    return 'days-ok';
}
$activeCount   = (int)($stats['active_licenses']  ?? 0);
$expiredCount  = (int)($stats['expired_licenses'] ?? 0);
$graceCount    = (int)($stats['grace_licenses']   ?? 0);
$revokedCount  = (int)($stats['revoked_licenses'] ?? 0);
$amcActive     = (int)($stats['amc_active']       ?? 0);
$amcExpired    = (int)($stats['amc_expired']       ?? 0);
$amcNa         = (int)($stats['amc_na']            ?? 0);
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
      <div class="fab-stat-value"><?= $activeCount ?></div>
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
      <div class="fab-stat-value"><?= $expiredCount ?></div>
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

<!-- Charts row -->
<div class="row g-3 mb-4">

  <!-- Donut chart: License Status -->
  <div class="col-lg-5">
    <div class="fab-card h-100">
      <h6 class="fw-semibold mb-3">License Status Distribution</h6>
      <div style="position:relative;height:220px;display:flex;align-items:center;justify-content:center;">
        <canvas id="licenseStatusChart"></canvas>
      </div>
      <div class="d-flex flex-wrap justify-content-center gap-3 mt-3" style="font-size:13px">
        <span class="d-flex align-items-center gap-1">
          <span style="width:12px;height:12px;border-radius:50%;background:#107C10;flex-shrink:0"></span>
          Active <strong><?= $activeCount ?></strong>
        </span>
        <span class="d-flex align-items-center gap-1">
          <span style="width:12px;height:12px;border-radius:50%;background:#CA5010;flex-shrink:0"></span>
          Grace <strong><?= $graceCount ?></strong>
        </span>
        <span class="d-flex align-items-center gap-1">
          <span style="width:12px;height:12px;border-radius:50%;background:#D13438;flex-shrink:0"></span>
          Expired <strong><?= $expiredCount ?></strong>
        </span>
        <span class="d-flex align-items-center gap-1">
          <span style="width:12px;height:12px;border-radius:50%;background:#A19F9D;flex-shrink:0"></span>
          Revoked <strong><?= $revokedCount ?></strong>
        </span>
      </div>
    </div>
  </div>

  <!-- Bar chart: AMC Status -->
  <div class="col-lg-7">
    <div class="fab-card h-100">
      <h6 class="fw-semibold mb-3">AMC Status Breakdown</h6>
      <div style="position:relative;height:220px;">
        <canvas id="amcStatusChart"></canvas>
      </div>
      <div class="d-flex flex-wrap justify-content-center gap-3 mt-3" style="font-size:13px">
        <span class="d-flex align-items-center gap-1">
          <span style="width:12px;height:12px;border-radius:2px;background:#107C10;flex-shrink:0"></span>
          AMC Active <strong><?= $amcActive ?></strong>
        </span>
        <span class="d-flex align-items-center gap-1">
          <span style="width:12px;height:12px;border-radius:2px;background:#D13438;flex-shrink:0"></span>
          AMC Expired <strong><?= $amcExpired ?></strong>
        </span>
        <span class="d-flex align-items-center gap-1">
          <span style="width:12px;height:12px;border-radius:2px;background:#E0E0E0;flex-shrink:0"></span>
          Not Applicable <strong><?= $amcNa ?></strong>
        </span>
      </div>
    </div>
  </div>

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

<!-- Chart.js (dashboard only) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
  Chart.defaults.font.family = "'Segoe UI', system-ui, -apple-system, sans-serif";
  Chart.defaults.font.size   = 13;

  // --- Donut: License Status ---
  new Chart(document.getElementById('licenseStatusChart'), {
    type: 'doughnut',
    data: {
      labels: ['Active', 'Grace', 'Expired', 'Revoked'],
      datasets: [{
        data: [<?= $activeCount ?>, <?= $graceCount ?>, <?= $expiredCount ?>, <?= $revokedCount ?>],
        backgroundColor: ['#107C10', '#CA5010', '#D13438', '#A19F9D'],
        borderWidth: 2,
        borderColor: '#fff',
        hoverOffset: 6,
      }]
    },
    options: {
      cutout: '68%',
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(ctx) {
              const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
              const pct   = total ? Math.round(ctx.parsed / total * 100) : 0;
              return ' ' + ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
            }
          }
        }
      }
    }
  });

  // --- Bar: AMC Status ---
  new Chart(document.getElementById('amcStatusChart'), {
    type: 'bar',
    data: {
      labels: ['AMC Active', 'AMC Expired', 'Not Applicable'],
      datasets: [{
        label: 'Licenses',
        data: [<?= $amcActive ?>, <?= $amcExpired ?>, <?= $amcNa ?>],
        backgroundColor: ['#107C10', '#D13438', '#E0E0E0'],
        borderRadius: 4,
        borderSkipped: false,
        maxBarThickness: 56,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(ctx) { return ' ' + ctx.parsed.y + ' licenses'; }
          }
        }
      },
      scales: {
        x: { grid: { display: false }, border: { display: false } },
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1, precision: 0 },
          grid: { color: '#F0F0F0' },
          border: { display: false }
        }
      }
    }
  });
})();
</script>

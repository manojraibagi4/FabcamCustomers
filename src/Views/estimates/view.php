<?php
$statusClass = [
    'draft'     => 'badge-revoked',
    'sent'      => 'badge-admin',
    'accepted'  => 'badge-active',
    'cancelled' => 'badge-expired',
];
$sc = $statusClass[$estimate['status']] ?? 'badge-revoked';
$halfRate = (float)$estimate['tax_rate'] / 2;
?>
<div class="fab-page-header">
  <div>
    <h1 class="fab-page-title"><?= htmlspecialchars($estimate['estimate_number'], ENT_QUOTES, 'UTF-8') ?></h1>
    <span class="badge <?= $sc ?>"><?= ucfirst($estimate['status']) ?></span>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <a href="<?= BASE_URL ?>/estimates/edit/<?= (int)$estimate['id'] ?>" class="btn btn-accent"><i class="bi bi-pencil me-1"></i>Edit</a>
    <a href="<?= BASE_URL ?>/estimates/pdf/<?= (int)$estimate['id'] ?>" class="btn btn-outline-success" target="_blank">
      <i class="bi bi-file-earmark-pdf me-1"></i>Download PDF</a>
    <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
    <form method="POST" action="<?= BASE_URL ?>/estimates/delete/<?= (int)$estimate['id'] ?>" class="d-inline">
      <button type="submit" class="btn btn-outline-danger"
              data-confirm="Delete <?= htmlspecialchars($estimate['estimate_number'], ENT_QUOTES, 'UTF-8') ?>? This cannot be undone.">
        <i class="bi bi-trash3 me-1"></i>Delete</button>
    </form>
    <?php endif; ?>
    <a href="<?= BASE_URL ?>/estimates" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
  </div>
</div>

<!-- Header cards -->
<div class="row g-3 mb-3">
  <div class="col-md-6">
    <div class="fab-card h-100">
      <div class="fab-section-label">Bill To</div>
      <div class="fw-bold fs-6 mb-1">
        <a href="<?= BASE_URL ?>/customers/view/<?= (int)$estimate['customer_id'] ?>">
          <?= htmlspecialchars($estimate['company_name'], ENT_QUOTES, 'UTF-8') ?>
        </a>
        <span class="badge badge-admin ms-1"><?= htmlspecialchars($estimate['cust_code'], ENT_QUOTES, 'UTF-8') ?></span>
      </div>
      <?php if ($estimate['gst_number']): ?>
      <div class="text-muted-fab" style="font-size:13px">GSTIN: <code><?= htmlspecialchars($estimate['gst_number'], ENT_QUOTES, 'UTF-8') ?></code></div>
      <?php endif; ?>
      <?php if ($estimate['customer_address']): ?>
      <div class="text-muted-fab mt-1" style="font-size:13px"><?= nl2br(htmlspecialchars($estimate['customer_address'], ENT_QUOTES, 'UTF-8')) ?></div>
      <?php endif; ?>
      <?php if ($estimate['mobile']): ?>
      <div class="text-muted-fab" style="font-size:13px"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($estimate['mobile'], ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>
    </div>
  </div>
  <div class="col-md-6">
    <div class="fab-card h-100">
      <div class="fab-section-label">Estimate Info</div>
      <table class="table table-sm table-borderless mb-0">
        <tr><td class="text-muted-fab" width="130">Estimate No.</td><td class="fw-semibold"><?= htmlspecialchars($estimate['estimate_number'], ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><td class="text-muted-fab">Date</td><td><?= htmlspecialchars($estimate['estimate_date'], ENT_QUOTES, 'UTF-8') ?></td></tr>
        <?php if ($estimate['valid_until']): ?>
        <tr><td class="text-muted-fab">Valid Until</td><td><?= htmlspecialchars($estimate['valid_until'], ENT_QUOTES, 'UTF-8') ?></td></tr>
        <?php endif; ?>
        <tr><td class="text-muted-fab">Created By</td><td><?= htmlspecialchars($estimate['created_by_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td></tr>
      </table>
    </div>
  </div>
</div>

<!-- Line Items -->
<div class="fab-card p-0 mb-3">
  <div class="px-4 py-3 border-bottom fw-semibold">Line Items</div>
  <div class="table-responsive">
    <table class="fab-table">
      <thead>
        <tr>
          <th style="width:40px">#</th>
          <th>Description</th>
          <th>HSN/SAC</th>
          <th class="text-center">Qty</th>
          <th class="text-center">Unit</th>
          <th class="text-end">Rate (&#8377;)</th>
          <th class="text-end">Amount (&#8377;)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
          <td class="text-muted-fab text-center"><?= (int)$item['sl_no'] ?></td>
          <td class="fw-semibold"><?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><code><?= htmlspecialchars($item['hsn_sac'] ?? '', ENT_QUOTES, 'UTF-8') ?></code></td>
          <td class="text-center"><?= rtrim(rtrim(number_format((float)$item['quantity'], 3), '0'), '.') ?></td>
          <td class="text-center"><?= htmlspecialchars($item['unit'] ?? 'Nos', ENT_QUOTES, 'UTF-8') ?></td>
          <td class="text-end"><?= number_format((float)$item['unit_price'], 2) ?></td>
          <td class="text-end fw-semibold"><?= number_format((float)$item['amount'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Totals + Notes -->
<div class="row g-3 mb-3">
  <div class="col-md-7">
    <?php if ($estimate['notes']): ?>
    <div class="fab-card mb-3">
      <div class="fab-section-label">Notes</div>
      <p class="mb-0" style="font-size:14px"><?= nl2br(htmlspecialchars($estimate['notes'], ENT_QUOTES, 'UTF-8')) ?></p>
    </div>
    <?php endif; ?>
    <?php if ($estimate['terms']): ?>
    <div class="fab-card">
      <div class="fab-section-label">Terms &amp; Conditions</div>
      <p class="mb-0" style="font-size:13px; color:var(--text-secondary)"><?= nl2br(htmlspecialchars($estimate['terms'], ENT_QUOTES, 'UTF-8')) ?></p>
    </div>
    <?php endif; ?>
  </div>
  <div class="col-md-5">
    <div class="fab-card">
      <table class="table table-sm table-borderless mb-0" style="font-size:14px">
        <tr><td class="text-muted-fab">Sub Total</td><td class="text-end fw-semibold">&#8377; <?= number_format((float)$estimate['subtotal'], 2) ?></td></tr>
        <?php if ((float)$estimate['discount_pct'] > 0): ?>
        <tr>
          <td class="text-muted-fab">Discount (<?= number_format((float)$estimate['discount_pct'], 2) ?>%)</td>
          <td class="text-end text-danger">- &#8377; <?= number_format((float)$estimate['discount_amt'], 2) ?></td>
        </tr>
        <tr><td class="text-muted-fab">Taxable Amount</td><td class="text-end fw-semibold">&#8377; <?= number_format((float)$estimate['taxable_amount'], 2) ?></td></tr>
        <?php endif; ?>

        <?php if ($estimate['tax_type'] === 'cgst_sgst'): ?>
        <tr><td class="text-muted-fab">CGST (<?= number_format($halfRate, 1) ?>%)</td><td class="text-end">&#8377; <?= number_format((float)$estimate['cgst_amount'], 2) ?></td></tr>
        <tr><td class="text-muted-fab">SGST (<?= number_format($halfRate, 1) ?>%)</td><td class="text-end">&#8377; <?= number_format((float)$estimate['sgst_amount'], 2) ?></td></tr>
        <?php elseif ($estimate['tax_type'] === 'igst'): ?>
        <tr><td class="text-muted-fab">IGST (<?= number_format((float)$estimate['tax_rate'], 1) ?>%)</td><td class="text-end">&#8377; <?= number_format((float)$estimate['igst_amount'], 2) ?></td></tr>
        <?php else: ?>
        <tr><td class="text-muted-fab">Tax</td><td class="text-end">Nil</td></tr>
        <?php endif; ?>

        <tr style="border-top:2px solid var(--accent)">
          <td class="text-accent fw-bold" style="font-size:17px">Grand Total</td>
          <td class="text-end text-accent fw-bold" style="font-size:17px">&#8377; <?= number_format((float)$estimate['grand_total'], 2) ?></td>
        </tr>
      </table>
    </div>
  </div>
</div>

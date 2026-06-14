<?php
$isEdit = !empty($estimate['id']);
$action = $isEdit
    ? BASE_URL . '/estimates/edit/' . (int)$estimate['id']
    : BASE_URL . '/estimates/add';
?>
<div class="fab-page-header">
  <h1 class="fab-page-title"><?= $isEdit ? htmlspecialchars($estimate['estimate_number'] ?? 'Edit Estimate', ENT_QUOTES, 'UTF-8') : 'New Estimate' ?></h1>
  <a href="<?= BASE_URL ?>/estimates" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger mb-3">
  <?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></div><?php endforeach; ?>
</div>
<?php endif; ?>

<form id="estimateForm" method="POST" action="<?= $action ?>">
  <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
  <input type="hidden" name="_items" id="itemsJson">

  <!-- Header details -->
  <div class="fab-card mb-3">
    <div class="fab-section-label">Estimate Details</div>
    <div class="row g-3">
      <div class="col-md-5">
        <label class="form-label">Customer <span class="text-danger">*</span></label>
        <select name="customer_id" class="form-select" data-searchable required>
          <option value="">— Select Customer —</option>
          <?php foreach ($customers as $c): ?>
          <option value="<?= (int)$c['id'] ?>" <?= (int)($estimate['customer_id'] ?? 0) === (int)$c['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['customer_id'] . ' — ' . $c['company_name'], ENT_QUOTES, 'UTF-8') ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <?php foreach (['draft'=>'Draft','sent'=>'Sent','accepted'=>'Accepted','cancelled'=>'Cancelled'] as $v=>$l): ?>
          <option value="<?= $v ?>" <?= ($estimate['status'] ?? 'draft') === $v ? 'selected' : '' ?>><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Estimate Date <span class="text-danger">*</span></label>
        <input type="date" name="estimate_date" class="form-control" required
               value="<?= htmlspecialchars($estimate['estimate_date'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8') ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label">Valid Until</label>
        <input type="date" name="valid_until" class="form-control"
               value="<?= htmlspecialchars($estimate['valid_until'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
      </div>
    </div>
  </div>

  <!-- Line Items -->
  <div class="fab-card mb-3 p-0">
    <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
      <h6 class="mb-0 fw-semibold">Line Items</h6>
      <button type="button" class="btn btn-sm btn-accent" id="addRowBtn"><i class="bi bi-plus-lg me-1"></i>Add Item</button>
    </div>
    <div class="table-responsive">
      <table class="fab-table" style="min-width:700px">
        <thead>
          <tr>
            <th style="width:40px">#</th>
            <th>Description <span class="text-danger">*</span></th>
            <th style="width:100px">HSN/SAC</th>
            <th style="width:75px">Qty</th>
            <th style="width:75px">Unit</th>
            <th style="width:130px">Rate (&#8377;)</th>
            <th style="width:120px">Amount (&#8377;)</th>
            <th style="width:44px"></th>
          </tr>
        </thead>
        <tbody id="itemsTbody"></tbody>
      </table>
    </div>
  </div>

  <!-- Tax & Totals + Notes/Terms -->
  <div class="row g-3 mb-3">
    <div class="col-lg-7">
      <div class="fab-card h-100">
        <div class="fab-section-label">Notes</div>
        <textarea name="notes" class="form-control mb-3" rows="3"
                  placeholder="Additional notes visible to the customer..."><?= htmlspecialchars($estimate['notes'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        <div class="fab-section-label">Terms &amp; Conditions</div>
        <textarea name="terms" class="form-control" rows="6"><?= htmlspecialchars($estimate['terms'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="fab-card h-100">
        <div class="fab-section-label">Tax &amp; Totals</div>
        <div class="row g-2 mb-3">
          <div class="col-12">
            <label class="form-label">Tax Type</label>
            <select name="tax_type" class="form-select" id="taxType">
              <option value="none"      <?= ($estimate['tax_type'] ?? '') === 'none'      ? 'selected' : '' ?>>No Tax</option>
              <option value="cgst_sgst" <?= ($estimate['tax_type'] ?? 'cgst_sgst') === 'cgst_sgst' ? 'selected' : '' ?>>CGST + SGST (Intra-State)</option>
              <option value="igst"      <?= ($estimate['tax_type'] ?? '') === 'igst'      ? 'selected' : '' ?>>IGST (Inter-State)</option>
            </select>
          </div>
          <div class="col-7" id="taxRateRow">
            <label class="form-label">Tax Rate</label>
            <select name="tax_rate" class="form-select" id="taxRate">
              <?php foreach ([5, 12, 18, 28] as $r): ?>
              <option value="<?= $r ?>" <?= (int)($estimate['tax_rate'] ?? 18) === $r ? 'selected' : '' ?>><?= $r ?>%</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-5">
            <label class="form-label">Discount (%)</label>
            <input type="number" name="discount_pct" class="form-control" id="discountPct"
                   min="0" max="100" step="0.01"
                   value="<?= htmlspecialchars(number_format((float)($estimate['discount_pct'] ?? 0), 2), ENT_QUOTES, 'UTF-8') ?>">
          </div>
        </div>

        <!-- Live summary -->
        <table class="table table-sm table-borderless mb-0" style="font-size:14px">
          <tr>
            <td class="text-muted-fab">Sub Total</td>
            <td class="text-end fw-semibold" id="sumSubtotal">&#8377; 0.00</td>
          </tr>
          <tr id="discRow" style="display:none">
            <td class="text-muted-fab" id="discLabel">Discount</td>
            <td class="text-end text-danger" id="sumDiscount"></td>
          </tr>
          <tr id="taxableRow" style="display:none">
            <td class="text-muted-fab">Taxable Amount</td>
            <td class="text-end fw-semibold" id="sumTaxable"></td>
          </tr>
          <tr id="cgstRow" style="display:none">
            <td class="text-muted-fab" id="cgstLabel">CGST</td>
            <td class="text-end" id="sumCgst"></td>
          </tr>
          <tr id="sgstRow" style="display:none">
            <td class="text-muted-fab" id="sgstLabel">SGST</td>
            <td class="text-end" id="sumSgst"></td>
          </tr>
          <tr id="igstRow" style="display:none">
            <td class="text-muted-fab" id="igstLabel">IGST</td>
            <td class="text-end" id="sumIgst"></td>
          </tr>
          <tr style="border-top:2px solid var(--accent)">
            <td class="text-accent fw-bold" style="font-size:16px">Grand Total</td>
            <td class="text-end text-accent fw-bold" style="font-size:16px" id="sumGrandTotal">&#8377; 0.00</td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <div class="d-flex gap-2 mb-4">
    <button type="submit" class="btn btn-accent">
      <i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Update Estimate' : 'Create Estimate' ?>
    </button>
    <a href="<?= BASE_URL ?>/estimates" class="btn btn-outline-secondary"><i class="bi bi-x me-1"></i>Cancel</a>
  </div>
</form>

<script>
(function () {
  // Seed from PHP (edit mode pre-populates from DB rows)
  var SEED = <?= json_encode(array_values($items), JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
  var items = SEED.length ? SEED.map(function (r) {
    return {
      description: r.description || '',
      hsn_sac:     r.hsn_sac     || '',
      quantity:    parseFloat(r.quantity)   || 1,
      unit:        r.unit         || 'Nos',
      unit_price:  parseFloat(r.unit_price) || 0,
    };
  }) : [];

  function fmt(n) {
    var v = parseFloat(n) || 0;
    return '₹ ' + v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }
  function esc(s) {
    return String(s || '')
      .replace(/&/g, '&amp;').replace(/"/g, '&quot;')
      .replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }

  function render() {
    var tbody = document.getElementById('itemsTbody');
    tbody.innerHTML = '';
    if (!items.length) { items.push({description:'',hsn_sac:'',quantity:1,unit:'Nos',unit_price:0}); }
    items.forEach(function (item, i) {
      var amt = (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0);
      var tr  = document.createElement('tr');
      tr.innerHTML =
        '<td class="text-muted-fab text-center" style="vertical-align:middle">' + (i + 1) + '</td>' +
        '<td><input type="text" class="form-control form-control-sm" placeholder="Product / Service description" ' +
          'value="' + esc(item.description) + '" data-i="' + i + '" data-f="description"></td>' +
        '<td><input type="text" class="form-control form-control-sm" placeholder="998313" ' +
          'value="' + esc(item.hsn_sac) + '" data-i="' + i + '" data-f="hsn_sac"></td>' +
        '<td><input type="number" class="form-control form-control-sm" min="0.001" step="0.001" ' +
          'value="' + item.quantity + '" data-i="' + i + '" data-f="quantity"></td>' +
        '<td><input type="text" class="form-control form-control-sm" ' +
          'value="' + esc(item.unit) + '" data-i="' + i + '" data-f="unit"></td>' +
        '<td><input type="number" class="form-control form-control-sm" min="0" step="0.01" ' +
          'value="' + item.unit_price + '" data-i="' + i + '" data-f="unit_price"></td>' +
        '<td class="fw-semibold text-end" style="vertical-align:middle">' + fmt(amt) + '</td>' +
        '<td style="vertical-align:middle">' +
          '<button type="button" class="btn btn-sm btn-outline-danger" data-del="' + i + '" title="Remove">' +
            '<i class="bi bi-x-lg"></i></button></td>';
      tbody.appendChild(tr);
    });
    calcTotals();
  }

  // Event delegation on tbody
  document.getElementById('itemsTbody').addEventListener('input', function (e) {
    var el = e.target;
    var i  = el.dataset.i;
    var f  = el.dataset.f;
    if (i !== undefined && f) {
      items[i][f] = el.value;
      // Re-render amount column only (avoid losing focus)
      var row = el.closest('tr');
      var qty   = parseFloat(items[i].quantity)   || 0;
      var price = parseFloat(items[i].unit_price) || 0;
      row.cells[6].textContent = '₹ ' + (qty * price).toFixed(2);
      calcTotals();
    }
  });
  document.getElementById('itemsTbody').addEventListener('click', function (e) {
    var btn = e.target.closest('[data-del]');
    if (btn) {
      items.splice(parseInt(btn.dataset.del), 1);
      render();
    }
  });

  document.getElementById('addRowBtn').addEventListener('click', function () {
    items.push({description:'', hsn_sac:'', quantity:1, unit:'Nos', unit_price:0});
    render();
  });

  function calcTotals() {
    var subtotal = 0;
    items.forEach(function (r) {
      subtotal += (parseFloat(r.quantity) || 0) * (parseFloat(r.unit_price) || 0);
    });
    var discPct = parseFloat(document.getElementById('discountPct').value) || 0;
    var discAmt = subtotal * discPct / 100;
    var taxable = subtotal - discAmt;
    var taxType = document.getElementById('taxType').value;
    var taxRate = parseFloat(document.getElementById('taxRate').value) || 0;
    var cgst = 0, sgst = 0, igst = 0;
    if (taxType === 'cgst_sgst') { cgst = sgst = taxable * (taxRate / 2) / 100; }
    else if (taxType === 'igst') { igst = taxable * taxRate / 100; }
    var grand = taxable + cgst + sgst + igst;

    document.getElementById('sumSubtotal').textContent   = fmt(subtotal);
    document.getElementById('sumGrandTotal').textContent = fmt(grand);

    var showDisc = discPct > 0;
    document.getElementById('discRow').style.display    = showDisc ? '' : 'none';
    document.getElementById('taxableRow').style.display = showDisc ? '' : 'none';
    if (showDisc) {
      document.getElementById('discLabel').textContent   = 'Discount (' + discPct.toFixed(2) + '%)';
      document.getElementById('sumDiscount').textContent = '- ₹ ' + discAmt.toFixed(2);
      document.getElementById('sumTaxable').textContent  = fmt(taxable);
    }

    document.getElementById('taxRateRow').style.display = taxType !== 'none' ? '' : 'none';
    document.getElementById('cgstRow').style.display    = taxType === 'cgst_sgst' ? '' : 'none';
    document.getElementById('sgstRow').style.display    = taxType === 'cgst_sgst' ? '' : 'none';
    document.getElementById('igstRow').style.display    = taxType === 'igst' ? '' : 'none';
    if (taxType === 'cgst_sgst') {
      var h = (taxRate / 2).toFixed(1);
      document.getElementById('cgstLabel').textContent = 'CGST (' + h + '%)';
      document.getElementById('sgstLabel').textContent = 'SGST (' + h + '%)';
      document.getElementById('sumCgst').textContent   = fmt(cgst);
      document.getElementById('sumSgst').textContent   = fmt(sgst);
    } else if (taxType === 'igst') {
      document.getElementById('igstLabel').textContent = 'IGST (' + taxRate.toFixed(1) + '%)';
      document.getElementById('sumIgst').textContent   = fmt(igst);
    }
  }

  ['taxType','taxRate'].forEach(function (id) {
    document.getElementById(id).addEventListener('change', calcTotals);
  });
  document.getElementById('discountPct').addEventListener('input', calcTotals);

  // Serialize & validate on submit
  document.getElementById('estimateForm').addEventListener('submit', function (e) {
    var filled = items.filter(function (r) { return r.description.trim() !== ''; });
    if (!filled.length) {
      e.preventDefault();
      alert('Please add at least one line item with a description.');
      return;
    }
    document.getElementById('itemsJson').value = JSON.stringify(items);
  });

  render();
}());
</script>

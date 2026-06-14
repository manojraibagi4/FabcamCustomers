<?php
// ─── Helpers ─────────────────────────────────────────────────────────────────
if (!function_exists('_fabNumW')) {
    function _fabNumW(int $n): string {
        $ones = ['','One','Two','Three','Four','Five','Six','Seven','Eight','Nine',
                 'Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen',
                 'Seventeen','Eighteen','Nineteen'];
        $tens = ['','','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];
        if ($n === 0) return '';
        $w = '';
        if ($n >= 10000000) { $w .= _fabNumW((int)($n / 10000000)) . ' Crore '; $n %= 10000000; }
        if ($n >= 100000)   { $w .= _fabNumW((int)($n / 100000))   . ' Lakh ';  $n %= 100000;  }
        if ($n >= 1000)     { $w .= _fabNumW((int)($n / 1000))     . ' Thousand '; $n %= 1000;  }
        if ($n >= 100)      { $w .= $ones[(int)($n / 100)] . ' Hundred '; $n %= 100; }
        if ($n >= 20)       { $w .= $tens[(int)($n / 10)] . ' '; $n %= 10; }
        if ($n > 0)         { $w .= $ones[$n] . ' '; }
        return $w;
    }
}
if (!function_exists('rupeeWords')) {
    function rupeeWords(float $amount): string {
        $amount = round($amount, 2);
        [$rStr, $pStr] = explode('.', number_format($amount, 2, '.', ''));
        $r = (int) str_replace(',', '', $rStr);
        $p = (int) $pStr;
        $out = 'Indian Rupee ' . trim(_fabNumW($r));
        if ($r === 0) $out = 'Indian Rupee Zero';
        if ($p > 0)   $out .= ' and ' . trim(_fabNumW($p)) . ' Paise';
        return $out . ' Only';
    }
}
if (!function_exists('indFmt')) {
    function indFmt(float $n): string {
        $str = number_format(round($n, 2), 2, '.', '');
        [$whole, $dec] = explode('.', $str);
        if (strlen($whole) <= 3) return $whole . '.' . $dec;
        $last3 = substr($whole, -3);
        $rest  = substr($whole, 0, -3);
        $rest  = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
        return $rest . ',' . $last3 . '.' . $dec;
    }
}

// ─── Logo ─────────────────────────────────────────────────────────────────────
// fabcamlogo_pdf.jpg is a pre-flattened JPEG (no alpha) — mPDF handles JPEG natively without GD
$logoFile = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'fabcamlogo_pdf.jpg';
$logoTag  = '';
if (file_exists($logoFile)) {
    $logoTag = '<img src="fabcamlogo_pdf.jpg" style="height:70px;width:auto;">';
}

// ─── Tax vars ─────────────────────────────────────────────────────────────────
$taxType  = $estimate['tax_type'] ?? 'none';
$taxRate  = (float)($estimate['tax_rate'] ?? 0);
$halfRate = $taxRate / 2;
$showDisc = (float)($estimate['discount_pct'] ?? 0) > 0;

// Per-line tax amounts
foreach ($items as &$item) {
    $amt = (float)$item['amount'];
    $item['cgst_amt'] = ($taxType === 'cgst_sgst') ? round($amt * $halfRate / 100, 2) : 0;
    $item['sgst_amt'] = ($taxType === 'cgst_sgst') ? round($amt * $halfRate / 100, 2) : 0;
    $item['igst_amt'] = ($taxType === 'igst')      ? round($amt * $taxRate  / 100, 2) : 0;
}
unset($item);

// Column counts for filler rows
$taxCols  = ($taxType === 'cgst_sgst') ? 4 : (($taxType === 'igst') ? 2 : 0);
$totalCols = 5 + $taxCols; // #, desc, qty, rate, [tax cols], amount
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: sans-serif; font-size:9.5pt; color:#1a1a1a; }

/* ── Outer wrapper ── */
.wrap { border:1px solid #bbb; }

/* ── Header ── */
.hdr-tbl { width:100%; border-collapse:collapse; border-bottom:1px solid #bbb; }
.hdr-tbl .co-cell { padding:12px 14px; width:62%; vertical-align:top; }
.hdr-tbl .title-cell { padding:12px 14px; border-left:1px solid #bbb; text-align:right; vertical-align:top; }
.co-name { font-size:13pt; font-weight:bold; }
.co-addr { font-size:8.5pt; color:#666; line-height:1.65; margin-top:3px; }
.doc-title { font-size:34pt; font-weight:bold; color:#bbb; letter-spacing:1px; }

/* ── Info row ── */
.info-tbl { width:100%; border-collapse:collapse; border-bottom:1px solid #bbb; background:#fafafa; }
.info-tbl td { padding:6px 12px; border-right:1px solid #ddd; font-size:9pt; }
.info-tbl td:last-child { border-right:none; }

/* ── Bill To ── */
.bill-to { padding:10px 14px; border-bottom:1px solid #bbb; }
.bill-lbl { font-size:8pt; font-weight:bold; color:#888; text-transform:uppercase; letter-spacing:0.4px; margin-bottom:4px; }
.bill-co  { font-size:11.5pt; font-weight:bold; margin-bottom:3px; }
.bill-sub { font-size:8.5pt; color:#555; line-height:1.6; }

/* ── Items table ── */
.items-tbl { width:100%; border-collapse:collapse; border-top:1px solid #bbb; font-size:9pt; }
.items-tbl th { background:#f0f0f0; padding:5px 7px; border:1px solid #ccc; font-weight:bold; text-align:center; font-size:8.5pt; }
.items-tbl th.left { text-align:left; }
.items-tbl td { padding:5px 7px; border:1px solid #ddd; vertical-align:top; }
.items-tbl tbody tr:nth-child(even) td { background:#fafafa; }
.item-desc-main { font-weight:bold; }
.item-desc-sub  { font-size:8pt; color:#666; margin-top:2px; }

/* ── Footer split: words | totals ── */
.footer-tbl { width:100%; border-collapse:collapse; border-top:1px solid #bbb; }
.footer-tbl .words-td { width:55%; padding:10px 14px; border-right:1px solid #bbb; vertical-align:top; }
.footer-tbl .totals-td { width:45%; padding:0; vertical-align:top; }
.words-lbl { font-size:8pt; font-weight:bold; color:#777; margin-bottom:4px; }
.words-val { font-size:9pt; font-style:italic; font-weight:bold; line-height:1.5; }
.notes-lbl { font-size:8pt; font-weight:bold; color:#777; margin-top:10px; margin-bottom:3px; }

/* ── Totals sub-table ── */
.tot-tbl { width:100%; border-collapse:collapse; }
.tot-tbl td { padding:5px 12px; border-bottom:1px solid #eee; }
.tot-tbl td.tot-lbl { color:#555; }
.tot-tbl td.tot-val { text-align:right; font-weight:600; }
.grand-row td { font-weight:bold; font-size:11pt; background:#f2f2f2; border-top:2px solid #888; border-bottom:none; }
.co-sig-block { text-align:center; padding:8px; border-top:1px solid #ddd; font-size:9pt; font-weight:bold; }

/* ── Terms + Sig ── */
.sig-tbl { width:100%; border-collapse:collapse; border-top:1px solid #bbb; }
.sig-tbl .terms-td { width:55%; padding:10px 14px; border-right:1px solid #bbb; vertical-align:top; }
.sig-tbl .sig-td  { width:45%; padding:10px 14px; text-align:center; vertical-align:bottom; }
.terms-lbl { font-size:8pt; font-weight:bold; color:#777; margin-bottom:5px; }
.terms-body { font-size:8.5pt; line-height:1.7; color:#444; }
.sig-line { border-top:1px solid #555; display:inline-block; min-width:180px; padding-top:4px; font-size:8.5pt; color:#555; margin-top:45px; }
</style>
</head>
<body>
<div class="wrap">

  <!-- ═══ HEADER ═══ -->
  <table class="hdr-tbl">
    <tr>
      <td class="co-cell">
        <table style="border-collapse:collapse;width:100%;">
          <tr>
            <?php if ($logoTag): ?>
            <td style="width:80px;vertical-align:middle;padding-right:12px;">
              <?= $logoTag ?>
            </td>
            <?php endif; ?>
            <td style="vertical-align:top;">
              <div class="co-name">FABCAM TECHNOLOGIES</div>
              <div class="co-addr">
                35/7A 1st Floor, 1st Main, Ayyappa Layout<br>
                Ambabhavani Tem Road, Near Sambhrama Engg. College,<br>
                Vidyaranyapura, BENGALURU Karnataka 560097<br>
                India<br>
                GSTIN 29CAFPK2482P1Z1
              </div>
            </td>
          </tr>
        </table>
      </td>
      <td class="title-cell">
        <div class="doc-title">Estimate</div>
      </td>
    </tr>
  </table>

  <!-- ═══ INFO ROW ═══ -->
  <table class="info-tbl">
    <tr>
      <td>#&nbsp;: <strong><?= htmlspecialchars($estimate['estimate_number'], ENT_QUOTES, 'UTF-8') ?></strong></td>
      <td>Estimate Date&nbsp;: <strong><?= date('d/m/Y', strtotime($estimate['estimate_date'])) ?></strong></td>
      <td>Place Of Supply&nbsp;: <strong>Karnataka (29)</strong></td>
      <?php if ($estimate['valid_until']): ?>
      <td>Valid Until&nbsp;: <strong><?= date('d/m/Y', strtotime($estimate['valid_until'])) ?></strong></td>
      <?php endif; ?>
    </tr>
  </table>

  <!-- ═══ BILL TO ═══ -->
  <div class="bill-to">
    <div class="bill-lbl">Bill To</div>
    <div class="bill-co"><?= htmlspecialchars(strtoupper($estimate['company_name']), ENT_QUOTES, 'UTF-8') ?></div>
    <?php if ($estimate['customer_address']): ?>
    <div class="bill-sub"><?= nl2br(htmlspecialchars($estimate['customer_address'], ENT_QUOTES, 'UTF-8')) ?></div>
    <?php endif; ?>
    <?php if ($estimate['gst_number']): ?>
    <div class="bill-sub">GSTIN <?= htmlspecialchars($estimate['gst_number'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if ($estimate['mobile']): ?>
    <div class="bill-sub">Ph: <?= htmlspecialchars($estimate['mobile'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
  </div>

  <!-- ═══ LINE ITEMS ═══ -->
  <table class="items-tbl">
    <thead>
      <tr>
        <th style="width:28px;">#</th>
        <th class="left">Item &amp; Description</th>
        <th style="width:42px;">Qty</th>
        <th style="width:90px;text-align:right;">Rate</th>
        <?php if ($taxType === 'cgst_sgst'): ?>
          <th colspan="2">CGST</th>
          <th colspan="2">SGST</th>
        <?php elseif ($taxType === 'igst'): ?>
          <th colspan="2">IGST</th>
        <?php endif; ?>
        <th style="width:90px;text-align:right;">Amount</th>
      </tr>
      <?php if ($taxType !== 'none'): ?>
      <tr>
        <th></th><th></th><th></th><th></th>
        <?php if ($taxType === 'cgst_sgst'): ?>
          <th style="width:34px;">%</th>
          <th style="width:75px;text-align:right;">Amt</th>
          <th style="width:34px;">%</th>
          <th style="width:75px;text-align:right;">Amt</th>
        <?php elseif ($taxType === 'igst'): ?>
          <th style="width:34px;">%</th>
          <th style="width:80px;text-align:right;">Amt</th>
        <?php endif; ?>
        <th></th>
      </tr>
      <?php endif; ?>
    </thead>
    <tbody>
      <?php foreach ($items as $item): ?>
      <tr>
        <td style="text-align:center;"><?= (int)$item['sl_no'] ?></td>
        <td>
          <div class="item-desc-main"><?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8') ?></div>
          <?php if (!empty($item['hsn_sac'])): ?>
          <div class="item-desc-sub">HSN/SAC: <?= htmlspecialchars($item['hsn_sac'], ENT_QUOTES, 'UTF-8') ?></div>
          <?php endif; ?>
        </td>
        <td style="text-align:center;"><?= rtrim(rtrim(number_format((float)$item['quantity'], 3), '0'), '.') ?></td>
        <td style="text-align:right;"><?= indFmt((float)$item['unit_price']) ?></td>
        <?php if ($taxType === 'cgst_sgst'): ?>
          <td style="text-align:center;"><?= number_format($halfRate, 0) ?>%</td>
          <td style="text-align:right;"><?= indFmt($item['cgst_amt']) ?></td>
          <td style="text-align:center;"><?= number_format($halfRate, 0) ?>%</td>
          <td style="text-align:right;"><?= indFmt($item['sgst_amt']) ?></td>
        <?php elseif ($taxType === 'igst'): ?>
          <td style="text-align:center;"><?= number_format($taxRate, 0) ?>%</td>
          <td style="text-align:right;"><?= indFmt($item['igst_amt']) ?></td>
        <?php endif; ?>
        <td style="text-align:right;font-weight:bold;"><?= indFmt((float)$item['amount']) ?></td>
      </tr>
      <?php endforeach; ?>
      <?php for ($f = count($items); $f < 5; $f++): ?>
      <tr>
        <td>&nbsp;</td><td></td><td></td><td></td>
        <?php for ($c = 0; $c < $taxCols; $c++): ?><td></td><?php endfor; ?>
        <td></td>
      </tr>
      <?php endfor; ?>
    </tbody>
  </table>

  <!-- ═══ WORDS + TOTALS ═══ -->
  <table class="footer-tbl">
    <tr>
      <td class="words-td">
        <div class="words-lbl">Total in Words</div>
        <div class="words-val"><?= htmlspecialchars(rupeeWords((float)$estimate['grand_total']), ENT_QUOTES, 'UTF-8') ?></div>
        <?php if ($estimate['notes']): ?>
        <div class="notes-lbl">Notes</div>
        <div style="font-size:9pt;line-height:1.6;"><?= nl2br(htmlspecialchars($estimate['notes'], ENT_QUOTES, 'UTF-8')) ?></div>
        <?php endif; ?>
      </td>
      <td class="totals-td">
        <table class="tot-tbl">
          <tr>
            <td class="tot-lbl">Sub Total</td>
            <td class="tot-val">&#8377;&nbsp;<?= indFmt((float)$estimate['subtotal']) ?></td>
          </tr>
          <?php if ($showDisc): ?>
          <tr>
            <td class="tot-lbl">Discount (<?= number_format((float)$estimate['discount_pct'], 1) ?>%)</td>
            <td class="tot-val" style="color:#b00;">- &#8377;&nbsp;<?= indFmt((float)$estimate['discount_amt']) ?></td>
          </tr>
          <tr>
            <td class="tot-lbl">Taxable Amount</td>
            <td class="tot-val">&#8377;&nbsp;<?= indFmt((float)$estimate['taxable_amount']) ?></td>
          </tr>
          <?php endif; ?>
          <?php if ($taxType === 'cgst_sgst'): ?>
          <tr>
            <td class="tot-lbl">CGST <?= number_format($halfRate, 0) ?> (<?= number_format($halfRate, 0) ?>%)</td>
            <td class="tot-val">&#8377;&nbsp;<?= indFmt((float)$estimate['cgst_amount']) ?></td>
          </tr>
          <tr>
            <td class="tot-lbl">SGST <?= number_format($halfRate, 0) ?> (<?= number_format($halfRate, 0) ?>%)</td>
            <td class="tot-val">&#8377;&nbsp;<?= indFmt((float)$estimate['sgst_amount']) ?></td>
          </tr>
          <?php elseif ($taxType === 'igst'): ?>
          <tr>
            <td class="tot-lbl">IGST (<?= number_format($taxRate, 0) ?>%)</td>
            <td class="tot-val">&#8377;&nbsp;<?= indFmt((float)$estimate['igst_amount']) ?></td>
          </tr>
          <?php else: ?>
          <tr>
            <td class="tot-lbl">Tax</td>
            <td class="tot-val">Nil</td>
          </tr>
          <?php endif; ?>
          <tr class="grand-row">
            <td class="tot-lbl" style="font-weight:bold;color:#1a1a1a;">Total</td>
            <td class="tot-val">&#8377;&nbsp;<?= indFmt((float)$estimate['grand_total']) ?></td>
          </tr>
        </table>
        <div class="co-sig-block">FABCAM TECHNOLOGIES</div>
      </td>
    </tr>
  </table>

  <!-- ═══ TERMS + SIGNATURE ═══ -->
  <table class="sig-tbl">
    <tr>
      <td class="terms-td">
        <div class="terms-lbl">Terms &amp; Conditions</div>
        <?php if ($estimate['terms']): ?>
        <div class="terms-body"><?= nl2br(htmlspecialchars($estimate['terms'], ENT_QUOTES, 'UTF-8')) ?></div>
        <?php endif; ?>
      </td>
      <td class="sig-td">
        <div class="sig-line">Authorised Signatory</div>
      </td>
    </tr>
  </table>

</div>
</body>
</html>

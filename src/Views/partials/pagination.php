<?php
// Required variables: $page, $totalPages, $total, $perPage, $paginationParams (array)
if ($totalPages <= 1 && $total <= $perPage) return;

$from = $total > 0 ? min($total, ($page - 1) * $perPage + 1) : 0;
$to   = min($total, $page * $perPage);

function paginationUrl(int $p, array $params): string {
    $params['page'] = $p;
    return '?' . http_build_query(array_filter($params, fn($v) => $v !== ''));
}
?>
<div class="d-flex align-items-center justify-content-between mt-3 px-1 flex-wrap gap-2">
  <div style="font-size:13px;color:var(--text-secondary)">
    <?php if ($total === 0): ?>
      No records found
    <?php else: ?>
      Showing <strong><?= $from ?>–<?= $to ?></strong> of <strong><?= $total ?></strong> records
    <?php endif; ?>
  </div>
  <?php if ($totalPages > 1): ?>
  <nav aria-label="Pagination">
    <ul class="pagination pagination-sm mb-0">
      <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
        <a class="page-link" href="<?= htmlspecialchars(paginationUrl($page - 1, $paginationParams), ENT_QUOTES) ?>">&#8249;</a>
      </li>
      <?php
      $start = max(1, $page - 2);
      $end   = min($totalPages, $page + 2);
      ?>
      <?php if ($start > 1): ?>
        <li class="page-item"><a class="page-link" href="<?= htmlspecialchars(paginationUrl(1, $paginationParams), ENT_QUOTES) ?>">1</a></li>
        <?php if ($start > 2): ?><li class="page-item disabled"><span class="page-link">&hellip;</span></li><?php endif; ?>
      <?php endif; ?>
      <?php for ($i = $start; $i <= $end; $i++): ?>
        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
          <a class="page-link" href="<?= htmlspecialchars(paginationUrl($i, $paginationParams), ENT_QUOTES) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
      <?php if ($end < $totalPages): ?>
        <?php if ($end < $totalPages - 1): ?><li class="page-item disabled"><span class="page-link">&hellip;</span></li><?php endif; ?>
        <li class="page-item"><a class="page-link" href="<?= htmlspecialchars(paginationUrl($totalPages, $paginationParams), ENT_QUOTES) ?>"><?= $totalPages ?></a></li>
      <?php endif; ?>
      <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
        <a class="page-link" href="<?= htmlspecialchars(paginationUrl($page + 1, $paginationParams), ENT_QUOTES) ?>">&#8250;</a>
      </li>
    </ul>
  </nav>
  <?php endif; ?>
</div>

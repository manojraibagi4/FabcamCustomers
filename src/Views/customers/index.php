<div class="fab-page-header">
  <h1 class="fab-page-title">Customers</h1>
  <a href="<?= BASE_URL ?>/customers/add" class="btn btn-accent"><i class="bi bi-plus-lg me-1"></i>Add Customer</a>
</div>

<!-- Search bar -->
<form method="GET" action="<?= BASE_URL ?>/customers" class="fab-search-bar mb-3">
  <input type="text" name="search" class="form-control" placeholder="Search by name, ID or contact…"
         value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8') ?>">
  <button type="submit" class="btn btn-outline-secondary">Search</button>
  <?php if ($search): ?>
  <a href="<?= BASE_URL ?>/customers" class="btn btn-outline-secondary">Clear</a>
  <?php endif; ?>
</form>

<div class="fab-table-wrap">
  <?php if (empty($customers)): ?>
  <div class="px-4 py-4 text-muted-fab text-center">No customers found.</div>
  <?php else: ?>
  <div class="table-responsive">
    <table class="fab-table" id="customerTable">
      <thead>
        <tr>
          <th>Customer ID</th>
          <th>Company Name</th>
          <th>Contact</th>
          <th>Mobile</th>
          <th>Email</th>
          <th>GST Number</th>
          <th>Added</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($customers as $c): ?>
        <tr>
          <td><span class="badge badge-admin"><?= htmlspecialchars($c['customer_id'], ENT_QUOTES, 'UTF-8') ?></span></td>
          <td>
            <a href="<?= BASE_URL ?>/customers/view/<?= (int)$c['id'] ?>" class="fw-semibold">
              <?= htmlspecialchars($c['company_name'], ENT_QUOTES, 'UTF-8') ?>
            </a>
          </td>
          <td><?= htmlspecialchars($c['contact_person'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($c['mobile'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($c['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
          <td><code><?= htmlspecialchars($c['gst_number'] ?? '', ENT_QUOTES, 'UTF-8') ?></code></td>
          <td class="text-muted-fab" style="font-size:12px"><?= htmlspecialchars(substr($c['created_at'], 0, 10), ENT_QUOTES, 'UTF-8') ?></td>
          <td class="action-links">
            <a href="<?= BASE_URL ?>/customers/view/<?= (int)$c['id'] ?>" class="btn btn-sm btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
            <a href="<?= BASE_URL ?>/customers/edit/<?= (int)$c['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
            <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
            <form method="POST" action="<?= BASE_URL ?>/customers/delete/<?= (int)$c['id'] ?>" class="d-inline">
              <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"
                      data-confirm="Delete &quot;<?= htmlspecialchars($c['company_name'], ENT_QUOTES, 'UTF-8') ?>&quot; and ALL their licenses? This cannot be undone."><i class="bi bi-trash3"></i></button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

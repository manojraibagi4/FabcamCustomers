<div class="fab-page-header">
  <h1 class="fab-page-title">Users</h1>
  <a href="<?= BASE_URL ?>/users/add" class="btn btn-accent"><i class="bi bi-plus-lg me-1"></i>Add User</a>
</div>

<div class="fab-table-wrap">
  <div class="table-responsive">
    <table class="fab-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Last Login</th>
          <th>Created</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
          <td class="fw-semibold"><?= htmlspecialchars($u['name'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><span class="badge badge-<?= htmlspecialchars($u['role'], ENT_QUOTES, 'UTF-8') ?>"><?= ucfirst($u['role']) ?></span></td>
          <td>
            <?php if ($u['is_active']): ?>
              <span class="badge badge-active">Active</span>
            <?php else: ?>
              <span class="badge badge-revoked">Inactive</span>
            <?php endif; ?>
          </td>
          <td class="text-muted-fab" style="font-size:12px">
            <?= $u['last_login'] ? htmlspecialchars(substr($u['last_login'],0,16), ENT_QUOTES, 'UTF-8') : '—' ?>
          </td>
          <td class="text-muted-fab" style="font-size:12px">
            <?= htmlspecialchars(substr($u['created_at'],0,10), ENT_QUOTES, 'UTF-8') ?>
          </td>
          <td class="action-links">
            <a href="<?= BASE_URL ?>/users/edit/<?= (int)$u['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
            <?php if ($u['id'] !== ($_SESSION['user_id'] ?? 0)): ?>
            <form method="POST" action="<?= BASE_URL ?>/users/toggle/<?= (int)$u['id'] ?>" class="d-inline">
              <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
              <?php if ($u['is_active']): ?>
              <button type="submit" class="btn btn-sm btn-outline-warning" title="Deactivate"
                      data-confirm="Deactivate this user?"><i class="bi bi-slash-circle"></i></button>
              <?php else: ?>
              <button type="submit" class="btn btn-sm btn-outline-success" title="Activate"
                      data-confirm="Activate this user?"><i class="bi bi-check-circle"></i></button>
              <?php endif; ?>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

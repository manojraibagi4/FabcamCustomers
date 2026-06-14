<?php

class UserController extends Controller {

    public function index(): void {
        $this->requireRole('admin');
        $users = (new UserModel())->getAll();
        $this->render('users/index', ['pageTitle' => 'Users', 'users' => $users]);
    }

    public function create(): void {
        $this->requireRole('admin');
        $this->render('users/form', ['pageTitle'=>'Add User','user'=>[],'errors'=>[]]);
    }

    public function store(): void {
        $this->requireRole('admin');
        $this->validateCsrf();
        $d      = $this->sanitizePost();
        $errors = $this->validate($d, true);

        if ($errors) {
            $this->render('users/form', ['pageTitle'=>'Add User','user'=>$d,'errors'=>$errors]);
            return;
        }
        (new UserModel())->insert($d);
        $this->flash('success', 'User added.');
        $this->redirect('/users');
    }

    public function edit(string $id): void {
        $this->requireRole('admin');
        $user = (new UserModel())->findById((int)$id);
        if (!$user) { http_response_code(404); die('Not found'); }
        $this->render('users/form', ['pageTitle'=>'Edit User','user'=>$user,'errors'=>[]]);
    }

    public function update(string $id): void {
        $this->requireRole('admin');
        $this->validateCsrf();
        $d      = $this->sanitizePost();
        $errors = $this->validate($d, false, (int)$id);
        $model  = new UserModel();
        $user   = $model->findById((int)$id);
        if (!$user) { http_response_code(404); die('Not found'); }

        if ($errors) {
            $this->render('users/form', ['pageTitle'=>'Edit User','user'=>array_merge($user,$d),'errors'=>$errors]);
            return;
        }
        $model->update((int)$id, $d);
        $this->flash('success', 'User updated.');
        $this->redirect('/users');
    }

    public function toggleActive(string $id): void {
        $this->requireRole('admin');
        $this->validateCsrf();
        $model = new UserModel();
        $user  = $model->findById((int)$id);
        if ($user) {
            $model->update((int)$id, array_merge($user, ['is_active' => $user['is_active'] ? 0 : 1]));
        }
        $this->redirect('/users');
    }

    private function sanitizePost(): array {
        return [
            'name'      => trim($_POST['name']      ?? ''),
            'email'     => trim($_POST['email']     ?? ''),
            'role'      => $_POST['role']      ?? 'sales',
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'password'  => $_POST['password']  ?? '',
        ];
    }

    private function validate(array $d, bool $requirePassword = false, int $excludeId = 0): array {
        $errors = [];
        if (empty($d['name']))  $errors[] = 'Name is required.';
        if (empty($d['email'])) $errors[] = 'Email is required.';
        elseif (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
        elseif ((new UserModel())->emailExists($d['email'], $excludeId)) $errors[] = 'Email already in use.';
        if ($requirePassword && empty($d['password'])) $errors[] = 'Password is required.';
        if (!in_array($d['role'], ['admin','sales'])) $errors[] = 'Invalid role.';
        return $errors;
    }
}

<?php

class AuthController extends Controller {

    public function showLogin(): void {
        if (isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        $this->render('auth/login', ['pageTitle' => 'Login'], 'auth');
    }

    public function processLogin(): void {
        $this->validateCsrf();

        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');
        $errors   = [];

        if (!$email || !$password) {
            $errors[] = 'Email and password are required.';
        }

        if (!$errors) {
            $model = new UserModel();
            $user  = $model->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user']      = [
                    'id'   => $user['id'],
                    'name' => $user['name'],
                    'role' => $user['role'],
                ];
                $model->updateLastLogin($user['id']);
                $this->redirect('/dashboard');
            } else {
                $errors[] = 'Invalid email or password.';
            }
        }

        $this->render('auth/login', [
            'pageTitle' => 'Login',
            'errors'    => $errors,
            'email'     => $email,
        ], 'auth');
    }

    public function logout(): void {
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}

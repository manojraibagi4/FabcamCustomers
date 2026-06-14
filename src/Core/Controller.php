<?php

class Controller {

    protected function render(string $view, array $data = [], string $layout = 'app'): void {
        extract($data, EXTR_SKIP);
        $viewPath   = __DIR__ . '/../Views/' . $view . '.php';
        $layoutPath = __DIR__ . '/../Views/layouts/' . $layout . '.php';
        require $layoutPath;
    }

    protected function redirect(string $path): void {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    protected function flash(string $type, string $message): void {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    protected function requireAuth(): void {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    protected function requireRole(string $role): void {
        $this->requireAuth();
        if (($_SESSION['user_role'] ?? '') !== $role) {
            http_response_code(403);
            $viewPath   = __DIR__ . '/../Views/errors/403.php';
            $layoutPath = __DIR__ . '/../Views/layouts/app.php';
            $pageTitle  = 'Access Denied';
            require $layoutPath;
            exit;
        }
    }

    protected function validateCsrf(): void {
        $token = $_POST['_csrf'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            // Regenerate so the next attempt gets a fresh token
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->flash('danger', 'Your session expired — please try again.');
            $referer = $_SERVER['HTTP_REFERER'] ?? '';
            $path    = '/login';
            if ($referer && str_starts_with($referer, BASE_URL)) {
                $path = parse_url($referer, PHP_URL_PATH) ?: '/login';
            }
            $this->redirect($path);
        }
    }

    protected function currentUser(): array {
        return $_SESSION['user'] ?? [];
    }

    protected function isAdmin(): bool {
        return ($_SESSION['user_role'] ?? '') === 'admin';
    }

    protected function e(string $value): string {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

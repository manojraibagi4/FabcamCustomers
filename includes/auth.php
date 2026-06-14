<?php

define('SESSION_TIMEOUT', 1800); // 30 minutes of inactivity

function startSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => false,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }

    // Expire idle authenticated sessions
    if (!empty($_SESSION['user_id'])) {
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
            session_unset();
            session_destroy();
            header('Location: ' . BASE_URL . '/login?timeout=1');
            exit;
        }
        $_SESSION['last_activity'] = time();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}

function currentUser(): array {
    return $_SESSION['user'] ?? [];
}

function requireAuth(): void {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}

function csrfToken(): string {
    return $_SESSION['csrf_token'] ?? '';
}

function verifyCsrf(string $token): bool {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

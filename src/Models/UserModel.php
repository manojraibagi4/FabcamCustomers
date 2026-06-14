<?php

class UserModel extends BaseModel {

    public function findByEmail(string $email): array|false {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ? AND is_active = 1 LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById(int $id): array|false {
        $stmt = $this->pdo->prepare('SELECT id, name, email, role, is_active, created_at, last_login FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll(): array {
        return $this->pdo->query('SELECT id, name, email, role, is_active, created_at, last_login FROM users ORDER BY name')->fetchAll();
    }

    public function insert(array $d): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (name, email, password_hash, role, is_active) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $d['name'],
            $d['email'],
            password_hash($d['password'], PASSWORD_BCRYPT),
            $d['role'],
            $d['is_active'] ?? 1,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $d): bool {
        $fields = ['name = ?', 'email = ?', 'role = ?', 'is_active = ?'];
        $vals   = [$d['name'], $d['email'], $d['role'], $d['is_active']];

        if (!empty($d['password'])) {
            $fields[] = 'password_hash = ?';
            $vals[]   = password_hash($d['password'], PASSWORD_BCRYPT);
        }

        $vals[] = $id;
        $sql    = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?';
        return $this->pdo->prepare($sql)->execute($vals);
    }

    public function updateLastLogin(int $id): void {
        $this->pdo->prepare('UPDATE users SET last_login = NOW() WHERE id = ?')->execute([$id]);
    }

    public function emailExists(string $email, int $excludeId = 0): bool {
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
        $stmt->execute([$email, $excludeId]);
        return (bool) $stmt->fetch();
    }
}

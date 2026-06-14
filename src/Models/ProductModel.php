<?php

class ProductModel extends BaseModel {

    public function getAll(): array {
        return $this->pdo->query('SELECT * FROM products ORDER BY product_name')->fetchAll();
    }

    public function findById(int $id): array|false {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function insert(array $d): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO products (product_name, module, description) VALUES (?, ?, ?)'
        );
        $stmt->execute([$d['product_name'], $d['module'] ?? '', $d['description'] ?? '']);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $d): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE products SET product_name = ?, module = ?, description = ? WHERE id = ?'
        );
        return $stmt->execute([$d['product_name'], $d['module'] ?? '', $d['description'] ?? '', $id]);
    }

    public function delete(int $id): bool {
        return $this->pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
    }

    public function findByName(string $name): array|false {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE product_name = ? LIMIT 1');
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    public function nameExists(string $name, int $excludeId = 0): bool {
        $stmt = $this->pdo->prepare('SELECT id FROM products WHERE product_name = ? AND id != ?');
        $stmt->execute([$name, $excludeId]);
        return (bool) $stmt->fetch();
    }
}

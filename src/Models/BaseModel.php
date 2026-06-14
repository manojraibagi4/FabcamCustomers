<?php

class BaseModel {
    protected PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    protected function paginate(string $sql, array $params, int $page, int $perPage): array {
        $countSql = 'SELECT COUNT(*) FROM (' . $sql . ') AS _count_query';
        $stmt = $this->pdo->prepare($countSql);
        $stmt->execute($params);
        $total = (int) $stmt->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare($sql . ' LIMIT ? OFFSET ?');
        // bind original params first
        $i = 1;
        foreach ($params as $val) {
            $stmt->bindValue($i++, $val);
        }
        $stmt->bindValue($i++, $perPage, PDO::PARAM_INT);
        $stmt->bindValue($i,   $offset,  PDO::PARAM_INT);
        $stmt->execute();

        return [
            'rows'       => $stmt->fetchAll(),
            'total'      => $total,
            'page'       => $page,
            'per_page'   => $perPage,
            'last_page'  => (int) ceil($total / $perPage),
        ];
    }

    protected function e(mixed $val): string {
        return htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8');
    }
}

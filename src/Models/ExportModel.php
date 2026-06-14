<?php

class ExportModel extends BaseModel {

    public function getLicenseExportData(array $filters = []): array {
        $where  = [];
        $params = [];

        $allowed_status = ['active', 'expired', 'grace', 'revoked'];
        if (!empty($filters['status']) && in_array($filters['status'], $allowed_status)) {
            $where[]  = 'l.license_status = ?';
            $params[] = $filters['status'];
        }

        $allowed_amc = ['active', 'expired', 'not_applicable'];
        if (!empty($filters['amc_status']) && in_array($filters['amc_status'], $allowed_amc)) {
            $where[]  = 'l.amc_status = ?';
            $params[] = $filters['amc_status'];
        }

        if (!empty($filters['product_id'])) {
            $where[]  = 'l.product_id = ?';
            $params[] = (int) $filters['product_id'];
        }

        if (!empty($filters['customer_id'])) {
            $where[]  = 'l.customer_id = ?';
            $params[] = (int) $filters['customer_id'];
        }

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $sql = "SELECT
                    c.customer_id    AS cust_code,
                    c.company_name,
                    c.contact_person,
                    c.mobile,
                    c.email          AS cust_email,
                    c.gst_number,
                    c.address,
                    p.product_name,
                    l.license_type,
                    l.machine_name,
                    l.server_code,
                    l.lock_code,
                    l.purchase_date,
                    l.expiry_date,
                    DATEDIFF(l.expiry_date, CURDATE()) AS days_left,
                    l.license_status,
                    l.purchase_price,
                    l.amc_cost,
                    l.renewal_date,
                    l.amc_status,
                    l.remarks
                FROM licenses l
                JOIN customers c ON c.id = l.customer_id
                JOIN products  p ON p.id = l.product_id
                $whereSql
                ORDER BY c.company_name ASC, l.expiry_date ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}

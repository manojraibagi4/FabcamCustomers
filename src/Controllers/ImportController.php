<?php

class ImportController extends Controller {

    public function index(): void {
        $this->requireAuth();
        $this->requireRole('admin');
        $this->render('import/index', ['pageTitle' => 'Import', 'result' => null]);
    }

    public function process(): void {
        $this->requireAuth();
        $this->requireRole('admin');
        $this->validateCsrf();

        // --- Validate upload ---
        $upload = $_FILES['import_file'] ?? null;
        if (!$upload || $upload['error'] !== UPLOAD_ERR_OK || empty($upload['tmp_name'])) {
            $this->flash('danger', 'Upload failed or no file selected.');
            $this->redirect('/import');
            return;
        }

        $ext = strtolower(pathinfo($upload['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['xlsx', 'xls'])) {
            $this->flash('danger', 'Only .xlsx and .xls files are supported.');
            $this->redirect('/import');
            return;
        }

        // --- Load spreadsheet ---
        try {
            $reader = $ext === 'xls'
                ? new \PhpOffice\PhpSpreadsheet\Reader\Xls()
                : new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $reader->setReadDataOnly(false);
            $spreadsheet = $reader->load($upload['tmp_name']);
            $sheet       = $spreadsheet->getActiveSheet();
        } catch (\Exception $e) {
            $this->flash('danger', 'Could not read file: ' . $e->getMessage());
            $this->redirect('/import');
            return;
        }

        // toArray: (nullValue, calculateFormulas, formatData, returnCellRef)
        $allRows = $sheet->toArray(null, false, true, false);

        if (count($allRows) < 2) {
            $this->flash('danger', 'File has no data rows.');
            $this->redirect('/import');
            return;
        }

        // --- Build header → column index map ---
        $headerRow = array_map('trim', array_map('strval', $allRows[0]));
        $colMap    = array_flip($headerRow);

        $required = ['Company Name', 'Expiry Date', 'Product'];
        foreach ($required as $h) {
            if (!isset($colMap[$h])) {
                $this->flash('danger', "Missing required column: \"$h\". Make sure the file matches the export format.");
                $this->redirect('/import');
                return;
            }
        }

        // --- Process rows ---
        $pdo      = Database::getInstance();
        $custModel = new CustomerModel();
        $prodModel = new ProductModel();
        $userId   = (int) ($_SESSION['user_id'] ?? 0);

        $result = [
            'customers_created' => 0,
            'customers_updated' => 0,
            'licenses_inserted' => 0,
            'licenses_updated'  => 0,
            'rows_skipped'      => 0,
            'errors'            => [],
        ];

        for ($r = 1; $r < count($allRows); $r++) {
            $row    = $allRows[$r];
            $rowNum = $r + 1; // 1-based for display

            $get = function (string $col) use ($row, $colMap): string {
                return isset($colMap[$col]) ? trim((string) ($row[$colMap[$col]] ?? '')) : '';
            };

            // Skip blank rows
            $companyName = $get('Company Name');
            $expiryDate  = $this->parseDate($get('Expiry Date'));
            if ($companyName === '' && $expiryDate === '') {
                continue;
            }

            // Validate required
            if ($companyName === '') {
                $result['errors'][] = "Row $rowNum: Company Name is required — skipped.";
                $result['rows_skipped']++;
                continue;
            }
            if ($expiryDate === '') {
                $result['errors'][] = "Row $rowNum: Expiry Date is missing or invalid — skipped.";
                $result['rows_skipped']++;
                continue;
            }

            // --- Customer ---
            $custCode = $get('Customer ID');
            $custData = [
                'company_name'   => $companyName,
                'contact_person' => $get('Contact Person'),
                'mobile'         => $get('Mobile'),
                'email'          => $get('Email'),
                'gst_number'     => $get('GST Number'),
                'address'        => $get('Address'),
            ];

            if ($custCode !== '') {
                $existing = $custModel->findByCode($custCode);
                if ($existing) {
                    $custModel->update($existing['id'], $custData);
                    $customerId = $existing['id'];
                    $result['customers_updated']++;
                } else {
                    $customerId = $custModel->insertWithCode($custCode, $custData, $userId);
                    $result['customers_created']++;
                }
            } else {
                $customerId = $custModel->insert($custData, $userId);
                $result['customers_created']++;
            }

            // --- Product ---
            $productName = $get('Product');
            if ($productName === '') {
                $result['errors'][] = "Row $rowNum: Product name is empty — license skipped.";
                $result['rows_skipped']++;
                continue;
            }
            $product = $prodModel->findByName($productName);
            if (!$product) {
                $result['errors'][] = "Row $rowNum: Product \"$productName\" not found — license skipped.";
                $result['rows_skipped']++;
                continue;
            }
            $productId = (int) $product['id'];

            // --- License ---
            $licenseType   = $this->normalizeLicenseType($get('License Type'));
            $licenseStatus = $this->normalizeLicenseStatus($get('License Status'));
            $amcStatus     = $this->normalizeAmcStatus($get('AMC Status'));
            $serverCode    = $get('Server Code');
            $lockCode      = $get('Lock Code');
            $machineName   = $get('Machine Name');
            $purchaseDate  = $this->parseDate($get('Purchase Date'));
            $renewalDate   = $this->parseDate($get('Renewal Date'));
            $purchasePrice = $this->parseFloat($get('Purchase Price (INR)'));
            $amcCost       = $this->parseFloat($get('AMC Cost (INR)'));
            $remarks       = $get('Remarks');

            // Check for existing license by customer + server_code (if server_code provided)
            $existingLicenseId = null;
            if ($serverCode !== '') {
                $stmt = $pdo->prepare(
                    'SELECT id FROM licenses WHERE customer_id = ? AND server_code = ? LIMIT 1'
                );
                $stmt->execute([$customerId, $serverCode]);
                $existingLicenseId = $stmt->fetchColumn() ?: null;
            }

            if ($existingLicenseId) {
                $pdo->prepare(
                    'UPDATE licenses SET
                        product_id=?, license_type=?, machine_name=?, lock_code=?,
                        purchase_date=?, expiry_date=?, license_status=?,
                        purchase_price=?, amc_cost=?, renewal_date=?, amc_status=?,
                        remarks=?, updated_by=?
                     WHERE id=?'
                )->execute([
                    $productId, $licenseType, $machineName, $lockCode,
                    $purchaseDate ?: null, $expiryDate, $licenseStatus,
                    $purchasePrice, $amcCost, $renewalDate ?: null, $amcStatus,
                    $remarks, $userId,
                    $existingLicenseId,
                ]);
                $result['licenses_updated']++;
            } else {
                $pdo->prepare(
                    'INSERT INTO licenses
                        (customer_id, product_id, license_type, machine_name, server_code, lock_code,
                         purchase_date, expiry_date, license_status,
                         purchase_price, amc_cost, renewal_date, amc_status,
                         remarks, updated_by)
                     VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
                )->execute([
                    $customerId, $productId, $licenseType, $machineName, $serverCode, $lockCode,
                    $purchaseDate ?: null, $expiryDate, $licenseStatus,
                    $purchasePrice, $amcCost, $renewalDate ?: null, $amcStatus,
                    $remarks, $userId,
                ]);
                $result['licenses_inserted']++;
            }
        }

        $this->render('import/index', [
            'pageTitle' => 'Import',
            'result'    => $result,
        ]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function parseDate(mixed $val): string {
        if ($val === null || $val === '') return '';
        // Excel stores dates as floats (serial numbers)
        if (is_numeric($val) && (float)$val > 1) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$val)
                    ->format('Y-m-d');
            } catch (\Exception $e) {
                return '';
            }
        }
        $ts = strtotime((string)$val);
        return $ts ? date('Y-m-d', $ts) : '';
    }

    private function normalizeLicenseType(string $val): string {
        $v = strtolower(trim($val));
        return in_array($v, ['single', 'multi', 'server', 'cloud']) ? $v : 'single';
    }

    private function normalizeLicenseStatus(string $val): string {
        $v = strtolower(trim($val));
        return in_array($v, ['active', 'expired', 'grace', 'revoked']) ? $v : 'active';
    }

    private function normalizeAmcStatus(string $val): string {
        $v = strtolower(trim($val));
        if ($v === 'not applicable') return 'not_applicable';
        return in_array($v, ['active', 'expired', 'not_applicable']) ? $v : 'not_applicable';
    }

    private function parseFloat(string $val): ?float {
        $v = trim($val);
        if ($v === '') return null;
        $v = preg_replace('/[^\d.\-]/', '', $v);
        return is_numeric($v) ? (float)$v : null;
    }
}

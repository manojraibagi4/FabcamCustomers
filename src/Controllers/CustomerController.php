<?php

class CustomerController extends Controller {

    public function index(): void {
        $this->requireAuth();
        $search    = trim($_GET['search'] ?? '');
        $model     = new CustomerModel();
        $customers = $model->getAll($search);
        $this->render('customers/index', [
            'pageTitle' => 'Customers',
            'customers' => $customers,
            'search'    => $search,
        ]);
    }

    public function create(): void {
        $this->requireAuth();
        $this->render('customers/form', [
            'pageTitle' => 'Add Customer',
            'customer'  => [],
            'errors'    => [],
        ]);
    }

    public function store(): void {
        $this->requireAuth();
        $this->validateCsrf();

        $d      = $this->sanitizePost();
        $errors = $this->validate($d);

        if ($errors) {
            $this->render('customers/form', [
                'pageTitle' => 'Add Customer',
                'customer'  => $d,
                'errors'    => $errors,
            ]);
            return;
        }

        $model = new CustomerModel();
        $id    = $model->insert($d, $_SESSION['user_id']);
        $this->flash('success', 'Customer added successfully.');
        $this->redirect('/customers/view/' . $id);
    }

    public function edit(string $id): void {
        $this->requireAuth();
        $model    = new CustomerModel();
        $customer = $model->findById((int)$id);
        if (!$customer) { $this->notFound(); return; }

        $this->render('customers/form', [
            'pageTitle' => 'Edit Customer',
            'customer'  => $customer,
            'errors'    => [],
        ]);
    }

    public function update(string $id): void {
        $this->requireAuth();
        $this->validateCsrf();

        $d      = $this->sanitizePost();
        $errors = $this->validate($d);

        $model    = new CustomerModel();
        $customer = $model->findById((int)$id);
        if (!$customer) { $this->notFound(); return; }

        if ($errors) {
            $this->render('customers/form', [
                'pageTitle' => 'Edit Customer',
                'customer'  => array_merge($customer, $d),
                'errors'    => $errors,
            ]);
            return;
        }

        $model->update((int)$id, $d);
        $this->flash('success', 'Customer updated successfully.');
        $this->redirect('/customers/view/' . $id);
    }

    public function view(string $id): void {
        $this->requireAuth();
        $model    = new CustomerModel();
        $customer = $model->findById((int)$id);
        if (!$customer) { $this->notFound(); return; }

        $licModel = new LicenseModel();
        $licenses = $licModel->getAll(['customer_id' => (int)$id]);

        $this->render('customers/view', [
            'pageTitle' => $customer['company_name'],
            'customer'  => $customer,
            'licenses'  => $licenses,
        ]);
    }

    public function delete(string $id): void {
        $this->requireAuth();
        $this->requireRole('admin');
        $this->validateCsrf();

        $model    = new CustomerModel();
        $customer = $model->findById((int)$id);
        if (!$customer) {
            $this->flash('danger', 'Customer not found.');
            $this->redirect('/customers');
            return;
        }

        // FK is RESTRICT — delete child licenses first, then the customer
        (new LicenseModel())->deleteByCustomer((int)$id);
        $model->delete((int)$id);
        $this->flash('success', 'Customer and all associated licenses deleted.');
        $this->redirect('/customers');
    }

    private function sanitizePost(): array {
        return [
            'company_name'   => trim($_POST['company_name']   ?? ''),
            'contact_person' => trim($_POST['contact_person'] ?? ''),
            'mobile'         => trim($_POST['mobile']         ?? ''),
            'email'          => trim($_POST['email']          ?? ''),
            'gst_number'     => trim($_POST['gst_number']     ?? ''),
            'address'        => trim($_POST['address']        ?? ''),
        ];
    }

    private function validate(array $d): array {
        $errors = [];
        if (empty($d['company_name'])) $errors[] = 'Company name is required.';
        if (!empty($d['email']) && !filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address.';
        }
        return $errors;
    }

    private function notFound(): void {
        http_response_code(404);
        $this->render('errors/404', ['pageTitle' => 'Not Found']);
    }
}

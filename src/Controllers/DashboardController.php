<?php

class DashboardController extends Controller {

    public function index(): void {
        $this->requireAuth();
        $licenseModel = new LicenseModel();
        $stats        = $licenseModel->getStatCounts();
        $expiring     = $licenseModel->getExpiringSoon(30);

        $this->render('dashboard/index', [
            'pageTitle' => 'Dashboard',
            'stats'     => $stats,
            'expiring'  => $expiring,
        ]);
    }
}

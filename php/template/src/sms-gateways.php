<?php ob_start();?>

    <!-- ========================
        Start Page Content
    ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Settings</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Settings</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
                </div>
            </div>                
            <!-- End Page Header -->

            <div class="card border-0">
                <div class="card-body pb-0 pt-0 px-2">
                    <ul class="nav nav-tabs nav-bordered nav-bordered-primary">
                        <li class="nav-item me-3">
                            <a href="profile-settings.php" class="nav-link p-2">
                                <i class="ti ti-settings-cog me-2"></i>General Settings
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a href="company-settings.php" class="nav-link p-2">
                                <i class="ti ti-world-cog me-2"></i>Website Settings
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a href="invoice-settings.php" class="nav-link p-2">
                                <i class="ti ti-apps me-2"></i>App Settings
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a href="email-settings.php" class="nav-link p-2 active">
                                <i class="ti ti-device-laptop me-2"></i>System Settings
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a href="payment-gateways.php" class="nav-link p-2">
                                <i class="ti ti-moneybag me-2"></i>Financial Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="sitemap.php" class="nav-link p-2">
                                <i class="ti ti-flag-cog me-2"></i>Other Settings
                            </a>
                        </li>
                    </ul>
                </div> <!-- end card body -->
            </div> <!-- end card -->

            <!-- start row -->
            <div class="row">
                <div class="col-xl-3 col-lg-12 theiaStickySidebar">

                    <div class="card mb-3 mb-xl-0">
                        <div class="card-body">
                            <div class="settings-sidebar">
                                <h5 class="mb-3 fs-17">System Settings</h5>
                                <div class="list-group list-group-flush settings-sidebar">
                                    <a href="email-settings.php" class="d-block p-2 fw-medium">Email Settings</a>
                                    <a href="sms-gateways.php" class="d-block p-2 fw-medium active">SMS Gateways</a>
                                    <a href="gdpr-cookies.php" class="d-block p-2 fw-medium">GDPR Cookies</a>
                                </div>
                            </div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->

                </div> <!-- end col -->

                <div class="col-xl-9 col-lg-12">

                    <div class="card mb-0">
                        <div class="card-body pb-0">
                            <div class="border-bottom mb-3 pb-3">
                                <h5 class="mb-0 fs-17">SMS Gateways</h5>
                            </div>
                                    <div class="row">

                                        <!-- Gateway Wrap -->
                                        <div class="col-xxl-4 col-sm-6">
                                            <div
                                                class="border rounded d-flex align-items-center justify-content-between p-3 mb-3 shadow">
                                                <div>
                                                    <img src="assets/img/icons/gateway-01.svg" alt="Img">
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0);" data-bs-toggle="modal"
                                                        data-bs-target="#add_nexmo"><i
                                                            class="ti ti-settings fs-24"></i></a>
                                                    <div class="form-check form-switch ps-2">
                                                        <input class="form-check-input ms-0 mt-0" type="checkbox" role="switch" checked>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Gateway Wrap -->

                                        <!-- Gateway Wrap -->
                                        <div class="col-xxl-4 col-sm-6">
                                            <div
                                                class="border rounded d-flex align-items-center justify-content-between p-3 mb-3 shadow">
                                                <div>
                                                    <img src="assets/img/icons/gateway-02.svg" alt="Img">
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0);" data-bs-toggle="modal"
                                                        data-bs-target="#add_factor"><i
                                                            class="ti ti-settings fs-24"></i></a>
                                                    <div class="form-check form-switch ps-2">
                                                        <input class="form-check-input ms-0 mt-0" type="checkbox" role="switch" checked>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Gateway Wrap -->

                                        <!-- Gateway Wrap -->
                                        <div class="col-xxl-4 col-sm-6">
                                            <div
                                                class="border rounded d-flex align-items-center justify-content-between p-3 mb-3 shadow">
                                                <div>
                                                    <img src="assets/img/icons/gateway-03.svg" alt="Img">
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0);" data-bs-toggle="modal"
                                                        data-bs-target="#add_twilio"><i
                                                            class="ti ti-settings fs-24"></i></a>
                                                    <div class="form-check form-switch ps-2">
                                                        <input class="form-check-input ms-0 mt-0" type="checkbox" role="switch" checked>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Gateway Wrap -->

                                    </div>
                                </div>
                            </div>
                            <!-- /Settings Info -->

                        </div>
                    </div>

        </div>
        <!-- End Content --> 

        <?php require_once '../partials/footer.php'; ?>

    </div>

    <!-- ========================
        End Page Content
    ========================= -->

<?php
$content = ob_get_clean();

require_once '../partials/main.php'; ?>   
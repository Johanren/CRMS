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
                            <a href="company-settings.php" class="nav-link p-2 active">
                                <i class="ti ti-world-cog me-2"></i>Website Settings
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a href="invoice-settings.php" class="nav-link p-2">
                                <i class="ti ti-apps me-2"></i>App Settings
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a href="email-settings.php" class="nav-link p-2">
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

                    <div class="card">
                        <div class="card-body">
                            <div class="settings-sidebar">
                                <h5 class="mb-3 fs-17">Website Settings</h5>
                                <div class="list-group list-group-flush settings-sidebar">
                                    <a href="company-settings.php" class="d-block p-2 fw-medium">Company Settings</a>
                                    <a href="localization-settings.php" class="d-block p-2 fw-medium">Localization</a>
                                    <a href="prefixes-settings.php" class="d-block p-2 fw-medium">Prefixes</a>
                                    <a href="preference-settings.php" class="d-block p-2 fw-medium">Preference</a>
                                    <a href="appearance-settings.php" class="d-block p-2 fw-medium">Appearance</a>
                                    <a href="language-settings.php" class="d-block p-2 fw-medium active">Language</a>
                                </div>
                            </div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->

                </div> <!-- end col -->

                <div class="col-xl-9 col-lg-12">

                    <!-- Custom Fields -->
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="row border-bottom mb-3 pb-3 align-items-center row-gap-3">
                                <div class="col-md-3">
                                    <h4 class="fs-17 mb-0">Language</h4>
                                </div>
                                <div class="col-md-9">
                                    <div class="d-flex align-items-center justify-content-md-end flex-wrap gap-2">
                                        <a href="language-settings.php" class="btn btn-primary d-flex align-items-center"><i class="ti ti-circle-arrow-left me-1"></i>Back to Translations</a>
                                        <div class="dropdown">
                                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow" data-bs-toggle="dropdown"><img src="assets/img/flags/us.svg" alt="Img" class="me-2" height="16">English</a>
                                            <div class="dropdown-menu  dropdown-menu-end">
                                                <ul>
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center gap-2"><img src="assets/img/flags/us.svg" alt="Img" height="16">English</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center gap-2"><img src="assets/img/flags/de.svg" alt="Img" height="16">German</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center gap-2"><img src="assets/img/flags/ae.svg" alt="Img" height="16">Arabic</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center gap-2"><img src="assets/img/flags/fr.svg" alt="Img" height="16">French</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="w-lg-25 w-md-25 w-100">
                                            <p class="fs-14 text-dark mb-1">Progress</p>
                                            <div class="d-flex align-items-center">
                                                <div class="progress w-100 bg-light" style="height: 5px; border-radius: 10px;">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 80%; border-radius: 10px;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="ms-2">80%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Start Table -->
                            <div class="table-responsive table-nowrap custom-table">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="no-sort">English</th>
                                            <th class="no-sort">Arabic</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                Name
                                            </td>
                                            <td>
                                                <div class="py-1 px-2 text-end bg-light border rounded text-dark">
                                                    اسم
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Phone
                                            </td>
                                            <td>
                                                <div class="py-1 px-2 text-end bg-light border rounded text-dark">
                                                    الفواتير المتكررة
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Email
                                            </td>
                                            <td>
                                                <div class="py-1 px-2 text-end bg-light border rounded text-dark">
                                                    بريد إلكتروني
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Tags
                                            </td>
                                            <td>
                                                <div class="py-1 px-2 text-end bg-light border rounded text-dark">
                                                    العلامات
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Location
                                            </td>
                                            <td>
                                                <div class="py-1 px-2 text-end bg-light border rounded text-dark">
                                                    موقع
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Rating
                                            </td>
                                            <td>
                                                <div class="py-1 px-2 text-end bg-light border rounded text-dark">
                                                    تصنيف
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Owner
                                            </td>
                                            <td>
                                                <div class="py-1 px-2 text-end bg-light border rounded text-dark">
                                                    مالك
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- End Table -->

                        </div>
                    </div>
                    <!-- /Custom Fields -->

                </div> <!-- end col -->    

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
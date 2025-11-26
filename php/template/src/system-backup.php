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

            <!-- Settings Menu -->
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
                            <a href="sitemap.php" class="nav-link p-2 active">
                                <i class="ti ti-flag-cog me-2"></i>Other Settings
                            </a>
                        </li>
                    </ul>
                </div> <!-- end card body -->
            </div> <!-- end card -->
            <!-- /Settings Menu -->

            <!-- start row -->
            <div class="row row-gap-3">
                <div class="col-xl-3 col-lg-12 theiaStickySidebar">

                    <!-- Settings Sidebar -->
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="settings-sidebar">
                                <h4 class="fs-17 mb-3">Other Settings</h4>
                                <div class="list-group list-group-flush settings-sidebar">
                                    <a href="sitemap.php" class="d-block p-2 fw-medium">Sitemap</a>
                                    <a href="clear-cache.php" class="d-block p-2 fw-medium">Clear Cache </a>
                                    <a href="storage.php" class="d-block p-2 fw-medium">Storage</a>
                                    <a href="cronjob.php" class="d-block p-2 fw-medium">Cronjob</a>
                                    <a href="ban-ip-address.php" class="d-block p-2 fw-medium">Ban IP Address</a>
                                    <a href="system-backup.php" class="d-block p-2 fw-medium active">System Backup</a>
                                    <a href="database-backup.php" class="d-block p-2 fw-medium">Database Backup</a>
                                    <a href="system-update.php" class="d-block p-2 fw-medium">System Update</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Settings Sidebar -->

                </div>

                <div class="col-xl-9 col-lg-12">

                    <!-- Settings Info -->
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="border-bottom mb-3 pb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <h4 class="fs-17 mb-0">System Backup</h4>
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#generate_backup"><i class="ti ti-folder-open me-1"></i>Generate Backup</a>
                            </div>

                            <!-- start table -->
                            <div class="table-responsive">
                                <table class="table table-nowrap">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Created On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>crm_customers_backup_2025-05-26</td>
                                            <td>22 Feb 2025</td>
                                            <td>
                                                <div class="dropdown table-action">
                                                    <a href="#" class="action-icon btn btn-xs shadow d-inline-flex btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#delete_backup"><i class="ti ti-trash me-1"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>crms_reports_export_2025-05-26</td>
                                            <td>10 Feb 2025</td>
                                            <td>
                                                <div class="dropdown table-action">
                                                    <a href="#" class="action-icon btn btn-xs shadow d-inline-flex btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#delete_backup"><i class="ti ti-trash me-1"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>crms_projects_export_2025-05-26</td>
                                            <td>17 Jan 2025</td>
                                            <td>
                                                <div class="dropdown table-action">
                                                    <a href="#" class="action-icon btn btn-xs shadow d-inline-flex btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#delete_backup"><i class="ti ti-trash me-1"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>crms_companies_export_2025-05-26</td>
                                            <td>07 Jan 2025</td>
                                            <td>
                                                <div class="dropdown table-action">
                                                    <a href="#" class="action-icon btn btn-xs shadow d-inline-flex btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#delete_backup"><i class="ti ti-trash me-1"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table -->

                        </div>
                    </div>
                    <!-- /Settings Info -->

                </div>
            </div>
            <!-- end row -->

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
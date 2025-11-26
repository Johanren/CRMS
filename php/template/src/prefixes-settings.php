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
                                    <a href="prefixes-settings.php" class="d-block p-2 fw-medium active">Prefixes</a>
                                    <a href="preference-settings.php" class="d-block p-2 fw-medium">Preference</a>
                                    <a href="appearance-settings.php" class="d-block p-2 fw-medium">Appearance</a>
                                    <a href="language-settings.php" class="d-block p-2 fw-medium">Language</a>
                                </div>
                            </div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->

                </div> <!-- end col -->

                <div class="col-xl-9 col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="border-bottom mb-3 pb-3">
                                <h5 class="mb-0 fs-17">Prefixes</h5>
                            </div>
                            <form action="prefixes-settings.php">
                                <div class="border-bottom mb-3">

                                    <!-- start row -->
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Products</label>
                                                <input type="text" class="form-control" value="SKU - ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Supplier</label>
                                                <input type="text" class="form-control" value="SUP - ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Purchase</label>
                                                <input type="text" class="form-control" value="PU - ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Purchase Return</label>
                                                <input type="text" class="form-control" value="PR - ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Sales</label>
                                                <input type="text" class="form-control" value="SA - ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Sales Return</label>
                                                <input type="text" class="form-control" value="SR -  ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Customer</label>
                                                <input type="text" class="form-control" value="CT - ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Expense</label>
                                                <input type="text" class="form-control" value="EX - ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Stock Transfer</label>
                                                <input type="text" class="form-control" value="ST -  ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Stock Adjustment</label>
                                                <input type="text" class="form-control" value="SA -  ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Sales Order</label>
                                                <input type="text" class="form-control" value="SO - ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Invoice</label>
                                                <input type="text" class="form-control" value="INV -  ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Estimation</label>
                                                <input type="text" class="form-control" value="EST - ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Transaction</label>
                                                <input type="text" class="form-control" value="TRN - ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Employee</label>
                                                <input type="text" class="form-control" value="EMP -  ">
                                            </div>
                                        </div> <!-- end col -->

                                        <div class="col-md-3 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Purchase Return</label>
                                                <input type="text" class="form-control" value="PR -  ">
                                            </div>
                                        </div> <!-- end col -->

                                    </div>
                                    <!-- end row -->

                                </div>
                                <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
                                    <a href="#" class="btn btn-sm btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->

                </div> <!-- end col -->

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
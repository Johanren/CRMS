<?php ob_start(); ?>

<!-- ========================
        Start Page Content
    ========================= -->

<div class="page-wrapper">

    <!-- Start Content -->
    <div class="content pb-0">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Dashboard</h4>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                <div id="reportrange" class="reportrange-picker d-flex align-items-center shadow">
                    <i class="ti ti-calendar-due text-dark fs-14 me-1"></i><span class="reportrange-picker-field">9 Jun 25 - 9 Jun 25</span>
                </div>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Start Welcome Wrap -->
        <div class="welcome-wrap mb-4">
            <div class=" d-flex align-items-center justify-content-between flex-wrap gap-3 bg-dark rounded p-4">
                <div>
                    <h2 class="mb-1 text-white fs-24">Bienvenido, <?php session_start(); echo $_SESSION['user'] ?></h2>
                    <p class="text-light fs-14 mb-0">14 nuevos leads han ingresado hoy !!!</p>
                </div>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <a href="leads.php" class="btn btn-danger btn-sm">Leads</a>
                    <a href="venta.php" class="btn btn-light btn-sm">Foco</a>
                </div>
            </div>
        </div>
        <!-- Endc Welcome Wrap -->

        <!-- start row -->
        <div class="row row-gap-3 mb-4">
            <!-- Total Companies -->
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill mb-0 position-relative overflow-hidden">
                    <div class="card-body position-relative z-1">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <p class="fs-14 mb-1">Total Companies</p>
                                    <h2 class="mb-1 fs-16">5468</h2>
                                    <p class="text-success mb-0 fs-13"> <i class="ti ti-arrow-bar-up me-1"></i>5.62%<span class="text-body ms-1">from last month</span></p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="avatar avatar-md rounded-circle bg-soft-primary border border-primary">
                                    <i class="ti ti-building fs-16 text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <img src="assets/img/icons/elemnt-01.svg" alt="elemnt-04" class="img-fluid position-absolute top-0 Start-0">
                </div>
            </div>
            <!-- /Total Companies -->

            <!-- Total Companies -->
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill mb-0 position-relative overflow-hidden">
                    <div class="card-body position-relative z-1">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <p class="fs-14 mb-1">Active Companies</p>
                                    <h2 class="mb-1 fs-16">4598</h2>
                                    <p class="text-danger mb-0 fs-13"> <i class="ti ti-arrow-bar-down me-1"></i>12%<span class="text-body ms-1">from last month</span></p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="avatar avatar-md rounded-circle bg-soft-success border border-success">
                                    <i class="ti ti-carousel-vertical fs-16 text-success"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <img src="assets/img/icons/elemnt-02.svg" alt="elemnt-04" class="img-fluid position-absolute top-0 Start-0">
                </div>
            </div>
            <!-- /Total Companies -->

            <!-- Total Companies -->
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill mb-0 position-relative overflow-hidden">
                    <div class="card-body position-relative z-1">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <p class="fs-14 mb-1">Total Subscribers</p>
                                    <h2 class="mb-1 fs-16">5468</h2>
                                    <p class="text-success mb-0 fs-13"> <i class="ti ti-arrow-bar-up me-1"></i>6%<span class="text-body ms-1">from last month</span></p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="avatar avatar-md rounded-circle bg-soft-warning border border-warning">
                                    <i class="ti ti-chalkboard-off fs-16 text-warning"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <img src="assets/img/icons/elemnt-03.svg" alt="elemnt-04" class="img-fluid position-absolute top-0 Start-0">
                </div>
            </div>
            <!-- /Total Companies -->

            <!-- Total Companies -->
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill mb-0 position-relative overflow-hidden">
                    <div class="card-body position-relative z-1">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <p class="fs-14 mb-1">Total Earnings</p>
                                    <h2 class="mb-1 fs-16">$89,878,58</h2>
                                    <p class="text-danger mb-0 fs-13"> <i class="ti ti-arrow-bar-down me-1"></i>16%<span class="text-body ms-1">from last month</span></p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="avatar avatar-md rounded-circle bg-soft-danger border border-danger mb-3">
                                    <i class="ti ti-businessplan fs-16 text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <img src="assets/img/icons/elemnt-04.svg" alt="elemnt-04" class="img-fluid position-absolute top-0 Start-0">
                </div>
            </div>
            <!-- /Total Companies -->

        </div>
        <!-- end row -->

        <!-- start row -->
        <div class="row">
            <!-- Companies -->
            <div class="col-xxl-3 col-lg-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="mb-0">Informe por leads</h6>
                        <!--<div class="dropdown">
                            <a class="dropdown-toggle btn btn-outline-light shadow p-2" data-bs-toggle="dropdown" href="javascript:void(0);">
                                <i class="ti ti-calendar me-1"></i>This Week
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item">
                                    This Month
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    This Week
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    Today
                                </a>
                            </div>
                        </div>-->
                    </div>
                    <div class="card-body">
                        <div id="leads-report"></div>
                    </div>
                </div>
            </div>
            <!-- /Companies -->

            <!-- Revenue -->
            <div class="col-lg-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="mb-0">Revenue</h6>
                        <div class="dropdown">
                            <a class="dropdown-toggle btn btn-outline-light shadow p-2" data-bs-toggle="dropdown" href="javascript:void(0);">
                                <i class="ti ti-calendar me-1"></i>2025
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item">
                                    2025
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    2024
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    2023
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
                            <div class="mb-1">
                                <h5 class="mb-2 fs-16 fw-bold">$89,878,58</h5>
                                <p class="mb-0 fs-13"><span class="text-success fw-normal me-1"><i class="ti ti-arrow-bar-up me-1"></i>40%</span>increased from last year</p>
                            </div>
                            <p class="fs-14 text-dark d-flex align-items-center mb-1"><i class="ti ti-circle-filled me-1 fs-6 text-teal"></i>Revenue</p>
                        </div>
                        <div id="revenue-income"></div>
                    </div>
                </div>
            </div>
            <!-- /Revenue -->

            <!-- Top Plans -->
            <div class="col-xxl-3 col-xl-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="mb-0">Informe leads perdidos</h6>
                        <!--<div class="dropdown">
                            <a class="dropdown-toggle btn btn-outline-light shadow p-2" data-bs-toggle="dropdown" href="javascript:void(0);">
                                <i class="ti ti-calendar me-1"></i>Last 30 Days
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item">
                                    Last 30 Days
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    Last 10 Days
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    Today
                                </a>
                            </div>
                        </div>-->
                    </div>
                    <div class="card-body">
                            <div id="leads-motivo"></div>
                        </div>
                </div>
            </div>
            <!-- /Top Plans -->
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
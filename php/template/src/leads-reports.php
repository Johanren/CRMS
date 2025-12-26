<?php ob_start();?>

    <!-- ========================
        Start Page Content
    ========================= -->

    <div class="page-wrapper">

            <!-- Start Content -->
            <div class="content pb-0">

                <!-- Page Header -->
                <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                    <div>
                        <h4 class="mb-1">Reporte Leads<span class="badge badge-soft-primary ms-2">125</span></h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="index.php">Hogar</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Reporte Leads</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="gap-2 d-flex align-items-center flex-wrap">
                        <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                        <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
                    </div>
                </div>                
				<!-- End Page Header -->

                <div class="row">
                    <div class="col-md-7 d-flex">
                        <div class="card shadow flex-fill">
                            <div
                                class="card-header d-flex justify-content-between align-items-center flex-wrap row-gap-2">
                                <h6 class="mb-0">Leads</h6>
                                <!--<div class="dropdown">
								    <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
                                        <i class="ti ti-calendar me-1"></i>
									    2025
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
								</div>-->
                            </div>
                            <div class="card-body">
                                <div id="leads-report"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 d-flex">
                        <div class="card shadow flex-fill">
                            <div
                                class="card-header d-flex justify-content-between align-items-center flex-wrap row-gap-2">
                                <h6 class="mb-0">Leads Estado</h6>
                                <!--<div class="dropdown">
								    <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
                                        <i class="ti ti-calendar me-1"></i>
									    2025
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
								</div>-->
                            </div>
                            <div class="card-body">
                                <div id="leads-analysis"></div>
                            </div>
                        </div>
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
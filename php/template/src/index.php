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
                <h4 class="mb-0">Panel de ofertas</h4>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                <div class="daterangepick form-control w-auto d-flex align-items-center">
                    <i class="ti ti-calendar text-dark me-2"></i>
                    <span class="reportrange-picker-field text-dark">23 May 2025 - 30 May 2025</span>
                </div>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- start row -->
        <div class="row">

            <div class="col-md-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                        <h6 class="mb-0">Ofertas creadas recientemente</h6>
                        <div class="dropdown">
                            <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
                                últimos 30 días
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item">
                                    últimos 15 días
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    últimos 30 días
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive custom-table">
                            <table class="table dataTable table-nowrap" id="deals-project">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre del trato</th>
                                        <th>Escenario</th>
                                        <th>Valor del trato</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

            <div class="col-md-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                            <h6 class="mb-0">Ofertas por etapa</h6>
                            <div class="d-flex align-items-center flex-wrap row-gap-3">
                                <div class="dropdown me-2">
                                    <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
                                        Canal de ventas
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            Canal de marketing
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            Canl de ventas
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            E-mail
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            Chats
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            Operacional
                                        </a>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
                                        últimos 30 días
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            últimos 30 días
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            últimos 15 días
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            últimos 7 días
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <div id="deals-chart"></div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

        </div>
        <!-- end row -->

        <!-- start row -->
        <div class="row">

            <div class="col-md-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                            <h6 class="mb-0">Etapa de acuerdos perdidos</h6>
                            <div class="d-flex align-items-center flex-wrap row-gap-3">
                                <div class="dropdown me-2">
                                    <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
                                        Canal marketing
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            Canal marketing
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            Canal de ventas
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            E-mail
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            Chats
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            Operacional
                                        </a>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
                                        últimos 30 días
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            últimos 30 días
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            últimos 6 meses
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            últimos 12 meses
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <div id="last-chart"></div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

            <div class="col-md-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                            <h6 class="mb-0">Etapa de acuerdos ganados</h6>
                            <div class="d-flex align-items-center flex-wrap row-gap-3">
                                <div class="dropdown me-2">
                                    <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
                                        Canal de marketing
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            Canal de marketing
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            Canal de ventas
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            E-mail
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            Chats
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            Operacional
                                        </a>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
                                        últimos 30 días
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            últimos 30 días
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            últimos 6 meses
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            últimos 12 meses
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <div id="won-chart"></div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

        </div>
        <!-- end row -->

        <!-- start row -->
        <div class="row">

            <div class="col-md-12 d-flex">
                <div class="card w-100">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                        <h6 class="mb-0">Ofertas por año</h6>
                        <div class="d-flex align-items-center flex-wrap row-gap-3">
                            <div class="dropdown me-2">
                                <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
                                    Canal de ventas
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        Canal de marketing
                                    </a>
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        Canal de ventas
                                    </a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
                                    últimos 30 días
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        últimos 3 meses
                                    </a>
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        últimos 6 meses
                                    </a>
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        últimos 12 meses
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <div id="deals-year"></div>
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
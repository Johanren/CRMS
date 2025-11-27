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
                    <h4 class="mb-0">Panel de control del Carrera</h4>
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

                <div class="col-md-12 col-xl-6 d-flex">		
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                <h6 class="mb-0">Carrera recientes</h6>
                                <div class="d-flex align-items-center flex-wrap row-gap-3">
                                    <div class="dropdown me-2">
                                        <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
                                            Últimos 30 días
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="javascript:void(0);" class="dropdown-item">
                                                Últimos 15 días
                                            </a>
                                            <a href="javascript:void(0);" class="dropdown-item">
                                                Últimos 30 días
                                            </a>
                                        </div>
                                    </div>
                                    <a class="btn btn-primary d-inline-flex align-items-center" href="javascript:void(0);" data-bs-toggle="offcanvas" 
                                    data-bs-target="#offcanvas_add">
                                        <i class="ti ti-square-rounded-plus-filled me-1"></i>Agregar Carrera
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive custom-table">
                                <table class="table table-nowrap" id="recent-project">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Nombre Carrera</th>
                                            <th>Prioridad</th>
                                            <th>Fecha de vencimiento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->
                </div> <!-- end col --> 

                <div class="col-md-12 col-xl-6 d-flex">		
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                <h6 class="mb-0">Carrera por etapa</h6>
                                <div class="dropdown">
                                    <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown" href="javascript:void(0);">
                                        Últimos 30 días
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            Últimos 15 días
                                        </a>
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            Últimos 30 días
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="contacts-analysis"></div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->
                </div> <!-- end col --> 

            </div>
            <!-- end row -->

            <!-- start row -->
            <div class="row">

                <div class="col-md-12 col-xl-6 d-flex">		
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                <h6 class="mb-0">Carrera por etapa</h6>
                                <div class="d-flex align-items-center flex-wrap row-gap-3">
                                    <div class="dropdown me-2">
                                        <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown"
                                            href="javascript:void(0);">
                                            Canal de ventas
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
                                        <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown"
                                            href="javascript:void(0);">
                                            Últimos 3 meses
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="javascript:void(0);" class="dropdown-item">
                                                Últimos 30 días
                                            </a>
                                            <a href="javascript:void(0);" class="dropdown-item">
                                                Últimos 15 días
                                            </a>
                                            <a href="javascript:void(0);" class="dropdown-item">
                                                Últimos 7 días
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body center pt-0">
                            <div id="project-stage"></div>
                            <p class="fw-medium mb-0">Estos datos se recopilan en función de los proyectos de los últimos 30 días.</p>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->
                </div> <!-- end col --> 

                <div class="col-md-12 col-xl-6 d-flex flex-column">	
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                <h6 class="mb-0">Lidera por etapas</h6>
                                <div class="d-flex align-items-center flex-wrap row-gap-3">
                                    <div class="dropdown me-2">
                                        <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown"
                                            href="javascript:void(0);">
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
                                        <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown"
                                            href="javascript:void(0);">
                                            Últimos 3 meses
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="javascript:void(0);" class="dropdown-item">
                                                Últimos 30 días
                                            </a>
                                            <a href="javascript:void(0);" class="dropdown-item">
                                                Últimos 15 días
                                            </a>
                                            <a href="javascript:void(0);" class="dropdown-item">
                                                Últimos 7 días
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

                    <div class="card w-100">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                <h6 class="mb-0">Etapa de acuerdos ganados</h6>
                                <div class="d-flex align-items-center flex-wrap row-gap-3">
                                    <div class="dropdown me-2">
                                        <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown"
                                            href="javascript:void(0);">
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
                                        <a class="dropdown-toggle btn btn-outline-light shadow" data-bs-toggle="dropdown"
                                            href="javascript:void(0);">
                                            Últimos 3 meses
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="javascript:void(0);" class="dropdown-item">
                                                Últimos 30 días
                                            </a>
                                            <a href="javascript:void(0);" class="dropdown-item">
                                                Últimos 15 días
                                            </a>
                                            <a href="javascript:void(0);" class="dropdown-item">
                                                Últimos 7 días
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
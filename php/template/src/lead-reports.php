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

        <!--<div class="row">
            <div class="col-md-7 d-flex">
                <div class="card shadow flex-fill">
                    <div
                        class="card-header d-flex justify-content-between align-items-center flex-wrap row-gap-2">
                        <h6 class="mb-0">Leads By Year</h6>
                        <div class="dropdown">
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
                        </div>
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
                        <h6 class="mb-0">Leads By Source</h6>
                        <div class="dropdown">
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
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="leads-analysis"></div>
                    </div>
                </div>
            </div>
        </div>-->

        <!-- card start -->
        <div class="card border-0 rounded-0">
            <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                <!--<div class="input-icon input-icon-start position-relative">
                    <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search">
                </div>-->
                <a href="javascript:void(0);" onclick="exportarExcel('leads_reporte')" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#####download_report"><i class="ti ti-file-download me-1"></i>Descargar Reporte</a>
            </div>
            <div class="card-body">

                <!-- table header -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-outline-light shadow px-2" data-bs-toggle="dropdown" data-bs-auto-close="outside"><i class="ti ti-filter me-2"></i>Filtro<i class="ti ti-chevron-down ms-2"></i></a>
                            <div class="filter-dropdown-menu dropdown-menu dropdown-menu-lg p-0">
                                <div class="filter-header d-flex align-items-center justify-content-between border-bottom">
                                    <h6 class="mb-0"><i class="ti ti-filter me-1"></i>Filter</h6>
                                    <button type="button" class="btn-close close-filter-btn" data-bs-dismiss="dropdown-menu" aria-label="Close"></button>
                                </div>
                                <div class="filter-set-view p-3">
                                    <div class="accordion" id="accordionExample">
                                        <div class="filter-set-content">
                                            <div class="filter-set-content-head">
                                                <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAsesor" aria-expanded="false" aria-controls="collapseThree">Asesor</a>
                                            </div>
                                            <div class="filter-set-contents accordion-collapse collapse" id="collapseAsesor" data-bs-parent="#accordionExample">
                                                <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                    <div id="listar_filtro_user" class="overflow-x-auto"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="filter-set-content">
                                            <div class="filter-set-content-head">
                                                <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseCarrera" aria-expanded="false" aria-controls="collapseThree">Carrera</a>
                                            </div>
                                            <div class="filter-set-contents accordion-collapse collapse" id="collapseCarrera" data-bs-parent="#accordionExample">
                                                <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                    <div id="listar_filtro_carrera" class="overflow-x-auto"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="filter-set-content">
                                            <div class="filter-set-content-head">
                                                <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#horarioCollapse" aria-expanded="false" aria-controls="collapseThree">Horario</a>
                                            </div>
                                            <div class="filter-set-contents accordion-collapse collapse" id="horarioCollapse" data-bs-parent="#accordionExample">
                                                <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                    <div id="listar_filtro_horario" class="overflow-x-auto"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="filter-set-content">
                                            <div class="filter-set-content-head">
                                                <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#status" aria-expanded="false" aria-controls="status">Estado</a>
                                            </div>
                                            <div class="filter-set-contents accordion-collapse collapse" id="status" data-bs-parent="#accordionExample">
                                                <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                    <div id="listar_filtro_estado"></div>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="contenedor-botones"></div>
                                </div>
                            </div>
                        </div>
                        <div id="reportrange" class="reportrange-picker d-flex align-items-center shadow">
                            <i class="ti ti-calendar-due text-dark fs-14 me-1"></i>
                            <span class="reportrange-picker-field">Seleccione fechas</span>
                        </div>
                    </div>
                    <!--<div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow" data-bs-toggle="dropdown"><i class="ti ti-sort-ascending-2 me-2"></i>Sort By</a>
                            <div class="dropdown-menu">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item">Newest</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item">Oldest</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn bg-soft-indigo px-2 border-0" data-bs-toggle="dropdown" data-bs-auto-close="outside"><i class="ti ti-columns-3 me-2"></i>Manage Columns</a>
                            <div class="dropdown-menu dropdown-menu-md dropdown-md p-3">
                                <ul>
                                    <li class="gap-1 d-flex align-items-center mb-2">
                                        <i class="ti ti-columns me-1"></i>
                                        <div class="form-check form-switch w-100 ps-0">

                                            <label class="form-check-label d-flex align-items-center gap-2 w-100">
                                                <span>Lead Name</span>
                                                <input class="form-check-input switchCheckDefault ms-auto" type="checkbox" role="switch" checked>
                                            </label>
                                        </div>
                                    </li>
                                    <li class="gap-1 d-flex align-items-center mb-2">
                                        <i class="ti ti-columns me-1"></i>
                                        <div class="form-check form-switch w-100 ps-0">

                                            <label class="form-check-label d-flex align-items-center gap-2 w-100">
                                                <span>Company Name</span>
                                                <input class="form-check-input switchCheckDefault ms-auto" type="checkbox" role="switch" checked>
                                            </label>
                                        </div>
                                    </li>
                                    <li class="gap-1 d-flex align-items-center mb-2">
                                        <i class="ti ti-columns me-1"></i>
                                        <div class="form-check form-switch w-100 ps-0">

                                            <label class="form-check-label d-flex align-items-center gap-2 w-100">
                                                <span>Phone</span>
                                                <input class="form-check-input switchCheckDefault ms-auto" type="checkbox" role="switch" checked>
                                            </label>
                                        </div>
                                    </li>
                                    <li class="gap-1 d-flex align-items-center mb-2">
                                        <i class="ti ti-columns me-1"></i>
                                        <div class="form-check form-switch w-100 ps-0">

                                            <label class="form-check-label d-flex align-items-center gap-2 w-100">
                                                <span>Lead Status</span>
                                                <input class="form-check-input switchCheckDefault ms-auto" type="checkbox" role="switch" checked>
                                            </label>
                                        </div>
                                    </li>
                                    <li class="gap-1 d-flex align-items-center mb-2">
                                        <i class="ti ti-columns me-1"></i>
                                        <div class="form-check form-switch w-100 ps-0">

                                            <label class="form-check-label d-flex align-items-center gap-2 w-100">
                                                <span>Created Date</span>
                                                <input class="form-check-input switchCheckDefault ms-auto" type="checkbox" role="switch" checked>
                                            </label>
                                        </div>
                                    </li>
                                    <li class="gap-1 d-flex align-items-center">
                                        <i class="ti ti-columns me-1"></i>
                                        <div class="form-check form-switch w-100 ps-0">

                                            <label class="form-check-label d-flex align-items-center gap-2 w-100">
                                                <span>Lead Owner</span>
                                                <input class="form-check-input switchCheckDefault ms-auto" type="checkbox" role="switch" checked>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>-->
                </div>
                <!-- table header -->

                <!-- Report List -->
                <div class="table-responsive custom-table">
                    <table class="table table-nowrap" id="leads_reports">
                        <thead class="table-light">
                            <tr id="thead_dynamic"></tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="datatable-length"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="datatable-paginate"></div>
                    </div>
                </div>
                <!-- /Contact List -->

            </div>
        </div>
        <!-- card end -->

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
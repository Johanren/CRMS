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
                <h4 class="mb-1">Reporte RST<span class="badge badge-soft-primary ms-2">125</span></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="index.php">Hogar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reporte RST</li>
                    </ol>
                </nav>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- card start -->
        <div class="card border-0 rounded-0">
            <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                <a href="javascript:void(0);" onclick="exportarExcel('rst_frm')" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#####download_report"><i class="ti ti-file-download me-1"></i>Descargar Reporte</a>
            </div>
            <div class="card-body">

                <!-- table header -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-outline-light shadow px-2" data-bs-toggle="dropdown" data-bs-auto-close="outside"><i class="ti ti-filter me-2"></i>Filtrar<i class="ti ti-chevron-down ms-2"></i></a>
                            <div class="filter-dropdown-menu dropdown-menu dropdown-menu-lg p-0">
                                <div class="filter-header d-flex align-items-center justify-content-between border-bottom">
                                    <h6 class="mb-0"><i class="ti ti-filter me-1"></i>Filtrar</h6>
                                    <button type="button" class="btn-close close-filter-btn" data-bs-dismiss="dropdown-menu" aria-label="Close"></button>
                                </div>
                                <div class="filter-set-view p-3">
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
                                        </div>
                                    </div>
                                    <div id="contenedor-botones"></div>
                                </div>
                            </div>
                        </div>
                        <div class="input-icon input-icon-start position-relative">
                            <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" id="buscador" placeholder="Buscar">
                        </div>
                    </div>
                </div>
                <!-- table header -->

                <!-- Report List -->
                <style>
                    /* ===== LOADER ===== */
                    .loader-overlay {
                        position: fixed;
                        inset: 0;
                        background: rgba(255, 255, 255, 0.85);
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        z-index: 9999;
                    }

                    .loader-overlay p {
                        margin-top: 10px;
                        font-weight: bold;
                    }

                    .spinner {
                        width: 50px;
                        height: 50px;
                        border: 6px solid #ddd;
                        border-top: 6px solid #007bff;
                        border-radius: 50%;
                        animation: spin 1s linear infinite;
                    }

                    @keyframes spin {
                        to {
                            transform: rotate(360deg);
                        }
                    }

                    /* UTIL */
                    .d-none {
                        display: none;
                    }
                </style>
                <div class="table-responsive custom-table">
                    <table class="table table-bordered" id="rst_reports">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Asesor</th>
                                <th>Observación</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div id="loaderFoco" class="loader-overlay d-none">
                        <div class="spinner"></div>
                        <p>Cargando reporte...</p>
                    </div>
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
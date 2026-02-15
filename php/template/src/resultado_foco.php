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
                <h4 class="mb-0">Foco</h4>
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

            <div class="col-md-12 d-flex">
                <div class="card w-100">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                        <h6 class="mb-0">Foco Detalle</h6>
                        <div class="d-flex align-items-center flex-wrap row-gap-3">
                            <div class="dropdown">
                                <a onclick="exportarExcel('foco_leads')" class="btn btn-primary"><i class="ti ti-square-rounded-plus-filled me-1"></i>Exportar excel</a>
                            </div>
                        </div>
                    </div>
                    <style>
                        /* ===== CONTENEDOR CON SCROLL ===== */
                        .contenedor-tabla-foco {
                            max-height: 65vh;
                            /* Altura visible */
                            overflow: auto;
                            /* Scroll vertical y horizontal */
                        }

                        /* ===== TABLA ===== */
                        #tablaFocoResultado {
                            border-collapse: separate;
                            /* NECESARIO para sticky */
                            border-spacing: 0;
                            width: max-content;
                            /* Permite scroll horizontal */
                            min-width: 100%;
                            text-align: center;
                        }

                        /* ===== CELDAS ===== */
                        #tablaFocoResultado th,
                        #tablaFocoResultado td {
                            border: 1px solid black;
                            padding: 8px;
                            white-space: nowrap;
                            /* Evita que se rompan columnas */
                        }

                        /* ===== THEAD FIJO ===== */
                        #tablaFocoResultado thead th {
                            position: sticky;
                            top: 0;
                            background-color: #f2f2f2;
                            font-weight: bold;
                            z-index: 20;
                            /* Encima del tbody */
                        }

                        /* ===== TD ===== */
                        #tablaFocoResultado td {
                            background-color: #ffffff;
                        }

                        /* ===== FILA SELECCIONADA ===== */
                        .fila-activa td {
                            background-color: #d1ecf1 !important;
                        }

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

                        /* ===== UTIL ===== */
                        .d-none {
                            display: none;
                        }
                    </style>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="btn btn-outline-light shadow px-2"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"><i
                                            class="ti ti-filter me-2"></i>Filtrar<i class="ti ti-chevron-down ms-2"></i></a>
                                    <div class="filter-dropdown-menu dropdown-menu dropdown-menu-lg p-0">
                                        <div
                                            class="filter-header d-flex align-items-center justify-content-between border-bottom">
                                            <h6 class="mb-0"><i class="ti ti-filter me-1"></i>Filtrar</h6>
                                            <button type="button" class="btn-close close-filter-btn"
                                                data-bs-dismiss="dropdown-menu" aria-label="Close"></button>
                                        </div>
                                        <div class="filter-set-view p-3">
                                            <div class="filter-set-view p-3">
                                                <div class="accordion" id="accordionExample">
                                                    <div class="filter-set-content">
                                                        <div class="filter-set-content-head">
                                                            <a href="#" class="collapsed" data-bs-toggle="collapse"
                                                                data-bs-target="#collapseAsesor" aria-expanded="false"
                                                                aria-controls="collapseThree">Asesor</a>
                                                        </div>
                                                        <div class="filter-set-contents accordion-collapse collapse"
                                                            id="collapseAsesor" data-bs-parent="#accordionExample">
                                                            <div
                                                                class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                                <div id="listar_filtro_user" class="overflow-x-auto"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="filter-set-content">
                                                        <div class="filter-set-content-head">
                                                            <a href="#" class="collapsed" data-bs-toggle="collapse"
                                                                data-bs-target="#collapseCarrera" aria-expanded="false"
                                                                aria-controls="collapseThree">Carrera</a>
                                                        </div>
                                                        <div class="filter-set-contents accordion-collapse collapse"
                                                            id="collapseCarrera" data-bs-parent="#accordionExample">
                                                            <div
                                                                class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                                <div id="listar_filtro_carrera" class="overflow-x-auto"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="filter-set-content">
                                                        <div class="filter-set-content-head">
                                                            <a href="#" class="collapsed" data-bs-toggle="collapse"
                                                                data-bs-target="#collapseEstado" aria-expanded="false"
                                                                aria-controls="collapseThree">Estado</a>
                                                        </div>
                                                        <div class="filter-set-contents accordion-collapse collapse"
                                                            id="collapseEstado" data-bs-parent="#accordionExample">
                                                            <div
                                                                class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                                <div id="listar_filtro_estado" class="overflow-x-auto"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="contenedor-botones"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="contenedor-tabla-foco">
                            <table id="tablaFocoResultado">
                                <thead></thead>
                                <tbody></tbody>
                            </table>
                            <div id="loaderFoco" class="loader-overlay d-none">
                                <div class="spinner"></div>
                                <p>Cargando reporte...</p>
                            </div>
                        </div>
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
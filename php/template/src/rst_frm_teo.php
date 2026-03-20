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
                <h4 class="mb-1">Desempeño TEO<span class="badge badge-soft-primary ms-2">125</span></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="index.php">Hogar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Desempeño TEO</li>
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
                <a href="javascript:void(0);" onclick="exportarExcel('rst_teo')" class="btn btn-primary"
                    data-bs-toggle="modal" data-bs-target="#####download_report"><i
                        class="ti ti-file-download me-1"></i>Descargar Reporte</a>
            </div>
            <div class="card-body">

                <style>
                    /* ===== TABLAS TIPO EXCEL ===== */
                    .table-excel {
                        width: 100%;
                        border-collapse: collapse;
                        font-size: 13px;
                        background: #fff;
                    }

                    .table-excel thead th {
                        background: #f8f9fa;
                        color: #333;
                        font-weight: 600;
                        text-align: center;
                        border: 1px solid #dee2e6;
                        padding: 8px;
                        white-space: nowrap;
                    }

                    .table-excel tbody td {
                        border: 1px solid #dee2e6;
                        padding: 6px;
                        text-align: center;
                    }

                    .table-excel tbody tr:hover {
                        background-color: #f1f5f9;
                    }

                    /* Columna Día / Estado */
                    .table-excel td:first-child {
                        font-weight: 600;
                        background: #f8f9fa;
                    }

                    /* ===== FILA TOTAL (VERDE) ===== */
                    .table-total {
                        background-color: #d1fae5 !important;
                        color: #065f46;
                        font-weight: bold;
                    }

                    /* ===== CONTENEDOR RESPONSIVE ===== */
                    .table-responsive-excel {
                        width: 100%;
                        overflow-x: auto;
                        margin-bottom: 20px;
                    }

                    /* Scroll bonito */
                    .table-responsive-excel::-webkit-scrollbar {
                        height: 8px;
                    }

                    .table-responsive-excel::-webkit-scrollbar-thumb {
                        background: #cbd5e1;
                        border-radius: 4px;
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

                    /* UTIL */
                    .d-none {
                        display: none;
                    }
                </style>
                <!-- table header -->
                <h5>Leads asignados por día</h5>
                <div id="tablaDias"></div>

                <h5>Resumen por estado</h5>
                <div id="tablaEstados"></div>
                <div id="loaderFoco" class="loader-overlay d-none">
                    <div class="spinner"></div>
                    <p>Cargando reporte...</p>
                </div>
                <!-- TABLA -->
                <div class="table-responsive custom-table position-relative">
                    <table class="table table-hover table-bordered align-middle" id="rst_reports">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>TEO</th>
                                <th>Tipo Transferencia</th>
                                <th>Observación</th>
                                <th>Asesor</th>
                                <th>Estado</th>
                                <th>Notas</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <!-- Loader -->
                    <div id="loaderFoco" class="loader-overlay d-none">
                        <div class="spinner"></div>
                        <p class="mt-2 fw-semibold">Cargando reporte...</p>
                    </div>
                </div>

                <!-- PAGINACIÓN -->
                <div class="row align-items-center mt-3">
                    <div class="col-md-6">
                        <div class="datatable-length"></div>
                    </div>
                    <div class="col-md-6 text-end">
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
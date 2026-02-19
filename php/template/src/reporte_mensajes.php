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
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                    data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i
                        class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                    data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse"
                    id="collapse-header"><i class="ti ti-transition-top"></i></a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- card start -->
        <div class="card border-0 rounded-0">
            <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                <!--<a href="javascript:void(0);" onclick="exportarExcel('CRMS_lead')" class="btn btn-primary"
                    data-bs-toggle="modal" data-bs-target="#####download_report"><i
                        class="ti ti-file-download me-1"></i>Descargar Reporte</a>-->
            </div>
            <div class="card-body">

                <!-- table header -->
                <!--<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
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
                </div>-->
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
                <!-- ===================== -->
                <!-- INFORME 1 -->
                <!-- ===================== -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header text-white">
                        <h5 class="mb-0">Informe 1 - Total diario por tipo de mensaje enviado</h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tablaReporte1" class="table table-bordered table-striped table-hover align-middle w-100">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo Mensaje</th>
                                        <th>Mensaje</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables cargará los datos aquí -->
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <th colspan="3" class="text-end">TOTAL GENERAL</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- ===================== -->
                <!-- INFORME 2 -->
                <!-- ===================== -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header text-white">
                        <h5 class="mb-0">Informe 2 - Total de mensajes por estado por día</h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tablaReporte2" class="table table-bordered table-striped table-hover align-middle w-100">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables cargará los datos aquí -->
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <th colspan="2" class="text-end">TOTAL GENERAL</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- ===================== -->
                <!-- INFORME 3 -->
                <!-- ===================== -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header text-white">
                        <h5 class="mb-0">Informe 3 - Total mensajes enviados por asesor</h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tablaReporte3" class="table table-bordered table-striped table-hover align-middle w-100">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Asesor</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables cargará los datos aquí -->
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <th colspan="2" class="text-end">TOTAL GENERAL</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
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
<script>
    function listarReportesMensajeria() {

        const params = new URLSearchParams();

        const loader = document.getElementById("loaderFoco");
        if (loader) loader.classList.remove("d-none");

        /* ==========================================================
           REPORTE 1
        ========================================================== */
        params.set("accion", "reporte1_mensajes");

        fetch("ajax/ajax.php?" + params.toString())
            .then(res => res.json())
            .then(data => {
                if (document.getElementById("tablaReporte1")) {
                    inicializarDataTableReporte1(data);
                }
            })
            .catch(err => console.error("Error reporte 1:", err))
            .finally(() => {
                if (loader) loader.classList.add("d-none");
            });


        /* ==========================================================
           REPORTE 2
        ========================================================== */
        params.set("accion", "reporte2_estados");

        fetch("ajax/ajax.php?" + params.toString())
            .then(res => res.json())
            .then(data => {
                if (document.getElementById("tablaReporte2")) {
                    inicializarDataTableReporte2(data);
                }
            })
            .catch(err => console.error("Error reporte 2:", err));


        /* ==========================================================
           REPORTE 3
        ========================================================== */
        params.set("accion", "reporte3_asesores");

        fetch("ajax/ajax.php?" + params.toString())
            .then(res => res.json())
            .then(data => {
                if (document.getElementById("tablaReporte3")) {
                    inicializarDataTableReporte3(data);
                }
            })
            .catch(err => console.error("Error reporte 3:", err));
    }

    function inicializarDataTableReporte1(result) {
        $('#tablaReporte1').DataTable({
            destroy: true,
            data: result.data,
            // DESHABILITA el orden automático inicial para respetar el de SQL
            order: [],
            columns: [{
                    data: 'fecha'
                },
                {
                    data: 'tipo_mensaje'
                },
                {
                    data: 'plantilla'
                },
                {
                    data: 'total_enviados'
                }
            ],
            footerCallback: function() {
                let api = this.api();
                $(api.column(3).footer()).html(
                    '<strong>' + result.total_general + '</strong>'
                );
            },
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });
    }

    function inicializarDataTableReporte2(result) {
        $('#tablaReporte2').DataTable({
            destroy: true,
            data: result.data,
            order: [], // Respeta el orden DESC de la base de datos
            columns: [{
                    data: 'fecha'
                },
                {
                    data: 'nombre'
                },
                {
                    data: 'total'
                }
            ],
            footerCallback: function() {
                let api = this.api();
                $(api.column(2).footer()).html(
                    '<strong>' + result.total_general + '</strong>'
                );
            },
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });
    }

    function inicializarDataTableReporte3(result) {
        $('#tablaReporte3').DataTable({
            destroy: true,
            data: result.data,
            order: [], // Respeta el orden DESC de la base de datos
            columns: [{
                    data: 'fecha'
                },
                {
                    data: 'asesor'
                },
                {
                    data: 'total'
                }
            ],
            footerCallback: function() {
                let api = this.api();
                $(api.column(2).footer()).html(
                    '<strong>' + result.total_general + '</strong>'
                );
            },
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        listarReportesMensajeria();
    });
</script>
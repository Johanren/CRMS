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
                <h4 class="mb-1">Reporte Perdido<span class="badge badge-soft-primary ms-2">125</span></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="index.php">Hogar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reporte Perdido</li>
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
                <a href="javascript:void(0);" onclick="exportarExcel('perdido')" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#####download_report"><i class="ti ti-file-download me-1"></i>Descargar Reporte</a>
            </div>
            <div class="card-body">

                <!-- table header -->
                <!--<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
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
                </div>-->
                <style>
                    .rst_reports {
                        width: 100%;
                        border-collapse: collapse;
                        font-size: 13px;
                        background: #fff;
                    }

                    .rst_reports thead th {
                        background: #f8f9fa;
                        color: #333;
                        font-weight: 600;
                        text-align: center;
                        border: 1px solid #dee2e6;
                        padding: 8px;
                        white-space: nowrap;
                    }

                    .rst_reports tbody td {
                        border: 1px solid #dee2e6;
                        padding: 6px;
                        text-align: center;
                    }

                    .rst_reports tbody tr:hover {
                        background-color: #f1f5f9;
                    }

                    /* Columna Día / Estado */
                    .rst_reports td:first-child {
                        font-weight: 600;
                        background: #f8f9fa;
                    }

                    .bg-total-fila {
                        background-color: #f8f9fa !important;
                        font-weight: bold;
                    }

                    .bg-gran-total {
                        background-color: #0d6efd !important;
                        color: white !important;
                    }

                    .cursor-pointer {
                        cursor: pointer;
                        transition: all 0.2s;
                    }

                    .cursor-pointer:hover {
                        background-color: rgba(13, 110, 253, 0.1) !important;
                        transform: scale(1.02);
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
                <div id="tablaMotivos">

                </div>
                <div id="loaderFoco" class="loader-overlay d-none">
                    <div class="spinner"></div>
                    <p>Cargando reporte...</p>
                </div>
                <div id="contenedorLeadsFoco" class="mt-4 d-none">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">
                                Leads filtrados por asesor y motivo
                            </h6>

                            <button id="btnAbrirModalMensajes" class="btn btn-primary btn-sm">
                                Enviar mensajes
                            </button>
                        </div>
                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                            <strong>Resultados del filtro:</strong>
                            <span class="badge bg-dark fs-12"><span id="contadorTotalLeads">0</span> Leads encontrados</span>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-nowrap" id="leads_list">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Carrera</th>
                                        <th>Telefono</th>
                                        <th>Estado</th>
                                        <th>Asesor</th>
                                        <th>Fecha creación</th>
                                        <th>Gestion</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modalGestionLead" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-xxl-custom">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Gestión de Lead</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body p-0">
                                <iframe id="frameGestion"
                                    src=""
                                    style="width:100%; height:80vh; border:none;">
                                </iframe>
                            </div>

                        </div>
                    </div>
                </div>

                <style>
                    .modal-xxl-custom {
                        max-width: 95%;
                        width: 95%;
                    }
                </style>
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
    function cargarReporteMotivosPivot() {
        const loader = $('#loaderFoco');
        const contenedor = $('#tablaMotivos');
        loader.removeClass('d-none');

        $.ajax({
            url: 'ajax/ajax.php?accion=reporte_leads_motivo',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (!data || data.length === 0) {
                    contenedor.html('<div class="alert alert-warning">No hay datos disponibles</div>');
                    return;
                }

                let asesoresSet = new Set();
                let estadosSet = new Set();
                let mapaIdAsesores = {}; // 🔹 Para guardar la relación Nombre -> ID

                data.forEach(r => {
                    const nomAsesor = r.asesor || "SIN ASESOR";
                    asesoresSet.add(nomAsesor);
                    estadosSet.add(r.estado || "SIN ESTADO");

                    // Guardamos el ID asociado al nombre del asesor
                    if (r.id_user && !mapaIdAsesores[nomAsesor]) {
                        mapaIdAsesores[nomAsesor] = r.id_user;
                    }
                });

                let asesores = Array.from(asesoresSet).sort();
                let estados = Array.from(estadosSet).sort();

                let tabla = {};
                estados.forEach(e => {
                    tabla[e] = {};
                    asesores.forEach(a => {
                        tabla[e][a] = 0;
                    });
                    tabla[e]['total'] = 0;
                });

                data.forEach(r => {
                    let asesor = r.asesor || "SIN ASESOR";
                    let estado = r.estado || "SIN ESTADO";
                    let cantidad = parseInt(r.cantidad) || 0;
                    if (tabla[estado]) {
                        tabla[estado][asesor] += cantidad;
                        tabla[estado]['total'] += cantidad;
                    }
                });

                let totalesAsesor = {};
                asesores.forEach(a => totalesAsesor[a] = 0);
                let granTotal = 0;

                estados.forEach(e => {
                    asesores.forEach(a => {
                        totalesAsesor[a] += tabla[e][a];
                    });
                    granTotal += tabla[e]['total'];
                });

                let html = `
            
            <div class="table-responsive">
                <table id="tablaPivot" class="rst_reports">
                    <thead>
                        <tr>
                            <th>Estado / Asesor</th>
                            ${asesores.map(a => `<th>${a}</th>`).join('')}
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                estados.forEach(e => {

                    html += `<tr>`;
                    html += `<td><strong>${e}</strong></td>`;

                    asesores.forEach(a => {

                        const valor = tabla[e][a];
                        const idUser = mapaIdAsesores[a] || "";

                        if (valor > 0) {

                            html += `
                            <td class="abrir-mensajes-foco text-primary fw-bold cursor-pointer"
                                data-estado="${e}"
                                data-user="${idUser}">
                                ${valor}
                            </td>`;

                        } else {

                            html += `<td class="text-muted-dash">-</td>`;
                        }

                    });

                    /* Total fila */
                    html += `
                    <td class="abrir-mensajes-foco bg-total-fila text-primary fw-bold cursor-pointer"
                        data-estado="${e}"
                        data-user="TODOS">
                        ${tabla[e]['total']}
                    </td>
                    </tr>`;
                });

                /* FOOTER */
                html += `
                </tbody>
                <tfoot>
                <tr>
                    <td class="bg-total-fila"><strong>TOTAL GENERAL</strong></td>

                    ${asesores.map(a => `
                        <td class="abrir-mensajes-foco bg-total-fila text-primary fw-bold cursor-pointer"
                            data-estado="TODOS"
                            data-user="${mapaIdAsesores[a] || ""}">
                            ${totalesAsesor[a]}
                        </td>
                    `).join('')}

                    <td class="abrir-mensajes-foco bg-gran-total fw-bold cursor-pointer"
                        data-estado="TODOS"
                        data-user="TODOS">
                        ${granTotal}
                    </td>
                </tr>
                </tfoot>
                </table>
            </div>`;

                contenedor.html(html);
            },
            error: function(e) {
                contenedor.html('<div class="alert alert-danger">Error al cargar el reporte</div>');
            },
            complete: function() {
                loader.addClass('d-none');
            }
        });
    }
    // Ejecutar al cargar
    $(document).ready(function() {
        cargarReporteMotivosPivot();
    });
</script>
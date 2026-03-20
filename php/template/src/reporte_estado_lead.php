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
                <h4 class="mb-1">Reporte estado lead<span class="badge badge-soft-primary ms-2">125</span></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="index.php">Hogar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reporte festado lead</li>
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
                <a href="javascript:void(0);" onclick="exportarExcel('estado_lead')" class="btn btn-primary"
                    data-bs-toggle="modal" data-bs-target="#####download_report"><i
                        class="ti ti-file-download me-1"></i>Descargar Reporte</a>
            </div>
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
                                                    <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">

                                                        <div class="form-check mb-2 border-bottom pb-1">
                                                            <input class="form-check-input select-all-filter" type="checkbox"
                                                                data-target=".filtro-asesor" id="all_asesor">
                                                            <label class="form-check-label fw-bold" for="all_asesor" style="cursor:pointer;">
                                                                Seleccionar todos
                                                            </label>
                                                        </div>

                                                        <div id="listar_filtro_user" class="overflow-x-auto"></div>
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
                                                    <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">

                                                        <div class="form-check mb-2 border-bottom pb-1">
                                                            <input class="form-check-input select-all-filter" type="checkbox"
                                                                data-target=".filtro-estado" id="all_estado">
                                                            <label class="form-check-label fw-bold" for="all_estado" style="cursor:pointer;">
                                                                Seleccionar todos
                                                            </label>
                                                        </div>

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
                        <div id="reportrange" class="reportrange-picker d-flex align-items-center shadow">
                            <i class="ti ti-calendar-due text-dark fs-14 me-1"></i>
                            <span class="reportrange-picker-field">Seleccione fechas</span>
                        </div>
                        <div class="input-icon input-icon-start position-relative">
                            <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" id="buscador" placeholder="Buscar">
                        </div>
                    </div>
                </div>
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

                    #rst_reports td,
                    #rst_reports th {
                        padding: 3px 6px !important;
                        font-size: 12px;
                    }

                    #paginacion button {
                        margin-right: 4px;
                    }
                </style>
                <div class="table-responsive custom-table">

                    <!-- CONTROLES -->
                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <!-- selector registros -->
                        <div>
                            <label class="me-2 fw-bold">Mostrar:</label>
                            <select id="limitSelect" class="form-select form-select-sm d-inline-block w-auto">
                                <option value="10">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        <!-- paginación -->
                        <div id="paginacion"></div>

                    </div>

                    <!-- TABLA -->
                    <table id="rst_reports" class="table table-bordered table-striped table-hover table-sm">

                        <thead class="table-dark text-center">
                            <tr>
                                <th style="width:40px"></th>
                                <th>Cliente</th>
                                <th>Asesor</th>
                                <th>Estado Actual</th>
                                <th>Cambios</th>
                            </tr>
                        </thead>

                        <tbody></tbody>

                    </table>

                </div>

                <!-- LOADER -->
                <div id="loaderFoco" class="loader-overlay d-none">
                    <div class="spinner"></div>
                    <p>Cargando reporte...</p>
                </div>
            </div>
        </div>

        <style>
            .grafico-container {
                position: relative;
                height: 380px;
                width: 100%;
            }

            .grafico-scroll {
                overflow-x: auto;
            }

            canvas {
                min-width: 600px;
            }
        </style>

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
    function exportarExcel(tipo) {
        const f = Filtros.obtener();
        const params = new URLSearchParams();

        // Tipo de reporte (ej: "leads", "asesores", "campanas", etc.)
        params.append("tipo", tipo);

        // Convertir filtros a parámetros GET
        for (let k in f) {
            if (Array.isArray(f[k]) && f[k].length > 0) {
                params.append(k, JSON.stringify(f[k]));
            } else if (f[k] !== "") {
                params.append(k, f[k]);
            }
        }

        window.location.href = "ajax/exportar_excel.php?" + params.toString();
    }

    /*Fecha inicio fin */

    $(function() {

        $('#reportrange').daterangepicker({
                opens: "left",
                autoUpdateInput: false,
                locale: {
                    format: "YYYY-MM-DD",
                    applyLabel: "Aplicar",
                    cancelLabel: "Cancelar",
                    daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                    monthNames: [
                        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                    ]
                }
            },
            function(start, end) {

                // Guardar fechas globales
                window.fecha_inicio = start.format("YYYY-MM-DD");
                window.fecha_fin = end.format("YYYY-MM-DD");

                // Mostrar en el span
                document.querySelector(".reportrange-picker-field").innerHTML =
                    start.format("DD MMM YY") + " - " + end.format("DD MMM YY");

                listarReporteEstadoLeads();
            }
        );
    });

    window.Filtros = {
        obtener: function() {

            let texto = "";
            let inputBuscador = document.getElementById("buscador");
            if (inputBuscador) {
                texto = inputBuscador.value.toLowerCase();
            }

            let asesor = [...document.querySelectorAll(".filtro-asesor:checked")]
                .map(c => c.value);

            let carrera = [...document.querySelectorAll(".filtro-carrera:checked")]
                .map(c => c.value);

            let estados = [...document.querySelectorAll(".filtro-estado:checked")]
                .map(c => c.value);

            let fecha_inicio = window.fecha_inicio || "";
            let fecha_fin = window.fecha_fin || "";

            return {
                texto,
                asesor,
                estados,
                carrera,
                fecha_inicio,
                fecha_fin
            };
        }
    };

    let paginaActual = 1;
    let limiteActual = 10; // 5, 50 o 100

    function listarReporteEstadoLeads(page = 1) {

        paginaActual = page;

        const f = Filtros.obtener();
        const params = new URLSearchParams();

        const loader = document.getElementById("loaderFoco");
        if (loader) loader.classList.remove("d-none");

        params.append("accion", "reporte_estado_leads");
        params.append("page", paginaActual);
        params.append("limit", limiteActual);

        if (f.texto !== "") params.append("texto", f.texto);
        if (f.asesor.length > 0) params.append("asesor", JSON.stringify(f.asesor));
        if (f.estados.length > 0) params.append("estados", JSON.stringify(f.estados));
        if (f.carrera.length > 0) params.append("carrera", JSON.stringify(f.carrera));
        if (f.fecha_inicio !== "") params.append("fecha_inicio", f.fecha_inicio);
        if (f.fecha_fin !== "") params.append("fecha_fin", f.fecha_fin);

        fetch("ajax/ajax.php?" + params.toString())
            .then(res => res.json())
            .then(data => {

                let tbody = document.querySelector("#rst_reports tbody");
                tbody.innerHTML = "";

                data.data.forEach(row => {

                    let tr = `
                <tr class="text-center">
                    <td style="width:40px">
                        <button class="btn btn-sm btn-primary btnHistorial"
                        data-id="${row.id_lead}">
                        +
                        </button>
                    </td>

                    <td class="text-start fw-bold">${row.cliente}</td>
                    <td>${row.asesor ?? "-"}</td>

                    <td>
                        <span class="badge bg-primary">
                        ${row.estado_actual}
                        </span>
                    </td>

                    <td>${row.cambios}</td>
                </tr>

                <tr id="hist_${row.id_lead}" style="display:none">
                    <td colspan="5">
                        <div class="contenidoHistorial p-2"></div>
                    </td>
                </tr>
                `;

                    tbody.innerHTML += tr;
                });

                renderPaginacion(data.total, data.limit, data.page);

            })
            .catch(err => console.error("Error reporte estados:", err))
            .finally(() => {
                if (loader) loader.classList.add("d-none");
            });

    }

    function renderPaginacion(total, limit, page) {

        const totalPaginas = Math.ceil(total / limit);
        let html = "";

        // BOTON ANTERIOR
        if (page > 1) {
            html += `
        <button class="btn btn-sm btn-light"
        onclick="listarReporteEstadoLeads(${page - 1})">
        &lt;
        </button>`;
        }

        let start = Math.max(1, page - 2);
        let end = Math.min(totalPaginas, page + 2);

        // Si estamos cerca del inicio
        if (page <= 4) {
            start = 1;
            end = Math.min(8, totalPaginas);
        }

        // Si estamos cerca del final
        if (page > totalPaginas - 4) {
            start = Math.max(1, totalPaginas - 7);
            end = totalPaginas;
        }

        // Primera página si no está visible
        if (start > 1) {
            html += `
        <button class="btn btn-sm btn-light"
        onclick="listarReporteEstadoLeads(1)">
        1
        </button>`;

            if (start > 2) {
                html += `<span class="mx-1">...</span>`;
            }
        }

        // PAGINAS
        for (let i = start; i <= end; i++) {

            html += `
        <button class="btn btn-sm ${i == page ? 'btn-primary' : 'btn-light'}"
        onclick="listarReporteEstadoLeads(${i})">
        ${i}
        </button>`;
        }

        // Última página si no está visible
        if (end < totalPaginas) {

            if (end < totalPaginas - 1) {
                html += `<span class="mx-1">...</span>`;
            }

            html += `
        <button class="btn btn-sm btn-light"
        onclick="listarReporteEstadoLeads(${totalPaginas})">
        ${totalPaginas}
        </button>`;
        }

        // BOTON SIGUIENTE
        if (page < totalPaginas) {
            html += `
        <button class="btn btn-sm btn-light"
        onclick="listarReporteEstadoLeads(${page + 1})">
        &gt;
        </button>`;
        }

        document.getElementById("paginacion").innerHTML = html;
    }

    document.addEventListener("click", function(e) {

        if (e.target.classList.contains("btnHistorial")) {

            let idlead = e.target.dataset.id;
            let fila = document.getElementById("hist_" + idlead);

            if (fila.style.display === "table-row") {
                fila.style.display = "none";
                return;
            }

            const params = new URLSearchParams();
            params.append("accion", "historial_estado_lead");
            params.append("idlead", idlead);

            fetch("ajax/ajax.php?" + params.toString())

                .then(res => res.json())

                .then(data => {

                    let html = `

                        <table class="table table-sm table-bordered">

                        <thead>

                        <tr class="table-light">

                            <th>Fecha</th>
                            <th>Asesor</th>
                            <th>Estado anterior</th>
                            <th>Estado nuevo</th>

                        </tr>

                        </thead>

                        <tbody>

                        `;

                    data.forEach(h => {

                        html += `

                        <tr>

                            <td>${h.fec_log} ${h.hor_log}</td>

                            <td>${h.asesor}</td>

                            <td>${h.estado_anterior ?? "-"}</td>

                            <td>${h.estado_nuevo ?? "-"}</td>

                        </tr>

                    `;

                    });

                    html += "</tbody></table>";

                    fila.querySelector(".contenidoHistorial").innerHTML = html;

                    fila.style.display = "table-row";

                })

                .catch(err => console.error("Error historial:", err));

        }

    });

    document.getElementById("limitSelect").addEventListener("change", function() {

        limiteActual = parseInt(this.value);
        listarReporteEstadoLeads(1);

    });

    document.addEventListener("change", function(e) {
        if (e.target.classList.contains("filtro") || e.target.classList.contains("select-all-filter")) {
            listarReporteEstadoLeads();
        }
    });

    document.addEventListener("input", function(e) {
        if (e.target.id === "buscador") {
            listarReporteEstadoLeads();
        }
    });

    listarReporteEstadoLeads();
</script>
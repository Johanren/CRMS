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
                <h4 class="mb-1">Reporte fuente y origen<span class="badge badge-soft-primary ms-2">125</span></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="index.php">Hogar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reporte fuente y origen</li>
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
        <div class="input-icon input-icon-start position-relative">
            <span id="resumen-filtros" class="text-muted small"></span>
        </div>
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
                                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAsesor" aria-expanded="false" aria-controls="collapseThree">Asesor</a>
                                        </div>
                                        <div class="filter-set-contents accordion-collapse collapse" id="collapseAsesor" data-bs-parent="#accordionExample">
                                            <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                <div class="form-check mb-2 border-bottom pb-1">
                                                    <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-asesor" id="all_asesor">
                                                    <label class="form-check-label fw-bold" for="all_asesor">Seleccionar todos</label>
                                                </div>
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
                                                <div class="form-check mb-2 border-bottom pb-1">
                                                    <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-carrera" id="all_carrera">
                                                    <label class="form-check-label fw-bold" for="all_carrera">Seleccionar todos</label>
                                                </div>
                                                <div id="listar_filtro_carrera" class="overflow-x-auto"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="filter-set-content">
                                        <div class="filter-set-content-head">
                                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseHorario" aria-expanded="false" aria-controls="collapseThree">Jornada</a>
                                        </div>
                                        <div class="filter-set-contents accordion-collapse collapse" id="collapseHorario" data-bs-parent="#accordionExample">
                                            <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                <div class="form-check mb-2 border-bottom pb-1">
                                                    <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-horario" id="all_horario">
                                                    <label class="form-check-label fw-bold" for="all_horario">Seleccionar todos</label>
                                                </div>
                                                <div id="listar_filtro_horario" class="overflow-x-auto"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="filter-set-content">
                                        <div class="filter-set-content-head">
                                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseEstado" aria-expanded="false" aria-controls="collapseThree">Estado</a>
                                        </div>
                                        <div class="filter-set-contents accordion-collapse collapse" id="collapseEstado" data-bs-parent="#accordionExample">
                                            <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                <div class="form-check mb-2 border-bottom pb-1">
                                                    <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-estado" id="all_estado">
                                                    <label class="form-check-label fw-bold" for="all_estado">Seleccionar todos</label>
                                                </div>
                                                <div id="listar_filtro_estado" class="overflow-x-auto"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="filter-set-content">
                                        <div class="filter-set-content-head">
                                            <a href="#" class="collapsed d-block py-2 fw-bold border-bottom" data-bs-toggle="collapse" data-bs-target="#collapseotro" aria-expanded="false" aria-controls="collapseotro">
                                                Otros <i class="bi bi-chevron-down float-end"></i>
                                            </a>
                                        </div>

                                        <div class="filter-set-contents accordion-collapse collapse mt-2" id="collapseotro" data-bs-parent="#accordionExample">
                                            <div class="ps-3 border-start">
                                                <div class="filter-set-content mb-2">
                                                    <div class="filter-set-content-head">
                                                        <a href="#" class="collapsed text-muted" data-bs-toggle="collapse" data-bs-target="#collapseInteres">Interes</a>
                                                    </div>
                                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseInteres">
                                                        <div class="filter-content-list bg-light rounded border p-2 shadow-sm mt-2">
                                                            <div class="form-check mb-2 border-bottom pb-1">
                                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-interes" id="all_interes">
                                                                <label class="form-check-label fw-bold" for="all_interes">Seleccionar todos</label>
                                                            </div>
                                                            <div id="listar_filtro_interes" class="overflow-x-auto"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="filter-set-content mb-2">
                                                    <div class="filter-set-content-head">
                                                        <a href="#" class="collapsed text-muted" data-bs-toggle="collapse" data-bs-target="#collapseMedio">Medio</a>
                                                    </div>
                                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseMedio">
                                                        <div class="filter-content-list bg-light rounded border p-2 shadow-sm mt-2">
                                                            <div class="form-check mb-2 border-bottom pb-1">
                                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-medio" id="all_medio">
                                                                <label class="form-check-label fw-bold" for="all_medio">Seleccionar todos</label>
                                                            </div>
                                                            <div id="listar_filtro_medio" class="overflow-x-auto"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="filter-set-content mb-2">
                                                    <div class="filter-set-content-head">
                                                        <a href="#" class="collapsed text-muted" data-bs-toggle="collapse" data-bs-target="#collapseFuente">Fuente</a>
                                                    </div>
                                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseFuente">
                                                        <div class="filter-content-list bg-light rounded border p-2 shadow-sm mt-2">
                                                            <div class="form-check mb-2 border-bottom pb-1">
                                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-fuente" id="all_fuente">
                                                                <label class="form-check-label fw-bold" for="all_fuente">Seleccionar todos</label>
                                                            </div>
                                                            <div id="listar_filtro_fuente" class="overflow-x-auto"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="filter-set-content mb-2">
                                                    <div class="filter-set-content-head">
                                                        <a href="#" class="collapsed text-muted" data-bs-toggle="collapse" data-bs-target="#collapseCamp">Campaña</a>
                                                    </div>
                                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseCamp">
                                                        <div class="filter-content-list bg-light rounded border p-2 shadow-sm mt-2">
                                                            <div class="form-check mb-2 border-bottom pb-1">
                                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-campana" id="all_campana">
                                                                <label class="form-check-label fw-bold" for="all_campana">Seleccionar todos</label>
                                                            </div>
                                                            <div id="listar_filtro_campana" class="overflow-x-auto"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="filter-set-content mb-2">
                                                    <div class="filter-set-content-head">
                                                        <a href="#" class="collapsed text-muted" data-bs-toggle="collapse" data-bs-target="#collapseAcc">Acción</a>
                                                    </div>
                                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseAcc">
                                                        <div class="filter-content-list bg-light rounded border p-2 shadow-sm mt-2">
                                                            <div class="form-check mb-2 border-bottom pb-1">
                                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-accion" id="all_accion">
                                                                <label class="form-check-label fw-bold" for="all_accion">Seleccionar todos</label>
                                                            </div>
                                                            <div id="listar_filtro_accion" class="overflow-x-auto"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="filter-set-content mb-2">
                                                    <div class="filter-set-content-head">
                                                        <a href="#" class="collapsed text-muted" data-bs-toggle="collapse" data-bs-target="#collapseDep">Departamento</a>
                                                    </div>
                                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseDep">
                                                        <div class="filter-content-list bg-light rounded border p-2 shadow-sm mt-2">
                                                            <div class="form-check mb-2 border-bottom pb-1">
                                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-dep" id="all_dep">
                                                                <label class="form-check-label fw-bold" for="all_dep">Seleccionar todos</label>
                                                            </div>
                                                            <div id="listar_filtro_dep" class="overflow-x-auto"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="filter-set-content mb-2">
                                                    <div class="filter-set-content-head">
                                                        <a href="#" class="collapsed text-muted" data-bs-toggle="collapse" data-bs-target="#collapseCiduad">Ciudad</a>
                                                    </div>
                                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseCiduad">
                                                        <div class="filter-content-list bg-light rounded border p-2 shadow-sm mt-2">
                                                            <div class="form-check mb-2 border-bottom pb-1">
                                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-ciu" id="all_ciu">
                                                                <label class="form-check-label fw-bold" for="all_ciu">Seleccionar todos</label>
                                                            </div>
                                                            <div id="listar_filtro_ciudad" class="overflow-x-auto"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="filter-set-content mb-2">
                                                    <div class="filter-set-content-head">
                                                        <a href="#" class="collapsed text-muted" data-bs-toggle="collapse" data-bs-target="#collapseBrr">Barrio</a>
                                                    </div>
                                                    <div class="filter-set-contents accordion-collapse collapse" id="collapseBrr">
                                                        <div class="filter-content-list bg-light rounded border p-2 shadow-sm mt-2">
                                                            <div class="form-check mb-2 border-bottom pb-1">
                                                                <input class="form-check-input select-all-filter" type="checkbox" data-target=".filtro-brr" id="all_brr">
                                                                <label class="form-check-label fw-bold" for="all_brr">Seleccionar todos</label>
                                                            </div>
                                                            <div id="listar_filtro_brr" class="overflow-x-auto"></div>
                                                        </div>
                                                    </div>
                                                </div>

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
        <div class="card border-0 rounded-0">
            <div class="card-body">
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
                </style>
                <div class="table-responsive custom-table">
                    <table id="rst_reports" class="table table-bordered table-striped table-hover table-sm">

                        <thead class="text-center">

                            <tr>
                                <th>MEDIO</th>
                                <th>FUENTE</th>
                                <th>CAMPAÑA</th>
                                <th>NUEVO LEADS</th>
                                <th>PROSPECTO</th>
                                <th>LEADS ACTIVO</th>
                                <th>INTERESADO</th>
                                <th>EN DECISIÓN</th>
                                <th>MATRICULA EN PROCESO</th>
                                <th>MATRICULADO</th>
                                <th>APLAZADO</th>
                                <th>PERDIDO</th>
                                <th>TOTAL</th>
                            </tr>

                        </thead>

                        <tbody></tbody>

                        <tfoot>
                            <tr id="fila_totales"></tr>
                        </tfoot>

                    </table>
                    <div id="loaderFoco" class="loader-overlay d-none">
                        <div class="spinner"></div>
                        <p>Cargando reporte...</p>
                    </div>
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

                listarReporteFuente();
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

            let asesor = [...document.querySelectorAll(".filtro-asesor:checked")].map(c => c.value);
            let carreras = [...document.querySelectorAll(".filtro-carrera:checked")].map(c => c.value);
            let horario = [...document.querySelectorAll(".filtro-horario:checked")].map(c => c.value);
            let interes = [...document.querySelectorAll(".filtro-interes:checked")].map(c => c.value);
            let medio = [...document.querySelectorAll(".filtro-medio:checked")].map(c => c.value);
            let fuente = [...document.querySelectorAll(".filtro-fuente:checked")].map(c => c.value);
            let campana = [...document.querySelectorAll(".filtro-campana:checked")].map(c => c.value);
            let accion = [...document.querySelectorAll(".filtro-accion:checked")].map(c => c.value);
            let departamento = [...document.querySelectorAll(".filtro-dep:checked")].map(c => c.value);
            let ciudad = [...document.querySelectorAll(".filtro-ciu:checked")].map(c => c.value);
            let barrio = [...document.querySelectorAll(".filtro-brr:checked")].map(c => c.value);
            let estados = [...document.querySelectorAll(".filtro-estado:checked")].map(c => c.value);
            let fecha_inicio = window.fecha_inicio || "";
            let fecha_fin = window.fecha_fin || "";

            return {
                texto,
                asesor,
                carreras,
                horario,
                interes,
                medio,
                fuente,
                campana,
                accion,
                departamento,
                ciudad,
                barrio,
                estados,
                fecha_inicio,
                fecha_fin
            };
        }
    };

    function listarReporteFuente() {

        const f = Filtros.obtener();
        const params = new URLSearchParams();

        const loader = document.getElementById("loaderFoco");
        if (loader) loader.classList.remove("d-none");

        params.append("accion", "reporte_fuente_origen");

        if (f.texto !== "") params.append("texto", f.texto);
        if (f.asesor.length > 0) params.append("asesor", JSON.stringify(f.asesor));
        if (f.carreras.length > 0) params.append("carreras", JSON.stringify(f.carreras));
        if (f.horario.length > 0) params.append("horario", JSON.stringify(f.horario));
        if (f.interes.length > 0) params.append("interes", JSON.stringify(f.interes));
        if (f.medio.length > 0) params.append("medio", JSON.stringify(f.medio));
        if (f.fuente.length > 0) params.append("fuente", JSON.stringify(f.fuente));
        if (f.campana.length > 0) params.append("campana", JSON.stringify(f.campana));
        if (f.accion.length > 0) params.append("accion", JSON.stringify(f.accion));
        if (f.departamento.length > 0) params.append("departamento", JSON.stringify(f.departamento));
        if (f.ciudad.length > 0) params.append("ciudad", JSON.stringify(f.ciudad));
        if (f.barrio.length > 0) params.append("barrio", JSON.stringify(f.barrio));
        if (f.estados.length > 0) params.append("estados", JSON.stringify(f.estados));
        if (f.fecha_inicio !== "") params.append("fecha_inicio", f.fecha_inicio);
        if (f.fecha_fin !== "") params.append("fecha_fin", f.fecha_fin);

        fetch("ajax/ajax.php?" + params.toString())
            .then(res => res.json())
            .then(data => {

                let tbody = document.querySelector("#rst_reports tbody");
                let filaTotales = document.querySelector("#fila_totales");

                tbody.innerHTML = "";
                filaTotales.innerHTML = "";

                let totalNuevo = 0;
                let totalProspecto = 0;
                let totalActivo = 0;
                let totalInteresado = 0;
                let totalDecision = 0;
                let totalMatriculaProceso = 0;
                let totalMatriculado = 0;
                let totalAplazado = 0;
                let totalPerdido = 0;
                let totalGeneral = 0;

                data.forEach(row => {

                    totalNuevo += parseInt(row.nuevo_leads);
                    totalProspecto += parseInt(row.prospecto);
                    totalActivo += parseInt(row.leads_activo);
                    totalInteresado += parseInt(row.interesado);
                    totalDecision += parseInt(row.en_decision);
                    totalMatriculaProceso += parseInt(row.matricula_proceso);
                    totalMatriculado += parseInt(row.matriculado);
                    totalAplazado += parseInt(row.aplazado);
                    totalPerdido += parseInt(row.perdido);
                    totalGeneral += parseInt(row.total);

                    function pintar(valor) {
                        return valor > 0 ?
                            `<span style="color:red;font-weight:600">${valor}</span>` :
                            `<span class="text-muted">-</span>`;
                    }

                    let tr = `
                <tr class="text-center">

                    <td class="text-start fw-bold">${row.medio}</td>
                    <td>${row.fuente}</td>
                    <td>${row.campana}</td>

                    <td>${pintar(row.nuevo_leads)}</td>
                    <td>${pintar(row.prospecto)}</td>
                    <td>${pintar(row.leads_activo)}</td>
                    <td>${pintar(row.interesado)}</td>
                    <td>${pintar(row.en_decision)}</td>
                    <td>${pintar(row.matricula_proceso)}</td>
                    <td>${pintar(row.matriculado)}</td>
                    <td>${pintar(row.aplazado)}</td>
                    <td>${pintar(row.perdido)}</td>

                    <td style="color:red;font-weight:bold">${row.total}</td>

                </tr>
                `;

                    tbody.innerHTML += tr;

                });

                filaTotales.innerHTML = `

            <tr style="font-weight:bold;background:#f5f5f5;" class="text-center">

                <td colspan="3" class="text-start"><strong>TOTAL GENERAL</strong></td>

                <td style="color:red">${totalNuevo}</td>
                <td style="color:red">${totalProspecto}</td>
                <td style="color:red">${totalActivo}</td>
                <td style="color:red">${totalInteresado}</td>
                <td style="color:red">${totalDecision}</td>
                <td style="color:red">${totalMatriculaProceso}</td>
                <td style="color:red">${totalMatriculado}</td>
                <td style="color:red">${totalAplazado}</td>
                <td style="color:red">${totalPerdido}</td>
                <td style="background:#1e66dc;color:white;font-weight:bold">${totalGeneral}</td>

            </tr>
            `;

            })
            .catch(err => console.error("Error reporte rst:", err))
            .finally(() => {
                if (loader) loader.classList.add("d-none");
            });
    }

    document.addEventListener("change", function(e) {
        if (e.target.classList.contains("filtro") || e.target.classList.contains("select-all-filter")) {
            listarReporteFuente();
        }
    });

    document.addEventListener("input", function(e) {
        if (e.target.id === "buscador") {
            listarReporteFuente();
        }
    });

    listarReporteFuente();
</script>
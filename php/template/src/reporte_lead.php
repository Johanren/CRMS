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
                <a href="javascript:void(0);" onclick="exportarExcel('CRMS_lead')" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#####download_report"><i class="ti ti-file-download me-1"></i>Descargar Reporte</a>
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
                                                    <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseEstado" aria-expanded="false" aria-controls="collapseThree">Estado</a>
                                                </div>
                                                <div class="filter-set-contents accordion-collapse collapse" id="collapseEstado" data-bs-parent="#accordionExample">
                                                    <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
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
                    <div id="rst_reports"></div>
                    <div id="loaderFoco" class="loader-overlay d-none">
                        <div class="spinner"></div>
                        <p>Cargando reporte...</p>
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
    window.Filtros = {
        obtener: function() {
            let texto = "";
            let inputBuscador = document.getElementById("buscador");
            if (inputBuscador) {
                texto = inputBuscador.value.toLowerCase();
            }
            let asesor = [...document.querySelectorAll(".filtro-asesor:checked")].map(c => c.value);
            let estados = [...document.querySelectorAll(".filtro-estado:checked")].map(c => c.value);
            let carrera = [...document.querySelectorAll(".filtro-carrera:checked")].map(c => c.value);
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

    function listarReporteCRMS() {
        const f = Filtros.obtener();
        const params = new URLSearchParams();

        // 1. Mostrar el Loader
        const loader = document.getElementById("loaderFoco");
        if (loader) loader.classList.remove("d-none");

        params.append("accion", "reporte_CRMS_lead");

        if (f.texto !== "") params.append("texto", f.texto);
        if (f.asesor.length > 0) params.append("asesor", JSON.stringify(f.asesor));
        if (f.estados.length > 0) params.append("estados", JSON.stringify(f.estados));
        if (f.carrera.length > 0) params.append("carrera", JSON.stringify(f.carrera));
        if (f.fecha_inicio !== "") params.append("fecha_inicio", f.fecha_inicio);
        if (f.fecha_fin !== "") params.append("fecha_fin", f.fecha_fin);

        fetch("ajax/ajax.php?" + params.toString())
            .then(res => res.json())
            .then(data => {
                if (document.getElementById("rst_reports")) {
                    inicializarDataTableRst(data);
                }
            })
            .catch(err => console.error("Error reporte rst:", err))
            .finally(() => {
                // 2. Ocultar el Loader (se ejecuta siempre, falle o tenga éxito)
                if (loader) loader.classList.add("d-none");
            });
    }

    document.addEventListener("change", function(e) {
        if (e.target.classList.contains("filtro")) {
            listarReporteCRMS();
        }
    });

    document.addEventListener("input", function(e) {
        if (e.target.id === "buscador") {
            listarReporteCRMS();
        }
    });

    listarReporteCRMS();

    function inicializarDataTableRst(data) {
        const contenedor = document.getElementById("rst_reports");
        if (!data || data.length === 0) {
            contenedor.innerHTML = "<div class='alert alert-warning'>No hay datos disponibles</div>";
            return;
        }

        // 1. Extraer encabezados únicos y filas
        const horariosSet = new Set();
        const programasSet = new Set();

        data.forEach(item => {
            // UNIFICACIÓN: Si es null, vacío o "(En blanco)", lo movemos a "Por Confirmar"
            let h = item.horario;
            if (!h || h === "" || h.toLowerCase() === "(en blanco)") {
                h = "Por Confirmar";
            }

            const p = item.programa || "SIN PROGRAMA";
            horariosSet.add(h);
            programasSet.add(p);
        });

        const encabezadosHorarios = Array.from(horariosSet).sort();
        const programas = Array.from(programasSet).sort();

        // 2. Mapear los datos a la matriz
        const matriz = {};
        programas.forEach(p => {
            matriz[p] = {};
            encabezadosHorarios.forEach(h => matriz[p][h] = 0);
        });

        data.forEach(item => {
            const p = item.programa || "SIN PROGRAMA";
            // Aplicamos la misma lógica de unificación aquí
            let h = item.horario;
            if (!h || h === "" || h.toLowerCase() === "(en blanco)") {
                h = "Por Confirmar";
            }
            matriz[p][h] += parseInt(item.total_leads);
        });

        // 3. Construir el HTML con clases de centrado (text-center)
        let html = `<table class="table table-bordered table-striped table-sm">
        <thead class="thead-dark text-center">
            <tr>
                <th class="text-start">Programa / Horario</th>
                ${encabezadosHorarios.map(h => `<th>${h}</th>`).join('')}
                <th>Total General</th>
            </tr>
        </thead>
        <tbody>`;

        let totalColumnas = {};
        encabezadosHorarios.forEach(h => totalColumnas[h] = 0);
        let granTotal = 0;

        programas.forEach(p => {
            let totalFila = 0;
            // Nombre del programa alineado a la izquierda para mejor lectura
            html += `<tr><td class="text-start"><strong>${p}</strong></td>`;

            encabezadosHorarios.forEach(h => {
                const valor = matriz[p][h];
                // Centramos los números
                html += `<td class="text-center">${valor > 0 ? valor : ''}</td>`;
                totalFila += valor;
                totalColumnas[h] += valor;
            });

            html += `<td class="table-secondary text-center"><strong>${totalFila}</strong></td></tr>`;
            granTotal += totalFila;
        });

        // 4. Fila de Totales Generales centrado
        html += `</tbody>
        <tfoot class="table-dark text-center">
            <tr>
                <td class="text-start"><strong>Total general</strong></td>
                ${encabezadosHorarios.map(h => `<td><strong>${totalColumnas[h]}</strong></td>`).join('')}
                    <td><strong>${granTotal}</strong></td>
                </tr>
            </tfoot>
            </table>`;

        contenedor.innerHTML = html;
    }

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
</script>
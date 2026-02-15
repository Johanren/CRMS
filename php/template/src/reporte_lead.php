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
                <a href="javascript:void(0);" onclick="exportarExcel('CRMS_lead')" class="btn btn-primary"
                    data-bs-toggle="modal" data-bs-target="#####download_report"><i
                        class="ti ti-file-download me-1"></i>Descargar Reporte</a>
            </div>
            <div class="card-body">

                <!-- table header -->
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
            <div class="modal fade" id="modalMensajesFoco" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Enviar Mensajes - Resultado Foco</h5>
                            <!--<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>-->
                        </div>
                        <div class="modal-body">
                            <div class="card-body">

                                <form id="frm_rst" class="row g-3 mb-4">

                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Carrera</label>
                                        <div id="filtro_carrera" class="border rounded p-2 bg-white"
                                            style="max-height: 150px; overflow-y: auto;">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Horario</label>
                                        <div id="filtro_horario" class="border rounded p-2 bg-white"
                                            style="max-height: 150px; overflow-y: auto;">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Estado</label>
                                        <div id="filtro_estado" class="border rounded p-2 bg-white"
                                            style="max-height: 150px; overflow-y: auto;">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Asesor</label>
                                        <div id="filtro_asesor" class="border rounded p-2 bg-white"
                                            style="max-height: 150px; overflow-y: auto;">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Numero</label>
                                        <input type="text" id="filtro_numero" class="form-control">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Programar envio</label>
                                        <input type="datetime-local" id="programar" class="form-control">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">URL</label>
                                        <input type="text" id="url" class="form-control">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Tema</label>
                                        <select class="form-select" id="tema_mensaje">
                                            <option value="">Seleccione</option>
                                            <option value="pago">Facilidad Pago</option>
                                            <option value="ingreso">Ingreso</option>
                                            <option value="empo">Empoderar</option>
                                            <option value="bono">Bono</option>
                                            <option value="incentivo">Incentivo</option>
                                        </select>
                                    </div>


                                    <div class="col-md-12 mt-3 d-none" id="wrapper-opciones">
                                        <div class="card shadow-sm border-info">
                                            <div
                                                class="card-header bg-info text-white py-2 d-flex justify-content-between align-items-center">
                                                <small class="text-uppercase fw-bold">Varias variantes detectadas: Seleccione
                                                    una</small>
                                                <span class="badge bg-white text-info" id="contador-variantes">0</span>
                                            </div>
                                            <div class="card-body p-0">
                                                <div id="contenedor-opciones-mensaje" class="list-group list-group-flush custom-scroll"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="col-md-2">
                                    <button id="btn_guardar_mensajes" class="btn btn-success">
                                        Guardar mensajes
                                    </button>
                                </div>

                                <div class="table-responsive mt-5">
                                    <table id="tabla_leads" class="table table-striped table-bordered w-100">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Cliente</th>
                                                <th>Teléfono</th>
                                                <th>Asesor</th>
                                                <th>Mensaje</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <!-- /Contact List -->

                            </div>
                        </div>
                    </div>
                </div>
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
            let carrera = [...document.querySelectorAll(".filtro-carrera:checked")].map(c => c.value);

            // --- CAMBIO AQUÍ ---
            // Buscamos los seleccionados
            let estados = [...document.querySelectorAll(".filtro-estado:checked")].map(c => c.value);

            // Si no hay ninguno seleccionado (posible primera carga), forzamos los que necesitas
            if (estados.length === 0 && !window.filtrosInicializados) {
                estados = ['Nuevo Leads', 'Leads Activo', 'Interesado', 'En Decisión', 'Prospecto'];
            }
            // -------------------

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

    // Función para marcar visualmente los estados deseados
    function marcarEstadosDefault() {
        const estadosAMarcar = ['Nuevo Leads', 'Leads Activo', 'Interesado', 'En Decisión', 'Prospecto'];

        // Buscamos todos los checkboxes de estado
        const checkboxes = document.querySelectorAll('.filtro-estado');

        checkboxes.forEach(chk => {
            if (estadosAMarcar.includes(chk.value)) {
                chk.checked = true;
            }
        });
    }

    // Creamos un observador para detectar cuando se cargue la lista en el div
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length) {
                marcarEstadosDefault();
            }
        });
    });

    // Empezamos a observar el contenedor de los estados
    const targetNode = document.getElementById('listar_filtro_estado');
    if (targetNode) {
        observer.observe(targetNode, {
            childList: true
        });
    }

    listarReporteCRMS();

    function inicializarDataTableRst(data) {
        const contenedor = document.getElementById("rst_reports");
        if (!data || data.length === 0) {
            contenedor.innerHTML = "<div class='alert alert-warning'>No hay datos disponibles</div>";
            return;
        }

        const programasSet = new Set();
        const mapaHorarios = {};

        // 1. Procesar data y UNIFICAR categorías "vagas"
        data.forEach(item => {
            let idH = item.id_horario;
            let nombreH = item.horario ? item.horario.toUpperCase() : "";

            // Lógica de unificación: Por Confirmar, Sin Identificar, null o En blanco
            if (!idH || !nombreH ||
                nombreH === "POR CONFIRMAR" ||
                nombreH === "SIN IDENTIFICAR" ||
                nombreH === "(EN BLANCO)") {

                idH = "99"; // ID alto para ordenamiento
                nombreH = "POR CONFIRMAR";
            }

            mapaHorarios[idH] = nombreH;
            programasSet.add(item.programa || "SIN PROGRAMA");
        });

        // Ordenar IDs y preparar encabezados
        const idsOrdenados = Object.keys(mapaHorarios).sort((a, b) => parseInt(a) - parseInt(b));
        const encabezadosHorarios = idsOrdenados.map(id => mapaHorarios[id]);
        const programas = Array.from(programasSet).sort();

        // 2. Crear Matriz de conteo
        const matriz = {};
        programas.forEach(p => {
            matriz[p] = {};
            encabezadosHorarios.forEach(h => matriz[p][h] = 0);
        });

        data.forEach(item => {
            const p = item.programa || "SIN PROGRAMA";
            let nombreH = item.horario ? item.horario.toUpperCase() : "";

            if (!item.id_horario || !nombreH ||
                nombreH === "POR CONFIRMAR" ||
                nombreH === "SIN IDENTIFICAR" ||
                nombreH === "(EN BLANCO)") {
                nombreH = "POR CONFIRMAR";
            }

            matriz[p][nombreH] += parseInt(item.total_leads);
        });

        // 3. Generar HTML de la Tabla
        let html = `
        <style>
            #rst_reports .table td { padding: 5px 10px !important; vertical-align: middle; }
            #rst_reports .table th { padding: 8px 10px !important; text-transform: uppercase; font-size: 0.75rem; }
            .bg-total-fila { background-color: #f1f1f1; font-weight: bold; }
            .cursor-pointer { cursor: pointer; transition: background 0.2s; }
            .cursor-pointer:hover { background-color: rgba(13, 110, 253, 0.1) !important; }
        </style>
        <table id="tabla_resumen_rst" class="table table-bordered table-striped table-hover table-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th class="text-start">Programa / Horario</th>
                    ${encabezadosHorarios.map(h => `<th>${h}</th>`).join('')}
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>`;

        let totalColumnas = {};
        encabezadosHorarios.forEach(h => totalColumnas[h] = 0);
        let granTotal = 0;

        programas.forEach(p => {
            let totalFila = 0;
            html += `<tr><td class="text-start"><strong>${p}</strong></td>`;

            encabezadosHorarios.forEach(h => {
                const valor = matriz[p][h];

                if (valor > 0) {
                    // AQUÍ SE ASIGNAN LOS DATOS CORRECTAMENTE USANDO p y h
                    html += `
                    <td class="abrir-mensajes-foco text-center text-primary fw-bold cursor-pointer"
                        data-programa="${p}" 
                        data-jornada="${h}">
                        ${valor}
                    </td>`;
                } else {
                    html += `<td class="text-center text-muted">-</td>`;
                }

                totalFila += valor;
                totalColumnas[h] += valor;
            });

            // Total de la fila (cliqueable para ver todo el programa)
            html += `
            <td class="text-center bg-total-fila text-primary fw-bold cursor-pointer" 
                data-programa="${p}" 
                data-jornada="TODOS">
                ${totalFila}
            </td>
        </tr>`;

            granTotal += totalFila;
        });

        // 4. Totales finales (Footer)
        html += `</tbody>
        <tfoot class="table-dark text-center text-white text-black">
            <tr>
                <td class="text-start"><strong>TOTAL GENERAL</strong></td>
                ${encabezadosHorarios.map(h => `<td><strong>${totalColumnas[h]}</strong></td>`).join('')}
                <td style="background-color: #0d6efd;"><strong>${granTotal}</strong></td>
            </tr>
        </tfoot>
    </table>`;

        contenedor.innerHTML = html;

        // 5. Inicializar el evento Click para las celdas
        asignarEventosCeldas();
    }

    /**
     * Función para manejar los clics en las celdas de la matriz
     */
    function asignarEventosCeldas() {
        const celdas = document.querySelectorAll('.abrir-mensajes-foco, .bg-total-fila');
        celdas.forEach(celda => {
            celda.onclick = function() {
                const programa = this.getAttribute('data-programa');
                const jornada = this.getAttribute('data-jornada');

                console.log(`Filtrando leads de: ${programa} - Jornada: ${jornada}`);

                // Aquí llamas a tu función que abre el modal o filtra la tabla de leads
                // Ejemplo: mostrarModalLeads(programa, jornada);
            };
        });
    }

    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("abrir-mensajes-foco")) {

            const programa = e.target.dataset.programa;
            const jornada = e.target.dataset.jornada;

            const modal = new bootstrap.Modal(document.getElementById('modalMensajesFoco'));
            modal.show();

            // Esperamos a que el modal esté visible
            setTimeout(() => {
                abrirModuloMensajesDesdeFoco(programa, jornada);
            }, 300);
        }
    });

    async function abrirModuloMensajesDesdeFoco(programaNombre, jornadaNombre) {

        await cargarMensajesPorTema();
        await cargarFiltrosRST(); // Ahora sí espera correctamente

        const checksCarrera = document.querySelectorAll('#filtro_carrera input[type="checkbox"]');

        checksCarrera.forEach(cb => {
            const label = cb.nextElementSibling?.textContent?.trim();
            cb.checked = (label === programaNombre.toUpperCase());
        });

        const checksJornada = document.querySelectorAll('#filtro_horario input[type="checkbox"]');

        checksJornada.forEach(cb => {
            const label = cb.nextElementSibling?.textContent?.trim();
            cb.checked = (label === jornadaNombre.toUpperCase());
        });

        ['filtro_estado', 'filtro_asesor'].forEach(id => {
            document.querySelectorAll(`#${id} input`).forEach(cb => cb.checked = false);
        });

        validarYCargarTabla();
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

    let tablaLeads = null;

    /* ===========================
       VARIABLES GLOBALES
    =========================== */
    const foco = 55;
    let mensajesPorTema = {}; // Ahora almacenará arrays de mensajes

    /* ===========================
       CARGAR MENSAJES POR TEMA (SOPORTA MÚLTIPLES)
    =========================== */
    function cargarMensajesPorTema() {
        const datos = new FormData();
        datos.append('accion', 'listar_mensajes_parametrizados');

        return fetch('ajax/ajax.php', {
                method: 'POST',
                body: datos
            })
            .then(res => res.json())
            .then(data => {
                mensajesPorTema = {};
                data.forEach(item => {
                    // Si el tipo no existe en nuestro objeto, lo inicializamos como array
                    if (!mensajesPorTema[item.tipo]) {
                        mensajesPorTema[item.tipo] = [];
                    }
                    mensajesPorTema[item.tipo].push(item.mensaje);
                });
                return true;
            })
            .catch(() => false);
    }

    /* ===========================
       DOM READY
    =========================== */
    document.addEventListener('DOMContentLoaded', async () => {
        await cargarMensajesPorTema();
        cargarFiltrosRST();

        ['filtro_carrera', 'filtro_horario', 'filtro_estado', 'filtro_asesor']
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', validarYCargarTabla);
        });

        document.getElementById('filtro_numero')?.addEventListener('input', validarYCargarTabla);
        document.getElementById('tema_mensaje')?.addEventListener('change', generarMensajesPorTema);
        document.getElementById('btn_guardar_mensajes')?.addEventListener('click', guardarMensajes);
    });

    /* ===========================
       LÓGICA DE FILTROS Y TABLA
    =========================== */
    function cargarFiltrosRST() {
        const datos = new FormData();
        datos.append('accion', 'catalogo_filtros_mensaje');

        return fetch('ajax/ajax.php', { // <-- IMPORTANTE: return
                method: 'POST',
                body: datos
            })
            .then(res => res.json())
            .then(data => {
                llenarSelect('filtro_carrera', data.carreras, 'id_programa', 'programa');
                llenarSelect('filtro_horario', data.horarios, 'id_jornada', 'jornada');
                llenarSelect('filtro_estado', data.estados, 'id_estado', 'estado');
                llenarSelect('filtro_asesor', data.asesores, 'id_asesor', 'asesor');
            });
    }

    /* ===========================
        AJUSTE: GENERAR CHECKBOXES EN LUGAR DE SELECTS
    =========================== */
    let urlsAsesores = {}; // Objeto global

    function llenarSelect(id, datos, valueKey, textKey) {
        const contenedor = document.getElementById(id);
        if (!contenedor) return;
        contenedor.innerHTML = '';

        datos.forEach(d => {
            // Si el catálogo es de asesores, guardamos su URL asociada al ID
            if (id === 'filtro_asesor') {
                urlsAsesores[d.id_asesor] = d.url || '';
            }

            const div = document.createElement('div');
            div.className = 'form-check small';
            const input = document.createElement('input');
            input.type = 'checkbox';
            input.className = 'form-check-input filtro-check';
            input.value = d[valueKey];
            input.id = `chk_${id}_${d[valueKey]}`;
            // Por esto:
            input.addEventListener('change', () => {
                if (id === 'filtro_asesor') {
                    actualizarUrlPorAsesor(); // <--- Esto es lo que te falta llamar
                }
                validarYCargarTabla();
            });

            const label = document.createElement('label');
            label.className = 'form-check-label';
            label.htmlFor = input.id;
            label.textContent = d[textKey] ? d[textKey].toUpperCase() : '';

            div.appendChild(input);
            div.appendChild(label);
            contenedor.appendChild(div);
        });
    }

    function actualizarUrlPorAsesor() {
        const asesoresSeleccionados = getValoresSelect('filtro_asesor');
        const inputUrl = document.getElementById('url');

        // Si solo hay UN asesor, le ayudamos al usuario poniendo su URL en el cuadro
        if (asesoresSeleccionados.length === 1) {
            const idAsesor = asesoresSeleccionados[0];
            if (urlsAsesores[idAsesor]) {
                inputUrl.value = urlsAsesores[idAsesor];
            }
        }
        // Si hay más de uno, NO borramos el input, permitimos que el usuario decida 
        // si quiere escribir una URL global o dejarlo vacío para que use las de cada asesor.
    }

    /* ===========================
        AJUSTE: LEER VALORES DE CHECKBOXES
    =========================== */
    function getValoresSelect(id) {
        const contenedor = document.getElementById(id);
        if (!contenedor) return [];

        // Buscamos solo los inputs tipo checkbox que estén marcados
        const seleccionados = contenedor.querySelectorAll('input[type="checkbox"]:checked');
        return Array.from(seleccionados).map(cb => cb.value);
    }

    /* ===========================
        AJUSTE: VALIDACIÓN Y BLOQUEO
    =========================== */
    function validarYCargarTabla() {
        const numero = document.getElementById('filtro_numero')?.value.trim();
        const filtrosIds = ['filtro_carrera', 'filtro_horario', 'filtro_estado', 'filtro_asesor'];

        if (numero) {
            // Si hay número, deshabilitamos todos los checkboxes
            filtrosIds.forEach(id => {
                document.querySelectorAll(`#${id} input`).forEach(cb => {
                    cb.disabled = true;
                    cb.checked = false;
                });
            });
            cargarTablaLeads();
            return;
        }

        // Habilitar checkboxes si no hay número
        filtrosIds.forEach(id => {
            document.querySelectorAll(`#${id} input`).forEach(cb => cb.disabled = false);
        });

        // Validar que al menos haya un check en cada grupo (si así lo requiere tu lógica)
        // O simplemente cargar si hay algún filtro activo.
        const algunFiltroActivo = filtrosIds.some(id => getValoresSelect(id).length > 0);

        if (algunFiltroActivo || numero) {
            cargarTablaLeads();
        } else {
            limpiarTabla('Seleccione filtros para cargar la lista');
        }
    }

    function cargarTablaLeads() {
        const datos = new FormData();
        datos.append('accion', 'listar_leads_filtrados');
        const numero = document.getElementById('filtro_numero')?.value.trim();

        if (numero) {
            datos.append('numero', numero);
        } else {
            ['filtro_carrera', 'filtro_horario', 'filtro_estado', 'filtro_asesor']
            .forEach(id => getValoresSelect(id).forEach(v => datos.append(id + '[]', v)));
        }

        fetch('ajax/ajax.php', {
                method: 'POST',
                body: datos
            })
            .then(res => res.json())
            .then(pintarTabla)
            .catch(() => limpiarTabla('Error al cargar datos'));
    }

    function pintarTabla(leads) {
        const tbody = document.querySelector('#tabla_leads tbody');
        if (tablaLeads) {
            tablaLeads.destroy();
            tablaLeads = null;
        }
        tbody.innerHTML = '';

        if (!leads || !leads.length) {
            limpiarTabla('No hay resultados');
            return;
        }

        leads.forEach(l => {
            const tr = document.createElement('tr');
            tr.dataset.cliente = l.cliente;
            tr.dataset.asesor = l.asesor;
            tr.dataset.id_asesor = l.id_asesor || ''; // <--- GUARDAMOS EL ID AQUÍ
            tr.dataset.carrera = (l.programa || l.carrera || '').toUpperCase();
            tr.dataset.jornada = (l.jornada || '').toUpperCase();
            tr.dataset.mensaje = '';

            tr.innerHTML = `
            <td>${l.id_lead}</td>
            <td>${l.cliente.split(' ')[0]}</td>
            <td>${l.numero}</td>
            <td>${l.asesor}</td>
            <td class="mensaje-col text-muted italic">Seleccione un tema</td>
        `;
            tbody.appendChild(tr);
        });
        iniciarDataTable();
    }

    function iniciarDataTable() {
        tablaLeads = $('#tabla_leads').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: "Buscar:",
                zeroRecords: "No hay resultados",
                info: "Mostrando _START_ a _END_ de _TOTAL_",
                paginate: {
                    next: "Siguiente",
                    previous: "Anterior"
                }
            }
        });
    }

    function limpiarTabla(msg) {
        if (tablaLeads) {
            tablaLeads.destroy();
            tablaLeads = null;
        }
        document.querySelector('#tabla_leads tbody').innerHTML =
            `<tr><td colspan="5" class="text-center text-muted">${msg}</td></tr>`;
    }

    /* ===========================
       GESTIÓN DE MENSAJES (MODAL O DIRECTO)
    =========================== */
    function generarMensajesPorTema() {
        const tema = document.getElementById('tema_mensaje')?.value;
        const contenedor = document.getElementById('contenedor-opciones-mensaje');
        const wrapper = document.getElementById('wrapper-opciones');
        const contador = document.getElementById('contador-variantes');

        // Si no hay tema seleccionado, ocultamos todo y salimos
        if (!tema || !mensajesPorTema[tema]) {
            wrapper.classList.add('d-none');
            contenedor.innerHTML = '';
            return;
        }

        const opciones = mensajesPorTema[tema];
        contenedor.innerHTML = '';

        if (opciones.length > 1) {
            // MOSTRAR PANEL: Hay 2 o más mensajes
            wrapper.classList.remove('d-none');
            contador.textContent = `${opciones.length} variantes`;

            opciones.forEach((msg, index) => {
                const btn = document.createElement('button');
                btn.type = "button";
                btn.className =
                    "list-group-item list-group-item-action opcion-mensaje d-flex align-items-start gap-3 py-3";

                btn.innerHTML = `
                <div class="badge rounded-pill bg-info mt-1">${index + 1}</div>
                <div class="flex-grow-1">
                    <p class="mb-0 text-dark" style="font-size: 0.88rem; line-height: 1.5;">${msg}</p>
                </div>
            `;

                btn.onclick = function() {
                    document.querySelectorAll('.opcion-mensaje').forEach(el => el.classList.remove(
                        'active-selection'));
                    btn.classList.add('active-selection');
                    aplicarMensajeALaTabla(msg);
                };

                contenedor.appendChild(btn);
            });
        } else {
            // OCULTAR Y PROCESAR: Solo hay 1 mensaje (o ninguno extrañamente)
            wrapper.classList.add('d-none');
            if (opciones.length === 1) {
                aplicarMensajeALaTabla(opciones[0]);
            }
        }
    }

    function aplicarMensajeALaTabla(plantilla) {
        // Leemos lo que haya en el input manual
        const urlManual = document.getElementById('url')?.value.trim();

        tablaLeads.rows().every(function() {
            const tr = this.node();
            const idAsesorFila = tr.dataset.id_asesor; // ID del asesor de este cliente específico
            const nombreCliente = tr.dataset.cliente.split(' ')[0];

            /* LÓGICA DE PRIORIDAD:
               1. Si el usuario escribió algo manualmente en el campo URL, usamos eso para todos.
               2. Si el campo URL está vacío, buscamos la URL específica de este asesor en nuestro objeto global.
            */
            const urlFinal = (urlManual !== '') ?
                urlManual :
                (urlsAsesores[idAsesorFila] || '');

            const mensajeFinal = plantilla
                .replace(/{{cliente}}/g, nombreCliente)
                .replace(/{{asesor}}/g, tr.dataset.asesor)
                .replace(/{{carrera}}/g, tr.dataset.carrera)
                .replace(/{{jornada}}/g, tr.dataset.jornada)
                .replace(/{{url}}/g, urlFinal)
                .replace(/{{foco}}/g, foco);

            tr.dataset.mensaje = mensajeFinal;
            const col = tr.querySelector('.mensaje-col');
            if (col) {
                col.textContent = mensajeFinal;
                col.classList.remove('text-muted');
            }
        });
    }

    /* ===========================
       GUARDAR / ENVIAR
    =========================== */
    function guardarMensajes() {
        if (!tablaLeads) return alert('No hay datos en la tabla');

        const mensajes = [];
        tablaLeads.rows().every(function() {
            const tr = this.node();
            if (tr.dataset.mensaje && tr.dataset.mensaje !== '') {
                mensajes.push({
                    id_lead: tr.children[0].textContent,
                    numero: tr.children[2].textContent,
                    mensaje: tr.dataset.mensaje,
                    cliente: tr.dataset.cliente,
                    asesor: tr.dataset.asesor
                });
            }
        });

        if (!mensajes.length) return alert('Primero seleccione un tema para generar los mensajes');

        const datos = new FormData();
        datos.append('accion', 'guardar_mensajes_rst');
        datos.append('mensajes', JSON.stringify(mensajes));

        fetch('ajax/ajax.php', {
                method: 'POST',
                body: datos
            })
            .then(res => res.json())
            .then(r => alert(r.ok ? '✔ Mensajes guardados correctamente' : '❌ Error al guardar'))
            .catch(() => alert('❌ Error de conexión con el servidor'));
    }
</script>
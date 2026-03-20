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
                        <div id="contenedorLeadsFoco" class="mt-4 d-none">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold">
                                        Leads filtrados por Programa y Jornada
                                    </h6>

                                    <button id="btnAbrirModalMensajes" class="btn btn-primary btn-sm">
                                        Enviar mensajes
                                    </button>
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
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
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
                                    <label class="form-label fw-bold">Jornada</label>
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

        <!-- end row -->

    </div>
    <!-- End Content -->

    <?php require_once '../partials/footer.php'; ?>

</div>

<script>
    let tablaLeads = null;

    /* ===========================
       VARIABLES GLOBALES
    =========================== */
    const foco = <?php session_start();
                    echo json_encode($_SESSION['foco'] ?? '55'); ?>;
    let mensajesPorTema = {};
    let urlsAsesores = {}; // Objeto para mapear ID -> {url, nombre}

    /* ===========================
       DOM READY
    =========================== */
    document.addEventListener('DOMContentLoaded', async () => {
        await cargarMensajesPorTema();
        cargarFiltrosRST();

        // Eventos para filtros
        ['filtro_carrera', 'filtro_horario', 'filtro_estado', 'filtro_asesor']
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', validarYCargarTabla);
        });

        document.getElementById('filtro_numero')?.addEventListener('input', validarYCargarTabla);
        document.getElementById('tema_mensaje')?.addEventListener('change', generarMensajesPorTema);
        document.getElementById('btn_guardar_mensajes')?.addEventListener('click', guardarMensajes);

        // Evento para actualizar mensajes si el usuario cambia la URL manualmente
        document.getElementById('url')?.addEventListener('input', () => {
            const btnActivo = document.querySelector('.opcion-mensaje.active-selection p');
            if (btnActivo) aplicarMensajeALaTabla(btnActivo.textContent);
        });
    });

    /* ===========================
       CARGA DE DATOS (AJAX)
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
                    if (!mensajesPorTema[item.tipo]) mensajesPorTema[item.tipo] = [];
                    mensajesPorTema[item.tipo].push(item.mensaje);
                });
                return true;
            })
            .catch(() => false);
    }

    function cargarFiltrosRST() {
        const datos = new FormData();
        datos.append('accion', 'catalogo_filtros_mensaje');

        // Agregamos el "return" al inicio
        return fetch('ajax/ajax.php', {
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

    function llenarSelect(id, datos, valueKey, textKey) {
        const contenedor = document.getElementById(id);
        if (!contenedor) return;
        contenedor.innerHTML = '';

        datos.forEach(d => {
            // Guardar datos de asesores en el diccionario global
            if (id === 'filtro_asesor') {
                urlsAsesores[d.id_asesor] = {
                    url: d.url || '',
                    nombre: (d.asesor || '').toUpperCase()
                };
            }

            const div = document.createElement('div');
            div.className = 'form-check small';
            const input = document.createElement('input');
            input.type = 'checkbox';
            input.className = 'form-check-input filtro-check';
            input.value = d[valueKey];
            input.id = `chk_${id}_${d[valueKey]}`;

            input.addEventListener('change', () => {
                if (id === 'filtro_asesor') actualizarUrlPorAsesor();
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

    /* ===========================
       LÓGICA DE NEGOCIO (URLS Y TABLA)
    =========================== */
    function actualizarUrlPorAsesor() {
        const seleccionados = getValoresSelect('filtro_asesor');
        const inputUrl = document.getElementById('url');

        // Si solo hay un asesor, ponemos su URL en el input para edición
        if (seleccionados.length === 1) {
            const id = seleccionados[0];
            inputUrl.value = urlsAsesores[id] ? urlsAsesores[id].url : '';
        }
    }

    function getValoresSelect(id) {
        const contenedor = document.getElementById(id);
        if (!contenedor) return [];
        const seleccionados = contenedor.querySelectorAll('input[type="checkbox"]:checked');
        return Array.from(seleccionados).map(cb => cb.value);
    }

    function validarYCargarTabla() {
        const numero = document.getElementById('filtro_numero')?.value.trim();
        const filtrosIds = ['filtro_carrera', 'filtro_horario', 'filtro_estado', 'filtro_asesor'];

        if (numero) {
            filtrosIds.forEach(id => {
                document.querySelectorAll(`#${id} input`).forEach(cb => {
                    cb.disabled = true;
                    cb.checked = false;
                });
            });
            cargarTablaLeads();
            return;
        }

        filtrosIds.forEach(id => {
            document.querySelectorAll(`#${id} input`).forEach(cb => cb.disabled = false);
        });

        const algunFiltro = filtrosIds.some(id => getValoresSelect(id).length > 0);
        if (algunFiltro) cargarTablaLeads();
        else limpiarTabla('Seleccione filtros para cargar la lista');
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

            // BUSCAR ID DEL ASESOR para vincular la URL correcta
            const idAsesor = Object.keys(urlsAsesores).find(key =>
                urlsAsesores[key].nombre === (l.asesor || '').toUpperCase()
            );

            tr.dataset.cliente = l.cliente;
            tr.dataset.asesor = l.asesor;
            tr.dataset.id_asesor = idAsesor || '';
            tr.dataset.carrera = (l.carrera || '').toUpperCase();
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
                zeroRecords: "No hay resultados"
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
       GESTIÓN DE MENSAJES
    =========================== */
    function generarMensajesPorTema() {
        const tema = document.getElementById('tema_mensaje')?.value;
        const contenedor = document.getElementById('contenedor-opciones-mensaje');
        const wrapper = document.getElementById('wrapper-opciones');
        const contador = document.getElementById('contador-variantes');

        if (!tema || !mensajesPorTema[tema]) {
            wrapper.classList.add('d-none');
            contenedor.innerHTML = '';
            return;
        }

        const opciones = mensajesPorTema[tema];
        contenedor.innerHTML = '';

        if (opciones.length > 1) {
            wrapper.classList.remove('d-none');
            contador.textContent = `${opciones.length} variantes`;

            opciones.forEach((msg, index) => {
                const btn = document.createElement('button');
                btn.type = "button";
                btn.className = "list-group-item list-group-item-action opcion-mensaje d-flex align-items-start gap-3 py-3";
                btn.innerHTML = `
                    <div class="badge rounded-pill bg-info mt-1">${index + 1}</div>
                    <div class="flex-grow-1">
                        <p class="mb-0 text-dark" style="font-size: 0.88rem;">${msg}</p>
                    </div>`;
                btn.onclick = function() {
                    document.querySelectorAll('.opcion-mensaje').forEach(el => el.classList.remove('active-selection', 'bg-light'));
                    btn.classList.add('active-selection', 'bg-light');
                    aplicarMensajeALaTabla(msg);
                };
                contenedor.appendChild(btn);
            });
        } else if (opciones.length === 1) {
            wrapper.classList.add('d-none');
            aplicarMensajeALaTabla(opciones[0]);
        }
    }

    function aplicarMensajeALaTabla(plantilla) {
        const asesoresSeleccionados = getValoresSelect('filtro_asesor');
        const cantidadAsesores = asesoresSeleccionados.length;
        const urlManual = document.getElementById('url')?.value.trim();

        tablaLeads.rows().every(function() {
            const tr = this.node();
            const idAsesorFila = tr.dataset.id_asesor;
            const nombreCliente = tr.dataset.cliente.split(' ')[0];

            // LOGICA REQUERIDA:
            // Si hay 1 asesor: Prioridad al input manual.
            // Si hay varios: Prioridad a la URL individual del catálogo.
            let urlFinal = '';
            if (cantidadAsesores === 1) {
                urlFinal = urlManual;
            } else {
                urlFinal = (urlsAsesores[idAsesorFila] ? urlsAsesores[idAsesorFila].url : urlManual) || '';
            }

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

    function guardarMensajes() {
        if (!tablaLeads) return alert('No hay datos en la tabla');

        const mensajes = [];
        tablaLeads.rows().every(function() {
            const tr = this.node();
            if (tr.dataset.mensaje) {
                mensajes.push({
                    id_lead: tr.children[0].textContent,
                    numero: tr.children[2].textContent,
                    mensaje: tr.dataset.mensaje,
                    cliente: tr.dataset.cliente,
                    asesor: tr.dataset.asesor
                });
            }
        });

        if (!mensajes.length) return alert('Seleccione un tema primero');

        const datos = new FormData();
        datos.append('accion', 'guardar_mensajes_rst');
        datos.append('mensajes', JSON.stringify(mensajes));

        fetch('ajax/ajax.php', {
                method: 'POST',
                body: datos
            })
            .then(res => res.json())
            .then(r => alert(r.ok ? '✔ Mensajes guardados' : '❌ Error'))
            .catch(() => alert('❌ Error de conexión'));
    }
</script>

<!-- ========================
        End Page Content
    ========================= -->

<?php
$content = ob_get_clean();

require_once '../partials/main.php'; ?>
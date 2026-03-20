window.Filtros = {
    obtener: function () {
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

        return { texto, asesor, carreras, horario, interes, medio, fuente, campana, accion, departamento, ciudad, barrio, estados, fecha_inicio, fecha_fin };
    }
};

function listarReporteRstFrm() {

    const f = Filtros.obtener();
    const params = new URLSearchParams();

    params.append("accion", "reporte_rst_frm");

    if (f.texto !== "") params.append("texto", f.texto);
    if (f.asesor.length > 0) params.append("asesor", JSON.stringify(f.asesor));

    fetch("ajax/ajax.php?" + params.toString())
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("rst_reports")) {
                inicializarDataTableRst(data);
            }
        })
        .catch(err => console.error("Error reporte rst:", err));
}

function inicializarDataTableRst(data) {
    const tableId = '#rst_reports';

    if (!Array.isArray(data)) {
        console.warn("Datos inválidos para DataTable");
        data = [];
    }

    // 1. Limpiar rastro de clones previos antes de destruir
    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().destroy();
        // Eliminamos la fila de filtros clonada para evitar duplicados
        $(tableId + ' thead tr:eq(1)').remove();
    }

    const columnas = [
        { data: "fecha", title: "Fecha" },
        { data: "cliente_nombre", title: "Cliente" },
        { data: "cliente_telefono", title: "Teléfono" },
        { data: "asesor_nombre", title: "Asesor RST" },
        { data: "tipo_nom", title: "Tipo Transferencia" },
        { data: "obs_rst", title: "Observación" },
        { data: "asesor_nombre_lead", title: "Asesor" },
        { data: "estado_leads", title: "Estado" },
        { data: "nota", title: "Notas" }
    ];

    const table = $(tableId).DataTable({
        data: data,
        columns: columnas,
        ordering: true,
        orderCellsTop: true, // 🔹 Importante para que el sorting no se rompa con filtros
        fixedHeader: true,
        autoWidth: false,
        responsive: true,
        pageLength: 10,
        dom: '<"datatable-top"lf>rt<"datatable-bottom"ip>',
        language: {
            search: '',
            searchPlaceholder: "Buscar...",
            lengthMenu: "Mostrar _MENU_",
            info: "_START_ - _END_ de _TOTAL_ registros",
            paginate: {
                next: '<i class="ti ti-chevron-right"></i>',
                previous: '<i class="ti ti-chevron-left"></i>'
            },
            emptyTable: "No hay registros para mostrar"
        },

        initComplete: function () {
            const api = this.api();

            // 2. Clonar solo si no existe ya la fila de filtros
            if ($(tableId + ' thead tr').length === 1) {
                $(tableId + ' thead tr').clone(true).appendTo(tableId + ' thead');
            }

            $(tableId + ' thead tr:eq(1) th').each(function (i) {
                $(this).html(
                    `<input type="text"
                        class="form-control form-control-sm"
                        placeholder="Filtrar..."
                        style="width: 100%;"
                    />`
                );

                $('input', this).on('keyup change clear', function () {
                    if (api.column(i).search() !== this.value) {
                        api.column(i).search(this.value).draw();
                    }
                });
            });
        }
    });

    // Mover controles (asegúrate de que estos contenedores existan en tu HTML)
    $('.datatable-length').html($(tableId + '_length'));
    $('.datatable-paginate').html($(tableId + '_paginate'));
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


function getUnique(arr, key) {
    return [...new Set(arr.map(i => i[key]))]
}

function getUniqueBy(arr, key) {
    return [...new Set(arr.map(i => i[key]).filter(v => v !== null))];
}

function construirTablaDias(data) {
    const loader = document.getElementById("loaderFoco");

    try {
        loader.classList.remove("d-none");

        const mesesMap = {
            1: 'Enero', 2: 'Febrero', 3: 'Marzo', 4: 'Abril',
            5: 'Mayo', 6: 'Junio', 7: 'Julio', 8: 'Agosto',
            9: 'Septiembre', 10: 'Octubre', 11: 'Noviembre', 12: 'Diciembre'
        };

        const asesores = [...new Set(data.map(d => d.asesor))];
        const rts = data[0]?.asesorRTS ?? '';

        // 👉 Agrupar datos por mes
        const datosPorMes = {};
        data.forEach(r => {
            if (!datosPorMes[r.mes]) datosPorMes[r.mes] = [];
            datosPorMes[r.mes].push(r);
        });

        // 👉 Ordenar meses
        const mesesOrdenados = Object.keys(datosPorMes)
            .map(Number)
            .sort((a, b) => a - b);

        let html = `
        <div class="table-responsive-excel">
        <table class="table-excel">
        <thead>
            <tr>
                <th rowspan="2">DÍA</th>
                <th rowspan="2">RTS ASIGNADO</th>`;

        asesores.forEach(a => {
            html += `<th colspan="2">${a}</th>`;
        });

        html += `<th rowspan="2">Total</th></tr><tr>`;

        asesores.forEach(() => {
            html += `<th>Llamada</th><th>WhatsApp</th>`;
        });

        html += `</tr></thead><tbody>`;

        let totalesAsesor = {};
        asesores.forEach(a => totalesAsesor[a] = { Llamada: 0, WhatsApp: 0 });

        let totalMesGeneral = 0;

        // 👉 Recorrer meses ordenados
        mesesOrdenados.forEach(mes => {
            const mesNombre = mesesMap[mes];
            const registrosMes = datosPorMes[mes];

            // Obtener días únicos del mes
            const dias = [...new Set(registrosMes.map(r => r.dia))]
                .sort((a, b) => a - b);

            // 👉 Fila separadora del mes
            html += `
                <tr class="table-mes">
                    <td colspan="${asesores.length * 2 + 3}">
                        <b>${mesNombre.toUpperCase()}</b>
                    </td>
                </tr>`;

            dias.forEach(dia => {
                let totalDia = 0;

                html += `<tr><td>${dia} - ${mesNombre}</td><td>${rts}</td>`;

                asesores.forEach(asesor => {
                    let llamada = 0;
                    let whatsapp = 0;

                    registrosMes
                        .filter(r => r.dia === dia && r.asesor === asesor)
                        .forEach(r => {
                            if (!r.tipo_nom) {
                                llamada += Number(r.total);
                            } else if (r.tipo_nom === 'Llamada') {
                                llamada += Number(r.tipo);
                            } else if (r.tipo_nom === 'WhatsApp') {
                                whatsapp += Number(r.tipo);
                            }
                        });

                    totalesAsesor[asesor].Llamada += llamada;
                    totalesAsesor[asesor].WhatsApp += whatsapp;

                    const subtotal = llamada + whatsapp;
                    totalDia += subtotal;

                    html += `<td>${llamada}</td><td>${whatsapp}</td>`;
                });

                totalMesGeneral += totalDia;
                html += `<td><b>${totalDia}</b></td></tr>`;
            });
        });

        // 👉 Totales finales
        html += `<tr class="table-total"><td colspan="2">TOTAL GENERAL</td>`;

        asesores.forEach(a => {
            html += `<td>${totalesAsesor[a].Llamada}</td>`;
            html += `<td>${totalesAsesor[a].WhatsApp}</td>`;
        });

        html += `<td>${totalMesGeneral}</td></tr>`;
        html += `</tbody></table></div>`;

        document.getElementById('tablaDias').innerHTML = html;

    } catch (e) {
        console.error(e);
    } finally {
        loader.classList.add("d-none");
    }
}

function construirTablaEstados(data) {
    const loader = document.getElementById("loaderFoco");

    try {
        loader.classList.remove("d-none");

        // 🔹 Estados únicos con su ID
        const estadosMap = {};
        data.forEach(r => {
            estadosMap[r.estado] = r.id;
        });

        const estados = Object.keys(estadosMap);
        const asesores = getUnique(data, 'asesor');

        let html = `
        <div class="table-responsive-excel">
            <table class="table-excel">
                <thead>
                    <tr>
                        <th>ASESOR</th>`;

        estados.forEach(e => html += `<th>${e}</th>`);
        html += `<th>Total</th></tr>
                </thead>
                <tbody>`;

        let totalEstados = Array(estados.length).fill(0);
        let totalGeneral = 0;

        asesores.forEach(asesor => {
            let totalAsesor = 0;

            html += `
                <tr>
                    <td><b>${asesor}</b></td>`;

            estados.forEach((estado, i) => {
                const reg = data.find(r => r.asesor === asesor && r.estado === estado);
                const val = reg ? parseInt(reg.total) : 0;

                totalAsesor += val;
                totalEstados[i] += val;

                html += `<td>${val}</td>`;
            });

            totalGeneral += totalAsesor;
            html += `<td><b>${totalAsesor}</b></td></tr>`;
        });

        /* FILA TOTAL */
        html += `
            <tr class="table-total">
                <td>TOTAL</td>`;

        totalEstados.forEach(t => html += `<td>${t}</td>`);
        html += `<td>${totalGeneral}</td></tr>`;

        html += `
                </tbody>
            </table>
        </div>`;

        document.getElementById('tablaEstados').innerHTML = html;

    } catch (e) {
        console.error("Error card leads:", e);
    } finally {
        loader.classList.add("d-none");
    }
}

function listarEstadoLeadRst() {

    fetch('ajax/ajax.php?accion=rst_frm_dia&cod_emp=1')
        .then(r => r.json())
        .then(data => {
            construirTablaDias(data.porDia)
            construirTablaEstados(data.porEstado)
        })
}

/* ==========================================================
   VARIABLES GLOBALES DE ESTADO
   ========================================================== */
window.estadosSeleccionadosNombres = [];
window.asesoresSeleccionadosIds = [];
window.asesoresSeleccionados = [];

/* ==========================================================
   1. CONSTRUCCIÓN DE LA TABLA (FOCO)
   ========================================================== */
function construirTablaEstadosLeads(data) {
    const loader = document.getElementById("loaderFoco");
    try {
        if (loader) loader.classList.remove("d-none");

        const estados = [...new Set(data.map(r => r.estado))];
        const asesores = [...new Set(data.map(r => r.asesor))];

        let html = `
        <div class="table-responsive-excel">
            <table class="table-excel">
                <thead>
                    <tr>
                        <th>ASESOR</th>`;
        estados.forEach(e => html += `<th>${e}</th>`);
        html += `<th>Total</th></tr></thead><tbody>`;

        let totalColumnasEstados = Array(estados.length).fill(0);
        let totalGeneralAbsoluto = 0;

        asesores.forEach(asesorName => {
            let totalFilaAsesor = 0;
            const regAsesor = data.find(r => r.asesor === asesorName);
            const idAsesor = regAsesor ? regAsesor.id_user : "";

            html += `<tr><td><b>${asesorName}</b></td>`;

            estados.forEach((estado, i) => {
                const reg = data.find(r => r.asesor === asesorName && r.estado === estado);
                const val = reg ? parseInt(reg.total) : 0;
                totalFilaAsesor += val;
                totalColumnasEstados[i] += val;

                // Clic en celda normal (Asesor + Estado)
                html += `
                    <td>
                        <a href="javascript:void(0);" 
                           class="fw-bold ${val > 0 ? 'text-primary' : 'text-muted'}"
                           onclick="filtrarYListarDesdeTabla('${idAsesor}', '${estado}', '${asesorName}')">
                           ${val}
                        </a>
                    </td>`;
            });

            totalGeneralAbsoluto += totalFilaAsesor;

            // TOTAL POR ASESOR (Fila derecha): Filtra por Asesor, pero todos los estados
            html += `
                <td class="table-total">
                    <a href="javascript:void(0);" class="fw-bold text-dark" 
                       onclick="filtrarYListarDesdeTabla('${idAsesor}', '', '${asesorName}')">
                       ${totalFilaAsesor}
                    </a>
                </td></tr>`;
        });

        // FILA DE TOTALES POR ESTADO (Abajo)
        html += `<tr class="table-total"><td>TOTAL</td>`;
        totalColumnasEstados.forEach((t, i) => {
            const nombreEstado = estados[i];
            html += `
                <td>
                    <a href="javascript:void(0);" class="fw-bold text-dark"
                       onclick="filtrarYListarDesdeTabla('', '${nombreEstado}', '')">
                       ${t}
                    </a>
                </td>`;
        });

        // TOTAL GENERAL ABSOLUTO (Esquina inferior derecha): Limpia filtros de asesor y estado
        html += `
            <td>
                <a href="javascript:void(0);" class="fw-bold text-danger"
                   onclick="filtrarYListarDesdeTabla('', '', '')">
                   ${totalGeneralAbsoluto}
                </a>
            </td></tr></tbody></table></div>`;

        document.getElementById('tablaEstadosLead').innerHTML = html;
    } catch (e) {
        console.error("Error en tabla:", e);
    } finally {
        if (loader) loader.classList.add("d-none");
    }
}

/* ==========================================================
   2. ACCIÓN DE FILTRADO Y VALIDACIÓN DE ENVÍO
   ========================================================== */
function filtrarYListarDesdeTabla(idAsesor, nombreEstado, nombreAsesor) {
    // 1. Guardamos en globales. Si el valor viene vacío, el filtro será "Todos"
    window.asesoresSeleccionadosIds = idAsesor ? [idAsesor.toString()] : [];
    window.estadosSeleccionadosNombres = nombreEstado ? [nombreEstado] : [];
    window.asesoresSeleccionados = nombreAsesor ? [nombreAsesor] : [];

    const contenedor = document.getElementById("contenedorLeadsFoco");
    if (contenedor) contenedor.classList.remove("d-none");

    listarLeadsDesdeFocoRST();
}

function listarLeadsDesdeFocoRST() {
    const params = new URLSearchParams();
    params.append("accion", "listar_leads");

    const carrerasIds = Array.from(document.querySelectorAll('#listar_filtro_carrera input:checked')).map(cb => cb.value);

    // Si las globales están vacías (porque se hizo clic en un Total), intentamos leer de los checkboxes
    const asesoresIds = window.asesoresSeleccionadosIds.length > 0
        ? window.asesoresSeleccionadosIds
        : Array.from(document.querySelectorAll('#listar_filtro_user input:checked')).map(cb => cb.value);

    const estadosNom = window.estadosSeleccionadosNombres.length > 0
        ? window.estadosSeleccionadosNombres
        : Array.from(document.querySelectorAll('#listar_filtro_estado input:checked')).map(cb => {
            return cb.nextElementSibling ? cb.nextElementSibling.textContent.trim() : cb.value;
        });

    console.group("🚀 Enviando Petición Ajax");
    console.log("Asesor ID:", asesoresIds);
    console.log("Estado Nombre:", estadosNom);
    console.log("Carreras IDs:", carrerasIds);
    console.groupEnd();

    if (carrerasIds.length > 0) params.append("carreras", JSON.stringify(carrerasIds));
    if (asesoresIds.length > 0) params.append("asesor", JSON.stringify(asesoresIds));
    if (estadosNom.length > 0) params.append("estados", JSON.stringify(estadosNom));

    params.append("lead_reporte_CRM_FOCO", "true");

    fetch("ajax/ajax.php?" + params.toString())
        .then(res => res.json())
        .then(data => {
            if (data && !data.error) inicializarDataTableLeads(data);
        })
        .catch(err => console.error("Error Fetch:", err));
}

/* ==========================================================
   3. MODAL DE MENSAJES (SINCRONIZACIÓN)
   ========================================================= */
document.getElementById("btnAbrirModalMensajes")?.addEventListener("click", function () {
    const modalElement = document.getElementById('modalMensajesFoco');
    if (!modalElement) return;
    new bootstrap.Modal(modalElement).show();
    sincronizarModalMensajes();
});

async function sincronizarModalMensajes() {
    if (typeof cargarMensajesPorTema === "function") await cargarMensajesPorTema();
    if (typeof cargarFiltrosRST === "function") await cargarFiltrosRST();

    // Resetear todos los checks antes de sincronizar
    document.querySelectorAll('#filtro_asesor input, #filtro_estado input').forEach(cb => cb.checked = false);

    // 1. Sincronizar Asesores (Por ID y por Primer Nombre)
    document.querySelectorAll('#filtro_asesor input').forEach(cb => {
        const labelCompleto = cb.nextElementSibling?.textContent?.trim().toUpperCase() || "";
        const primerNombreLabel = labelCompleto.split(' ')[0];

        // Match por ID
        const matchId = window.asesoresSeleccionadosIds.includes(cb.value);

        // Match por Primer Nombre
        const matchNombre = window.asesoresSeleccionados.some(nomCompleto => {
            const primeraPalabraGuardada = nomCompleto.trim().toUpperCase().split(' ')[0];
            return primeraPalabraGuardada === primerNombreLabel;
        });

        if (matchId || matchNombre) cb.checked = true;
    });

    // 2. Sincronizar Estados (Nombre)
    document.querySelectorAll('#filtro_estado input').forEach(cb => {
        const labelModal = cb.nextElementSibling?.textContent?.trim().toUpperCase();
        cb.checked = window.estadosSeleccionadosNombres.some(e => e.toUpperCase() === labelModal);
    });

    if (typeof validarYCargarTabla === "function") validarYCargarTabla();
}

/* ==========================================================
   4. RENDERIZADO Y GESTIÓN
   ========================================================== */
function inicializarDataTableLeads(data) {
    if ($.fn.DataTable.isDataTable('#leads_list')) $('#leads_list').DataTable().destroy();

    const tbody = document.querySelector("#leads_list tbody");
    tbody.innerHTML = data.map(l => `
        <tr>
            <td><a href="javascript:void(0)" onclick="abrirModalGestion('${l.id_lead}', '${l.id_cliente}')" class="text-primary fw-bold">${l.nombres} ${l.apellidos}</a></td>
            <td>${l.desc_pro}</td>
            <td>${l.telefono_principal}</td>
            <td>${l.estado}</td>
            <td>${l.nombreAsesor}</td>
            <td>${l.fecha_creacion}</td>
            <td class="text-center">${l.fecha_ultima_gestion === new Date().toISOString().split('T')[0] ? '<span class="badge bg-success">OK</span>' : '<span class="badge bg-secondary">Pendiente</span>'}</td>
        </tr>`).join('');

    $('#leads_list').DataTable({
        responsive: true,
        language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" }
    });
}

function abrirModalGestion(idLead, idCliente) {
    document.getElementById('frameGestion').src = `leads-details.php?id=${idLead}&id_cliente=${idCliente}&modal=1`;
    new bootstrap.Modal(document.getElementById('modalGestionLead')).show();
}

function listarEstadoLead() {

    const f = Filtros.obtener();
    const params = new URLSearchParams();

    params.append("accion", "lead_dia");

    if (f.asesor.length > 0) params.append("asesor", JSON.stringify(f.asesor));
    if (f.carreras.length > 0) params.append("carreras", JSON.stringify(f.carreras));
    if (f.horario.length > 0) params.append("horario", JSON.stringify(f.horario));
    if (f.estados.length > 0) params.append("estados", JSON.stringify(f.estados));

    fetch("ajax/ajax.php?" + params.toString())
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("tablaEstadosLead")) {
                construirTablaEstadosLeads(data.porEstado);
            }
        })
        .catch(err => console.error("Error reporte rst:", err));
}

function obtenerPaginaActual() {
    return window.location.pathname.split('/').pop();
}

if (obtenerPaginaActual() === 'lead_dia.php') {


    document.addEventListener("change", function (e) {
        if (e.target.classList.contains("filtro") || e.target.classList.contains("select-all-filter")) {
            listarEstadoLead();
        }
    });
    listarEstadoLead();
}

if (obtenerPaginaActual() === 'rst_frm_dia.php') {

    listarEstadoLeadRst();
}

if (obtenerPaginaActual() === 'rst_frm.php') {

    document.addEventListener("change", function (e) {
        if (e.target.classList.contains("filtro") || e.target.classList.contains("select-all-filter")) {
            listarReporteRstFrm();
        }
    });

    document.addEventListener("input", function (e) {
        if (e.target.id === "buscador") {
            listarReporteRstFrm();
        }
    });

    listarReporteRstFrm();
}
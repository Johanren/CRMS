function cargarNotificaciones() {
    const datos = new FormData();
    datos.append("accion", "listar");

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
    .then(r => {
        // Validación: ¿El servidor respondió bien?
        if (!r.ok) throw new Error('Error en la respuesta del servidor');
        return r.json();
    })
    .then(data => {
        console.log(data);
        // Validamos que 'data' sea un array antes de pasarlo
        if (Array.isArray(data)) {
            inicializarDataTableLeads1(data);
        } else {
            console.warn("La respuesta no es un array válido:", data);
        }
    })
    .catch(error => {
        console.error("Error al cargar notificaciones:", error);
    });
}

function cargarTopbarNotificaciones() {
    const datos = new FormData();
    datos.append("accion", "listar_limit");
    datos.append("limit", "3");

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
    .then(r => r.json())
    .then(data => {
        const body = document.getElementById("listaNotificaciones");
        if (!body) return;

        let htmlContenido = "";

        if (data.length === 0) {
            body.innerHTML = `<div class="p-3 text-center text-muted small">No hay notificaciones nuevas</div>`;
            return;
        }

        data.forEach(n => {
            // Intentar parsear la referencia de forma segura
            let url = "#";
            try {
                const ref = typeof n.referencia === 'string' ? JSON.parse(n.referencia) : n.referencia;
                url = `${n.modulo}?id=${ref.id}&id_cliente=${ref.id_cliente}`;
            } catch (e) {
                console.error("Error parseando referencia:", e);
            }

            htmlContenido += `
            <div class="dropdown-item notification-item py-3 border-bottom text-wrap">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <p class="fw-semibold mb-1 text-break text-dark" style="font-size: 13px;">${n.titulo}</p>
                        <p class="mb-2 small text-muted text-break">${n.mensaje}</p>
                        <a href="javascript:void(0);" 
                           class="btn btn-sm btn-link p-0 fw-bold text-decoration-none fs-12" 
                           onclick="verNotificacion(${n.id_notificacion}, '${url}')">
                           Ver detalle <i class="ri-arrow-right-s-line align-middle"></i>
                        </a>
                    </div>
                </div>
            </div>`;
        });

        body.innerHTML = htmlContenido;

        // Si usas la librería SimpleBar, hay que refrescarla
        if (window.SimpleBar) {
            const instance = SimpleBar.instances.get(body);
            if (instance) instance.recalculate();
        }
    })
    .catch(error => console.error("Error en el fetch:", error));
}

function actualizarBadge() {
    const datos = new FormData();
    datos.append("accion", "contador");

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(r => r.json())
        .then(total => {
            document.querySelector(".badge").innerText = total;
        });
}

/*function verNotificacion(id, url) {

    const datos = new FormData();
    datos.append("accion", "marcar_leida");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(resp => {

            if (resp.ok) {
                // Redirige al módulo correspondiente
                window.location.href = url;
            } else {
                console.error("No se pudo marcar la notificación como leída");
                // Igual redirigimos para no bloquear UX
                window.location.href = url;
            }

        })
        .catch(error => {
            console.error("Error:", error);
            // Fallback de seguridad
            window.location.href = url;
        });
}*

function cargarNuevosLeads() {
    const datos = new FormData();
    datos.append("accion", "nuevos_leads");

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(r => r.json())
        .then(data => {
            const body = document.querySelector(".nuevosLeads");
            if (body) {
                body.textContent = data.total || 0;
            } else {
                console.warn("Elemento .nuevosLeads no encontrado");
            }
        })
        .catch(error => console.error("Error en fetch:", error));
}

/* =====================================================
SOLUCIÓN REAL: EL MODAL NO ABRE POR DATATABLE + DISPLAY NONE
===================================================== */

let ultimoIdNotificacion = 0;
let tablaLeadsFoco2 = null;
let tablaLeadsFoco1 = null;

function monitorearNotificacionesNuevas() {
    const datos = new FormData();
    datos.append("accion", "listar_limit");

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
    .then(r => r.json())
    .then(data => {
        if (!data || data.length === 0) return;

        const nueva = data[0];
        const idActual = parseInt(nueva.id_notificacion);

        if (ultimoIdNotificacion === 0) {
            ultimoIdNotificacion = idActual;
            return; 
        }

        if (idActual > ultimoIdNotificacion) {
            ultimoIdNotificacion = idActual;

            const modalElement = document.getElementById("modalNuevaNotificacion");
            if (!modalElement) {
                console.error("No existe el elemento modalNuevaNotificacion en el DOM");
                return;
            }

            let modal = bootstrap.Modal.getInstance(modalElement);
            if (!modal) {
                modal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: true
                });
            }

            inicializarDataTableLeads([nueva]);

            modal.show();

            actualizarBadge();
            cargarTopbarNotificaciones();
            if (typeof cargarNotificaciones === 'function') cargarNotificaciones();
        }
    })
    .catch(error => {
        console.error("Error en el monitoreo:", error);
    });
}

function inicializarDataTableLeads(data) {

    const tableId = '#leads_list2';

    /* ==========================================
       DESTRUIR DATATABLE + QUITAR FILTROS PREVIOS
    ========================================== */
    if (tablaLeadsFoco2) {
        tablaLeadsFoco2.destroy();
        tablaLeadsFoco2 = null;
    }

    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().destroy();
    }

    $(tableId + ' thead tr:eq(1)').remove();

    /* ==========================================
       CONTADOR
    ========================================== */
    const spanContador = document.getElementById("contadorTotalLeads2");
    if (spanContador) spanContador.innerText = data.length;

    /* ==========================================
       BODY
    ========================================== */
    const tbody = document.querySelector("#leads_list2 tbody");
    tbody.innerHTML = "";

    if (!data.length) {
        tbody.innerHTML = `
        <tr>
            <td colspan="9" class="text-center">
                No hay resultados
            </td>
        </tr>`;
        return;
    }

    const hoy = new Date().toISOString().split('T')[0];

    data.forEach(l => {

        const fueGestionadoHoy =
            l.fec_ult_gest === hoy
                ? '<span class="badge bg-success">OK</span>'
                : '<span class="badge bg-secondary">Pendiente</span>';

        const tr = document.createElement("tr");

        tr.innerHTML = `
            <td>
                <a href="javascript:void(0)"
                   class="text-primary fw-bold"
                   onclick="abrirModalGestion('${l.id_lead}','${l.cliente_id}', '${l.id_notificacion}')">
                   ${l.nombres} ${l.apellidos}
                </a>
            </td>
            <td>${l.desc_pro}</td>
            <td>${l.telefono_principal}</td>
            <td>${l.estado}</td>
            <td>${l.nombreAsesor}</td>
            <td>${l.fecha_creacion}</td>
            <td>${l.fec_ult_gest || ''}</td>
            <td>${l.fec_ult_asig || ''}</td>
            <td>${fueGestionadoHoy}</td>
        `;

        tbody.appendChild(tr);
    });

    /* ==========================================
       DATATABLE + FILTROS POR COLUMNA
    ========================================== */
    tablaLeadsFoco2 = $(tableId).DataTable({
        responsive: true,
        ordering: true,
        orderCellsTop: true,
        fixedHeader: true,
        pageLength: 10,

        initComplete: function () {

            const api = this.api();

            /* CLONAR HEADER */
            if ($(tableId + ' thead tr').length === 1) {
                $(tableId + ' thead tr')
                    .clone(true)
                    .appendTo(tableId + ' thead');
            }

            /* INPUTS FILTRO */
            $(tableId + ' thead tr:eq(1) th').each(function (i) {

                $(this).html(`
                    <input type="text"
                        class="form-control form-control-sm"
                        placeholder="Filtrar..."
                        style="width:100%;" />
                `);

                $('input', this).on('keyup change clear', function () {

                    if (api.column(i).search() !== this.value) {
                        api.column(i).search(this.value).draw();
                    }

                });
            });
        }
    });
}

function inicializarDataTableLeads1(data) {
    const tableId = '#leads_list_1';
    
    // 2. Destruir instancia previa correctamente
    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().clear().destroy();
    }

    // Limpiar restos de filas de filtros previos para evitar duplicados
    $(tableId + ' thead tr:eq(1)').remove();

    // 3. Actualizar contador
    const spanContador = document.getElementById("contadorTotalLeads1");
    if (spanContador) spanContador.innerText = data.length;

    // 4. Llenar el cuerpo de la tabla
    const tbody = document.querySelector(tableId + " tbody");
    tbody.innerHTML = "";

    if (!data || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center">No hay resultados</td></tr>';
        return;
    }

    const hoy = new Date().toISOString().split('T')[0];

    data.forEach(l => {
        const fueGestionadoHoy = l.fec_ult_gest === hoy
            ? '<span class="badge bg-success">OK</span>'
            : '<span class="badge bg-secondary">Pendiente</span>';

        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>
                <a href="javascript:void(0)" class="text-primary fw-bold"
                   onclick="abrirModalGestion('${l.id_lead}','${l.cliente_id}', '${l.id_notificacion}')">
                   ${l.nombres} ${l.apellidos}
                </a>
            </td>
            <td>${l.desc_pro || ''}</td>
            <td>${l.telefono_principal || ''}</td>
            <td>${l.estado || ''}</td>
            <td>${l.nombreAsesor || ''}</td>
            <td>${l.fecha_creacion || ''}</td>
            <td>${l.fec_ult_gest || ''}</td>
            <td>${l.fec_ult_asig || ''}</td>
            <td>${fueGestionadoHoy}</td>
        `;
        tbody.appendChild(tr);
    });

    // 5. Inicializar DataTable
    tablaLeadsFoco1 = $(tableId).DataTable({
        responsive: true,
        ordering: true,
        orderCellsTop: true,
        fixedHeader: true,
        pageLength: 10,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" // Opcional: Idioma
        },
        initComplete: function () {
            const api = this.api();

            // Clonar solo si no existe la fila de filtros
            if ($(tableId + ' thead tr').length === 1) {
                $(tableId + ' thead tr').clone(true).appendTo(tableId + ' thead');
            }

            $(tableId + ' thead tr:eq(1) th').each(function (i) {
                $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Filtrar..." />');
                
                $('input', this).on('keyup change clear', function () {
                    if (api.column(i).search() !== this.value) {
                        api.column(i).search(this.value).draw();
                    }
                });
            });
        }
    });
}

function abrirModalGestion(idLead, idCliente, idNotificacion = null) {
    // 1. Cargar el iframe y mostrar el modal
    const url = `leads-details.php?id=${idLead}&id_cliente=${idCliente}&modal=1`;
    const frame = document.getElementById('frameGestion');
    if (frame) frame.src = url;

    const modalElement = document.getElementById('modalGestionLead');
    if (modalElement) {
        let myModal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        myModal.show();
    }

    // 2. Petición para actualizar fecha de gestión (GET)
    const params = new URLSearchParams({
        accion: "actualizar_fecha_gestion",
        id_lead: idLead,
        cliente_id: idCliente
    });

    fetch("ajax/ajax.php?" + params.toString())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                console.log("✅ Fecha de gestión actualizada");
            }
        })
        .catch(err => console.error("Error al actualizar fecha:", err));

    // 3. Marcar como leída (POST) - Solo si se pasa un ID de notificación
    if (idNotificacion) {
        const datos = new FormData();
        datos.append("accion", "marcar_leida");
        datos.append("id", idNotificacion);

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
        .then(res => res.json())
        .then(data => {
            console.log("✅ Notificación marcada como leída");
            // Opcional: refrescar el badge o la lista de notificaciones
            if (typeof actualizarBadge === 'function') actualizarBadge();
        })
        .catch(err => console.error("Error al marcar como leída:", err));
    }
}

setInterval(() => {
    monitorearNotificacionesNuevas();
}, 5000);

document.addEventListener("DOMContentLoaded", function () {

    function obtenerPaginaActual() {
        return window.location.pathname.split('/').pop();
    }
    if (obtenerPaginaActual() === 'notifications.php') {
        cargarNotificaciones();
    }
    cargarTopbarNotificaciones();
    actualizarBadge();
    //cargarNuevosLeads();

    monitorearNotificacionesNuevas();
});
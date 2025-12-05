/*LEADS*/
function inicializarDataTableLeads(leads) {

    if ($.fn.DataTable.isDataTable('#leads_list')) {
        $('#leads_list').DataTable().clear().destroy();
    }

    $('#leads_list').DataTable({
        "bFilter": false,
        "bInfo": false,
        "ordering": true,
        "autoWidth": true,
        "language": {
            search: ' ',
            sLengthMenu: '_MENU_',
            searchPlaceholder: "Search",
            info: "_START_ - _END_ of _TOTAL_ items",
            lengthMenu: "Show _MENU_ entries",
            paginate: {
                next: '<i class="ti ti-chevron-right"></i> ',
                previous: '<i class="ti ti-chevron-left"></i> '
            },
        },
        initComplete: (settings, json) => {
            $('#leads_list .dataTables_paginate').appendTo('.datatable-paginate');
            $('#leads_list .dataTables_length').appendTo('.datatable-length');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": leads,

        "columns": [{
                data: null,
                render: function(row) {
                    return row.nombres + " " + row.apellidos;
                }
            },
            { "data": "programa" },
            { "data": "telefono_principal" },
            {
                "render": function(data, type, row) {
                    let class_name = "";
                    let status_name = "";

                    switch (row.estado_leads_id) {
                        case "1":
                            class_name = "warning";
                            status_name = "No contactado";
                            break;
                        case "2":
                            class_name = "success";
                            status_name = "Contactado";
                            break;
                        case "3":
                            class_name = "Paused";
                            status_name = "Pendiente";
                            break;
                        case "4":
                            class_name = "danger";
                            status_name = "Perdido";
                            break;
                        default:
                            class_name = "info";
                            status_name = "Paused";
                            break;
                    }

                    return `<span class="badge badge-pill badge-status bg-${class_name}">${status_name}</span>`;
                }
            },
            {
                data: "asesor",
                render: function(asesor) {
                    if (!asesor || asesor === null || asesor === "") {
                        return "<span class='text-muted'>No hay asesor asignado</span>";
                    }
                    return asesor;
                }
            },
            { "data": "fecha_creacion" },
            {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarLeads(${row.id_estado_leads})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarLeads(${row.cod_dep})"
                                data-bs-toggle="modal" 
                                data-bs-target="#delete_campaign">
                                    <i class="ti ti-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    `
            }
        ]
    });
}

if (document.getElementById("formLeads")) {
    document.getElementById("formLeads").addEventListener("submit", function(e) {
        e.preventDefault();

        let datos = new FormData(this);
        if (document.getElementById("cliente_id")) {
            let leadsId = document.getElementById("cliente_id").value;
            if (leadsId && parseInt(leadsId) > 0) {
                datos.append("accion", "actualizar_leads");
            } else {
                datos.append("accion", "registrar_leads");
            }
        } else {
            datos.append("accion", "registrar_leads");
        }

        fetch("ajax/ajax.php", {
                method: "POST",
                body: datos
            })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    cargarKanban();
                    this.reset();
                    document.getElementById("offcanvas").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

function listarLeads() {
    fetch("ajax/ajax.php?accion=listar_leads")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("leads_list")) {
                inicializarDataTableLeads(data);
            }
        })
        .catch(err => console.error("Error al listar leads:", err));
}

window.editarLeads = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_leads");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
        .then(res => res.json())
        .then(data => {

            const leads = data.find(c => c.id_lead == id);
            if (!leads) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-leads").textContent = "Editar Cliente";
            document.getElementById("btn-canvas-leads").textContent = "Editar";
            // Llenar campos
            document.getElementById("nombresLeads").value = leads.nombres;
            document.getElementById("apellidosLeads").value = leads.apellidos;
            document.getElementById("identificacionLeads").value = leads.identificacion;
            document.getElementById("telefonoLeads").value = leads.telefono_principal;
            document.getElementById("correoLeads").value = leads.email;
            document.getElementById("direLeads").value = leads.direccion;
            document.getElementById("infoLeads").value = leads.info_adicional;
            document.getElementById("carrera").value = leads.carrera_id;
            document.getElementById("interes").value = leads.interes_id;
            document.getElementById("horario").value = leads.horario_id;
            document.getElementById("medio").value = leads.medio_id;
            document.getElementById("fuente").value = leads.fuente_id;
            document.getElementById("campana").value = leads.campana_id;
            document.getElementById("accion").value = leads.accion_id;
            document.getElementById("departamento").value = leads.departamento_id;
            document.getElementById("ciudad").value = leads.ciudad_id;
            document.getElementById("barrio").value = leads.barrio_id;
            document.getElementById("observacionLeads").value = leads.observaciones;

            // Guardar ID oculto
            if (!document.getElementById("cliente_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "cliente_id";
                hidden.name = "cliente_id";
                document.getElementById("formLeads").appendChild(hidden);
            }
            document.getElementById("cliente_id").value = leads.cliente_id;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offcanvas_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

if (document.getElementById("btnCerrarOffcanvas-depar")) {
    document.getElementById("btnCerrarOffcanvas-depar").addEventListener("click", function() {
        document.getElementById("formDepart").reset();
        document.getElementById("title-canvas-depar").textContent = "Nueva Departamento";
        document.getElementById("btn-canvas-depar").textContent = "Crear";
    });
}

window.eliminarLeads = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_leads");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                Swal.fire("Éxito", data.message, "success");
                listarDepart();
            } else {
                Swal.fire("Error", data.message, "error");
            }

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

function listarLeadsOption() {
    fetch("ajax/ajax.php?accion=listar_depar_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("leads")) {
                document.getElementById("leads").innerHTML = data.option;
            }
        });
}

listarLeadsOption();
listarLeads();

document.addEventListener("DOMContentLoaded", function() {
    if (document.getElementById("departamento")) {
        // Cuando seleccionas un departamento
        document.getElementById("departamento").addEventListener("change", function() {
            let id_dep = this.value;

            if (id_dep !== "") {

                document.getElementById("contenedor_ciudad").style.display = "block";

                // Reiniciar barrio
                document.getElementById("barrio").innerHTML = "<option value=''>Seleccione...</option>";
                document.getElementById("contenedor_barrio").style.display = "none";

                // Cargar ciudades por departamento
                fetch("ajax/ajax.php?accion=listar_ciudad_por_departamento&id_dep=" + id_dep)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById("ciudad").innerHTML = data.option;
                    });

            } else {
                document.getElementById("contenedor_ciudad").style.display = "none";
                document.getElementById("contenedor_barrio").style.display = "none";
            }
        });
    }

    if (document.getElementById("ciudad")) {

        // Cuando seleccionas una ciudad
        document.getElementById("ciudad").addEventListener("change", function() {
            let id_ciudad = this.value;

            if (id_ciudad !== "") {
                document.getElementById("contenedor_barrio").style.display = "block";

                fetch("ajax/ajax.php?accion=listar_barrio_por_ciudad&id_ciudad=" + id_ciudad)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById("barrio").innerHTML = data.option;
                    });

            } else {
                document.getElementById("contenedor_barrio").style.display = "none";
            }
        });
    }

});

//Tarjetas leads.

document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("kanban-container")) {
        cargarKanban();
    }
});

async function cargarKanban() {
    const estados = await cargarEstados();
    const leads = await cargarLeads();
    renderKanban(estados, leads);
}

/* ================================
   1. Cargar ESTADOS desde PHP
================================ */
async function cargarEstados() {
    const res = await fetch("ajax/ajax.php?accion=getEstados");
    return await res.json();
}

/* ================================
   2. Cargar LEADS desde PHP
================================ */
async function cargarLeads() {
    const res = await fetch("ajax/ajax.php?accion=getLeads");
    return await res.json();
}

/* ================================
   3. Renderizar tablero CON TU DISEÑO
================================ */
function renderKanban(estados, leads) {

    const contenedor = document.getElementById("kanban-container");
    contenedor.innerHTML = "";

    estados.forEach(estado => {

        const cantidad = leads.filter(l => l.estado_leads_id == estado.id_estado_leads).length;

        // COLORES según estado (opcional, puedes personalizar)
        const coloresEstado = {
            1: "text-info",
            2: "text-info",
            3: "text-warning",
            4: "text-info",
            5: "text-info",
            6: "text-success"
        };

        let columna = document.createElement("div");
        columna.className = "kanban-list-items p-2 rounded border";
        columna.innerHTML = `
            <div class="card mb-0 border-0 shadow">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="d-flex align-items-center mb-1">
                                <i class="ti ti-circle-filled fs-10 ${coloresEstado[estado.id_estado_leads] || 'text-secondary'} me-1"></i>
                                ${estado.nombre}
                            </h6>
                            <span class="fw-medium">${cantidad} Leads</span>
                        </div>

                        <div class="d-flex align-items-center">
                            <a href="javascript:void(0);" class="text-info">
                                <i class="ti ti-plus"></i>
                            </a>

                            <div class="dropdown table-action ms-2">
                                <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvas_add">
                                        <i class="fa-solid fa-pencil text-blue"></i> Editar
                                    </a>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#delete_lead">
                                        <i class="fa-regular fa-trash-can text-danger"></i> Eliminar
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="kanban-drag-wrap">
                <div class="kanban-list" data-estado="${estado.id_estado_leads}"></div>
            </div>
        `;

        contenedor.appendChild(columna);

        // Insertar leads
        let lista = columna.querySelector(".kanban-list");

        leads.filter(l => l.estado_leads_id == estado.id_estado_leads)
            .forEach(l => lista.appendChild(crearCardLead(l, estado.id_estado_leads)));
    });

    activarDragAndDrop();
}

/* ================================
   4. Card del lead CON TU DISEÑO
================================ */
function crearCardLead(l, estadoId) {

    if (!l || !l.id_lead) return document.createElement("div"); // evita undefined

    const coloresTop = {
        1: "bg-info",
        2: "bg-info",
        3: "bg-warning",
        4: "bg-info",
        5: "bg-info",
        6: "bg-success"
    };

    const nombre = l.nombres || "";
    const apellido = l.apellidos || "";
    const iniciales = (nombre.charAt(0) + apellido.charAt(0)).toUpperCase();

    let card = document.createElement("div");
    card.className = "card kanban-card border mb-0 mt-3 shadow ui-sortable-handle";
    card.draggable = true;
    card.dataset.id = l.id_lead;

    card.innerHTML = `
        <div class="card-body">

            <div class="d-block">
                <div class="card-topbar mb-3 pt-1 ${coloresTop[estadoId] || 'bg-secondary'}"></div>
                    <div class="dropdown table-action ms-2">
                                <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </a>
                                <div class="d-flex align-items-center">
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" onclick="editarLeads(${l.id_lead})" href="#" data-bs-toggle="offcanvas">
                                            <i class="fa-solid fa-pencil text-blue"></i> Editar
                                        </a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#delete_lead">
                                            <i class="fa-regular fa-trash-can text-danger"></i> Eliminar
                                        </a>
                                    </div>
                                </div>
                            </div>
                    <div class="d-flex align-items-center mb-3">
                    <a href="leads-details.php?id=${l.id_lead}"
                        class="avatar rounded-circle bg-soft-info flex-shrink-0 me-2">
                        <span class="avatar-title text-info">${iniciales || "?"}</span>
                    </a>
                    <h6 class="fw-medium fs-14 mb-0">
                        <a href="leads-details.php?id=${l.id_lead}">${nombre} ${apellido}</a>
                    </h6>
                </div>
            </div>

            <div class="d-flex flex-column">
                <p class="text-default mb-2">
                    <i class="ti ti-mail text-dark me-1"></i>${l.email || 'Sin email'}
                </p>
                <p class="text-default mb-2">
                    <i class="ti ti-phone text-dark me-1"></i>${l.telefono_principal || 'Sin teléfono'}
                </p>
                <p class="text-default">
                    <i class="ti ti-map-pin-pin text-dark me-1"></i>${l.ciudad || 'Sin ciudad'}
                </p>
            </div>

        </div>
    `;

    return card;
}

/* ================================
   5. Drag & Drop
================================ */
function activarDragAndDrop() {

    // --- Asegura altura mínima a listas vacías ---
    document.querySelectorAll(".kanban-list").forEach(lista => {
        lista.style.minHeight = "50px";
    });

    // --- HABILITAR que las cards sean arrastrables ---
    document.querySelectorAll(".kanban-card").forEach(card => {
        card.addEventListener("dragstart", e => {
            e.dataTransfer.setData("lead", e.target.dataset.id);

            // Estilo visual al arrastrar
            setTimeout(() => {
                card.classList.add("dragging");
            }, 0);
        });

        card.addEventListener("dragend", e => {
            card.classList.remove("dragging");
        });
    });

    // --- ÁREAS donde soltar ---
    document.querySelectorAll(".kanban-list").forEach(lista => {

        lista.addEventListener("dragenter", e => {
            e.preventDefault();
            lista.classList.add("kanban-hover");
        });

        lista.addEventListener("dragleave", e => {
            lista.classList.remove("kanban-hover");
        });

        lista.addEventListener("dragover", e => {
            e.preventDefault(); // NECESARIO para permitir drop
        });

        lista.addEventListener("drop", e => {
            e.preventDefault();

            let idLead = e.dataTransfer.getData("lead");
            let idEstado = lista.dataset.estado;

            let card = document.querySelector(`[data-id='${idLead}']`);

            if (!card) return; // evita romper cuando es null

            lista.appendChild(card);

            lista.classList.remove("kanban-hover");

            // Actualiza en BD
            updateEstadoLead(idLead, idEstado);

            // Actualiza contador
            actualizarContadores();
        });
    });
}


function actualizarContadores() {
    document.querySelectorAll(".kanban-list-items").forEach(col => {
        const lista = col.querySelector(".kanban-list");
        const count = lista.children.length;
        col.querySelector(".fw-medium").textContent = count + " Leads";
    });
}


/* ================================
   6. Actualizar estado en DB
================================ */
async function updateEstadoLead(idLead, idEstado) {
    const formData = new FormData();
    formData.append("accion", "updateEstado");
    formData.append("id_lead", idLead);
    formData.append("id_estado", idEstado);

    await fetch("ajax/ajax.php", {
        method: "POST",
        body: formData
    });
}

/* ================================
   Leads Details
================================ */

const params = new URLSearchParams(window.location.search);
const idLead = params.get("id");

async function listarLeadsId() {
    let datos = new FormData();
    datos.append("id", idLead);
    datos.append("accion", "listar_leads_id");

    const res = await fetch("ajax/ajax.php", { method: "POST", body: datos });
    const data = await res.json();

    let d = data[0];

    // Asignación de datos básicos
    document.getElementById("nombreClienteLeads").textContent = `${d.nombres} ${d.apellidos}`;
    document.getElementById("empresaCarrera").textContent = d.nom_emp;
    //document.getElementById("valorCarrera").textContent = new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 }).format(d.val_pro);
    document.getElementById("direccionClienteLeads").textContent = d.direccion;
    document.getElementById("fuenteLeads").textContent = d.desc_fue;

    [...document.getElementsByClassName("fechaLeads")].forEach(elem => {
        elem.textContent = d.fecha_creacion;
    });

    // 🔥 Cargar estados y renderizar stepper dinámico
    const estados = await cargarEstados();
    renderEstadosLead(estados, d);
}

function cambiarEstadoLead(elemento, id_lead) {

    const nuevoEstado = elemento.dataset.id;

    let form = new FormData();
    form.append("accion", "updateEstado");
    form.append("id_lead", id_lead);
    form.append("id_estado", nuevoEstado);

    fetch("ajax/ajax.php", {
            method: "POST",
            body: form
        })
        .then(r => r.json())
        .then(res => {

            /*if (res.status === "success") {
                Swal.fire("Actualizado", "El estado del lead fue cambiado", "success");

                // Recargar vista completa del lead
                listarLeadsId();
            }*/
            listarLeadsId();
        });
}

function renderEstadosLead(estados, lead) {

    const coloresEstado = {
        1: "bg-indigo",
        2: "bg-indigo",
        3: "bg-warning",
        4: "bg-warning",
        5: "bg-primary",
        6: "bg-success"
    };

    // Contenedor en tu HTML
    let cont = document.getElementById("estadoLeadsSecundarioSeguimiento");
    cont.innerHTML = ""; // limpiar

    let wrapper = document.createElement("div");
    wrapper.className = "step-progress d-flex flex-wrap gap-2";

    estados.forEach(est => {

        const activo = (est.id_estado_leads == lead.estado_leads_id);

        wrapper.innerHTML += `
            <div 
                class="step ${activo ? coloresEstado[est.id_estado_leads ] : 'bg-light text-black'} pointer estado-item"
                data-id="${est.id_estado_leads }"
                data-nombre="${est.nombre}">
                
                ${est.nombre}
            </div>
        `;
    });

    cont.appendChild(wrapper);

    // Agregar eventos de clic para cambiar estado
    document.querySelectorAll(".estado-item").forEach(btn => {
        btn.addEventListener("click", () => cambiarEstadoLead(btn, lead.id_lead));
    });
}



listarLeadsId();

/* ================================
   Notas
================================ */

let archivosSeleccionados = [];

document.getElementById("desc_arch").addEventListener("change", function() {

    // Convertir FileList a array y añadir al almacenamiento
    archivosSeleccionados = [...archivosSeleccionados, ...Array.from(this.files)];

    mostrarArchivos();
});

function mostrarArchivos() {
    let contenedor = document.getElementById("preview-archivos");
    contenedor.innerHTML = "";

    archivosSeleccionados.forEach((archivo, index) => {

        let nombre = archivo.name;
        let tamano = (archivo.size / 1024).toFixed(1) + " KB";
        let extension = nombre.split(".").pop().toLowerCase();

        // Iconos según extensión
        let icono = "ti ti-file";
        if (["jpg", "jpeg", "png", "gif"].includes(extension)) icono = "ti ti-file-image";
        if (["mp4", "avi", "mov"].includes(extension)) icono = "ti ti-file-video";
        if (["pdf"].includes(extension)) icono = "ti ti-file-text";
        if (["xls", "xlsx"].includes(extension)) icono = "ti ti-file-spreadsheet";
        if (["doc", "docx"].includes(extension)) icono = "ti ti-file-description";

        let card = `
        <div class="col-md-7 mt-2" id="file-${index}">
            <div class="card mb-0">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                        
                        <div class="d-flex align-items-center me-3">
                            <span class="avatar bg-success me-2">
                                <i class="${icono} fs-20"></i>
                            </span>
                            <div>
                                <h6 class="fw-medium fs-14 mb-1">${nombre}</h6>
                                <p class="mb-0">${tamano}</p>
                            </div>
                        </div>

                        <!-- Botón de borrar -->
                        <a href="javascript:void(0);" class="avatar avatar-xs rounded-circle bg-danger text-white"
                            onclick="eliminarArchivo(${index})">
                            <i class="ti ti-x"></i>
                        </a>

                    </div>
                </div>
            </div>
        </div>
        `;

        contenedor.insertAdjacentHTML("beforeend", card);
    });

}

function eliminarArchivo(index) {
    // Eliminar archivo del array
    archivosSeleccionados.splice(index, 1);

    // Volver a renderizar la lista
    mostrarArchivos();
}


if (document.getElementById("formNotas")) {
    document.getElementById("formNotas").addEventListener("submit", function(e) {
        e.preventDefault();

        const datos = new FormData(this);
        datos.append("id", idLead);
        datos.append("accion", "registrar_notas");

        // Añadir archivos desde array
        archivosSeleccionados.forEach(file => {
            datos.append("desc_arch[]", file);
        });

        fetch("ajax/ajax.php", {
                method: "POST",
                body: datos
            })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    this.reset();
                    archivosSeleccionados = [];
                    document.getElementById("cerrarModalNotas").click();
                    listarNotas();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

function listarNotas() {
    fetch("ajax/ajax.php?accion=listarNotas&id=" + idLead)
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("contenedorNotas")) {
                renderNotas(data);
            }
        })
        .catch(err => console.error("Error al listar leads:", err));
}
listarNotas();

function renderNotas(data) {
    let cont = document.getElementById("contenedorNotas");
    cont.innerHTML = "";

    data.forEach(n => {

        // === ARCHIVOS ===
        let archivosHtml = "";
        n.archivos.forEach(a => {
            archivosHtml += `
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                <div class="d-flex align-items-center me-3">
                                    <span class="avatar bg-teal me-2">
                                        <i class="ti ti-file fs-20"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-medium fs-14 mb-1">${a.desc_arch}</h6>
                                        <p class="mb-0">Adjunto</p>
                                    </div>
                                </div>
                                <a href="uploads/notas/${a.desc_arch}" 
                                   download 
                                   class="avatar avatar-xs rounded-circle bg-light text-dark">
                                    <i class="ti ti-arrow-down"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>`;
        });

        // === COMENTARIOS ===
        let comentariosHtml = "";
        n.comentarios.forEach(c => {
            comentariosHtml += `
                <div class="bg-light p-3 rounded mt-2">
                    <p class="mb-2">${c.desc_com}</p>
                    <p>Comentado por <span class="text-info">${c.user_name}</span></p>
                </div>`;
        });

        // === TARJETA COMPLETA ===
        cont.innerHTML += `
            <div class="card mb-3">
                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-2 pb-2">
                        <div class="d-inline-flex align-items-center mb-2">
                            <span class="avatar avatar-md me-2 flex-shrink-0">
                                <img src="assets/img/profiles/avatar-20.jpg" alt="img">
                            </span>
                            <div>
                                <h6 class="fw-medium fs-14 mb-1">${n.user_name ?? "Usuario"}</h6>
                                <p class="mb-0">${n.fecha_creacion_nota}</p>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="dropdown">
                                <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item"><i class="ti ti-edit me-1"></i>Editar</a>
                                    <a class="dropdown-item"><i class="ti ti-trash me-1"></i>Eliminar</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-medium fs-14 mb-1">${n.tit_nota}</h5>
                    <p>${n.desc_not}</p>

                    <div class="row mt-3">
                        ${archivosHtml}
                    </div>

                    <div class="mt-3">
                        ${comentariosHtml}
                    </div>

                    <!-- BOTÓN PARA MOSTRAR EDITOR -->
                    <div class="text-end mt-3">
                        <a href="javascript:void(0);" 
                           class="add-comment link-primary fw-medium" 
                           data-id="${n.cod_not}">
                            <i class="ti ti-circle-plus me-1"></i>Agregar comentario
                        </a>
                    </div>

                    <!-- === EDITOR DE COMENTARIOS (OCULTO AL INICIO) === -->
                    <div class="notes-editor mt-3 d-none" id="editorNota-${n.cod_not}">
                        <input type="hidden" id="idNotaComentario-${n.cod_not}">
                        
                        <div class="">
                            <textarea class="form-control comentario-textarea" rows="3"></textarea>
                            <div class="text-end note-btns mt-3">
                                <a href="javascript:void(0);" class="btn btn-light add-cancel me-2">Cancelar</a>
                                <a href="javascript:void(0);" class="btn btn-primary btn-save-comment" data-id="${n.cod_not}">Guardar</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        `;
    });
}

document.addEventListener("click", (e) => {

    // === ABRIR EDITOR DE COMENTARIO ===
    if (e.target.closest(".add-comment")) {
        e.preventDefault();

        let btn = e.target.closest(".add-comment");
        let notaId = btn.dataset.id;

        // Mostrar solo el editor de esta nota
        let editor = document.getElementById(`editorNota-${notaId}`);
        editor.classList.remove("d-none");

        // Guardar ID para enviar al backend
        document.getElementById(`idNotaComentario-${notaId}`).value = notaId;
    }

    // === CANCELAR ===
    if (e.target.closest(".add-cancel")) {
        e.preventDefault();

        let editor = e.target.closest(".notes-editor");
        editor.classList.add("d-none");
    }

    // === GUARDAR COMENTARIO ===
    if (e.target.closest(".btn-save-comment")) {
        e.preventDefault();

        let notaId = e.target.dataset.id;
        let editor = document.getElementById(`editorNota-${notaId}`);
        let comentario = editor.querySelector(".comentario-textarea").value;

        if (comentario.trim() === "") {
            alert("El comentario está vacío");
            return;
        }

        // Aquí haces el fetch a tu backend
        guardarComentario(notaId, comentario);

        editor.classList.add("d-none");
    }

});

document.addEventListener("click", (e) => {

    // GUARDAR COMENTARIO
    if (e.target.closest(".btn-save-comment")) {
        e.preventDefault();

        let notaId = e.target.dataset.id;
        let editor = document.getElementById(`editorNota-${notaId}`);
        let comentario = editor.querySelector(".comentario-textarea").value;

        if (comentario.trim() === "") {
            Swal.fire("Error", "El comentario está vacío", "error");
            return;
        }

        let f = new FormData();
        f.append("accion", "agregar_comentario");
        f.append("nota_id", notaId);
        f.append("comentario", comentario);

        fetch("ajax/ajax.php", {
                method: "POST",
                body: f
            })
            .then(r => r.json())
            .then(res => {
                if (res.status === "success") {
                    Swal.fire("Éxito", "Comentario agregado", "success");
                    listarNotas();
                }
            });
    }
});

/* ================================
   Llamadas
================================ */

if (document.getElementById("formCalls")) {
    document.getElementById("formCalls").addEventListener("submit", function(e) {
        e.preventDefault();

        const datos = new FormData(this);
        datos.append("id", idLead);
        datos.append("accion", "registrar_calls");


        fetch("ajax/ajax.php", {
                method: "POST",
                body: datos
            })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    this.reset();
                    document.getElementById("cerrarModalCalls").click();
                    listarLlamadas();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

function listarLlamadas() {
    let f = new FormData();
    f.append("accion", "listar_llamadas");
    f.append("id_lead", idLead); // opcional si filtras por lead

    fetch("ajax/ajax.php", {
            method: "POST",
            body: f
        })
        .then(r => r.json())
        .then(data => {
            let cont = document.getElementById("listaLlamadas");
            cont.innerHTML = "";

            data.forEach(call => {
                cont.innerHTML += generarCardLlamada(call);
            });

            activarEventosEstados();
        });
}

listarLlamadas();

function activarEventosEstados() {
    document.querySelectorAll(".cambiar-estado").forEach(btn => {
        btn.addEventListener("click", (e) => {
            let nuevoEstado = e.target.dataset.estado;

            let btnEstado = e.target.closest(".dropdown").querySelector(".estado-btn");
            let id = btnEstado.dataset.id;

            actualizarEstadoLlamada(id, nuevoEstado, btnEstado);
        });
    });
}


function generarCardLlamada(c) {
    return `
        <div class="card mb-3">
            <div class="card-body">

                <div class="d-sm-flex align-items-center justify-content-between pb-2">

                    <div class="d-flex align-items-center mb-2">
                        <span class="avatar avatar-md me-2 flex-shrink-0">
                            <img src="assets/img/profiles/avatar-19.jpg" alt="img">
                        </span>

                        <p class="mb-0">
                            <span class="text-dark fw-medium">${c.user_name}</span>
                            logged a call on ${formatearFecha(c.fecha_creacion_call)}
                        </p>
                    </div>

                    <div class="d-inline-flex align-items-center mb-2">
                        <!-- ESTADO -->
                        <div class="dropdown me-2">
                            <a href="#"
                               class="btn btn-sm btn-outline-light dropdown-toggle estado-btn"
                               data-id="${c.id_calls}"
                               data-bs-toggle="dropdown">
                               ${c.estado_call}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                ${generarOpcionesEstado()}
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="dropdown">
                            <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow"
                               data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item editar-call" data-id="${c.id_calls}" data-bs-toggle="modal" data-bs-target="#edit_call">
                                    <i class="ti ti-edit me-1"></i>Editar
                                </a>

                                <a class="dropdown-item eliminar-call" data-id="${c.id_calls}">
                                    <i class="ti ti-trash me-1"></i>Eliminar
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <p class="mb-0">${c.desc_call}</p>
            </div>
        </div>
    `;
}

const estadosMap = {
    "ocupado": "Ocupado",
    "no disponible": "No disponible",
    "sin respuesta": "Sin respuesta",
    "numero incorrecto": "Número incorrecto",
    "mensaje de voz": "Mensaje de voz izquierdo",
    "avanzado": "Avanzado"
};


function generarOpcionesEstado() {
    let estados = [
        { label: "Ocupado", value: "ocupado" },
        { label: "No disponible", value: "no disponible" },
        { label: "Sin Respuesta", value: "sin respuesta" },
        { label: "Número incorrecto", value: "numero incorrecto" },
        { label: "Mensaje de voz izquierdo", value: "mensaje de voz" },
        { label: "Avanzado", value: "avanzado" }
    ];

    return estados
        .map(e => `
            <a class="dropdown-item cambiar-estado"
               data-estado="${e.value}">
               ${e.label}
            </a>
        `)
        .join("");
}


function actualizarEstadoLlamada(id, estado, btnRef) {
    let f = new FormData();
    f.append("accion", "actualizar_estado_call");
    f.append("id_calls", id);
    f.append("estado_call", estado);

    fetch("ajax/ajax.php", {
            method: "POST",
            body: f
        })
        .then(r => r.json())
        .then(res => {
            if (res.status === "success") {

                btnRef.textContent = estadosMap[estado]; // ← Mostrar label

                Swal.fire("Actualizado", "El estado ha sido actualizado", "success");
            }
        });
}

/*function formatearFecha(f) {
    let dt = new Date(f);
    return dt.toLocaleDateString() + ", " +
        dt.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
}*/

/* ================================
   Proxima actividad
================================ */

if (document.getElementById("formProActi")) {
    document.getElementById("formProActi").addEventListener("submit", function(e) {
        e.preventDefault();

        const datos = new FormData(this);
        datos.append("id", idLead);
        datos.append("accion", "registrar_actividad_pro");


        fetch("ajax/ajax.php", {
                method: "POST",
                body: datos
            })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    this.reset();
                    document.getElementById("cerrarModalProAct").click();
                    listarProximasActividades();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

function listarProximasActividades() {
    let f = new FormData();
    f.append("accion", "listar_proximas_actividades");
    f.append("id_lead", idLead);

    fetch("ajax/ajax.php", {
            method: "POST",
            body: f
        })
        .then(r => r.json())
        .then(res => {

            let cont = document.getElementById("contenedorActividades");
            cont.innerHTML = "";

            if (res.length === 0) {
                cont.innerHTML = "<p class='text-muted'>No hay próximas actividades.</p>";
                return;
            }

            res.forEach(a => {
                cont.innerHTML += renderActividad(a);
            });
        });
}

listarProximasActividades();

function formatearFecha(f) {
    const fecha = new Date(f);
    return fecha.toLocaleString("es-CO", {
        year: "numeric",
        month: "short",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    });
}

function renderActividad(a) {
    return `
    <div class="card-body p-3">
        <div class="d-flex flex-lg-nowrap flex-wrap row-gap-2">
            
            <span class="avatar avatar-md flex-shrink-0 rounded me-2 bg-warning">
                <i class="ti ti-user-pin fs-20"></i>
            </span>

            <div>
                <h6 class="fw-medium fs-14 mb-1">${a.tit_act}</h6>

                <p class="mb-1">${a.desc_act}</p>

                <p>${formatearFecha(a.fecha_creacion_actividad_proxima)}</p>

                <div class="card mb-0">
                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-md-4">
                                <div>
                                    <label class="form-label">Recordatorio</label>
                                    <p class="mb-0 fw-medium">${a.recor_act}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div>
                                    <label class="form-label">Prioridad de la tarea</label>
                                    <p class="mb-0 fw-medium">${a.prio_act}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div>
                                    <label class="form-label">Asignado a</label>
                                    <p class="mb-0 fw-medium">${a.nombre_usuario ?? "Sin asignar"}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>`;
}

/* ================================
   Actividades
================================ */

async function listarNota() {
    const res = await fetch("ajax/ajax.php?accion=listarNotas&id=" + idLead);
    const data = await res.json();
    return data; // <-- AHORA SÍ retorna
}


async function listarLlamada() {
    let f = new FormData();
    f.append("accion", "listar_llamadas");
    f.append("id_lead", idLead);

    const res = await fetch("ajax/ajax.php", {
        method: "POST",
        body: f
    });

    const data = await res.json();
    return data; // <-- clave
}


async function listarProximasActividade() {
    let f = new FormData();
    f.append("accion", "listar_proximas_actividades");
    f.append("id_lead", idLead);

    const res = await fetch("ajax/ajax.php", {
        method: "POST",
        body: f
    });

    const data = await res.json();
    return data; // <-- clave
}


async function actualizarTimeline() {
    // 🔥 Tus funciones ya devuelven datos, solo las llamamos
    const notas = await listarNota();
    const calls = await listarLlamada();
    const actividades = await listarProximasActividade();

    renderTimeline({ notas, calls, actividades });
}

function renderTimeline({ notas = [], calls = [], actividades = [] }) {

    const container = document.getElementById("timeline-container");
    container.innerHTML = "";

    // Convertimos cada elemento al mismo estándar
    const formattedNotas = notas.map(n => ({
        tipo: "nota",
        fecha: n.fecha_creacion_nota,
        titulo: `Nota agregada por ${n.autor || "Usuario"}`,
        descripcion: n.descripcion || n.desc_not,
        hora: formatearHora(n.fecha_creacion_nota),
        icono: "ti ti-notes",
        color: "bg-danger"
    }));

    const formattedCalls = calls.map(c => ({
        tipo: "call",
        fecha: c.fecha_creacion_call,
        titulo: `Llamada (${c.estado_call})`,
        descripcion: c.desc_call,
        hora: formatearHora(c.fecha_creacion_call),
        icono: "ti ti-phone",
        color: "bg-teal"
    }));

    const formattedActs = actividades.map(a => ({
        tipo: "actividad",
        fecha: a.fecha_creacion_actividad_proxima,
        titulo: a.tit_act,
        descripcion: a.desc_act,
        hora: formatearHora(a.fecha_creacion_actividad_proxima),
        icono: "ti ti-user-pin",
        color: "bg-warning",
        recordatorio: a.recor_act,
        prioridad: a.prio_act,
        asignado: a.nombre_user || "Sin asignar"
    }));

    // Unir todo
    let timeline = [...formattedNotas, ...formattedCalls, ...formattedActs];

    // Orden descendente
    timeline.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));

    // Agrupación
    const grupos = agruparPorDia(timeline);

    // Render
    Object.keys(grupos).forEach(dia => {

        container.innerHTML += `
            <div class="badge badge-soft-info border-0 mb-3">
                <i class="ti ti-calendar-check me-1"></i>${dia}
            </div>
        `;

        grupos[dia].forEach(item => {
            if (item.tipo === "actividad") {
                container.innerHTML += tarjetaActividad(item);
            } else {
                container.innerHTML += tarjetaSimple(item);
            }
        });
    });
}

function tarjetaSimple(item) {
    return `
        <div class="card border shadow-none mb-3 rounded-3">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap row-gap-2 align-items-start">
                    
                    <!-- ICONO -->
                    <span class="avatar avatar-md flex-shrink-0 rounded me-3 ${item.color}">
                        <i class="${item.icono} fs-20 text-white"></i>
                    </span>

                    <!-- TEXTO -->
                    <div class="flex-grow-1">
                        <h6 class="fw-medium fs-14 mb-1">${item.titulo}</h6>

                        ${item.descripcion 
                            ? `<p class="mb-1 text-muted lh-sm">${item.descripcion}</p>` 
                            : ""}

                        <p class="mb-0 fw-semibold text-dark">${item.fecha}, ${item.hora}</p>
                    </div>

                </div>
            </div>
        </div>
    `;
}


function tarjetaActividad(item) {
    return `
        <div class="card border shadow-none mb-3 rounded-3">
            <div class="card-body p-3">
                
                <div class="d-flex flex-lg-nowrap flex-wrap row-gap-2">
                    
                    <!-- ICONO -->
                    <span class="avatar avatar-md flex-shrink-0 rounded me-3 ${item.color}">
                        <i class="${item.icono} fs-20 text-white"></i>
                    </span>

                    <!-- CONTENIDO -->
                    <div class="flex-grow-1">

                        <h6 class="fw-medium fs-14 mb-1">${item.titulo}</h6>

                        <p class="mb-1 text-muted lh-sm">${item.descripcion}</p>

                        <p class="fw-semibold mb-2">${item.fecha}, ${item.hora}</p>

                        <!-- TARJETITA INTERNA -->
                        <div class="card border shadow-none rounded-2 mb-0">
                            <div class="card-body p-3">
                                <div class="row gy-3">

                                    <div class="col-md-4">
                                        <label class="form-label">Recordatorio</label>
                                        <select class="select form-select">
                                            <option selected>${item.recordatorio}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Prioridad</label>
                                        <select class="select form-select">
                                            <option selected>${item.prioridad}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Asignado a</label>
                                        <select class="select form-select">
                                            <option selected>${item.asignado}</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    `;
}


function formatearHora(fecha) {
    const d = new Date(fecha);
    return d.toLocaleTimeString("es-ES", { hour: "2-digit", minute: "2-digit" });
}

function agruparPorDia(lista) {
    const grupos = {};
    lista.forEach(item => {
        const fecha = new Date(item.fecha);
        const key = fecha.toLocaleDateString("es-ES", { day: "2-digit", month: "short", year: "numeric" });

        if (!grupos[key]) grupos[key] = [];
        grupos[key].push(item);
    });
    return grupos;
}

actualizarTimeline();

actualizarTimeline();
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
        datos.append("accion", "registrar_leads");

        fetch("ajax/ajax.php", {
                method: "POST",
                body: datos
            })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarLeads();
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
            inicializarDataTableLeads(data);
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

            const campana = data.find(c => c.cod_dep == id);
            if (!campana) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-depar").textContent = "Editar Departamento";
            document.getElementById("btn-canvas-depar").textContent = "Editar";
            // Llenar campos
            document.getElementById("nom_dep").value = campana.desc_dep;

            // Guardar ID oculto
            if (!document.getElementById("departamento_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "departamento_id";
                hidden.name = "departamento_id";
                document.getElementById("formDepart").appendChild(hidden);
            }
            document.getElementById("departamento_id").value = campana.cod_dep;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offdepartamento_add');
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
});

//Tarjetas leads.

document.addEventListener("DOMContentLoaded", () => {
    cargarKanban();
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
            1: "text-success",
            2: "text-info",
            3: "text-warning",
            4: "text-danger"
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
                                        data-bs-target="#offcanvas_edit">
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
        1: "text-success",
        2: "text-info",
        3: "text-warning",
        4: "text-danger"
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
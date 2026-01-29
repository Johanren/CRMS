//Carrera

function inicializarDataTableCarrera(carrera) {

    if ($.fn.DataTable.isDataTable('#info-carr')) {
        $('#info-carr').DataTable().clear().destroy();
    }

    $('#info-carr').DataTable({
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
            $('#info-carr .dataTables_paginate').appendTo('.datatable-paginate-carr');
            $('#info-carr .dataTables_length').appendTo('.datatable-length-carr');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": carrera,

        "columns": [
            { "data": "cod_pro" },
            { "data": "desc_pro" },
            { "data": "nlar_pro" },
            { "data": "val_pro" },
            { "data": "nom_emp" },
            {
                data: "act_pro",
                render: (data, type, row) => {

                    if (row.act_pro === "1") {
                        return `
                        <span class="badge bg-success">Activo</span>
                    `;
                    }

                    // Si act_pro NO es 1
                    return `
                        <span class="badge bg-secondary">Inactivo</span>
                    `;
                }
            }, {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarCarrera(${row.cod_pro})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarCarrera(${row.cod_pro})"
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

function listarCarr() {
    fetch("ajax/ajax.php?accion=listar_carr")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableCarrera(data);
        })
        .catch(err => console.error("Error al listar carrera:", err));
}

if (document.getElementById("formCarr")) {
    document.getElementById("formCarr").addEventListener("submit", function (e) {
        e.preventDefault();

        let datos = new FormData(this);

        // Detectar si viene carrera_id
        const carreraId = datos.get("carrera_id");

        if (carreraId && carreraId !== "") {
            datos.append("accion", "update_carr");
        } else {
            datos.append("accion", "registrar_carr");
        }

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarCarr();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-carr").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

window.editarCarrera = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_carr");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            const carr = data.find(c => c.cod_pro == id);
            if (!carr) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-carr").textContent = "Editar Carrera";
            document.getElementById("btn-canvas-carr").textContent = "Editar";
            // Llenar campos
            document.getElementById("nom_carr").value = carr.desc_pro;
            document.getElementById("nom_carr_lar").value = carr.nlar_pro;
            document.getElementById("val_carr").value = carr.val_pro;
            document.getElementById("emp_carr").value = carr.emp_pro;
            document.getElementById("act_pro").value = carr.act_pro;
            document.getElementById("contenedorActivo").style.display = "block";
            // Guardar ID oculto
            if (!document.getElementById("carrera_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "carrera_id";
                hidden.name = "carrera_id";
                document.getElementById("formCarr").appendChild(hidden);
            }
            document.getElementById("carrera_id").value = carr.cod_pro;
            // Abrir offcanvas manualmente
            let el = document.getElementById('offcarrera_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

if (document.getElementById("btnCerrarOffcanvas-carr")) {
    document.getElementById("btnCerrarOffcanvas-carr").addEventListener("click", function () {
        document.getElementById("formCarr").reset();
        document.getElementById("title-canvas-carr").textContent = "Nueva Carrera";
        document.getElementById("btn-canvas-carr").textContent = "Crear";
    });
}

window.eliminarCarrera = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_carr");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                Swal.fire("Éxito", data.message, "success");
                listarCarr();
            } else {
                Swal.fire("Error", data.message, "error");
            }

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

function listarCarreraOption() {
    fetch("ajax/ajax.php?accion=listar_carrera_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("carrera")) {
                document.getElementById("carrera").innerHTML = data.option;
            }
        });
}

function listarCarreraLIi() {
    fetch("ajax/ajax.php?accion=listar_carrera_ul")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("listar_filtro_carrera")) {
                document.getElementById("listar_filtro_carrera").innerHTML = data.option;
            }
        });
}

function obtenerPaginaActual() {
    return window.location.pathname.split('/').pop();
}
if (obtenerPaginaActual() === 'programa.php') {
    listarCarr();
}
if (obtenerPaginaActual() === 'venta.php' || obtenerPaginaActual() === 'index.php' || obtenerPaginaActual() === 'leads.php' || obtenerPaginaActual() === 'leads-detals.php' || obtenerPaginaActual() === 'leads-list.php' || obtenerPaginaActual() === 'contacts.php' || obtenerPaginaActual() === 'lead-reports.php') {
    listarCarreraOption();
    listarCarreraLIi();
}

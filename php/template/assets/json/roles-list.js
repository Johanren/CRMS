/*ROL*/
function inicializarDataTableRol(rol) {

    if ($.fn.DataTable.isDataTable('#info-rol')) {
        $('#info-rol').DataTable().clear().destroy();
    }

    $('#info-rol').DataTable({
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
            $('#info-rol .dataTables_paginate').appendTo('.datatable-paginate');
            $('#info-rol .dataTables_length').appendTo('.datatable-length');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": rol,

        "columns": [
            { "data": "nombre_rol" },
            {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarRol(${row.id_rol})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarRol(${row.id_rol})"
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

if (document.getElementById("formRol")) {
    document.getElementById("formRol").addEventListener("submit", function (e) {
        e.preventDefault();

        let datos = new FormData(this);
        datos.append("accion", "registrar_rol");

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarRol();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-rol").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

function listarRol() {
    fetch("ajax/ajax.php?accion=listar_rol")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableRol(data);
        })
        .catch(err => console.error("Error al listar rol:", err));
}

window.editarRol = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_rol");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            const rol = data.find(c => c.id_rol == id);
            if (!rol) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-rol").textContent = "Editar Rol";
            document.getElementById("btn-canvas-rol").textContent = "Editar";
            // Llenar campos
            document.getElementById("rol").value = rol.nombre_rol;

            // Guardar ID oculto
            if (!document.getElementById("rol_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "rol_id";
                hidden.name = "rol_id";
                document.getElementById("formRol").appendChild(hidden);
            }
            document.getElementById("rol_id").value = rol.id_rol;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offrol_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

if (document.getElementById("btnCerrarOffcanvas-rol")) {
    document.getElementById("btnCerrarOffcanvas-rol").addEventListener("click", function () {
        document.getElementById("formRol").reset();
        document.getElementById("title-canvas-rol").textContent = "Nuevo Rol";
        document.getElementById("btn-canvas-rol").textContent = "Crear";
    });
}

window.eliminarRol = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_rol");
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

function listarRolOption() {
    fetch("ajax/ajax.php?accion=listar_rol_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("rolS")) {
                document.getElementById("rolS").innerHTML = data.option;
            }
        });
}

function obtenerPaginaActual() {
    return window.location.pathname.split('/').pop();
}
if (obtenerPaginaActual() === 'roles-permissions.php') {
    listarRol();
    listarRolOption();
}
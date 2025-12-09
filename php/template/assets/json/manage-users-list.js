/*USER*/
function inicializarDataTableUser(rol) {

    if ($.fn.DataTable.isDataTable('#info-user')) {
        $('#info-user').DataTable().clear().destroy();
    }

    $('#info-user').DataTable({
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
            $('#info-user .dataTables_paginate').appendTo('.datatable-paginate');
            $('#info-user .dataTables_length').appendTo('.datatable-length');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": rol,

        "columns": [
            { "data": "codigo" },
            { "data": "usuario" },
            { "data": "email" },
            { "data": "nombre_rol" },
            { "data": "nom_emp" },
            { "data": "fecha_creacion" },
            {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarUser(${row.id_user})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarUser(${row.id_user})"
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

if (document.getElementById("formUser")) {
    document.getElementById("formUser").addEventListener("submit", function(e) {
        e.preventDefault();

        let datos = new FormData(this);
        let userIdElement = document.getElementById("user_id");
        let userId = userIdElement ? userIdElement.value : null;
        if (userId && parseInt(userId) > 0) {
            datos.append("accion", "actualizar_user");
        } else {
            datos.append("accion", "registrar_user");
        }

        fetch("ajax/ajax.php", {
                method: "POST",
                body: datos
            })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarUser();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-user").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

function listarUser() {
    fetch("ajax/ajax.php?accion=listar_user")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableUser(data);
        })
        .catch(err => console.error("Error al listar user:", err));
}

window.editarUser = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_user");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
        .then(res => res.json())
        .then(data => {

            const user = data.find(c => c.id_user == id);
            if (!user) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-user").textContent = "Editar Usuario";
            document.getElementById("btn-canvas-user").textContent = "Editar";
            // Llenar campos
            document.getElementById("codigoUser").value = user.codigo;
            document.getElementById("nombreUser").value = user.nombres;
            document.getElementById("apellidoUser").value = user.apellidos;
            document.getElementById("correoUser").value = user.email;
            document.getElementById("telefonoUser").value = user.telefono;
            document.getElementById("contrasenaUser").value = user.password;
            document.getElementById("rolS").value = user.id_rol;
            document.getElementById("empre").value = user.cod_emp;

            // Guardar ID oculto
            if (!document.getElementById("user_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "user_id";
                hidden.name = "user_id";
                document.getElementById("formUser").appendChild(hidden);
            }
            document.getElementById("user_id").value = user.id_user;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offUser_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

if (document.getElementById("btnCerrarOffcanvas-user")) {
    document.getElementById("btnCerrarOffcanvas-user").addEventListener("click", function() {
        document.getElementById("formUser").reset();
        document.getElementById("title-canvas-user").textContent = "Nuevo Usuario";
        document.getElementById("btn-canvas-user").textContent = "Crear";
    });
}

window.eliminarUser = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_user");
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

function listarUserOption() {
    fetch("ajax/ajax.php?accion=listar_user_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("user")) {
                document.getElementById("user").innerHTML = data.option;
            }
        });
}

function listarUserUl() {
    fetch("ajax/ajax.php?accion=listar_user_ul")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("listar_filtro_user")) {
                document.getElementById("listar_filtro_user").innerHTML = data.option;
            }
        });
}


listarUserOption();
listarUser();
listarUserUl();
//Accion

function inicializarDataTableAccion(accion) {

    if ($.fn.DataTable.isDataTable('#info-acc')) {
        $('#info-acc').DataTable().clear().destroy();
    }

    $('#info-acc').DataTable({
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
            $('#info-acc .dataTables_paginate').appendTo('.datatable-paginate-acc');
            $('#info-acc .dataTables_length').appendTo('.datatable-length-acc');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": accion,

        "columns": [
            { "data": "id_accion" },
            { "data": "nombre" }, {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarAccion(${row.id_accion})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarAccion(${row.id_fuente})"
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

function listarAcc() {
    fetch("ajax/ajax.php?accion=listar_acc")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableAccion(data);
        })
        .catch(err => console.error("Error al listar Accion:", err));
}

if (document.getElementById("formAcc")) {
    document.getElementById("formAcc").addEventListener("submit", function (e) {
        e.preventDefault();

        let datos = new FormData(this);
        datos.append("accion", "registrar_acc");

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarAcc();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-acc").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

window.editarAccion = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_acc");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            const acc = data.find(c => c.id_accion == id);
            if (!acc) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-acc").textContent = "Editar Accion";
            document.getElementById("btn-canvas-acc").textContent = "Editar";
            // Llenar campos
            document.getElementById("des_acc").value = acc.nombre;
            // Guardar ID oculto
            if (!document.getElementById("accion_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "accion_id";
                hidden.name = "accion_id";
                document.getElementById("formAcc").appendChild(hidden);
            }
            document.getElementById("accion_id").value = acc.id_accion;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offaccion_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

if (document.getElementById("btnCerrarOffcanvas-acc")) {
    document.getElementById("btnCerrarOffcanvas-acc").addEventListener("click", function () {
        document.getElementById("formACC").reset();
        document.getElementById("title-canvas-acc").textContent = "Nueva Acción";
        document.getElementById("btn-canvas-acc").textContent = "Crear";
    });
}

window.eliminarAccion = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_acc");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                Swal.fire("Éxito", data.message, "success");
                listarAcc();
            } else {
                Swal.fire("Error", data.message, "error");
            }

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

function listarAccOption() {
    fetch("ajax/ajax.php?accion=listar_acc_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("accion")) {
                document.getElementById("accion").innerHTML = data.option;
            }
        });
}

function listarAccUl() {
    fetch("ajax/ajax.php?accion=listar_acc_ul")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("listar_filtro_accion")) {
                document.getElementById("listar_filtro_accion").innerHTML = data.option;
            }
        });
}

listarAcc();
listarAccOption();
listarAccUl();
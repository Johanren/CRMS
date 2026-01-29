//Interes

function inicializarDataTableInteres(interes) {

    if ($.fn.DataTable.isDataTable('#info-ins')) {
        $('#info-ins').DataTable().clear().destroy();
    }

    $('#info-ins').DataTable({
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
            $('#info-ins .dataTables_paginate').appendTo('.datatable-paginate-ins');
            $('#info-ins .dataTables_length').appendTo('.datatable-length-ins');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": interes,

        "columns": [
            { "data": "id_interes" },
            { "data": "nombre" }, {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarInteres(${row.id_interes})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarInteres(${row.id_interes})"
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

function listarItns() {
    fetch("ajax/ajax.php?accion=listar_ins")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableInteres(data);
        })
        .catch(err => console.error("Error al listar Interes:", err));
}

if (document.getElementById("formIns")) {
    document.getElementById("formIns").addEventListener("submit", function (e) {
        e.preventDefault();

        let datos = new FormData(this);
        datos.append("accion", "registrar_ins");

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarItns();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-ins").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

window.editarInteres = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_ins");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            const ins = data.find(c => c.id_interes == id);
            if (!ins) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-ins").textContent = "Editar Horario";
            document.getElementById("btn-canvas-ins").textContent = "Editar";
            // Llenar campos
            document.getElementById("des_ins").value = ins.nombre;
            // Guardar ID oculto
            if (!document.getElementById("interes_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "interes_id";
                hidden.name = "interes_id";
                document.getElementById("formIns").appendChild(hidden);
            }
            document.getElementById("interes_id").value = ins.id_interes;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offinteres_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

if (document.getElementById("btnCerrarOffcanvas-ins")) {
    document.getElementById("btnCerrarOffcanvas-ins").addEventListener("click", function () {
        document.getElementById("formIns").reset();
        document.getElementById("title-canvas-ins").textContent = "Nuevo Horario";
        document.getElementById("btn-canvas-ins").textContent = "Crear";
    });
}

window.eliminarInteres = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_ins");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                Swal.fire("Éxito", data.message, "success");
                listarItns();
            } else {
                Swal.fire("Error", data.message, "error");
            }

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

function listarInsOption() {
    fetch("ajax/ajax.php?accion=listar_ins_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("interes")) {
                document.getElementById("interes").innerHTML = data.option;
            }
        });
}

function listarInsLi() {
    fetch("ajax/ajax.php?accion=listar_ins_ul")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("listar_filtro_interes")) {
                document.getElementById("listar_filtro_interes").innerHTML = data.option;
            }
        });
}

function obtenerPaginaActual() {
    return window.location.pathname.split('/').pop();
}
if (obtenerPaginaActual() === 'interes.php') {
    listarItns();
}
if (obtenerPaginaActual() === 'leads.php' || obtenerPaginaActual() === 'leads-list.php' || obtenerPaginaActual() === 'leads-details.php') {
    listarInsOption();
    listarInsLi();
}

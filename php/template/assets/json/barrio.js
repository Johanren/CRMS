//Barrio

function inicializarDataTableBarrio(barrio) {

    if ($.fn.DataTable.isDataTable('#info-brr')) {
        $('#info-brr').DataTable().clear().destroy();
    }

    $('#info-brr').DataTable({
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
            $('#info-brr .dataTables_paginate').appendTo('.datatable-paginate-brr');
            $('#info-brr .dataTables_length').appendTo('.datatable-length-brr');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": barrio,

        "columns": [
            { "data": "id_barrio" },
            { "data": "desc_brr" },
            { "data": "desc_ciu" },
            { "data": "desc_dep" }, {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarBarrio(${row.id_barrio})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarBarrio(${row.id_barrio})"
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

function listarBarrio() {
    fetch("ajax/ajax.php?accion=listar_barrio")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableBarrio(data);
        })
        .catch(err => console.error("Error al listar barrio:", err));
}

if (document.getElementById("formBarrio")) {
    document.getElementById("formBarrio").addEventListener("submit", function (e) {
        e.preventDefault();

        let datos = new FormData(this);
        datos.append("accion", "registrar_barrio");

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarBarrio();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-brr").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

window.editarBarrio = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_brr");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            const barrio = data.find(c => c.id_barrio == id);
            if (!barrio) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-brr").textContent = "Editar Ciudad";
            document.getElementById("btn-canvas-brr").textContent = "Editar";
            // Llenar campos
            document.getElementById("nom_brr").value = barrio.desc_brr;
            document.getElementById("ciudad").value = barrio.cod_ciu;
            // Guardar ID oculto
            if (!document.getElementById("barrio_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "barrio_id";
                hidden.name = "barrio_id";
                document.getElementById("formBarrio").appendChild(hidden);
            }
            document.getElementById("barrio_id").value = barrio.id_barrio;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offbarrio_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

if (document.getElementById("btnCerrarOffcanvas-brr")) {
    document.getElementById("btnCerrarOffcanvas-brr").addEventListener("click", function () {
        document.getElementById("formBarrio").reset();
        document.getElementById("title-canvas-brr").textContent = "Nueva Barrio";
        document.getElementById("btn-canvas-brr").textContent = "Crear";
    });
}

window.eliminarBarrio = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_brr");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                Swal.fire("Éxito", data.message, "success");
                listarBarrio();
            } else {
                Swal.fire("Error", data.message, "error");
            }

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

function listarBarrioOption() {
    fetch("ajax/ajax.php?accion=listar_barrio_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("barrio")) {
                document.getElementById("barrio").innerHTML = data.option;
            }
        });
}

function listarBarrioUl() {
    fetch("ajax/ajax.php?accion=listar_barrio_ul")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("listar_filtro_brr")) {
                document.getElementById("listar_filtro_brr").innerHTML = data.option;
            }
        });
}

function obtenerPaginaActual() {
    return window.location.pathname.split('/').pop();
}
if (obtenerPaginaActual() === 'barrio.php') {
    listarBarrio();
}
if (obtenerPaginaActual() === 'leads.php' || obtenerPaginaActual() === 'leads-detals.php' || obtenerPaginaActual() === 'leads-list.php') {
    listarBarrioOption();
    listarBarrioUl();
}
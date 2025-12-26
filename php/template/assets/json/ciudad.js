//CIUDAD

function inicializarDataTableCiudad(ciudad) {

    if ($.fn.DataTable.isDataTable('#info-ciu')) {
        $('#info-ciu').DataTable().clear().destroy();
    }

    $('#info-ciu').DataTable({
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
            $('#info-ciu .dataTables_paginate').appendTo('.datatable-paginate-ciu');
            $('#info-ciu .dataTables_length').appendTo('.datatable-length-ciu');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": ciudad,

        "columns": [
            { "data": "cod_ciu" },
            { "data": "desc_ciu" },
            { "data": "desc_dep" }, {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarCiudad(${row.cod_ciu})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarCiudad(${row.cod_ciu})"
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

function listarCiud() {
    fetch("ajax/ajax.php?accion=listar_ciudad")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableCiudad(data);
        })
        .catch(err => console.error("Error al listar ciudad:", err));
}

if (document.getElementById("formCiudad")) {
    document.getElementById("formCiudad").addEventListener("submit", function (e) {
        e.preventDefault();

        let datos = new FormData(this);
        datos.append("accion", "registrar_ciudad");

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarCiud();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-ciu").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

window.editarCiudad = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_ciu");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            const ciudad = data.find(c => c.cod_ciu == id);
            if (!ciudad) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-ciu").textContent = "Editar Ciudad";
            document.getElementById("btn-canvas-ciu").textContent = "Editar";
            // Llenar campos
            document.getElementById("nom_ciu").value = ciudad.desc_ciu;
            document.getElementById("departamento").value = ciudad.dep_ciu;
            // Guardar ID oculto
            if (!document.getElementById("ciudad_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "ciudad_id";
                hidden.name = "ciudad_id";
                document.getElementById("formCiudad").appendChild(hidden);
            }
            document.getElementById("ciudad_id").value = ciudad.cod_ciu;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offciudad_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

window.eliminarCiudad = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_ciu");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                Swal.fire("Éxito", data.message, "success");
                listarCiud();
            } else {
                Swal.fire("Error", data.message, "error");
            }

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

if (document.getElementById("btnCerrarOffcanvas-ciu")) {
    document.getElementById("btnCerrarOffcanvas-ciu").addEventListener("click", function () {
        document.getElementById("formCiudad").reset();
        document.getElementById("title-canvas-ciu").textContent = "Nueva Departamento";
        document.getElementById("btn-canvas-ciu").textContent = "Crear";
    });
}

function listarCiudOption() {
    fetch("ajax/ajax.php?accion=listar_ciudad_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("ciudad")) {
                document.getElementById("ciudad").innerHTML = data.option;
            }
        });
}

function listarCiudUl() {
    fetch("ajax/ajax.php?accion=listar_ciudad_ul")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("listar_filtro_ciudad")) {
                document.getElementById("listar_filtro_ciudad").innerHTML = data.option;
            }
        });
}

listarCiud();
listarCiudOption();
listarCiudUl();
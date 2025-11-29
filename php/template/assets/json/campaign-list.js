function inicializarDataTableCampanas(campanas) {

    if ($.fn.DataTable.isDataTable('#campaign-list')) {
        $('#campaign-list').DataTable().clear().destroy();
    }

    $('#campaign-list').DataTable({
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
            $('.dataTables_paginate').appendTo('.datatable-paginate');
            $('.dataTables_length').appendTo('.datatable-length');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": campanas,

        "columns": [{
                "render": () => '<div class="form-check form-check-md"><input class="form-check-input" type="checkbox"></div>'
            },
            { "data": "codigo" },
            { "data": "nombre" },
            { "data": "tipo_audiencia" },
            { "data": "fecha" },
            {
                "render": function(data, type, row) {
                    let class_name = "";
                    let status_name = "";

                    switch (row.status) {
                        case "0":
                            class_name = "success";
                            status_name = "Success";
                            break;
                        case "1":
                            class_name = "warning";
                            status_name = "Pending";
                            break;
                        case "2":
                            class_name = "danger";
                            status_name = "Bounced";
                            break;
                        case "3":
                            class_name = "green";
                            status_name = "Running";
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
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarCampana(${row.id_campana})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                data-id="${row.id_campana}"
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

// Registrar campañas
document.getElementById("formCampana").addEventListener("submit", function(e) {
    e.preventDefault();

    let datos = new FormData(this);
    datos.append("accion", "registrar_campana");

    fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                Swal.fire("Éxito", data.message, "success");
                listarCampanas();
                this.reset();
                document.getElementById("btnCerrarOffcanvas").click();
            } else {
                Swal.fire("Error", data.message, "error");
            }
        });
});

function listarCampanas() {
    fetch("ajax/ajax.php?accion=listar_campanas")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableCampanas(data);
        })
        .catch(err => console.error("Error al listar campañas:", err));
}

listarCampanas();

window.editarCampana = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_campana");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
        .then(res => res.json())
        .then(data => {

            const campana = data.find(c => c.id_campana == id);
            if (!campana) return;

            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas").textContent = "Editar campaña";
            document.getElementById("btn-canvas").textContent = "Editar";
            // Llenar campos
            document.getElementById("codigo").value = campana.codigo;
            document.getElementById("nombre").value = campana.nombre;
            document.getElementById("fecha").value = campana.fecha;
            document.getElementById("audiencia").value = campana.id_audiencia;

            // Guardar ID oculto
            if (!document.getElementById("campana_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "campana_id";
                hidden.name = "campana_id";
                document.getElementById("formCampana").appendChild(hidden);
            }
            document.getElementById("campana_id").value = campana.id_campana;

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

document.addEventListener("click", function(e) {
    if (e.target.closest(".dropdown-item[data-bs-target='#delete_campaign']")) {
        let id = e.target.closest(".dropdown-item").dataset.id;
        document.getElementById("delete_campaign").setAttribute("data-id", id);
    }
});

document.getElementById("btnConfirmDelete").addEventListener("click", function() {

    let id = document.getElementById("delete_campaign").getAttribute("data-id");

    if (!id) {
        Swal.fire("Error", "No se encontró ID de campaña", "error");
        return;
    }

    eliminarCampana(id);
});

document.getElementById("btnCerrarOffcanvas").addEventListener("click", function() {
    document.getElementById("formCampana").reset();
    document.getElementById("title-canvas").textContent = "Nueva Campaña";
    document.getElementById("btn-canvas").textContent = "Crear";
});

window.eliminarCampana = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_campana");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                Swal.fire("Éxito", data.message, "success");
                cargarCampanas();
            } else {
                Swal.fire("Error", data.message, "error");
            }

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};
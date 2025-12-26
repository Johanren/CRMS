//Horario

function inicializarDataTableHorario(horario) {

    if ($.fn.DataTable.isDataTable('#info-hrs')) {
        $('#info-hrs').DataTable().clear().destroy();
    }

    $('#info-hrs').DataTable({
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
            $('#info-hrs .dataTables_paginate').appendTo('.datatable-paginate-hrs');
            $('#info-hrs .dataTables_length').appendTo('.datatable-length-hrs');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": horario,

        "columns": [
            { "data": "id_horario" },
            { "data": "descripcion" }, {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarHorario(${row.id_horario})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarHorario(${row.id_horario})"
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

function listarHrs() {
    fetch("ajax/ajax.php?accion=listar_hrs")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableHorario(data);
        })
        .catch(err => console.error("Error al listar Horario:", err));
}

if (document.getElementById("formHrs")) {
    document.getElementById("formHrs").addEventListener("submit", function (e) {
        e.preventDefault();

        let datos = new FormData(this);
        datos.append("accion", "registrar_hrs");

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarHrs();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-hrs").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

window.editarHorario = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_hrs");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            const hrs = data.find(c => c.id_horario == id);
            if (!hrs) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-hrs").textContent = "Editar Horario";
            document.getElementById("btn-canvas-hrs").textContent = "Editar";
            // Llenar campos
            document.getElementById("des_hrs").value = hrs.descripcion;
            // Guardar ID oculto
            if (!document.getElementById("horario_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "horario_id";
                hidden.name = "horario_id";
                document.getElementById("formHrs").appendChild(hidden);
            }
            document.getElementById("horario_id").value = hrs.id_horario;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offhorario_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

if (document.getElementById("btnCerrarOffcanvas-hrs")) {
    document.getElementById("btnCerrarOffcanvas-hrs").addEventListener("click", function () {
        document.getElementById("formHrs").reset();
        document.getElementById("title-canvas-hrs").textContent = "Nuevo Horario";
        document.getElementById("btn-canvas-hrs").textContent = "Crear";
    });
}

window.eliminarHorario = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_hrs");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                Swal.fire("Éxito", data.message, "success");
                listarHrs();
            } else {
                Swal.fire("Error", data.message, "error");
            }

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

function listarHrsOption() {
    fetch("ajax/ajax.php?accion=listar_hrs_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("horario")) {
                document.getElementById("horario").innerHTML = data.option;
            }
        });
}

function listarHrsUl() {
    fetch("ajax/ajax.php?accion=listar_hrs_ul")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("listar_filtro_horario")) {
                document.getElementById("listar_filtro_horario").innerHTML = data.option;
            }
        });
}

listarHrs();
listarHrsOption();
listarHrsUl();

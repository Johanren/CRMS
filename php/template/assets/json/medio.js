//Medios

function inicializarDataTableMedio(medio) {

    if ($.fn.DataTable.isDataTable('#info-mdo')) {
        $('#info-mdo').DataTable().clear().destroy();
    }

    $('#info-mdo').DataTable({
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
            $('#info-mdo .dataTables_paginate').appendTo('.datatable-paginate-mdo');
            $('#info-mdo .dataTables_length').appendTo('.datatable-length-mdo');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": medio,

        "columns": [
            { "data": "cod_med" },
            { "data": "desc_med" }, {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarMedio(${row.cod_med})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarMedio(${row.cod_med})"
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

function listarMdo() {
    fetch("ajax/ajax.php?accion=listar_mdo")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableMedio(data);
        })
        .catch(err => console.error("Error al listar Medio:", err));
}

if (document.getElementById("formMdo")) {
    document.getElementById("formMdo").addEventListener("submit", function (e) {
        e.preventDefault();

        let datos = new FormData(this);
        datos.append("accion", "registrar_mdo");

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarMdo();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-mdo").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

window.editarMedio = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_mdo");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            const ins = data.find(c => c.cod_med == id);
            if (!ins) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-mdo").textContent = "Editar Medio";
            document.getElementById("btn-canvas-mdo").textContent = "Editar";
            // Llenar campos
            document.getElementById("des_mdo").value = ins.desc_med;
            // Guardar ID oculto
            if (!document.getElementById("medio_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "medio_id";
                hidden.name = "medio_id";
                document.getElementById("formMdo").appendChild(hidden);
            }
            document.getElementById("medio_id").value = ins.cod_med;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offmedio_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

if (document.getElementById("btnCerrarOffcanvas-mdo")) {
    document.getElementById("btnCerrarOffcanvas-mdo").addEventListener("click", function () {
        document.getElementById("formMdo").reset();
        document.getElementById("title-canvas-mdo").textContent = "Nuevo medio";
        document.getElementById("btn-canvas-mdo").textContent = "Crear";
    });
}

window.eliminarMedio = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_mdo");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                Swal.fire("Éxito", data.message, "success");
                listarMdo();
            } else {
                Swal.fire("Error", data.message, "error");
            }

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

function listarMdsOption() {
    fetch("ajax/ajax.php?accion=listar_mdo_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("medio")) {
                document.getElementById("medio").innerHTML = data.option;
            }
        });
}

function listarMdsLi() {
    fetch("ajax/ajax.php?accion=listar_mdo_ul")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("listar_filtro_medio")) {
                document.getElementById("listar_filtro_medio").innerHTML = data.option;
            }
        });
}

listarMdo();
listarMdsOption();
listarMdsLi();

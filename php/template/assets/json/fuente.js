//Fuente

function inicializarDataTableFuente(fuente) {

    if ($.fn.DataTable.isDataTable('#info-fnt')) {
        $('#info-fnt').DataTable().clear().destroy();
    }

    $('#info-fnt').DataTable({
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
            $('#info-fnt .dataTables_paginate').appendTo('.datatable-paginate-fnt');
            $('#info-fnt .dataTables_length').appendTo('.datatable-length-fnt');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": fuente,

        "columns": [
            { "data": "cod_fue" },
            { "data": "desc_fue" },
            { "data": "desc_med" }, {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarFuente(${row.cod_fue})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarFuente(${row.id_fuente})"
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

function listarFnt() {
    fetch("ajax/ajax.php?accion=listar_fnt")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableFuente(data);
        })
        .catch(err => console.error("Error al listar Fuente:", err));
}

if (document.getElementById("formFnt")) {
    document.getElementById("formFnt").addEventListener("submit", function (e) {
        e.preventDefault();

        let datos = new FormData(this);
        datos.append("accion", "registrar_fnt");

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarFnt();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-fnt").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

window.editarFuente = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_fnt");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            const ins = data.find(c => c.cod_fue == id);
            if (!ins) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-fnt").textContent = "Editar Fuente";
            document.getElementById("btn-canvas-fnt").textContent = "Editar";
            // Llenar campos
            document.getElementById("des_fnt").value = ins.desc_fue;
            document.getElementById("medio").value = ins.med_fue;
            // Guardar ID oculto
            if (!document.getElementById("fuente_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "fuente_id";
                hidden.name = "fuente_id";
                document.getElementById("formFnt").appendChild(hidden);
            }
            document.getElementById("fuente_id").value = ins.cod_fue;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offfuente_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

if (document.getElementById("btnCerrarOffcanvas-fnt")) {
    document.getElementById("btnCerrarOffcanvas-fnt").addEventListener("click", function () {
        document.getElementById("formFnt").reset();
        document.getElementById("title-canvas-fnt").textContent = "Nueva Fuente";
        document.getElementById("btn-canvas-fnt").textContent = "Crear";
    });
}

window.eliminarFuente = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_fnt");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                Swal.fire("Éxito", data.message, "success");
                listarFnt();
            } else {
                Swal.fire("Error", data.message, "error");
            }

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

function listarFntOption() {
    fetch("ajax/ajax.php?accion=listar_fnt_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("fuente")) {
                document.getElementById("fuente").innerHTML = data.option;
            }
        });
}

function listarFntLi() {
    fetch("ajax/ajax.php?accion=listar_fnt_ul")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("listar_filtro_fuente")) {
                document.getElementById("listar_filtro_fuente").innerHTML = data.option;
            }
        });
}

listarFnt();
listarFntLi();
listarFntOption();
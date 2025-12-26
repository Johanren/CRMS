/*DEPARTAMENTO*/
function inicializarDataTabledepartamento(departamento) {

    if ($.fn.DataTable.isDataTable('#info_dep')) {
        $('#info_dep').DataTable().clear().destroy();
    }

    $('#info_dep').DataTable({
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
            $('#info_dep .dataTables_paginate').appendTo('.datatable-paginate');
            $('#info_dep .dataTables_length').appendTo('.datatable-length');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": departamento,

        "columns": [
            { "data": "cod_dep" },
            { "data": "desc_dep" },
            {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarDepartament(${row.cod_dep})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarDepartament(${row.cod_dep})"
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

if (document.getElementById("formDepart")) {
    document.getElementById("formDepart").addEventListener("submit", function (e) {
        e.preventDefault();

        let datos = new FormData(this);
        datos.append("accion", "registrar_Depart");

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarDepart();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-depar").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

function listarDepart() {
    fetch("ajax/ajax.php?accion=listar_depar")
        .then(res => res.json())
        .then(data => {
            inicializarDataTabledepartamento(data);
        })
        .catch(err => console.error("Error al listar departamento:", err));
}

window.editarDepartament = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_depar");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            const campana = data.find(c => c.cod_dep == id);
            if (!campana) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-depar").textContent = "Editar Departamento";
            document.getElementById("btn-canvas-depar").textContent = "Editar";
            // Llenar campos
            document.getElementById("nom_dep").value = campana.desc_dep;

            // Guardar ID oculto
            if (!document.getElementById("departamento_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "departamento_id";
                hidden.name = "departamento_id";
                document.getElementById("formDepart").appendChild(hidden);
            }
            document.getElementById("departamento_id").value = campana.cod_dep;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offdepartamento_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

if (document.getElementById("btnCerrarOffcanvas-depar")) {
    document.getElementById("btnCerrarOffcanvas-depar").addEventListener("click", function () {
        document.getElementById("formDepart").reset();
        document.getElementById("title-canvas-depar").textContent = "Nueva Departamento";
        document.getElementById("btn-canvas-depar").textContent = "Crear";
    });
}

window.eliminarDepartamento = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_depar");
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

function listarDeparOption() {
    fetch("ajax/ajax.php?accion=listar_depar_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("departamento")) {
                document.getElementById("departamento").innerHTML = data.option;
            }
        });
}

function listarDeparUl() {
    fetch("ajax/ajax.php?accion=listar_depar_ul")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("listar_filtro_dep")) {
                document.getElementById("listar_filtro_dep").innerHTML = data.option;
            }
        });
}

listarDeparOption();
listarDepart();
listarDeparUl();

//Estado leads

function inicializarDataTableEstadoLeads(est_leads) {

    if ($.fn.DataTable.isDataTable('#info-est_leads')) {
        $('#info-est_leads').DataTable().clear().destroy();
    }

    $('#info-est_leads').DataTable({
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
            $('#info-est_leads .dataTables_paginate').appendTo('.datatable-paginate-est_leads');
            $('#info-est_leads .dataTables_length').appendTo('.datatable-length-est_leads');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": est_leads,

        "columns": [
            { "data": "id_estado_leads" },
            { "data": "nombre" }, {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarAccion(${row.id_estado_leads})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarAccion(${row.id_estado_leads})"
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

function listarEst_leads() {
    fetch("ajax/ajax.php?accion=listar_est_leads")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableEstadoLeads(data);
        })
        .catch(err => console.error("Error al listar Estado Leads:", err));
}

if (document.getElementById("formEst_leads")) {
    document.getElementById("formEst_leads").addEventListener("submit", function (e) {
        e.preventDefault();

        let datos = new FormData(this);
        datos.append("accion", "registrar_est_leads");

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarEst_leads();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-est_leads").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

window.editarEst_leads = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_est_leads");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            const acc = data.find(c => c.id_estado_leads == id);
            if (!acc) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-est_leads").textContent = "Editar Estado Leads";
            document.getElementById("btn-canvas-est_leads").textContent = "Editar";
            // Llenar campos
            document.getElementById("des_est_leads").value = acc.nombre;
            // Guardar ID oculto
            if (!document.getElementById("est_leads_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "est_leads_id";
                hidden.name = "est_leads_id";
                document.getElementById("formEst_leads").appendChild(hidden);
            }
            document.getElementById("est_leads_id").value = acc.id_estado_leads;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offestadoleads_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

if (document.getElementById("btnCerrarOffcanvas-est_leads")) {
    document.getElementById("btnCerrarOffcanvas-est_leads").addEventListener("click", function () {
        document.getElementById("formEst_leads").reset();
        document.getElementById("title-canvas-est_leads").textContent = "Nueva Estado Leads";
        document.getElementById("btn-canvas-est_leads").textContent = "Crear";
    });
}

window.eliminarAccion = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_est_leads");
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

function listarEst_leadsOption() {
    fetch("ajax/ajax.php?accion=listar_est_leads_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("est_leads")) {
                document.getElementById("est_leads").innerHTML = data.option;
            }
        });
}

function listarEst_leadsIl() {
    fetch("ajax/ajax.php?accion=listar_est_leads_ul")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("listar_filtro_estado")) {
                document.getElementById("listar_filtro_estado").innerHTML = data.option;
            }
        });
}

function listarCampanaIl() {
    fetch("ajax/ajax.php?accion=listar_campana_li")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("listar_filtro_campana")) {
                document.getElementById("listar_filtro_campana").innerHTML = data.option;
            }
        });
}

listarEst_leads();
listarEst_leadsOption();
listarEst_leadsIl();
listarCampanaIl();

//Campañas

function listarCampanaOption() {
    fetch("ajax/ajax.php?accion=listar_campana_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("campana")) {
                document.getElementById("campana").innerHTML = data.option;
            }
        });
}
listarCampanaOption();

$('#departamento').on('change', function () {
    let id_dep = $(this).val();

    if (id_dep !== "") {

        $("#contenedor_ciudad").show();
        $("#contenedor_barrio").hide();
        $("#barrio").html("<option value=''>Seleccione...</option>");

        fetch("ajax/ajax.php?accion=listar_ciudad_por_departamento&id_dep=" + id_dep)
            .then(res => res.json())
            .then(data => {
                $("#ciudad").html(data.option).trigger("change.select2");
            });

    } else {
        $("#contenedor_ciudad").hide();
        $("#contenedor_barrio").hide();
    }
});

$('#ciudad').on('change', function () {
    let id_ciudad = $(this).val();

    if (id_ciudad !== "") {

        $("#contenedor_barrio").show();

        fetch("ajax/ajax.php?accion=listar_barrio_por_ciudad&id_ciudad=" + id_ciudad)
            .then(res => res.json())
            .then(data => {
                $("#barrio").html(data.option).trigger("change.select2");
            });

    } else {
        $("#contenedor_barrio").hide();
    }
});

$('#medio').on('change', function () {
    let id_med = $(this).val();

    if (id_med !== "") {

        $("#contenedor_fuente").show();

        fetch("ajax/ajax.php?accion=listar_fuente_por_medio&id_med=" + id_med)
            .then(res => res.json())
            .then(data => {
                $("#fuente").html(data.option).trigger("change.select2");
            });

    } else {
        $("#contenedor_fuente").hide();
    }
});

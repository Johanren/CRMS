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
            { "data": "id_dep" },
            { "data": "nom_dep" },
            {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarDepartament(${row.id_dep})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarDepartament(${row.id_dep})"
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
    document.getElementById("formDepart").addEventListener("submit", function(e) {
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

            const campana = data.find(c => c.id_dep == id);
            if (!campana) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-depar").textContent = "Editar Departamento";
            document.getElementById("btn-canvas-depar").textContent = "Editar";
            // Llenar campos
            document.getElementById("nom_dep").value = campana.nom_dep;

            // Guardar ID oculto
            if (!document.getElementById("departamento_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "departamento_id";
                hidden.name = "departamento_id";
                document.getElementById("formDepart").appendChild(hidden);
            }
            document.getElementById("departamento_id").value = campana.id_dep;

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
    document.getElementById("btnCerrarOffcanvas-depar").addEventListener("click", function() {
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

listarDeparOption();
listarDepart();

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
            { "data": "id_ciudad" },
            { "data": "nombre" },
            { "data": "nom_dep" }, {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarCiudad(${row.id_ciudad})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarCiudad(${row.id_ciudad})"
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
    document.getElementById("formCiudad").addEventListener("submit", function(e) {
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

            const ciudad = data.find(c => c.id_ciudad == id);
            if (!ciudad) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-ciu").textContent = "Editar Ciudad";
            document.getElementById("btn-canvas-ciu").textContent = "Editar";
            // Llenar campos
            document.getElementById("nom_ciu").value = ciudad.nombre;
            document.getElementById("departamento").value = ciudad.id_departamento;
            // Guardar ID oculto
            if (!document.getElementById("ciudad_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "ciudad_id";
                hidden.name = "ciudad_id";
                document.getElementById("formCiudad").appendChild(hidden);
            }
            document.getElementById("ciudad_id").value = ciudad.id_ciudad;

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
    document.getElementById("btnCerrarOffcanvas-ciu").addEventListener("click", function() {
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

listarCiud();
listarCiudOption();

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
            { "data": "barrio" },
            { "data": "nombre" },
            { "data": "nom_dep" }, {
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
    document.getElementById("formBarrio").addEventListener("submit", function(e) {
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
            document.getElementById("nom_brr").value = barrio.nombre;
            document.getElementById("ciudad").value = barrio.ciudad_id;
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
    document.getElementById("btnCerrarOffcanvas-brr").addEventListener("click", function() {
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

listarBarrio();
listarBarrioOption();

//Carrera

function inicializarDataTableCarrera(barrio) {

    if ($.fn.DataTable.isDataTable('#info-carr')) {
        $('#info-carr').DataTable().clear().destroy();
    }

    $('#info-carr').DataTable({
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
            $('#info-carr .dataTables_paginate').appendTo('.datatable-paginate-carr');
            $('#info-carr .dataTables_length').appendTo('.datatable-length-carr');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": barrio,

        "columns": [
            { "data": "id_carrera" },
            { "data": "nombre" }, {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarCarrera(${row.id_carrera})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarCarrera(${row.id_carrera})"
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

function listarCarr() {
    fetch("ajax/ajax.php?accion=listar_carr")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableCarrera(data);
        })
        .catch(err => console.error("Error al listar carrera:", err));
}

if (document.getElementById("formCarr")) {
    document.getElementById("formCarr").addEventListener("submit", function(e) {
        e.preventDefault();

        let datos = new FormData(this);
        datos.append("accion", "registrar_carr");

        fetch("ajax/ajax.php", {
                method: "POST",
                body: datos
            })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    listarCarr();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-carr").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

window.editarCarrera = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_carr");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
        .then(res => res.json())
        .then(data => {

            const carr = data.find(c => c.id_carrera == id);
            if (!carr) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-carr").textContent = "Editar Carrera";
            document.getElementById("btn-canvas-carr").textContent = "Editar";
            // Llenar campos
            document.getElementById("nom_carr").value = carr.nombre;
            // Guardar ID oculto
            if (!document.getElementById("carrera_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "carrera_id";
                hidden.name = "carrera_id";
                document.getElementById("formCarr").appendChild(hidden);
            }
            document.getElementById("carrera_id").value = carr.id_carrera;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offcarrera_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

if (document.getElementById("btnCerrarOffcanvas-carr")) {
    document.getElementById("btnCerrarOffcanvas-carr").addEventListener("click", function() {
        document.getElementById("formCarr").reset();
        document.getElementById("title-canvas-carr").textContent = "Nueva Carrera";
        document.getElementById("btn-canvas-carr").textContent = "Crear";
    });
}

window.eliminarCarrera = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_carr");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                Swal.fire("Éxito", data.message, "success");
                listarCarr();
            } else {
                Swal.fire("Error", data.message, "error");
            }

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

function listarCarreraOption() {
    fetch("ajax/ajax.php?accion=listar_carrera_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("carrera")) {
                document.getElementById("carrera").innerHTML = data.option;
            }
        });
}

listarCarr();
listarCarreraOption();

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
    document.getElementById("formHrs").addEventListener("submit", function(e) {
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
    document.getElementById("btnCerrarOffcanvas-hrs").addEventListener("click", function() {
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

listarHrs();
listarHrsOption();

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
    document.getElementById("formIns").addEventListener("submit", function(e) {
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
    document.getElementById("btnCerrarOffcanvas-ins").addEventListener("click", function() {
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

listarItns();
listarInsOption();

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
            { "data": "id_medio" },
            { "data": "nombre" }, {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarMedio(${row.id_medio})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarMedio(${row.id_medio})"
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
    document.getElementById("formMdo").addEventListener("submit", function(e) {
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

            const ins = data.find(c => c.id_medio == id);
            if (!ins) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-mdo").textContent = "Editar Medio";
            document.getElementById("btn-canvas-mdo").textContent = "Editar";
            // Llenar campos
            document.getElementById("des_mdo").value = ins.nombre;
            // Guardar ID oculto
            if (!document.getElementById("medio_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "medio_id";
                hidden.name = "medio_id";
                document.getElementById("formMdo").appendChild(hidden);
            }
            document.getElementById("medio_id").value = ins.id_medio;

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
    document.getElementById("btnCerrarOffcanvas-mdo").addEventListener("click", function() {
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

listarMdo();
listarMdsOption();

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
            { "data": "id_fuente" },
            { "data": "nombre" }, {
                "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarFuente(${row.id_fuente})">
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
    document.getElementById("formFnt").addEventListener("submit", function(e) {
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

            const ins = data.find(c => c.id_medio == id);
            if (!ins) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-fnt").textContent = "Editar Fuente";
            document.getElementById("btn-canvas-fnt").textContent = "Editar";
            // Llenar campos
            document.getElementById("des_fnt").value = ins.nombre;
            // Guardar ID oculto
            if (!document.getElementById("fuente_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "fuente_id";
                hidden.name = "fuente_id";
                document.getElementById("formFnt").appendChild(hidden);
            }
            document.getElementById("fuente_id").value = ins.id_medio;

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
    document.getElementById("btnCerrarOffcanvas-fnt").addEventListener("click", function() {
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

listarFnt();
listarFntOption();

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
    document.getElementById("formAcc").addEventListener("submit", function(e) {
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
    document.getElementById("btnCerrarOffcanvas-acc").addEventListener("click", function() {
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

listarAcc();
listarAccOption();

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
    document.getElementById("formEst_leads").addEventListener("submit", function(e) {
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
    document.getElementById("btnCerrarOffcanvas-est_leads").addEventListener("click", function() {
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

listarEst_leads();
listarEst_leadsOption();

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

$('#departamento').on('change', function() {
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

$('#ciudad').on('change', function() {
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
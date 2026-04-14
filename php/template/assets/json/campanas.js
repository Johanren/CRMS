//CIUDAD

let paginaActual = 1;
let limiteActual = 10; // 5, 50 o 100

function inicializarDataTableCampanas(page = 1) {

    paginaActual = page;

    const f = Filtros.obtener();
    const params = new URLSearchParams();

    const loader = document.getElementById("loaderFoco");
    if (loader) loader.classList.remove("d-none");

    params.append("accion", "listar_campana");
    params.append("page", paginaActual);
    params.append("limit", limiteActual);

    fetch("ajax/ajax.php?" + params.toString())
        .then(res => res.json())
        .then(data => {

            let tbody = document.querySelector("#info-campa tbody");
            tbody.innerHTML = "";

            data.data.forEach(row => {

                let tr = `
                <tr class="text-center">
                    <td style="width:40px">
                        <button class="btn btn-sm btn-primary btnHistorial"
                        data-id="${row.cod_cam}">
                        +
                        </button>
                    </td>

                    <td class="text-start fw-bold">${row.nom_cam}</td>
                    <td>${row.fre_cam ?? "-"}</td>
                    <td>${row.fini_cam ?? "-"}</td>
                    <td>${row.ffin_cam ?? "-"}</td>
                    <td>${row.det_cam ?? "-"}</td>
                    <td>${row.act_cam}</td>
                    <td><div class="text-center">
                            <img src="ajax/${row.img_cam}" 
                                 class="img-thumbnail shadow-sm" 
                                 style="height: 80px; width: 80px; object-fit: cover; cursor: zoom-in;" 
                                 onclick="abrirVisorImagen('ajax/${row.img_cam}')"
                                 onerror="this.src='https://via.placeholder.com/40?text=No+Img'">
                        </div></td>
                    <td><div class="dropdown table-action">
                        <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="editarCampana(${row.cod_cam})">
                                <i class="ti ti-edit text-blue"></i> Edit
                            </a>
                            <a class="dropdown-item" href="#" onclick="eliminarCampana(${row.cod_cam})" data-bs-toggle="modal" data-bs-target="#delete_campaign">
                                <i class="ti ti-trash"></i> Delete
                            </a>
                        </div>
                    </div></td>
                </tr>

                <tr id="hist_${row.cod_cam}" style="display:none">
                    <td colspan="9">
                        <div class="contenidoHistorial p-2"></div>
                    </td>
                </tr>
                `;

                tbody.innerHTML += tr;
            });

            renderPaginacion(data.total, data.limit, data.page);

        })
        .catch(err => console.error("Error reporte estados:", err))
        .finally(() => {
            if (loader) loader.classList.add("d-none");
        });

}

function renderPaginacion(total, limit, page) {

    const totalPaginas = Math.ceil(total / limit);
    let html = "";

    // BOTON ANTERIOR
    if (page > 1) {
        html += `
        <button class="btn btn-sm btn-light"
        onclick="inicializarDataTableCampanas(${page - 1})">
        &lt;
        </button>`;
    }

    let start = Math.max(1, page - 2);
    let end = Math.min(totalPaginas, page + 2);

    // Si estamos cerca del inicio
    if (page <= 4) {
        start = 1;
        end = Math.min(8, totalPaginas);
    }

    // Si estamos cerca del final
    if (page > totalPaginas - 4) {
        start = Math.max(1, totalPaginas - 7);
        end = totalPaginas;
    }

    // Primera página si no está visible
    if (start > 1) {
        html += `
        <button class="btn btn-sm btn-light"
        onclick="inicializarDataTableCampanas(1)">
        1
        </button>`;

        if (start > 2) {
            html += `<span class="mx-1">...</span>`;
        }
    }

    // PAGINAS
    for (let i = start; i <= end; i++) {

        html += `
        <button class="btn btn-sm ${i == page ? 'btn-primary' : 'btn-light'}"
        onclick="inicializarDataTableCampanas(${i})">
        ${i}
        </button>`;
    }

    // Última página si no está visible
    if (end < totalPaginas) {

        if (end < totalPaginas - 1) {
            html += `<span class="mx-1">...</span>`;
        }

        html += `
        <button class="btn btn-sm btn-light"
        onclick="inicializarDataTableCampanas(${totalPaginas})">
        ${totalPaginas}
        </button>`;
    }

    // BOTON SIGUIENTE
    if (page < totalPaginas) {
        html += `
        <button class="btn btn-sm btn-light"
        onclick="inicializarDataTableCampanas(${page + 1})">
        &gt;
        </button>`;
    }

    document.getElementById("paginacion").innerHTML = html;
}

document.addEventListener("click", function (e) {

    if (e.target.classList.contains("btnHistorial")) {

        let idlead = e.target.dataset.id;
        let fila = document.getElementById("hist_" + idlead);

        if (fila.style.display === "table-row") {
            fila.style.display = "none";
            return;
        }

        const params = new URLSearchParams();
        params.append("accion", "listar_campanaxmedio");
        params.append("id_campana", idlead);

        fetch("ajax/ajax.php?" + params.toString())

            .then(res => res.json())

            .then(data => {

                let html = `

                        <table class="table table-bordered">
                        <thead>
                        <tr class="table-light">
                            <th>Campaña</th>
                            <th>Medio</th>
                            <th>Fuente</th>
                            <th>Fecha</th>
                            <th>RSC</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>

                        `;

                data.forEach(h => {

                    html += `
                        <tr>
                            <td>${h.nom_cam ?? "-"}</td>
                            <td>${h.desc_med ?? "-"}</td>
                            <td>${h.desc_fue ?? "-"}</td>
                            <td>${h.fec_cxm ?? "-"}</td>
                            <td>${h.rsc_cxm ?? "-"}</td>
                            <td><div class="dropdown table-action">
                                <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" onclick="editarCampanaxmedio(${h.rsc_cxm})">
                                        <i class="ti ti-edit text-blue"></i> Edit
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="eliminarCampanaxmedio(${h.rsc_cxm})" data-bs-toggle="modal" data-bs-target="#delete_campaign">
                                        <i class="ti ti-trash"></i> Delete
                                    </a>
                                </div>
                            </div></td>
                        </tr>

                    `;

                });

                html += "</tbody></table>";

                fila.querySelector(".contenidoHistorial").innerHTML = html;

                fila.style.display = "table-row";

            })

            .catch(err => console.error("Error historial:", err));

    }

});

document.getElementById("limitSelect").addEventListener("change", function () {

    limiteActual = parseInt(this.value);
    inicializarDataTableCampanas(1);

});

/*function inicializarDataTableCampanas(ciudad) {
    if ($.fn.DataTable.isDataTable('#info-campa')) {
        $('#info-campa').DataTable().clear().destroy();
    }

    $('#info-campa').DataTable({
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
            $('#info-campa .dataTables_paginate').appendTo('.datatable-paginate-campa');
            $('#info-campa .dataTables_length').appendTo('.datatable-length-campa');
        },
        "data": ciudad,
        "columns": [
            { "data": "cod_cam" },
            { "data": "nom_cam" },
            { "data": "fre_cam" },
            { "data": "fini_cam" },
            { "data": "ffin_cam" },
            { "data": "det_cam" },
            { "data": "act_cam" },
            {
                "data": "img_cam",
                "render": function (data) {
                    return `
                        <div class="text-center">
                            <img src="ajax/${data}" 
                                 class="img-thumbnail shadow-sm" 
                                 style="height: 80px; width: 80px; object-fit: cover; cursor: zoom-in;" 
                                 onclick="abrirVisorImagen('ajax/${data}')"
                                 onerror="this.src='https://via.placeholder.com/40?text=No+Img'">
                        </div>`;
                }
            },
            {
                "render": (data, type, row) => `
                    <div class="dropdown table-action">
                        <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="editarCampana(${row.cod_cam})">
                                <i class="ti ti-edit text-blue"></i> Edit
                            </a>
                            <a class="dropdown-item" href="#" onclick="eliminarCampana(${row.cod_cam})" data-bs-toggle="modal" data-bs-target="#delete_campaign">
                                <i class="ti ti-trash"></i> Delete
                            </a>
                        </div>
                    </div>`
            }
        ]
    });
}*/

/*function inicializarDataTableCampanasxmedio(ciudad) {
    if ($.fn.DataTable.isDataTable('#info-campa-fuente')) {
        $('#info-campa-fuente').DataTable().clear().destroy();
    }

    $('#info-campa-fuente').DataTable({
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
            $('#info-campa-fuente .dataTables_paginate').appendTo('.datatable-paginate-campa-fuente');
            $('#info-campa-fuente .dataTables_length').appendTo('.datatable-length-campa-fuente');
        },
        "data": ciudad,
        "columns": [
            { "data": "nom_cam" },
            { "data": "desc_med" },
            { "data": "desc_fue" },
            { "data": "fec_cxm" },
            { "data": "rsc_cxm" },
            {
                "render": (data, type, row) => `
                    <div class="dropdown table-action">
                        <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="editarCampanaxmedio(${row.rsc_cxm})">
                                <i class="ti ti-edit text-blue"></i> Edit
                            </a>
                            <a class="dropdown-item" href="#" onclick="eliminarCampanaxmedio(${row.rsc_cxm})" data-bs-toggle="modal" data-bs-target="#delete_campaign">
                                <i class="ti ti-trash"></i> Delete
                            </a>
                        </div>
                    </div>`
            }
        ]
    });
}*/

// --- LÓGICA DEL VISOR DE IMÁGENES ---
let escala = 1;
let moviendose = false;
let startPos = { x: 0, y: 0 };
let currentPos = { x: 0, y: 0 };

function abrirVisorImagen(src) {
    const img = document.getElementById('imgZoom');
    img.src = src;
    resetearImagen();
    const modal = new bootstrap.Modal(document.getElementById('modalVisor'));
    modal.show();
}

function ajustarZoom(factor) {
    escala *= factor;
    // Límite de zoom mínimo
    if (escala < 0.5) escala = 0.5;
    aplicarTransformacion();
}

function resetearImagen() {
    escala = 1;
    currentPos = { x: 0, y: 0 };
    aplicarTransformacion();
}

function aplicarTransformacion() {
    const img = document.getElementById('imgZoom');
    img.style.transform = `translate(${currentPos.x}px, ${currentPos.y}px) scale(${escala})`;
}

// Manejo de arrastre y mouse
document.addEventListener('DOMContentLoaded', () => {
    const img = document.getElementById('imgZoom');
    const contenedor = img.parentElement;

    img.onmousedown = (e) => {
        e.preventDefault();
        moviendose = true;
        startPos = { x: e.clientX - currentPos.x, y: e.clientY - currentPos.y };
        img.style.cursor = 'grabbing';
    };

    window.onmousemove = (e) => {
        if (!moviendose) return;
        currentPos = { x: e.clientX - startPos.x, y: e.clientY - startPos.y };
        aplicarTransformacion();
    };

    window.onmouseup = () => {
        moviendose = false;
        img.style.cursor = 'grab';
    };

    // Zoom con la rueda del ratón
    contenedor.onwheel = (e) => {
        e.preventDefault();
        const delta = e.deltaY > 0 ? 0.9 : 1.1;
        ajustarZoom(delta);
    };
});

/*function ListarCmapana() {
    fetch("ajax/ajax.php?accion=listar_campana")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableCampanas(data);
        })
        .catch(err => console.error("Error al listar ciudad:", err));
}*/

/*function ListarCmapanaxmedio() {
    fetch("ajax/ajax.php?accion=listar_campanaxmedio")
        .then(res => res.json())
        .then(data => {
            inicializarDataTableCampanasxmedio(data);
        })
        .catch(err => console.error("Error al listar ciudad:", err));
}*/

if (document.getElementById("formCampanas")) {
    document.getElementById("formCampanas").addEventListener("submit", function (e) {
        e.preventDefault();

        let datos = new FormData(this);
        let userIdElement = document.getElementById("campana_id");
        let userId = userIdElement ? userIdElement.value : null;
        if (userId && parseInt(userId) > 0) {
            datos.append("accion", "actualizar_campanas");
        } else {
            datos.append("accion", "registrar_campanas");
        }

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    inicializarDataTableCampanas();
                    listarCampanasOption();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-campa").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

if (document.getElementById("formCampanasMedio")) {
    document.getElementById("formCampanasMedio").addEventListener("submit", function (e) {
        e.preventDefault();

        let datos = new FormData(this);
        let userIdElement = document.getElementById("campanaxmedio_id");
        let userId = userIdElement ? userIdElement.value : null;
        if (userId && parseInt(userId) > 0) {
            datos.append("accion", "actualizar_campanasxmedio");
        } else {
            datos.append("accion", "registrar_campanasxmedio");
        }

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    inicializarDataTableCampanas();
                    this.reset();
                    document.getElementById("btnCerrarOffcanvas-campaMedio").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

window.editarCampana = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_campanas");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            const ciudad = data.find(c => c.cod_cam == id);
            if (!ciudad) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-ciu").textContent = "Editar Campaña";
            document.getElementById("btncampanas").textContent = "Editar";
            // Llenar campos
            document.getElementById("nom_cam").value = ciudad.nom_cam;
            document.getElementById("fre_cam").value = ciudad.fre_cam;
            document.getElementById("fini_cam").value = ciudad.fini_cam;
            document.getElementById("ffin_cam").value = ciudad.ffin_cam;
            document.getElementById("det_cam").value = ciudad.det_cam;
            document.getElementById("act_cam").value = ciudad.act_cam;
            // Guardar ID oculto
            if (!document.getElementById("campana_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "campana_id";
                hidden.name = "campana_id";
                document.getElementById("formCampanas").appendChild(hidden);
            }
            if (!document.getElementById("img_cam_edit")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "img_cam_edit";
                hidden.name = "img_cam_edit";
                document.getElementById("formCampanas").appendChild(hidden);
            }
            document.getElementById("img_cam_edit").value = ciudad.img_cam;
            document.getElementById("campana_id").value = ciudad.cod_cam;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offcampana_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

window.eliminarCampana = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_campanas");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                Swal.fire("Éxito", data.message, "success");
                ListarCmapana();
            } else {
                Swal.fire("Error", data.message, "error");
            }

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

window.editarCampanaxmedio = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_campanasxmedio");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            const ciudad = data.find(c => c.rsc_cxm == id);
            if (!ciudad) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-camp").textContent = "Editar Campaña";
            document.getElementById("btncampanasMedio").textContent = "Editar";
            // Llenar campos
            document.getElementById("cam_cxm").value = ciudad.cam_cxm;
            document.getElementById("medio").value = ciudad.med_cxm;
            document.getElementById("contenedor_fuente").style.display = "block";
            document.getElementById("fuente").value = ciudad.fue_cxm;
            document.getElementById("fec_cxm").value = ciudad.fec_cxm;
            document.getElementById("rsc_cxm").value = ciudad.rsc_cxm;
            // Guardar ID oculto
            if (!document.getElementById("campanaxmedio_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "campanaxmedio_id";
                hidden.name = "campanaxmedio_id";
                document.getElementById("formCampanasMedio").appendChild(hidden);
            }
            document.getElementById("campanaxmedio_id").value = ciudad.rsc_cxm;

            // Abrir offcanvas manualmente
            let el = document.getElementById('offcampanaMedio_add');
            let offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
            offcanvas.show();

        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "No se pudo cargar la información", "error");
        });

};

window.eliminarCampanaxmedio = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_campanasxmedio");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {
                Swal.fire("Éxito", data.message, "success");
                ListarCmapanaxmedio();
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

function listarCampanasOption() {
    fetch("ajax/ajax.php?accion=listar_campanas_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("cam_cxm")) {
                document.getElementById("cam_cxm").innerHTML = data.option;
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
function obtenerPaginaActual() {
    return window.location.pathname.split('/').pop();
}
if (obtenerPaginaActual() === 'campanas.php') {
    inicializarDataTableCampanas();
    //ListarCmapanaxmedio();
}
if (obtenerPaginaActual() === 'leads.php' || obtenerPaginaActual() === 'leads-details.php' || obtenerPaginaActual() === 'leads-list.php' || obtenerPaginaActual() === 'campanas.php') {
    listarCampanasOption();
    //listarCiudUl();
}

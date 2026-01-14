function paginaActual() {
    return window.location.pathname.split("/").pop();
}

let debounceTimer = null;

function ejecutarCargaOptimizada() {
    clearTimeout(debounceTimer);

    debounceTimer = setTimeout(async () => {

        const page = paginaActual();
        const tareas = [];

        // 🔵 leads.php → Kanban
        if (page === "leads.php") {
            tareas.push(cargarKanban());
        }

        // 🟢 leads-list.php → Listado
        if (page === "leads-list.php") {
            tareas.push(listarLeads());
        }

        // 🟣 contacts.php → Grid contactos
        if (page === "contacts.php") {
            tareas.push(cargarContactGrid());
        }

        if (tareas.length > 0) {
            await Promise.all(tareas);
        }

    }, 300);
}

const params = new URLSearchParams(window.location.search);

/* ==================
Cargar proxima actividad LEADS
====================*/
if (document.getElementById("offproximaActividad")) {
    const offcanvasActividad = document.getElementById("offproximaActividad");

    offcanvasActividad.addEventListener("shown.bs.offcanvas", () => {
        cargarProximaActividad();
    });

    document.getElementById("fechaActividadInicio").addEventListener("change", cargarProximaActividad);
    document.getElementById("fechaActividadFin").addEventListener("change", cargarProximaActividad);
}

function renderDataTableProximaActividad(data) {

    if ($.fn.DataTable.isDataTable('#tablaProximaActividad')) {
        $('#tablaProximaActividad').DataTable().clear().destroy();
    }

    $('#tablaProximaActividad').DataTable({
        data: data,
        ordering: true,
        autoWidth: false,
        pageLength: 5,

        scrollY: "280px",
        scrollCollapse: true,

        bFilter: false,
        bInfo: false,

        language: {
            sLengthMenu: '_MENU_',
            paginate: {
                next: '<i class="ti ti-chevron-right"></i>',
                previous: '<i class="ti ti-chevron-left"></i>'
            }
        },

        initComplete: () => {
            $('#tablaProximaActividad_wrapper .dataTables_paginate')
                .appendTo('.datatable-paginate');

            $('#tablaProximaActividad_wrapper .dataTables_length')
                .appendTo('.datatable-length');
        },

        columns: [
            { data: "fecha" },
            {
                data: null,
                render: function (row) {
                    return `${row.nombres} ${row.apellidos}`;
                }
            },
            { data: "telefono_principal" },
            {
                data: "desc_act",
                render: function (data) {
                    return `
                        <div style="max-width:260px; white-space:normal;">
                            ${data || "-"}
                        </div>
                    `;
                }
            },
            {
                data: "prio_act",
                render: function (data) {
                    let color = "secondary";
                    if (data === "alto") color = "danger";
                    if (data === "medio") color = "warning";
                    if (data === "baja") color = "success";

                    return `<span class="badge bg-${color}">${data}</span>`;
                }
            },
            {
                data: null,
                render: function (row) {
                    return `<a href="leads-details.php?id=${row.id_lead}&id_cliente=${row.id_cliente}&id_actividad=${row.cod_act}">Ver</a>`;
                }
            },
        ]
    });
}

function cargarProximaActividad() {
    const inicio = document.getElementById("fechaActividadInicio").value;
    const fin = document.getElementById("fechaActividadFin").value;

    const datos = new FormData();
    datos.append("accion", "listar_proxima_actividad");
    datos.append("fecha_inicio", inicio);
    datos.append("fecha_fin", fin);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {
            renderDataTableProximaActividad(data);
        });
}


/*agregar en modal registro cliente notas*/
if (document.getElementById("btnMostrarNota")) {
    const btnMostrarNota = document.getElementById("btnMostrarNota");
    const contenedorNota = document.getElementById("contenedorNota");
    const btnCancelarNota = document.getElementById("cancelarNota");

    btnMostrarNota.addEventListener("click", () => {
        contenedorNota.style.display = "block";
        btnMostrarNota.style.display = "none"; // Oculta el botón
    });

    btnCancelarNota.addEventListener("click", () => {
        contenedorNota.style.display = "none";
        btnMostrarNota.style.display = "inline-block"; // Muestra el botón de nuevo

        // Limpiar campos
        //document.getElementById("tit_not").value = "";
        document.getElementById("desc_not").value = "";
        document.getElementById("desc_arch").value = "";
        document.getElementById("preview-archivos").innerHTML = "";
    });
}

/*agregar en modal registro cliente actividad notas*/
if (document.getElementById("btnMostrarAtividad")) {
    const btnMostrarNota = document.getElementById("btnMostrarAtividad");
    const contenedorNota = document.getElementById("contenedorActividad");
    const btnCancelarNota = document.getElementById("cancelarActividad");

    btnMostrarNota.addEventListener("click", () => {
        contenedorNota.style.display = "block";
        btnMostrarNota.style.display = "none"; // Oculta el botón
    });

    btnCancelarNota.addEventListener("click", () => {
        contenedorNota.style.display = "none";
        btnMostrarNota.style.display = "inline-block"; // Muestra el botón de nuevo

        // Limpiar campos
        //document.getElementById("tit_not").value = "";
        document.getElementById("descProAct").value = "";
        document.getElementById("recor_act").value = "";
        document.getElementById("prio_act").innerHTML = "";
    });
}

/*agregar en modal registro cliente actividad llamadas*/
if (document.getElementById("btnMostrarAtividadLlamada")) {
    const btnMostrarNota = document.getElementById("btnMostrarAtividadLlamada");
    const contenedorNota = document.getElementById("contenedorActividadLlamada");
    const btnCancelarNota = document.getElementById("cancelarActividadLlamada");

    btnMostrarNota.addEventListener("click", () => {
        contenedorNota.style.display = "block";
        btnMostrarNota.style.display = "none"; // Oculta el botón
    });

    btnCancelarNota.addEventListener("click", () => {
        contenedorNota.style.display = "none";
        btnMostrarNota.style.display = "inline-block"; // Muestra el botón de nuevo

        // Limpiar campos
        //document.getElementById("tit_not").value = "";
        document.getElementById("descProAct").value = "";
        document.getElementById("recor_act").value = "";
        document.getElementById("prio_act").innerHTML = "";
    });
}

if (document.getElementById("btnAgregarNumero")) {
    const btnAgregarNumero = document.getElementById("btnAgregarNumero");
    const contenedorNumeros = document.getElementById("contenedorNumeros");
    const listaNumeros = document.getElementById("listaNumeros");
    const template = document.getElementById("template-numero");
    const btnNuevoNumero = document.getElementById("btnNuevoNumero");
    let clienteIdGlobal = params.get("id_cliente") || 0;

    // 3️⃣ Mostrar contenedor de números adicionales
    btnAgregarNumero.addEventListener("click", () => {
        contenedorNumeros.style.display = "block";

        if (listaNumeros.children.length === 0) {
            agregarNumeroAdicional({}, false, clienteIdGlobal);
        }
    });

    // 4️⃣ Crear un número adicional nuevo
    function agregarNumero() {
        const clone = template.content.cloneNode(true);
        const selectIndicativo = clone.querySelector(".indicativo");

        // Botón eliminar
        clone.querySelector(".btnEliminarNumero").addEventListener("click", function () {
            this.closest(".numeroItem").remove();

            if (listaNumeros.children.length === 0) {
                contenedorNumeros.style.display = "none";
            }
        });

        listaNumeros.appendChild(clone);
    }

    function existeNumeroEnLista(data) {
        const items = listaNumeros.querySelectorAll(".numeroItem");

        for (let item of items) {

            // 🔹 Si viene de BD → comparar por ID
            if (data.id_numero_adicional && item.dataset.id) {
                if (item.dataset.id == data.id_numero_adicional) {
                    return true;
                }
            }

            // 🔹 Si no tiene ID → comparar por número + indicativo
            const numeroTxt = item.querySelector(".numeroTxt")?.textContent.trim();

            if (
                numeroTxt === (data.telefono || data.numero)
            ) {
                return true;
            }
        }

        return false;
    }

    function agregarNumeroAdicional(data = {}, desdeBD = false, id_cliente = 0) {

        // 🚫 EVITAR DUPLICADOS
        if (desdeBD && existeNumeroEnLista(data)) {
            return;
        }

        const clone = template.content.cloneNode(true);
        const item = clone.querySelector(".numeroItem");

        const indicativo = clone.querySelector(".indicativo");
        const numero = clone.querySelector(".numeroTel");
        const desc = clone.querySelector(".descNumero");

        const indicativoTxt = clone.querySelector(".indicativoTxt");
        const numeroTxt = clone.querySelector(".numeroTxt");
        const descTxt = clone.querySelector(".descTxt");

        const bloqueTexto = clone.querySelector(".telefonoTexto");
        const bloqueInputs = clone.querySelectorAll(".telefonoInput");

        // Valores
        indicativo.value = data.indicativo || "";
        numero.value = data.numero || data.telefono || "";
        desc.value = data.descripcion || "";

        // ID BD
        if (data.id_numero_adicional) {
            item.dataset.id = data.id_numero_adicional;
        }

        // 👉 SI VIENE DE BD → TEXTO
        if (desdeBD) {
            indicativoTxt.textContent = data.indicativo;
            numeroTxt.textContent = data.telefono;
            descTxt.textContent = data.descripcion || "";

            bloqueTexto.classList.remove("d-none");
            bloqueInputs.forEach(i => i.classList.add("d-none"));
        }
        // 👉 GUARDAR AL PERDER FOCO
        [indicativo, numero, desc].forEach(input => {
            input.addEventListener("keydown", e => {
                if (e.key === "Enter") {
                    e.preventDefault();
                    guardarTelefono(item, id_cliente);
                }
            });
        });

        if (clone.querySelector(".editarNumero")) {
            // 👉 EDITAR
            clone.querySelector(".editarNumero").addEventListener("click", () => {
                bloqueTexto.classList.add("d-none");
                bloqueInputs.forEach(i => i.classList.remove("d-none"));
                indicativo.focus();
            });
        }

        // 👉 ELIMINAR
        clone.querySelector(".btnEliminarNumero").addEventListener("click", () => {
            item.remove();
            eliminarTelefono(item);
        });

        contenedorNumeros.style.display = "block";
        listaNumeros.appendChild(clone);
    }

    function eliminarTelefono(item) {
        const numero = item.querySelector(".numeroTel").value.trim();
        const datos = new FormData();
        datos.append("accion", "eliminar_telefono_adicional");
        datos.append("telefono", numero);


        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(resp => {

                if (!resp.status) {
                    console.error("Error al eliminar teléfono", resp);
                    return;
                } else {
                    Swal.fire("Eliminado", "Número eliminado", "success");
                }

            })
            .catch(err => console.error("Error AJAX:", err));
    }

    function guardarTelefono(item, id_cliente) {

        const indicativo = item.querySelector(".indicativo").value.trim();
        const numero = item.querySelector(".numeroTel").value.trim();
        const descripcion = item.querySelector(".descNumero").value.trim();

        if (!numero) return;

        const esUpdate = !!item.dataset.id; // 🔥 clave

        const datos = new FormData();
        datos.append("accion", esUpdate ? "actualizar_telefono_adicional" : "guardar_telefono_adicional");
        datos.append("cliente_id", id_cliente);
        datos.append("indicativo", indicativo);
        datos.append("telefono", numero);
        datos.append("descripcion", descripcion);
        datos.append("id", item.dataset.id);


        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(resp => {

                if (!resp.status) {
                    console.error("Error al guardar teléfono", resp);
                    return;
                }

                // 🆔 Si fue INSERT, asignar nuevo ID
                if (!esUpdate) {
                    item.dataset.id = resp.id;
                }

                // 📝 Pasar a texto
                item.querySelector(".indicativoTxt").textContent = indicativo;
                item.querySelector(".numeroTxt").textContent = numero;
                item.querySelector(".descTxt").textContent = descripcion || "";

                item.querySelector(".telefonoTexto").classList.remove("d-none");
                item.querySelectorAll(".telefonoInput").forEach(i => i.classList.add("d-none"));
            })
            .catch(err => console.error("Error AJAX:", err));
    }

    /*Busqueda existencia cliente */

    if (document.getElementById("identificacionLeads")) {
        ["identificacionLeads", "telefonoLeads"].forEach(id => {
            const campo = document.getElementById(id);

            campo.addEventListener("blur", async function () {
                let valor = this.value.trim();
                if (valor === "") return;

                const datos = new FormData();
                datos.append("accion", "buscar_cliente");
                datos.append("valor", valor);

                try {
                    let response = await fetch("ajax/ajax.php", {
                        method: "POST",
                        body: datos
                    });

                    let data = await response.json();

                    if (data.status === "existe") {

                        Swal.fire("Cliente ya creado", data.message, "warning");

                        if (data.cliente) {
                            let c = data.cliente;

                            document.getElementById("id_cliente_leads").value = c.id_cliente;
                            document.getElementById("nombresLeads").value = c.nombres || "";
                            document.getElementById("apellidosLeads").value = c.apellidos || "";
                            document.getElementById("telefonoLeads").value = c.telefono_principal || "";
                            document.getElementById("correoLeads").value = c.email || "";
                            document.getElementById("direLeads").value = c.direccion || "";
                        }

                        //  🔥  NUEVO: cargar números adicionales
                        if (data.numeros_adicionales && data.numeros_adicionales.length > 0) {

                            // Si ya tienes un contenedor dinámico lo llenas aquí
                            data.numeros_adicionales.forEach(num => {
                                agregarNumeroAdicional(num, false, 0);
                            });
                        }
                    }

                } catch (error) {
                    console.error("Error en la validación:", error);
                }
            });
        });
    }
    btnNuevoNumero.addEventListener("click", () => agregarNumeroAdicional({}, false, clienteIdGlobal));


}


/*Fecha inicio fin */

$(function () {

    $('#reportrange').daterangepicker(
        {
            opens: "left",
            autoUpdateInput: false,
            locale: {
                format: "YYYY-MM-DD",
                applyLabel: "Aplicar",
                cancelLabel: "Cancelar",
                daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                monthNames: [
                    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                ]
            }
        },
        function (start, end) {

            // Guardar fechas globales
            window.fecha_inicio = start.format("YYYY-MM-DD");
            window.fecha_fin = end.format("YYYY-MM-DD");

            // Mostrar en el span
            document.querySelector(".reportrange-picker-field").innerHTML =
                start.format("DD MMM YY") + " - " + end.format("DD MMM YY");

            // Llamar a función principal
            ejecutarCargaOptimizada();
        }
    );
});


/*=============
FILTROS
=============*/

window.Filtros = {
    obtener: function () {
        let texto = "";
        let inputBuscador = document.getElementById("buscador");
        if (inputBuscador) {
            texto = inputBuscador.value.toLowerCase();
        }

        let asesor = [...document.querySelectorAll(".filtro-asesor:checked")].map(c => c.value);
        let carreras = [...document.querySelectorAll(".filtro-carrera:checked")].map(c => c.value);
        let horario = [...document.querySelectorAll(".filtro-horario:checked")].map(c => c.value);
        let interes = [...document.querySelectorAll(".filtro-interes:checked")].map(c => c.value);
        let medio = [...document.querySelectorAll(".filtro-medio:checked")].map(c => c.value);
        let fuente = [...document.querySelectorAll(".filtro-fuente:checked")].map(c => c.value);
        let campana = [...document.querySelectorAll(".filtro-campana:checked")].map(c => c.value);
        let accion = [...document.querySelectorAll(".filtro-accion:checked")].map(c => c.value);
        let departamento = [...document.querySelectorAll(".filtro-dep:checked")].map(c => c.value);
        let ciudad = [...document.querySelectorAll(".filtro-ciu:checked")].map(c => c.value);
        let barrio = [...document.querySelectorAll(".filtro-brr:checked")].map(c => c.value);
        let estados = [...document.querySelectorAll(".filtro-estado:checked")].map(c => c.value);
        let fecha_inicio = window.fecha_inicio || "";
        let fecha_fin = window.fecha_fin || "";

        return { texto, asesor, carreras, horario, interes, medio, fuente, campana, accion, departamento, ciudad, barrio, estados, fecha_inicio, fecha_fin };
    }
};

/*LEADS*/
function inicializarDataTableLeads(leads) {

    if ($.fn.DataTable.isDataTable('#leads_list')) {
        $('#leads_list').DataTable().clear().destroy();
    }

    $('#leads_list').DataTable({
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
            $('#leads_list .dataTables_paginate').appendTo('.datatable-paginate');
            $('#leads_list .dataTables_length').appendTo('.datatable-length');
        },

        // 🔥 AQUÍ SE CARGA TU DATA DINÁMICA
        "data": leads,

        "columns": [{
            data: null,
            render: function (row) {
                return `<a href="leads-details.php?id=${row.id_lead}&id_cliente=${row.id_cliente}">${row.nombres} ${row.apellidos}</a>`;
            }
        },
        { "data": "desc_pro" },
        { "data": "telefono_principal" },
        {
            "render": function (data, type, row) {
                let class_name = "";
                let status_name = "";

                switch (row.estado_leads_id) {
                    case "1":
                        class_name = "info";
                        status_name = "Nuevo Leads";
                        break;
                    case "2":
                        class_name = "info";
                        status_name = "Leads Activo";
                        break;
                    case "3":
                        class_name = "warning";
                        status_name = "Interesado";
                        break;
                    case "4":
                        class_name = "warning";
                        status_name = "En Desición";
                        break;
                    case "5":
                        class_name = "success";
                        status_name = "Matricula en proceso";
                        break;
                    case "6":
                        class_name = "success";
                        status_name = "Matriculado";
                        break;
                    default:
                        class_name = "danger";
                        status_name = "Perdido";
                        break;
                }

                return `<span class="badge badge-pill badge-status bg-${class_name}">${status_name}</span>`;
            }
        },
        {
            data: null, // importante: recibir toda la fila
            render: function (row) {
                let nombre = row.nombreAsesor || "";
                let apellido = row.apellidoAsesor || "";

                if (nombre === "" && apellido === "") {
                    return "<span class='text-muted'>No hay asesor asignado</span>";
                }

                return nombre + " " + apellido;
            }
        },
        { "data": "fecha_creacion" },
        {
            "render": (data, type, row) => `
                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick="editarLeads(${row.id_lead})">
                                    <i class="ti ti-edit text-blue"></i> Edit
                                </a>
                                <!--<a class="dropdown-item" 
                                href="#" 
                                onclick="eliminarLeads(${row.cod_dep})"
                                data-bs-toggle="modal" 
                                data-bs-target="#delete_campaign">
                                    <i class="ti ti-trash"></i> Delete
                                </a>-->
                            </div>
                        </div>
                    `
        }
        ]
    });
}

if (document.getElementById("formLeads")) {
    document.getElementById("formLeads").addEventListener("submit", function (e) {
        e.preventDefault();

        let numeros = [];

        document.querySelectorAll(".numeroItem").forEach(item => {
            let indicativo = item.querySelector(".indicativo").value;
            let numero = item.querySelector(".numeroTel").value;
            let descripcion = item.querySelector(".descNumero").value;

            numeros.push({ indicativo, numero, descripcion });
        });

        let datos = new FormData(this);
        let leadsIdElement = document.getElementById("cliente_id");
        let leadsId = leadsIdElement ? leadsIdElement.value : null;
        if (leadsId && parseInt(leadsId) > 0) {
            datos.append("accion", "actualizar_leads");
        } else {
            datos.append("accion", "registrar_leads");
            datos.append("numerosAdicionales", JSON.stringify(numeros));
        }

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    cargarKanban();
                    this.reset();
                    //document.getElementById("btn-canvas-leads").click();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

function exportarExcel(tipo) {
    const f = Filtros.obtener();
    const params = new URLSearchParams();

    // Tipo de reporte (ej: "leads", "asesores", "campanas", etc.)
    params.append("tipo", tipo);

    // Convertir filtros a parámetros GET
    for (let k in f) {
        if (Array.isArray(f[k]) && f[k].length > 0) {
            params.append(k, JSON.stringify(f[k]));
        } else if (f[k] !== "") {
            params.append(k, f[k]);
        }
    }

    window.location.href = "ajax/exportar_excel.php?" + params.toString();
}

function listarLeads() {

    const f = Filtros.obtener();

    // Enviar filtros solo si existen
    const params = new URLSearchParams();

    if (f.texto !== "") params.append("texto", f.texto);
    if (f.asesor.length > 0) params.append("asesor", JSON.stringify(f.asesor));
    if (f.carreras.length > 0) params.append("carreras", JSON.stringify(f.carreras));
    if (f.horario.length > 0) params.append("horario", JSON.stringify(f.horario));
    if (f.interes.length > 0) params.append("interes", JSON.stringify(f.interes));
    if (f.medio.length > 0) params.append("medio", JSON.stringify(f.medio));
    if (f.fuente.length > 0) params.append("fuente", JSON.stringify(f.fuente));
    if (f.campana.length > 0) params.append("campana", JSON.stringify(f.campana));
    if (f.accion.length > 0) params.append("accion", JSON.stringify(f.accion));
    if (f.departamento.length > 0) params.append("departamento", JSON.stringify(f.departamento));
    if (f.ciudad.length > 0) params.append("ciudad", JSON.stringify(f.ciudad));
    if (f.barrio.length > 0) params.append("barrio", JSON.stringify(f.barrio));
    if (f.estados.length > 0) params.append("estados", JSON.stringify(f.estados));
    if (f.fecha_inicio !== "") params.append("fecha_inicio", f.fecha_inicio);
    if (f.fecha_fin !== "") params.append("fecha_fin", f.fecha_fin);


    params.append("accion", "listar_leads");

    fetch("ajax/ajax.php?" + params.toString())
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("leads_list")) {
                inicializarDataTableLeads(data);
            }
        })
        .catch(err => console.error("Error al listar leads:", err));
}

window.editarLeads = (id) => {

    let datos = new FormData();
    datos.append("accion", "consultar_leads");
    datos.append("id", id);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {

            const leads = data.find(c => c.id_lead == id);
            if (!leads) return;
            // Cambiar título del OFFCANVAS
            document.getElementById("title-canvas-leads").textContent = "Editar Cliente";
            document.getElementById("btn-canvas-leads").textContent = "Editar";
            // Llenar campos
            document.getElementById("nombresLeads").value = leads.nombres;
            document.getElementById("apellidosLeads").value = leads.apellidos;
            document.getElementById("identificacionLeads").value = leads.identificacion;
            document.getElementById("telefonoLeads").value = leads.telefono_principal;
            document.getElementById("correoLeads").value = leads.email;
            document.getElementById("direLeads").value = leads.direccion;
            document.getElementById("infoLeads").value = leads.info_adicional;
            document.getElementById("carrera").value = leads.carrera_id;
            document.getElementById("interes").value = leads.interes_id;
            document.getElementById("horario").value = leads.horario_id;
            document.getElementById("medio").value = leads.medio_id;
            document.getElementById("fuente").value = leads.fuente_id;
            document.getElementById("campana").value = leads.campana_id;
            document.getElementById("accion").value = leads.accion_id;
            document.getElementById("departamento").value = leads.departamento_id;
            document.getElementById("ciudad").value = leads.ciudad_id;
            document.getElementById("barrio").value = leads.barrio_id;
            document.getElementById("observacionLeads").value = leads.observaciones;

            // Guardar ID oculto
            if (!document.getElementById("cliente_id")) {
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.id = "cliente_id";
                hidden.name = "cliente_id";
                document.getElementById("formLeads").appendChild(hidden);
            }
            document.getElementById("cliente_id").value = leads.cliente_id;

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

if (document.getElementById("btnCerrarOffcanvas-depar")) {
    document.getElementById("btnCerrarOffcanvas-depar").addEventListener("click", function () {
        document.getElementById("formDepart").reset();
        document.getElementById("title-canvas-depar").textContent = "Nueva Departamento";
        document.getElementById("btn-canvas-depar").textContent = "Crear";
    });
}

window.eliminarLeads = (id) => {

    let datos = new FormData();
    datos.append("accion", "eliminar_leads");
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

function listarLeadsOption() {
    fetch("ajax/ajax.php?accion=listar_depar_option")
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("leads")) {
                document.getElementById("leads").innerHTML = data.option;
            }
        });
}

listarLeadsOption();
listarLeads();

document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById("departamento")) {
        // Cuando seleccionas un departamento
        document.getElementById("departamento").addEventListener("change", function () {
            let id_dep = this.value;

            if (id_dep !== "") {

                document.getElementById("contenedor_ciudad").style.display = "block";

                // Reiniciar barrio
                document.getElementById("barrio").innerHTML = "<option value=''>Seleccione...</option>";
                document.getElementById("contenedor_barrio").style.display = "none";

                // Cargar ciudades por departamento
                fetch("ajax/ajax.php?accion=listar_ciudad_por_departamento&id_dep=" + id_dep)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById("ciudad").innerHTML = data.option;
                    });

            } else {
                document.getElementById("contenedor_ciudad").style.display = "none";
                document.getElementById("contenedor_barrio").style.display = "none";
            }
        });
    }

    if (document.getElementById("ciudad")) {

        // Cuando seleccionas una ciudad
        document.getElementById("ciudad").addEventListener("change", function () {
            let id_ciudad = this.value;

            if (id_ciudad !== "") {
                document.getElementById("contenedor_barrio").style.display = "block";

                fetch("ajax/ajax.php?accion=listar_barrio_por_ciudad&id_ciudad=" + id_ciudad)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById("barrio").innerHTML = data.option;
                    });

            } else {
                document.getElementById("contenedor_barrio").style.display = "none";
            }
        });
    }

});

document.querySelectorAll(".editable").forEach(el => {

    el.addEventListener("click", function () {

        let id = this.id;
        let storedId = this.dataset.id;
        let value = this.textContent.trim();

        // --------------------------
        // 1️⃣ CAMPOS QUE USAN INPUT
        // --------------------------
        if (id === "nombreClienteLeads" || id === "direccionClienteLeads" || id === "apellidoClienteLeads" || id === "identificacionLeads" || id === "correoLeads" || id === "telefonoLeads" || id === "acudienteLeads" || id === "telAcudienteLeads") {

            let input = document.getElementById("input_" + id);
            console.log(input);
            if (!input) return;

            input.value = storedId || value;

            input.classList.remove("d-none");
            this.classList.add("d-none");
            input.focus();

            // Guardar al perder el foco
            input.onblur = () => updateLeadField(id, input.value, this);

            return;
        }

        // --------------------------
        // 2️⃣ CAMPO ESPECIAL: OBSERVACIONES (TEXTAREA)
        // --------------------------
        if (id === "observacionesLead") {

            let textarea = document.getElementById("textarea_observacionesLead");

            textarea.value = storedId || value;
            textarea.classList.remove("d-none");
            this.classList.add("d-none");
            textarea.focus();

            textarea.onblur = () => updateLeadField(id, textarea.value, this);

            return;
        }

        // --------------------------
        // 3️⃣ CAMPOS QUE USAN SELECT
        // --------------------------
        let container = document.getElementById("select_" + id);
        if (!container) return;

        let select = container.querySelector("select");

        if (storedId) {
            select.value = storedId;
        }

        container.classList.remove("d-none");
        this.classList.add("d-none");
        select.focus();

        select.onchange = () => updateLeadField(id, select.value, this);
        select.onblur = () => updateLeadField(id, select.value, this);
    });

});


function updateLeadField(fieldId, newValue, textElement) {

    let leadId = new URLSearchParams(window.location.search).get("id");
    let fieldName = fieldId.replace("Lead", "");

    $.ajax({
        url: "ajax/ajax.php",
        type: "POST",
        data: {
            accion: "update_field",
            lead_id: leadId,
            column: fieldName + "_id",
            value: newValue
        },
        success: function (response) {

            let container = document.getElementById("select_" + fieldId);
            let textarea = document.getElementById("textarea_" + fieldId);
            let input = document.getElementById("input_" + fieldId);

            textElement.textContent = respuestaNombreNuevo(fieldName, newValue);

            textElement.dataset.id = newValue;

            if (container) container.classList.add("d-none");
            if (textarea) textarea.classList.add("d-none");
            if (input) input.classList.add("d-none");

            textElement.classList.remove("d-none");

            console.log("Actualizado correctamente:", response);
        },
        error: function () {
            alert("Error al actualizar el lead.");
        }
    });
}

function respuestaNombreNuevo(field, value) {
    let select = document.getElementById(field);
    if (select) {
        let opt = select.querySelector(`option[value="${value}"]`);
        return opt ? opt.textContent : value;
    }
    return value;
}


//Tarjetas leads.

document.addEventListener("DOMContentLoaded", () => {
    ejecutarCargaOptimizada();
});

// 1. Detectar cambios en filtros
document.addEventListener("change", function (e) {
    if (e.target.classList.contains("filtro")) {
        ejecutarCargaOptimizada();
    }
});

document.addEventListener("input", function (e) {
    if (e.target.id === "buscador") {
        ejecutarCargaOptimizada();
    }
});

function normalizar(txt) {
    if (!txt) return "";
    return txt
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "");
}


async function cargarKanban() {
    const [estados, leads] = await Promise.all([
        cargarEstados(),
        cargarLeads()
    ]);

    renderKanban(estados, leads);
}

async function cargarContactGrid() {
    const leads = await cargarLeads();
    renderContactGrid(leads);
}

/* ================================
   1. Cargar ESTADOS desde PHP
================================ */
async function cargarEstados() {
    const res = await fetch("ajax/ajax.php?accion=getEstados");
    return await res.json();
}

/* ================================
   2. Cargar LEADS desde PHP
================================ */
async function cargarLeads() {
    const f = Filtros.obtener();

    // Enviar filtros solo si existen
    const params = new URLSearchParams();

    if (f.texto !== "") params.append("texto", f.texto);
    if (f.asesor.length > 0) params.append("asesor", JSON.stringify(f.asesor));
    if (f.carreras.length > 0) params.append("carreras", JSON.stringify(f.carreras));
    if (f.horario.length > 0) params.append("horario", JSON.stringify(f.horario));
    if (f.interes.length > 0) params.append("interes", JSON.stringify(f.interes));
    if (f.medio.length > 0) params.append("medio", JSON.stringify(f.medio));
    if (f.fuente.length > 0) params.append("fuente", JSON.stringify(f.fuente));
    if (f.campana.length > 0) params.append("campana", JSON.stringify(f.campana));
    if (f.accion.length > 0) params.append("accion", JSON.stringify(f.accion));
    if (f.departamento.length > 0) params.append("departamento", JSON.stringify(f.departamento));
    if (f.ciudad.length > 0) params.append("ciudad", JSON.stringify(f.ciudad));
    if (f.barrio.length > 0) params.append("barrio", JSON.stringify(f.barrio));
    if (f.estados.length > 0) params.append("estados", JSON.stringify(f.estados));
    if (f.fecha_inicio !== "") params.append("fecha_inicio", f.fecha_inicio);
    if (f.fecha_fin !== "") params.append("fecha_fin", f.fecha_fin);

    params.append("accion", "getLeads");

    const res = await fetch("ajax/ajax.php?" + params.toString());
    return await res.json();
}

function agruparLeadsPorEstado(leads) {
    const map = {};
    for (const l of leads) {
        if (!map[l.estado_leads_id]) {
            map[l.estado_leads_id] = [];
        }
        map[l.estado_leads_id].push(l);
    }
    return map;
}

/* ================================
   3. Renderizar tablero CON TU DISEÑO
================================ */
function renderKanban(estados, leads) {

    const contenedor = document.getElementById("kanban-container");
    contenedor.innerHTML = "";

    const leadsPorEstado = agruparLeadsPorEstado(leads);
    const fragmentGlobal = document.createDocumentFragment();

    estados.forEach(estado => {

        const listaLeads = leadsPorEstado[estado.id_estado_leads] || [];
        const cantidad = listaLeads.length;

        const coloresEstado = {
            1: "text-info",
            2: "text-info",
            3: "text-info",
            4: "text-warning",
            5: "text-info",
            6: "text-success",
            7: "text-success",
            8: "text-danger",
            9: "text-warning",
        };

        const columna = document.createElement("div");
        columna.className = "kanban-list-items p-2 rounded border";

        columna.innerHTML = `
            <div class="card mb-0 border-0 shadow">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="d-flex align-items-center mb-1">
                                <i class="ti ti-circle-filled fs-10 ${coloresEstado[estado.ord_eld] || 'text-secondary'} me-1"></i>
                                ${estado.nombre}
                            </h6>
                            <span class="fw-medium">${cantidad} Leads</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kanban-drag-wrap">
                <div class="kanban-list" data-estado="${estado.id_estado_leads}"></div>
            </div>
        `;

        fragmentGlobal.appendChild(columna);

        const lista = columna.querySelector(".kanban-list");
        const fragmentLeads = document.createDocumentFragment();

        // ⚠️ Limite inicial (AJUSTABLE)
        listaLeads.slice(0, 150).forEach(l => {
            fragmentLeads.appendChild(crearCardLead(l, estado.id_estado_leads));
        });

        lista.appendChild(fragmentLeads);
    });

    contenedor.appendChild(fragmentGlobal);
    activarDragAndDrop();
}


/* ================================
   4. Card del lead CON TU DISEÑO
================================ */
function crearCardLead(l, estadoId) {
    const loader = document.getElementById("loaderFoco");

    try {
        loader.classList.remove("d-none");
        if (!l?.id_lead) return document.createElement("div");

        const coloresTop = {
            1: "bg-info",
            2: "bg-info",
            3: "bg-warning",
            4: "bg-info",
            5: "bg-info",
            6: "bg-success"
        };

        const iniciales = ((l.nombres || "")[0] || "") + ((l.apellidos || "")[0] || "");

        const card = document.createElement("div");
        card.className = "card kanban-card border mb-0 mt-3 shadow";
        card.draggable = true;
        card.dataset.id = l.id_lead;

        card.innerHTML = `
        <div class="card-body">
            <div class="card-topbar mb-3 pt-1 ${coloresTop[estadoId] || 'bg-secondary'}"></div>

            <div class="d-flex align-items-center mb-3">
                <a href="leads-details.php?id=${l.id_lead}&id_cliente=${l.cliente_id}"
                   class="avatar rounded-circle bg-soft-info flex-shrink-0 me-2">
                    <span class="avatar-title text-info">${iniciales.toUpperCase() || "?"}</span>
                </a>
                <h6 class="fw-medium fs-14 mb-0">
                    <a href="leads-details.php?id=${l.id_lead}&id_cliente=${l.cliente_id}">
                        ${l.nombres || ""} ${l.apellidos || ""}
                    </a>
                </h6>
            </div>
            <h6 class="fw-medium fs-14 mb-1">Asesor: ${l.nombreAsesor}</h6>
            <p class="mb-1"><i class="ti ti-mail me-1"></i>${l.email || "Sin email"}</p>
            <p class="mb-1"><i class="ti ti-phone me-1"></i>${l.telefono_principal || "Sin teléfono"}</p>
            <p class="mb-1"><i class="ti ti-map-pin me-1"></i>${l.ciudad || "Sin ciudad"}</p>
            <p class="mb-1"><i class="ti ti-pencil me-1"></i>${l.desc_pro || "Sin programa"}</p>
            <p><i class="ti ti-calendar me-1"></i>${l.horario || "Sin horario"}</p>
        </div>
    `;

        return card;
    } catch (e) {
        console.error("Error card leads:", e);
    } finally {
        loader.classList.add("d-none");
    }
}

/* ================================
   5. Drag & Drop
================================ */
function activarDragAndDrop() {

    document.querySelectorAll(".kanban-list").forEach(l => {
        l.style.minHeight = "60px";
    });

    document.addEventListener("dragstart", e => {
        const card = e.target.closest(".kanban-card");
        if (!card) return;

        e.dataTransfer.setData("lead", card.dataset.id);
        setTimeout(() => card.classList.add("dragging"), 0);
    });

    document.addEventListener("dragend", e => {
        const card = e.target.closest(".kanban-card");
        if (card) card.classList.remove("dragging");
    });

    document.querySelectorAll(".kanban-list").forEach(lista => {

        lista.addEventListener("dragover", e => e.preventDefault());

        lista.addEventListener("drop", e => {
            e.preventDefault();

            const idLead = e.dataTransfer.getData("lead");
            const card = document.querySelector(`[data-id="${idLead}"]`);
            if (!card) return;

            lista.appendChild(card);

            updateEstadoLead(idLead, lista.dataset.estado);
            actualizarContadores();
        });
    });
}

function actualizarContadores() {
    document.querySelectorAll(".kanban-list-items").forEach(col => {
        const lista = col.querySelector(".kanban-list");
        const count = lista.children.length;
        col.querySelector(".fw-medium").textContent = count + " Leads";
    });
}


/* ================================
   6. Actualizar estado en DB
================================ */
async function updateEstadoLead(idLead, idEstado) {
    const formData = new FormData();
    formData.append("accion", "updateEstado");
    formData.append("id_lead", idLead);
    formData.append("id_estado", idEstado);

    await fetch("ajax/ajax.php", {
        method: "POST",
        body: formData
    });
}

/* ================================
   Card del lead cliente CON TU DISEÑO
================================ */

function renderContactGrid(leads) {
    const cont = document.getElementById("contact-grid");
    cont.innerHTML = ""; // limpiar

    leads.forEach(l => {
        const nombre = l.nombres || "";
        const apellido = l.apellidos || "";
        const iniciales = (nombre.charAt(0) + apellido.charAt(0)).toUpperCase();

        // COLORES POR ESTADO
        const coloresEstado = {
            1: "badge-soft-info",
            2: "badge-soft-info",
            3: "badge-soft-warning",
            4: "badge-soft-info",
            5: "badge-soft-info",
            6: "badge-soft-success"
        };

        // Estado del lead: si no existe, usa “Sin estado”
        const estadoNombre = l.nombre || "Sin estado";
        const estadoID = l.estado_leads_id || 0;
        const colorBadge = coloresEstado[estadoID] || "badge-soft-secondary";

        const card = document.createElement("div");
        card.className = "col-xxl-3 col-xl-4 col-md-6";

        card.innerHTML = `
            <div class="card border shadow">
                <div class="card-body">

                    <!-- Top usuario -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center">

                            <a href="leads-details.php?id=${l.id_lead}"
                                class="avatar avatar-md flex-shrink-0 me-2">
                                <span class="avatar-title rounded-circle bg-soft-info text-info fs-5">
                                    ${iniciales}
                                </span>
                            </a>

                            <div>
                                <h6 class="fs-14"><a href="leads-details.php?id=${l.id_lead}" class="fw-medium">
                                    ${nombre} ${apellido}
                                </a></h6>
                                <p class="text-default mb-0">${l.desc_pro || "Sin programa"}</p>
                            </div>
                        </div>

                        <div class="dropdown table-action">
                            <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow"
                                data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" onclick="editarLeads(${l.id_lead})" href="#"
                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvas_edit">
                                    <i class="ti ti-edit text-blue"></i> Editar
                                </a>

                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#delete_contact">
                                    <i class="ti ti-trash"></i> Eliminar
                                </a>

                                <a class="dropdown-item" href="leads-details.php?id=${l.id_lead}">
                                    <i class="ti ti-eye text-blue-light"></i> Ver
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Datos -->
                    <div class="d-block">
                        <div class="d-flex flex-column">
                            <p class="text-default d-inline-flex align-items-center mb-2">
                                <i class="ti ti-mail text-dark me-1"></i>${l.email || "Sin email"}
                            </p>

                            <p class="text-default d-inline-flex align-items-center mb-2">
                                <i class="ti ti-phone text-dark me-1"></i>${l.telefono_principal || "Sin teléfono"}
                            </p>

                            <p class="text-default d-inline-flex align-items-center">
                                <i class="ti ti-map-pin-pin text-dark me-1"></i>${l.ciudad || "Sin ciudad"}
                            </p>
                        </div>

                        <!-- BADGE DE ESTADO DINÁMICO -->
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge badge-tag ${colorBadge} me-2">
                                ${estadoNombre}
                            </span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <div class="d-flex align-items-center grid-social-links">

                            <a href="mailto:${l.email}" class="avatar avatar-xs text-dark rounded-circle me-1">
                                <i class="ti ti-mail fs-14"></i>
                            </a>

                            <a href="tel:${l.telefono_principal}" class="avatar avatar-xs text-dark rounded-circle me-1">
                                <i class="ti ti-phone-check fs-14"></i>
                            </a>

                            <a href="#" class="avatar avatar-xs text-dark rounded-circle me-1">
                                <i class="ti ti-message-circle-share fs-14"></i>
                            </a>

                            <a href="#" class="avatar avatar-xs text-dark rounded-circle">
                                <i class="ti ti-brand-facebook fs-14"></i>
                            </a>

                        </div>

                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xs bg-info rounded-circle text-white">
                                <i class="ti ti-user fs-14"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        `;

        cont.appendChild(card);
    });
}

/* ================================
   Leads Details
================================ */

const idLead = params.get("id");
const id_actividad = params.get("id_actividad");

document.addEventListener("DOMContentLoaded", () => {
    if (id_actividad && Number(id_actividad) > 0) {
        cargarActividad(id_actividad);
    }
});

async function cargarActividad(id_actividad) {
    let datos = new FormData();
    datos.append("id_actividad", id_actividad);
    datos.append("accion", "visualizar_actividad");

    const res = await fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    });

    const data = await res.json(); // si el backend devuelve JSON
    console.log(data);
}

async function listarLeadsId() {
    let datos = new FormData();
    datos.append("id", idLead);
    datos.append("accion", "listar_leads_id");

    const res = await fetch("ajax/ajax.php", { method: "POST", body: datos });
    const data = await res.json();

    let d = data[0];

    //Info cliente

    function mostrarCampo(idElemento, valor, textoVacio) {
        const finalValue = valor && valor.trim() !== "" ? valor : textoVacio;
        const el = document.getElementById(idElemento);
        el.textContent = finalValue;
        el.dataset.id = finalValue;
    }
    mostrarCampo("identificacionLeads", d.identificacion, "Sin identificacion");
    mostrarCampo("correoLeads", d.email, "Sin correo");
    mostrarCampo("telefonoLeads", d.telefono_principal, "Sin telefono");
    mostrarCampo("nombreClienteLeads", d.nombres, "Sin nombres");
    mostrarCampo("apellidoClienteLeads", d.apellidos, "Sin apellidos");
    mostrarCampo("direccionClienteLeads", d.direccion, "Sin dirección");
    mostrarCampo("acudienteLeads", d.acudiente, "Sin acudiente");
    mostrarCampo("telAcudienteLeads", d.tel_acudiente, "Sin telefono acudiente");

    // 🔥 Cargar teléfonos adicionales
    if (d.cliente_id) {
        cargarTelefonosAdicionales(d.cliente_id);
    }

    if (d.Nfactura !== null) {
        contenedorMatricula = document.getElementById("contenedor_matricula");
        contenedorMatricula.style.display = "block";
        document.getElementById("Nfactura").textContent = d.Nfactura ?? '#';
        document.getElementById("valorF").textContent = d.valorF ?? '#';
        document.getElementById("metodoF").textContent = d.metodoF ?? '#';
    }

    // Asignación de datos básicos

    document.getElementById("empresaCarrera").textContent = d.nom_emp ?? 'Sin Empresa';
    document.getElementById("empresaCarrera").dataset.id = d.cod_emp;

    //document.getElementById("valorCarrera").textContent = new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 }).format(d.val_pro);

    document.getElementById("asesorLeads").textContent = `${d.nombreAsesor} ${d.apellidoAsesor}`;
    cargarAsesoresDropdown();
    document.getElementById("carreraLead").textContent = d.desc_pro ?? 'Sin carrera';
    document.getElementById("carreraLead").dataset.id = d.carrera_id;

    document.getElementById("horarioLead").textContent = d.horario ?? 'Sin horario';
    document.getElementById("horarioLead").dataset.id = d.horario_id;

    document.getElementById("interesLead").textContent = d.interes ?? 'Sin interes';
    document.getElementById("interesLead").dataset.id = d.interes_id;

    document.getElementById("medioLead").textContent = d.desc_med ?? 'Sin medio';
    document.getElementById("medioLead").dataset.id = d.medio_id;

    document.getElementById("fuenteLead").textContent = d.desc_fue ?? 'Sin fuente';
    document.getElementById("fuenteLead").dataset.id = d.fuente_id;

    document.getElementById("campanaLead").textContent = d.campana ?? 'Sin campana';
    document.getElementById("campanaLead").dataset.id = d.campana_id;

    document.getElementById("accionLead").textContent = d.accion ?? 'Sin accion';
    document.getElementById("accionLead").dataset.id = d.accion_id;

    document.getElementById("departamentoLead").textContent = d.desc_dep ?? 'Sin departamento';
    document.getElementById("departamentoLead").dataset.id = d.departamento_id;

    document.getElementById("ciudadLead").textContent = d.ciudad ?? 'Sin ciudad';
    document.getElementById("ciudadLead").dataset.id = d.ciudad_id;

    document.getElementById("barrioLead").textContent = d.desc_brr ?? 'Sin barrio';
    document.getElementById("barrioLead").dataset.id = d.barrio_id;

    mostrarCampo("observacionesLead", d.observaciones, "Sin Observaciones");

    [...document.getElementsByClassName("fechaLeads")].forEach(elem => {
        elem.textContent = d.fecha_creacion;
    });

    // 🔥 Cargar estados y renderizar stepper dinámico
    const estados = await cargarEstados();
    renderEstadosLead(estados, d);
}

async function cargarTelefonosAdicionales(cliente_id) {

    const datos = new FormData();
    datos.append("accion", "listar_telefonos_adicionales");
    datos.append("cliente_id", cliente_id);

    const res = await fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    });

    const telefonos = await res.json();


    if (telefonos.length > 0) {
        telefonos.forEach(tel => {
            agregarNumeroAdicional(tel, true, cliente_id);
        });
    } else {
        contenedorNumeros.style.display = "block";
        agregarNumeroAdicional({}, false, cliente_id);
    }
}


function cargarAsesoresDropdown() {

    fetch("ajax/ajax.php?accion=listar_user_option_leads")
        .then(res => res.json())
        .then(data => {

            let html = "";

            data.forEach(user => {
                html += `
                    <a class="dropdown-item cambiar-asesor" 
                       href="#" 
                       data-id="${user.id_user}">
                       ${user.nombres} ${user.apellidos}
                    </a>`;
            });

            document.getElementById("userAsesor").innerHTML = html;

            activarEventosCambioAsesor();
        })
        .catch(err => console.error("Error cargando asesores:", err));
}

function activarEventosCambioAsesor() {
    document.querySelectorAll(".cambiar-asesor").forEach(item => {

        item.addEventListener("click", function (e) {
            e.preventDefault();

            let nuevoId = this.getAttribute("data-id");
            let datos = new FormData();
            datos.append("accion", "cambiar_asesor");
            datos.append("id_lead", idLead);
            datos.append("nuevo_user_id", nuevoId);

            fetch("ajax/ajax.php", {
                method: "POST",
                body: datos
            })
                .then(res => res.text())
                .then(resp => {

                    if (resp === "ok") {
                        // Actualizar visualmente
                        document.getElementById("asesorLeads").textContent = this.textContent;

                        Swal.fire({
                            icon: "success",
                            title: "Asignado correctamente",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire("Error", "No se pudo cambiar el asesor", "error");
                    }
                });
        });
    });
}

function cambiarEstadoLead(elemento, id_lead) {

    const nuevoEstado = elemento.dataset.id;

    let form = new FormData();
    form.append("accion", "updateEstado");
    form.append("id_lead", id_lead);
    form.append("id_estado", nuevoEstado);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: form
    })
        .then(r => r.json())
        .then(res => {

            /*if (res.status === "success") {
                Swal.fire("Actualizado", "El estado del lead fue cambiado", "success");

                // Recargar vista completa del lead
                listarLeadsId();
            }*/
            listarLeadsId();
        });
}

function renderEstadosLead(estados, lead) {

    const coloresEstado = {
        1: "bg-indigo",
        2: "bg-indigo",
        3: "bg-warning",
        4: "bg-warning",
        5: "bg-success",
        6: "bg-success",
        7: "bg-primary",
        8: "bg-indigo"
    };

    // Contenedor en tu HTML
    let cont = document.getElementById("estadoLeadsSecundarioSeguimiento");
    cont.innerHTML = ""; // limpiar

    let wrapper = document.createElement("div");
    wrapper.className = "step-progress d-flex flex-wrap gap-2";

    estados.forEach(est => {

        const activo = (est.id_estado_leads == lead.estado_leads_id);

        wrapper.innerHTML += `
            <div 
                class="step ${activo ? coloresEstado[est.id_estado_leads] : 'bg-light text-black'} pointer estado-item"
                data-id="${est.id_estado_leads}"
                data-nombre="${est.nombre}">
                
                ${est.nombre}
            </div>
        `;
    });

    cont.appendChild(wrapper);

    // Agregar eventos de clic para cambiar estado
    document.querySelectorAll(".estado-item").forEach(btn => {
        btn.addEventListener("click", () => {

            let idEstado = parseInt(btn.dataset.id);
            let nombreEstado = btn.dataset.nombre;

            if (idEstado === 6) {
                abrirModalMatriculado(nombreEstado);
            }

            if (idEstado === 7) {
                abrirModalPerdido(nombreEstado);
            }

            if (idEstado === 8) {
                abrirModalAplazado(nombreEstado);
            }

            // Otros estados → cambiar normal
            cambiarEstadoLead(btn, lead.id_lead);
        });
    });
}

function abrirModalMatriculado(nombreEstado) {
    window.abriendoMatricula = true;
    let modal = new bootstrap.Modal(document.getElementById("add_matricula"));
    modal.show();
}

if (document.getElementById("formMatricula")) {
    document.getElementById("formMatricula").addEventListener("submit", function (e) {

        e.preventDefault();

        const datos = new FormData(this);
        datos.append("id", idLead);
        datos.append("accion", "registrar_matricula");

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {

                    Swal.fire("Éxito", data.message, "success");
                    this.reset();
                    archivosSeleccionados = [];
                    document.getElementById("cerrarModalMatricula").click();
                    listarLeadsId();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });

}

function abrirModalPerdido(nombreEstado) {
    window.abriendoPerdido = true;
    document.getElementById("tit_not").value = nombreEstado;

    // Cambiar ID del formulario
    const form = document.getElementById("formNotas");
    form.id = "formMotivo";

    // Ocultar adjunto
    document.querySelector('[name="desc_arch[]"]').closest('.mb-3').style.display = "none";

    // Limpiar input file
    document.getElementById("desc_arch").value = "";
    document.getElementById("preview-archivos").innerHTML = "";

    let modal = new bootstrap.Modal(document.getElementById("add_notes"));
    modal.show();
}

document.getElementById("add_notes").addEventListener("show.bs.modal", function () {
    // Si el form tiene el ID cambiado, restaurarlo
    const form = document.getElementById("formMotivo") || document.getElementById("formNotas");

    // Detectar si NO se abrió desde abrirModalPerdido()
    // (porque esa función siempre cambia el ID)
    if (form.id === "formMotivo" && !window.abriendoPerdido) {
        form.id = "formNotas";

        // Mostrar adjunto
        document.querySelector('[name="desc_arch[]"]').closest('.mb-3').style.display = "block";

        // Limpiar
        //document.getElementById("tit_not").value = "";
        document.getElementById("desc_not").value = "";
        document.getElementById("desc_arch").value = "";
        document.getElementById("preview-archivos").innerHTML = "";
    }

    // Reset flag
    window.abriendoPerdido = false;
});


function abrirModalAplazado(nombreEstado) {

    // Llenar título automáticamente
    document.getElementById("tituloProAct").value = "Aplazado";

    // Resetear los demás campos si quieres
    document.getElementById("descProAct").value = "";
    document.getElementById("recor_act").selectedIndex = 0;
    document.getElementById("prio_act").selectedIndex = 0;

    let modal = new bootstrap.Modal(document.getElementById("create_actividad"));
    modal.show();
}

listarLeadsId();

/* ================================
   Notas
================================ */

let archivosSeleccionados = [];

document.getElementById("desc_arch").addEventListener("change", function () {

    // Convertir FileList a array y añadir al almacenamiento
    archivosSeleccionados = [...archivosSeleccionados, ...Array.from(this.files)];

    mostrarArchivos();
});

function mostrarArchivos() {
    let contenedor = document.getElementById("preview-archivos");
    contenedor.innerHTML = "";

    archivosSeleccionados.forEach((archivo, index) => {

        let nombre = archivo.name;
        let tamano = (archivo.size / 1024).toFixed(1) + " KB";
        let extension = nombre.split(".").pop().toLowerCase();

        // Iconos según extensión
        let icono = "ti ti-file";
        if (["jpg", "jpeg", "png", "gif"].includes(extension)) icono = "ti ti-file-image";
        if (["mp4", "avi", "mov"].includes(extension)) icono = "ti ti-file-video";
        if (["pdf"].includes(extension)) icono = "ti ti-file-text";
        if (["xls", "xlsx"].includes(extension)) icono = "ti ti-file-spreadsheet";
        if (["doc", "docx"].includes(extension)) icono = "ti ti-file-description";

        let card = `
        <div class="col-md-7 mt-2" id="file-${index}">
            <div class="card mb-0">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                        
                        <div class="d-flex align-items-center me-3">
                            <span class="avatar bg-success me-2">
                                <i class="${icono} fs-20"></i>
                            </span>
                            <div>
                                <h6 class="fw-medium fs-14 mb-1">${nombre}</h6>
                                <p class="mb-0">${tamano}</p>
                            </div>
                        </div>

                        <!-- Botón de borrar -->
                        <a href="javascript:void(0);" class="avatar avatar-xs rounded-circle bg-danger text-white"
                            onclick="eliminarArchivo(${index})">
                            <i class="ti ti-x"></i>
                        </a>

                    </div>
                </div>
            </div>
        </div>
        `;

        contenedor.insertAdjacentHTML("beforeend", card);
    });

}

function eliminarArchivo(index) {
    // Eliminar archivo del array
    archivosSeleccionados.splice(index, 1);

    // Volver a renderizar la lista
    mostrarArchivos();
}


if (document.getElementById("add_notes")) {
    document.getElementById("add_notes").addEventListener("submit", function (e) {
        const formMotivo = document.getElementById("formMotivo");
        const formNotas = document.getElementById("formNotas");

        let form = formMotivo || formNotas; // el que exista
        if (!form) return;

        e.preventDefault();

        const datos = new FormData(form);
        datos.append("id", idLead);

        // Detectar qué acción enviar
        let accion = (form.id === "formMotivo")
            ? "registrar_motivo"
            : "registrar_notas";

        datos.append("accion", accion);
        datos.append("column", "est_motivo");
        // Si es NOTAS, agregamos archivos
        if (accion === "registrar_notas") {
            archivosSeleccionados.forEach(file => {
                datos.append("desc_arch[]", file);
            });
        }

        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {

                    Swal.fire("Éxito", data.message, "success");

                    form.reset();
                    archivosSeleccionados = [];
                    document.getElementById("cerrarModalNotas").click();

                    if (accion === "registrar_notas") {
                        listarNotas();
                        listarProximasActividades();
                    }

                    actualizarTimeline();

                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });

}

function listarNotas() {
    fetch("ajax/ajax.php?accion=listarNotas&id=" + idLead)
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("contenedorNotas")) {
                renderNotas(data);
            }
        })
        .catch(err => console.error("Error al listar leads:", err));
}
listarNotas();

function renderNotas(data) {
    let cont = document.getElementById("contenedorNotas");
    cont.innerHTML = "";

    data.forEach(n => {

        // === ARCHIVOS ===
        let archivosHtml = "";
        n.archivos.forEach(a => {
            archivosHtml += `
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                <div class="d-flex align-items-center me-3">
                                    <span class="avatar bg-teal me-2">
                                        <i class="ti ti-file fs-20"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-medium fs-14 mb-1">${a.desc_arch}</h6>
                                        <p class="mb-0">Adjunto</p>
                                    </div>
                                </div>
                                <a href="uploads/notas/${a.desc_arch}" 
                                   download 
                                   class="avatar avatar-xs rounded-circle bg-light text-dark">
                                    <i class="ti ti-arrow-down"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>`;
        });

        // === COMENTARIOS ===
        let comentariosHtml = "";
        n.comentarios.forEach(c => {
            comentariosHtml += `
                <div class="bg-light p-3 rounded mt-2">
                    <p class="mb-2">${c.desc_com}</p>
                    <p>Comentado por <span class="text-info">${c.user_name}</span></p>
                </div>`;
        });

        // === TARJETA COMPLETA ===
        cont.innerHTML += `
            <div class="card mb-3">
                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-2 pb-2">
                        <div class="d-inline-flex align-items-center mb-2">
                            <span class="avatar avatar-md me-2 flex-shrink-0">
                                <img src="assets/img/profiles/avatar-20.jpg" alt="img">
                            </span>
                            <div>
                                <h6 class="fw-medium fs-14 mb-1">${n.user_name ?? "Usuario"}</h6>
                                <p class="mb-0">${n.fecha_creacion_nota}</p>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="dropdown">
                                <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item"><i class="ti ti-edit me-1"></i>Editar</a>
                                    <a class="dropdown-item"><i class="ti ti-trash me-1"></i>Eliminar</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-medium fs-14 mb-1">${n.tit_nota}</h5>
                    <p>${n.desc_not}</p>

                    <div class="row mt-3">
                        ${archivosHtml}
                    </div>

                    <div class="mt-3">
                        ${comentariosHtml}
                    </div>

                    <!-- BOTÓN PARA MOSTRAR EDITOR -->
                    <div class="text-end mt-3">
                        <a href="javascript:void(0);" 
                           class="add-comment link-primary fw-medium" 
                           data-id="${n.cod_not}">
                            <i class="ti ti-circle-plus me-1"></i>Agregar comentario
                        </a>
                    </div>

                    <!-- === EDITOR DE COMENTARIOS (OCULTO AL INICIO) === -->
                    <div class="notes-editor mt-3 d-none" id="editorNota-${n.cod_not}">
                        <input type="hidden" id="idNotaComentario-${n.cod_not}">
                        
                        <div class="">
                            <textarea class="form-control comentario-textarea" rows="3"></textarea>
                            <div class="text-end note-btns mt-3">
                                <a href="javascript:void(0);" class="btn btn-light add-cancel me-2">Cancelar</a>
                                <a href="javascript:void(0);" class="btn btn-primary btn-save-comment" data-id="${n.cod_not}">Guardar</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        `;
    });
}

document.addEventListener("click", (e) => {

    // === ABRIR EDITOR DE COMENTARIO ===
    if (e.target.closest(".add-comment")) {
        e.preventDefault();

        let btn = e.target.closest(".add-comment");
        let notaId = btn.dataset.id;

        // Mostrar solo el editor de esta nota
        let editor = document.getElementById(`editorNota-${notaId}`);
        editor.classList.remove("d-none");

        // Guardar ID para enviar al backend
        document.getElementById(`idNotaComentario-${notaId}`).value = notaId;
    }

    // === CANCELAR ===
    if (e.target.closest(".add-cancel")) {
        e.preventDefault();

        let editor = e.target.closest(".notes-editor");
        editor.classList.add("d-none");
    }

    // === GUARDAR COMENTARIO ===
    if (e.target.closest(".btn-save-comment")) {
        e.preventDefault();

        let notaId = e.target.dataset.id;
        let editor = document.getElementById(`editorNota-${notaId}`);
        let comentario = editor.querySelector(".comentario-textarea").value;

        if (comentario.trim() === "") {
            alert("El comentario está vacío");
            return;
        }

        // Aquí haces el fetch a tu backend
        guardarComentario(notaId, comentario);

        editor.classList.add("d-none");
    }

});

document.addEventListener("click", (e) => {

    // GUARDAR COMENTARIO
    if (e.target.closest(".btn-save-comment")) {
        e.preventDefault();

        let notaId = e.target.dataset.id;
        let editor = document.getElementById(`editorNota-${notaId}`);
        let comentario = editor.querySelector(".comentario-textarea").value;

        if (comentario.trim() === "") {
            Swal.fire("Error", "El comentario está vacío", "error");
            return;
        }

        let f = new FormData();
        f.append("accion", "agregar_comentario");
        f.append("nota_id", notaId);
        f.append("comentario", comentario);

        fetch("ajax/ajax.php", {
            method: "POST",
            body: f
        })
            .then(r => r.json())
            .then(res => {
                if (res.status === "success") {
                    Swal.fire("Éxito", "Comentario agregado", "success");
                    listarNotas();
                }
            });
    }
});

/* ================================
   Llamadas
================================ */

if (document.getElementById("formCalls")) {
    document.getElementById("formCalls").addEventListener("submit", function (e) {
        e.preventDefault();

        const datos = new FormData(this);
        datos.append("id", idLead);
        datos.append("accion", "registrar_calls");


        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    this.reset();
                    document.getElementById("cerrarModalCalls").click();
                    listarLlamadas();
                    listarProximasActividades();
                    actualizarTimeline();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

function listarLlamadas() {
    let f = new FormData();
    f.append("accion", "listar_llamadas");
    f.append("id_lead", idLead); // opcional si filtras por lead

    fetch("ajax/ajax.php", {
        method: "POST",
        body: f
    })
        .then(r => r.json())
        .then(data => {
            let cont = document.getElementById("listaLlamadas");
            cont.innerHTML = "";

            data.forEach(call => {
                cont.innerHTML += generarCardLlamada(call);
            });

            activarEventosEstados();
        });
}

listarLlamadas();

function activarEventosEstados() {
    document.querySelectorAll(".cambiar-estado").forEach(btn => {
        btn.addEventListener("click", (e) => {
            let nuevoEstado = e.target.dataset.estado;

            let btnEstado = e.target.closest(".dropdown").querySelector(".estado-btn");
            let id = btnEstado.dataset.id;

            actualizarEstadoLlamada(id, nuevoEstado, btnEstado);
        });
    });
}


function generarCardLlamada(c) {
    return `
        <div class="card mb-3">
            <div class="card-body">

                <div class="d-sm-flex align-items-center justify-content-between pb-2">

                    <div class="d-flex align-items-center mb-2">
                        <span class="avatar avatar-md me-2 flex-shrink-0">
                            <img src="assets/img/profiles/avatar-19.jpg" alt="img">
                        </span>

                        <p class="mb-0">
                            <span class="text-dark fw-medium">${c.user_name}</span>
                            logged a call on ${formatearFecha(c.fecha_creacion_call)}
                        </p>
                    </div>

                    <div class="d-inline-flex align-items-center mb-2">
                        <!-- ESTADO -->
                        <div class="dropdown me-2">
                            <a href="#"
                               class="btn btn-sm btn-outline-light dropdown-toggle estado-btn"
                               data-id="${c.id_calls}"
                               data-bs-toggle="dropdown">
                               ${c.estado_call}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                ${generarOpcionesEstado()}
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="dropdown">
                            <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow"
                               data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item editar-call" data-id="${c.id_calls}" data-bs-toggle="modal" data-bs-target="#edit_call">
                                    <i class="ti ti-edit me-1"></i>Editar
                                </a>

                                <a class="dropdown-item eliminar-call" data-id="${c.id_calls}">
                                    <i class="ti ti-trash me-1"></i>Eliminar
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <p class="mb-0">${c.desc_call}</p>
            </div>
        </div>
    `;
}

const estadosMap = {
    "ocupado": "Ocupado",
    "no disponible": "No disponible",
    "sin respuesta": "Sin respuesta",
    "numero incorrecto": "Número incorrecto",
    "mensaje de voz": "Mensaje de voz izquierdo",
    "avanzado": "Avanzado"
};


function generarOpcionesEstado() {
    let estados = [
        { label: "Ocupado", value: "ocupado" },
        { label: "No disponible", value: "no disponible" },
        { label: "Sin Respuesta", value: "sin respuesta" },
        { label: "Número incorrecto", value: "numero incorrecto" },
        { label: "Mensaje de voz izquierdo", value: "mensaje de voz" },
        { label: "Avanzado", value: "avanzado" }
    ];

    return estados
        .map(e => `
            <a class="dropdown-item cambiar-estado"
               data-estado="${e.value}">
               ${e.label}
            </a>
        `)
        .join("");
}


function actualizarEstadoLlamada(id, estado, btnRef) {
    let f = new FormData();
    f.append("accion", "actualizar_estado_call");
    f.append("id_calls", id);
    f.append("estado_call", estado);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: f
    })
        .then(r => r.json())
        .then(res => {
            if (res.status === "success") {

                btnRef.textContent = estadosMap[estado]; // ← Mostrar label

                Swal.fire("Actualizado", "El estado ha sido actualizado", "success");
            }
        });
}

/*function formatearFecha(f) {
    let dt = new Date(f);
    return dt.toLocaleDateString() + ", " +
        dt.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
}*/

/* ================================
   Proxima actividad
================================ */

if (document.getElementById("formProActi")) {
    document.getElementById("formProActi").addEventListener("submit", function (e) {
        e.preventDefault();

        const datos = new FormData(this);
        datos.append("id", idLead);
        datos.append("accion", "registrar_actividad_pro");


        fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    Swal.fire("Éxito", data.message, "success");
                    this.reset();
                    document.getElementById("cerrarModalProAct").click();
                    listarProximasActividades();
                    actualizarTimeline();
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
    });
}

function listarProximasActividades() {
    let f = new FormData();
    f.append("accion", "listar_proximas_actividades");
    f.append("id_lead", idLead);

    fetch("ajax/ajax.php", {
        method: "POST",
        body: f
    })
        .then(r => r.json())
        .then(res => {

            let cont = document.getElementById("contenedorActividades");
            cont.innerHTML = "";

            if (res.length === 0) {
                cont.innerHTML = "<p class='text-muted'>No hay próximas actividades.</p>";
                return;
            }

            res.forEach(a => {
                cont.innerHTML += renderActividad(a);
            });
        });
}

listarProximasActividades();

function formatearFecha(f) {
    const fecha = new Date(f);
    return fecha.toLocaleString("es-CO", {
        year: "numeric",
        month: "short",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    });
}

function renderActividad(a) {
    return `
    <div class="card-body p-3">
        <div class="d-flex flex-lg-nowrap flex-wrap row-gap-2">
            
            <span class="avatar avatar-md flex-shrink-0 rounded me-2 bg-warning">
                <i class="ti ti-user-pin fs-20"></i>
            </span>

            <div>
                <h6 class="fw-medium fs-14 mb-1">${a.tit_act ?? "Actividad"}</h6>

                <p class="mb-1">${a.desc_act}</p>

                <p>${formatearFecha(a.fecha_creacion_actividad_proxima)}</p>

                <div class="card mb-0">
                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-md-4">
                                <div>
                                    <label class="form-label">Recordatorio</label>
                                    <p class="mb-0 fw-medium">${a.recor_act}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div>
                                    <label class="form-label">Prioridad de la tarea</label>
                                    <p class="mb-0 fw-medium">${a.prio_act}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div>
                                    <label class="form-label">Asignado a</label>
                                    <p class="mb-0 fw-medium">${a.nombre_usuario ?? "Sin asignar"}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>`;
}

/* ================================
   Actividades
================================ */

async function listarNota() {
    const res = await fetch("ajax/ajax.php?accion=listarNotas&id=" + idLead);
    const data = await res.json();
    return data; // <-- AHORA SÍ retorna
}


async function listarLlamada() {
    let f = new FormData();
    f.append("accion", "listar_llamadas");
    f.append("id_lead", idLead);

    const res = await fetch("ajax/ajax.php", {
        method: "POST",
        body: f
    });

    const data = await res.json();
    return data; // <-- clave
}


async function listarProximasActividade() {
    let f = new FormData();
    f.append("accion", "listar_proximas_actividades");
    f.append("id_lead", idLead);

    const res = await fetch("ajax/ajax.php", {
        method: "POST",
        body: f
    });

    const data = await res.json();
    return data; // <-- clave
}

async function listarMotivos() {
    let f = new FormData();
    f.append("accion", "listar_motivos");
    f.append("id_lead", idLead);

    const res = await fetch("ajax/ajax.php", {
        method: "POST",
        body: f
    });

    const data = await res.json();
    return data; // <-- clave
}


async function actualizarTimeline() {
    // 🔥 Tus funciones ya devuelven datos, solo las llamamos
    const notas = await listarNota();
    const calls = await listarLlamada();
    const actividades = await listarProximasActividade();
    const motivos = await listarMotivos();

    renderTimeline({ notas, calls, actividades, motivos });
}

function renderTimeline({ notas = [], calls = [], actividades = [], motivos = [] }) {

    const container = document.getElementById("timeline-container");
    container.innerHTML = "";

    // Convertimos cada elemento al mismo estándar
    const formattedNotas = notas.map(n => ({
        tipo: "nota",
        fecha: n.fecha_creacion_nota,
        titulo: `Nota agregada por ${n.autor || "Usuario"}`,
        descripcion: n.descripcion || n.desc_not,
        hora: formatearHora(n.fecha_creacion_nota),
        icono: "ti ti-notes",
        color: "bg-danger"
    }));

    const formattedCalls = calls.map(c => ({
        tipo: "call",
        fecha: c.fecha_creacion_call,
        titulo: `Llamada (${c.estado_call})`,
        descripcion: c.desc_call,
        hora: formatearHora(c.fecha_creacion_call),
        icono: "ti ti-phone",
        color: "bg-teal"
    }));

    const formattedActs = actividades.map(a => ({
        tipo: "actividad",
        fecha: a.fecha_creacion_actividad_proxima,
        titulo: a.tit_act,
        descripcion: a.desc_act,
        hora: formatearHora(a.fecha_creacion_actividad_proxima),
        icono: "ti ti-user-pin",
        color: "bg-warning",
        recordatorio: a.recor_act,
        prioridad: a.prio_act,
        asignado: a.nombre_user || "Sin asignar"
    }));

    const formattedMotivo = motivos.map(m => ({
        tipo: "motivo",
        fecha: m.fecha_creacion_motivo,
        titulo: `Motivo agregado por ${m.autor || "Usuario"}`,
        descripcion: m.descripcion || m.desc_mot,
        hora: formatearHora(m.fecha_creacion_motivo),
        icono: "ti ti-notes",
        color: "bg-danger"
    }));

    // Unir todo
    let timeline = [...formattedNotas, ...formattedCalls, ...formattedActs, ...formattedMotivo];

    // Orden descendente
    timeline.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));

    // Agrupación
    const grupos = agruparPorDia(timeline);

    // Render
    Object.keys(grupos).forEach(dia => {

        container.innerHTML += `
            <div class="badge badge-soft-info border-0 mb-3">
                <i class="ti ti-calendar-check me-1"></i>${dia}
            </div>
        `;

        grupos[dia].forEach(item => {
            if (item.tipo === "actividad") {
                container.innerHTML += tarjetaActividad(item);
            } else {
                container.innerHTML += tarjetaSimple(item);
            }
        });
    });
}

function tarjetaSimple(item) {
    return `
        <div class="card border shadow-none mb-3 rounded-3">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap row-gap-2 align-items-start">
                    
                    <!-- ICONO -->
                    <span class="avatar avatar-md flex-shrink-0 rounded me-3 ${item.color}">
                        <i class="${item.icono} fs-20 text-white"></i>
                    </span>

                    <!-- TEXTO -->
                    <div class="flex-grow-1">
                        <h6 class="fw-medium fs-14 mb-1">${item.titulo}</h6>

                        ${item.descripcion
            ? `<p class="mb-1 text-muted lh-sm">${item.descripcion}</p>`
            : ""}

                        <p class="mb-0 fw-semibold text-dark">${item.fecha}, ${item.hora}</p>
                    </div>

                </div>
            </div>
        </div>
    `;
}


function tarjetaActividad(item) {
    return `
        <div class="card border shadow-none mb-3 rounded-3">
            <div class="card-body p-3">
                
                <div class="d-flex flex-lg-nowrap flex-wrap row-gap-2">
                    
                    <!-- ICONO -->
                    <span class="avatar avatar-md flex-shrink-0 rounded me-3 ${item.color}">
                        <i class="${item.icono} fs-20 text-white"></i>
                    </span>

                    <!-- CONTENIDO -->
                    <div class="flex-grow-1">

                        <h6 class="fw-medium fs-14 mb-1">${item.titulo ?? "Actividad"}</h6>

                        <p class="mb-1 text-muted lh-sm">${item.descripcion}</p>

                        <p class="fw-semibold mb-2">${item.fecha}, ${item.hora}</p>

                        <!-- TARJETITA INTERNA -->
                        <div class="card border shadow-none rounded-2 mb-0">
                            <div class="card-body p-3">
                                <div class="row gy-3">

                                    <div class="col-md-4">
                                        <label class="form-label">Recordatorio</label>
                                        <select class="select form-select">
                                            <option selected>${item.recordatorio}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Prioridad</label>
                                        <select class="select form-select">
                                            <option selected>${item.prioridad}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Asignado a</label>
                                        <select class="select form-select">
                                            <option selected>${item.asignado}</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    `;
}


function formatearHora(fecha) {
    const d = new Date(fecha);
    return d.toLocaleTimeString("es-ES", { hour: "2-digit", minute: "2-digit" });
}

function agruparPorDia(lista) {
    const grupos = {};
    lista.forEach(item => {
        const fecha = new Date(item.fecha);
        const key = fecha.toLocaleDateString("es-ES", { day: "2-digit", month: "short", year: "numeric" });

        if (!grupos[key]) grupos[key] = [];
        grupos[key].push(item);
    });
    return grupos;
}

/*=====
Chat asesor
*/

function enviarMensaje() {
    const mensaje = document.getElementById('mensaje').value;
    const conversacion_id = document.getElementById('conversacion_id').value;

    fetch('ajax/ajax.php', {
        method: 'POST',
        body: new URLSearchParams({
            accion: 'enviar_mensaje',
            conversacion_id,
            mensaje
        })
    })
    .then(r => r.json())
    .then(() => {
        document.getElementById('mensaje').value = '';
        cargarMensajes();
    });
}

actualizarTimeline();
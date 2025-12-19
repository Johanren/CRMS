document.addEventListener("DOMContentLoaded", function () {
    const contenedor = document.getElementById("contenedor-botones");

    if (contenedor) {
        contenedor.innerHTML = `
            <div class="d-flex align-items-center gap-2 mt-2">
                <a id="btnGuardarFiltros" class="btn btn-outline-primary w-100">Guardar filtros</a>
                <a id="btnCargarFiltros" class="btn btn-primary w-100" style="display:none;">Aplicar filtros guardados</a>
                <a id="btnRestablecerFiltros" class="btn btn-outline-danger w-100">Restablecer filtros</a>
            </div>
        `;

        document.getElementById("btnGuardarFiltros").addEventListener("click", btnGuardarFiltros);
        document.getElementById("btnCargarFiltros").addEventListener("click", btnCargarFiltros);
        document.getElementById("btnRestablecerFiltros").addEventListener("click", btnRestablecerFiltros);

        // 🔥 AUTO APLICAR FILTROS AL CARGAR
        setTimeout(() => {
            document.getElementById("btnCargarFiltros").click();
        }, 300);
    }
});

function btnGuardarFiltros() {
    let filtros = window.Filtros.obtener();

    fetch("ajax/ajax.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `filtros=${encodeURIComponent(JSON.stringify(filtros))}&accion=guardar_filtros`
    })
        .then(res => res.json())
        .then(data => {
            Swal.fire(data.success ? "Filtros Guardados" : "Error", data.message || "", data.success ? "success" : "error");
        });
}

function btnCargarFiltros() {
    fetch("ajax/ajax.php?nombre=default&accion=cargar_filtro")
        .then(res => res.json())
        .then(filtros => {
            filtros = typeof filtros === "string" ? JSON.parse(filtros) : filtros;

            if (!filtros) return Swal.fire("Sin filtros guardados", "", "warning");

            // Forzar arrays vacíos si vienen undefined
            filtros.asesor = filtros.asesor || [];
            filtros.carreras = filtros.carreras || [];
            filtros.horario = filtros.horario || [];
            filtros.interes = filtros.interes || [];
            filtros.medio = filtros.medio || [];
            filtros.fuente = filtros.fuente || [];
            filtros.campana = filtros.campana || [];
            filtros.accion = filtros.accion || [];
            filtros.departamento = filtros.departamento || [];
            filtros.ciudad = filtros.ciudad || [];
            filtros.barrio = filtros.barrio || [];
            filtros.estados = filtros.estados || [];

            document.getElementById("buscador").value = filtros.texto || "";

            marcarCheckboxes(".filtro-asesor", filtros.asesor);
            marcarCheckboxes(".filtro-carrera", filtros.carreras);
            marcarCheckboxes(".filtro-horario", filtros.horario);
            marcarCheckboxes(".filtro-interes", filtros.interes);
            marcarCheckboxes(".filtro-medio", filtros.medio);
            marcarCheckboxes(".filtro-fuente", filtros.fuente);
            marcarCheckboxes(".filtro-campana", filtros.campana);
            marcarCheckboxes(".filtro-accion", filtros.accion);
            marcarCheckboxes(".filtro-dep", filtros.departamento);
            marcarCheckboxes(".filtro-ciu", filtros.ciudad);
            marcarCheckboxes(".filtro-brr", filtros.barrio);
            marcarCheckboxes(".filtro-estado", filtros.estados);

            window.fecha_inicio = filtros.fecha_inicio || "";
            window.fecha_fin = filtros.fecha_fin || "";

            if (document.getElementById("fecha_inicio")) {
                document.getElementById("fecha_inicio").value = filtros.fecha_inicio || "";
            }
            if (document.getElementById("fecha_fin")) {
                document.getElementById("fecha_fin").value = filtros.fecha_fin || "";
            }

            if (typeof window.listarLeads === "function") window.listarLeads();
            if (typeof window.cargarKanban === "function") window.cargarKanban();
            if (typeof window.cargarContactGrid === "function") window.cargarContactGrid();
            if (typeof window.listarLeadsReporte === "function") window.listarLeadsReporte();
            mostrarResumenFiltros(filtros);
            //Swal.fire("Filtros aplicados", "Se aplicaron los filtros guardados.", "success");
        });
}

function marcarCheckboxes(selector, valores = []) {
    valores = Array.isArray(valores) ? valores : [];
    document.querySelectorAll(selector).forEach(chk => {
        chk.checked = valores.includes(chk.value);
    });
}

function mostrarResumenFiltros(filtros) {
    const resumen = [];

    const map = {
        texto: "Texto",
        asesor: "Asesor",
        carreras: "Carrera",
        horario: "Horario",
        interes: "Interés",
        medio: "Medio",
        fuente: "Fuente",
        campana: "Campaña",
        accion: "Acción",
        departamento: "Departamento",
        ciudad: "Ciudad",
        barrio: "Barrio",
        estados: "Estado",
        fecha_inicio: "Desde",
        fecha_fin: "Hasta"
    };

    for (const key in map) {
        if (!filtros[key]) continue;

        // Arrays
        if (Array.isArray(filtros[key]) && filtros[key].length > 0) {
            resumen.push(`${map[key]}: ${filtros[key].join(", ")}`);
        }

        // Strings
        if (typeof filtros[key] === "string" && filtros[key].trim() !== "") {
            resumen.push(`${map[key]}: ${filtros[key]}`);
        }
    }

    const span = document.getElementById("resumen-filtros");
    span.innerText = resumen.length
        ? "Filtros aplicados: " + resumen.join(" | ")
        : "Sin filtros aplicados";
}

function btnRestablecerFiltros() {

    // 🔹 Limpiar buscador
    if (document.getElementById("buscador")) {
        document.getElementById("buscador").value = "";
    }

    // 🔹 Desmarcar todos los checkboxes de filtros
    document.querySelectorAll(`
        .filtro-asesor,
        .filtro-carrera,
        .filtro-horario,
        .filtro-interes,
        .filtro-medio,
        .filtro-fuente,
        .filtro-campana,
        .filtro-accion,
        .filtro-dep,
        .filtro-ciu,
        .filtro-brr,
        .filtro-estado
    `).forEach(chk => chk.checked = false);

    // 🔹 Limpiar fechas
    window.fecha_inicio = "";
    window.fecha_fin = "";

    if (document.getElementById("fecha_inicio")) {
        document.getElementById("fecha_inicio").value = "";
    }
    if (document.getElementById("fecha_fin")) {
        document.getElementById("fecha_fin").value = "";
    }

    // 🔹 Resetear filtros en memoria (si usas el objeto Filtros)
    if (window.Filtros && typeof window.Filtros.limpiar === "function") {
        window.Filtros.limpiar();
    }

    // 🔹 Recargar vistas sin filtros
    if (typeof window.listarLeads === "function") window.listarLeads();
    if (typeof window.cargarKanban === "function") window.cargarKanban();
    if (typeof window.cargarContactGrid === "function") window.cargarContactGrid();
    if (typeof window.listarLeadsReporte === "function") window.listarLeadsReporte();

    // 🔹 Limpiar resumen
    const span = document.getElementById("resumen-filtros");
    if (span) span.innerText = "Sin filtros aplicados";

    // 🔹 Feedback opcional
    Swal.fire("Filtros restablecidos", "Se limpiaron los filtros aplicados.", "info");
}

function enviarFiltrosALeads(jornada, programa, tipo) {

    const filtros = {
        texto: "",
        asesor: [],
        carreras: [programa],
        horario: [jornada],
        interes: tipo === "con_horario" ? ["Con Horario"] : [],
        medio: [],
        fuente: [],
        campana: [],
        accion: [],
        departamento: [],
        ciudad: [],
        barrio: [],
        estados: ["Nuevo Leads","Leads Activo","Interesado","En Decisión","Matricula en proceso"],
        fecha_inicio: "",
        fecha_fin: ""
    };

    fetch("ajax/ajax.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `accion=guardar_filtros&filtros=${encodeURIComponent(JSON.stringify(filtros))}`
    })
    .then(res => res.json())
    .then(resp => {
        if (!resp.success) {
            Swal.fire("Error", "No se pudieron guardar los filtros", "error");
            return;
        }

        /* 👉 Redirigir a leads */
        window.location.href = "leads.php";
    })
    .catch(err => {
        console.error(err);
        Swal.fire("Error", "Error al enviar filtros", "error");
    });
}

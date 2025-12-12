// 3. Obtener valores de filtros
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

document.getElementById("btnGuardarFiltros").addEventListener("click", function () {
    let filtros = window.Filtros.obtener();
    localStorage.setItem("filtros_guardados", JSON.stringify(filtros));

    Swal.fire("Filtros Guardados", "Tus filtros fueron guardados correctamente", "success");
});

document.getElementById("btnCargarFiltros").addEventListener("click", function () {
    let data = localStorage.getItem("filtros_guardados");

    if (!data) {
        Swal.fire("Sin filtros guardados", "Aún no has guardado filtros.", "warning");
        return;
    }

    let filtros = JSON.parse(data);

    if (document.getElementById("buscador")) {
        document.getElementById("buscador").value = filtros.texto || "";
    }

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

    if (document.getElementById("fecha_inicio"))
        document.getElementById("fecha_inicio").value = filtros.fecha_inicio;

    if (document.getElementById("fecha_fin"))
        document.getElementById("fecha_fin").value = filtros.fecha_fin;

    if (typeof window.listarLeadsReporte === "function") {
        window.listarLeadsReporte();
    }

    Swal.fire("Filtros aplicados", "Se aplicaron los filtros guardados.", "success");
});

function marcarCheckboxes(selector, valores) {
    document.querySelectorAll(selector).forEach(chk => {
        chk.checked = valores.includes(chk.value);
    });
}

function listarLeadsReporte() {

	const f = Filtros.obtener();

	const params = new URLSearchParams();

    if (f.asesor.length > 0) params.append("asesor", JSON.stringify(f.asesor));
    if (f.carreras.length > 0) params.append("carreras", JSON.stringify(f.carreras));
    if (f.horario.length > 0) params.append("horario", JSON.stringify(f.horario));
    if (f.estados.length > 0) params.append("estados", JSON.stringify(f.estados));
    if (f.fecha_inicio !== "") params.append("fecha_inicio", f.fecha_inicio);
    if (f.fecha_fin !== "") params.append("fecha_fin", f.fecha_fin);

	// Acción para tu controlador
	params.append("accion", "listar_leads_reporte");

	// 🔹 Petición AJAX
	fetch("ajax/ajax.php?" + params.toString())
		.then(res => res.json())
		.then(data => {
			if (document.getElementById("leads_reports")) {
				inicializarDataTableLeads(data);
			}
		})
		.catch(err => console.error("Error al listar leads:", err));
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
            listarLeadsReporte()
        }
    );
});

document.addEventListener("change", function (e) {
    if (e.target.classList.contains("filtro")) {
        listarLeadsReporte();
    }
});

listarLeadsReporte();

function inicializarDataTableLeads(data) {

    if (!data || data.length === 0) {
        console.warn("No hay datos para mostrar en el reporte");
        return;
    }

    // Obtener columnas dinámicas desde las claves del JSON
    const columnas = Object.keys(data[0]).map(key => ({
        title: key,
        data: key
    }));

    // Limpiar encabezado
    const thead = document.getElementById("thead_dynamic");
    thead.innerHTML = "";

    // Insertar encabezados
    columnas.forEach(col => {
        const th = document.createElement("th");
        th.textContent = col.title;
        thead.appendChild(th);
    });

    // Destruir DataTable anterior si existe
    if ($.fn.DataTable.isDataTable('#leads_reports')) {
        $('#leads_reports').DataTable().clear().destroy();
    }

    $('#leads_reports').DataTable({
        data: data,
        columns: columnas,
        ordering: true,
        autoWidth: true,
        language: {
            search: ' ',
            sLengthMenu: '_MENU_',
            searchPlaceholder: "Search",
            info: "_START_ - _END_ of _TOTAL_ items",
            lengthMenu: "Show _MENU_ entries",
            paginate: {
                next: '<i class="ti ti-chevron-right"></i> ',
                previous: '<i class="ti ti-chevron-left"></i> '
            }
        }
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

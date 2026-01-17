window.Filtros = {
    obtener: function () {
        let texto = "";
        let inputBuscador = document.getElementById("buscador");
        if (inputBuscador) {
            texto = inputBuscador.value.toLowerCase();
        }
        let asesor = [...document.querySelectorAll(".filtro-asesor:checked")].map(c => c.value);
        let fecha_inicio = window.fecha_inicio || "";
        let fecha_fin = window.fecha_fin || "";

        return { texto, asesor, fecha_inicio, fecha_fin };
    }
};

function listarReporteRstFrm() {

    const f = Filtros.obtener();
    const params = new URLSearchParams();

    params.append("accion", "reporte_rst_frm");

    if (f.texto !== "") params.append("texto", f.texto);
    if (f.asesor.length > 0) params.append("asesor", JSON.stringify(f.asesor));
    if (f.fecha_inicio !== "") params.append("fecha_inicio", f.fecha_inicio);
    if (f.fecha_fin !== "") params.append("fecha_fin", f.fecha_fin);

    fetch("ajax/ajax.php?" + params.toString())
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("rst_reports")) {
                inicializarDataTableRst(data);
            }
        })
        .catch(err => console.error("Error reporte rst:", err));
}

document.addEventListener("change", function (e) {
    if (e.target.classList.contains("filtro")) {
        listarReporteRstFrm();
    }
});

document.addEventListener("input", function (e) {
    if (e.target.id === "buscador") {
        listarReporteRstFrm();
    }
});

listarReporteRstFrm();

function inicializarDataTableRst(data) {

    const tableId = '#rst_reports';

    // 🔒 Si no hay datos, igual inicializamos la tabla vacía
    if (!Array.isArray(data)) {
        console.warn("Datos inválidos para DataTable");
        data = [];
    }

    // 🔥 Destruir DataTable anterior
    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().clear().destroy();
    }

    const columnas = [
        { data: "fecha", title: "Fecha" },
        { data: "cliente_nombre", title: "Cliente" },
        { data: "cliente_telefono", title: "Teléfono" },
        { data: "asesor_nombre", title: "Asesor RST" },
        { data: "obs_rst", title: "Observación" },
        { data: "asesor_nombre_lead", title: "Asesor" },
        { data: "estado_leads", title: "Estado" }
    ];

    const table = $(tableId).DataTable({
        data: data,
        columns: columnas,
        ordering: true,
        autoWidth: false,
        responsive: true,
        pageLength: 10,
        dom: '<"datatable-top"lf>rt<"datatable-bottom"ip>',
        language: {
            search: '',
            searchPlaceholder: "Buscar...",
            lengthMenu: "Mostrar _MENU_",
            info: "_START_ - _END_ de _TOTAL_ registros",
            paginate: {
                next: '<i class="ti ti-chevron-right"></i>',
                previous: '<i class="ti ti-chevron-left"></i>'
            },
            emptyTable: "No hay registros para mostrar"
        }
    });

    /* ===========================
       MOVER CONTROLES A TU HTML
    ============================ */
    $('.datatable-length').html($(tableId + '_length'));
    $('.datatable-paginate').html($(tableId + '_paginate'));
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


/*DIAS RST */

function getUnique(arr, key) {
    return [...new Set(arr.map(i => i[key]))]
}

function construirTablaDias(data) {
    const loader = document.getElementById("loaderFoco");

    try {
        loader.classList.remove("d-none");
        const dias = [...new Set(data.map(d => d.dia))].sort((a, b) => a - b)
        const asesores = getUnique(data, 'asesor')
        const asesoresRTS = getUnique(data, 'asesorRTS')

        let html = `
    <div class="table-responsive-excel">
    <table class="table-excel">
        <thead>
            <tr>
                <th>DÍA</th>`
        html += `<th>RTS ASIGNADO</th>`
        asesores.forEach(a => html += `<th>${a}</th>`)
        html += `<th>Total</th></tr></thead><tbody>`

        let totalGeneral = Array(asesores.length).fill(0)
        let totalMes = 0

        dias.forEach(dia => {
            let totalDia = 0
            html += `<tr><td>${dia}</td>`
            asesoresRTS.forEach(a => html += `<td>${a}</td>`)
            asesores.forEach((asesor, i) => {
                const reg = data.find(r => r.dia == dia && r.asesor == asesor)
                const val = reg ? parseInt(reg.total) : 0
                totalDia += val
                totalGeneral[i] += val
                html += `<td>${val}</td>`
            })
            totalMes += totalDia
            html += `<td><b>${totalDia}</b></td></tr>`
        })

        /* FILA TOTAL FINAL */
        html += `<tr class="table-total"><td>TOTAL</td>`
        html += `<td></td>`
        totalGeneral.forEach(t => html += `<td>${t}</td>`)
        html += `<td>${totalMes}</td></tr>`

        html += `</tbody></table></div>`
        document.getElementById('tablaDias').innerHTML = html
    } catch (e) {
        console.error("Error card leads:", e);
    } finally {
        loader.classList.add("d-none");
    }
}

function construirTablaEstados(data) {
    const loader = document.getElementById("loaderFoco");

    try {
        loader.classList.remove("d-none");

        // 🔹 Estados únicos con su ID
        const estadosMap = {};
        data.forEach(r => {
            estadosMap[r.estado] = r.id;
        });

        const estados = Object.keys(estadosMap);
        const asesores = getUnique(data, 'asesor');

        let html = `
        <div class="table-responsive-excel">
        <table class="table-excel">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ESTADO</th>`;

        asesores.forEach(a => html += `<th>${a}</th>`);
        html += `<th>Total</th></tr></thead><tbody>`;

        let totalGeneral = Array(asesores.length).fill(0);
        let totalEstados = 0;

        estados.forEach(estado => {
            let totalEstado = 0;
            html += `<tr>
                <td>${estadosMap[estado]}</td>
                <td>${estado}</td>`;

            asesores.forEach((asesor, i) => {
                const reg = data.find(r => r.estado === estado && r.asesor === asesor);
                const val = reg ? parseInt(reg.total) : 0;
                totalEstado += val;
                totalGeneral[i] += val;
                html += `<td>${val}</td>`;
            });

            totalEstados += totalEstado;
            html += `<td><b>${totalEstado}</b></td></tr>`;
        });

        /* FILA TOTAL */
        html += `<tr class="table-total">
            <td colspan="2">TOTAL</td>`;

        totalGeneral.forEach(t => html += `<td>${t}</td>`);
        html += `<td>${totalEstados}</td></tr>`;

        html += `</tbody></table></div>`;
        document.getElementById('tablaEstados').innerHTML = html;

    } catch (e) {
        console.error("Error card leads:", e);
    } finally {
        loader.classList.add("d-none");
    }
}

const loader = document.getElementById("loaderFoco");

try {
    loader.classList.remove("d-none");
    fetch('ajax/ajax.php?accion=rst_frm_dia')
        .then(r => r.json())
        .then(data => {
            construirTablaDias(data.porDia)
            construirTablaEstados(data.porEstado)
        })
} catch (e) {
    console.error("Error card leads:", e);
} finally {
    loader.classList.add("d-none");
}
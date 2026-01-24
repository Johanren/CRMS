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

    if (!Array.isArray(data)) {
        console.warn("Datos inválidos para DataTable");
        data = [];
    }

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
        data,
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
        },

        // 🔹 Crear filtros por columna
        initComplete: function () {
            const api = this.api();

            // Clonar header
            $(tableId + ' thead tr').clone(true).appendTo(tableId + ' thead');

            $(tableId + ' thead tr:eq(1) th').each(function (i) {
                $(this).html(
                    `<input type="text"
                        class="form-control form-control-sm"
                        placeholder="Filtrar..."
                    />`
                );

                $('input', this).on('keyup change clear', function () {
                    if (api.column(i).search() !== this.value) {
                        api.column(i).search(this.value).draw();
                    }
                });
            });
        }
    });

    // 🔹 Mover controles
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

        if (!Array.isArray(data) || data.length === 0) {
            document.getElementById("tablaEstados").innerHTML = "<p>No hay datos</p>";
            return;
        }

        // 🔹 ESTADOS LIMPIOS (reconstruidos siempre desde data)
        const estados = [...new Map(
            data.map(d => [
                Number(d.id),
                { id: Number(d.id), nombre: d.estado }
            ])
        ).values()].sort((a, b) => a.id - b.id);

        // 🔹 Asesores únicos
        const asesores = [...new Set(data.map(d => d.asesor))];

        let html = `
        <div class="table-responsive-excel">
        <table class="table-excel">
            <thead>
                <tr>
                    <th>Asesor</th>`;

        estados.forEach(e => html += `<th>${e.nombre}</th>`);
        html += `<th>Total</th></tr>
            </thead><tbody>`;

        let totalPorEstado = Array(estados.length).fill(0);
        let totalGeneral = 0;

        // 🔹 Filas por asesor
        asesores.forEach(asesor => {
            let totalAsesor = 0;
            html += `<tr><td>${asesor}</td>`;

            estados.forEach((estado, i) => {

                // 🔥 SUMA REAL (no find)
                const val = data
                    .filter(r =>
                        Number(r.id) === estado.id &&
                        r.asesor === asesor
                    )
                    .reduce((sum, r) => sum + Number(r.total), 0);

                totalAsesor += val;
                totalPorEstado[i] += val;

                html += `<td>${val}</td>`;
            });

            totalGeneral += totalAsesor;
            html += `<td><b>${totalAsesor}</b></td></tr>`;
        });

        // 🔹 TOTAL
        html += `<tr class="table-total">
            <td><b>Total</b></td>`;
        totalPorEstado.forEach(t => html += `<td><b>${t}</b></td>`);
        html += `<td><b>${totalGeneral}</b></td></tr>`;

        // 🔹 PORCENTAJE
        html += `<tr class="table-porcentaje">
            <td><b>%</b></td>`;

        let sumaPorcentaje = 0;
        totalPorEstado.forEach(t => {
            const p = totalGeneral ? (t / totalGeneral) * 100 : 0;
            sumaPorcentaje += p;
            html += `<td>${p.toFixed(1)}%</td>`;
        });

        html += `<td>${sumaPorcentaje.toFixed(0)}%</td></tr>`;

        html += `</tbody></table></div>`;

        document.getElementById("tablaEstados").innerHTML = html;

    } catch (e) {
        console.error("Error resumen estados:", e);
    } finally {
        loader.classList.add("d-none");
    }
}

const loader = document.getElementById("loaderFoco");

async function cargarDashboard() {
    try {
        loader.classList.remove("d-none");

        // 🔹 Fetch en paralelo
        const [rstRes, estadosRes] = await Promise.all([
            fetch('ajax/ajax.php?accion=rst_frm_dia'),
            fetch('ajax/ajax.php?accion=getEstados')
        ]);

        const rstData = await rstRes.json();
        const estadosCatalogo = await estadosRes.json();

        construirTablaDias(rstData.porDia);
        construirTablaEstados(rstData.porEstado, estadosCatalogo);

    } catch (e) {
        console.error("Error card leads:", e);
    } finally {
        loader.classList.add("d-none");
    }
}

// 🚀 Ejecutar
cargarDashboard();
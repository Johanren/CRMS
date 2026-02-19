window.Filtros = {
    obtener: function() {
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

document.addEventListener("change", function(e) {
    if (e.target.classList.contains("filtro")) {
        listarReporteRstFrm();
    }
});

document.addEventListener("input", function(e) {
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
        { data: "tipo_nom", title: "Tipo Transferencia" },
        { data: "obs_rst", title: "Observación" },
        { data: "asesor_nombre_lead", title: "Asesor" },
        { data: "estado_leads", title: "Estado" },
        { data: "nota", title: "Notas" }
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


function getUnique(arr, key) {
    return [...new Set(arr.map(i => i[key]))]
}

function getUniqueBy(arr, key) {
    return [...new Set(arr.map(i => i[key]).filter(v => v !== null))];
}

function construirTablaDias(data) {
    const loader = document.getElementById("loaderFoco");

    try {
        loader.classList.remove("d-none");

        const mesesMap = {
            1: 'Enero', 2: 'Febrero', 3: 'Marzo', 4: 'Abril',
            5: 'Mayo', 6: 'Junio', 7: 'Julio', 8: 'Agosto',
            9: 'Septiembre', 10: 'Octubre', 11: 'Noviembre', 12: 'Diciembre'
        };

        const asesores = [...new Set(data.map(d => d.asesor))];
        const rts = data[0]?.asesorRTS ?? '';

        // 👉 Agrupar datos por mes
        const datosPorMes = {};
        data.forEach(r => {
            if (!datosPorMes[r.mes]) datosPorMes[r.mes] = [];
            datosPorMes[r.mes].push(r);
        });

        // 👉 Ordenar meses
        const mesesOrdenados = Object.keys(datosPorMes)
            .map(Number)
            .sort((a, b) => a - b);

        let html = `
        <div class="table-responsive-excel">
        <table class="table-excel">
        <thead>
            <tr>
                <th rowspan="2">DÍA</th>
                <th rowspan="2">RTS ASIGNADO</th>`;

        asesores.forEach(a => {
            html += `<th colspan="2">${a}</th>`;
        });

        html += `<th rowspan="2">Total</th></tr><tr>`;

        asesores.forEach(() => {
            html += `<th>Llamada</th><th>WhatsApp</th>`;
        });

        html += `</tr></thead><tbody>`;

        let totalesAsesor = {};
        asesores.forEach(a => totalesAsesor[a] = { Llamada: 0, WhatsApp: 0 });

        let totalMesGeneral = 0;

        // 👉 Recorrer meses ordenados
        mesesOrdenados.forEach(mes => {
            const mesNombre = mesesMap[mes];
            const registrosMes = datosPorMes[mes];

            // Obtener días únicos del mes
            const dias = [...new Set(registrosMes.map(r => r.dia))]
                .sort((a, b) => a - b);

            // 👉 Fila separadora del mes
            html += `
                <tr class="table-mes">
                    <td colspan="${asesores.length * 2 + 3}">
                        <b>${mesNombre.toUpperCase()}</b>
                    </td>
                </tr>`;

            dias.forEach(dia => {
                let totalDia = 0;

                html += `<tr><td>${dia} - ${mesNombre}</td><td>${rts}</td>`;

                asesores.forEach(asesor => {
                    let llamada = 0;
                    let whatsapp = 0;

                    registrosMes
                        .filter(r => r.dia === dia && r.asesor === asesor)
                        .forEach(r => {
                            if (!r.tipo_nom) {
                                llamada += Number(r.total);
                            } else if (r.tipo_nom === 'Llamada') {
                                llamada += Number(r.tipo);
                            } else if (r.tipo_nom === 'WhatsApp') {
                                whatsapp += Number(r.tipo);
                            }
                        });

                    totalesAsesor[asesor].Llamada += llamada;
                    totalesAsesor[asesor].WhatsApp += whatsapp;

                    const subtotal = llamada + whatsapp;
                    totalDia += subtotal;

                    html += `<td>${llamada}</td><td>${whatsapp}</td>`;
                });

                totalMesGeneral += totalDia;
                html += `<td><b>${totalDia}</b></td></tr>`;
            });
        });

        // 👉 Totales finales
        html += `<tr class="table-total"><td colspan="2">TOTAL GENERAL</td>`;

        asesores.forEach(a => {
            html += `<td>${totalesAsesor[a].Llamada}</td>`;
            html += `<td>${totalesAsesor[a].WhatsApp}</td>`;
        });

        html += `<td>${totalMesGeneral}</td></tr>`;
        html += `</tbody></table></div>`;

        document.getElementById('tablaDias').innerHTML = html;

    } catch (e) {
        console.error(e);
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
                        <th>ASESOR</th>`;

        estados.forEach(e => html += `<th>${e}</th>`);
        html += `<th>Total</th></tr>
                </thead>
                <tbody>`;

        let totalEstados = Array(estados.length).fill(0);
        let totalGeneral = 0;

        asesores.forEach(asesor => {
            let totalAsesor = 0;

            html += `
                <tr>
                    <td><b>${asesor}</b></td>`;

            estados.forEach((estado, i) => {
                const reg = data.find(r => r.asesor === asesor && r.estado === estado);
                const val = reg ? parseInt(reg.total) : 0;

                totalAsesor += val;
                totalEstados[i] += val;

                html += `<td>${val}</td>`;
            });

            totalGeneral += totalAsesor;
            html += `<td><b>${totalAsesor}</b></td></tr>`;
        });

        /* FILA TOTAL */
        html += `
            <tr class="table-total">
                <td>TOTAL</td>`;

        totalEstados.forEach(t => html += `<td>${t}</td>`);
        html += `<td>${totalGeneral}</td></tr>`;

        html += `
                </tbody>
            </table>
        </div>`;

        document.getElementById('tablaEstados').innerHTML = html;

    } catch (e) {
        console.error("Error card leads:", e);
    } finally {
        loader.classList.add("d-none");
    }
}

fetch('ajax/ajax.php?accion=rst_frm_dia&cod_emp=1')
    .then(r => r.json())
    .then(data => {
        construirTablaDias(data.porDia)
        construirTablaEstados(data.porEstado)
    })
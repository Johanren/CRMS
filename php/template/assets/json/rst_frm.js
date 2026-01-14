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
        { data: "asesor_nombre", title: "Asesor" },
        { data: "obs_rst", title: "Observación" }
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

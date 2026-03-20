document.addEventListener("DOMContentLoaded", () => {
    $(document).ready(function () {

        // Función centralizada para ejecutar cualquier función de recarga que exista en la página
        function refrescarDatos() {
            const funcionesRecarga = [
                'listarLeads', 'cargarKanban', 'cargarContactGrid',
                'listarLeadsReporte', 'listarReporteCRMS', 'listarReporteEstadoLeads',
                'listarEstadoLead', 'listarReporteFuente', 'listarReporteRstFrm'
            ];

            funcionesRecarga.forEach(nombreFunc => {
                if (typeof window[nombreFunc] === 'function') {
                    window[nombreFunc]();
                }
            });
        }

        // 1. Lógica de "Seleccionar todos"
        $(document).on('change', '.select-all-filter', function () {
            let targetSelector = $(this).data('target');
            let isChecked = $(this).is(':checked');

            // Marcamos los hijos SIN disparar sus eventos individuales (evita el bucle)
            $(targetSelector).prop('checked', isChecked);

            // Ejecutamos la recarga UNA SOLA VEZ
            refrescarDatos();
        });

        // 2. Lógica para los checkboxes hijos (individuales)
        $(document).on('change', 'input[type="checkbox"]:not(.select-all-filter)', function () {
            let classList = $(this).attr('class') ? $(this).attr('class').split(' ') : [];
            let filterClass = classList.filter(c => c.startsWith('filtro-'))[0];

            if (!filterClass) return;

            let clise = "." + filterClass;
            let allSelectedCheckbox = $(`.select-all-filter[data-target="${clise}"]`);

            // Actualizar el estado del "Seleccionar todos" visualmente
            let total = $(clise).length;
            let marcados = $(clise + ":checked").length;
            allSelectedCheckbox.prop('checked', (total > 0 && total === marcados));

            // Ejecutar la recarga
            refrescarDatos();
        });
    });

    FiltrosUI.init();
});

const FiltrosUI = {

    dom: {},
    debounceTimer: null,

    init() {
        this.cacheDOM();
        if (!this.dom.contenedor) return;

        this.renderBotones();
        this.bindEvents();
        this.cargarFiltros(true); // auto aplicar al cargar
    },

    cacheDOM() {
        this.dom = {
            contenedor: document.getElementById("contenedor-botones"),
            buscador: document.getElementById("buscador"),
            fechaInicio: document.getElementById("fecha_inicio"),
            fechaFin: document.getElementById("fecha_fin"),
            resumen: document.getElementById("resumen-filtros")
        };
    },

    renderBotones() {
        this.dom.contenedor.innerHTML = `
            <div class="d-flex align-items-center gap-2 mt-2">
                <button id="btnGuardarFiltros" class="btn btn-outline-primary w-100">Guardar filtros</button>
                <button id="btnCargarFiltros" class="btn btn-primary w-100 d-none">Aplicar filtros guardados</button>
                <button id="btnRestablecerFiltros" class="btn btn-outline-danger w-100">Restablecer filtros</button>
            </div>
        `;
    },

    bindEvents() {
        document.getElementById("btnGuardarFiltros")?.addEventListener("click", () => this.guardarFiltros());
        document.getElementById("btnCargarFiltros")?.addEventListener("click", () => this.cargarFiltros());
        document.getElementById("btnRestablecerFiltros")?.addEventListener("click", () => this.restablecerFiltros());
    },

    guardarFiltros() {
        const filtros = window.Filtros.obtener();

        fetch("ajax/ajax.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `accion=guardar_filtros&filtros=${encodeURIComponent(JSON.stringify(filtros))}`
        })
            .then(r => r.json())
            .then(r => Swal.fire(r.success ? "Filtros Guardados" : "Error", r.message || "", r.success ? "success" : "error"));
    },

    cargarFiltros(auto = false) {
        fetch("ajax/ajax.php?accion=cargar_filtro")
            .then(r => r.json())
            .then(filtros => {
                if (!filtros) {
                    if (!auto) Swal.fire("Sin filtros guardados", "", "warning");
                    return;
                }
                this.aplicarFiltros(filtros);
            });
    },

    aplicarFiltros(filtros) {
        filtros = this.normalizarFiltros(filtros);

        // Actualización de inputs directos
        if (this.dom.buscador) this.dom.buscador.value = filtros.texto;
        if (this.dom.fechaInicio) this.dom.fechaInicio.value = filtros.fecha_inicio;
        if (this.dom.fechaFin) this.dom.fechaFin.value = filtros.fecha_fin;

        window.fecha_inicio = filtros.fecha_inicio;
        window.fecha_fin = filtros.fecha_fin;

        // Mapeo de selectores vs datos del objeto filtros
        const mapeo = {
            ".filtro-asesor": filtros.asesor,
            ".filtro-carrera": filtros.carreras,
            ".filtro-horario": filtros.horario,
            ".filtro-interes": filtros.interes,
            ".filtro-medio": filtros.medio,
            ".filtro-fuente": filtros.fuente,
            ".filtro-campana": filtros.campana,
            ".filtro-accion": filtros.accion,
            ".filtro-dep": filtros.departamento,
            ".filtro-ciu": filtros.ciudad,
            ".filtro-brr": filtros.barrio,
            ".filtro-estado": filtros.estados
        };

        // Marcado masivo silencioso
        Object.entries(mapeo).forEach(([selector, valores]) => {
            this.marcarSilencioso(selector, valores);
        });

        // Una sola actualización para todo
        this.actualizarVistas();
        this.mostrarResumen(filtros);
    },

    marcarSilencioso(selector, valores) {
        if (!valores) return;

        const $elementos = $(selector);
        if ($elementos.length === 0) return;

        // Marcamos cada checkbox hijo
        $elementos.each(function () {
            $(this).prop('checked', valores.includes($(this).val()));
        });

        // IMPORTANTE: Actualizar el checkbox "Seleccionar Todos" de este grupo
        // Buscamos el checkbox maestro que tenga este selector como data-target
        const total = $elementos.length;
        const marcados = $elementos.filter(':checked').length;
        $(`.select-all-filter[data-target="${selector}"]`).prop('checked', total > 0 && total === marcados);
    },

    normalizarFiltros(filtros) {
        filtros = typeof filtros === "string" ? JSON.parse(filtros) : filtros;

        const arrays = [
            "asesor", "carreras", "horario", "interes", "medio",
            "fuente", "campana", "accion", "departamento",
            "ciudad", "barrio", "estados"
        ];

        arrays.forEach(k => filtros[k] = Array.isArray(filtros[k]) ? filtros[k] : []);

        filtros.texto = filtros.texto || "";
        filtros.fecha_inicio = filtros.fecha_inicio || "";
        filtros.fecha_fin = filtros.fecha_fin || "";

        return filtros;
    },

    marcar(selector, valores) {
        if (!valores || !Array.isArray(valores)) return;

        $(selector).each(function () {
            $(this).prop('checked', valores.includes($(this).val()));
        });
        $(selector).trigger('change');
    },

    actualizarVistas() {
        // Aumentamos un poco el debounce para asegurar que el DOM terminó de procesar
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => {
            console.log("Re-listando datos con filtros aplicados...");

            const metodos = [
                'listarLeads', 'cargarKanban', 'cargarContactGrid',
                'listarLeadsReporte', 'listarReporteCRMS', 'listarReporteEstadoLeads',
                'listarEstadoLead', 'listarReporteFuente', 'listarReporteRstFrm'
            ];

            metodos.forEach(f => {
                if (typeof window[f] === 'function') {
                    window[f]();
                }
            });
        }, 200);
    },

    mostrarResumen(filtros) {
        if (!this.dom.resumen) return;

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

        const resumen = Object.keys(map)
            .filter(k => filtros[k]?.length || filtros[k])
            .map(k => `${map[k]}: ${Array.isArray(filtros[k]) ? filtros[k].join(", ") : filtros[k]}`);

        this.dom.resumen.innerText = resumen.length
            ? "Filtros aplicados: " + resumen.join(" | ")
            : "Sin filtros aplicados";
    },

    restablecerFiltros() {
        fetch("ajax/ajax.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "accion=eliminar_filtros"
        })
            .then(r => r.json())
            .then(resp => {
                if (!resp.success) {
                    Swal.fire("Error", resp.message || "", "error");
                    return;
                }

                // 1. Limpiar todos los checkboxes que empiezan con 'filtro-'
                const filtrosCheck = document.querySelectorAll("[class^='filtro-']");
                filtrosCheck.forEach(c => c.checked = false);

                // 2. NUEVO: Limpiar también las casillas de "Seleccionar todos"
                const selectAllCheck = document.querySelectorAll(".select-all-filter");
                selectAllCheck.forEach(c => c.checked = false);

                // 3. NUEVO: Disparar el evento change (usando jQuery para asegurar compatibilidad con el listener)
                // Esto le avisa a cualquier otra lógica que los filtros han cambiado
                $("[class^='filtro-'], .select-all-filter").trigger('change');

                // 4. Limpiar inputs de texto y fechas
                if (this.dom.buscador) this.dom.buscador.value = "";
                if (this.dom.fechaInicio) this.dom.fechaInicio.value = "";
                if (this.dom.fechaFin) this.dom.fechaFin.value = "";

                window.fecha_inicio = "";
                window.fecha_fin = "";

                // 5. Limpiar filtros globales si existe la función
                window.Filtros?.limpiar?.();

                this.actualizarVistas();

                if (this.dom.resumen) {
                    this.dom.resumen.innerText = "Sin filtros aplicados";
                }

                Swal.fire("Filtros restablecidos", "", "info");
            });
    }
};

/* =========================
   ENVÍO DE FILTROS A LEADS
========================= */
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
        estados: [
            "Nuevo Leads",
            "Leads Activo",
            "Interesado",
            "En Decisión",
            "Matricula en proceso",
            "Prospecto"
        ],
        fecha_inicio: "",
        fecha_fin: ""
    };

    fetch("ajax/ajax.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `accion=guardar_filtros&filtros=${encodeURIComponent(JSON.stringify(filtros))}`
    })
        .then(r => r.json())
        .then(resp => {
            if (!resp.success) {
                Swal.fire("Error", "No se pudieron guardar los filtros", "error");
                return;
            }
            window.location.href = "leads.php";
        })
        .catch(() => Swal.fire("Error", "Error al enviar filtros", "error"));
}

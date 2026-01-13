document.addEventListener("DOMContentLoaded", () => {
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

        if (this.dom.buscador) this.dom.buscador.value = filtros.texto;
        if (this.dom.fechaInicio) this.dom.fechaInicio.value = filtros.fecha_inicio;
        if (this.dom.fechaFin) this.dom.fechaFin.value = filtros.fecha_fin;

        this.marcar(".filtro-asesor", filtros.asesor);
        this.marcar(".filtro-carrera", filtros.carreras);
        this.marcar(".filtro-horario", filtros.horario);
        this.marcar(".filtro-interes", filtros.interes);
        this.marcar(".filtro-medio", filtros.medio);
        this.marcar(".filtro-fuente", filtros.fuente);
        this.marcar(".filtro-campana", filtros.campana);
        this.marcar(".filtro-accion", filtros.accion);
        this.marcar(".filtro-dep", filtros.departamento);
        this.marcar(".filtro-ciu", filtros.ciudad);
        this.marcar(".filtro-brr", filtros.barrio);
        this.marcar(".filtro-estado", filtros.estados);

        window.fecha_inicio = filtros.fecha_inicio;
        window.fecha_fin = filtros.fecha_fin;

        this.actualizarVistas();
        this.mostrarResumen(filtros);
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
        document.querySelectorAll(selector).forEach(chk => {
            chk.checked = valores.includes(chk.value);
        });
    },

    actualizarVistas() {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => {
            window.listarLeads?.();
            window.cargarKanban?.();
            window.cargarContactGrid?.();
            window.listarLeadsReporte?.();
        }, 120);
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

                document.querySelectorAll("[class^='filtro-']").forEach(c => c.checked = false);
                if (this.dom.buscador) this.dom.buscador.value = "";
                if (this.dom.fechaInicio) this.dom.fechaInicio.value = "";
                if (this.dom.fechaFin) this.dom.fechaFin.value = "";

                window.fecha_inicio = "";
                window.fecha_fin = "";

                window.Filtros?.limpiar?.();

                this.actualizarVistas();
                if (this.dom.resumen) this.dom.resumen.innerText = "Sin filtros aplicados";

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
            "Matricula en proceso"
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

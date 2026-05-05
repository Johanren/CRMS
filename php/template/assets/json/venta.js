function obtenerPaginaActual() {
    return window.location.pathname.split('/').pop();
}
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
async function cargarTablaFoco() {

    const datos = new FormData();
    datos.append("accion", "tabla_foco");

    const res = await fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    });

    const data = await res.json();

    const jornadas = [...new Set(data.map(d => d.jornada))];
    const programas = [...new Set(data.map(d => d.programa))];

    const thead = document.querySelector("#tablaFoco thead");
    const tbody = document.querySelector("#tablaFoco tbody");

    /* ================= HEADER ================= */
    let h1 = `<tr><th rowspan="2">Jornada</th>`;
    programas.forEach(p => {
        h1 += `<th colspan="4">${p}</th>`;
    });
    h1 += `<th colspan="3">Total</th></tr>`;

    let h2 = `<tr>`;
    programas.forEach(() => {
        h2 += `<th>Cupos</th><th>Venta</th><th>Reintegro</th><th>Acción</th>`;
    });
    h2 += `<th>Cupos</th><th>Venta</th><th>Reintegros</th></tr>`;

    thead.innerHTML = h1 + h2;

    /* ================= BODY ================= */
    tbody.innerHTML = "";

    const totalesPrograma = {};
    programas.forEach(p => {
        totalesPrograma[p] = { c: 0, v: 0, r: 0 };
    });

    let totalGeneralC = 0;
    let totalGeneralV = 0;
    let totalGeneralR = 0;

    jornadas.forEach(jornada => {

        let fila = `<tr><td>${jornada}</td>`;
        let totalC = 0,
            totalV = 0,
            totalR = 0;

        programas.forEach(programa => {

            const filaData = data.find(d => d.jornada === jornada && d.programa === programa);

            const c = filaData ? parseInt(filaData.ventas) : 0;
            const v = filaData ? parseInt(filaData.cupos) : 0;
            const r = filaData ? parseInt(filaData.reintegros) : 0;

            totalC += c;
            totalV += v;
            totalR += r;

            totalesPrograma[programa].c += c;
            totalesPrograma[programa].v += v;
            totalesPrograma[programa].r += r;

            fila += `
                <td class="no-editable"
                    data-jornada="${jornada}"
                    data-programa="${programa}"
                    data-campo="cupos">${c}</td>

                <td class="editable"
                    data-jornada="${jornada}"
                    data-programa="${programa}"
                    data-campo="ventas">${v}</td>

                <td class="editable"
                    data-jornada="${jornada}"
                    data-programa="${programa}"
                    data-campo="reintegros">${r}</td>

                <td class="text-center">
                    <button class="btn btn-sm btn-danger btnEliminar"
                        data-jornada="${jornada}"
                        data-programa="${programa}">
                        🗑️
                    </button>
                </td>
            `;
        });

        fila += `
            <td><b>${totalC}</b></td>
            <td><b>${totalV}</b></td>
            <td><b>${totalR}</b></td>
        </tr>`;

        totalGeneralC += totalC;
        totalGeneralV += totalV;
        totalGeneralR += totalR;

        tbody.innerHTML += fila;
    });

    /* ================= FILA FINAL TOTALES ================= */
    let filaTotales = `<tr class="table-secondary fw-bold"><td>Totales</td>`;

    programas.forEach(p => {
        filaTotales += `
            <td>${totalesPrograma[p].c}</td>
            <td>${totalesPrograma[p].v}</td>
            <td>${totalesPrograma[p].r}</td>
            <td></td>
        `;
    });

    filaTotales += `
        <td>${totalGeneralC}</td>
        <td>${totalGeneralV}</td>
        <td>${totalGeneralR}</td>
    </tr>`;

    tbody.innerHTML += filaTotales;
}

/* ================= ACTIVAR SELECCIÓN DE FILA (NO INTERFIERE) ================= */
function activarSeleccionFila(tablaId) {
    const tabla = document.getElementById(tablaId);
    if (!tabla) return;

    tabla.addEventListener("click", function (e) {
        const fila = e.target.closest("tr");
        if (!fila || fila.parentNode.tagName !== "TBODY") return;

        tabla.querySelectorAll(".fila-activa")
            .forEach(f => f.classList.remove("fila-activa"));

        fila.classList.add("fila-activa");
    });
}

/* ================= FUNCIÓN PRINCIPAL ================= */
async function cargarTablaFocoReporte() {

    const loader = document.getElementById("loaderFoco");

    try {
        loader.classList.remove("d-none");

        /* ================= DATOS FOCO ================= */
        const fdForm = new FormData();
        fdForm.append("accion", "tabla_foco");

        const focoRes = await fetch("ajax/ajax.php", {
            method: "POST",
            body: fdForm
        });
        await focoRes.json(); // se mantiene aunque no se use directamente

        /* ================= DATOS LEADS ================= */
        const leadForm = new FormData();
        leadForm.append("accion", "leads_foco_detalle");

        const leadRes = await fetch("ajax/ajax.php", {
            method: "POST",
            body: leadForm
        });
        const leadsData = await leadRes.json();

        /* ================= JORNADAS ================= */
        const jornadas = [...new Map(
            leadsData.map(d => [d.id_jornada, {
                id_jornada: d.id_jornada,
                jornada: d.jornada
            }])
        ).values()];

        const programas = [...new Set(leadsData.map(d => d.programa))];

        const thead = document.querySelector("#tablaFocoReporte thead");
        const tbody = document.querySelector("#tablaFocoReporte tbody");

        /* ================= HEADER ================= */
        let h1 = `<tr><th rowspan="2">Jornada</th>`;
        programas.forEach(p => h1 += `<th colspan="3">${p}</th>`);
        h1 += `<th colspan="3">Total</th></tr>`;

        let h2 = `<tr>`;
        programas.forEach(() => {
            h2 += `<th>Cupos</th><th>Ventas</th><th>Reintegros</th>`;
        });
        h2 += `<th>Cupos</th><th>Ventas</th><th>Reintegros</th></tr>`;

        thead.innerHTML = h1 + h2;
        tbody.innerHTML = "";

        /* ================= ACUMULADORES ================= */
        const totalesLeads = {};
        programas.forEach(p => totalesLeads[p] = { con: 0, solo: 0 });

        let totalConHorarioGeneral = 0;
        let totalSoloCarreraGeneral = 0;

        /* ================= BODY ================= */
        jornadas.forEach(j => {

            const { id_jornada, jornada } = j;

            /* ===== FILA CUPOS ===== */
            let filaCupos = `<tr class="fila-dato"><td rowspan="3">${jornada}</td>`;
            let totalFilaCupos = 0;

            programas.forEach(programa => {
                const d = leadsData.find(f =>
                    f.id_jornada === id_jornada &&
                    f.programa === programa
                );

                const cupos = d ? Number(d.cupos) : 0;
                totalFilaCupos += cupos;

                filaCupos += `<td colspan="3">${cupos}</td>`;
            });

            filaCupos += `<td colspan="3"><b>${totalFilaCupos}</b></td></tr>`;
            tbody.innerHTML += filaCupos;

            /* ===== FILA VENTAS / REINTEGROS ===== */
            let filaVR = `<tr class="fila-dato">`;
            let totalFilaV = 0;
            let totalFilaR = 0;

            programas.forEach(programa => {
                const d = leadsData.find(f =>
                    f.id_jornada === id_jornada &&
                    f.programa === programa
                );

                const ventas = d ? Number(d.ventas) : 0;
                const reintegros = d ? Number(d.reintegros) : 0;

                totalFilaV += ventas;
                totalFilaR += reintegros;

                filaVR += `
                    <td>${ventas}</td>
                    <td>${ventas - reintegros}</td>
                    <td>${reintegros}</td>
                `;
            });

            filaVR += `
                <td><b>${totalFilaV}</b></td>
                <td><b>${totalFilaV - totalFilaR}</b></td>
                <td><b>${totalFilaR}</b></td>
            </tr>`;
            tbody.innerHTML += filaVR;

            /* ===== FILA LEADS (NO SE TOCA TU BLOQUE) ===== */
            let filaLeads = `<tr class="fila-dato">`;
            let totalConHorarioFila = 0;
            let totalSoloCarreraFila = 0;

            programas.forEach(programa => {

                const leads = leadsData.filter(l =>
                    l.id_jornada === id_jornada &&
                    l.programa === programa
                );

                const conHorario = leads.reduce((a, b) => a + Number(b.con_horario), 0);
                const soloCarrera = leads.reduce((a, b) => a + Number(b.solo_carrera), 0);

                totalesLeads[programa].con += conHorario;
                totalesLeads[programa].solo += soloCarrera;

                totalConHorarioFila += conHorario;
                totalSoloCarreraFila += soloCarrera;

                /* 🔒 BLOQUE ORIGINAL INTACTO 🔒 */
                filaLeads += `
                    <td colspan="2"
                        class="text-primary fw-bold cursor-pointer"
                        onclick="enviarFiltrosALeads('${id_jornada}','${programa}','con_horario')">
                        ${conHorario}
                    </td>
                    <td colspan="1"
                        class="text-primary fw-bold cursor-pointer"
                        onclick="enviarFiltrosALeads('','${programa}','solo_carrera')">
                        ${soloCarrera}
                    </td>
                `;
            });

            totalConHorarioGeneral += totalConHorarioFila;
            totalSoloCarreraGeneral += totalSoloCarreraFila;

            filaLeads += `
                <td colspan="2"><b>${totalConHorarioFila}</b></td>
                <td><b>${totalSoloCarreraFila}</b></td>
            </tr>`;
            tbody.innerHTML += filaLeads;
        });

        /* ===== FILA TOTALES ===== */
        let filaTot = `<tr class="table-secondary fw-bold fila-dato"><td>Totales</td>`;

        programas.forEach(p => {
            filaTot += `
                <td colspan="2">${totalesLeads[p].con}</td>
                <td>${totalesLeads[p].solo}</td>
            `;
        });

        filaTot += `
            <td colspan="2">${totalConHorarioGeneral}</td>
            <td>${totalSoloCarreraGeneral}</td>
        </tr>`;

        tbody.innerHTML += filaTot;

        /* ===== ACTIVAR COLOR POR FILA (SEGURO) ===== */
        activarSeleccionFila("tablaFocoReporte");

    } catch (e) {
        console.error("Error tabla foco:", e);
    } finally {
        loader.classList.add("d-none");
    }
}

// Variable global para controlar la cancelación de peticiones anteriores
let abortController;

/**
 * Función principal optimizada.
 * Implementa construcción de string eficiente y manejo de concurrencia.
 */
async function cargarTablaFocoResultado() {
    // 1. Cancelar petición en curso si existe para evitar "cola" de procesos
    if (abortController) {
        abortController.abort();
    }
    abortController = new AbortController();
    const { signal } = abortController;

    const loader = document.getElementById("loaderFoco");
    const thead = document.querySelector("#tablaFocoResultado thead");
    const tbody = document.querySelector("#tablaFocoResultado tbody");

    // --- LÓGICA DE FILTROS ---
    const f = Filtros.obtener();
    const params = new FormData();
    params.append("accion", "leads_foco_resultado");

    if (f.texto !== "") params.append("texto", f.texto);
    if (f.asesor && f.asesor.length > 0) params.append("asesor", JSON.stringify(f.asesor));
    if (f.estados && f.estados.length > 0) params.append("estados", JSON.stringify(f.estados));
    if (f.carreras && f.carreras.length > 0) params.append("carreras", JSON.stringify(f.carreras));
    if (f.fecha_inicio !== "") params.append("fecha_inicio", f.fecha_inicio);
    if (f.fecha_fin !== "") params.append("fecha_fin", f.fecha_fin);

    try {
        loader.classList.remove("d-none");

        const leadRes = await fetch("ajax/ajax.php", {
            method: "POST",
            body: params,
            signal: signal // Asignamos la señal de aborto
        });

        const leadsData = await leadRes.json();

        // --- CÁLCULOS INICIALES ---
        // Calculamos la MODA (el que más se repite) para el encabezado
        const conteoCupos = {};
        leadsData.forEach(d => {
            conteoCupos[d.ventas] = (conteoCupos[d.ventas] || 0) + 1;
        });
        const cupoModa = Object.keys(conteoCupos).reduce((a, b) => conteoCupos[a] > conteoCupos[b] ? a : b);
        const totalLeads = leadsData.reduce((total, d) => total + Number(d.con_horario || 0), 0);
        const totalVendi = leadsData.reduce((total, d) => total + Number(d.ventas_estado_6 || 0), 0);

        // --- RENDERIZADO DE THEAD ---
        // Usamos cupoModa para el resumen inicial
        thead.innerHTML = `
            <tr class="fw-bold text-center">
                <th id="resumenCupo1" style="cursor:pointer" colspan="1" data-base="${cupoModa}">${cupoModa}</th>
                <th id="resumenPorcentaje" style="cursor:pointer" colspan="2">100%</th>
                <th class="resumenCupo2" colspan="1">${cupoModa}</th>
                <th colspan="6" class="bg-warning text-center">VENTAS</th>
                <th colspan="6" class="bg-primary text-white text-center">REINTEGROS</th>
                <th colspan="3" class="bg-info text-center">RESULTADOS</th>
            </tr>
            <tr>
                <th rowspan="2">Técnica</th>
                <th rowspan="2">J</th>
                <th rowspan="2">Leads</th>
                <th rowspan="2">Cupos</th>
                <th rowspan="2">Meta</th>
                <th rowspan="2">Vendido</th>
                <th rowspan="2">Cumpl %</th>
                <th rowspan="2">Faltan</th>
                <th rowspan="2">Leads/Faltan</th>
                <th rowspan="2" id="thValorPrograma" style="cursor:pointer">$</th>
                <th rowspan="2">Meta</th>
                <th rowspan="2">ADN</th>
                <th rowspan="2">Reintegros</th>
                <th rowspan="2">Cumpl %</th>
                <th rowspan="2">Cupos</th>
                <th rowspan="2"></th>
                <th rowspan="2">Alumnos</th>
                <th rowspan="2">Densidad</th>
                <th rowspan="2">Faltan</th>
            </tr>
        `;

        // --- RENDERIZADO DE TBODY (USANDO ACUMULADOR DE STRING) ---
        // Esto es mucho más rápido que hacer innerHTML += en cada vuelta
        // --- RENDERIZADO DE TBODY ---
        let htmlRows = "";
        leadsData.forEach(row => {
            htmlRows += `
            <tr>
                <td>${row.programa}</td>
                <td>${row.jornada}</td>
                <td>
                    <a href="javascript:void(0);" 
                       class="abrir-mensajes-foco fw-bold text-primary"
                       data-programa="${row.programa}"
                       data-jornada="${row.jornada}">
                       ${row.con_horario}
                    </a>
                </td>
                <td class="col-cupos" data-base="${row.ventas}">${row.ventas}</td>
                <td class="col-metas">0</td>
                <td class="col-ventas" data-ventas="${row.ventas_estado_6}">${row.ventas_estado_6}</td>
                <td class="col-resultado">0%</td>
                <td class="col-restante">0</td>
                <td class="col-leads-restante">0</td>
                <td class="col-valor" data-valor="${row.valor_programa}"></td>
                <td class="col-meta">0</td>
                <td class="col-ADN" data-ADN="0">0</td>
                <td class="col-reintegro" data-reintegro="0">0</td>
                <td class="col-resulado-reintegro">0%</td>
                <td class="col-cupo-reintegro">0</td>
                <td class="col-cupo-ADN">0</td>
                <td class="col-alumno">0</td>
                <td class="col-densidad">0%</td>
                <td class="col-falta">0</td>
            </tr>`;
        });

        // Fila Totales
        htmlRows += `
        <tr class="fw-bold table-secondary">
            <td>Total Grupos</td>
            <td id="totalGrupo"></td>
            <td id="totalLeads">${totalLeads}</td>
            <td id="totalCupos">0</td>
            <td id="totalMeta">0</td>
            <td>${totalVendi}</td>
            <td id="totalCumpl">0</td>
            <td id="totalRestante">0</td>
            <td id="totalLeadsRestante">0</td>
            <td id="totalValor">0</td>
            <td id="totalMetaIntegro">0</td>
            <td>0</td>
            <td>0</td>
            <td id="totalCumIntegro">0</td>
            <td id="totalCupoIntegro">0</td>
            <td id="totalADN">0</td>
            <td id="totalAlumno">0</td>
            <td id="totalDensidad">0</td>
            <td id="totalFalta">0</td>
        </tr>
        <tr class="fw-bold table-secondary">
            <td>Alumnos x Grupo</td>
            <td id="totalalumnoPorciento"></td>
            <td id="totalAlumnosBajo"></td>
            <td id="totalDensidadPorciento1">0</td>
            <td></td><td></td><td></td><td></td><td></td>
            <td id="totalValorPorcen">0</td>
            <td id="totalMetaPorcen">0</td>
            <td></td>
            <td>0%</td>
            <td colspan="2" id="totalCuposPorcen">0</td>
            <td></td>
            <td id="totalalumnoPorciento1">0</td>
            <td id="totalDensidadPorciento">0</td>
            <td id="totalFaltaPorciento">0</td>
        </tr>
        <tr class="fw-bold table-secondary">
            <td></td>
            <td>Ventas</td>
            <td id="totalVendi" data-vendi="${totalVendi}">${totalVendi}</td>
            <td id="tdvalorVendi">0%</td>
        </tr>
        <tr class="fw-bold table-secondary">
            <td></td>
            <td>Reintegros</td>
            <td>0</td>
            <td>0%</td>
        </tr>`;

        // Inserción masiva en el DOM (Una sola operación)
        tbody.innerHTML = htmlRows;

        // Ejecutar lógicas adicionales
        activarPorcentajeResumen(leadsData);
        activarSeleccionFila("tablaFocoResultado");

    } catch (e) {
        if (e.name === 'AbortError') {
            console.log("Petición cancelada porque el usuario cambió los filtros rápidamente.");
        } else {
            console.error("Error tabla foco:", e);
        }
    } finally {
        // Solo ocultamos el loader si no es una cancelación por nueva petición
        if (!signal.aborted) {
            loader.classList.add("d-none");
        }
    }
}

/**
 * DEBOUNCE: Esta es la clave para que los filtros no bloqueen la web.
 * Envuelve tu llamada a los filtros con esta función.
 */
let timeoutFiltro;

function manejarCambioFiltro() {
    clearTimeout(timeoutFiltro);
    timeoutFiltro = setTimeout(() => {
        cargarTablaFocoResultado();
    }, 500);
}

/* ==========================================================
   VARIABLES GLOBALES PARA MANTENER EL ESTADO
   ========================================================== */
window.programaSeleccionado = null;
window.jornadaSeleccionada = null;
window.estadosSeleccionadosNombres = [];
window.asesoresSeleccionadosIds = [];

/* ==========================================================
   1. ABRIR LEADS DESDE FOCO (TABLA PRINCIPAL)
   ========================================================== */
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("abrir-mensajes-foco")) {

        // 1. Detectar si el clic viene de la tabla Pivot (Estados/Asesores)
        // o de la tabla RST (Programas/Horarios)
        const programa = e.target.dataset.programa;
        const jornada = e.target.dataset.jornada;
        const estado = e.target.dataset.estado; // Nuevo
        const user = e.target.dataset.user;   // Nuevo (ID)

        // Si existen 'estado' o 'user', es que venimos de la tabla Pivot
        if (estado || user) {
            listarLeadsDesdeFocoPivot(estado, user);
        } else {
            // Si no, usamos la lógica anterior de programa/jornada
            window.programaSeleccionado = programa;
            window.jornadaSeleccionada = jornada;
            listarLeadsDesdeFoco(programa, jornada);
        }
    }
});

// Nueva función espejo para no alterar la original listarLeadsDesdeFoco
function listarLeadsDesdeFocoPivot(estadoNombre, userId) {
    const params = new URLSearchParams();
    params.append("accion", "listar_leads");

    // 1. Lógica de Estados
    if (estadoNombre && estadoNombre !== "TODOS") {
        params.append("estadosPer", JSON.stringify([estadoNombre]));
    } else {
        // Si es TODOS, capturamos los que estén marcados en el filtro lateral por defecto
        const estadosValues = Array.from(document.querySelectorAll('#listar_filtro_estado input[type="checkbox"]:checked'))
            .map(cb => cb.value).filter(v => v);
        if (estadosValues.length > 0) params.append("estadosPer", JSON.stringify(estadosValues));
    }

    // 2. Lógica de Asesor (User)
    if (userId && userId !== "TODOS") {
        params.append("asesor", JSON.stringify([userId]));
    } else {
        const asesoresIds = Array.from(document.querySelectorAll('#listar_filtro_user input[type="checkbox"]:checked'))
            .map(cb => cb.value).filter(v => v);
        if (asesoresIds.length > 0) params.append("asesor", JSON.stringify(asesoresIds));
    }

    // 3. Mantener otros filtros (Carreras/Horarios) que ya estén marcados
    const carrerasIds = Array.from(document.querySelectorAll('#listar_filtro_carrera input[type="checkbox"]:checked'))
        .map(cb => cb.value).filter(v => v);
    if (carrerasIds.length > 0) params.append("carreras", JSON.stringify(carrerasIds));

    params.append("lead_reporte_CRM_FOCO", "true");

    fetch("ajax/ajax.php?" + params.toString())
        .then(res => res.json())
        .then(data => {
            if (document.getElementById("leads_list")) {
                inicializarDataTableLeads(data);
                const contenedor = document.getElementById("contenedorLeadsFoco");
                if (contenedor) contenedor.classList.remove("d-none");
                document.getElementById("contenedorLeadsFoco").scrollIntoView({ behavior: 'smooth' });
            }
        })
        .catch(err => console.error("Error al listar leads pivot:", err));
}

window.dataLeadsGlobal = [];
let tablaLeadsFoco = null;

/* ==========================================================
   CONSULTA PRINCIPAL
========================================================== */

function listarLeadsDesdeFoco(programaNombre, jornadaNombre) {

    const f = Filtros.obtener();
    const params = new URLSearchParams();
    params.append("accion", "listar_leads");

    /* CARRERAS */
    let carrerasArray = [];
    if (programaNombre && programaNombre !== "TODOS") {
        carrerasArray = [programaNombre];
    }

    /* JORNADAS */
    let horarioArray = [];

    if (jornadaNombre && jornadaNombre !== "TODOS") {
        try {
            const parsed = JSON.parse(jornadaNombre);
            horarioArray = Array.isArray(parsed) ? parsed : [parsed];
        } catch (e) {
            horarioArray = [jornadaNombre];
        }

        //  Validar si existe "POR CONFIRMAR"
        const existePorConfirmar = horarioArray.some(item =>
            typeof item === "string" &&
            item.trim().toUpperCase() === "POR CONFIRMAR"
        );

        //  Si existe, agregar "NULL" (evitando duplicados)
        if (existePorConfirmar && !horarioArray.includes("NULL")) {
            horarioArray.push("NULL");
        }
    }

    /* ESTADOS */
    const estadosValues = Array.from(
        document.querySelectorAll('#listar_filtro_estado input[type="checkbox"]:checked')
    )
        .map(cb => cb.value)
        .filter(v => v);

    /* ASESORES */
    const asesoresIds = Array.from(
        document.querySelectorAll('#listar_filtro_user input[type="checkbox"]:checked')
    )
        .map(cb => cb.value)
        .filter(v => v);

    /* CARRERAS CHECKBOX */
    const carrerasIds = Array.from(
        document.querySelectorAll('#listar_filtro_carrera input[type="checkbox"]:checked')
    )
        .map(cb => cb.value)
        .filter(v => v);

    let carrerasFinal = carrerasArray.length > 0 ? carrerasArray : carrerasIds;

    /* PARAMS */
    if (carrerasFinal.length > 0)
        params.append("carreras", JSON.stringify(carrerasFinal));

    if (horarioArray.length > 0)
        params.append("horario", JSON.stringify(horarioArray));

    if (asesoresIds.length > 0)
        params.append("asesor", JSON.stringify(asesoresIds));

    if (estadosValues.length > 0)
        params.append("estados", JSON.stringify(estadosValues));

    if (f.fecha_inicio !== "") params.append("fecha_inicio", f.fecha_inicio);
    if (f.fecha_fin !== "") params.append("fecha_fin", f.fecha_fin);

    params.append("lead_reporte_CRM_FOCO", "true");

    fetch("ajax/ajax.php?" + params.toString())
        .then(res => res.json())
        .then(data => {

            if (!data || data.error) return;

            window.dataLeadsGlobal = data;

            const contenedor = document.getElementById("contenedorLeadsFoco");
            if (contenedor) contenedor.classList.add("d-none");

            /* SOLO TABLA RESUMEN */
            renderTablaResumen(data, programaNombre, jornadaNombre);

            /* LIMPIAR DETALLE */
            limpiarTablaLeads();
            document.getElementById("tablaResumenFiltros")
                .scrollIntoView({ behavior: "smooth" });

        })
        .catch(err => console.error(err));
}

/* ==========================================================
   TABLA RESUMEN
========================================================== */

function renderTablaResumen(data, programaNombre, jornadaNombre) {

    const contenedor = document.getElementById("tablaResumenFiltros");
    if (!contenedor) return;

    contenedor.innerHTML = "";

    const ordenBaseEstados = [
        "Nuevo Leads",
        "Prospecto",
        "Leads Activo",
        "Interesado",
        "En Decisión",
        /*"Matricula en proceso",
        "Matriculado",
        "Perdido",
        "Aplazado",
        "DESERTOR",
        "NUNCA ASISTIO"*/
    ];

    /* ==========================================
       SOLO ESTADOS QUE EXISTEN EN LA DATA
    ========================================== */
    const estadosData = [...new Set(
        data.map(x => (x.estado || "").trim())
            .filter(v => v !== "")
    )];

    const estados = [
        ...ordenBaseEstados.filter(est => estadosData.includes(est)),
        ...estadosData.filter(est => !ordenBaseEstados.includes(est))
    ];

    let agrupado = {};
    let titulo = "";
    let primeraColumna = "";

    /* ==========================================
       SOLO JORNADA
    ========================================== */
    if (jornadaNombre !== "TODOS" && programaNombre === "TODOS") {

        titulo = "JORNADA: " + jornadaNombre;
        primeraColumna = "Carreras";

        data.forEach(row => {

            let fila = (row.desc_pro || "SIN CARRERA") + " - " + (row.horario || "SIN JORNADA");
            let estado = row.estado || "SIN ESTADO";

            if (!agrupado[fila]) agrupado[fila] = {};
            if (!agrupado[fila][estado]) agrupado[fila][estado] = 0;

            agrupado[fila][estado]++;
        });
    }

    /* ==========================================
       SOLO CARRERA
    ========================================== */
    else if (programaNombre !== "TODOS" && jornadaNombre === "TODOS") {

        titulo = "CARRERA: " + programaNombre;
        primeraColumna = "Jornadas";

        data.forEach(row => {

            let fila = (row.desc_pro || "SIN CARRERA") + " - " + (row.horario || "SIN JORNADA");
            let estado = row.estado || "SIN ESTADO";

            if (!agrupado[fila]) agrupado[fila] = {};
            if (!agrupado[fila][estado]) agrupado[fila][estado] = 0;

            agrupado[fila][estado]++;
        });
    }

    /* ==========================================
       CARRERA + JORNADA
    ========================================== */
    else {

        titulo = "RESULTADO FILTRADO";
        primeraColumna = "Detalle";

        data.forEach(row => {

            let fila = (row.desc_pro || "SIN CARRERA") + " - " + (row.horario || "SIN JORNADA");
            let estado = row.estado || "SIN ESTADO";

            if (!agrupado[fila]) agrupado[fila] = {};
            if (!agrupado[fila][estado]) agrupado[fila][estado] = 0;

            agrupado[fila][estado]++;
        });
    }

    /* ==================================================
       TABLA ESTILO EXCEL
    ================================================== */
    let html = `
    <div class="table-responsive">
    <table class="table-excel" id="tablaResumenCRM">
        <thead>
            <tr>
                <th>${primeraColumna}</th>
    `;

    estados.forEach(est => {
        html += `<th>${est}</th>`;
    });

    html += `<th>Total</th></tr></thead><tbody>`;

    let totalGeneral = 0;

    for (let fila in agrupado) {

        html += `<tr>`;
        html += `<td>${fila}</td>`;

        let totalFila = 0;

        estados.forEach(est => {

            let cantidad = agrupado[fila][est] || 0;
            totalFila += cantidad;

            if (cantidad > 0) {

                html += `
                <td class="text-primary fw-bold cursor-pointer"
                    onclick="abrirDetalle('${fila}','${est}','${programaNombre}','${jornadaNombre}')">
                    ${cantidad}
                </td>`;

            } else {

                html += `<td class="text-muted">-</td>`;
            }
        });

        totalGeneral += totalFila;

        html += `
        <td class="text-primary fw-bold cursor-pointer"
            onclick="abrirDetalle('${fila}','', '${programaNombre}', '${jornadaNombre}')">
            ${totalFila}
        </td>`;

        html += `</tr>`;
    }

    /* ==========================================
       FILA TOTALES
    ========================================== */
    html += `<tr class="table-total">`;

    html += `
    <td class="cursor-pointer"
        onclick="abrirDetalle('','', '${programaNombre}', '${jornadaNombre}')">
        TOTALES
    </td>`;

    estados.forEach(est => {

        let suma = 0;

        for (let fila in agrupado) {
            suma += agrupado[fila][est] || 0;
        }

        html += `
        <td class="cursor-pointer"
            onclick="abrirDetalle('', '${est}', '${programaNombre}', '${jornadaNombre}')">
            ${suma}
        </td>`;
    });

    html += `
    <td class="cursor-pointer"
        onclick="abrirDetalle('','', '${programaNombre}', '${jornadaNombre}')">
        ${totalGeneral}
    </td>`;

    html += `</tr>`;

    html += `</tbody></table></div>`;

    contenedor.innerHTML = html;
}

/* ==========================================================
   ABRIR DETALLE MEJORADO
========================================================== */
function abrirDetalle(fila, estado, programaNombre, jornadaNombre) {

    let filtrados = window.dataLeadsGlobal.filter(row => {

        let ok = true;

        /* ======================================
           FILTRO FILA
        ====================================== */
        if (fila) {

            const filaActual =
                (row.desc_pro || "") + " - " + (row.horario || "");

            ok = ok && filaActual === fila;
        }

        /* ======================================
           FILTRO ESTADO
        ====================================== */
        if (estado) {
            ok = ok && (row.estado || "") === estado;
        }

        return ok;
    });

    const contenedor = document.getElementById("contenedorLeadsFoco");

    if (contenedor) {
        contenedor.classList.remove("d-none");
    }

    inicializarDataTableLeads(filtrados);

    document
        .getElementById("contenedorLeadsFoco")
        .scrollIntoView({
            behavior: "smooth"
        });
}

/* ==========================================================
   LIMPIAR TABLA DETALLE
========================================================== */
function limpiarTablaLeads() {

    if (tablaLeadsFoco) {
        tablaLeadsFoco.destroy();
        tablaLeadsFoco = null;
    }

    const tbody = document.querySelector("#leads_list tbody");

    tbody.innerHTML = `
        <tr>
            <td colspan="9" class="text-center">
                Seleccione un valor del resumen
            </td>
        </tr>
    `;
}



/* ==========================================================
   TU TABLA ORIGINAL
========================================================== */
function inicializarDataTableLeads(data) {

    const tableId = '#leads_list';

    /* ==========================================
       DESTRUIR DATATABLE + QUITAR FILTROS PREVIOS
    ========================================== */
    if (tablaLeadsFoco) {
        tablaLeadsFoco.destroy();
        tablaLeadsFoco = null;
    }

    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().destroy();
    }

    $(tableId + ' thead tr:eq(1)').remove();

    /* ==========================================
       CONTADOR
    ========================================== */
    const spanContador = document.getElementById("contadorTotalLeads");
    if (spanContador) spanContador.innerText = data.length;

    /* ==========================================
       BODY
    ========================================== */
    const tbody = document.querySelector("#leads_list tbody");
    tbody.innerHTML = "";

    if (!data.length) {
        tbody.innerHTML = `
        <tr>
            <td colspan="9" class="text-center">
                No hay resultados
            </td>
        </tr>`;
        return;
    }

    const hoy = new Date().toISOString().split('T')[0];

    data.forEach(l => {

        const fueGestionadoHoy =
            l.fec_ult_gest === hoy
                ? '<span class="badge bg-success">OK</span>'
                : '<span class="badge bg-secondary">Pendiente</span>';

        const tr = document.createElement("tr");

        tr.innerHTML = `
            <td>
                <a href="javascript:void(0)"
                   class="text-primary fw-bold"
                   onclick="abrirModalGestion('${l.id_lead}','${l.cliente_id}')">
                   ${l.nombres} ${l.apellidos}
                </a>
            </td>
            <td>${l.desc_pro}</td>
            <td>${l.telefono_principal}</td>
            <td>${l.estado}</td>
            <td>${l.nombreAsesor}</td>
            <td>${l.fecha_creacion}</td>
            <td>${l.fec_ult_gest || ''}</td>
            <td>${l.fec_ult_asig || ''}</td>
            <td>${fueGestionadoHoy}</td>
        `;

        tbody.appendChild(tr);
    });

    /* ==========================================
       DATATABLE + FILTROS POR COLUMNA
    ========================================== */
    tablaLeadsFoco = $(tableId).DataTable({
        responsive: true,
        ordering: true,
        orderCellsTop: true,
        fixedHeader: true,
        pageLength: 10,

        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },

        initComplete: function () {

            const api = this.api();

            /* CLONAR HEADER */
            if ($(tableId + ' thead tr').length === 1) {
                $(tableId + ' thead tr')
                    .clone(true)
                    .appendTo(tableId + ' thead');
            }

            /* INPUTS FILTRO */
            $(tableId + ' thead tr:eq(1) th').each(function (i) {

                $(this).html(`
                    <input type="text"
                        class="form-control form-control-sm"
                        placeholder="Filtrar..."
                        style="width:100%;" />
                `);

                $('input', this).on('keyup change clear', function () {

                    if (api.column(i).search() !== this.value) {
                        api.column(i).search(this.value).draw();
                    }

                });
            });
        }
    });
}

function abrirModalGestion(idLead, idCliente) {

    const url = `leads-details.php?id=${idLead}&id_cliente=${idCliente}&modal=1`;
    document.getElementById('frameGestion').src = url;

    const modalElement = document.getElementById('modalGestionLead');
    let myModal = bootstrap.Modal.getInstance(modalElement);
    if (!myModal) myModal = new bootstrap.Modal(modalElement);
    myModal.show();

    const params = new URLSearchParams({
        accion: "actualizar_fecha_gestion",
        id_lead: idLead,
        cliente_id: idCliente
    });

    fetch("ajax/ajax.php?" + params.toString())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                console.log("✅ Fecha de gestión actualizada correctamente");
            } else {
                console.error("❌ Error:", data.message);
            }
        })
        .catch(err => console.error("Error en la petición:", err));
}

/* ==========================================================
   3. ABRIR MODAL MENSAJES (SINCRONIZACIÓN FINAL)
   ========================================================== */
document.getElementById("btnAbrirModalMensajes")?.addEventListener("click", function () {
    const modalElement = document.getElementById('modalMensajesFoco');
    if (!modalElement) return;

    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    abrirModuloMensajesDesdeFoco(window.programaSeleccionado, window.jornadaSeleccionada);
});

async function abrirModuloMensajesDesdeFoco(programaNombre, jornadaNombre) {
    // 1️⃣ Esperamos carga de filtros del modal
    await cargarMensajesPorTema();
    await cargarFiltrosRST();

    console.log("Sincronizando filtros en modal...");

    // 2️⃣ Marcar Carrera (Por Nombre)
    document.querySelectorAll('#filtro_carrera input[type="checkbox"]').forEach(cb => {
        const label = cb.nextElementSibling?.textContent?.trim().toUpperCase();
        if (label === programaNombre?.toUpperCase()) cb.checked = true;
    });

    // 3️⃣ Marcar Jornada (Por Nombre)
    document.querySelectorAll('#filtro_horario input[type="checkbox"]').forEach(cb => {
        const label = cb.nextElementSibling?.textContent?.trim().toUpperCase();
        if (label === jornadaNombre?.toUpperCase()) cb.checked = true;
    });

    // 4️⃣ 🔥 MARCAR ESTADOS (Réplica de lógica de Carrera - Por Nombre)
    document.querySelectorAll('#filtro_estado input[type="checkbox"]').forEach(cb => {
        cb.checked = false; // Reset
        const labelModal = cb.nextElementSibling?.textContent?.trim().toUpperCase();

        // Comparamos el nombre del checkbox del modal contra nuestro array guardado
        const existe = window.estadosSeleccionadosNombres.some(e => e.toUpperCase() === labelModal);
        if (existe) {
            cb.checked = true;
            console.log("Estado sincronizado:", labelModal);
        }
    });

    // 5️⃣ Marcar Asesores (Por ID)
    document.querySelectorAll('#filtro_asesor input').forEach(cb => {
        cb.checked = window.asesoresSeleccionadosIds.includes(cb.value);
    });

    // 6️⃣ Cargar la tabla del modal
    if (typeof validarYCargarTabla === "function") {
        validarYCargarTabla();
    }
}

/* ======================================================
   4. SINCRONIZACIÓN EN TIEMPO REAL (OPCIONAL)
====================================================== */
document.addEventListener("change", function (e) {
    const container = e.target.closest("#listar_filtro_user, #listar_filtro_estado");
    if (!container || e.target.type !== "checkbox") return;

    if (container.id === "listar_filtro_estado") {
        window.estadosSeleccionadosNombres = Array.from(document.querySelectorAll('#listar_filtro_estado input[type="checkbox"]:checked'))
            .map(cb => cb.nextElementSibling?.textContent?.trim() || "");
    }

    if (container.id === "listar_filtro_user") {
        window.asesoresSeleccionadosIds = Array.from(document.querySelectorAll('#listar_filtro_user input[type="checkbox"]:checked'))
            .map(cb => cb.value);
    }
});

function activarPorcentajeResumen(leadsData) {

    const thPorcentaje = document.getElementById("resumenPorcentaje");
    const thCupo1 = document.getElementById("resumenCupo1");
    const thCupo2 = document.querySelector(".resumenCupo2");
    const totalCupos = document.getElementById("totalCupos");
    const totalMeta = document.getElementById("totalMeta");
    const totalCumpl = document.getElementById("totalCumpl");
    const totalRestante = document.getElementById("totalRestante");
    const totalLR = document.getElementById("totalLeadsRestante");
    const totalValores = document.getElementById("totalValor");
    const totalMetaIntegro = document.getElementById("totalMetaIntegro");
    const totalCumIntegro = document.getElementById("totalCumIntegro");
    const totalCupoIntegro = document.getElementById("totalCupoIntegro");
    const ADNLEADS = document.getElementById("totalADN");
    const totalAlumnos = document.getElementById("totalAlumno");
    const totalAlumnosBajo = document.getElementById("totalAlumnosBajo");
    const totalDensidad = document.getElementById("totalDensidad");
    const totalFaltaSuma = document.getElementById("totalFalta");
    const totalalumnoPorciento = document.getElementById("totalalumnoPorciento");
    const totalalumnoPorciento1 = document.getElementById("totalalumnoPorciento1");
    const totalDensidadPorciento = document.getElementById("totalDensidadPorciento");
    const totalDensidadPorciento1 = document.getElementById("totalDensidadPorciento1");
    const totalValorPorcen = document.getElementById("totalValorPorcen");
    const totalMetaPorcen = document.getElementById("totalMetaPorcen");
    const totalCuposPorcen = document.getElementById("totalCuposPorcen");
    const totalFaltaPorciento = document.getElementById("totalFaltaPorciento");

    const thValorPrograma = document.getElementById("thValorPrograma");

    if (thValorPrograma && !thValorPrograma.dataset.base) {
        // toma el primer valor_programa como base
        const primerValor = document.querySelector(".col-valor")?.dataset.valor || 0;
        thValorPrograma.dataset.base = primerValor;
        thValorPrograma.textContent = Number(primerValor).toLocaleString("es-CO");
    }

    if (!thPorcentaje || !thCupo1 || !thCupo2) return;

    // ===== inicializar cupo base =====
    if (!thCupo1.dataset.base) {
        thCupo1.dataset.base = parseFloat(thCupo1.textContent) || 0;
    }

    const obtenerPorcentajeActual = () => {
        return parseFloat(thPorcentaje.textContent) || 100;
    };

    const recalcular = (porcentaje) => {
        const cupoModaBase = parseFloat(thCupo1.dataset.base) || 0;
        const nuevoCupoResumen = Math.round(cupoModaBase * (porcentaje / 100));

        // Header principal
        thCupo2.textContent = nuevoCupoResumen;

        // Columnas cupos INDIVIDUALES
        let totalC = 0;
        document.querySelectorAll(".col-cupos").forEach(td => {
            // Tomamos el cupo original de esa fila y le aplicamos el porcentaje
            const baseIndividual = parseFloat(td.dataset.base) || 0;
            const nuevoCupoIndividual = Math.round(baseIndividual * (porcentaje / 100));

            td.textContent = nuevoCupoIndividual;
            totalC += nuevoCupoIndividual;
        });

        // Columnas metas (80% del cupo individual recién calculado)
        let totalM = 0;
        document.querySelectorAll("#tablaFocoResultado tbody tr").forEach(tr => {
            const tdCupo = tr.querySelector(".col-cupos");
            const tdMeta = tr.querySelector(".col-metas");
            if (tdCupo && tdMeta) {
                const valorCupo = parseFloat(tdCupo.textContent) || 0;
                const nuevaMeta = Math.round(valorCupo * 0.8);
                tdMeta.textContent = nuevaMeta;
                totalM += nuevaMeta;
            }
        });
        let totalCum = 0;
        let totalR = 0;
        let totalLeadsRestante = 0;
        let totalValor = 0;
        let totalmetaIntegro = 0;
        let totalCumReintegro = 0;
        let totalcupoIntegro = 0;
        let totalADN = 0;
        let totalAlumno = 0;
        let totalresultadoDencidad = 0;
        let totalFalta = 0;
        let totalGrupo = 0;
        //  RESULTADO = SI(cupos=0,0,ventas*meta)
        document.querySelectorAll("#tablaFocoResultado tbody tr").forEach(tr => {

            const cupos = Number(tr.querySelector(".col-cupos")?.textContent || 0);
            const meta = Number(tr.querySelector(".col-metas")?.textContent || 0);
            const ventas = Number(tr.querySelector(".col-ventas")?.dataset.ventas || 0);
            const reintegro = Number(tr.querySelector(".col-reintegro")?.dataset.reintegro || 0);
            const restantes = Number(tr.children[7]?.textContent || 0);
            const valores = Number(tr.querySelector(".col-valor")?.dataset.valor || 0);
            const Valor = Number(tr.querySelector("#totalVendi")?.dataset.vendi || 0);
            const ADN = Number(tr.children[11]?.textContent || 0);
            const leads = Number(tr.children[2]?.textContent || 0); // con_horario
            const totalLeads = Number(document.querySelector("#totalLeads")?.textContent || 0);
            /* ================= CUMPLIMIENTO ================= */
            const resultado = calcularResultado(cupos, ventas, meta);
            const tdResultado = tr.querySelector(".col-resultado");
            if (tdResultado) {
                tdResultado.textContent = resultado + "%";
                totalCum += resultado;
            }

            /* ================= RESTANTE ================= */
            const restante = Math.max(meta - ventas, 0);
            const tdRestante = tr.querySelector(".col-restante");
            if (tdRestante) {
                tdRestante.textContent = restante;
                totalR += restante;
            }

            /* ================= LEADS - RESTANTE ================= */
            const leadsRestante = leads / restante;
            const tdLeadsRestante = tr.querySelector(".col-leads-restante");
            if (tdLeadsRestante) {
                tdLeadsRestante.textContent = Math.round(leadsRestante);
                totalLeadsRestante += leadsRestante;
            }

            /* ================= VALOR ================= */
            const valor = restante * valores;
            const tdValor = tr.querySelector(".col-valor");
            if (tdValor) {
                tdValor.textContent = valor.toLocaleString("es-CO", {
                    style: "currency",
                    currency: "COP"
                });
                totalValor += valor;
            }

            /* ================= META REINTEGRO ================= */
            const metaIntegro = cupos - meta;
            const tdmetaIntegro = tr.querySelector(".col-meta");
            if (tdmetaIntegro) {
                tdmetaIntegro.textContent = metaIntegro;
                totalmetaIntegro += metaIntegro;
            }

            /* ================= CUMPLIMIENTO REINTEGRO ================= */
            const resultadoReintegro = calcularResultado(cupos, reintegro, metaIntegro);
            const tdResultadoReintegro = tr.querySelector(".col-resulado-reintegro");
            if (tdResultadoReintegro) {
                tdResultadoReintegro.textContent = resultadoReintegro + "%";
                totalCumReintegro += resultadoReintegro;
            }

            /* ================= Cupos REINTEGRO ================= */
            const cupoIntegro = metaIntegro - reintegro;
            const tdcupoIntegro = tr.querySelector(".col-cupo-reintegro");
            if (tdcupoIntegro) {
                tdcupoIntegro.textContent = cupoIntegro;
                totalcupoIntegro += cupoIntegro;
            }

            /* ================= Cupos REINTEGRO ================= */
            const ADNDeals = ADN - cupoIntegro;
            const tdADN = tr.querySelector(".col-cupo-ADN");
            if (tdADN) {
                tdADN.textContent = ADNDeals;
                totalADN += ADNDeals;
            }

            /* ================= Alumnos ================= */
            const alumno = ventas + reintegro;
            const tdalumno = tr.querySelector(".col-alumno");
            if (tdalumno) {
                tdalumno.textContent = alumno;
                totalAlumno += alumno;
            }

            /* ================= densidad ================= */
            const resultadoDencidad = calcularResultado(cupos, alumno, cupos);
            const tdresultadoDencidad = tr.querySelector(".col-densidad");
            if (tdresultadoDencidad) {
                tdresultadoDencidad.textContent = resultadoDencidad + "%";
                totalresultadoDencidad += resultadoDencidad;
            }

            /* ================= Falta ================= */
            const falta = cupoIntegro - restante;
            const tdFalta = tr.querySelector(".col-falta");
            if (tdFalta) {
                tdFalta.textContent = falta;
                totalFalta += falta;
            }

            /* ================= Total Grupos ================= */
            const grupo = Math.round(totalLeads / thCupo2.textContent);
            const tdGrupo = tr.querySelector("#totalGrupo");
            if (tdGrupo) {
                tdGrupo.textContent = grupo;
                totalGrupo = grupo;
            }

            /* ================= Total VALOR VENDI ================= */
            const valorVendi = Math.round(Valor / totalAlumno);
            const tdvalorVendi = tr.querySelector("#tdvalorVendi");
            if (tdvalorVendi) {
                tdvalorVendi.textContent = valorVendi + "%";
            }

        });

        if (totalCupos) totalCupos.textContent = totalC;
        if (totalMeta) totalMeta.textContent = totalM;
        if (totalCumpl) totalCumpl.textContent = totalCum + "%";
        if (totalRestante) totalRestante.textContent = totalR;
        if (totalLR) totalLR.textContent = totalLeadsRestante;
        if (totalValores) totalValores.textContent = totalValor.toLocaleString("es-CO", {
            style: "currency",
            currency: "COP"
        });
        if (totalMetaIntegro) totalMetaIntegro.textContent = totalmetaIntegro;
        if (totalCumIntegro) totalCumIntegro.textContent = totalCumReintegro + "%";
        if (totalCupoIntegro) totalCupoIntegro.textContent = totalcupoIntegro;
        if (ADNLEADS) ADNLEADS.textContent = totalADN;
        if (totalAlumnos) totalAlumnos.textContent = totalAlumno;
        if (totalAlumnosBajo) totalAlumnosBajo.textContent = totalAlumno;
        if (totalDensidad) totalDensidad.textContent = totalresultadoDencidad + "%";
        if (totalFaltaSuma) totalFaltaSuma.textContent = totalFalta;
        if (totalalumnoPorciento) totalalumnoPorciento.textContent = Math.round(totalAlumno / totalGrupo);
        if (totalalumnoPorciento1) totalalumnoPorciento1.textContent = Math.round(totalAlumno / totalGrupo);
        if (totalDensidadPorciento) totalalumnoPorciento1.textContent = Math.round(totalAlumno / totalC);
        if (totalDensidadPorciento1) totalalumnoPorciento1.textContent = Math.round(totalAlumno / totalC);
        if (totalValorPorcen) totalValorPorcen.textContent = Math.round(totalValor / 4200000);
        if (totalMetaPorcen) totalMetaPorcen.textContent = Math.round(totalmetaIntegro / totalC);
        if (totalFaltaPorciento) totalFaltaPorciento.textContent = totalFalta / totalC;
        /* ================= TABLA RESUMEN INFERIOR ================= */

        if (!leadsData || !leadsData.length) return;

        /* eliminar tabla previa */
        document.getElementById("tablaResumenInferior")?.remove();

        /* fechas */
        const fechaHoy = new Date();

        const fechaHoyStr = fechaHoy.toLocaleDateString("es-CO");
        const horaHoyStr = fechaHoy.toLocaleTimeString("es-CO", {
            hour: "2-digit",
            minute: "2-digit"
        });

        /* fechas DBA (iguales para todos) */
        const fechaInicio = leadsData[0].fecha_inicio;
        const fechaFin = leadsData[0].fecha_fin;

        /* cálculo días */
        const diasEntre = (f1, f2) => {
            const d1 = new Date(f1);
            const d2 = new Date(f2);
            return Math.max(((d2 - d1) / (1000 * 60 * 60 * 24)).toFixed(2), 0);
        };

        const pasado = diasEntre(fechaInicio, fechaHoy);
        const futuro = diasEntre(fechaHoy, fechaFin);

        /* totales */
        const totalVentas = totalAlumno;
        const totalFaltaFinal = totalFalta;

        /* ratios */
        const ventasPasado = pasado > 0 ? (totalVentas / pasado).toFixed(2) : 0;
        const faltaFuturo = futuro > 0 ? (totalFaltaFinal / futuro).toFixed(2) : 0;

        /* tabla */
        const tablaResumen = `
        <table id="tablaResumenInferior" class="table table-bordered text-center mt-4">
            <thead>
                <tr>
                    <th colspan="2">Actualizado</th>
                    <th colspan="2">Inicio</th>
                    <th colspan="2">Ventas</th>
                    <th colspan="2">Reintegros</th>
                </tr>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Pasado</th>
                    <th>Futuro</th>
                    <th>Total</th>
                    <th>Ratio</th>
                    <th>Total</th>
                    <th>Ratio</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>${fechaHoyStr}</td>
                    <td>${horaHoyStr}</td>
                    <td>${pasado}</td>
                    <td>${futuro}</td>
                    <td>${totalVentas}</td>
                    <td>${ventasPasado}</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td colspan="2">Falta</td>
                    <td colspan="2"></td>
                    <td>${totalFaltaFinal}</td>
                    <td>${faltaFuturo}</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
            </tbody>
        </table>
        `;

        /* insertar */
        document
            .getElementById("tablaFocoResultado")
            .insertAdjacentHTML("afterend", tablaResumen);
    };

    // ===== cálculo inicial =====
    recalcular(obtenerPorcentajeActual());

    /* ================= CLICK EN PORCENTAJE ================= */
    thPorcentaje.addEventListener("click", () => {

        const valorActual = obtenerPorcentajeActual();

        thPorcentaje.innerHTML = `
            <input
                type="number"
                min="0"
                max="300"
                step="1"
                value="${valorActual}"
                class="form-control form-control-sm text-center"
                style="width:70px;margin:auto"
            >
        `;

        const input = thPorcentaje.querySelector("input");
        input.focus();

        const aplicar = () => {
            let porcentaje = parseFloat(input.value);
            if (isNaN(porcentaje)) porcentaje = 0;

            thPorcentaje.textContent = porcentaje + "%";
            recalcular(porcentaje);
        };

        input.addEventListener("blur", aplicar);
        input.addEventListener("keydown", e => {
            if (e.key === "Enter") aplicar();
        });
    });

    /* ================= CLICK EN CUPO BASE ================= */
    thCupo1.addEventListener("click", () => {

        const valorActual = parseFloat(thCupo1.dataset.base);

        thCupo1.innerHTML = `
            <input
                type="number"
                min="0"
                step="1"
                value="${valorActual}"
                class="form-control form-control-sm text-center"
                style="width:70px;margin:auto"
            >
        `;

        const input = thCupo1.querySelector("input");
        input.focus();

        const aplicar = () => {
            let nuevoBase = parseFloat(input.value);
            if (isNaN(nuevoBase)) nuevoBase = 0;

            // actualizar base
            thCupo1.dataset.base = nuevoBase;
            thCupo1.textContent = nuevoBase;

            // recalcular con el porcentaje actual
            recalcular(obtenerPorcentajeActual());
        };

        input.addEventListener("blur", aplicar);
        input.addEventListener("keydown", e => {
            if (e.key === "Enter") aplicar();
        });
    });

    /* ================= CLICK EN $ (VALOR PROGRAMA) ================= */
    thValorPrograma?.addEventListener("click", () => {

        const valorActual = parseFloat(thValorPrograma.dataset.base) || 0;

        thValorPrograma.innerHTML = `
        <input
            type="number"
            min="0"
            step="1000"
            value="${valorActual}"
            class="form-control form-control-sm text-center"
            style="width:110px;margin:auto"
        >
    `;

        const input = thValorPrograma.querySelector("input");
        input.focus();

        const aplicar = () => {
            let nuevoValor = parseFloat(input.value);
            if (isNaN(nuevoValor)) nuevoValor = 0;

            // guardar valor base
            thValorPrograma.dataset.base = nuevoValor;
            thValorPrograma.textContent = nuevoValor.toLocaleString("es-CO");

            // actualizar TODAS las filas
            document.querySelectorAll(".col-valor").forEach(td => {
                td.dataset.valor = nuevoValor;
            });

            // recalcular con porcentaje actual
            recalcular(obtenerPorcentajeActual());
        };

        input.addEventListener("blur", aplicar);
        input.addEventListener("keydown", e => {
            if (e.key === "Enter") aplicar();
        });
    });
}

function fechaActual() {
    const f = new Date();
    return f.toLocaleDateString("es-CO");
}

function horaActual() {
    const f = new Date();
    return f.toLocaleTimeString("es-CO", { hour: "2-digit", minute: "2-digit" });
}

function diasEntre(fecha1, fecha2) {
    const f1 = new Date(fecha1);
    const f2 = new Date(fecha2);
    const diff = (f2 - f1) / (1000 * 60 * 60 * 24);
    return diff > 0 ? diff.toFixed(2) : 0;
}


function calcularResultado(cupos, ventas, meta) {
    cupos = Number(cupos) || 0;
    ventas = Number(ventas) || 0;
    meta = Number(meta) || 0;

    return cupos === 0 ? 0 : Math.round((ventas / meta) * 100);
}

/* ===================== EDICIÓN INLINE ===================== */

document.addEventListener("click", function (e) {

    const td = e.target;

    if (!td.classList.contains("editable")) return;
    if (td.dataset.campo === "cupos") return;
    if (td.querySelector("input")) return;

    const valorOriginal = td.innerText.trim();

    const input = document.createElement("input");
    input.type = "number";
    input.min = 0;
    input.value = valorOriginal;
    input.className = "form-control form-control-sm";
    input.style.width = "70px";

    td.innerHTML = "";
    td.appendChild(input);
    input.focus();

    input.addEventListener("blur", () => guardarEdicion(td, input.value, valorOriginal));

    input.addEventListener("keydown", e => {
        if (e.key === "Enter") guardarEdicion(td, input.value, valorOriginal);
        if (e.key === "Escape") td.innerText = valorOriginal;
    });
});

async function guardarEdicion(td, nuevoValor, valorOriginal) {

    nuevoValor = parseInt(nuevoValor) || 0;

    const jornada = td.dataset.jornada;
    const programa = td.dataset.programa;
    const campoEditado = td.dataset.campo;

    const fila = td.closest("tr");
    const celdas = fila.querySelectorAll("td");

    let ventas = 0;
    let reintegros = 0;
    let cupos = 0;

    //  Leer valores actuales aunque NO se editen
    celdas.forEach(celda => {
        if (celda.dataset?.programa === programa) {
            if (celda.dataset.campo === "ventas") {
                ventas = parseInt(celda.innerText) || 0;
            }
            if (celda.dataset.campo === "reintegros") {
                reintegros = parseInt(celda.innerText) || 0;
            }
        }
    });

    //  Reemplazar solo el campo editado
    if (campoEditado === "ventas") ventas = nuevoValor;
    if (campoEditado === "reintegros") reintegros = nuevoValor;

    //  TU REGLA DE NEGOCIO
    cupos = ventas + reintegros;

    const datos = new FormData();
    datos.append("accion", "editar_foco_detalle");
    datos.append("jornada", jornada);
    datos.append("programa", programa);
    datos.append("ventas", ventas);
    datos.append("reintegros", reintegros);
    datos.append("cupos", cupos);

    const res = await fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    });

    const resp = await res.json();

    if (resp.status === "success") {
        cargarTablaFoco();
    } else {
        td.innerText = valorOriginal;
        Swal.fire("Error", resp.message, "error");
    }
}

/* ===================== ELIMINAR ===================== */

document.addEventListener("click", async function (e) {

    if (!e.target.classList.contains("btnEliminar")) return;

    const jornada = e.target.dataset.jornada;
    const programa = e.target.dataset.programa;

    const confirm = await Swal.fire({
        title: "¿Eliminar programa?",
        text: `Eliminar ${programa} de ${jornada}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar"
    });

    if (!confirm.isConfirmed) return;

    const datos = new FormData();
    datos.append("accion", "eliminar_foco_programa");
    datos.append("jornada", jornada);
    datos.append("programa", programa);

    const res = await fetch("ajax/ajax.php", {
        method: "POST",
        body: datos
    });

    const data = await res.json();

    if (data.status === "success") {
        Swal.fire("Eliminado", "Programa eliminado correctamente", "success");
        cargarTablaFoco();
    } else {
        Swal.fire("Error", data.message, "error");
    }
});
/* ===================== INIT ===================== */
if (obtenerPaginaActual() === 'venta.php') {
    document.addEventListener("DOMContentLoaded", cargarTablaFoco);
}
if (obtenerPaginaActual() === 'index.php') {
    document.addEventListener("DOMContentLoaded", cargarTablaFocoReporte);
}
if (obtenerPaginaActual() === 'resultado_foco.php') {
    document.addEventListener("DOMContentLoaded", cargarTablaFocoResultado);
    document.addEventListener("change", function (e) {
        if (e.target.classList.contains("filtro") || e.target.classList.contains("select-all-filter")) {
            cargarTablaFocoResultado();
        }
    });
}

function calcularTotalCupos() {
    const ventas = parseInt(document.getElementById('cupoVentaFoco').value) || 0;
    const reintegros = parseInt(document.getElementById('cupoReintegroFoco').value) || 0;

    document.getElementById('totalCupoFoco').value = ventas + reintegros;
}

document.getElementById('cupoVentaFoco').addEventListener('input', calcularTotalCupos);
document.getElementById('cupoReintegroFoco').addEventListener('input', calcularTotalCupos);

if (document.getElementById("formFoco")) {
    document.getElementById("btnCrearFoco").addEventListener("click", async function () {

        const form = document.getElementById("formFoco");
        let totalCupoFoco = document.getElementById('totalCupoFoco').value;
        let datos = new FormData(form);
        datos.append("accion", "registrar_foco");
        datos.append("totalCupoFoco", totalCupoFoco);
        const res = await fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        });

        const data = await res.json();

        if (data.status === "success") {

            focoCreado = true;
            focoId = data.foco_id; // 🔥 ID DEL FOCO
            cargarTablaFoco();
            // 👉 Mostrar nombre del foco
            document.getElementById("nombreFocoActivo").innerText =
                document.getElementById("nombreFoco").value;

            Swal.fire("Éxito", "Foco creado, ahora puedes agregar detalles", "success");

            // 🔒 Bloquear campos del FOCO
            //bloquearCamposFoco();

            // 🧹 Limpiar SOLO detalle
            limpiarCamposDetalle();

        } else {
            Swal.fire("Error", data.message, "error");
        }
    });

    document.getElementById("btnGuardarDetalle").addEventListener("click", async function () {

        if (!focoCreado) {
            Swal.fire("Error", "Primero debes crear el foco", "error");
            return;
        }

        const form = document.getElementById("formFoco");
        let totalCupoFoco = document.getElementById('totalCupoFoco').value;
        let datos = new FormData(form);
        datos.append("totalCupoFoco", totalCupoFoco);
        datos.append("accion", "registrar_foco_detalle");
        datos.append("foco_id", focoId);

        const res = await fetch("ajax/ajax.php", {
            method: "POST",
            body: datos
        });

        const data = await res.json();

        if (data.status === "success") {
            Swal.fire("Éxito", "Detalle guardado", "success");
            cargarTablaFoco();
            // 🧹 LIMPIA TODO
            form.reset();
            focoCreado = false;
            focoId = null;
            document.getElementById("nombreFocoActivo").innerText = "";

            desbloquearCamposFoco();

        } else {
            Swal.fire("Error", data.message, "error");
        }
    });

}

function bloquearCamposFoco() {
    [
        "codigoFoco",
        "nombreFoco",
        "fechaInicioFoco",
        "fechaFinFoco",
        "carrera"
    ].forEach(id => {
        document.getElementById(id).setAttribute("disabled", true);
    });
}

function desbloquearCamposFoco() {
    [
        "codigoFoco",
        "nombreFoco",
        "fechaInicioFoco",
        "fechaFinFoco"
    ].forEach(id => {
        document.getElementById(id).removeAttribute("disabled");
    });
}

function limpiarCamposDetalle() {
    [
        "horario",
        "cupoVentaFoco",
        "cupoReintegroFoco",
        "totalCupoFoco"
    ].forEach(id => {
        document.getElementById(id).value = "";
    });
}

async function validarBotonCierreFoco() {

    const res = await fetch("ajax/ajax.php?accion=consultarFocoFecha");
    const data = await res.json();

    const fechaFin = new Date(data.ffin_foc + "T23:59:59");
    const hoy = new Date();

    const diffTime = fechaFin - hoy;
    const diffDias = diffTime / (1000 * 60 * 60 * 24);

    const btn = document.getElementById("btnCierreFoco");

    if (diffDias <= 5 || diffDias < 0) {
        btn.style.display = "inline-block";
    } else {
        btn.style.display = "none";
    }
}

validarBotonCierreFoco();

document.getElementById("btnCierreFoco").addEventListener("click", async () => {

    const res = await fetch("ajax/ajax.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "accion=cerrar_foco"
    });

    const data = await res.json();

    if (data.status === "success") {
        Swal.fire("Éxito", "Foco actualizado correctamente", "success")
            .then(() => location.reload());
    } else {
        Swal.fire("Error", data.message, "error");
    }
});
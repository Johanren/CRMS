
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
        let totalC = 0, totalV = 0, totalR = 0;

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

async function cargarTablaFocoReporte() {
    try {

        /* ================= DATOS FOCO ================= */
        const fdForm = new FormData();
        fdForm.append("accion", "tabla_foco");

        const focoRes = await fetch("ajax/ajax.php", {
            method: "POST",
            body: fdForm
        });
        const focoData = await focoRes.json();

        /* ================= DATOS LEADS ================= */
        const leadForm = new FormData();
        leadForm.append("accion", "leads_foco_detalle");

        const leadRes = await fetch("ajax/ajax.php", {
            method: "POST",
            body: leadForm
        });
        const leadsData = await leadRes.json();

        /* ================= JORNADAS (con id_jornada) ================= */
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

        /* ================= ACUMULADORES GENERALES ================= */
        const totalesLeads = {};
        programas.forEach(p => totalesLeads[p] = { con: 0, solo: 0 });

        let totalConHorarioGeneral = 0;
        let totalSoloCarreraGeneral = 0;

        /* ================= BODY ================= */
        jornadas.forEach(j => {

            const { id_jornada, jornada } = j;

            /* ================= FILA 1 → CUPOS ================= */
            let filaCupos = `<tr><td rowspan="3">${jornada}</td>`;
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

            /* ================= FILA 2 → VENTAS / REINTEGROS ================= */
            let filaVR = `<tr>`;
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

            /* ================= FILA 3 → LEADS ================= */
            let filaLeads = `<tr>`;
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

        /* ================= FILA FINAL → TOTALES GENERALES ================= */
        let filaTot = `<tr class="table-secondary fw-bold"><td>Totales</td>`;

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

    } catch (e) {
        console.error("Error tabla foco:", e);
    }
}

async function cargarTablaFocoResultado() {

    /* ================= DATOS LEADS ================= */
    const leadForm = new FormData();
    leadForm.append("accion", "leads_foco_resultado");

    const leadRes = await fetch("ajax/ajax.php", {
        method: "POST",
        body: leadForm
    });
    const leadsData = await leadRes.json();

    const thead = document.querySelector("#tablaFocoResultado thead");
    const tbody = document.querySelector("#tablaFocoResultado tbody");
    let cupos = [...new Set(leadsData.map(d => d.ventas))];
    const totalLeads = leadsData.reduce((total, d) => {
        return total + Number(d.con_horario || 0);
    }, 0);

    const totalVendi = leadsData.reduce((total, d) => {
        return total + Number(d.ventas_estado_6 || 0);
    }, 0);

    thead.innerHTML = `

            <!-- ================= FILA SUPERIOR RESUMEN ================= -->
            <tr class="fw-bold text-center">
                <th id="resumenCupo1" style="cursor:pointer" colspan="1">${cupos[0]}</th>
                <th id="resumenPorcentaje" style="cursor:pointer" colspan="2">100%</th>
                <th class="resumenCupo2" colspan="1">${cupos[0]}</th>

                <th colspan="6" class="bg-warning text-center">VENTAS</th>
                <th colspan="6" class="bg-primary text-white text-center">REINTEGROS</th>
                <th colspan="3" class="bg-info text-center">RESULTADOS</th>
            </tr>

            <!-- ================= HEADER NIVEL 1 ================= -->
            <tr>
                <th rowspan="2">Técnica</th>
                <th rowspan="2">J</th>
                <th rowspan="2">Leads</th>
                <th rowspan="2">Cupos</th>
            </tr>

            <!-- ================= HEADER NIVEL 2 ================= -->
            <tr>
                <!-- VENTAS -->
                <th rowspan="2">Meta</th>
                <th rowspan="2">Vendido</th>
                <th rowspan="2">Cumpl %</th>
                <th rowspan="2">Faltan</th>
                <th rowspan="2">Leads/Faltan</th>
                <th rowspan="2" id="thValorPrograma" style="cursor:pointer">$</th>

                <!-- REINTEGROS -->
                <th rowspan="2">Meta</th>
                <th rowspan="2">ADN</th>
                <th rowspan="2">Reintegros</th>
                <th rowspan="2">Cumpl %</th>
                <th rowspan="2">Cupos</th>
                <th rowspan="2"></th>

                <!-- RESULTADOS -->
                <th rowspan="2">Alumnos</th>
                <th rowspan="2">Densidad</th>
                <th rowspan="2">Faltan</th>
            </tr>
        `;


    tbody.innerHTML = "";

    /* ================= FILAS DE EJEMPLO ================= */

    leadsData.forEach(row => {
        tbody.innerHTML += `
            <tr>
                <td>${row.programa}</td>
                <td>${row.jornada}</td>
                <td>${row.con_horario}</td>
                <td class="col-cupos">0</td>

                <!-- VENTAS -->
                <td class="col-metas">0</td>
                <td class="col-ventas" data-ventas="${row.ventas_estado_6}">${row.ventas_estado_6}</td>
                <td class="col-resultado">0%</td>
                <td class="col-restante">0</td>
                <td class="col-leads-restante">0</td>
                <td class="col-valor" data-valor="${row.valor_programa}"></td>

                <!-- REINTEGROS -->
                <td class="col-meta">0</td>
                <td class="col-ADN" data-ADN="0">0</td>
                <td class="col-reintegro" data-reintegro="0">0</td>
                <td class="col-resulado-reintegro">0%</td>
                <td class="col-cupo-reintegro">0</td>
                <td class="col-cupo-ADN">0</td>

                <!-- RESULTADOS -->
                <td class="col-alumno">0</td>
                <td class="col-densidad">0%</td>
                <td class="col-falta">0</td>
            </tr>
        `;
    });

    /* ================= FILA TOTALES ================= */
    tbody.innerHTML += `
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
    `;

    /* ================= FILA ABAJO 1 ================= */
    tbody.innerHTML += `
        <tr class="fw-bold table-secondary">
            <td>Alumnos x Grupo</td>
            <td id="totalalumnoPorciento"></td>
            <td id="totalAlumnosBajo"></td>
            <td id="totalDensidadPorciento1">0</td>

            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
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
    `;

    /* ================= FILA CUPOS ================= */
    tbody.innerHTML += `
        <tr class="fw-bold table-secondary">
            <td></td>
            <td id="">Ventas</td>
            <td id="totalVendi" data-vendi="${totalVendi}">${totalVendi}</td>
            <td id="tdvalorVendi">0%</td>
        </tr>
        <tr class="fw-bold table-secondary">
            <td></td>
            <td id="">Reintegros</td>
            <td id="">0</td>
            <td id="">0%</td>
        </tr>
    `;
    activarPorcentajeResumen(leadsData);
}

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
        const cupoBase = parseFloat(thCupo1.dataset.base) || 0;
        const nuevoCupo = Math.round(cupoBase * (porcentaje / 100));

        // header
        thCupo2.textContent = nuevoCupo;

        // columnas cupos
        let totalC = 0;
        document.querySelectorAll(".col-cupos").forEach(td => {
            td.textContent = nuevoCupo;
            totalC += nuevoCupo;
        });

        // columnas metas (80%)
        let totalM = 0;
        document.querySelectorAll(".col-metas").forEach(td => {
            const nuevaMeta = Math.round(nuevoCupo * 0.8);
            td.textContent = nuevaMeta;
            totalM += nuevaMeta;
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
        // 🔹 RESULTADO = SI(cupos=0,0,ventas*meta)
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
            const leadsRestante = leads - restante;
            const tdLeadsRestante = tr.querySelector(".col-leads-restante");
            if (tdLeadsRestante) {
                tdLeadsRestante.textContent = leadsRestante;
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

    // 🔹 Leer valores actuales aunque NO se editen
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

    // 🔹 Reemplazar solo el campo editado
    if (campoEditado === "ventas") ventas = nuevoValor;
    if (campoEditado === "reintegros") reintegros = nuevoValor;

    // 🔹 TU REGLA DE NEGOCIO
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

document.addEventListener("DOMContentLoaded", cargarTablaFoco);
document.addEventListener("DOMContentLoaded", cargarTablaFocoReporte);
document.addEventListener("DOMContentLoaded", cargarTablaFocoResultado);

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
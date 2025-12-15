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

    // ================= HEADER =================
    let h1 = `<tr><th rowspan="2">Jornada</th>`;
    programas.forEach(p => {
        h1 += `<th colspan="3">${p}</th>`;
    });
    h1 += `<th colspan="3">Total</th></tr>`;

    let h2 = `<tr>`;
    programas.forEach(() => {
        h2 += `<th>Cupos</th><th>Venta</th><th>Reintegro</th>`;
    });
    h2 += `<th>Cupos</th><th>Venta</th><th>Reintegros</th></tr>`;

    thead.innerHTML = h1 + h2;

    // ================= BODY =================
    tbody.innerHTML = "";

    // 🔹 acumuladores por programa
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

            // 🔹 acumular por programa
            totalesPrograma[programa].c += c;
            totalesPrograma[programa].v += v;
            totalesPrograma[programa].r += r;

            fila += `<td>${c}</td><td>${v}</td><td>${r}</td>`;
        });

        fila += `<td><b>${totalC}</b></td><td><b>${totalV}</b></td><td><b>${totalR}</b></td></tr>`;

        totalGeneralC += totalC;
        totalGeneralV += totalV;
        totalGeneralR += totalR;

        tbody.innerHTML += fila;
    });

    // ================= FILA FINAL TOTALES =================
    let filaTotales = `<tr class="table-secondary fw-bold"><td>Totales</td>`;

    programas.forEach(p => {
        filaTotales += `
            <td>${totalesPrograma[p].c}</td>
            <td>${totalesPrograma[p].v}</td>
            <td>${totalesPrograma[p].r}</td>
        `;
    });

    filaTotales += `
        <td>${totalGeneralC}</td>
        <td>${totalGeneralV}</td>
        <td>${totalGeneralR}</td>
    </tr>`;

    tbody.innerHTML += filaTotales;
}


cargarTablaFoco();


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
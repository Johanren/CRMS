<?php ob_start(); ?>

<?php

/* =========================
           VARIABLES DINÁMICAS
        ========================= */

$country        = "57";
$message        = "Prueba crm";
$encoding       = "string";
$messageFormat  = 1;

/* Lista de destinatarios */
$addresseeList = [
    [
        "mobile" => '3186447732',
        "correlationLabel" => "Veimar"
    ]
];
//"dateToSend": "string",yyyy-MM-dd HH:mm:ss

$postData = [
    "country"        => $country,
    "message"        => $message,
    "encoding"       => $encoding,
    "messageFormat"  => $messageFormat,
    "addresseeList"  => $addresseeList
];
$jsonData = json_encode($postData, JSON_UNESCAPED_UNICODE);

/*$curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://apitellit.aldeamo.com/SmsiWS/smsSendPost/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $jsonData,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Basic bXVsdGljb21wdXRvOk5Ca2xmZCRzYXM1ZGM='
            ],
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            echo 'Error CURL: ' . curl_error($curl);
        }

        curl_close($curl);

        echo $response."<br>";*/

?>

<!-- ========================
        Start Page Content
    ========================= -->

<div class="page-wrapper">

    <!-- Start Content -->
    <div class="content pb-0">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Envio de mensajes<span class="badge badge-soft-primary ms-2">125</span></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="index.php">Hogar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Envio de mensajes</li>
                    </ol>
                </nav>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                    data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i
                        class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                    data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse"
                    id="collapse-header"><i class="ti ti-transition-top"></i></a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- card start -->
        <div class="card border-0 rounded-0">
            <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                <!--<a href="javascript:void(0);" onclick="exportarExcel('rst_frm')" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#####download_report"><i class="ti ti-file-download me-1"></i>Descargar Reporte</a>-->
            </div>
            <div class="card-body">

                <form id="frm_rst" class="row g-3 mb-4">

                    <div class="col-md-3">
                        <label class="form-label">Carrera</label>
                        <select class="form-select" id="filtro_carrera" multiple>
                            <option value="">Todas</option>
                            <option value="1">Ingeniería</option>
                            <option value="2">Administración</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Horario</label>
                        <select class="form-select" id="filtro_horario" multiple>
                            <option value="">Todos</option>
                            <option value="mañana">Mañana</option>
                            <option value="noche">Noche</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Estado</label>
                        <select class="form-select" id="filtro_estado" multiple>
                            <option value="">Todos</option>
                            <option value="interesado">Interesado</option>
                            <option value="seguimiento">Seguimiento</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Asesor</label>
                        <select class="form-select" id="filtro_asesor" multiple>
                            <option value="">Todos</option>
                            <option value="1">Sandra</option>
                            <option value="2">Yalie</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Numero</label>
                        <input type="text" id="filtro_numero" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Programar envio</label>
                        <input type="datetime-local" id="programar" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">URL</label>
                        <input type="text" id="url" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Tema</label>
                        <select class="form-select" id="tema_mensaje">
                            <option value="">Seleccione</option>
                            <option value="pago">Facilidad Pago</option>
                            <option value="ingreso">Ingreso</option>
                            <option value="empo">Empoderar</option>
                            <option value="bono">Bono</option>
                        </select>
                    </div>
                </form>

                <div class="col-md-2">
                    <button id="btn_guardar_mensajes" class="btn btn-success">
                        Guardar mensajes
                    </button>
                </div>

                <div class="table-responsive mt-5">
                    <table id="tabla_leads" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Asesor</th>
                                <th>Mensaje</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- /Contact List -->

            </div>
        </div>
        <!-- card end -->

    </div>
    <style>
    /* ===== LOADER ===== */
    .loader-overlay {
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.85);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loader-overlay p {
        margin-top: 10px;
        font-weight: bold;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 6px solid #ddd;
        border-top: 6px solid #007bff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* ===== UTIL ===== */
    .d-none {
        display: none;
    }
    </style>
    <div id="loaderFoco" class="loader-overlay d-none">
        <div class="spinner"></div>
        <p id="loaderTexto">Cargando datos...</p>
    </div>

    <!-- End Content -->

    <?php require_once '../partials/footer.php'; ?>

</div>

<script>
let tablaLeads = null;

/* ===========================
   VARIABLES GLOBALES
=========================== */
const foco = <?php session_start(); echo json_encode($_SESSION['foco']); ?>;
let mensajesPorTema = {};

/* ===========================
   CARGAR MENSAJES POR TEMA
=========================== */
function cargarMensajesPorTema() {

    const datos = new FormData();
    datos.append('accion', 'listar_mensajes_parametrizados');

    return fetch('ajax/ajax.php', {
        method: 'POST',
        body: datos
    })
    .then(res => res.json())
    .then(data => {

        mensajesPorTema = {};

        data.forEach(item => {
            mensajesPorTema[item.tipo] = item.mensaje;
        });

        console.log('✔ Mensajes cargados:', mensajesPorTema);
        return true;
    })
    .catch(err => {
        console.error('❌ Error cargando mensajes', err);
        return false;
    });
}

/* ===========================
   DOM READY
=========================== */
document.addEventListener('DOMContentLoaded', async () => {

    await cargarMensajesPorTema(); // 🔥 asegurar carga

    cargarFiltrosRST();

    ['filtro_carrera','filtro_horario','filtro_estado','filtro_asesor']
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', validarYCargarTabla);
        });

    document.getElementById('tema_mensaje')
        .addEventListener('change', generarMensajesPorTema);

    document.getElementById('btn_guardar_mensajes')
        .addEventListener('click', guardarMensajes);
});

/* ===========================
   CARGAR FILTROS
=========================== */
function cargarFiltrosRST() {

    const datos = new FormData();
    datos.append('accion', 'catalogo_filtros_mensaje');

    fetch('ajax/ajax.php', { method:'POST', body:datos })
        .then(res => res.json())
        .then(data => {
            llenarSelect('filtro_carrera', data.carreras, 'id_programa', 'programa');
            llenarSelect('filtro_horario', data.horarios, 'id_jornada', 'jornada');
            llenarSelect('filtro_estado', data.estados, 'id_estado', 'estado');
            llenarSelect('filtro_asesor', data.asesores, 'id_asesor', 'asesor');
        })
        .catch(console.error);
}

function llenarSelect(id, datos, valueKey, textKey) {

    const select = document.getElementById(id);
    if (!select) return;

    select.innerHTML = `<option value="">Seleccione</option>`;

    datos.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d[valueKey];
        opt.textContent = d[textKey];
        select.appendChild(opt);
    });
}

function getValoresSelect(id) {
    const select = document.getElementById(id);
    return select ? Array.from(select.selectedOptions).map(o => o.value) : [];
}

/* ===========================
   VALIDAR FILTROS
=========================== */
function validarYCargarTabla() {

    const filtrosOK = [
        'filtro_carrera',
        'filtro_horario',
        'filtro_estado',
        'filtro_asesor'
    ].every(id => getValoresSelect(id).length);

    filtrosOK ? cargarTablaLeads() : limpiarTabla('Seleccione todos los filtros');
}

/* ===========================
   CARGAR TABLA
=========================== */
function cargarTablaLeads() {

    const datos = new FormData();
    datos.append('accion', 'listar_leads_filtrados');

    ['filtro_carrera','filtro_horario','filtro_estado','filtro_asesor']
        .forEach(id => getValoresSelect(id).forEach(v => datos.append(id+'[]', v)));

    fetch('ajax/ajax.php', { method:'POST', body:datos })
        .then(res => res.json())
        .then(pintarTabla)
        .catch(() => limpiarTabla('Error al cargar datos'));
}

/* ===========================
   PINTAR TABLA
=========================== */
function pintarTabla(leads) {

    const tbody = document.querySelector('#tabla_leads tbody');

    if (tablaLeads) {
        tablaLeads.destroy();
        tablaLeads = null;
    }

    tbody.innerHTML = '';

    if (!leads.length) {
        limpiarTabla('No hay resultados');
        return;
    }

    leads.forEach(l => {

        const tr = document.createElement('tr');
        tr.dataset.cliente = l.cliente;
        tr.dataset.asesor = l.asesor;
        tr.dataset.carrera = l.carrera;
        tr.dataset.jornada = l.jornada;
        tr.dataset.mensaje = '';

        tr.innerHTML = `
            <td>${l.id_lead}</td>
            <td>${l.cliente.split(' ')[0]}</td>
            <td>${l.numero}</td>
            <td>${l.asesor}</td>
            <td class="mensaje-col text-muted">Seleccione un tema</td>
        `;

        tbody.appendChild(tr);
    });

    iniciarDataTable();
}

/* ===========================
   DATATABLE
=========================== */
function iniciarDataTable() {

    tablaLeads = $('#tabla_leads').DataTable({
        responsive:true,
        pageLength:10,
        lengthChange:false,
        language:{
            search:"Buscar:",
            zeroRecords:"No hay resultados",
            info:"Mostrando _START_ a _END_ de _TOTAL_",
            paginate:{ next:"Siguiente", previous:"Anterior" }
        }
    });
}

/* ===========================
   LIMPIAR TABLA
=========================== */
function limpiarTabla(msg) {

    if (tablaLeads) {
        tablaLeads.destroy();
        tablaLeads = null;
    }

    document.querySelector('#tabla_leads tbody').innerHTML = `
        <tr><td colspan="5" class="text-center text-muted">${msg}</td></tr>`;
}

/* ===========================
   GENERAR MENSAJES
=========================== */
function generarMensajesPorTema() {

    const tema = this.value;
    const url = document.getElementById('url')?.value || '';

    if (!mensajesPorTema[tema] || !tablaLeads) return;

    tablaLeads.rows().every(function () {

        const tr = this.node();
        const cliente = tr.dataset.cliente.split(' ')[0];

        const mensaje = mensajesPorTema[tema]
            .replace('{{cliente}}', cliente)
            .replace('{{asesor}}', tr.dataset.asesor)
            .replace('{{carrera}}', tr.dataset.carrera)
            .replace('{{jornada}}', tr.dataset.jornada)
            .replace('{{url}}', url)
            .replace('{{foco}}', foco);

        tr.dataset.mensaje = mensaje;
        tr.querySelector('.mensaje-col').textContent = mensaje;
        tr.querySelector('.mensaje-col').classList.remove('text-muted');
    });
}

/* ===========================
   GUARDAR MENSAJES
=========================== */
function guardarMensajes() {

    if (!tablaLeads) return alert('No hay mensajes');

    const mensajes = [];

    tablaLeads.rows().every(function () {
        const tr = this.node();
        if (tr.dataset.mensaje) {
            mensajes.push({
                id_lead: tr.children[0].textContent,
                numero: tr.children[2].textContent,
                mensaje: tr.dataset.mensaje
            });
        }
    });

    if (!mensajes.length) return alert('No hay mensajes generados');

    const datos = new FormData();
    datos.append('accion', 'guardar_mensajes_rst');
    datos.append('mensajes', JSON.stringify(mensajes));

    fetch('ajax/ajax.php', { method:'POST', body:datos })
        .then(res => res.json())
        .then(r => alert(r.ok ? '✔ Mensajes enviados' : '❌ Error'))
        .catch(() => alert('❌ Error servidor'));
}
</script>



<!-- ========================
        End Page Content
    ========================= -->

<?php
$content = ob_get_clean();

require_once '../partials/main.php'; ?>
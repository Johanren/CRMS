<?php ob_start(); ?>

<?php

class ListMensajeModels
{

    public static function enviarSMS(
        $numero,
        $mensaje,
        $cliente = '',
        $dateToSend = null
    ) {

        try {

            /*
            ==========================================
            LIMPIAR NÚMERO
            ==========================================
            */
            $numero = preg_replace(
                '/[^0-9]/',
                '',
                $numero
            );

            /*
            ==========================================
            AGREGAR INDICATIVO 57
            ==========================================
            */
            if (strlen($numero) == 10) {

                $numero = '57' . $numero;
            }

            /*
            ==========================================
            VALIDAR NÚMERO
            ==========================================
            */
            if (!preg_match('/^57[0-9]{10}$/', $numero)) {

                return [
                    'ok'    => false,
                    'error' => 'Número inválido'
                ];
            }

            /*
            ==========================================
            PAYLOAD CRWAVE
            ==========================================
            */
            $payload = [

                "messages" => [
                    [
                        "phone_number"   => $numero,
                        "message" => $mensaje
                    ]
                ]

            ];

            /*
            ==========================================
            FECHA PROGRAMADA
            ==========================================
            */
            if (!empty($dateToSend)) {

                $payload['date_to_send'] = date(
                    'Y-m-d H:i:s',
                    strtotime($dateToSend)
                );
            }

            /*
            ==========================================
            JSON
            ==========================================
            */
            $jsonData = json_encode(
                $payload,
                JSON_UNESCAPED_UNICODE
            );

            /*
            ==========================================
            CURL
            ==========================================
            */
            $curl = curl_init();

            curl_setopt_array($curl, [

                CURLOPT_URL => 'https://crwave.com.co/client/api/v1/sms/batch/',

                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 60,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,

                CURLOPT_CUSTOMREQUEST  => 'POST',

                CURLOPT_POSTFIELDS     => $jsonData,

                CURLOPT_HTTPHEADER => [

                    'Content-Type: application/json',

                    /*
                    ==========================================
                    REEMPLAZA TU TOKEN
                    ==========================================
                    */
                    'Authorization: Token e8248e3af9b51010422e09c55fe7ff517eb12f4fe395d236020652d806639354'

                ],

            ]);

            /*
            ==========================================
            RESPUESTA
            ==========================================
            */
            $response  = curl_exec($curl);
            $error     = curl_error($curl);
            $httpCode  = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);

            /*
            ==========================================
            ERROR CURL
            ==========================================
            */
            if ($error) {

                return [
                    'ok'    => false,
                    'error' => $error
                ];
            }

            /*
            ==========================================
            DECODIFICAR JSON
            ==========================================
            */
            $data = json_decode($response, true);

            return [

                'ok'        => $httpCode == 202,
                'http_code' => $httpCode,
                'cliente'   => $cliente,
                'numero'    => $numero,
                'response'  => $data,
                'raw'       => $response

            ];
        } catch (Exception $e) {

            return [
                'ok'    => false,
                'error' => $e->getMessage()
            ];
        }
    }
}

/*
==========================================
PRUEBA
==========================================
*/

/*$envio = ListMensajeModels::enviarSMS('3142905475','Mensaje de prueba desde CRWave','yo',null);

echo "<pre>";
print_r($envio);
echo "</pre>";*/
?>

<!-- ========================
        Start Page Content
    ========================= -->
<?php
$esModal = isset($_GET['modal']) && $_GET['modal'] == 1;
?>
<?php if (!$esModal): ?>
    <div class="page-wrapper">
    <?php endif; ?>

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
                        <label class="form-label fw-bold">Carrera</label>
                        <div id="filtro_carrera" class="border rounded p-2 bg-white"
                            style="max-height: 150px; overflow-y: auto;">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Jornada</label>
                        <div id="filtro_horario" class="border rounded p-2 bg-white"
                            style="max-height: 150px; overflow-y: auto;">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Estado</label>
                        <div id="filtro_estado" class="border rounded p-2 bg-white"
                            style="max-height: 150px; overflow-y: auto;">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Asesor</label>
                        <div id="filtro_asesor" class="border rounded p-2 bg-white"
                            style="max-height: 150px; overflow-y: auto;">
                        </div>
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
                            <option value="incentivo">Incentivo</option>
                        </select>
                    </div>


                    <div class="col-md-12 mt-3 d-none" id="wrapper-opciones">
                        <div class="card shadow-sm border-info">
                            <div
                                class="card-header bg-info text-white py-2 d-flex justify-content-between align-items-center">
                                <small class="text-uppercase fw-bold">Varias variantes detectadas: Seleccione
                                    una</small>
                                <span class="badge bg-white text-info" id="contador-variantes">0</span>
                            </div>
                            <div class="card-body p-0">
                                <div id="contenedor-opciones-mensaje" class="list-group list-group-flush custom-scroll"
                                    style="max-height: 250px; overflow-y: auto;">
                                </div>
                            </div>
                        </div>
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

        #filtro_horario,
        #filtro_estado {
            text-transform: uppercase;
        }
    </style>
    <div id="loaderFoco" class="loader-overlay d-none">
        <div class="spinner"></div>
        <p id="loaderTexto">Cargando datos...</p>
    </div>

    <!-- End Content -->

    <?php require_once '../partials/footer.php'; ?>

    <?php if (!$esModal): ?>
    </div>
<?php endif; ?>

<script>
    let tablaLeads = null;

    /* ===========================
       VARIABLES GLOBALES
    =========================== */
    const foco = <?php session_start();
                    echo json_encode($_SESSION['foco'] ?? '55'); ?>;
    let mensajesPorTema = {};
    let urlsAsesores = {}; // Objeto para mapear ID -> {url, nombre}

    /* ===========================
       DOM READY
    =========================== */
    document.addEventListener('DOMContentLoaded', async () => {
        await cargarMensajesPorTema();
        cargarFiltrosRST();
        cargarUrls();

        // Eventos para filtros
        ['filtro_carrera', 'filtro_horario', 'filtro_estado', 'filtro_asesor']
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', validarYCargarTabla);
        });

        document.getElementById('filtro_numero')?.addEventListener('input', validarYCargarTabla);
        document.getElementById('tema_mensaje')?.addEventListener('change', generarMensajesPorTema);
        document.getElementById('btn_guardar_mensajes')?.addEventListener('click', guardarMensajes);

        // Evento para actualizar mensajes si el usuario cambia la URL manualmente
        document.getElementById('url')?.addEventListener('input', () => {
            const btnActivo = document.querySelector('.opcion-mensaje.active-selection p');
            if (btnActivo) aplicarMensajeALaTabla(btnActivo.textContent);
        });
    });

    /* ===========================
       CARGA DE DATOS (AJAX)
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
                    if (!mensajesPorTema[item.tipo]) mensajesPorTema[item.tipo] = [];
                    mensajesPorTema[item.tipo].push(item.mensaje);
                });
                return true;
            })
            .catch(() => false);
    }

    function cargarFiltrosRST() {
        const datos = new FormData();
        datos.append('accion', 'catalogo_filtros_mensaje');

        fetch('ajax/ajax.php', {
                method: 'POST',
                body: datos
            })
            .then(res => res.json())
            .then(data => {
                llenarSelect('filtro_carrera', data.carreras, 'id_programa', 'programa');
                llenarSelect('filtro_horario', data.horarios, 'id_jornada', 'jornada');
                llenarSelect('filtro_estado', data.estados, 'id_estado', 'estado');
                llenarSelect('filtro_asesor', data.asesores, 'id_asesor', 'asesor');
            });
    }

    function cargarUrls() {
        fetch('ajax/ajax.php?accion=cargarUrls')
            .then(res => res.json())
            .then(data => {
                if (data && data.short_url) {
                    document.getElementById('url').value = data.short_url;
                }
            })
            .catch(err => {
                console.error("Error cargando URL:", err);
            });
    }

    function llenarSelect(id, datos, valueKey, textKey) {
        const contenedor = document.getElementById(id);
        if (!contenedor) return;
        contenedor.innerHTML = '';

        datos.forEach(d => {
            // Guardar datos de asesores en el diccionario global
            if (id === 'filtro_asesor') {
                urlsAsesores[d.id_asesor] = {
                    url: d.url || '',
                    nombre: (d.asesor || '').toUpperCase()
                };
            }

            const div = document.createElement('div');
            div.className = 'form-check small';
            const input = document.createElement('input');
            input.type = 'checkbox';
            input.className = 'form-check-input filtro-check';
            input.value = d[valueKey];
            input.id = `chk_${id}_${d[valueKey]}`;

            input.addEventListener('change', () => {
                if (id === 'filtro_asesor') actualizarUrlPorAsesor();
                validarYCargarTabla();
            });

            const label = document.createElement('label');
            label.className = 'form-check-label';
            label.htmlFor = input.id;
            label.textContent = d[textKey] ? d[textKey].toUpperCase() : '';

            div.appendChild(input);
            div.appendChild(label);
            contenedor.appendChild(div);
        });
    }

    /* ===========================
       LÓGICA DE NEGOCIO (URLS Y TABLA)
    =========================== */
    function actualizarUrlPorAsesor() {
        const seleccionados = getValoresSelect('filtro_asesor');
        const inputUrl = document.getElementById('url');

        if (inputUrl.value.trim() !== '') {
            return;
        }

        // Si solo hay un asesor, ponemos su URL en el input para edición
        if (seleccionados.length === 1) {
            const id = seleccionados[0];
            inputUrl.value = urlsAsesores[id] ? urlsAsesores[id].url : '';
        }
    }

    function getValoresSelect(id) {
        const contenedor = document.getElementById(id);
        if (!contenedor) return [];
        const seleccionados = contenedor.querySelectorAll('input[type="checkbox"]:checked');
        return Array.from(seleccionados).map(cb => cb.value);
    }

    function validarYCargarTabla() {
        const numero = document.getElementById('filtro_numero')?.value.trim();
        const filtrosIds = ['filtro_carrera', 'filtro_horario', 'filtro_estado', 'filtro_asesor'];

        if (numero) {
            filtrosIds.forEach(id => {
                document.querySelectorAll(`#${id} input`).forEach(cb => {
                    cb.disabled = true;
                    cb.checked = false;
                });
            });
            cargarTablaLeads();
            return;
        }

        filtrosIds.forEach(id => {
            document.querySelectorAll(`#${id} input`).forEach(cb => cb.disabled = false);
        });

        const algunFiltro = filtrosIds.some(id => getValoresSelect(id).length > 0);
        if (algunFiltro) cargarTablaLeads();
        else limpiarTabla('Seleccione filtros para cargar la lista');
    }

    function cargarTablaLeads() {
        const datos = new FormData();
        datos.append('accion', 'listar_leads_filtrados');
        const numero = document.getElementById('filtro_numero')?.value.trim();

        if (numero) {
            datos.append('numero', numero);
        } else {
            ['filtro_carrera', 'filtro_horario', 'filtro_estado', 'filtro_asesor']
            .forEach(id => getValoresSelect(id).forEach(v => datos.append(id + '[]', v)));
        }

        fetch('ajax/ajax.php', {
                method: 'POST',
                body: datos
            })
            .then(res => res.json())
            .then(pintarTabla)
            .catch(() => limpiarTabla('Error al cargar datos'));
    }

    function pintarTabla(leads) {
        const tbody = document.querySelector('#tabla_leads tbody');
        if (tablaLeads) {
            tablaLeads.destroy();
            tablaLeads = null;
        }
        tbody.innerHTML = '';

        if (!leads || !leads.length) {
            limpiarTabla('No hay resultados');
            return;
        }

        leads.forEach(l => {
            const tr = document.createElement('tr');

            // BUSCAR ID DEL ASESOR para vincular la URL correcta
            const idAsesor = Object.keys(urlsAsesores).find(key =>
                urlsAsesores[key].nombre === (l.asesor || '').toUpperCase()
            );

            tr.dataset.cliente = l.cliente;
            tr.dataset.asesor = l.asesor;
            tr.dataset.id_asesor = idAsesor || '';
            tr.dataset.carrera = (l.carrera || '').toUpperCase();
            tr.dataset.jornada = (l.jornada || '').toUpperCase();
            tr.dataset.mensaje = '';

            tr.innerHTML = `
                <td>${l.id_lead}</td>
                <td>${l.cliente.split(' ')[0]}</td>
                <td>${l.numero}</td>
                <td>${l.asesor}</td>
                <td class="mensaje-col text-muted italic">Seleccione un tema</td>
            `;
            tbody.appendChild(tr);
        });
        iniciarDataTable();
    }

    function iniciarDataTable() {
        tablaLeads = $('#tabla_leads').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: "Buscar:",
                zeroRecords: "No hay resultados"
            }
        });
    }

    function limpiarTabla(msg) {
        if (tablaLeads) {
            tablaLeads.destroy();
            tablaLeads = null;
        }
        document.querySelector('#tabla_leads tbody').innerHTML =
            `<tr><td colspan="5" class="text-center text-muted">${msg}</td></tr>`;
    }

    /* ===========================
       GESTIÓN DE MENSAJES
    =========================== */
    function generarMensajesPorTema() {
        const tema = document.getElementById('tema_mensaje')?.value;
        const contenedor = document.getElementById('contenedor-opciones-mensaje');
        const wrapper = document.getElementById('wrapper-opciones');
        const contador = document.getElementById('contador-variantes');

        if (!tema || !mensajesPorTema[tema]) {
            wrapper.classList.add('d-none');
            contenedor.innerHTML = '';
            return;
        }

        const opciones = mensajesPorTema[tema];
        contenedor.innerHTML = '';

        if (opciones.length > 1) {
            wrapper.classList.remove('d-none');
            contador.textContent = `${opciones.length} variantes`;

            opciones.forEach((msg, index) => {
                const btn = document.createElement('button');
                btn.type = "button";
                btn.className = "list-group-item list-group-item-action opcion-mensaje d-flex align-items-start gap-3 py-3";
                btn.innerHTML = `
                    <div class="badge rounded-pill bg-info mt-1">${index + 1}</div>
                    <div class="flex-grow-1">
                        <p class="mb-0 text-dark" style="font-size: 0.88rem;">${msg}</p>
                    </div>`;
                btn.onclick = function() {
                    document.querySelectorAll('.opcion-mensaje').forEach(el => el.classList.remove('active-selection', 'bg-light'));
                    btn.classList.add('active-selection', 'bg-light');
                    aplicarMensajeALaTabla(msg);
                };
                contenedor.appendChild(btn);
            });
        } else if (opciones.length === 1) {
            wrapper.classList.add('d-none');
            aplicarMensajeALaTabla(opciones[0]);
        }
    }

    function aplicarMensajeALaTabla(plantilla) {
        const asesoresSeleccionados = getValoresSelect('filtro_asesor');
        const cantidadAsesores = asesoresSeleccionados.length;
        const urlManual = document.getElementById('url')?.value.trim();

        tablaLeads.rows().every(function() {
            const tr = this.node();
            const idAsesorFila = tr.dataset.id_asesor;
            const nombreCliente = tr.dataset.cliente.split(' ')[0];

            // LOGICA REQUERIDA:
            // Si hay 1 asesor: Prioridad al input manual.
            // Si hay varios: Prioridad a la URL individual del catálogo.
            let urlFinal = '';
            if (cantidadAsesores === 1) {
                urlFinal = urlManual;
            } else {
                urlFinal = (urlsAsesores[idAsesorFila] ? urlsAsesores[idAsesorFila].url : urlManual) || '';
            }

            const mensajeFinal = plantilla
                .replace(/{{cliente}}/g, nombreCliente)
                .replace(/{{asesor}}/g, tr.dataset.asesor)
                .replace(/{{carrera}}/g, tr.dataset.carrera)
                .replace(/{{jornada}}/g, tr.dataset.jornada)
                .replace(/{{url}}/g, urlFinal)
                .replace(/{{foco}}/g, foco);

            tr.dataset.mensaje = mensajeFinal;
            const col = tr.querySelector('.mensaje-col');
            if (col) {
                col.textContent = mensajeFinal;
                col.classList.remove('text-muted');
            }
        });
    }

    function guardarMensajes() {
        if (!tablaLeads) return alert('No hay datos en la tabla');

        const mensajes = [];
        tablaLeads.rows().every(function() {
            const tr = this.node();
            if (tr.dataset.mensaje) {
                mensajes.push({
                    id_lead: tr.children[0].textContent,
                    numero: tr.children[2].textContent,
                    mensaje: tr.dataset.mensaje,
                    cliente: tr.dataset.cliente,
                    asesor: tr.dataset.asesor
                });
            }
        });

        if (!mensajes.length) return alert('Seleccione un tema primero');

        const datos = new FormData();
        datos.append('accion', 'guardar_mensajes_rst');
        datos.append('mensajes', JSON.stringify(mensajes));

        fetch('ajax/ajax.php', {
                method: 'POST',
                body: datos
            })
            .then(res => res.json())

            .then(r => {

                if (r.ok) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Mensajes enviados',
                        text: `Se enviaron ${r.enviados} mensajes correctamente`,
                        confirmButtonText: 'Aceptar'
                    });

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: r.error || 'Ocurrió un error al guardar los mensajes',
                        confirmButtonText: 'Cerrar'
                    });

                }

            })

            .catch(() => {

                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No fue posible conectar con el servidor',
                    confirmButtonText: 'Cerrar'
                });

            });
    }
</script>

<!-- ========================
        End Page Content
    ========================= -->

<?php
$content = ob_get_clean();

require_once '../partials/main.php'; ?>
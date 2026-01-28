<?php ob_start(); ?>

<!-- ========================
        Start Page Content
    ========================= -->

<div class="page-wrapper">

    <!-- Start Content -->
    <div class="content pb-0">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Frm mensajes<span class="badge badge-soft-primary ms-2">125</span></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="index.php">Hogar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Frm mensajes</li>
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
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Crear mensaje</h5>
                </div>
                <div class="card-body">

                    <form id="frm_mensajes">

                        <div class="row g-3">

                            <!-- Tipo -->
                            <div class="col-md-4">
                                <label class="form-label">Tipo</label>
                                <select class="form-select" id="tipo_mensaje" required>
                                    <option value="">Seleccione</option>
                                    <option value="1">SMS</option>
                                    <option value="2">WhatsApp</option>
                                </select>
                            </div>

                            <!-- Tema -->
                            <div class="col-md-4">
                                <label class="form-label">Tema</label>
                                <select class="form-select" id="tema_mensaje" required>
                                    <option value="">Seleccione</option>
                                    <option value="1">Facilidad Pago</option>
                                    <option value="2">Ingreso</option>
                                    <option value="3">Empoderar</option>
                                    <option value="4">Bono</option>
                                </select>
                            </div>

                            <!-- Mensaje -->
                            <div class="col-md-12">
                                <label class="form-label">Mensaje</label>
                                <textarea id="mensaje_template" rows="5" class="form-control" required
                                    placeholder="Aquí se cargará el mensaje parametrizado"></textarea>

                                <small class="text-muted">
                                    Variables disponibles:
                                    <code>{{cliente}}</code>,
                                    <code>{{asesor}}</code>,
                                    <code>{{carrera}}</code>,
                                    <code>{{jornada}}</code>,
                                    <code>{{url}}</code>
                                </small>
                            </div>

                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-success">
                                    Guardar mensaje
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-bordered" id="tabla_mensajes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Tema</th>
                                <th>Mensaje</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>
        <!-- card end -->

    </div>

    <!-- End Content -->

    <?php require_once '../partials/footer.php'; ?>

</div>

<div class="modal fade" id="modalEditarMensaje" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Editar mensaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="frm_editar_mensaje">

                    <input type="hidden" id="edit_id">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Tipo</label>
                            <select id="edit_tipo" class="form-select">
                                <option value="1">SMS</option>
                                <option value="2">WhatsApp</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tema</label>
                            <select id="edit_tema" class="form-select">
                                <option value="1">Facilidad Pago</option>
                                <option value="2">Ingreso</option>
                                <option value="3">Empoderar</option>
                                <option value="4">Bono</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Mensaje</label>
                            <textarea id="edit_mensaje" rows="5" class="form-control"></textarea>
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" onclick="actualizarMensaje()">Actualizar</button>
            </div>

        </div>
    </div>
</div>


<script>
const mensajesPorTema = {
    1: `Hola {{cliente}}, soy {{asesor}} de Multitech, tu cupo en la promoción {{foco}} aún está disponible.
Puedes iniciar solo con $200.000. ¿Te explico en 2 minutos? {{url}}`,

    2: `{{cliente}}, tu carrera {{carrera}}, jornada {{jornada}} inicia muy pronto.
Aquí no solo estudias: puedes trabajar y generar ingresos.
¿Conversamos? {{asesor}} {{url}}`,

    3: `{{cliente}}, estudiando en Multitech APRENDES y GANAS DINERO.
¿Te animas? {{asesor}} {{url}}`,

    4: `{{cliente}}, al matricularte en Multitech recibes un BONO de $500.000
para que un familiar o amigo estudie (Excel Certificado).
¿Te explico? {{asesor}} {{url}}`
};

document.getElementById('tema_mensaje').addEventListener('change', function() {
    const tema = this.value;
    const textarea = document.getElementById('mensaje_template');

    textarea.value = mensajesPorTema[tema] || '';
});

document.getElementById('frm_mensajes').addEventListener('submit', function(e) {
    e.preventDefault();

    const datos = new FormData();
    datos.append('accion', 'guardar_mensaje');
    datos.append('tipo', document.getElementById('tipo_mensaje').value);
    datos.append('tema', document.getElementById('tema_mensaje').value);
    datos.append('mensaje', document.getElementById('mensaje_template').value);

    fetch('ajax/ajax.php', {
            method: 'POST',
            body: datos
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.ok) {
                alert('Mensaje guardado');
                cargarMensajes();
                this.reset();
            }
        });
});

function cargarMensajes() {

    const datos = new FormData();
    datos.append('accion', 'listar_mensajes');

    fetch('ajax/ajax.php', {
            method: 'POST',
            body: datos
        })
        .then(res => res.json())
        .then(data => {

            const tbody = document.querySelector('#tabla_mensajes tbody');
            tbody.innerHTML = '';

            data.forEach(m => {
                tbody.innerHTML += `
                <tr>
                    <td>${m.id}</td>
                    <td>${m.tipo}</td>
                    <td>${m.tema}</td>
                    <td>${m.mensaje}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editarMensaje(${m.id})">
                            Editar
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarMensaje(${m.id})">
                            Eliminar
                        </button>
                    </td>
                </tr>
            `;
            });

        });
}
document.addEventListener('DOMContentLoaded', () => {
    cargarMensajes();
});

function editarMensaje(id) {

    const datos = new FormData();
    datos.append('accion', 'obtener_mensaje');
    datos.append('id', id);

    fetch('ajax/ajax.php', {
            method: 'POST',
            body: datos
        })
        .then(res => res.json())
        .then(data => {

            const m = data[0]; // 👈 AQUÍ ESTÁ LA CLAVE

            document.getElementById('edit_id').value = m.id;
            document.getElementById('edit_tipo').value = m.tipo;
            document.getElementById('edit_tema').value = m.tema;
            document.getElementById('edit_mensaje').value = m.mensaje;

            new bootstrap.Modal(
                document.getElementById('modalEditarMensaje')
            ).show();
        });
}

function actualizarMensaje() {

    const datos = new FormData();
    datos.append('accion', 'actualizar_mensaje');
    datos.append('id', document.getElementById('edit_id').value);
    datos.append('tipo', document.getElementById('edit_tipo').value);
    datos.append('tema', document.getElementById('edit_tema').value);
    datos.append('mensaje', document.getElementById('edit_mensaje').value);

    fetch('ajax/ajax.php', {
            method: 'POST',
            body: datos
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.ok) {
                bootstrap.Modal.getInstance(
                    document.getElementById('modalEditarMensaje')
                ).hide();

                cargarMensajes();
            }
        });
}

function eliminarMensaje(id) {

    if (!confirm('¿Eliminar este mensaje?')) return;

    const datos = new FormData();
    datos.append('accion', 'eliminar_mensaje');
    datos.append('id', id);

    fetch('ajax/ajax.php', {
            method: 'POST',
            body: datos
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.ok) {
                cargarMensajes();
            }
        });
}
</script>


<!-- ========================
        End Page Content
    ========================= -->

<?php
$content = ob_get_clean();

require_once '../partials/main.php'; ?>
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

                    <form id="frm_acortador">

                        <div class="row g-3">

                            <div class="col-md-12">
                                <label class="form-label">Mensaje</label>
                                <textarea id="mensaje" class="form-control" rows="4"></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Carrera</label>
                                <select id="carrera" class="form-select">
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jornada</label>
                                <select id="horario" class="form-select">
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Final URL corta</label>
                                <textarea id="slug_url" class="form-control" rows="1">CONSTRUIR_FUTURO_HOY</textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">URL corta final</label>
                                <input type="text" id="url_final" class="form-control" readonly>
                            </div>

                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-success">
                                    Generar y Guardar
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>

        </div>
        <!-- card end -->

    </div>

    <!-- End Content -->

    <?php require_once '../partials/footer.php'; ?>

</div>


<!-- ========================
        End Page Content
    ========================= -->

<?php
$content = ob_get_clean();

require_once '../partials/main.php'; ?>

<script>
    $(document).ready(function() {

        function generarURL() {

            let mensaje = $("#mensaje").val();
            let carrera = $("#carrera option:selected").text();
            let horario = $("#horario option:selected").text();
            let slug = $("#slug_url").val();

            if (slug == "") {
                slug = "CONSTRUIR_FUTURO_HOY";
            }

            let mensajeFinal = mensaje + " carrera " + carrera + " horario " + horario;

            let mensajeCodificado = encodeURIComponent(mensajeFinal);

            let urlOriginal = "https://wa.me/573112263657?text=" + mensajeCodificado;

            let urlCorta = "https://ebi.re/" + slug;

            $("#url_final").val(urlCorta);

            return {
                mensaje: mensaje,
                carrera: carrera,
                horario: horario,
                slug: slug,
                original_url: urlOriginal,
                short_url: urlCorta
            };

        }

        $("#mensaje,#carrera,#horario,#slug_url").on("keyup change", function() {
            generarURL();
        });

        $("#frm_acortador").submit(function(e) {

            e.preventDefault();

            let data = generarURL();

            const datos = new FormData();

            datos.append("accion", "guardar_acortador");
            datos.append("mensaje", data.mensaje);
            datos.append("carrera", data.carrera);
            datos.append("horario", data.horario);
            datos.append("slug", data.slug);
            datos.append("original_url", data.original_url);
            datos.append("short_url", data.short_url);

            fetch("ajax/ajax.php", {
                    method: "POST",
                    body: datos
                })
                .then(res => res.json())
                .then(resp => {

                    if (resp.ok) {

                        alert("URL creada correctamente");

                        $("#frm_acortador")[0].reset();

                    } else {

                        alert("Error al guardar");

                    }

                });

        });

    });
</script>
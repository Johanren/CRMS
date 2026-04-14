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
                <h4 class="mb-0">Panel de ofertas</h4>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                <div class="daterangepick form-control w-auto d-flex align-items-center">
                    <i class="ti ti-calendar text-dark me-2"></i>
                    <span class="reportrange-picker-field text-dark">23 May 2025 - 30 May 2025</span>
                </div>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- start row -->
        <div class="row">

            <div class="col-md-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                            <h6 class="mb-0">Campañas</h6>
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcampana_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Agregar Campañas</a>

                                <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcampanaMedio_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Agregar Campañas X Medio</a>
                            </div>
                        </div>
                    </div>
                    <style>
                        #info-campa td,
                        #info-campa th {
                            padding: 3px 6px !important;
                            font-size: 12px;
                        }

                        #paginacion button {
                            margin-right: 4px;
                        }
                    </style>
                    <div class="card-body">
                        <div class="table-responsive custom-table">
                            <div class="d-flex justify-content-between align-items-center mb-2">

                                <!-- selector registros -->
                                <div>
                                    <label class="me-2 fw-bold">Mostrar:</label>
                                    <select id="limitSelect" class="form-select form-select-sm d-inline-block w-auto">
                                        <option value="10">10</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>

                                <!-- paginación -->
                                <div id="paginacion"></div>

                            </div>
                            <table class="table dataTable table-nowrap" id="info-campa">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Campaña</th>
                                        <th>fecha registrada</th>
                                        <th>Fecha inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Descripción</th>
                                        <th>Activo</th>
                                        <th>Ilustración</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="datatable-length-campa"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="datatable-paginate-campa"></div>
                            </div>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->

            <!--<div class="col-md-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                            <h6 class="mb-0">Campañas X medio y fuente</h6>
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcampanaMedio_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Agregar Campañas X Medio</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive custom-table">
                            <table class="table dataTable table-nowrap" id="info-campa-fuente">
                                <thead class="table-light">
                                    <tr>
                                        <th>Campaña</th>
                                        <th>Medio</th>
                                        <th>Fuente</th>
                                        <th>Fecha</th>
                                        <th>RSC</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="datatable-length-campa-fuente"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="datatable-paginate-campa-fuente"></div>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div>--> <!-- end col -->

        </div>
        <!-- end row -->

    </div>
    <!-- End Content -->

    <?php require_once '../partials/footer.php'; ?>

</div>
<div class="modal fade" id="modalVisor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vista Previa de Imagen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" style="overflow: hidden; position: relative; height: 500px;">
                <div style="position: absolute; top: 10px; right: 10px; z-index: 100;">
                    <button class="btn btn-primary btn-sm" onclick="ajustarZoom(1.2)"><i class="ti ti-zoom-in"></i></button>
                    <button class="btn btn-primary btn-sm" onclick="ajustarZoom(0.8)"><i class="ti ti-zoom-out"></i></button>
                    <button class="btn btn-secondary btn-sm" onclick="resetearImagen()"><i class="ti ti-refresh"></i></button>
                </div>
                <img id="imgZoom" src="" style="max-width: 100%; transition: transform 0.2s; cursor: grab;">
            </div>
        </div>
    </div>
</div>
<!-- ========================
        End Page Content
    ========================= -->

<?php
$content = ob_get_clean();

require_once '../partials/main.php'; ?>